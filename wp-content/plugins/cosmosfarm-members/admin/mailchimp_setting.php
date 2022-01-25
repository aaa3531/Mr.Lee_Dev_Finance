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
		<?php wp_nonce_field('cosmosfarm-members-mailchimp-save', 'cosmosfarm-members-mailchimp-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_mailchimp_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_mailchimp_api_key">메일침프 API 키</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_mailchimp_api_key" name="cosmosfarm_members_mailchimp_api_key" class="regular-text" value="<?php echo $option->mailchimp_api_key?>">
						<p class="description">필수로 입력해주세요.</p>
						<p class="description"><a href="https://mailchimp.com/help/about-api-keys/" onclick="window.open(this.href);return false;">메일침프 API 키 설명 (영문)</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_mailchimp_list_id">메일침프 List ID</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_mailchimp_list_id" name="cosmosfarm_members_mailchimp_list_id" class="regular-text" value="<?php echo $option->mailchimp_list_id?>">
						<p class="description">필수로 입력해주세요.</p>
						<p class="description"><a href="https://mailchimp.com/help/find-your-list-id/" onclick="window.open(this.href);return false;">메일침프 List ID 설명 (영문)</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_mailchimp_field">메일침프 체크박스 필드</label></th>
					<td>
						<select id="cosmosfarm_members_mailchimp_field" name="cosmosfarm_members_mailchimp_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'checkbox') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->mailchimp_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">회원가입 또는 프로필 수정 시 체크박스를 체크해야 메일침프에 가입됩니다. 원하는 필드가 없다면 새로 추가해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">메일침프 웹훅 주소</label></th>
					<td>
						<code><?php echo add_query_arg(array('action'=>'cosmosfarm_members_mailchimp_webhook'), site_url('/'))?></code>
						<p class="description">필수는 아니며 필요한 경우 메일침프 홈페이지에 등록해주세요.</p>
						<p class="description"><a href="https://mailchimp.com/help/set-up-webhooks/" onclick="window.open(this.href);return false;">메일침프 웹훅 설명 (영문)</a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">필드 추가 예제</label></th>
					<td>
						<a href="<?php echo COSMOSFARM_MEMBERS_URL . '/images/mailchimp_setting.png'?>" onclick="window.open(this.href);return false;" title="크게보기"><img src="<?php echo COSMOSFARM_MEMBERS_URL . '/images/mailchimp_setting.png'?>" alt="" style="max-width:100%;vertical-align:middle"></a>
						<p class="description">사용자의 이메일을 메일침프에 저장하시려면 <a href="<?php echo admin_url('options-general.php?page=wpmem-settings&tab=fields')?>">WP-Members</a> 플러그인에서 <code>newsletter</code> 필드를 추가하세요.</p>
						<p class="description">사용자가 직접 체크박스에 체크했을 경우 메일침프에 이메일이 등록됩니다.</p>
						<p class="description">Meta Key, 필드 타입은 반드시 동일해야만 동작됩니다.</p>
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