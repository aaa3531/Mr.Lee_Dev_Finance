<?php if(!defined('ABSPATH')) exit;?>
<div class="cosmosfarm-members-form signin-form <?php echo $option->skin?>">
	<form method="post" action="<?php echo esc_url($login_action_url)?>">
		<input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to)?>">
		<input type="hidden" name="a" value="login">
		<div class="form-wrap">
			<div class="form-top">
				<div class="form-top-wrap">
					<div class="form-top-fields">
						<?php if($option->allow_email_login):?>
						<div class="form-input-row"><label for="log"><?php echo __('Email', 'cosmosfarm-members')?></label><input type="text" id="log" name="log"></div>
						<?php else:?>
						<div class="form-input-row"><label for="log"><?php echo __('Username', 'cosmosfarm-members')?></label><input type="text" id="log" name="log"></div>
						<?php endif?>
						
						<div class="form-input-row"><label for="pwd"><?php echo __('Password', 'cosmosfarm-members')?></label><input type="password" id="pwd" name="pwd"></div>
						
						<div class="form-checkbox-row"><label><input type="checkbox" id="rememberme" name="rememberme" value="forever"><?php echo __('Keep me signed in', 'cosmosfarm-members')?></label></div>
					</div>
					<div class="form-top-button">
						<button type="submit" class="login-button"><?php echo __('Log In', 'cosmosfarm-members')?></button>
					</div>
				</div>
			</div>
			<div class="form-bottom">
				<?php echo cosmosfarm_members_social_buttons(array('redirect_to'=>$redirect_to))?>
				
				<div class="form-link">
					<div class="form-link-item">
						<?php if(get_cosmosfarm_members_profile_url()):?>
						<a href="<?php echo add_query_arg(array('a'=>'pwdreset'), get_cosmosfarm_members_profile_url())?>" class="form-button pwdreset"><?php echo __('Forgot Password', 'cosmosfarm-members')?></a>
						<?php endif?>
					</div>
					<div class="form-link-item">
						<?php if(wp_registration_url()):?>
						<a href="<?php echo wp_registration_url()?>" class="form-button register"><?php echo __('Register', 'cosmosfarm-members')?></a>
						<?php endif?>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>