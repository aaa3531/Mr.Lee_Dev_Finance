<?php if(!defined('ABSPATH')) exit;?>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1 class="wp-heading-inline">알림톡 템플릿 추가하기</h1>
	<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
	<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
	<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
	<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	<p>코스모스팜 회원관리는 한국형 회원가입 레이아웃과 기능을 제공합니다.</p>
	
	<hr class="wp-header-end">
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-alimtalk-template-save', 'cosmosfarm-members-alimtalk-template-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_alimtalk_template_save">
		
		<h3>알림톡 템플릿 설정</h3>
		<div class="alimtalk-template-wrap">
			<div class="alimtalk-template-left">
				<table class="form-table">
					<tbody>
						<?php $template_code = isset($_GET['template_code'])&&$_GET['template_code'] ? $_GET['template_code'] : ''?>
						<tr valign="top">
							<th scope="row">템플릿명</th>
							<td>
								<input type="text" name="cosmosfarm_members_alimtalk_template[<?php echo $template_code?>][name]" class="regular-text" value="Template <?php echo date('Y-m-d', current_time('timestamp'))?>" required>
								<p class="description">템플릿명은 중복되지 않게 입력해주세요.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">템플릿 코드</th>
							<td><?php echo $template_code?></td>
						</tr>
					<?php if($result['result'] == 'success'):?>
						<?php foreach($result['templates'] as $index=>$template):?>
							<?php if($template_code == $template->templateCode):?>
							<?php preg_match_all('/#{(.*?)}/', $template->templateContent, $match)?>
							<tr valign="top">
								<th scope="row">템플릿 내용</th>
								<td><?php echo nl2br($template->templateContent)?></td>
							</tr>
							<tr valign="top">
								<th scope="row">템플릿 치환 정보</th>
								<td>
								<?php if(isset($match[1])&&$match[1]):?>
									<?php foreach($match[1] as $key=>$replace):?>
									<div class="template-row">
										<div class="regular-text template-replace" data-index="<?php echo $key?>"><span class="replace"><?php echo $replace?></span><span class="replace-field"></span></div>
										<span class="field-name"></span>
										<input type="hidden" name="cosmosfarm_members_alimtalk_template[<?php echo $template->templateCode?>][key][<?php echo $key?>]" value="<?php echo $replace?>">
										<input type="hidden" name="cosmosfarm_members_alimtalk_template[<?php echo $template->templateCode?>][value][<?php echo $key?>]" value="">
										<input type="hidden" name="cosmosfarm_members_alimtalk_template[<?php echo $template->templateCode?>][type][<?php echo $key?>]" value="">
										<input type="hidden" name="cosmosfarm_members_alimtalk_template[<?php echo $template->templateCode?>][type_label][<?php echo $key?>]" value="">
									</div>
									<?php endforeach?>
									<p class="description">각 요소를 누르면 템플릿 치환값을 설정할 수 있습니다.</p>
								<?php endif?>
								</td>
							</tr>
							<?php endif?>
						<?php endforeach?>
					<?php else: echo $result['message']?>
					<?php endif?>
					</tbody>
				</table>
			</div>
			<?php if(isset($match[1])&&$match[1]):?>
			<div class="alimtalk-template-right" style="display: none;">
				<div class="categorydiv">
					<input type="hidden" id="cosmosfarm-members-alimtalk-template-index" value="">
					<ul id="category-tabs" class="category-tabs">
						<li class="alimtalk-template-field tabs" data-anchor="user-info"><a href="#" onclick="return false;">사용자</a></li>
						<li class="alimtalk-template-field hide-if-no-js" data-anchor="cosmosfarm-members-product"><a href="#" onclick="return false;">정기결제 상품</a></li>
						<li class="alimtalk-template-field hide-if-no-js" data-anchor="woocommerce-product"><a href="#" onclick="return false;">우커머스 상품</a></li>
					</ul>
			
					<div id="user-info" class="tabs-panel">
						<ul class="categorychecklist">
							<?php foreach(wpmem_fields() as $field_key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<li>
									<label><input value="<?php echo $field_key?>" type="radio" name="radio"><span class="field-label"><?php echo $field['label']?></span></label>
								</li>
							<?php endforeach?>
						</ul>
					</div>
					
					<div id="cosmosfarm-members-product" class="tabs-panel" style="display: none;">
						<ul class="categorychecklist">
							<li><label><input value="product_title" type="radio" name="radio"> <span class="field-label">상품 이름</span></label></li>
							<li><label><input value="product_price" type="radio" name="radio"> <span class="field-label">가격</span></label></li>
							<li><label><input value="product_first_price" type="radio" name="radio"> <span class="field-label">첫 결제 가격</span></label></li>
							<li><label><input value="product_subscription_type" type="radio" name="radio"> <span class="field-label">이용기간</span></label></li>
						</ul>
					</div>
					
					<div id="woocommerce-product" class="tabs-panel" style="display: none;">
						<ul class="categorychecklist">
							<li><label><input value="wc_product_title" type="radio" name="radio"> <span class="field-label">상품 이름</span></label></li>
							<li><label><input value="wc_product_price" type="radio" name="radio"> <span class="field-label">가격</span></label></li>
							<li><label><input value="wc_product_quantity" type="radio" name="radio"> <span class="field-label">수량</span></label></li>
						</ul>
					</div>
					
					<button type="button" class="button" onclick="cosmosfarm_members_alimtalk_template_set_complete()">선택완료</button>
				</div>
			</div>
			<?php endif?>
		</div>
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
	</form>
</div>
<div class="clear"></div>

<script>
var template_code = '<?php echo $template_code?>';

jQuery(document).ready(function(){
	jQuery('.alimtalk-template-field').click(function(){
		jQuery('.alimtalk-template-field').removeClass('tabs');
		jQuery('.alimtalk-template-field').addClass('hide-if-no-js');
		jQuery(this).addClass('tabs');

		var tabs_panel = jQuery(this).data('anchor');
		jQuery('.tabs-panel').hide();
		jQuery('#' + tabs_panel).show();
	});

	jQuery('.template-replace', '.alimtalk-template-left .template-row').click(function(){
		jQuery('.template-replace').removeClass('selected');
		jQuery(this).addClass('selected');
		
		jQuery('.alimtalk-template-right').show();

		jQuery('#cosmosfarm-members-alimtalk-template-index').val(jQuery(this).data('index'));
	});
});

function cosmosfarm_members_alimtalk_template_set_complete(){
	if(jQuery('input[type="radio"]:checked', '.categorychecklist').length){
		var value = jQuery('input[type="radio"]:checked', '.categorychecklist').val();
		var field_label = jQuery('input[type="radio"]:checked', '.categorychecklist').siblings('.field-label').text();
		var type = jQuery('input[type="radio"]:checked', '.categorychecklist').parents('.tabs-panel').attr('id');
		
		jQuery('.template-replace.selected .replace-field').html('<code>'+field_label+'</code>');

		var index = jQuery('#cosmosfarm-members-alimtalk-template-index').val();
		if(index !== ''){
			jQuery('input[name="cosmosfarm_members_alimtalk_template['+template_code+'][value]['+index+']"]').val(value);
			jQuery('input[name="cosmosfarm_members_alimtalk_template['+template_code+'][type]['+index+']"]').val(type);
			jQuery('input[name="cosmosfarm_members_alimtalk_template['+template_code+'][type_label]['+index+']"]').val(field_label);
		}
	}
	
	jQuery('.template-replace').removeClass('selected');
	jQuery('.alimtalk-template-right').hide();

	jQuery('input[type="radio"]:checked', '.categorychecklist').prop('checked', false);
}
</script>