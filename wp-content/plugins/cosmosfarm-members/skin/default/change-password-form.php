<?php if(!defined('ABSPATH')) exit;?>
<div class="cosmosfarm-members-form pwdchange-form <?php echo $option->skin?>">
	<form method="post" action="<?php echo esc_url($form_action_url)?>">
		<?php wp_nonce_field('wpmem_shortform_nonce', '_wpmem_pwdchange_nonce')?>
		<input type="hidden" name="redirect_to" value="<?php echo esc_url($form_action_url)?>">
		<input type="hidden" name="a" value="<?php echo esc_attr($a)?>">
		<input type="hidden" name="formsubmit" value="1">
		
		<?php if($a == 'set_password_from_key'):?>
			<input type="hidden" name="key" value="<?php echo esc_attr($key)?>">
			<input type="hidden" name="login" value="<?php echo esc_attr($login)?>">
		<?php endif?>
		
		<fieldset>
			<legend><?php echo __('Change password', 'cosmosfarm-members')?></legend>
			
			<?php if($a == 'pwdchange'):?>
			<label for="current_password"><?php echo __('Current password', 'cosmosfarm-members')?></label>
			<div class="div_text">
				<input name="current_password" type="password" id="current_password" class="password" required>
			</div>
			<?php endif?>
			
			<label for="pass1"><?php echo __('New password', 'cosmosfarm-members')?></label>
			<div class="div_text">
				<input name="pass1" type="password" id="pass1" class="password" required>
			</div>
			
			<label for="pass2"><?php echo __('Confirm new password', 'cosmosfarm-members')?></label>
			<div class="div_text">
				<input name="pass2" type="password" id="pass2" class="password" required>
				<?php if($option->use_strong_password):?>
				<span class="password-strength-meter-display bad"><?php echo __('Password must consist of 8 digits, including English, numbers and special characters.', 'cosmosfarm-members')?></span>
				<?php endif?>
			</div>
			
			<div class="button_div">
				<input type="submit" value="<?php echo __('Update Password', 'cosmosfarm-members')?>" class="buttons">
			</div>
		</fieldset>
	</form>
</div>