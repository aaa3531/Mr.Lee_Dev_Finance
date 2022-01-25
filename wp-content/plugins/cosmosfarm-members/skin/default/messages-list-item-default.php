<li class="messages-list-item item-type-<?php echo $item->get_type()?> item-status-<?php echo $item->get_status()?> item-post-id-<?php echo $item->ID?>">
	<div class="item-avatar"><?php echo get_avatar($from_user_id, 96)?></div>
	<div class="item-right-wrap">
		<div class="cosmosfarm-members-item-wrap">
			<?php if(isset($from_user->ID) && $from_user->ID):?>
			<div class="add-item-middot item-display-name"><?php echo $from_user->display_name?></div>
			<div class="add-item-middot item-date"><?php echo $item->post_date?></div>
			<?php else:?>
			<div class="item-date"><?php echo $item->post_date?></div>
			<?php endif?>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($item->post_title):?>
			<div class="item-title"><?php echo $item->post_title?></div>
			<?php endif?>
			
			<div class="item-content"><?php echo wpautop($item->post_content)?></div>
		</div>
		<?php if($item->user_id == get_current_user_id()):?>
		<div class="cosmosfarm-members-item-wrap">
			<div class="add-item-middot item-button-toggle"><a href="#" onclick="return cosmosfarm_members_messages_toggle(this, '<?php echo $item->ID?>');"><span class="text-read">읽음 표시</span><span class="text-unread">안읽음 표시</span></a></div>
			<div class="add-item-middot item-button-delete"><a href="<?php echo get_cosmosfarm_members_messages_url(array('to_user_id'=>$item->from_user_id, 'redirect_to'=>$_SERVER['REQUEST_URI']))?>" title="답장쓰기">답장쓰기</a></div>
			<div class="add-item-middot item-button-delete"><a href="#" onclick="return cosmosfarm_members_messages_delete(this, '<?php echo $item->ID?>');">삭제</a></div>
		</div>
		<?php endif?>
	</div>
</li>