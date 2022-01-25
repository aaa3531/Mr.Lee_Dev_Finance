<?php
/*
Plugin Name: 코스모스팜 회원관리
Plugin URI: https://www.cosmosfarm.com/wpstore/product/cosmosfarm-members
Description: 한국형 회원가입 레이아웃과 기능을 제공합니다.
Version: 2.9.13
Author: 코스모스팜 - Cosmosfarm
Author URI: https://www.cosmosfarm.com/
*/

if(!defined('ABSPATH')) exit;

define('COSMOSFARM_MEMBERS_VERSION', '2.9.13');
define('COSMOSFARM_MEMBERS_DIR_PATH', dirname(__FILE__));
define('COSMOSFARM_MEMBERS_URL', plugins_url('', __FILE__));

if(!defined('COSMOSFARM_MEMBERS_CERTIFIED_PHONE')){
	define('COSMOSFARM_MEMBERS_CERTIFIED_PHONE', false);
}

if(!defined('COSMOSFARM_MEMBERS_USE_SUBSCRIPTION_ITEM')){
	define('COSMOSFARM_MEMBERS_USE_SUBSCRIPTION_ITEM', false);
}

include_once 'class/Cosmosfarm_Members_Comments_Reviews.class.php';
include_once 'class/Cosmosfarm_Members_Controller.class.php';
include_once 'class/Cosmosfarm_Members_KBoard.class.php';
include_once 'class/Cosmosfarm_Members_Mail.class.php';
include_once 'class/Cosmosfarm_Members_MailChimp.class.php';
include_once 'class/Cosmosfarm_Members_Message.class.php';
include_once 'class/Cosmosfarm_Members_Mycred.class.php';
include_once 'class/Cosmosfarm_Members_Notification.class.php';
include_once 'class/Cosmosfarm_Members_Option.class.php';
include_once 'class/Cosmosfarm_Members_Page_Builder.class.php';
include_once 'class/Cosmosfarm_Members_Security.class.php';
include_once 'class/Cosmosfarm_Members_Skin.class.php';
include_once 'class/Cosmosfarm_Members_Sms.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Coupon.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Item_Controller.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Item_Table_Columns.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Item.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Notification.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Order.class.php';
include_once 'class/Cosmosfarm_Members_Subscription_Product.class.php';
include_once 'class/Cosmosfarm_Members.class.php';

add_action('plugins_loaded', 'cosmosfarm_members_plugins_loaded');
function cosmosfarm_members_plugins_loaded(){
	global $sosmosfarm_members_security;
	$sosmosfarm_members_security = new Cosmosfarm_Members_Security();
	
	if(!session_id() && !is_admin() && !wp_is_json_request()){
		session_start();
	}
}

add_action('init', 'cosmosfarm_members_init', 5);
function cosmosfarm_members_init(){
	global $cosmosfarm_members, $cosmosfarm_members_skin, $cosmosfarm_members_sms, $cosmosfarm_members_page_builder, $cosmosfarm_members_kboard, $cosmosfarm_members_subscription_item_controller, $cosmosfarm_members_subscription_item_table_columns, $cosmosfarm_members_controller;
	
	load_plugin_textdomain('cosmosfarm-members', false, dirname(plugin_basename(__FILE__)) . '/languages');
	
	if(defined('WPMEM_VERSION')){
		cosmosfarm_members_rewrite_rule();
		cosmosfarm_members_register_post_type_product();
		
		$cosmosfarm_members = new Cosmosfarm_Members();
		$cosmosfarm_members_skin = new Cosmosfarm_Members_Skin();
		$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
		$sosmosfarm_members_mailchimp = new Cosmosfarm_Members_MailChimp();
		$cosmosfarm_members_page_builder = new Cosmosfarm_Members_Page_Builder();
		$cosmosfarm_members_kboard = new Cosmosfarm_Members_KBoard();
		
		if(COSMOSFARM_MEMBERS_USE_SUBSCRIPTION_ITEM){
			$cosmosfarm_members_subscription_item_controller = new Cosmosfarm_Members_Subscription_Item_Controller();
			$cosmosfarm_members_subscription_item_table_columns = new Cosmosfarm_Members_Subscription_Item_Table_Columns();
		}
		
		$cosmosfarm_members_controller = new Cosmosfarm_Members_Controller();
		
		add_action('admin_menu', array($cosmosfarm_members, 'add_admin_menu'));
	}
	
	/*
	$cosmosfarm_members_dormant_member = isset($_GET['cosmosfarm_members_dormant_member']) ? $_GET['cosmosfarm_members_dormant_member'] : '';
	if($cosmosfarm_members_dormant_member){
		cosmosfarm_members_dormant_member();
		exit;
	}
	*/
}

//add_action('wp_login', 'cosmosfarm_members_subscription_again');
add_action('cosmosfarm_members_subscription_again', 'cosmosfarm_members_subscription_again');
function cosmosfarm_members_subscription_again($user_login='', $user=''){
	global $cosmosfarm_members_controller;
	
	$option = get_cosmosfarm_members_option();
	if($option->subscription_checkout_page_id){
		
		set_time_limit(3600);
		ini_set('memory_limit', '-1');
		
		$subscription_order = new Cosmosfarm_Members_Subscription_Order();
		$args = array(
			'post_type'      => $subscription_order->post_type,
			'order'          => 'DESC',
			'orderby'        => 'ID',
			'posts_per_page' => '-1',
			'meta_query'     => array(
				array(
					'key'     => 'end_datetime',
					'value'   => date('YmdHis', current_time('timestamp')),
					'compare' => '<='
				),
				array(
					'key'     => 'subscription_next',
					'value'   => 'wait',
					'compare' => '='
				)
			)
		);
		$query = new WP_Query($args);
		foreach($query->posts as $post){
			$cosmosfarm_members_controller->subscription_again($post->ID);
		}
	}
}

/**
 * 정기결제 지금 실행
 */
function cosmosfarm_members_subscription_again_now(){
	wp_clear_scheduled_hook('cosmosfarm_members_subscription_again');
	wp_schedule_event(time(), 'cosmosfarm_members_10min', 'cosmosfarm_members_subscription_again');
}

add_action('cosmosfarm_members_dormant_member', 'cosmosfarm_members_dormant_member');
function cosmosfarm_members_dormant_member(){
	
	set_time_limit(3600);
	ini_set('memory_limit', '-1');
	
	// 삭제 안내 이메일 보내기
	$user_lastlogin = date('Y-m-d H:i:s', strtotime('-11 month', current_time('timestamp')));
	
	$args = array(
		'role__not_in' => array('Administrator'),
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => 'user_lastlogin',
				'value'   => $user_lastlogin,
				'compare' => '<='
			),
			array(
				'key'     => 'dormant_member_alert',
				'value'   => '1',
				'compare' => '!='
			)
		)
	);
	$user_query = new WP_User_Query($args);
	
	foreach($user_query->get_results() as $user){
		cosmosfarm_members_send_email(array(
			'to' => $user->user_email,
			'subject' => Cosmosfarm_Members_Security::dormant_member_email_title(),
			'message' => Cosmosfarm_Members_Security::dormant_member_email_message(),
		));
		
		update_user_meta($user->ID, 'dormant_member_alert', '1');
	}
	
	// 회원 삭제 하기
	$user_lastlogin = date('Y-m-d H:i:s', strtotime('-12 month', current_time('timestamp')));
	
	$args = array(
		'role__not_in' => array('Administrator'),
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => 'user_lastlogin',
				'value'   => $user_lastlogin,
				'compare' => '<='
			)
		)
	);
	$user_query = new WP_User_Query($args);
	
	foreach($user_query->get_results() as $user){
		if(is_multisite()){
			if(!function_exists('wpmu_delete_user')){
				include_once ABSPATH . '/wp-admin/includes/ms.php';
			}
			
			wpmu_delete_user($user->ID);
		}
		else{
			if(!function_exists('wp_delete_user')){
				include_once ABSPATH . '/wp-admin/includes/user.php';
			}
			
			wp_delete_user($user->ID);
		}
	}
}

add_action('admin_init', 'cosmosfarm_members_admin_init');
function cosmosfarm_members_admin_init(){
	global $pagenow;
	
	include_once 'class/Cosmosfarm_Members_Meta_Box.class.php';
	$cosmosfarm_members_meta_box = new Cosmosfarm_Members_Meta_Box();
	
	include_once 'class/Cosmosfarm_Members_Manage_Users.class.php';
	$cosmosfarm_members_manage_users = new Cosmosfarm_Members_Manage_Users();
	
	include_once 'class/Cosmosfarm_Members_Privacy_Blind_On.class.php';
	$cosmosfarm_members_privacy_blind_on = new Cosmosfarm_Members_Privacy_Blind_On();
	
	// 정기결제 상품이라면 포스트 편집 화면이 아니라 상품 편집 화면으로 이동한다.
	if($pagenow == 'post.php' && isset($_GET['post'])){
		$post = intval($_GET['post']);
		
		if(get_post_type($post) == 'cosmosfarm_product'){
			$action = isset($_GET['action']) ? $_GET['action'] : '';
			
			if($action != 'elementor'){
				wp_redirect(admin_url("admin.php?page=cosmosfarm_subscription_product&product_id={$post}"));
				exit;
			}
		}
	}
}

add_action('wp_enqueue_scripts', 'cosmosfarm_members_scripts', 999);
function cosmosfarm_members_scripts(){
	$option = get_cosmosfarm_members_option();
	
	wp_enqueue_script('cosmosfarm-members-script', COSMOSFARM_MEMBERS_URL . '/assets/js/script.js', array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
	wp_enqueue_style("cosmosfarm-members-style", COSMOSFARM_MEMBERS_URL . "/assets/css/style.css", array(), COSMOSFARM_MEMBERS_VERSION);
	
	wp_enqueue_script("cosmosfarm-members-{$option->skin}", COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}/script.js", array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
	wp_enqueue_style("cosmosfarm-members-{$option->skin}", COSMOSFARM_MEMBERS_URL . "/skin/{$option->skin}/style.css", array(), COSMOSFARM_MEMBERS_VERSION);
	
	// jQuery lightSlider 등록
	wp_register_style('lightslider', COSMOSFARM_MEMBERS_URL . '/assets/lightslider/css/lightslider.css', array(), '1.1.6');
	wp_register_script('lightslider', COSMOSFARM_MEMBERS_URL . '/assets/lightslider/js/lightslider.js', array('jquery'), '1.1.6', true);
	
	// jQuery lightGallery 등록
	wp_register_style('lightgallery', COSMOSFARM_MEMBERS_URL . "/assets/lightgallery/css/lightgallery.min.css", array(), '1.6.12');
	wp_register_script('lightgallery', COSMOSFARM_MEMBERS_URL . "/assets/lightgallery/js/lightgallery.min.js", array('jquery'), '1.6.12', true);
	wp_register_script('lg-zoom', COSMOSFARM_MEMBERS_URL . "/assets/lightgallery/js/lg-zoom.min.js", array(), '1.1.0', true);
	
	// 스크립트 등록
	wp_register_script('daum-postcode', '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js', array('jquery'), NULL, true);
	wp_register_script('iamport-payment', 'https://cdn.iamport.kr/js/iamport.payment-1.1.7.js', array('jquery'), '1.1.7');
	wp_register_script('cosmosfarm-members-subscription', COSMOSFARM_MEMBERS_URL . '/assets/js/subscription.js', array('iamport-payment'), COSMOSFARM_MEMBERS_VERSION, true);
	
	// 스타일 등록
	wp_register_style('jquery-flick-style', COSMOSFARM_MEMBERS_URL . "/assets/css/jquery-ui.css", array(), '1.12.1');
	
	// 설정 등록
	$localize = array(
		'ajax_nonce' => wp_create_nonce('cosmosfarm-members-check-ajax-referer'),
		'home_url' => home_url('/', 'relative'),
		'site_url' => site_url('/', 'relative'),
		'post_url' => admin_url('/admin-post.php', 'relative'),
		'ajax_url' => admin_url('/admin-ajax.php', 'relative'),
		'locale' => get_cosmosfarm_members_locale(),
		'postcode_service_disabled' => $option->postcode_service_disabled,
		'use_postcode_service_iframe' => $option->use_postcode_service_iframe,
		'use_strong_password' => $option->use_strong_password,
		'use_certification' => $option->use_certification,
		'certified_phone' => COSMOSFARM_MEMBERS_CERTIFIED_PHONE,
		'certification_min_age' => $option->certification_min_age,
		'certification_name_field' => $option->certification_name_field,
		'certification_gender_field' => $option->certification_gender_field,
		'certification_birth_field' => $option->certification_birth_field,
		'certification_carrier_field' => $option->certification_carrier_field,
		'certification_phone_field' => $option->certification_phone_field,
		'exists_check' => $option->exists_check,
		'iamport_id' => $option->iamport_id,
		'is_user_logged_in' => is_user_logged_in(),
	);
	wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_settings', $localize);
	
	// 번역 등록
	$localize = array(
		'please_enter_the_postcode' => __('Please enter the postcode.', 'cosmosfarm-members'),
		'please_wait' => __('Please wait.', 'cosmosfarm-members'),
		'yes' => __('Yes', 'cosmosfarm-members'),
		'no' => __('No', 'cosmosfarm-members'),
		'password_must_consist_of_8_digits' => __('Password must consist of 8 digits, including English, numbers and special characters.', 'cosmosfarm-members'),
		'your_password_is_different' => __('Your password is different.', 'cosmosfarm-members'),
		'please_enter_your_password_without_spaces' => __('Please enter your password without spaces.', 'cosmosfarm-members'),
		'it_is_a_safe_password' => __('It is a safe password.', 'cosmosfarm-members'),
		'male' => __('Male', 'cosmosfarm-members'),
		'female' => __('Female', 'cosmosfarm-members'),
		'certificate_completed' => __('Certificate Completed', 'cosmosfarm-members'),
		'please_fill_out_this_field' => __('Please fill out this field.', 'cosmosfarm-members'),
		'available' => __('Available.', 'cosmosfarm-members'),
		'not_available' => __('Not available.', 'cosmosfarm-members'),
		'already_in_use' => __('Already in use.', 'cosmosfarm-members'),
		'are_you_sure_you_want_to_delete' => __('Are you sure you want to delete?', 'cosmosfarm-members'),
		'no_notifications_found' => __('No notifications found.', 'cosmosfarm-members'),
		'no_messages_found' => __('No messages found.', 'cosmosfarm-members'),
		'no_orders_found' => __('No orders found.', 'cosmosfarm-members'),
		'no_users_found' => __('No users found.', 'cosmosfarm-members'),
		'please_agree' => __('Please agree.', 'cosmosfarm-members'),
		'place_order' => __('Place order', 'cosmosfarm-members'),
		'required' => __('%s is required.', 'cosmosfarm-members'),
		'this_page_will_refresh_do_you_want_to_continue' => __('This page will refresh. Do you want to continue?', 'cosmosfarm-members'),
		'this_page_will_be_refreshed_to_apply_the_coupon_do_you_want_to_continue' => __('This page will be refreshed to apply the coupon. Do you want to continue?', 'cosmosfarm-members'),
		'please_exists_check' => __('Please double check %s.', 'cosmosfarm-members'),
	);
	wp_localize_script("cosmosfarm-members-{$option->skin}", 'cosmosfarm_members_localize_strings', apply_filters('cosmosfarm_members_localize_strings', $localize));
}

add_action('admin_enqueue_scripts', 'cosmosfarm_members_admin_scripts', 999);
function cosmosfarm_members_admin_scripts(){
	$option = get_cosmosfarm_members_option();
	
	wp_enqueue_script('cosmosfarm-members-admin-script', COSMOSFARM_MEMBERS_URL . '/assets/js/admin.js', array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
	wp_enqueue_style('cosmosfarm-members-admin', COSMOSFARM_MEMBERS_URL . '/admin/admin.css', array(), COSMOSFARM_MEMBERS_VERSION);
	
	$localize = array(
			'ajax_nonce' => wp_create_nonce('cosmosfarm-members-check-ajax-referer'),
			'home_url' => home_url('/', 'relative'),
			'site_url' => site_url('/', 'relative'),
			'post_url' => admin_url('admin-post.php'),
			'ajax_url' => admin_url('admin-ajax.php'),
			'locale' => get_cosmosfarm_members_locale()
	);
	wp_localize_script('cosmosfarm-members-admin-script', 'cosmosfarm_members_admin_settings', $localize);
}

add_action('admin_notices', 'cosmosfarm_members_admin_notices');
function cosmosfarm_members_admin_notices(){
	$screen = get_current_screen();
	
	if(!defined('WPMEM_VERSION') && $screen->id != 'update'){
		$action = 'install-plugin';
		$plugin = 'wp-members';
		$install_url = wp_nonce_url(add_query_arg(array('action'=>$action, 'plugin'=>$plugin), admin_url('update.php')), $action.'_'.$plugin);
		$message = sprintf('코스모스팜 회원관리 사용을 위해서는 먼저 <a href="%s">WP-Members</a> 플러그인을 설치하고 활성화해주세요.', $install_url);
		
		$class = 'notice notice-error';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
	}
	
	if($screen->id == 'toplevel_page_cosmosfarm_subscription_order'){
		$order_id = isset($_GET['hide_error_message_admin_notice']) ? intval($_GET['hide_error_message_admin_notice']) : '';
		
		if($order_id){
			$order = new Cosmosfarm_Members_Subscription_Order();
			$order->init_with_id($order_id);
			$order->hide_error_message_admin_notice();
		}
	}
	
	$subscription_order = new Cosmosfarm_Members_Subscription_Order();
	$args = array(
		'post_type'      => $subscription_order->post_type,
		'order'          => 'DESC',
		'orderby'        => 'ID',
		'posts_per_page' => '-1',
		'meta_query'     => array(
			array(
				'key'     => 'error_message_admin_notice',
				'value'   => '1',
				'compare' => '='
			)
		)
	);
	$query = new WP_Query($args);
	foreach($query->posts as $post){
		$order = new Cosmosfarm_Members_Subscription_Order();
		$order->init_with_id($post->ID);
		
		$row1 = sprintf('<p>[%d] %s: %s</p>', $order->ID(), '다음 정기결제 실패', $order->error_message());
		
		$edit_url = admin_url("admin.php?page=cosmosfarm_subscription_order&order_id={$order->ID}");
		$hide_url = admin_url("admin.php?page=cosmosfarm_subscription_order&hide_error_message_admin_notice={$order->ID}");
		$row2 = sprintf('<p><a href="%s" class="button">마지막 주문정보 보기</a> <a href="%s" class="button">메시지 숨기기</a></p>', $edit_url, $hide_url);
		
		echo sprintf('<div class="notice notice-error">%s%s</div>', $row1, $row2);
	}
}

/**
 * 정기결제 상품 상세페이지에서 사용될 이미지 사이즈를 설정한다.
 */
add_action('after_setup_theme', 'cosmosfarm_members_after_setup_theme');
function cosmosfarm_members_after_setup_theme(){
	add_image_size('cosmosfarm-product-thumbnail', 200, 200, true);
	add_image_size('cosmosfarm-product-large', 800, 533, true);
}

function cosmosfarm_members_menu_item($args){
	$item = new stdClass();
	$item->ID = 10000000 + (isset($args['order']) ? $args['order'] : 0);
	$item->db_id = $item->ID;
	$item->title = isset($args['title']) ? $args['title'] : '';
	$item->url = isset($args['url']) ? $args['url'] : '';
	$item->menu_order = $item->ID;
	$item->menu_item_parent = 0;
	$item->post_parent = 0;
	$item->type = 'custom';
	$item->object = 'custom';
	$item->object_id = '';
	$item->classes = isset($args['classes']) ? $args['classes'] : array();
	$item->target = '';
	$item->attr_title = '';
	$item->description = '';
	$item->xfn = '';
	$item->status = '';
	return $item;
}

/**
 * 주소 규칙을 추가한다.
 */
function cosmosfarm_members_rewrite_rule($page=''){
	$option = get_cosmosfarm_members_option();
	
	if($option->user_profile_page_id && get_option('permalink_structure')){
		$post = get_post($option->user_profile_page_id);

		add_rewrite_tag("%profile_id%", '([^&]+)');
		
		add_rewrite_rule(sprintf('^%s/([0-9]+)/?', $post->post_name), sprintf('index.php?page_id=%s&profile_id=$matches[1]', $post->ID), 'top');
	}
}

/**
 * 상품 포스트 타입을 등록한다.
 */
function cosmosfarm_members_register_post_type_product(){
	global $wp_post_types;
	
	$post_type = cosmosfarm_members_subscription_product()->post_type;
	
	$args = array(
		'labels'       => array('name'=>'정기결제 상품'),
		'public'       => true,
		'rewrite'      => array('slug'=>'cosmosfarm-product'),
		'supports'     => array('comments'),
		'show_in_menu' => false,
		'has_archive'  => true
	);
	
	$cosmosfarm_product = new WP_Post_Type($post_type, $args);
	$cosmosfarm_product->add_supports();
	$cosmosfarm_product->add_rewrite_rules();
	
	$wp_post_types[$post_type] = $cosmosfarm_product;
	
	/*
	register_post_type($post_type, array(
		
		'show_ui'      => false,
		'public'       => true,
		'rewrite'      => array('slug'=>'cosmosfarm-product'),
		'supports'     => array('comments'),
		'show_in_menu' => false,
		'has_archive'  => true
	));
	*/
	
	register_taxonomy('cosmosfarm_product_tag', $post_type, array(
		'public'       => true,
		'rewrite'      => array('slug'=>'cosmosfarm-product-tag'),
		'hierarchical' => true
	));
}

/**
 * 별점을 반환한다.
 */
function cosmosfarm_members_star_rating_display($rating, $format='<span class="cosmosfarm-members-star-rating">%s</span>'){
	wp_enqueue_style('dashicons');
	
	$rating = round($rating);
	$display = '';
	
	switch($rating){
		case 5: $display = '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>'; break;
		case 4: $display = '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-empty"></span>'; break;
		case 3: $display = '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>'; break;
		case 2: $display = '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>'; break;
		case 1: $display = '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>'; break;
		default: $display = '<span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>'; break;
	}
	return sprintf($format, $display);
}

function get_cosmosfarm_menu_add_login(){
	$menu_add_login = get_option('cosmosfarm_menu_add_login', '');
	return stripslashes($menu_add_login);
}

function get_cosmosfarm_login_menus(){
	$login_menus = get_option('cosmosfarm_login_menus', array());
	return $login_menus;
}

function get_cosmosfarm_policy_service_content(){
	$policy_service = get_option('cosmosfarm_members_policy_service', '');
	return stripslashes($policy_service);
}

function get_cosmosfarm_policy_privacy_content(){
	$policy_privacy = get_option('cosmosfarm_members_policy_privacy', '');
	return stripslashes($policy_privacy);
}

function get_cosmosfarm_members_option(){
	global $cosmosfarm_members_option;
	if($cosmosfarm_members_option === null){
		$cosmosfarm_members_option = new Cosmosfarm_Members_Option();
	}
	return $cosmosfarm_members_option;
}

function get_cosmosfarm_members_sms(){
	global $cosmosfarm_members_sms;
	if($cosmosfarm_members_sms === null){
		$cosmosfarm_members_sms = new Cosmosfarm_Members_Sms();
	}
	return $cosmosfarm_members_sms;
}

function get_cosmosfarm_members_profile_url(){
	$option = get_cosmosfarm_members_option();
	
	$profile_url = '';
	if($option->account_page_id || $option->account_page_url){
		if($option->account_page_id){
			$profile_url = get_permalink($option->account_page_id);
		}
		else if($option->account_page_url){
			$profile_url = $option->account_page_url;
		}
	}
	else if(wpmem_profile_url()){
		$profile_url = wpmem_profile_url();
	}
	return esc_url_raw($profile_url);
}

function get_cosmosfarm_members_notifications_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->notifications_page_id){
		$url = get_permalink($option->notifications_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

function get_cosmosfarm_members_messages_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->messages_page_id){
		$url = get_permalink($option->messages_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

/**
 * 주문 목록 페이지 주소
 */
function get_cosmosfarm_members_orders_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->subscription_orders_page_id){
		$url = get_permalink($option->subscription_orders_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

/**
 * 상품 목록 페이지 주소
 */
function get_cosmosfarm_members_product_list_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->subscription_product_list_page_id){
		$url = get_permalink($option->subscription_product_list_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

/**
 * 회원 리스트 페이지 주소
 */
function get_cosmosfarm_members_users_url($args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->users_page_id){
		$url = get_permalink($option->users_page_id);
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

/**
 * 회원 공개 프로필 주소
 */
function get_cosmosfarm_members_user_profile_url($user_id='', $args=array()){
	$option = get_cosmosfarm_members_option();
	
	$url = '';
	if($option->user_profile_page_id){
		if(!$user_id){
			$user_id = get_current_user_id();
		}
		
		if(get_option('permalink_structure')){
			$url = sprintf('%s/%s', untrailingslashit(get_permalink($option->user_profile_page_id)), $user_id);
		}
		else{
			$url = add_query_arg(array('profile_id'=>$user_id), get_permalink($option->user_profile_page_id));
		}
		
		$url = add_query_arg($args, $url);
	}
	return esc_url_raw($url);
}

add_shortcode('cosmosfarm_members_login_url', 'get_cosmosfarm_members_login_url');
function get_cosmosfarm_members_login_url($args=array()){
	if(is_array($args) && isset($args['redirect']) && $args['redirect']){
		$redirect = $args['redirect'];
	}
	else if(is_array($args) && isset($args['redirect_query_var']) && $args['redirect_query_var'] && isset($_REQUEST[$args['redirect_query_var']]) && $_REQUEST[$args['redirect_query_var']]){
		$redirect = $_REQUEST[$args['redirect_query_var']];
	}
	else if(is_string($args) && $args){
		$redirect = $redirect;
	}
	else{
		$redirect = $_SERVER['REQUEST_URI'];
	}
	
	return wp_login_url($redirect);
}

add_shortcode('cosmosfarm_members_logout_url', 'get_cosmosfarm_members_logout_url');
function get_cosmosfarm_members_logout_url($args=array()){
	if(is_array($args) && isset($args['redirect']) && $args['redirect']){
		$redirect = $args['redirect'];
	}
	else if(is_array($args) && isset($args['redirect_query_var']) && $args['redirect_query_var'] && isset($_REQUEST[$args['redirect_query_var']]) && $_REQUEST[$args['redirect_query_var']]){
		$redirect = $_REQUEST[$args['redirect_query_var']];
	}
	else if(is_string($args) && $args){
		$redirect = $redirect;
	}
	else{
		$redirect = $_SERVER['REQUEST_URI'];
	}
	
	return wp_logout_url($redirect);
}

function get_cosmosfarm_members_file_handler(){
	if(!class_exists('Cosmosfarm_Members_File_Handler')){
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/Cosmosfarm_Members_File_Handler.class.php';
	}
	return new Cosmosfarm_Members_File_Handler();
}

function get_cosmosfarm_members_locale(){
	return apply_filters('cosmosfarm_members_locale', get_locale());
}

function get_cosmosfarm_members_news_list(){
	
	$news_list = get_transient('cosmosfarm_members_news_list');
	
	if($news_list){
		return $news_list;
	}
	
	$response = wp_remote_get('http://updates.wp-kboard.com/v1/AUTH_3529e134-c9d7-4172-8338-f64309faa5e5/kboard/news.json');
	
	if(!is_wp_error($response) && isset($response['body']) && $response['body']){
		$news_list = json_decode($response['body']);
	}
	else{
		$news_list = array();
	}
	
	set_transient('cosmosfarm_members_news_list', $news_list, 60*60);
	
	return $news_list;
}

/**
 * 정기결제 타입을 반환한다. (일반결제/빌링결제)
 * @return string
 */
function get_cosmosfarm_members_subscription_pg_type(){
	$subscription_pg_type = 'billing';
	$option = get_cosmosfarm_members_option();
	if($option->subscription_pg_type){
		$subscription_pg_type = $option->subscription_pg_type;
	}
	return apply_filters('cosmosfarm_members_subscription_pg_type', $subscription_pg_type);
}

/**
 * 취소 및 환불 규정 내용을 반환한다.
 */
function get_cosmosfarm_members_subscription_cancellation_refund_policy_content(){
	$content = '';
	$option = get_cosmosfarm_members_option();
	if($option->subscription_cancellation_refund_policy_page_id){
		$page = get_post($option->subscription_cancellation_refund_policy_page_id);
		$content = $page->post_content;
	}
	return apply_filters('cosmosfarm_members_subscription_cancellation_refund_policy_content', $content);
}

/**
 * 아임포트 PG 설정 목록을 반환한다.
 */
function get_cosmosfarm_members_subscription_iamport_pg_list(){
	$iamport_pg_list = new stdClass();
	$iamport_pg_list->card = (object) array('title'=>'신용카드', 'iamport_id'=>'', 'pg'=>'');
	return apply_filters('cosmosfarm_members_subscription_iamport_pg_list', $iamport_pg_list);
}

/**
 * 초기화된 정기결제 상품 인스턴스를 반환한다.
 * @return Cosmosfarm_Members_Subscription_Product
 */
function cosmosfarm_members_subscription_product(){
	global $cosmosfarm_members_subscription_product;
	if($cosmosfarm_members_subscription_product === null){
		$cosmosfarm_members_subscription_product = new Cosmosfarm_Members_Subscription_Product();
	}
	return $cosmosfarm_members_subscription_product;
}

function cosmosfarm_members_currency_format($value, $format='%s원'){
	return sprintf(apply_filters('cosmosfarm_members_currency_format', $format), number_format($value));
}

/**
 * 문자열에 특수문자(*)를 추가해서 알아볼 수 없도록 변경한다.
 */
function cosmosfarm_members_text_masking($value){
	if($value){
		if(strpos($value, '@') !== false){
			$value2 = explode('@', $value);
			$value = cosmosfarm_members_text_masking($value2[0]) . '@' . $value2[1];
		}
		else{
			$strlen = mb_strlen($value, 'utf-8');
			
			if($strlen > 4){
				$showlen = 3;
				$value = mb_substr($value, 0, $showlen, 'utf-8') . str_repeat('*', $strlen-$showlen);
			}
			else if($strlen > 3){
				$showlen = 2;
				$value = mb_substr($value, 0, $showlen, 'utf-8') . str_repeat('*', $strlen-$showlen);
			}
			else if($strlen > 2){
				$value = preg_split('//u', $value, null, PREG_SPLIT_NO_EMPTY);
				$value = $value[0] . '*' . $value[2];
			}
			else if($strlen > 1){
				$value = preg_split('//u', $value, null, PREG_SPLIT_NO_EMPTY);
				$value = $value[0] . '*';
			}
		}
	}
	return $value;
}

/**
 * 연락처 번호에 특수문자(*)를 추가해서 알아볼 수 없도록 변경한다.
 */
function cosmosfarm_members_phone_masking($value){
	if($value){
		if(strpos($value, '-') !== false){
			$value = explode('-', $value);
			$strlen = mb_strlen($value[1], 'utf-8');
			$value = $value[0] . '-' . str_repeat('*', $strlen) . '-' . $value[2];
		}
		else{
			$value = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $value);
			$value = explode('-', $value);
			$strlen = mb_strlen($value[1], 'utf-8');
			$value = $value[0] . str_repeat('*', $strlen) . $value[2];
		}
	}
	return $value;
}

/**
 * 사용자 역할 설정이 있는 이용중인 상품을 반환한다.
 */
function cosmosfarm_members_subscription_last_order($user_id=''){
	$order = new Cosmosfarm_Members_Subscription_Order();
	
	if($user_id){
		$args = array(
			'post_type'		  => $order->order_post_type(),
			'author'		  => $user_id,
			'orderby'		  => 'ID',
			'order'			  => 'DESC',
			'posts_per_page'  => 1,
			'meta_query'	  => array(
				array(
					'key' 	  => 'subscription_prev_role',
					'value'   => array(''),
					'compare' => 'NOT IN'
				),
				array(
					'key'	  => 'status',
					'value'	  => 'paid',
					'compare' => '=',
				),
				array(
					'key'	  => 'subscription_next',
					'value'	  => array('success', 'wait'),
					'compare' => 'IN',
				),
			),
		);
		
		$query = new WP_Query($args);
		
		$post_id = isset($query->post->ID) ? $query->post->ID : '';
		if($post_id){
			$order->init_with_id($post_id);
		}
	}
	
	return $order;
}

/**
 * 이메일을 전송한다.
 */
function cosmosfarm_members_send_email($args=array()){
	$args = array_merge(array(
		'to' => '',
		'subject' => '',
		'message' => '',
	), $args);
	
	if(!$args['to']){
		return false;
	}
	
	if(!$args['subject']){
		return false;
	}
	
	$mail = new Cosmosfarm_Members_Mail();
	return $mail->send($args);
}

function cosmosfarm_members_send_verify_email($user, $verify_code=''){
	$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
	
	if($user->ID && $user->user_email && !$action){
		
		if(!$verify_code) $verify_code = md5(uniqid());
		$option = get_cosmosfarm_members_option();
		
		if($option->verify_email_title && $option->verify_email_content){
			
			$blogname = get_option('blogname');
			$home_url = home_url();
			$verify_email_url = home_url('?action=cosmosfarm_members_verify_email_confirm&verify_code='.$verify_code);
			
			$subject = str_replace('[blogname]', $blogname, $option->verify_email_title);
			$subject = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $subject);
			$subject = str_replace('[verify_email_url]', sprintf('<a href="%s" target="_blank">%s</a>', $verify_email_url, $verify_email_url), $subject);
			
			$message = str_replace('[blogname]', $blogname, $option->verify_email_content);
			$message = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $message);
			$message = str_replace('[verify_email_url]', sprintf('<a href="%s" target="_blank">%s</a>', $verify_email_url, $verify_email_url), $message);
			
			if($option->allow_email_login){
				$subject = str_replace('[id_or_email]', $user->user_email, $subject);
				$message = str_replace('[id_or_email]', $user->user_email, $message);
			}
			else{
				$subject = str_replace('[id_or_email]', $user->display_name, $subject);
				$message = str_replace('[id_or_email]', $user->display_name, $message);
			}
			
			$verify_email = array(
				'to' => $user->user_email,
				'subject' => $subject,
				'message' => $message,
			);
			$verify_email = apply_filters('cosmosfarm_members_send_verify_email', $verify_email, $user);
			
			$mail = new Cosmosfarm_Members_Mail();
			$mail->send($verify_email);
		}
	}
	return $verify_code;
}

function cosmosfarm_members_send_confirmed_email($user){
	if($user->ID && $user->user_email){
		$option = get_cosmosfarm_members_option();
		
		if($option->confirmed_email_title && $option->confirmed_email_content){
			
			$blogname = get_option('blogname');
			$home_url = home_url();
			
			$subject = str_replace('[blogname]', $blogname, $option->confirmed_email_title);
			$subject = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $subject);
			
			$message = str_replace('[blogname]', $blogname, $option->confirmed_email_content);
			$message = str_replace('[home_url]', sprintf('<a href="%s" target="_blank">%s</a>', $home_url, $home_url), $message);
			
			if($option->allow_email_login){
				$subject = str_replace('[id_or_email]', $user->user_email, $subject);
				$message = str_replace('[id_or_email]', $user->user_email, $message);
			}
			else{
				$subject = str_replace('[id_or_email]', $user->display_name, $subject);
				$message = str_replace('[id_or_email]', $user->display_name, $message);
			}
			
			$confirmed_email = array(
				'to' => $user->user_email,
				'subject' => $subject,
				'message' => $message,
			);
			$confirmed_email = apply_filters('cosmosfarm_members_send_confirmed_email', $confirmed_email, $user);
			
			$mail = new Cosmosfarm_Members_Mail();
			$mail->send($confirmed_email);
		}
	}
}

function cosmosfarm_members_send_message($args=array()){
	$args = array_merge(array(
		'from_user_id' => '', // required
		'to_user_id'   => '', // required
		'title'        => '',
		'content'      => '', // required
		'item_type'    => '',
		'meta_input'   => array(),
	), $args);
	
	$message = new Cosmosfarm_Members_Message();
	return $message->create($args);
}

function cosmosfarm_members_send_notification($args=array()){
	$args = array_merge(array(
		'from_user_id' => '',
		'to_user_id'   => '', // required
		'title'        => '',
		'content'      => '', // required
		'item_type'    => '',
		'meta_input'   => array(),
	), $args);
	
	$notification = new Cosmosfarm_Members_Notification();
	return $notification->create($args);
}

function cosmosfarm_members_skins(){
	$dir = COSMOSFARM_MEMBERS_DIR_PATH . '/skin';
	if($dh = opendir($dir)){
		while(($name = readdir($dh)) !== false){
			if($name == "." || $name == ".." || $name == "readme.txt" || $name == ".git") continue;
			$skin = new stdClass();
			$skin->name = $name;
			$skin->dir = COSMOSFARM_MEMBERS_DIR_PATH . "/skin/{$name}";
			$skin->url = COSMOSFARM_MEMBERS_URL . "/skin/{$name}";
			$ist[$name] = $skin;
		}
	}
	closedir($dh);
	return apply_filters('cosmosfarm_members_skin_list', $ist);
}

function cosmosfarm_members_user_value_exists($meta_key, $meta_value, $skip_user_id=''){
	global $wpdb;
	
	if($meta_value){
		if(in_array($meta_key, array('username', 'user_login', 'user_nicename', 'user_email', 'user_url', 'display_name'))){
			if($meta_key == 'username') $meta_key = 'user_login';
			$meta_value = esc_sql($meta_value);
			
			$where = "`$meta_key`='$meta_value'";
			
			if($skip_user_id && is_array($skip_user_id)){
				$where .= " AND `ID` NOT IN (".implode(',', $skip_user_id).")";
			}
			else if($skip_user_id){
				$where .= " AND `ID`!='{$skip_user_id}'";
			}
			
			$count = $wpdb->get_var("SELECT COUNT(*) FROM `$wpdb->users` WHERE {$where}");
			if($count) return true;
		}
		else{
			$meta_key = esc_sql($meta_key);
			$meta_value = esc_sql($meta_value);
			
			$where = "`meta_key`='$meta_key' AND `meta_value`='$meta_value'";
			
			if($skip_user_id && is_array($skip_user_id)){
				$where .= " AND `user_id` NOT IN (".implode(',', $skip_user_id).")";
			}
			else if($skip_user_id){
				$where .= " AND `user_id`!='{$skip_user_id}'";
			}
			
			$count = $wpdb->get_var("SELECT COUNT(*) FROM `$wpdb->usermeta` WHERE {$where}");
			if($count) return true;
		}
	}
	return false;
}

/**
 * SMS 문자를 전송한다.
 */
function cosmosfarm_members_sms_send($phone, $content){
	$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
	return $cosmosfarm_members_sms->send($phone, $content);
}

/**
 * SMS 문자를 대량으로 전송한다.
 */
function cosmosfarm_members_sms_send_every($user_meta_key, $content){
	$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
	return $cosmosfarm_members_sms->send_every($user_meta_key, $content);
}

/**
 * 카카오 알림톡을 전송한다.
 */
function cosmosfarm_members_alimtalk_send($phone, $template_name, $user_id='', $product_id=''){
	$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
	return $cosmosfarm_members_sms->alimtalk_send($phone, $template_name, $user_id, $product_id);
}

/**
 * 카카오 알림톡을 대량으로 전송한다.
 */
function cosmosfarm_members_alimtalk_send_every($template_name, $phone_field){
	$cosmosfarm_members_sms = get_cosmosfarm_members_sms();
	return $cosmosfarm_members_sms->alimtalk_send_every($template_name, $phone_field);
}

/**
 * Elementor Page Builder 를 사용할 수 있는지 확인한다.
 * @return boolean
 */
function cosmosfarm_members_is_elementor_support(){
	$is_elementor_support = false;
	if(in_array('cosmosfarm_product', get_option('elementor_cpt_support', array()))){
		$is_elementor_support = true;
	}
	return $is_elementor_support;
}

/**
 * 고급 기능이 활성화되어있는지 확인한다.
 */
function cosmosfarm_members_is_advanced(){
	$is_advanced = false;
	if(COSMOSFARM_MEMBERS_USE_SUBSCRIPTION_ITEM){
		$is_advanced = true;
	}
	return $is_advanced;
}

/**
 * 내장된 PG 기능을 반환한다.
 */
function cosmosfarm_members_load_pg($name, $args=array()){
	if($name == 'inicis'){
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/Cosmosfarm_Members_Abstract_PG.class.php';
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/Cosmosfarm_Members_PG_Inicis.class.php';
		$pg = new Cosmosfarm_Members_PG_Inicis();
		
		if(isset($args['pg_type']) && $args['pg_type']){
			if($args['pg_type'] == 'billing'){
				$pg->set_pg_type_billing();
			}
			else if($args['pg_type'] == 'general'){
				$pg->set_pg_type_general();
			}
		}
		$pg->init();
		
		return $pg;
	}
	else if($name == 'nicepay'){
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/Cosmosfarm_Members_Abstract_PG.class.php';
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/Cosmosfarm_Members_PG_Nicepay.class.php';
		$pg = new Cosmosfarm_Members_PG_Nicepay();
		
		if(isset($args['pg_type']) && $args['pg_type']){
			if($args['pg_type'] == 'billing'){
				$pg->set_pg_type_billing();
			}
			else if($args['pg_type'] == 'general'){
				$pg->set_pg_type_general();
			}
		}
		$pg->init();
		
		return $pg;
	}
	else if($name == 'iamport'){
		include_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/api/Cosmosfarm_Members_API_Iamport.class.php';
		$pg = new Cosmosfarm_Members_API_Iamport();
		return $pg;
	}
	return false;
}

/**
 * 택배사 목록을 반환한다.
 */
function cosmosfarm_members_courier_company_list(){
	$courier_company_list = array();
	$courier_company_list['01'] = array(
		'name' => 'CJ대한통운',
		'tracking_url' => 'https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=%s',
	);
	return apply_filters('cosmosfarm_members_courier_company_list', $courier_company_list);
}

/**
 * PG사 등 외부에서 데이터가 넘어올 때 인증이 풀리는 문제 방지
 */
function cosmosfarm_members_form_resubmit(){
	$data = $_POST;
	
	if(!is_user_logged_in()){
		if(!isset($data['cosmosfarm_members_form_resubmit']) || !$data['cosmosfarm_members_form_resubmit']){
			$data['cosmosfarm_members_form_resubmit'] = '1';
			
			echo '<html><head><meta charset="UTF-8"></head><body>';
			echo '<form id="f" name="f" method="post">';
			
			foreach($data as $key=>$value){
				echo cosmosfarm_members_form_resubmit_field($key, $value);
			}
			
			echo '</form>';
			echo '<script>';
			echo 'document.f.submit();';
			echo '</script></body></html>';
			exit;
		}
	}
}

function cosmosfarm_members_form_resubmit_field($key, $value){
	if(is_array($value)){
		$list = array();
		foreach($value as $i=>$j){
			$list[] = cosmosfarm_members_form_resubmit_field($key.'['.$i.']', $j);
		}
		return implode('', $list);
	}
	return '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($value).'">';
}

/**
 * 소셜 로그인 버튼의 HTML을 반환한다.
 */
add_shortcode('cosmosfarm_members_social_buttons', 'cosmosfarm_members_social_buttons');
function cosmosfarm_members_social_buttons($args=array()){
	global $cosmosfarm_members;
	
	$option = get_cosmosfarm_members_option();
	
	if((!is_user_logged_in() || $option->social_buttons_shortcode_display != '1') && $option->social_login_active){
		
		$redirect_to = isset($args['redirect_to']) && $args['redirect_to'] ? $args['redirect_to'] : $_SERVER['REQUEST_URI'];
		$skin = isset($args['skin']) && $args['skin'] ? $args['skin'] : '';
		$file = isset($args['file']) && $args['file'] ? $args['file'] : '';
		
		return $cosmosfarm_members->social_buttons('shortcode', $redirect_to, $skin, $file);
	}
	
	return '';
}

/**
 * 회원가입, 로그인, 회원정보, 로그아웃 링크를 반환한다.
 */
add_shortcode('cosmosfarm_members_account_links', 'cosmosfarm_members_account_links');
function cosmosfarm_members_account_links($args=array()){
	global $cosmosfarm_members_skin;
	return $cosmosfarm_members_skin->account_links($args);
}

/**
 * 읽지 않은 알림 개수를 반환한다.
 */
add_shortcode('cosmosfarm_members_unread_notifications_count', 'cosmosfarm_members_unread_notifications_count');
function cosmosfarm_members_unread_notifications_count($args=array()){
	$unread_count = 0;
	$user_id = 0;
	
	if(isset($args['user_id']) && $args['user_id']){
		$user_id = intval($args['user_id']);
	}
	
	if(!$user_id){
		$user_id = get_current_user_id();
	}
	
	if($user_id){
		$unread_count = intval(get_user_meta($user_id,  'cosmosfarm_members_unread_notifications_count', true));
	}
	
	return '<span class="cosmosfarm-members-unread-notifications-count'.($unread_count?'':' display-hide').'">' . $unread_count . '</span>';
}

/**
 * 읽지 않는 쪽지 개수를 반환한다.
 */
add_shortcode('cosmosfarm_members_unread_messages_count', 'cosmosfarm_members_unread_messages_count');
function cosmosfarm_members_unread_messages_count($args=array()){
	$unread_count = 0;
	$user_id = 0;
	
	if(isset($args['user_id']) && $args['user_id']){
		$user_id = intval($args['user_id']);
	}
	
	if(!$user_id){
		$user_id = get_current_user_id();
	}
	
	if($user_id){
		$unread_count = intval(get_user_meta($user_id,  'cosmosfarm_members_unread_messages_count', true));
	}
	
	return '<span class="cosmosfarm-members-unread-messages-count'.($unread_count?'':' display-hide').'">' . $unread_count . '</span>';
}

/**
 * 정기결제 상품을 이용중인지 상태를 반환한다.
 */
add_shortcode('cosmosfarm_members_subscription_status_display', 'cosmosfarm_members_subscription_status_display');
function cosmosfarm_members_subscription_status_display($args=array()){
	
	$product_id = '';
	if(is_array($args) && isset($args['product_id']) && $args['product_id']){
		$product_id = intval($args['product_id']);
	}
	
	$order = new Cosmosfarm_Members_Subscription_Order();
	
	if(!$product_id){
		$dispay_message = '숏코드에 product_id 값을 입력해주세요';
		return apply_filters('cosmosfarm_members_subscription_status_display', $dispay_message, 'message_type_1', $product_id, $order);
	}
	
	if(!is_user_logged_in()){
		$dispay_message = '로그인해주세요';
		return apply_filters('cosmosfarm_members_subscription_status_display', $dispay_message, 'message_type_2', $product_id, $order);
	}
	
	$args = array(
		'post_type'  => $order->order_post_type(),
		'author' => get_current_user_id(),
		'orderby' => 'ID',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'product_id',
				'value'   => $product_id,
				'compare' => '=',
			),
			array(
				'key'     => 'status',
				'value'   => 'paid',
				'compare' => '=',
			),
			array(
				'key'     => 'subscription_next',
				'value'   => array('success', 'wait'),
				'compare' => 'IN',
			),
		),
	);
	
	$query = new WP_Query($args);
	if($query->found_posts){
		$order->init_with_id($query->post->ID);
		
		if($order->ID()){
			$end_datetime = $order->end_datetime();
			
			if($end_datetime){
				$dispay_message = sprintf('이용기간 %s 까지', date('Y년 m월 d일 H:i', strtotime($end_datetime)));
				return apply_filters('cosmosfarm_members_subscription_status_display', $dispay_message, 'message_type_3', $product_id, $order);
			}
			else{
				$dispay_message = '이용기간 무제한';
				return apply_filters('cosmosfarm_members_subscription_status_display', $dispay_message, 'message_type_4', $product_id, $order);
			}
		}
	}
	
	$dispay_message = '미가입';
	return apply_filters('cosmosfarm_members_subscription_status_display', $dispay_message, 'message_type_5', $product_id, $order);
}

/**
 * 워드프레스 사용자의 표시할 이름을 반환한다.
 */
add_shortcode('cosmosfarm_members_user_display_name', 'cosmosfarm_members_user_display_name');
function cosmosfarm_members_user_display_name($args=array()){
	$display_name = '';
	
	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
		$display_name = $current_user->display_name;
	}
	
	return apply_filters('cosmosfarm_members_user_display_name', $display_name);
}

/**
 * 불필요한 우커머스 체크아웃 필드를 제거한다.
 */
add_action('wpmem_hooks_loaded', 'cosmosfarm_members_wpmem_hooks_loaded', 10, 1);
function cosmosfarm_members_wpmem_hooks_loaded(){
	remove_filter('woocommerce_checkout_fields', 'wpmem_woo_checkout_form');
	remove_action('woocommerce_checkout_update_order_meta', 'wpmem_woo_checkout_update_meta');
	remove_action('woocommerce_form_field_multicheckbox', 'wpmem_form_field_wc_custom_field_types', 10, 4);
	remove_action('woocommerce_form_field_multiselect',   'wpmem_form_field_wc_custom_field_types', 10, 4);
	remove_action('woocommerce_form_field_radio',         'wpmem_form_field_wc_custom_field_types', 10, 4);
}

add_filter('wpmem_settings', 'cosmosfarm_members_wpmem_settings', 10, 1);
function cosmosfarm_members_wpmem_settings($settings){
	$option = get_cosmosfarm_members_option();
	
	if($option->account_page_id){
		$settings['user_pages']['profile'] = $option->account_page_id;
	}
	
	if($option->register_page_id){
		$settings['user_pages']['register'] = $option->register_page_id;
	}
	
	if($option->login_page_id){
		$settings['user_pages']['login'] = $option->login_page_id;
	}
	
	return $settings;
}

add_action('mycred_init', 'cosmosfarm_members_mycred_init');
function cosmosfarm_members_mycred_init(){
	global $cosmosfarm_members_mycred;
	$cosmosfarm_members_mycred = new Cosmosfarm_Members_Mycred();
}

add_filter('comment_form_defaults', 'cosmosfarm_members_comment_form_defaults', 10, 1);
function cosmosfarm_members_comment_form_defaults($fields){
	$social_buttons = cosmosfarm_members_social_buttons();
	$fields['must_log_in'] = sprintf(__('<div class="must-log-in"><p><a href="%s">로그인</a>을 해야 댓글을 남길 수 있습니다.</p>%s</div>'), wp_login_url(apply_filters('the_permalink', get_permalink())), $social_buttons);
	return $fields;
}

add_action('switch_blog', 'cosmosfarm_members_switch_blog');
function cosmosfarm_members_switch_blog(){
	global $cosmosfarm_members_option;
	$cosmosfarm_members_option = new Cosmosfarm_Members_Option();
}

add_action('plugins_loaded', 'cosmosfarm_members_update_check');
function cosmosfarm_members_update_check(){
	global $wpdb;
	
	if(version_compare(COSMOSFARM_MEMBERS_VERSION, get_option('cosmosfarm_members_version'), '<=')) return;
	
	if(get_option('cosmosfarm_members_version') !== false){
		update_option('cosmosfarm_members_version', COSMOSFARM_MEMBERS_VERSION);
	}
	else{
		add_option('cosmosfarm_members_version', COSMOSFARM_MEMBERS_VERSION, null, 'yes');
	}
	
	cosmosfarm_members_activation_execute();
}

add_filter('cron_schedules', 'cosmosfarm_members_cron_schedules');
function cosmosfarm_members_cron_schedules($schedules){
	if(!isset($schedules['cosmosfarm_members_5min'])){
		$schedules['cosmosfarm_members_5min'] = array(
			'interval' => 60*5,
			'display' => '매 5분'
		);
	}
	if(!isset($schedules['cosmosfarm_members_10min'])){
		$schedules['cosmosfarm_members_10min'] = array(
			'interval' => 60*10,
			'display' => '매 10분'
		);
	}
	if(!isset($schedules['cosmosfarm_members_30min'])){
		$schedules['cosmosfarm_members_30min'] = array(
			'interval' => 60*30,
			'display' => '매 30분'
		);
	}
	return $schedules;
}

register_activation_hook(__FILE__, 'cosmosfarm_members_activation');
function cosmosfarm_members_activation($networkwide){
	global $wpdb;
	if(function_exists('is_multisite') && is_multisite()){
		if($networkwide){
			$old_blog = $wpdb->blogid;
			$blogids = $wpdb->get_col("SELECT `blog_id` FROM {$wpdb->blogs}");
			foreach($blogids as $blog_id){
				switch_to_blog($blog_id);
				cosmosfarm_members_activation_execute();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	cosmosfarm_members_activation_execute();
}

function cosmosfarm_members_activation_execute(){
	global $wpdb;
	
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset_collate = $wpdb->get_charset_collate();
	
	dbDelta("CREATE TABLE `{$wpdb->prefix}cosmosfarm_members_login_history` (
	`login_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) unsigned NOT NULL,
	`login_datetime` datetime NOT NULL,
	`ip_address` varchar(20) NOT NULL,
	`browser` varchar(127) NOT NULL,
	`operating_system` varchar(127) NOT NULL,
	`country_name` varchar(127) NOT NULL,
	`country_code` varchar(127) NOT NULL,
	`login_result` varchar(20) NOT NULL,
	`user_agent` TEXT NOT NULL,
	PRIMARY KEY (`login_history_id`),
	KEY `user_id` (`user_id`)
	) {$charset_collate};");
	
	dbDelta("CREATE TABLE `{$wpdb->prefix}cosmosfarm_members_activity_history` (
	`activity_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) unsigned NOT NULL,
	`related_user_id` bigint(20) unsigned NOT NULL,
	`activity_datetime` datetime NOT NULL,
	`ip_address` varchar(20) NOT NULL,
	`comment` varchar(127) NOT NULL,
	PRIMARY KEY (`activity_history_id`),
	KEY `user_id` (`user_id`),
	KEY `related_user_id` (`related_user_id`)
	) {$charset_collate};");
	
	if(!wp_next_scheduled('cosmosfarm_members_subscription_again')){
		wp_schedule_event(time(), 'cosmosfarm_members_10min', 'cosmosfarm_members_subscription_again');
	}
	
	cosmosfarm_members_rewrite_rule();
	cosmosfarm_members_register_post_type_product();
	flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'cosmosfarm_members_deactivation');
function cosmosfarm_members_deactivation(){
	wp_clear_scheduled_hook('cosmosfarm_members_subscription_again');
	
	flush_rewrite_rules();
}

register_uninstall_hook(__FILE__, 'cosmosfarm_members_uninstall');
function cosmosfarm_members_uninstall(){
	global $wpdb;
	if(function_exists('is_multisite') && is_multisite()){
		$old_blog = $wpdb->blogid;
		$blogids = $wpdb->get_col("SELECT `blog_id` FROM {$wpdb->blogs}");
		foreach($blogids as $blog_id){
			switch_to_blog($blog_id);
			cosmosfarm_members_uninstall_execute();
		}
		switch_to_blog($old_blog);
		return;
	}
	cosmosfarm_members_uninstall_execute();
}

function cosmosfarm_members_uninstall_execute(){
	$option = get_cosmosfarm_members_option();
	$option->truncate();
}