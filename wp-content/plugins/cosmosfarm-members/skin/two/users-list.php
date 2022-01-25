<?php
$args = array(
		'order'   => 'DESC',
		'orderby' => 'ID',
		'number'  => 20,
		'paged'   => $paged,
		'search'  => "*$keyword*",
		'search_columns' => array('user_login', 'user_nicename', 'display_name')
);
$query = new WP_User_Query(apply_filters('cosmosfarm_members_users_list_query_args', $args));
$users = $query->get_results();
foreach($users as $user){
	echo $skin->users_list_item($user);
}
if(!$query->get_total() && $paged == 1):
?>
<li class="not-found"><?php echo __('No users found.', 'cosmosfarm-members')?></li>
<?php endif?>