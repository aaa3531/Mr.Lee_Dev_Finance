<?php get_header(); ?>
<div class="content">
    <div class="page-w">
       <div class="top-banner">
           <?php get_sidebar('top'); ?>
       </div>
        <div class="article">
            <div class="section" id="trends-sec">
                <h4>Trends</h4>
                <div class="section-inner">
                    <?php main_trends_list(array(
                    'category_name' => 'trends',
                    'posts_per_page' => 4
                    )); ?>
                </div>
            </div>
            
            <div class="section" id="latest-news">
                <h4>Latest News</h4>
                <div class="section-inner">
                    <?php main_latest_news(); ?>
                </div>
                <nav class="cat-paging"><?php pagination_bar();?></nav>
            </div>
            <div class="section" id="trends-sec-mobile">
                <h4>Trends</h4>
                 <?php main_slider_news(array(
                    'category_name' => 'trends',
                    'posts_per_page' => 8
                    )); ?>
            </div>
            <div class="section" id="latest-news-mobile">
                <h4>Latest News</h4>
                 <?php main_slider_news(array(
                    'posts_per_page' => 8,
                 )); ?>
            </div>
        </div>
         <div class="sidebar" id="index-sidebar">
            <?php get_sidebar('main'); ?>
        </div>
    </div>
</div>
<div class="slider-content">
    <div class="page-w">
        <div class="slider-sec">
            <div class="section" id="startup">
                <h4>StartUp</h4>
                <?php main_slider_news(array(
                    'category_name' => 'startup',
                    'posts_per_page' => 8
                    )); ?>
            </div>
            <div class="section" id="financial">
                <h4>Financing</h4>
                <?php main_slider_news(array(
                    'category_name' => 'financing',
                    'posts_per_page' => 8
                    )); ?>
            </div>
            <div class="section" id="workinsight">
                <h4>Workinsight</h4>
                <?php main_slider_news(array(
                    'category_name' => 'workinsight',
                    'posts_per_page' => 8
                    )); ?>
            </div>
            <div class="section" id="business">
                <h4>Blockchain</h4>
                <?php main_slider_news(array(
                    'category_name' => 'blockchain',
                    'posts_per_page' => 8
                    )); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>