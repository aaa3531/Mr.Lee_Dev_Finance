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
	
	<h3>알림톡 템플릿 설정</h3>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">알림톡</th>
				<td>
					<p class="description">템플릿은 토스트 클라우드 콘솔(Console)에서 등록할 수 있으며 승인된 템플릿만 표시됩니다.</p>
					<p class="description">
						<a href="https://docs.toast.com/ko/Notification/KakaoTalk%20Bizmessage/ko/alimtalk-console-guide/#_8" target="_blank">템플릿 등록 방법</a>
					</p>
					<?php if(!$fields):?>
					<br>
					<p class="description">사용 가능한 템플릿이 없습니다.</p>
					<?php endif?>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php if($result['result'] == 'success'):?>
		<?php if(isset($result['templates'])&&$result['templates']):?>
			<?php foreach($result['templates'] as $template): preg_match_all('/#{(.*?)}/', $template->templateContent, $match);?>
			<h3><?php echo $template->templateCode?></h3>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">템플릿 코드</th>
						<td>
							<?php echo $template->templateCode?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">템플릿 내용</th>
						<td>
							<p class="description">
								<?php echo nl2br($template->templateContent)?>
							</p>
							<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_alimtalk_template_setting&action=alimtalk_template_new&template_code='.$template->templateCode)?>" class="button">템플릿 추가하기</a>
						</td>
					</tr>
				</tbody>
			</table>
			<?php endforeach?>
		<?php endif?>
	<?php else: echo $result['message']?>
	<?php endif?>
	
	<?php if($fields):?>
	<h3>사용 가능한 템플릿 목록</h3>
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-alimtalk-template-save', 'cosmosfarm-members-alimtalk-template-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_alimtalk_template_save">
		<input type="hidden" name="delete" value="1">
		<input type="hidden" name="field_index" value="">

		<table class="widefat striped">
			<thead>
				<tr valign="top">
					<th>템플릿명</th>
					<th>템플릿 정보</th>
					<th>동작</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($fields as $field_index=>$field):?>
				<tr valign="top">
					<th>
						<div><?php echo $field['name']?></div>
					</th>
					<td>
						<div>템플릿 코드 - <?php echo $field['template_code']?></div>
						<?php foreach($field['key'] as $index=>$key):?>
						<div>
							#{<?php echo $field['key'][$index]?>} -
							<?php echo $field['type_label'][$index]?>
							<?php if($field['type'][$index] == 'user-info'):?>(사용자)<?php endif?>
							<?php if($field['type'][$index] == 'cosmosfarm-members-product'):?>(정기결제)<?php endif?>
							<?php if($field['type'][$index] == 'woocommerce-product'):?>(우커머스)<?php endif?>
						</div>
						<?php endforeach?>
					</td>
					<td>
						<button type="submit" class="button" onclick="return cosmosfarm_members_alimtalk_delete('<?php echo $field_index?>')">템플릿 삭제하기</button>
					</td>
				</tr>
				<?php endforeach?>
			</tbody>
		</table>
	</form>
	<?php endif?>
</div>
<div class="clear"></div>

<script>
function cosmosfarm_members_alimtalk_delete(index){
	var remove = confirm('정말 삭제하시겠습니까?');
	if(remove){
		jQuery('input[name="field_index"]').val(index);
		return true;
	}
	return false;
}
</script>