<?php if(!defined('ABSPATH')) exit;?>
<div class="cosmosfarm-members-form signin-form <?php echo $option->skin?>">
	<form method="post" action="<?php echo esc_url($login_action_url)?>">
		<input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to)?>">
		<input type="hidden" name="a" value="login">
		<fieldset>
			<legend><?php echo __('Log In', 'cosmosfarm-members')?></legend>
			
			<?php if($option->allow_email_login):?>
			<label for="log"><?php echo __('Email', 'cosmosfarm-members')?></label>
			<div class="div_text">
				<input name="log" type="text" id="log" class="username">
			</div>
			<?php else:?>
			<label for="log"><?php echo __('Username', 'cosmosfarm-members')?></label>
			<div class="div_text">
				<input name="log" type="text" id="log" class="username">
			</div>
			<?php endif?>
			
			<label for="pwd"><?php echo __('Password', 'cosmosfarm-members')?></label>
			<div class="div_text">
				<input name="pwd" type="password" id="pwd" class="password">
			</div>
			
			<div class="button_div">
				<label><input name="rememberme" type="checkbox" id="rememberme" value="forever"><?php echo __('Keep me signed in', 'cosmosfarm-members')?></label>
				<input type="submit" class="buttons" value="<?php echo __('Log In', 'cosmosfarm-members')?>">
			</div>
			
			<?php echo cosmosfarm_members_social_buttons(array('redirect_to'=>$redirect_to))?>
			
			<?php if(get_cosmosfarm_members_profile_url()):?>
			<div align="right" class="link-text pwdreset">
				<a href="<?php echo add_query_arg(array('a'=>'pwdreset'), get_cosmosfarm_members_profile_url())?>">아이디/비밀번호 찾기</a>
			</div>
			<?php endif?>
			
			<?php if(wp_registration_url()):?>
			<div align="right" class="link-text register">
				<a href="<?php echo wp_registration_url()?>"><?php echo __('Register', 'cosmosfarm-members')?></a>
			</div>
			<?php endif?>
		</fieldset>
	</form>
</div>