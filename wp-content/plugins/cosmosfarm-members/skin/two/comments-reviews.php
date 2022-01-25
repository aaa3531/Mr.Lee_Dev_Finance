<?php if(!defined('ABSPATH')) exit;?>

<?php
if(post_password_required()){
	return;
}

$total_comments_count = get_comments_number();
$product = cosmosfarm_members_subscription_product();
?>

<div id="comments" class="comments-area cosmosfarm-members-reviews">
	<div class="comments-info">
		<div class="comments-info-column">
			<div class="average-ratings">
				<?php
				if($product->average_ratings()){
					echo sprintf('<span class="large-font">%s</span>/5', $product->average_ratings());
				}
				else{
					echo '등록된 리뷰가 없습니다.';
				}
				?>
			</div>
			
			<div class="comments-stars">
				<?php echo cosmosfarm_members_star_rating_display($product->average_ratings())?>
			</div>
			
			<div class="total-comments-count">
				<?php echo $total_comments_count?>개의 평가
			</div>
		</div>
		<div class="comments-info-column">
			<?php
			$reviews_count = array();
			for($i=1;$i<=5;$i++){
				$reviews_count[$i] = get_comments(array(
					'post_id'=>$product->ID(),
					'status'=>'approve',
					'type'=>'comment',
					'meta_key'=>'rating',
					'meta_value'=>$i,
					'count'=>true
				));
			}
			$max_count = max($reviews_count);
			?>
			<div class="progressbar-wrap">
				<?php for($i=5; $i>=1; $i--):?>
				<div class="progressbar">
					<div class="progressbar-title">
						<?php echo $i?>
					</div>
					<div class="progressbar-background">
						<div class="progressbar-fill" style="width: <?php echo $reviews_count[$i] ? round($reviews_count[$i] / $max_count * 100) : 0?>%;"></div>
					</div>
				</div>
				<?php endfor?>
			</div>
		</div>
	</div>
	
	<?php if(have_comments()):?>
		<ul class="comment-list">
			<?php wp_list_comments(array('callback'=>'cosmosfarm_members_comments_reviews', 'max_depth'=>2))?>
		</ul>
		
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'twentytwelve' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentytwelve' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>
		
		<?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) :
			?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'twentytwelve' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(); ?>
</div>

<?php
function cosmosfarm_members_comments_reviews($comment, $args, $depth){
	global $post;
	
	$author_id = $post->post_author;
	$GLOBALS['comment'] = $comment;
	
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :?>
	<li id="comment-<?php comment_ID()?>" <?php comment_class()?>>
		<div class="pingback-entry"><span class="pingback-heading">Pingback:</span> <?php comment_author_link(); ?></div>
	<?php
		break;
		default :?>
	<li id="li-comment-<?php comment_ID()?>">
		<div id="comment-<?php comment_ID()?>" <?php comment_class('clr')?>>
			<div class="comment-header">
				<div class="comment-author vcard">
					<?php echo get_avatar($comment, 45)?>
				</div>
				
				<div class="comment-meta">
					<span class="comment-display-name"><?php comment_author_link()?></span>
					<span class="comment-date"><?php echo sprintf('<time datetime="%s">%s %s</time>', get_comment_time('c'), get_comment_date(), get_comment_time('h:i'))?></span>
				</div>
				
				<div class="comment-rating">
					<?php echo cosmosfarm_members_star_rating_display(get_comment_meta($comment->comment_ID, 'rating', true))?>
				</div>
			</div>
			
			<div class="comment-details clr">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.'); ?></p>
				<?php endif; ?>
				<div class="comment-content entry clr">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
				<div class="comment-links">
					<?php comment_reply_link(array_merge($args, array(
						'reply_text' => __('Reply', 'cosmosfarm-members'),
						'depth'      => $depth,
						'max_depth'	 => $args['max_depth'] )
					) ); ?>
					
					<?php edit_comment_link(); ?>
				</div>
			</div>
		</div>
	<?php
		break;
	endswitch; // End comment_type check.
}