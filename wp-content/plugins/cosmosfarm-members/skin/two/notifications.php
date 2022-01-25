<?php if(!defined('ABSPATH')) exit;?>

<?php echo $skin->header()?>

<div id="cosmosfarm-members-notifications">
	<input type="hidden" name="notifications_request_url" value="<?php echo get_permalink()?>">
	<input type="hidden" name="notifications_list_page" value="1">
	<input type="hidden" name="notifications_list_keyword" value="<?php echo esc_attr($keyword)?>">
	<input type="hidden" name="notifications_list_view" value="<?php echo esc_attr($notifications_view)?>">
	
	<div class="notifications-search">
		<form method="get" action="<?php echo get_permalink()?>">
			<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
			<input type="hidden" name="notifications_view" value="<?php echo esc_attr($notifications_view)?>">
			<input type="text" name="keyword" value="<?php echo esc_attr($keyword)?>" placeholder="<?php echo __('Search', 'cosmosfarm-members')?>">
		</form>
	</div>
	
	<div class="notifications-controlbar">
		<div class="controlbar-left">
    		<form method="get" action="<?php echo get_permalink()?>">
    			<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
    			<input type="hidden" name="keyword" value="<?php echo esc_attr($keyword)?>">
        		<select name="notifications_view" onchange="this.form.submit()">
        			<option value="inbox"<?php if($notifications_view == 'inbox'):?> selected<?php endif?>>전체보기</option>
        			<option value="unread"<?php if($notifications_view == 'unread'):?> selected<?php endif?>>안읽은알림</option>
        		</select>
    		</form>
		</div>
		
		<?php if($notification->is_subnotify_email() || $notification->is_subnotify_sms()):?>
		<div class="subnotify">
			<?php if($notification->is_subnotify_email()):?>
			<label><input type="checkbox" name="notifications_subnotify_email" value="1" onchange="cosmosfarm_members_notifications_subnotify_update(this)"<?php if($notification->is_user_subnotify_email()):?> checked<?php endif?>> 이메일 알림 받기</label>
			<?php endif?>
			
			<?php if($notification->is_subnotify_sms()):?>
			<label><input type="checkbox" name="notifications_subnotify_sms" value="1" onchange="cosmosfarm_members_notifications_subnotify_update(this)"<?php if($notification->is_user_subnotify_sms()):?> checked<?php endif?>> SMS 알림 받기</label>
			<?php endif?>
		</div>
		<?php endif?>
	</div>
	
	<ul class="notifications-list">
		<?php echo $skin->notifications_list()?>
	</ul>
	
	<button type="button" class="notifications-more cosmosfarm-members-button" onclick="cosmosfarm_members_notifications_more(this)"><?php echo __('More', 'cosmosfarm-members')?></button>
</div>