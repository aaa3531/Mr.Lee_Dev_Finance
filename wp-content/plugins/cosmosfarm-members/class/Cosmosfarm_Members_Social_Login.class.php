<?php
/**
 * Cosmosfarm_Members_Social_Login
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Social_Login {
	
	var $social_id;
	var $channel;
	var $picture;
	var $user_url;
	var $user_email;
	var $display_name;
	var $nickname;
	var $raw_data;
	
	public function user_register($user_id){
		add_user_meta($user_id, 'cosmosfarm_members_social_id', $this->social_id);
		add_user_meta($user_id, 'cosmosfarm_members_social_channel', $this->channel);
		add_user_meta($user_id, 'cosmosfarm_members_social_picture', $this->picture);
		wp_update_user(array('ID'=>$user_id, 'user_url'=>$this->user_url, 'user_email'=>$this->user_email, 'display_name'=>$this->display_name, 'nickname'=>$this->nickname));
		
		do_action('cosmosfarm_members_user_social_register', $user_id, $this);
	}
}
?>