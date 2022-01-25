<?php
/**
 * Cosmosfarm_Members_Privacy_Blind_On
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Privacy_Blind_On {
	
	public function __construct(){
		add_action('current_screen', array($this, 'current_screen'));
		
		$this->privacy_blind_on_field();
	}
	
	public function current_screen(){
		$privacy_blind_on = get_option('cosmosfarm_members_privacy_blind_on', '');
		
		if($privacy_blind_on){
			$current_screen = get_current_screen();
			
			if($current_screen->id == 'users'){
				add_filter('user_login', array($this, 'user_login'), 99, 3);
				add_filter('user_email', array($this, 'user_email'), 99, 3);
				add_filter('manage_users_custom_column',  array($this, 'users_custom_column'), 99, 3);
			}
			
			if($current_screen->id == 'toplevel_page_cosmosfarm_subscription_order' && !isset($_GET['order_id'])){
				add_filter('cosmosfarm_members_subscription_order_buyer_name', array($this, 'order_buyer_name'), 99, 2);
				add_filter('cosmosfarm_members_subscription_order_buyer_email', array($this, 'order_buyer_email'), 99, 2);
				add_filter('cosmosfarm_members_subscription_order_buyer_tel', array($this, 'order_buyer_tel'), 99, 2);
			}
			
			add_filter('manage_cosmosfarm_members_activity_history_columns', array($this, 'activity_history_columns'), 99, 1);
		}
	}
	
	public function privacy_blind_on_field(){
		register_setting('general', 'cosmosfarm_members_privacy_blind_on', array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => ''
		));
		
		add_settings_field(
			'cosmosfarm_members_privacy_blind_on',
			'사이트 내 개인정보 표시',
			array($this, 'privacy_blind_on_field_html'),
			'general',
			'default',
			array('label_for'=>'cosmosfarm_members_privacy_blind_on')
		);
	}
	
	public function privacy_blind_on_field_html($args){
		$privacy_blind_on = get_option('cosmosfarm_members_privacy_blind_on', '');
		?>
		<label><input type="radio" id="cosmosfarm_members_privacy_blind_on" name="cosmosfarm_members_privacy_blind_on" value=""<?php if(!$privacy_blind_on):?> checked<?php endif?>> 표시</label>
		<label><input type="radio" name="cosmosfarm_members_privacy_blind_on" value="1"<?php if($privacy_blind_on):?> checked<?php endif?>> 미표시</label>
		<?php
	}
	
	public function user_login($value, $user_id, $context){
		$value = cosmosfarm_members_text_masking($value);
		return $value;
	}
	
	public function user_email($value, $user_id, $context){
		$value = cosmosfarm_members_text_masking($value);
		return $value;
	}
	
	public function users_custom_column($value, $column_name, $user_id){
		if($column_name == 'display_name'){
			$value = cosmosfarm_members_text_masking($value);
		}
		return $value;
	}
	
	public function order_buyer_name($value, $order){
		$value = cosmosfarm_members_text_masking($value);
		return $value;
	}
	
	public function order_buyer_email($value, $order){
		$value = cosmosfarm_members_text_masking($value);
		return $value;
	}
	
	public function order_buyer_tel($value, $order){
		$value = cosmosfarm_members_phone_masking($value);
		return $value;
	}
	
	public function activity_history_columns($columns){
		add_filter('user_login', array($this, 'user_login'), 99, 3);
		add_filter('user_email', array($this, 'user_email'), 99, 3);
		return $columns;
	}
}