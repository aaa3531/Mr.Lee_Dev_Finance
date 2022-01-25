<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
<!--
    <meta property="og:type" content="website">
    <meta property="og:title" content="TechTrend">
    <meta property="og:description" content="넥스트 유니콘을 꿈꾸는 사람들을 위한 글로벌 트렌드 정보 플랫폼. Tech Trend와 함께 한 발 앞선 비즈니스로 거듭나세요.">
    <meta property="og:image" content="<?php echo get_template_directory_uri();?>/images/opimg.jpg">
    <meta property="og:url" content="<?php echo get_option("siteurl"); ?>">   
    <meta property="og:site_name" content="TechTrend">
    <meta property="og:author" content="TechTrend"> 
-->
    
<!--
    <meta name="title" content="TechTrend">
    <meta name="publisher" content="TechTrend">
    <meta name="author" content="TechTrend">
    <meta name="robots" content="index,follow">
    <meta name="keywords" content="스타트업,비즈니스모델,테크,트렌드,실리콘밸리"> 
    <meta name="description" content="넥스트 유니콘을 꿈꾸는 사람들을 위한 글로벌 트렌드 정보 플랫폼. Tech Trend와 함께 한 발 앞선 비즈니스로 거듭나세요.">
    <link rel="canonical" href="//techtrend.co.kr">
-->
    <?php if ( is_home() || is_front_page() ){  ?>
    <?php }else { ?>
    <meta property="og:type" content="website">
    <meta property="og:title" content="[ARKTERIA] <?php if ( is_single() ) {
        single_post_title('', true); 
    } ?>">
    <meta property="og:description" content="[ARKTERIA] <?php if ( is_single() ) {
        single_post_title('', true); 
    }?>" >
    <meta property="og:image" content="<?php if ( is_single() ) {
        if (has_post_thumbnail( $post->ID ) ):
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
       echo $image[0];
    endif;
    }?>">
    <meta property="og:url" content="<?php if ( is_single() ) {
        echo get_permalink();
    }?>">   
    <meta property="og:site_name" content="ARKTERIA">
    <meta property="og:author" content="ARKTERIA"> 
    
    <meta name="title" content="[ARKTERIA] <?php if ( is_single() ) {
        single_post_title('', true); 
    }?>">
    <meta name="publisher" content="ARKTERIA">
    <meta name="author" content="ARKTERIA">
    <meta name="robots" content="index,follow">
    <meta name="keywords" content="스타트업,비즈니스모델,테크,트렌드,실리콘밸리"> 
    <meta name="description" content="[ARKTERIA] <?php if ( is_single() ) {
        single_post_title('', true);
    }?>">
    <link rel="canonical" href="//dhstock.co.kr">
    <?php } ?>      
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="x-ua-compatible" content="IE=edge" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css"/>
    <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/fakescroll.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favi32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favi96.png">
    <link rel="icon" type="image/png" sizes="128x128" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favi128.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favi192.png">
    <script>
//        //HTTP 프로토콜 HTTPS로 변경
//    if(document.location.protocol == 'http:'){
//        document.location.href = document.location.href.replace('http:','https:');
//    }
    </script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z734PYNHXH"></script>
    <script>
//      window.dataLayer = window.dataLayer || [];
//      function gtag(){dataLayer.push(arguments);}
//      gtag('js', new Date());
//    
//      gtag('config', 'G-Z734PYNHXH');
    </script>
    <?php wp_head();?>
    <title><?php bloginfo('name'); ?></title>
</head>
<body>
<header class="header">
    <div id="header-top">
        <div class="page-w">
            <?php if( is_user_logged_in()): ?>
            <div class="top-login">
                <ul>
                   <?php if(current_user_can('administrator')){?>
                   <li><a href="<?php echo get_option("siteurl"); ?>/wp-admin" target="_blank">관리자</a></li>
                   <?php }?>
                    <li><a href="<?php echo get_option("siteurl"); ?>/mypage">마이페이지</a></li>
                    <li><a href="<?php echo wp_logout_url( home_url() ); ?>">로그아웃</a></li>  
                </ul>
            </div>
            <?php else: ?>
            <div class="top-login">
                <ul>
                    <li><a href="<?php echo get_option("siteurl"); ?>/login">로그인</a></li>
                    <li><a href="<?php echo get_option("siteurl"); ?>/join">회원가입</a></li>       
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="top-logo">
        <div class="page-w">
            <a href="<?php echo get_option("siteurl"); ?>" id="logo"><img src="<?php echo get_template_directory_uri(); ?>/images/arkteria.jpg" alt=""></a>
        </div>
    </div>
      <div class="main-menu">
        <div class="page-w">
            <div class="menu-inner" id="menu-inner">
                <?php main_menu();?>
            </div>
        </div>
      </div>
</header>