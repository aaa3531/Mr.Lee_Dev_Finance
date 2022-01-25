<?php
/**
 * 코스모스팜 회원관리 라인 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Line {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $authorize_url = 'https://access.line.me/dialog/oauth/weblogin';
	private $accesstoken_url = 'https://api.line.me/v2/oauth/accessToken';
	private $userinfo_url = 'https://api.line.me/v2/profile';
	private $token;
	
	public function __construct(){
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->line_client_id;
		$this->client_secret = $option->line_client_secret;
		$this->redirect_url = home_url();
	}
	
	private function get_state(){
		$microtime = microtime();
		$rand = mt_rand();
		return md5($microtime . $rand);
	}
	
	public function get_request_url(){
		$_SESSION['state'] = $this->get_state();
		$args['response_type'] = 'code';
		$args['client_id'] = $this->client_id;
		$args['redirect_uri'] = $this->redirect_url;
		$args['state'] = $_SESSION['state'];
		return add_query_arg($args, $this->authorize_url);
	}
	
	public function init_access_token(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		$state = isset($_GET['state'])?$_GET['state']:'';
		if($code && $state == $_SESSION['state']){
			$body['client_id'] = $this->client_id;
			$body['client_secret'] = $this->client_secret;
			$body['grant_type'] = 'authorization_code';
			$body['redirect_uri'] = $this->redirect_url;
			$body['code'] = $code;
			$response = wp_safe_remote_post($this->accesstoken_url, array('body'=>$body));
			$data = json_decode($response['body']);
			$this->token = array('Authorization'=>"{$data->token_type} {$data->access_token}");
		}
		
		$error = isset($_GET['error'])?$_GET['error']:'';
		if($error){
			$error_code = isset($_GET['errorCode'])?$_GET['errorCode']:'';
			$error_message = isset($_GET['errorMessage'])?$_GET['errorMessage']:'';
			wp_die("<p>error: {$error}</p><p>errorCode: {$error_code}</p><p>errorMessage: {$error_message}</p>");
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$response = wp_remote_get($this->userinfo_url, array('headers'=>$this->token));
			$data = json_decode($response['body']);
			$profile->id = isset($data->userId)?$data->userId:'';
			$profile->user_login = '';
			$profile->email = '';
			$profile->nickname = isset($data->displayName)?$data->displayName:'';
			$profile->picture = isset($data->pictureUrl)?$data->pictureUrl.'/large':'';
			$profile->url = '';
			$profile->raw_data = $data;
		}
		return $profile;
	}
}
?>