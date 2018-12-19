<?php

class ControllerExtensionPaymentCoinbase extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/coinbase');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        
        $token = 'token=' . $this->session->data['token'];
        $extenstionLink = 'extension/extension';

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('coinbase', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link($extenstionLink, $token . '&type=payment', true));
        }

        //Callback Url
        $callbackUrl = $this->url->link('extension/payment/coinbase/callback', '', true);
        $data['action'] = $this->url->link('extension/payment/coinbase', $token, true);
        $data['cancel'] = $this->url->link($extenstionLink, $token . '&type=payment', true);
        $data['callback_url'] = str_replace('admin/', '', $callbackUrl);
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['text_sign_in'] = $this->language->get('text_sign_in');
        $data['text_sign_up'] = $this->language->get('text_sign_up');
        $data['text_support'] = $this->language->get('text_support');
        $data['text_coinbase_preview_heading'] = $this->language->get('text_coinbase_preview_heading');
        $data['text_coinbase_preview_intro'] = $this->language->get('text_coinbase_preview_intro');
        $data['text_api_credentials_section'] = $this->language->get('text_api_credentials_section');
        $data['entry_api_key'] = $this->language->get('entry_api_key');
        $data['entry_api_secret'] = $this->language->get('entry_api_secret');

        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_completed_status'] = $this->language->get('entry_completed_status');
        $data['entry_pending_status'] = $this->language->get('entry_pending_status');
        $data['entry_confirmed_status'] = $this->language->get('entry_confirmed_status');
        $data['entry_unresolved_status'] = $this->language->get('entry_unresolved_status');
        $data['entry_expired_status'] = $this->language->get('entry_expired_status');

        $data['help_project_id'] = $this->language->get('help_project_id');
        $data['help_api_key'] = $this->language->get('help_api_key');
        $data['help_api_secret'] = $this->language->get('help_api_secret');
        $data['help_total'] = $this->language->get('help_total');
        $data['help_api_test_mode'] = $this->language->get('help_api_test_mode');
        $data['help_api_callback'] = $this->language->get('help_api_callback');
        $data['error_permission'] = $this->language->get('error_permission');
        $data['error_project_id'] = $this->language->get('error_project_id');
        $data['error_api_key'] = $this->language->get('error_api_key');
        $data['error_api_secret'] = $this->language->get('error_api_secret');
        $data['error_api_status_inactive'] = $this->language->get('error_api_status_inactive');
        $data['text_order_settings_section'] = $this->language->get('text_order_settings_section');
        $data['entry_api_callback'] = $this->language->get('entry_api_callback');
        $data['text_api_test_mode_yes'] = $this->language->get('text_api_test_mode_yes');
        $data['text_api_test_mode_no'] = $this->language->get('text_api_test_mode_no');
        $data['entry_api_test_mode'] = $this->language->get('entry_api_test_mode');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['api_key'])) {
            $data['error_api_key'] = $this->error['api_key'];
        } else {
            $data['error_api_key'] = '';
        }

        if (isset($this->error['api_secret'])) {
            $data['error_api_secret'] = $this->error['api_secret'];
        } else {
            $data['error_api_secret'] = '';
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $token, true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link($extenstionLink, $token . '&type=payment', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/coinbase', $token, true)
        );

        $fields = array(
            'coinbase_status',
            'coinbase_api_key',
            'coinbase_api_secret',
            'coinbase_api_test_mode',
            'coinbase_order_status_id',
            'coinbase_completed_status_id',
            'coinbase_pending_status_id',
            'coinbase_resolved_status_id',
            'coinbase_unresolved_status_id',
            'coinbase_expired_status_id',
            'coinbase_geo_zone_id',
            'coinbase_total',
            'coinbase_sort_order'
        );

        foreach ($fields as $field) {
            if (isset($this->request->post[$field])) {
                $data[$field] = $this->request->post[$field];
            } else {
                $data[$field] = $this->config->get($field);
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/coinbase', $data));
    }

    protected function validate()
    {
        $this->load->model('setting/setting');

        if (!$this->user->hasPermission('modify', 'extension/payment/coinbase')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['coinbase_api_key'] ||
            empty($this->request->post['coinbase_api_key'])) {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }

        if (!$this->request->post['coinbase_api_secret'] ||
            empty($this->request->post['coinbase_api_secret'])) {
            $this->error['api_secret'] = $this->language->get('error_api_secret');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('extension/payment/coinbase');

        $this->model_extension_payment_coinbase->install();
    }

    public function uninstall()
    {
        $this->load->model('extension/payment/coinbase');

        $this->model_extension_payment_coinbase->uninstall();
    }
}
