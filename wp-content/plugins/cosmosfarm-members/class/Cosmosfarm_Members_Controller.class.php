<?php
/**
 * Cosmosfarm_Members_Controller
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_Controller {
	
	public function __construct(){
		add_action('admin_post_cosmosfarm_members_setting_save', array($this, 'setting_save'));
		add_action('admin_post_cosmosfarm_members_service_save', array($this, 'policy_service_save'));
		add_action('admin_post_cosmosfarm_members_privacy_save', array($this, 'policy_privacy_save'));
		add_action('admin_post_cosmosfarm_members_certification_save', array($this, 'certification_save'));
		add_action('admin_post_cosmosfarm_members_verify_email_save', array($this, 'verify_email_save'));
		add_action('admin_post_cosmosfarm_members_change_role_save', array($this, 'change_role_save'));
		add_action('admin_post_cosmosfarm_members_security_save', array($this, 'security_save'));
		add_action('admin_post_cosmosfarm_members_security_dormant_member_save', array($this, 'security_dormant_member_save'));
		add_action('admin_post_cosmosfarm_members_activity_history_download', array($this, 'activity_history_download'));
		add_action('admin_post_cosmosfarm_members_exists_check_save', array($this, 'exists_check_save'));
		add_action('admin_post_cosmosfarm_members_sms_setting_save', array($this, 'sms_setting_save'));
		add_action('admin_post_cosmosfarm_members_sms_send', array($this, 'sms_send'));
		add_action('admin_post_cosmosfarm_members_alimtalk_template_save', array($this, 'alimtalk_template_save'));
		add_action('admin_post_cosmosfarm_members_communication_save', array($this, 'communication_save'));
		add_action('admin_post_cosmosfarm_members_bulk_sms_save', array($this, 'bulk_sms_save'));
		add_action('wp_ajax_cosmosfarm_members_bulk_sms_send', array($this, 'bulk_sms_send'));
		add_action('admin_post_cosmosfarm_members_notification_save', array($this, 'notification_save'));
		add_action('admin_post_cosmosfarm_members_message_save', array($this, 'message_save'));
		add_action('admin_post_cosmosfarm_members_subscription_save', array($this, 'subscription_save'));
		add_action('admin_post_cosmosfarm_members_product_save', array($this, 'product_save'));
		add_action('admin_post_cosmosfarm_members_coupon_save', array($this, 'coupon_save'));
		add_action('admin_post_cosmosfarm_members_subnoti_save', array($this, 'subnoti_save'));
		add_action('admin_post_cosmosfarm_members_search_users', array($this, 'search_users'));
		add_action('admin_post_cosmosfarm_members_search_product', array($this, 'search_product'));
		add_action('admin_post_cosmosfarm_members_order_new', array($this, 'order_new'));
		add_action('admin_post_cosmosfarm_members_order_save', array($this, 'order_save'));
		add_action('admin_post_cosmosfarm_members_order_cancel', array($this, 'order_cancel'));
		add_action('admin_post_cosmosfarm_members_order_cancel_partial', array($this, 'order_cancel_partial'));
		add_action('admin_post_cosmosfarm_members_order_download', array($this, 'order_download'));
		add_action('admin_post_cosmosfarm_members_mailchimp_save', array($this, 'mailchimp_save'));
		
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		switch($action){
			case 'cosmosfarm_members_social_login': $this->social_login(); break;
			case 'cosmosfarm_members_social_login_callback_naver': $this->social_login_callback('naver'); break;
			case 'cosmosfarm_members_social_login_callback_facebook': $this->social_login_callback('facebook'); break;
			case 'cosmosfarm_members_social_login_callback_kakao': $this->social_login_callback('kakao'); break;
			case 'cosmosfarm_members_social_login_callback_google': $this->social_login_callback('google'); break;
			case 'cosmosfarm_members_social_login_callback_twitter': $this->social_login_callback('twitter'); break;
			case 'cosmosfarm_members_social_login_callback_instagram': $this->social_login_callback('instagram'); break;
			case 'cosmosfarm_members_verify_email_confirm': $this->verify_email_confirm(); break;
			case 'cosmosfarm_members_delete_account': $this->delete_account(); break;
			case 'cosmosfarm_members_login_timeout': $this->login_timeout(); break;
			case 'cosmosfarm_members_certification_confirm': $this->certification_confirm(); break;
			case 'cosmosfarm_members_subscription_apply_coupon': $this->subscription_apply_coupon(); break;
			case 'cosmosfarm_members_subscription_register_card': $this->subscription_register_card(); break;
			case 'cosmosfarm_members_pre_subscription_request_pay': $this->pre_subscription_request_pay(); break;
			case 'cosmosfarm_members_subscription_request_pay_open_dialog': $this->subscription_request_pay_open_dialog(); break;
			case 'cosmosfarm_members_subscription_request_pay_open_close': $this->subscription_request_pay_open_close(); break;
			case 'cosmosfarm_members_subscription_request_pay_pg_callback': $this->subscription_request_pay_pg_callback(); break;
			case 'cosmosfarm_members_subscription_request_pay': $this->subscription_request_pay(); break;
			case 'cosmosfarm_members_subscription_request_pay_complete': $this->subscription_request_pay_complete(); break;
			case 'cosmosfarm_members_subscription_request_pay_mobile': $this->subscription_request_pay_mobile(); break;
			case 'cosmosfarm_members_subscription_update': $this->subscription_update(); break;
			case 'cosmosfarm_members_exists_check': $this->exists_check(); break;
			case 'cosmosfarm_members_notifications_read': $this->notifications_read(); break;
			case 'cosmosfarm_members_notifications_unread': $this->notifications_unread(); break;
			case 'cosmosfarm_members_notifications_delete': $this->notifications_delete(); break;
			case 'cosmosfarm_members_notifications_subnotify_update': $this->notifications_subnotify_update(); break;
			case 'cosmosfarm_members_messages_read': $this->messages_read(); break;
			case 'cosmosfarm_members_messages_unread': $this->messages_unread(); break;
			case 'cosmosfarm_members_messages_delete': $this->messages_delete(); break;
			case 'cosmosfarm_members_messages_subnotify_update': $this->messages_subnotify_update(); break;
			case 'cosmosfarm_members_messages_send': $this->messages_send(); break;
		}
		
		$code = isset($_GET['code'])?$_GET['code']:'';
		$state = isset($_GET['state'])?$_GET['state']:'';
		if(!$action && $code && $state){
			$this->social_login_callback('line');
		}
		
		$comments_reviews = new Cosmosfarm_Members_Comments_Reviews();
		$comments_reviews->init_action();
	}
	
	public function setting_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-setting-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-setting-save-nonce'], 'cosmosfarm-members-setting-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option_name = 'cosmosfarm_menu_add_login';
			$new_value = trim($_POST[$option_name]);
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'yes');
				else add_option($option_name, $new_value, '', 'yes');
			}
			
			$option_name = 'cosmosfarm_login_menus';
			$new_value = isset($_POST[$option_name])?$_POST[$option_name]:'';
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'yes');
				else add_option($option_name, $new_value, '', 'yes');
			}
			
			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_social_login_active', array());
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function policy_service_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-service-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-service-save-nonce'], 'cosmosfarm-members-service-save')){
			$option_name = 'cosmosfarm_members_policy_service';
			$new_value = trim($_POST[$option_name]);
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'no');
				else add_option($option_name, $new_value, '', 'no');
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function policy_privacy_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-privacy-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-privacy-save-nonce'], 'cosmosfarm-members-privacy-save')){
			$option_name = 'cosmosfarm_members_policy_privacy';
			$new_value = trim($_POST[$option_name]);
			if(!$new_value){
				delete_option($option_name);
			}
			else{
				if(get_option($option_name) !== false) update_option($option_name, $new_value, 'no');
				else add_option($option_name, $new_value, '', 'no');
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function certification_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-certification-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-certification-save-nonce'], 'cosmosfarm-members-certification-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function verify_email_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-verify-email-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-verify-email-save-nonce'], 'cosmosfarm-members-verify-email-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function change_role_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-change-role-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-change-role-save-nonce'], 'cosmosfarm-members-change-role-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function security_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-security-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-security-save-nonce'], 'cosmosfarm-members-security-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			$use_dormant_member = isset($_POST['cosmosfarm_members_use_dormant_member']) ? intval($_POST['cosmosfarm_members_use_dormant_member']) : '';
			
			if($use_dormant_member){
				if(!wp_next_scheduled('cosmosfarm_members_dormant_member')){
					wp_schedule_event(time(), 'hourly', 'cosmosfarm_members_dormant_member');
				}
			}
			else{
				wp_clear_scheduled_hook('cosmosfarm_members_dormant_member');
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function security_dormant_member_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-security-dormant-member-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-security-dormant-member-save-nonce'], 'cosmosfarm-members-security-dormant-member-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			$dormant_member_email_test = isset($_POST['cosmosfarm_members_dormant_member_email_test']) ? sanitize_text_field($_POST['cosmosfarm_members_dormant_member_email_test']) : '';
			if($dormant_member_email_test){
				$option->init();
				
				cosmosfarm_members_send_email(array(
					'to' => $dormant_member_email_test,
					'subject' => Cosmosfarm_Members_Security::dormant_member_email_title(),
					'message' => Cosmosfarm_Members_Security::dormant_member_email_message(),
				));
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function activity_history_download(){
		if(current_user_can('manage_options') && isset($_GET['cosmosfarm-members-activity-history-download-nonce']) && wp_verify_nonce($_GET['cosmosfarm-members-activity-history-download-nonce'], 'cosmosfarm-members-activity-history-download')){
			set_time_limit(3600);
			ini_set('memory_limit', '-1');
			
			header('Content-Type: text/html; charset=UTF-8');
			
			do_action('cosmosfarm_members_pre_activity_history_download');
			
			global $wpdb;
			$results = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}cosmosfarm_members_activity_history` WHERE 1=1 ORDER BY activity_history_id DESC");
			
			$date = date('YmdHis', current_time('timestamp'));
			$filename = "개인정보 활동 기록 {$date}.csv";
			
			$columns = array('사용자(아이디)', '사용자(이메일)', '조회된 회원(아이디)', '조회된 회원(이메일)', '내용', '아이피 주소', '활동 시간');
			
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Pragma: no-cache');
			header('Expires: 0');
			
			@ob_clean();
			@flush();
			
			$csv = fopen('php://output', 'w');
			
			fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($csv, $columns);
			
			foreach($results as $item){
				$row_data = array();
				
				$user = get_userdata($item->user_id);
				if($user){
					$row_data[] = $user->user_login;
					$row_data[] = $user->user_email;
				}
				
				$user = get_userdata($item->related_user_id);
				if($user){
					$row_data[] = $user->user_login;
					$row_data[] = $user->user_email;
				}
				
				$row_data[] = $item->comment;
				$row_data[] = $item->ip_address;
				$row_data[] = $item->activity_datetime;
				
				fputcsv($csv, $row_data);
			}
			@ob_flush();
			@flush();
			
			fclose($csv);
			exit;
		}
		echo '<script>window.history.go(-1);</script>';
		exit;
	}
	
	public function exists_check_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-exists-check-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-exists-check-save-nonce'], 'cosmosfarm-members-exists-check-save')){
			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_exists_check', array());
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * SMS/알림톡 설정을 저장한다.
	 */
	public function sms_setting_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-sms-setting-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-sms-setting-save-nonce'], 'cosmosfarm-members-sms-setting-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function sms_send(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-sms-send-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-sms-send-nonce'], 'cosmosfarm-members-sms-send')){
			
			header('Content-Type: text/html; charset=UTF-8');
			
			$_POST = stripslashes_deep($_POST);
			
			$service = isset($_POST['service']) ? sanitize_textarea_field($_POST['service']) : '';
			$user_id = isset($_POST['user_id']) ? sanitize_textarea_field($_POST['user_id']) : '';
			$phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
			$content = isset($_POST['content']) ? sanitize_textarea_field($_POST['content']) : '';
			$every = isset($_POST['every']) ? sanitize_textarea_field($_POST['every']) : '';
			$phone_field = isset($_POST['phone_field']) ? sanitize_textarea_field($_POST['phone_field']) : '';
			$template_code = isset($_POST['template_code']) ? sanitize_textarea_field($_POST['template_code']) : '';
			
			$option = get_cosmosfarm_members_option();
			$sms = get_cosmosfarm_members_sms();
			if($every){
				if($service == 'alimtalk'){
					$result = $sms->alimtalk_send_every($template_code, $phone_field);
				}
				else{
					$result = $sms->send_every($phone_field, $content);
				}
			}
			else{
				if($service == 'alimtalk'){
					$result = $sms->alimtalk_send($phone, $template_code, $user_id, '');
				}
				else{
					$result = $sms->send($phone, $content);
				}
			}
			
			if($result['result'] == 'success'){
				$redirect_url = wp_get_referer();
				
				echo "<script>alert('{$result['message']}')</script>";
				echo "<script>window.location.href='{$redirect_url}';</script>";
			}
			else{
				echo "<script>alert('{$result['message']}')</script>";
				echo "<script>window.history.back();</script>";
			}
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 알림톡 템플릿 설정을 저장한다.
	 */
	public function alimtalk_template_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-alimtalk-template-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-alimtalk-template-save-nonce'], 'cosmosfarm-members-alimtalk-template-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option = get_cosmosfarm_members_option();
			$alimtalk_template = $option->alimtalk_template ? $option->alimtalk_template : array();
			
			$field_index = isset($_POST['field_index']) ? $_POST['field_index'] : '';
			$delete = isset($_POST['delete'])&&$_POST['delete'] ? $_POST['delete'] : '';
			if($delete && $field_index != ''){
				unset($alimtalk_template[$field_index]);
			}
			else{
				$fields = array();
				if(isset($_POST['cosmosfarm_members_alimtalk_template'])){
					foreach($_POST['cosmosfarm_members_alimtalk_template'] as $name=>$template){
						$template_name = sanitize_textarea_field($template['name']);
						
						$fields['template_code'] = $name;
						$fields['key'] = array();
						$fields['name'] = $template_name;
						if(isset($template['key'])){
							foreach($template['key'] as $key=>$value){
								$template_key = isset($template['key'][$key]) ? sanitize_textarea_field($template['key'][$key]) : '';
								$template_value = isset($template['value'][$key]) ? sanitize_textarea_field($template['value'][$key]) : '';
								$template_type = isset($template['type'][$key]) ? sanitize_textarea_field($template['type'][$key]) : '';
								$template_type_label = isset($template['type_label'][$key]) ? sanitize_textarea_field($template['type_label'][$key]) : '';
								
								if($template_key != '' && $template_value != '' && $template_type != '' && $template_type_label != ''){
									$fields['key'][$key] = $template_key;
									$fields['value'][$key] = $template_value;
									$fields['type'][$key] = $template_type;
									$fields['type_label'][$key] = $template_type_label;
								}
							}
						}
					}
					if($fields){
						array_push($alimtalk_template, $fields);
					}
				}
			}
			
			$_POST['cosmosfarm_members_alimtalk_template'] = $alimtalk_template;
			
			$option->update('cosmosfarm_members_alimtalk_template', array());
			
			wp_redirect(admin_url('admin.php?page=cosmosfarm_members_alimtalk_template_setting'));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 커뮤니케이션 설정을 저장한다.
	 */
	public function communication_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-communication-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-communication-save-nonce'], 'cosmosfarm-members-communication-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_notifications_page_id');
			$option->update('cosmosfarm_members_notifications_kboard');
			$option->update('cosmosfarm_members_notifications_kboard_comments');
			$option->update('cosmosfarm_members_notifications_subnotify_email', '');
			$option->update('cosmosfarm_members_notifications_subnotify_sms', '');
			$option->update('cosmosfarm_members_notifications_subnotify_email_title');
			$option->update('cosmosfarm_members_notifications_subnotify_email_content');
			$option->update('cosmosfarm_members_notifications_subnotify_sms_message');
			$option->update('cosmosfarm_members_notifications_subnotify_alimtalk_template');
			$option->update('cosmosfarm_members_messages_page_id');
			$option->update('cosmosfarm_members_messages_subnotify_email', '');
			$option->update('cosmosfarm_members_messages_subnotify_sms', '');
			$option->update('cosmosfarm_members_messages_subnotify_alimtalk', '');
			$option->update('cosmosfarm_members_messages_subnotify_email_title');
			$option->update('cosmosfarm_members_messages_subnotify_email_content');
			$option->update('cosmosfarm_members_messages_subnotify_sms_message');
			$option->update('cosmosfarm_members_messages_subnotify_alimtalk_template');
			$option->update('cosmosfarm_members_subnotify_sms_field');
			$option->update('cosmosfarm_members_users_page_id');
			$option->update('cosmosfarm_members_user_profile_page_id');
			$option->save();
			
			cosmosfarm_members_rewrite_rule();
			flush_rewrite_rules();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}

	/**
	 * 대량문자 설정을 저장한다.
	 */
	public function bulk_sms_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-bulk-sms-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-bulk-sms-save-nonce'], 'cosmosfarm-members-bulk-sms-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option = get_cosmosfarm_members_option();
			$option->update('cosmosfarm_members_subnotify_sms_field');
			$option->update('cosmosfarm_members_bulk_sms_permission_roles', array());
			$option->update('cosmosfarm_members_bulk_sms_content');
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}

	/**
	 * 대량문자를 발송한다.
	 */
	public function bulk_sms_send(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		if(current_user_can('manage_options')){
			$_POST = stripslashes_deep($_POST);
			
			$sms_field = isset($_POST['sms_field']) ? sanitize_text_field($_POST['sms_field']) : '';
			$sms_roles = isset($_POST['sms_roles']) ? $_POST['sms_roles'] : array();
			$sms_roles = array_map('sanitize_text_field', $sms_roles);
			$sms_content = isset($_POST['sms_content']) ? sanitize_textarea_field($_POST['sms_content']) : '';
			
			$sms = get_cosmosfarm_members_sms();
			
			$result = array();
			
			if(!$sms_content){
				$result = array('result'=>'fail', 'message'=>'SMS 내용을 입력해주세요.');
			}
			if(!$sms_roles){
				$result = array('result'=>'fail', 'message'=>'역할을 선택해주세요.');
			}
			if(!$sms_field){
				$result = array('result'=>'fail', 'message'=>'휴대폰 번호 필드를 선택해주세요.');
			}
			
			if($sms_field && $sms_roles && $sms_content){
				$result = $sms->send_every($sms_field, $sms_content, $sms_roles);
			}
			
			wp_send_json($result);
		}
	}
	
	/**
	 * 알림 정보를 저장한다.
	 */
	public function notification_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-notification-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-notification-save-nonce'], 'cosmosfarm-members-notification-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br' => array(),
				'p' => array(),
				'strong' => array(),
			);
			
			$notification_id = isset($_POST['notification_id']) ? intval($_POST['notification_id']) : '';
			$notification_title = isset($_POST['notification_title']) ? sanitize_text_field($_POST['notification_title']) : '';
			$notification_content = isset($_POST['notification_content']) ? wp_kses($_POST['notification_content'], $allowed_html) : '';
			$notification_from_user_id = isset($_POST['notification_from_user_id']) ? intval($_POST['notification_from_user_id']) : '';
			$notification_to_user_id = isset($_POST['notification_to_user_id']) ? intval($_POST['notification_to_user_id']) : '';
			
			$notification = new Cosmosfarm_Members_Notification($notification_id);
			if($notification->ID){
				$notification->update(array(
					'post_title'   => $notification_title,
					'post_content' => $notification_content,
				));
				
				update_post_meta($notification->ID, 'from_user_id', $notification_from_user_id);
				update_post_meta($notification->ID, 'to_user_id', $notification_to_user_id);
				
				wp_redirect(wp_get_referer());
			}
			else{
				$post_id = cosmosfarm_members_send_notification(array(
					'from_user_id' => $notification_from_user_id,
					'to_user_id'   => $notification_to_user_id,
					'title'        => $notification_title,
					'content'      => $notification_content
				));
				
				wp_redirect(admin_url("admin.php?page=cosmosfarm_members_notification&notification_id={$post_id}"));
			}
			
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 쪽지 정보를 저장한다.
	 */
	public function message_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-message-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-message-save-nonce'], 'cosmosfarm-members-message-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br' => array(),
				'p' => array(),
				'strong' => array(),
			);
			
			$message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : '';
			$message_title = isset($_POST['message_title']) ? sanitize_text_field($_POST['message_title']) : '';
			$message_content = isset($_POST['message_content']) ? wp_kses($_POST['message_content'], $allowed_html) : '';
			$message_from_user_id = isset($_POST['message_from_user_id']) ? intval($_POST['message_from_user_id']) : '';
			$message_to_user_id = isset($_POST['message_to_user_id']) ? intval($_POST['message_to_user_id']) : '';
			
			$message = new Cosmosfarm_Members_Message($message_id);
			if($message->ID){
				$message->update(array(
					'post_title'   => $message_title,
					'post_content' => $message_content,
				));
				
				update_post_meta($message->ID, 'from_user_id', $message_from_user_id);
				update_post_meta($message->ID, 'to_user_id', $message_to_user_id);
				
				wp_redirect(wp_get_referer());
			}
			else{
				$post_id = cosmosfarm_members_send_message(array(
					'from_user_id' => $message_from_user_id,
					'to_user_id'   => $message_to_user_id,
					'title'        => $message_title,
					'content'      => $message_content
				));
				
				wp_redirect(admin_url("admin.php?page=cosmosfarm_members_message&message_id={$post_id}"));
			}
			
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 정기결제 설정을 저장한다.
	 */
	public function subscription_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-subscription-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-subscription-save-nonce'], 'cosmosfarm-members-subscription-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			$builtin_pg = isset($_POST['cosmosfarm_members_builtin_pg']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg']) : '';
			
			$pg = cosmosfarm_members_load_pg($builtin_pg);
			if($pg){
				$pg->save_settings();
			}
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 정기결제 상품 정보를 저장한다.
	 */
	public function product_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-product-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-product-save-nonce'], 'cosmosfarm-members-product-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
			$product_title = isset($_POST['product_title']) ? sanitize_text_field($_POST['product_title']) : '';
			$product_content = isset($_POST['product_content']) ? $_POST['product_content'] : '';
			$product_excerpt = isset($_POST['product_excerpt']) ? $_POST['product_excerpt'] : '';
			$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
			$product_thumbnail_id = isset($_POST['product_thumbnail_id']) ? intval($_POST['product_thumbnail_id']) : '';
			$product_gallery_images = isset($_POST['product_gallery_images']) ? $_POST['product_gallery_images'] : '';
			$product_tags = isset($_POST['product_tags']) ? sanitize_text_field($_POST['product_tags']) : '';
			$product_subscription_item_active = isset($_POST['product_subscription_item_active']) ? sanitize_text_field($_POST['product_subscription_item_active']) : '';
			$product_subscription_pg_type = isset($_POST['product_subscription_pg_type']) ? sanitize_text_field($_POST['product_subscription_pg_type']) : '';
			$product_price = isset($_POST['product_price']) ? intval($_POST['product_price']) : '';
			$product_first_price = isset($_POST['product_first_price']) ? intval($_POST['product_first_price']) : '';
			$product_pay_count_limit = isset($_POST['product_pay_count_limit']) ? intval($_POST['product_pay_count_limit']) : '';
			$product_subscription_type = isset($_POST['product_subscription_type']) ? sanitize_text_field($_POST['product_subscription_type']) : '';
			$product_subscription_active = isset($_POST['product_subscription_active']) ? sanitize_text_field($_POST['product_subscription_active']) : '';
			$product_subscription_first_free = isset($_POST['product_subscription_first_free']) ? sanitize_text_field($_POST['product_subscription_first_free']) : '';
			$product_subscription_again_price_type = isset($_POST['product_subscription_again_price_type']) ? sanitize_text_field($_POST['product_subscription_again_price_type']) : '';
			$product_subscription_role = isset($_POST['product_subscription_role']) ? sanitize_text_field($_POST['product_subscription_role']) : '';
			$product_subscription_multiple_pay = isset($_POST['product_subscription_multiple_pay']) ? sanitize_text_field($_POST['product_subscription_multiple_pay']) : '';
			$product_earn_points_type = isset($_POST['product_earn_points_type']) ? sanitize_text_field($_POST['product_earn_points_type']) : '';
			$product_earn_points = isset($_POST['product_earn_points']) ? sanitize_text_field($_POST['product_earn_points']) : '';
			
			/*
			 * 결제 필드
			 */
			$fields = array();
			if(isset($_POST['product_field'])){
				foreach($_POST['product_field']['type'] as $key=>$type){
					$field_row = array();
					$field_row['type'] = sanitize_key($_POST['product_field']['type'][$key]);
					$field_row['data'] = sanitize_textarea_field($_POST['product_field']['data'][$key]);
					$field_row['label'] = sanitize_text_field($_POST['product_field']['label'][$key]);
					$field_row['meta_key'] = sanitize_key($_POST['product_field']['meta_key'][$key]);
					$field_row['user_meta_key'] = sanitize_key($_POST['product_field']['user_meta_key'][$key]);
					$field_row['required'] = sanitize_text_field($_POST['product_field']['required'][$key]);
					$field_row['order_view'] = sanitize_text_field($_POST['product_field']['order_view'][$key]);
					$fields[] = $field_row;
				}
			}
			
			/*
			 * 메시지 전송
			 */
			$product_subscription_send_sms_paid = isset($_POST['product_subscription_send_sms_paid']) ? sanitize_textarea_field($_POST['product_subscription_send_sms_paid']) : '';
			$product_subscription_send_sms_again = isset($_POST['product_subscription_send_sms_again']) ? sanitize_textarea_field($_POST['product_subscription_send_sms_again']) : '';
			$product_subscription_send_sms_again_failure = isset($_POST['product_subscription_send_sms_again_failure']) ? sanitize_textarea_field($_POST['product_subscription_send_sms_again_failure']) : '';
			$product_subscription_send_sms_expiry = isset($_POST['product_subscription_send_sms_expiry']) ? sanitize_textarea_field($_POST['product_subscription_send_sms_expiry']) : '';
			
			/*
			 * 구매자 설정
			 */
			$product_subscription_user_update = isset($_POST['product_subscription_user_update']) ? sanitize_text_field($_POST['product_subscription_user_update']) : '';
			$product_subscription_user_update_message = isset($_POST['product_subscription_user_update_message']) ? sanitize_text_field($_POST['product_subscription_user_update_message']) : '';
			$product_subscription_deactivate_pay_count = isset($_POST['product_subscription_deactivate_pay_count']) ? sanitize_text_field($_POST['product_subscription_deactivate_pay_count']) : '';
			$product_subscription_deactivate_pay_count_message = isset($_POST['product_subscription_deactivate_pay_count_message']) ? sanitize_text_field($_POST['product_subscription_deactivate_pay_count_message']) : '';
			
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if(!$product->ID()){
				$product->create(get_current_user_id(), array('title'=>$product_title, 'content'=>$product_content, 'excerpt'=>$product_excerpt, 'name'=>$product_name));
			}
			else{
				$product->update(array('title'=>$product_title, 'content'=>$product_content, 'excerpt'=>$product_excerpt, 'name'=>$product_name));
			}
			
			if($product->ID()){
				if($product_thumbnail_id){
					set_post_thumbnail($product->ID(), $product_thumbnail_id);
				}
				else{
					delete_post_thumbnail($product->ID());
				}
				
				if(cosmosfarm_members_is_advanced()){
					$product->set_subscription_item_active($product_subscription_item_active);
				}
				
				$product->set_gallery_images($product_gallery_images);
				$product->set_tags($product_tags);
				$product->set_subscription_pg_type($product_subscription_pg_type);
				$product->set_price($product_price);
				$product->set_first_price($product_first_price);
				$product->set_pay_count_limit($product_pay_count_limit);
				$product->set_subscription_type($product_subscription_type);
				$product->set_subscription_active($product_subscription_active);
				$product->set_subscription_first_free($product_subscription_first_free);
				$product->set_subscription_again_price_type($product_subscription_again_price_type);
				$product->set_subscription_role($product_subscription_role);
				
				if($product_subscription_role) $product_subscription_multiple_pay = '';
				$product->set_subscription_multiple_pay($product_subscription_multiple_pay);
				
				$product->set_earn_points_type($product_earn_points_type);
				$product->set_earn_points($product_earn_points);
				
				$meta_fields = $product->get_meta_fields();
				$product->update_meta_fields($meta_fields, $_POST);
				
				/*
				 * 결제 필드
				 */
				$product->set_fields($fields);
				
				/*
				 * 메시지 전송
				 */
				$product->set_subscription_send_sms_paid($product_subscription_send_sms_paid);
				$product->set_subscription_send_sms_again($product_subscription_send_sms_again);
				$product->set_subscription_send_sms_again_failure($product_subscription_send_sms_again_failure);
				$product->set_subscription_send_sms_expiry($product_subscription_send_sms_expiry);
				
				/*
				 * 구매자 설정
				 */
				$product->set_subscription_user_update($product_subscription_user_update);
				$product->set_subscription_user_update_message($product_subscription_user_update_message);
				$product->set_subscription_deactivate_pay_count($product_subscription_deactivate_pay_count);
				$product->set_subscription_deactivate_pay_count_message($product_subscription_deactivate_pay_count_message);
			}
			
			$cosmosfarm_subscription_product_setting = isset($_POST['cosmosfarm_subscription_product_setting'])?'#cosmosfarm-subscription-product-setting-'.intval($_POST['cosmosfarm_subscription_product_setting']):'';
			wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_product&product_id=' . $product->ID() . $cosmosfarm_subscription_product_setting));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 정기결제 쿠폰 정보를 저장한다.
	 */
	public function coupon_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-coupon-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-coupon-save-nonce'], 'cosmosfarm-members-coupon-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$coupon_id = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : '';
			$coupon_title = isset($_POST['coupon_title']) ? sanitize_text_field($_POST['coupon_title']) : '';
			$coupon_code = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';
			$coupon_active = isset($_POST['coupon_active']) ? sanitize_text_field($_POST['coupon_active']) : '';
			$coupon_usage_limit = isset($_POST['coupon_usage_limit']) ? sanitize_text_field($_POST['coupon_usage_limit']) : '';
			$coupon_usage_count = isset($_POST['coupon_usage_count']) ? sanitize_text_field($_POST['coupon_usage_count']) : '';
			$coupon_usage_date = isset($_POST['coupon_usage_date']) ? sanitize_text_field($_POST['coupon_usage_date']) : '';
			$coupon_usage_start_date = isset($_POST['coupon_usage_start_date']) ? sanitize_text_field($_POST['coupon_usage_start_date']) : '';
			$coupon_usage_end_date = isset($_POST['coupon_usage_end_date']) ? sanitize_text_field($_POST['coupon_usage_end_date']) : '';
			$coupon_discount = isset($_POST['coupon_discount']) ? sanitize_text_field($_POST['coupon_discount']) : '';
			$coupon_discount_amount = isset($_POST['coupon_discount_amount']) ? sanitize_text_field($_POST['coupon_discount_amount']) : '';
			$coupon_discount_cycle = isset($_POST['coupon_discount_cycle']) ? sanitize_text_field($_POST['coupon_discount_cycle']) : '';
			
			// 공백 제거
			$coupon_code = str_replace(' ', '', $coupon_code);
			
			$coupon_product_ids = array();
			if(isset($_POST['coupon_product_id'])){
				foreach($_POST['coupon_product_id'] as $key=>$product_id){
					$coupon_product_ids[] = intval($product_id);
				}
			}
			
			$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
			if(!$coupon->ID()){
				$coupon->create(get_current_user_id(), array('title'=>$coupon_title, 'content'=>$coupon_code, 'excerpt'=>$coupon_code, 'name'=>$coupon_title));
			}
			else{
				$coupon->update(array('title'=>$coupon_title, 'content'=>$coupon_code, 'excerpt'=>$coupon_code, 'name'=>$coupon_title));
			}
			
			if($coupon->ID()){
				$coupon->set_coupon_code($coupon_code);
				$coupon->set_active($coupon_active);
				$coupon->set_usage_limit($coupon_usage_limit);
				$coupon->set_usage_count($coupon_usage_count);
				$coupon->set_usage_date($coupon_usage_date);
				$coupon->set_usage_start_date($coupon_usage_start_date);
				$coupon->set_usage_end_date($coupon_usage_end_date);
				$coupon->set_discount($coupon_discount);
				$coupon->set_discount_amount($coupon_discount_amount);
				$coupon->set_discount_cycle($coupon_discount_cycle);
				$coupon->set_product_ids($coupon_product_ids);
			}
			
			wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_coupon&coupon_id='.$coupon->ID()));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function subnoti_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-subnoti-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-subnoti-save-nonce'], 'cosmosfarm-members-subnoti-save')){
			
			$_POST = stripslashes_deep($_POST);
			
			$coupon_id = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : '';
			$coupon_title = isset($_POST['coupon_title']) ? sanitize_text_field($_POST['coupon_title']) : '';
			$coupon_code = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';
			$coupon_active = isset($_POST['coupon_active']) ? sanitize_text_field($_POST['coupon_active']) : '';
			$coupon_usage_limit = isset($_POST['coupon_usage_limit']) ? sanitize_text_field($_POST['coupon_usage_limit']) : '';
			$coupon_usage_count = isset($_POST['coupon_usage_count']) ? sanitize_text_field($_POST['coupon_usage_count']) : '';
			$coupon_usage_date = isset($_POST['coupon_usage_date']) ? sanitize_text_field($_POST['coupon_usage_date']) : '';
			$coupon_usage_start_date = isset($_POST['coupon_usage_start_date']) ? sanitize_text_field($_POST['coupon_usage_start_date']) : '';
			$coupon_usage_end_date = isset($_POST['coupon_usage_end_date']) ? sanitize_text_field($_POST['coupon_usage_end_date']) : '';
			$coupon_discount = isset($_POST['coupon_discount']) ? sanitize_text_field($_POST['coupon_discount']) : '';
			$coupon_discount_amount = isset($_POST['coupon_discount_amount']) ? sanitize_text_field($_POST['coupon_discount_amount']) : '';
			$coupon_discount_cycle = isset($_POST['coupon_discount_cycle']) ? sanitize_text_field($_POST['coupon_discount_cycle']) : '';
			
			// 공백 제거
			$coupon_code = str_replace(' ', '', $coupon_code);
			
			$coupon_product_ids = array();
			if(isset($_POST['coupon_product_id'])){
				foreach($_POST['coupon_product_id'] as $key=>$product_id){
					$coupon_product_ids[] = intval($product_id);
				}
			}
			
			$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
			if(!$coupon->ID()){
				$coupon->create(get_current_user_id(), array('title'=>$coupon_title, 'content'=>$coupon_code, 'excerpt'=>$coupon_code, 'name'=>$coupon_title));
			}
			else{
				$coupon->update(array('title'=>$coupon_title, 'content'=>$coupon_code, 'excerpt'=>$coupon_code, 'name'=>$coupon_title));
			}
			
			if($coupon->ID()){
				$coupon->set_coupon_code($coupon_code);
				$coupon->set_active($coupon_active);
				$coupon->set_usage_limit($coupon_usage_limit);
				$coupon->set_usage_count($coupon_usage_count);
				$coupon->set_usage_date($coupon_usage_date);
				$coupon->set_usage_start_date($coupon_usage_start_date);
				$coupon->set_usage_end_date($coupon_usage_end_date);
				$coupon->set_discount($coupon_discount);
				$coupon->set_discount_amount($coupon_discount_amount);
				$coupon->set_discount_cycle($coupon_discount_cycle);
				$coupon->set_product_ids($coupon_product_ids);
			}
			
			wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_notification&notification_id='.$coupon->ID()));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 자동완성 필드에서 워드프레스 사용자를 검색한다.
	 */
	public function search_users(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		if(current_user_can('manage_options')){
			$keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
			$role__in = isset($_POST['role__in']) ? sanitize_text_field($_POST['role__in']) : '';
			$role__not_in = isset($_POST['role__not_in']) ? sanitize_text_field($_POST['role__not_in']) : '';
			
			/**
			 * Add support for the "display_name" search column in WP_User_Query
			 * @see http://wordpress.stackexchange.com/a/166369/26350
			 */
			add_filter('user_search_columns', function($search_columns){
				$search_columns[] = 'display_name';
				return $search_columns;
			});
			
			$args = array(
				'blog_id' => get_current_blog_id(),
				'search' => "*{$keyword}*",
				'search_columns' => array('user_login', 'user_email'),
				'number' => 10
			);
			
			if($role__in){
				$args['role__in'] = $role__in;
			}
			if($role__not_in){
				$args['role__not_in'] = $role__not_in;
			}
			
			$user_query = new WP_User_Query($args);
			
			$user_list = array();
			foreach($user_query->get_results() as $key=>$user){
				if($user->data->user_login == $user->data->user_email){
					$label = "#{$user->ID} {$user->data->display_name} ({$user->data->user_email})";
				}
				else{
					$label = "#{$user->ID} {$user->data->display_name} ({$user->data->user_login}, {$user->data->user_email})";
				}
				$user_list[$key]['ID'] = $user->ID;
				$user_list[$key]['label'] = $label;
				$user_list[$key]['value'] = $user->data->user_login;
				$user_list[$key]['user_email'] = $user->data->user_email;
				$user_list[$key]['display_name'] = $user->data->display_name;
			}
			
			wp_send_json($user_list);
		}
	}
	
	/**
	 * 정기결제 주문 추가하기 페이지에서 상품 선택시 상품 정보를 검색한다.
	 */
	public function search_product(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		if(current_user_can('manage_options')){
			$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			
			$args = array();
			if($product->ID()){
				$args['order_subscription_type'] = $product->subscription_type();
				$args['order_subscription_role'] = $product->subscription_role();
				
				$args['order_price'] = $product->first_price();
				
				$next_datetime = $product->next_subscription_datetime();
				if($next_datetime){
					$args['order_end_date']['order_end_year'] = date('Y', strtotime($next_datetime));
					$args['order_end_date']['order_end_month'] = date('m', strtotime($next_datetime));
					$args['order_end_date']['order_end_day'] = date('d', strtotime($next_datetime));
					$args['order_end_date']['order_end_hour'] = date('H', strtotime($next_datetime));
					$args['order_end_date']['order_end_minute'] = date('i', strtotime($next_datetime));
				}
				
				$args['order_fields_html'] = '';
				$fields = $product->fields();
				$fields_count = count($fields);
				for($index=0; $index<$fields_count; $index++){
					if($fields[$index]['type'] == 'hr') continue;
					if($fields[$index]['type'] == 'zip'){
						$args['order_fields_html'] .= $product->get_order_field_template($fields[$index++], $fields[$index++], $fields[$index]);
					}
					else{
						$args['order_fields_html'] .= $product->get_order_field_template($fields[$index]);
					}
				}
			}
			wp_send_json($args);
		}
	}
	
	/**
	 * 정기결제 주문을 결제과정 없이 직접 생성한다.
	 */
	public function order_new(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-order-new-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-order-new-nonce'], 'cosmosfarm-members-order-new')){
			header('Content-Type: text/html; charset=UTF-8');
			
			$option = get_cosmosfarm_members_option();
			
			$_POST = stripslashes_deep($_POST);
			
			$user_login = isset($_POST['user_login']) ? sanitize_text_field($_POST['user_login']) : '';
			$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
			$order_price = isset($_POST['order_price']) ? sanitize_text_field($_POST['order_price']) : '';
			$order_start_year = isset($_POST['order_start_year']) ? intval($_POST['order_start_year']) : 0;
			$order_start_month = isset($_POST['order_start_month']) ? intval($_POST['order_start_month']) : 0;
			$order_start_day = isset($_POST['order_start_day']) ? intval($_POST['order_start_day']) : 0;
			$order_start_hour = isset($_POST['order_start_hour']) ? intval($_POST['order_start_hour']) : 0;
			$order_start_minute = isset($_POST['order_start_minute']) ? intval($_POST['order_start_minute']) : 0;
			$order_end_year = isset($_POST['order_end_year']) ? intval($_POST['order_end_year']) : 0;
			$order_end_month = isset($_POST['order_end_month']) ? intval($_POST['order_end_month']) : 0;
			$order_end_day = isset($_POST['order_end_day']) ? intval($_POST['order_end_day']) : 0;
			$order_end_hour = isset($_POST['order_end_hour']) ? intval($_POST['order_end_hour']) : 0;
			$order_end_minute = isset($_POST['order_end_minute']) ? intval($_POST['order_end_minute']) : 0;
			$order_subscription_role = isset($_POST['order_subscription_role']) ? sanitize_text_field($_POST['order_subscription_role']) : '';
			$order_customer_uid	= isset($_POST['order_customer_uid']) ? sanitize_text_field($_POST['order_customer_uid']) : '';
			
			if($user_login){
				$user = get_user_by('login', $user_login);
				
				if($user){
					if($product_id){
						$product = new Cosmosfarm_Members_Subscription_Product($product_id);
						$order = new Cosmosfarm_Members_Subscription_Order();
						
						if(!$product->subscription_multiple_pay()){
							if($product->is_in_use($user->ID)){
								echo "<script>alert('해당 사용자 이미 상품을 이용중입니다. 여러번 결제가 불가능한 상품입니다.')</script>";
								echo "<script>window.history.back();</script>";
								exit;
							}
						}
						
						$meta_input = array();
						
						if($order_customer_uid && $order_price){
							$buyer_name = isset($_POST['buyer_name']) ? sanitize_text_field($_POST['buyer_name']) : '';
							$buyer_email = isset($_POST['buyer_email']) ? sanitize_text_field($_POST['buyer_email']) : '';
							$buyer_tel = isset($_POST['buyer_tel']) ? sanitize_text_field($_POST['buyer_tel']) : '';
							$addr1 = isset($_POST['addr1']) ? sanitize_text_field($_POST['addr1']) : '';
							$addr2 = isset($_POST['addr2']) ? sanitize_text_field($_POST['addr2']) : '';
							$zip = isset($_POST['zip']) ? sanitize_text_field($_POST['zip']) : '';
							
							$meta_input = array(
								'name'           => $product->title(),
								'buyer_name'     => $buyer_name,
								'buyer_email'    => $buyer_email,
								'buyer_tel'      => $buyer_tel,
								'buyer_addr'     => (($addr1 && $addr2) ? trim("{$addr1} {$addr2}") : ''),
								'buyer_postcode' => $zip
							);
							
							$builtin_pg = $option->builtin_pg;
							$pg_type = $product->get_subscription_pg_type() == 'general' ? 'general' : 'billing';
							
							$subscribe_result = new stdClass();
							$subscribe_result->status = 'failed';
							
							if($builtin_pg){
								$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>$pg_type));
								
								if($pg){
									$subscribe_result = $pg->subscribe($order_customer_uid, $order_price, $meta_input);
								}
							}
							
							if($subscribe_result->status == 'failed'){
								echo "<script>alert('결제 요청 정보가 입력되었지만 결제에 실패하여 주문을 등록할 수 없습니다. 다시 시도해주세요.')</script>";
								echo "<script>window.history.back();</script>";
								exit;
							}
							
							$meta_input['imp_uid'] = isset($subscribe_result->imp_uid) ? $subscribe_result->imp_uid : '';
							$meta_input['builtin_pg'] = $builtin_pg;
							$meta_input['builtin_pg_id'] = isset($subscribe_result->builtin_pg_id) ? $subscribe_result->builtin_pg_id : '';
							$meta_input['builtin_pg_tid'] = isset($subscribe_result->builtin_pg_tid) ? $subscribe_result->builtin_pg_tid : '';
							$meta_input['merchant_uid'] = isset($subscribe_result->merchant_uid) ? $subscribe_result->merchant_uid : '';
							$meta_input['receipt_url'] = isset($subscribe_result->receipt_url) ? $subscribe_result->receipt_url : '';
							$meta_input['payment_method'] = isset($custom_data['payment_method']) ? sanitize_text_field($custom_data['payment_method']) : '';
							$meta_input['pg_type'] = $pg_type;
						}
						
						$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
						
						$order->set_sequence_id(uniqid());
						$order->set_status_paid();
						$order->set_product_id($product->ID());
						$order->set_price($order_price);
						$order->set_balance($order_price);
						$order->set_first_price($product->first_price());
						$order->set_pay_count_limit($product->pay_count_limit());
						$order->set_subscription_type($product->subscription_type());
						$order->set_subscription_active($product->subscription_active());
						$order->set_subscription_again_price_type($product->subscription_again_price_type());
						
						if($order_customer_uid){
							$order->set_iamport_customer_uid($order_customer_uid);
						}
						
						$pay_count = 1;
						$order->set_pay_count($pay_count);
						
						if($order_subscription_role){
							$order->set_subscription_role($order_subscription_role);
							$order->set_subscription_prev_role($user->roles[0]);
							
							if(!is_super_admin($user->ID)){
								$user->remove_role($user->roles[0]);
								$user->add_role($order_subscription_role);
							}
						}
						
						$start_date = date('YmdHis', mktime($order_start_hour, $order_start_minute, 0, $order_start_month, $order_start_day, $order_start_year));
						$end_date = date('YmdHis', mktime($order_end_hour, $order_end_minute, 0, $order_end_month, $order_end_day, $order_end_year));
						
						$order->set_start_datetime($start_date);
						
						if($order_end_year && $order_end_month && $order_end_day && ($start_date < $end_date)){
							$order->set_end_datetime($end_date);
							$order->set_subscription_type('');
							$order->set_subscription_next('wait');
						}
						else{
							$order->set_subscription_type('onetime');
							$order->set_subscription_next('success');
						}
						
						if($product->is_use_earn_points()){
							if($product->earn_points_type() == 'reset'){
								$balance = mycred_get_users_balance($user->ID);
								mycred_add('cosmosfarm_subscription_earn_points', $user->ID, ($balance*-1), __('Reset Points', 'cosmosfarm-members'));
							}
							
							mycred_add('cosmosfarm_subscription_earn_points', $user->ID, $product->earn_points(), __('Earn Points', 'cosmosfarm-members'));
							
							$order->set_earn_points_type($product->earn_points_type());
							$order->set_earn_points($product->earn_points());
						}
						
						if($product->subscription_active()){
							$content = sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 1회차]', $product->title());
						}
						else{
							$content = sprintf('<strong>%s</strong> 결제되었습니다.', $product->title());
						}
						
						$custom_data = $_POST;
						
						$order->update(array('content'=>$content));
						$order->update_meta_fields($product->fields(), $custom_data);
						
						do_action('cosmosfarm_members_subscription_request_pay', $order, $product, $custom_data);
						
						cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => $content
						));
						
						wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_order&order_id=' . $order->ID()));
						exit;
					}
					else{
						echo "<script>alert('상품을 선택해주세요.')</script>";
						echo "<script>window.history.back();</script>";
						exit;
					}
				}
				else{
					echo "<script>alert('일치하는 사용자가 없습니다.')</script>";
					echo "<script>window.history.back();</script>";
					exit;
				}
			}
			else{
				echo "<script>alert('사용자 아이디를 입력해주세요.')</script>";
				echo "<script>window.history.back();</script>";
				exit;
			}
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 정기결제 주문 정보를 저장한다.
	 */
	public function order_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-order-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-order-save-nonce'], 'cosmosfarm-members-order-save')){
			$option = get_cosmosfarm_members_option();
			
			$_POST = stripslashes_deep($_POST);
			
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
			//$order_content = isset($_POST['order_content']) ? $_POST['order_content'] : '';
			$order_product_id = isset($_POST['order_product_id']) ? intval($_POST['order_product_id']) : '';
			$order_price = isset($_POST['order_price']) ? sanitize_text_field($_POST['order_price']) : '';
			$order_coupon_id = isset($_POST['order_coupon_id']) ? sanitize_text_field($_POST['order_coupon_id']) : '';
			$order_coupon_price = isset($_POST['order_coupon_price']) ? sanitize_text_field($_POST['order_coupon_price']) : '';
			$order_before_coupon_price = isset($_POST['order_before_coupon_price']) ? sanitize_text_field($_POST['order_before_coupon_price']) : '';
			$order_status = isset($_POST['order_status']) ? sanitize_text_field($_POST['order_status']) : '';
			$order_balance = isset($_POST['order_balance']) ? sanitize_text_field($_POST['order_balance']) : '';
			$order_end_year = isset($_POST['order_end_year']) ? sanitize_text_field($_POST['order_end_year']) : '';
			$order_end_month = isset($_POST['order_end_month']) ? sanitize_text_field($_POST['order_end_month']) : '';
			$order_end_day = isset($_POST['order_end_day']) ? sanitize_text_field($_POST['order_end_day']) : '';
			$order_end_hour = isset($_POST['order_end_hour']) ? sanitize_text_field($_POST['order_end_hour']) : '';
			$order_end_minute = isset($_POST['order_end_minute']) ? sanitize_text_field($_POST['order_end_minute']) : '';
			$order_customer_uid = isset($_POST['order_customer_uid']) ? sanitize_text_field($_POST['order_customer_uid']) : '';
			$order_subscription_active = isset($_POST['order_subscription_active']) ? sanitize_text_field($_POST['order_subscription_active']) : '';
			$order_subscription_next = isset($_POST['order_subscription_next']) ? sanitize_text_field($_POST['order_subscription_next']) : '';
			$order_error_message = isset($_POST['order_error_message']) ? sanitize_text_field($_POST['order_error_message']) : '';
			$order_pay_count = isset($_POST['order_pay_count']) ? sanitize_text_field($_POST['order_pay_count']) : '';
			$order_subscription_prev_role = isset($_POST['order_subscription_prev_role']) ? sanitize_text_field($_POST['order_subscription_prev_role']) : '';
			$order_subscription_role = isset($_POST['order_subscription_role']) ? sanitize_text_field($_POST['order_subscription_role']) : '';
			$order_courier_company = isset($_POST['order_courier_company']) ? sanitize_textarea_field($_POST['order_courier_company']) : '';
			$order_tracking_code = isset($_POST['order_tracking_code']) ? sanitize_textarea_field($_POST['order_tracking_code']) : '';
			$order_comment = isset($_POST['order_comment']) ? sanitize_textarea_field($_POST['order_comment']) : '';
			
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			
			$order->set_product_id($order_product_id);
			$order->set_price($order_price);
			$order->set_coupon_id($order_coupon_id);
			$order->set_coupon_price($order_coupon_price);
			$order->set_before_coupon_price($order_before_coupon_price);
			$order->set_balance($order_balance);
			$order->set_iamport_customer_uid($order_customer_uid);
			$order->set_subscription_active($order_subscription_active);
			$order->set_subscription_next($order_subscription_next);
			$order->set_error_message($order_error_message);
			$order->set_pay_count($order_pay_count);
			
			$user = $order->user();
			if(!is_super_admin($user->ID)){
				$order->set_subscription_prev_role($order_subscription_prev_role);
				$order->set_subscription_role($order_subscription_role);
			}
			
			if($order_status == 'paid'){
				$order->set_status_paid();
			}
			else if($order_status == 'cancelled'){
				$order->set_status_cancelled();
			}
			
			if($order->end_datetime() && $order_end_year && $order_end_month && $order_end_day && $order_end_hour && $order_end_minute){
				$order_end_second = date('s', strtotime($order->end_datetime()));
				$next_datetime = date('YmdHis', mktime($order_end_hour, $order_end_minute, $order_end_second, $order_end_month, $order_end_day, $order_end_year));
				$order->set_end_datetime($next_datetime);
				
				cosmosfarm_members_subscription_again_now();
			}
			
			if($option->subscription_courier_company){
				$order->set_courier_company($order_courier_company);
				$order->set_tracking_code($order_tracking_code);
			}
			
			$order->set_order_comment($order_comment);
			
			$product = new Cosmosfarm_Members_Subscription_Product($order_product_id);
			$order->update_meta_fields($product->fields(), $_POST);
			
			wp_redirect(admin_url('admin.php?page=cosmosfarm_subscription_order&order_id=' . $order->ID()));
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	/**
	 * 정기결제 주문을 취소한다.
	 */
	public function order_cancel(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(current_user_can('manage_options')){
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			$cancel_id = $order->cancel_id();
			$builtin_pg = $order->builtin_pg();
			
			if($builtin_pg){
				$pg_type = $order->get_pg_type();
				$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>$pg_type));
				$pg->set_order($order);
			}
			else{
				$pg = cosmosfarm_members_load_pg('iamport');
			}
			
			if($cancel_id){
				$cancel_result = $pg->cancel($cancel_id);
				
				if($cancel_result->status == 'cancelled'){
					$order->set_status_cancelled();
					$order->execute_expiry_action();
					
					cosmosfarm_members_send_notification(array(
						'to_user_id' => $order->user()->ID,
						'content'    => sprintf('<strong>%s</strong> 결제가 취소되었습니다.', $order->title()),
						'meta_input' => array(
							'url'      => $cancel_result->receipt_url,
							'url_name' =>'영수증',
						),
					));
					
					$result = array('result'=>'success', 'message'=>__('This payment has been canceled.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>$cancel_result->error_message);
				}
			}
			else{
				$order->set_status_cancelled();
				$order->execute_expiry_action();
				
				cosmosfarm_members_send_notification(array(
					'to_user_id' => $order->user()->ID,
					'content'    => sprintf('<strong>%s</strong> 결제가 취소되었습니다.', $order->title()),
				));
				
				$result = array('result'=>'success', 'message'=>__('This payment has been canceled.', 'cosmosfarm-members'));
			}
		}
		
		$result = apply_filters('cosmosfarm_members_order_cancel_result', $result);
		wp_send_json($result);
	}
	
	public function order_cancel_partial(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(current_user_can('manage_options')){
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
			$order_balance = isset($_POST['order_balance']) ? intval($_POST['order_balance']) : '';
			$order_cancel_price = isset($_POST['order_cancel_price']) ? intval($_POST['order_cancel_price']) : '';
			$new_balance = $order_balance - $order_cancel_price;
			
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			$cancel_id = $order->cancel_id();
			$builtin_pg = $order->builtin_pg();
			
			if($builtin_pg){
				$pg_type = $order->get_pg_type();
				$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>$pg_type));
				$pg->set_order($order);
			}
			else{
				$pg = cosmosfarm_members_load_pg('iamport');
			}
			
			if($cancel_id){
				$cancel_result = $pg->cancel_partial($cancel_id, array(
					'price' => $order_cancel_price,
					'balance' => $new_balance,
				));
				
				if($cancel_result->status == 'cancelled'){
					$order->set_balance($new_balance);
					
					if($new_balance <= 0){
						$order->set_status_cancelled();
						$order->execute_expiry_action();
					}
					
					cosmosfarm_members_send_notification(array(
						'to_user_id' => $order->user()->ID,
						'content'    => sprintf('<strong>%s</strong> 결제가 부분 취소되었습니다.', $order->title()),
						'meta_input' => array(
							'url'                  => $cancel_result->receipt_url,
							'url_name'             =>'영수증',
							'partial_cancel_price' => $order_cancel_price,
						),
					));
					
					$result = array('result'=>'success', 'message'=>__('This payment has been partially canceled.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>$cancel_result->error_message);
				}
			}
			else{
				$order->set_balance($new_balance);
				
				if($new_balance <= 0){
					$order->set_status_cancelled();
					$order->execute_expiry_action();
				}
				
				cosmosfarm_members_send_notification(array(
					'to_user_id' => $order->user()->ID,
					'content'    => sprintf('<strong>%s</strong> 결제가 부분 취소되었습니다.', $order->title()),
					'meta_input' => array(
						'partial_cancel_price' => $order_cancel_price,
					),
				));
				
				$result = array('result'=>'success', 'message'=>__('This payment has been partially canceled.', 'cosmosfarm-members'));
			}
		}
		
		$result = apply_filters('cosmosfarm_members_order_cancel_result', $result);
		wp_send_json($result);
	}
	
	/**
	 * 정기결제 주문을 다운로드한다.
	 */
	public function order_download(){
		if(current_user_can('manage_options') && isset($_GET['cosmosfarm-members-order-download-nonce']) && wp_verify_nonce($_GET['cosmosfarm-members-order-download-nonce'], 'cosmosfarm-members-order-download')){
			set_time_limit(3600);
			ini_set('memory_limit', '-1');
			
			header('Content-Type: text/html; charset=UTF-8');
			
			do_action('cosmosfarm_members_pre_order_download');
			
			$option = get_cosmosfarm_members_option();
			$courier_company_list = cosmosfarm_members_courier_company_list();
			
			$subscription_next = isset($_GET['subscription_next']) ? sanitize_text_field($_GET['subscription_next']) : '';
			$order_archives = isset($_GET['order_archives']) ? sanitize_text_field($_GET['order_archives']) : '';
			$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
			$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
			
			$order = new Cosmosfarm_Members_Subscription_Order();
			$args = array(
				'post_type' => $order->post_type,
				'posts_per_page' => -1,
			);
			
			$meta_query = array();
			
			if($subscription_next){
				$meta_query[] = array(
					'key'     => 'subscription_next',
					'value'   => $subscription_next,
					'compare' => 'LIKE',
				);
			}
			
			if($order_archives){
				$time = strtotime($order_archives . '01');
				$args['date_query'] = array(
					'year'  => date('Y', $time),
					'month' => date('m', $time),
				);
			}
			
			if($keyword){
				if($target){
					$meta_query[] = array(
						'key'     => $target,
						'value'   => $keyword,
						'compare' => 'LIKE',
					);
				}
				else{
					$args['s'] = $keyword;
				}
			}
			
			$args['meta_query'] = $meta_query;
			$query = new WP_Query($args);
			
			$date = date('YmdHis', current_time('timestamp'));
			$filename = "주문 목록 {$date}.csv";
			
			if($option->subscription_courier_company){
				$columns = array('상품 이름', '가격', '쿠폰', '결제 상태', '정기결제 상태', '다음 정기결제 실패', '정기결제 회차', '사용자', '주문자명', '이메일', '전화번호', '택배사', '운송장 번호', '날짜', '시작일', '만료일');
			}
			else{
				$columns = array('상품 이름', '가격', '쿠폰', '결제 상태', '정기결제 상태', '다음 정기결제 실패', '정기결제 회차', '사용자', '주문자명', '이메일', '전화번호', '날짜', '시작일', '만료일');
			}
			
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Pragma: no-cache');
			header('Expires: 0');
			
			@ob_clean();
			@flush();
			
			$csv = fopen('php://output', 'w');
			
			fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($csv, $columns);
			
			foreach($query->posts as $post){
				$order = new Cosmosfarm_Members_Subscription_Order($post->ID);
				$product = new Cosmosfarm_Members_Subscription_Product($order->product_id());
				
				$user = $order->user();
				$row_data = array();
				$row_data[] = $order->title();
				
				// 상황에 따른 가격 정보
				if($order->pay_count() == '1'){
					if($order->subscription_first_free()){
						$row_data[] = 0;
					}
					else if($order->coupon_id()){
						$row_data[] = $order->coupon_price();
					}
					else{
						$row_data[] = $order->first_price();
					}
				}
				else if($order->coupon_id()){
					$row_data[] = $order->coupon_price();
				}
				else{
					$row_data[] = $order->price();
				}
				
				if($order->coupon_id()){
					$coupon = new Cosmosfarm_Members_Subscription_Coupon();
					$coupon->init_with_id($order->coupon_id());
					$row_data[] = $coupon->title();
				}
				else{
					$row_data[] = '';
				}
				
				$row_data[] = $order->status_format();
				
				if($order->subscription_active()){
					$row_data[] = $order->subscription_next_format() ? $order->subscription_next_format() . ' (이용기간 만료 후 자동결제)' : '(이용기간 만료 후 자동결제)';
				}
				else{
					$row_data[] = $order->subscription_next_format() ? $order->subscription_next_format() . ' (자동결제 없음)' : '(자동결제 없음)';
				}
				
				$row_data[] = $order->error_message();
				$row_data[] = $order->pay_count();
				$row_data[] = "{$user->display_name}({$user->user_login})";
				$row_data[] = $order->buyer_name;
				$row_data[] = $order->buyer_email;
				$row_data[] = '="' . $order->buyer_tel . '"';
				
				if($option->subscription_courier_company){
					if($order->tracking_code()){
						$courier_company = $order->courier_company() ? $order->courier_company() : $option->subscription_courier_company;
						$row_data[] = $courier_company_list[$courier_company]['name'];
						$row_data[] = '="' . $order->tracking_code() . '"';
					}
					else{
						$row_data[] = '';
						$row_data[] = '';
					}
				}
				
				$row_data[] = $order->post_date;
				$row_data[] = $order->start_datetime() ? date('Y-m-d H:i', strtotime($order->start_datetime())) : '';
				$row_data[] = $order->end_datetime() ? date('Y-m-d H:i', strtotime($order->end_datetime())) : '';
				
				$fields = $product->fields();
				$fields_count = count($fields);
				for($index=0; $index<$fields_count; $index++){
					if(in_array($fields[$index]['type'], array('hr', 'agree'))) continue;
					
					$meta_value = get_post_meta($order->ID(), $fields[$index]['meta_key'], true);
					$row_data[] = $meta_value;
				}
				
				fputcsv($csv, $row_data);
			}
			@ob_flush();
			@flush();
			
			fclose($csv);
			exit;
		}
		echo '<script>window.history.go(-1);</script>';
		exit;
	}
	
	/**
	 * 메일침프 설정을 저장한다.
	 */
	public function mailchimp_save(){
		if(current_user_can('manage_options') && isset($_POST['cosmosfarm-members-mailchimp-save-nonce']) && wp_verify_nonce($_POST['cosmosfarm-members-mailchimp-save-nonce'], 'cosmosfarm-members-mailchimp-save')){
			$option = get_cosmosfarm_members_option();
			$option->save();
			
			wp_redirect(wp_get_referer());
			exit;
		}
		else{
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
	}
	
	public function social_login(){
		$channel = isset($_GET['channel'])?$_GET['channel']:'';
		if(is_object($api = $this->get_social_api($channel))){
			
			$redirect_to = isset($_GET['redirect_to']) ? esc_url_raw(trim($_GET['redirect_to'])) : '';
			if($redirect_to){
				$_SESSION['cosmosfarm_members_social_login_redirect_to'] = $redirect_to;
			}
			
			wp_redirect($api->get_request_url());
			exit;
		}
		wp_redirect(home_url());
		exit;
	}
	
	public function social_login_callback($channel){
		if(is_object($api = $this->get_social_api($channel))){
			$api->init_access_token();
			$profile = $api->get_profile();
			
			if($profile->id){
				if($channel == 'naver'){
					if($profile->naver_enc_id){
						$social_id = "{$channel}@{$profile->naver_enc_id}";
						$user = get_users(array('meta_key'=>'cosmosfarm_members_social_id','meta_value'=>$social_id, 'number'=>1, 'count_total'=>false));
						$user = reset($user);
					}
					
					if(!isset($user->ID) || !$user->ID){
						$social_id = "{$channel}@{$profile->id}";
						$user = get_users(array('meta_key'=>'cosmosfarm_members_social_id','meta_value'=>$social_id, 'number'=>1, 'count_total'=>false));
						$user = reset($user);
					}
				}
				else{
					$social_id = "{$channel}@{$profile->id}";
					$user = get_users(array('meta_key'=>'cosmosfarm_members_social_id','meta_value'=>$social_id, 'number'=>1, 'count_total'=>false));
					$user = reset($user);
				}
				
				$random_password = wp_generate_password(128, true, true);
				
				if(!isset($user->ID) || !$user->ID){
					$profile->user_login = sanitize_user($profile->user_login);
					$profile->email = sanitize_email($profile->email);
					$profile->nickname = sanitize_text_field($profile->nickname);
					$profile->picture = sanitize_text_field($profile->picture);
					$profile->url = sanitize_text_field($profile->url);
					
					if(!$profile->user_login || username_exists($profile->user_login)){
						$profile->user_login = "{$channel}_" . uniqid();
					}
					
					if(!$profile->email || email_exists($profile->email)){
						// 무작위 이메일 주소로 회원 등록후, 등록된 이메일을 지우기 위해서 $update_email에 빈 값을 등록해준다.
						$profile->email = "{$channel}_" . uniqid() . '@example.com';
						$update_email = '';
					}
					else{
						$update_email = $profile->email;
					}
					
					include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Social_Login.class.php';
					$social_login = new Cosmosfarm_Members_Social_Login();
					$social_login->social_id = $social_id;
					$social_login->channel = $channel;
					$social_login->picture = $profile->picture;
					$social_login->user_url = $profile->url;
					$social_login->user_email = $update_email;
					$social_login->display_name = $profile->nickname;
					$social_login->nickname = $profile->nickname;
					$social_login->raw_data = $profile->raw_data;
					
					add_action('user_register', array($social_login, 'user_register'), 10, 1);
					
					$user_id = wp_create_user($profile->user_login, $random_password, $profile->email);
					
					$user = new WP_User($user_id);
				}
				else{
					wp_set_password($random_password, $user->ID);
					update_user_meta($user->ID, 'cosmosfarm_members_social_picture', $profile->picture);
				}
				
				do_action('cosmosfarm_members_social_login_callback', $channel, $profile, $user, $random_password);
				
				wp_set_current_user($user->ID, $user->user_login);
				wp_set_auth_cookie($user->ID, true, is_ssl());
				do_action('wp_login', $user->user_login, $user);
				
				$redirect_to = home_url();
				$option = get_cosmosfarm_members_option();
				
				if($option->login_redirect_page == 'main'){
					$redirect_to = home_url();
				}
				else if($option->login_redirect_page == 'url' && $option->login_redirect_url){
					$redirect_to = $option->login_redirect_url;
				}
				else if(isset($_SESSION['cosmosfarm_members_social_login_redirect_to']) && $_SESSION['cosmosfarm_members_social_login_redirect_to']){
					$redirect_to = $_SESSION['cosmosfarm_members_social_login_redirect_to'];
					$_SESSION['cosmosfarm_members_social_login_redirect_to'] = '';
					unset($_SESSION['cosmosfarm_members_social_login_redirect_to']);
				}
				
				$redirect_to = apply_filters('cosmosfarm_members_social_login_redirect_to', $redirect_to, $profile, $user, $random_password);
				
				wp_redirect($redirect_to);
				exit;
			}
		}
		wp_redirect(home_url());
		exit;
	}
	
	public function get_social_api($channel){
		switch($channel){
			
			case 'naver':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Naver.class.php';
				$api = new Cosmosfarm_Members_API_Naver();
				break;
				
			case 'facebook':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Facebook.class.php';
				$api = new Cosmosfarm_Members_API_Facebook();
				break;
				
			case 'kakao':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Kakao.class.php';
				$api = new Cosmosfarm_Members_API_Kakao();
				break;
				
			case 'google':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Google.class.php';
				$api = new Cosmosfarm_Members_API_Google();
				break;
				
			case 'twitter':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Twitter.class.php';
				$api = new Cosmosfarm_Members_API_Twitter();
				break;
				
			case 'instagram':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Instagram.class.php';
				$api = new Cosmosfarm_Members_API_Instagram();
				break;
				
			case 'line':
				include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Line.class.php';
				$api = new Cosmosfarm_Members_API_Line();
				break;
				
			default: $api = false;
		}
		return apply_filters('cosmosfarm_members_get_social_api', $api, $channel);
	}
	
	private function verify_email_confirm(){
		$verify_code = isset($_GET['verify_code'])?$_GET['verify_code']:'';
		
		if($verify_code){
			$users = get_users(array('meta_key'=>'wait_verify_email', 'meta_value'=>$verify_code));
			
			foreach($users as $user){
				delete_user_meta($user->ID, 'wait_verify_email');
				update_user_meta($user->ID, 'verify_email', '1');
				
				$option = get_cosmosfarm_members_option();
				if($option->confirmed_email){
					cosmosfarm_members_send_confirmed_email($user);
				}
				
				wp_redirect(add_query_arg(array('verify_email_confirm'=>'1'), wp_login_url()));
				exit;
			}
		}
		
		if(is_user_logged_in()){
			wp_redirect(home_url());
		}
		else{
			wp_redirect(wp_login_url());
		}
		exit;
	}
	
	private function delete_account(){
		if(current_user_can('manage_options')){
			wp_die(__('You will not be able to perform this task.', 'cosmosfarm-members'));
		}
		
		if(is_user_logged_in() && isset($_GET['cosmosfarm_members_delete_account_nonce']) || wp_verify_nonce($_GET['cosmosfarm_members_delete_account_nonce'], 'cosmosfarm_members_delete_account')){
			$current_user = wp_get_current_user();
			
			if($current_user->ID){
				header('Content-Type: text/html; charset=UTF-8');
				
				do_action('cosmosfarm_members_delete_account');
				
				if(is_multisite()){
					if(!function_exists('wpmu_delete_user')){
						include_once ABSPATH . '/wp-admin/includes/ms.php';
					}
					
					if(wpmu_delete_user($current_user->ID)){
						wp_clear_auth_cookie();
					}
				}
				else{
					if(!function_exists('wp_delete_user')){
						include_once ABSPATH . '/wp-admin/includes/user.php';
					}
					
					if(wp_delete_user($current_user->ID)){
						wp_clear_auth_cookie();
					}
				}
				
				$message = __('Your account has been deleted. Thank you.', 'cosmosfarm-members');
				$home_url = home_url();
				
				echo "<script>alert('{$message}');</script>";
				echo "<script>window.location.href='{$home_url}';</script>";
				exit;
			}
		}
		
		if(is_user_logged_in()){
			wp_redirect(home_url());
		}
		else{
			wp_redirect(wp_login_url());
		}
		exit;
	}
	
	private function login_timeout(){
		if(is_user_logged_in()){
			$option = get_cosmosfarm_members_option();
			$use_login_timeout = apply_filters('cosmosfarm_members_use_login_timeout', $option->use_login_timeout, $option);
			if($use_login_timeout){
				
				wp_logout();
				
				if($use_login_timeout == '1'){
					wp_redirect(add_query_arg(array('login_timeout'=>'1'), wp_login_url(wp_get_referer())));
					exit;
				}
				else if($use_login_timeout == '2'){
					wp_redirect(wp_get_referer());
					exit;
				}
			}
		}
		
		if(is_user_logged_in()){
			wp_redirect(home_url());
		}
		else{
			wp_redirect(wp_login_url());
		}
		exit;
	}
	
	private function certification_confirm(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$imp_uid = isset($_POST['imp_uid']) ? sanitize_text_field($_POST['imp_uid']) : '';
		if($imp_uid){
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
			$api = new Cosmosfarm_Members_API_Iamport();
			$certification = $api->getCertification($imp_uid);
			wp_send_json($certification);
		}
		exit;
	}
	
	/**
	 * 정기결제 상품 쿠폰 적용
	 */
	private function subscription_apply_coupon(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
			$coupon_code = isset($_POST['cosmosfarm_members_subscription_coupon_code']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_coupon_code']) : '';
			
			if($product_id && $coupon_code){
				$coupon = new Cosmosfarm_Members_Subscription_Coupon();
				$coupon->init_with_coupon_code($coupon_code);
				
				if($coupon->ID() && $coupon->is_available($product_id)){
					$coupon->save_coupon_id(get_current_user_id(), $product_id, $coupon->ID());
					
					$result['result'] = 'success';
					$result['message'] = __('The coupon has been applied.', 'cosmosfarm-members');
				}
			}
			else if($product_id){
				$coupon = new Cosmosfarm_Members_Subscription_Coupon();
				$coupon->save_coupon_id(get_current_user_id(), $product_id, '');
				
				$result['result'] = 'success';
				$result['message'] = __('The coupon has been removed.', 'cosmosfarm-members');
			}
		}
		
		wp_send_json($result);
	}
	
	private function subscription_register_card(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$builtin_pg = isset($_REQUEST['builtin_pg']) ? sanitize_text_field($_REQUEST['builtin_pg']) : '';
			$pg_type = isset($_REQUEST['pg_type']) && $_REQUEST['pg_type'] ? sanitize_text_field($_REQUEST['pg_type']) : 'billing';
			
			$card_number = isset($_POST['cosmosfarm_members_subscription_checkout_card_number']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_card_number']) : '';
			$expiry = isset($_POST['cosmosfarm_members_subscription_checkout_expiry']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_expiry']) : '';
			$birth = isset($_POST['cosmosfarm_members_subscription_checkout_birth']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_birth']) : '';
			$pwd_2digit = isset($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']) ? sanitize_text_field($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']) : '';
			
			if($card_number && $expiry && $birth && $pwd_2digit){
				if($builtin_pg){
					$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>$pg_type));
				}
				else{
					$pg = cosmosfarm_members_load_pg('iamport');
				}
				
				$result = $pg->registerCard($card_number, $expiry, $birth, $pwd_2digit);
				$result->result = 'success';
			}
		}
		
		wp_send_json($result);
	}
	
	private function pre_subscription_request_pay(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		/*
		 * 보안상 민감한 정보는 삭제한다.
		 */
		unset($_POST['security']);
		unset($_POST['cosmosfarm_members_subscription_checkout_card_number']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_month']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_year']);
		unset($_POST['cosmosfarm_members_subscription_checkout_birth']);
		unset($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']);
		unset($_REQUEST['security']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_card_number']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_expiry_month']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_expiry_year']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_birth']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_pwd_2digit']);
		
		$_POST = stripslashes_deep($_POST);
		
		$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
		$sign_up_id = isset($_POST['sign_up_id']) ? sanitize_text_field($_POST['sign_up_id']) : '';
		$sign_up_pw = isset($_POST['sign_up_pw']) ? sanitize_text_field($_POST['sign_up_pw']) : '';
		
		if($sign_up_id && $sign_up_pw){
			// 먼저 기존 회원인지 확인한다.
			$user = wp_signon(array(
				'user_login'    => $sign_up_id,
				'user_password' => $sign_up_pw,
				'remember'      => true
			), is_ssl());
			
			if(is_wp_error($user)){
				// 기존 회원이 아니라면 새로 등록한다.
				$option = get_cosmosfarm_members_option();
				if($option->allow_email_login && strpos($sign_up_id, '@')){
					$user_id = wp_create_user($sign_up_id, $sign_up_pw, $sign_up_id);
				}
				else{
					$user_id = wp_create_user($sign_up_id, $sign_up_pw);
				}
				
				$user = new WP_User($user_id);
				wp_set_auth_cookie($user->ID, true, is_ssl());
				do_action('wp_login', $user->user_login, $user);
				
				$result = array('result'=>'success', 'message'=>__('You are registered.', 'cosmosfarm-members'));
			}
			else{
				$result = array('result'=>'success', 'message'=>__('You are logged in.', 'cosmosfarm-members'));
			}
			
			if($user && isset($user->ID) && $user->ID){
				wp_set_current_user($user->ID, $user->user_login);
			}
			else{
				$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
			}
		}
		else{
			if(!is_user_logged_in()){
				$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
			}
			else{
				$product = new Cosmosfarm_Members_Subscription_Product($product_id);
				
				if($product->ID()){
					do_action('cosmosfarm_members_pre_subscription_request_pay', $product);
					
					$result = array('result'=>'success', 'message'=>'');
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_pre_subscription_request_pay_result', $result);
		wp_send_json($result);
	}
	
	/**
	 * 결제창을 띄운다.
	 */
	private function subscription_request_pay_open_dialog(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		header('Content-Type: text/html; charset=UTF-8');
		
		$product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : '';
		$pg = isset($_REQUEST['pg']) ? sanitize_text_field($_REQUEST['pg']) : '';
		$pg_type = isset($_REQUEST['pg_type']) && $_REQUEST['pg_type'] ? sanitize_text_field($_REQUEST['pg_type']) : 'billing';
		
		$pg = cosmosfarm_members_load_pg($pg, array('pg_type'=>$pg_type));
		if($pg){
			$pg->open_dialog();
		}
		exit;
	}
	
	/**
	 * 결제창을 닫는다.
	 */
	private function subscription_request_pay_open_close(){
		header('Content-Type: text/html; charset=UTF-8');
		
		$product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : '';
		$pg = isset($_REQUEST['pg']) ? sanitize_text_field($_REQUEST['pg']) : '';
		$pg_type = isset($_REQUEST['pg_type']) && $_REQUEST['pg_type'] ? sanitize_text_field($_REQUEST['pg_type']) : 'billing';
		
		$pg = cosmosfarm_members_load_pg($pg, array('pg_type'=>$pg_type));
		if($pg){
			$pg->open_close();
		}
		exit;
	}
	
	/**
	 * PG사 연동 결제 완료
	 */
	private function subscription_request_pay_pg_callback(){
		header('Content-Type: text/html; charset=UTF-8');
		
		cosmosfarm_members_form_resubmit();
		
		$pg = isset($_REQUEST['pg']) ? sanitize_text_field($_REQUEST['pg']) : '';
		$pg_type = isset($_REQUEST['pg_type']) && $_REQUEST['pg_type'] ? sanitize_text_field($_REQUEST['pg_type']) : 'billing';
		
		$pg = cosmosfarm_members_load_pg($pg, array('pg_type'=>$pg_type));
		if($pg){
			$pg->callback();
		}
		exit;
	}
	
	/**
	 * 일반결제
	 */
	private function subscription_request_pay_complete(){
		header('Content-Type: text/html; charset=UTF-8');
		
		$user = wp_get_current_user();
		$order = new Cosmosfarm_Members_Subscription_Order();
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		$pay_success_url = esc_url_raw(home_url());
		
		$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
		$buyer_name = isset($_POST['buyer_name']) ? sanitize_text_field($_POST['buyer_name']) : '';
		$buyer_email = isset($_POST['buyer_email']) ? sanitize_text_field($_POST['buyer_email']) : '';
		$buyer_tel = isset($_POST['buyer_tel']) ? sanitize_text_field($_POST['buyer_tel']) : '';
		$addr1 = isset($_POST['addr1']) ? sanitize_text_field($_POST['addr1']) : '';
		$addr2 = isset($_POST['addr2']) ? sanitize_text_field($_POST['addr2']) : '';
		$zip = isset($_POST['zip']) ? sanitize_text_field($_POST['zip']) : '';
		
		$imp_uid = isset($_REQUEST['imp_uid']) ? sanitize_text_field($_REQUEST['imp_uid']) : '';
		$builtin_pg = isset($_REQUEST['builtin_pg']) ? sanitize_text_field($_REQUEST['builtin_pg']) : '';
		$pg_type = isset($_REQUEST['pg_type']) && $_REQUEST['pg_type'] ? sanitize_text_field($_REQUEST['pg_type']) : 'general';
		$display = isset($_REQUEST['display']) ? sanitize_text_field($_REQUEST['display']) : '';
		
		if($product_id && ($imp_uid || $builtin_pg)){
			check_ajax_referer("cosmosfarm-members-subscription-checkout-{$product_id}", 'checkout_nonce');
			
			if($imp_uid){
				$pg = cosmosfarm_members_load_pg('iamport');
				$payment = (array) $pg->getPayment($imp_uid);
			}
			else if($builtin_pg){
				$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>'general'));
				
				if($pg){
					$payment = (array) $pg->getPayment();
				}
				else{
					$payment = array();
					$payment['status'] = 'failed';
				}
			}
			
			$amount = isset($payment['amount']) ? intval($payment['amount']) : 0;
			$custom_data = isset($payment['custom_data']) ? (array) $payment['custom_data'] : array();
			$pay_success_url = isset($custom_data['pay_success_url']) ? esc_url_raw($custom_data['pay_success_url']) : $pay_success_url;
			$buyer_name = isset($payment['buyer_name']) ? sanitize_text_field($payment['buyer_name']) : $buyer_name;
			$buyer_email = isset($payment['buyer_email']) ? sanitize_text_field($payment['buyer_email']) : $buyer_email;
			$buyer_tel = isset($payment['buyer_tel']) ? sanitize_text_field($payment['buyer_tel']) : $buyer_tel;
			$buyer_addr = isset($payment['buyer_addr']) ? sanitize_text_field($payment['buyer_addr']) : (($addr1 && $addr2) ? trim("{$addr1} {$addr2}") : '');
			$buyer_postcode = isset($payment['buyer_postcode']) ? sanitize_text_field($payment['buyer_postcode']) : $zip;
			
			if(!is_user_logged_in()){
				$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
			}
			else if(isset($payment['error_message']) && $payment['error_message']){
				$result = array('result'=>'error', 'message'=>$payment['error_message']);
			}
			else if(isset($payment['status']) && $payment['status'] == 'paid'){
				$product = new Cosmosfarm_Members_Subscription_Product($product_id);
				
				// 적용된 쿠폰이 있는지 확인한다.
				$coupon = new Cosmosfarm_Members_Subscription_Coupon();
				$coupon_id = $coupon->get_save_coupon_id($user->ID, $product->ID());
				$coupon->init_with_id($coupon_id);
				
				// 쿠폰 설정에 따라서 가격을 변경한다.
				$coupon_price = $product->price();
				if($coupon->ID()){
					$coupon_price = $coupon->calculate($product->price());
				}
				
				if($product->ID() && $amount == $coupon_price){
					$meta_input = array(
						'name'           => $product->title(),
						'buyer_name'     => $buyer_name,
						'buyer_email'    => $buyer_email,
						'buyer_tel'      => $buyer_tel,
						'buyer_addr'     => $buyer_addr,
						'buyer_postcode' => $buyer_postcode,
						'imp_uid'        => isset($payment['imp_uid']) ? sanitize_text_field($payment['imp_uid']) : '',
						'builtin_pg'     => $builtin_pg,
						'builtin_pg_id'  => isset($payment['builtin_pg_id']) ? sanitize_text_field($payment['builtin_pg_id']) : '',
						'builtin_pg_tid' => isset($payment['builtin_pg_tid']) ? sanitize_text_field($payment['builtin_pg_tid']) : '',
						'merchant_uid'   => isset($payment['merchant_uid']) ? sanitize_text_field($payment['merchant_uid']) : '',
						'receipt_url'    => isset($payment['receipt_url']) ? sanitize_text_field($payment['receipt_url']) : '',
						'payment_method' => isset($custom_data['payment_method']) ? sanitize_text_field($custom_data['payment_method']) : '',
						'pg_type'        => $pg_type,
					);
					
					$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
					
					if($order->ID()){
						$order->set_sequence_id(uniqid());
						$order->set_status_paid();
						$order->set_product_id($product->ID());
						$order->set_price($product->price());
						$order->set_balance($product->price());
						$order->set_subscription_type($product->subscription_type());
						$order->set_subscription_active('');
						$order->set_pay_count('1');
						
						if($coupon->ID()){
							$order->set_coupon_id($coupon->ID());
							$order->set_coupon_price($coupon_price);
							
							$coupon_usage_count = $coupon->usage_count() + 1;
							$coupon->set_usage_count($coupon_usage_count);
							
							$coupon->save_coupon_id($user->ID, $product->ID(), '');
						}
						
						if($product->subscription_role()){
							$order->set_subscription_role($product->subscription_role());
							$order->set_subscription_prev_role($user->roles[0]);
							
							if(!is_super_admin($user->ID)){
								$user->remove_role($user->roles[0]);
								$user->add_role($product->subscription_role());
							}
						}
						
						$order->set_subscription_next('success');
						
						$next_datetime = $product->next_subscription_datetime();
						if($next_datetime){
							$order->set_subscription_next('wait');
							$order->set_start_datetime();
							$order->set_end_datetime($next_datetime);
						}
						
						if($product->is_use_earn_points()){
							if($product->earn_points_type() == 'reset'){
								$balance = mycred_get_users_balance($user->ID);
								mycred_add('cosmosfarm_subscription_earn_points', $user->ID, ($balance*-1), __('Reset Points', 'cosmosfarm-members'));
							}
							
							mycred_add('cosmosfarm_subscription_earn_points', $user->ID, $product->earn_points(), __('Earn Points', 'cosmosfarm-members'));
							
							$order->set_earn_points_type($product->earn_points_type());
							$order->set_earn_points($product->earn_points());
						}
						
						$order->update(array('content'=>sprintf('<strong>%s</strong> 결제되었습니다.', $product->title())));
						$order->update_meta_fields($product->fields(), $custom_data);
						
						do_action('cosmosfarm_members_subscription_request_pay', $order, $product, $custom_data);
						
						cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => sprintf('<strong>%s</strong> 결제되었습니다.', $product->title()),
							'meta_input' => array(
								'url'      => $payment['receipt_url'],
								'url_name' =>'영수증',
							),
						));
						$result = array('result'=>'success', 'message'=>'결제되었습니다. 고맙습니다.');
						
						$pg->updateCustomerUID();
					}
				}
			}
		}
		
		if(!$order->ID()){
			$result = array('result'=>'error', 'message'=>'결제에 실패했습니다.');
		}
		
		$result['pay_success_url'] = $pay_success_url;
		$result = apply_filters('cosmosfarm_members_subscription_request_pay_result', $result);
		
		if($display == 'pc'){
			wp_send_json($result);
		}
		else{
			$message = esc_js($result['message']);
			$pay_success_url = esc_url_raw($result['pay_success_url']);
			echo "<script>alert('{$message}');window.location.href='{$pay_success_url}';</script>";
			exit;
		}
	}
	
	/**
	 * 빌링결제
	 */
	private function subscription_request_pay(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		/*
		 * 보안상 민감한 정보는 삭제한다.
		 */
		unset($_POST['security']);
		unset($_POST['cosmosfarm_members_subscription_checkout_card_number']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_month']);
		unset($_POST['cosmosfarm_members_subscription_checkout_expiry_year']);
		unset($_POST['cosmosfarm_members_subscription_checkout_birth']);
		unset($_POST['cosmosfarm_members_subscription_checkout_pwd_2digit']);
		unset($_REQUEST['security']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_card_number']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_expiry_month']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_expiry_year']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_birth']);
		unset($_REQUEST['cosmosfarm_members_subscription_checkout_pwd_2digit']);
		
		$_POST = stripslashes_deep($_POST);
		
		$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
		$buyer_name = isset($_POST['buyer_name']) ? sanitize_text_field($_POST['buyer_name']) : '';
		$buyer_email = isset($_POST['buyer_email']) ? sanitize_text_field($_POST['buyer_email']) : '';
		$buyer_tel = isset($_POST['buyer_tel']) ? sanitize_text_field($_POST['buyer_tel']) : '';
		$addr1 = isset($_POST['addr1']) ? sanitize_text_field($_POST['addr1']) : '';
		$addr2 = isset($_POST['addr2']) ? sanitize_text_field($_POST['addr2']) : '';
		$zip = isset($_POST['zip']) ? sanitize_text_field($_POST['zip']) : '';
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			
			if($product->ID()){
				check_ajax_referer("cosmosfarm-members-subscription-checkout-{$product_id}", 'checkout_nonce');
				
				$meta_input = array(
					'name'           => $product->title(),
					'buyer_name'     => $buyer_name,
					'buyer_email'    => $buyer_email,
					'buyer_tel'      => $buyer_tel,
					'buyer_addr'     => (($addr1 && $addr2) ? trim("{$addr1} {$addr2}") : ''),
					'buyer_postcode' => $zip
				);
				
				$result = $this->subscription_request_pay_order($product, $meta_input, $_POST);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_subscription_request_pay_result', $result);
		wp_send_json($result);
	}
	
	/**
	 * 빌링결제 모바일
	 */
	private function subscription_request_pay_mobile(){
		header('Content-Type: text/html; charset=UTF-8');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		$pay_success_url = esc_url_raw(home_url());
		
		$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : '';
		$imp_uid = isset($_GET['imp_uid']) ? sanitize_text_field($_GET['imp_uid']) : '';
		$imp_success = isset($_GET['imp_success']) ? sanitize_text_field($_GET['imp_success']) : '';
		
		if($product_id && $imp_uid && $imp_success == 'true'){
			check_ajax_referer("cosmosfarm-members-subscription-checkout-{$product_id}", 'checkout_nonce');
			
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
			$api = new Cosmosfarm_Members_API_Iamport();
			$payment = (array) $api->getPayment($imp_uid);
			
			$amount = isset($payment['amount']) ? intval($payment['amount']) : 0;
			$custom_data = isset($payment['custom_data']) ? (array) $payment['custom_data'] : array();
			$pay_success_url = isset($custom_data['pay_success_url']) ? esc_url_raw($custom_data['pay_success_url']) : $pay_success_url;
			$buyer_name = isset($payment['buyer_name']) ? sanitize_text_field($payment['buyer_name']) : '';
			$buyer_email = isset($payment['buyer_email']) ? sanitize_text_field($payment['buyer_email']) : '';
			$buyer_tel = isset($payment['buyer_tel']) ? sanitize_text_field($payment['buyer_tel']) : '';
			$buyer_addr = isset($payment['buyer_addr']) ? sanitize_text_field($payment['buyer_addr']) : '';
			$buyer_postcode = isset($payment['buyer_postcode']) ? sanitize_text_field($payment['buyer_postcode']) : '';
			
			if(!is_user_logged_in()){
				$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
			}
			else if(isset($payment['error_message']) && $payment['error_message']){
				$result = array('result'=>'error', 'message'=>$payment['error_message']);
			}
			else if(isset($payment['status']) && $payment['status'] == 'paid'){
				$user = wp_get_current_user();
				$product = new Cosmosfarm_Members_Subscription_Product($product_id);
				
				// 주문이 저장되기 전에 첫 결제 무료 이용을 확인한다.
				$is_subscription_first_free = $product->is_subscription_first_free($user->ID, $custom_data);
				
				// 적용된 쿠폰이 있는지 확인한다.
				$coupon = new Cosmosfarm_Members_Subscription_Coupon();
				$coupon_id = $coupon->get_save_coupon_id($user->ID, $product->ID());
				$coupon->init_with_id($coupon_id);
				
				// 쿠폰 설정에 따라서 가격을 변경한다.
				$coupon_price = $product->first_price();
				if($coupon->ID()){
					$coupon_price = $coupon->calculate($product->first_price());
				}
				
				if($product->ID() && $amount == $coupon_price){
					$meta_input = array(
						'name'           => $product->title(),
						'buyer_name'     => $buyer_name,
						'buyer_email'    => $buyer_email,
						'buyer_tel'      => $buyer_tel,
						'buyer_addr'     => $buyer_addr,
						'buyer_postcode' => $buyer_postcode
					);
					
					$result = $this->subscription_request_pay_order($product, $meta_input, $custom_data);
				}
			}
		}
		
		$result['pay_success_url'] = $pay_success_url;
		$result = apply_filters('cosmosfarm_members_subscription_request_pay_result', $result);
		
		$message = esc_js($result['message']);
		$pay_success_url = esc_url_raw($result['pay_success_url']);
		echo "<script>alert('{$message}');window.location.href='{$pay_success_url}';</script>";
		exit;
	}
	
	/**
	 * 빌링결제 주문정보 생성
	 * @param Cosmosfarm_Members_Subscription_Product $product
	 * @param array $meta_input
	 * @param array $custom_data
	 * @return string[]
	 */
	private function subscription_request_pay_order($product, $meta_input, $custom_data){
		if($product->ID()){
			$user = wp_get_current_user();
			$order = new Cosmosfarm_Members_Subscription_Order();
			$option = get_cosmosfarm_members_option();
			
			// 주문이 저장되기 전에 첫 결제 무료 이용을 확인한다.
			$is_subscription_first_free = $product->is_subscription_first_free($user->ID, $custom_data);
			
			// 적용된 쿠폰이 있는지 확인한다.
			$coupon = new Cosmosfarm_Members_Subscription_Coupon();
			$coupon_id = $coupon->get_save_coupon_id($user->ID, $product->ID());
			$coupon->init_with_id($coupon_id);
			
			// 쿠폰 설정에 따라서 가격을 변경한다.
			$before_coupon_price = $product->first_price();
			$coupon_price = $before_coupon_price;
			if($coupon->ID()){
				$coupon_price = $coupon->calculate($product->first_price());
			}
			
			$imp_uid = isset($_REQUEST['imp_uid']) ? sanitize_text_field($_REQUEST['imp_uid']) : '';
			$builtin_pg = isset($_REQUEST['builtin_pg']) ? sanitize_text_field($_REQUEST['builtin_pg']) : '';
			$pg_type = isset($_REQUEST['pg_type']) && $_REQUEST['pg_type'] ? sanitize_text_field($_REQUEST['pg_type']) : 'billing';
			
			if($imp_uid){
				$pg = cosmosfarm_members_load_pg('iamport');
				$customer_uid = $pg->getCustomerUID();
			}
			else if($builtin_pg){
				$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>$pg_type));
				if($pg){
					$customer_uid = $pg->getCustomerUID();
				}
			}
			
			if($coupon_price){
				if($imp_uid){
					$is_getpayment = false;
					
					if($imp_uid && in_array($option->subscription_pg, array('kakao', 'kakaopay', 'jtnet', 'danal_tpay', 'danal'))){
						$is_getpayment = true;
					}
					
					$iamport_pg_list = get_cosmosfarm_members_subscription_iamport_pg_list();
					if($iamport_pg_list && isset($custom_data['payment_method'])){
						if(isset($iamport_pg_list[$custom_data['payment_method']]->is_getpayment) && $iamport_pg_list[$custom_data['payment_method']]->is_getpayment){
							$is_getpayment = true;
						}
					}
					
					if($is_getpayment){
						$subscribe_result = $pg->getPayment($imp_uid);
					}
					else{
						$subscribe_result = $pg->subscribe($customer_uid, $coupon_price, $meta_input);
					}
				}
				else if($builtin_pg){
					if($pg){
						$subscribe_result = $pg->subscribe($customer_uid, $coupon_price, $meta_input);
					}
					else{
						$subscribe_result = new stdClass();
						$subscribe_result->status = 'failed';
					}
				}
			}
			else{
				$subscribe_result = new stdClass();
				$subscribe_result->status = 'paid';
			}
			
			if($subscribe_result->status == 'paid'){
				$meta_input['imp_uid'] = isset($subscribe_result->imp_uid) ? $subscribe_result->imp_uid : '';
				$meta_input['builtin_pg'] = $builtin_pg;
				$meta_input['builtin_pg_id'] = isset($subscribe_result->builtin_pg_id) ? $subscribe_result->builtin_pg_id : '';
				$meta_input['builtin_pg_tid'] = isset($subscribe_result->builtin_pg_tid) ? $subscribe_result->builtin_pg_tid : '';
				$meta_input['merchant_uid'] = isset($subscribe_result->merchant_uid) ? $subscribe_result->merchant_uid : '';
				$meta_input['receipt_url'] = isset($subscribe_result->receipt_url) ? $subscribe_result->receipt_url : '';
				$meta_input['payment_method'] = isset($custom_data['payment_method']) ? sanitize_text_field($custom_data['payment_method']) : '';
				$meta_input['pg_type'] = $pg_type;
				
				$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
				
				if($order->ID()){
					$order->set_sequence_id(uniqid());
					$order->set_status_paid();
					$order->set_product_id($product->ID());
					$order->set_price($coupon_price);
					$order->set_balance($coupon_price);
					$order->set_first_price($product->first_price());
					$order->set_pay_count_limit($product->pay_count_limit());
					$order->set_subscription_type($product->subscription_type());
					$order->set_subscription_active($product->subscription_active());
					$order->set_subscription_again_price_type($product->subscription_again_price_type());
					
					if(isset($customer_uid) && $customer_uid){
						$order->set_iamport_customer_uid($customer_uid);
					}
					
					if($coupon->ID()){
						$order->set_coupon_id($coupon->ID());
						$order->set_coupon_price($coupon_price);
						$order->set_before_coupon_price($before_coupon_price);
						
						$coupon_usage_count = $coupon->usage_count() + 1;
						$coupon->set_usage_count($coupon_usage_count);
						
						$coupon->save_coupon_id($user->ID, $product->ID(), '');
					}
					
					$pay_count = 1;
					$order->set_pay_count($pay_count);
					
					if($product->subscription_role()){
						$order->set_subscription_role($product->subscription_role());
						
						// 현재 주문의 경우 'success', 'wait' 상태가 입력되어 있지 않기 때문에 last_order로 반환되지 않음
						$last_order = cosmosfarm_members_subscription_last_order($user->ID);
						if($last_order->ID()){
							$order->set_subscription_prev_role($last_order->subscription_prev_role());
						}
						else{
							$order->set_subscription_prev_role($user->roles[0]);
						}
						
						if(!is_super_admin($user->ID)){
							$user->remove_role($user->roles[0]);
							$user->add_role($product->subscription_role());
						}
					}
					
					$order->set_subscription_next('success');
					
					if($is_subscription_first_free){
						$next_datetime = $product->next_subscription_datetime_first_free();
						if($next_datetime){
							$order->set_subscription_next('wait');
							$order->set_start_datetime();
							$order->set_end_datetime($next_datetime);
							
							// 첫 결제 무료 이용기간이 있을 경우 결제 성공 후 취소한다.
							if($subscribe_result->imp_uid){
								$cancel_result = $pg->cancel($subscribe_result->imp_uid);
							}
							else{
								$pg->set_order($order);
								$cancel_result = $pg->cancel($subscribe_result->builtin_pg_tid);
							}
							
							$order->set_price(0);
							$order->set_balance(0);
							$order->set_subscription_first_free('1');
						}
					}
					else{
						$next_datetime = $product->next_subscription_datetime();
						if($next_datetime){
							$order->set_subscription_next('wait');
							$order->set_start_datetime();
							$order->set_end_datetime($next_datetime);
						}
					}
					
					if($product->is_use_earn_points()){
						if($product->earn_points_type() == 'reset'){
							$balance = mycred_get_users_balance($user->ID);
							mycred_add('cosmosfarm_subscription_earn_points', $user->ID, ($balance*-1), __('Reset Points', 'cosmosfarm-members'));
						}
						
						mycred_add('cosmosfarm_subscription_earn_points', $user->ID, $product->earn_points(), __('Earn Points', 'cosmosfarm-members'));
						
						$order->set_earn_points_type($product->earn_points_type());
						$order->set_earn_points($product->earn_points());
					}
					
					if($product->subscription_active()){
						$content = sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 1회차]', $product->title());
					}
					else{
						$content = sprintf('<strong>%s</strong> 결제되었습니다.', $product->title());
					}
					
					$order->update(array('content'=>$content));
					$order->update_meta_fields($product->fields(), $custom_data);
					
					do_action('cosmosfarm_members_subscription_request_pay', $order, $product, $custom_data);
					
					if(isset($subscribe_result->receipt_url)){
						cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => $content,
							'meta_input' => array(
								'url'      => $subscribe_result->receipt_url,
								'url_name' =>'영수증',
							)
						));
					}
					else{
						cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => $content
						));
					}
					
					$result = array('result'=>'success', 'message'=>'결제되었습니다. 고맙습니다.');
					
					if(isset($pg)) $pg->updateCustomerUID();
				}
			}
		}
		
		if(!$order->ID()){
			if(isset($subscribe_result->error_message) && $subscribe_result->error_message){
				$result = array('result'=>'error', 'message'=>$subscribe_result->error_message);
			}
			else{
				$result = array('result'=>'error', 'message'=>'결제에 실패했습니다.');
			}
		}
		
		return $result;
	}
	
	/**
	 * 빌링결제 자동결제 실행
	 * @param int $order_id
	 */
	public function subscription_again($order_id){
		$order_id = intval($order_id);
		$old_order = new Cosmosfarm_Members_Subscription_Order($order_id);
		
		if($old_order->ID() && $old_order->product_id() && $old_order->end_datetime() <= date('YmdHis', current_time('timestamp'))){
			$old_order->set_subscription_next('expiry');
			
			$user = $old_order->user();
			$product = new Cosmosfarm_Members_Subscription_Product($old_order->product_id());
			$order = new Cosmosfarm_Members_Subscription_Order();
			
			$meta_input = array(
				'name'           => $product->title(),
				'buyer_name'     => $old_order->buyer_name,
				'buyer_email'    => $old_order->buyer_email,
				'buyer_tel'      => $old_order->buyer_tel,
				'buyer_addr'     => (($old_order->addr1 || $old_order->addr2) ? trim("{$old_order->addr1} {$old_order->addr2}") : ''),
				'buyer_postcode' => $old_order->zip
			);
			
			if(!$user || !$user->ID){
				do_action('cosmosfarm_members_subscription_again_failure', $old_order, $product);
				
				$old_order->set_error_message('사용자 정보가 없습니다.');
				$old_order->show_error_message_admin_notice();
				$old_order->set_subscription_again_error();
			}
			else{
				$pay_count_limit = true;
				if($product->pay_count_limit()){
					if($old_order->pay_count() >= $product->pay_count_limit()){
						$pay_count_limit = false;
					}
				}
				
				$pay_count_limit = apply_filters('cosmosfarm_members_subscription_pay_count_limit', $pay_count_limit, $old_order, $product);
				
				if($product->ID() && $product->subscription_active() && $old_order->subscription_active() && $old_order->is_paid() && $pay_count_limit){
					
					do_action('cosmosfarm_members_pre_subscription_again', $old_order, $product);
					
					if($product->subscription_again_price_type() == 'old_order'){
						if($old_order->pay_count() == 1 && $old_order->subscription_first_free()){
							$again_price = $product->price();
						}
						else if($old_order->coupon_id() && $old_order->before_coupon_price()){
							$again_price = $old_order->before_coupon_price();
						}
						else{
							$again_price = $old_order->price();
						}
					}
					else{
						$again_price = $product->price();
					}
					
					// 적용된 쿠폰이 있는지 확인한다.
					$coupon_id = $old_order->coupon_id();
					$coupon = new Cosmosfarm_Members_Subscription_Coupon();
					$coupon->init_with_id($coupon_id);
					
					// 쿠폰 설정에 따라서 가격을 변경한다.
					$before_coupon_price = $again_price;
					if($coupon->ID() && $coupon->discount_cycle() == 'subscription'){
						$again_price = $coupon->calculate($again_price);
					}
					
					$again_price = apply_filters('cosmosfarm_members_subscription_again_price', $again_price, $old_order, $product);
					
					if($again_price){
						/*
						 * 결제 건에 맞는 PG사 설정 로딩
						 */
						$builtin_pg = $old_order->builtin_pg();
						if($builtin_pg){
							$pg = cosmosfarm_members_load_pg($builtin_pg, array('pg_type'=>'billing'));
						}
						else{
							$pg = cosmosfarm_members_load_pg('iamport');
						}
						
						/*
						 * PG사 설정 로딩 후 재결제 실행
						 */
						if($pg){
							$customer_uid = $old_order->iamport_customer_uid();
							if(!$customer_uid){
								$customer_uid = $pg->getCustomerUID($user->ID);
							}
							
							$subscribe_result = $pg->subscribe($customer_uid, $again_price, $meta_input);
						}
						else{
							$subscribe_result = new stdClass();
							$subscribe_result->status = 'failed';
						}
					}
					else{
						$subscribe_result = new stdClass();
						$subscribe_result->status = 'paid';
					}
					
					/*
					 * 재결제 성공 후 실행
					 */
					if($subscribe_result->status == 'paid'){
						$meta_input['imp_uid'] = isset($subscribe_result->imp_uid) ? $subscribe_result->imp_uid : '';
						$meta_input['builtin_pg'] = $builtin_pg;
						$meta_input['builtin_pg_id'] = isset($subscribe_result->builtin_pg_id) ? $subscribe_result->builtin_pg_id : '';
						$meta_input['builtin_pg_tid'] = isset($subscribe_result->builtin_pg_tid) ? $subscribe_result->builtin_pg_tid : '';
						$meta_input['merchant_uid'] = isset($subscribe_result->merchant_uid) ? $subscribe_result->merchant_uid : '';
						$meta_input['receipt_url'] = isset($subscribe_result->receipt_url) ? $subscribe_result->receipt_url : '';
						$meta_input['payment_method'] = isset($custom_data['payment_method']) ? sanitize_text_field($custom_data['payment_method']) : '';
						$meta_input['pg_type'] = $old_order->get_pg_type();
						
						$order->create($user->ID, array('title'=>$product->title(), 'meta_input'=>$meta_input));
						
						if($order->ID()){
							$order->set_sequence_id($old_order->sequence_id());
							$order->set_status_paid();
							$order->set_product_id($product->ID());
							$order->set_price($again_price);
							$order->set_balance($again_price);
							$order->set_first_price($product->first_price());
							$order->set_pay_count_limit($product->pay_count_limit());
							$order->set_subscription_type($product->subscription_type());
							$order->set_subscription_active($product->subscription_active());
							$order->set_subscription_again_price_type($product->subscription_again_price_type());
							
							if(isset($customer_uid) && $customer_uid){
								$order->set_iamport_customer_uid($customer_uid);
							}
							
							if($coupon->ID() && $coupon->discount_cycle() == 'subscription'){
								$order->set_coupon_id($coupon->ID());
								$order->set_coupon_price($again_price);
								$order->set_before_coupon_price($before_coupon_price);
								
								//$coupon_usage_count = $coupon->usage_count() + 1;
								//$coupon->set_usage_count($coupon_usage_count);
								
								$coupon->save_coupon_id($user->ID, $product->ID(), '');
							}
							
							$pay_count = $old_order->pay_count() + 1;
							$order->set_pay_count($pay_count);
							
							if($product->subscription_role()){
								$order->set_subscription_role($product->subscription_role());
								
								$subscription_prev_role = $old_order->subscription_prev_role();
								$order->set_subscription_prev_role($subscription_prev_role ? $subscription_prev_role : $user->roles[0]);
								
								if(!is_super_admin($user->ID)){
									$user->remove_role($user->roles[0]);
									$user->add_role($product->subscription_role());
								}
							}
							else{
								if(!is_super_admin($user->ID)){
									$user->remove_role($user->roles[0]);
									$user->add_role(get_option('default_role'));
								}
							}
							
							$order->set_subscription_next('success');
							$next_datetime = $product->next_subscription_datetime($old_order->end_datetime());
							if($next_datetime){
								$order->set_subscription_next('wait');
								$order->set_start_datetime();
								$order->set_end_datetime($next_datetime);
							}
							
							if($product->is_use_earn_points()){
								if($product->earn_points_type() == 'reset'){
									$balance = mycred_get_users_balance($user->ID);
									mycred_add('cosmosfarm_subscription_earn_points', $user->ID, ($balance*-1), __('Reset Points', 'cosmosfarm-members'));
								}
								
								mycred_add('cosmosfarm_subscription_earn_points', $user->ID, $product->earn_points(), __('Earn Points', 'cosmosfarm-members'));
								
								$order->set_earn_points_type($product->earn_points_type());
								$order->set_earn_points($product->earn_points());
							}
							
							$order->update(array('content'=>sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 %d회차]', $product->title(), $pay_count)));
							$order->update_meta_fields($product->fields(), get_post_meta($old_order->ID()));
							
							do_action('cosmosfarm_members_subscription_again_success', $order, $product, $old_order);
							
							cosmosfarm_members_send_notification(array(
								'to_user_id' => $user->ID,
								'content'    => sprintf('<strong>%s</strong> 결제되었습니다. [정기결제 %d회차]', $product->title(), $pay_count),
								'meta_input' => array(
									'url'      => $subscribe_result->receipt_url,
									'url_name' => '영수증',
								),
							));
						}
					}
					
					if(!$order->ID()){
						if($old_order->subscription_prev_role()){
							if(!is_super_admin($user->ID)){
								$user->remove_role($user->roles[0]);
								$user->add_role($old_order->subscription_prev_role());
							}
						}
						
						do_action('cosmosfarm_members_subscription_again_failure', $old_order, $product);
						
						$notification_id = cosmosfarm_members_send_notification(array(
							'to_user_id' => $user->ID,
							'content'    => sprintf('<strong>%s</strong> 결제에 실패했습니다. [%s]', $product->title(), $subscribe_result->error_message),
						));
						
						if(!$notification_id){
							$message = sprintf('<h1>%s 결제에 실패했습니다.</h1>', $product->title());
							$message .= sprintf('<hr style="border: 0;border-bottom: 1px solid #f6f6f6;margin: 20px 0;">');
							$message .= sprintf('<p>안녕하세요.</p>');
							$message .= sprintf('<p>%s 결제에 실패 했음을 알려드립니다.</p>', $product->title());
							$message .= sprintf('<p>실패 메시지 : <strong>%s</strong></p>', $subscribe_result->error_message);
							$message .= sprintf('<p>지금 <a href="%s" target="_blank">웹사이트</a>에 접속 후 다시 결제를 진행해주세요.</p>', home_url());
							$message .= sprintf('<p>경우에 따라서는 웹사이트 관리자에게 문의해서 문제를 해결해야 합니다.</p>');
							$message .= sprintf('<p>고맙습니다.</p>');
							
							$mail = new Cosmosfarm_Members_Mail();
							$mail->send(apply_filters('cosmosfarm_members_subscription_again_failure_email_args', array(
								'to' => $user->user_email,
								'subject' => sprintf('%s 결제에 실패했습니다. 확인해주세요.', $product->title()),
								'message' => $message,
							), $old_order, $product, $subscribe_result));
						}
						
						$old_order->set_error_message($subscribe_result->error_message);
						$old_order->show_error_message_admin_notice();
						$old_order->set_subscription_again_error();
					}
				}
				else{
					if($old_order->subscription_prev_role()){
						$last_order = cosmosfarm_members_subscription_last_order($user->ID);
						if(!is_super_admin($user->ID) && !$last_order->ID()){
							$user->remove_role($user->roles[0]);
							$user->add_role($old_order->subscription_prev_role());
						}
					}
					
					do_action('cosmosfarm_members_subscription_expiry', $old_order, $product);
					
					cosmosfarm_members_send_notification(array(
						'to_user_id' => $user->ID,
						'content'    => sprintf('<strong>%s</strong> 만료되었습니다. [만료]', $product->title()),
					));
				}
			}
		}
	}
	
	/**
	 * 빌링결제 정보 업데이트
	 */
	private function subscription_update(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$subscription_active = isset($_POST['subscription_active']) && $_POST['subscription_active'] ? '1' : '';
			$order = new Cosmosfarm_Members_Subscription_Order($order_id);
			
			if($order->ID() && $order->user_id()){
				if($order->user_id() == get_current_user_id()){
					$product_id = $order->product_id();
					$product = new Cosmosfarm_Members_Subscription_Product($product_id);
					
					if(!$product->is_subscription_user_update()){
						$result = array('result'=>'error', 'message'=>$product->get_subscription_user_update_message());
					}
					else if(!$subscription_active && $product->get_subscription_deactivate_pay_count() > $order->pay_count()){
						$subscription_deactivate_pay_count_message = $product->get_subscription_deactivate_pay_count_message();
						
						if(strpos($subscription_deactivate_pay_count_message, '%d') !== false){
							$result = array('result'=>'error', 'message'=>sprintf($product->get_subscription_deactivate_pay_count_message(), $product->get_subscription_deactivate_pay_count()));
						}
						else{
							$result = array('result'=>'error', 'message'=>$product->get_subscription_deactivate_pay_count_message());
						}
					}
					else{
						$order->set_subscription_active($subscription_active);
						$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'order_id'=>$order_id, 'subscription_active'=>$subscription_active);
					}
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_subscription_update_result', $result);
		wp_send_json($result);
	}
	
	private function exists_check(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$meta_key = isset($_POST['meta_key']) ? sanitize_text_field($_POST['meta_key']) : '';
		$meta_value = isset($_POST['meta_value']) ? sanitize_text_field($_POST['meta_value']) : '';
		
		if(is_user_logged_in()){
			$user_id = get_current_user_id();
		}
		else{
			$user_id = 0;
		}
		
		$exists = cosmosfarm_members_user_value_exists($meta_key, $meta_value, $user_id);
		
		if($exists){
			$message = __('Already in use.', 'cosmosfarm-members');
		}
		else{
			$message = __('Available.', 'cosmosfarm-members');
		}
		
		$result = array('exists'=>$exists, 'meta_key'=>$meta_key, 'meta_value'=>$meta_value, 'message'=>$message);
		
		$wpmem_fields = wpmem_fields();
		if(isset($wpmem_fields[$meta_key]['type']) && $wpmem_fields[$meta_key]['type'] == 'email'){
			if(!is_email($meta_value)){
				$result['exists'] = true;
				$result['message'] = __('You must enter a valid email address.', 'cosmosfarm-members');
			}
		}
		
		$result = apply_filters('cosmosfarm_members_exists_check_result', $result);
		
		wp_send_json($result);
	}
	
	private function notifications_read(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$notification = new Cosmosfarm_Members_Notification($post_id);
			
			if($notification->user_id == get_current_user_id()){
				$notification->read();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_notifications_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_read_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_unread(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$notification = new Cosmosfarm_Members_Notification($post_id);
			
			if($notification->user_id == get_current_user_id()){
				$notification->unread();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_notifications_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_unread_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_delete(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$notification = new Cosmosfarm_Members_Notification($post_id);
			
			if($notification->user_id == get_current_user_id()){
				$notification->delete();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_notifications_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been deleted.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_delete_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_subnotify_update(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$user_id = get_current_user_id();
			$notifications_subnotify_email = isset($_POST['notifications_subnotify_email']) ? intval($_POST['notifications_subnotify_email']) : 0;
			$notifications_subnotify_sms = isset($_POST['notifications_subnotify_sms']) ? intval($_POST['notifications_subnotify_sms']) : 0;
			
			if($notifications_subnotify_email){
				update_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_email', $notifications_subnotify_email);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_email');
			}
			
			if($notifications_subnotify_sms){
				update_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_sms', $notifications_subnotify_sms);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_notifications_subnotify_sms');
			}
			
			$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'));
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_subnotify_update_result', $result);
		wp_send_json($result);
	}
	
	private function notifications_send(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$to_user_id = isset($_POST['to_user_id']) ? intval($_POST['to_user_id']) : 0;
			$title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
			$content = isset($_POST['content']) ? sanitize_text_field($_POST['content']) : '';
			
			if(!$to_user_id){
				$result = array('result'=>'error', 'message'=>__('Recipient is required.', 'cosmosfarm-members'));
			}
			else if(!$content){
				$result = array('result'=>'error', 'message'=>__('Content is required.', 'cosmosfarm-members'));
			}
			else{
				$post_id = cosmosfarm_members_send_notification(array(
					'from_user_id' => get_current_user_id(),
					'to_user_id'   => $to_user_id,
					'title'        => $title,
					'content'      => $content
				));
				
				if($post_id){
					$result = array('result'=>'success', 'message'=>__('A notification has been sent.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>__('Notification failed to send.', 'cosmosfarm-members'));
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_notifications_send_result', $result);
		wp_send_json($result);
	}
	
	private function messages_read(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$message = new Cosmosfarm_Members_Message($post_id);
			
			if($message->user_id == get_current_user_id()){
				$message->read();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_messages_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_read_result', $result);
		wp_send_json($result);
	}
	
	private function messages_unread(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$message = new Cosmosfarm_Members_Message($post_id);
			
			if($message->user_id == get_current_user_id()){
				$message->unread();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_messages_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_unread_result', $result);
		wp_send_json($result);
	}
	
	private function messages_delete(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
			$message = new Cosmosfarm_Members_Message($post_id);
			
			if($message->user_id == get_current_user_id()){
				$message->delete();
				$unread_count = intval(get_user_meta(get_current_user_id(),  'cosmosfarm_members_unread_messages_count', true));
				$result = array('result'=>'success', 'message'=>__('Has been deleted.', 'cosmosfarm-members'), 'unread_count'=>$unread_count);
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_delete_result', $result);
		wp_send_json($result);
	}
	
	private function messages_subnotify_update(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$user_id = get_current_user_id();
			$messages_subnotify_email = isset($_POST['messages_subnotify_email']) ? intval($_POST['messages_subnotify_email']) : 0;
			$messages_subnotify_sms = isset($_POST['messages_subnotify_sms']) ? intval($_POST['messages_subnotify_sms']) : 0;
			$messages_subnotify_alimtalk = isset($_POST['messages_subnotify_alimtalk']) ? intval($_POST['messages_subnotify_alimtalk']) : 0;
			
			if($messages_subnotify_email){
				update_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_email', $messages_subnotify_email);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_email');
			}
			
			if($messages_subnotify_sms){
				update_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_sms', $messages_subnotify_sms);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_sms');
			}
			
			if($messages_subnotify_alimtalk){
				update_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_alimtalk', $messages_subnotify_alimtalk);
			}
			else{
				delete_user_meta($user_id, 'cosmosfarm_members_messages_subnotify_alimtalk');
			}
			
			$result = array('result'=>'success', 'message'=>__('Has been changed.', 'cosmosfarm-members'));
		}
		
		$result = apply_filters('cosmosfarm_members_messages_subnotify_update_result', $result);
		wp_send_json($result);
	}
	
	private function messages_send(){
		check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
		
		$result = array('result'=>'error', 'message'=>__('You do not have permission.', 'cosmosfarm-members'));
		
		$_POST = stripslashes_deep($_POST);
		
		if(!is_user_logged_in()){
			$result = array('result'=>'error', 'message'=>__('Please Log in to continue.', 'cosmosfarm-members'));
		}
		else{
			$to_user_id = isset($_POST['to_user_id']) ? intval($_POST['to_user_id']) : 0;
			$title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
			$content = isset($_POST['content']) ? sanitize_textarea_field($_POST['content']) : '';
			
			if(!$to_user_id){
				$result = array('result'=>'error', 'message'=>__('Recipient is required.', 'cosmosfarm-members'));
			}
			else if(!$content){
				$result = array('result'=>'error', 'message'=>__('Content is required.', 'cosmosfarm-members'));
			}
			else{
				$post_id = cosmosfarm_members_send_message(array(
					'from_user_id' => get_current_user_id(),
					'to_user_id'   => $to_user_id,
					'title'        => $title,
					'content'      => $content
				));
				
				if($post_id){
					$result = array('result'=>'success', 'message'=>__('Your message has been sent.', 'cosmosfarm-members'));
				}
				else{
					$result = array('result'=>'error', 'message'=>__('Message failed to send.', 'cosmosfarm-members'));
				}
			}
		}
		
		$result = apply_filters('cosmosfarm_members_messages_send_result', $result);
		wp_send_json($result);
	}
}