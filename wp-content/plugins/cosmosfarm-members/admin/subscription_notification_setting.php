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
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>" onsubmit="return cosmosfarm_members_notification_submit(this);">
		<?php wp_nonce_field('cosmosfarm-members-subnoti-save', 'cosmosfarm-members-subnoti-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_subnoti_save">
		<input type="hidden" name="notification_id" value="<?php echo $notification_id?>">
		
		<h3>알림 내용</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="notification_title">알림 이름</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="notification_title" name="notification_title" class="regular-text" value="<?php echo $notification->title()?>" required>
						<p class="description">알림의 이름을 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="notification_active">사용 가능 상태</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="notification_active" name="notification_active" required>
							<option value="1"<?php if($notification->active() == '1'):?> selected<?php endif?>>사용</option>
							<option value=""<?php if($notification->active() == ''):?> selected<?php endif?>>중지</option>
						</select>
						<p class="description">알림의 사용 가능 상태를 선택해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="notification_message">알림 메시지</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<textarea id="notification_message" name="notification_message" rows="5" class="regular-text"></textarea>
						<p class="description">알림의 사용 가능 상태를 선택해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label>적용 상품</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<?php if($product_list):?>
							<?php foreach($product_list as $product):?>
							<label style="margin-right:10px;"><input type="checkbox" name="notification_product_id[]" value="<?php echo $product->ID?>"<?php if(in_array($product->ID, $notification->product_ids())):?> checked<?php endif?>> <?php echo esc_html($product->post_title)?></label>
							<?php endforeach?>
						<?php else:?>
						상품이 없습니다.
						<?php endif?>
						<p class="description">선택된 상품에 알림 코드가 입력되면 할인이 적용됩니다.</p>
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

function cosmosfarm_members_notification_code_generator(){
	var notification_code = '';
	var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var characters_length = characters.length;
	var length = 8;
	
	for(var i = 0; i<length; i++){
		notification_code += characters.charAt(Math.floor(Math.random() * characters_length));
	}

	jQuery('#notification_code').val(notification_code.toUpperCase());
}

function cosmosfarm_members_notification_usage_date(value){
	if(value == 'continue'){
		jQuery('.notification-usage-date').hide();
	}
	else{
		jQuery('.notification-usage-date').show();
	}
}

function cosmosfarm_members_notification_usage_start_date(start_date){
	if(!jQuery('#notification_usage_end_date').val()){
		start_date = start_date.split('-');
		var end_date = new Date(parseInt(start_date[0]), parseInt(start_date[1])-1, parseInt(start_date[2]));
		end_date.setMonth(end_date.getMonth()+1);
		end_date.setDate(end_date.getDate()-1);
		
		jQuery('#notification_usage_end_date').val(end_date.yyyymmdd());
	}
}

function cosmosfarm_members_notification_usage_end_date(end_date){
	var start_date = jQuery('#notification_usage_start_date').val();
	if(start_date > end_date){
		alert('종료일은 시작일보다 이전으로 설정할 수 없습니다.');

		start_date = start_date.split('-');
		var end_date = new Date(parseInt(start_date[0]), parseInt(start_date[1])-1, parseInt(start_date[2]));
		end_date.setMonth(end_date.getMonth()+1);
		end_date.setDate(end_date.getDate()-1);
		
		jQuery('#notification_usage_end_date').val(end_date.yyyymmdd());
	}
}

function cosmosfarm_members_notification_discount(value){
	if(value == 'amount'){
		jQuery('.notification-discount-text').text('할인금액');
	}
	else{
		jQuery('.notification-discount-text').text('할인율');
	}

	jQuery('#notification_discount_amount').val('');
}

function cosmosfarm_members_notification_discount_amount(obj){
	var value = jQuery(obj).val();
	var discount = jQuery('#notification_discount').val();

	if(discount == 'rate' && value > 100){
		jQuery(obj).val(100);
	}
	
	if(value < 0){
		jQuery(obj).val(0);
	}
		
}

function cosmosfarm_members_notification_submit(form){
	if(jQuery('#notification_usage_date').val() == 'date_select'){
		if(!jQuery('#notification_usage_start_date').val()){
			alert('시작일을 선택해주세요.');
			jQuery('#notification_usage_start_date').focus();

			return false;
		}
		if(!jQuery('#notification_usage_end_date').val()){
			alert('종료일을 선택해주세요.');
			jQuery('#notification_usage_end_date').focus();

			return false;
		}
	}
	
	return true;
}
</script>

<?php
wp_enqueue_style('jquery-flick-style', COSMOSFARM_MEMBERS_URL . '/assets/css/jquery-ui.css', array(), '1.12.1');
?>