<?php if(!defined('ABSPATH')) exit;?>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1 class="wp-heading-inline">코스모스팜 회원관리</h1>
	<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
	<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
	<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
	<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	<p>코스모스팜 회원관리는 한국형 회원가입 레이아웃과 기능을 제공합니다.</p>
	
	<hr class="wp-header-end">
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-bulk-sms-save', 'cosmosfarm-members-bulk-sms-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_bulk_sms_save">
		
		<table class="form-table">
			<tbody>
				<?php if($option->sms_service):?>
				<tr valign="top">
					<th scope="row"><label>SMS 서비스</label></th>
					<td>
						<code>
							<?php if($option->sms_service == 'cafe24'):?>
							카페24
							<?php elseif($option->sms_service == 'toast_cloud'):?>
							토스트 클라우드
							<?php endif?>
						</code>
						<p class="description"><a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a> 페이지에서 설정하실 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bulk-sms-field">휴대폰 번호 필드</label></th>
					<td>
						<select id="bulk-sms-field" name="cosmosfarm_members_subnotify_sms_field">
							<option value="">선택</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($key == $option->subnotify_sms_field):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">대량문자 발송 시 참조할 휴대폰 번호 필드를 선택해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bulk-sms-roles">역할 선택</label></th>
					<td>
						<div class="cosmosfarm-members-bulk-sms-roles-view">
							<?php foreach(get_editable_roles() as $roles_key=>$roles_value):?>
								<label><input type="checkbox" name="cosmosfarm_members_bulk_sms_permission_roles[]" value="<?php echo $roles_key?>"<?php if(in_array($roles_key, $option->bulk_sms_permission_roles)):?> checked<?php endif?>> <?php echo _x($roles_value['name'], 'User role')?></label>
							<?php endforeach?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bulk-sms-content">SMS 내용</label></th>
					<td>
						<textarea id="bulk-sms-content" name="cosmosfarm_members_bulk_sms_content" rows="20" class="large-text"><?php echo $option->bulk_sms_content?></textarea>
						<p class="description">현재 길이는 <b class="total-length">0</b>byte 입니다.</p>
						<?php if($option->sms_service == 'cafe24'):?>
						<p class="description">
							90byte가 넘으면 내용이 짤리거나 LMS로 전송될 수 있으니 90byte 이내로 작성해주세요.<br>
							단문(SMS) : 최대 90byte까지 전송할 수 있으며, 잔여건수 1건이 차감됩니다.<br>
							장문(LMS) : 한번에 최대 2,000byte까지 전송할 수 있으며 1회 발송당 잔여건수 3건이 차감됩니다.
						</p>
						<?php elseif($option->sms_service == 'toast_cloud'):?>
						<p class="description">
							90byte가 넘으면 내용이 짤리거나 LMS로 전송될 수 있으니 90byte 이내로 작성해주세요.<br>
							단문(SMS) : 최대 90byte까지 전송할 수 있으며, 수신 1건당 9.9원이 청구됩니다.<br>
							장문(LMS) : 한번에 최대 2,000byte까지 전송할 수 있으며 수신 1건당 30원이 청구됩니다.
						</p>
						<?php endif?>
						<p class="submit">
							<input type="button" class="button-primary" value="보내기" onclick="cosmosfarm_members_bulk_sms_send(this.form)">
						</p>
					</td>
				</tr>
				<?php else:?>
				<tr valign="top">
					<th scope="row"><label>SMS 서비스</label></th>
					<td>
						<p class="description" style="margin-top:0;">SMS 서비스가 설정되어 있지 않습니다.<br>
						<a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a>이 되어있어야 합니다.</p>
					</td>
				</tr>
				<?php endif?>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
		
		<ul class="cosmosfarm-members-news-list">
			<?php
			foreach(get_cosmosfarm_members_news_list() as $news_item):?>
			<li>
				<a href="<?php echo esc_url($news_item->url)?>" target="<?php echo esc_attr($news_item->target)?>" style="text-decoration:none"><?php echo esc_html($news_item->title)?></a>
			</li>
			<?php endforeach?>
		</ul>
	</form>
</div>
<div class="clear"></div>

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
	jQuery('[name="cosmosfarm_members_bulk_sms_content"]').keyup(function(e){
		jQuery('.total-length').text(jQuery(this).val().bytes());
	});
	if(jQuery('.total-length').length){
		jQuery('.total-length').text(jQuery('[name="cosmosfarm_members_bulk_sms_content"]').val().bytes());
	}
});

function cosmosfarm_members_bulk_sms_send(form){
	var sms_field = jQuery('#bulk-sms-field', form).val();
	var sms_content = jQuery('#bulk-sms-content', form).val();
	var sms_roles = [];
	
	jQuery('input[name="cosmosfarm_members_bulk_sms_permission_roles[]"]:checked', form).each(function(index, elementor){
		sms_roles.push(jQuery(this).val());
	});

	if(confirm('대량문자를 발송하시겠습니까?')){
		jQuery.post(ajaxurl, {action:'cosmosfarm_members_bulk_sms_send', sms_field:sms_field, sms_roles:sms_roles, sms_content:sms_content, security:cosmosfarm_members_admin_settings.ajax_nonce}, function(res){
			if(res.message){
				alert(res.message);
			}
		});
	}
}
</script>