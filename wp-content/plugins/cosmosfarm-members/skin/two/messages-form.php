<?php if(!defined('ABSPATH')) exit;?>

<?php echo $skin->header()?>

<div id="cosmosfarm-members-messages">
	<form method="post" action="<?php echo get_permalink()?>" onsubmit="return cosmosfarm_members_send_message_submit(this)">
		<input type="hidden" name="to_user_id" value="<?php echo $to_user_id?>">
		<input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to)?>">
		
		<div class="messages-form">
			<div class="messages-form-row">
				<label class="form-row-name">
					<span>받는사람</span>
				</label>
				<div class="form-row-value">
					<div class="form-row-padding">
						<div class="message-to-user-wrap">
							<?php echo get_avatar($to_user_id, 24)?>
							<?php echo $to_user->display_name?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="messages-form-row">
				<label class="form-row-name" for="message-title-field">
					<span>제목</span>
				</label>
				<div class="form-row-value">
					<div class="form-row-padding">
						<input type="text" id="message-title-field" name="title" placeholder="제목 입력" autofocus>
					</div>
				</div>
			</div>
			
			<div class="messages-form-row">
				<div class="form-row-padding">
					<textarea name="content" placeholder="내용 입력" rows="10" required></textarea>
				</div>
			</div>
			
			<div class="messages-form-row messages-form-controlbar">
				<div class="controlbar-right">
					<button type="submit" class="cosmosfarm-members-button">전송</button>
				</div>
			</div>
		</div>
	</form>
</div>