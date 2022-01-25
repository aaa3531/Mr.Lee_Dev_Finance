<?php
/**
 * Cosmosfarm_Members_KBoard
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_KBoard {
	
	var $option;
	
	public function __construct(){
		$this->option = get_cosmosfarm_members_option();
		
		if(defined('KBOARD_VERSION') && defined('KBOARD_COMMNETS_VERSION')){
			if($this->option->notifications_kboard){
				add_action('kboard_skin_editor_option', array($this, 'kboard_skin_editor_option'), 10, 3);
				add_action('kboard_document_insert', array($this, 'kboard_document_insert'), 10, 4);
				add_action('kboard_comments_insert', array($this, 'kboard_comments_insert'), 10, 3);
			}
			
			if($this->option->notifications_kboard_comments){
				add_action('kboard_comments_field', array($this, 'comments_field'), 99, 4);
				add_action('kboard_comments_insert', array($this, 'comments_insert'), 10, 3);
			}
		}
	}
	
	public function kboard_skin_editor_option($content, $board, $builder){
		if(is_user_logged_in()){
			$display = true;
		}
		else{
			$display = false;
		}
		
		if(apply_filters('cosmosfarm_members_kboard_notify_display', $display, $content, $board, $builder)){ ?>
			<label class="attr-value-option">
				<input type="hidden" name="kboard_option_notify" value="">
				<input type="checkbox" name="kboard_option_notify" value="1"<?php if($content->option->notify || (!$content->uid && $this->is_kboard_notify_default($board))):?> checked<?php endif?>>
				<?php echo apply_filters('cosmosfarm_members_kboard_notify_text', __('Notify me of new comments', 'cosmosfarm-members'), $content, $board, $builder)?>
			</label>
		<?php }
	}
	
	public function kboard_document_insert($content_uid, $board_id, $content, $board){
		if($this->option->notifications_kboard){
			if($content->parent_uid){
				$parent = new KBContent();
				$parent->initWithUID($content->parent_uid);
				
				if($parent->option->notify && $parent->getUserID()){
					if($parent->getUserID() != get_current_user_id()){
						$url = new KBUrl();
						
						$notification = array(
							'to_user_id' => $parent->getUserID(),
							'title'      => $this->get_text('kboard_document_insert_title'),
							'content'    => sprintf($this->get_text('kboard_document_insert_content'), mb_strimwidth(wp_strip_all_tags($parent->getTitle()), 0, 50, '...')),
							'item_type'  => 'default',
							'meta_input' => array(
								'url'           => $url->getDocumentRedirect($content_uid),
								'url_name'      => $this->get_text('view')
							)
						);
						
						$notification = apply_filters('cosmosfarm_members_kboard_notify_document_insert', $notification, $content_uid, $board_id, $content, $board);
						cosmosfarm_members_send_notification($notification);
					}
				}
			}
		}
	}
	
	public function kboard_comments_insert($comment_uid, $content_uid, $board){
		if($this->option->notifications_kboard){
			$comment = new KBComment();
			$comment->initWithUID($comment_uid);
			
			if(!$comment->parent_uid){
				$content = new KBContent();
				$content->initWithUID($content_uid);
				
				if($content->option->notify && $content->getUserID()){
					if($content->getUserID() != get_current_user_id()){
						$url = new KBUrl();
						
						$notification = array(
							'to_user_id' => $content->getUserID(),
							'title'      => $this->get_text('kboard_comments_insert_title'),
							'content'    => sprintf($this->get_text('kboard_comments_insert_content'), mb_strimwidth(wp_strip_all_tags($content->getTitle()), 0, 50, '...')),
							'item_type'  => 'default',
							'meta_input' => array(
								'url'           => $url->getDocumentRedirect($content_uid),
								'url_name'      => $this->get_text('view')
							)
						);
						
						$notification = apply_filters('cosmosfarm_members_kboard_notify_comments_insert', $notification, $comment_uid, $content_uid, $board);
						cosmosfarm_members_send_notification($notification);
					}
				}
			}
		}
	}
	
	public function comments_field($field_html, $board, $content_uid, $builder){
		if(is_user_logged_in()){
			$display = true;
		}
		else{
			$display = false;
		}
		
		$content = new KBContent();
		$content->initWithUID($content_uid);
		
		if(apply_filters('cosmosfarm_members_comments_notify_display', $display, $content, $board, $builder)){
			?>
			<div class="comments-field">
				<label>
					<input type="checkbox" name="comment_option_notify" value="1"<?php if($this->is_comments_notify_default($board)):?> checked<?php endif?>>
					<?php echo apply_filters('cosmosfarm_members_comments_notify_text', __('Notify me of new comments', 'cosmosfarm-members'), $content, $board, $builder)?>
				</label>
			</div>
			<?php
		}
		return $field_html;
	}
	
	public function comments_insert($comment_uid, $content_uid, $board){
		if($this->option->notifications_kboard_comments){
			$comment = new KBComment();
			$comment->initWithUID($comment_uid);
			
			if($comment->parent_uid){
				$parent = new KBComment();
				$parent->initWithUID($comment->parent_uid);
				
				if($parent->option->notify && $parent->getUserID()){
					if($parent->getUserID() != get_current_user_id()){
						$url = new KBUrl();
						
						$notification = array(
							'to_user_id' => $parent->getUserID(),
							'title'      => $this->get_text('comments_insert_title'),
							'content'    => sprintf($this->get_text('comments_insert_content'), mb_strimwidth(wp_strip_all_tags($parent->content), 0, 50, '...')),
							'item_type'  => 'default',
							'meta_input' => array(
								'url'           => $url->getDocumentRedirect($content_uid),
								'url_name'      => $this->get_text('view')
							)
						);
						
						$notification = apply_filters('cosmosfarm_members_comments_notify_insert', $notification, $comment_uid, $content_uid, $board);
						cosmosfarm_members_send_notification($notification);
					}
				}
			}
		}
	}
	
	public function is_kboard_notify_default($board){
		return apply_filters('cosmosfarm_members_kboard_notify_default', true, $board);
	}
	
	public function is_comments_notify_default($board){
		return apply_filters('cosmosfarm_members_comments_notify_default', true, $board);
	}
	
	public function get_text($key=''){
		$text = array(
			'kboard_document_insert_title' => '게시글에 새로운 답글이 달렸습니다.',
			'kboard_document_insert_content' => '&quot;<strong>%s</strong>&quot; 게시글에 새로운 답글이 달렸습니다.',
			'kboard_comments_insert_title' => '게시글에 새로운 댓글이 달렸습니다.',
			'kboard_comments_insert_content' => '&quot;<strong>%s</strong>&quot; 게시글에 새로운 댓글이 달렸습니다.',
			'comments_insert_title' => '댓글에 새로운 답글이 달렸습니다.',
			'comments_insert_content' => '&quot;<strong>%s</strong>&quot; 댓글에 새로운 답글이 달렸습니다.',
			'view' => '확인하기'
		);
		
		$text = apply_filters('cosmosfarm_members_kboard_notify_text', $text);
		
		if($key){
			if(isset($text[$key])){
				return $text[$key];
			}
			return '';
		}
		return $text;
	}
}
?>