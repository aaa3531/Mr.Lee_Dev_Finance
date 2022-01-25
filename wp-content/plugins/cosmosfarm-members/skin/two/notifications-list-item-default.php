<li class="notifications-list-item item-type-<?php echo $item->get_type()?> item-status-<?php echo $item->get_status()?> item-post-id-<?php echo $item->ID?>">
	<div class="item-avatar"><?php echo get_avatar($from_user_id, 96)?></div>
	<div class="item-right-wrap">
		<div class="cosmosfarm-members-item-wrap">
			<?php if(isset($from_user->ID) && $from_user->ID):?>
			<div class="add-item-middot item-display-name"><?php echo $from_user->display_name?></div>
			<div class="add-item-middot item-date"><?php echo $item->post_date?></div>
			<?php else:?>
			<div class="add-item-middot item-date"><?php echo $item->post_date?></div>
			<?php endif?>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($item->post_title):?>
			<div class="item-title"><?php echo $item->post_title?></div>
			<?php endif?>
			
			<div class="item-content"><?php echo wpautop($item->post_content)?></div>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($item->url):?>
			<div class="add-item-middot item-button-url"><a href="<?php echo esc_url($item->url)?>" onclick="window.open(this.href);return false;"><?php echo $item->url_name ? $item->url_name : $item->url?></a></div>
			<?php endif?>
			<div class="add-item-middot item-button-toggle"><a href="#" onclick="return cosmosfarm_members_notifications_toggle(this, '<?php echo $item->ID?>');"><span class="text-read">읽음 표시</span><span class="text-unread">안읽음 표시</span></a></div>
			<div class="add-item-middot item-button-delete"><a href="#" onclick="return cosmosfarm_members_notifications_delete(this, '<?php echo $item->ID?>');">삭제</a></div>
		</div>
	</div>
</li>