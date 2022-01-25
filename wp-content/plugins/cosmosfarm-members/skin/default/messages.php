<?php if(!defined('ABSPATH')) exit;?>

<?php echo $skin->header()?>

<div id="cosmosfarm-members-messages">
	<input type="hidden" name="messages_request_url" value="<?php echo get_permalink()?>">
	<input type="hidden" name="messages_list_page" value="1">
	<input type="hidden" name="messages_list_keyword" value="<?php echo esc_attr($keyword)?>">
	<input type="hidden" name="messages_list_view" value="<?php echo esc_attr($messages_view)?>">
	
	<div class="messages-search">
		<form method="get" action="<?php echo get_permalink()?>">
			<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
			<input type="hidden" name="messages_view" value="<?php echo esc_attr($messages_view)?>">
			<input type="text" name="keyword" value="<?php echo esc_attr($keyword)?>" placeholder="<?php echo __('Search', 'cosmosfarm-members')?>">
		</form>
	</div>
	
	<div class="messages-controlbar">
		<div class="messages-left">
			<form method="get" action="<?php echo get_permalink()?>">
				<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
				<input type="hidden" name="keyword" value="<?php echo esc_attr($keyword)?>">
				<select name="messages_view" onchange="this.form.submit()">
        			<option value="inbox"<?php if($messages_view == 'inbox'):?> selected<?php endif?>>받은쪽지함</option>
        			<option value="sent"<?php if($messages_view == 'sent'):?> selected<?php endif?>>보낸쪽지함</option>
        		</select>
			</form>
		</div>
		
		<?php if($message->is_subnotify_email() || $message->is_subnotify_sms()):?>
		<div class="subnotify">
			<?php if($message->is_subnotify_email()):?>
			<label><input type="checkbox" name="messages_subnotify_email" value="1" onchange="cosmosfarm_members_messages_subnotify_update(this)"<?php if($message->is_user_subnotify_email()):?> checked<?php endif?>> 이메일 알림 받기</label>
			<?php endif?>
			
			<?php if($message->is_subnotify_sms()):?>
			<label><input type="checkbox" name="messages_subnotify_sms" value="1" onchange="cosmosfarm_members_messages_subnotify_update(this)"<?php if($message->is_user_subnotify_sms()):?> checked<?php endif?>> SMS 알림 받기</label>
			<?php endif?>
		</div>
		<?php endif?>
	</div>
	
	<ul class="messages-list">
		<?php echo $skin->messages_list()?>
	</ul>
	
	<button type="button" class="messages-more cosmosfarm-members-button" onclick="cosmosfarm_members_messages_more(this)"><?php echo __('More', 'cosmosfarm-members')?></button>
</div>