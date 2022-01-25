<?php wp_footer(); ?>
<footer class="footer">
    <div class="footer-menu-section">
        <div class="page-w">
            <?php footer_menu();?>
        </div>
    </div>
    <div class="footer-address">
       <div class="page-w">
        <img class="footer-logo" src="<?php echo get_template_directory_uri(); ?>/images/footer-logo.svg" alt="">
        <div class="footer-sns">
            <ul>
                <li><a href="<?php bloginfo('rss2_url'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-rss.svg" alt="RSS 피드" title="RSS 피드"></a></li>
<!--
                <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-twitter.svg" alt=""></a></li>
                <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-facebook.svg" alt=""></a></li>
-->
            </ul>
        </div>
        <div class="footer-info">
            <p> 본 사이트는 개발사의 소속 클라이언트 사업주님의 프로젝트 일부로써, 본 사이트는 서비스영업용이 아닌 견본 샘플용임을 알려드립니다. 운영중이 아닌 홈페이지 샘플용입니다. </p>
        </div>
        <div class="footer-info">
            <p> This site is part of the project of the developer's client business owner, and we inform you that this site is for sample samples and not for service sales. This is for a sample website that is not in operation.</p>
        </div>
        <div class="footer-info">
            <ul>
                <li>ARKTERIA (DH Stock)</li>
                <li>서울 마포</li> 
<!--
                <li>등록번호 : 서울, 아OOOOO </li>
                <li>등록일자 : 2012년11월21일</li>
-->
               
                <li>대표이사 : 디에이치</li>
                <li>사업자등록번호 : 501-31-99941</li>
                <li>문의 : iddmglobal@gmail.com
 </li>
            </ul>
        </div>
        </div>
        <div id="copy">
            <p>Copyright 2021 &copy; ARKTERIA. All Rights Reserved</p>
        </div>
    </div>
</footer>
</body>
</html>