<?php
$args = array(
	'post_type'      => $message->post_type,
	'order'          => 'DESC',
	'orderby'        => 'ID',
	'posts_per_page' => 20,
	'paged'          => $paged,
	's'              => $keyword,
	'meta_query'     => $meta_query
);
$query = new WP_Query(apply_filters('cosmosfarm_members_messages_list_query_args', $args));
foreach($query->posts as $post){
	echo $skin->messages_list_item($post->ID);
}
if(!$query->found_posts && $paged == 1):
?>
<li class="not-found"><?php echo __('No messages found.', 'cosmosfarm-members')?></li>
<?php endif?>