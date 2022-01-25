<?php if(!defined('ABSPATH')) exit;?>
<script>
var cosmosfarm_members_login_timeout = {
	timeout_minutes: <?php echo intval($login_timeout)?>,
	timeout_url: '<?php echo esc_url_raw($login_timeout_url)?>',
	current_countdown: 0,
	timeout_process: '',
	init: function(){
		if(cosmosfarm_members_login_timeout.timeout_minutes){
			clearTimeout(cosmosfarm_members_login_timeout.timeout_process);
			cosmosfarm_members_login_timeout.current_countdown = parseInt(cosmosfarm_members_login_timeout.timeout_minutes) * 60;
			cosmosfarm_members_login_timeout.countdown();
		}
	},
	countdown: function(){
		if(cosmosfarm_members_login_timeout.current_countdown <= 0){
			top.window.location.href = cosmosfarm_members_login_timeout.timeout_url;
		}
		else{
			if(cosmosfarm_members_login_timeout.current_countdown <= 10){
				jQuery('#cosmosfarm-members-login-timeout-popup-background').removeClass('hide');
				jQuery('#cosmosfarm-members-login-timeout-popup').removeClass('hide');
			}
			jQuery('#cosmosfarm-members-login-timeout-popup .popup-countdown').text(cosmosfarm_members_login_timeout.current_countdown);
			cosmosfarm_members_login_timeout.current_countdown = parseInt(cosmosfarm_members_login_timeout.current_countdown) - 1;
			cosmosfarm_members_login_timeout.timeout_process = setTimeout(cosmosfarm_members_login_timeout.countdown, 1000);
		}
	},
	extend_time: function(){
		cosmosfarm_members_login_timeout.init();
		jQuery('#cosmosfarm-members-login-timeout-popup-background').addClass('hide');
		jQuery('#cosmosfarm-members-login-timeout-popup').addClass('hide');
	}
}
window.onload = function(){
	cosmosfarm_members_login_timeout.init();
}
</script>
<div id="cosmosfarm-members-login-timeout-popup-background" class="hide"></div>
<div id="cosmosfarm-members-login-timeout-popup" class="hide">
	<div class="popup-wrap">
		<div class="popup-message"><?php echo __('Log out automatically.', 'cosmosfarm-members')?></div>
		<div class="popup-countdown">0</div>
		<div class="popup-controlbar"><button type="button" onclick="cosmosfarm_members_login_timeout.extend_time()"><?php echo __('Extend Time', 'cosmosfarm-members')?></button></div>
	</div>
</div>