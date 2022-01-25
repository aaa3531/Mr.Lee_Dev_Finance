<?php
/**
 * Cosmosfarm_Members_Option
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Option {
	
	var $skin;
	var $channel;
	var $social_login_active;
	var $allow_email_login;
	var $login_redirect_page;
	var $login_redirect_url;
	var $naver_client_id;
	var $naver_client_secret;
	var $facebook_client_id;
	var $facebook_client_secret;
	var $kakao_client_id;
	var $google_client_id;
	var $google_client_secret;
	var $twitter_client_id;
	var $twitter_client_secret;
	var $instagram_client_id;
	var $instagram_client_secret;
	var $line_client_id;
	var $line_client_secret;
	var $login_page_id;
	var $login_page_url;
	var $register_page_id;
	var $register_page_url;
	var $account_page_id;
	var $account_page_url;
	var $user_required;
	var $verify_email;
	var $verify_email_title;
	var $verify_email_content;
	var $confirmed_email;
	var $confirmed_email_title;
	var $confirmed_email_content;
	var $page_restriction_redirect;
	var $page_restriction_message;
	var $page_restriction_alert_message;
	var $page_restriction_permission_message;
	var $postcode_service_disabled;
	var $use_postcode_service_iframe;
	var $social_buttons_shortcode_display;
	var $change_role_active;
	var $change_role_thresholds;
	var $use_delete_account;
	
	var $use_strong_password;
	var $save_login_history;
	var $use_login_protect;
	var $use_dormant_member;
	var $dormant_member_email_title;
	var $dormant_member_email_message;
	var $login_protect_time;
	var $login_protect_count;
	var $login_protect_lockdown;
	var $use_login_timeout;
	var $login_timeout;
	var $save_activity_history;
	var $active_closed_site;
	var $login_otp_phone_number;
	var $login_otp_user_roles;
	
	var $builtin_pg;
	var $iamport_id;
	var $iamport_api_key;
	var $iamport_api_secret;
	var $iamport_pg_mid;
	var $use_certification;
	var $certification_min_age;
	var $certification_name_field;
	var $certification_gender_field;
	var $certification_birth_field;
	var $certification_carrier_field;
	var $certification_phone_field;
	
	var $exists_check;
	
	var $sms_service;
	var $sms_caller1;
	var $sms_caller2;
	var $sms_caller3;
	var $sms_cafe24_id;
	var $sms_cafe24_secret;
	var $sms_toast_cloud_appkey;
	var $alimtalk_service;
	var $alimtalk_appkey;
	var $alimtalk_secretkey;
	var $alimtalk_plusfriend_id;
	
	var $notifications_page_id;
	var $notifications_kboard;
	var $notifications_kboard_comments;
	var $notifications_subnotify_email;
	var $notifications_subnotify_sms;
	var $notifications_subnotify_email_title;
	var $notifications_subnotify_email_content;
	var $notifications_subnotify_sms_message;
	var $notifications_subnotify_alimtalk_template;
	var $messages_page_id;
	var $messages_subnotify_email;
	var $messages_subnotify_sms;
	var $messages_subnotify_alimtalk;
	var $messages_subnotify_email_title;
	var $messages_subnotify_email_content;
	var $messages_subnotify_sms_message;
	var $messages_subnotify_alimtalk_template;
	var $subnotify_sms_field;
	var $users_page_id;
	var $user_profile_page_id;
	
	var $bulk_sms_permission_roles;
	var $bulk_sms_content;
	
	var $alimtalk_template;
	
	var $subscription_pg_type;
	var $subscription_pg;
	var $subscription_general_pg;
	var $subscription_checkout_page_id;
	var $subscription_checkout_view_mode;
	var $subscription_orders_page_id;
	var $subscription_product_list_page_id;
	var $subscription_cancellation_refund_policy_page_id;
	var $subscription_payment_completed_page_id;
	var $subscription_courier_company;
	
	var $mailchimp_api_key;
	var $mailchimp_list_id;
	var $mailchimp_field;
	
	public function __construct(){
		$this->channel = array(
			'naver' => __('Naver', 'cosmosfarm-members'),
			'facebook' => __('Facebook', 'cosmosfarm-members'),
			'kakao' => __('Kakao', 'cosmosfarm-members'),
			'google' => __('Google', 'cosmosfarm-members'),
			'twitter' => __('Twitter', 'cosmosfarm-members'),
			'instagram' => __('Instagram', 'cosmosfarm-members'),
			'line' => __('Line', 'cosmosfarm-members'),
		);
		$this->init();
	}
	
	public function init(){
		$this->skin = get_option('cosmosfarm_members_skin', 'default');
		$this->social_login_active = get_option('cosmosfarm_members_social_login_active', array());
		$this->allow_email_login = get_option('cosmosfarm_members_allow_email_login', '');
		$this->login_redirect_page = get_option('cosmosfarm_members_login_redirect_page', '');
		$this->login_redirect_url = get_option('cosmosfarm_members_login_redirect_url', '');
		$this->naver_client_id = get_option('cosmosfarm_members_naver_client_id', '');
		$this->naver_client_secret = get_option('cosmosfarm_members_naver_client_secret', '');
		$this->facebook_client_id = get_option('cosmosfarm_members_facebook_client_id', '');
		$this->facebook_client_secret = get_option('cosmosfarm_members_facebook_client_secret', '');
		$this->kakao_client_id = get_option('cosmosfarm_members_kakao_client_id', '');
		$this->google_client_id = get_option('cosmosfarm_members_google_client_id', '');
		$this->google_client_secret = get_option('cosmosfarm_members_google_client_secret', '');
		$this->twitter_client_id = get_option('cosmosfarm_members_twitter_client_id', '');
		$this->twitter_client_secret = get_option('cosmosfarm_members_twitter_client_secret', '');
		$this->instagram_client_id = get_option('cosmosfarm_members_instagram_client_id', '');
		$this->instagram_client_secret = get_option('cosmosfarm_members_instagram_client_secret', '');
		$this->line_client_id = get_option('cosmosfarm_members_line_client_id', '');
		$this->line_client_secret = get_option('cosmosfarm_members_line_client_secret', '');
		$this->login_page_id = get_option('cosmosfarm_members_login_page_id', '');
		$this->login_page_url = get_option('cosmosfarm_members_login_page_url', '');
		$this->register_page_id = get_option('cosmosfarm_members_register_page_id', '');
		$this->register_page_url = get_option('cosmosfarm_members_register_page_url', '');
		$this->account_page_id = get_option('cosmosfarm_members_account_page_id', '');
		$this->account_page_url = get_option('cosmosfarm_members_account_page_url', '');
		$this->user_required = get_option('cosmosfarm_members_user_required', '');
		$this->verify_email = get_option('cosmosfarm_members_verify_email', '');
		$this->verify_email_title = stripslashes(get_option('cosmosfarm_members_verify_email_title', ''));
		$this->verify_email_content = stripslashes(get_option('cosmosfarm_members_verify_email_content', ''));
		$this->confirmed_email = get_option('cosmosfarm_members_confirmed_email', '');
		$this->confirmed_email_title = stripslashes(get_option('cosmosfarm_members_confirmed_email_title', ''));
		$this->confirmed_email_content = stripslashes(get_option('cosmosfarm_members_confirmed_email_content', ''));
		$this->page_restriction_redirect = get_option('cosmosfarm_members_page_restriction_redirect', '');
		$this->page_restriction_message = get_option('cosmosfarm_members_page_restriction_message', '');
		$this->page_restriction_alert_message = get_option('cosmosfarm_members_page_restriction_alert_message', '');
		$this->page_restriction_permission_message = get_option('cosmosfarm_members_page_restriction_permission_message', '');
		$this->postcode_service_disabled = get_option('cosmosfarm_members_postcode_service_disabled', '');
		$this->use_postcode_service_iframe = get_option('cosmosfarm_members_use_postcode_service_iframe', '');
		$this->social_buttons_shortcode_display = get_option('cosmosfarm_members_social_buttons_shortcode_display', '');
		$this->change_role_active = get_option('cosmosfarm_members_change_role_active', '');
		$this->change_role_thresholds = get_option('cosmosfarm_members_change_role_thresholds', '');
		if(!$this->change_role_thresholds) $this->change_role_thresholds = array();
		$this->use_delete_account = get_option('cosmosfarm_members_use_delete_account', '');
		
		$this->use_strong_password = get_option('cosmosfarm_members_use_strong_password', '');
		$this->save_login_history = get_option('cosmosfarm_members_save_login_history', '');
		$this->use_login_protect = get_option('cosmosfarm_members_use_login_protect', '');
		$this->use_dormant_member = get_option('cosmosfarm_members_use_dormant_member', '');
		$this->dormant_member_email_title = get_option('cosmosfarm_members_dormant_member_email_title', '');
		$this->dormant_member_email_message = get_option('cosmosfarm_members_dormant_member_email_message', '');
		$this->login_protect_time = get_option('cosmosfarm_members_login_protect_time', '');
		$this->login_protect_count = get_option('cosmosfarm_members_login_protect_count', '');
		$this->login_protect_lockdown = get_option('cosmosfarm_members_login_protect_lockdown', '');
		$this->use_login_timeout = get_option('cosmosfarm_members_use_login_timeout', '');
		$this->login_timeout = get_option('cosmosfarm_members_login_timeout', '');
		$this->save_activity_history = get_option('cosmosfarm_members_save_activity_history', '');
		$this->active_closed_site = get_option('cosmosfarm_members_active_closed_site', '');
		$this->login_otp_phone_number = get_option('cosmosfarm_members_login_otp_phone_number', '');
		$this->login_otp_user_roles = get_option('cosmosfarm_members_login_otp_user_roles', array());
		
		$this->builtin_pg = get_option('cosmosfarm_members_builtin_pg', '');
		$this->iamport_id = get_option('cosmosfarm_members_iamport_id', '');
		$this->iamport_api_key = get_option('cosmosfarm_members_iamport_api_key', '');
		$this->iamport_api_secret = get_option('cosmosfarm_members_iamport_api_secret', '');
		$this->iamport_pg_mid = get_option('cosmosfarm_members_iamport_pg_mid', '');
		$this->use_certification = get_option('cosmosfarm_members_use_certification', '');
		$this->certification_min_age = get_option('cosmosfarm_members_certification_min_age', '');
		$this->certification_name_field = get_option('cosmosfarm_members_certification_name_field', '');
		$this->certification_gender_field = get_option('cosmosfarm_members_certification_gender_field', '');
		$this->certification_birth_field = get_option('cosmosfarm_members_certification_birth_field', '');
		$this->certification_carrier_field = get_option('cosmosfarm_members_certification_carrier_field', '');
		$this->certification_phone_field = get_option('cosmosfarm_members_certification_phone_field', '');
		
		$this->exists_check = get_option('cosmosfarm_members_exists_check', array());
		
		$this->sms_service = get_option('cosmosfarm_members_sms_service', '');
		$this->sms_caller1 = get_option('cosmosfarm_members_sms_caller1', '');
		$this->sms_caller2 = get_option('cosmosfarm_members_sms_caller2', '');
		$this->sms_caller3 = get_option('cosmosfarm_members_sms_caller3', '');
		$this->sms_cafe24_id= get_option('cosmosfarm_members_sms_cafe24_id', '');
		$this->sms_cafe24_secret = get_option('cosmosfarm_members_sms_cafe24_secret', '');
		$this->sms_toast_cloud_appkey = get_option('cosmosfarm_members_sms_toast_cloud_appkey', '');
		$this->alimtalk_service = get_option('cosmosfarm_members_alimtalk_service', '');
		$this->alimtalk_appkey = get_option('cosmosfarm_members_alimtalk_appkey', '');
		$this->alimtalk_secretkey = get_option('cosmosfarm_members_alimtalk_secretkey', '');
		$this->alimtalk_plusfriend_id = get_option('cosmosfarm_members_alimtalk_plusfriend_id', '');
		
		$this->notifications_page_id = get_option('cosmosfarm_members_notifications_page_id', '');
		$this->notifications_kboard = get_option('cosmosfarm_members_notifications_kboard', '');
		$this->notifications_kboard_comments = get_option('cosmosfarm_members_notifications_kboard_comments', '');
		$this->notifications_subnotify_email = get_option('cosmosfarm_members_notifications_subnotify_email', '');
		$this->notifications_subnotify_sms = get_option('cosmosfarm_members_notifications_subnotify_sms', '');
		$this->notifications_subnotify_email_title = get_option('cosmosfarm_members_notifications_subnotify_email_title', '');
		$this->notifications_subnotify_email_content = get_option('cosmosfarm_members_notifications_subnotify_email_content', '');
		$this->notifications_subnotify_sms_message = get_option('cosmosfarm_members_notifications_subnotify_sms_message', '');
		$this->notifications_subnotify_alimtalk_template = get_option('cosmosfarm_members_notifications_subnotify_alimtalk_template', '');
		$this->messages_page_id = get_option('cosmosfarm_members_messages_page_id', '');
		$this->messages_subnotify_email = get_option('cosmosfarm_members_messages_subnotify_email', '');
		$this->messages_subnotify_sms = get_option('cosmosfarm_members_messages_subnotify_sms', '');
		$this->messages_subnotify_alimtalk = get_option('cosmosfarm_members_messages_subnotify_alimtalk', '');
		$this->messages_subnotify_email_title = get_option('cosmosfarm_members_messages_subnotify_email_title', '');
		$this->messages_subnotify_email_content = get_option('cosmosfarm_members_messages_subnotify_email_content', '');
		$this->messages_subnotify_sms_message = get_option('cosmosfarm_members_messages_subnotify_sms_message', '');
		$this->messages_subnotify_alimtalk_template = get_option('cosmosfarm_members_messages_subnotify_alimtalk_template', '');
		$this->subnotify_sms_field = get_option('cosmosfarm_members_subnotify_sms_field', '');
		$this->users_page_id = get_option('cosmosfarm_members_users_page_id', '');
		$this->user_profile_page_id = get_option('cosmosfarm_members_user_profile_page_id', '');
		
		$this->bulk_sms_permission_roles = get_option('cosmosfarm_members_bulk_sms_permission_roles', array());
		$this->bulk_sms_content = get_option('cosmosfarm_members_bulk_sms_content', '');
		
		$this->alimtalk_template = get_option('cosmosfarm_members_alimtalk_template', '');
		
		$this->subscription_pg_type = get_option('cosmosfarm_members_subscription_pg_type', '');
		$this->subscription_pg = get_option('cosmosfarm_members_subscription_pg', '');
		$this->subscription_general_pg = get_option('cosmosfarm_members_subscription_general_pg', '');
		$this->subscription_checkout_page_id = get_option('cosmosfarm_members_subscription_checkout_page_id', '');
		$this->subscription_checkout_view_mode = get_option('cosmosfarm_members_subscription_checkout_view_mode', '');
		$this->subscription_orders_page_id = get_option('cosmosfarm_members_subscription_orders_page_id', '');
		$this->subscription_product_list_page_id = get_option('cosmosfarm_members_subscription_product_list_page_id', '');
		$this->subscription_cancellation_refund_policy_page_id = get_option('cosmosfarm_members_subscription_cancellation_refund_policy_page_id', '');
		$this->subscription_payment_completed_page_id = get_option('cosmosfarm_members_subscription_payment_completed_page_id', '');
		$this->subscription_courier_company = get_option('cosmosfarm_members_subscription_courier_company', '');
		
		$this->mailchimp_api_key = get_option('cosmosfarm_members_mailchimp_api_key', '');
		$this->mailchimp_list_id = get_option('cosmosfarm_members_mailchimp_list_id', '');
		$this->mailchimp_field = get_option('cosmosfarm_members_mailchimp_field', '');
	}
	
	public function save(){
		$this->update('cosmosfarm_members_skin');
		$this->update('cosmosfarm_members_allow_email_login');
		$this->update('cosmosfarm_members_login_redirect_page');
		$this->update('cosmosfarm_members_login_redirect_url');
		$this->update('cosmosfarm_members_naver_client_id');
		$this->update('cosmosfarm_members_naver_client_secret');
		$this->update('cosmosfarm_members_facebook_client_id');
		$this->update('cosmosfarm_members_facebook_client_secret');
		$this->update('cosmosfarm_members_kakao_client_id');
		$this->update('cosmosfarm_members_google_client_id');
		$this->update('cosmosfarm_members_google_client_secret');
		$this->update('cosmosfarm_members_twitter_client_id');
		$this->update('cosmosfarm_members_twitter_client_secret');
		$this->update('cosmosfarm_members_instagram_client_id');
		$this->update('cosmosfarm_members_instagram_client_secret');
		$this->update('cosmosfarm_members_line_client_id');
		$this->update('cosmosfarm_members_line_client_secret');
		$this->update('cosmosfarm_members_login_page_id');
		$this->update('cosmosfarm_members_login_page_url');
		$this->update('cosmosfarm_members_register_page_id');
		$this->update('cosmosfarm_members_register_page_url');
		$this->update('cosmosfarm_members_account_page_id');
		$this->update('cosmosfarm_members_account_page_url');
		$this->update('cosmosfarm_members_user_required');
		$this->update('cosmosfarm_members_verify_email');
		$this->update('cosmosfarm_members_verify_email_title');
		$this->update('cosmosfarm_members_verify_email_content');
		$this->update('cosmosfarm_members_confirmed_email');
		$this->update('cosmosfarm_members_confirmed_email_title');
		$this->update('cosmosfarm_members_confirmed_email_content');
		$this->update('cosmosfarm_members_page_restriction_redirect');
		$this->update('cosmosfarm_members_page_restriction_message');
		$this->update('cosmosfarm_members_page_restriction_alert_message');
		$this->update('cosmosfarm_members_page_restriction_permission_message');
		$this->update('cosmosfarm_members_postcode_service_disabled');
		$this->update('cosmosfarm_members_use_postcode_service_iframe');
		$this->update('cosmosfarm_members_social_buttons_shortcode_display');
		$this->update('cosmosfarm_members_change_role_active');
		$this->update('cosmosfarm_members_change_role_thresholds');
		$this->update('cosmosfarm_members_use_delete_account');
		
		$this->update('cosmosfarm_members_use_strong_password');
		$this->update('cosmosfarm_members_save_login_history');
		$this->update('cosmosfarm_members_use_login_protect');
		$this->update('cosmosfarm_members_use_dormant_member');
		$this->update('cosmosfarm_members_dormant_member_email_title');
		$this->update('cosmosfarm_members_dormant_member_email_message');
		$this->update('cosmosfarm_members_login_protect_time');
		$this->update('cosmosfarm_members_login_protect_count');
		$this->update('cosmosfarm_members_login_protect_lockdown');
		$this->update('cosmosfarm_members_use_login_timeout');
		$this->update('cosmosfarm_members_login_timeout');
		$this->update('cosmosfarm_members_save_activity_history');
		$this->update('cosmosfarm_members_active_closed_site');
		$this->update('cosmosfarm_members_login_otp_phone_number');
		$this->update('cosmosfarm_members_login_otp_user_roles', array());
		
		$this->update('cosmosfarm_members_builtin_pg');
		$this->update('cosmosfarm_members_iamport_id');
		$this->update('cosmosfarm_members_iamport_api_key');
		$this->update('cosmosfarm_members_iamport_api_secret');
		$this->update('cosmosfarm_members_iamport_pg_mid');
		$this->update('cosmosfarm_members_use_certification');
		$this->update('cosmosfarm_members_certification_min_age');
		$this->update('cosmosfarm_members_certification_name_field');
		$this->update('cosmosfarm_members_certification_gender_field');
		$this->update('cosmosfarm_members_certification_birth_field');
		$this->update('cosmosfarm_members_certification_carrier_field');
		$this->update('cosmosfarm_members_certification_phone_field');
		
		$this->update('cosmosfarm_members_sms_service');
		$this->update('cosmosfarm_members_sms_caller1');
		$this->update('cosmosfarm_members_sms_caller2');
		$this->update('cosmosfarm_members_sms_caller3');
		$this->update('cosmosfarm_members_sms_cafe24_id');
		$this->update('cosmosfarm_members_sms_cafe24_secret');
		$this->update('cosmosfarm_members_sms_toast_cloud_appkey');
		$this->update('cosmosfarm_members_alimtalk_service');
		$this->update('cosmosfarm_members_alimtalk_appkey');
		$this->update('cosmosfarm_members_alimtalk_secretkey');
		$this->update('cosmosfarm_members_alimtalk_plusfriend_id');
		
		$this->update('cosmosfarm_members_subscription_pg_type');
		$this->update('cosmosfarm_members_subscription_pg');
		$this->update('cosmosfarm_members_subscription_general_pg');
		$this->update('cosmosfarm_members_subscription_checkout_page_id');
		$this->update('cosmosfarm_members_subscription_checkout_view_mode');
		$this->update('cosmosfarm_members_subscription_orders_page_id');
		$this->update('cosmosfarm_members_subscription_product_list_page_id');
		$this->update('cosmosfarm_members_subscription_cancellation_refund_policy_page_id');
		$this->update('cosmosfarm_members_subscription_payment_completed_page_id');
		$this->update('cosmosfarm_members_subscription_courier_company');
		
		$this->update('cosmosfarm_members_mailchimp_api_key');
		$this->update('cosmosfarm_members_mailchimp_list_id');
		$this->update('cosmosfarm_members_mailchimp_field');
	}
	
	public function update($option_name, $default=false){
		if(isset($_POST[$option_name])){
			if(is_string($_POST[$option_name])){
				$new_value = trim($_POST[$option_name]);
			}
			else{
				$new_value = $_POST[$option_name];
			}
			if(get_option($option_name) !== false){
				update_option($option_name, $new_value, 'yes');
			}
			else add_option($option_name, $new_value, '', 'yes');
		}
		else if($default !== false){
			if(get_option($option_name) !== false){
				update_option($option_name, $default, 'yes');
			}
			else add_option($option_name, $default, '', 'yes');
		}
	}
	
	public function truncate(){
		delete_option('cosmosfarm_members_policy_service');
		delete_option('cosmosfarm_members_policy_privacy');
		delete_option('cosmosfarm_members_skin');
		delete_option('cosmosfarm_members_social_login_active');
		delete_option('cosmosfarm_members_allow_email_login');
		delete_option('cosmosfarm_members_login_redirect_page');
		delete_option('cosmosfarm_members_login_redirect_url');
		delete_option('cosmosfarm_members_naver_client_id');
		delete_option('cosmosfarm_members_naver_client_secret');
		delete_option('cosmosfarm_members_facebook_client_id');
		delete_option('cosmosfarm_members_facebook_client_secret');
		delete_option('cosmosfarm_members_kakao_client_id');
		delete_option('cosmosfarm_members_google_client_id');
		delete_option('cosmosfarm_members_google_client_secret');
		delete_option('cosmosfarm_members_twitter_client_id');
		delete_option('cosmosfarm_members_twitter_client_secret');
		delete_option('cosmosfarm_members_instagram_client_id');
		delete_option('cosmosfarm_members_instagram_client_secret');
		delete_option('cosmosfarm_members_line_client_id');
		delete_option('cosmosfarm_members_line_client_secret');
		delete_option('cosmosfarm_members_login_page_id');
		delete_option('cosmosfarm_members_login_page_url');
		delete_option('cosmosfarm_members_register_page_id');
		delete_option('cosmosfarm_members_register_page_url');
		delete_option('cosmosfarm_members_account_page_id');
		delete_option('cosmosfarm_members_account_page_url');
		delete_option('cosmosfarm_members_user_required');
		delete_option('cosmosfarm_members_verify_email');
		delete_option('cosmosfarm_members_verify_email_title');
		delete_option('cosmosfarm_members_verify_email_content');
		delete_option('cosmosfarm_members_confirmed_email');
		delete_option('cosmosfarm_members_confirmed_email_title');
		delete_option('cosmosfarm_members_confirmed_email_content');
		delete_option('cosmosfarm_members_page_restriction_redirect');
		delete_option('cosmosfarm_members_page_restriction_message');
		delete_option('cosmosfarm_members_page_restriction_alert_message');
		delete_option('cosmosfarm_members_page_restriction_permission_message');
		delete_option('cosmosfarm_members_postcode_service_disabled');
		delete_option('cosmosfarm_members_use_postcode_service_iframe');
		delete_option('cosmosfarm_members_social_buttons_shortcode_display');
		delete_option('cosmosfarm_members_change_role_active');
		delete_option('cosmosfarm_members_change_role_thresholds');
		delete_option('cosmosfarm_members_use_delete_account');
		
		delete_option('cosmosfarm_members_use_strong_password');
		delete_option('cosmosfarm_members_save_login_history');
		delete_option('cosmosfarm_members_use_login_protect');
		delete_option('cosmosfarm_members_use_dormant_member');
		delete_option('cosmosfarm_members_dormant_member_email_title');
		delete_option('cosmosfarm_members_dormant_member_email_message');
		delete_option('cosmosfarm_members_login_protect_time');
		delete_option('cosmosfarm_members_login_protect_count');
		delete_option('cosmosfarm_members_login_protect_lockdown');
		delete_option('cosmosfarm_members_use_login_timeout');
		delete_option('cosmosfarm_members_login_timeout');
		delete_option('cosmosfarm_members_save_activity_history');
		delete_option('cosmosfarm_members_active_closed_site');
		delete_option('cosmosfarm_members_login_otp_phone_number');
		delete_option('cosmosfarm_members_login_otp_user_roles');
		
		delete_option('cosmosfarm_members_builtin_pg');
		delete_option('cosmosfarm_members_iamport_id');
		delete_option('cosmosfarm_members_iamport_api_key');
		delete_option('cosmosfarm_members_iamport_api_secret');
		delete_option('cosmosfarm_members_iamport_pg_mid');
		delete_option('cosmosfarm_members_use_certification');
		delete_option('cosmosfarm_members_certification_min_age');
		delete_option('cosmosfarm_members_certification_name_field');
		delete_option('cosmosfarm_members_certification_gender_field');
		delete_option('cosmosfarm_members_certification_birth_field');
		delete_option('cosmosfarm_members_certification_carrier_field');
		delete_option('cosmosfarm_members_certification_phone_field');
		
		delete_option('cosmosfarm_members_exists_check');
		
		delete_option('cosmosfarm_members_sms_service');
		delete_option('cosmosfarm_members_sms_caller1');
		delete_option('cosmosfarm_members_sms_caller2');
		delete_option('cosmosfarm_members_sms_caller3');
		delete_option('cosmosfarm_members_sms_cafe24_id');
		delete_option('cosmosfarm_members_sms_cafe24_secret');
		delete_option('cosmosfarm_members_sms_toast_cloud_appkey');
		delete_option('cosmosfarm_members_alimtalk_service');
		delete_option('cosmosfarm_members_alimtalk_appkey');
		delete_option('cosmosfarm_members_alimtalk_secretkey');
		delete_option('cosmosfarm_members_alimtalk_plusfriend_id');
		
		delete_option('cosmosfarm_members_notifications_page_id');
		delete_option('cosmosfarm_members_notifications_kboard');
		delete_option('cosmosfarm_members_notifications_kboard_comments');
		delete_option('cosmosfarm_members_notifications_subnotify_email', '');
		delete_option('cosmosfarm_members_notifications_subnotify_sms', '');
		delete_option('cosmosfarm_members_notifications_subnotify_email_title');
		delete_option('cosmosfarm_members_notifications_subnotify_email_content');
		delete_option('cosmosfarm_members_notifications_subnotify_sms_message');
		delete_option('cosmosfarm_members_notifications_subnotify_alimtalk_template');
		delete_option('cosmosfarm_members_messages_page_id');
		delete_option('cosmosfarm_members_messages_subnotify_email', '');
		delete_option('cosmosfarm_members_messages_subnotify_sms', '');
		delete_option('cosmosfarm_members_messages_subnotify_alimtalk', '');
		delete_option('cosmosfarm_members_messages_subnotify_email_title');
		delete_option('cosmosfarm_members_messages_subnotify_email_content');
		delete_option('cosmosfarm_members_messages_subnotify_sms_message');
		delete_option('cosmosfarm_members_messages_subnotify_alimtalk_template');
		delete_option('cosmosfarm_members_subnotify_sms_message');
		delete_option('cosmosfarm_members_users_page_id');
		delete_option('cosmosfarm_members_user_profile_page_id');

		delete_option('cosmosfarm_members_bulk_sms_permission_roles');
		delete_option('cosmosfarm_members_bulk_sms_content');
		
		delete_option('cosmosfarm_members_alimtalk_template');
		
		delete_option('cosmosfarm_members_subscription_pg_type');
		delete_option('cosmosfarm_members_subscription_pg');
		delete_option('cosmosfarm_members_subscription_general_pg');
		delete_option('cosmosfarm_members_subscription_checkout_page_id');
		delete_option('cosmosfarm_members_subscription_checkout_view_mode');
		delete_option('cosmosfarm_members_subscription_orders_page_id');
		delete_option('cosmosfarm_members_subscription_product_list_page_id');
		delete_option('cosmosfarm_members_subscription_cancellation_refund_policy_page_id');
		delete_option('cosmosfarm_members_subscription_payment_completed_page_id');
		delete_option('cosmosfarm_members_subscription_courier_company');
		
		delete_option('cosmosfarm_members_mailchimp_api_key');
		delete_option('cosmosfarm_members_mailchimp_list_id');
		delete_option('cosmosfarm_members_mailchimp_field');
	}
}
?>