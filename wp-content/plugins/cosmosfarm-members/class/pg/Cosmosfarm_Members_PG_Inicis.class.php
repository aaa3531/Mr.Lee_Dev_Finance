<?php
/**
 * Cosmosfarm_Members_PG_Inicis
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_PG_Inicis extends Cosmosfarm_Members_Abstract_PG {
	
	var $util;
	var $timestamp;
	var $order_id;
	var $mid;
	var $sign_key;
	var $merchant_key;
	var $api_key;
	
	public function __construct(){
		parent::__construct();
		
		require_once COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/INIStdPayUtil.php';
		
		$this->util = new INIStdPayUtil();
		$this->timestamp = $this->util->getTimestamp();
		$this->order_id = $this->get_order_id();
	}
	
	public function init($args=array()){
		if($this->is_test()){
			if($this->pg_type == 'billing'){
				$this->mid = 'INIBillTst';
			}
			else if($this->pg_type == 'general'){
				$this->mid = 'INIpayTest';
			}
			$this->sign_key = 'SU5JTElURV9UUklQTEVERVNfS0VZU1RS';
			$this->merchant_key = 'b09LVzhuTGZVaEY1WmJoQnZzdXpRdz09';
			$this->api_key = '';
		}
		else{
			if($this->pg_type == 'billing'){
				$this->mid = get_option('cosmosfarm_members_builtin_pg_inicis_mid', '');
				$this->sign_key = get_option('cosmosfarm_members_builtin_pg_inicis_sign_key', '');
				$this->merchant_key = get_option('cosmosfarm_members_builtin_pg_inicis_merchant_key', '');
				$this->api_key = get_option('cosmosfarm_members_builtin_pg_inicis_api_key', '');
			}
			else if($this->pg_type == 'general'){
				$this->mid = get_option('cosmosfarm_members_builtin_pg_inicis_general_mid', '');
				$this->sign_key = get_option('cosmosfarm_members_builtin_pg_inicis_general_sign_key', '');
				$this->merchant_key = get_option('cosmosfarm_members_builtin_pg_inicis_general_merchant_key', '');
				$this->api_key = get_option('cosmosfarm_members_builtin_pg_inicis_general_api_key', '');
			}
		}
		
		if($this->mid == 'INIpayTest'){
			$this->api_key = 'ItEQKi3rY7uvDS8l';
		}
		else if($this->mid == 'INIBillTst'){
			$this->api_key = 'rKnPljRn5m6J9Mzz';
		}
		else if($this->mid == 'iniescrow0'){
			$this->api_key = 'yERbIlJ3NhTeObsA';
		}
	}
	
	public function get_name(){
		return 'inicis';
	}
	
	public function subscribe($customer_uid, $price, $args=array()){
		$payment_method = isset($args['payment_method']) ? sanitize_text_field($args['payment_method']) : 'card';
		$merchant_uid = isset($args['merchant_uid']) ? sanitize_text_field($args['merchant_uid']) : $this->order_id;
		$name = isset($args['name']) ? sanitize_text_field($args['name']) : '';
		$buyer_name = isset($args['buyer_name']) ? sanitize_text_field($args['buyer_name']) : '';
		$buyer_email = isset($args['buyer_email']) ? sanitize_text_field($args['buyer_email']) : '';
		$buyer_tel = isset($args['buyer_tel']) ? sanitize_text_field($args['buyer_tel']) : '';
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=utf-8');
		$args['body'] = array(
			'type'   => 'Billing',
			'paymethod' => ucfirst($payment_method),
			'timestamp' => date('YmdHis', current_time('timestamp')),
			'clientIp' => $_SERVER['SERVER_ADDR'],
			'mid' => $this->mid,
			'url' => site_url(),
			'moid' => $merchant_uid,
			'goodName' => $name,
			'buyerName' => $buyer_name,
			'buyerEmail' => $buyer_email,
			'buyerTel' => $buyer_tel,
			'price' => $price,
			'regNo' => '',
			'cardPw' => '',
			'currency' => 'WON',
			'billKey' => $customer_uid,
			'authentification' => '00',
			'cardQuota' => '',
			'quotaInterest' => '',
		);
		
		$hashData = $this->api_key;
		foreach(array('type', 'paymethod', 'timestamp', 'clientIp', 'mid', 'moid', 'price', 'billKey') as $name){
			$hashData .= $args['body'][$name];
		}
		$args['body']['hashData'] = hash('sha512', $hashData);
		
		$response = wp_remote_request('https://iniapi.inicis.com/api/v1/billing', $args);
		
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
				
				if($body->resultCode == '00'){
					$result->status = 'paid';
					$result->builtin_pg_id = $body->payAuthCode;
					$result->builtin_pg_tid = $body->tid;
					$result->receipt_url = "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid={$body->tid}&noMethod=1";
					$result->message = '결제 완료되었습니다.';
					return $result;
				}
				else{
					$result->status = 'failed';
					$result->error_message = $body->resultMsg;
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
			if($this->is_mobile()){
				if(isset($_SESSION['payment']['skip_pay']) && $_SESSION['payment']['skip_pay']){
					$payment['status'] = 'paid';
					$payment['amount'] = 0;
					$payment['payment_method'] = '';
					$payment['custom_data'] = $_SESSION['payment']['P_NOTI'];
					$payment['imp_uid'] = '';
					$payment['builtin_pg_id'] = '';
					$payment['builtin_pg_tid'] = '';
					$payment['merchant_uid'] = '';
					$payment['receipt_url'] = '';
					$payment['message'] = '결제 완료되었습니다.';
					$payment['error_message'] = '';
				}
				else if($_SESSION['payment']['P_STATUS'] == '00'){
					$payment['status'] = 'paid';
					$payment['amount'] = $_SESSION['payment']['P_AMT'];
					$payment['payment_method'] = strtolower($_SESSION['payment']['P_TYPE']);
					$payment['custom_data'] = $_SESSION['payment']['P_NOTI'];
					$payment['imp_uid'] = '';
					$payment['builtin_pg_id'] = '';
					$payment['builtin_pg_tid'] = $_SESSION['payment']['P_TID'];
					$payment['merchant_uid'] = $_SESSION['payment']['P_OID'];
					$payment['receipt_url'] = $payment['builtin_pg_tid'] ? "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid={$payment['builtin_pg_tid']}&noMethod=1" : '';
					$payment['message'] = '결제 완료되었습니다.';
					$payment['error_message'] = '';
				}
			}
			else{
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
				else if($_SESSION['payment']->resultCode == '0000'){
					$payment['status'] = 'paid';
					$payment['amount'] = $_SESSION['payment']->TotPrice;
					$payment['payment_method'] = strtolower($_SESSION['payment']->payMethod);
					$payment['custom_data'] = $_SESSION['payment']->custom_data;
					$payment['imp_uid'] = '';
					$payment['builtin_pg_id'] = '';
					$payment['builtin_pg_tid'] = $_SESSION['payment']->tid;
					$payment['merchant_uid'] = $_SESSION['payment']->MOID;
					$payment['receipt_url'] = $payment['builtin_pg_tid'] ? "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid={$payment['builtin_pg_tid']}&noMethod=1" : '';
					$payment['message'] = '결제 완료되었습니다.';
					$payment['error_message'] = '';
				}
			}
			
			unset($_SESSION['payment']);
		}
		
		return $payment;
	}
	
	public function cancel($builtin_pg_tid, $args=array()){
		$payment_method = $this->order->payment_method() ? sanitize_text_field($this->order->payment_method()) : 'card';
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=utf-8');
		$args['body'] = array(
			'type' => 'Refund',
			'paymethod' => ucfirst($payment_method),
			'timestamp' => date('YmdHis', current_time('timestamp')),
			'clientIp' => $_SERVER['SERVER_ADDR'],
			'mid' => $this->mid,
			'tid' => $builtin_pg_tid,
			'msg' => '환불',
		);
		
		$hashData = $this->api_key;
		foreach(array('type', 'paymethod', 'timestamp', 'clientIp', 'mid', 'tid') as $name){
			$hashData .= $args['body'][$name];
		}
		$args['body']['hashData'] = hash('sha512', $hashData);
		
		$response = wp_remote_request('https://iniapi.inicis.com/api/v1/refund', $args);
		
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
				
				if($body->resultCode == '00'){
					$result->status = 'cancelled';
					$result->receipt_url = "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid={$builtin_pg_tid}&noMethod=1";
					$result->message = '결제가 취소되었습니다.';
					return $result;
				}
				else{
					$result->status = 'failed';
					$result->error_message = $body->resultMsg;
					return $result;
				}
			}
		}
		
		$result->status = 'failed';
		$result->error_message = '결제를 취소하지 못했습니다.';
		return $result;
	}
	
	public function cancel_partial($builtin_pg_tid, $args=array()){
		$payment_method = $this->order->payment_method() ? sanitize_text_field($this->order->payment_method()) : 'card';
		
		$price = isset($args['price']) ? sanitize_text_field($args['price']) : '';
		$balance = isset($args['balance']) ? sanitize_text_field($args['balance']) : '';
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=utf-8');
		$args['body'] = array(
			'type' => 'PartialRefund',
			'paymethod' => ucfirst($payment_method),
			'timestamp' => date('YmdHis', current_time('timestamp')),
			'clientIp' => $_SERVER['SERVER_ADDR'],
			'mid' => $this->mid,
			'tid' => $builtin_pg_tid,
			'msg' => '환불',
			'price' => $price,
			'confirmPrice' => $balance,
			'currency' => 'WON',
		);
		
		$hashData = $this->api_key;
		foreach(array('type', 'paymethod', 'timestamp', 'clientIp', 'mid', 'tid', 'price', 'confirmPrice') as $name){
			$hashData .= $args['body'][$name];
		}
		$args['body']['hashData'] = hash('sha512', $hashData);
		
		$response = wp_remote_request('https://iniapi.inicis.com/api/v1/refund', $args);
		
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
				
				if($body->resultCode == '00'){
					$result->status = 'cancelled';
					$result->receipt_url = "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid={$builtin_pg_tid}&noMethod=1";
					$result->message = '부분 환불되었습니다.';
					return $result;
				}
				else{
					$result->status = 'failed';
					$result->error_message = $body->resultMsg;
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
		
		if($this->is_mobile() && $this->pg_type == 'billing'){
			$values['mid'] = $this->mid;
			$values['buyername'] = isset($_POST['buyer_name']) && $_POST['buyer_name'] ? sanitize_text_field($_POST['buyer_name']) : '홍길동';
			$values['goodname'] = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
			$values['price'] = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';
			$values['orderid'] = isset($_POST['merchant_uid']) ? sanitize_text_field($_POST['merchant_uid']) : '';
			$values['returnurl'] = $this->get_callback_url();
			$values['merchantreserved'] = 'below1000=Y';
			$values['timestamp'] = $this->timestamp;
			
			$period_from = isset($_POST['period_from']) ? sanitize_text_field($_POST['period_from']) : '';
			$period_to = isset($_POST['period_to']) ? sanitize_text_field($_POST['period_to']) : '';
			if($period_from && $period_to){
				$values['period'] = sprintf('%s%s', $period_from, $period_to);
			}
			
			$values['period_custom'] = '';
			$values['carduse'] = '';
			$values['p_noti'] = isset($_POST['custom_data']) ? base64_encode($_POST['custom_data']) : ''; // 한글 입력 불가
			$values['hashdata'] = $this->get_mobile_hashdata($values['orderid']);
		}
		else if($this->is_mobile()){
			$values['P_INI_PAYMENT'] = 'CARD';
			$values['P_MID'] = $this->mid;
			$values['P_OID'] = isset($_POST['merchant_uid']) ? sanitize_text_field($_POST['merchant_uid']) : '';
			
			$values['P_GOODS'] = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
			$values['P_AMT'] = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';
			$values['P_UNAME'] = isset($_POST['buyer_name']) && $_POST['buyer_name'] ? sanitize_text_field($_POST['buyer_name']) : '홍길동';
			$values['P_MOBILE'] = isset($_POST['buyer_tel']) && $_POST['buyer_tel'] ? sanitize_text_field($_POST['buyer_tel']) : '010-1234-5678';
			$values['P_EMAIL'] = isset($_POST['buyer_email']) && $_POST['buyer_email'] ? sanitize_text_field($_POST['buyer_email']) : '';
			
			$values['P_QUOTABASE'] = '02:03:04:05:06:07:08:09:10:11:12';
			$values['P_RESERVED'] = 'twotrs_isp=Y&block_isp=Y&twotrs_isp_noti=N&apprun_check=Y&extension_enable=Y';
			
			$values['P_NEXT_URL'] = $this->get_callback_url();
			$values['P_NOTI'] = isset($_POST['custom_data']) ? base64_encode($_POST['custom_data']) : ''; // 한글 입력 불가
			$values['P_CHARSET'] = 'utf8';
		}
		else{
			$values['version'] = '1.0';
			$values['mid'] = $this->mid;
			$values['oid'] = isset($_POST['merchant_uid']) ? sanitize_text_field($_POST['merchant_uid']) : '';
			
			$values['goodname'] = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
			$values['price'] = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';
			$values['currency'] = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : 'WON';
			$values['buyername'] = isset($_POST['buyer_name']) && $_POST['buyer_name'] ? sanitize_text_field($_POST['buyer_name']) : '홍길동';
			$values['buyertel'] = isset($_POST['buyer_tel']) && $_POST['buyer_tel'] ? sanitize_text_field($_POST['buyer_tel']) : '010-1234-5678';
			$values['buyeremail'] = isset($_POST['buyer_email']) && $_POST['buyer_email'] ? sanitize_text_field($_POST['buyer_email']) : '';
			
			$values['timestamp'] = $this->timestamp;
			$values['signature'] = $this->get_signature($values['price'], $values['oid']);
			$values['returnUrl'] = $this->get_callback_url();
			$values['mKey'] = $this->get_m_key();
			
			$values['gopaymethod'] = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : 'Card';
			
			$period_from = isset($_POST['period_from']) ? sanitize_text_field($_POST['period_from']) : '';
			$period_to = isset($_POST['period_to']) ? sanitize_text_field($_POST['period_to']) : '';
			if($period_from && $period_to){
				$values['offerPeriod'] = sprintf('%s-%s', $period_from, $period_to);
			}
			
			if($this->pg_type == 'billing'){
				$values['acceptmethod'] = 'popreturn:BILLAUTH(card):below1000'; //popreturn:HPP(2)
			}
			else if($this->pg_type == 'general'){
				$values['quotabase'] = '2:3:4:5:6:7:8:9:10:11:12';
				$values['acceptmethod'] = 'popreturn:HPP(2):Card(0):no_receipt:cardpoint';
			}
			
			$values['billPrint_msg'] = '';
			$values['languageView'] = 'ko';
			$values['charset'] = 'UTF-8';
			$values['payViewType'] = 'overlay';
			$values['closeUrl'] = $this->get_close_url();
			$values['popupUrl'] = '';
			
			$values['merchantData'] = isset($_POST['custom_data']) ? base64_encode($_POST['custom_data']) : ''; // 한글 입력 불가
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
			if($this->is_mobile()){
				$result = array();
				$result['skip_pay'] = 'skip_pay';
				$result['P_AMT'] = 0;
				$custom_data = isset($_POST['custom_data']) ? $_POST['custom_data'] : '';
				parse_str($custom_data, $result['P_NOTI']);
				$_SESSION['payment'] = $result;
			}
			else{
				$result = new stdClass();
				$result->skip_pay = 'skip_pay';
				$result->TotPrice = 0;
				$custom_data = isset($_POST['custom_data']) ? $_POST['custom_data'] : '';
				parse_str($custom_data, $result->custom_data);
				$_SESSION['payment'] = $result;
			}
			
			$success = 'true';
			$message = '';
			$error_msg = '';
			
			include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/callback.php';
		}
		else if($this->is_mobile() && $this->pg_type == 'billing'){
			include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/dialog-mobile-billing.php';
		}
		else if($this->is_mobile()){
			include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/dialog-mobile.php';
		}
		else{
			include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/dialog.php';
		}
	}
	
	public function open_close(){
		$pg = $this;
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/close.php';
	}
	
	public function callback(){
		$pg = $this;
		
		if($this->is_mobile() && $this->pg_type == 'billing'){
			$resultcode = isset($_POST['resultcode']) ? sanitize_text_field($_POST['resultcode']) : '';
			$resultmsg = isset($_POST['resultmsg']) ? sanitize_text_field($_POST['resultmsg']) : '';
			$billkey = isset($_POST['billkey']) ? sanitize_text_field($_POST['billkey']) : '';
			
			if($resultcode == '00'){
				$this->updateCustomerUID(get_current_user_id(), $billkey);
				
				$success = 'true';
				$message = $resultmsg;
				$error_msg = '';
			}
			else{
				$success = 'false';
				$message = '';
				$error_msg = $resultmsg;
			}
		}
		else if($this->is_mobile()){
			$P_STATUS = isset($_POST['P_STATUS']) ? sanitize_text_field($_POST['P_STATUS']) : '';
			$P_RMESG1 = isset($_POST['P_RMESG1']) ? sanitize_text_field($_POST['P_RMESG1']) : '';
			$P_TID = isset($_POST['P_TID']) ? sanitize_text_field($_POST['P_TID']) : '';
			$P_REQ_URL = isset($_POST['P_REQ_URL']) ? sanitize_text_field($_POST['P_REQ_URL']) : '';
			$P_NOTI = isset($_POST['P_NOTI']) ? sanitize_text_field($_POST['P_NOTI']) : '';
			$P_AMT = isset($_POST['P_AMT']) ? sanitize_text_field($_POST['P_AMT']) : '';
			
			if($P_STATUS == '00'){
				$args = array();
				$args['method'] = 'POST';
				$args['timeout'] = '15';
				$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=utf-8');
				$args['body'] = array(
					'P_MID' => $this->mid,
					'P_TID' => $P_TID,
				);
				$response = wp_remote_request($P_REQ_URL, $args);
				
				if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
					$body = wp_remote_retrieve_body($response);
					
					if($body){
						parse_str($body, $result);
						
						if(isset($result['P_STATUS']) && $result['P_STATUS'] == '00'){
							$merchantData = base64_decode($result['P_NOTI']);
							parse_str($merchantData, $custom_data);
							
							$result['P_NOTI'] = $custom_data;
							$_SESSION['payment'] = $result;
						}
					}
				}
				
				$success = 'true';
				$message = $P_RMESG1;
				$error_msg = '';
			}
			else{
				$success = 'false';
				$message = '';
				$error_msg = $P_RMESG1;
			}
		}
		else{
			$resultCode = isset($_POST['resultCode']) ? sanitize_text_field($_POST['resultCode']) : '';
			$resultMsg = isset($_POST['resultMsg']) ? sanitize_text_field($_POST['resultMsg']) : '';
			$merchantData = isset($_POST['merchantData']) ? sanitize_text_field($_POST['merchantData']) : '';
			
			if($resultCode == '0000'){
				$mid = isset($_POST['mid']) ? sanitize_text_field($_POST['mid']) : '';
				$timestamp = $this->timestamp;
				
				$authToken = isset($_POST['authToken']) ? $_POST['authToken'] : '';
				$authUrl = isset($_POST['authUrl']) ? sanitize_text_field($_POST['authUrl']) : '';
				$netCancel = isset($_POST['netCancelUrl']) ? sanitize_text_field($_POST['netCancelUrl']) : '';
				
				$result = $this->get_result_data($authUrl, array(
					'mid' => $mid,
					'authToken' => $authToken,
					'timestamp' => $timestamp,
				));
				
				if($result->resultCode == '0000'){
					$secureSignature = $this->util->makeSignatureAuth(array(
						'mid' => $mid,
						'tstamp' => $timestamp,
						'MOID' => $result->MOID,
						'TotPrice' => $result->TotPrice,
					));
					
					if($result->authSignature == $secureSignature){
						$merchantData = base64_decode($merchantData);
						parse_str($merchantData, $custom_data);
						
						if(isset($result->CARD_BillKey) && $result->CARD_BillKey){
							$this->updateCustomerUID(get_current_user_id(), $result->CARD_BillKey);
						}
						else{
							$result->custom_data = $custom_data;
							$_SESSION['payment'] = $result;
						}
						
						$success = 'true';
						$message = $result->resultMsg;
						$error_msg = '';
					}
					else{
						$success = 'false';
						$message = '';
						$error_msg = '데이터 위변조 체크 실패';
					}
				}
				else{
					$success = 'false';
					$message = '';
					$error_msg = $result->resultMsg;
				}
			}
			else{
				$success = 'false';
				$message = '';
				$error_msg = $resultMsg;
			}
		}
		
		include COSMOSFARM_MEMBERS_DIR_PATH . '/class/pg/inicis/callback.php';
		exit;
	}
	
	public function is_test(){
		if(get_option('cosmosfarm_members_builtin_pg_inicis_mid', '') || get_option('cosmosfarm_members_builtin_pg_inicis_general_mid', '')){
			return false;
		}
		return true;
	}
	
	public function save_settings(){
		$inicis_mid = isset($_POST['cosmosfarm_members_builtin_pg_inicis_mid']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_mid']) : '';
		$inicis_sign_key = isset($_POST['cosmosfarm_members_builtin_pg_inicis_sign_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_sign_key']) : '';
		$inicis_merchant_key = isset($_POST['cosmosfarm_members_builtin_pg_inicis_merchant_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_merchant_key']) : '';
		$inicis_api_key = isset($_POST['cosmosfarm_members_builtin_pg_inicis_api_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_api_key']) : '';
		
		$inicis_general_mid = isset($_POST['cosmosfarm_members_builtin_pg_inicis_general_mid']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_general_mid']) : '';
		$inicis_general_sign_key = isset($_POST['cosmosfarm_members_builtin_pg_inicis_general_sign_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_general_sign_key']) : '';
		$inicis_general_merchant_key = isset($_POST['cosmosfarm_members_builtin_pg_inicis_general_merchant_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_general_merchant_key']) : '';
		$inicis_general_api_key = isset($_POST['cosmosfarm_members_builtin_pg_inicis_general_api_key']) ? sanitize_text_field($_POST['cosmosfarm_members_builtin_pg_inicis_general_api_key']) : '';
		
		update_option('cosmosfarm_members_builtin_pg_inicis_mid', $inicis_mid);
		update_option('cosmosfarm_members_builtin_pg_inicis_sign_key', $inicis_sign_key);
		update_option('cosmosfarm_members_builtin_pg_inicis_merchant_key', $inicis_merchant_key);
		update_option('cosmosfarm_members_builtin_pg_inicis_api_key', $inicis_api_key);
		
		update_option('cosmosfarm_members_builtin_pg_inicis_general_mid', $inicis_general_mid);
		update_option('cosmosfarm_members_builtin_pg_inicis_general_sign_key', $inicis_general_sign_key);
		update_option('cosmosfarm_members_builtin_pg_inicis_general_merchant_key', $inicis_general_merchant_key);
		update_option('cosmosfarm_members_builtin_pg_inicis_general_api_key', $inicis_general_api_key);
	}
	
	public function get_signature($price, $order_id=''){
		if(!$order_id){
			$order_id = $this->order_id;
		}
		
		$args = array(
			'oid'       => $order_id,
			'price'     => $price,
			'timestamp' => $this->timestamp
		);
		
		return $this->util->makeSignature($args);
	}
	
	public function get_m_key(){
		return $this->util->makeHash($this->sign_key, 'sha256');
	}
	
	public function get_mobile_hashdata($order_id){
		return $this->util->makeHash($this->mid . $order_id . $this->timestamp . $this->merchant_key, 'sha256');
	}
	
	public function get_result_data($url, $args=array()){
		$mid = isset($args['mid']) ? sanitize_text_field($args['mid']) : '';
		$authToken = isset($args['authToken']) ? $args['authToken'] : '';
		$timestamp = isset($args['timestamp']) ? sanitize_text_field($args['timestamp']) : '';
		$charset = isset($args['charset']) ? sanitize_text_field($args['charset']) : 'UTF-8';
		$format = isset($args['format']) ? sanitize_text_field($args['format']) : 'JSON';
		
		$signature = $this->util->makeSignature(array(
			'authToken' => $authToken,
			'timestamp' => $timestamp,
		));
		
		$args = array();
		$args['method'] = 'POST';
		$args['timeout'] = '15';
		$args['headers'] = array('Content-type' => 'application/x-www-form-urlencoded;charset=utf-8');
		$args['body'] = array(
			'mid' => $mid,
			'authToken' => $authToken,
			'signature' => $signature,
			'timestamp' => $timestamp,
			'charset' => $charset,
			'format' => $format,
		);
		
		$response = wp_remote_request($url, $args);
		
		$result = new stdClass();
		$result->resultCode = '';
		$result->resultMsg = '';
		
		if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)){
			$body = wp_remote_retrieve_body($response);
			
			if($body){
				$result = json_decode($body);
			}
		}
		
		return $result;
	}
	
	public function get_current_timestamp(){
		return $this->timestamp;
	}
}