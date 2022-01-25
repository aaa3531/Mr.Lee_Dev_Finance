<?php
/**
 * 코스모스팜 회원관리 카카오 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Kakao {
	
	private $client_id;
	private $redirect_url;
	private $authorize_url = 'https://kauth.kakao.com/oauth/authorize';
	private $accesstoken_url = 'https://kauth.kakao.com/oauth/token';
	private $userinfo_url = 'https://kapi.kakao.com/v2/user/me?secure_resource=true';
	private $token;
	
	public function __construct(){
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->kakao_client_id;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_kakao');
	}
	
	private function get_state(){
		$microtime = microtime();
		$rand = mt_rand();
		return md5($microtime . $rand);
	}
	
	public function get_request_url(){
		$_SESSION['state'] = $this->get_state();
		return $this->authorize_url . '?response_type=code&client_id=' . $this->client_id . '&state=' . $_SESSION['state'] . '&redirect_uri=' . urlencode($this->redirect_url);
	}
	
	public function init_access_token(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if($code){
			$body['grant_type'] = 'authorization_code';
			$body['client_id'] = $this->client_id;
			$body['redirect_uri'] = $this->redirect_url;
			$body['code'] = $code;
			$response = wp_safe_remote_post($this->accesstoken_url, array('body'=>$body));
			$data = json_decode($response['body']);
			
			if(isset($data->error) && $data->error){
				wp_die("$data->error");
			}
			
			$this->token = array('Authorization'=>ucfirst($data->token_type)." {$data->access_token}");
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$response = wp_remote_get($this->userinfo_url, array('headers'=>$this->token));
			$data = json_decode($response['body']);
			$profile->id = isset($data->id)?$data->id:'';
			$profile->user_login = isset($data->kakao_account->email)?$data->kakao_account->email:'';
			$profile->email = $profile->user_login;
			$profile->nickname = isset($data->properties->nickname)?$data->properties->nickname:'';
			$profile->picture = isset($data->properties->profile_image)?$data->properties->profile_image:'';
			$profile->url = '';
			$profile->raw_data = $data;
		}
		return $profile;
	}
}