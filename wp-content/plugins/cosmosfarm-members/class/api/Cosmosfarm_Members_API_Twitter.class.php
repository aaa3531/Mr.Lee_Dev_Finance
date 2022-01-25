<?php
/**
 * 코스모스팜 회원관리 트위터 연동
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Twitter {
	
	private $client_id;
	private $client_secret;
	private $redirect_url;
	private $token;
	private $twitteroauth;
	
	public function __construct(){
		require_once 'twitteroauth/twitteroauth.php';
		$option = get_cosmosfarm_members_option();
		$this->client_id = $option->twitter_client_id;
		$this->client_secret = $option->twitter_client_secret;
		$this->redirect_url = home_url('?action=cosmosfarm_members_social_login_callback_twitter');
	}
	
	public function get_request_url(){
		$twitteroauth = new TwitterOAuth($this->client_id, $this->client_secret);
		$request_token = $twitteroauth->getRequestToken($this->redirect_url);
		
		if(!isset($request_token['oauth_token']) || !$request_token['oauth_token']){
			print_r($request_token);
			exit;
		}
		
		$_SESSION['twitter']['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['twitter']['oauth_token_secret'] = $request_token['oauth_token_secret'];
		
		if($twitteroauth->http_code==200){
			return $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
		}
		else{
			return '';
		}
	}
	
	public function init_access_token(){
		$code = isset($_GET['oauth_verifier'])?$_GET['oauth_verifier']:'';
		if($code){
			$this->twitteroauth = new TwitterOAuth($this->client_id, $this->client_secret, $_SESSION['twitter']['oauth_token'], $_SESSION['twitter']['oauth_token_secret']);
			$this->token = $this->twitteroauth->getAccessToken($code);
		}
	}
	
	public function get_profile(){
		$profile = new stdClass();
		if($this->token){
			$user_info = $this->twitteroauth->get('account/verify_credentials', array('include_email' => 'true'));
			$profile->id = isset($user_info->id)?$user_info->id:'';
			$profile->user_login = '';
			$profile->email = isset($user_info->email)?$user_info->email:'';
			$profile->nickname = isset($user_info->name)?$user_info->name:'';
			$profile->picture = isset($user_info->profile_image_url_https)?$user_info->profile_image_url_https:'';
			$profile->url = isset($user_info->screen_name)?"http://twitter.com/{$user_info->screen_name}":'';
			$profile->raw_data = $user_info;
		}
		return $profile;
	}
}
?>