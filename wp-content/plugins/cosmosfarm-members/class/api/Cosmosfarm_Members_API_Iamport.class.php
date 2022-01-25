<?php
/**
 * Cosmosfarm_Members_API_Iamport
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_API_Iamport {
	
	private $access_token;
	private $expired_at;
	
	private $certification_url = 'https://api.iamport.kr/certifications';
	private $accesstoken_url = 'https://api.iamport.kr/users/getToken';
	private $subscribe_url = 'https://api.iamport.kr/subscribe/payments/again';
	private $register_card_url = 'https://api.iamport.kr/subscribe/customers';
	private $cancel_url = 'https://api.iamport.kr/payments/cancel';
	private $payment_url = 'https://api.iamport.kr/payments';
	
	public function getCertification($imp_uid){
		$result = new stdClass();
		$result->name = '';
		$result->birth = '';
		$result->gender = '';
		$result->error_message = '';
		
		if(COSMOSFARM_MEMBERS_CERTIFIED_PHONE){
			$result->carrier = '';
			$result->phone = '';
		}
		
		if($imp_uid && $this->getAccessToken()){
			
			$args = array();
			$args['method'] = 'GET';
			$args['timeout'] = '15';
			$args['headers'] = array('Authorization' => $this->getAccessToken());
			
			$response = wp_remote_request(sprintf('%s/%s', $this->certification_url, $imp_uid), $args);
			
			if(is_wp_error($response)){
				$result->error_message = $response->get_error_message();
			}
			else{
				$data = json_decode($response['body']);
				if($data->response){
					// 아임포트에서 보내주는 timestamp는 한국시간 기준으로 생성됐기 때문에 timezone을 변경해준다.
					date_default_timezone_set('Asia/Seoul');
					
					$result->name = $data->response->name;
					$result->birth = date('Y-m-d', $data->response->birth);
					$result->gender = $data->response->gender;
					
					// WordPress calculates offsets from UTC.
					date_default_timezone_set('UTC');
					
					if(COSMOSFARM_MEMBERS_CERTIFIED_PHONE){
						$result->carrier = isset($data->response->carrier) ? $data->response->carrier : '';
						$result->phone = isset($data->response->phone) ? $data->response->phone : '';
					}
				}
				else{
					$result->error_message = '결제 시도 중 상점 인증에 문제가 발생됐습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
				}
			}
		}
		else{
			$result->error_message = '결제 시도 중 상점 인증에 문제가 발생됐습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
		}
		
		return $result;
	}
	
	public function registerCard($card_number, $expiry, $birth, $pwd_2digit){
		$result = new stdClass();
		$result->error_message = '';
		
		if($this->getAccessToken()){
			$card_number = str_replace('-', '', $card_number);
			$expiry = str_replace('-', '', $expiry);
			$birth= str_replace('-', '', $birth);
			
			$args = array();
			$args['method'] = 'POST';
			$args['timeout'] = '15';
			$args['headers'] = array('Authorization' => $this->getAccessToken());
			$args['body'] = array(
				'card_number' => sprintf('%s-%s-%s-%s', substr($card_number, 0, 4), substr($card_number, 4, 4), substr($card_number, 8, 4), substr($card_number, 12, 4)),
				'expiry'      => sprintf('%s-%s', substr($expiry, 0, 4), substr($expiry, 4, 2)),
				'birth'       => $birth,
				'pwd_2digit'  => $pwd_2digit
			);
			
			$response = wp_remote_request(sprintf('%s/%s', $this->register_card_url, $this->getCustomerUID()), $args);
			
			if(is_wp_error($response)){
				$result->error_message = $response->get_error_message();
			}
			else{
				$data = json_decode($response['body']);
				if($data->response){
					$result = $data->response;
					$result->error_message = '';
				}
				else{
					$result->error_message = $data->message;
				}
			}
		}
		else{
			$result->error_message = '결제 시도 중 상점 인증에 문제가 발생됐습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
		}
		
		return $result;
	}
	
	public function subscribe($customer_uid, $amount, $buyer=array()){
		$result = new stdClass();
		$result->status = '';
		$result->error_message = '';
		
		if($this->getAccessToken()){
			$merchant_uid = $this->getMerchantUID();
			
			$args = array();
			$args['method'] = 'POST';
			$args['timeout'] = '15';
			$args['headers'] = array('Authorization' => $this->getAccessToken());
			$args['body'] = array(
				'customer_uid'   => $customer_uid,
				'merchant_uid'   => $merchant_uid,
				'amount'         => intval($amount),
				'name'           => (isset($buyer['name']) ? $buyer['name'] : ''),
				'buyer_name'     => (isset($buyer['buyer_name']) ? $buyer['buyer_name'] : ''),
				'buyer_email'    => (isset($buyer['buyer_email']) ? $buyer['buyer_email'] : ''),
				'buyer_tel'      => (isset($buyer['buyer_tel']) ? $buyer['buyer_tel'] : ''),
				'buyer_addr'     => (isset($buyer['buyer_addr']) ? $buyer['buyer_addr'] : ''),
				'buyer_postcode' => (isset($buyer['buyer_postcode']) ? $buyer['buyer_postcode'] : ''),
				'custom_data'    => (isset($buyer['custom_data']) ? $buyer['custom_data'] : ''),
			);
			
			$response = wp_remote_request(sprintf('%s', $this->subscribe_url), $args);
			
			if(is_wp_error($response)){
				$result->error_message = $response->get_error_message();
			}
			else{
				$data = json_decode($response['body']);
				if($data->response){
					if($data->response->status == 'paid'){
						$result = $data->response;
						$result->error_message = '';
						$result->merchant_uid = $merchant_uid;
					}
					else{
						$result->error_message = $data->response->fail_reason;
					}
				}
				else{
					$result->error_message = $data->message;
				}
			}
		}
		else{
			$result->error_message = '결제 시도 중 상점 인증에 문제가 발생됐습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
		}
		
		return $result;
	}
	
	public function cancel($imp_uid, $amount=0){
		$result = new stdClass();
		$result->status = '';
		$result->error_message = '';
		
		if($this->getAccessToken()){
			
			$args = array();
			$args['method'] = 'POST';
			$args['timeout'] = '15';
			$args['headers'] = array('Authorization' => $this->getAccessToken());
			$args['body'] = array(
				'imp_uid' => $imp_uid,
				'amount'  => intval($amount)
			);
			
			$response = wp_remote_request(sprintf('%s', $this->cancel_url), $args);
			
			if(is_wp_error($response)){
				$result->error_message = $response->get_error_message();
			}
			else{
				$data = json_decode($response['body']);
				if($data->response){
					if($data->response->status == 'cancelled'){
						$result = $data->response;
						$result->error_message = '';
					}
					else{
						$result->error_message = $data->response->fail_reason;
					}
				}
				else{
					$result->error_message = $data->message;
				}
			}
		}
		else{
			$result->error_message = '결제 시도 중 상점 인증에 문제가 발생됐습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
		}
		
		return $result;
	}
	
	public function getAccessToken(){
		if(time() < $this->expired_at && $this->access_token){
			return $this->access_token;
		}
		
		$option = get_cosmosfarm_members_option();
		
		$body['imp_key'] = $option->iamport_api_key;
		$body['imp_secret'] = $option->iamport_api_secret;
		$response = wp_safe_remote_post($this->accesstoken_url, array('body'=>$body));
		
		if(is_wp_error($response)){
			$this->access_token = '';
		}
		else{
			$data = json_decode($response['body']);
			if($data->response){
				$this->expired_at = time() + ($data->response->expired_at - $data->response->now);
				$this->access_token = $data->response->access_token;
			}
		}
		
		return $this->access_token;
	}
	
	public function getCustomerUID($user_id=''){
		$customer_uid = '';
		
		if(!$user_id){
			$user_id = get_current_user_id();
		}
		
		$user = new WP_User($user_id);
		
		if($user->ID){
			$customer_uid = get_user_meta($user->ID, 'cosmosfarm_iamport_customer_uid', true);
			if(!$customer_uid){
				$customer_uid = uniqid();
				update_user_meta($user->ID, 'cosmosfarm_iamport_customer_uid', $customer_uid);
			}
		}
		
		return $customer_uid;
	}
	
	public function updateCustomerUID($user_id=''){
		if(!$user_id){
			$user_id = get_current_user_id();
		}
		
		$user = new WP_User($user_id);
		
		if($user->ID){
			update_user_meta($user->ID, 'cosmosfarm_iamport_customer_uid', uniqid());
		}
	}
	
	public function getMerchantUID(){
		$uniqid = hexdec(uniqid());
		return sprintf('%s-%s-%s-%s', substr($uniqid, 0, 4), substr($uniqid, 4, 4), substr($uniqid, 8, 4), substr($uniqid, 12, 4));
	}
	
	public function getPayment($imp_uid){
		if($imp_uid && $this->getAccessToken()){
			
			$args = array();
			$args['method'] = 'GET';
			$args['timeout'] = '15';
			$args['headers'] = array('Authorization' => $this->getAccessToken());
			
			$response = wp_remote_request(sprintf('%s/%s', $this->payment_url, $imp_uid), $args);
			
			if(is_wp_error($response)){
				$result->error_message = $response->get_error_message();
			}
			else{
				$data = json_decode($response['body']);
				if($data->response){
					$result = $data->response;
					$result->error_message = $data->response->fail_reason;
				}
				else{
					$result->error_message = $data->message;
				}
			}
		}
		else{
			$result->error_message = '결제 시도 중 상점 인증에 문제가 발생됐습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
		}
		
		if(isset($result->custom_data)){
			$result->custom_data = json_decode($result->custom_data);
		}
		
		return $result;
	}
	
	public function save_settings(){
		
	}

	public function set_order($order){
		
	}
}