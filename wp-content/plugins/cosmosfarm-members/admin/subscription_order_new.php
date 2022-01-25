<?php if(!defined('ABSPATH')) exit;?>
<style>
.default-hide { display: none; }
</style>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1 class="wp-heading-inline">코스모스팜 회원관리</h1>
	<a href="<?php echo admin_url('admin.php?page=cosmosfarm_subscription_order&action=order_new')?>" class="page-title-action">주문 추가하기</a>
	<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
	<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
	<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
	<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	<p>코스모스팜 회원관리는 한국형 회원가입 레이아웃과 기능을 제공합니다.</p>
	
	<hr class="wp-header-end">
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-order-new', 'cosmosfarm-members-order-new-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_order_new">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="order_title">상품</label></th>
					<td>
						<select id="order_title" name="product_id" onchange="cosmosfarm_members_search_product(this.value)">
							<option value="">상품 선택</option>
							<?php foreach($product_list->posts as $item):?>
							<option value="<?php echo intval($item->ID)?>"><?php echo esc_html($item->post_title)?></option>
							<?php endforeach?>
						</select>
						<span class="spinner" style="float:none;"></span>
						<p>상품을 선택하면 필드가 표시됩니다.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table default-hide">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="order_price">가격</label></th>
					<td>
						<input type="number" id="order_price" name="order_price" value="0">원
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_start_datetime">시작일</label></th>
					<td>
						<input type="text" name="order_start_year" size="4" maxlength="4" value="<?php echo date('Y', current_time('timestamp'))?>">년
						<input type="text" name="order_start_month" size="2" maxlength="2" value="<?php echo date('m', current_time('timestamp'))?>">월
						<input type="text" name="order_start_day" size="2" maxlength="2" value="<?php echo date('d', current_time('timestamp'))?>">일
						<input type="text" name="order_start_hour" size="2" maxlength="2" value="<?php echo date('H', current_time('timestamp'))?>">시
						<input type="text" name="order_start_minute" size="2" maxlength="2" value="<?php echo date('i', current_time('timestamp'))?>">분
					</td>
				</tr>
				<tr valign="top" class="order_end_datetime">
					<th scope="row"><label for="order_end_datetime">만료일</label></th>
					<td>
						<input type="text" name="order_end_year" size="4" maxlength="4" value="">년
						<input type="text" name="order_end_month" size="2" maxlength="2" value="">월
						<input type="text" name="order_end_day" size="2" maxlength="2" value="">일
						<input type="text" name="order_end_hour" size="2" maxlength="2" value="">시
						<input type="text" name="order_end_minute" size="2" maxlength="2" value="">분
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="user_login">사용자 아이디</label></th>
					<td>
						<input type="text" id="user_login" name="user_login" placeholder="사용자 검색" autocomplete="off">
						<p class="description">사용자의 아이디(user_login) 또는 이메일(user_email)로 검색할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_role">사용자 역할(Role)</label></th>
					<td>
						<select id="order_subscription_role" name="order_subscription_role">
							<option value="">역할 변경 없음</option>
							<?php foreach(get_editable_roles() as $key=>$value): if($key == 'administrator') continue;?>
							<option value="<?php echo $key?>"><?php echo _x($value['name'], 'User role')?></option>
							<?php endforeach?>
						</select>
						<p class="description">이용기간 동안 사용자의 역할(Role)을 변경합니다.</p>
						<p class="description">역할(Role) 관리는 <a href="https://ko.wordpress.org/plugins/user-role-editor/" onclick="window.open(this.href);return false;">User Role Editor</a> 플러그인으로 가능합니다.</p>
						<p class="description">역할(Role) 변경은 다른 상품 혹은 다른 기능과 충돌에 유의해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_customer_uid">결제 요청 정보</label></th>
					<td>
						<input type="text" id="order_customer_uid" name="order_customer_uid" class="regular-text" placeholder="결제 요청 정보" autocomplete="off">
						<p class="description">PG사에 결제 요청시 사용됩니다.</p>
						<p class="description">정보를 입력하면 PG사에 결제를 요청합니다.</p>
						
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table default-hide">
			<tbody class="order_fields_html"></tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="주문 추가하기">
		</p>
	</form>
</div>
<div class="clear"></div>
<script>
var bring_order = {
	order_id: '<?php echo esc_js($order->ID())?>',
	user_login: '<?php echo esc_js($order->user()->user_login)?>',
	customer_uid: '<?php echo esc_js($order->iamport_customer_uid())?>'
};

jQuery(document).ready(function(){
	var order_end_year = jQuery('input[name="order_end_year"]').val();
	var order_end_month = jQuery('input[name="order_end_month"]').val();
	var order_end_day = jQuery('input[name="order_end_day"]').val();
	
	if(!(order_end_year && order_end_month && order_end_day)){
		jQuery('.order_end_datetime').hide();
	}
	
	jQuery('#user_login').autocomplete({
		minLength: 0,
		source: function(request, response){
			var keyword = request.term;
			jQuery.post(cosmosfarm_members_admin_settings.post_url, {action:'cosmosfarm_members_search_users', keyword:keyword, security:cosmosfarm_members_admin_settings.ajax_nonce}, function(res){
				response(jQuery.ui.autocomplete.filter(res, keyword));
			});
		},
		focus: function(event, ui){
			return true;
		},
		select: function(event, ui){
			this.value = ui.item.value;
			return false;
		}
	});
	jQuery('#user_login').focus(function(){
		jQuery(this).autocomplete('search', jQuery(this).val());
	});
});

function cosmosfarm_members_search_product(product_id){
	if(product_id){
		jQuery('.form-table .spinner').addClass('is-active');
		jQuery('.default-hide').hide();
		
		jQuery.post(cosmosfarm_members_admin_settings.post_url,{action:'cosmosfarm_members_search_product', product_id:product_id, bring_order_id:bring_order.order_id, security:cosmosfarm_members_admin_settings.ajax_nonce}, function(res){
			jQuery('.form-table .spinner').removeClass('is-active');
			jQuery('.default-hide').show();
			
			jQuery('input[name="order_price"]').val(res.order_price);
			jQuery('input[name="user_login"]').val(bring_order.user_login);
			jQuery('input[name="order_customer_uid"]').val(bring_order.customer_uid);
			
			if(typeof res.order_end_date === 'undefined'){
				jQuery('input[name="order_end_year"]').val('');
				jQuery('input[name="order_end_month"]').val('');
				jQuery('input[name="order_end_minute"]').val('');
				jQuery('input[name="order_end_hour"]').val('');
				jQuery('input[name="order_end_day"]').val('');
				jQuery('.order_end_datetime').hide();
			}
			else{
				jQuery('.order_end_datetime').show();
				jQuery('input[name="order_end_year"]').val(res.order_end_date.order_end_year);
				jQuery('input[name="order_end_month"]').val(res.order_end_date.order_end_month);
				jQuery('input[name="order_end_minute"]').val(res.order_end_date.order_end_minute);
				jQuery('input[name="order_end_hour"]').val(res.order_end_date.order_end_hour);
				jQuery('input[name="order_end_day"]').val(res.order_end_date.order_end_day);
			}
			
			jQuery('select[name="order_subscription_role"]').val(res.order_subscription_role);
			
			jQuery('.order_fields_html').html(res.order_fields_html);
		});
	}
	else{
		jQuery('.default-hide').hide();
		
		jQuery('input[name="order_price"]').val(0);
		
		jQuery('input[name="order_end_year"]').val('');
		jQuery('input[name="order_end_month"]').val('');
		jQuery('input[name="order_end_minute"]').val('');
		jQuery('input[name="order_end_hour"]').val('');
		jQuery('input[name="order_end_day"]').val('');
		jQuery('.order_end_datetime').hide();
		
		jQuery('select[name="order_subscription_role"]').val('');
		
		jQuery('.order_fields_html').html('');
	}
}
</script>