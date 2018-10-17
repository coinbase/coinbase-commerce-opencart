<?php

class ModelExtensionPaymentCoinbase extends Model
{
    public function install()
    {
        $this->db->query("
	    	CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "coinbase_commerce_order` (
	        	`id` INT(11) NOT NULL AUTO_INCREMENT,
	        	`store_order_id` INT(11) NOT NULL,
	        	`store_total_amount` FLOAT NOT NULL,	        	
	        	`coinbase_commerce_charge_code` VARCHAR(50) NOT NULL,
	        	`coinbase_commerce_transaction_id` VARCHAR(100),
	        	`coinbase_commerce_status` TEXT,
	        	`coinbase_commerce_coins_expected` FLOAT,	        	
	        	`coinbase_commerce_coins_received` FLOAT,
	        	`coinbase_commerce_received_currency` TEXT NOT NULL,
	        	PRIMARY KEY (`id`)
	     	) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
    	");

        $this->load->model('setting/setting');

        $settings = array();
        $settings['payment_coinbase_api_test_mode'] = 0;
        $settings['payment_coinbase_order_status_id'] = 1;
        $settings['payment_coinbase_completed_status_id'] = 2;
        $settings['payment_coinbase_pending_status_id'] = 1;
        $settings['payment_coinbase_resolved_status_id'] = 5;
        $settings['payment_coinbase_unresolved_status_id'] = 8;
        $settings['payment_coinbase_expired_status_id'] = 14;
        $settings['payment_coinbase_total'] = 30;
        $settings['payment_coinbase_sort_order'] = 0;

        $this->model_setting_setting->editSetting('payment_coinbase', $settings);
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "coinbase_commerce_order`;");
    }
}
