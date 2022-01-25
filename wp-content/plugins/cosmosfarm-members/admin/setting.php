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
		<?php wp_nonce_field('cosmosfarm-members-setting-save', 'cosmosfarm-members-setting-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_setting_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_skin">스킨 선택</label></th>
					<td>
						<div style="overflow:hidden">
							<ul style="float:left;margin:0">
								<?php foreach(cosmosfarm_members_skins() as $skin):?>
								<li style="float:left;margin:5px;">
									<a href="<?php echo COSMOSFARM_MEMBERS_URL . '/skin/' . $skin->name . '/screenshot.png'?>" onclick="window.open(this.href);return false;" title="크게보기"><img src="<?php echo COSMOSFARM_MEMBERS_URL . '/skin/' . $skin->name . '/screenshot.png'?>" alt="<?php echo $skin->name?> 스킨" style="width:200px;vertical-align:middle"></a>
									<label style="display:block;padding:5px 0"><input type="radio" name="cosmosfarm_members_skin" value="<?php echo $skin->name?>"<?php if($option->skin == $skin->name):?> checked<?php endif?>><?php echo $skin->name?></label>
								</li>
								<?php endforeach?>
							</ul>
						</div>
						<p class="description">로그인, 회원가입, 회원정보 페이지의 레이아웃을 선택할 수 있습니다.</p>
						<p class="description">레이아웃 깨짐 또는 색상변경 등 문의는 <a href="https://www.cosmosfarm.com/threads" onclick="window.open(this.href);return false;">워드프레스 커뮤니티</a>에 올려주시면 도움 받으실 수 있습니다.</p>
						<p class="description">스킨 디자인을 기증해주시면 코스모스팜 회원관리 플러그인에 추가해서 업데이트하겠습니다. <a href="https://www.cosmosfarm.com/support" onclick="window.open(this.href);return false;">문의하기</a></p>
						<hr>
						<p class="description">로그인 폼의 레이아웃은 <code>/wp-content/plugins/cosmosfarm-members/skin/사용중인스킨/login-form.php</code> 파일을 편집해주세요.</p>
						<p class="description">또는 테마에 <code>/wp-content/themes/사용중인테마/cosmosfarm-members/login-form.php</code> 파일을 추가해서 편집해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_menu_add_login">메뉴에 로그인 링크 추가</label></th>
					<td>
						<?php if($nav_menus):?>
						<select id="cosmosfarm_menu_add_login" name="cosmosfarm_menu_add_login">
							<option value="">사용중지</option>
							<option value="1"<?php if(get_cosmosfarm_menu_add_login()):?> selected<?php endif?>>사용</option>
						</select>
						<?php endif?>
						<div>
							<?php foreach($nav_menus as $menu):?>
							<label><input type="checkbox" name="cosmosfarm_login_menus[]" value="<?php echo $menu->slug?>"<?php if(in_array($menu->slug, get_cosmosfarm_login_menus())):?> checked<?php endif?>><?php echo $menu->name?></label>
							<?php endforeach?>
						</div>
						<p class="description">메뉴에 로그인, 로그아웃, 회원가입, 회원정보 링크를 추가합니다. 메뉴가 없다면 먼저 생성해주세요. <a href="<?php echo admin_url('nav-menus.php')?>">메뉴 관리</a></p>
						<p class="description">메뉴를 추가할 수 없는 테마에서는 <code>[cosmosfarm_members_account_links]</code> 숏코드를 사용해서 링크를 출력할 수 있습니다.</p>
						<p class="description"><a href="https://blog.cosmosfarm.com/?p=462" onclick="window.open(this.href);return false;">워드프레스 메뉴에 로그인 로그아웃 추가하기</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_allow_email_login">이메일로 로그인하기</label></th>
					<td>
						<select id="cosmosfarm_members_allow_email_login" name="cosmosfarm_members_allow_email_login">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->allow_email_login):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">아이디 대신 이메일로 로그인 합니다. 회원가입시 아이디를 받지 않고 자동으로 생성합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_login_redirect_page">로그인 완료후 이동</label></th>
					<td>
						<select id="cosmosfarm_members_login_redirect_page" name="cosmosfarm_members_login_redirect_page">
							<option value="">로그인전 페이지로 되돌아가기 (기본설정)</option>
							<option value="main"<?php if($option->login_redirect_page == 'main'):?> selected<?php endif?>>메인페이지 (<?php echo home_url()?>)</option>
							<option value="url"<?php if($option->login_redirect_page == 'url'):?> selected<?php endif?>>주소 직접입력</option>
						</select>
						<input type="text" name="cosmosfarm_members_login_redirect_url" class="regular-text" value="<?php echo $option->login_redirect_url?>" placeholder="주소 직접입력">
						<p class="description">로그인후 이동될 페이지를 선택하거나 주소를 입력하세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_login_page_id">로그인 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_login_page_id" name="cosmosfarm_members_login_page_id">
							<option value="">주소 직접입력</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->login_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<input type="text" name="cosmosfarm_members_login_page_url" class="regular-text" value="<?php echo $option->login_page_url?>" placeholder="주소 직접입력">
						<p class="description"><code>[cosmosfarm_members_login_form]</code> 숏코드가 삽입된 로그인 페이지를 선택해주세요. 혹은 직접 페이지 주소를 입력할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_register_page_id">회원가입 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_register_page_id" name="cosmosfarm_members_register_page_id">
							<option value="">주소 직접입력</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->register_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<input type="text" name="cosmosfarm_members_register_page_url" class="regular-text" value="<?php echo $option->register_page_url?>" placeholder="주소 직접입력">
						<p class="description"><code>[wpmem_form register]</code> 숏코드가 삽입된 회원가입 페이지를 선택해주세요. 혹은 직접 페이지 주소를 입력할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_account_page_id">회원정보 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_account_page_id" name="cosmosfarm_members_account_page_id">
							<option value="">주소 직접입력</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->account_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<input type="text" name="cosmosfarm_members_account_page_url" class="regular-text" value="<?php echo $option->account_page_url?>" placeholder="주소 직접입력">
						<p class="description"><code>[wpmem_profile register=hide]</code> 숏코드가 삽입된 회원정보 페이지를 선택해주세요. 혹은 직접 페이지 주소를 입력할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_user_required">필수정보 반드시 입력</label></th>
					<td>
						<select id="cosmosfarm_members_user_required" name="cosmosfarm_members_user_required">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->user_required):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description"><a href="<?php echo admin_url('options-general.php?page=wpmem-settings&tab=fields')?>">WP-Members</a> 플러그인에서 필수로 선택된 필드 정보가 비어있다면 회원정보 페이지로 이동합니다.</p>
						<p class="description">소셜 로그인시 이메일 정보, 이용약관동의 등의 정보를 받을 땐 반드시 사용해주세요.</p>
						<p class="description">페이지가 자동으로 이동하기 때문에 필요하지 않다면 사용을 중지해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_page_restriction_redirect">페이지 제한 로그인 화면</label></th>
					<td>
						<select id="cosmosfarm_members_page_restriction_redirect" name="cosmosfarm_members_page_restriction_redirect">
							<option value="">[1] 제한된 페이지에 로그인 화면 표시</option>
							<option value="1"<?php if($option->page_restriction_redirect == '1'):?> selected<?php endif?>>[2] 알림 후 로그인 페이지로 이동</option>
							<option value="2"<?php if($option->page_restriction_redirect == '2'):?> selected<?php endif?>>[3] 알림 없이 로그인 페이지로 이동</option>
							<option value="3"<?php if($option->page_restriction_redirect == '3'):?> selected<?php endif?>>[4] 알림 후 회원가입 페이지로 이동</option>
							<option value="4"<?php if($option->page_restriction_redirect == '4'):?> selected<?php endif?>>[5] 알림 없이 회원가입 페이지로 이동</option>
						</select>
						<p class="description">비회원에게 로그인/회원가입 화면을 보여줍니다. 페이지 제한 설정은 각 페이지, 포스트, 카테고리 편집 화면에서 할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_page_restriction_message">페이지 제한 알림 메시지 [1]</label></th>
					<td>
						<?php wp_editor($option->page_restriction_message, 'cosmosfarm_members_page_restriction_message', array('editor_height'=>100))?>
						<p class="description">제한된 페이지에서 띄울 메시지를 짧게 입력해주세요. 빈칸이라면 기본 메시지가 보입니다.</p>
						<p class="description"><code><?php echo __('Log in to view this page.', 'cosmosfarm-members')?></code></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_page_restriction_alert_message">페이지 제한 알림 메시지 [2],[4]</label></th>
					<td>
						<input type="text" name="cosmosfarm_members_page_restriction_alert_message" class="regular-text" value="<?php echo $option->page_restriction_alert_message?>">
						<p class="description">제한된 페이지에서 띄울 메시지를 짧게 입력해주세요. 빈칸이라면 기본 메시지가 보입니다.</p>
						<p class="description"><code><?php echo __('Log in to view this page.', 'cosmosfarm-members')?></code></p>
						<p class="description"><code><?php echo __('Sign up to view this page.', 'cosmosfarm-members')?></code></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_page_restriction_permission_message">페이지 권한 없음 메시지</label></th>
					<td>
						<?php wp_editor($option->page_restriction_permission_message, 'cosmosfarm_members_page_restriction_permission_message', array('editor_height'=>100))?>
						<p class="description">제한된 페이지를 볼 권한이 없을 경우 표시될 메시지를 짧게 입력해주세요. 빈칸이라면 기본 메시지가 보입니다.</p>
						<p class="description"><code><?php echo __('You do not have permission to view this page.', 'cosmosfarm-members')?></code></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_postcode_service_disabled">우편번호/주소 검색 서비스</label></th>
					<td>
						<select id="cosmosfarm_members_postcode_service_disabled" name="cosmosfarm_members_postcode_service_disabled">
							<option value="">사용</option>
							<option value="1"<?php if($option->postcode_service_disabled):?> selected<?php endif?>>사용중지</option>
						</select>
						<p class="description">우편번호/주소 검색 서비스를 사용합니다.</p>
						<p class="description">우커머스에도 적용되며 사이트 언어가 한국어일 경우 사용 가능합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_postcode_service_iframe">우편번호/주소 검색 방식</label></th>
					<td>
						<select id="cosmosfarm_members_use_postcode_service_iframe" name="cosmosfarm_members_use_postcode_service_iframe">
							<option value="">새창으로 띄우기</option>
							<option value="1"<?php if($option->use_postcode_service_iframe):?> selected<?php endif?>>레이어로 띄우기</option>
						</select>
						<p class="description">우편번호/주소 검색창을 표시할 방법을 선택합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_social_buttons_shortcode_display">소셜 로그인 숏코드 표시</label></th>
					<td>
						<select id="cosmosfarm_members_social_buttons_shortcode_display" name="cosmosfarm_members_social_buttons_shortcode_display">
							<option value="">항상 표시</option>
							<option value="1"<?php if($option->social_buttons_shortcode_display):?> selected<?php endif?>>로그인 사용자에겐 숨기기</option>
						</select>
						<p class="description"><code>[cosmosfarm_members_social_buttons]</code> 숏코드로 만들어진 소셜 로그인 버튼의 표시 여부를 결정합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_delete_account">계정 삭제 (탈퇴 기능)</label></th>
					<td>
						<select id="cosmosfarm_members_use_delete_account" name="cosmosfarm_members_use_delete_account">
							<option value="">비활성화</option>
							<option value="1"<?php if($option->use_delete_account):?> selected<?php endif?>>활성화</option>
						</select>
						<p class="description">사용자가 직접 회원정보 페이지에서 계정을 삭제할 수 있도록 합니다.</p>
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
		
		<hr>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_social_login_active">소셜로그인</label></th>
					<td>
						<div style="overflow:hidden">
							<ul id="cosmosfarm_members_social_login_active" style="float:left;margin:0">
								<?php foreach($option->social_login_active as $key=>$channel):?>
								<li class="ui-state-default">
									<p>
										<label><input type="checkbox" name="cosmosfarm_members_social_login_active[]" value="<?php echo $channel?>" checked><?php echo $option->channel[$channel]?></label>
										<?php if($channel=='instagram'):?><strong>페이스북의 정책으로 인해서 인스타그램 소셜 로그인은 중단 되었습니다.</strong><?php endif?>
									</p>
									<?php if($channel == 'naver'):?>
									<p>
										<label>Client ID</label>
										<input type="text" name="cosmosfarm_members_naver_client_id" value="<?php echo $option->naver_client_id?>">
									</p>
									<p>
										<label>Client Secret</label>
										<input type="text" name="cosmosfarm_members_naver_client_secret" value="<?php echo $option->naver_client_secret?>">
									</p>
									<p>
										<label>Callback URL</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://developers.naver.com/apps/#/register" onclick="window.open(this.href);return false;">https://developers.naver.com/apps/#/register</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220959853733" onclick="window.open(this.href);return false;">네이버 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'facebook'):?>
									<p>
										<label>앱 ID</label>
										<input type="text" name="cosmosfarm_members_facebook_client_id" value="<?php echo $option->facebook_client_id?>">
									</p>
									<p>
										<label>앱 시크릿 코드</label>
										<input type="text" name="cosmosfarm_members_facebook_client_secret" value="<?php echo $option->facebook_client_secret?>">
									</p>
									<p>
										<label>OAuth 리디렉션 URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://developers.facebook.com/apps/" onclick="window.open(this.href);return false;">https://developers.facebook.com/apps/</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220958859734" onclick="window.open(this.href);return false;">페이스북 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'kakao'):?>
									<p>
										<label>REST API 키</label>
										<input type="text" name="cosmosfarm_members_kakao_client_id" value="<?php echo $option->kakao_client_id?>">
									</p>
									<p>
										<label>로그인 Redirect URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://developers.kakao.com/console/app" onclick="window.open(this.href);return false;">https://developers.kakao.com/console/app</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.cosmosfarm.com/?p=1202" onclick="window.open(this.href);return false;">카카오 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'google'):?>
									<p>
										<label>클라이언트 ID</label>
										<input type="text" name="cosmosfarm_members_google_client_id" value="<?php echo $option->google_client_id?>">
									</p>
									<p>
										<label>클라이언트 보안 비밀</label>
										<input type="text" name="cosmosfarm_members_google_client_secret" value="<?php echo $option->google_client_secret?>">
									</p>
									<p>
										<label>승인된 리디렉션 URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://console.developers.google.com/" onclick="window.open(this.href);return false;">https://console.developers.google.com/</a> 페이지에서 프로젝트를 만들어주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220959813418" onclick="window.open(this.href);return false;">구글 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'twitter'):?>
									<p>
										<label>Consumer Key (API Key)</label>
										<input type="text" name="cosmosfarm_members_twitter_client_id" value="<?php echo $option->twitter_client_id?>">
									</p>
									<p>
										<label>Consumer Secret (API Secret)</label>
										<input type="text" name="cosmosfarm_members_twitter_client_secret" value="<?php echo $option->twitter_client_secret?>">
									</p>
									<p>
										<label>Callback URL</label>
										<input type="text" value="<?php echo site_url('/')?>" readonly>
									</p>
									<p>
										<a href="https://developer.twitter.com/en/apps/create" onclick="window.open(this.href);return false;">https://developer.twitter.com/en/apps/create</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.cosmosfarm.com/?p=732" onclick="window.open(this.href);return false;">트위터 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'instagram'):?>
									<p>
										<label>Client ID</label>
										<input type="text" name="cosmosfarm_members_instagram_client_id" value="<?php echo $option->instagram_client_id?>">
									</p>
									<p>
										<label>Client Secret</label>
										<input type="text" name="cosmosfarm_members_instagram_client_secret" value="<?php echo $option->instagram_client_secret?>">
									</p>
									<p>
										<label>Website URL</label>
										<input type="text" value="<?php echo site_url()?>" readonly>
									</p>
									<p>
										<label>Valid redirect URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://www.instagram.com/developer/clients/register/" onclick="window.open(this.href);return false;">https://www.instagram.com/developer/clients/register/</a> 페이지에서 Client를 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220960406624" onclick="window.open(this.href);return false;">인스타그램 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'line'):?>
									<p>
										<label>Channel ID</label>
										<input type="text" name="cosmosfarm_members_line_client_id" value="<?php echo $option->line_client_id?>">
									</p>
									<p>
										<label>Channel Secret</label>
										<input type="text" name="cosmosfarm_members_line_client_secret" value="<?php echo $option->line_client_secret?>">
									</p>
									<p>
										<label>Callback URL</label>
										<input type="text" value="<?php echo site_url()?>" readonly>
									</p>
									<p>
										<a href="https://business.line.me/services/login" onclick="window.open(this.href);return false;">https://business.line.me/services/login</a> 페이지에서 Channel을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220960346707" onclick="window.open(this.href);return false;">라인 소셜 로그인 설정 방법</a>
									</p>
									<?php endif?>
								</li>
								<?php endforeach?>
								<?php foreach($option->channel as $channel=>$value): if(!in_array($channel, $option->social_login_active)):?>
								<li class="ui-state-default">
									<p>
										<label><input type="checkbox" name="cosmosfarm_members_social_login_active[]" value="<?php echo $channel?>"><?php echo $option->channel[$channel]?></label>
										<?php if($channel=='instagram'):?><strong>페이스북의 정책으로 인해서 인스타그램 소셜 로그인은 중단 되었습니다.</strong><?php endif?>
									</p>
									<?php if($channel == 'naver'):?>
									<p>
										<label>Client ID</label>
										<input type="text" name="cosmosfarm_members_naver_client_id" value="<?php echo $option->naver_client_id?>">
									</p>
									<p>
										<label>Client Secret</label>
										<input type="text" name="cosmosfarm_members_naver_client_secret" value="<?php echo $option->naver_client_secret?>">
									</p>
									<p>
										<label>Callback URL</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://developers.naver.com/apps/#/register" onclick="window.open(this.href);return false;">https://developers.naver.com/apps/#/register</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220959853733" onclick="window.open(this.href);return false;">네이버 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'facebook'):?>
									<p>
										<label>앱 ID</label>
										<input type="text" name="cosmosfarm_members_facebook_client_id" value="<?php echo $option->facebook_client_id?>">
									</p>
									<p>
										<label>앱 시크릿 코드</label>
										<input type="text" name="cosmosfarm_members_facebook_client_secret" value="<?php echo $option->facebook_client_secret?>">
									</p>
									<p>
										<label>OAuth 리디렉션 URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://developers.facebook.com/apps/" onclick="window.open(this.href);return false;">https://developers.facebook.com/apps/</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220958859734" onclick="window.open(this.href);return false;">페이스북 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'kakao'):?>
									<p>
										<label>REST API 키</label>
										<input type="text" name="cosmosfarm_members_kakao_client_id" value="<?php echo $option->kakao_client_id?>">
									</p>
									<p>
										<label>로그인 Redirect URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://developers.kakao.com/console/app" onclick="window.open(this.href);return false;">https://developers.kakao.com/console/app</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.cosmosfarm.com/?p=1202" onclick="window.open(this.href);return false;">카카오 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'google'):?>
									<p>
										<label>클라이언트 ID</label>
										<input type="text" name="cosmosfarm_members_google_client_id" value="<?php echo $option->google_client_id?>">
									</p>
									<p>
										<label>클라이언트 보안 비밀</label>
										<input type="text" name="cosmosfarm_members_google_client_secret" value="<?php echo $option->google_client_secret?>">
									</p>
									<p>
										<label>승인된 리디렉션 URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://console.developers.google.com/" onclick="window.open(this.href);return false;">https://console.developers.google.com/</a> 페이지에서 프로젝트를 만들고 Google API를 사용 설정해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220959813418" onclick="window.open(this.href);return false;">구글 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'twitter'):?>
									<p>
										<label>Consumer Key (API Key)</label>
										<input type="text" name="cosmosfarm_members_twitter_client_id" value="<?php echo $option->twitter_client_id?>">
									</p>
									<p>
										<label>Consumer Secret (API Secret)</label>
										<input type="text" name="cosmosfarm_members_twitter_client_secret" value="<?php echo $option->twitter_client_secret?>">
									</p>
									<p>
										<label>Callback URL</label>
										<input type="text" value="<?php echo site_url('/')?>" readonly>
									</p>
									<p>
										<a href="https://developer.twitter.com/en/apps/create" onclick="window.open(this.href);return false;">https://developer.twitter.com/en/apps/create</a> 페이지에서 애플리케이션을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.cosmosfarm.com/?p=732" onclick="window.open(this.href);return false;">트위터 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'instagram'):?>
									<p>
										<label>Client ID</label>
										<input type="text" name="cosmosfarm_members_instagram_client_id" value="<?php echo $option->instagram_client_id?>">
									</p>
									<p>
										<label>Client Secret</label>
										<input type="text" name="cosmosfarm_members_instagram_client_secret" value="<?php echo $option->instagram_client_secret?>">
									</p>
									<p>
										<label>Website URL</label>
										<input type="text" value="<?php echo site_url()?>" readonly>
									</p>
									<p>
										<label>Valid redirect URI</label>
										<input type="text" value="<?php echo site_url('?action=cosmosfarm_members_social_login_callback_' . $channel)?>" readonly>
									</p>
									<p>
										<a href="https://www.instagram.com/developer/clients/register/" onclick="window.open(this.href);return false;">https://www.instagram.com/developer/clients/register/</a> 페이지에서 Client를 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220960406624" onclick="window.open(this.href);return false;">인스타그램 소셜 로그인 설정 방법</a>
									</p>
									<?php elseif($channel == 'line'):?>
									<p>
										<label>Channel ID</label>
										<input type="text" name="cosmosfarm_members_line_client_id" value="<?php echo $option->line_client_id?>">
									</p>
									<p>
										<label>Channel Secret</label>
										<input type="text" name="cosmosfarm_members_line_client_secret" value="<?php echo $option->line_client_secret?>">
									</p>
									<p>
										<label>Callback URL</label>
										<input type="text" value="<?php echo site_url()?>" readonly>
									</p>
									<p>
										<a href="https://business.line.me/services/login" onclick="window.open(this.href);return false;">https://business.line.me/services/login</a> 페이지에서 Channel을 등록해주세요.
									</p>
									<p>
										<a href="https://blog.naver.com/chan2rrj/220960346707" onclick="window.open(this.href);return false;">라인 소셜 로그인 설정 방법</a>
									</p>
									<?php endif?>
								</li>
								<?php endif; endforeach?>
							</ul>
						</div>
						<p class="description">소셜로그인 사용할 서비스를 체크하고 순서를 마우스로 드래그 하세요.</p>
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
jQuery(function(){
	jQuery('#cosmosfarm_members_social_login_active').sortable();
});
</script>