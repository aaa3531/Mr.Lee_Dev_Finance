<?php if(!defined('ABSPATH')) exit;?>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1 class="wp-heading-inline">코스모스팜 회원관리</h1>
	<a href="<?php echo admin_url("admin.php?page=cosmosfarm_members_message&action=new")?>" class="page-title-action">쪽지 등록하기</a>
	<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
	<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
	<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
	<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	<p>코스모스팜 회원관리는 한국형 회원가입 레이아웃과 기능을 제공합니다.</p>
	
	<hr class="wp-header-end">
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-message-save', 'cosmosfarm-members-message-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_message_save">
		<input type="hidden" name="message_id" value="<?php echo $message->ID?>">
		
		<h3>쪽지 내용</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="message_title">쪽지 제목</label></th>
					<td>
						<input type="text" id="message_title" name="message_title" class="regular-text" value="<?php echo $message->post_title?>">
						<p class="description">쪽지의 제목을 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="message_content">쪽지 내용</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<textarea id="message_content" name="message_content" class="regular-text" required><?php echo $message->post_content?></textarea>
						<p class="description">쪽지의 내용을 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="message_from_user_id">보낸 사용자</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="message_from_user_id" name="message_from_user_id" class="regular-text" value="<?php echo $from_user_id?>" required>
						<p class="description">보낸 사용자의 ID를 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="message_to_user_id">받는 사용자</label> <span style="font-size:12px;color:red;">(필수)</span></th>
					<td>
						<input type="text" id="message_to_user_id" name="message_to_user_id" class="regular-text" value="<?php echo $to_user_id?>" required>
						<p class="description">받는 사용자의 ID를 입력해주세요.</p>
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