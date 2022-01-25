<?php
/**
 * 코스모스팜 회원관리 페이스북 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Facebook {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $authorize_url = 'https://www.facebook.com/dialog/oauth';
	private $accesstoken_url = 'https://graph.facebook.com/oauth/access_token';
	private $userinfo_url = 'https://graph.facebook.com/me';
	private $token;
	
	public function __construct(){
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->facebook_client_id;
		$this->client_secret = $option->facebook_client_secret;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_facebook');
	}
	
	public function get_request_url(){
		if(wp_is_mobile()){
			return $this->authorize_url . '?client_id=' . $this->client_id . '&scope=email,public_profile&display=popup&redirect_uri=' . urlencode($this->redirect_url);
		}
		return $this->authorize_url . '?client_id=' . $this->client_id . '&scope=email,public_profile&redirect_uri=' . urlencode($this->redirect_url);
	}
	
	public function init_access_token(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if($code){
			$args['client_id'] = $this->client_id;
			$args['client_secret'] = $this->client_secret;
			$args['redirect_uri'] = $this->redirect_url;
			$args['code'] = $code;
			$response = wp_remote_get(add_query_arg($args, $this->accesstoken_url), array('timeout'=>15));
			
			if(is_wp_error($response)){
				wp_die($response->get_error_message(), '', array('back_link'=>true));
			}
			
			$data = json_decode($response['body']);
			if(isset($data->error) && $data->error){
				wp_die($data->error->message, '', array('back_link'=>true));
			}
			else{
				$this->token = $data->access_token;
			}
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$args['fields'] = 'id,name,email';
			$args['access_token'] = $this->token;
			$response = wp_remote_get(add_query_arg($args, $this->userinfo_url), array('timeout'=>15));
			$data = json_decode($response['body']);
			$profile->id = isset($data->id)?$data->id:'';
			$profile->user_login = isset($data->email)?$data->email:'';
			$profile->email = $profile->user_login;
			$profile->nickname = isset($data->name)?$data->name:'';
			$profile->picture = isset($data->id)?"https://graph.facebook.com/{$data->id}/picture?type=large":'';
			$profile->url = isset($data->id)?"http://www.facebook.com/{$data->id}":'';
			$profile->raw_data = $data;
		}
		return $profile;
	}
}