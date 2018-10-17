<?php

class ControllerExtensionPaymentCoinbase extends Controller
{

    public function index()
    {
        $this->load->language('extension/payment/coinbase');
        $this->load->model('checkout/order');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['action'] = $this->url->link('extension/payment/coinbase/checkout', '', true);

        return $this->load->view('extension/payment/coinbase', $data);
    }

    public function checkout()
    {

        $this->load->model('checkout/order');
        $this->load->model('extension/payment/coinbase');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        //$secret_key = md5(uniqid(rand(), true));

        //Pricing
        $pricing["amount"] = $order_info['total'];
        $pricing["currency"] = $order_info['currency_code'];

        //Metadata attached with Charge
        $metaData["id"] = $order_info['customer_id'];
        $metaData["customer_name"] = $order_info['firstname'] . " " . $order_info['lastname'];
        $metaData["customer_email"] = $order_info['email'];
        $metaData["store_increment_id"] = $order_info['order_id'];

        //Json Data Curl Request
        $data = json_encode([
            "name" => $order_info['store_name'] . ' order #' . $order_info['order_id'],
            "description" => "Purchased through Coinbase Commerce",
            "local_price" => $pricing,
            "pricing_type" => "fixed_price",
            "metadata" => $metaData,
            "redirect_url" => $this->url->link('extension/payment/coinbase/redirect&orderId=' . $order_info["order_id"], true),
            "cancel_url" => $this->url->link('checkout/checkout')
        ]);

        //Receive Curl Response
        $result = $this->getCurlResponse($data);

        if($result) {

            //Fetch Expected Price
        $this->model_extension_payment_coinbase->addOrder(array(
            'store_order_id' => $order_info['order_id'],
            'store_total_amount' => $order_info['total'],
            'coinbase_commerce_charge_code' => $result['data']['code'],
            //'coinbase_commerce_transaction_id' => $result['payments']['transaction_id'],
            //'coinbase_commerce_status' => $result['timeline']['status']
            //'coinbase_commerce_coins_expected' => $result['data']['pricing']['amount'], //Need to add logic after pricing
            //'coinbase_commerce_coins_received' => $result['payments']['value']['local']['amount'],
            //'coinbase_commerce_received_currency' => $result['payments']['value']['local']['currency']
        ));
        $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('payment_coinbase_order_status_id'));
            //var_dump($result);
            //exit();
        $this->response->redirect($result['data']['hosted_url']);
        } else {
            $this->log->write("Order #" . $order_info['order_id'] . " is not valid. Please check Coinbase Commerce API request logs.");
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }
    }

    public function redirect()
    {
        if(isset($_GET['orderId']) && $_GET['orderId'] == $this->session->data['order_id']) {
            $this->load->model('extension/payment/coinbase');

            $order_info = $this->model_extension_payment_coinbase->getOrder($this->session->data['order_id']);
            if($order_info['coinbase_commerce_status'] == 'COMPLETED' || 'RESOLVED') {
                $this->session->data['success'] = 'Coinbase Commerce: Order is being Processing. Charge code: ' . $order_info['coinbase_commerce_charge_code'];
                $this->response->redirect($this->url->link('checkout/success', '', true));
            }
            else {

                $this->session->data['error'] = 'Coinbase Commerce: Order is not completed. Charge code: ' . $order_info['coinbase_commerce_charge_code'];
                $this->response->redirect($this->url->link('checkout/checkout'));
            }
        }
        else
            $this->response->redirect($this->url->link('common/home', '', true));
    }

    public function callback()
    {
        //Read Input
        $input = file_get_contents('php://input');
        $this->log->write("Raw Post " . $input);
        $this->log->write("Signature " . $this->request->server['HTTP_X_CC_WEBHOOK_SIGNATURE']);
        if (!$this->authenticate($input)) {
            $this->log->write("Authentication Failed");
            return null;
        }
        $this->log->write("Authentication Successfull");

        //Retrieve Order Details
        $jsonInput = json_decode($input);

        $data['orderId'] = $jsonInput->event->data->metadata->store_increment_id;
        $data['chargeCode'] = $jsonInput->event->data->code;
        $data['type'] = $jsonInput->event->type;
        $data['timeline'] = end($jsonInput->event->data->timeline);
        $data['coinbaseStatus'] = end($jsonInput->event->data->timeline)->status;
        $data['coinbaseContext'] = isset(end($jsonInput->event->data->timeline)->context) ? end($jsonInput->event->data->timeline)->context : "" ;
        $data['coinbasePayment'] = reset($jsonInput->event->data->payments);
        $data['eventDataNode'] = $jsonInput->event->data;

        //Update Order Status and DB Record
        $this->updateRecord($data);

        $this->response->addHeader('HTTP/1.1 200 OK');
    }

    public function getCurlResponse($data)
    {
        $curl = curl_init('https://api.commerce.coinbase.com/charges/');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'X-CC-Api-Key: ' . $this->config->get('payment_coinbase_api_key'),
                'X-CC-Version: 2018-03-22')
        );
        $response = json_decode(curl_exec($curl), TRUE);

        return $response;
    }

    public function authenticate($payload, $signature = NULL)
    {
        //print_r($signature);
        $key = $this->config->get('payment_coinbase_api_secret');
        $headerSignature = isset($signature) ? $signature : $this->request->server['HTTP_X_CC_WEBHOOK_SIGNATURE'];
        $computedSignature = hash_hmac('sha256', $payload, $key);
        return $headerSignature === $computedSignature;
    }

    public function updateRecord($data){
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/coinbase');

        $order_info = $this->model_checkout_order->getOrder($data['orderId']);

        try {
            if ($order_info) {

                $order_status = '';
                $status_message = 'Coinbase Commerce Status ' . $data['coinbaseStatus'] . ' Type ' . $data['type'];

                if ($data['coinbaseStatus'] == 'NEW' && $data['type'] == 'charge:created') {
                    $order_status = 'coinbase_created_status_id';  //Created
                } elseif ($data['coinbaseStatus'] == 'PENDING' && $data['type'] == 'charge:pending') {
                    $order_status = 'payment_coinbase_pending_status_id';  //Pending
                    $recordToUpdate['fields']['coinbase_commerce_status'] = $data['coinbaseStatus'];
                } elseif ($data['coinbaseStatus'] == 'COMPLETED' && $data['type'] == 'charge:confirmed') {
                    $order_status = 'payment_coinbase_completed_status_id';  //Processing
                    $recordToUpdate['fields']['coinbase_commerce_status'] = $data['coinbaseStatus'];
                } elseif ($data['coinbaseStatus'] == 'RESOLVED') {
                    $order_status = 'payment_coinbase_resolved_status_id'; //Complete
                } elseif ($data['coinbaseStatus'] == 'UNRESOLVED') {
                    $order_status = 'payment_coinbase_unresolved_status_id'; //Denied
                    $status_message .= ' Context ' . $data['coinbaseContext'];
                } elseif ($data['type'] == 'charge:failed' && $data['coinbaseStatus'] == 'EXPIRED') {
                    $order_status = 'payment_coinbase_expired_status_id'; //Expired
                    $status_message .= ' Context ' . $data['coinbaseContext'];
                }

                $this->log->write('Coinbase Commerce: Order Status ' . $order_status);
                $this->log->write('Coinbase Commerce: ' . $status_message);

                if ($order_status) {

                    //Update DB Record
                    $recordToUpdate['store_order_id'] = $data['orderId'];
                    $recordToUpdate['fields']['coinbase_commerce_status'] = $data['coinbaseStatus'];

                    //Update Coinbase info when Payment Done
                    if($data['type'] != 'charge:created' && $data['coinbaseStatus'] != 'EXPIRED') {
                        $coinsExpected = $data['coinbasePayment']->network;
                        $recordToUpdate['fields']['coinbase_commerce_transaction_id'] = $t = $data['coinbasePayment']->transaction_id;
                        $recordToUpdate['fields']['coinbase_commerce_coins_expected'] = $e = $data['eventDataNode']->pricing->$coinsExpected->amount;
                        $recordToUpdate['fields']['coinbase_commerce_coins_received'] = $p = $data['coinbasePayment']->value->crypto->amount;
                        $recordToUpdate['fields']['coinbase_commerce_received_currency'] = $c = $coinsExpected . "(" . $data['coinbasePayment']->value->crypto->currency . ")";
                        $this->log->write('Updated Coinbase Payment info in DB');
                        $status_message .= '<br/><b>Transaction Details </b><br/>';
                        $status_message .= 'Transaction Id: <b>' . $t . '</b><br/>';
                        $status_message .= 'Expected Amount: <b>' . $e . '</b><br/>';
                        $status_message .= 'Amount Paid: <b>' . $p . ' ' . $c . '</b><br/>';
                    }
                    $this->model_extension_payment_coinbase->updateOrder($recordToUpdate);

                    //Update History Status
                    $this->model_checkout_order->addOrderHistory(
                        $data['orderId'],
                        $this->config->get($order_status),
                        $status_message
                    );

                    //Send Email
                    $this->sendEmail($order_info['email'], $status_message);

                    $this->log->write('Coinbase Commerce: payment status updated');
                } else {
                    $this->log->write('Coinbase Commerce: Unknown payment status');
                }
            }
        }catch(Exception $e) {
            echo 'Exception: ' .$e->getMessage();
        }
    }

    public function sendEmail($email_to, $message){

        $subject = "Coinbase Commerce Order Status Email";
        print_r($this->config->get('config_mail_smtp_password'));
        $mail = new Mail($this->config->get('config_mail_engine'));
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($email_to);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();
    }
}
