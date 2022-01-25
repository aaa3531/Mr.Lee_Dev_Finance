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
		<?php wp_nonce_field('cosmosfarm-members-sms-setting-save', 'cosmosfarm-members-sms-setting-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_sms_setting_save">
		
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo admin_url("admin.php?page=cosmosfarm_members_sms_setting&tab=sms")?>" class="nav-tab<?php if($tab == 'sms'):?> nav-tab-active<?php endif?>">SMS</a>
			<a href="<?php echo admin_url("admin.php?page=cosmosfarm_members_sms_setting&tab=alimtalk")?>" class="nav-tab<?php if($tab == 'alimtalk'):?> nav-tab-active<?php endif?>">알림톡</a>
		</h2>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label>현재 서버 IP 주소</label></th>
					<td>
						<?php echo $_SERVER['SERVER_ADDR']?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_alimtalk_service">알림톡 서비스</label></th>
					<td>
						<select id="cosmosfarm_members_alimtalk_service" name="cosmosfarm_members_alimtalk_service">
							<option value="">비활성화</option>
							<option value="alimtalk"<?php if($option->alimtalk_service == 'alimtalk'):?> selected<?php endif?>>활성화</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<a href="https://blog.naver.com/PostView.nhn?blogId=chan2rrj&logNo=221205995135" onclick="window.open(this.href);return false;">카카오 알림톡 서비스 세팅하는 방법</a>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_alimtalk_appkey">Appkey</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_alimtalk_appkey" name="cosmosfarm_members_alimtalk_appkey" value="<?php echo $option->alimtalk_appkey?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_alimtalk_secretkey">SecretKey</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_alimtalk_secretkey" name="cosmosfarm_members_alimtalk_secretkey" value="<?php echo $option->alimtalk_secretkey?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_alimtalk_plusfriend_id">플러스친구 ID</label></th>
					<td>
						<?php
						$sms = get_cosmosfarm_members_sms();
						$result = $sms->get_plus_friends();
						?>
						<?php if($result['result'] == 'success'):?>
						<select id="cosmosfarm_members_alimtalk_plusfriend_id" name="cosmosfarm_members_alimtalk_plusfriend_id">
							<?php foreach($result['plus_friends'] as $plus_friend):?>
							<option value="<?php echo $plus_friend->plusFriendId?>"<?php if($plus_friend->plusFriendId == $option->alimtalk_plusfriend_id):?> selected<?php endif?>><?php echo $plus_friend->plusFriendId?></option>
							<?php endforeach?>
						</select>
						<?php else: echo $result['message']?>
						<?php endif?>
						
						<?php if(!$option->alimtalk_template):?>
						<p class="description">사용 가능한 템플릿이 없습니다.<br>
						<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_alimtalk_template_setting')?>">알림톡 템플릿 설정</a> 페이지에서 템플릿을 등록할 수 있습니다.
						</p>
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<button type="button" class="button" onclick="cosmosfarm_members_open_sms_form()">알림톡 보내기 테스트</button>
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