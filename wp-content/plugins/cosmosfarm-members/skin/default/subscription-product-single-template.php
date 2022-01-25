<?php
get_header();
$product = cosmosfarm_members_subscription_product();
$option = get_cosmosfarm_members_option();

$class_pick_post_id = $product->get_meta_value('class_pick');
if($class_pick_post_id){
	$class_pick_permalink = add_query_arg(array('iframe_id'=>'class-pick-iframe'), get_permalink($class_pick_post_id));
}
else{
	$class_pick_permalink = '';
}
?>
<div class="productMain <?php if($class_pick_permalink):?>class-pick<?php endif?>">
	<div class="leftContents">
		<div class="overview">
			<div class="ctrlBox">
				<div class="leftCtrl">
					<div class="ctrlNav">
						<a class="navItem" href="/">홈</a>
						<span class="navArrow">&gt;</span>
						<a class="navItem" href="/programs">프로그램 예약</a>
						<!--<a class="navItem" href="<?php echo esc_url(get_cosmosfarm_members_product_list_url())?>">미술콘텐츠몰</a>-->
					</div>
				</div>
			</div>
			
			<?php if($product->gallery_images()):?>
				<div class="cosmosfarm-subscription-product-single-gallery">
					<?php foreach($product->gallery_images() as $attachment_id):?>
					<div data-thumb="<?php echo wp_get_attachment_image_src($attachment_id, 'cosmosfarm-product-thumbnail')[0]?>" data-src="<?php echo wp_get_attachment_image_src($attachment_id, 'full')[0]?>">
						<?php echo wp_get_attachment_image($attachment_id, 'cosmosfarm-product-large')?>
					</div>
					<?php endforeach?>
				</div>
			<?php elseif(has_post_thumbnail()):?>
				<?php the_post_thumbnail()?>
			<?php else:?>
				<div class="cosmosfarm-subscription-product-single-no-gallery"></div>
			<?php endif?>
			
			<div class="titleWrap onlyMobile" style="padding-top: 30px;">
				<div class="rcPanelBody">
					<div class="titRow">
						<h1><?php the_title()?></h1>
					</div>
					<div>
						<div class="accPanelGroup">
							<div class="accPanel active">
								<div class="panelCollapse">
									<div class="detailBorder"></div>
									<div class="panelBody">
										<form method="get" action="<?php echo esc_url($product->get_order_url_without_query())?>">
											<input type="hidden" name="cosmosfarm_product_id" value="<?php echo $product->ID()?>">
											<input type="hidden" name="cosmosfarm_redirect_to" value="<?php echo esc_url($_SERVER['REQUEST_URI'])?>">
											<?php echo wpautop($product->excerpt())?>
											<div class="optionItemRow">
												<div class="optionItemCol" style="width: auto">
													<i class="fas fa-check"></i> <?php echo $product->get_meta_value('age')?>
												</div>
											</div>
											<div class="optionItemRow">
												<div class="optionItemCol" style="width: auto">
													<i class="fas fa-check"></i> <?php echo $product->get_meta_value('number_of_lessons')?>
												</div>
											</div>
											<div class="optionItemRow">
												<div class="optionItemCol" style="width: auto">
													<i class="fas fa-check"></i>
													<?php if($product->subscription_type() == 'onetime'):?>
														기간 무제한
													<?php else:?>
														<?php echo $product->subscription_type_format()?> / <?php echo $product->subscription_active() ? '이용기간 만료 후 자동결제' : '자동결제 없음'?>
													<?php endif?>
												</div>
											</div>
											<?php if($product->first_price_discount_rate()):?>
											<div class="optionItemRow" style="margin: 40px 0; border: 3px solid #ea5515; border-radius: 10px; color: black; background-color: white;">
												<div class="optionItemCol" style="position: relative; float: none; margin: 10px; width: auto;">
													<i class="fas fa-gift"></i>
													<?php if(is_user_logged_in()):?>
														<?php echo wp_get_current_user()->display_name?>님만의 특별한 혜택
													<?php else:?>
														플랜에이 회원만의 특별한 혜택
														<a href="<?php echo esc_url(wp_login_url($_SERVER['REQUEST_URI']))?>" style="position: absolute; right: 0; display:inline-block; margin-left: 10px; padding: 0 2px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">로그인</a>
													<?php endif?>
												</div>
												<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
													<span style="text-decoration: line-through;"><?php echo cosmosfarm_members_currency_format($product->price())?></span>
													&rarr; 첫 결제
													<span style="font-weight: bold;"><?php echo cosmosfarm_members_currency_format($product->first_price())?></span>
													총 <?php echo $product->first_price_discount_rate_format()?> 할인
												</div>
												<div class="optionItemCol" style="float: none; margin: 10px; width: auto; color: gray; font-size: 12px;">
													※ 첫 결제 할인 혜택은 곧 종료됩니다.
												</div>
											</div>
											<?php endif?>
											<?php if($product->ID() == '5535'):?>
											<div class="optionItemRow" style="margin: 40px 0; border: 3px solid #ea5515; border-radius: 10px; color: black; background-color: white;">
												<div class="optionItemCol" style="position: relative; float: none; margin: 10px; width: auto;">
													<i class="fas fa-gift"></i>
													<?php if(is_user_logged_in()):?>
														<?php echo wp_get_current_user()->display_name?>님만의 특별한 혜택
													<?php else:?>
														플랜에이 회원만의 특별한 혜택
														<a href="<?php echo esc_url(wp_login_url($_SERVER['REQUEST_URI']))?>" style="position: absolute; right: 0; display:inline-block; margin-left: 10px; padding: 0 2px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">로그인</a>
													<?php endif?>
												</div>
												<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
													45,000원 &rarr; <span style="font-weight: bold;">수업예약 수수료 0원</span>
												</div>
												<div class="optionItemCol" style="float: none; margin: 10px; width: auto; color: gray; font-size: 12px;">
													※ 수업료 나중에 결제 혜택은 곧 종료됩니다.
												</div>
											</div>
											<?php endif?>
											<?php if($product->ID() == '1341'):?>
											<div class="optionItemRow" style="margin: 40px 0; border: 3px solid #ea5515; border-radius: 10px; color: black; background-color: white;">
												<div class="optionItemCol" style="position: relative; float: none; margin: 10px; width: auto;">
													<i class="fas fa-gift"></i>
													알려드립니다.
												</div>
												<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
													총 4회 수업으로 구성된 1개월 수업을 신청하시면 더욱 저렴한 비용으로 플랜에이 프로그램을 경험하실 수 있습니다.
												</div>
												<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
													<a href="/cosmosfarm-product/basic-course-2/" style="display:inline-block; margin-right: 10px; padding: 0 4px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">Basic Course 보기</a>
													<a href="/cosmosfarm-product/planning-course/" style="display:inline-block; margin-right: 10px; padding: 0 4px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">Planning Course 보기</a>
													<a href="/programs/" style="display:inline-block; margin-right: 10px; padding: 0 4px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">전체 보기</a>
												</div>
											</div>
											<?php endif?>
											<?php if(wp_is_mobile()):?>
											
											<?php if(in_array($product->ID(), array('476', '478'))):?>
											<div class="optionItemRow">
												<div class="optionItemCol" style="float: none; width: auto">
													<i class="fa fa-calendar"></i>
													<label for="cosmosfarm_members_subscription_checkout_date">시작날짜 선택</label>
													<div>
														<input type="text" id="cosmosfarm_members_subscription_checkout_date" class="cosmosfarm-members-datepicker" name="date" value="<?php echo date('Y-m-d', current_time('timestamp'))?>" style="width:100%">
													</div>
												</div>
											</div>
											<div class="optionItemRow">
												<div class="optionItemCol" style="float: none; width: auto">
													<i class="fa fa-calendar"></i>
													<label for="cosmosfarm_members_subscription_checkout_time">시간 선택</label>
													<div>
														<select id="cosmosfarm_members_subscription_checkout_time" name="time" style="width:100%">
															<option value="9시">9시</option>
															<option value="10시">10시</option>
															<option value="11시">11시</option>
															<option value="12시">12시</option>
															<option value="13시">13시</option>
															<option value="14시">14시</option>
															<option value="15시">15시</option>
															<option value="16시">16시</option>
															<option value="17시">17시</option>
															<option value="18시">18시</option>
														</select>
													</div>
												</div>
											</div>
											<div class="optionItemRow">
												<div class="optionItemCol" style="float: none; width: auto">
													<i class="fa fa-calendar"></i>
													<label for="cosmosfarm_members_subscription_checkout_week">요일 선택</label>
													<div>
														<select id="cosmosfarm_members_subscription_checkout_week" name="week" style="width:100%">
															<option value="월요일">월요일</option>
															<option value="화요일">화요일</option>
															<option value="수요일">수요일</option>
															<option value="목요일">목요일</option>
															<option value="금요일">금요일</option>
														</select>
													</div>
												</div>
											</div>
											<?php endif?>
											
											<?php endif?>
											<div class="priceRow">
												<div class="priceCol">
													<a href="<?php echo esc_url($product->get_order_url())?>" class="purchaseBtn" onclick="trace_potential_buyer('<?php echo esc_js($product->title())?>', '<?php echo esc_js($product->price())?>')">
														<span>
															<b>수업 예약하기</b>
															<!--
															<b>프로그램 예약</b>
															<?php if($product->first_price() && $product->first_price() != $product->price()):?>
																<span class="tahoma">첫 달 <?php echo cosmosfarm_members_currency_format($product->first_price())?> (<?php echo round(($product->price()-$product->first_price())/$product->price()*100)?>% 할인)</span>
															<?php else:?>
																<span class="tahoma"><?php echo cosmosfarm_members_currency_format($product->price())?></span>
															<?php endif?>
															-->
														</span>
													</a>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="ratingWrap">
				<div class="ratingBox">
					<div class="ratingStars">
						<?php echo cosmosfarm_members_star_rating_display($product->average_ratings())?>
					</div>
					<div class="ratePoint">
						<div class="countingTxt"><?php echo get_comments_number()?>개의 평가</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="service-description" class="dscNavi">
			<div class="dscNaviItem active">
				<a href="#service-description" class="naviHeader plain inline-block padding-top-15 padding-bottom-15  " style="cursor: pointer;">수업 소개</a>
			</div>
			<?php if($class_pick_permalink):?>
			<div class="dscNaviItem">
				<a href="#class-pick" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer; color: #ea5415;">수업 Pick!</a>
			</div>
			<?php endif?>
			<div class="dscNaviItem">
				<a href="#cancellation-refund" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 정책</a>
			</div>
			<div class="dscNaviItem">
				<a href="#service-assessment" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 리뷰</a>
			</div>
		</div>
		<div class="dscContents">
			<div class="dscContPanel">
				<div class="dscContPanelRow">
					<div class="dcprDetail">
						<?php the_content()?>
						<?php
						$share_title = str_replace('[', '&#91;', $product->title());
						$share_title = str_replace(']', '&#93;', $share_title);
						echo do_shortcode('[cosmosfarm_share_buttons url="' . esc_url($product->permalink()) . '" title="' . $share_title . '" align="center"]')?>
					</div>
				</div>
			</div>
		</div>
		<?php if($class_pick_permalink):?>
		<div id="class-pick" class="dscNavi">
			<div class="dscNaviItem">
				<a href="#service-description" class="naviHeader plain inline-block padding-top-15 padding-bottom-15  " style="cursor: pointer;">수업 소개</a>
			</div>
			<?php if($class_pick_permalink):?>
			<div class="dscNaviItem active">
				<a href="#class-pick" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer; color: #ea5415;">수업 Pick!</a>
			</div>
			<?php endif?>
			<div class="dscNaviItem">
				<a href="#cancellation-refund" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 정책</a>
			</div>
			<div class="dscNaviItem">
				<a href="#service-assessment" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 리뷰</a>
			</div>
		</div>
		<div class="dscContents">
			<div class="dscContPanel">
				<div class="dscContPanelRow">
					<div class="dcprDetail">
						<iframe id="class-pick-iframe" src="<?php echo esc_url($class_pick_permalink)?>" style="width:100%" scrolling="no" frameborder="0"></iframe>
					</div>
				</div>
			</div>
		</div>
		<?php endif?>
		<div id="cancellation-refund" class="dscNavi">
			<div class="dscNaviItem">
				<a href="#service-description" class="naviHeader plain inline-block padding-top-15 padding-bottom-15  " style="cursor: pointer;">수업 소개</a>
			</div>
			<?php if($class_pick_permalink):?>
			<div class="dscNaviItem">
				<a href="#class-pick" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer; color: #ea5415;">수업 Pick!</a>
			</div>
			<?php endif?>
			<div class="dscNaviItem active">
				<a href="#cancellation-refund" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 정책</a>
			</div>
			<div class="dscNaviItem">
				<a href="#service-assessment" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 리뷰</a>
			</div>
		</div>
		<div class="cancelWrap">
			<div class="cancelPanel">
				<div class="cancelDsc">
					<?php echo wpautop(get_cosmosfarm_members_subscription_cancellation_refund_policy_content());?>
				</div>
			</div>
		</div>
		<div id="service-assessment" class="dscNavi">
			<div class="dscNaviItem">
				<a href="#service-description" class="naviHeader plain inline-block padding-top-15 padding-bottom-15  " style="cursor: pointer;">수업 소개</a>
			</div>
			<?php if($class_pick_permalink):?>
			<div class="dscNaviItem">
				<a href="#class-pick" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer; color: #ea5415;">수업 Pick!</a>
			</div>
			<?php endif?>
			<div class="dscNaviItem">
				<a href="#cancellation-refund" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 정책</a>
			</div>
			<div class="dscNaviItem active">
				<a href="#service-assessment" class="naviHeader plain inline-block padding-top-15 padding-bottom-15 " style="cursor: pointer;">수업 리뷰</a>
			</div>
		</div>
		<div class="ratingContain">
			<?php comments_template()?>
		</div>
	</div>
	<div class="rightContents" style="margin-top: 33px !important;">
		<div class="rightContain" style="">
			<div class="rightWrap mobileHidden">
				<div class="rcPanelBody">
					<div class="titRow">
						<h1><?php the_title()?></h1>
					</div>
				</div>
			</div>
			<div>
				<div class="accPanelGroup mobileHidden">
					<div class="accPanel active">
						<div class="panelCollapse">
							<div class="detailBorder"></div>
							<div class="panelBody">
								<form method="get" action="<?php echo esc_url($product->get_order_url_without_query())?>">
									<input type="hidden" name="cosmosfarm_product_id" value="<?php echo $product->ID()?>">
									<input type="hidden" name="cosmosfarm_redirect_to" value="<?php echo esc_url($_SERVER['REQUEST_URI'])?>">
									
									<?php echo wpautop($product->excerpt())?>
									<div class="optionItemRow">
										<div class="optionItemCol" style="width: auto">
											<i class="fas fa-check"></i> <?php echo $product->get_meta_value('age')?>
										</div>
									</div>
									<div class="optionItemRow">
										<div class="optionItemCol" style="width: auto">
											<i class="fas fa-check"></i> <?php echo $product->get_meta_value('number_of_lessons')?>
										</div>
									</div>
									<div class="optionItemRow">
										<div class="optionItemCol" style="width: auto">
											<i class="fas fa-check"></i>
											<?php if($product->subscription_type() == 'onetime'):?>
												기간 무제한
											<?php else:?>
												<?php echo $product->subscription_type_format()?> / <?php echo $product->subscription_active() ? '이용기간 만료 후 자동결제' : '자동결제 없음'?>
											<?php endif?>
										</div>
									</div>
									<?php if($product->first_price_discount_rate()):?>
									<div class="optionItemRow" style="margin: 40px 0; border: 3px solid #ea5515; border-radius: 10px; color: black; background-color: white;">
										<div class="optionItemCol" style="position: relative; float: none; margin: 10px; width: auto;">
											<i class="fas fa-gift"></i>
											<?php if(is_user_logged_in()):?>
												<?php echo wp_get_current_user()->display_name?>님만의 특별한 혜택
											<?php else:?>
												플랜에이 회원만의 특별한 혜택
												<a href="<?php echo esc_url(wp_login_url($_SERVER['REQUEST_URI']))?>" style="position: absolute; right: 0; display:inline-block; margin-left: 10px; padding: 0 2px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">로그인</a>
											<?php endif?>
										</div>
										<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
											<span style="text-decoration: line-through;"><?php echo cosmosfarm_members_currency_format($product->price())?></span>
											&rarr; 첫 결제
											<span style="font-weight: bold;"><?php echo cosmosfarm_members_currency_format($product->first_price())?></span>
											총 <?php echo $product->first_price_discount_rate_format()?> 할인
										</div>
										<div class="optionItemCol" style="float: none; margin: 10px; width: auto; color: gray; font-size: 12px;">
											※ 첫 결제 할인 혜택은 곧 종료됩니다.
										</div>
									</div>
									<?php endif?>
									<?php if($product->ID() == '5535'):?>
									<div class="optionItemRow" style="margin: 40px 0; border: 3px solid #ea5515; border-radius: 10px; color: black; background-color: white;">
										<div class="optionItemCol" style="position: relative; float: none; margin: 10px; width: auto;">
											<i class="fas fa-gift"></i>
											<?php if(is_user_logged_in()):?>
												<?php echo wp_get_current_user()->display_name?>님만의 특별한 혜택
											<?php else:?>
												플랜에이 회원만의 특별한 혜택
												<a href="<?php echo esc_url(wp_login_url($_SERVER['REQUEST_URI']))?>" style="position: absolute; right: 0; display:inline-block; margin-left: 10px; padding: 0 2px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">로그인</a>
											<?php endif?>
										</div>
										<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
											45,000원 &rarr; <span style="font-weight: bold;">수업예약 수수료 0원</span>
										</div>
										<div class="optionItemCol" style="float: none; margin: 10px; width: auto; color: gray; font-size: 12px;">
											※ 수업료 나중에 결제 혜택은 곧 종료됩니다.
										</div>
									</div>
									<?php endif?>
									<?php if($product->ID() == '1341'):?>
									<div class="optionItemRow" style="margin: 40px 0; border: 3px solid #ea5515; border-radius: 10px; color: black; background-color: white;">
										<div class="optionItemCol" style="position: relative; float: none; margin: 10px; width: auto;">
											<i class="fas fa-gift"></i>
											알려드립니다.
										</div>
										<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
											총 4회 수업으로 구성된 1개월 수업을 신청하시면 더욱 저렴한 비용으로 플랜에이 프로그램을 경험하실 수 있습니다.
										</div>
										<div class="optionItemCol" style="float: none; margin: 10px; width: auto;">
											<a href="/cosmosfarm-product/basic-course-2/" style="display:inline-block; margin-right: 10px; padding: 0 4px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">Basic Course 보기</a>
											<a href="/cosmosfarm-product/planning-course/" style="display:inline-block; margin-right: 10px; padding: 0 4px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">Planning Course 보기</a>
											<a href="/programs/" style="display:inline-block; margin-right: 10px; padding: 0 4px; font-size: 12px; background-color: #ea5515; color: white; border-radius: 5px;">전체 보기</a>
										</div>
									</div>
									<?php endif?>
									<?php if(!wp_is_mobile()):?>
									
									<?php if(in_array($product->ID(), array('476', '478'))):?>
									<div class="optionItemRow">
										<div class="optionItemCol" style="float: none; width: auto">
											<i class="fa fa-calendar"></i>
											<label for="cosmosfarm_members_subscription_checkout_date">시작날짜 선택</label>
											<div>
												<input type="text" id="cosmosfarm_members_subscription_checkout_date" class="cosmosfarm-members-datepicker" name="date" value="<?php echo date('Y-m-d', current_time('timestamp'))?>" style="width:100%">
											</div>
										</div>
									</div>
									<div class="optionItemRow">
										<div class="optionItemCol" style="float: none; width: auto">
											<i class="fa fa-calendar"></i>
											<label for="cosmosfarm_members_subscription_checkout_time">시간 선택</label>
											<div>
												<select id="cosmosfarm_members_subscription_checkout_time" name="time" style="width:100%">
													<option value="9시">9시</option>
													<option value="10시">10시</option>
													<option value="11시">11시</option>
													<option value="12시">12시</option>
													<option value="13시">13시</option>
													<option value="14시">14시</option>
													<option value="15시">15시</option>
													<option value="16시">16시</option>
													<option value="17시">17시</option>
													<option value="18시">18시</option>
												</select>
											</div>
										</div>
									</div>
									<div class="optionItemRow">
										<div class="optionItemCol" style="float: none; width: auto">
											<i class="fa fa-calendar"></i>
											<label for="cosmosfarm_members_subscription_checkout_week">요일 선택</label>
											<div>
												<select id="cosmosfarm_members_subscription_checkout_week" name="week" style="width:100%">
													<option value="월요일">월요일</option>
													<option value="화요일">화요일</option>
													<option value="수요일">수요일</option>
													<option value="목요일">목요일</option>
													<option value="금요일">금요일</option>
												</select>
											</div>
										</div>
									</div>
									<?php endif?>
									
									<?php endif?>
									<div class="priceRow">
										<div class="priceCol">
											<a href="<?php echo esc_url($product->get_order_url())?>" class="purchaseBtn" onclick="trace_potential_buyer('<?php echo esc_js($product->title())?>', '<?php echo esc_js($product->price())?>')">
												<span>
													<b>수업 예약하기</b>
													<!--
													<b>프로그램 예약</b>
													<?php if($product->first_price() && $product->first_price() != $product->price()):?>
														<span class="tahoma">첫 달 <?php echo cosmosfarm_members_currency_format($product->first_price())?> (<?php echo round(($product->price()-$product->first_price())/$product->price()*100)?>% 할인)</span>
													<?php else:?>
														<span class="tahoma"><?php echo cosmosfarm_members_currency_format($product->price())?></span>
													<?php endif?>
													-->
												</span>
											</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="icnBoxTxt" style="display:none">
			<div class="icnImg">
				<i class="fas fa-child"></i>
			</div>
			<h6 class="icnBoxCtn">클릭 몇 번이면 전문 미술강사가 원하는 시간, 장소로 방문합니다.</h6>
			<div class="clearfix"></div>
		</div>
		<!--
		<div class="icnBoxTxt">
			<div class="icnImg">
				<i class="fas fa-shield-alt"></i>
			</div>
			<h6 class="icnBoxCtn">플랜에이를 통해 결제하면 거래 완료시까지 결제 대금을 안전하게 보호 받을 수 있습니다.</h6>
			<div class="clearfix"></div>
		</div>
		<div class="sellerInfoContain">
			<div class="sellerInfoBox">
				<div class="sellerInfoPanel">
					<div class="siPanelBody">
						<div class="sipbRow">
							<div class="siImgWrap">
								<div class="sellerImgBox">
									<a class="sellerImgLink" href="#">
										<?php echo get_avatar($product->user()->ID, '', '', '', array('class'=>'sellerImg'))?>
									</a>
									<div class="rankImg master">
										<a href="#">
											<img src="/wp-content/uploads/2018/10/grade_m.png">
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="sipbRow">
							<div class="siTxtWrap">
								<h2 class="sellerName">
									<a class="sellerNameLink" href="#"><?php echo $product->user()->user_login?></a>
								</h2>
								<div class="snSeparator" style="border-bottom: 2px solid #ffd400"></div>
							</div>
						</div>
						<div class="sipbRow">
							<div class="siSubtxtWrap">
								<h5 class="subtxtTit">결제 후 플랜에이 통화 가능</h5>
								<h5 class="subtxtTit2">연락가능시간&nbsp;:&nbsp;9시&nbsp;~&nbsp;18시</h5>
							</div>
						</div>
						<div class="sipbRow">
							<div class="siSubtxtWrap">
								<a href="mailto:<?php echo $product->user()->user_email?>" type="button" class="slideBtn color2 fullBtn">
									<span class="btnTxt">전문가에게 문의 <i class="far fa-envelope"></i></span>
								</a>
							</div>
						</div>
						<div class="sipbRow">
							<div class="siDetailWrap">
								<div class="siDetailRow">
									<div class="siDetailCol">
										<div class="width25">
											<h5 class="siDetailTit">184건</h5>
											<h6 class="siDetailTxt">총작업개수</h6>
										</div>
										<div class="width20">
											<h5 class="siDetailTit">
												<span>97%</span>
											</h5>
											<h6 class="siDetailTxt">만족도</h6>
										</div>
										<div class="width30">
											<h5 class="siDetailTit">2시간 이내</h5>
											<h6 class="siDetailTxt">평균응답시간</h6>
										</div>
										<div class="width25 lastCol">
											<h5 class="siDetailTit">기업회원</h5>
											<h6 class="siDetailTxt">회원구분</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="sipbRow">
							<div class="siIntroWrap">
								<ul class="siIntroBox">
									<li class="siIntroList">
										<h5 class="siIntroTit">
											<b>전문가 소개</b>
										</h5>
										<h6 class="siIntroTxt">
											<span class="siIntroDsc">Brand Health는 <br>브랜드의 가치를 높여 사업의 성장을 돕습니다. 사업의 본질을 진단하고 최적의 솔루션을 제공합니다.</span>
										</h6>
									</li>
								</ul>
							</div>
						</div>
						<div class="sipbRow" style="">
							<div class="sipbTagWrap">
								<h6 class="sipbTag">
								<?php foreach($product->tags() as $tag):?>
									<span class="sipbTagItem">
										<a href="<?php echo esc_url($product->get_tag_link($tag->term_id))?>" class="tagBtn" title="<?php echo esc_attr($tag->name)?>">#<?php echo $tag->name?></a>
									</span>
								<?php endforeach?>
								</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		-->
		<div class="orderBottomBtn" style="display: block;">
			<div class="purchaseFixedWrapper">
				<div>
					<div class="pfwInner inactive" style="display: block;">
						<a href="<?php echo esc_url($product->get_order_url())?>" class="purchaseFixedBtn" style="right: 0;line-height: 40px;" onclick="trace_potential_buyer('<?php echo esc_js($product->title())?>', '<?php echo esc_js($product->price())?>')">
							<b style="color:white;">수업 예약하기</b>
							<!--
							<b>프로그램 예약</b>
							<?php if($product->first_price_discount_rate()):?>
								<span class="tahoma">첫 달 <?php echo cosmosfarm_members_currency_format($product->first_price())?> (<?php echo $product->first_price_discount_rate_format()?> 할인)</span>
							<?php else:?>
								<span class="tahoma"><?php echo cosmosfarm_members_currency_format($product->price())?></span>
							<?php endif?>
							-->
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
wp_enqueue_script('lightslider');
wp_enqueue_style('lightslider');
wp_enqueue_style('lightgallery');
wp_enqueue_script('lightgallery');
wp_enqueue_script('lg-zoom');

wp_enqueue_style('jquery-flick-style');
wp_enqueue_script('jquery-ui-datepicker');
?>

<script>
jQuery(document).ready(function(){
	jQuery('.cosmosfarm-subscription-product-single-gallery').lightSlider({
		gallery: true,
		item: 1,
		loop: true,
		thumbItem: 6,
		slideMargin: 0,
		adaptiveHeight: false,
		enableDrag: true,
		addClass: 'cosmosfarm-subscription-product-single-lightslider',
		enableTouch: true
	});
	
	jQuery('.cosmosfarm-subscription-product-single-gallery').lightGallery({
		download: false,
		getCaptionFromTitleOrAlt: false
	});
	
	if(typeof jQuery('.cosmosfarm-members-datepicker').datepicker === 'function'){
		jQuery('.cosmosfarm-members-datepicker').datepicker({
			closeText : '닫기',
			prevText : '이전달',
			nextText : '다음달',
			currentText : '오늘',
			monthNames : [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
			monthNamesShort : [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
			dayNames : [ '일', '월', '화', '수', '목', '금', '토' ],
			dayNamesShort : [ '일', '월', '화', '수', '목', '금', '토' ],
			dayNamesMin : [ '일', '월', '화', '수', '목', '금', '토' ],
			weekHeader : 'Wk',
			dateFormat : 'yy-mm-dd',
			firstDay : 0,
			isRTL : false,
			duration : 0,
			showAnim : 'show',
			showMonthAfterYear : true,
			yearSuffix : '년'
		});
	}
	
	dscContents_resize();
});


jQuery(window).resize(function(){
	dscContents_resize();
});

function dscContents_resize(){
	if(jQuery(window).width() < 600){
		jQuery('.productMain .dscContents').each(function(){
			jQuery(this).css('width', jQuery(window).width()+'px');
		});
	}
	else{
		jQuery('.productMain .dscContents').each(function(){
			jQuery(this).css('width', 'auto');
		});
	}
}

function trace_potential_buyer(product_title, product_price){
	gtag('event', 'potential_buyer', {'event_category':'potential_buyer', 'event_label':product_title});
	
	gtag('event', 'begin_checkout', {'value':product_price, 'currency':'KRW', 'items':{'name':product_title, 'price':product_price}});
	fbq('track', 'InitiateCheckout');
}

function user_logged_in_check(){
	trace_potential_buyer('<?php echo esc_js($product->title())?>', '<?php echo esc_js($product->price())?>');

	if(!cosmosfarm_members_settings.is_user_logged_in){
		if(confirm('로그인이 필요한 서비스입니다. 로그인 하시겠습니까?')){
			return true;
		}
		else{
			return false;
		}
	}
	
	return true;
}

function user_logged_in_check2(){
	trace_potential_buyer('<?php echo esc_js($product->title())?>', '<?php echo esc_js($product->price())?>');
	
	if(!cosmosfarm_members_settings.is_user_logged_in){
		if(confirm('로그인이 필요한 서비스입니다. 로그인 하시겠습니까?')){
			return true;
		}
		else{
			return false;
		}
	}
	
	return true;
}
</script>

<?php
get_sidebar();
get_footer();
?>