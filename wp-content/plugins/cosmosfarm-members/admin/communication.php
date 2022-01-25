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
		<?php wp_nonce_field('cosmosfarm-members-communication-save', 'cosmosfarm-members-communication-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_communication_save">
		
		<h3>휴대폰 번호 설정</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_subnotify_sms_field">휴대폰 번호 필드</label></th>
					<td>
						<select id="cosmosfarm_members_subnotify_sms_field" name="cosmosfarm_members_subnotify_sms_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->subnotify_sms_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">SMS 알림, 알림톡 전송시 참조할 휴대폰 번호 필드를 선택해주세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<hr>
		
		<h3>알림 설정</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_page_id">알림 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_notifications_page_id" name="cosmosfarm_members_notifications_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->notifications_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description"><code>[cosmosfarm_members_notifications]</code> 숏코드가 삽입된 알림 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_kboard">KBoard 게시판 알림</label></th>
					<td>
						<select id="cosmosfarm_members_notifications_kboard" name="cosmosfarm_members_notifications_kboard">
							<option value="">사용안함</option>
							<option value="1"<?php if($option->notifications_kboard):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">게시글에 새로운 답글 혹은 댓글이 달리면 알림을 받을 수 있도록 허용합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_kboard_comments">KBoard 댓글 알림</label></th>
					<td>
						<select id="cosmosfarm_members_notifications_kboard_comments" name="cosmosfarm_members_notifications_kboard_comments">
							<option value="">사용안함</option>
							<option value="1"<?php if($option->notifications_kboard_comments):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">댓글에 새로운 답글(댓글)이 달리면 알림을 받을 수 있도록 허용합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label>새로운 알림이 등록되면</label></th>
					<td>
						<p><label><input type="checkbox" name="cosmosfarm_members_notifications_subnotify_email" value="1"<?php if($option->notifications_subnotify_email):?> checked<?php endif?>> 이메일 알림 받기 허용</label></p>
						<?php if(get_cosmosfarm_members_sms()->is_active() && $option->subnotify_sms_field):?>
						<p><label><input type="checkbox" name="cosmosfarm_members_notifications_subnotify_sms" value="1"<?php if($option->notifications_subnotify_sms):?> checked<?php endif?>> SMS(알림톡) 알림 받기 허용</label></p>
						<?php else:?>
						<p style="text-decoration:line-through"><label><input type="checkbox" name="cosmosfarm_members_notifications_subnotify_sms" value="" disabled> SMS(알림톡) 알림 받기 허용</label></p>
						<?php endif?>
						<p class="description">새로운 알림이 등록되면 다른 수단으로 알림을 받을 수 있도록 사용자가 직접 설정할 수 있습니다.</p>
						<p class="description">짧은 메시지가 전송되며 전체 내용이 전송되지는 않습니다. 전체 내용은 홈페이지에서 확인할 수 있습니다.</p>
						<p class="description">이메일 혹은 SMS 알림 전송시 비용이 발생될 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_subnotify_email_title">이메일 알림 제목</label></th>
					<td>
						<input type="text" name="cosmosfarm_members_notifications_subnotify_email_title" class="regular-text" value="<?php echo $option->notifications_subnotify_email_title?$option->notifications_subnotify_email_title:'새로운 알림이 등록됐습니다.'?>">
						<p class="description">이메일 알림의 제목입니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_subnotify_email_content">이메일 알림 내용</label></th>
					<td>
						<textarea name="cosmosfarm_members_notifications_subnotify_email_content" rows="6" class="regular-text"><?php echo $option->notifications_subnotify_email_content?$option->notifications_subnotify_email_content:sprintf("새로운 알림이 등록됐습니다.\n홈페이지에서 확인해주세요.\n<a href=\"%s\" target=\"_blank\">%s</a>", home_url(), home_url())?></textarea>
						<p class="description">이메일 알림의 내용으로 짧은 메시지를 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_subnotify_sms_message">SMS 알림 내용</label></th>
					<td>
						<input type="text" name="cosmosfarm_members_notifications_subnotify_sms_message" class="regular-text" value="<?php echo $option->notifications_subnotify_sms_message?$option->notifications_subnotify_sms_message:'새로운 알림이 등록됐습니다. 홈페이지에서 확인해주세요.'?>">
						<p class="description">90자 이내의 짧은 내용을 입력해주세요.</p>
						<?php /*
						<p class="description"><a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_sms_setting')?>" onclick="window.open(this.href);return false;">SMS 서비스 설정</a>이 카카오 알림톡일 경우 적용되지 않습니다.</p>
						*/?>
						<p class="description">SMS로 알림을 보내시려면 <label for="cosmosfarm_members_notifications_subnotify_alimtalk_template" style="font-weight:bold">알림톡 템플릿</label> 설정을 사용안함(SMS 사용)으로 세팅해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_notifications_subnotify_alimtalk_template">알림톡 템플릿</label></th>
					<td>
						<?php if($option->alimtalk_template):?>
						<select id="cosmosfarm_members_notifications_subnotify_alimtalk_template" name="cosmosfarm_members_notifications_subnotify_alimtalk_template">
							<option value="">사용안함(SMS 사용)</option>
							<?php foreach($option->alimtalk_template as $item):?>
							<option value="<?php echo $item['template_code']?>"<?php if($item['template_code'] == $option->notifications_subnotify_alimtalk_template):?> selected<?php endif?>><?php echo $item['name']?></option>
							<?php endforeach?>
						</select>
						<?php else:?>
						<code>등록된 템플릿이 없습니다.</code>
						<?php endif?>
						<?php /*
						<p class="description"><a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_sms_setting')?>" onclick="window.open(this.href);return false;">SMS 서비스 설정</a>이 카카오 알림톡일 때만 적용됩니다.</p>
						*/?>
						<p class="description">알림톡으로 알림을 보내시려면 <label for="cosmosfarm_members_notifications_subnotify_alimtalk_template" style="font-weight:bold">알림톡 템플릿</label> 설정을 세팅해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">개발자 팁 - 필터</label></th>
					<td>
						<code>cosmosfarm_members_notifications_subnotify_email_args</code>, <code>cosmosfarm_members_notifications_subnotify_sms_args</code>
						<p class="description">필터에 프로그램 코드를 추가해서 동적으로 설정을 변경할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">개발자 팁 - 액션</label></th>
					<td>
						<code>cosmosfarm_members_send_notification</code>
						<p class="description">액션에 새로운 프로그램을 추가할 수 있습니다.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<hr>
		
		<h3>쪽지 설정</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_messages_page_id">쪽지 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_messages_page_id" name="cosmosfarm_members_messages_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->messages_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description"><code>[cosmosfarm_members_messages]</code> 숏코드가 삽입된 쪽지 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label>새로운 쪽지가 등록되면</label></th>
					<td>
						<p><label><input type="checkbox" name="cosmosfarm_members_messages_subnotify_email" value="1"<?php if($option->messages_subnotify_email):?> checked<?php endif?>> 이메일 알림 받기 허용</label></p>
						<?php if(get_cosmosfarm_members_sms()->is_active() && $option->subnotify_sms_field):?>
						<p><label><input type="checkbox" name="cosmosfarm_members_messages_subnotify_sms" value="1"<?php if($option->messages_subnotify_sms):?> checked<?php endif?>> SMS(알림톡) 알림 받기 허용</label></p>
						<?php else:?>
						<p style="text-decoration:line-through"><label><input type="checkbox" name="cosmosfarm_members_messages_subnotify_sms" value="" disabled> SMS(알림톡) 알림 받기 허용</label></p>
						<?php endif?>
						<p class="description">새로운 쪽지가 등록되면 다른 수단으로 알림을 받을 수 있도록 사용자가 직접 설정할 수 있습니다.</p>
						<p class="description">짧은 메시지가 전송되며 전체 내용이 전송되지는 않습니다. 전체 내용은 홈페이지에서 확인할 수 있습니다.</p>
						<p class="description">이메일 혹은 SMS 알림 전송시 비용이 발생될 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_messages_subnotify_email_title">이메일 알림 제목</label></th>
					<td>
						<input type="text" name="cosmosfarm_members_messages_subnotify_email_title" class="regular-text" value="<?php echo $option->messages_subnotify_email_title?$option->messages_subnotify_email_title:'새로운 쪽지가 도착했습니다.'?>">
						<p class="description">이메일 알림의 제목입니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_messages_subnotify_email_content">이메일 알림 내용</label></th>
					<td>
						<textarea name="cosmosfarm_members_messages_subnotify_email_content" rows="6" class="regular-text"><?php echo $option->messages_subnotify_email_content?$option->messages_subnotify_email_content:sprintf("새로운 쪽지가 도착했습니다.\n홈페이지에서 확인해주세요.\n<a href=\"%s\" target=\"_blank\">%s</a>", home_url(), home_url())?></textarea>
						<p class="description">이메일 알림의 내용으로 짧은 메시지를 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_messages_subnotify_sms_message">SMS 알림 내용</label></th>
					<td>
						<input type="text" name="cosmosfarm_members_messages_subnotify_sms_message" class="regular-text" value="<?php echo $option->messages_subnotify_sms_message?$option->messages_subnotify_sms_message:'새로운 쪽지가 도착했습니다. 홈페이지에서 확인해주세요.'?>">
						<p class="description">90자 이내의 짧은 내용을 입력해주세요.</p>
						<?php /*
						<p class="description"><a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_sms_setting')?>" onclick="window.open(this.href);return false;">SMS 서비스 설정</a>이 카카오 알림톡일 경우 적용되지 않습니다.</p>
						*/?>
						<p class="description">SMS로 알림을 보내시려면 <label for="cosmosfarm_members_messages_subnotify_alimtalk_template" style="font-weight:bold">알림톡 템플릿</label> 설정을 사용안함(SMS 사용)으로 세팅해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_messages_subnotify_alimtalk_template">알림톡 템플릿</label></th>
					<td>
						<?php if($option->alimtalk_template):?>
						<select id="cosmosfarm_members_messages_subnotify_alimtalk_template" name="cosmosfarm_members_messages_subnotify_alimtalk_template">
							<option value="">사용안함(SMS 사용)</option>
							<?php foreach($option->alimtalk_template as $item):?>
							<option value="<?php echo $item['template_code']?>"<?php if($item['template_code'] == $option->messages_subnotify_alimtalk_template):?> selected<?php endif?>><?php echo $item['name']?></option>
							<?php endforeach?>
						</select>
						<?php else:?>
						<code>등록된 템플릿이 없습니다.</code>
						<?php endif?>
						<?php /*
						<p class="description"><a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_sms_setting')?>" onclick="window.open(this.href);return false;">SMS 서비스 설정</a>이 카카오 알림톡일 때만 적용됩니다.</p>
						*/?>
						<p class="description">알림톡으로 알림을 보내시려면 <label for="cosmosfarm_members_messages_subnotify_alimtalk_template" style="font-weight:bold">알림톡 템플릿</label> 설정을 세팅해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">개발자 팁 - 필터</label></th>
					<td>
						<code>cosmosfarm_members_messages_subnotify_email_args</code>, <code>cosmosfarm_members_messages_subnotify_sms_args</code>
						<p class="description">필터에 프로그램 코드를 추가해서 동적으로 설정을 변경할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">개발자 팁 - 액션</label></th>
					<td>
						<code>cosmosfarm_members_send_message</code>
						<p class="description">액션에 새로운 프로그램을 추가할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">KBoard(케이보드) 연동</label></th>
					<td>
						<p class="description"><a href="https://blog.naver.com/PostView.nhn?blogId=chan2rrj&logNo=221184216595" onclick="window.open(this.href);return false;">워드프레스 쪽지 보내기 게시판과 연동 방법</a></p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<hr>
		
		<h3>회원 리스트 설정</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_users_page_id">회원 리스트 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_messages_page_id" name="cosmosfarm_members_users_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->users_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description"><code>[cosmosfarm_members_users]</code> 숏코드가 삽입된 회원 리스트 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<hr>
		
		<h3>회원 공개 프로필 설정</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_user_profile_page_id">회원 공개 프로필 페이지</label></th>
					<td>
						<select id="cosmosfarm_members_user_profile_page_id" name="cosmosfarm_members_user_profile_page_id">
							<option value="">사용안함</option>
							<?php foreach(get_pages() as $page):?>
							<option value="<?php echo $page->ID?>"<?php if($option->user_profile_page_id == $page->ID):?> selected<?php endif?>><?php echo $page->post_title?></option>
							<?php endforeach?>
						</select>
						<p class="description"><code>[cosmosfarm_members_user_profile]</code> 숏코드가 삽입된 회원 공개 프로필 페이지를 선택해주세요.</p>
						<p class="description">다른 페이지와 겹치지 않게 새로운 페이지를 만들어주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_user_profile_page_id">페이지 주소</label></th>
					<td>
						<?php if($option->user_profile_page_id):?>
							<code><?php echo get_cosmosfarm_members_user_profile_url()?></code>
							<p class="description">페이지 주소 형식은 <a href="<?php echo admin_url('options-permalink.php')?>" onclick="window.open(this.href);return false;">고유주소 설정</a>에 의해서 결정됩니다.</p>
						<?php else:?>
						<code>페이지를 선택해주세요.</code>
						<?php endif?>
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