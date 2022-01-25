<li class="users-list-item item-type-<?php echo $item_type?> item-user-id-<?php echo $user->ID?>">
	<div class="item-avatar"><?php echo get_avatar($user->ID, 96)?></div>
	<div class="item-right-wrap">
		<div class="cosmosfarm-members-item-wrap">
			<div class="add-item-middot item-display-name"><?php echo $user->display_name?></div>
			<div class="add-item-middot item-date" title="<?php echo $user->user_registered?>"><?php echo date('Y-m-d', strtotime($user->user_registered))?></div>
			
			<?php if(class_exists('myCRED_Core')):?>
			<div class="add-item-middot item-point"><?php echo number_format(mycred_get_users_cred($user->ID))?> <?php echo __('Points', 'cosmosfarm-members')?></div>
			<?php endif?>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($user->ID != get_current_user_id()):?>
			<div class="add-item-middot item-message"><a href="<?php echo get_cosmosfarm_members_messages_url(array('to_user_id'=>$user->ID, 'redirect_to'=>$_SERVER['REQUEST_URI']))?>" title="쪽지 보내기">쪽지 보내기</a></div>
			<?php endif?>
		</div>
	</div>
</li>