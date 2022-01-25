<?php
/**
 * Cosmosfarm_Members_Manage_Users
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Manage_Users {
	
	public function __construct(){
		add_filter('manage_users_columns', array($this, 'columns'), 9);
		add_filter('manage_users_sortable_columns', array($this, 'sortable_columns'), 9);
		add_filter('manage_users_custom_column', array($this, 'custom_column'), 9, 3);
		add_filter('user_search_columns', array($this, 'user_search_columns'));
	}
	
	public function columns($columns){
		$columns['display_name'] = str_replace(':', '', __('Display name publicly as'));
		$columns['user_registered'] = __('Register', 'cosmosfarm-members');
		return $columns;
	}
	
	public function sortable_columns($columns){
		$columns['display_name'] = 'display_name';
		$columns['user_registered'] = 'user_registered';
		return $columns;
	}
	
	public function custom_column($output, $column_name, $user_id){
		switch ($column_name) {
			case 'display_name' :
				$user = get_userdata($user_id);
				$output = $user->display_name;
				break;
			case 'user_registered' :
				$user = get_userdata($user_id);
				$output = get_date_from_gmt($user->user_registered);
				break;
		}
		return $output;
	}
	
	public function user_search_columns($search_columns){
		$search_columns[] = 'display_name';
		return $search_columns;
	}
}