<?php if(!defined('ABSPATH')) exit;?>
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
		<?php wp_nonce_field('cosmosfarm-members-order-save', 'cosmosfarm-members-order-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_order_save">
		<input type="hidden" name="order_id" value="<?php echo $order->ID()?>">
		<table class="form-table subscription-order">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="order_post_date">날짜</label></th>
					<td>
						<?php echo $order->post_date?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_product_id">상품</label></th>
					<td>
						<select id="order_product_id" name="order_product_id">
							<option value="">상품 선택</option>
							<?php foreach($product_list->posts as $item):?>
							<option value="<?php echo $item->ID?>"<?php if($item->ID == $product->ID()):?> selected<?php endif?>><?php echo esc_html($item->post_title)?></option>
							<?php endforeach?>
						</select>
						<p><?php echo esc_html($order->title())?> <a class="button" href="<?php echo admin_url("admin.php?page=cosmosfarm_subscription_product&product_id=".$product->ID())?>">상품편집</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">사용자</label></th>
					<td>
						<?php if($user && $user->ID):?>
							<?php echo $user->display_name?>(<?php echo $user->user_login?>)
							<p><a class="button" href="mailto:<?php echo esc_url($user->user_email)?>" target="_blank" title="이메일 보내기"><?php echo esc_html($user->user_email)?></a> <a class="button" href="<?php echo admin_url("user-edit.php?user_id={$user->ID}")?>">사용자 편집</a></p>
						<?php else:?>
							정보 없음
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">주문자명</label></th>
					<td>
						<?php echo esc_html($order->buyer_name)?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">이메일</label></th>
					<td>
						<?php if($order->buyer_email):?><a href="mailto:<?php echo esc_url($order->buyer_email)?>" target="_blank" title="이메일 보내기"><?php echo esc_html($order->buyer_email)?></a><?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_title">전화번호</label></th>
					<td>
						<?php echo esc_html($order->buyer_tel)?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_price">가격</label></th>
					<td>
						<?php
						if($order->pay_count() == '1'){
							$price_description = array();
							
							if($order->subscription_first_free()){
								echo cosmosfarm_members_currency_format(0);
								
								$price_description[] = '첫 결제 무료';
							}
							else if($order->coupon_id()){
								echo cosmosfarm_members_currency_format($order->coupon_price());
							}
							else{
								echo cosmosfarm_members_currency_format($order->first_price());
							}
							
							if($order->coupon_id()){
								$coupon = new Cosmosfarm_Members_Subscription_Coupon();
								$coupon->init_with_id($order->coupon_id());
								
								if($coupon->ID()){
									$price_description[] = sprintf('<a href="%s" target="_blank" title="쿠폰 정보">%s 쿠폰 적용</a>', admin_url('admin.php?page=cosmosfarm_subscription_coupon&coupon_id=' . $coupon->ID()), $coupon->title());
								}
								else{
									$price_description[] = '쿠폰 적용';
								}
							}
							
							if($price_description){
								echo sprintf('(%s)', implode(', ', $price_description));
							}
						}
						else if($order->coupon_id()){
							$coupon = new Cosmosfarm_Members_Subscription_Coupon();
							$coupon->init_with_id($order->coupon_id());
							
							if($coupon->ID()){
								echo cosmosfarm_members_currency_format($order->coupon_price()) . sprintf(' (<a href="%s" target="_blank" title="쿠폰 정보">%s 쿠폰 적용</a>)', admin_url('admin.php?page=cosmosfarm_subscription_coupon&coupon_id=' . $coupon->ID()), $coupon->title());
							}
							else{
								echo cosmosfarm_members_currency_format($order->coupon_price()) . ' (쿠폰 적용)';
							}
						}
						else{
							echo cosmosfarm_members_currency_format($order->price());
						}
						?>
						<p><input type="number" id="order_price" name="order_price" value="<?php echo floatval(get_post_meta($order->ID(), 'price', true))?>"> 원</p>
						<p class="description">실제 저장된 가격을 변경할 수 있습니다.</p>
						<p class="description">정기결제의 경우 다음 정기결제에 영향을 줄 수 있기 때문에 이유가 없다면 변경하지 마세요.</p>
						<?php if($order->receipt_url):?>
						<p><a class="button" href="<?php echo esc_url($order->receipt_url)?>" target="_blank" title="영수증">영수증</a></p>
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_coupon_id">쿠폰</label></th>
					<td>
						<select id="order_coupon_id" name="order_coupon_id">
							<option value="">쿠폰 없음</option>
							<?php foreach($coupon_list->posts as $item):?>
							<option value="<?php echo $item->ID?>"<?php if($item->ID == $order->coupon_id()):?> selected<?php endif?>><?php echo esc_html($item->post_title)?></option>
							<?php endforeach?>
						</select>
						<?php if($order->coupon_id()):?>
						<p>쿠폰 적용후 가격:<input type="number" id="order_coupon_price" name="order_coupon_price" value="<?php echo floatval($order->coupon_price())?>"></p>
						<p>쿠폰 적용전 가격:<input type="number" id="order_before_coupon_price" name="order_before_coupon_price" value="<?php echo floatval($order->before_coupon_price())?>"></p>
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_status">결제 상태</label></th>
					<td>
						<select id="order_status" name="order_status">
							<option value="paid"<?php if($order->status() == 'paid'):?> selected<?php endif?>>결제됨</option>
							<option value="cancelled"<?php if($order->status() == 'cancelled'):?> selected<?php endif?>>취소됨</option>
						</select>
						<p class="description">결제 상태를 바꿔도 금액은 환불되지 않기 때문에 특별한 경우가 아니라면 임의로 변경하지 마세요.</p>
						<p class="description">실제 금액을 환불하고 주문을 취소하시려면 '전액 환불하기' 버튼을 눌러주세요.</p>
						<?php if($order->imp_uid):?>
						<p class="description">아임포트에서 환불된 주문이라서 '전액 환불하기'가 동작하지 않는다면 만료일을 현재 시간으로 변경하세요.</p>
						<?php endif?>
						<p><button type="button" class="button" onclick="cosmosfarm_members_order_cancel('<?php echo $order->ID()?>')">전액 환불하기</button></p>
					</td>
				</tr>
				<?php if(!$order->imp_uid):?>
				<tr valign="top">
					<th scope="row"><label for="order_balance">부분 환불</label></th>
					<td>
						<p>잔액 <input type="number" id="order_balance" name="order_balance" value="<?php echo floatval($order->balance())?>"> 원 중 <input type="number" id="order_cancel_price" name="order_cancel_price" value="0"> 원을 환불합니다.</p>
						<p class="description">부분 환불을 원할 경우 가격을 입력해주세요.</p>
						<p class="description">잔액이 맞지 않을 경우 부분 환불되지 않습니다.</p>
						<p><button type="button" class="button" onclick="cosmosfarm_members_order_cancel_partial('<?php echo $order->ID()?>', this.form)">부분 환불하기</button></p>
					</td>
				</tr>
				<?php endif?>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_type">이용기간</label></th>
					<td>
						<?php if($order->subscription_type() == 'onetime'):?>계속사용<?php endif?>
						<?php if($order->subscription_type() == 'daily'):?>1일<?php endif?>
						<?php if($order->subscription_type() == 'weekly'):?>1주일<?php endif?>
						<?php if($order->subscription_type() == '2weekly'):?>2주일<?php endif?>
						<?php if($order->subscription_type() == '3weekly'):?>3주일<?php endif?>
						<?php if($order->subscription_type() == '4weekly'):?>4주일<?php endif?>
						<?php if($order->subscription_type() == 'monthly'):?>1개월<?php endif?>
						<?php if($order->subscription_type() == '2monthly'):?>2개월<?php endif?>
						<?php if($order->subscription_type() == '3monthly'):?>3개월<?php endif?>
						<?php if($order->subscription_type() == '4monthly'):?>4개월<?php endif?>
						<?php if($order->subscription_type() == '5monthly'):?>5개월<?php endif?>
						<?php if($order->subscription_type() == '6monthly'):?>6개월<?php endif?>
						<?php if($order->subscription_type() == '7monthly'):?>7개월<?php endif?>
						<?php if($order->subscription_type() == '8monthly'):?>8개월<?php endif?>
						<?php if($order->subscription_type() == '9monthly'):?>9개월<?php endif?>
						<?php if($order->subscription_type() == '10monthly'):?>10개월<?php endif?>
						<?php if($order->subscription_type() == '11monthly'):?>11개월<?php endif?>
						<?php if($order->subscription_type() == '12monthly'):?>1년<?php endif?>
						<?php if($order->subscription_type() == '24monthly'):?>2년<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_customer_uid">결제 요청 정보</label></th>
					<td>
						<p><input type="text" id="order_customer_uid" name="order_customer_uid" class="regular-text" value="<?php echo esc_attr($order->iamport_customer_uid())?>"></p>
						<p class="description">PG사에 결제 요청시 사용됩니다.</p>
						<p class="description">외부 유출되지 않도록 관리해주세요.</p>
						<?php if($order->iamport_customer_uid()):?>
						<p><button type="button" class="button" onclick="window.open('<?php echo admin_url('admin.php?page=cosmosfarm_subscription_order&action=order_new&bring_order_id=' . $order->ID())?>');return false;">결제 요청 정보로 주문 추가하기</button></p>
						<?php endif?>
					</td>
				</tr>
				<?php if($order->subscription_type() != 'onetime'):?>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_active">정기결제</label></th>
					<td>
						<select id="order_subscription_active" name="order_subscription_active">
							<option value="">자동결제 없음</option>
							<option value="1"<?php if($order->subscription_active()):?> selected<?php endif?>>이용기간 만료 후 자동결제</option>
						</select>
						<p class="description">결제 상태가 '결제됨' 상태일 경우에 동작합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_subscription_next">정기결제 상태</label></th>
					<td>
						<span class="subscription-next-<?php echo $order->subscription_next()?>"><?php echo $order->subscription_next_format()?></span>
						<p>
							<select id="order_subscription_next" name="order_subscription_next">
								<option value="wait"<?php if($order->subscription_next() == 'wait'):?> selected<?php endif?>>진행중</option>
								<option value="cancel"<?php if($order->subscription_next() == 'cancel'):?> selected<?php endif?>>취소됨</option>
								<option value="expiry"<?php if($order->subscription_next() == 'expiry'):?> selected<?php endif?>>만료됨</option>
							</select>
						</p>
						<p class="description">만료된 상태를 진행중으로 변경하면 다시 정기결제가 실행될 수 있습니다.</p>
						<p class="description">정기결제가 중복으로 실행될 수 있기 때문에 이유가 없다면 상태를 변경하지 마세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_error_message">다음 정기결제 실패</label></th>
					<td>
						<p><input type="text" id="order_error_message" name="order_error_message" class="regular-text" value="<?php echo esc_attr($order->error_message())?>"></p>
						<p class="description">다음 정기결제가 실패했다면 메시지가 표시됩니다.</p>
						<p class="description">관리자가 수동으로 정기결제를 다시 실행하거나 구매자에게 연락해 재결제를 요청하세요.</p>
						<p class="description">주문 목록에서 구매자가 정기결제 실패 이후 다시 결제한 주문이 있는지 검색해보세요.</p>
						<p class="description"><a href="https://blog.cosmosfarm.com/?p=1497" onclick="window.open(this.href);return false;">실패한 정기결제 다시 실행하는 방법</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_pay_count">정기결제 회차</label></th>
					<td>
						<p><input type="number" id="order_pay_count" name="order_pay_count" value="<?php echo $order->pay_count()?>"> 회차</p>
						<p class="description">일반결제는 1로 표시되며 빌링결제(정기결제)는 몇 번 자동 결제했는지 회차를 표시합니다.</p>
						<p class="description">설정을 변경하면 다음 정기결제 시 이어서 적용됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_start_datetime">시작일</label></th>
					<td>
						<?php if($order->end_datetime()):?>
						<?php echo date('Y-m-d H:i', strtotime($order->start_datetime()))?>
						<?php else:?>
						제한없음
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_end_datetime">만료일</label></th>
					<td>
						<?php if($order->end_datetime()):?>
						<input type="text" name="order_end_year" size="4" maxlength="4" value="<?php echo date('Y', strtotime($order->end_datetime()))?>">년
						<input type="text" name="order_end_month" size="2" maxlength="2" value="<?php echo date('m', strtotime($order->end_datetime()))?>">월
						<input type="text" name="order_end_day" size="2" maxlength="2" value="<?php echo date('d', strtotime($order->end_datetime()))?>">일
						<input type="text" name="order_end_hour" size="2" maxlength="2" value="<?php echo date('H', strtotime($order->end_datetime()))?>">시
						<input type="text" name="order_end_minute" size="2" maxlength="2" value="<?php echo date('i', strtotime($order->end_datetime()))?>">분
						<p class="description">만료일을 현재시간 혹은 이전 시간으로 변경하면 정기결제 상태가 만료됩니다.</p>
						<p class="description">정기결제 상태가 만료될 경우 자동 결제를 사용하고 있다면 자동으로 결제되고 연장됩니다.</p>
						<?php else:?>
						제한없음
						<?php endif?>
					</td>
				</tr>
				<?php endif?>
				<tr valign="top">
					<th scope="row"><label for="">사용자 역할(Role)</label></th>
					<td>
						<?php if($order->subscription_role()):?>
						결제전
						<select id="order_subscription_prev_role" name="order_subscription_prev_role">
							<?php foreach(get_editable_roles() as $key=>$value): if($key == 'administrator') continue;?>
							<option value="<?php echo $key?>"<?php if($order->subscription_prev_role() == $key):?> selected<?php endif?>><?php echo _x($value['name'], 'User role')?></option>
							<?php endforeach?>
						</select>
						→
						결제후
						<select id="order_subscription_role" name="order_subscription_role">
							<?php foreach(get_editable_roles() as $key=>$value): if($key == 'administrator') continue;?>
							<option value="<?php echo $key?>"<?php if($order->subscription_role() == $key):?> selected<?php endif?>><?php echo _x($value['name'], 'User role')?></option>
							<?php endforeach?>
						</select>
						<p class="description">설정을 변경하면 다음 정기결제 시 이어서 적용됩니다.</p>
						<p class="description">정기결제가 종료되면 결제전 역할로 되돌아갑니다.</p>
						<p class="description">정기결제 상태가 '만료됨'인 경우에는 무시됩니다.</p>
						<?php else:?>
						역할 변경 없음
						<?php endif?>
					</td>
				</tr>
				<?php if($option->subscription_courier_company):?>
				<?php
				$courier_company_list = cosmosfarm_members_courier_company_list();
				$courier_company_name = $courier_company_list[$option->subscription_courier_company]['name'];
				?>
				<tr valign="top">
					<th scope="row"><label for="order_courier_company">택배사</label></th>
					<td>
						<select id="order_courier_company" name="order_courier_company">
							<option value=""<?php if($order->courier_company() == ''):?> selected<?php endif?>>기본 택배사 (<?php echo esc_html($courier_company_name)?>)</option>
							<?php foreach(cosmosfarm_members_courier_company_list() as $key=>$courier):?>
							<option value="<?php echo esc_attr($key)?>"<?php if($order->courier_company() == $key):?> selected<?php endif?>><?php echo esc_html($courier['name'])?></option>
							<?php endforeach?>
						</select>
						<p class="description">기본 택배사와 다를 경우 택배사를 선택하세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="order_tracking_code">운송장 번호</label></th>
					<td>
						<p><input type="text" id="order_tracking_code" name="order_tracking_code" class="regular-text" value="<?php echo esc_attr($order->tracking_code())?>"></p>
						<?php if($order->tracking_code()):?>
						<?php
						$courier_company = $order->courier_company() ? $order->courier_company() : $option->subscription_courier_company;
						?>
						<p class="description"><a href="<?php echo esc_url(sprintf($courier_company_list[$courier_company]['tracking_url'], $order->tracking_code()))?>" class="button" onclick="window.open(this.href);return false;">배송 상태 확인</a></p>
						<?php else:?>
						<p class="description">운송장 번호를 입력하세요.</p>
						<?php endif?>
					</td>
				</tr>
				<?php endif?>
				<tr valign="top">
					<th scope="row"><label for="order_comment">결제 메모</label></th>
					<td>
						<textarea id="order_comment" name="order_comment" rows="3" class="regular-text"><?php echo esc_textarea($order->order_comment())?></textarea>
						<p class="description">결제 관련해서 메모를 입력해두세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
		
		<hr>
		
		<table class="form-table">
			<tbody>
				<?php
				$fields = $product->fields();
				$fields_count = count($fields);
				for($index=0; $index<$fields_count; $index++){
					if($fields[$index]['type'] == 'hr') continue;
					if($fields[$index]['type'] == 'zip'){
						echo $order->get_order_field_template($fields[$index++], $fields[$index++], $fields[$index]);
					}
					else{
						echo $order->get_order_field_template($fields[$index]);
					}
				}
				?>
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