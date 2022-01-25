<?php
/**
 * Cosmosfarm_Members_Security
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
final class Cosmosfarm_Members_Security {
	
	static $login_protect_lockdown = false;
	
	public function __construct(){
		add_filter('authenticate', array($this, 'login_protect'), 99, 3);
		add_filter('authenticate', array($this, 'authenticate_verify_email'), 999, 3);
		add_action('wp_login', array($this, 'login'), 10, 2);
		add_action('wp_login_failed', array($this, 'login_failed'), 10, 1);
		add_action('wp_footer', array($this, 'login_timeout'));
		
		add_action('show_user_profile', array($this, 'save_history_profile_view'));
		add_action('edit_user_profile', array($this, 'save_history_profile_view'));
		add_action('personal_options_update', array($this, 'save_history_profile_update'));
		add_action('edit_user_profile_update', array($this, 'save_history_profile_update'));
		add_action('wpmem_post_update_data', array($this, 'save_history_my_profile_update'));
		add_action('wpmem_pwd_change', array($this, 'save_history_password_change'));
		add_action('set_user_role', array($this, 'save_history_user_role_change'), 10, 3);
		
		add_filter('manage_cosmosfarm_members_subscription_order_columns', array($this, 'save_history_subscription_order_list'), 10, 1);
		add_action('cosmosfarm_members_before_subscription_order_setting', array($this, 'save_history_subscription_order_setting'), 10, 2);
		
		add_filter('wpmem_pwd_change_error', array($this, 'pwd_change_error'), 10, 1);
		add_action('template_redirect', array($this, 'closed_site_check'), 0);
		
		add_action('login_form', array($this, 'add_login_otp_field'));
		add_action('wp_ajax_cosmosfarm_members_login_otp_send', array($this, 'login_otp_send'));
		add_action('wp_ajax_nopriv_cosmosfarm_members_login_otp_send', array($this, 'login_otp_send'));
		add_filter('authenticate', array($this, 'login_otp_check'), 5, 3);
	}
	
	public function login_protect($user, $username, $password){
		if($username){
			$check_user = get_user_by('login', $username);
			if(!$check_user){
				$check_user = get_user_by('email', $username);
			}
			if($check_user){
				$option = get_cosmosfarm_members_option();
				if($option->save_login_history && $option->use_login_protect){
					$login_protect_lockdown_timestamp = get_user_meta($check_user->ID, 'login_protect_lockdown_timestamp', true);
					if($login_protect_lockdown_timestamp){
						if(current_time('timestamp') - $login_protect_lockdown_timestamp <= $option->login_protect_lockdown * 60){
							self::$login_protect_lockdown = true;
							return new WP_Error('login_lockdown', sprintf(__('You have exceeded the maximum number of %d attempts, try again in %d minutes.', 'cosmosfarm-members'), $option->login_protect_count, $option->login_protect_lockdown));
						}
					}
				}
			}
		}
		return $user;
	}
	
	public function authenticate_verify_email($user, $username, $password){
		$pass = ((!is_wp_error($user)) && $password) ? wp_check_password($password, $user->user_pass, $user->ID) : false;
		if(!$pass){
			return $user;
		}
		
		if(!current_user_can('manage_options')){
			$option = get_cosmosfarm_members_option();
			$wait_verify_email = get_user_meta($user->ID, 'wait_verify_email', true);
			if($option->verify_email && $wait_verify_email){
				return new WP_Error('verify_email_failed', __('Please check the email sent to your email address.', 'cosmosfarm-members'));
			}
		}
		return $user;
	}
	
	public function login($user_login, $user){
		update_user_meta($user->ID, 'last_login', current_time('timestamp'));
		$this->add_login_history($user->ID, true);
	}
	
	public function login_failed($username){
		global $wpdb;
		
		$option = get_cosmosfarm_members_option();
		
		if($user = get_user_by('login', $username)){
			$this->add_login_history($user->ID, false);
		}
		else if($user = get_user_by('email', $username)){
			$this->add_login_history($user->ID, false);
		}
		
		if($user){
			if($option->save_login_history && $option->use_login_protect){
				
				$login_protect_time = $option->login_protect_time * -1;
				$datetime = date('Y-m-d H:i:s', strtotime("{$login_protect_time} minutes", current_time('timestamp')));
				$failed_login_count = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}cosmosfarm_members_login_history` WHERE `user_id`='{$user->ID}' AND `login_datetime`>='{$datetime}' AND `login_result`='failure'");
				
				if($failed_login_count >= $option->login_protect_count){
					update_user_meta($user->ID, 'login_protect_lockdown_timestamp', current_time('timestamp'));
				}
			}
		}
	}
	
	public function login_timeout(){
		global $cosmosfarm_members_skin;
		
		if(is_user_logged_in()){
			$option = get_cosmosfarm_members_option();
			$use_login_timeout = apply_filters('cosmosfarm_members_use_login_timeout', $option->use_login_timeout, $option);
			if($use_login_timeout){
				$login_timeout_url = add_query_arg(array('action'=>'cosmosfarm_members_login_timeout'), site_url());
				echo $cosmosfarm_members_skin->login_timeout_popup($login_timeout_url, $option->login_timeout);
			}
		}
	}
	
	public function add_login_history($user_id, $login_result){
		global $wpdb;
		
		if(!self::$login_protect_lockdown){
			$option = get_cosmosfarm_members_option();
			if($option->save_login_history){
				$country = $this->get_user_country();
				$data = array(
						'user_id' => $user_id,
						'login_datetime' => date('Y-m-d H:i:s', current_time('timestamp')),
						'ip_address' => $this->get_user_ip(),
						'browser' => $this->get_browser(),
						'operating_system' => $this->get_operating_system(),
						'country_name' => $country->country_name,
						'country_code' => $country->country_code,
						'login_result' => $login_result?'success':'failure',
						'user_agent' => isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'',
				);
				$wpdb->insert("{$wpdb->prefix}cosmosfarm_members_login_history", $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
				
				update_user_meta($user_id, 'user_lastlogin', date('Y-m-d H:i:s', current_time('timestamp')));
				update_user_meta($user_id, 'dormant_member_alert', '');
			}
		}
	}
	
	public function add_activity_history($user_id, $related_user_id, $comment){
		global $wpdb;
		
		$data = array(
				'user_id' => $user_id,
				'related_user_id' => $related_user_id,
				'activity_datetime' => date('Y-m-d H:i:s', current_time('timestamp')),
				'ip_address' => $this->get_user_ip(),
				'comment' => $comment,
		);
		$wpdb->insert("{$wpdb->prefix}cosmosfarm_members_activity_history", $data, array('%d', '%d', '%s', '%s', '%s'));
	}
	
	public function get_user_ip(){
		static $ip;
		
		if($ip !== null){
			return $ip;
		}
		if(isset($_SERVER["HTTP_CF_CONNECTING_IP"]) && $_SERVER["HTTP_CF_CONNECTING_IP"] && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)){
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}
	    else if(isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    
	    return $ip;
	}
	
	public function get_browser(){
		$user_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
		$search_engines = array(
				'AOL.com' => 'aolbuild',
				'Baidu' => 'baidu',
				'Bingbot/MSN' => 'bingbot',
				'Bingbot/MSN' => 'bingpreview',
				'Bingbot/MSN' => 'msnbot',
				'DuckDuckGo' => 'duckduckgo',
				'Google' => 'adsbot-google',
				'Google' => 'googlebot',
				'Google' => 'mediapartners-google',
				'Teoma' => 'teoma',
				'Yahoo!' => 'slurp',
				'Yandex' => 'yandex',
				'Naver' => 'Yeti',
				'Naver' => 'NAVER',
				'Naver' => 'Naver',
				'Daum' => 'Daumoa',
				'Daum' => 'Daum',
		);
		foreach($search_engines as $engine=>$pattern){
			if(strpos($user_agent, $pattern)){
				return $engine;
			}
		}
		$browsers = array(
				'Opera' => 'Opera',
				'Opera' => 'OPR/',
				'Firefox' => 'Firebird',
				'Firefox' => 'Firefox',
				'Galeon' => 'Galeon',
				'Chrome' => 'Chrome',
				'MyIE' => 'MyIE',
				'Lynx' => 'Lynx',
				'Konqueror' => 'Konqueror',
				'Safari' => 'Safari',
				'Edge' => 'Edge',
				'Internet Explorer' => 'MSIE',
				'Internet Explorer' => 'Trident/7',
		);
		foreach($browsers as $browser=>$pattern){
			if(strpos($user_agent, $pattern)){
				return $browser;
			}
		}
		return 'Unknown';
	}
	
	public function get_operating_system(){
		$user_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
		$os_list = array(
				'Windows 10' => '/windows nt 10/i',
				'Windows 8.1' => '/windows nt 6.3/i',
				'Windows 8' => '/windows nt 6.2/i',
				'Windows 7' => '/windows nt 6.1/i',
				'Windows Vista' => '/windows nt 6.0/i',
				'Windows Server 2003/XP x64' => '/windows nt 5.2/i',
				'Windows XP' => '/windows nt 5.1/i',
				'Windows XP' => '/windows xp/i',
				'Windows 2000' => '/windows nt 5.0/i',
				'Windows ME' => '/windows me/i',
				'Windows 98' => '/win98/i',
				'Windows 95' => '/win95/i',
				'Windows 3.11' => '/win16/i',
				'Mac OS X' => '/macintosh|mac os x/i',
				'Mac OS 9' => '/mac_powerpc/i',
				'Linux' => '/linux/i',
				'Ubuntu' => '/ubuntu/i',
				'Android' => '/android/i',
				'iPhone' => '/iphone/i',
				'iPod' => '/ipod/i',
				'iPad' => '/ipad/i',
				'BlackBerry' => '/blackberry/i',
				'webOS' => '/webos/i',
		);
		foreach($os_list as $os=>$pattern){
			if(preg_match($pattern, $user_agent)){
				return $os;
			}
	
		}
		return 'Unknown';
	}
	
	public function get_user_country(){
		$args = array(
				'user-agent'=>isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'',
				'headers'=>array('referer'=>site_url()),
		);
		$user_ip = $this->get_user_ip();
		$response = wp_remote_get("http://www.geoplugin.net/json.gp?ip={$user_ip}", $args);
		if(is_array($response) && !is_wp_error($response) && $response['response']['code'] == '200'){
			$data = json_decode($response['body']);
			$country = new stdClass();
			$country->country_name = isset($data->geoplugin_countryName) ? $data->geoplugin_countryName : 'Unknown';
			$country->country_code = isset($data->geoplugin_countryCode) ? $data->geoplugin_countryCode : 'Unknown';
			return $country;
		}
		else{
			$country = new stdClass();
			$country->country_name = 'Unknown';
			$country->country_code = 'Unknown';
			return $country;
		}
	}
	
	public function save_history_profile_view($user){
		$option = get_cosmosfarm_members_option();
		if($option->save_activity_history){
			if($user->ID == get_current_user_id()){
				$this->add_activity_history(get_current_user_id(), $user->ID, '본인 회원정보 조회');
			}
			else{
				$this->add_activity_history(get_current_user_id(), $user->ID, '회원정보 조회');
			}
		}
	}
	
	public function save_history_profile_update($user_id){
		if(current_user_can('edit_user', $user_id)){
			$option = get_cosmosfarm_members_option();
			if($option->save_activity_history){
				if($user_id == get_current_user_id()){
					$this->add_activity_history(get_current_user_id(), $user_id, '본인 회원정보 업데이트');
				}
				else{
					$this->add_activity_history(get_current_user_id(), $user_id, '회원정보 업데이트');
				}
			}
		}
	}
	
	public function save_history_my_profile_update(){
		$option = get_cosmosfarm_members_option();
		if($option->save_activity_history){
			$this->add_activity_history(get_current_user_id(), get_current_user_id(), '본인 회원정보 업데이트');
		}
	}
	
	public function save_history_password_change(){
		$option = get_cosmosfarm_members_option();
		if($option->save_activity_history){
			$this->add_activity_history(get_current_user_id(), get_current_user_id(), '본인 비밀번호 변경');
		}
	}
	
	public function save_history_user_role_change($user_id, $role, $old_roles){
		$option = get_cosmosfarm_members_option();
		if($option->save_activity_history){
			$crruent_user_id = get_current_user_id();
			
			// 회원가입 시에는 get_current_user_id() 값이 비어있기 때문에 $user_id 값을 사용한다.
			$crruent_user_id = $crruent_user_id ? $crruent_user_id : $user_id;
			
			$this->add_activity_history($crruent_user_id, $user_id, "사용자 역할 변경 ({$role})");
		}
	}
	
	public function save_history_subscription_order_list($columns){
		$option = get_cosmosfarm_members_option();
		if($option->save_activity_history){
			$this->add_activity_history(get_current_user_id(), get_current_user_id(), '주문 목록 조회');
		}
		return $columns;
	}
	
	public function save_history_subscription_order_setting($order, $product){
		$option = get_cosmosfarm_members_option();
		if($option->save_activity_history){
			$user = $order->user();
			$this->add_activity_history(get_current_user_id(), $user->ID, sprintf('[%s] 주문 조회', $order->ID()));
		}
	}
	
	public function pwd_change_error($is_error){
		$current_password = isset($_POST['current_password'])?trim($_POST['current_password']):'';
		if($current_password){
			$user = wp_get_current_user();
			if(!$user || !wp_check_password($current_password, $user->user_pass, $user->ID)){
				$is_error = 'pwdchangerr';
			}
		}
		return $is_error;
	}
	
	public function closed_site_check(){
		$option = get_cosmosfarm_members_option();
		if($option->active_closed_site && $option->login_page_id && !is_user_logged_in()){
			
			$allow_pages = array();
			if(isset($option->login_page_id)) $allow_pages[] = $option->login_page_id;
			if(isset($option->register_page_id)) $allow_pages[] = $option->register_page_id;
			if(isset($option->account_page_id)) $allow_pages[] = $option->account_page_id;
			
			$allow_pages = apply_filters('cosmosfarm_members_closed_site_allow_pages', $allow_pages);
			
			if(is_array($allow_pages) && $allow_pages && !in_array(get_the_ID(), $allow_pages)){
				auth_redirect();
			}
		}
	}
	
	public function add_login_otp_field(){
		$option = get_cosmosfarm_members_option();
		
		if($option->login_otp_phone_number):
			wp_enqueue_script('cosmosfarm-members-login-otp', COSMOSFARM_MEMBERS_URL . '/assets/js/login-otp.js', array('jquery'), COSMOSFARM_MEMBERS_VERSION, true);
			?>
			<script>
			var cosmosfarm_members_login_otp_send_ajax_url = '<?php echo esc_url_raw(admin_url('/admin-ajax.php', 'relative'))?>';
			</script>
			<p>
				<label for="login_otp_number"><button type="button" class="button" onclick="cosmosfarm_members_login_otp_send(this.form)">인증번호 받기</button><br>
				<input type="text" size="20" value="" class="input" id="login_otp_number" name="login_otp_number" placeholder="인증번호 6자리 입력"></label>
			</p>
		<?php endif?>
	<?php }
	
	public function login_otp_send(){
		$option = get_cosmosfarm_members_option();
		
		$result = array('result'=>'success', 'message'=>'인증번호 없이 로그인할 수 있습니다.');
		
		if($option->login_otp_phone_number){
			$log = isset($_POST['log']) ? sanitize_text_field($_POST['log']) : '';
			
			if($log){
				$user = get_user_by('login', $log);
				if(!$user){
					$user = get_user_by('email', $log);
				}
				
				if($user){
					if(array_intersect($option->login_otp_user_roles, $user->roles)){
						$login_otp_number = mt_rand(100000, 999999);
						
						$billing_phone = get_user_meta($user->ID, 'billing_phone', true);
						$phone_number = $billing_phone ? $billing_phone : $option->login_otp_phone_number;
						
						cosmosfarm_members_sms_send($phone_number, "인증번호 6자리 [{$login_otp_number}]를 입력해주세요.");
						
						update_user_meta($user->ID, 'cosmosfarm_members_login_otp_number', $login_otp_number);
						$result = array('result'=>'success', 'message'=>sprintf('[%s] 휴대폰 문자를 확인해주세요.', cosmosfarm_members_phone_masking($phone_number)));
					}
				}
				else{
					$result = array('result'=>'error', 'message'=>'사용자명 또는 이메일 주소를 입력해주세요.');
				}
			}
			else{
				$result = array('result'=>'error', 'message'=>'사용자명 또는 이메일 주소를 입력해주세요.');
			}
		}
		
		wp_send_json($result);
	}
	
	public function login_otp_check($user, $username, $password){
		$option = get_cosmosfarm_members_option();
		
		if($option->login_otp_phone_number){
			$input_value = isset($_POST['login_otp_number']) ? sanitize_text_field($_POST['login_otp_number']) : '';
			
			$user = get_user_by('login', $username);
			if(!$user){
				$user = get_user_by('email', $username);
			}
			
			if($user){
				if(array_intersect($option->login_otp_user_roles, $user->roles)){
					$stored_value = get_user_meta($user->ID, 'cosmosfarm_members_login_otp_number', true);
					
					if(empty($input_value) || $input_value != $stored_value){
						remove_action('authenticate', 'wp_authenticate_username_password', 20);
						remove_action('authenticate', 'wp_authenticate_email_password', 20);
						
						return new WP_Error('denied', __("<strong>에러</strong>: 인증번호가 올바르지 않습니다."));
					}
					else{
						delete_user_meta($user->ID, 'cosmosfarm_members_login_otp_number');
						update_user_meta($user->ID, 'cosmosfarm_members_login_otp_auth_time', date('YmdHis', current_time('timestamp')));
					}
				}
			}
		}
		
		return null;
	}
	
	public static function dormant_member_email_title(){
		$option = get_cosmosfarm_members_option();
		
		if($option->dormant_member_email_title){
			return $option->dormant_member_email_title;
		}
		
		return sprintf('[%s] 휴면 회원 삭제 예정 알림', get_bloginfo('name'));
	}
	
	public static function dormant_member_email_message(){
		$option = get_cosmosfarm_members_option();
		
		if($option->dormant_member_email_message){
			return $option->dormant_member_email_message;
		}
		
		ob_start();
		?>안녕하세요.
30일 후 계정이 자동으로 탈퇴 및 정보가 삭제될 예정입니다.
원치 않으실 경우 홈페이지에 로그인을 해주시길 부탁드립니다.
고맙습니다.

로그인 하기:
<?php echo esc_url_raw($option->login_page_id ? get_permalink($option->login_page_id) : wp_login_url())?><?php
		
		return ob_get_clean();
	}
}