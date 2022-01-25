<?php if(!defined('ABSPATH')) exit;
if($field_type == 'buyer_name'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'buyer_email'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="email" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'buyer_tel'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'text'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : $default_value)?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'number'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="number" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : $default_value)?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'select'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<select id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>"<?php if($field['required']):?> required<?php endif?>>
			<?php
			if(isset($field['data'])):
				$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
				$list = explode(',', $field['data']);
				$list = array_map('trim', $list);
				foreach($list as $value):
			?>
			<option value="<?php echo esc_attr($value)?>"<?php if($value == $user_meta_value || $value == $default_value):?> selected<?php endif?>><?php echo esc_html($value)?></option>
			<?php endforeach; endif;?>
		</select>
	</div>
</div>
<?php elseif($field_type == 'radio'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for=""><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<?php
		if(isset($field['data'])):
			$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
			$list = explode(',', $field['data']);
			$list = array_map('trim', $list);
			foreach($list as $value):
		?>
		<label><input type="radio" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($value)?>"<?php if($value == $user_meta_value || $value == $default_value):?> checked<?php endif?>> <?php echo esc_html($value)?></label>
		<?php endforeach; endif;?>
	</div>
</div>
<?php elseif($field_type == 'checkbox'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for=""><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<?php
		if(isset($field['data'])):
			$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
			$list = explode(',', $field['data']);
			$list = array_map('trim', $list);
			foreach($list as $value):
		?>
		<label><input type="checkbox" name="<?php echo esc_attr($field['meta_key'])?>[]" value="<?php echo esc_attr($value)?>"<?php if($value == $user_meta_value || $value == $default_value):?> checked<?php endif?>> <?php echo esc_html($value)?></label>
		<?php endforeach; endif;?>
	</div>
</div>
<?php elseif($field_type == 'zip'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : '')?>"<?php if(get_cosmosfarm_members_locale() == 'ko_KR' && !$option->postcode_service_disabled):?> onclick="cosmosfarm_members_open_postcode('subscription_checkout')" readonly<?php endif?><?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field2['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field2['meta_key'])?>"><?php echo esc_html($field2['label'])?><?php if($field2['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field2['meta_key'])?>" name="<?php echo esc_attr($field2['meta_key'])?>" value="<?php echo esc_attr($field2['user_meta_key'] ? $user->{$field2['user_meta_key']} : '')?>"<?php if(get_cosmosfarm_members_locale() == 'ko_KR' && !$option->postcode_service_disabled):?> onclick="cosmosfarm_members_open_postcode('subscription_checkout')" readonly<?php endif?><?php if($field2['required']):?> required<?php endif?>>
	</div>
</div>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field3['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field3['meta_key'])?>"><?php echo esc_html($field3['label'])?><?php if($field3['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field3['meta_key'])?>" name="<?php echo esc_attr($field3['meta_key'])?>" value="<?php echo esc_attr($field3['user_meta_key'] ? $user->{$field3['user_meta_key']} : '')?>"<?php if($field3['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'textarea'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<textarea id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" rows="5" name="<?php echo esc_attr($field['meta_key'])?>"<?php if($field['required']):?> required<?php endif?>><?php echo esc_html($field['user_meta_key'] ? $user->{$field['user_meta_key']} : $default_value)?></textarea>
	</div>
</div>
<?php elseif($field_type == 'datepicker'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" class="cosmosfarm-members-datepicker" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : ($default_value ? $default_value : ''))?>"<?php if($field['required']):?> required<?php endif?>>
	</div>
</div>
<?php elseif($field_type == 'timepicker'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<?php
		$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
		$user_meta_value = date('H:i', strtotime($user_meta_value));
		$default_value = date('H:i', strtotime($default_value));
		?>
		<select id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>"<?php if($field['required']):?> required<?php endif?>>
			<option value="">선택</option>
			<?php for($time=mktime(9, 0, 0); $time<=mktime(21, 0, 0); $time=strtotime('+30 min', $time)):?>
			<option value="<?php echo date('H:i', $time)?>"<?php if(date('H:i', $time) == $user_meta_value || date('H:i', $time) == $default_value):?> selected<?php endif?>><?php echo date('H:i', $time)?></option>
			<?php endfor?>
		</select>
	</div>
</div>
<?php elseif($field_type == 'weekpicker'):?>
<div class="checkout-attr-row meta-key-<?php echo esc_attr($field['meta_key'])?>">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?><?php if($field['required']):?> <span class="required">*</span><?php endif?></label>
	<div class="attr-value">
		<?php
		$user_meta_value = $field['user_meta_key'] ? $user->{$field['user_meta_key']} : '';
		?>
		<select id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>"<?php if($field['required']):?> required<?php endif?>>
			<option value="">선택</option>
			<option value="월요일"<?php if('월요일' == $user_meta_value || '월요일' == $default_value):?> selected<?php endif?>>월요일</option>
			<option value="화요일"<?php if('화요일' == $user_meta_value || '화요일' == $default_value):?> selected<?php endif?>>화요일</option>
			<option value="수요일"<?php if('수요일' == $user_meta_value || '수요일' == $default_value):?> selected<?php endif?>>수요일</option>
			<option value="목요일"<?php if('목요일' == $user_meta_value || '목요일' == $default_value):?> selected<?php endif?>>목요일</option>
			<option value="금요일"<?php if('금요일' == $user_meta_value || '금요일' == $default_value):?> selected<?php endif?>>금요일</option>
		</select>
	</div>
</div>
<?php elseif($field_type == 'agree'):?>
<div class="checkout-attr-row meta-key-agree title-<?php echo substr(md5($field['label']), 0, 10)?>">
	<div class="attr-value">
		<h5 class="agree-title"><?php echo esc_html($field['label'])?></h5>
		<div class="agree-content"><?php echo wpautop($field['data'])?></div>
		<label><input type="checkbox" name="agree" value="1" required> <?php echo sprintf(__('I agree to %s.', 'cosmosfarm-members'), esc_html($field['label']))?> <span class="required">*</span></label>
	</div>
</div>
<?php elseif($field_type == 'hr'):?>
<hr>
<?php elseif($field_type == 'hidden'):?>
<input type="hidden" id="cosmosfarm_members_subscription_checkout_<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($field['user_meta_key'] ? $user->{$field['user_meta_key']} : $default_value)?>">
<?php elseif($field_type == 'nice_billing'):?>
<div class="checkout-attr-row meta-key-card-number">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_card_number">신용카드번호 <span class="required">*</span></label>
	<div class="attr-value">
		<input type="number" id="cosmosfarm_members_subscription_checkout_card_number" name="cosmosfarm_members_subscription_checkout_card_number" value="" maxlength="16" size="16" required>
		<div class="description">신용카드번호 숫자만 입력해주세요.</div>
	</div>
</div>
<div class="checkout-attr-row meta-key-card-expiry">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_expiry">만료일 <span class="required">*</span></label>
	<div class="attr-value">
		<select id="cosmosfarm_members_subscription_checkout_expiry" name="cosmosfarm_members_subscription_checkout_expiry_month" class="width-auto" required>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
		</select>
		월
		<select name="cosmosfarm_members_subscription_checkout_expiry_year" class="width-auto" required>
			<?php for($year=date('Y', current_time('timestamp')); $year<=(date('Y', current_time('timestamp'))+20); $year++):?>
			<option value="<?php echo $year?>"><?php echo $year?></option>
			<?php endfor?>
		</select>
		년
	</div>
</div>
<div class="checkout-attr-row meta-key-pwd-2digit">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_pwd_2digit">비밀번호 앞 2자리 <span class="required">*</span></label>
	<div style="overflow:hidden;width:0;height:0;">
		<input style="width:0;height:0;background:transparent;color:transparent;border:none;" type="text" name="fake-autofill-fields">
		<input style="width:0;height:0;background:transparent;color:transparent;border:none;" type="password" name="fake-autofill-fields">
	</div>
	<div class="attr-value">
		<input type="password" id="cosmosfarm_members_subscription_checkout_pwd_2digit" name="cosmosfarm_members_subscription_checkout_pwd_2digit" value="" maxlength="2" size="2" autocomplete="new-password" required>
		<div class="description">신용카드 비밀번호의 앞 2자리를 입력해주세요.</div>
	</div>
</div>
<div class="checkout-attr-row meta-key-birth-business">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_birth_or_business_license">생년월일6자리 또는 사업자등록번호 <span class="required">*</span></label>
	<div class="attr-value">
		<input type="number" id="cosmosfarm_members_subscription_checkout_birth_or_business_license" name="cosmosfarm_members_subscription_checkout_birth" value="" maxlength="10" size="10" required>
		<div class="description">개인카드는 생년월일 6자리, 법인카드는 사업자등록번호 10자리를 입력해주세요.</div>
	</div>
</div>
<?php elseif($field_type == 'billing_agree'):?>
<div class="checkout-attr-row meta-key-billing-agree">
	<div class="attr-value">
		<label><input type="checkbox" name="agree" value="1" required> <?php echo __('I have confirmed my payment terms and I agree to proceed with the purchase.', 'cosmosfarm-members')?> <span class="required">*</span></label>
	</div>
</div>
<?php elseif($field_type == 'sign_up'):?>
<div class="checkout-attr-row meta-key-sign-up-id">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_sign_up_id"><?php echo $option->allow_email_login ? __('Email', 'cosmosfarm-members') : __('Username', 'cosmosfarm-members')?> <span class="required">*</span></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_checkout_sign_up_id" name="sign_up_id" value="" required>
	</div>
</div>
<div class="checkout-attr-row meta-key-sign-up-pw">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_sign_up_pw"><?php echo __('Password', 'cosmosfarm-members')?> <span class="required">*</span></label>
	<div class="attr-value">
		<input type="password" id="cosmosfarm_members_subscription_checkout_sign_up_pw" name="sign_up_pw" value="" required>
	</div>
</div>
<div class="checkout-attr-row meta-key-sign-up-button">
	<button type="button" onclick="cosmosfarm_members_subscription_sign_up(this.form)"><?php echo __('Register', 'cosmosfarm-members')?></button>
	<?php echo __('or', 'cosmosfarm-members')?> <a href="<?php echo wp_login_url($_SERVER['REQUEST_URI'])?>"><?php echo __('Log In', 'cosmosfarm-members')?></a>
</div>
<?php elseif($field_type == 'payment_method'):?>
<script>
var cosmosfarm_members_subscription_iamport_pg_list = <?php echo json_encode(get_cosmosfarm_members_subscription_iamport_pg_list())?>;
</script>
<div class="checkout-attr-row meta-key-payment-method">
	<label class="attr-name" for="cosmosfarm_members_subscription_checkout_payment_method"><?php echo __('Payment method', 'cosmosfarm-members')?> <span class="required">*</span></label>
	<div class="attr-value">
		<select id="cosmosfarm_members_subscription_checkout_payment_method" name="payment_method">
			<?php foreach(get_cosmosfarm_members_subscription_iamport_pg_list() as $key=>$item):?>
			<option value="<?php echo esc_attr($key)?>"><?php echo esc_html($item->title)?></option>
			<?php endforeach?>
		</select>
	</div>
</div>
<?php elseif($field_type == 'coupon_code_enter'):?>
<div class="checkout-attr-row meta-key-coupon-code-enter">
	<label class="attr-name" for="cosmosfarm_members_subscription_coupon_code"><?php echo __('Coupon code', 'cosmosfarm-members')?></label>
	<div class="attr-value">
		<input type="text" id="cosmosfarm_members_subscription_coupon_code" name="cosmosfarm_members_subscription_coupon_code" value="">
	</div>
</div>
<div class="checkout-attr-row meta-key-coupon-code-button">
	<button type="button" onclick="cosmosfarm_members_subscription_apply_coupon(this.form)"><?php echo __('Apply coupon', 'cosmosfarm-members')?></button>
</div>
<?php elseif($field_type == 'coupon_code_remove'):?>
<div class="checkout-attr-row meta-key-coupon-code-remove-button">
	<input type="hidden" id="cosmosfarm_members_subscription_coupon_code" name="cosmosfarm_members_subscription_coupon_code" value="">
	<button type="button" onclick="cosmosfarm_members_subscription_apply_coupon(this.form)"><?php echo __('Remove coupon', 'cosmosfarm-members')?></button>
</div>
<?php endif?>