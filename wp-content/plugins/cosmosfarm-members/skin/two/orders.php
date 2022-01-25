<?php if(!defined('ABSPATH')) exit;?>

<?php echo $skin->header()?>

<div id="cosmosfarm-members-orders">
	<input type="hidden" name="orders_request_url" value="<?php echo get_permalink()?>">
	<input type="hidden" name="orders_list_page" value="1">
	<input type="hidden" name="orders_list_keyword" value="<?php echo esc_attr($keyword)?>">
	<input type="hidden" name="orders_list_view" value="<?php echo esc_attr($orders_view)?>">
	
	<div class="orders-search">
		<form method="get" action="<?php echo get_permalink()?>">
			<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
			<input type="hidden" name="orders_view" value="<?php echo esc_attr($orders_view)?>">
			<input type="text" name="keyword" value="<?php echo esc_attr($keyword)?>" placeholder="<?php echo __('Search', 'cosmosfarm-members')?>">
		</form>
	</div>
	
	<div class="orders-controlbar">
		<div class="controlbar-left">
    		<form method="get" action="<?php echo get_permalink()?>">
    			<input type="hidden" name="page_id" value="<?php echo get_the_ID()?>">
    			<input type="hidden" name="keyword" value="<?php echo esc_attr($keyword)?>">
        		<select name="orders_view" onchange="this.form.submit()">
        			<option value="paid"<?php if($orders_view == 'paid'):?> selected<?php endif?>>결제됨</option>
        			<option value="expired"<?php if($orders_view == 'expired'):?> selected<?php endif?>>만료됨</option>
        		</select>
    		</form>
		</div>
	</div>
	
	<ul class="orders-list">
		<?php echo $skin->orders_list()?>
	</ul>
	
	<button type="button" class="orders-more cosmosfarm-members-button" onclick="cosmosfarm_members_orders_more(this)"><?php echo __('More', 'cosmosfarm-members')?></button>
</div>