<span class="cosmosfarm-members-subscription-product-button">
	<?php if($product->is_subscription_multiple_pay()):?>
		<button type="button" class="button-order" onclick="window.location.href='<?php echo $product->get_order_url()?>'">구매하기</button>
	<?php elseif($order = $product->is_in_use()):?>
		<?php if($order->subscription_next() == 'success'):?>
			<button type="button" class="button-order">사용중</button>
		<?php elseif($product->subscription_active()):?>
			<?php if($order->subscription_active()):?>
				<button type="button" class="button-order"><?php echo date('Y-m-d', strtotime($order->end_datetime()))?>에 자동결제됩니다.</button>
			<?php else:?>
				<button type="button" class="button-order"><?php echo date('Y-m-d', strtotime($order->end_datetime()))?>에 종료됩니다.</button>
			<?php endif?>
		<?php else:?>
			<button type="button" class="button-order">이용기간 <?php echo date('Y-m-d', strtotime($order->end_datetime()))?> 까지</button>
		<?php endif?>
	<?php else:?>
		<button type="button" class="button-order" onclick="window.location.href='<?php echo $product->get_order_url()?>'">구매하기</button>
	<?php endif?>
</span>