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
           <div class="section" id="category-list">
           <h4><?php single_cat_title(); ?></h4>
             <?php if ( have_posts() ) : ?>
              <div class="section-inner">
               <?php news_category_list();?>
               </div>
               <?php else: ?>
                <div class="section-inner">
                    <div class="no-result">등록된 기사가 없습니다.</div>
                </div>
                <?php endif; ?>
               <nav class="cat-paging"><?php pagination_bar();?></nav>
           </div>
         </div>
         <div class="sidebar sub-sidebar">
            <?php get_sidebar('main'); ?>
            <div class="recommend">
                <h4>추천 기사</h4>
                <ul class="related-news">
                <?php 
                $related = new WP_Query(
                     array(
                         'category__in'   => wp_get_post_categories( $post->ID ),
                         'posts_per_page' => 3,
                         'post__not_in'   => array( $post->ID )
                     )
                 );

                 if( $related->have_posts() ) { 
                     while( $related->have_posts() ) { 
                         $related->the_post(); ?>
                          <li>
                          <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php               the_title_attribute(); ?>">
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
                     <?php }
                     wp_reset_postdata();
                 }
              ?>
                </ul>
            </div>
        </div>
   </div>
</div>
<?php get_footer(); ?>