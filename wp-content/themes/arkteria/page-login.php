<?php if(is_user_logged_in()){?>
<?php wp_redirect( home_url().'/mypage/' ); exit; ?>
<?php }else{ ?>
<?php get_header(); ?>
<div class="page-content">
          <div class="login-form">
          <h4>로그인</h4>
           <?php echo do_shortcode('[cosmosfarm_members_login_form]');?>
           </div>
</div>
<?php get_footer(); ?>
<?php } ?>