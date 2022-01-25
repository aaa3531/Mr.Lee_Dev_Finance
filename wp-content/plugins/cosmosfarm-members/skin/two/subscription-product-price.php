<?php if($product->first_price() && $product->first_price() != $product->price()):?>
	<span><?php echo cosmosfarm_members_currency_format($product->price())?></span>
	<span>&rarr;</span>
	<span>첫 결제 가격 <?php echo cosmosfarm_members_currency_format($product->first_price())?></span>
<?php else:?>
	<?php echo cosmosfarm_members_currency_format($product->price())?>	
<?php endif?>