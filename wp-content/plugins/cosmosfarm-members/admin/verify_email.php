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
		<?php wp_nonce_field('cosmosfarm-members-verify-email-save', 'cosmosfarm-members-verify-email-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_verify_email_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_verify_email">이메일 인증 가입</label></th>
					<td>
						<select id="cosmosfarm_members_verify_email" name="cosmosfarm_members_verify_email">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->verify_email):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">회원가입 시 입력한 이메일로 확인 메일을 전송해서 실제 사용하는 이메일인지 확인합니다.</p>
						<p class="description">※ 이메일 확인 전에는 로그인이 불가능합니다.</p>
						<p class="description">※ 소셜 로그인에는 동작하지 않습니다.</p>
						<p class="description">※ 호스팅 서버와 메일 서비스에 따라서 메일 전송이 원활하지 못할 수 있습니다.</p>
						<p class="description">※ 이메일 전송에 문제가 있다면 <a href="https://blog.cosmosfarm.com/?p=720" onclick="window.open(this.href);return false;">워드프레스 이메일 전송 문제 해결 방법</a>을 참고해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_verify_email_title">인증 이메일 제목</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_verify_email_title" name="cosmosfarm_members_verify_email_title" value="<?php if($option->verify_email_title): echo $option->verify_email_title; else:?>[[blogname]] 회원가입해 주셔서 감사합니다.<?php endif?>" style="width:100%">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_verify_email_content">인증 이메일 내용</label></th>
					<td>
						<textarea id="cosmosfarm_members_verify_email_content" name="cosmosfarm_members_verify_email_content" rows="10" style="width:100%"><?php if($option->verify_email_content): echo $option->verify_email_content; else:?>안녕하세요, [id_or_email]님.

[blogname]에 등록 해 주셔서 감사합니다!

등록을 확인하려면 아래 링크를 클릭하십시오.

[verify_email_url]

감사합니다.<?php endif?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_confirmed_email">이메일 인증 완료 메일</label></th>
					<td>
						<select id="cosmosfarm_members_confirmed_email" name="cosmosfarm_members_confirmed_email">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->confirmed_email):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description">회원가입 이메일 인증 완료 후 새로운 이메일을 발송합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_confirmed_email_title">완료 이메일 제목</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_confirmed_email_title" name="cosmosfarm_members_confirmed_email_title" value="<?php if($option->confirmed_email_title): echo $option->confirmed_email_title; else:?>[[blogname]] 귀하의 계정이 확인되었습니다.<?php endif?>" style="width:100%">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_confirmed_email_content">완료 이메일 내용</label></th>
					<td>
						<textarea id="cosmosfarm_members_confirmed_email_content" name="cosmosfarm_members_confirmed_email_content" rows="10" style="width:100%"><?php if($option->confirmed_email_content): echo $option->confirmed_email_content; else:?>안녕하세요, [id_or_email]님.

우리 사이트에 가입해 주셔서 다시 한번 감사드립니다!

이제 홈페이지에 로그인하시면 모든 서비스를 이용하실 수 있습니다.

[home_url]

감사합니다.<?php endif?></textarea>
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