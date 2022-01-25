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
		<?php wp_nonce_field('cosmosfarm-members-security-save', 'cosmosfarm-members-security-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_security_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_strong_password">강력한 비밀번호 사용</label></th>
					<td>
						<select id="cosmosfarm_members_use_strong_password" name="cosmosfarm_members_use_strong_password">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->use_strong_password):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">복잡한 비밀번호를 반드시 사용하도록 합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_save_login_history">로그인 기록 저장</label></th>
					<td>
						<select id="cosmosfarm_members_save_login_history" name="cosmosfarm_members_save_login_history">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->save_login_history):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">언제 어디서 로그인을 시도했는지 IP주소와 성공 여부 등 정보를 저장합니다.<br><a href="<?php echo admin_url('users.php')?>">사용자 리스트</a>에 마지막 로그인 시간을 표시합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_login_protect">로그인 공격 보안</label></th>
					<td>
						<?php if($option->save_login_history):?>
						<select id="cosmosfarm_members_use_login_protect" name="cosmosfarm_members_use_login_protect">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->use_login_protect):?> selected<?php endif?>>사용</option>
						</select>
						<p>
							비밀번호를
							<select id="cosmosfarm_members_login_protect_time" name="cosmosfarm_members_login_protect_time">
								<option value="1"<?php if($option->login_protect_time == '1'):?> selected<?php endif?>>1분</option>
								<option value="2"<?php if($option->login_protect_time == '2'):?> selected<?php endif?>>2분</option>
								<option value="3"<?php if($option->login_protect_time == '3'):?> selected<?php endif?>>3분</option>
								<option value="4"<?php if($option->login_protect_time == '4'):?> selected<?php endif?>>4분</option>
								<option value="5"<?php if($option->login_protect_time == '5'):?> selected<?php endif?>>5분</option>
								<option value="10"<?php if($option->login_protect_time == '10'):?> selected<?php endif?>>10분</option>
								<option value="15"<?php if($option->login_protect_time == '15'):?> selected<?php endif?>>15분</option>
								<option value="20"<?php if($option->login_protect_time == '20'):?> selected<?php endif?>>20분</option>
								<option value="30"<?php if($option->login_protect_time == '30'):?> selected<?php endif?>>30분</option>
								<option value="40"<?php if($option->login_protect_time == '40'):?> selected<?php endif?>>40분</option>
								<option value="50"<?php if($option->login_protect_time == '50'):?> selected<?php endif?>>50분</option>
								<option value="60"<?php if($option->login_protect_time == '60'):?> selected<?php endif?>>60분</option>
							</select>
							동안
							<select id="cosmosfarm_members_login_protect_count" name="cosmosfarm_members_login_protect_count">
								<option value="1"<?php if($option->login_protect_count == '1'):?> selected<?php endif?>>1회</option>
								<option value="2"<?php if($option->login_protect_count == '2'):?> selected<?php endif?>>2회</option>
								<option value="3"<?php if($option->login_protect_count == '3'):?> selected<?php endif?>>3회</option>
								<option value="4"<?php if($option->login_protect_count == '4'):?> selected<?php endif?>>4회</option>
								<option value="5"<?php if($option->login_protect_count == '5'):?> selected<?php endif?>>5회</option>
								<option value="6"<?php if($option->login_protect_count == '6'):?> selected<?php endif?>>6회</option>
								<option value="7"<?php if($option->login_protect_count == '7'):?> selected<?php endif?>>7회</option>
								<option value="8"<?php if($option->login_protect_count == '8'):?> selected<?php endif?>>8회</option>
								<option value="9"<?php if($option->login_protect_count == '9'):?> selected<?php endif?>>9회</option>
								<option value="10"<?php if($option->login_protect_count == '10'):?> selected<?php endif?>>10회</option>
							</select>
							틀릴 경우
							<select id="cosmosfarm_members_login_protect_lockdown" name="cosmosfarm_members_login_protect_lockdown">
								<option value="1"<?php if($option->login_protect_lockdown == '1'):?> selected<?php endif?>>1분</option>
								<option value="2"<?php if($option->login_protect_lockdown == '2'):?> selected<?php endif?>>2분</option>
								<option value="3"<?php if($option->login_protect_lockdown == '3'):?> selected<?php endif?>>3분</option>
								<option value="4"<?php if($option->login_protect_lockdown == '4'):?> selected<?php endif?>>4분</option>
								<option value="5"<?php if($option->login_protect_lockdown == '5'):?> selected<?php endif?>>5분</option>
								<option value="10"<?php if($option->login_protect_lockdown == '10'):?> selected<?php endif?>>10분</option>
								<option value="15"<?php if($option->login_protect_lockdown == '15'):?> selected<?php endif?>>15분</option>
								<option value="20"<?php if($option->login_protect_lockdown == '20'):?> selected<?php endif?>>20분</option>
								<option value="30"<?php if($option->login_protect_lockdown == '30'):?> selected<?php endif?>>30분</option>
								<option value="40"<?php if($option->login_protect_lockdown == '40'):?> selected<?php endif?>>40분</option>
								<option value="50"<?php if($option->login_protect_lockdown == '50'):?> selected<?php endif?>>50분</option>
								<option value="60"<?php if($option->login_protect_lockdown == '60'):?> selected<?php endif?>>60분</option>
							</select>
							동안 로그인 시도를 제한합니다.
						</p>
						<?php else:?>
						<p>※ 로그인 기록 저장을 사용해주세요.</p>
						<?php endif?>
						<p class="description">무차별 대입 공격(Brute Force Attack)을 차단하기 위해서 비밀번호를 연속해서 잘못 입력하면 잠시동안 로그인을 차단합니다.</p>
						<p class="description">관리자 계정도 예외 없이 차단됩니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_dormant_member">휴면 회원 자동 삭제</label></th>
					<td>
						<?php if($option->save_login_history):?>
							<select id="cosmosfarm_members_use_dormant_member" name="cosmosfarm_members_use_dormant_member">
								<option value="">사용중지</option>
								<option value="1"<?php if($option->use_dormant_member):?> selected<?php endif?>>사용</option>
							</select>
							<?php if($option->use_dormant_member):?>
								<a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_security&tab=dormant_member'))?>" class="button">상세 설정</a>
							<?php endif?>
						<?php else:?>
						<p>※ 로그인 기록 저장을 사용해주세요.</p>
						<?php endif?>
						<p class="description">1년 이상 로그인 기록이 없는 회원 정보를 자동으로 삭제 처리합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_login_timeout">자동 로그아웃</label></th>
					<td>
						<select id="cosmosfarm_members_use_login_timeout" name="cosmosfarm_members_use_login_timeout">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->use_login_timeout == '1'):?> selected<?php endif?>>자동 로그아웃 후 로그인 페이지로 이동</option>
							<option value="2"<?php if($option->use_login_timeout == '2'):?> selected<?php endif?>>자동 로그아웃 후 원래 있던 페이지로 되돌아오기</option>
						</select>
						<p>
							<select id="cosmosfarm_members_login_timeout" name="cosmosfarm_members_login_timeout">
								<option value="1"<?php if($option->login_timeout == '1'):?> selected<?php endif?>>1분</option>
								<option value="2"<?php if($option->login_timeout == '2'):?> selected<?php endif?>>2분</option>
								<option value="3"<?php if($option->login_timeout == '3'):?> selected<?php endif?>>3분</option>
								<option value="4"<?php if($option->login_timeout == '4'):?> selected<?php endif?>>4분</option>
								<option value="5"<?php if($option->login_timeout == '5'):?> selected<?php endif?>>5분</option>
								<option value="6"<?php if($option->login_timeout == '6'):?> selected<?php endif?>>6분</option>
								<option value="7"<?php if($option->login_timeout == '7'):?> selected<?php endif?>>7분</option>
								<option value="8"<?php if($option->login_timeout == '8'):?> selected<?php endif?>>8분</option>
								<option value="9"<?php if($option->login_timeout == '9'):?> selected<?php endif?>>9분</option>
								<option value="10"<?php if($option->login_timeout == '10'):?> selected<?php endif?>>10분</option>
								<option value="15"<?php if($option->login_timeout == '15'):?> selected<?php endif?>>15분</option>
								<option value="20"<?php if($option->login_timeout == '20'):?> selected<?php endif?>>20분</option>
								<option value="30"<?php if($option->login_timeout == '30'):?> selected<?php endif?>>30분</option>
								<option value="40"<?php if($option->login_timeout == '40'):?> selected<?php endif?>>40분</option>
								<option value="50"<?php if($option->login_timeout == '50'):?> selected<?php endif?>>50분</option>
								<option value="60"<?php if($option->login_timeout == '60'):?> selected<?php endif?>>1시간</option>
								<option value="120"<?php if($option->login_timeout == '120'):?> selected<?php endif?>>2시간</option>
								<option value="180"<?php if($option->login_timeout == '180'):?> selected<?php endif?>>3시간</option>
								<option value="240"<?php if($option->login_timeout == '240'):?> selected<?php endif?>>4시간</option>
								<option value="300"<?php if($option->login_timeout == '300'):?> selected<?php endif?>>5시간</option>
								<option value="360"<?php if($option->login_timeout == '360'):?> selected<?php endif?>>6시간</option>
							</select>
							동안 페이지 이동이 없으면 자동으로 로그아웃을 합니다.
						</p>
						<p class="description">사용자의 활동이 없을 경우 개인정보 보호를 위해서 자동으로 로그아웃을 합니다.</p>
						<p class="description">너무 긴 시간을 설정하면 사용자의 브라우저에 따라서 제대로 동작하지 않을 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_save_activity_history">개인정보 활동 기록 저장</label></th>
					<td>
						<select id="cosmosfarm_members_save_activity_history" name="cosmosfarm_members_save_activity_history">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->save_activity_history):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">본인 또는 관리자가 회원정보를 조회하거나 업데이트한 시간, IP주소 등 정보를 저장합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_active_closed_site">폐쇄형 홈페이지</label></th>
					<td>
						<select id="cosmosfarm_members_active_closed_site" name="cosmosfarm_members_active_closed_site">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->active_closed_site):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">메인 페이지를 포함해서 모든 페이지를 로그인한 사용자만 접근할 수 있도록 합니다.</p>
						<p class="description">모든 페이지에서 로그인하지 않은 사용자는 자동으로 로그인 페이지로 이동합니다.</p>
						<p class="description">설정에서 로그인 페이지가 선택되어 있어야 실제로 동작합니다.</p>
						<p class="description"><code>cosmosfarm_members_closed_site_allow_pages</code> 필터를 사용해서 접근가능한 페이지를 편집할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_login_otp_phone_number">관리자 문자 인증 로그인</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_login_otp_phone_number" name="cosmosfarm_members_login_otp_phone_number" value="<?php echo esc_attr($option->login_otp_phone_number)?>" placeholder="휴대폰 번호 입력">
						<p class="description">먼저 <a href="<?php echo esc_url(admin_url('admin.php?page=cosmosfarm_members_sms_setting'))?>" onclick="window.open(this.href);return false;">SMS 설정</a>이 되어있어야 합니다.</p>
						<p class="description">관리자의 휴대폰 번호를 입력하면 동작하고 입력된 휴대폰 번호로 OTP 인증번호가 전송됩니다.</p>
						<div class="" style="margin-top: 10px;">
							<input type="hidden" name="cosmosfarm_members_login_otp_user_roles" value="">
							<?php $login_otp_user_roles = $option->login_otp_user_roles?>
							<?php foreach(get_editable_roles() as $key=>$value):?>
								<label><input type="checkbox" name="cosmosfarm_members_login_otp_user_roles[]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator' || in_array($key, $login_otp_user_roles)):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label>
							<?php endforeach?>
						</div>
						<p class="description">선택된 사용자 역할의 계정 로그인시 문자 인증 과정을 추가합니다.</p>
						<p class="description">각각 사용자의 <code>billing_phone</code> 필드에 휴대폰 번호가 입력되어 있다면 이쪽으로 OTP 인증번호가 전송됩니다.</p>
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