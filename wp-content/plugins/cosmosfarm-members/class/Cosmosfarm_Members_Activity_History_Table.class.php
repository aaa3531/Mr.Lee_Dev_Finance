<?php
/**
 * Cosmosfarm_Members_Activity_History_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Activity_History_Table extends WP_List_Table {
	
	public function __construct(){
		parent::__construct(array('screen'=>'cosmosfarm_members_activity_history'));
	}
	
	public function prepare_items(){
		global $wpdb;
		
		$this->_column_headers = $this->get_column_info();
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		switch($target){
			case 'user_login':
				$user = get_user_by('login', $keyword);
				if($user) $where = "`user_id`='{$user->ID}'";
				else $where = "`user_id`=''";
				break;
			case 'user_email':
				$user = get_user_by('email', $keyword);
				if($user) $where = "`user_id`='{$user->ID}'";
				else $where = "`user_id`=''";
				break;
			case 'related_user_login':
				$user = get_user_by('login', $keyword);
				if($user) $where = "`related_user_id`='{$user->ID}'";
				else $where = "`related_user_id`=''";
				break;
			case 'related_user_email':
				$user = get_user_by('email', $keyword);
				if($user) $where = "`related_user_id`='{$user->ID}'";
				else $where = "`related_user_id`=''";
				break;
			case 'ip_address':
				$where = "`ip_address`='".esc_sql($keyword)."'";
				break;
			default:
				$where = '1';
		}
		
		$page = $this->get_pagenum();
		$per_page = 20;
		$offset = (($page-1)*$per_page);
		
		$this->items = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}cosmosfarm_members_activity_history` WHERE {$where} ORDER BY activity_history_id DESC LIMIT {$offset},{$per_page}");
		$total_items = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}cosmosfarm_members_activity_history` WHERE {$where}");
		
		$this->set_pagination_args(array('total_items'=>$total_items, 'per_page'=>$per_page));
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'activity-history';
		return $classes;
	}
	
	public function no_items(){
		echo __('No history found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
			'cb' => '<input type="checkbox">',
			'user_id' => '사용자',
			'related_user_id' => '조회된 회원',
			'comment' => '내용',
			'ip_address' => '아이피 주소',
			'activity_datetime' => '활동 시간',
		);
	}
	
	function get_bulk_actions(){
		return array('none' => '동작 없음');
	}
	
	public function display_tablenav($which){
		global $wpdb;
		?>
		<div class="tablenav <?php echo esc_attr($which)?>">
			<div class="alignleft actions bulkactions"><?php $this->bulk_actions($which)?></div>
			<?php if($which=='top'):?>
				<div class="alignleft actions">
					<select>
						<option>전체</option>
					</select>
					<?php
					$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
					$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
					$download_url = array(
						'action'            => 'cosmosfarm_members_activity_history_download',
						'target'            => $target,
						'keyword'           => $keyword
					);
					?>
					<input type="button" class="button" value="내보내기" onclick="window.location.href='<?php echo wp_nonce_url(add_query_arg($download_url, admin_url('admin-post.php')), 'cosmosfarm-members-activity-history-download', 'cosmosfarm-members-activity-history-download-nonce')?>'">
					<span class="spinner"></span>
				</div>
			<?php endif?>
			<?php
			$this->extra_tablenav($which);
			$this->pagination($which);
			?>
			<br class="clear">
		</div>
	<?php }
	
	public function display_rows(){
		foreach($this->items as $item){
			$this->single_row($item);
		}
	}
	
	public function single_row($item){
		$edit_url = admin_url("admin.php?page=kboard_list&board_id={$item->activity_history_id}");
		
		list($columns) = $this->get_column_info();
		$column_keys = array_keys($columns);
		
		echo '<tr data-activity-history-id"'.$item->activity_history_id.'">';
		
		if(in_array('cb', $column_keys)){
			echo '<th scope="row" class="column-cb check-column">';
			echo '<input type="checkbox" name="activity_history_id[]" value="'.$item->activity_history_id.'">';
			echo '</th>';
		}
		
		if(in_array('user_id', $column_keys)){
			echo '<td class="column-user_id column-primary" data-colname="사용자">';
			$user = get_userdata($item->user_id);
			$user->filter = 'display';
			if($user){
				echo $user->user_login;
				if($user->user_email){
					echo " ({$user->user_email})";
				}
			}
			else{
				echo '삭제된 회원';
			}
			echo '</td>';
		}
		
		if(in_array('related_user_id', $column_keys)){
			echo '<td class="column-related_user_id" data-colname="조회된 회원">';
			$user = get_userdata($item->related_user_id);
			$user->filter = 'display';
			if($user){
				echo $user->user_login;
				if($user->user_email){
					echo " ({$user->user_email})";
				}
			}
			else{
				echo '삭제된 회원';
			}
			echo '</td>';
		}
		
		if(in_array('comment', $column_keys)){
			echo '<td class="column-comment" data-colname="내용">';
			echo $item->comment;
			echo '</td>';
		}
		
		if(in_array('ip_address', $column_keys)){
			echo '<td class="column-ip_address" data-colname="아이피 주소">';
			echo $item->ip_address;
			echo '</td>';
		}
		
		if(in_array('activity_datetime', $column_keys)){
			echo '<td class="column-activity_datetime" data-colname="활동 시간">';
			echo $item->activity_datetime;
			echo '</td>';
		}
		
		echo '</tr>';
	}
	
	public function search_box($text, $input_id){
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) :'';
		?>
		<p class="search-box">
			<select name="target" style="float:left;height:28px;margin:0 4px 0 0">
				<option value="user_login"<?php if($target == 'user_login'):?> selected<?php endif?>>사용자 계정</option>
				<option value="user_email"<?php if($target == 'user_email'):?> selected<?php endif?>>사용자 이메일</option>
				<option value="related_user_login"<?php if($target == 'related_user_login'):?> selected<?php endif?>>조회된 회원 계정</option>
				<option value="related_user_email"<?php if($target == 'related_user_email'):?> selected<?php endif?>>조회된 회원 이메일</option>
				<option value="ip_address"<?php if($target == 'ip_address'):?> selected<?php endif?>>아이피 주소</option>
			</select>
			<input type="search" id="<?php echo $input_id?>" name="s" value="<?php _admin_search_query()?>">
			<?php submit_button($text, 'button', false, false, array('id'=>'search-submit'))?>
		</p>
	<?php }
}