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
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>" onsubmit="return cosmosfarm_members_coupon_submit(this);">
		<?php wp_nonce_field('cosmosfarm-members-coupon-save', 'cosmosfarm-members-coupon-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_coupon_save">
		<input type="hidden" name="coupon_id" value="<?php echo $coupon_id?>">
		
		<h3>쿠폰 내용</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="coupon_title">쿠폰 이름</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="coupon_title" name="coupon_title" class="regular-text" value="<?php echo $coupon->title()?>" required>
						<p class="description">쿠폰의 이름을 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_code">쿠폰 코드</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="coupon_code" name="coupon_code" class="regular-text" value="<?php echo $coupon->content()?>" required>
						<button type="button" class="button" onclick="cosmosfarm_members_coupon_code_generator()">쿠폰 코드 생성</button>
						<p class="description">쿠폰의 코드를 공백 없이 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_active">사용 가능 상태</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="coupon_active" name="coupon_active" required>
							<option value="1"<?php if($coupon->active() == '1'):?> selected<?php endif?>>사용 가능</option>
							<option value=""<?php if($coupon->active() == ''):?> selected<?php endif?>>만료됨</option>
						</select>
						<p class="description">쿠폰의 사용 가능 상태를 선택해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_usage_limit">최대 사용 횟수</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<input type="number" id="coupon_usage_limit" name="coupon_usage_limit" class="regular-text" value="<?php echo $coupon->usage_limit()?>" placeholder="무제한">
						<p class="description">쿠폰의 최대 사용 횟수를 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_usage_count">현재까지의 사용 횟수</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<input type="number" id="coupon_usage_count" name="coupon_usage_count" class="regular-text" value="<?php echo $coupon->usage_count()?>" placeholder="0">
						<p class="description">쿠폰 사용 횟수는 쿠폰이 사용될 때 자동으로 입력됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_usage_date">사용 기간</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="coupon_usage_date" name="coupon_usage_date" onchange="cosmosfarm_members_coupon_usage_date(this.value)">
							<option value="continue"<?php if($coupon->usage_date() == 'continue'):?> selected<?php endif?>>무제한</option>
							<option value="date_select"<?php if($coupon->usage_date() == 'date_select'):?> selected<?php endif?>>기간선택</option>
						</select>
						
						<span class="coupon-usage-date"<?php if($coupon->usage_date() != 'date_select'):?> style="display: none;"<?php endif?>>
							<input type="text" id="coupon_usage_start_date" name="coupon_usage_start_date" class="datepicker" value="<?php echo $coupon->usage_start_date() ? $coupon->usage_start_date() : date('Y-m-d', current_time('timestamp'))?>" onchange="cosmosfarm_members_coupon_usage_start_date(this.value)" placeholder="시작일" readonly> ~
							<input type="text" id="coupon_usage_end_date" name="coupon_usage_end_date" class="datepicker" value="<?php echo $coupon->usage_end_date() ? $coupon->usage_end_date() : date('Y-m-d', strtotime('+1 month', current_time('timestamp')))?>" onchange="cosmosfarm_members_coupon_usage_end_date(this.value)" placeholder="종료일" readonly>
						</span>
						
						<p class="description">쿠폰의 사용 기간을 선택해주세요.</p>
						<p class="description">설정을 변경하면 정기결제에 영향을 줄 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_discount">적용 기준</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="coupon_discount" name="coupon_discount" onchange="cosmosfarm_members_coupon_discount(this.value)" required>
							<option value="amount"<?php if($coupon->discount() == 'amount'):?> selected<?php endif?>>할인금액</option>
							<option value="rate"<?php if($coupon->discount() == 'rate'):?> selected<?php endif?>>할인율</option>
						</select>
						<p class="description">쿠폰의 적용 기준을 선택해주세요.</p>
						<p class="description">설정을 변경하면 정기결제에 영향을 줄 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_discount_amount"><span class="coupon-discount-text"><?php if(!$coupon->discount() || $coupon->discount() == 'amount'):?>할인금액<?php else:?>할인율<?php endif?></span></label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="number" id="coupon_discount_amount" name="coupon_discount_amount" class="regular-text" value="<?php echo $coupon->discount_amount()?>" onkeyup="cosmosfarm_members_coupon_discount_amount(this)" required>
						<p class="description"><span class="coupon-discount-text"><?php if(!$coupon->discount() || $coupon->discount() == 'amount'):?>할인금액<?php else:?>할인율<?php endif?></span>을 입력해주세요.</p>
						<p class="description">쿠폰 적용 후 최종 결제 가격이 0원이 되지 않도록 조절해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="coupon_discount_cycle">적용 주기</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="coupon_discount_cycle" name="coupon_discount_cycle" required>
							<option value="once"<?php if($coupon->discount_cycle() == 'once'):?> selected<?php endif?>>첫 결제만 적용</option>
							<option value="subscription"<?php if($coupon->discount_cycle() == 'subscription'):?> selected<?php endif?>>모든 정기결제 적용</option>
						</select>
						<p class="description">쿠폰의 할인 적용 주기를 선택해주세요.</p>
						<p class="description">설정을 변경하면 정기결제에 영향을 줄 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label>적용 상품</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<?php if($product_list):?>
							<?php foreach($product_list as $product):?>
							<label style="margin-right:10px;"><input type="checkbox" name="coupon_product_id[]" value="<?php echo $product->ID?>"<?php if(in_array($product->ID, $coupon->product_ids())):?> checked<?php endif?>> <?php echo esc_html($product->post_title)?></label>
							<?php endforeach?>
						<?php else:?>
						상품이 없습니다.
						<?php endif?>
						<p class="description">선택된 상품에 쿠폰 코드가 입력되면 할인이 적용됩니다.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
	</form>
	
	<ul class="cosmosfarm-members-news-list">
		<?php
		foreach(get_cosmosfarm_members_news_list() as $news_item):?>
		<li>
			<a href="<?php echo esc_url($news_item->url)?>" target="<?php echo esc_attr($news_item->target)?>" style="text-decoration:none"><?php echo esc_html($news_item->title)?></a>
		</li>
		<?php endforeach?>
	</ul>
</div>
<div class="clear"></div>

<script>
jQuery(document).ready(function(){
	jQuery('.datepicker').datepicker({
		closeText : '닫기',
		prevText : '이전달',
		nextText : '다음달',
		currentText : '오늘',
		monthNames : [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
		monthNamesShort : [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
		dayNames : [ '일', '월', '화', '수', '목', '금', '토' ],
		dayNamesShort : [ '일', '월', '화', '수', '목', '금', '토' ],
		dayNamesMin : [ '일', '월', '화', '수', '목', '금', '토' ],
		weekHeader : 'Wk',
		dateFormat : 'yy-mm-dd',
		firstDay : 0,
		isRTL : false,
		duration : 0,
		showAnim : 'show',
		showMonthAfterYear : true,
		yearSuffix : '년',
		changeYear:true,
		changeMonth:true
	}); 
});

Date.prototype.yyyymmdd = function() {
	var yyyy = this.getFullYear().toString();
	var mm = (this.getMonth() + 1).toString();
	var dd = this.getDate().toString();
	return  yyyy + "-" + (mm[1] ? mm : '0' + mm[0]) + '-' + (dd[1] ? dd : '0' + dd[0]);
}

function cosmosfarm_members_coupon_code_generator(){
	var coupon_code = '';
	var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var characters_length = characters.length;
	var length = 8;
	
	for(var i = 0; i<length; i++){
		coupon_code += characters.charAt(Math.floor(Math.random() * characters_length));
	}

	jQuery('#coupon_code').val(coupon_code.toUpperCase());
}

function cosmosfarm_members_coupon_usage_date(value){
	if(value == 'continue'){
		jQuery('.coupon-usage-date').hide();
	}
	else{
		jQuery('.coupon-usage-date').show();
	}
}

function cosmosfarm_members_coupon_usage_start_date(start_date){
	if(!jQuery('#coupon_usage_end_date').val()){
		start_date = start_date.split('-');
		var end_date = new Date(parseInt(start_date[0]), parseInt(start_date[1])-1, parseInt(start_date[2]));
		end_date.setMonth(end_date.getMonth()+1);
		end_date.setDate(end_date.getDate()-1);
		
		jQuery('#coupon_usage_end_date').val(end_date.yyyymmdd());
	}
}

function cosmosfarm_members_coupon_usage_end_date(end_date){
	var start_date = jQuery('#coupon_usage_start_date').val();
	if(start_date > end_date){
		alert('종료일은 시작일보다 이전으로 설정할 수 없습니다.');

		start_date = start_date.split('-');
		var end_date = new Date(parseInt(start_date[0]), parseInt(start_date[1])-1, parseInt(start_date[2]));
		end_date.setMonth(end_date.getMonth()+1);
		end_date.setDate(end_date.getDate()-1);
		
		jQuery('#coupon_usage_end_date').val(end_date.yyyymmdd());
	}
}

function cosmosfarm_members_coupon_discount(value){
	if(value == 'amount'){
		jQuery('.coupon-discount-text').text('할인금액');
	}
	else{
		jQuery('.coupon-discount-text').text('할인율');
	}

	jQuery('#coupon_discount_amount').val('');
}

function cosmosfarm_members_coupon_discount_amount(obj){
	var value = jQuery(obj).val();
	var discount = jQuery('#coupon_discount').val();

	if(discount == 'rate' && value > 100){
		jQuery(obj).val(100);
	}
	
	if(value < 0){
		jQuery(obj).val(0);
	}
		
}

function cosmosfarm_members_coupon_submit(form){
	if(jQuery('#coupon_usage_date').val() == 'date_select'){
		if(!jQuery('#coupon_usage_start_date').val()){
			alert('시작일을 선택해주세요.');
			jQuery('#coupon_usage_start_date').focus();

			return false;
		}
		if(!jQuery('#coupon_usage_end_date').val()){
			alert('종료일을 선택해주세요.');
			jQuery('#coupon_usage_end_date').focus();

			return false;
		}
	}
	
	return true;
}
</script>

<?php
wp_enqueue_style('jquery-flick-style', COSMOSFARM_MEMBERS_URL . '/assets/css/jquery-ui.css', array(), '1.12.1');
?>