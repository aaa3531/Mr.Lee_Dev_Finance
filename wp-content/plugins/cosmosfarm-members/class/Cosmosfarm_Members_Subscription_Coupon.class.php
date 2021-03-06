<?php
/**
 * Cosmosfarm_Members_Subscription_Coupon
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Coupon {
	
	var $post_type = 'cosmosfarm_coupon';
	var $post;
	var $post_id = 0;
	
	public function __construct($coupon_id=''){
		if($coupon_id){
			$this->init_with_id($coupon_id);
		}
	}
	
	public function __get($name){
		if($this->post_id && isset($this->post->{$name})){
			return $this->post->{$name};
		}
		return '';
	}
	
	public function __set($name, $value){
		if($this->post_id){
			$this->post->{$name} = $value;
		}
	}
	
	public function coupon_post_type(){
		return 'cosmosfarm_coupon';
	}
	
	public function init_with_id($post_id){
		$this->post_id = 0;
		$post_id = intval($post_id);
		if($post_id){
			$this->post = get_post($post_id);
			if($this->post && $this->post->ID){
				$this->post_id = $this->post->ID;
			}
		}
	}
	
	public function init_with_coupon_code($coupon_code){
		$this->post_id = 0;
		
		if($coupon_code){
			$args = array(
				'post_type'  => $this->post_type,
				'meta_query' => array(
					array(
						'key'     => 'coupon_code',
						'value'   => $coupon_code,
						'compare' => '='
					)
				)
			);
			$query = new WP_Query($args);
			
			if($query->posts){
				$this->init_with_id($query->posts[0]->ID);
			}
		}
	}
	
	public function ID(){
		return intval($this->post_id);
	}
	
	public function title(){
		return $this->post_title;
	}
	
	public function content(){
		return $this->post_content;
	}
	
	public function excerpt(){
		return $this->post_excerpt ? $this->post_excerpt : $this->post_content;
	}
	
	public function name(){
		return $this->post_name;
	}
	
	public function create($user_id, $args){
		$user_id = intval($user_id);
		$title = isset($args['title']) ? $args['title'] : '';
		$content = isset($args['content']) ? $args['content'] : '';
		$excerpt = isset($args['excerpt']) ? $args['excerpt'] : '';
		$name = isset($args['name']) ? $args['name'] : '';
		$meta_input = isset($args['meta_input']) ? $args['meta_input'] : array();
		
		$this->post_id = wp_insert_post(array(
			'post_title'     => wp_strip_all_tags($title),
			'post_content'   => $content,
			'post_excerpt'   => $excerpt,
			'post_name'      => $name,
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => $user_id,
			'post_type'      => $this->post_type,
			'meta_input'     => $meta_input
		));
		return $this->post_id;
	}
	
	public function update($args){
		if($this->post_id){
			$args['ID'] = $this->post_id;
			
			if(isset($args['title'])){
				$args['post_title'] = $args['title'];
			}
			
			if(isset($args['content'])){
				$args['post_content'] = $args['content'];
			}
			
			if(isset($args['excerpt'])){
				$args['post_excerpt'] = $args['excerpt'];
			}
			
			if(isset($args['name'])){
				$args['post_name'] = $args['name'];
			}
			
			wp_update_post($args);
		}
	}
	
	public function delete(){
		if($this->post_id){
			wp_delete_post($this->post_id);
		}
	}
	
	/**
	 * ?????? ????????? ????????????.
	 * @param string $coupon_code
	 */
	public function set_coupon_code($coupon_code){
		if($this->post_id){
			update_post_meta($this->post_id, 'coupon_code', $coupon_code);
		}
	}
	
	public function coupon_code(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'coupon_code', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ?????? ?????? ?????? ????????? ????????????.
	 * @param int $active
	 */
	public function set_active($active){
		if($this->post_id){
			update_post_meta($this->post_id, 'coupon_active', $active);
		}
	}
	
	public function active(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'coupon_active', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ?????? ?????? ?????? ????????? ????????????.
	 * @param int $usage_limit
	 */
	public function set_usage_limit($usage_limit){
		if($this->post_id){
			update_post_meta($this->post_id, 'usage_limit', $usage_limit);
		}
	}
	
	public function usage_limit(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'usage_limit', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ??????????????? ?????? ?????? ????????? ????????????.
	 * @param int $usage_count
	 */
	public function set_usage_count($usage_count){
		if($this->post_id){
			$usage_count = intval($usage_count);
			update_post_meta($this->post_id, 'usage_count', $usage_count);
		}
	}
	
	public function usage_count(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'usage_count', true);
			if($value){
				return intval($value);
			}
		}
		return 0;
	}
	
	/**
	 * ?????? ?????? ????????? ????????????.
	 * @param string $usage_date
	 */
	public function set_usage_date($usage_date){
		if($this->post_id){
			update_post_meta($this->post_id, 'usage_date', $usage_date);
		}
	}
	
	public function usage_date(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'usage_date', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ?????? ?????? ???????????? ????????????.
	 * @param string $usage_start_date
	 */
	public function set_usage_start_date($usage_start_date){
		if($this->post_id){
			$usage_start_date = date('Ymd', strtotime($usage_start_date));
			update_post_meta($this->post_id, 'usage_start_date', $usage_start_date);
		}
	}
	
	public function usage_start_date($format='Y-m-d'){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'usage_start_date', true);
			if($value){
				return date($format, strtotime($value));
			}
		}
		return '';
	}
	
	/**
	 * ?????? ?????? ???????????? ????????????.
	 * @param string $usage_end_date
	 */
	public function set_usage_end_date($usage_end_date){
		if($this->post_id){
			$usage_end_date = date('Ymd', strtotime($usage_end_date));
			update_post_meta($this->post_id, 'usage_end_date', $usage_end_date);
		}
	}
	
	public function usage_end_date($format='Y-m-d'){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'usage_end_date', true);
			if($value){
				return date($format, strtotime($value));
			}
		}
		return '';
	}
	
	/**
	 * ?????? ?????? ????????? ????????????.
	 * @param string $discount
	 */
	public function set_discount($discount){
		if($this->post_id){
			update_post_meta($this->post_id, 'discount', $discount);
		}
	}
	
	public function discount(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'discount', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ????????????, ???????????? ????????????.
	 * @param int $discount_amount
	 */
	public function set_discount_amount($discount_amount){
		if($this->post_id){
			update_post_meta($this->post_id, 'discount_amount', $discount_amount);
		}
	}
	
	public function discount_amount(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'discount_amount', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ?????? ????????? ????????????.
	 * @param string $discount_cycle
	 */
	public function set_discount_cycle($discount_cycle){
		if($this->post_id){
			update_post_meta($this->post_id, 'discount_cycle', $discount_cycle);
		}
	}
	
	public function discount_cycle(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'discount_cycle', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * ?????? ????????? ????????????.
	 * @param array $product_ids
	 */
	public function set_product_ids($product_ids){
		if($this->post_id){
			$post_meta = get_post_meta($this->post_id, 'product_id');
			
			foreach($product_ids as $product_id){
				if(in_array($product_id, $post_meta)){
					$key = array_search($product_id, $post_meta);
					unset($post_meta[$key]);
				}
				else{
					add_post_meta($this->post_id, 'product_id', $product_id);
				}
			}
			
			foreach($post_meta as $product_id){
				delete_post_meta($this->post_id, 'product_id', $product_id);
			}
		}
	}
	
	public function product_ids(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'product_id');
			if($value){
				return $value;
			}
		}
		return array();
	}
	
	/**
	 * ????????? ????????? ????????? ??? ????????? ????????????.
	 * @param int $product_id
	 * @return boolean
	 */
	public function is_available($product_id=''){
		if($this->post_id){
			if($this->active()){
				$usage_limit = intval($this->usage_limit());
				$usage_count = intval($this->usage_count());
				
				if(!$usage_limit || $usage_limit > $usage_count){
					if($this->usage_date() == 'continue' || $this->usage_end_date('Ymd') >= date('Ymd', current_time('timestamp'))){
						if(!$product_id || in_array($product_id, $this->product_ids())){
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	/**
	 * ??????????????? ????????? ?????? ????????? ????????????.
	 * @param int $user_id
	 * @param int $product_id
	 * @param int $coupon_id
	 */
	public function save_coupon_id($user_id, $product_id, $coupon_id=''){
		$cosmosfarm_coupon = get_user_meta($user_id, 'cosmosfarm_coupon', true);
		if(!is_array($cosmosfarm_coupon)){
			$cosmosfarm_coupon = array();
		}
		
		$cosmosfarm_coupon[$product_id] = intval($coupon_id);
		
		foreach($cosmosfarm_coupon as $key=>$coupon_id){
			$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
			if(!$coupon->is_available()){
				unset($cosmosfarm_coupon[$key]);
			}
		}
		
		update_user_meta($user_id, 'cosmosfarm_coupon', $cosmosfarm_coupon);
	}
	
	/**
	 * ??????????????? ????????? ?????? ????????? ????????????.
	 * @param int $user_id
	 * @param int $product_id
	 * @return int
	 */
	public function get_save_coupon_id($user_id, $product_id){
		$cosmosfarm_coupon = get_user_meta($user_id, 'cosmosfarm_coupon', true);
		if(!is_array($cosmosfarm_coupon)){
			$cosmosfarm_coupon = array();
		}
		
		foreach($cosmosfarm_coupon as $key=>$coupon_id){
			$coupon = new Cosmosfarm_Members_Subscription_Coupon($coupon_id);
			if(!$coupon->is_available()){
				unset($cosmosfarm_coupon[$key]);
			}
		}
		
		update_user_meta($user_id, 'cosmosfarm_coupon', $cosmosfarm_coupon);
		
		if(isset($cosmosfarm_coupon[$product_id])){
			return $cosmosfarm_coupon[$product_id];
		}
		return '';
	}
	
	/**
	 * ????????? ????????? ????????? ????????????.
	 * @param int $price
	 * @return int
	 */
	public function calculate($price){
		if($this->post_id){
			if($this->discount() == 'amount'){
				$price = $price - intval($this->discount_amount());
				$price = round($price);
			}
			else if($this->discount() == 'rate'){
				$price = $price - ($price * (intval($this->discount_amount()) / 100));
				$price = round($price);
			}
		}
		
		if($price < 0){
			$price = 0;
		}
		
		return $price;
	}
}