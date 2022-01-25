<?php
/**
 * 코스모스팜 회원관리 구글 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Google {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $token;
	private $client;
	
	public function __construct(){
		if(!class_exists('Google_Client')){
			wp_die('<a href="https://www.cosmosfarm.com/wpstore/product/cosmosfarm-members-google-login" target="_blank">코스모스팜 회원관리 구글 소셜 로그인</a> 플러그인이 설치 및 활성화되어 있지 않습니다.', '', array('back_link'=>true));
		}
		
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->google_client_id;
		$this->client_secret = $option->google_client_secret;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_google');
		
		$this->client = new Google_Client();
		$this->client->setClientId($this->client_id);
		$this->client->setClientSecret($this->client_secret);
		$this->client->setRedirectUri($this->redirect_url);
		$this->client->addScope('email');
		$this->client->addScope('profile');
	}
	
	public function get_request_url(){
		return $this->client->createAuthUrl();
	}
	
	public function init_access_token(){
		$code = isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '';
		if($code){
			$token = $this->client->fetchAccessTokenWithAuthCode($code);
			
			$this->token = isset($token['access_token']) ? $token['access_token'] : '';
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		
		if($this->token){
			$this->client->setAccessToken($this->token);
			
			$google_oauth = new Google_Service_Oauth2($this->client);
			$data = $google_oauth->userinfo->get();
			
			$profile->id = isset($data->id)?$data->id:'';
			$profile->user_login = isset($data->email)?$data->email:'';
			$profile->email = $profile->user_login;
			$profile->nickname = isset($data->name)?$data->name:'';
			
			if(isset($data->picture)){
				$url = parse_url($data->picture);
				$profile->picture = "{$url['scheme']}://{$url['host']}{$url['path']}?sz=500";
			}
			else{
				$profile->picture = '';
			}
			$profile->url = isset($data->link)?$data->link:'';
			$profile->raw_data = $data;
		}
		return $profile;
	}
}
?>