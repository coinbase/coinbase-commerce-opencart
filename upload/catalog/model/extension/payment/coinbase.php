<?php

class ModelExtensionPaymentCoinbase extends Model
{
    public function addOrder($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "coinbase_commerce_order` SET `store_order_id` = '" . (int)$data['store_order_id'] . "', `store_total_amount` = '" . $this->db->escape($data['store_total_amount']) . "', `coinbase_commerce_charge_code` = '" . $this->db->escape($data['coinbase_commerce_charge_code']) . "'");
    }

    public function getOrder($order_id)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coinbase_commerce_order` WHERE `store_order_id` = '" . (int)$order_id . "' LIMIT 1");

        return $query->row;
    }

    public function updateOrder($data)
    {
        foreach($data['fields'] as $key => $value) {
            //echo "{$key} => {$value} ";
            $this->db->query( "UPDATE `" . DB_PREFIX . "coinbase_commerce_order` SET `" . $key . "` = '" . $value . "' WHERE `store_order_id` = " . $this->db->escape($data['store_order_id']));
        }
    }

    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/coinbase');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('coinbase_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('coinbase_total') > 0 && $this->config->get('coinbase_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('coinbase_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code' => 'coinbase',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('coinbase_sort_order')
            );
        }

        return $method_data;
    }

    public function getOrderItems($products, $order)
    {
        $this->load->model('catalog/product');
        $items = array();

        if ($products && count($products) > 0) {
            foreach ($products as $product) {
                $product_data = $this->model_catalog_product->getProduct($product['product_id']);
                $product_name = $product['name'];
                if ($product['model'] && !empty($product['model'])) {
                    $product_name .= ' - ' . $product['model'];
                }

                $options_count = 0;
                $options_line = '';
                foreach ($product['option'] as $option) {
                    $options_count++;
                    $separator = $options_count == count($product['option']) ? '' : ' - ';
                    if ($option['type'] != 'file') {
                        $options_line .= $option['value'] . $separator;
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                        if ($upload_info) {
                            $options_line .= $upload_info['name'] . $separator;
                        }
                    }
                }

                if (!empty($options_line)) {
                    $product_name .= ' - ' . $options_line;
                }

                $items[] = array(
                    'name' => htmlspecialchars($product_name),
                    'amount' => $this->currency->format($product['price'], $order['currency_code'], false, false),
                    'qty' => $product['quantity'],
                    'item_number' => $product_data['sku'] && !empty($product_data['sku']) ? $product_data['sku'] : ''
                );
            }
        }

        return $items;
    }

}
