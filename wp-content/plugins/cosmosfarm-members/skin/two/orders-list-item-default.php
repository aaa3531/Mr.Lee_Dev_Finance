<li class="orders-list-item item-type-<?php echo $item->get_type()?> item-status-<?php echo $item->get_status()?> item-post-id-<?php echo $item->ID?> item-more-area-hide">
	<div class="item-right-wrap">
		<div class="cosmosfarm-members-item-wrap">
			<div class="add-item-middot item-date"><?php echo $item->post_date?></div>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($item->post_title):?>
			<div class="item-title"><?php echo esc_html($item->post_title)?></div>
			<?php endif?>
			
			<div class="item-content"><?php echo wpautop($item->post_content)?></div>
			
			<?php if($product->subscription_type() != 'onetime' && $product->subscription_active()):?>
				<div class="item-content">
					<?php if(in_array($item->subscription_next(), array('success', 'wait'))):?>
						<?php if($item->subscription_active()):?>
							<?php echo date('Y-m-d H:i', strtotime($item->end_datetime()))?> 이후 <span class="bold">자동결제</span>됩니다.
						<?php else:?>
							<?php echo date('Y-m-d H:i', strtotime($item->end_datetime()))?> 이후 <span class="bold">만료</span>됩니다.
						<?php endif?>
					<?php elseif(in_array($item->subscription_next(), array('expiry'))):?>
						<?php echo date('Y-m-d H:i', strtotime($item->end_datetime()))?>에 <span class="bold">만료</span>되었습니다.
					<?php endif?>
				</div>
			<?php endif?>
		</div>
		<div class="cosmosfarm-members-item-wrap">
			<?php if($item->receipt_url):?>
			<div class="add-item-middot"><a href="<?php echo esc_url($item->receipt_url)?>" onclick="window.open(this.href);return false;">영수증</a></div>
			<?php endif?>
			
			<?php
			if($option->subscription_courier_company){
				if($item->tracking_code()){
					$courier_company = $item->courier_company() ? $item->courier_company() : $option->subscription_courier_company;
					$courier_company_list = cosmosfarm_members_courier_company_list();
					echo '<div class="add-item-middot">';
					echo sprintf('<a href="%s" onclick="window.open(this.href);return false;">배송 상태 확인</a>', sprintf($courier_company_list[$courier_company]['tracking_url'], $item->tracking_code()));
					echo '</div>';
				}
				else{
					//echo '<a href="#" onclick="return false;">배송 준비중</a>';
				}
			}
			?>
			
			<?php if($fields):?>
			<div class="add-item-middot"><a href="#" onclick="return cosmosfarm_members_orders_toggle(this, '<?php echo $item->ID?>');">더보기 <i class="fas fa-sort-down"></i></a></div>
			<?php endif?>
		</div>
		<?php if($fields):?>
			<div class="cosmosfarm-members-item-wrap item-more-area">
				<table>
				<?php
				foreach($fields as $key=>$field){
					$meta_value = get_post_meta($item->ID(), $field['meta_key']);
					
					echo '<tr>';
					
					echo '<td class="field-label">';
					echo $field['label'];
					echo '</td>';
					
					echo '<td class="field-value">';
					if(is_array($meta_value)){
						echo implode(', ', $meta_value);
					}
					else{
						echo $meta_value;
					}
					echo '</td>';
					
					echo '</tr>';
				}
				?>
				</table>
			</div>
		<?php endif?>
	</div>
	
	<?php if($product->subscription_type() != 'onetime' && $product->subscription_active()):?>
		<?php if(in_array($item->subscription_next(), array('success', 'wait'))):?>
			<div class="item-subscription-status">
				<?php if($item->subscription_active()):?>
					<label>
						자동결제
						<div class="cosmosfarm-toggle-switch">
							<div class="switch">
							  <input type="checkbox" onchange="cosmosfarm_members_subscription_update(this, '<?php echo $item->ID()?>', '')"<?php if($item->subscription_active()):?> checked<?php endif?>>
							  <span class="slider round"></span>
							</div>
						</div>
					</label>
				<?php else:?>
					<label>
						자동결제
						<div class="cosmosfarm-toggle-switch">
							<div class="switch">
							  <input type="checkbox" onchange="cosmosfarm_members_subscription_update(this, '<?php echo $item->ID()?>', '1')"<?php if($item->subscription_active()):?> checked<?php endif?>>
							  <span class="slider round"></span>
							</div>
						</div>
					</label>
				<?php endif?>
			</div>
		<?php endif?>
	<?php endif?>
</li>