<?php if(!defined('ABSPATH')) exit;?>

<?php echo $skin->header()?>

<div id="cosmosfarm-members-users">
	<input type="hidden" name="users_request_url" value="<?php echo get_permalink()?>">
	<input type="hidden" name="users_list_page" value="1">
	<input type="hidden" name="users_list_keyword" value="<?php echo esc_attr($keyword)?>">
	
	<div class="users-search">
		<form method="get" action="<?php echo get_permalink()?>">
			<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
			<input type="text" name="keyword" value="<?php echo esc_attr($keyword)?>" placeholder="<?php echo __('Search', 'cosmosfarm-members')?>">
		</form>
	</div>
	
	<ul class="users-list">
		<?php echo $skin->users_list()?>
	</ul>
	
	<button type="button" class="users-more cosmosfarm-members-button" onclick="cosmosfarm_members_users_more(this)"><?php echo __('More', 'cosmosfarm-members')?></button>
</div>