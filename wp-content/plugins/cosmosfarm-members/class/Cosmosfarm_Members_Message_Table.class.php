<?php
/**
 * Cosmosfarm_Members_Message_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Message_Table extends WP_List_Table {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function prepare_items(){
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		$per_page = 20;
		$message = new Cosmosfarm_Members_Message();
		$args = array(
			'post_type'      => $message->post_type,
			'orderby'        => 'ID',
			'posts_per_page' => $per_page,
			'paged'          => $this->get_pagenum(),
			's'              => $keyword
		);
		$query = new WP_Query($args);
		$this->items = $query->posts;
		
		$this->set_pagination_args(array('total_items'=>$query->found_posts, 'per_page'=>$per_page));
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'message';
		return $classes;
	}
	
	public function no_items(){
		echo __('No messages found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
			'cb' => '<input type="checkbox">',
			'content' => '쪽지 내용',
			'from_user' => '보낸 사용자',
			'to_user' => '받는 사용자',
			'date' => '날짜',
		);
	}
	
	function get_bulk_actions(){
		return array(
			'delete' => __('Delete Permanently', 'cosmosfarm-members')
		);
	}
	
	public function display_rows(){
		foreach($this->items as $post){
			$message = new Cosmosfarm_Members_Message($post->ID);
			$this->single_row($message);
		}
	}
	
	public function single_row($message){
		$edit_url = admin_url("admin.php?page=cosmosfarm_members_message&message_id={$message->ID}");
		
		$from_user_id = $message->get_from_user_id();
		$from_user = get_userdata($from_user_id);
		
		$to_user_id = $message->get_to_user_id();
		$to_user = get_userdata($to_user_id);
		
		echo '<tr data-notification-id="'.$message->ID.'">';
		
		echo '<th scope="row" class="check-column">';
		echo '<input type="checkbox" name="message_id[]" value="'.$message->ID.'">';
		echo '</th>';
		
		echo '<td>';
		echo sprintf('<div>%s</div>', $message->post_content);
		echo sprintf('<div><a href="%s">수정하기</a></div>', admin_url("admin.php?page=cosmosfarm_members_message&message_id={$message->ID}"));
		echo '</td>';
		
		echo '<td>';
		echo $from_user ? $from_user->display_name : '';
		echo '</td>';
		
		echo '<td>';
		echo $to_user ? $to_user->display_name : '';
		echo '</td>';
		
		echo '<td>';
		echo $message->post_date;
		echo '</td>';
		
		echo '</tr>';
	}
	
	public function search_box($text, $input_id){
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
	?>
	<p class="search-box">
		<input type="search" id="<?php echo $input_id?>" name="s" value="<?php _admin_search_query()?>">
		<?php submit_button($text, 'button', false, false, array('id'=>'search-submit'))?>
	</p>
	<?php }
}
?>