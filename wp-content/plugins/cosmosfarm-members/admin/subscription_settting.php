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
		<?php wp_nonce_field('cosmosfarm-members-subscription-save', 'cosmosfarm-members-subscription-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_subscription_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg">PG사 선택</label></th>
					<td>
						<select id="cosmosfarm_members_builtin_pg" name="cosmosfarm_members_builtin_pg" onchange="cosmosfarm_members_builtin_pg_setting_change(this.value)">
							<option value="">선택하세요</option>
							<option value="inicis"<?php if($option->builtin_pg == 'inicis'):?> selected<?php endif?>>[기본] KG이니시스 신용카드</option>
							<option value="nicepay"<?php if($option->builtin_pg == 'nicepay'):?> selected<?php endif?>>[기본] 나이스페이 신용카드</option>
							
							<?php if($option->iamport_id):?>
							<option value="iamport"<?php if($option->builtin_pg == 'iamport'):?> selected<?php endif?>>[제거예정] 아임포트</option>
							<?php endif?>
						</select>
						<p class="description">기술지원을 받기 위해서는 [기본] PG사를 선택하고 코스모스팜(퍼널모아)를 통해서 PG사에 가입해주세요.</p>
						<p class="description">나이스페이는 신용카드 정기결제 시 보안 프로그램 설치 과정이 없기 때문에 보다 편리하게 사용할 수 있습니다.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table builtin-pg-setting setting-inicis<?php if($option->builtin_pg == 'inicis'):?> active<?php endif?>">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">KG이니시스</label></th>
					<td>
						<a href="https://www.funnelmoa.com/pg/?ref=cosmosfarm_members_to_funnelmoa_pg&utm_campaign=cosmosfarm_members_to_funnelmoa_pg&utm_source=wordpress&utm_medium=referral" class="button" target="_blank">PG 가입하기</a>
						<a href="https://blog.cosmosfarm.com/?p=1209" class="button" target="_blank">키(Key) 정보 조회 방법</a>
						<p class="description">아래 정보를 입력하지 않아도 테스트 결제는 가능합니다.</p>
						<p class="description">테스트 결제는 직접 취소하지 않을 경우 당일 자정 이전에 자동 취소 처리됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_mid">빌링결제 상점아이디(MID)</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_mid" name="cosmosfarm_members_builtin_pg_inicis_mid" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_mid', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_sign_key">Sign Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_sign_key" name="cosmosfarm_members_builtin_pg_inicis_sign_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_sign_key', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_merchant_key">Merchant Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_merchant_key" name="cosmosfarm_members_builtin_pg_inicis_merchant_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_merchant_key', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_api_key">API Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_api_key" name="cosmosfarm_members_builtin_pg_inicis_api_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_api_key', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_general_mid">일반결제 상점아이디(MID)</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_general_mid" name="cosmosfarm_members_builtin_pg_inicis_general_mid" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_general_mid', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_general_sign_key">Sign Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_general_sign_key" name="cosmosfarm_members_builtin_pg_inicis_general_sign_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_general_sign_key', '')?>">
					</td>
				</tr>
				<!--
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_general_merchant_key">Merchant Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_general_merchant_key" name="cosmosfarm_members_builtin_pg_inicis_general_merchant_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_general_merchant_key', '')?>">
					</td>
				</tr>
				-->
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_inicis_general_api_key">API Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_inicis_general_api_key" name="cosmosfarm_members_builtin_pg_inicis_general_api_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_inicis_general_api_key', '')?>">
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table builtin-pg-setting setting-nicepay<?php if($option->builtin_pg == 'nicepay'):?> active<?php endif?>">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">나이스페이</label></th>
					<td>
						<a href="https://www.funnelmoa.com/pg/?ref=cosmosfarm_members_to_funnelmoa_pg&utm_campaign=cosmosfarm_members_to_funnelmoa_pg&utm_source=wordpress&utm_medium=referral" class="button" target="_blank">PG 가입하기</a>
						<!--<a href="https://blog.cosmosfarm.com/?p=1209" class="button" target="_blank">키(Key) 정보 조회 방법</a>-->
						<p class="description">아래 정보를 입력하지 않아도 테스트 결제는 가능합니다.</p>
						<p class="description">테스트 결제는 직접 취소하지 않을 경우 당일 자정 이전에 자동 취소 처리됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_nicepay_billing_mid">빌링결제 상점아이디(MID)</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_nicepay_billing_mid" name="cosmosfarm_members_builtin_pg_nicepay_billing_mid" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_nicepay_billing_mid', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key">Merchant Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key" name="cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_nicepay_billing_merchant_key', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_nicepay_general_mid">일반결제 상점아이디(MID)</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_nicepay_general_mid" name="cosmosfarm_members_builtin_pg_nicepay_general_mid" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_nicepay_general_mid', '')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_builtin_pg_nicepay_general_merchant_key">Merchant Key</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_builtin_pg_nicepay_general_merchant_key" name="cosmosfarm_members_builtin_pg_nicepay_general_merchant_key" class="regular-text" value="<?php echo get_option('cosmosfarm_members_builtin_pg_nicepay_general_merchant_key', '')?>">
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table builtin-pg-setting setting-iamport<?php if($option->builtin_pg == 'iamport'):?> active<?php endif?>">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">아임포트</label></th>
					<td>
						<p class="description">아임포트에 로그인 후 <a href="https://admin.iamport.kr/settings" onclick="window.open(this.href);return false;">시스템설정</a>에 있는 정보를 입력하시면 테스트 결제 또는 실제 결제 기능을 사용할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_id">가맹점 식별코드</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_id" name="cosmosfarm_members_iamport_id" class="regular-text" value="<?php echo $option->iamport_id?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_api_key">REST API 키</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_api_key" name="cosmosfarm_members_iamport_api_key" class="regular-text" value="<?php echo $option->iamport_api_key?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_api_secret">REST API secret</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_api_secret" name="cosmosfarm_members_iamport_api_secret" class="regular-text" value="<?php echo $option->iamport_api_secret?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_pg_mid">PG상점아이디</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_pg_mid" name="cosmosfarm_members_iamport_pg_mid" class="regular-text" value="<?php echo $option->iamport_pg_mid?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_pg">빌링결제 PG사</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_pg" name="cosmosfarm_members_subscription_pg">
							<option value="">사용안함</option>
							<option value="nice"<?php if($option->subscription_pg == 'nice'):?> selected<?php endif?>>나이스페이먼츠</option>
							<option value="jtnet"<?php if($option->subscription_pg == 'jtnet'):?> selected<?php endif?>>JTNet(tPay)</option>
							<option value="html5_inicis"<?php if($option->subscription_pg == 'html5_inicis'):?> selected<?php endif?>>KG이니시스(웹표준결제창)</option>
							<option value="kcp_billing"<?php if($option->subscription_pg == 'kcp_billing'):?> selected<?php endif?>>NHN KCP 빌링결제(엔에이치엔한국사이버결제)</option>
							<option value="kakaopay"<?php if($option->subscription_pg == 'kakaopay'):?> selected<?php endif?>>[간편결제] 카카오페이</option>
							<option value="kakao"<?php if($option->subscription_pg == 'kakao'):?> selected<?php endif?>>[간편결제] 카카오페이(삭제예정)</option>
							<option value="danal_tpay"<?php if($option->subscription_pg == 'danal_tpay'):?> selected<?php endif?>>다날-신용카드/계좌이체/가상계좌</option>
							<option value="danal"<?php if($option->subscription_pg == 'danal'):?> selected<?php endif?>>다날-휴대폰소액결제</option>
						</select>
						<p class="description">사전에 가입된 PG사를 선택해주세요.</p>
						<p class="description"><a href="https://docs.iamport.kr/admin/test-mode" onclick="window.open(this.href);return false;">정기결제 테스트모드 설정 방법</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_general_pg">일반결제 PG사</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_general_pg" name="cosmosfarm_members_subscription_general_pg">
							<option value="">사용안함</option>
							<option value="html5_inicis"<?php if($option->subscription_general_pg == 'html5_inicis'):?> selected<?php endif?>>KG이니시스(웹표준결제창)</option>
							<option value="kcp"<?php if($option->subscription_general_pg == 'kcp'):?> selected<?php endif?>>NHN KCP(엔에이치엔한국사이버결제)</option>
							<option value="nice"<?php if($option->subscription_general_pg == 'nice'):?> selected<?php endif?>>나이스페이먼츠</option>
							<option value="jtnet"<?php if($option->subscription_general_pg == 'jtnet'):?> selected<?php endif?>>JTNet(tPay)</option>
							<option value="uplus"<?php if($option->subscription_general_pg == 'uplus'):?> selected<?php endif?>>LGU+</option>
							<option value="kakaopay"<?php if($option->subscription_general_pg == 'kakaopay'):?> selected<?php endif?>>[간편결제] 카카오페이</option>
							<option value="kakao"<?php if($option->subscription_general_pg == 'kakao'):?> selected<?php endif?>>[간편결제] 카카오페이(삭제예정)</option>
							<option value="danal_tpay"<?php if($option->subscription_general_pg == 'danal_tpay'):?> selected<?php endif?>>다날-신용카드/계좌이체/가상계좌</option>
							<option value="danal"<?php if($option->subscription_general_pg == 'danal'):?> selected<?php endif?>>다날-휴대폰소액결제</option>
							<option value="paypal"<?php if($option->subscription_general_pg == 'paypal'):?> selected<?php endif?>>페이팔-Express Checkout</option>
						</select>
						<p class="description">사전에 가입된 PG사를 선택해주세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_pg_type">기본 결제방식</label></th>
					<td>
						<select id="cosmosfarm_members_subscription_pg_type" name="cosmosfarm_members_subscription_pg_type">
							<option value="">빌링결제</option>
							<option value="general"<?php if($option->subscription_pg_type == 'general'):?> selected<?php endif?>>일반결제</option>
						</select>
						<p class="description">결제방식은 상품마다 개별적으로 적용하실 수 있습니다.</p>
						<p class="description">상품에서 직접 결제방식을 선택하지 않은 경우에 기본 결제방식을 사용합니다.</p>
						<p class="description">정기결제 기능을 사용하기 위해서는 빌링결제를 선택해주세요.</p>
						<p class="description"><a href="https://blog.cosmosfarm.com/?p=170" onclick="window.open(this.href);return false;">PG 가입시 일반결제와 빌링결제 차이 알아보기</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<p class="description">※ 반드시 서버에 SSL인증서가 설치되어 있고 항상 https로 접속되어야 합니다. 홈페이지를 https로 접속할 수 없다면 정기결제 기능을 사용해선 안됩니다.</p>
						<p class="description">※ <a href="https://blog.cosmosfarm.com/?p=906" onclick="window.open(this.href);return false;">웹호스팅 무료 SSL 인증서 설치하기 방법</a>을 참고해서 웹호스팅에 SSL 인증서를 설치해주세요.</p>
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
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_checkout_page_id">정기결제 주문 페이지</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_checkout_page_id" name="cosmosfarm_members_subscription_checkout_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_checkout_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description">사용자가 상품을 결제하기 위해 표시되는 결제 페이지입니다.</p>
						<p class="description"><code>[cosmosfarm_members_subscription_checkout]</code> 숏코드가 삽입된 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
						<p class="description">레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/subscription-checkout.php</code> 파일을 편집해주세요.</p>
						<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/subscription-checkout.php</code> 파일을 추가해서 편집해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_checkout_view_mode">비회원 주문시 동작</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_checkout_view_mode" name="cosmosfarm_members_subscription_checkout_view_mode">
							<option value="">회원가입 및 로그인 사용자만 결제 가능</option>
							<option value="view_reg"<?php if($option->subscription_checkout_view_mode == 'view_reg'):?> selected<?php endif?>>결제 페이지에 회원가입 폼 표시</option>
						</select>
						<p class="description">비회원이 주문 페이지에 접근하면 어떻게 동작할지 선택합니다.</p>
						<p class="description">회원가입 폼을 표시하면 결제를 빠르게 진행할 수 있지만 회원가입 시 필수 정보를 받지 못할 수 있습니다.</p>
						<p class="description">장단점을 비교해보고 선택하세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_orders_page_id">주문 목록 페이지</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_orders_page_id" name="cosmosfarm_members_subscription_orders_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_orders_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description">사용자 본인이 결제한 내역을 확인할 수 있는 페이지입니다.</p>
						<p class="description"><code>[cosmosfarm_members_orders]</code> 숏코드가 삽입된 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				<!--
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_product_list_page_id">상품 목록 페이지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_product_list_page_id" name="cosmosfarm_members_subscription_product_list_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_product_list_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description">등록된 상품의 전체 목록이 표시되는 상점 페이지입니다.</p>
						<p class="description">상품 각각의 숏코드를 사용할 경우 상품 목록 페이지는 사용하지 않을 수 있습니다.</p>
						<p class="description"><code>[cosmosfarm_members_subscription_product_list posts_per_page="6"]</code> 숏코드가 삽입된 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_cancellation_refund_policy_page_id">취소 및 환불 규정 페이지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_cancellation_refund_policy_page_id" name="cosmosfarm_members_subscription_cancellation_refund_policy_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_cancellation_refund_policy_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description">상품 상세 페이지, 결제 페이지 등에 내용이 표시됩니다.</p>
						<p class="description">필요한 경우 메뉴에도 해당 페이지를 추가해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				-->
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_payment_completed_page_id">결제 완료 페이지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_payment_completed_page_id" name="cosmosfarm_members_subscription_payment_completed_page_id">
							<option value="">이전 페이지로 되돌아가기</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->subscription_payment_completed_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description">결제 완료 후 이동할 페이지를 선택합니다.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
						<p class="description">구글과 페이스북의 결제 전환 전환추적 코드가 자동으로 삽입됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subscription_courier_company">기본 택배사</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
					<td>
						<select id="cosmosfarm_members_subscription_courier_company" name="cosmosfarm_members_subscription_courier_company">
							<option value="">배송 없음</option>
							<?php foreach(cosmosfarm_members_courier_company_list() as $key=>$courier):?>
							<option value="<?php echo esc_attr($key)?>"<?php if($option->subscription_courier_company == $key):?> selected<?php endif?>><?php echo esc_html($courier['name'])?></option>
							<?php endforeach?>
						</select>
						<p class="description">배송되는 상품이 있다면 택배사를 선택해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<p class="description">※ 반드시 서버에 SSL인증서가 설치되어 있고 항상 https로 접속되어야 합니다. 홈페이지를 https로 접속할 수 없다면 정기결제 기능을 사용해선 안됩니다.</p>
						<p class="description">※ <a href="https://blog.cosmosfarm.com/?p=906" onclick="window.open(this.href);return false;">웹호스팅 무료 SSL 인증서 설치하기 방법</a>을 참고해서 웹호스팅에 SSL 인증서를 설치해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<ul class="cosmosfarm-members-news-list">
							<?php
							foreach(get_cosmosfarm_members_news_list() as $news_item):?>
							<li>
								<a href="<?php echo esc_url($news_item->url)?>" target="<?php echo esc_attr($news_item->target)?>" style="text-decoration:none"><?php echo esc_html($news_item->title)?></a>
							</li>
							<?php endforeach?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
	</form>
</div>
<div class="clear"></div>

<script>
function cosmosfarm_members_builtin_pg_setting_change(value){
	jQuery('.builtin-pg-setting').removeClass('active');
	jQuery('.setting-' + value).addClass('active');
}
</script>