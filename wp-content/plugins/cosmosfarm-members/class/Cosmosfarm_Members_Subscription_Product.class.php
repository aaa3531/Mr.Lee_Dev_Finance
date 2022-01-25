<?php
/**
 * Cosmosfarm_Members_Subscription_Product
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Product extends Cosmosfarm_Members_Subscription_Order {
	
	var $post_type = 'cosmosfarm_product';
	
	public function __construct($product_id=''){
		if($product_id){
			$this->init_with_id($product_id);
		}
	}
	
	public function __get($name){
		if($this->post_id && isset($this->post->{$name})){
			return apply_filters("cosmosfarm_members_subscription_product_{$name}", $this->post->{$name}, $this);
		}
		return '';
	}
	
	public function product_post_type(){
		return 'cosmosfarm_product';
	}
	
	public function product_id(){
		if($this->post_id){
			return intval($this->post_id);
		}
		return 0;
	}
	
	public function set_fields($fields){
		if($this->post_id){
			update_post_meta($this->post_id, 'fields', $fields);
		}
	}
	
	/**
	 * 상품 결제 필드를 반환한다.
	 * @return array
	 */
	public function fields(){
		$fields = array();
		if($this->post_id){
			$fields = get_post_meta($this->post_id, 'fields', true);
		}
		return apply_filters('cosmosfarm_members_subscription_product_fields', $fields, $this);
	}
	
	/**
	 * 상품 갤러리 이미지의 ID 값을 입력 받는다.
	 * @param array $gallery_images
	 */
	public function set_gallery_images($gallery_images){
		if($this->post_id){
			update_post_meta($this->post_id, 'gallery_images', $gallery_images);
		}
	}
	
	/**
	 * 상품 갤러리 이미지의 ID 값을 반환한다.
	 * @return array
	 */
	public function gallery_images(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'gallery_images', true);
			if($value){
				return $value;
			}
		}
		return array();
	}
	
	public function order_view_fields(){
		$order_view_fields = array();
		
		if($this->post_id){
			foreach($this->fields() as $key=>$field){
				if($field['type'] == 'hr') continue;
				//if($field['type'] == 'hidden') continue;
				if(!$field['meta_key']) continue;
				if(!$field['order_view']) continue; // 주문내역에 숨김, 표시 체크
				
				array_push($order_view_fields, $field);
			}
		}
		return $order_view_fields;
	}
	
	public function get_admin_field_template($field=array(), $field2=array(), $field3=array()){
		ob_start();
		if(isset($field['type']) && $field['type']){
			$field_type = $field['type'];
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/subscription_product_field_template.php';
			do_action('cosmosfarm_members_product_admin_field_template', $field_type, $field, $field2, $field3);
		}
		return ob_get_clean();
	}
	
	public function get_admin_field_template_list(){
		$list = array(
			'buyer_name'  => '주문자명',
			'buyer_email' => '주문자 이메일',
			'buyer_tel'   => '주문자 전화번호',
			'text'        => '텍스트',
			'number'      => '숫자',
			'select'      => '셀렉트',
			'radio'       => '라디오',
			'checkbox'    => '체크박스',
			'zip'         => '주소',
			'textarea'    => '텍스트에어리어',
			'datepicker'  => '날짜선택',
			'timepicker'  => '시간선택',
			'weekpicker'  => '요일선택',
			'agree'       => '동의하기',
			'hr'          => '구분선',
			'hidden'      => '숨김필드'
		);
		return apply_filters('cosmosfarm_members_product_admin_field_template_list', $list);
	}
	
	/**
	 * 상품 주문 페이지의 주소를 반환한다.
	 * @return string
	 */
	public function get_order_url(){
		if($this->post_id){
			$option = get_cosmosfarm_members_option();
			if($option->subscription_checkout_page_id){
				$order_url = get_permalink($option->subscription_checkout_page_id);
				$order_url = add_query_arg(array('cosmosfarm_product_id'=>$this->post_id, 'cosmosfarm_redirect_to'=>$_SERVER['REQUEST_URI']), $order_url);
				return $order_url;
			}
		}
		return '';
	}
	
	/**
	 * 상품 주문 페이지의 주소를 반환한다.
	 * @return string
	 */
	public function get_order_url_without_redirect(){
		if($this->post_id){
			$option = get_cosmosfarm_members_option();
			if($option->subscription_checkout_page_id){
				$order_url = get_permalink($option->subscription_checkout_page_id);
				$order_url = add_query_arg(array('cosmosfarm_product_id'=>$this->post_id), $order_url);
				return $order_url;
			}
		}
		return '';
	}
	
	/**
	 * 상품 주문 페이지의 주소를 반환한다.
	 * @return string
	 */
	public function get_order_url_without_query(){
		if($this->post_id){
			$option = get_cosmosfarm_members_option();
			if($option->subscription_checkout_page_id){
				$order_url = get_permalink($option->subscription_checkout_page_id);
				return $order_url;
			}
		}
		return '';
	}
	
	/**
	 * 현재 결제 후 이용중인지 확인한다.
	 * {@inheritDoc}
	 * @see Cosmosfarm_Members_Subscription_Order::is_in_use()
	 */
	public function is_in_use($user_id=''){
		return parent::is_in_use($user_id);
	}
	
	/**
	 * 첫 결제 무료인지 확인한다. (첫 결제 무료 이용이 설정되어 있어도 상황에 따라서 무료 이용이 아닐 수 있다.)
	 * @param string $user_id
	 * @param array $args
	 * @return string
	 */
	public function is_subscription_first_free($user_id='', $args=array()){
		$first_free = false;
		if($this->post_id){
			if($this->subscription_type() != 'onetime' && $this->subscription_active() && $this->subscription_first_free()){
				if(!$user_id){
					$user_id = get_current_user_id();
				}
				
				$user = new WP_User($user_id);
				
				if($user->ID){
					$query_args = array(
						'post_type'  => $this->order_post_type(),
						'author' => $user->ID,
						'orderby' => 'ID',
						'posts_per_page' => -1,
						'meta_query' => array(
							array(
								'key'     => 'product_id',
								'value'   => $this->post_id,
								'compare' => '='
							)
						)
					);
					$query = new WP_Query($query_args);
					if(!$query->found_posts){
						$first_free = true;
					}
				}
			}
		}
		return apply_filters('cosmosfarm_members_subscription_product_first_free', $first_free, $this, $args);
	}
	
	/**
	 * 상품 태그 정보를 저장한다.
	 * @param string|array $tags
	 * @param boolean $append
	 */
	public function set_tags($tags, $append=false){
		if($this->post_id){
			if(!is_array($tags)){
				$tags = explode(',', $tags);
				$tags = array_map('trim', $tags);
			}
			wp_set_object_terms($this->post_id, $tags, 'cosmosfarm_product_tag', $append);
		}
	}
	
	/**
	 * 상품 태그 정보를 반환한다.
	 * @return array()
	 */
	public function tags(){
		if($this->post_id){
			$terms = wp_get_object_terms($this->post_id, 'cosmosfarm_product_tag');
			return $terms;
		}
		return array();
	}
	
	/**
	 * 상품 태그 정보를 문자열로 반환한다.
	 * @return string
	 */
	public function tags_to_string(){
		$tags = array();
		if($this->post_id){
			foreach($this->tags() as $tag){
				$tags[] = $tag->name;
			}
		}
		return implode(',', $tags);
	}
	
	/**
	 * 상품 태그 검색 링크를 반환한다.
	 * @param int $term_id
	 * @return string
	 */
	public function get_tag_link($term_id){
		$term = get_term($term_id, 'cosmosfarm_product_tag');
		return add_query_arg(array('tag'=>$term->name), get_cosmosfarm_members_product_list_url());
	}
	
	/**
	 * 상품 상세페이지 링크를 반환한다.
	 * @return string
	 */
	public function permalink(){
		if($this->post_id){
			return get_post_permalink($this->post_id);
		}
		return '';
	}
	
	/**
	 * 별점 평균을 반환한다.
	 * @return int
	 */
	public function average_ratings(){
		if($this->post_id){
			$average_ratings = get_post_meta($this->post_id, 'average_ratings', true);
			return round($average_ratings, 1);
		}
		return '';
	}
	
	/**
	 * 상품의 결제방식을 반환한다.
	 * @param string $subscription_pg_type
	 */
	public function set_subscription_pg_type($subscription_pg_type){
		if($this->post_id){
			if($subscription_pg_type == 'general'){ // 일반결제
				update_post_meta($this->post_id, 'subscription_pg_type', 'general');
			}
			else if($subscription_pg_type == 'billing'){ // 빌링결제
				update_post_meta($this->post_id, 'subscription_pg_type', 'billing');
			}
			else{ // 설정에 의해 결정
				update_post_meta($this->post_id, 'subscription_pg_type', '');
			}
		}
	}
	
	/**
	 * 상품의 결제방식을 반환한다. (값이 없다면 빌링결제, 일반결제는 general 반환)
	 * @return string
	 */
	public function get_subscription_pg_type(){
		$subscription_pg_type = 'billing';
		if($this->post_id){
			$subscription_pg_type = get_post_meta($this->post_id, 'subscription_pg_type', true);
			
			if($subscription_pg_type != 'general' && $subscription_pg_type != 'billing'){
				$subscription_pg_type = get_cosmosfarm_members_subscription_pg_type();
			}
			/*
			else if($subscription_pg_type == 'billing'){
				$subscription_pg_type = '';
			}
			*/
		}
		return apply_filters('cosmosfarm_members_subscription_product_pg_type', $subscription_pg_type, $this);
	}
	
	/**
	 * 확장된 정기결제 기능 사용 여부를 저장한다.
	 * @param string $active
	 */
	public function set_subscription_item_active($active){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_item_active', $active);
		}
	}
	
	/**
	 * 확장된 정기결제 기능 사용 여부를 반환한다.
	 * @return string
	 */
	public function is_subscription_item_active(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_item_active', true);
			if($value){
				return $value;
			}
		}
		return array();
	}
	
	/**
	 * 결제시 사용 가능한 쿠폰이 있는지 확인한다.
	 * @return array
	 */
	public function is_coupon_available(){
		$coupon_available = array();
		
		if($this->post_id){
			$coupon = new Cosmosfarm_Members_Subscription_Coupon();
			$today_ymd = date('Ymd', current_time('timestamp'));
			
			$args = array(
				'post_type'      => $coupon->post_type,
				'orderby'        => 'ID',
				'posts_per_page' => -1,
				'meta_query' => array (
					'relation' => 'AND',
					array (
						'key' => 'product_id',
						'value' => array($this->post_id),
						'compare' => 'IN'
					),
					array (
						'key' => 'coupon_active',
						'value' => '1',
						'compare' => '='
					),
					array(
						'relation' => 'OR',
						array(
							'key'     => 'usage_date',
							'value'   => 'continue',
							'compare' => '='
						),
						array(
							'relation' => 'AND',
							array(
								'key'     => 'usage_start_date',
								'value'   => $today_ymd,
								'compare' => '<='
							),
							array(
								'key'     => 'usage_end_date',
								'value'   => $today_ymd,
								'compare' => '>='
							)
						)
					)
				)
			);
			$query = new WP_Query($args);
			
			$coupon_available = $query->posts;
		}
		
		return $coupon_available;
	}
	
	/**
	 * 사용 가능한 쿠폰 목록을 반환한다.
	 * @return Cosmosfarm_Members_Subscription_Coupon[]
	 */
	public function get_coupon_list(){
		$coupon_list = array();
		$coupon_available = $this->is_coupon_available();
		
		foreach($coupon_available as $post){
			$coupon = new Cosmosfarm_Members_Subscription_Coupon();
			$coupon->init_with_id($post->ID);
			$coupon_list[] = $coupon;
		}
		
		return $coupon_list;
	}
	
	/**
	 * 첫 결제완료 문자 메시지를 저장한다.
	 * @param string $subscription_send_sms_paid
	 */
	public function set_subscription_send_sms_paid($subscription_send_sms_paid){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_send_sms_paid', $subscription_send_sms_paid);
		}
	}
	
	/**
	 * 첫 결제완료 문자 메시지를 반환한다.
	 * @return string
	 */
	public function get_subscription_send_sms_paid(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_send_sms_paid', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 정기결제 문자 메시지를 저장한다.
	 * @param string $subscription_send_sms_again
	 */
	public function set_subscription_send_sms_again($subscription_send_sms_again){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_send_sms_again', $subscription_send_sms_again);
		}
	}
	
	/**
	 * 정기결제 문자 메시지를 반환한다.
	 * @return string
	 */
	public function get_subscription_send_sms_again(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_send_sms_again', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 결제 실패 문자 메시지를 저장한다.
	 * @param string $subscription_send_sms_again_failure
	 */
	public function set_subscription_send_sms_again_failure($subscription_send_sms_again_failure){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_send_sms_again_failure', $subscription_send_sms_again_failure);
		}
	}
	
	/**
	 * 결제 실패 문자 메시지를 반환한다.
	 * @return string
	 */
	public function get_subscription_send_sms_again_failure(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_send_sms_again_failure', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 만료 문자 메시지를 저장한다.
	 * @param string $subscription_send_sms_expiry
	 */
	public function set_subscription_send_sms_expiry($subscription_send_sms_expiry){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_send_sms_expiry', $subscription_send_sms_expiry);
		}
	}
	
	/**
	 * 만료 문자 메시지를 반환한다.
	 * @return string
	 */
	public function get_subscription_send_sms_expiry(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_send_sms_expiry', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 자동결제 상태 업데이트 설정을 저장한다.
	 * @param string $subscription_user_update
	 */
	public function set_subscription_user_update($subscription_user_update){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_user_update', $subscription_user_update);
		}
	}
	
	/**
	 * 자동결제 상태 업데이트 설정을 반환한다.
	 * @return string
	 */
	public function is_subscription_user_update(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_user_update', true);
			if($value){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 자동결제 상태 업데이트 메시지를 저장한다.
	 * @param string $subscription_user_update_message
	 */
	public function set_subscription_user_update_message($subscription_user_update_message){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_user_update_message', $subscription_user_update_message);
		}
	}
	
	/**
	 * 자동결제 상태 업데이트 메시지를 반환한다.
	 * @return string
	 */
	public function get_subscription_user_update_message(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_user_update_message', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 자동결제 중지 제한 설정을 저장한다.
	 * @param string $subscription_deactivate_pay_count
	 */
	public function set_subscription_deactivate_pay_count($subscription_deactivate_pay_count){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_deactivate_pay_count', $subscription_deactivate_pay_count);
		}
	}
	
	/**
	 * 자동결제 중지 제한 설정을 반환한다.
	 * @return string
	 */
	public function get_subscription_deactivate_pay_count(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_deactivate_pay_count', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 자동결제 중지 메시지를 저장한다.
	 * @param string $subscription_deactivate_pay_count_message
	 */
	public function set_subscription_deactivate_pay_count_message($subscription_deactivate_pay_count_message){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_deactivate_pay_count_message', $subscription_deactivate_pay_count_message);
		}
	}
	
	/**
	 * 자동결제 중지 메시지를 반환한다.
	 * @return string
	 */
	public function get_subscription_deactivate_pay_count_message(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_deactivate_pay_count_message', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
}