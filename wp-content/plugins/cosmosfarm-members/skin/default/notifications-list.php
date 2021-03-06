<?php
$args = array(
	'post_type'      => $notification->post_type,
	'author'         => get_current_user_id(),
	'order'          => 'DESC',
	'orderby'        => 'ID',
	'posts_per_page' => 20,
	'paged'          => $paged,
	's'              => $keyword,
	'meta_query'     => $meta_query
);
$query = new WP_Query(apply_filters('cosmosfarm_members_notifications_list_query_args', $args));
foreach($query->posts as $post){
	echo $skin->notifications_list_item($post->ID);
}
if(!$query->found_posts && $paged == 1):
?>
<li class="not-found"><?php echo __('No notifications found.', 'cosmosfarm-members')?></li>
<?php endif?>