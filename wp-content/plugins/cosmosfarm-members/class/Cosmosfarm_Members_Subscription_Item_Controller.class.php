<?php
/**
 * Cosmosfarm_Members_Subscription_Item_Controller
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Item_Controller {
	
	public function __construct(){
		add_action('cosmosfarm_members_subscription_request_pay', array($this, 'subscription_request_pay'), 5, 3);
		add_action('cosmosfarm_members_subscription_again_success', array($this, 'subscription_again_success'), 5, 2);
		add_action('cosmosfarm_members_subscription_expiry', array($this, 'subscription_expiry'), 5, 2);
		add_action('cosmosfarm_members_subscription_again_failure', array($this, 'subscription_again_failure'), 5, 2);
	}
	
	public function subscription_request_pay($order, $product, $custom_data){
		if(cosmosfarm_members_is_advanced() && $product->is_subscription_item_active() && $order->ID()){
			$extend_item_id = isset($custom_data['extend_item_id']) ? intval($custom_data['extend_item_id']) : '';
			
			if($extend_item_id){
				$item = new Cosmosfarm_Members_Subscription_Item();
				$item->init_with_id($extend_item_id);
				$item->update(array('post_title'=>$product->title()));
				
				/*
				 * 아이템의 종료일을 가져와서
				 * 아이템의 종료일을 기준으로 기간을 연장한다.
				 */
				$next_datetime = $product->next_subscription_datetime($item->get_end_datetime());
				$order->set_end_datetime($next_datetime);
				
				// 이전 결제의 정기결제 상태를 만료됨으로 변경한다.
				$args = array(
					'post_type'      => 'cosmosfarm_order',
					'order'          => 'DESC',
					'orderby'        => 'ID',
					'posts_per_page' => '-1',
					'meta_query'     => array(
						array(
							'key'     => 'sequence_id',
							'value'   => $item->get_sequence_id(),
							'compare' => '='
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
					$old_order = new Cosmosfarm_Members_Subscription_Order($post->ID);
					$old_order->set_subscription_next('expiry');
				}
				
				$item->set_order_id($order->ID());
				$item->set_product_id($product->ID());
				$item->set_sequence_id($order->sequence_id());
				$item->set_end_datetime($order->end_datetime());
				$item->set_subscription_active('1'); // 이용중
			}
			else{
				$item = new Cosmosfarm_Members_Subscription_Item();
				$item->create($order->user_id(), array('post_title'=>$product->title(), 'post_name'=>uniqid()));
				
				$item->set_order_id($order->ID());
				$item->set_product_id($product->ID());
				$item->set_sequence_id($order->sequence_id());
				$item->set_end_datetime($order->end_datetime());
				$item->set_subscription_active('1'); // 이용중
				
				// 첫 결제 무료 이용을 확인한다.
				if($product->is_subscription_first_free()){
					$item->set_first_free('1');
				}
			}
		}
	}
	
	public function subscription_again_success($order, $product){
		if(cosmosfarm_members_is_advanced() && $product->is_subscription_item_active() && $order->ID()){
			$item = new Cosmosfarm_Members_Subscription_Item();
			$item->init_with_sequence_id($order->sequence_id());
			$item->update(array('post_title'=>$product->title()));
			
			$item->set_order_id($order->ID());
			$item->set_end_datetime($order->end_datetime());
			$item->set_subscription_active('1'); // 이용중
			$item->set_first_free(''); // 첫 결제 무료 이용 만료 (설정되어있다면)
		}
	}
	
	public function subscription_expiry($order, $product){
		if(cosmosfarm_members_is_advanced() && $product->is_subscription_item_active() && $order->ID()){
			$item = new Cosmosfarm_Members_Subscription_Item();
			$item->init_with_sequence_id($order->sequence_id());
			$item->set_subscription_active(''); // 종료됨
			$item->set_first_free(''); // 첫 결제 무료 이용 만료 (설정되어있다면)
		}
	}
	
	public function subscription_again_failure($order, $product){
		if(cosmosfarm_members_is_advanced() && $product->is_subscription_item_active() && $order->ID()){
			$item = new Cosmosfarm_Members_Subscription_Item();
			$item->init_with_sequence_id($order->sequence_id());
			$item->set_subscription_active(''); // 종료됨
			$item->set_first_free(''); // 첫 결제 무료 이용 만료 (설정되어있다면)
		}
	}
}