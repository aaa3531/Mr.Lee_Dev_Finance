<?php
    $wpfp_before = "";
    echo "<div class='wpfp-span'>";
    if (!empty($user)) {
        if (wpfp_is_user_favlist_public($user)) {
            $wpfp_before = "$user's Favorite Posts.";
        } else {
            $wpfp_before = "$user's list is not public.";
        }
    }

    if ($wpfp_before):
        echo '<div class="wpfp-page-before">'.$wpfp_before.'</div>';
    endif;

    if ($favorite_post_ids) {
		$favorite_post_ids = array_reverse($favorite_post_ids);
        $post_per_page = wpfp_get_option("post_per_page");
        $page = intval(get_query_var('paged'));

        $qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
        // custom post type support can easily be added with a line of code like below.
        // $qry['post_type'] = array('post','page');
        query_posts($qry);
        
        echo "<ul class='index-latest-list'>";
        
        while ( have_posts() ) : the_post();
            $excerpt = mb_strimwidth( strip_tags(get_the_content()), 0, 200, '[...]' );
            echo "<li>";
            if(has_post_thumbnail()){
                echo "<div class='index-list-thumbnail'>";
                the_post_thumbnail('full',array('class' => 'category-thumbnail-inner'));
                echo "</div>";
            }else{
                echo "<div class='none-index-pic-thumbnail'>";
                echo "</div>";
            }
            echo "<a href='".get_permalink()."'>";
            echo "<div class='index-trends-section'>";
            echo "<h3>". get_the_title() ."</h3>";
            echo "<p>".$excerpt."</p>";
            echo "<span class='list-author'>".get_author_name()."</span>";
            echo "<span class='list-date'>POSTED ON ".get_the_date()."</span>";
            echo "</div></a>";
            wpfp_remove_favorite_link(get_the_ID());
            echo "</li>";
        endwhile;
        echo "</ul>";
        echo "<nav class='cat-paging'>";
        pagination_bar();
        echo "</nav>";
        echo '<div class="navigation">';
            if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
            <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
            <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>
            <?php }
        echo '</div>';
        wp_reset_query();
    }
        //    } else {
//        $wpfp_options = wpfp_get_options();
//        echo "<ul><li>";
//        echo $wpfp_options['favorites_empty'];
//        echo "</li></ul>";
//    }

    echo wpfp_clear_list_link();
    echo "</div>";
    wpfp_cookie_warning();
