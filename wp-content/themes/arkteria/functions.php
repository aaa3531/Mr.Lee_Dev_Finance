<?php 
// 썸네일 기능 사용하기
   add_theme_support( 'post-thumbnails' );
   show_admin_bar(false);
  //메뉴 활성화 시키기
   /*----------------------------------------------------------------*/
   function add_main_menu_position(){
       add_theme_support('menus');
       register_nav_menus(array(
           'main_menu' =>__('Main Menu'),
           'mobile_menu' =>__('Mobile Menu'),
           'footer_menu' =>__('Footer Menu'),
//           'login_menu' =>__('Login Menu'),
//           'my_menu' =>__('My Menu'),
//           'client_menu' =>__('Client Menu'),
//           'profess_menu' =>__('Profess Menu'),
       ));
   }
   add_action( 'after_setup_theme', 'add_main_menu_position' );
   
   //PC메뉴 등록하기
   /*----------------------------------------------------------------*/
   function main_menu() {
       add_main_menu_position();
       wp_nav_menu(array(
  
        'theme_location' => 'main_menu',
        'container_class' => 'main-menu-inner',
        'menu_class' => 'header-menu-ul',
        'container' => 'nav',
       )); 
   }
   //모바일 메뉴 등록하기
   /*----------------------------------------------------------------*/
   function mobile_menu() {
       add_main_menu_position();
       wp_nav_menu(array(
        'theme_location' => 'mobile_menu',
        'container_class' => 'm-header-menu',
        'menu_class' => 'm-cat',
        'container' => 'nav',
       )); 
   }

   //footer 메뉴 등록하기
   /*----------------------------------------------------------------*/
   function footer_menu() {
       add_main_menu_position();
       wp_nav_menu(array(
        'theme_location' => 'footer_menu',
        'container_class' => 'footer-menu',
        'menu_class' => 'footer-menu-ul',
        'container' => 'nav',
       )); 
   }


//메인 사이드바 기능
function enable_main_widgets() {
    register_sidebar(
        array(
            'name'          => __( '메인 배너(광고) 관리', 'techtrend' ),
            'id'   => 'top',
            'class'   => 'top-content',
            'description'   => 'Here you can add widgets to the main sidebar.',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 id="widget-heading">',
            'after_title'   => '</h5>',
    ));
 }
 add_action('widgets_init','enable_main_widgets');

//사이드바 기능
function enable_widgets() {
    register_sidebar(
        array(
            'name'          => __( '사이드 광고(배너)/위젯 관리', 'techtrend' ),
            'id'   => 'main',
            'class'   => 'sidebar-content',
            'description'   => 'Here you can add widgets to the main sidebar.',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 id="widget-heading">',
            'after_title'   => '</h5>',
    ));
 }
 add_action('widgets_init','enable_widgets');

    //메인화면 trends 목록
   function main_trends_list( $recent_trend ){
       $query = new WP_Query( $recent_trend );
        echo '<ul class="index-trends-list">';
        if($query->have_posts()){
            while( $query->have_posts()){
                $query->the_post();
                echo '<li>';
                echo '<a href="'.get_post_permalink().'">';
                if(has_post_thumbnail()){
                    echo '<div class="index-trends-thumbnail">';
                    the_post_thumbnail('full',array('class' => 'category-thumbnail-inner'));
                    echo '</div>';
                }else{
                    echo '<div class="none-index-pic-thumbnail">';
                    echo '</div>';
                }                 
                echo '<div class="index-trends-section">';
                echo '<h6>'.get_the_date().'</h6>';
                echo '<span>'.get_the_title().'</span>';
                echo '</div></a></li>';
            }
            wp_reset_postdata();
        }
        echo '</ul>';
   }

 //메인화면 latest 목록
   function main_latest_news(){
        echo '<ul class="index-latest-list">';
        if(have_posts()){
            while( have_posts()){
                the_post();
                $excerpt = mb_strimwidth( strip_tags(get_the_content()), 0, 200, '[...]' );
                echo '<li>';
                echo '<a href="'.get_post_permalink().'">';
                if(has_post_thumbnail()){
                    echo '<div class="index-list-thumbnail">';
                    the_post_thumbnail('full',array('class' => 'category-thumbnail-inner'));
                    echo '</div>';
                }else{
                    echo '<div class="none-index-pic-thumbnail">';
                    echo '</div>';
                }   
                echo '<div class="index-trends-section">';
                echo '<h3>'.get_the_title().'</h3>';
                echo '<p>'.$excerpt.'</p>';
                echo '<span class="list-author">'.get_author_name().'</span>';
                echo '<span class="list-date">POSTED ON '.get_the_date().'</span>';
                echo '</div></a></li>';
            }
            wp_reset_postdata();
        }
        echo '</ul>';
   }

//메인화면 slider list
function main_slider_news( $slider_news ){
       $query = new WP_Query( $slider_news );
        echo '<ul class="index-slider-list">';
        if($query->have_posts()){
            while( $query->have_posts()){
                $query->the_post();
                echo '<li>';
                echo '<a href="'.get_post_permalink().'">';
                if(has_post_thumbnail()){
                    echo '<div class="index-list-thumbnail">';
                    the_post_thumbnail('full',array('class' => 'category-thumbnail-inner'));
                    echo '</div>';
                }else{
                    echo '<div class="none-index-pic-thumbnail">';
                    echo '</div>';
                }   
               
                echo '<div class="index-trends-section">';
                echo '<h3>'.get_the_title().'</h3>';
                echo '</div></a></li>';
            }
            wp_reset_postdata();
        }
        echo '</ul>';
   }
//기사 카테고리
   function news_category_list(){
       echo '<ul class="index-latest-list">';
        if(have_posts()){
            while(have_posts()){
                the_post();
                $excerpt = mb_strimwidth( strip_tags(get_the_content()), 0, 200, '[...]' );
                echo '<li>';
                echo '<a href="'.get_post_permalink().'">';
                if(has_post_thumbnail()){
                    echo '<div class="index-list-thumbnail">';
                    the_post_thumbnail('full',array('class' => 'category-thumbnail-inner'));
                    echo '</div>';
                }else{
                    echo '<div class="none-index-pic-thumbnail">';
                    echo '</div>';
                }   
                
                echo '<div class="index-trends-section">';
                echo '<h3>'.get_the_title().'</h3>';
                echo '<p>'.$excerpt.'</p>';
                echo '<span class="list-author">'.get_author_name().'</span>';
                echo '<span class="list-date">POSTED ON '.get_the_date().'</span>';
                echo '</div></a></li>';
            }
            wp_reset_postdata();
        }
        echo '</ul>';
   }
   
	
 //카테고리 페이징
   function pagination_bar(){
        global $wp_query;
        $total_pages = $wp_query->max_num_pages;
        if ($total_pages > 1){
            $current_page = max(1, get_query_var('paged'));
            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'mid_size' => 10,
                'prev_text' => __('<div class="prev_page"><</div>'),
	            'next_text' => __('<div class="next_page">></div>'),
            ));
        } else if($total_pages == 1){
            $current_page = max(1, get_query_var('paged'));
            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'mid_size' => 1,
            ));
            echo '<span aria-current="page" class="page-numbers current">1</span>';
        }
    }

    //search 폼
    function wpdocs_after_setup_theme() {
    add_theme_support( 'html5', array( 'search-form' ) );
    }
    add_action( 'after_setup_theme', 'wpdocs_after_setup_theme' );


    add_filter('wpmem_member_links_args', 'wpmem_member_links_args_2020_03_31', 999, 1);
    function wpmem_member_links_args_2020_03_31($args){
	$current_user = wp_get_current_user();
	    $args['wrapper_before'] = '<div class="cosmosfarm-members-form member-form">';
		$args['wrapper_before'] .= '<div class="profile-header"><form id="cosmosfarm_members_avatar_form" method="post" enctype="multipart/form-data">';
		$args['wrapper_before'] .= wp_nonce_field('cosmosfarm_members_avatar', 'cosmosfarm_members_avatar_nonce');
		$args['wrapper_before'] .= '';
		$args['wrapper_before'] .= '<div class="avatar-img"><label for="cosmosfarm_members_avatar_file" title="'.__('Change Avatar', 'cosmosfarm-members').'">'.get_avatar(get_current_user_id(), '150').'<p class="change-avatar-message">'.__('Change Avatar', 'cosmosfarm-members').'</p><input type="file" name="cosmosfarm_members_avatar_file" id="cosmosfarm_members_avatar_file" multiple="false" accept="image/*" onchange="cosmosfarm_members_avatar_form_submit(this)"></label></div>';
        $args['wrapper_before'] .= '<div class="member-info">';
        $args['wrapper_before'] .= '<ul>';
		$args['wrapper_before'] .= '<li><div class="info-name">이름</div><div class="info">'.$current_user->display_name.' <span>(@'.$current_user->username.')</span></div></li>';
		$args['wrapper_before'] .= '<li><div class="info-name">이메일</div><div class="info">'.$current_user->user_email.'</div></li>';
		$args['wrapper_before'] .= '<li><div class="info-name">휴대폰</div><div class="info">'.$current_user->billing_phone.'</div></li>';
		$args['wrapper_before'] .= '<li><div class="info-name">가입일</div><div class="info">'.$current_user->user_registered.'</div></li>';
        $args['wrapper_before'] .= '</ul>';
		$args['wrapper_before'] .= '</div>';
		$args['wrapper_before'] .= '</form></div>';
		
		$args['wrapper_before'] .= '<ul class="members-link">';
		$args['wrapper_after'] = '</ul></div>';
	
	return $args;
}

?>