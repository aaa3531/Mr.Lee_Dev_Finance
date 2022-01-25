<?php
/**
 * Cosmosfarm_Members
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members {
	
	static $register_form_page = false;
	
	public function __construct(){
		add_filter('wp_get_nav_menu_items', array($this, 'nav_menu_items'), 20, 3);
		add_filter('wpmem_login_form_args', array($this, 'login_form_args'), 10, 2);
		add_filter('wpmem_login_form_buttons', array($this, 'login_form_buttons'), 10, 2);
		add_filter('wpmem_register_form_args', array($this, 'register_form_args'), 10, 1);
		add_filter('wpmem_register_fields_arr', array($this, 'register_fields_arr'), 10, 2);
		add_filter('wpmem_register_form_rows', array($this, 'register_form_rows'), 10, 2);
		add_filter('wpmem_login_failed_args', array($this, 'login_failed_args'), 10, 1);
		add_filter('wpmem_msg_dialog_arr', array($this, 'msg_dialog_arr'), 10, 2);
		add_filter('wpmem_restricted_msg', array($this, 'restricted_msg'), 10, 1);
		add_filter('wpmem_inc_login_inputs', array($this, 'inc_login_inputs'), 10, 1);
		add_filter('wpmem_pre_validate_form', array($this, 'pre_validate_form'), 10, 2);
		add_filter('wpmem_register_data', array($this, 'register_data'), 10, 2);
		add_filter('wpmem_pwdreset_args', array($this, 'pwdreset_args'), 10, 1);
		add_filter('wpmem_inc_resetpassword_inputs', array($this, 'inc_resetpassword_inputs'), 10, 1);
		add_filter('wpmem_default_text_strings', array($this, 'default_text_strings'), 20, 1);
		add_filter('login_url', array($this, 'login_url'), 10, 2);
		add_filter('login_redirect', array($this, 'login_redirect'), 10, 3);
		add_filter('wpmem_login_redirect', array($this, 'login_redirect'), 10, 1);
		add_filter('register_url', array($this, 'register_url'));
		add_filter('woocommerce_checkout_fields' , array($this, 'woocommerce_checkout_fields'), 10, 1);
		add_filter('woocommerce_billing_fields', array($this, 'woocommerce_billing_fields'), 10, 1);
		add_filter('woocommerce_shipping_fields', array($this, 'woocommerce_shipping_fields'), 10, 2);
		add_filter('get_avatar', array($this, 'get_avatar'), 1, 6);
		add_filter('get_avatar_url', array($this, 'get_avatar_url'), 1, 3);
		add_action('wp_footer', array($this, 'user_required'));
		add_action('wp_footer', array($this, 'print_purchase_tracking_code'));
		add_action('wp_footer', array($this, 'print_sign_up_tracking_code'));
		add_action('wpmem_pre_register_data', array($this, 'pre_register_data'));
		add_action('wpmem_pre_update_data', array($this, 'pre_update_data'));
		add_action('kboard_comments_login_content', array($this, 'kboard_comments_login_content'), 20, 3);
		add_action('wpmem_post_register_data', array($this, 'post_register_data'), 10, 1);
		add_action('wpmem_register_redirect', array($this, 'register_redirect'), 10, 1);
		add_filter('the_content', array($this, 'page_restriction'), 10, 1);
		add_filter('the_content', array($this, 'login_page_message'), 10, 1);
		add_action('woocommerce_before_checkout_form', array($this, 'woocommerce_checkout_add_daum_postcode'), 10);
		add_filter('manage_users_columns', array($this, 'manage_users_columns'), 10, 1);
		add_filter('manage_users_sortable_columns', array($this, 'manage_users_sortable_columns'), 10, 1);
		add_filter('manage_users_custom_column', array($this, 'manage_users_custom_column'), 10, 3);
		add_action('cosmosfarm_members_subscription_request_pay', array($this, 'subscription_send_sms_paid'), 10, 2);
		add_action('cosmosfarm_members_subscription_again_success', array($this, 'subscription_send_sms_again'), 10, 2);
		add_action('cosmosfarm_members_subscription_again_failure', array($this, 'subscription_send_sms_again_failure'), 10, 2);
		add_action('cosmosfarm_members_subscription_expiry', array($this, 'subscription_send_sms_expiry'), 10, 2);
		
		if(is_admin()){
			add_action('pre_user_query', array($this, 'manage_users_query'), 10, 1);
			add_action('admin_footer', array($this, 'admin_footer'));
		}
	}
	
	public function add_admin_menu(){
		global $_wp_last_utility_menu;
		
		$option = get_cosmosfarm_members_option();
		
		$_wp_last_utility_menu++;
		add_menu_page('코스모스팜 회원관리', '회원가입관리', 'manage_options', 'cosmosfarm_members_setting', array($this, 'setting'), COSMOSFARM_MEMBERS_URL . '/images/icon.png', $_wp_last_utility_menu);
		add_submenu_page('cosmosfarm_members_setting', '설정', '설정', 'manage_options', 'cosmosfarm_members_setting');
		add_submenu_page('cosmosfarm_members_setting', '이용약관', '이용약관', 'manage_options', 'cosmosfarm_members_policy_service', array($this, 'policy_service'));
		add_submenu_page('cosmosfarm_members_setting', '개인정보', '개인정보', 'manage_options', 'cosmosfarm_members_policy_privacy', array($this, 'policy_privacy'));
		add_submenu_page('cosmosfarm_members_setting', '이메일 인증 가입', '이메일 인증 가입', 'manage_options', 'cosmosfarm_members_verify_email', array($this, 'verify_email_setting'));
		add_submenu_page('cosmosfarm_members_setting', '자동 등업 관리', '자동 등업 관리', 'manage_options', 'cosmosfarm_members_change_role', array($this, 'change_role_setting'));
		add_submenu_page('cosmosfarm_members_setting', '본인인증', '본인인증', 'manage_options', 'cosmosfarm_members_certification', array($this, 'certification'));
		add_submenu_page('cosmosfarm_members_setting', '보안설정', '보안설정', 'manage_options', 'cosmosfarm_members_security', array($this, 'security_setting'));
		
		if($option->save_activity_history){
			add_submenu_page('cosmosfarm_members_setting', '개인정보 활동 기록', '개인정보 활동 기록', 'manage_options', 'cosmosfarm_members_activity_history', array($this, 'activity_history'));
		}
		
		add_submenu_page('cosmosfarm_members_setting', '중복확인 설정', '중복확인 설정', 'manage_options', 'cosmosfarm_members_exists_check', array($this, 'exists_check_setting'));
		add_submenu_page('cosmosfarm_members_setting', 'SMS/알림톡 설정', 'SMS/알림톡 설정', 'manage_options', 'cosmosfarm_members_sms_setting', array($this, 'sms_setting'));
		
		if($option->alimtalk_service == 'alimtalk'){
			add_submenu_page('cosmosfarm_members_setting', '알림톡 템플릿 설정', '알림톡 템플릿 설정', 'manage_options', 'cosmosfarm_members_alimtalk_template_setting', array($this, 'alimtalk_template_setting'));
		}
		
		add_submenu_page('cosmosfarm_members_setting', '커뮤니케이션', '커뮤니케이션', 'manage_options', 'cosmosfarm_members_communication', array($this, 'communication_setting'));
		add_submenu_page('cosmosfarm_members_setting', '대량문자 발송', '대량문자 발송', 'manage_options', 'cosmosfarm_members_bulk_sms', array($this, 'bulk_sms_setting'));
		
		if($option->notifications_page_id){
			add_submenu_page('cosmosfarm_members_setting', '알림 목록', '알림 목록', 'manage_options', 'cosmosfarm_members_notification', array($this, 'notification'));
		}
		if($option->messages_page_id){
			add_submenu_page('cosmosfarm_members_setting', '쪽지 목록', '쪽지 목록', 'manage_options', 'cosmosfarm_members_message', array($this, 'message'));
		}
		
		add_submenu_page('cosmosfarm_members_setting', '정기결제 설정', '정기결제 설정', 'manage_options', 'cosmosfarm_subscription_settting', array($this, 'subscription_settting'));
		add_submenu_page('cosmosfarm_members_setting', '메일침프 설정', '메일침프 설정', 'manage_options', 'cosmosfarm_mailchimp_setting', array($this, 'mailchimp_setting'));
		
		if($option->subscription_checkout_page_id){
			$_wp_last_utility_menu++;
			add_menu_page('코스모스팜 회원관리', '정기결제', 'manage_options', 'cosmosfarm_subscription_order', array($this, 'subscription_order'), COSMOSFARM_MEMBERS_URL . '/images/icon.png', $_wp_last_utility_menu);
			add_submenu_page('cosmosfarm_subscription_order', '주문', '주문', 'manage_options', 'cosmosfarm_subscription_order');
			add_submenu_page('cosmosfarm_subscription_order', '상품', '상품', 'manage_options', 'cosmosfarm_subscription_product', array($this, 'subscription_product'));
			add_submenu_page('cosmosfarm_subscription_order', '상품 추가하기', '상품 추가하기', 'manage_options', 'cosmosfarm_subscription_product_setting', array($this, 'subscription_product_setting'));
			add_submenu_page('cosmosfarm_subscription_order', '쿠폰', '쿠폰', 'manage_options', 'cosmosfarm_subscription_coupon', array($this, 'subscription_coupon'));
			add_submenu_page('cosmosfarm_subscription_order', '쿠폰 추가하기', '쿠폰 추가하기', 'manage_options', 'cosmosfarm_subscription_coupon_setting', array($this, 'subscription_coupon_setting'));
			//add_submenu_page('cosmosfarm_subscription_order', '알림', '알림', 'manage_options', 'cosmosfarm_subscription_notification', array($this, 'subscription_notification'));
			//add_submenu_page('cosmosfarm_subscription_order', '알림 추가하기', '알림 추가하기', 'manage_options', 'cosmosfarm_subscription_notification_setting', array($this, 'subscription_notification_setting'));
		}
		
		do_action('cosmosfarm_members_admin_menu');
	}
	
	public function setting(){
		wp_enqueue_script('jquery-ui-sortable');
		$nav_menus = wp_get_nav_menus();
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/setting.php';
	}
	
	public function policy_service(){
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/policy_service.php';
	}
	
	public function policy_privacy(){
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/policy_privacy.php';
	}
	
	public function certification(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/certification.php';
	}
	
	public function verify_email_setting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/verify_email.php';
	}
	
	public function change_role_setting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/change_role.php';
	}
	
	public function security_setting(){
		$option = get_cosmosfarm_members_option();
		$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
		
		if($tab == 'dormant_member'){
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/security_dormant_member.php';
		}
		else{
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/security.php';
		}
	}
	
	public function activity_history(){
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Activity_History_Table.class.php';
		$table = new Cosmosfarm_Members_Activity_History_Table();
		if(isset($_POST['activity_history_id'])){
			$action = $table->current_action();
			if($action == 'delete'){
				foreach($_POST['activity_history_id'] as $key=>$value){
					
				}
			}
		}
		$table->prepare_items();
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/activity_history.php';
	}
	
	public function exists_check_setting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/exists_check.php';
	}
	
	public function sms_setting(){
		$option = get_cosmosfarm_members_option();
		$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'sms';
		 
		if($tab == 'sms'){
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/sms_setting.php';
		}
		else if($tab == 'alimtalk'){
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/alimtalk_setting.php';
		}
	}
	
	public function alimtalk_template_setting(){
		$option = get_cosmosfarm_members_option();
		$fields = $option->alimtalk_template;
		$sms = get_cosmosfarm_members_sms();
		$result = $sms->get_alimtalk_template();
		
		if(isset($_GET['action']) && ($_GET['action'] == 'alimtalk_template_new')){
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/alimtalk_template_new.php';
		}
		else{
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/alimtalk_template.php';
		}
	}
	
	public function communication_setting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/communication.php';
	}

	public function bulk_sms_setting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/bulk_sms.php';
	}
	
	/**
	 * 알림 목록 메뉴
	 */
	public function notification(){
		$notification_id = isset($_GET['notification_id']) ? intval($_GET['notification_id']) : '';
		$action = isset($_GET['action']) ? intval($_GET['action']) : '';
		if($notification_id){
			$this->notification_setting($notification_id);
		}
		else if($action == 'new'){
			$this->notification_setting();
		}
		else{
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Notification_Table.class.php';
			$table = new Cosmosfarm_Members_Notification_Table();
			if(isset($_POST['notification_id'])){
				$action = $table->current_action();
				if($action == 'delete'){
					foreach($_POST['notification_id'] as $key=>$notification_id){
						$notification = new Cosmosfarm_Members_Notification($notification_id);
						$notification->delete();
					}
				}
			}
			$table->prepare_items();
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/notification.php';
		}
	}
	
	/**
	 * 알림 편집 화면
	 * @param string $notification_id
	 */
	public function notification_setting($notification_id=''){
		$option = get_cosmosfarm_members_option();
		$notification = new Cosmosfarm_Members_Notification($notification_id);
		
		$from_user_id = $notification->get_from_user_id();
		$from_user = get_userdata($from_user_id);
		
		$to_user_id = $notification->get_to_user_id();
		$to_user = get_userdata($to_user_id);
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/notification_setting.php';
	}
	
	/**
	 * 쪽지 목록 메뉴
	 */
	public function message(){
		$message_id = isset($_GET['message_id']) ? intval($_GET['message_id']) : '';
		$action = isset($_GET['action']) ? intval($_GET['action']) : '';
		if($message_id){
			$this->message_setting($message_id);
		}
		else if($action == 'new'){
			$this->message_setting();
		}
		else{
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Message_Table.class.php';
			$table = new Cosmosfarm_Members_Message_Table();
			if(isset($_POST['message_id'])){
				$action = $table->current_action();
				if($action == 'delete'){
					foreach($_POST['message_id'] as $key=>$message_id){
						$message = new Cosmosfarm_Members_Message($message_id);
						$message->delete();
					}
				}
			}
			$table->prepare_items();
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/message.php';
		}
	}
	
	/**
	 * 쪽지 편집 화면
	 * @param string $message_id
	 */
	public function message_setting($message_id=''){
		$option = get_cosmosfarm_members_option();
		$message = new Cosmosfarm_Members_Message($message_id);
		
		$from_user_id = $message->get_from_user_id();
		$from_user = get_userdata($from_user_id);
		
		$to_user_id = $message->get_to_user_id();
		$to_user = get_userdata($to_user_id);
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/message_setting.php';
	}
	
	public function subscription_settting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_settting.php';
	}
	
	public function subscription_order(){
		if(isset($_GET['order_id'])){
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : '';
			$this->subscription_order_setting($order_id);
		}
		else if(isset($_GET['action']) && ($_GET['action'] == 'order_new')){
			$this->subscription_order_new();
		}
		else{
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Subscription_Order_Table.class.php';
			$table = new Cosmosfarm_Members_Subscription_Order_Table();
			if(isset($_POST['order_id'])){
				$action = $table->current_action();
				if($action == 'refund'){
					foreach($_POST['order_id'] as $key=>$order_id){
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
								
								echo sprintf('<div class="notice notice-success"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), '결제가 취소되었습니다.');
							}
							else{
								echo sprintf('<div class="notice notice-error"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), esc_html($cancel_result->error_message));
							}
						}
						else{
							$order->set_status_cancelled();
							$order->execute_expiry_action();
							
							cosmosfarm_members_send_notification(array(
								'to_user_id' => $order->user()->ID,
								'content'    => sprintf('<strong>%s</strong> 결제가 취소되었습니다.', $order->title()),
							));
							
							echo sprintf('<div class="notice notice-success"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), '결제가 취소되었습니다.');
						}
					}
				}
				else if($action == 'refund_and_delete'){
					foreach($_POST['order_id'] as $key=>$order_id){
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
								$order->delete();
								
								cosmosfarm_members_send_notification(array(
									'to_user_id' => $order->user()->ID,
									'content'    => sprintf('<strong>%s</strong> 결제가 취소되었습니다.', $order->title()),
									'meta_input' => array(
										'url'      => $cancel_result->receipt_url,
										'url_name' =>'영수증',
									),
								));
								
								echo sprintf('<div class="notice notice-success"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), '결제를 취소하고 영구적으로 삭제했습니다.');
							}
							else{
								echo sprintf('<div class="notice notice-error"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), esc_html($cancel_result->error_message));
							}
						}
						else{
							$order->set_status_cancelled();
							$order->execute_expiry_action();
							$order->delete();
							
							cosmosfarm_members_send_notification(array(
								'to_user_id' => $order->user()->ID,
								'content'    => sprintf('<strong>%s</strong> 결제가 취소되었습니다.', $order->title()),
							));
							
							echo sprintf('<div class="notice notice-success"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), '결제를 취소하고 영구적으로 삭제했습니다.');
						}
					}
				}
				else if($action == 'delete'){
					foreach($_POST['order_id'] as $key=>$order_id){
						$order = new Cosmosfarm_Members_Subscription_Order($order_id);
						$order->delete();
						
						echo sprintf('<div class="notice notice-success"><p>[%d] %s: %s</p></div>', $order->ID(), esc_html($order->title()), '영구적으로 삭제했습니다.');
					}
				}
			}
			$table->prepare_items();
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_order.php';
		}
	}
	
	public function subscription_order_new(){
		wp_enqueue_script('jquery-ui-autocomplete');
		
		$product = new Cosmosfarm_Members_Subscription_Product();
		
		$args = array(
			'post_type'      => $product->post_type,
			'order'          => 'DESC',
			'orderby'        => 'ID',
			'posts_per_page' => '-1'
		);
		$product_list = new WP_Query($args);
		
		$bring_order_id = isset($_GET['bring_order_id']) ? intval($_GET['bring_order_id']) : '';
		$order = new Cosmosfarm_Members_Subscription_Order($bring_order_id);
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_order_new.php';
	}
	
	public function subscription_order_setting($order_id=''){
		$order = new Cosmosfarm_Members_Subscription_Order($order_id);
		if($order->ID()){
			$product = new Cosmosfarm_Members_Subscription_Product($order->product_id());
			$option = get_cosmosfarm_members_option();
			$user = $order->user();
			
			$args = array(
				'post_type'      => $product->post_type,
				'order'          => 'DESC',
				'orderby'        => 'ID',
				'posts_per_page' => '-1'
			);
			$product_list = new WP_Query($args);
			
			$coupon = new Cosmosfarm_Members_Subscription_Coupon();
			$args = array(
				'post_type'      => $coupon->post_type,
				'order'          => 'DESC',
				'orderby'        => 'ID',
				'posts_per_page' => '-1'
			);
			$coupon_list = new WP_Query($args);
			
			do_action('cosmosfarm_members_before_subscription_order_setting', $order, $product);
			
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_order_setting.php';
			
			do_action('cosmosfarm_members_after_subscription_order_setting', $order, $product);
		}
		else{
			wp_die('주문정보가 없습니다.');
		}
	}
	
	public function subscription_product(){
		$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : '';
		if($product_id){
			$this->subscription_product_setting($product_id);
		}
		else{
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Subscription_Product_Table.class.php';
			$table = new Cosmosfarm_Members_Subscription_Product_Table();
			if(isset($_POST['product_id'])){
				$action = $table->current_action();
				if($action == 'delete'){
					foreach($_POST['product_id'] as $key=>$product_id){
						$product = new Cosmosfarm_Members_Subscription_Product($product_id);
						$product->delete();
					}
				}
			}
			$table->prepare_items();
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_product.php';
		}
	}
	
	public function subscription_product_setting($product_id=''){
		$product = new Cosmosfarm_Members_Subscription_Product($product_id);
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Subscription_Checkout_Fields_Table.class.php';
		$table = new Cosmosfarm_Members_Subscription_Checkout_Fields_Table();
		$table->prepare_items();
		$option = get_cosmosfarm_members_option();
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_media();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_product_setting.php';
	}
	
	/**
	 * 정기결제 쿠폰
	 */
	public function subscription_coupon(){
		$coupon_id = isset($_GET['coupon_id']) ? intval($_GET['coupon_id']) : '';
		if($coupon_id){
			$this->subscription_coupon_setting($coupon_id);
		}
		else{
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Subscription_Coupon_Table.class.php';
			$table = new Cosmosfarm_Members_Subscription_Coupon_Table();
			if(isset($_POST['coupon_id'])){
				$action = $table->current_action();
				if($action == 'delete'){
					foreach($_POST['coupon_id'] as $key=>$coupon_id){
						$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
						$coupon->delete();
					}
				}
			}
			$table->prepare_items();
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_coupon.php';
		}
	}
	
	/**
	 * 정기결제 쿠폰 추가하기
	 * @param string $coupon_id
	 */
	public function subscription_coupon_setting($coupon_id=''){
		$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
		$option = get_cosmosfarm_members_option();
		
		$per_page = -1;
		$product = new Cosmosfarm_Members_Subscription_Product();
		$args = array(
			'post_type'      => $product->post_type,
			'orderby'        => 'ID',
			'posts_per_page' => $per_page
		);
		
		$query = new WP_Query($args);
		$product_list = $query->posts;
		
		wp_enqueue_script('jquery-ui-datepicker');
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_coupon_setting.php';
	}
	
	/**
	 * 정기결제 알림
	 */
	public function subscription_notification(){
		$notification_id = isset($_GET['notification_id']) ? intval($_GET['notification_id']) : '';
		if($notification_id){
			$this->subscription_notification_setting($notification_id);
		}
		else{
			include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_Subscription_Notification_Table.class.php';
			$table = new Cosmosfarm_Members_Subscription_Notification_Table();
			/*
			if(isset($_POST['coupon_id'])){
				$action = $table->current_action();
				if($action == 'delete'){
					foreach($_POST['coupon_id'] as $key=>$coupon_id){
						//$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
						//$coupon->delete();
					}
				}
			}
			*/
			$table->prepare_items();
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_notification.php';
		}
	}
	
	/**
	 * 정기결제 알림 추가하기
	 * @param string $notification_id
	 */
	public function subscription_notification_setting($notification_id=''){
		$notification = new Cosmosfarm_Members_Subscription_Notification($notification_id);
		$option = get_cosmosfarm_members_option();
		
		$per_page = -1;
		$product = new Cosmosfarm_Members_Subscription_Product();
		$args = array(
			'post_type'      => $product->post_type,
			'orderby'        => 'ID',
			'posts_per_page' => $per_page
		);
		
		$query = new WP_Query($args);
		$product_list = $query->posts;
		
		wp_enqueue_script('jquery-ui-datepicker');
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_notification_setting.php';
	}
	
	public function mailchimp_setting(){
		$option = get_cosmosfarm_members_option();
		include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/mailchimp_setting.php';
	}
	
	public function nav_menu_items($items, $menu, $args){
		if(!is_admin()){
			if(get_cosmosfarm_menu_add_login() && in_array($menu->slug, get_cosmosfarm_login_menus())){
				$profile_url = get_cosmosfarm_members_profile_url();
				
				$menu_items['login'] = array('title'=>__('Log In', 'cosmosfarm-members'), 'url'=>get_cosmosfarm_members_login_url(), 'order'=>1, 'classes'=>'cosmosfarm-members-login');
				$menu_items['logout'] = array('title'=>__('Log Out', 'cosmosfarm-members'), 'url'=>get_cosmosfarm_members_logout_url(), 'order'=>1, 'classes'=>'cosmosfarm-members-logout');
				$menu_items['register'] = array('title'=>__('Register', 'cosmosfarm-members'), 'url'=>wp_registration_url(), 'order'=>2, 'classes'=>'cosmosfarm-members-register');
				$menu_items['account'] = array('title'=>__('Account', 'cosmosfarm-members'), 'url'=>$profile_url, 'order'=>2, 'classes'=>'cosmosfarm-members-account');
				
				$menu_items = apply_filters('cosmosfarm_members_menu_items', $menu_items, $menu, $args);
				
				if(is_user_logged_in()){
					if($menu_items['account']['order'] <= $menu_items['logout']['order']){
						if($menu_items['account']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['account']);
						if($menu_items['logout']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['logout']);
					}
					else{
						if($menu_items['logout']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['logout']);
						if($menu_items['account']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['account']);
					}
				}
				else{
					if($menu_items['register']['order'] <= $menu_items['login']['order']){
						if($menu_items['register']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['register']);
						if($menu_items['login']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['login']);
					}
					else{
						if($menu_items['login']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['login']);
						if($menu_items['register']['url']) $items[] = cosmosfarm_members_menu_item($menu_items['register']);
					}
				}
			}
		}
		return $items;
	}
	
	public function login_form_args($args, $action){
		$option = get_cosmosfarm_members_option();
		
		switch($action){
			case 'login'; $class_name = 'signin-form'; break;
			case 'pwdreset'; $class_name = 'pwdreset-form'; break;
			case 'pwdchange'; $class_name = 'pwdchange-form'; break;
			case 'getusername'; $class_name = 'getusername-form'; break;
		}
		
		if(!is_array($args)){
			$args = array();
		}
		$args['main_div_before'] = "<div class=\"cosmosfarm-members-form {$class_name} {$option->skin}\">";
		return $args;
	}
	
	public function login_form_buttons($args, $action){
		global $wpmem;
		
		switch($action){
			case 'login'; $button_text = $wpmem->get_text("login_button"); break;
			case 'pwdreset'; $button_text = $wpmem->get_text("pwdreset_button"); break;
			case 'pwdchange'; $button_text = $wpmem->get_text("pwdchg_button"); break;
			case 'getusername'; $button_text = $wpmem->get_text("username_button"); break;
		}
		
		if($action == 'login'){
			$args = '<div class="button_div"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever">' . $wpmem->get_text('remember_me') . '</label><input type="submit" value="' . $button_text . '" class="buttons"></div>';
			
			$option = get_cosmosfarm_members_option();
			$redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url();
			
			if($option->social_login_active){
				$args .= $this->social_buttons($action, $redirect_to);
			}
		}
		else{
			$args = '<div class="button_div"><input type="submit" value="' . $button_text . '" class="buttons"></div>';
		}
		
		return $args;
	}
	
	public function social_buttons($action='', $redirect_to='', $skin='', $file=''){
		$option = get_cosmosfarm_members_option();
		
		if(!$skin){
			$skin = $option->skin;
		}
		
		if(!$file){
			$file = 'social-buttons';
		}
		
		ob_start();
		$skin_path = COSMOSFARM_MEMBERS_URL . "/skin/{$skin}";
		include COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$skin}/{$file}.php";
		return ob_get_clean();
	}
	
	public function register_form_args($args){
		$option = get_cosmosfarm_members_option();
		if(!is_array($args)){
			$args = array();
		}
		$args['main_div_before'] = "<div class=\"cosmosfarm-members-form signup-form {$option->skin}\">";
		return $args;
	}
	
	public function register_fields_arr($rows, $toggle){
		/*
		 if(isset($rows['user_email'])){
		 $rows['user_email'][1] = __('Email', 'cosmosfarm-members');
		 $rows['user_email']['label'] = __('Email', 'cosmosfarm-members');
		 }
		 */
		return $rows;
	}
	
	public function register_form_rows($rows, $toggle){
		self::$register_form_page = true;
		
		wp_enqueue_script('daum-postcode');
		
		$option = get_cosmosfarm_members_option();
		$keys = array_keys($rows);
		
		$register_form_policy_fields = apply_filters('cosmosfarm_members_register_form_policy_fields', array('policy_service', 'policy_privacy'));
		
		foreach($rows as $meta_key=>$field){
			if($field['type'] == 'checkbox' && !in_array($meta_key, $register_form_policy_fields)){
				$field['label'] = $field['field_before'] . $field['label'];
				$field['field'] = $field['field'] . $field['field_after'];
				$field['field_before'] = '';
				$field['field_after'] = '';
				$rows[$meta_key] = $field;
			}
		}
		
		foreach($option->exists_check as $exists_check_field){
			if(isset($rows[$exists_check_field])){
				if($exists_check_field == 'username' && $toggle != 'new') continue;
				$rows[$exists_check_field]['field'] = '<div class="add-buttons">' . $rows[$exists_check_field]['field'] . '<div class="add-buttons-wrap">';
				$rows[$exists_check_field]['field'] .= '<button type="button" onclick="cosmosfarm_members_exists_check(\'' . $exists_check_field . '\', this.form)">'.__('Check Availability', 'cosmosfarm-members').'</button>';
				$rows[$exists_check_field]['field'] .= '<input type="hidden" class="cosmosfarm_members_exists_check_field '.$exists_check_field.'">';
				$rows[$exists_check_field]['field'] .= '</div></div>';
			}
		}
		
		if(defined('COSMOSFARM_MEMBERS_JAPAN_ZIP') && COSMOSFARM_MEMBERS_JAPAN_ZIP === true){
			if(isset($rows['zip'])){
				$rows['zip']['field'] = '<div class="add-buttons"><input name="zip" type="text" id="zip" value="' . $rows['zip']['value'] . '" class="textbox"><div class="add-buttons-wrap"><button type="button" onclick="cosmosfarm_find_japan_address()">'.__('Find', 'cosmosfarm-members').'</button></div></div>';
			}
		}
		else if(get_cosmosfarm_members_locale() == 'ko_KR' && !$option->postcode_service_disabled){
			if(isset($rows['zip'])){
				wp_enqueue_script('daum-postcode');
				$rows['zip']['field'] = '<input name="zip" type="text" id="zip" value="' . $rows['zip']['value'] . '" class="textbox cursor-pointer" onclick="cosmosfarm_members_open_postcode()" readonly>';
			}
			
			if(isset($rows['addr1'])){
				$rows['addr1']['field'] = '<input name="addr1" type="text" id="addr1" value="' . $rows['addr1']['value'] . '" class="textbox cursor-pointer" onclick="cosmosfarm_members_open_postcode()" readonly>';
			}
		}
		
		if(isset($rows['policy_service'])){
			$policy_service = wpautop(get_cosmosfarm_policy_service_content());
			$rows['policy_service']['field_before'] = '<div class="div_checkbox agree">';
			$rows['policy_service']['field_before'] .= "<div class=\"policy_content\">{$policy_service}</div>";
			$rows['policy_service']['field'] = '<label>' . $rows['policy_service']['field'] . sprintf(__('I agree to %s.', 'cosmosfarm-members'), $rows['policy_service']['label_text']) . '</label>';
		}
		
		if(isset($rows['policy_privacy'])){
			$policy_privacy = wpautop(get_cosmosfarm_policy_privacy_content());
			$rows['policy_privacy']['field_before'] = '<div class="div_checkbox agree">';
			$rows['policy_privacy']['field_before'] .= "<div class=\"policy_content\">{$policy_privacy}</div>";
			$rows['policy_privacy']['field'] = '<label>' . $rows['policy_privacy']['field'] . sprintf(__('I agree to %s.', 'cosmosfarm-members'), $rows['policy_privacy']['label_text']) . '</label>';
		}
		
		if(isset($rows['username'])){
			if($option->allow_email_login){
				unset($rows['username']);
			}
		}
		
		if(isset($rows['birthday'])){
			wp_enqueue_script('jquery-ui-datepicker');
			wp_register_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/flick/jquery-ui.css');
			wp_enqueue_style('jquery-ui');
		}
		
		if($option->iamport_id && $option->use_certification){
			if($option->certification_name_field && in_array($option->certification_name_field, $keys)) $add_certify_index[$option->certification_name_field] = array_search($option->certification_name_field, $keys);
			if($option->certification_gender_field && in_array($option->certification_gender_field, $keys)) $add_certify_index[$option->certification_gender_field] = array_search($option->certification_gender_field, $keys);
			if($option->certification_birth_field && in_array($option->certification_birth_field, $keys)) $add_certify_index[$option->certification_birth_field] = array_search($option->certification_birth_field, $keys);
			if(COSMOSFARM_MEMBERS_CERTIFIED_PHONE){
				if($option->certification_carrier_field && in_array($option->certification_carrier_field, $keys)) $add_certify_index[$option->certification_carrier_field] = array_search($option->certification_carrier_field, $keys);
				if($option->certification_phone_field && in_array($option->certification_phone_field, $keys)) $add_certify_index[$option->certification_phone_field] = array_search($option->certification_phone_field, $keys);
			}
			if(isset($add_certify_index) && is_array($add_certify_index)){
				asort($add_certify_index);
				reset($add_certify_index);
				$add_certify = key($add_certify_index);
				
				if($option->certification_name_field){
					if(isset($rows[$option->certification_name_field]) && $add_certify == $option->certification_name_field){
						$rows[$option->certification_name_field]['field'] = '<div class="add-buttons"><input name="'.$option->certification_name_field.'" type="text" id="'.$option->certification_name_field.'" value="' . $rows[$option->certification_name_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly><div class="add-buttons-wrap"><button type="button" onclick="cosmosfarm_members_certification()">'.__('Certify', 'cosmosfarm-members').'</button></div></div>';
						$rows[$option->certification_name_field]['field'] .= "<script>jQuery(document).ready(function(){IMP.init('{$option->iamport_id}')})</script>";
						wp_enqueue_script('iamport-payment');
					}
					else{
						$rows[$option->certification_name_field]['field'] = '<input name="'.$option->certification_name_field.'" type="text" id="'.$option->certification_name_field.'" value="' . $rows[$option->certification_name_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly>';
					}
				}
				
				if($option->certification_gender_field){
					if(isset($rows[$option->certification_gender_field]) && $add_certify == $option->certification_gender_field){
						$rows[$option->certification_gender_field]['field'] = '<div class="add-buttons"><input name="'.$option->certification_gender_field.'" type="text" id="'.$option->certification_gender_field.'" value="' . $rows[$option->certification_gender_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly><div class="add-buttons-wrap"><button type="button" onclick="cosmosfarm_members_certification()">'.__('Certify', 'cosmosfarm-members').'</button></div></div>';
						$rows[$option->certification_gender_field]['field'] .= "<script>jQuery(document).ready(function(){IMP.init('{$option->iamport_id}')})</script>";
						wp_enqueue_script('iamport-payment');
					}
					else{
						$rows[$option->certification_gender_field]['field'] = '<input name="'.$option->certification_gender_field.'" type="text" id="'.$option->certification_gender_field.'" value="' . $rows[$option->certification_gender_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly>';
					}
				}
				
				if($option->certification_birth_field){
					if(isset($rows[$option->certification_birth_field]) && $add_certify == $option->certification_birth_field){
						$rows[$option->certification_birth_field]['field'] = '<div class="add-buttons"><input name="'.$option->certification_birth_field.'" type="text" id="'.$option->certification_birth_field.'" value="' . $rows[$option->certification_birth_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly><div class="add-buttons-wrap"><button type="button" onclick="cosmosfarm_members_certification()">'.__('Certify', 'cosmosfarm-members').'</button></div></div>';
						$rows[$option->certification_birth_field]['field'] .= "<script>jQuery(document).ready(function(){IMP.init('{$option->iamport_id}')})</script>";
						wp_enqueue_script('iamport-payment');
					}
					else{
						$rows[$option->certification_birth_field]['field'] = '<input name="'.$option->certification_birth_field.'" type="text" id="'.$option->certification_birth_field.'" value="' . $rows[$option->certification_birth_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly>';
					}
				}
				
				if(COSMOSFARM_MEMBERS_CERTIFIED_PHONE){
					if($option->certification_carrier_field){
						if(isset($rows[$option->certification_carrier_field]) && $add_certify == $option->certification_carrier_field){
							$rows[$option->certification_carrier_field]['field'] = '<div class="add-buttons"><input name="'.$option->certification_carrier_field.'" type="text" id="'.$option->certification_carrier_field.'" value="' . $rows[$option->certification_carrier_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly><div class="add-buttons-wrap"><button type="button" onclick="cosmosfarm_members_certification()">'.__('Certify', 'cosmosfarm-members').'</button></div></div>';
							$rows[$option->certification_carrier_field]['field'] .= "<script>jQuery(document).ready(function(){IMP.init('{$option->iamport_id}')})</script>";
							wp_enqueue_script('iamport-payment');
						}
						else{
							$rows[$option->certification_carrier_field]['field'] = '<input name="'.$option->certification_carrier_field.'" type="text" id="'.$option->certification_carrier_field.'" value="' . $rows[$option->certification_carrier_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly>';
						}
					}
					
					if($option->certification_phone_field){
						if(isset($rows[$option->certification_phone_field]) && $add_certify == $option->certification_phone_field){
							$rows[$option->certification_phone_field]['field'] = '<div class="add-buttons"><input name="'.$option->certification_phone_field.'" type="text" id="'.$option->certification_phone_field.'" value="' . $rows[$option->certification_phone_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly><div class="add-buttons-wrap"><button type="button" onclick="cosmosfarm_members_certification()">'.__('Certify', 'cosmosfarm-members').'</button></div></div>';
							$rows[$option->certification_phone_field]['field'] .= "<script>jQuery(document).ready(function(){IMP.init('{$option->iamport_id}')})</script>";
							wp_enqueue_script('iamport-payment');
						}
						else{
							$rows[$option->certification_phone_field]['field'] = '<input name="'.$option->certification_phone_field.'" type="text" id="'.$option->certification_phone_field.'" value="' . $rows[$option->certification_phone_field]['value'] . '" class="textbox cursor-default" onclick="cosmosfarm_members_certification()" readonly>';
						}
					}
				}
			}
		}
		
		if($toggle == 'new'){
			if($option->use_strong_password){
				wp_enqueue_script('password-strength-meter');
				$password_strength_meter_display = '<span class="password-strength-meter-display bad">' . __('Password must consist of 8 digits, including English, numbers and special characters.', 'cosmosfarm-members') . '</span>';
				if(isset($rows['confirm_password'])){
					$rows['confirm_password']['field_after'] = $password_strength_meter_display . $rows['confirm_password']['field_after'];
					$password_strength_meter_display = '';
				}
				if(isset($rows['password'])){
					$rows['password']['field_after'] = $password_strength_meter_display . $rows['password']['field_after'];
				}
			}
		}
		
		// 내 정보 수정에서 이용약관, 개인정보 체크박스를 숨긴다.
		if($toggle == 'edit'){
			// 이용약관 체크박스
			if(isset($rows['policy_service'])){
				$rows['policy_service']['label'] = '';
				$rows['policy_service']['field_before'] = '';
				$rows['policy_service']['field'] = '<input type="hidden" id="policy_service" name="policy_service" value="agree">';
			}
			
			// 개인정보 체크박스
			if(isset($rows['policy_privacy'])){
				$rows['policy_privacy']['label'] = '';
				$rows['policy_privacy']['field_before'] = '';
				$rows['policy_privacy']['field'] = '<input type="hidden" id="policy_privacy" name="policy_privacy" value="agree">';
			}
		}
		
		return $rows;
	}
	
	public function login_failed_args($args){
		global $wpmem;
		
		if(!is_array($args)){
			$args = array();
		}
		
		$user_login = isset($_POST['log'])?sanitize_user($_POST['log']):'';
		$user_password = isset($_POST['pwd'])?$_POST['pwd']:'';
		
		$option = get_cosmosfarm_members_option();
		
		$user = get_user_by('login', $user_login);
		if(!$user){
			$user = get_user_by('email', $user_login);
		}
		
		if($user && wp_check_password($user_password, $user->data->user_pass, $user->ID)){
			if(($wpmem->mod_reg == 1) && (get_user_meta($user->ID, 'active', true) != 1)){
				$args['message'] = __('<strong>ERROR</strong>: User has not been activated.', 'wp-members');
			}
			
			if($option->verify_email && get_user_meta($user->ID, 'wait_verify_email', true)){
				$verify_code = cosmosfarm_members_send_verify_email($user);
				update_user_meta($user->ID, 'wait_verify_email', $verify_code);
				$args['message'] = __('Please check the email sent to your email address.', 'cosmosfarm-members');
			}
		}
		
		if($user && $option->use_login_protect){
			$login_protect_lockdown_timestamp = get_user_meta($user->ID, 'login_protect_lockdown_timestamp', true);
			if($login_protect_lockdown_timestamp){
				if(current_time('timestamp') - $login_protect_lockdown_timestamp <= $option->login_protect_lockdown * 60){
					$args['message'] = sprintf(__('You have exceeded the maximum number of %d attempts, try again in %d minutes.', 'cosmosfarm-members'), $option->login_protect_count, $option->login_protect_lockdown);
				}
			}
		}
		
		$args['div_before'] = '<div class="cosmosfarm-members-form loginfailed"><div class="message">';
		$args['div_after'] = '</div></div>';
		return $args;
	}
	
	public function msg_dialog_arr($args, $toggle){
		$args['div_before'] = "<div class=\"cosmosfarm-members-form {$toggle}\"><div class=\"message\">";
		$args['div_after'] = '</div></div>';
		return $args;
	}
	
	public function restricted_msg($msg){
		global $wpmem, $post;
		$msg = '<p style="text-align:center">' . $wpmem->get_text("restricted_msg") . '</p>';
		
		$option = get_cosmosfarm_members_option();
		if($post && $post->ID == $option->account_page_id){
			$msg = ''; // 회원정보 페이지에서는 페이지 제한 메시지를 숨긴다.
		}
		return $msg;
	}
	
	public function inc_login_inputs($array){
		global $wpmem;
		if($array[0]['tag'] == 'log'){
			$option = get_cosmosfarm_members_option();
			if($option->allow_email_login){
				$array[0]['name'] = __( 'Email', 'cosmosfarm-members');
			}
		}
		return $array;
	}
	
	/**
	 * 데이터가 잘 입력됐는지 체크한다.
	 * @param array $fields
	 * @param string $toggle
	 * @return array
	 */
	public function pre_validate_form($fields, $toggle){
		$option = get_cosmosfarm_members_option();
		if($toggle == 'register' && $option->allow_email_login){
			if(!isset($fields['username']) || !$fields['username']){
				$fields['username'] = uniqid(); // 사용자명은 영문소문자(a-z)와 숫자만을 포함할 수 있기 때문에 (특수문자가 포함된) 이메일 대신 랜덤 문자열을 입력한다.
			}
		}
		return $fields;
	}
	
	/**
	 * 실제 입력될 데이터를 만든다.
	 * @param array $fields
	 * @param string $toggle
	 * @return array
	 */
	public function register_data($fields, $toggle){
		$option = get_cosmosfarm_members_option();
		if($toggle == 'new'){
			$display = '';
			$display_name = isset($_POST['display_name']) ? sanitize_user($_POST['display_name']) : '';
			$user_nicename = isset($_POST['user_nicename']) ? sanitize_user($_POST['user_nicename']) : '';
			$nickname = isset($_POST['nickname']) ? sanitize_user($_POST['nickname']) : '';
			
			if($option->allow_email_login){
				$user_email = explode('@', $fields['user_email']);
				$display = reset($user_email);
				$display = sanitize_user($display);
				$fields['username'] = sanitize_email($fields['user_email']);
			}
			
			$fields['display_name'] = $display_name ? $display_name : $display;
			$fields['user_nicename'] = $user_nicename ? $user_nicename : $fields['display_name'];
			$fields['nickname'] = $nickname ? $nickname : $fields['display_name'];
		}
		else if($toggle == 'edit'){
			unset($fields['username']);
		}
		return $fields;
	}
	
	public function pwdreset_args($args){
		$option = get_cosmosfarm_members_option();
		if($option->allow_email_login){
			if(!isset($args['user']) || !$args['user']){
				if(is_object($user = get_user_by('email', $args['email']))){
					$args['user'] = $user->user_login;
				}
				else if(is_object($user = get_user_by('login', $args['email']))){
					$args['user']  = $user->user_login;
					$args['email'] = $user->user_email;
				}
				else{
					$args['user'] = $args['email'];
				}
			}
		}
		return $args;
	}
	
	public function inc_resetpassword_inputs($array){
		$option = get_cosmosfarm_members_option();
		if($option->allow_email_login){
			foreach($array as $input){
				if($input['tag'] == 'email'){
					$new_array[] = $input;
				}
			}
			$array = $new_array;
		}
		return $array;
	}
	
	public function default_text_strings($args){
		if(!is_array($args)){
			$args = array();
		}
		$args['login_username'] = __('Username', 'cosmosfarm-members');
		$args['pwdreset_username'] = __('Username', 'cosmosfarm-members');
		$args['pwdreset_email'] = __('Email', 'cosmosfarm-members');
		$args['remember_me'] = __('Keep me signed in', 'cosmosfarm-members');
		$args['forgot_link_before'] = '';
		$args['forgot_link'] = __('Forgot Password', 'cosmosfarm-members');
		$args['register_link_before'] = '';
		$args['register_link'] = __('Register', 'cosmosfarm-members');
		$args['register_submit'] = __('Register', 'cosmosfarm-members');
		$args['register_required'] = '<span class="req">*</span>'.__('Required', 'cosmosfarm-members');
		$args['username_email'] = __('Email', 'cosmosfarm-members');
		$args['username_link_before'] = '';
		$args['username_link'] = __('Forgot Username', 'cosmosfarm-members');
		$args['username_button'] = __('Forgot Username', 'cosmosfarm-members');
		$args['usernamefailed'] = __('Email address does not exist.', 'cosmosfarm-members');
		$args['usernamesuccess'] = __('An email was sent to %s with your username.', 'cosmosfarm-members');
		
		return $args;
	}
	
	public function login_url($login_url, $redirect_to){
		if(!is_admin()){
			$option = get_cosmosfarm_members_option();
			
			if(isset($_GET['redirect_to']) && $_GET['redirect_to']){
				$redirect_to = '';
			}
			else if($option->login_page_id == get_the_ID()){
				$redirect_to = '';
			}
			else if(wpmem_login_url() == get_permalink()){
				$redirect_to = '';
			}
			
			$redirect_to = $this->login_redirect($redirect_to);
			
			if($option->login_page_id || $option->login_page_url){
				if($option->login_page_id){
					if($redirect_to){
						$login_url = add_query_arg(array('redirect_to'=>urlencode($redirect_to)), get_permalink($option->login_page_id));
					}
					else{
						$login_url = get_permalink($option->login_page_id);
					}
				}
				else if($option->login_page_url){
					if($redirect_to){
						$login_url = add_query_arg(array('redirect_to'=>urlencode($redirect_to)), $option->login_page_url);
					}
					else{
						$login_url = $option->login_page_url;
					}
				}
			}
			else if(wpmem_login_url()){
				$login_url = wpmem_login_url($redirect_to);
			}
		}
		return $login_url;
	}
	
	public function login_redirect($redirect_to, $request='', $user=''){
		$option = get_cosmosfarm_members_option();
		if($option->login_redirect_page == 'main'){
			$redirect_to = home_url();
		}
		else if($option->login_redirect_page == 'url' && $option->login_redirect_url){
			$redirect_to = $option->login_redirect_url;
		}
		return esc_url_raw($redirect_to);
	}
	
	public function register_url($register_url){
		if(!is_admin()){
			$option = get_cosmosfarm_members_option();
			
			if($option->register_page_id || $option->login_page_url){
				if($option->register_page_id){
					$register_url = get_permalink($option->register_page_id);
				}
				else if($option->register_page_url){
					$register_url = $option->register_page_url;
				}
			}
			else if(wpmem_register_url()){
				$register_url = wpmem_register_url();
			}
		}
		return $register_url;
	}
	
	public function woocommerce_checkout_fields($fields){
		global $woocommerce;
		if(is_user_logged_in()){
			
			if(isset($fields['billing']['billing_first_name'])){
				$fields['billing']['billing_first_name']['default'] = get_user_meta(get_current_user_id(), 'first_name', true);
			}
			if(isset($fields['billing']['billing_last_name'])){
				$fields['billing']['billing_last_name']['default'] = get_user_meta(get_current_user_id(), 'last_name', true);
			}
			if(isset($fields['billing']['billing_phone'])){
				$fields['billing']['billing_phone']['default'] = get_user_meta(get_current_user_id(), 'phone1', true);
			}
			if(isset($fields['billing']['billing_country'])){
				$fields['billing']['billing_country']['default'] = get_user_meta(get_current_user_id(), 'country', true);
			}
			if(isset($fields['billing']['billing_address_1'])){
				$fields['billing']['billing_address_1']['default'] = get_user_meta(get_current_user_id(), 'addr1', true);
			}
			if(isset($fields['billing']['billing_address_2'])){
				$fields['billing']['billing_address_2']['default'] = get_user_meta(get_current_user_id(), 'addr2', true);
			}
			if(isset($fields['billing']['billing_city'])){
				$fields['billing']['billing_city']['default'] = get_user_meta(get_current_user_id(), 'city', true);
			}
			if(isset($fields['billing']['billing_state'])){
				$fields['billing']['billing_state']['default'] = get_user_meta(get_current_user_id(), 'state', true);
			}
			
			if(isset($fields['shipping']['shipping_first_name'])){
				$fields['shipping']['shipping_first_name']['default'] = get_user_meta(get_current_user_id(), 'first_name', true);
			}
			if(isset($fields['shipping']['shipping_last_name'])){
				$fields['shipping']['shipping_last_name']['default'] = get_user_meta(get_current_user_id(), 'last_name', true);
			}
			if(isset($fields['shipping']['shipping_country'])){
				$fields['shipping']['shipping_country']['default'] = get_user_meta(get_current_user_id(), 'country', true);
			}
			if(isset($fields['shipping']['shipping_address_1'])){
				$fields['shipping']['shipping_address_1']['default'] = get_user_meta(get_current_user_id(), 'addr1', true);
			}
			if(isset($fields['shipping']['shipping_address_2'])){
				$fields['shipping']['shipping_address_2']['default'] = get_user_meta(get_current_user_id(), 'addr2', true);
			}
			if(isset($fields['shipping']['shipping_city'])){
				$fields['shipping']['shipping_city']['default'] = get_user_meta(get_current_user_id(), 'city', true);
			}
			if(isset($fields['shipping']['shipping_state'])){
				$fields['shipping']['shipping_state']['default'] = get_user_meta(get_current_user_id(), 'state', true);
			}
			
			$postcode = get_user_meta(get_current_user_id(), 'zip', true);
			if($postcode){
				$woocommerce->customer->set_postcode($postcode);
				$woocommerce->customer->set_shipping_postcode($postcode);
			}
		}
		return $fields;
	}
	
	public function woocommerce_billing_fields($fields){
		wp_enqueue_script('daum-postcode');
		return $fields;
	}
	
	public function woocommerce_shipping_fields($fields, $locale){
		wp_enqueue_script('daum-postcode');
		return $fields;
	}
	
	public function get_avatar($avatar, $id_or_email, $size, $default, $alt, $args){
		$user = false;
		
		if(is_numeric($id_or_email)){
			$id = (int) $id_or_email;
			$user = get_user_by('id', $id);
		}
		else if(is_object( $id_or_email)){
			if(!empty($id_or_email->user_id)){
				$id = (int) $id_or_email->user_id;
				$user = get_user_by('id' , $id);
			}
		}
		else{
			$user = get_user_by('email', $id_or_email);
		}
		
		if(isset($user->ID) && $user->ID){
			
			$class = '';
			if(isset($args['class']) && $args['class']){
				if(is_array($args['class'])){
					$class .= " {$args['class']} ";
				}
			}
			
			$width = $size;
			if(isset($args['width']) && $args['width']){
				$width = $args['width'];
			}
			
			$height = $size;
			if(isset($args['height']) && $args['height']){
				$width = $args['height'];
			}
			
			$cosmosfarm_members_avatar = get_user_meta($user->ID, 'cosmosfarm_members_avatar', true);
			if($cosmosfarm_members_avatar){
				$cosmosfarm_members_avatar = content_url("/uploads{$cosmosfarm_members_avatar}");
				$avatar = "<img alt='{$alt}' src='{$cosmosfarm_members_avatar}' class='avatar avatar-{$size} photo {$class}' height='{$height}' width='{$width}' />";
			}
			else{
				$social_picture = get_user_meta($user->ID, 'cosmosfarm_members_social_picture', true);
				if($social_picture){
					$avatar = "<img alt='{$alt}' src='{$social_picture}' class='avatar avatar-{$size} photo {$class}' height='{$height}' width='{$width}' />";
				}
			}
		}
		
		return $avatar;
	}
	
	public function get_avatar_url($url, $id_or_email, $args){
		$user = false;
		
		if(is_numeric($id_or_email)){
			$id = (int) $id_or_email;
			$user = get_user_by('id', $id);
		}
		else if(is_object($id_or_email)){
			if(!empty($id_or_email->user_id)){
				$id = (int) $id_or_email->user_id;
				$user = get_user_by('id' , $id);
			}
		}
		else{
			$user = get_user_by('email', $id_or_email);
		}
		
		if(isset($user->ID) && $user->ID){
			
			$cosmosfarm_members_avatar = get_user_meta($user->ID, 'cosmosfarm_members_avatar', true);
			if($cosmosfarm_members_avatar){
				$cosmosfarm_members_avatar = content_url("/uploads{$cosmosfarm_members_avatar}");
				$url = $cosmosfarm_members_avatar;
			}
			else{
				$social_picture = get_user_meta($user->ID, 'cosmosfarm_members_social_picture', true);
				if($social_picture){
					$url = $social_picture;
				}
			}
		}
		
		return $url;
	}
	
	/**
	 * 회원정보에서 필수 정보값이 입력되어 있는지 확인한다.
	 */
	public function user_required(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		if($option->user_required){
			if(!self::$register_form_page && is_user_logged_in()){
				$user_id = get_current_user_id();
				$current_user = get_userdata($user_id);
				$profile_url = get_cosmosfarm_members_profile_url();
				
				if($profile_url){
					$profile_url = add_query_arg(array('a'=>'edit'), $profile_url);
				}
				else{
					$profile_url = admin_url('profile.php');
				}
				
				do_action('cosmosfarm_members_pre_user_required', $current_user, $profile_url);
				
				$wpmem_fields = apply_filters('wpmem_register_fields_arr', wpmem_fields(), 'user_required');
				
				$meta_arr = array('username', 'password', 'confirm_password', 'password_confirm');
				
				foreach($wpmem_fields as $meta_key=>$field){
					if($field['required'] && !in_array($meta_key, $meta_arr)){
						if($meta_key == 'confirm_email') $meta_key = 'user_email';
						if(!$current_user->{$meta_key}){
							echo '<script>alert("'.__('Please enter the required information.', 'cosmosfarm-members').'");</script>';
							echo "<script>window.location.href='{$profile_url}';</script>";
							return;
						}
					}
				}
				
				do_action('cosmosfarm_members_user_required', $current_user, $profile_url);
			}
		}
	}
	
	/**
	 * 구매 전환추적 코드를 출력한다.
	 */
	public function print_purchase_tracking_code(){
		global $post;
		
		$option = get_cosmosfarm_members_option();
		if(is_page() && $post && $option->subscription_payment_completed_page_id){
			if($post->ID == $option->subscription_payment_completed_page_id){
				$product_id = isset($_GET['cosmosfarm_product_id']) ? intval($_GET['cosmosfarm_product_id']) : '';
				
				$product = new Cosmosfarm_Members_Subscription_Product();
				$product->init_with_id($product_id);
				
				if($product->ID()){
					?>
					<script>
					jQuery(document).ready(function(){
						if(typeof fbq != 'undefined'){
							fbq('track', 'Purchase', {currency: 'KRW', value: <?php echo intval($product->price())?>});
						}
						if(typeof gtag != 'undefined'){
							gtag('event', 'purchase', {
								'transaction_id': '<?php echo uniqid()?>',
								'value': <?php echo intval($product->price())?>,
								'currency': 'KRW',
								'items': [
									{
										'id': '<?php echo intval($product->ID())?>',
										'name': '<?php echo esc_js(wp_strip_all_tags($product->title()))?>',
										'quantity': 1,
										'price': '<?php echo intval($product->price())?>'
									}
								]
							});
						}
					});
					</script>
					<?php
					
					do_action('cosmosfarm_members_print_purchase_tracking_code', $product);
				}
			}
		}
	}
	
	/**
	 * 회원가입 추적 코드를 출력한다.
	 */
	public function print_sign_up_tracking_code(){
		global $post;
		
		$register_success = isset($_GET['register_success']) ? intval($_GET['register_success']) : '';
		if($register_success){
			?>
			<script>
			jQuery(document).ready(function(){
				if(typeof gtag != 'undefined'){
					gtag('event', 'sign_up', {'method': ''});
				}
			});
			</script>
			<?php
		}
	}
	
	public function pre_register_data($fields){
		global $wpmem_themsg, $wpdb;
		
		add_filter('wp_handle_upload_prefilter', array($this, 'file_rename'));
		
		$option = get_cosmosfarm_members_option();
		
		foreach($option->exists_check as $exists_check_field){
			if(isset($fields[$exists_check_field])){
				if(cosmosfarm_members_user_value_exists($exists_check_field, $fields[$exists_check_field])){
					$fields = wpmem_fields();
					$wpmem_themsg = sprintf(__('%s already exists.', 'cosmosfarm-members'), $fields[$exists_check_field]['label']);
					break;
				}
			}
		}
	}
	
	public function pre_update_data($fields){
		global $wpmem_themsg, $wpdb;
		
		add_filter('wp_handle_upload_prefilter', array($this, 'file_rename'));
		
		$option = get_cosmosfarm_members_option();
		$current_user = wp_get_current_user();
		
		foreach($option->exists_check as $exists_check_field){
			if(isset($fields[$exists_check_field])){
				if($fields[$exists_check_field] != $current_user->{$exists_check_field}){
					if(cosmosfarm_members_user_value_exists($exists_check_field, $fields[$exists_check_field])){
						$fields = wpmem_fields();
						$wpmem_themsg = sprintf(__('%s already exists.', 'cosmosfarm-members'), $fields[$exists_check_field]['label']);
						break;
					}
				}
			}
		}
	}
	
	public function file_rename($file){
		$file['name'] = uniqid() . '-' . $file['name'];
		return $file;
	}
	
	public function kboard_comments_login_content($board, $content_uid, $comment_builder){
		echo cosmosfarm_members_social_buttons();
	}
	
	public function post_register_data($fields){
		$option = get_cosmosfarm_members_option();
		if($option->verify_email && $fields['ID']){
			
			$user = new WP_User($fields['ID']);
			if($user->ID && $user->user_email){
				
				$verify_code = cosmosfarm_members_send_verify_email($user);
				update_user_meta($user->ID, 'wait_verify_email', $verify_code);
			}
		}
	}
	
	public function register_redirect($fields){
		wp_redirect(add_query_arg(array('register_success'=>'1'), wp_login_url()));
		exit;
	}
	
	public function page_restriction($content){
		global $post, $wp_query;
		
		if(!$post || !isset($post->ID)){
			return $content;
		}
		
		if(is_home() || is_front_page()){
			return $content;
		}
		
		if(!$wp_query->post){
			return $content;
		}
		
		if($wp_query->post->ID != $post->ID){
			return $content;
		}
		
		if(!is_singular() || !in_the_loop() || !is_main_query()){
			return $content;
		}
		
		$option = get_cosmosfarm_members_option();
		
		$referer_url = wp_get_referer() ? wp_get_referer() : home_url();
		
		$page_restriction_message = apply_filters('cosmosfarm_members_page_restriction_message', sprintf('<p>%s</p>', __('Log in to view this page.', 'cosmosfarm-members')));
		$page_restriction_login_message = apply_filters('cosmosfarm_members_page_restriction_login_message', __('Log in to view this page.', 'cosmosfarm-members'));
		$page_restriction_register_message = apply_filters('cosmosfarm_members_page_restriction_register_message', __('Sign up to view this page.', 'cosmosfarm-members'));
		$page_restriction_permission_message = apply_filters('cosmosfarm_members_page_restriction_permission_message', sprintf('<p>%s</p>', __('You do not have permission to view this page.', 'cosmosfarm-members')));
		
		if($option->page_restriction_message){
			$page_restriction_message = wpautop($option->page_restriction_message);
		}
		
		if($option->page_restriction_alert_message){
			$page_restriction_login_message = $option->page_restriction_alert_message;
			$page_restriction_register_message = $option->page_restriction_alert_message;
		}
		
		if($option->page_restriction_permission_message){
			$page_restriction_permission_message = wpautop($option->page_restriction_permission_message);
		}
		
		if(!$option->subscription_checkout_view_mode){ // 정기결제의 비회원 주문시 동작
			if($option->subscription_checkout_page_id && $post->ID == $option->subscription_checkout_page_id){
				if(!is_user_logged_in()){
					if($option->page_restriction_redirect == '1'){
						?>
						<script>
						(function(){
							if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
								window.location.href="<?php echo esc_js(wp_login_url($_SERVER['REQUEST_URI']))?>";
							}
							else{
								window.location.href="<?php echo esc_js($referer_url)?>";
							}
						})();
						</script>
						<?php
						exit;
					}
					else if($option->page_restriction_redirect == '2'){
						echo '<script>window.location.href="' . wp_login_url($_SERVER['REQUEST_URI']) . '";</script>';
						exit;
					}
					else if($option->page_restriction_redirect == '3'){
						?>
						<script>
						(function(){
							if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
								window.location.href="<?php echo esc_js(wp_registration_url())?>";
							}
							else{
								window.location.href="<?php echo esc_js($referer_url)?>";
							}
						})();
						</script>
						<?php
						exit;
					}
					else if($option->page_restriction_redirect == '4'){
						echo '<script>window.location.href="' . wp_registration_url() . '";</script>';
						exit;
					}
					else{
						$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
						$content = wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
					}
				}
			}
		}
		
		if($option->notifications_page_id && $post->ID == $option->notifications_page_id){
			if(!is_user_logged_in()){
				if($option->page_restriction_redirect == '1'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_login_url($_SERVER['REQUEST_URI']))?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '2'){
					echo '<script>window.location.href="' . wp_login_url($_SERVER['REQUEST_URI']) . '";</script>';
					exit;
				}
				else if($option->page_restriction_redirect == '3'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_registration_url())?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '4'){
					echo '<script>window.location.href="' . wp_registration_url() . '";</script>';
					exit;
				}
				else{
					$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
					$content = wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
				}
			}
		}
		
		if($option->messages_page_id && $post->ID == $option->messages_page_id){
			if(!is_user_logged_in()){
				if($option->page_restriction_redirect == '1'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_login_url($_SERVER['REQUEST_URI']))?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '2'){
					echo '<script>window.location.href="' . wp_login_url($_SERVER['REQUEST_URI']) . '";</script>';
					exit;
				}
				else if($option->page_restriction_redirect == '3'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_registration_url())?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '4'){
					echo '<script>window.location.href="' . wp_registration_url() . '";</script>';
					exit;
				}
				else{
					$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
					$content = wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
				}
			}
		}
		
		if($option->subscription_orders_page_id && $post->ID == $option->subscription_orders_page_id){
			if(!is_user_logged_in()){
				if($option->page_restriction_redirect == '1'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_login_url($_SERVER['REQUEST_URI']))?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '2'){
					echo '<script>window.location.href="' . wp_login_url($_SERVER['REQUEST_URI']) . '";</script>';
					exit;
				}
				else if($option->page_restriction_redirect == '3'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_registration_url())?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '4'){
					echo '<script>window.location.href="' . wp_registration_url() . '";</script>';
					exit;
				}
				else{
					$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
					$content = wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
				}
			}
		}
		
		$page_restriction = get_post_meta($post->ID, 'cosmosfarm_members_page_restriction', true);
		$page_restriction = apply_filters('cosmosfarm_members_page_restriction', $page_restriction);
		
		if($page_restriction){
			if(!is_user_logged_in()){
				if($option->page_restriction_redirect == '1'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_login_url($_SERVER['REQUEST_URI']))?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '2'){
					echo '<script>window.location.href="' . wp_login_url($_SERVER['REQUEST_URI']) . '";</script>';
					exit;
				}
				else if($option->page_restriction_redirect == '3'){
					?>
					<script>
					(function(){
						if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
							window.location.href="<?php echo esc_js(wp_registration_url())?>";
						}
						else{
							window.location.href="<?php echo esc_js($referer_url)?>";
						}
					})();
					</script>
					<?php
					exit;
				}
				else if($option->page_restriction_redirect == '4'){
					echo '<script>window.location.href="' . wp_registration_url() . '";</script>';
					exit;
				}
				else{
					$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
					if(has_excerpt()){
						$content = wpautop(get_the_excerpt());
						$content .= wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
					}
					else{
						$content = wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
					}
					$content = apply_filters('cosmosfarm_members_page_restriction_content', $content, 'page_restriction');
				}
			}
			else{
				$current_user = wp_get_current_user();
				$restriction_roles = get_post_meta($post->ID, 'cosmosfarm_members_page_restriction_roles', true);
				
				$this_restriction = true;
				foreach($current_user->roles as $role){
					if(in_array($role, $restriction_roles)){
						$this_restriction = false;
						break;
					}
				}
				
				if($this_restriction){
					$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
					if(has_excerpt()){
						$content = wpautop(get_the_excerpt());
						$content .= wpmem_inc_regmessage('page_restriction_permission', $page_restriction_permission_message);
					}
					else{
						$content = wpmem_inc_regmessage('page_restriction_permission', $page_restriction_permission_message);
					}
					$content = apply_filters('cosmosfarm_members_page_restriction_content', $content, 'page_restriction');
				}
			}
		}
		
		$category_list = get_the_category();
		foreach($category_list as $category){
			$term_meta = get_option("taxonomy_{$category->term_id}");
			$category_restriction = isset($term_meta['cosmosfarm_members_category_restriction'])?$term_meta['cosmosfarm_members_category_restriction']:'';
			$category_restriction = apply_filters('cosmosfarm_members_category_restriction', $category_restriction, $category);
			
			if($category_restriction){
				if(!is_user_logged_in()){
					if($option->page_restriction_redirect == '1'){
						?>
						<script>
						(function(){
							if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
								window.location.href="<?php echo esc_js(wp_login_url($_SERVER['REQUEST_URI']))?>";
							}
							else{
								window.location.href="<?php echo esc_js($referer_url)?>";
							}
						})();
						</script>
						<?php
						exit;
					}
					else if($option->page_restriction_redirect == '2'){
						echo '<script>window.location.href="' . wp_login_url($_SERVER['REQUEST_URI']) . '";</script>';
						exit;
					}
					else if($option->page_restriction_redirect == '3'){
						?>
						<script>
						(function(){
							if(confirm("<?php echo esc_js($page_restriction_login_message)?>")){
								window.location.href="<?php echo esc_js(wp_registration_url())?>";
							}
							else{
								window.location.href="<?php echo esc_js($referer_url)?>";
							}
						})();
						</script>
						<?php
						exit;
					}
					else if($option->page_restriction_redirect == '4'){
						echo '<script>window.location.href="' . wp_registration_url() . '";</script>';
						exit;
					}
					else{
						$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
						if(has_excerpt()){
							$content = wpautop(get_the_excerpt());
							$content .= wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
						}
						else{
							$content = wpmem_inc_regmessage('page_restriction', $page_restriction_message) . wpmem_inc_login('page_restriction');
						}
						$content = apply_filters('cosmosfarm_members_page_restriction_content', $content, 'category_restriction');
					}
				}
				else{
					$current_user = wp_get_current_user();
					$restriction_roles = isset($term_meta['cosmosfarm_members_category_restriction_roles'])?$term_meta['cosmosfarm_members_category_restriction_roles']:array();
					
					$this_restriction = true;
					foreach($current_user->roles as $role){
						if(in_array($role, $restriction_roles)){
							$this_restriction = false;
							break;
						}
					}
					
					if($this_restriction){
						$_REQUEST['redirect_to'] = $_SERVER['REQUEST_URI'];
						if(has_excerpt()){
							$content = wpautop(get_the_excerpt());
							$content .= wpmem_inc_regmessage('page_restriction_permission', $page_restriction_permission_message);
						}
						else{
							$content = wpmem_inc_regmessage('page_restriction_permission', $page_restriction_permission_message);
						}
						$content = apply_filters('cosmosfarm_members_page_restriction_content', $content, 'category_restriction');
					}
				}
			}
		}
		
		return $content;
	}
	
	public function login_page_message($content){
		global $post;
		
		if(is_page()){
			$option = get_cosmosfarm_members_option();
			
			if(isset($_GET['verify_email_confirm']) && $_GET['verify_email_confirm']){
				$content = wpmem_inc_regmessage('verify_email_confirm', '<p>'.__('Your email has been verified. Please log in.', 'cosmosfarm-members').'</p>') . $content;
			}
			
			if(isset($_GET['register_success']) && $_GET['register_success']){
				if($option->verify_email){
					$content = wpmem_inc_regmessage('register_success', '<p>'.__('Congratulations! Register was successful.', 'cosmosfarm-members').'</p><p>'.__('An email sent to the email address you signed up with, please check.', 'cosmosfarm-members').'</p>') . $content;
				}
				else{
					$content = wpmem_inc_regmessage('register_success', '<p>'.__('Congratulations! Register was successful.', 'cosmosfarm-members').'</p>') . $content;
				}
			}
			
			if(isset($_GET['login_timeout']) && $_GET['login_timeout']){
				$content = wpmem_inc_regmessage('login_timeout', '<p>'.__('You have been automatically logged out to protect your privacy.', 'cosmosfarm-members').'</p>') . $content;
			}
		}
		
		return $content;
	}
	
	public function woocommerce_checkout_add_daum_postcode(){
		wp_enqueue_script('daum-postcode');
	}
	
	public function manage_users_columns($columns){
		$option = get_cosmosfarm_members_option();
		if($option->subscription_checkout_page_id){
			$columns['user_first_subscription_order'] = __('First subscription order', 'cosmosfarm-members');
		}
		if($option->subscription_checkout_page_id){
			$columns['user_last_subscription_order'] = __('Last subscription order', 'cosmosfarm-members');
		}
		if($option->save_login_history){
			$columns['user_lastlogin'] = __('Last login', 'cosmosfarm-members');
		}
		if($option->sms_service){
			$columns['user_notification'] = __('Notification', 'cosmosfarm-members');
		}
		return $columns;
	}
	
	public function manage_users_sortable_columns($columns){
		$columns['user_lastlogin'] = 'user_lastlogin';
		return $columns;
	}
	
	public function manage_users_custom_column($output, $column_name, $user_id){
		global $wpdb;
		$option = get_cosmosfarm_members_option();
		if($option->subscription_checkout_page_id && $column_name == 'user_first_subscription_order'){
			$args = array(
				'post_type' => 'cosmosfarm_order',
				'author' => $user_id,
				'posts_per_page' => 1,
				'orderby' => 'ID',
			);
			$query = new WP_Query($args);
			
			if($query->found_posts){
				$order = new Cosmosfarm_Members_Subscription_Order();
				$order->init_with_id($query->post->ID);
				
				if($order->ID()){
					$output = sprintf('<a href="%s" target="_blank">%s</a>', admin_url('admin.php?page=cosmosfarm_subscription_order&order_id=' . $order->ID()), $query->post->post_title);
					$output .= sprintf('<div>%s/%s</div>', $order->status_format(), $order->subscription_next_format());
					$output .= sprintf('<div>%s</div>', $order->post_date);
				}
			}
		}
		if($option->subscription_checkout_page_id && $column_name == 'user_last_subscription_order'){
			$args = array(
				'post_type' => 'cosmosfarm_order',
				'author' => $user_id,
				'posts_per_page' => 1,
				'order' => 'ASC',
				'orderby' => 'ID',
			);
			$query = new WP_Query($args);
			
			if($query->found_posts){
				$order = new Cosmosfarm_Members_Subscription_Order();
				$order->init_with_id($query->post->ID);
				
				if($order->ID()){
					$output = sprintf('<a href="%s" target="_blank">%s</a>', admin_url('admin.php?page=cosmosfarm_subscription_order&order_id=' . $order->ID()), $query->post->post_title);
					$output .= sprintf('<div>%s/%s</div>', $order->status_format(), $order->subscription_next_format());
					$output .= sprintf('<div>%s</div>', $order->post_date);
				}
			}
		}
		if($option->save_login_history && $column_name == 'user_lastlogin'){
			$output = $wpdb->get_var("SELECT `login_datetime` FROM `{$wpdb->prefix}cosmosfarm_members_login_history` WHERE `user_id`='$user_id' ORDER BY `login_history_id` DESC LIMIT 1");
		}
		if($option->sms_service && $column_name == 'user_notification'){
			$output = '<button type="button" class="button button-small" onclick="cosmosfarm_members_open_sms_form(\'' . $user_id . '\')">SMS 보내기</button>';
		}
		return $output;
	}
	
	public function manage_users_query($user_query){
		global $wpdb;
		if($user_query->query_vars['orderby'] == 'user_lastlogin'){
			$user_query->query_from .= " LEFT OUTER JOIN (SELECT `user_id`, MAX(`login_datetime`) AS `login_datetime` FROM `{$wpdb->prefix}cosmosfarm_members_login_history` GROUP BY `user_id`) AS `login_history` ON (`{$wpdb->users}`.`ID` = `login_history`.`user_id`)";
			$user_query->query_orderby = " ORDER BY `login_history`.`login_datetime` " . ($user_query->query_vars['order'] == 'ASC' ? 'ASC ' : 'DESC ');
		}
	}
	
	public function admin_footer(){
		$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'sms';
		?>
		<script>
		function cosmosfarm_members_open_sms_form(user_id){
			var w = '600';
			var h = '630';
			window.open('<?php echo site_url()?>?template=cosmosfarm_members_sms_form&user_id='+user_id+'&service=<?php echo esc_js($tab)?>', 'cosmosfarm_members_sms_form', 'scrollbars=yes,width='+w+',height='+h+',left='+(screen.availWidth-w)*0.5+',top='+(screen.availHeight-h)*0.5);
			return false;
		}
		</script>
	<?php }
	
	/**
	 * 첫 결제완료 문자 메시지
	 * @param Cosmosfarm_Members_Subscription_Order $order
	 * @param Cosmosfarm_Members_Subscription_Product $product
	 */
	public function subscription_send_sms_paid($order, $product){
		if($order->ID()){
			$user = $order->user();
			
			if($user->ID){
				$buyer_name = wp_strip_all_tags($order->get_meta_value('buyer_name'));
				$buyer_tel = wp_strip_all_tags($order->get_meta_value('buyer_tel'));
				$sms = $product->get_subscription_send_sms_paid();
				
				if($buyer_tel && $sms){
					if($buyer_name){
						$sms = str_replace('#{NAME}', $buyer_name, $sms);
					}
					$sms = str_replace('#{PRODUCT}', $product->title(), $sms);
					$sms = str_replace('#{PRICE}', number_format($order->price()), $sms);
					
					cosmosfarm_members_sms_send($buyer_tel, $sms);
				}
			}
		}
	}
	
	/**
	 * 정기결제 문자 메시지
	 * @param Cosmosfarm_Members_Subscription_Order $order
	 * @param Cosmosfarm_Members_Subscription_Product $product
	 */
	public function subscription_send_sms_again($order, $product){
		if($order->ID()){
			$user = $order->user();
			
			if($user->ID){
				$buyer_name = wp_strip_all_tags($order->get_meta_value('buyer_name'));
				$buyer_tel = wp_strip_all_tags($order->get_meta_value('buyer_tel'));
				$sms = $product->get_subscription_send_sms_again();
				
				if($buyer_tel && $sms){
					if($buyer_name){
						$sms = str_replace('#{NAME}', $buyer_name, $sms);
					}
					$sms = str_replace('#{PRODUCT}', $product->title(), $sms);
					$sms = str_replace('#{PRICE}', number_format($order->price()), $sms);
					
					cosmosfarm_members_sms_send($buyer_tel, $sms);
				}
			}
		}
	}
	
	public function subscription_send_sms_again_failure($order, $product){
		if($order->ID()){
			$user = $order->user();
			
			if($user->ID){
				$buyer_name = wp_strip_all_tags($order->get_meta_value('buyer_name'));
				$buyer_tel = wp_strip_all_tags($order->get_meta_value('buyer_tel'));
				$sms = $product->get_subscription_send_sms_again_failure();
				
				if($buyer_tel && $sms){
					if($buyer_name){
						$sms = str_replace('#{NAME}', $buyer_name, $sms);
					}
					$sms = str_replace('#{PRODUCT}', $product->title(), $sms);
					$sms = str_replace('#{PRICE}', number_format($order->price()), $sms);
					
					cosmosfarm_members_sms_send($buyer_tel, $sms);
				}
			}
		}
	}
	
	public function subscription_send_sms_expiry($order, $product){
		if($order->ID()){
			$user = $order->user();
			
			if($user->ID){
				$buyer_name = wp_strip_all_tags($order->get_meta_value('buyer_name'));
				$buyer_tel = wp_strip_all_tags($order->get_meta_value('buyer_tel'));
				$sms = $product->get_subscription_send_sms_expiry();
				
				if($buyer_tel && $sms){
					if($buyer_name){
						$sms = str_replace('#{NAME}', $buyer_name, $sms);
					}
					$sms = str_replace('#{PRODUCT}', $product->title(), $sms);
					$sms = str_replace('#{PRICE}', number_format($order->price()), $sms);
					
					cosmosfarm_members_sms_send($buyer_tel, $sms);
				}
			}
		}
	}
}