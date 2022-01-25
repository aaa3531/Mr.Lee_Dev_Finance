<?php
/**
 * Cosmosfarm_Members_Page_Builder
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Page_Builder {
	
	static $raw_print_index = 0;
	
	public function __construct(){
		register_nav_menu('cosmosfarm-members-header-menu', __('Cosmosfarm Members Header Menu', 'cosmosfarm-members'));
		add_shortcode('cosmosfarm_members_login_form', array($this, 'shortcode_login_form'));
		add_shortcode('cosmosfarm_members_header_menu', array($this, 'shortcode_header_menu'));
		add_shortcode('cosmosfarm_members_notifications', array($this, 'shortcode_notifications'));
		add_shortcode('cosmosfarm_members_messages', array($this, 'shortcode_messages'));
		add_shortcode('cosmosfarm_members_orders', array($this, 'shortcode_orders'));
		add_shortcode('cosmosfarm_members_users', array($this, 'shortcode_users'));
		add_shortcode('cosmosfarm_members_user_profile', array($this, 'shortcode_user_profile'));
		add_shortcode('cosmosfarm_members_subscription_product', array($this, 'shortcode_subscription_product'));
		add_shortcode('cosmosfarm_members_subscription_product_title', array($this, 'shortcode_subscription_product_title'));
		add_shortcode('cosmosfarm_members_subscription_product_price', array($this, 'shortcode_subscription_product_price'));
		add_shortcode('cosmosfarm_members_subscription_product_type', array($this, 'shortcode_subscription_product_type'));
		add_shortcode('cosmosfarm_members_subscription_product_first_free', array($this, 'shortcode_subscription_product_first_free'));
		add_shortcode('cosmosfarm_members_subscription_product_button', array($this, 'shortcode_subscription_product_button'));
		add_shortcode('cosmosfarm_members_subscription_product_list', array($this, 'shortcode_subscription_product_list'));
		add_shortcode('cosmosfarm_members_subscription_product_latest', array($this, 'shortcode_subscription_product_latest'));
		add_shortcode('cosmosfarm_members_subscription_checkout', array($this, 'shortcode_subscription_checkout'));
		add_filter('single_template', array($this, 'subscription_product_single_template'));
		add_filter('wpmem_login_form', array($this, 'form_layout'), 10, 2);
		add_filter('wpmem_member_links_args', array($this, 'member_links_args'), 10, 1);
		add_filter('wpmem_register_links_args', array($this, 'register_links_args'), 10, 1);
		add_filter('wpmem_login_links_args', array($this, 'login_links_args'), 10, 1);
	}
	
	public function shortcode_login_form($atts=array()){
		global $wpmem;
		
		if(is_user_logged_in()){
			$layout = apply_filters('cosmosfarm_members_login_form_user_logged_in', '');
		}
		else{
			$layout = do_shortcode('[wpmem_form login]');
		}
		
		if(isset($atts['raw_print']) && $atts['raw_print']){
			$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
			echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout. '</div>';
			return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
		}
		
		return $layout;
	}
	
	public function shortcode_header_menu($atts=array()){
		global $cosmosfarm_members_skin;
		
		$current_page = isset($atts['current_page']) ? $atts['current_page'] : '';
		
		$layout = $cosmosfarm_members_skin->header($current_page);
		
		if(isset($atts['raw_print']) && $atts['raw_print']){
			$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
			echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout . '</div>';
			return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
		}
		return $layout;
	}
	
	public function shortcode_notifications($atts=array()){
		global $cosmosfarm_members_skin;
		
		if(is_user_logged_in()){
			$layout = $cosmosfarm_members_skin->notifications();
			
			if(isset($atts['raw_print']) && $atts['raw_print']){
				$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
				echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout . '</div>';
				return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
			}
			return $layout;
		}
		return '';
	}
	
	public function shortcode_messages($atts=array()){
		global $cosmosfarm_members_skin;
		
		if(is_user_logged_in()){
			$layout = $cosmosfarm_members_skin->messages();
			
			if(isset($atts['raw_print']) && $atts['raw_print']){
				$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
				echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout . '</div>';
				return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
			}
			return $layout;
		}
		return '';
	}
	
	public function shortcode_orders($atts=array()){
		global $cosmosfarm_members_skin;
		
		if(is_user_logged_in()){
			$layout = $cosmosfarm_members_skin->orders();
			
			if(isset($atts['raw_print']) && $atts['raw_print']){
				$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
				echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout . '</div>';
				return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
			}
			return $layout;
		}
		return '';
	}
	
	public function shortcode_users($atts=array()){
		global $cosmosfarm_members_skin;
		
		if(is_user_logged_in()){
			$layout = $cosmosfarm_members_skin->users();
			
			if(isset($atts['raw_print']) && $atts['raw_print']){
				$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
				echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout . '</div>';
				return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
			}
			return $layout;
		}
		return '';
	}
	
	public function shortcode_user_profile($atts=array()){
		global $cosmosfarm_members_skin;
		
		$layout = $cosmosfarm_members_skin->user_profile();
		
		if(isset($atts['raw_print']) && $atts['raw_print']){
			$raw_print_index = Cosmosfarm_Members_Page_Builder::$raw_print_index++;
			echo '<div data-target="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_content">' . $layout . '</div>';
			return '<div id="cosmosfarm_members_raw_print_' . $raw_print_index . '" class="cosmosfarm_members_raw_print"></div>';
		}
		return $layout;
	}
	
	public function shortcode_subscription_product($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = 0;
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		if($product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if($product->ID()){
				return $cosmosfarm_members_skin->subscription_product($product);
			}
		}
		
		return '';
	}
	
	public function shortcode_subscription_product_title($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = 0;
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		if($product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if($product->ID()){
				return $cosmosfarm_members_skin->subscription_product_title($product);
			}
		}
		
		return '';
	}
	
	public function shortcode_subscription_product_price($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = 0;
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		if($product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if($product->ID()){
				return $cosmosfarm_members_skin->subscription_product_price($product);
			}
		}
		
		return '';
	}
	
	public function shortcode_subscription_product_type($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = 0;
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		if($product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if($product->ID()){
				return $cosmosfarm_members_skin->subscription_product_type($product);
			}
		}
		
		return '';
	}
	
	public function shortcode_subscription_product_first_free($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = 0;
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		if($product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if($product->ID()){
				return $cosmosfarm_members_skin->subscription_product_first_free($product);
			}
		}
		
		return '';
	}
	
	public function shortcode_subscription_product_button($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = 0;
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		if($product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			if($product->ID()){
				return $cosmosfarm_members_skin->subscription_product_button($product);
			}
		}
		
		return '';
	}
	
	/**
	 * 정기결제 상품 목록 페이지 숏코드
	 * @param array $args
	 * @return string
	 */
	public function shortcode_subscription_product_list($args=array()){
		global $cosmosfarm_members_skin;
		
		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
		$tag = isset($_GET['tag']) ? sanitize_text_field($_GET['tag']) : '';
		
		$args = shortcode_atts(array(
			'post_type'      => array('posts', 'cosmosfarm_product'),
			'order'          => 'DESC',
			'orderby'        => 'ID',
			'posts_per_page' => 6,
			'paged'          => get_query_var('paged'),
			's'              => $keyword
		), $args, 'cosmosfarm_members_subscription_product_list');
		
		if($tag){
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'cosmosfarm_product_tag',
					'field'    => 'slug',
					'terms'    => array($tag)
				)
			);
		}
		
		$query = new WP_Query($args);
		
		$html = $cosmosfarm_members_skin->subscription_product_list($query);
		
		return $html;
	}
	
	/**
	 * 정기결제 상품 최신글 숏코드
	 * @param array $args
	 * @return string
	 */
	public function shortcode_subscription_product_latest($args=array()){
		global $cosmosfarm_members_skin;
		
		$args = shortcode_atts(array(
			'post_type'      => array('posts', 'cosmosfarm_product'),
			'order'          => 'DESC',
			'orderby'        => 'ID',
			'posts_per_page' => 3
		), $args, 'cosmosfarm_members_subscription_product_latest');
		
		$query = new WP_Query($args);
		
		$html = $cosmosfarm_members_skin->subscription_product_latest($query);
		
		return $html;
	}
	
	/**
	 * 정기결제 상품 상세페이지 템플릿
	 * @return string
	 */
	public function subscription_product_single_template($single_template){
		global $cosmosfarm_members_skin, $post;
		
		if($post->post_type == cosmosfarm_members_subscription_product()->post_type){
			$product_template = $cosmosfarm_members_skin->subscription_product_single_template($post->ID);
			
			if($product_template){
				$single_template = $product_template;
			}
			
			$comments_reviews = new Cosmosfarm_Members_Comments_Reviews();
			$comments_reviews->add_field_to_comment_form();
			
			add_filter('comments_template', array($this, 'comments_reviews'));
		}
		
		return $single_template;
	}
	
	function comments_reviews($comment_template){
		global $cosmosfarm_members_skin, $post;
		
		if($post->post_type == cosmosfarm_members_subscription_product()->post_type){
			$comment_template = $cosmosfarm_members_skin->comments_reviews($post->ID);
		}
		
		return $comment_template;
	}
	
	/**
	 * 정기결제 상품 결제 페이지 숏코드
	 * @param array $args
	 * @return string
	 */
	public function shortcode_subscription_checkout($args=array()){
		global $cosmosfarm_members_skin;
		
		$product_id = isset($_GET['cosmosfarm_product_id']) ? intval($_GET['cosmosfarm_product_id']) : '';
		if(isset($args['id']) && $args['id']){
			$product_id = intval($args['id']);
		}
		
		$product = new Cosmosfarm_Members_Subscription_Product($product_id);
		if($product->ID()){
			return $cosmosfarm_members_skin->subscription_checkout($product);
		}
		return __('There is no product information to pay for.', 'cosmosfarm-members');
	}
	
	public function form_layout($form, $action){
		global $cosmosfarm_members_skin;
		
		if($action == 'login'){
			$form = $cosmosfarm_members_skin->login_form($form, $action);
		}
		else if($action == 'pwdchange'){
			$form = $cosmosfarm_members_skin->change_password_form($form, $action);
		}
		
		return $form;
	}
	
	public function member_links_args($args){
		global $wpmem;
		
		if(!is_array($args)){
			$args = array();
		}
		
		$current_user = wp_get_current_user();
		
		if($current_user->cosmosfarm_members_social_id && $current_user->cosmosfarm_members_social_channel){
			unset($args['rows'][1]);
		}
		
		if(isset($_POST['cosmosfarm_members_avatar_nonce']) && wp_verify_nonce($_POST['cosmosfarm_members_avatar_nonce'], 'cosmosfarm_members_avatar')){
			
			$file_handler = get_cosmosfarm_members_file_handler();
			$upload_file = $file_handler->upload_avatar('cosmosfarm_members_avatar_file');
			
			if($upload_file){
				$cosmosfarm_members_avatar = get_user_meta($current_user->ID, 'cosmosfarm_members_avatar', true);
				
				if($cosmosfarm_members_avatar){
					$upload_dir = wp_upload_dir();
					@unlink("{$upload_dir['basedir']}{$cosmosfarm_members_avatar}");
				}
				
				update_user_meta($current_user->ID, 'cosmosfarm_members_avatar', $upload_file['url']);
			}
		}
		
		$args['wrapper_before'] = '<div class="cosmosfarm-members-form">';
		
		$args['wrapper_before'] .= '<div class="profile-header"><form id="cosmosfarm_members_avatar_form" method="post" enctype="multipart/form-data">';
		$args['wrapper_before'] .= wp_nonce_field('cosmosfarm_members_avatar', 'cosmosfarm_members_avatar_nonce');
		$args['wrapper_before'] .= '';
		
		$args['wrapper_before'] .= '<div class="avatar-img"><label for="cosmosfarm_members_avatar_file" title="'.__('Change Avatar', 'cosmosfarm-members').'">'.get_avatar(get_current_user_id(), '150').'<p class="change-avatar-message">'.__('Change Avatar', 'cosmosfarm-members').'</p><input type="file" name="cosmosfarm_members_avatar_file" id="cosmosfarm_members_avatar_file" multiple="false" accept="image/*" onchange="cosmosfarm_members_avatar_form_submit(this)"></label></div>';
		$args['wrapper_before'] .= '<div class="display-name">'.$current_user->display_name.'</div>';
		
		$args['wrapper_before'] .= '</form></div>';
		
		$args['wrapper_before'] .= '<ul class="members-link">';
		$args['wrapper_after'] = '</ul></div>';
		
		$rows = array();
		
		$orders_url = get_cosmosfarm_members_orders_url();
		if($orders_url){
			$rows[] = '<li class="subscription-orders"><a href="'.$orders_url.'">'.__('Orders', 'cosmosfarm-members').'</a></li>';
		}
		
		if(class_exists('WooCommerce')){
			$woocommerce_myaccount_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
			
			$rows[] = '<li class="orders"><a href="'.wc_get_endpoint_url('orders', '', $woocommerce_myaccount_url).'">'.__('Orders', 'cosmosfarm-members').'</a></li>';
			
			if(class_exists('MSPS_User')){
				$msps_user = new MSPS_User(get_current_user_id());
				$rows[] = '<li class="mshop-point"><a href="'.wc_get_endpoint_url('mshop-point', '', $woocommerce_myaccount_url).'">'.__('My Points', 'cosmosfarm-members').' : ' . number_format($msps_user->get_point(), wc_get_price_decimals()) . '</a></li>';
			}
		}
		
		if(class_exists('myCRED_Core')){
			$rows[] ='<li class="mycred"><a href="#" onclick="alert(\''.__('Thank you.', 'cosmosfarm-members').'\');return false;">'.__('My Points', 'cosmosfarm-members').' : ' . number_format(mycred_get_users_cred(get_current_user_id())) . '</a></li>';
		}
		
		if(isset($rows) && $rows){
			$args['rows'] = array_merge($rows, $args['rows']);
		}
		
		$logout_url = wp_logout_url(wp_login_url());
		$args['rows'][] = '<li class="logout"><a href="'.$logout_url.'">'.__('Log Out', 'cosmosfarm-members').'</a></li>';
		
		$option = get_cosmosfarm_members_option();
		if($option->use_delete_account){
			$delete_account_url = wp_nonce_url(add_query_arg(array('action'=>'cosmosfarm_members_delete_account'), $_SERVER['REQUEST_URI']), 'cosmosfarm_members_delete_account', 'cosmosfarm_members_delete_account_nonce');
			$args['rows'][] ='<li class="delete-account"><a href="'.$delete_account_url.'" onclick="return confirm(\''.__('Press OK button to delete all information from DB.\nDo you want to delete account?\nYou can also re-register at any time.', 'cosmosfarm-members').'\')">'.__('Delete account', 'cosmosfarm-members').'</a></li>';
		}
		
		return $args;
	}
	
	public function register_links_args($args){
		global $wpmem;
		
		if(!is_array($args)){
			$args = array();
		}
		/*
		if(is_user_logged_in()){
			$profile_url = get_cosmosfarm_members_profile_url();
			if($profile_url){
				echo "<script>window.location.href='{$profile_url}';</script>";
			}
		}
		*/
		return $args;
	}
	
	public function login_links_args($args){
		global $wpmem;
		
		if(!is_array($args)){
			$args = array();
		}
		/*
		if(is_user_logged_in()){
			$profile_url = get_cosmosfarm_members_profile_url();
			if($profile_url){
				echo "<script>window.location.href='{$profile_url}';</script>";
			}
		}
		*/
		return $args;
	}
}
?>