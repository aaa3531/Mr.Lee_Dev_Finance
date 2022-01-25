<div class="ctrlBox">
	<div class="leftCtrl">
		<div class="ctrlNav">
			<a class="navItem" href="/">홈</a>
			<span class="navArrow">></span>
			<a class="navItem" href="#">미술컨텐츠몰</a>
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
			<a href="<?php echo get_post_permalink()?>">
				<div class="listItemWrap hot">
					<div class="badgeImg">
						<img class="badge01" src="/wp-content/uploads/2018/12/badge_01-1.png" title="hot item">
					</div>
					<div class="listImg">
						<div class="thumbImg-wrap">
							<div class="thumbImg">
								<img class="listItemImg" src="<?php echo $product->thumbnail_src()?>" />
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
								<span class="star on"><i class="fas fa-star"></i></span>
								<span class="star on"><i class="fas fa-star"></i></span>
								<span class="star on"><i class="fas fa-star"></i></span>
								<span class="star on"><i class="fas fa-star"></i></span>
								<span class="star on"><i class="fas fa-star"></i></span>
							</div>
							<div class="countNum">(88)</div>
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

<?php /*
<div class="pagWrap">
	<ul class="pagItemWrap mobileHiddenIB">
		<li class="disabled"><span>«</span></li>
		<li class="active"><span>1</span></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">6</a></li>
		<li><a href="#">7</a></li>
		<li><a href="#">8</a></li>
		<li class="disabled"><span>...</span></li>
		<li><a href="#">126</a></li>
		<li><a href="#">127</a></li>
		<li><a href="#" rel="next">»</a></li>
		</ul>
		<ul class="pagItemWrap onlyMobileIB">
		<li class="disabled"><span>«</span></li>
		<li class="active"><span>1</span></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li class="disabled"><span>...</span></li>
		<li><a href="#">127</a></li>
		<li><a href="#" rel="next">»</a></li>
	</ul>
</div>
*/?>