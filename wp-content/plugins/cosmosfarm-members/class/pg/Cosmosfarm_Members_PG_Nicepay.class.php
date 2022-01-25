<?php
/**
 * Cosmosfarm_Members_PG_Nicepay
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_PG_Nicepay extends Cosmosfarm_Members_Abstract_PG {
	
	var $order_id;
	var $mid;
	var $merchant_key;
	
	public function __construct(){
		parent::__construct();
		
		$this->order_id = $this->get_order_id();
	}
	
	public function init($args=array()){
		if($this->is_test()){
			if($this->pg_type == 'billing'){
				$this->mid = 'nictest04m';
				$this->merchant_key = 'b+zhZ4yOZ7FsH8pm5lhDfHZEb79tIwnjsdA0FBXh86yLc6BJeFVrZFXhAoJ3gEWgrWwN+lJMV0W4hvDdbe4Sjw==';
			}
			else if($this->pg_type == 'general'){
				$this->mid = 'nictest00m';
				$this->merchant_key = '33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==';
			}
		}
		else{
			if($this->pg_type == 'billing'){
				$this->mid = get_option('cosmosfarm_members_builtin_pg_nicepay_billing_mid', '');
				$this->merchant_key = get_option('cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key', '');
			}
			else if($this->pg_type == 'general'){
				$this->mid = get_option('cosmosfarm_members_builtin_pg_nicepay_general_mid', '');
				$this->merchant_key = get_option('cosmosfarm_members_builtin_pg_nicepay_general_merchant_key', '');
			}
		}
	}
	
	public function get_name(){
		return 'nicepay';
	}
	
	public function subscribe($customer_uid, $price, $args=array()){
		$payment_method = isset($args['payment_method']) ? sanitize_text_field($args['payment_method']) : 'card';
		$merchant_uid = isset($args['merchant_uid']) ? sanitize_text_field($args['merchant_uid']) : $this->order_id;
		$name = isset($args['name']) ? sanitize_text_field($args['name']) : '';
		$buyer_name = isset($args['buyer_name']) ? sanitize_text_field($args['buyer_name']) : '';
		$buyer_email = isset($args['buyer_email']) ? sanitize_text_field($args['buyer_email']) : '';
		$buyer_tel = isset($args['buyer_tel']) ? sanitize_text_field($args['buyer_tel']) : '';
		
		$ediDate = date('YmdHis', current_time('timestamp'));
		$signData = bin2hex(hash('sha256', $this->mid . $ediDate . $merchant_uid . $price . $customer_uid . $this->merchant_key, true));
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=euc-kr');
		$args['body'] = array(
			'TID' => $this->get_new_tid(),
			'BID' => $customer_uid,
			'MID' => $this->mid,
			'Amt' => $price,
			'Moid' => $merchant_uid,
			'GoodsName' => iconv('UTF-8', 'EUC-KR//TRANSLIT', $name),
			'BuyerName' => iconv('UTF-8', 'EUC-KR//TRANSLIT', $buyer_name),
			'BuyerEmail' => $buyer_email,
			'BuyerTel' => str_replace('-', '', $buyer_tel),
			'CardInterest' => '0', // 무이자
			'CardQuota' => '00', // 할부
			'EdiDate' => $ediDate,
			'CharSet' => 'utf-8',
			'SignData' => $signData,
		);
		
		$response = wp_remote_request('https://webapi.nicepay.co.kr/webapi/billing/billing_approve.jsp', $args);
		
		// 반환 데이터 초기화
		$result = new stdClass();
		$result->status = 'ready';
		$result->imp_uid = '';
		$result->builtin_pg_id = '';
		$result->builtin_pg_tid = '';
		$result->merchant_uid = $merchant_uid;
		$result->receipt_url = '';
		$result->message = '';
		$result->error_message = '';
		
		if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
			$body = wp_remote_retrieve_body($response);
			
			if($body){
				$body = json_decode($body);
				
				if($body->ResultCode == '3001'){
					$result->status = 'paid';
					$result->builtin_pg_id = $body->AuthCode;
					$result->builtin_pg_tid = $body->TID;
					$result->receipt_url = "https://npg.nicepay.co.kr/issue/IssueLoaderMail.do?TID={$body->TID}&type=0";
					$result->message = '결제 완료되었습니다.';
					return $result;
				}
				else{
					$result->status = 'failed';
					$result->error_message = $body->ResultMsg;
					return $result;
				}
			}
		}
		
		$result->status = 'failed';
		$result->error_message = '결제에 실패했습니다.';
		return $result;
	}
	
	public function getPayment(){
		$payment = array();
		$payment['status'] = 'failed';
		$payment['error_message'] = '결제에 실패했습니다.';
		
		if(isset($_SESSION['payment'])){
			if(isset($_SESSION['payment']->skip_pay) && $_SESSION['payment']->skip_pay){
				$payment['status'] = 'paid';
				$payment['amount'] = 0;
				$payment['payment_method'] = '';
				$payment['custom_data'] = $_SESSION['payment']->custom_data;
				$payment['imp_uid'] = '';
				$payment['builtin_pg_id'] = '';
				$payment['builtin_pg_tid'] = '';
				$payment['merchant_uid'] = '';
				$payment['receipt_url'] = '';
				$payment['message'] = '결제 완료되었습니다.';
				$payment['error_message'] = '';
			}
			else if($_SESSION['payment']->ResultCode == '3001'){
				$payment['status'] = 'paid';
				$payment['amount'] = $_SESSION['payment']->Amt;
				$payment['payment_method'] = strtolower($_SESSION['payment']->PayMethod);
				$payment['custom_data'] = $_SESSION['payment']->custom_data;
				$payment['imp_uid'] = '';
				$payment['builtin_pg_id'] = $_SESSION['payment']->AuthCode;
				$payment['builtin_pg_tid'] = $_SESSION['payment']->TID;
				$payment['merchant_uid'] = $_SESSION['payment']->Moid;
				$payment['receipt_url'] = $payment['builtin_pg_tid'] ? "https://npg.nicepay.co.kr/issue/IssueLoaderMail.do?TID={$payment['builtin_pg_tid']}&type=0" : '';
				$payment['message'] = '결제 완료되었습니다.';
				$payment['error_message'] = '';
			}
			
			unset($_SESSION['payment']);
		}
		
		return $payment;
	}
	
	public function cancel($builtin_pg_tid, $args=array()){
		$cancel_msg = '환불';
		$cancel_amt = $this->order->balance();
		$partial_cancel_code = 0; // 전체취소
		$edi_date = date('YmdHis', current_time('timestamp'));
		$sign_data = bin2hex(hash('sha256', $this->mid . $cancel_amt . $edi_date . $this->merchant_key, true));
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=euc-kr');
		$args['body'] = array(
			'TID'   => $builtin_pg_tid,
			'MID' => $this->mid,
			'Moid' => $this->order->get_meta_value('merchant_uid'),
			'CancelAmt' => $cancel_amt,
			'CancelMsg' => iconv('UTF-8', 'EUC-KR//TRANSLIT', $cancel_msg),
			'PartialCancelCode' => $partial_cancel_code,
			'EdiDate' => $edi_date,
			'SignData' => $sign_data,
			'CharSet' => 'utf-8',
		);
		
		$response = wp_remote_request('https://webapi.nicepay.co.kr/webapi/cancel_process.jsp', $args);
		
		// 반환 데이터 초기화
		$result = new stdClass();
		$result->status = 'ready';
		$result->receipt_url = '';
		$result->message = '';
		$result->error_message = '';
		
		if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
			$body = wp_remote_retrieve_body($response);
			
			if($body){
				$body = json_decode($body);
				
				if($body->ResultCode == '2001'){
					$result->status = 'cancelled';
					$result->receipt_url = "https://npg.nicepay.co.kr/issue/IssueLoaderMail.do?TID={$body->TID}&type=0";
					$result->message = '결제가 취소되었습니다.';
					return $result;
				}
				else{
					$result->status = 'failed';
					$result->error_message = $body->ResultMsg;
					return $result;
				}
			}
		}
		
		$result->status = 'failed';
		$result->error_message = '결제를 취소하지 못했습니다.';
		return $result;
	}
	
	public function cancel_partial($builtin_pg_tid, $args=array()){
		$cancel_msg = '환불';
		$cancel_amt = isset($args['price']) ? sanitize_text_field($args['price']) : '';
		$partial_cancel_code = 1; // 부분취소
		$edi_date = date('YmdHis', current_time('timestamp'));
		$sign_data = bin2hex(hash('sha256', $this->mid . $cancel_amt . $edi_date . $this->merchant_key, true));
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=euc-kr');
		$args['body'] = array(
			'TID'   => $builtin_pg_tid,
			'MID' => $this->mid,
			'Moid' => $this->order->get_meta_value('merchant_uid'),
			'CancelAmt' => $cancel_amt,
			'CancelMsg' => iconv('UTF-8', 'EUC-KR//TRANSLIT', $cancel_msg),
			'PartialCancelCode' => $partial_cancel_code,
			'EdiDate' => $edi_date,
			'SignData' => $sign_data,
			'CharSet' => 'utf-8',
		);
		
		$response = wp_remote_request('https://webapi.nicepay.co.kr/webapi/cancel_process.jsp', $args);
		
		// 반환 데이터 초기화
		$result = new stdClass();
		$result->status = 'ready';
		$result->receipt_url = '';
		$result->message = '';
		$result->error_message = '';
		
		if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
			$body = wp_remote_retrieve_body($response);
			
			if($body){
				$body = json_decode($body);
				
				if(in_array($body->ResultCode, array('2001', '2211'))){
					$result->status = 'cancelled';
					$result->receipt_url = "https://npg.nicepay.co.kr/issue/IssueLoaderMail.do?TID={$body->TID}&type=0";
					$result->message = '부분 환불되었습니다.';
					return $result;
				}
				else{
					$result->status = 'failed';
					$result->error_message = $body->ResultMsg;
					return $result;
				}
			}
		}
		
		$result->status = 'failed';
		$result->error_message = '결제를 환불하지 못했습니다.';
		return $result;
	}
	
	public function get_init_values(){
		$values = array();
		
		$_POST = stripslashes_deep($_POST);
		
		$price = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';
		$edi_date = date('YmdHis', current_time('timestamp'));
		$hash_string = bin2hex(hash('sha256', $edi_date . $this->mid . $price . $this->merchant_key, true));
		
		$values = array();
		$values['PayMethod']		 = 'CARD';
		$values['GoodsName']		 = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
		$values['Amt']				 = $price;
		$values['MID'] 				 = $this->mid;
		$values['Moid']				 = $this->order_id;
		$values['BuyerName']		 = isset($_POST['buyer_name']) ? sanitize_text_field($_POST['buyer_name']) : '';
		$values['BuyerTel']			 = isset($_POST['buyer_tel']) ? sanitize_text_field($_POST['buyer_tel']) : '';
		$values['BuyerEmail']		 = isset($_POST['buyer_email']) ? sanitize_text_field($_POST['buyer_email']) : '';
		$values['GoodsCl']			 = '1';
		$values['TransType']		 = '0';
		$values['CharSet']			 = 'utf-8';
		$values['ReqReserved']		 = isset($_POST['custom_data']) ? base64_encode($_POST['custom_data']) : ''; // 한글 입력 불가
		$values['EdiDate']			 = $edi_date;
		$values['SignData']			 = $hash_string;
		
		if($this->is_mobile()){
			$values['ReturnURL']	 = $this->get_callback_url();
		}
		
		return $values;
	}
	
	public function open_dialog(){
		$pg = $this;
		
		unset($_SESSION['payment']);
		
		$price = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';
		
		if(!$price && $this->pg_type == 'general'){
			/*
			 * 일반결제에서 결제할 가격이 없을 경우 결제창을 띄우지 않는다.
			 */
			$result = new stdClass();
			$result->skip_pay = 'skip_pay';
			$result->Amt = 0;
			$custom_data = isset($_POST['custom_data']) ? $_POST['custom_data'] : '';
			parse_str($custom_data, $result->custom_data);
			$_SESSION['payment'] = $result;
			
			$success = 'true';
			$message = '';
			$error_msg = '';
			
			include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/nicepay/callback.php';
		}
		else{
			if($this->is_mobile()){
				include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/nicepay/dialog-mobile.php';
			}
			else{
				include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/nicepay/dialog.php';
			}
		}
	}
	
	public function open_close(){
		
	}
	
	public function callback(){
		$pg = $this;
		
		$auth_result_code = isset($_POST['AuthResultCode']) ? sanitize_text_field($_POST['AuthResultCode']) : '';
		$auth_result_msg = isset($_POST['AuthResultMsg']) ? sanitize_text_field($_POST['AuthResultMsg']) : '';
		
		$success = 'false';
		$message = '';
		$error_msg = $auth_result_msg;
		
		if($auth_result_code == '0000'){
			$success = 'true';
			
			$next_app_url = isset($_POST['NextAppURL']) ? sanitize_text_field($_POST['NextAppURL']) : '';
			$tid = isset($_POST['TxTid']) ? sanitize_text_field($_POST['TxTid']) : '';
			$auth_token = isset($_POST['AuthToken']) ? sanitize_text_field($_POST['AuthToken']) : '';
			$paymethod = isset($_POST['PayMethod']) ? sanitize_text_field($_POST['PayMethod']) : '';
			$mid = isset($_POST['MID']) ? sanitize_text_field($_POST['MID']) : '';
			$moid = isset($_POST['Moid']) ? sanitize_text_field($_POST['Moid']) : '';
			$amt = isset($_POST['Amt']) ? sanitize_text_field($_POST['Amt']) : '';
			$req_reserved = isset($_POST['ReqReserved']) ? sanitize_text_field($_POST['ReqReserved']) : '';
			$net_cancel_url = isset($_POST['NetCancelURL']) ? sanitize_text_field($_POST['NetCancelURL']) : '';
			
			$result = $this->get_result_data($next_app_url, array(
				'NextAppURL' => $next_app_url,
				'TxTid' => $tid,
				'AuthToken' => $auth_token,
				'PayMethod' => $paymethod,
				'MID' => $mid,
				'Moid' => $moid,
				'Amt' => $amt,
				'ReqReserved' => $req_reserved,
				'NetCancelURL' => $net_cancel_url
			));
			
			if($result->ResultCode == '3001'){
				$custom_data = $req_reserved ? base64_decode($req_reserved) : '';
				parse_str($custom_data, $result->custom_data);
				$_SESSION['payment'] = $result;
				
				$success = 'true';
				$message = $result->ResultMsg;
				$error_msg = '';
			}
			else{
				$success = 'false';
				$message = '';
				$error_msg = $result->ResultMsg;
			}
		}
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/nicepay/callback.php';
		exit;
	}
	
	public function is_test(){
		if(get_option('cosmosfarm_members_builtin_pg_nicepay_billing_mid', '') || get_option('cosmosfarm_members_builtin_pg_nicepay_general_mid', '')){
			return false;
		}
		return true;
	}
	
	public function save_settings(){
		$nicepay_billing_mid = isset($_POST['cosmosfarm_members_builtin_pg_nicepay_billing_mid']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_nicepay_billing_mid']) : '';
		$nicepay_billing_merchant_key = isset($_POST['cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key']) : '';
		
		$nicepay_general_mid = isset($_POST['cosmosfarm_members_builtin_pg_nicepay_general_mid']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_nicepay_general_mid']) : '';
		$nicepay_general_merchant_key = isset($_POST['cosmosfarm_members_builtin_pg_nicepay_general_merchant_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_nicepay_general_merchant_key']) : '';
		
		update_option('cosmosfarm_members_builtin_pg_nicepay_billing_mid', $nicepay_billing_mid);
		update_option('cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key', $nicepay_billing_merchant_key);
		
		update_option('cosmosfarm_members_builtin_pg_nicepay_general_mid', $nicepay_general_mid);
		update_option('cosmosfarm_members_builtin_pg_nicepay_general_merchant_key', $nicepay_general_merchant_key);
	}
	
	public function get_result_data($url, $args=array()){
		$next_app_url = isset($args['NextAppURL']) ? sanitize_text_field($args['NextAppURL']) : '';
		$tid = isset($args['TxTid']) ? sanitize_text_field($args['TxTid']) : '';
		$auth_token = isset($args['AuthToken']) ? sanitize_text_field($args['AuthToken']) : '';
		$paymethod = isset($args['PayMethod']) ? sanitize_text_field($args['PayMethod']) : '';
		$mid = isset($args['MID']) ? sanitize_text_field($args['MID']) : '';
		$moid = isset($args['Moid']) ? sanitize_text_field($args['Moid']) : '';
		$amt = isset($args['Amt']) ? sanitize_text_field($args['Amt']) : '';
		$req_reserved = isset($args['ReqReserved']) ? sanitize_text_field($args['ReqReserved']) : '';
		$net_cancel_url = isset($args['NetCancelURL']) ? sanitize_text_field($args['NetCancelURL']) : '';
		
		$edi_date = date('YmdHis', current_time('timestamp'));
		$merchant_key = $this->merchant_key;
		$sign_data = bin2hex(hash('sha256', $auth_token . $mid . $amt . $edi_date . $merchant_key, true));
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=euc-kr');
		$args['body'] = array(
			'TID' => $tid,
			'AuthToken' => $auth_token,
			'MID' => $mid,
			'Amt' => $amt,
			'EdiDate' => $edi_date,
			'SignData' => $sign_data,
			'CharSet' => 'utf-8',
		);
		
		$response = wp_remote_request($next_app_url, $args);
		
		$result = new stdClass();
		$result->ResultCode = '';
		$result->ResultMsg = '';
		
		if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
			$body = wp_remote_retrieve_body($response);
			
			if($body){
				$result = json_decode($body);
			}
		}
		
		return $result;
	}
	
	public function get_new_tid(){
		$tid = '';
		if($this->mid){
			$tid = sprintf('%s%s%s%s%s', $this->mid, '01', '16', date('ymdHis', current_time('timestamp')), mt_rand(1000, 9999));
		}
		return $tid;
	}
	
	public function registerCard($card_number, $expiry, $birth, $pwd_2digit){
		$card_number = sanitize_text_field($card_number);
		$expiry = sanitize_text_field($expiry);
		$birth = sanitize_text_field($birth);
		$pwd_2digit = sanitize_text_field($pwd_2digit);
		
		$expYear = substr($expiry, 2, 2);
		$expMonth = substr($expiry, 4, 2);
		
		$plainText = "CardNo=".$card_number."&ExpYear=".$expYear."&ExpMonth=".$expMonth."&IDNo=".$birth."&CardPw=".$pwd_2digit;
		$ediDate = date('YmdHis', current_time('timestamp'));
		$encData = bin2hex($this->aesEncryptSSL($plainText, substr($this->merchant_key, 0, 16)));
		$signData = bin2hex(hash('sha256', $this->mid . $ediDate . $this->order_id . $this->merchant_key, true));		
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=euc-kr');
		$args['body'] = array(
			'MID' => $this->mid,
			'Moid' => $this->order_id,
			'EdiDate' => $ediDate,
			'EncData' => $encData,
			'SignData' => $signData,
		);
		
		$response = wp_remote_request('https://webapi.nicepay.co.kr/webapi/billing/billing_regist.jsp', $args);
		
		$result = new stdClass();
		$result->error_message = '신용카드 정보 확인 중 문제가 발생했습니다. 문제가 계속될 경우 관리자에게 문의해주세요.';
		
		if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
			$body = wp_remote_retrieve_body($response);
			$body = iconv('EUC-KR', 'UTF-8//TRANSLIT', $body);
			
			if($body){
				$result = json_decode($body);
				
				if($result->ResultCode == 'F100' && $result->BID){
					$this->updateCustomerUID(get_current_user_id(), $result->BID);
					$result->error_message = '';
				}
				else{
					$result->error_message = $result->ResultMsg;
				}
			}
		}
		
		return $result;
	}
	
	// AES 암호화 (opnessl)
	public function aesEncryptSSL($data, $key){
		$iv = openssl_random_pseudo_bytes(16);
		$encdata = @openssl_encrypt($data, "AES-128-ECB", $key, true, $iv);
		return $encdata;
	}
	
	// AES 복호화 (openssl)
	public function aesDecryptSSL($data, $key){
		$iv = openssl_random_pseudo_bytes(16);
		$decdata = @openssl_decrypt($data, "AES-128-ECB", $key, OPENSSL_RAW_DATA, $iv);
		return $decdata;
	}
}