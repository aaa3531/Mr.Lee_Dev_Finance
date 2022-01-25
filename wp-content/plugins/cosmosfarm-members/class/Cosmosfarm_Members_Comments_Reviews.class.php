<?php
/**
 * Cosmosfarm_Members_Comments_Reviews
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Comments_Reviews {
	
	public function __construct(){
		
	}
	
	/**
	 * 액션을 등록한다.
	 */
	public function init_action(){
		add_action('comment_post', array($this, 'save_comment'), 10, 1);
		add_action('delete_comment', array($this, 'delete_comment'), 10, 2);
	}
	
	/**
	 * 정기결제 상품 댓글에 별점 추가
	 * @param array $default
	 * @return string
	 */
	public function add_field_to_comment_form(){
		add_action('comment_form_logged_in_after', array($this, 'rating_field'));
		add_action('comment_form_after_fields', array($this, 'rating_field'));
	}
	
	/**
	 * 별점 필드를 추가한다.
	 * @param array $fields
	 * @return array
	 */
	public function rating_field(){ ?>
		<p>
			<label for="rating">별점 <span class="required">*</span></label>
			<select id="rating" name="rating" required>
				<option value="">선택</option>
				<option value="1">★</option>
				<option value="2">★★</option>
				<option value="3">★★★</option>
				<option value="4">★★★★</option>
				<option value="5">★★★★★</option>
			</select>
		</p>
	<?php }
	
	/**
	 * 댓글의 별점 데이터를 저장한다.
	 * @param int $comment_id
	 */
	function save_comment($comment_id){
		$rating = isset($_POST['rating']) && $_POST['rating'] ? intval($_POST['rating']) : '';
		
		if($rating){
			add_comment_meta($comment_id, 'rating', $rating);
			
			$post_id = isset($_POST['comment_post_ID']) && $_POST['comment_post_ID'] ? intval($_POST['comment_post_ID']) : '';
			if($post_id){
				$comments = get_approved_comments($post_id);
				$average_ratings = 0;
				
				if($comments){
					$i = 0;
					$total = 0;
					
					foreach($comments as $comment){
						$rating = get_comment_meta($comment->comment_ID, 'rating', true);
						if($rating){
							$i++;
							$total += $rating;
						}
					}
					
					if($i > 0){
						$average_ratings = round($total / $i, 1);
					}
				}
				
				update_post_meta($post_id, 'average_ratings', $average_ratings);
			}
		}
	}
	
	/**
	 * 댓글을 삭제하면 별점 데이터도 삭제한다.
	 * @param int $comment_id
	 * @param WP_Comment $comment
	 */
	function delete_comment($comment_id, $comment){
		$post_id = $comment->comment_post_ID;
		$post = get_post($post_id);
		
		if($post->ID && $post->post_type == cosmosfarm_members_subscription_product()->post_type){
			$comments = get_approved_comments($post_id);
			$average_ratings = 0;
			
			if($comments){
				$i = 0;
				$total = 0;
				
				foreach($comments as $comment){
					$rating = get_comment_meta($comment->comment_ID, 'rating', true);
					if($rating){
						$i++;
						$total += $rating;
					}
				}
				
				if($i > 0){
					$average_ratings = round($total / $i, 1);
				}
			}
			
			update_post_meta($post_id, 'average_ratings', $average_ratings);
		}
	}
}