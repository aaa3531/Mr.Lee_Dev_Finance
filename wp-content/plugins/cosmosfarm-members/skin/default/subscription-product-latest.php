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
					<div class="listImg">
						<div class="thumbImg-wrap">
							<div class="thumbImg">
								<img class="listItemImg" src="<?php echo $product->thumbnail_src()?>" />
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
</div>