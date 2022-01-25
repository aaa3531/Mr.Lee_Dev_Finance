<?php
/**
 * Cosmosfarm_Members_Skin
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Skin {
	
	public function __construct(){
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		switch($action){
			case 'cosmosfarm_members_skin_notifications_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->notifications_list();
				exit;
			case 'cosmosfarm_members_skin_messages_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->messages_list();
				exit;
			case 'cosmosfarm_members_skin_orders_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->orders_list();
				exit;
			case 'cosmosfarm_members_skin_users_list':
				check_ajax_referer('cosmosfarm-members-check-ajax-referer', 'security');
				echo $this->users_list();
				exit;
		}
	}
	
	public function header($current_page=''){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/header.php";
		$layout = '';
		
		$file_path = apply_filters('cosmosfarm_members_template_header', $file_path);
		
		if(file_exists($file_path)){
			
			if(!$current_page){
				if($option->account_page_id == $post->ID){
					$current_page = 'profile';
				}
				else if($option->subscription_orders_page_id == $post->ID){
					$current_page = 'orders';
				}
				else if($option->notifications_page_id == $post->ID){
					$current_page = 'notifications';
				}
				else if($option->messages_page_id == $post->ID){
					$current_page = 'messages';
				}
				else if($option->users_page_id == $post->ID){
					$current_page = 'users';
				}
				else{
					$current_page = $post->ID;
				}
			}
			
			$menu_items = array();
			
			$menu_items['profile'] = array(
				'id' => 'profile',
				'url' => get_cosmosfarm_members_profile_url(),
				'title' => __('Account', 'cosmosfarm-members'),
			);
			
			if($option->subscription_orders_page_id){
				$menu_items['orders'] = array(
					'id' => 'orders',
					'url' => get_cosmosfarm_members_orders_url(),
					'title' => __('Orders', 'cosmosfarm-members'),
				);
			}
			
			if($option->notifications_page_id){
				$menu_items['notifications'] = array(
					'id' => 'notifications',
					'url' => get_cosmosfarm_members_notifications_url(),
					'title' => sprintf('%s %s', __('Notifications', 'cosmosfarm-members'), cosmosfarm_members_unread_notifications_count()),
				);
			}
			
			if($option->messages_page_id){
				$menu_items['messages'] = array(
					'id' => 'messages',
					'url' => get_cosmosfarm_members_messages_url(),
					'title' => sprintf('%s %s', __('Messages', 'cosmosfarm-members'), cosmosfarm_members_unread_messages_count()),
				);
			}
			
			if($option->users_page_id){
				$menu_items['users'] = array(
					'id' => 'users',
					'url' => get_cosmosfarm_members_users_url(),
					'title' => __('Members', 'cosmosfarm-members'),
				);
			}
			
			$menu_name = 'cosmosfarm-members-header-menu';
			if(($locations = get_nav_menu_locations()) && isset($locations[$menu_name])){
				$menu = wp_get_nav_menu_object($locations[$menu_name]);
				
				if(isset($menu->term_id) && $menu->term_id){
					foreach(wp_get_nav_menu_items($menu->term_id) as $menu_item){
						$menu_items[$menu_item->object_id] = array(
							'id' => $menu_item->object_id,
							'url' => $menu_item->url,
							'title' => $menu_item->title,
						);
					}
				}
			}
			
			$menu_items = apply_filters('cosmosfarm_members_header_menu_items', $menu_items);
			
			$current_page = apply_filters('cosmosfarm_members_header_menu_current_page', $current_page, $menu_items);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function login_form($layout, $action){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
		
		if(!$redirect_to && $post->ID == $option->login_page_id){
			$redirect_to = home_url();
		}
		else if(!$redirect_to){
			$redirect_to = get_permalink();
		}
		
		$redirect_to = apply_filters('cosmosfarm_members_login_redirect_to', $redirect_to);
		$login_action_url = remove_query_arg(array('verify_email_confirm', 'register_success', 'login_timeout'));
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/login-form.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/login-form.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/login-form.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_login_form', $file_path, $action);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function change_password_form($layout, $action){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$form_action_url = get_permalink();
		
		$a = isset($_GET['a']) ? sanitize_text_field($_GET['a']) : '';
		$key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
		$login = isset($_GET['login']) ? sanitize_text_field($_GET['login']) : '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/change-password-form.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/change-password-form.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/change-password-form.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_change_password_form', $file_path, $action);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function account_links($args=array()){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/account-links.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/account-links.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/account-links.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_account_links', $file_path, $args);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function login_timeout_popup($login_timeout_url, $login_timeout){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/login-timeout-popup.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/login-timeout-popup.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/login-timeout-popup.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_login_timeout_popup', $file_path, $login_timeout_url, $login_timeout);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function notifications(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/notifications.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/notifications.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/notifications.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$notifications_view = isset($_GET['notifications_view']) ? sanitize_text_field($_GET['notifications_view']) : 'inbox';
		
		if(!in_array($notifications_view, array('inbox', 'unread'))){
			$notifications_view = 'inbox';
		}
		
		$notification = new Cosmosfarm_Members_Notification();
		
		$file_path = apply_filters('cosmosfarm_members_template_notifications', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function notifications_list(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/notifications-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/notifications-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/notifications-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		$notifications_view = isset($_REQUEST['notifications_view']) ? sanitize_text_field($_REQUEST['notifications_view']) : 'inbox';
		
		if(!in_array($notifications_view, array('inbox', 'unread'))){
			$notifications_view = 'inbox';
		}
		
		$meta_query = array();
		if($notifications_view == 'unread'){
			$meta_query[] = array(
				'key'     => 'item_status',
				'value'   => $notifications_view,
				'compare' => '=',
			);
		}
		
		$notification = new Cosmosfarm_Members_Notification();
		
		$file_path = apply_filters('cosmosfarm_members_template_notifications_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function notifications_list_item($post_id, $item_type_default='default'){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item = new Cosmosfarm_Members_Notification($post_id);
		$from_user_id = get_post_meta($item->ID, 'from_user_id', true);
		$from_user = get_userdata($from_user_id);
		$item_type = get_post_meta($item->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/notifications-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/notifications-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/notifications-list-item-%s.php', $option->skin, $item_type);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_notifications_list_item', $file_path, $item_type, $item);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function messages(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$redirect_to = isset($_GET['redirect_to']) ? esc_url_raw($_GET['redirect_to']) : get_cosmosfarm_members_messages_url();
		$to_user_id = isset($_GET['to_user_id']) ? intval($_GET['to_user_id']) : '';
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$messages_view = isset($_GET['messages_view']) ? sanitize_text_field($_GET['messages_view']) : 'inbox';
		
		if(!in_array($messages_view, array('inbox', 'sent'))){
			$messages_view = 'inbox';
		}
		
		if($to_user_id){
			$to_user = get_userdata($to_user_id);
			if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/messages-form.php')){
				$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
				$file_path = get_stylesheet_directory() . '/cosmosfarm-members/messages-form.php';
			}
			else{
				$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
				$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/messages-form.php";
			}
		}
		else{
			if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/messages.php')){
				$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
				$file_path = get_stylesheet_directory() . '/cosmosfarm-members/messages.php';
			}
			else{
				$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
				$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/messages.php";
			}
		}
		
		$message = new Cosmosfarm_Members_Message();
		
		$file_path = apply_filters('cosmosfarm_members_template_messages', $file_path, $to_user_id);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function messages_list(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/messages-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/messages-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/messages-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		$messages_view = isset($_REQUEST['messages_view']) ? sanitize_text_field($_REQUEST['messages_view']) : 'inbox';
		
		if(!in_array($messages_view, array('inbox', 'sent'))){
			$messages_view = 'inbox';
		}
		
		$meta_query = array();
		if($messages_view == 'inbox'){
			$meta_query[] = array(
				'key'     => 'to_user_id',
				'value'   => get_current_user_id(),
				'compare' => '=',
			);
		}
		else if($messages_view == 'sent'){
			$meta_query[] = array(
				'key'     => 'from_user_id',
				'value'   => get_current_user_id(),
				'compare' => '=',
			);
		}
		
		$message = new Cosmosfarm_Members_Message();
		
		$file_path = apply_filters('cosmosfarm_members_template_messages_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function messages_list_item($post_id, $item_type_default='default'){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item = new Cosmosfarm_Members_Message($post_id);
		$from_user_id = get_post_meta($item->ID, 'from_user_id', true);
		$from_user = get_userdata($from_user_id);
		$item_type = get_post_meta($item->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/messages-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/messages-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/messages-list-item-%s.php', $option->skin, $item_type);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_messages_list_item', $file_path, $item_type, $item);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function orders(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/orders.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/orders.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/orders.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$orders_view = isset($_GET['orders_view']) ? sanitize_text_field($_GET['orders_view']) : 'paid';
		
		if(!in_array($orders_view, array('paid', 'expired'))){
			$orders_view = 'paid';
		}
		
		$order = new Cosmosfarm_Members_Subscription_Order();
		
		$file_path = apply_filters('cosmosfarm_members_template_orders', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function orders_list(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/orders-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/orders-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/orders-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		$orders_view = isset($_REQUEST['orders_view']) ? sanitize_text_field($_REQUEST['orders_view']) : 'paid';
		
		if(!in_array($orders_view, array('paid', 'expired'))){
			$orders_view = 'paid';
		}
		
		$meta_query = array();
		if($orders_view == 'paid'){
			$meta_query[] = array(
				'key'     => 'subscription_next',
				'value'   => array('success', 'wait'),
				'compare' => 'IN',
			);
		}
		else if($orders_view == 'expired'){
			$meta_query[] = array(
				'key'     => 'subscription_next',
				'value'   => array('expiry', 'cancel'),
				'compare' => 'IN',
			);
		}
		
		$order = new Cosmosfarm_Members_Subscription_Order();
		
		$file_path = apply_filters('cosmosfarm_members_template_orders_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function orders_list_item($post_id, $item_type_default='default'){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item = new Cosmosfarm_Members_Subscription_Order($post_id);
		$item_type = get_post_meta($item->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/orders-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/orders-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/orders-list-item-%s.php', $option->skin, $item_type);
		}
		
		$product = new Cosmosfarm_Members_Subscription_Product($item->product_id());
		$fields = $product->order_view_fields();
		
		$file_path = apply_filters('cosmosfarm_members_template_orders_list_item', $file_path, $item_type, $item);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			$settings = array(
				'iamport_id' => $option->iamport_id,
				'iamport_pg_mid' => $option->iamport_pg_mid,
				'subscription_pg' => $option->subscription_pg,
			);
			wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_subscription_checkout_settings', $settings);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function users(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/users.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/users.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/users.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		
		$file_path = apply_filters('cosmosfarm_members_template_users', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function users_list(){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/users-list.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/users-list.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/users-list.php";
		}
		
		$paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']): 1;
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		
		$file_path = apply_filters('cosmosfarm_members_template_users_list', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function users_list_item($user, $item_type_default='default'){
		//global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$item_type = get_user_meta($user->ID, 'item_type', true);
		$item_type = $item_type ? $item_type : $item_type_default;
		
		if(file_exists(get_stylesheet_directory() . sprintf('/cosmosfarm-members/users-list-item-%s.php', $item_type))){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . sprintf('/cosmosfarm-members/users-list-item-%s.php', $item_type);
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . sprintf('/skin/%s/users-list-item-%s.php', $option->skin, $item_type);
		}
		
		$file_path = sprintf($file_path, $item_type);
		$file_path = apply_filters('cosmosfarm_members_template_users_list_item', $file_path, $item_type, $user);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function user_profile(){
		global $wp_query;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		
		$profile_id = '';
		
		if(isset($_GET['profile_id'])){
			$profile_id = intval($_GET['profile_id']);
		}
		
		if(isset($wp_query->query_vars['profile_id'])){
			$profile_id = intval($wp_query->query_vars['profile_id']);
		}
		
		if(!$profile_id){
			$profile_id = get_current_user_id();
		}
		
		$user = get_userdata($profile_id);
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/user-profile.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/user-profile.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/user-profile.php";
		}
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		
		$tab_items = array();
		$tab_items['about'] = array(
			'id' => 'about',
			'url' => get_cosmosfarm_members_user_profile_url($user->ID),
			'title' => __('About', 'cosmosfarm-members'),
		);
		
		$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'about';
		
		$file_path = apply_filters('cosmosfarm_members_template_user_profile', $file_path);
		
		if(file_exists($file_path)){
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/subscription-product.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/subscription-product.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product.php";
		}
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			/*
			$settings = array(
				'iamport_id' => $option->iamport_id,
				'iamport_pg_mid' => $option->iamport_pg_mid,
				'subscription_pg' => $option->subscription_pg,
			);
			wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_subscription_checkout_settings', $settings);
			*/
			
			do_action('cosmosfarm_members_skin_subscription_product', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product_title($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-title.php";
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_title', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			do_action('cosmosfarm_members_skin_subscription_product_title', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product_price($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-price.php";
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_price', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			do_action('cosmosfarm_members_skin_subscription_product_price', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product_type($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-type.php";
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_type', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			do_action('cosmosfarm_members_skin_subscription_product_type', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product_first_free($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-first-free.php";
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_first_free', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			do_action('cosmosfarm_members_skin_subscription_product_first_free', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_product_button($product){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
		$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-button.php";
		
		if(is_int($product)){
			$product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_button', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			do_action('cosmosfarm_members_skin_subscription_product_button', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	/**
	 * 정기결제 상품 목록 페이지
	 * @param WP_Query $query
	 * @return string
	 */
	public function subscription_product_list($query){
		$option = get_cosmosfarm_members_option();
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . "/cosmosfarm-members/subscription-product-list.php")){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . "/cosmosfarm-members/subscription-product-list.php";
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-list.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_list', $file_path, $query);
		
		if(file_exists($file_path)){
			do_action('cosmosfarm_members_skin_subscription_product_list', $this, $query);
			
			ob_start();
			
			include $file_path;
			
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	/**
	 * 정기결제 상품 최신글
	 * @param WP_Query $query
	 * @return string
	 */
	public function subscription_product_latest($query){
		$option = get_cosmosfarm_members_option();
		$layout = '';
		
		if(file_exists(get_stylesheet_directory() . "/cosmosfarm-members/subscription-product-latest.php")){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . "/cosmosfarm-members/subscription-product-latest.php";
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-latest.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_latest', $file_path, $query);
		
		if(file_exists($file_path)){
			do_action('cosmosfarm_members_skin_subscription_product_latest', $this, $query);
			
			ob_start();
			
			include $file_path;
			
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	/**
	 * 정기결제 상품 상세페이지
	 * @return string
	 */
	public function subscription_product_single_template($product){
		global $cosmosfarm_members_subscription_product;
		
		$option = get_cosmosfarm_members_option();
		
		if(file_exists(get_stylesheet_directory() . "/cosmosfarm-members/subscription-product-single-template.php")){
			$file_path = get_stylesheet_directory() . "/cosmosfarm-members/subscription-product-single-template.php";
		}
		else{
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-product-single-template.php";
		}
		
		if(is_int($product)){
			$cosmosfarm_members_subscription_product = new Cosmosfarm_Members_Subscription_Product($product);
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_product_single_template', $file_path, $cosmosfarm_members_subscription_product);
		
		if(file_exists($file_path)){
			wp_enqueue_style("cosmosfarm-members-{$option->skin}-subscription-product-single", COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}/subscription-product-single-template.css", array(), COSMOSFARM_MEMBERS_VERSION);
			
			do_action('cosmosfarm_members_skin_subscription_product_single_template', $this, $cosmosfarm_members_subscription_product);
			
			return $file_path;
		}
		
		return '';
	}
	
	/**
	 * 정기결제 상품 결제 페이지
	 * @param Cosmosfarm_Members_Subscription_Product $product
	 * @return string
	 */
	public function subscription_checkout($product){
		global $post, $cosmosfarm_members_subscription_product, $cosmosfarm_members_subscription_checkout_title_index;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
		$iamport = new Cosmosfarm_Members_API_Iamport();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-checkout.php";
		}
		
		if(is_int($product)){
			$cosmosfarm_members_subscription_product = new Cosmosfarm_Members_Subscription_Product($product);
			$product = $cosmosfarm_members_subscription_product;
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_checkout', $file_path, $product);
		
		if(file_exists($file_path)){
			wp_enqueue_style('jquery-flick-style');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('daum-postcode');
			wp_enqueue_script('cosmosfarm-members-subscription');
			
			// Cosmosfarm_Members_Subscription_Item 연장을 위한 ID를 입력받는다.
			$extend_item_id = isset($_GET['extend_item_id']) ? intval($_GET['extend_item_id']) : '';
			
			$pay_success_url = isset($_GET['cosmosfarm_redirect_to']) ? esc_url_raw($_GET['cosmosfarm_redirect_to']) : wp_get_referer();
			
			// 결제 완료 페이지 설정이 있다면 해당 페이지 주소로 설정한다.
			if($option->subscription_payment_completed_page_id){
				$pay_success_url = add_query_arg(array('cosmosfarm_product_id'=>$product->ID()), get_permalink($option->subscription_payment_completed_page_id));
			}
			$pay_success_url = esc_url_raw(apply_filters('cosmosfarm_members_subscription_pay_success_url', $pay_success_url, $product));
			
			// 첫 결제 무료 이용을 확인한다.
			$is_subscription_first_free = $product->is_subscription_first_free($user->ID, $_REQUEST);
			if($is_subscription_first_free){
				if($product->subscription_first_free() == '1month'){
					$product_period_interval = 'month';
				}
				else{
					$product_period_interval = '';
				}
				
				$product_period_from = date('Ymd', current_time('timestamp'));
				$product_period_to = $product->next_subscription_datetime_first_free($product_period_from, 'Ymd');
				
				// 다날 빌링 결제는 제공기간 형식이 다른 PG사와는 다르다.
				if(in_array($option->subscription_pg, array('danal_tpay', 'danal'))){
					$product_period_from = date('YmdHi', current_time('timestamp'));
					$product_period_to = $product->next_subscription_datetime_first_free($product_period_from, 'YmdHi');
				}
			}
			else{
				if($product->subscription_type() == 'monthly'){
					$product_period_interval = 'month';
				}
				else if($product->subscription_type() == '12monthly'){
					$product_period_interval = 'year';
				}
				else{
					$product_period_interval = '';
				}
				
				$product_period_from = date('Ymd', current_time('timestamp'));
				$product_period_to = $product->next_subscription_datetime($product_period_from, 'Ymd');
				
				// 다날 빌링 결제는 제공기간 형식이 다른 PG사와는 다르다.
				if(in_array($option->subscription_pg, array('danal_tpay', 'danal'))){
					$product_period_from = date('YmdHi', current_time('timestamp'));
					$product_period_to = $product->next_subscription_datetime($product_period_from, 'YmdHi');
				}
			}
			
			// 결제방식에 따른 설정
			$subscription_pg_type = $product->get_subscription_pg_type();
			if($subscription_pg_type == 'general'){
				// 일반결제는 첫 결제 가격을 사용하지 않는다.
				$price = $product->price();
				
				// 일반결제는 자동결제되지 않는다.
				$product_period_interval = '';
				
				$m_redirect_url = add_query_arg(array(
					'action' => 'cosmosfarm_members_subscription_request_pay_complete',
					'display' => 'mobile',
					'product_id' => $product->ID(),
					'checkout_nonce' => wp_create_nonce('cosmosfarm-members-subscription-checkout-' . $product->ID()),
				), get_permalink());
			}
			else{
				// 빌링결제는 값을 비워준다.
				$subscription_pg_type = '';
				
				// 빌링결제는 첫 결제 가격을 사용한다.
				$price = $product->first_price();
				
				$m_redirect_url = add_query_arg(array(
					'action' => 'cosmosfarm_members_subscription_request_pay_mobile',
					'product_id' => $product->ID(),
					'checkout_nonce' => wp_create_nonce('cosmosfarm-members-subscription-checkout-' . $product->ID()),
				), get_permalink());
			}
			$m_redirect_url = esc_url_raw(apply_filters('cosmosfarm_members_subscription_m_redirect_url', $m_redirect_url, $product));
			
			// 적용된 쿠폰이 있는지 확인한다.
			$coupon = new Cosmosfarm_Members_Subscription_Coupon();
			$coupon_id = $coupon->get_save_coupon_id(get_current_user_id(), $product->ID());
			$coupon->init_with_id($coupon_id);
			
			// 쿠폰 설정에 따라서 가격을 변경한다.
			$coupon_price = $price;
			if($coupon->ID()){
				$coupon_price = $coupon->calculate($price);
			}
			
			// 결제 버튼의 텍스트 생성
			$button_display_text = sprintf('%s %s', cosmosfarm_members_currency_format($coupon_price), __('Place order', 'cosmosfarm-members'));
			$button_display_text = apply_filters('cosmosfarm_members_subscription_checkout_button_display_text', $button_display_text, $product, $is_subscription_first_free, $coupon);
			
			$settings = array(
				'checkout_nonce'          => wp_create_nonce('cosmosfarm-members-subscription-checkout-' . $product->ID()),
				'builtin_pg'              => $option->builtin_pg,
				'iamport_id'              => $option->iamport_id,
				'iamport_pg_mid' 		  => $option->iamport_pg_mid,
				'subscription_pg_type'    => $subscription_pg_type,
				'subscription_pg'         => $option->subscription_pg,
				'subscription_general_pg' => $option->subscription_general_pg,
				'merchant_uid'            => $iamport->getMerchantUID(),
				'customer_uid'            => $iamport->getCustomerUID(),
				'product_id'              => $product->ID(),
				'product_title'           => $product->title(),
				'product_price'           => $coupon_price,
				'product_period_interval' => $product_period_interval,
				'product_period_from'     => $product_period_from,
				'product_period_to'       => $product_period_to,
				'pay_success_url'         => $pay_success_url,
				'm_redirect_url'          => $m_redirect_url,
				'app_scheme'              => apply_filters('cosmosfarm_members_subscription_pay_app_scheme', '', $product),
				'button_display_text'     => $button_display_text,
				'sign_up_success_url'     => add_query_arg(array('register_success'=>'1')),
			);
			wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_subscription_checkout_settings', $settings);
			
			// 주문 페이지의 섹션 제목의 번호를 1로 초기화한다.
			$cosmosfarm_members_subscription_checkout_title_index = 1;
			
			do_action('cosmosfarm_members_skin_subscription_checkout', $this, $product);
			
			ob_start();
			include $file_path;
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	public function subscription_checkout_field_template($field=array(), $field2=array(), $field3=array()){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		$skin = $this;
		$layout = '';
		$user = wp_get_current_user();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout-fields.php')){
			$skin_path = get_stylesheet_directory_uri() . '/cosmosfarm-members';
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/subscription-checkout-fields.php';
		}
		else{
			$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}";
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/subscription-checkout-fields.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_subscription_checkout_fields', $file_path, $field);
		
		if(file_exists($file_path) && isset($field['type']) && $field['type']){
			$field_type = $field['type'];
			
			$default_value = '';
			if(isset($field['default_value'])){
				$default_value = $field['default_value'];
			}
			
			ob_start();
			include $file_path;
			do_action('cosmosfarm_members_skin_subscription_checkout_field_template', $field_type, $field, $field2, $field3);
			$layout = ob_get_clean();
		}
		
		return $layout;
	}
	
	/**
	 * 주문 페이지의 섹션 제목을 출력 형태로 반환한다.
	 * @param string $title
	 * @return string
	 */
	public function subscription_checkout_title($title=''){
		global $cosmosfarm_members_subscription_checkout_title_index;
		
		$title = sprintf('%d. %s', $cosmosfarm_members_subscription_checkout_title_index, $title);
		
		// 번호를 증가한다.
		$cosmosfarm_members_subscription_checkout_title_index++;
		
		return $title;
	}
	
	/**
	 * 상품 리뷰 댓글 템플릿 파일 경로를 반환한다.
	 * @return string
	 */
	public function comments_reviews(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		
		if(file_exists(get_stylesheet_directory() . '/cosmosfarm-members/comments-reviews.php')){
			$file_path = get_stylesheet_directory() . '/cosmosfarm-members/comments-reviews.php';
		}
		else{
			$file_path = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$option->skin}/comments-reviews.php";
		}
		
		$file_path = apply_filters('cosmosfarm_members_template_comments_reviews', $file_path);
		
		if(file_exists($file_path)){
			do_action('cosmosfarm_members_skin_comments_reviews', $this);
			
			return $file_path;
		}
		
		return '';
	}
}