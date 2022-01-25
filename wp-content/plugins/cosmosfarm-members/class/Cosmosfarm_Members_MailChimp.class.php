<?php
/**
 * Cosmosfarm_Members_MailChimp
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_MailChimp {
	
	var $option;
	
	public function __construct(){
		$this->option = get_cosmosfarm_members_option();
		
		if($this->option->mailchimp_api_key && $this->option->mailchimp_list_id && $this->option->mailchimp_field){
			add_action('user_register', array($this, 'user_register'), 10, 1);
			add_action('profile_update', array($this, 'profile_update'), 10, 2);
			add_action('wp_delete_user', array($this, 'delete_mailchimp'), 10, 1);
			add_action('wpmu_delete_user', array($this, 'delete_mailchimp'), 10, 1);
			add_action('admin_post_cosmosfarm_members_subscribe_mailchimp', array($this, 'admin_post_cosmosfarm_members_update_mailchimp'));
			add_action('admin_post_cosmosfarm_members_delete_mailchimp', array($this, 'admin_post_cosmosfarm_members_update_mailchimp'));
			add_filter('manage_users_columns', array($this, 'mailchimp_columns'), 10, 1);
			add_filter('manage_users_custom_column', array($this, 'mailchimp_custom_columns'), 10, 3);
			
			$action = isset($_GET['action'])?$_GET['action']:'';
			$data = (isset($_POST['data'])&&$_POST['data']) ? $_POST['data'] : array();
			$type = (isset($_POST['type'])&&$_POST['type']) ? $_POST['type'] : array();
			
			if($action == 'cosmosfarm_members_mailchimp_webhook' && $data && $type){
				$this->mailchimp_webhook($type, $data);
			}
		}
	}
	
	public function user_register($user_id){
		$user = get_userdata($user_id);
		$mailchimp_field = $this->option->mailchimp_field;
		
		if($mailchimp_field && $user->$mailchimp_field){
			$this->subscribe_mailchimp($user);
		}
	}
	
	public function profile_update($user_id, $old_data){
		$mailchimp_field = $this->option->mailchimp_field;
		$user = get_userdata($user_id);
		
		if($mailchimp_field && $user->$mailchimp_field){
			$this->subscribe_mailchimp($user);
		}
		else{
			$this->delete_mailchimp($user_id);
		}
	}
	
	public function delete_mailchimp($user_id){
		$user = get_userdata($user_id);
		$mailchimp_field = $this->option->mailchimp_field;
		
		if($mailchimp_field){
			$api_key = $this->option->mailchimp_api_key;
			$list_id = $this->option->mailchimp_list_id;
			
			$args = array(
				'method' => 'POST',
				'headers' => array(
					'X-HTTP-Method-Override' => 'DELETE',
					'Authorization' => 'Basic ' . base64_encode('user:'. $api_key)
				),
				'body' => json_encode(array(
					'email_address' => $user->user_email,
					'status'        => 'delete'
				))
			);
			
			$response = wp_remote_post('https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($user->user_email)), $args);
		}
	}
	
	public function admin_post_cosmosfarm_members_update_mailchimp(){
		$api_key = $this->option->mailchimp_api_key;
		$list_id = $this->option->mailchimp_list_id;
		$mailchimp_field = $this->option->mailchimp_field;
		
		$user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
		$status = isset($_POST['mailchimp_field']) ? sanitize_text_field($_POST['mailchimp_field']) : '';
		if($user_id && $status){
			$user = get_userdata($user_id);
			$mailchimp_list = get_user_meta($user_id, 'mailchimp_list', true);
			$wpmem_fields = wpmem_fields();
			$mailchimp_field_checked_value = (isset($wpmem_fields[$mailchimp_field]['checked_value'])&&$wpmem_fields[$mailchimp_field]['checked_value']) ? $wpmem_fields[$mailchimp_field]['checked_value'] : '';
			$status = $_POST['mailchimp_field'];
			
			switch($status){
				case 'subscribe':
					$this->subscribe_mailchimp($user);
					$mailchimp_list[$list_id] = $user->user_email;
					update_user_meta($user_id, 'mailchimp_field', $mailchimp_field_checked_value);
					break;
				case 'delete':
					$this->delete_mailchimp($user_id);
					unset($mailchimp_list[$list_id]);
					update_user_meta($user_id, 'mailchimp_list', $mailchimp_list);
					update_user_meta($user_id, 'mailchimp_field', '');
					break;
			}
		}
	}
	
	public function subscribe_mailchimp($user){
		$api_key = $this->option->mailchimp_api_key;
		$list_id = $this->option->mailchimp_list_id;
		
		$mailchimp_list = get_user_meta($user->ID, 'mailchimp_list', true);
		if(!$mailchimp_list){
			$mailchimp_list = array();
		}
		$method = (isset($mailchimp_list[$list_id])&&$mailchimp_list[$list_id]) ? 'PATCH' : 'PUT';
		$mailchimp_list[$list_id] = (isset($mailchimp_list[$list_id])&&$mailchimp_list[$list_id]) ? $mailchimp_list[$list_id] : $user->user_email;
		
		$args = array(
			'method' => $method,
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode('user:'. $api_key)
			),
			'body' => json_encode(array(
				'email_address' => $user->user_email,
				'status' => 'subscribed'
			))
		);
		
		$response = wp_remote_post(sprintf('https://%s.api.mailchimp.com/3.0/lists/%s/members/%s', substr($api_key,strpos($api_key,'-')+1), $list_id, md5(strtolower($mailchimp_list[$list_id]))), $args);
		
		if(isset($response['response']['code']) && $response['response']['code'] == '200'){
			$mailchimp_list[$list_id] = $user->user_email;
		}
		else{
			unset($mailchimp_list[$list_id]);
		}
		
		update_user_meta($user->ID, 'mailchimp_list', $mailchimp_list);
	}
	
	public function mailchimp_webhook($type, $data){
		$user = get_user_by('email', $data['email']);
		if($user){
			if($type == 'subscribe'){
				$mailchimp_list = get_user_meta($user->ID, 'mailchimp_list', true);
				$mailchimp_list[$data['list_id']] = $data['email'];
				$wpmem_fields = wpmem_fields();
				$mailchimp_field_checked_value = (isset($wpmem_fields['mailchimp_field']['checked_value'])&&$wpmem_fields['mailchimp_field']['checked_value']) ? $wpmem_fields['mailchimp_field']['checked_value'] : '';
				
				update_user_meta($user->ID, 'mailchimp_list', $mailchimp_list);
				update_user_meta($user->ID, 'mailchimp_field', $mailchimp_field_checked_value);
			}
			else if($type == 'unsubscribe'){
				update_user_meta($user->ID, 'mailchimp_field', '');
			}
		}
	}
	
	public function mailchimp_columns($columns){
		$columns['mailchimp'] = '메일침프';
		return $columns;
	}
	
	public function mailchimp_custom_columns($output, $column_name, $user_id){
		$list_id = $this->option->mailchimp_list_id;
		if($column_name == 'mailchimp'){
			if(get_user_meta($user_id, 'mailchimp_field', true)){
				return '<button type="button" class="button" onclick="cosmosfarm_members_mailchimp_subscribe(\''.$user_id.'\', \''.'delete'.'\')">구독취소</button>';
			}
			return '<button type="button" class="button" onclick="cosmosfarm_members_mailchimp_subscribe(\''.$user_id.'\', \''.'subscribe'.'\')">구독추가</button>';
		}
		return $output;
	}
}