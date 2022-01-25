<?php
/**
 * 코스모스팜 회원관리 네이버 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Naver {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $authorize_url = 'https://nid.naver.com/oauth2.0/authorize';
	private $accesstoken_url = 'https://nid.naver.com/oauth2.0/token';
	private $userinfo_url = 'https://openapi.naver.com/v1/nid/me';
	private $token;
	
	public function __construct(){
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->naver_client_id;
		$this->client_secret = $option->naver_client_secret;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_naver');
	}
	
	private function get_state(){
		$microtime = microtime();
		$rand = mt_rand();
		return md5($microtime . $rand);
	}
	
	public function get_request_url(){
		$_SESSION['state'] = $this->get_state();
		return $this->authorize_url . '?response_type=code&client_id=' . $this->client_id . '&state=' . $_SESSION['state'] . '&redirect_url=' . urlencode($this->redirect_url);
	}
	
	public function init_access_token(){
		$code = isset($_GET['code'])?$_GET['code']:'';
		if($code){
			$args['grant_type'] = 'authorization_code';
			$args['client_id'] = $this->client_id;
			$args['client_secret'] = $this->client_secret;
			$args['state'] = $_SESSION['state'];
			$args['code'] = $code;
			$response = wp_remote_get(add_query_arg($args, $this->accesstoken_url));
			$data = json_decode($response['body']);
			$this->token = array('Authorization'=>"{$data->token_type} {$data->access_token}");
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$response = wp_remote_get($this->userinfo_url, array('headers'=>$this->token));
			$data = json_decode($response['body']);
			$profile->id = $data->response->id;
			$profile->naver_enc_id = isset($data->response->enc_id)?$data->response->enc_id:'';
			$profile->user_login = isset($data->response->email)?$data->response->email:'';
			$profile->email = isset($data->response->email)?$data->response->email:'';
			$profile->nickname = isset($data->response->nickname)?$data->response->nickname:'';
			$profile->picture = isset($data->response->profile_image)?$data->response->profile_image:'';
			
			$profile_email = $profile->email ? explode('@', $profile->email) : '';
			
			$profile->url = $profile_email ? 'https://blog.naver.com/' . reset($profile_email) : '';
			$profile->raw_data = $data->response;
		}
		return $profile;
	}
}
?>