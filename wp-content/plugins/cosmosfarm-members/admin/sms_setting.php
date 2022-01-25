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
					<th scope="row"><label for="cosmosfarm_members_sms_service">SMS 서비스</label></th>
					<td>
						<select id="cosmosfarm_members_sms_service" name="cosmosfarm_members_sms_service">
							<option value="">사용중지</option>
							<option value="cafe24"<?php if($option->sms_service == 'cafe24'):?> selected<?php endif?>>카페24</option>
							<option value="toast_cloud"<?php if($option->sms_service == 'toast_cloud'):?> selected<?php endif?>>토스트 클라우드</option>
						</select>
						<p class="description">SMS 보내기 서비스 제공업체를 선택하세요.</p>
						<p class="description">서비스 제공업체를 변경한 다음 저장하면 추가 설정이 표시됩니다.</p>
					</td>
				</tr>
				<?php if($option->sms_service == 'cafe24'):?>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<a href="https://blog.naver.com/PostView.nhn?blogId=chan2rrj&logNo=221105544348" onclick="window.open(this.href);return false;">카페24 SMS 서비스 세팅하는 방법</a>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_sms_caller1">발신번호</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_sms_caller1" name="cosmosfarm_members_sms_caller1" value="<?php echo $option->sms_caller1?>" style="width:70px">
						-
						<input type="text" id="cosmosfarm_members_sms_caller2" name="cosmosfarm_members_sms_caller2" value="<?php echo $option->sms_caller2?>" style="width:70px">
						-
						<input type="text" id="cosmosfarm_members_sms_caller3" name="cosmosfarm_members_sms_caller3" value="<?php echo $option->sms_caller3?>" style="width:70px">
						<p class="description">1577, 1588 등 대표전화번호로 설정할 경우 첫 번째와 두 번째 필드에 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_sms_cafe24_id">카페24 SMS 아이디</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_sms_cafe24_id" name="cosmosfarm_members_sms_cafe24_id" value="<?php echo $option->sms_cafe24_id?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_sms_cafe24_secret">카페24 SMS 인증키</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_sms_cafe24_secret" name="cosmosfarm_members_sms_cafe24_secret" class="regular-text" value="<?php echo $option->sms_cafe24_secret?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">잔여건수</th>
					<td>
						<?php
						$sms = get_cosmosfarm_members_sms();
						$result = $sms->get_count_cafe24();
						if($result['result'] == 'success'){
							echo $result['count'];
						}
						else{
							echo $result['message'];
						}
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<button type="button" class="button" onclick="cosmosfarm_members_open_sms_form()">SMS 보내기 테스트</button>
					</td>
				</tr>
				<?php elseif($option->sms_service == 'toast_cloud'):?>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<a href="https://blog.naver.com/PostView.nhn?blogId=chan2rrj&logNo=221205995135" onclick="window.open(this.href);return false;">토스트 클라우드 SMS 서비스 세팅하는 방법</a>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_sms_caller1">발신번호</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_sms_caller1" name="cosmosfarm_members_sms_caller1" value="<?php echo $option->sms_caller1?>" style="width:60px">
						-
						<input type="text" id="cosmosfarm_members_sms_caller2" name="cosmosfarm_members_sms_caller2" value="<?php echo $option->sms_caller2?>" style="width:70px">
						-
						<input type="text" id="cosmosfarm_members_sms_caller3" name="cosmosfarm_members_sms_caller3" value="<?php echo $option->sms_caller3?>" style="width:70px">
						<p class="description">1577, 1588 등 대표전화번호로 설정할 경우 첫 번째와 두 번째 필드에 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_sms_toast_cloud_appkey">Appkey</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_sms_toast_cloud_appkey" name="cosmosfarm_members_sms_toast_cloud_appkey" class="regular-text" value="<?php echo $option->sms_toast_cloud_appkey?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<button type="button" class="button" onclick="cosmosfarm_members_open_sms_form()">SMS 보내기 테스트</button>
					</td>
				</tr>
				<?php endif?>
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