<div class="cosmosfarm-members-subscription cosmosfarm-members-subscription-checkout subscription-product-<?php echo $product->ID()?>">
	<h3 class="subscription-product-title"><?php echo $product->title()?></h3>
	
	<div class="subscription-description subscription-content"><?php echo wpautop($product->excerpt())?></div>
	
	<hr>
	
	<div class="subscription-description subscription-price">
	<?php if($product->first_price() && $product->first_price() != $product->price()):?>
		<span class="subscription-price"><?php echo cosmosfarm_members_currency_format($product->price())?></span>
		<span class="subscription-arrow">&rarr;</span>
		<span class="subscription-first-price">첫 결제 가격 <?php echo cosmosfarm_members_currency_format($product->first_price())?></span>
	<?php else:?>
		<?php echo cosmosfarm_members_currency_format($product->price())?>
	<?php endif?>
	</div>
	
	<div class="subscription-description subscription-type">
		<?php if($product->subscription_type() == 'onetime'):?>
			기간 무제한
		<?php else:?>
			<?php echo $product->subscription_type_format()?> / <?php echo $product->subscription_active() ? '이용기간 만료 후 자동결제' : '자동결제 없음'?>
		<?php endif?>
	</div>
	
	<?php if($product->first_price() && $product->first_price() != $product->price() && $product->subscription_again_price_type() != 'old_order' && $product->subscription_type() == 'onetime'):?>
	<div class="subscription-description subscription-content">
		첫 결제 이후 자동결제는 원래 가격(<?php echo cosmosfarm_members_currency_format($product->price())?>)으로 결제됩니다.
	</div>
	<?php endif?>
	
	<hr>
	
	<?php if($product->is_in_use() && !$product->is_subscription_multiple_pay()):?>
		<?php echo wpmem_inc_regmessage('already_existing_order', '<p>1회만 구매할 수 있는 상품으로 기간 종료 후 다시 구매해주세요.</p>')?>
	<?php else:?>
		<form autocomplete="off" method="post" onsubmit="return cosmosfarm_members_subscription_pay(this)">
			<input type="hidden" name="security" value="">
			<input type="hidden" name="product_id" value="<?php echo intval($product->ID())?>">
			<input type="hidden" name="extend_item_id" value="<?php echo esc_attr($extend_item_id)?>">
			
			<div class="checkout-attr-group">
				<?php if(!is_user_logged_in()):?>
					<h3 class="subscription-checkout-title"><?php echo esc_html($skin->subscription_checkout_title('먼저 회원가입을 해주세요'))?></h3>
					<?php echo $skin->subscription_checkout_field_template(array('type'=>'sign_up'))?>
					<hr>
				<?php endif?>
				
				<?php if($product->is_coupon_available()):?>
					<?php if($coupon->ID()):?>
						<h3 class="subscription-checkout-title"><?php echo esc_html($skin->subscription_checkout_title($coupon->title() . ' 쿠폰이 적용되었습니다'))?></h3>
						<div class="subscription-description subscription-coupon" style="margin: 0 0 10px 0;">
							<span class="subscription-price"><?php echo cosmosfarm_members_currency_format($price - $coupon_price)?> 할인된 <?php echo cosmosfarm_members_currency_format($coupon_price)?>이 결제됩니다.</span>
						</div>
						<?php if($coupon->discount_cycle() == 'once' && $product->subscription_again_price_type() != 'old_order' && $product->subscription_type() == 'onetime'):?>
						<div class="subscription-description subscription-coupon-content" style="margin: 0 0 10px 0;">
							첫 결제 이후 자동결제는 원래 가격(<?php echo cosmosfarm_members_currency_format($product->price())?>)으로 결제됩니다.
						</div>
						<?php endif?>
						<?php echo $skin->subscription_checkout_field_template(array('type'=>'coupon_code_remove'))?>
					<?php else:?>
						<h3 class="subscription-checkout-title"><?php echo esc_html($skin->subscription_checkout_title('쿠폰 코드가 있다면 입력해주세요'))?></h3>
						<?php echo $skin->subscription_checkout_field_template(array('type'=>'coupon_code_enter'))?>
					<?php endif?>
					<hr>
				<?php endif?>
				
				<?php
				do_action('cosmosfarm_members_skin_subscription_checkout_field_before', $product, $skin);
				?>
				
				<h3 class="subscription-checkout-title"><?php echo esc_html($skin->subscription_checkout_title('주문정보를 입력해주세요'))?></h3>
				<?php
				$fields = $product->fields();
				if($fields):
					$fields_count = count($fields);
					for($index=0; $index<$fields_count; $index++){
						if($fields[$index]['type'] == 'zip'){
							echo $skin->subscription_checkout_field_template($fields[$index++], $fields[$index++], $fields[$index]);
						}
						else{
							echo $skin->subscription_checkout_field_template($fields[$index]);
						}
					}
					?>
					<hr>
				<?php endif?>
				
				<?php
				do_action('cosmosfarm_members_skin_subscription_checkout_field_after', $product, $skin);
				?>
				
				<?php
				$iamport_pg_list = (array) get_cosmosfarm_members_subscription_iamport_pg_list();
				if(count($iamport_pg_list) > 1):?>
					<h3 class="subscription-checkout-title"><?php echo esc_html($skin->subscription_checkout_title('결제 수단'))?></h3>
					<?php echo $skin->subscription_checkout_field_template(array('type'=>'payment_method'))?>
					<hr>
				<?php endif?>
				
				<?php if($product->get_subscription_pg_type() == 'billing' && ($option->builtin_pg == 'nicepay' || $option->subscription_pg == 'nice')):?>
					<h3 class="subscription-checkout-title"><?php echo esc_html($skin->subscription_checkout_title('신용카드 정보 입력'))?></h3>
					<?php echo $skin->subscription_checkout_field_template(array('type'=>'nice_billing'))?> 
					<hr>
				<?php endif?>
				
				<?php echo $skin->subscription_checkout_field_template(array('type'=>'billing_agree'))?>
			</div>
			
			<button type="submit" data-display="<?php echo esc_attr($button_display_text)?>"><?php echo esc_html($button_display_text)?></button>
		</form>
	<?php endif?>
</div>