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
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>" onsubmit="return cosmosfarm_members_product_save_submit(this)">
		<?php wp_nonce_field('cosmosfarm-members-product-save', 'cosmosfarm-members-product-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_product_save">
		<input type="hidden" name="product_id" value="<?php echo $product->ID()?>">
		<input type="hidden" name="cosmosfarm_subscription_product_setting" value="">
		
		<?php if($product->ID()):?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="product_title">숏코드</label></th>
					<td>
						<code>[cosmosfarm_members_subscription_product id="<?php echo $product->ID()?>"]</code>
						<p class="description">숏코드를 페이지 혹은 글에 삽입해주세요.</p>
						<p class="description">레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/subscription-product.php</code> 파일을 편집해주세요.</p>
						<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/subscription-product.php</code> 파일을 추가해서 편집해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="product_title">결제 페이지 주소</label></th>
					<td>
						<code><?php echo $product->get_order_url_without_redirect()?></code>
						<p class="description">상품의 결제 페이지로 연결되는 주소입니다.</p>
						<p class="description">직접 만든 버튼 또는 외부 페이지에 결제 페이지 주소를 링크할 수 있습니다.</p>
						<p class="description">상대방에게 문자나 이메일로 공유해서 결제를 받을 수 있습니다.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php endif?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">중요 알림</label></th>
					<td>
						결제 필드에서 주문자명, 이메일, 전화번호는 반드시 추가하고 필수 입력으로 설정해주세요.
						<p class="description"><a href="https://blog.cosmosfarm.com/?p=1505" onclick="window.open(this.href);return false;">정기결제 필수 필드 추가 방법</a></p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<hr>
		
		<h2 class="nav-tab-wrapper">
			<a href="#cosmosfarm-subscription-product-setting-0" class="cosmosfarm-subscription-product-setting-tab nav-tab nav-tab-active" onclick="cosmosfarm_members_product_setting_tab_chnage(0);">상품 내용</a>
			<a href="#cosmosfarm-subscription-product-setting-1" class="cosmosfarm-subscription-product-setting-tab nav-tab" onclick="cosmosfarm_members_product_setting_tab_chnage(1);">결제 정보</a>
			<a href="#cosmosfarm-subscription-product-setting-2" class="cosmosfarm-subscription-product-setting-tab nav-tab" onclick="cosmosfarm_members_product_setting_tab_chnage(2);">결제 필드</a>
			<a href="#cosmosfarm-subscription-product-setting-3" class="cosmosfarm-subscription-product-setting-tab nav-tab" onclick="cosmosfarm_members_product_setting_tab_chnage(3);">메시지 전송</a>
			<a href="#cosmosfarm-subscription-product-setting-4" class="cosmosfarm-subscription-product-setting-tab nav-tab" onclick="cosmosfarm_members_product_setting_tab_chnage(4);">구매자 설정</a>
			<?php if(cosmosfarm_members_is_advanced()):?>
			<a href="#cosmosfarm-subscription-product-setting-5" class="cosmosfarm-subscription-product-setting-tab nav-tab" onclick="cosmosfarm_members_product_setting_tab_chnage(5);">고급 기능</a>
			<?php endif?>
		</h2>
		
		<div class="cosmosfarm-subscription-product-setting cosmosfarm-subscription-product-setting-active">
			<!-- 상품 내용 -->
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="product_title">상품 이름</label> <span style="font-size:12px;color:red;">(필수)</span></th>
						<td>
							<input type="text" id="product_title" name="product_title" class="regular-text" value="<?php echo $product->title()?>">
							<p class="description">상품의 이름을 입력해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_content">상세 설명</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<?php if(cosmosfarm_members_is_elementor_support()):?>
								<a style="display:inline-block" href="<?php echo add_query_arg(array('post'=>$product->ID(), 'action'=>'elementor'), admin_url('post.php'))?>">
									<div id="elementor-editor-button" class="button button-primary button-hero">Elementor 사용</div>
								</a>
							<?php else:?>
								<?php echo wp_editor($product->content(), 'product_content', array('editor_height'=>300))?>
							<?php endif?>
							<p class="description">상품의 상세 설명을 입력해주세요.</p>
							<p class="description">보통의 경우 사용되지 않습니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_excerpt">요약 설명</label> <span style="font-size:12px;color:red;">(필수)</span></th>
						<td>
							<textarea name="product_excerpt" rows="3" cols="30" style="width:100%"><?php echo $product->excerpt()?></textarea>
							<p class="description">상품의 요약 설명을 간략하게 입력해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="">썸네일</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<div id="product-thumbnail-img">
								<?php echo get_the_post_thumbnail($product->ID(), 'thumbnail')?>
							</div>
							<input type="hidden" id="product-thumbnail-id" name="product_thumbnail_id" value="<?php echo get_post_thumbnail_id($product->ID())?>">
							<button type="button" id="product-thumbnail-button" class="button">이미지 선택</button>
							<button type="button" id="product-thumbnail-clear-button" class="button">이미지 삭제</button> 
							<p class="description">상품의 대표 이미지를 설정해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="">이미지 갤러리</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<ul id="product-gallery-img">
								<?php foreach($product->gallery_images() as $attachment_id):?>
								<li>
									<input type="hidden" name="product_gallery_images[]" value="<?php echo $attachment_id?>">
									<?php echo wp_get_attachment_image($attachment_id, 'thumbnail')?>
								</li>
								<?php endforeach?>
							</ul>
							<button type="button" id="product-gallery-button" class="button">이미지 추가</button> 
							<p class="description">이미지가 있을 경우 상품 상세 페이지에서 갤러리가 표시됩니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_tags">상품 태그</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<input type="text" id="product_tags" name="product_tags" class="regular-text" value="<?php echo $product->tags_to_string()?>">
							<p class="description">상품의 태그를 입력해주세요.</p>
							<p class="description">특수문자는 사용할 수 없습니다.</p>
							<p class="description">콤마(,)를 기준으로 분리됩니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_name">고유주소</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<input type="text" id="product_name" name="product_name" class="regular-text" value="<?php echo urldecode($product->name())?>">
							<p class="description">상세페이지를 사용할 경우 페이지 주소로 사용됩니다.</p>
							<p class="description">직접 입력하지 않을 경우 자동으로 입력됩니다.</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<?php if($meta_fields = $product->get_meta_fields()):?>
			<h3>추가 상품 내용</h3>
			<table class="form-table">
				<tbody>
					<?php foreach($meta_fields as $field):?>
					<tr valign="top">
						<th scope="row"><label for="product-meta-key-<?php echo esc_attr($field['meta_key'])?>"><?php echo $field['label']?></label></th>
						<td>
							<input type="<?php echo esc_attr($field['type'])?>" id="product-meta-key-<?php echo esc_attr($field['meta_key'])?>" name="<?php echo esc_attr($field['meta_key'])?>"<?php if(in_array($field['type'], array('text', 'email'))):?> class="regular-text"<?php endif?> value="<?php echo esc_attr($product->get_meta_value($field['meta_key']))?>" placeholder="<?php echo esc_attr($field['placeholder'])?>">
							<?php if($field['description']):?><p class="description"><?php echo esc_attr($field['description'])?></p><?php endif?>
						</td>
					</tr>
					<?php endforeach?>
				</tbody>
			</table>
			<?php endif?>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="변경 사항 저장">
			</p>
		</div>
		
		<div class="cosmosfarm-subscription-product-setting">
			<!-- 결제 정보 -->
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_pg_type">결제방식</label> <span style="font-size:12px;color:red;">(필수)</span></th>
						<td>
							<?php
							$subscription_pg_type = get_post_meta($product->ID(), 'subscription_pg_type', true);
							?>
							<select id="product_subscription_pg_type" name="product_subscription_pg_type">
								<option value="">설정에 의해 결정 (<?php echo get_cosmosfarm_members_subscription_pg_type()=='general' ? '일반결제' : '빌링결제';?> 설정됨)</option>
								<option value="billing"<?php if($subscription_pg_type == 'billing'):?> selected<?php endif?>>빌링결제</option>
								<option value="general"<?php if($subscription_pg_type == 'general'):?> selected<?php endif?>>일반결제</option>
							</select>
							<p class="description">상품마다 결제방식을 선택할 수 있습니다.</p>
							<p class="description">설정에 의해 결정을 선택한 경우에는 <a href="<?php echo admin_url('admin.php?page=cosmosfarm_subscription_settting')?>" onclick="window.open(this.href);return false;">정기결제 설정</a> 페이지에서 결제방식을 변경할 수 있습니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_price">가격</label> <span style="font-size:12px;color:red;">(필수)</span></th>
						<td>
							<input type="number" id="product_price" name="product_price" value="<?php echo $product->price()?>">원
							<p class="description">0원 이상의 가격을 입력해주세요. 특수문자 제외하고 숫자만 입력해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_first_price">첫 결제 가격</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<input type="number" id="product_first_price" name="product_first_price" value="<?php echo intval($product->first_price)?>">원
							<p class="description">정기결제 상품에만 적용됩니다.</p>
							<p class="description">0원일 경우 기본 가격으로 결제됩니다.</p>
							<p class="description">첫 결제시 비용을 받지 않으시려면 <label for="product_subscription_first_free" style="font-weight:bold">첫 결제 무료 이용기간</label>설정을 사용해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_type">이용기간</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_type" name="product_subscription_type">
								<option value="onetime"<?php if($product->subscription_type() == 'onetime'):?> selected<?php endif?>>제한없음</option>
								<option value="daily"<?php if($product->subscription_type() == 'daily'):?> selected<?php endif?>>1일</option>
								<option value="weekly"<?php if($product->subscription_type() == 'weekly'):?> selected<?php endif?>>1주일</option>
								<option value="2weekly"<?php if($product->subscription_type() == '2weekly'):?> selected<?php endif?>>2주일</option>
								<option value="3weekly"<?php if($product->subscription_type() == '3weekly'):?> selected<?php endif?>>3주일</option>
								<option value="4weekly"<?php if($product->subscription_type() == '4weekly'):?> selected<?php endif?>>4주일</option>
								<option value="monthly"<?php if($product->subscription_type() == 'monthly'):?> selected<?php endif?>>1개월</option>
								<option value="2monthly"<?php if($product->subscription_type() == '2monthly'):?> selected<?php endif?>>2개월</option>
								<option value="3monthly"<?php if($product->subscription_type() == '3monthly'):?> selected<?php endif?>>3개월</option>
								<option value="4monthly"<?php if($product->subscription_type() == '4monthly'):?> selected<?php endif?>>4개월</option>
								<option value="5monthly"<?php if($product->subscription_type() == '5monthly'):?> selected<?php endif?>>5개월</option>
								<option value="6monthly"<?php if($product->subscription_type() == '6monthly'):?> selected<?php endif?>>6개월</option>
								<option value="7monthly"<?php if($product->subscription_type() == '7monthly'):?> selected<?php endif?>>7개월</option>
								<option value="8monthly"<?php if($product->subscription_type() == '8monthly'):?> selected<?php endif?>>8개월</option>
								<option value="9monthly"<?php if($product->subscription_type() == '9monthly'):?> selected<?php endif?>>9개월</option>
								<option value="10monthly"<?php if($product->subscription_type() == '10monthly'):?> selected<?php endif?>>10개월</option>
								<option value="11monthly"<?php if($product->subscription_type() == '11monthly'):?> selected<?php endif?>>11개월</option>
								<option value="12monthly"<?php if($product->subscription_type() == '12monthly'):?> selected<?php endif?>>1년</option>
								<option value="24monthly"<?php if($product->subscription_type() == '24monthly'):?> selected<?php endif?>>2년</option>
							</select>
							<p class="description">멜론, 유튜브 프리미엄 등과 같은 정기구독 서비스, 정기배송 등 결제 후 다음 결제가 필요한 때까지의 기간입니다.</p>
							<p class="description">이용기간이 제한없음일 경우 자동 결제가 실행되지 않고 계속 상태가 유지됩니다.</p>
							<p class="description">최대 이용기간은 PG사 계약에 따라서 달라집니다. PG사와 협의 후 설정해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_active">정기결제</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_active" name="product_subscription_active">
								<option value="">자동 결제 없음</option>
								<option value="1"<?php if($product->subscription_active()):?> selected<?php endif?>>이용기간 만료 후 자동 결제</option>
							</select>
							<p class="description"><label for="product_subscription_type" style="font-weight:bold">이용기간</label>이 제한없음일 경우 자동 결제가 실행되지 않습니다.</p>
							<p class="description">※ 반드시 빌링결제를 사용할 수 있는 경우에만 사용해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_pay_count_limit">정기결제 만료 횟수</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_pay_count_limit" name="product_pay_count_limit">
								<option value="">자동 만료 없음</option>
								<option value="2"<?php if($product->pay_count_limit() == 2):?> selected<?php endif?>>2회</option>
								<option value="3"<?php if($product->pay_count_limit() == 3):?> selected<?php endif?>>3회</option>
								<option value="4"<?php if($product->pay_count_limit() == 4):?> selected<?php endif?>>4회</option>
								<option value="5"<?php if($product->pay_count_limit() == 5):?> selected<?php endif?>>5회</option>
								<option value="6"<?php if($product->pay_count_limit() == 6):?> selected<?php endif?>>6회</option>
								<option value="7"<?php if($product->pay_count_limit() == 7):?> selected<?php endif?>>7회</option>
								<option value="8"<?php if($product->pay_count_limit() == 8):?> selected<?php endif?>>8회</option>
								<option value="9"<?php if($product->pay_count_limit() == 9):?> selected<?php endif?>>9회</option>
								<option value="10"<?php if($product->pay_count_limit() == 10):?> selected<?php endif?>>10회</option>
								<option value="11"<?php if($product->pay_count_limit() == 11):?> selected<?php endif?>>11회</option>
								<option value="12"<?php if($product->pay_count_limit() == 12):?> selected<?php endif?>>12회</option>
								<option value="13"<?php if($product->pay_count_limit() == 13):?> selected<?php endif?>>13회</option>
								<option value="14"<?php if($product->pay_count_limit() == 14):?> selected<?php endif?>>14회</option>
								<option value="15"<?php if($product->pay_count_limit() == 15):?> selected<?php endif?>>15회</option>
								<option value="16"<?php if($product->pay_count_limit() == 16):?> selected<?php endif?>>16회</option>
								<option value="17"<?php if($product->pay_count_limit() == 17):?> selected<?php endif?>>17회</option>
								<option value="18"<?php if($product->pay_count_limit() == 18):?> selected<?php endif?>>18회</option>
								<option value="19"<?php if($product->pay_count_limit() == 19):?> selected<?php endif?>>19회</option>
								<option value="20"<?php if($product->pay_count_limit() == 20):?> selected<?php endif?>>20회</option>
								<option value="21"<?php if($product->pay_count_limit() == 21):?> selected<?php endif?>>21회</option>
								<option value="22"<?php if($product->pay_count_limit() == 22):?> selected<?php endif?>>22회</option>
								<option value="23"<?php if($product->pay_count_limit() == 23):?> selected<?php endif?>>23회</option>
								<option value="24"<?php if($product->pay_count_limit() == 24):?> selected<?php endif?>>24회</option>
								<option value="25"<?php if($product->pay_count_limit() == 25):?> selected<?php endif?>>25회</option>
								<option value="26"<?php if($product->pay_count_limit() == 26):?> selected<?php endif?>>26회</option>
								<option value="27"<?php if($product->pay_count_limit() == 27):?> selected<?php endif?>>27회</option>
								<option value="28"<?php if($product->pay_count_limit() == 28):?> selected<?php endif?>>28회</option>
								<option value="29"<?php if($product->pay_count_limit() == 29):?> selected<?php endif?>>29회</option>
								<option value="30"<?php if($product->pay_count_limit() == 30):?> selected<?php endif?>>30회</option>
								<option value="31"<?php if($product->pay_count_limit() == 31):?> selected<?php endif?>>31회</option>
								<option value="32"<?php if($product->pay_count_limit() == 32):?> selected<?php endif?>>32회</option>
								<option value="33"<?php if($product->pay_count_limit() == 33):?> selected<?php endif?>>33회</option>
								<option value="34"<?php if($product->pay_count_limit() == 34):?> selected<?php endif?>>34회</option>
								<option value="35"<?php if($product->pay_count_limit() == 35):?> selected<?php endif?>>35회</option>
								<option value="36"<?php if($product->pay_count_limit() == 36):?> selected<?php endif?>>36회</option>
							</select>
							
							<p class="description"><label for="product_subscription_active" style="font-weight:bold">정기결제</label>가 이용기간 만료 후 자동 결제인 경우에 적용됩니다.</p>
							<p class="description">첫 결제를 포함해서 모든 결제 횟수가 설정된 횟수가 될때 까지만 정기결제가 실행됩니다. (횟수가 지나면 결제되지 않습니다.)</p>
							<p class="description">만료 횟수가 2회일 경우 "첫 결제 + 정기결제 1회" 까지만 결제되고 중단됩니다.</p>
							<p class="description">예를 들어 <label for="product_subscription_type" style="font-weight:bold">이용기간</label>이 1개월인 경우 "1년=12달=12회" 기준을 참고해서 설정해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_first_free">첫 결제 무료 이용기간</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_first_free" name="product_subscription_first_free">
								<option value="">무료 이용기간 없음</option>
								<option value="1day"<?php if($product->subscription_first_free() == '1day'):?> selected<?php endif?>>1일</option>
								<option value="2day"<?php if($product->subscription_first_free() == '2day'):?> selected<?php endif?>>2일</option>
								<option value="3day"<?php if($product->subscription_first_free() == '3day'):?> selected<?php endif?>>3일</option>
								<option value="4day"<?php if($product->subscription_first_free() == '4day'):?> selected<?php endif?>>4일</option>
								<option value="5day"<?php if($product->subscription_first_free() == '5day'):?> selected<?php endif?>>5일</option>
								<option value="6day"<?php if($product->subscription_first_free() == '6day'):?> selected<?php endif?>>6일</option>
								<option value="7day"<?php if($product->subscription_first_free() == '7day'):?> selected<?php endif?>>7일</option>
								<option value="8day"<?php if($product->subscription_first_free() == '8day'):?> selected<?php endif?>>8일</option>
								<option value="9day"<?php if($product->subscription_first_free() == '9day'):?> selected<?php endif?>>9일</option>
								<option value="10day"<?php if($product->subscription_first_free() == '10day'):?> selected<?php endif?>>10일</option>
								<option value="11day"<?php if($product->subscription_first_free() == '11day'):?> selected<?php endif?>>11일</option>
								<option value="12day"<?php if($product->subscription_first_free() == '12day'):?> selected<?php endif?>>12일</option>
								<option value="13day"<?php if($product->subscription_first_free() == '13day'):?> selected<?php endif?>>13일</option>
								<option value="14day"<?php if($product->subscription_first_free() == '14day'):?> selected<?php endif?>>14일</option>
								<option value="15day"<?php if($product->subscription_first_free() == '15day'):?> selected<?php endif?>>15일</option>
								<option value="16day"<?php if($product->subscription_first_free() == '16day'):?> selected<?php endif?>>16일</option>
								<option value="17day"<?php if($product->subscription_first_free() == '17day'):?> selected<?php endif?>>17일</option>
								<option value="18day"<?php if($product->subscription_first_free() == '18day'):?> selected<?php endif?>>18일</option>
								<option value="19day"<?php if($product->subscription_first_free() == '19day'):?> selected<?php endif?>>19일</option>
								<option value="20day"<?php if($product->subscription_first_free() == '20day'):?> selected<?php endif?>>20일</option>
								<option value="21day"<?php if($product->subscription_first_free() == '21day'):?> selected<?php endif?>>21일</option>
								<option value="22day"<?php if($product->subscription_first_free() == '22day'):?> selected<?php endif?>>22일</option>
								<option value="23day"<?php if($product->subscription_first_free() == '23day'):?> selected<?php endif?>>23일</option>
								<option value="24day"<?php if($product->subscription_first_free() == '24day'):?> selected<?php endif?>>24일</option>
								<option value="25day"<?php if($product->subscription_first_free() == '25day'):?> selected<?php endif?>>25일</option>
								<option value="26day"<?php if($product->subscription_first_free() == '26day'):?> selected<?php endif?>>26일</option>
								<option value="27day"<?php if($product->subscription_first_free() == '27day'):?> selected<?php endif?>>27일</option>
								<option value="28day"<?php if($product->subscription_first_free() == '28day'):?> selected<?php endif?>>28일</option>
								<option value="29day"<?php if($product->subscription_first_free() == '29day'):?> selected<?php endif?>>29일</option>
								<option value="30day"<?php if($product->subscription_first_free() == '30day'):?> selected<?php endif?>>30일</option>
								<option value="1month"<?php if($product->subscription_first_free() == '1month'):?> selected<?php endif?>>1개월</option>
								<option value="2month"<?php if($product->subscription_first_free() == '2month'):?> selected<?php endif?>>2개월</option>
								<option value="3month"<?php if($product->subscription_first_free() == '3month'):?> selected<?php endif?>>3개월</option>
							</select>
							<p class="description">실제 사용 가능한 카드인지 확인하기 위해서 실제로 결제 후 성공시 다시 결제를 취소하게 됩니다.</p>
							<p class="description"><label for="product_subscription_type" style="font-weight:bold">이용기간</label>이 제한없음 또는 <label for="product_subscription_active" style="font-weight:bold">정기결제</label>가 자동 결제 없음일 경우에는 적용되지 않습니다.</p>
							<p class="description">사용자의 동일 상품의 과거 결제 내역이 존재하면 적용되지 않습니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_again_price_type">정기결제 기준 가격</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_again_price_type" name="product_subscription_again_price_type">
								<option value="">상품에 설정된 가격으로 결제</option>
								<option value="old_order"<?php if($product->subscription_again_price_type() == 'old_order'):?> selected<?php endif?>>이전 결제된 가격으로 결제</option>
							</select>
							<p class="description">정기결제 상품의 가격이 바뀔 때 기존 사용자의 경우 정기결제 가격을 유지할 때 사용됩니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_role">사용자 역할(Role)</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_role" name="product_subscription_role">
								<option value="">역할 변경 없음</option>
								<?php foreach(get_editable_roles() as $key=>$value): if($key == 'administrator') continue;?>
								<option value="<?php echo $key?>"<?php if($product->subscription_role() == $key):?> selected<?php endif?>><?php echo _x($value['name'], 'User role')?></option>
								<?php endforeach?>
							</select>
							<p class="description">이용기간 동안 사용자의 역할(Role)을 변경합니다.</p>
							<p class="description">역할(Role) 관리는 <a href="https://ko.wordpress.org/plugins/user-role-editor/" onclick="window.open(this.href);return false;">User Role Editor</a> 플러그인으로 가능합니다.</p>
							<p class="description">역할(Role) 변경은 다른 상품 혹은 다른 기능과 충돌에 유의해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_multiple_pay">여러번 결제 가능</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_multiple_pay" name="product_subscription_multiple_pay">
								<option value="">비활성화</option>
								<option value="1"<?php if($product->subscription_multiple_pay() == '1'):?> selected<?php endif?>>활성화</option>
							</select>
							<p class="description">사용자 역할(Role) 변경이 없을 경우에만 활성화됩니다.</p>
							<p class="description">상품의 성격에 따라서 선택해주세요.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_earn_points_type">포인트 적립</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_earn_points_type" name="product_earn_points_type">
								<option value="accumulate"<?php if($product->earn_points_type() == 'accumulate'):?> selected<?php endif?>>적립</option>
								<option value="reset"<?php if($product->earn_points_type() == 'reset'):?> selected<?php endif?>>초기화</option>
							</select><br>
							<input type="number" id="product_earn_points" name="product_earn_points" value="<?php echo esc_attr($product->earn_points())?>" placeholder="숫자 입력">
							<p class="description">적립의 경우 사용자에게 남아있는 포인트에 계속 더해집니다.</p>
							<p class="description">초기화의 경우 사용자에게 남아있는 포인트에 상관없이 매 결제 시 동일한 포인트로 변경됩니다.</p>
							<p class="description">포인트는 <a href="https://ko.wordpress.org/plugins/mycred/" onclick="window.open(this.href);return false;">myCred</a> 플러그인 기반으로 동작하기 때문에 해당 플러그인을 설치해주세요.</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="변경 사항 저장">
			</p>
		</div>
		
		<div class="cosmosfarm-subscription-product-setting">
			<!-- 결제 필드 -->	
			<?php $table->search_box('Search', 'search')?>
			<?php $table->display()?>
			
			<p class="description">주문자명, 이메일, 전화번호는 반드시 추가하고 필수 입력으로 설정해주세요.</p>
			<p class="description"><a href="https://blog.cosmosfarm.com/?p=1505" onclick="window.open(this.href);return false;">정기결제 필수 필드 추가 방법</a></p>
			<p class="description">라벨은 필드의 이름입니다.</p>
			<p class="description">Meta Key는 DB저장시 사용되는 필드의 고유한 키값으로 공백(스페이스)와 특수문자는 입력할 수 없으며 영문 소문자, 언더바(_)만 사용이 가능합니다.</p>
			<p class="description">데이터는 셀렉트, 라디오, 체크박스에서 사용됩니다. 콤마(,)로 값을 구분합니다.</p>
			<p class="description">User Meta Key는 사용자 정보를 자동으로 입력하기 위해서 사용됩니다.</p>
			<p class="description">주문내역에 표시는 구매자 본인이 결제정보를 확인할 수 있도록 합니다.</p>
			<hr>
			<p class="description">결제 필드의 레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/subscription-checkout-fields.php</code> 파일을 편집해주세요.</p>
			<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/subscription-checkout-fields.php</code> 파일을 추가해서 편집해주세요.</p>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="변경 사항 저장">
			</p>
		</div>
		
		<div class="cosmosfarm-subscription-product-setting">
			<!-- 메시지 전송 -->
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_send_sms_paid">첫 결제완료 문자 메시지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<textarea id="product_subscription_send_sms_paid" name="product_subscription_send_sms_paid" style="width:600px;max-width:100%;height:100px;"><?php echo esc_textarea($product->get_subscription_send_sms_paid())?></textarea>
							<p class="description">첫 결제가 성공한 후 알림 문자가 구매자에게 전송됩니다.</p>
							<p class="description">구매자가 결제 시 휴대폰 번호를 입력했을 경우에만 전송이 가능합니다. (결제 필드에 주문자 전화번호 필드를 추가하세요.)</p>
							<p class="description">문자 길이에 따라서 요금이 다르게 적용됩니다.</p>
							<p class="description">먼저 <a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a>이 되어있어야 합니다.</p>
							<p class="description">내용에 아래 특수문자를 입력하시면 실제 정보로 자동 치환됩니다.</p>
							<p class="description">주문자명:<code>#{NAME}</code></p>
							<p class="description">상품명:<code>#{PRODUCT}</code></p>
							<p class="description">결제가격:<code>#{PRICE}</code></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_send_sms_again">정기결제 문자 메시지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<textarea id="product_subscription_send_sms_again" name="product_subscription_send_sms_again" style="width:600px;max-width:100%;height:100px;"><?php echo esc_textarea($product->get_subscription_send_sms_again())?></textarea>
							<p class="description">첫 결제를 제외한 정기결제가 성공한 후 알림 문자가 구매자에게 전송됩니다.</p>
							<p class="description">구매자가 결제 시 휴대폰 번호를 입력했을 경우에만 전송이 가능합니다. (결제 필드에 주문자 전화번호 필드를 추가하세요.)</p>
							<p class="description">문자 길이에 따라서 요금이 다르게 적용됩니다.</p>
							<p class="description">먼저 <a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a>이 되어있어야 합니다.</p>
							<p class="description">내용에 아래 특수문자를 입력하시면 실제 정보로 자동 치환됩니다.</p>
							<p class="description">주문자명:<code>#{NAME}</code></p>
							<p class="description">상품명:<code>#{PRODUCT}</code></p>
							<p class="description">결제가격:<code>#{PRICE}</code></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_send_sms_again_failure">결제 실패 문자 메시지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<textarea id="product_subscription_send_sms_again_failure" name="product_subscription_send_sms_again_failure" style="width:600px;max-width:100%;height:100px;"><?php echo esc_textarea($product->get_subscription_send_sms_again_failure())?></textarea>
							<p class="description">정기결제가 실패하면 알림 문자가 구매자에게 전송됩니다.</p>
							<p class="description">구매자가 결제 시 휴대폰 번호를 입력했을 경우에만 전송이 가능합니다. (결제 필드에 주문자 전화번호 필드를 추가하세요.)</p>
							<p class="description">문자 길이에 따라서 요금이 다르게 적용됩니다.</p>
							<p class="description">먼저 <a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a>이 되어있어야 합니다.</p>
							<p class="description">내용에 아래 특수문자를 입력하시면 실제 정보로 자동 치환됩니다.</p>
							<p class="description">주문자명:<code>#{NAME}</code></p>
							<p class="description">상품명:<code>#{PRODUCT}</code></p>
							<p class="description">결제가격:<code>#{PRICE}</code></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_send_sms_expiry">만료 문자 메시지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<textarea id="product_subscription_send_sms_expiry" name="product_subscription_send_sms_expiry" style="width:600px;max-width:100%;height:100px;"><?php echo esc_textarea($product->get_subscription_send_sms_expiry())?></textarea>
							<p class="description">기간 또는 횟수가 만료된 경우 알림 문자가 구매자에게 전송됩니다.</p>
							<p class="description">구매자가 결제 시 휴대폰 번호를 입력했을 경우에만 전송이 가능합니다. (결제 필드에 주문자 전화번호 필드를 추가하세요.)</p>
							<p class="description">문자 길이에 따라서 요금이 다르게 적용됩니다.</p>
							<p class="description">먼저 <a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a>이 되어있어야 합니다.</p>
							<p class="description">내용에 아래 특수문자를 입력하시면 실제 정보로 자동 치환됩니다.</p>
							<p class="description">주문자명:<code>#{NAME}</code></p>
							<p class="description">상품명:<code>#{PRODUCT}</code></p>
							<p class="description">결제가격:<code>#{PRICE}</code></p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="변경 사항 저장">
			</p>
		</div>
		
		<div class="cosmosfarm-subscription-product-setting">
			<!-- 구매자 설정 -->
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_user_update">자동결제 상태 업데이트</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_user_update" name="product_subscription_user_update">
								<option value="">구매자 직접 변경 가능</option>
								<option value="1"<?php if(!$product->is_subscription_user_update()):?> selected<?php endif?>>구매자 직접 변경 불가</option>
							</select>
							<p class="description">자동결제(정기결제) 실행 여부를 구매자가 직접 변경할 수 있습니다.</p>
							<p class="description">구매자 직접 변경 불가인 경우 진행/중지 등 어떤 상태로도 구매자는 변경할 수 없고 관리자만 변경할 수 있습니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_user_update_message">자동결제 상태 업데이트 메시지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<input type="text" id="product_subscription_user_update_message" name="product_subscription_user_update_message" class="regular-text" value="<?php echo $product->get_subscription_user_update_message() ? esc_attr($product->get_subscription_user_update_message()) : '구매자가 직접 변경 불가능합니다.'?>">
							<p class="description"><label for="product_subscription_user_update" style="font-weight:bold">자동결제 상태 업데이트</label> 설정이 구매자 직접 변경 불가일 때 표시되는 메시지입니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_deactivate_pay_count">자동결제 중지 제한</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<input type="number" id="product_subscription_deactivate_pay_count" name="product_subscription_deactivate_pay_count" value="<?php echo esc_attr($product->get_subscription_deactivate_pay_count())?>"> 회차 이상이거나 같을 때 중지 가능
							<p class="description">입력된 회차 이상일 때 구매자가 자동결제를 중지할 수 있습니다.</p>
							<p class="description">빈칸일 경우 구매자는 언제든지 자동결제 상태를 변경할 수 있습니다.</p>
							<p class="description">진행 상태로는 항상 변경할 수 있습니다.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_deactivate_pay_count_message">자동결제 중지 메시지</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<input type="text" id="product_subscription_deactivate_pay_count_message" name="product_subscription_deactivate_pay_count_message" class="regular-text" value="<?php echo $product->get_subscription_deactivate_pay_count_message() ? esc_attr($product->get_subscription_deactivate_pay_count_message()) : '%d회차 이전에는 자동결제를 중지할 수 없습니다.'?>">
							<p class="description"><label for="product_subscription_deactivate_pay_count" style="font-weight:bold">자동결제 중지 제한</label> 설정된 경우 자동결제를 중지할 수 없을 때 표시되는 메시지입니다.</p>
							<p class="description">메시지 내용에 <code>%d</code> 입력시 자동으로 숫자로 변경됩니다.</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="변경 사항 저장">
			</p>
		</div>
		
		<?php if(cosmosfarm_members_is_advanced()):?>
		<div class="cosmosfarm-subscription-product-setting">
			<!-- 고급 기능 -->
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="product_subscription_item_active">확장된 정기결제 기능</label> <span style="font-size:12px;color:gray;">(선택)</span></th>
						<td>
							<select id="product_subscription_item_active" name="product_subscription_item_active">
								<option value="">비활성화</option>
								<option value="1"<?php if($product->is_subscription_item_active()):?> selected<?php endif?>>활성화</option>
							</select>
							<p class="description">확장된 정기결제 기능에 대해서는 코스모스팜 커뮤니티 또는 고객지원으로 문의해주세요.</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="변경 사항 저장">
			</p>
		</div>
		<?php endif?>
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

<?php foreach($product->get_admin_field_template_list() as $key=>$value):?>
<table style="display:none">
	<tbody class="cosmosfarm-members-field-<?php echo $key?>">
		<tr class="cosmosfarm-members-subscription-fields">
			<?php echo $product->get_admin_field_template(array('type'=>$key))?>
		</tr>
	</tbody>
</table>
<?php endforeach?>

<script>
jQuery(document).ready(function(){
	jQuery('#product-thumbnail-button').click(function(e){
		e.preventDefault();
		
		var frame = wp.media({
			library: {type: 'image'}
		});
		
		frame.on('select', function(){
			var attachment = frame.state().get('selection').first().toJSON();
			var img = jQuery('<img width="150" height="150" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">').attr('src', attachment.sizes.thumbnail.url);
			jQuery('#product-thumbnail-id').val(attachment.id);
			jQuery('#product-thumbnail-img').html('').append(img);
		});
		
		frame.open();
	});

	jQuery('#product-thumbnail-clear-button').click(function(){
		jQuery('#product-thumbnail-id').val('');
		jQuery('#product-thumbnail-img').html('');
	});
	
	jQuery('#product-gallery-button').click(function(e){
		e.preventDefault();
		
		var frame = wp.media({
			library: {type: 'image'}
		});
		
		frame.on('select', function(){
			var attachment = frame.state().get('selection').first().toJSON();
			var img = jQuery('<img width="150" height="150" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">').attr('src', attachment.sizes.thumbnail.url);
			var input = jQuery('<input type="hidden" name="product_gallery_images[]">').val(attachment.id);
			var li = jQuery('<li></li>').append(input).append(img);
			li.click(function(){
				jQuery(this).remove();
			});
			jQuery('#product-gallery-img').append(li);
		});
		
		frame.open();
	});
	
	jQuery('#product-gallery-img li').each(function(){
		jQuery(this).attr('title', '삭제').click(function(){
			jQuery(this).remove();
		});
	});
});
function cosmosfarm_members_product_setting_tab_init(){
	var index = location.hash.slice(1).replace('cosmosfarm-subscription-product-setting-', '');
	cosmosfarm_members_product_setting_tab_chnage(index);
}
cosmosfarm_members_product_setting_tab_init();
function cosmosfarm_members_product_setting_tab_chnage(index){
	jQuery('.cosmosfarm-subscription-product-setting-tab').removeClass('nav-tab-active').eq(index).addClass('nav-tab-active');
	jQuery('.cosmosfarm-subscription-product-setting').removeClass('cosmosfarm-subscription-product-setting-active').eq(index).addClass('cosmosfarm-subscription-product-setting-active');
	jQuery('input[name=cosmosfarm_subscription_product_setting]').val(index);
}

jQuery(function(){
	jQuery('#the-list').sortable();
});
function cosmosfarm_members_product_field_new(){
	var new_field_type = jQuery('select[name=new_field_type]').val();
	jQuery('#the-list').append(jQuery('.cosmosfarm-members-field-'+new_field_type).html());

	jQuery('#the-list .no-items').hide();
}
function cosmosfarm_members_product_field_delete(obj){
	jQuery(obj).parent().parent().remove();

	if(jQuery('#the-list tr').not('.no-items').length < 1){
		jQuery('#the-list .no-items').show();
	}
}

function cosmosfarm_members_product_save_submit(form){
	if(!jQuery('#product_title', form).val()){
		alert('상품 이름은 필수입니다.');
		cosmosfarm_members_product_setting_tab_chnage(0);
		jQuery('#product_title', form).focus();
		return false;
	}
	if(!jQuery('#product_price', form).val()){
		alert('가격은 필수입니다.');
		cosmosfarm_members_product_setting_tab_chnage(1);
		jQuery('#product_price', form).focus();
		return false;
	}
	return true;
}
</script>