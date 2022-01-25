<?php if(!defined('ABSPATH')) exit;?>

<div id="cosmosfarm-members-user-profile">
	<div class="profile-header">
		<?php echo get_avatar($user->ID, '100')?>
		
		<div class="header-right">
			<h1 class="display-name"><?php echo $user->display_name?> <span class="user-role">(<?php echo translate_user_role($GLOBALS['wp_roles']->role_names[$user->roles[0]])?>)</span></h1>
			<ul class="profile-tabs">
				<?php foreach($tab_items as $item):?>
				<li class="tab-item-<?php echo $item['id']?><?php if($current_tab == $item['id']):?> profile-tabs-selected<?php endif?>">
					<a href="<?php echo $item['url']?>"><?php echo $item['title']?></a>
				</li>
				<?php endforeach?>
			</ul>
		</div>
	</div>
	<div class="profile-body">
		<div class="profile-body-row">
			<?php echo wpautop($user->description)?>
		</div>
		
		<?php if($user_tags = get_user_meta($user->ID, 'my_tags', true)):?>
		<div class="profile-body-row">
			<?php
			$user_tags = explode(',', $user_tags);
			$user_tags = array_map('trim', $user_tags);
			
			foreach($user_tags as $tag){
				echo sprintf('<span class="user-tag">%s</span>', $tag);
			}
			?>
		</div>
		<?php endif?>
	</div>
</div>