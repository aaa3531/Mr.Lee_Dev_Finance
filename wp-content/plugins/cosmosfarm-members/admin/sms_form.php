<?php if(!defined('ABSPATH')) exit;?>
<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>SMS 보내기</title>
	<?php $wp_scripts->print_scripts('jquery')?>
	<style>
	html, body { margin: 0; padding: 0; width: 1px; min-width: 100%; *width: 100%; background-color: white; }
	form { margin: 0; padding: 0; }
	label { font-size: 15px; display: block; width: 100%; color: black; }
	input, select { margin: 0; padding: 6px; width: 100%; border: 0; background-color: #f7f7f7; box-sizing: border-box; }
	textarea { margin: 0; padding: 6px; width: 100%; border: 0; background-color: #f7f7f7; box-sizing: border-box; }
	button { padding: 6px 12px; border: #2e71f2; background-color: #2e71f2; color: white; cursor: pointer; }
	#cosmosfarm-members-sms-form { margin: 0 auto; padding: 5px 5px 0 5px; }
	#cosmosfarm-members-sms-form input[type="checkbox"] { width: auto; }
	#cosmosfarm-members-sms-form .every { display: inline; }
	.row { margin: 0 0 15px 0; }
	.center { text-align: center; }
	.message { font-size: 14px; color: gray; }
	.sms-every-phone-number-field { display: none; }
	</style>
</head>
<body>
<div id="cosmosfarm-members-sms-form">
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-sms-send', 'cosmosfarm-members-sms-send-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_sms_send">
		<input type="hidden" name="service" value="<?php echo $service?>">
		
		<?php if($user && $user->ID):?>
		<input type="hidden" name="user_id" value="<?php echo $user->ID?>">
		<div class="row">
			<label>받는사람</label>
			<?php echo $user->display_name?>(<?php echo $user->user_login?>)
			<br>
			<select name="meta" onchange="kboard_phone_update(this.value)">
				<option value="">등록된 번호 선택</option>
				<?php
				$all_meta_for_user = get_user_meta($user->ID);
				foreach($all_meta_for_user as $key=>$value):
				if(strpos($key, 'tel') === false && strpos($key, 'phone') === false && strpos($key, 'fax') === false) continue;
					if(is_array($value)) $value = reset($value);
					if(!$value) continue;
					?>
					<option value="<?php echo $value?>"><?php echo $key?>(<?php echo $value?>)</option>
				<?php endforeach?>
			</select>
		</div>
		<?php endif?>
		
		<div class="row">
			<label for="cosmosfarm-members-sms-phone">단건 발송 휴대폰번호 입력</label>
			<input name="phone" id="cosmosfarm-members-sms-phone" placeholder="휴대폰번호를 입력해주세요." autofocus>
		</div>
		
		<div class="row">
			<label class="every" for="cosmosfarm-members-sms-every">대량 발송 <input type="checkbox" name="every" id="cosmosfarm-members-sms-every" value="1" onchange="cosmosfarm_phone_number_field_toggle()"></label>
			<div class="message">SMS 발송 전 잔여건수를 반드시 확인해주세요.<br>
			잔여 건수가 부족하면 정상적으로 SMS 발송이 되지 않거나 일부 사용자만 받아볼 수 있습니다.<br>
			대량 발송 체크박스가 체크되어 있다면 SMS를 대량으로 발송합니다.
			</div>
		</div>
		
		<div class="sms-every-phone-number-field">
			<div class="row">
				<label for="cosmosfarm-members-sms-phone-field">대량 발송 휴대폰번호 필드 선택</label>
				<select name="phone_field" id="cosmosfarm-members-sms-phone-field">
					<option value="">사용안함</option>
					<?php foreach(wpmem_fields() as $key=>$field):?>
						<?php if($field['type'] != 'text') continue?>
						<?php if(!$field['register']) continue?>
						<option value="<?php echo $key?>"><?php echo $field['label']?></option>
					<?php endforeach?>
				</select>
				
				<label class="message">대량 SMS 발송을 하시려면 반드시 선택해주세요.</label>
			</div>
		</div>
		
		<?php if($service == 'alimtalk'):?>
		<div class="row">
			<label for="cosmosfarm-members-alimtalk-template-name">알림톡 템플릿*</label>
			<?php if($option->alimtalk_template):?>
			<select id="cosmosfarm-members-alimtalk-template-name" name="template_code">
				<?php foreach($option->alimtalk_template as $item):?>
				<option value="<?php echo $item['template_code']?>"><?php echo $item['name']?></option>
				<?php endforeach?>
			</select>
			<?php else:?>
			<div class="message">등록된 템플릿이 없습니다.<br>
			<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_alimtalk_template_setting')?>" target="_blank">알림톡 템플릿 설정</a> 페이지에서 등록할 수 있습니다.</div>
			<?php endif?>
		</div>
		<?php else:?>
		<div class="row">
			<label for="cosmosfarm-members-sms-content">내용*</label>
			<textarea name="content" id="cosmosfarm-members-sms-content" rows="10" placeholder="내용을 입력해주세요." required></textarea>
		</div>
		
		<div class="row">
			<div class="message">현재 길이는 <span class="total-length">0</span>byte 입니다.<br>
			90byte가 넘으면 내용이 짤리거나 LMS로 전송될 수 있으니 90byte 이내로 작성해주세요.<br>
			단문(SMS) : 최대 90byte까지 전송할 수 있으며, 잔여건수 1건이 차감됩니다.<br>
			장문(LMS) : 한번에 최대 2,000byte까지 전송할 수 있으며 1회 발송당 잔여건수 3건이 차감됩니다.</div>
		</div>
		<?php endif?>
		
		<div class="row center controlbar">
			<button type="submit" class="button">전송</button>
		</div>
	</form>
</div>
<script>
String.prototype.bytes = function(){
	var msg = this;
	var cnt = 0;
	for(var i=0; i<msg.length; i++){
		cnt += (msg.charCodeAt(i) > 128) ? 2 : 1;
	}
	return cnt;
}
jQuery(document).ready(function(res){
	jQuery('[name=content]').keyup(function(e){
		jQuery('.total-length').text(jQuery(this).val().bytes());
	});
	if(jQuery('.total-length').length){
		jQuery('.total-length').text(jQuery('[name=content]').val().bytes());
	}

	if(jQuery('#cosmosfarm-members-sms-every').prop('checked')){
		jQuery('.sms-every-phone-number-field').show();
	}
});
function kboard_phone_update(phone){
	jQuery('input[name=phone]').val(phone);
}
function cosmosfarm_phone_number_field_toggle(){
	jQuery('.sms-every-phone-number-field').toggle();
}
</script>
</body>
</html>