<?php get_header(); ?>
<?php $edit = $_GET['a'];?>
<?php if($edit =='edit'){ ?>
<div class="page-content">
          <div class="avatar">
              <?php echo get_avatar(get_current_user_id(), '300');?>
          </div>
          <div class="login-form">
          <h4>회원정보 수정</h4>
           <?php echo do_shortcode('[wpmem_profile register=hide]');?>
           </div>
</div>
<?php } else if($edit =='pwdchange'){ ?>
<div class="page-content">
          <div class="login-form">
          <h4>비밀번호 변경</h4>
           <?php echo do_shortcode('[wpmem_profile register=hide]');?>
           </div>
</div>
<?php }else if($edit == 'pwdreset' || $edit == 'getusername'){?>
<div class="page-content">
          <div class="login-form">
          <h4>아이디/비밀번호 찾기</h4>
           <?php echo do_shortcode('[wpmem_profile register=hide]');?>
           </div>
</div>
<?php }else{ ?>
<div class="content">
    <div class="page-w">
        <div class="article">
           <div class="section" id="mypage-info">
              <h4>마이페이지</h4>
              <?php echo do_shortcode('[wpmem_profile register=hide]');?>
           </div>
            <div class="section" id="my-favorite-news">
              <h4>내가 구독한 기사<b> (<?php echo sizeof(wpfp_get_users_favorites()); ?>)</b></h4>
              <?php echo do_shortcode('[wp-favorite-posts]');?>
           </div>
           
         </div>
         <div class="sidebar sub-sidebar">
            <?php get_sidebar('main'); ?>
        </div>
   </div>
</div>
<?php }?>
<?php get_footer(); ?>