<?php
/**
 * Cosmosfarm_Members_Subscription_Item_Table_Columns
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Item_Table_Columns {
	
	public function __construct(){
		add_filter('manage_cosmosfarm_item_posts_columns', array($this, 'manage_columns'), 10, 1);
		add_action('manage_cosmosfarm_item_posts_custom_column' , array($this, 'manage_custom_column'), 10, 2);
	}
	
	public function manage_columns($columns){
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'subscription_item_user' => '사용자',
			'subscription_item_end_datetime' => '만료일',
			'date' => __('Date'),
		);
		return $columns;
	}
	
	public function manage_custom_column($column, $post_id){
		switch($column){
			case 'subscription_item_user':
				$subscription_item = new Cosmosfarm_Members_Subscription_Item();
				$subscription_item->init_with_id($post_id);
				$user = get_userdata($subscription_item->post_author);
				if($user && $user->ID){
					echo sprintf('<a href="%s">%s(%s)</a>', get_edit_user_link($user->ID), $user->display_name, $user->user_email);
				}
				else{
					echo '정보 없음';
				}
				break;
			case 'subscription_item_end_datetime':
				$subscription_item = new Cosmosfarm_Members_Subscription_Item();
				$subscription_item->init_with_id($post_id);
				$datetime = $subscription_item->get_end_datetime();
				if($datetime){
					echo date('Y-m-d H:i', strtotime($datetime));
				}
				else{
					echo '';
				}
				break;
		}
	}
}