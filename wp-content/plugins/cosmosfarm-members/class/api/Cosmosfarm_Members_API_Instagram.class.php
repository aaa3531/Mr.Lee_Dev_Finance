<?php
/**
 * 코스모스팜 회원관리 인스타그램 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Instagram {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $authorize_url = 'https://api.instagram.com/oauth/authorize/';
	private $accesstoken_url = 'https://api.instagram.com/oauth/access_token';
	private $userinfo_url = 'https://api.instagram.com/v1/users/self/';
	private $token;
	
	public function __construct(){
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->instagram_client_id;
		$this->client_secret = $option->instagram_client_secret;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_instagram');
	}
	
	public function get_request_url(){
		$args['response_type'] = 'code';
		$args['client_id'] = $this->client_id;
		$args['redirect_uri'] = $this->redirect_url;
		$args['scope'] = 'basic';
		return add_query_arg($args, $this->authorize_url);
	}
	
	public function init_access_token(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if($code){
			$body['client_id'] = $this->client_id;
			$body['client_secret'] = $this->client_secret;
			$body['grant_type'] = 'authorization_code';
			$body['redirect_uri'] = $this->redirect_url;
			$body['code'] = $code;
			$response = wp_safe_remote_post($this->accesstoken_url, array('body'=>$body));
			$data = json_decode($response['body']);
			
			if(isset($data->error_message) && $data->error_message){
				wp_die($data->error_message);
			}
			
			$this->token = $data->access_token;
		}
		
		$error = isset($_GET['error'])?$_GET['error']:'';
		if($error){
			$error_reason = isset($_GET['error_reason'])?$_GET['error_reason']:'';
			$error_description = isset($_GET['error_description'])?$_GET['error_description']:'';
			wp_die("<p>error: {$error}</p><p>error_reason: {$error_reason}</p><p>error_description: {$error_description}</p>");
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$args['access_token'] = $this->token;
			$response = wp_remote_get(add_query_arg($args, $this->userinfo_url));
			$data = json_decode($response['body']);
			$profile->id = isset($data->data->id)?$data->data->id:'';
			$profile->user_login = '';
			$profile->email = '';
			$profile->nickname = isset($data->data->full_name)?$data->data->full_name:'';
			$profile->picture = isset($data->data->profile_picture)?$data->data->profile_picture:'';
			$profile->url = isset($data->data->username)?"https://www.instagram.com/{$data->data->username}/":'';
			$profile->raw_data = $data->data;
		}
		return $profile;
	}
}
?>