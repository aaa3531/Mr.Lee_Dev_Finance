<div class="ctrlBox">
	<div class="leftCtrl">
		<div class="ctrlNav">
			<a class="navItem" href="/">홈</a>
			<span class="navArrow">&gt;</span>
			<a class="navItem" href="<?php echo get_cosmosfarm_members_product_list_url()?>">미술콘텐츠몰</a>
		</div>	
	</div>
	<div class="rightCtrl">
		<div class="selectControl">
			<select class="sortingList">
				<option value="recommendationPoint">추천순</option>
				<option value="rankingPoints" selected>랭킹순</option>
				<option value="createdAt">신규등록순</option>
			</select>
		</div>
	</div>
</div>

<div class="contentsWraps">
	<!-- 리스트 시작 -->
	<ul class="contentsWrap">
		<?php while($query->have_posts()): $query->the_post()?>
		<?php
		$product_id = get_the_ID();
		$product = new Cosmosfarm_Members_Subscription_Product($product_id);
		?>
		<?php if($product->ID()):?>
		<li class="contentsList">
			<a href="<?php echo esc_url(get_post_permalink())?>">
				<div class="listItemWrap hot">
					<div class="badgeImg">
						<img class="badge01" src="/wp-content/uploads/2018/12/badge_01-1.png" title="hot item">
					</div>
					<div class="listImg">
						<div class="thumbImg-wrap">
							<div class="thumbImg">
								<?php echo get_the_post_thumbnail($product->ID(), '', array('class'=>'listItemImg'))?>
								<?php /*
								<div class="thumbImg-child" style="background-image:url(<?php echo $product->thumbnail_src()?>)"></div>
								*/?>
							</div>
						</div>
						<div class="itemProfile">
							<div class="profileWrap">
								<div class="profileBg"></div>
								<div class="profileRank master">
									<?php echo get_avatar($product->user()->ID, '70', '', '', array('class'=>'userImg'))?>
									<img id="profile" class="rankImg" src="/wp-content/uploads/2018/10/grade_m.png">
								</div>
							</div>
						</div>
					</div>
					<div class="descWrap">
						<div class="titleContainer">
							<div class="userInfo">
								<div class="userName"><?php echo $product->user()->user_login?></div>
							</div>
							<div class="titWrap">
								<h4 class="tit"><?php echo $product->title()?></h4>
							</div>
						</div>
						<div class="priceWrap"><span class="priceNum"><?php echo number_format($product->price())?></span> 원</div>
					</div>
					<div class="favWrap">
						<div class="half lft"><a href="#" class="favorite"><i class="fa fa-heart-o chk" style="font-weight: 800;"></i></a></div>
						<div class="half rgt">
							<div class="countStar">
								<?php echo cosmosfarm_members_star_rating_display($product->average_ratings())?>
							</div>
							<div class="countNum">(<?php comments_number( '0', '1', '%')?>)</div>
						</div>
					</div>
				</div>
			</a>
  		</li>
		<?php endif?>
		<?php endwhile?>
		<?php wp_reset_query()?>
	</ul>
	<!-- 리스트 끝 -->
	
	<!-- 페이징 시작 -->
	<div class="pagWrap">
		<?php
		$big = 999999999; // need an unlikely integer
		
		$pages = paginate_links(array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '?paged=%#%',
			'current' => max(1, get_query_var('paged')),
			'prev_text' => '«',
			'next_text' => '»',
			'total' => $query->max_num_pages,
			'type'  => 'array',
		));
		?>
		<?php if($pages):?>
		<ul class="pagItemWrap mobileHiddenIB">
			<?php foreach($pages as $page):?>
			<li><?php echo $page?></li>
			<?php endforeach?>
		</ul>
		<?php endif?>
	</div>
	<!-- 페이징 끝 -->
</div>