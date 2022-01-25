<?php
/**
 * Cosmosfarm_Members_Abstract_PG
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
abstract class Cosmosfarm_Members_Abstract_PG {
	
	var $pg_type = 'billing';
	var $order;
	
	public function __construct(){
		$this->order = new Cosmosfarm_Members_Subscription_Order();
	}
	
	abstract public function init($args=array());
	
	abstract public function get_name();
	
	abstract public function subscribe($customer_uid, $price, $args=array());
	
	abstract public function getPayment();
	
	abstract public function cancel($builtin_pg_tid, $args=array());
	
	abstract public function cancel_partial($builtin_pg_tid, $args=array());
	
	abstract public function get_init_values();
	
	abstract public function open_dialog();
	
	abstract public function open_close();
	
	abstract public function callback();
	
	abstract public function is_test();
	
	abstract public function save_settings();
	
	public function get_callback_url(){
		return site_url('?action=cosmosfarm_members_subscription_request_pay_pg_callback&pg=' . $this->get_name() . '&pg_type=' . $this->pg_type);
	}
	
	public function get_close_url(){
		return site_url('?action=cosmosfarm_members_subscription_request_pay_open_close&pg=' . $this->get_name() . '&pg_type=' . $this->pg_type);
	}
	
	public function set_pg_type_billing(){
		$this->pg_type = 'billing';
	}
	
	public function set_pg_type_general(){
		$this->pg_type = 'general';
	}
	
	public function set_order($order){
		$this->order = $order;
	}
	
	public function get_order(){
		return $this->order;
	}
	
	public function get_order_id(){
		$uniqid = hexdec(uniqid());
		return sprintf('%s-%s-%s-%s', substr($uniqid, 0, 4), substr($uniqid, 4, 4), substr($uniqid, 8, 4), substr($uniqid, 12, 4));
	}
	
	public function getMerchantUID(){
		return $this->get_order_id();
	}
	
	public function getCustomerUID($user_id=''){
		$customer_uid = '';
		
		if(!$user_id){
			$user_id = get_current_user_id();
		}
		
		$user = new WP_User($user_id);
		
		if($user->ID){
			$customer_uid = get_user_meta($user->ID, 'cosmosfarm_iamport_customer_uid', true);
			if(!$customer_uid){
				$customer_uid = uniqid();
				update_user_meta($user->ID, 'cosmosfarm_iamport_customer_uid', $customer_uid);
			}
		}
		
		return $customer_uid;
	}
	
	public function updateCustomerUID($user_id='', $new_uid=''){
		if(!$user_id){
			$user_id = get_current_user_id();
		}
		
		if(!$new_uid){
			$new_uid = uniqid();
		}
		
		$user = new WP_User($user_id);
		
		if($user->ID){
			update_user_meta($user->ID, 'cosmosfarm_iamport_customer_uid', $new_uid);
		}
	}
	
	public function is_mobile(){
		return wp_is_mobile();
	}
}