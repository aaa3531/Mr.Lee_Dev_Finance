<?php if(!defined('ABSPATH')) exit;?>
<div class="cosmosfarm-members-account-links">
	<?php if(is_user_logged_in()):?>
	<!-- 로그인시 출력 -->
	<div class="cosmosfarm-members-account-link"><a href="<?php echo get_cosmosfarm_members_profile_url()?>"><?php echo __('Account', 'cosmosfarm-members')?></a></div>
	<?php else:?>
	<!-- 비로그인 출력 -->
	<div class="cosmosfarm-members-register-link"><a href="<?php echo wp_registration_url()?>"><?php echo __('Register', 'cosmosfarm-members')?></a></div>
	<?php endif?>
	
	<!-- 로그인,로그아웃 출력 -->
	<div class="cosmosfarm-members-loginout-link"><?php echo wp_loginout($_SERVER['REQUEST_URI'], false)?></div>
</div>