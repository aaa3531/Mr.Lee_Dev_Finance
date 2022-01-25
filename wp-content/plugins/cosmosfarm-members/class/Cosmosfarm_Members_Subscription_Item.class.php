<?php
/**
 * Cosmosfarm_Members_Subscription_Item
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Item {
	
	var $post_type = 'cosmosfarm_item';
	var $post;
	var $post_id = 0;
	var $order;
	var $product;
	
	public function __construct($post_id=''){
		$this->post_id = 0;
		$this->order = new Cosmosfarm_Members_Subscription_Order();
		$this->product = new Cosmosfarm_Members_Subscription_Product();
		
		if($post_id){
			$this->init_with_id($post_id);
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
	
	public function init_with_id($post_id){
		$this->post_id = 0;
		$this->order = new Cosmosfarm_Members_Subscription_Order();
		$this->product = new Cosmosfarm_Members_Subscription_Product();
		
		$post_id = intval($post_id);
		if($post_id){
			$this->post = get_post($post_id);
			if($this->post && $this->post->ID){
				$this->post_id = $this->post->ID;
				
				// Cosmosfarm_Members_Subscription_Order 초기화
				$this->order->init_with_id($this->get_order_id());
				
				// Cosmosfarm_Members_Subscription_Product 초기화
				$this->product->init_with_id($this->get_product_id());
			}
		}
	}
	
	public function init_with_sequence_id($sequence_id){
		$this->post_id = 0;
		$this->order = new Cosmosfarm_Members_Subscription_Order();
		$this->product = new Cosmosfarm_Members_Subscription_Product();
		
		if($sequence_id){
			$args = array(
				'post_type'  => $this->post_type,
				'meta_query' => array(
					array(
						'key'     => 'sequence_id',
						'value'   => $sequence_id,
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
	
	public function create($user_id, $args){
		$user_id = intval($user_id);
		$title = isset($args['post_title']) ? $args['post_title'] : '';
		$content = isset($args['post_content']) ? $args['post_content'] : '';
		$excerpt = isset($args['post_excerpt']) ? $args['post_excerpt'] : '';
		$name = isset($args['post_name']) ? $args['post_name'] : '';
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
			
			wp_update_post($args);
		}
	}
	
	public function delete(){
		if($this->post_id){
			wp_delete_post($this->post_id);
		}
	}
	
	/**
	 * 정기결제 주문 ID값을 반환한다.
	 * @return int
	 */
	public function get_order_id(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'order_id', true);
			if($value){
				return intval($value);
			}
		}
		return 0;
	}
	
	/**
	 * 정기결제 주문 ID값을 입력한다.
	 * @param int $product_id
	 */
	public function set_order_id($order_id){
		if($this->post_id){
			$order_id= intval($order_id);
			update_post_meta($this->post_id, 'order_id', $order_id);
		}
	}
	
	/**
	 * 정기결제 상품 ID값을 반환한다.
	 * @return int
	 */
	public function get_product_id(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'product_id', true);
			if($value){
				return intval($value);
			}
		}
		return 0;
	}
	
	/**
	 * 정기결제 상품 ID값을 입력한다.
	 * @param int $product_id
	 */
	public function set_product_id($product_id){
		if($this->post_id){
			$product_id= intval($product_id);
			update_post_meta($this->post_id, 'product_id', $product_id);
		}
	}
	
	/**
	 * 정기결제의 sequence_id 값을 반환한다.
	 * @return string
	 */
	public function get_sequence_id(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'sequence_id', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 정기결제의 sequence_id 값을 입력한다.
	 * @param string $sequence_id
	 */
	public function set_sequence_id($sequence_id){
		if($this->post_id){
			update_post_meta($this->post_id, 'sequence_id', $sequence_id);
		}
	}
	
	/**
	 * 아이템이 정기구독 중인지 확인한다.
	 * @return string
	 */
	public function is_subscription_active(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_active', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 아이템의 정기구독 상태를 입력한다.
	 * @param string $subscription_active
	 */
	public function set_subscription_active($subscription_active){
		if($this->post_id){
			update_post_meta($this->post_id, 'subscription_active', $subscription_active);
		}
	}
	
	/**
	 * 첫 결제 무료이용을 확인한다.
	 * @return string
	 */
	public function is_first_free(){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'first_free', true);
			if($value){
				return $value;
			}
		}
		return '';
	}
	
	/**
	 * 첫 결제 무료이용을 입력한다.
	 * @param string $subscription_active
	 */
	public function set_first_free($first_free){
		if($this->post_id){
			update_post_meta($this->post_id, 'first_free', $first_free);
		}
	}
	
	/**
	 * 아이템의 정기구독 종료일을 반환한다.
	 * @param string $format
	 * @return string
	 */
	public function get_end_datetime($format='YmdHis'){
		if($this->post_id){
			$value = get_post_meta($this->post_id, 'subscription_end_date', true);
			if($value){
				return date($format, strtotime($value));
			}
		}
		return '';
	}
	
	/**
	 * 아이템의 정기구독 종료일을 입력한다. 
	 * @param string $end_date
	 */
	public function set_end_datetime($end_date){
		if($this->post_id){
			$end_date = date('YmdHis', strtotime($end_date));
			update_post_meta($this->post_id, 'subscription_end_date', $end_date);
		}
	}
	
	/**
	 * 저장된 값을 반환한다.
	 * @param string $meta_key
	 * @return string
	 */
	public function get_meta_value($meta_key){
		$value = '';
		if($this->post_id){
			$value = get_post_meta($this->post_id, $meta_key, true);
		}
		return $value;
	}
	
	/**
	 * 값을 저장한다.
	 * @param string $meta_key
	 * @param string $meta_value
	 */
	public function set_meta_value($meta_key, $meta_value){
		if($this->post_id){
			update_post_meta($this->post_id, $meta_key, $meta_value);
		}
	}
	
	/**
	 * 관리자인지 확인한다.
	 * @return boolean
	 */
	public function is_admin(){
		if($this->post_id){
			if(is_user_logged_in()){
				if(is_super_admin()){
					return true;
				}
				else if(get_current_user_id() == $this->post_author){
					return true;
				}
			}
		}
		return false;
	}
}