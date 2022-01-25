<?php if($product->subscription_type() == 'onetime'):?>
	기간 무제한
<?php else:?>
	<?php echo $product->subscription_type_format()?> / <?php echo $product->subscription_active() ? '자동결제' : '자동결제 없음'?>
<?php endif?>