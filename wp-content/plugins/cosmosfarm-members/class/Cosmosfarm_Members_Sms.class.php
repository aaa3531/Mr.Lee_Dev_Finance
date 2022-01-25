<?php
/**
 * Cosmosfarm_Members_Sms
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Sms {
	
	public function __construct(){
		$template = isset($_GET['template'])?$_GET['template']:'';
		switch($template){
			case 'cosmosfarm_members_sms_form': add_action('template_redirect', array($this, 'sms_form')); break;
		}
	}
	
	public function sms_form(){
		global $wpdb, $wp_scripts, $wp_styles;
		if(current_user_can('manage_options')){
			$service = isset($_GET['service'])?sanitize_text_field($_GET['service']):'sms';
			$user_id = isset($_GET['user_id'])?intval($_GET['user_id']):'';
			$user = new WP_User($user_id);
			$option = get_cosmosfarm_members_option();
			include COSMOSFARM_MEMBERS_DIR_PATH . '/admin/sms_form.php';
			exit;
		}
	}
	
	public function send($phone, $content){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$phone = trim($phone);
		if(!$phone){
			return array('result'=>'error', 'message'=>'휴대폰번호를 확인해주세요.');
		}
		
		$phone = preg_replace("/[^0-9]/", "", $phone);
		if(preg_match("/^01[0-9]{8,9}$/", $phone)){
			$phone = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $phone);
		}
		else{
			return array('result'=>'error', 'message'=>'잘못된 번호형식입니다.');
		}
		
		$content = trim($content);
		if(!$content){
			return array('result'=>'error', 'message'=>'내용을 입력해주세요.');
		}
		
		if($option->sms_service == 'cafe24'){
			return $this->send_execute_cafe24($phone, $content);
		}
		else if($option->sms_service == 'toast_cloud'){
			return $this->send_execute_toast_cloud($phone, $content);
		}
	}
	
	public function send_every($phone_field, $content, $roles=array()){
		set_time_limit(3600);
		
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		if(!$phone_field){
			return array('result'=>'error', 'message'=>'대량 SMS를 보내시려면 휴대폰 필드를 선택해주세요.');
		}
		
		$content = trim($content);
		if(!$content){
			return array('result'=>'error', 'message'=>'내용을 입력해주세요.');
		}
		
		$limit = apply_filters('cosmosfarm_members_send_every_limit', 100);
		$page = 1;
		$result = array();
		if($option->sms_service == 'cafe24'){
			while($page){
				$args = array(
					'meta_query' => array(
						'key' => $phone_field,
						'value' => array(''),
						'compare' => 'NOT IN'
					),
					'orderby' => 'ID',
					'order' => 'ASC',
					'number' => $limit,
					'paged' => $page,
				);
				if($roles){
					$args['role__in'] = $roles;
				}
				
				$user_query = new WP_User_Query($args);
				$results = $user_query->get_results();
				if($results){
					$phone_list = array();
					foreach($results as $user){
						$phone = get_user_meta($user->ID, $phone_field, true);
						$phone = trim($phone);
						
						if($phone){
							$phone = preg_replace("/[^0-9]/", "", $phone);
							if(preg_match("/^01[0-9]{8,9}$/", $phone)){
								$phone = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $phone);
								$phone_list[] = $phone;
							}
						}
					}
					
					$phone_list = implode(',', $phone_list);
					
					$result = $this->send_execute_cafe24($phone_list, $content);
					if(isset($result['result']) && $result['result'] == 'error'){
						break;
					}
				}
				else{
					break;
				}
				$page++;
			}
		}
		else if($option->sms_service == 'toast_cloud'){
			while($page){
				$args = array(
					'meta_query' => array(
						'key' => $phone_field,
						'value' => array(''),
						'compare' => 'NOT IN'
					),
					'orderby' => 'ID',
					'order' => 'ASC',
					'number' => $limit,
					'paged' => $page,
				);
				if($roles){
					$args['role__in'] = $roles;
				}
				
				$user_query = new WP_User_Query($args);
				$results = $user_query->get_results();
				if($results){
					$phone_list = array();
					foreach($results as $user){
						$phone = get_user_meta($user->ID, $phone_field, true);
						$phone = trim($phone);
						
						if($phone){
							$phone = preg_replace("/[^0-9]/", "", $phone);
							if(preg_match("/^01[0-9]{8,9}$/", $phone)){
								$phone = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $phone);
								$phone_list[]['recipientNo'] = $phone;
							}
						}
					}
					
					$result = $this->send_execute_toast_cloud($phone_list, $content);
					if(isset($result['result']) && $result['result'] == 'error'){
						break;
					}
				}
				else{
					break;
				}
				$page++;
			}
		}
		
		return $result;
	}
	
	public function alimtalk_send($phone, $template_code, $user_id='', $product_id=''){
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$phone = trim($phone);
		if(!$phone){
			return array('result'=>'error', 'message'=>'휴대폰번호를 확인해주세요.');
		}
		
		$phone = preg_replace("/[^0-9]/", "", $phone);
		if(preg_match("/^01[0-9]{8,9}$/", $phone)){
			$phone = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $phone);
		}
		else{
			return array('result'=>'error', 'message'=>'잘못된 번호형식입니다.');
		}
		
		$option = get_cosmosfarm_members_option();
		$template = $option->alimtalk_template;
		$args = array();
		
		$template_code = trim($template_code);
		if(!$template_code){
			return array('result'=>'error', 'message'=>'템플릿 코드를 확인해주세요.');
		}
		
		$template_parameter = array();
		if(isset($args['key'])){
			foreach($args['key'] as $key=>$value){
				if($args['type'][$key] == 'user-info'){
					if(!$user_id){
						$user_meta = '테스트';
					}
					else{
						$user_meta = get_user_meta($user_id, $args['value'][$key], true);
					}
					$template_parameter[$value] = $user_meta;
				}
				else if($args['type'][$key] == 'cosmosfarm-members-product'){
					if($product_id){
						$product = new Cosmosfarm_Members_Subscription_Product();
						$product->init_with_id($product_id);
						if($product->ID()){
							switch($args['value'][$key]){
								case 'product_title': $template_parameter[$value] = $product->title();
								break;
								case 'product_price': $template_parameter[$value] = cosmosfarm_members_currency_format($product->price());
								break;
								case 'product_first_price': $template_parameter[$value] = cosmosfarm_members_currency_format($product->first_price());
								break;
								case 'product_subscription_type': $template_parameter[$value] = $product->subscription_type_format();
								break;
							}
						}
						else{
							$template_parameter[$value] = '테스트';
						}
					}
					else{
						$template_parameter[$value] = '테스트';
					}
				}
				else if($args['type'][$key] == 'woocommerce-product'){
					if($product_id){
						$product = wc_get_order($product_id);
						if($product->get_order_number()){
							$items = $product->get_items();
							$product_name = array();
							foreach($items as $item){
								$product_name[] = $item->get_name();
							}
							
							switch($args['value'][$key]){
								case 'wc_product_title': $template_parameter[$value] = implode(',', $product_name);
								break;
								case 'wc_product_price': $template_parameter[$value] = $product->get_total();
								break;
								case 'wc_product_quantity': $template_parameter[$value] = $product->get_item_count();
								break;
							}
						}
						else{
							$template_parameter[$value] = '테스트';
						}
					}
					else{
						$template_parameter[$value] = '테스트';
					}
				}
			}
		}
		
		return $this->send_execute_alimtalk($phone, $template_code, $template_parameter);
	}
	
	public function alimtalk_send_every($template_code, $phone_field){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		if(!$phone_field){
			return array('result'=>'error', 'message'=>'대량 발송 휴대폰번호 필드를 선택해주세요.');
		}
		
		$option = get_cosmosfarm_members_option();
		$template = $option->alimtalk_template;
		$args = array();
		
		$template_code = trim($template_code);
		if(!$template_code){
			return array('result'=>'error', 'message'=>'템플릿 코드를 확인해주세요.');
		}
		
		$user_args = array(
			'meta_key'	   => $phone_field,
			'meta_value'   => array(''),
			'meta_compare' => 'NOT IN'
		);
		$user_query = new WP_User_Query($user_args);
		
		$phone_list = array();
		$index = 0;
		foreach($user_query->get_results() as $user_key=>$user){
			$phone = get_user_meta($user->ID, $phone_field, true);
			$phone = trim($phone);
			
			if($phone){
				$phone = preg_replace("/[^0-9]/", "", $phone);
				if(preg_match("/^01[0-9]{8,9}$/", $phone)){
					$phone = preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $phone);
					$phone_list[$index]['recipientNo'] = $phone;
					
					$template_parameter = array();
					if(isset($args['key'])){
						foreach($args['key'] as $key=>$value){
							if($args['type'][$key] == 'user-info'){
								$user_meta = get_user_meta($user->ID, $args['value'][$key], true);
								$template_parameter[$value] = $user_meta;
							}
						}
						if($template_parameter){
							$phone_list[$index]['templateParameter'] = $template_parameter;
						}
						$phone_list[$index]['isResend'] = true;
					}
				}
			}
		}
		
		return $this->send_every_execute_alimtalk($template_code, $phone_list);
	}
	
	public function send_execute_cafe24($phone, $content){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$body = array();
		$body['user_id'] = base64_encode($option->sms_cafe24_id);
		$body['secure'] = base64_encode($option->sms_cafe24_secret);
		$body['msg'] = base64_encode($content);
		$body['rphone'] = base64_encode($phone);
		$body['sphone1'] = base64_encode($option->sms_caller1);
		$body['sphone2'] = base64_encode($option->sms_caller2);
		$body['sphone3'] = base64_encode($option->sms_caller3);
		$body['rdate'] = base64_encode('');
		$body['rtime'] = base64_encode('');
		$body['mode'] = base64_encode('1'); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$body['returnurl'] = base64_encode('');
		$body['testflag'] = base64_encode(''); // test is Y
		$body['destination'] = strtr(base64_encode(''), '+/=', '-,');
		$body['repeatFlag'] = base64_encode('');
		$body['repeatNum'] = base64_encode('');
		$body['repeatTime'] = base64_encode('');
		
		if(strlen(iconv('utf8', 'euckr', $content)) > 90){
			$type = 'L';
		}
		else{
			$type = '';
		}
		$body['smsType'] = base64_encode($type); // LMS일경우 L
		
		$response = wp_safe_remote_post('https://sslsms.cafe24.com/sms_sender.php', array('body'=>$body));
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		list($result, $count) = explode(',', $response['body']);
		
		if($result == 'success'){
			return array('result'=>'success', 'message'=>"성공적으로 전송되었습니다. 잔여건수는 {$count}건 입니다.");
		}
		else if($result == 'reserved'){
			return array('result'=>'success', 'message'=>"성공적으로 예약되었습니다. 잔여건수는 {$count}건 입니다.");
		}
		else if($result == '3205'){
			return array('result'=>'error', 'message'=>'잘못된 번호형식입니다.');
		}
		else if($result == '0044'){
			return array('result'=>'error', 'message'=>'스팸문자는발송되지 않습니다.');
		}
		else if($result == '-100'){
			return array('result'=>'error', 'message'=>'카페24 서버 에러가 발생했습니다.');
		}
		else if($result == '-102'){
			return array('result'=>'error', 'message'=>'아이디와 인증키를 다시 확인해주세요.');
		}
		else if($result == '-114'){
			return array('result'=>'error', 'message'=>'등록/인증되지 않은 발신번호입니다.');
		}
		else if($result == '-201'){
			return array('result'=>'error', 'message'=>'SMS 발송 건수가 부족합니다.');
		}
		else if($result == '-202'){
			return array('result'=>'error', 'message'=>'문자 \'됬\'은 카페24 SMS 발송시 사용불가능한 문자입니다.');
		}
		else if($result){
			return array('result'=>'error', 'message'=>"error code: {$result}");
		}
		return array('result'=>'error', 'message'=>'알 수 없는 에러가 발생했습니다.');
	}
	
	public function send_execute_toast_cloud($phone, $content){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		if(!is_array($phone)){
			$phone = array(array('recipientNo' => $phone));
		}
		
		if(strlen(iconv('utf8', 'euckr', $content)) > 90){
			$sender = 'mms';
			$title = mb_substr($content, 0, 40);
			$args = array(
				'headers' => array('content-type' => 'application/json;charset=UTF-8'),
				'body'    => json_encode(array(
					'title'         => $title,
					'body'          => $content,
					'sendNo'        => "{$option->sms_caller1}{$option->sms_caller2}{$option->sms_caller3}",
					'recipientList' => $phone
				))
			);
		}
		else{
			$sender = 'sms';
			$args = array(
				'headers' => array('content-type' => 'application/json;charset=UTF-8'),
				'body'    => json_encode(array(
					'body'          => $content,
					'sendNo'        => "{$option->sms_caller1}{$option->sms_caller2}{$option->sms_caller3}",
					'recipientList' => $phone
				))
			);
		}
		
		$response = wp_safe_remote_post("https://api-sms.cloud.toast.com/sms/v2.0/appKeys/{$option->sms_toast_cloud_appkey}/sender/{$sender}", $args);
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		$result = json_decode($response['body']);
		
		if(isset($result->header)){
			if($result->header->isSuccessful){
				return array('result'=>'success', 'message'=>"성공적으로 전송되었습니다.");
			}
			else{
				return array('result'=>'error', 'message'=>$result->header->resultMessage);
			}
		}
		return array('result'=>'error', 'message'=>'알 수 없는 에러가 발생했습니다.');
	}
	
	public function send_execute_alimtalk($phone, $template_code, $template_parameter){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$args = array();
		$args['headers'] = array(
			'content-type' => 'application/json;charset=UTF-8',
			'X-Secret-Key' => $option->alimtalk_secretkey
		);
		
		if($template_parameter){
			$args['body'] = json_encode(array(
				'plusFriendId'  => $option->alimtalk_plusfriend_id,
				'templateCode'  => $template_code,
				'recipientList' => array(array(
					'recipientNo' => $phone,
					'templateParameter' => apply_filters('cosmosfarm_members_alimtalk_template_paremeter', $template_parameter, $template_code),
					'isResend'	  => true
				)),
			));
		}
		else{
			$args['body'] = json_encode(array(
				'plusFriendId'  => $option->alimtalk_plusfriend_id,
				'templateCode'  => $template_code,
				'recipientList' => array(array(
					'recipientNo' => $phone,
					'isResend'	  => true
				)),
			));
		}
		
		$response = wp_safe_remote_post("https://api-alimtalk.cloud.toast.com/alimtalk/v1.2/appkeys/{$option->alimtalk_appkey}/messages", $args);
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		$result = json_decode($response['body']);
		
		if(isset($result->header)){
			if($result->header->isSuccessful){
				return array('result'=>'success', 'message'=>"성공적으로 전송되었습니다.");
			}
			else{
				return array('result'=>'error', 'message'=>$result->header->resultMessage);
			}
		}
		return array('result'=>'error', 'message'=>'알 수 없는 에러가 발생했습니다.');
	}
	
	public function send_every_execute_alimtalk($template_code, $recipient_list){
		$option = get_cosmosfarm_members_option();
		if(!$this->is_active()){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$args = array(
			'headers' => array(
				'content-type' => 'application/json;charset=UTF-8',
				'X-Secret-Key' => $option->alimtalk_secretkey
			),
			'body' => json_encode(array(
				'plusFriendId'  => $option->alimtalk_plusfriend_id,
				'templateCode'  => $template_code,
				'recipientList' => apply_filters('cosmosfarm_members_alimtalk_every_recipient_list', $recipient_list, $template_code)
			))
		);
		
		$response = wp_safe_remote_post("https://api-alimtalk.cloud.toast.com/alimtalk/v1.2/appkeys/{$option->alimtalk_appkey}/messages", $args);
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		$result = json_decode($response['body']);
		
		if(isset($result->header)){
			if($result->header->isSuccessful){
				return array('result'=>'success', 'message'=>"성공적으로 전송되었습니다.");
			}
			else{
				return array('result'=>'error', 'message'=>$result->header->resultMessage);
			}
		}
		return array('result'=>'error', 'message'=>'알 수 없는 에러가 발생했습니다.');
	}
	
	public function get_count_cafe24(){
		$option = get_cosmosfarm_members_option();
		if(!$option->sms_service || !$option->sms_cafe24_id || !$option->sms_cafe24_secret){
			return array('result'=>'error', 'message'=>'SMS 보내기 설정이 사용중지 되었습니다.');
		}
		
		$body['user_id'] = base64_encode($option->sms_cafe24_id);
		$body['secure'] = base64_encode($option->sms_cafe24_secret);
		$body['mode'] = base64_encode('1'); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		
		$response = wp_safe_remote_post('https://sslsms.cafe24.com/sms_remain.php', array('body'=>$body));
		
		if(is_wp_error($response)){
			return array('result'=>'error', 'message'=>$response->get_error_message());
		}
		
		$result = $response['body'];
		
		if($result == '-100'){
			return array('result'=>'error', 'message'=>'카페24 서버 에러가 발생했습니다.');
		}
		else if($result == '-102'){
			return array('result'=>'error', 'message'=>'아이디와 인증키를 다시 확인해주세요.');
		}
		else if($result == '-114'){
			return array('result'=>'error', 'message'=>'등록/인증되지 않은 발신번호입니다.');
		}
		else if($result == '-201'){
			return array('result'=>'error', 'message'=>'SMS 발송 건수가 부족합니다.');
		}
		else if($result == '-202'){
			return array('result'=>'error', 'message'=>'문자 \'됬\'은 카페24 SMS 발송시 사용불가능한 문자입니다.');
		}
		return array('result'=>'success', 'count'=>intval($result));
	}
	
	public function get_plus_friends(){
		$option = get_cosmosfarm_members_option();
		
		$args = array(
			'headers' => array(
				'content-type' => 'application/json;charset=UTF-8',
				'X-Secret-Key' => $option->alimtalk_secretkey
			)
		);
		
		$response = wp_safe_remote_get("https://api-alimtalk.cloud.toast.com/alimtalk/v1.2/appkeys/{$option->alimtalk_appkey}/plus-friends", $args);
		$result = json_decode($response['body']);
		
		if(isset($result->header)){
			if($result->header->isSuccessful){
				if(isset($result->plusFriends) && $result->plusFriends){
					return array('result'=>'success', 'plus_friends'=>$result->plusFriends);
				}
			}
			else{
				return array('result'=>'error', 'message'=>$result->header->resultMessage);
			}
		}
		
		return array();
	}
	
	public function get_alimtalk_template(){
		$option = get_cosmosfarm_members_option();
		
		$args = array(
			'headers' => array(
				'content-type' => 'application/json;charset=UTF-8',
				'X-Secret-Key' => $option->alimtalk_secretkey
			)
		);
		
		$response = wp_safe_remote_get("https://api-alimtalk.cloud.toast.com/alimtalk/v1.2/appkeys/{$option->alimtalk_appkey}/templates?plusFriendId={$option->alimtalk_plusfriend_id}&templateStatus=TSC03", $args);
		$result = json_decode($response['body']);
		
		if(isset($result->header)){
			if($result->header->isSuccessful){
				if(isset($result->templateListResponse->templates) && $result->templateListResponse->templates){
					return array('result'=>'success', 'templates'=>$result->templateListResponse->templates);
				}
			}
			else{
				return array('result'=>'error', 'message'=>$result->header->resultMessage);
			}
		}
		
		return array();
	}
	
	public function is_active(){
		$option = get_cosmosfarm_members_option();
		if($option->sms_service && $option->sms_caller1 && $option->sms_caller2){
			if($option->sms_service == 'cafe24'){
				if($option->sms_cafe24_id && $option->sms_cafe24_secret){
					return true;
				}
			}
			else if($option->sms_service == 'toast_cloud'){
				if($option->sms_toast_cloud_appkey){
					return true;
				}
			}
		}
		
		if($option->alimtalk_service == 'alimtalk'){
			if($option->alimtalk_appkey){
				return true;
			}
		}
		
		return false;
	}
}
?>