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
		<?php wp_nonce_field('cosmosfarm-members-privacy-save', 'cosmosfarm-members-privacy-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_privacy_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_policy_privacy">개인정보</label></th>
					<td>
						<?php wp_editor(get_cosmosfarm_policy_privacy_content(), 'cosmosfarm_members_policy_privacy')?>
						<p class="description">개인정보 내용을 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">필드 추가 예제</label></th>
					<td>
						<a href="<?php echo COSMOSFARM_MEMBERS_URL . '/images/policy_privacy.png'?>" onclick="window.open(this.href);return false;" title="크게보기"><img src="<?php echo COSMOSFARM_MEMBERS_URL . '/images/policy_privacy.png'?>" alt="" style="max-width:100%;vertical-align:middle"></a>
						<p class="description"><a href="<?php echo admin_url('options-general.php?page=wpmem-settings&tab=fields')?>">WP-Members</a> 플러그인에서 <code>policy_privacy</code> 필드를 추가하세요.</p>
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