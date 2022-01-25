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
		<?php wp_nonce_field('cosmosfarm-members-security-dormant-member-save', 'cosmosfarm-members-security-dormant-member-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_security_dormant_member_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">휴면 회원 자동 삭제</label></th>
					<td>
						<?php if(wp_next_scheduled('cosmosfarm_members_dormant_member')):?>
						<code>사용</code>
						<?php else:?>
						<code>사용중지</code>
						<?php endif?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_dormant_member_email_title">삭제 안내 이메일 제목</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_dormant_member_email_title" name="cosmosfarm_members_dormant_member_email_title" class="regular-text" value="<?php echo esc_attr(Cosmosfarm_Members_Security::dormant_member_email_title())?>">
						<p class="description">휴면 회원 정보가 삭제되기 30일 전 안내를 전송합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_dormant_member_email_message">삭제 안내 이메일 내용</label></th>
					<td>
						<textarea id="cosmosfarm_members_dormant_member_email_message" name="cosmosfarm_members_dormant_member_email_message" rows="7" style="width:600px;max-width:100%;"><?php
						echo esc_textarea(Cosmosfarm_Members_Security::dormant_member_email_message());
						?></textarea>
						<p class="description">휴면 회원 정보가 삭제되기 30일 전 안내를 전송합니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_dormant_member_email_test">테스트 메일 받기</label></th>
					<td>
						<input type="email" id="cosmosfarm_members_dormant_member_email_test" name="cosmosfarm_members_dormant_member_email_test" class="regular-text" value="" placeholder="<?php echo esc_attr(get_option('admin_email'))?>">
						<p class="description">이메일을 입력하시면 테스트 메일을 받아볼 수 있습니다.</p>
						<p class="description">이메일이 도착하지 않는다면 서버 설정 확인 및 스팸메일 함도 살펴봐주세요.</p>
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