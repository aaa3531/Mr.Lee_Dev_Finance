<?php get_header(); ?>
<div class="content">
    <div class="page-w">
       <div class="top-banner">
           <?php get_sidebar('top'); ?>
          </div>
        <div class="article">
           <div class="section" id="hashtag">
               <h4>인기 태그 모음</h4>
               <div class="section-inner">
                  <ul class="popular-tag">
                   <?php
                        global $wpdb;
                        $term_ids = $wpdb->get_col("
                        SELECT term_id , count(*) cont FROM $wpdb->term_taxonomy
                        INNER JOIN $wpdb->term_relationships ON $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id
                        INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
                        WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= $wpdb->posts.post_date AND  $wpdb->term_taxonomy.taxonomy='post_tag'
                        GROUP BY term_id
                        ORDER BY cont DESC
                        LIMIT 20");

                        if(count($term_ids) > 0){
                           $tags = get_tags(array(
                           'orderby' => 'count',
                           'order'   => 'DESC',
                           'number'  => 20,
                           'include' => $term_ids,
                        ));
                        foreach ( (array) $tags as $tag ) {
                           echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag"># ' . $tag->name . '</a></li>';
                        }
                     }
                     ?>
                     </ul>
               </div>
           </div>
           <div class="section" id="news-posting">
              <div class="news-inner">
               <?php if(have_posts()) {
                      while(have_posts()) :the_post();?>
                     <div class="post-title-bg">
                         <h3><?php the_title(); ?></h3>
                         <p><span class="author"><?php echo get_avatar( get_the_author_meta('ID'), 60); ?><?php the_author(); ?></span> <span class="date">POST ON <?php the_date(); ?></span></p>
                        <div id="button-s">
                         <?php if(current_user_can('administrator')){ 
                            echo edit_post_link( __( '기사수정', 'textdomain' ), '<p>', '</p>' );
                         } ?> 
                         <?php if( is_user_logged_in()){ ?>
                         <?php if (function_exists('wpfp_link')) { wpfp_link(); } ?> 
                         <?php }else{ ?>
                         <span class="add-my-news">
                             <a href="<?php echo get_option("siteurl"); ?>/login/" onclick="alert('기사를 구독하시려면 회원로그인이 필요합니다.');">+ 기사 구독하기</a>
                         </span>
                         <?php } ?> 
                         </div>   
                     </div>
                     <content class="inner-contents">
                         <?php the_content(); ?>
                     </content>
                     <div class="hashtag">
                        <?php the_tags( '<ul class="popular-tag"><li>', '</li><li>', '</li></ul>' ); ?>
                     </div>
                     <?php if ( comments_open() || get_comments_number() ) : ?>
					   <div class="comment-area">
					         <?php comments_template(); ?>
					   </div>
				     <?php endif;?>
                     <!--            하단 네비 메뉴 -->
<!--
                     <div class="post_navi_menu">
                         <?php previous_post_link('%link', '<div class="prev_post_arrow">이전</div>', TRUE); ?>
                         <?php next_post_link('%link', '<div class="next_post_arrow">다음</div>', TRUE); ?>
                     </div>  
-->
                     <?php endwhile;
			            }
                ?>
               </div>
           </div>
         </div>
         <div class="sidebar sub-sidebar">
            <?php get_sidebar('main'); ?>
             <div class="recommend">
                <h4>관련기사 보기</h4>
                <div class="related">
                <?php 
                $tags = wp_get_post_tags($post->ID);
if ($tags) {
    echo '<ul class="related-news">';
    $max=count($tags) >5 ? 5 : count($tags) ;
    $idsToExclude=[$post->ID];
    //ob_start();
    for($k=0;$k< $max ;$k++){
        $args=array(
        'tag__in' => $tags[$k]->term_id,
        'post__not_in' => $idsToExclude,
        'posts_per_page'=>5,
        'ignore_sticky_posts'=>1 // caller_get_posts is deprecated
        );
        $my_query = new WP_Query($args);
        if( $my_query->have_posts() ) {
            while ($my_query->have_posts()) : $my_query->the_post(); 
            if(!in_array(get_the_id(),$idsToExclude)){
                $idsToExclude[]=get_the_id();   // add this id to the exclusion for the next query
            }           
            ?>
            <li>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
            <?php if(has_post_thumbnail()){
                    echo '<div class="recommend-thumbnail">';
                    the_post_thumbnail('full',array('class' => 'category-thumbnail-inner'));
                    echo '</div>';
                }else{
                    echo '<div class="none-index-pic-thumbnail">';
                    echo '</div>';
                } 
            ?>
            <?php the_title(); ?></a>
            </li>
            <?php
            endwhile;
        }

    }
    echo '</ul>';
    //echo ob_get_clean();
    wp_reset_query();
}
              ?>
              </div>
            </div>
        </div>
   </div>
</div>
<?php get_footer(); ?>