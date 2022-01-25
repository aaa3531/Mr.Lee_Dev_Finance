<?php if(!defined('ABSPATH')) exit;
if($field_type == 'buyer_name'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'buyer_email'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="email" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'buyer_tel'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'text'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'number'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="number" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'select'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<?php
		$meta_value = get_post_meta($order->ID(), $field['meta_key'], true);
		$list = explode(',', $field['data']);
		$list = array_map('trim', $list);
		?>
		<select id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>">
			<?php foreach($list as $value):?>
			<option value="<?php echo $value?>"<?php if($value == $meta_value):?> selected<?php endif?>><?php echo esc_html($value)?></option>
			<?php endforeach?>
		</select>
	</td>
</tr>
<?php elseif($field_type == 'radio'):?>
<tr valign="top">
	<th scope="row"><label for=""><?php echo esc_html($field['label'])?></label></th>
	<td>
		<?php
		$meta_value = get_post_meta($order->ID(), $field['meta_key'], true);
		$list = explode(',', $field['data']);
		$list = array_map('trim', $list);
		?>
		<?php foreach($list as $value):?>
		<label><input type="radio" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr($value)?>"<?php if($value == $meta_value):?> checked<?php endif?>><?php echo esc_html($value)?></label>
		<?php endforeach?>
	</td>
</tr>
<?php elseif($field_type == 'checkbox'):?>
<tr valign="top">
	<th scope="row"><label for=""><?php echo esc_html($field['label'])?></label></th>
	<td>
		<?php
		$meta_value = get_post_meta($order->ID(), $field['meta_key']);
		$list = explode(',', $field['data']);
		$list = array_map('trim', $list);
		?>
		<?php foreach($list as $value):?>
		<label><input type="checkbox" name="<?php echo esc_attr($field['meta_key'])?>[]" value="<?php echo esc_attr($value)?>"<?php if(in_array($value, $meta_value)):?> checked<?php endif?>><?php echo esc_html($value)?></label>
		<?php endforeach?>
	</td>
</tr>
<?php elseif($field_type == 'zip'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo $field['label']?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field2['meta_key'])?>"><?php echo $field2['label']?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field2['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field2['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field2['meta_key'], true))?>">
	</td>
</tr>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field3['meta_key'])?>"><?php echo $field3['label']?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field3['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field3['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field3['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'textarea'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<textarea id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" rows="5" name="<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html(get_post_meta($order->ID(), $field['meta_key'], true))?></textarea>
	</td>
</tr>
<?php elseif($field_type == 'datepicker'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php elseif($field_type == 'timepicker'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<?php
		$meta_value = get_post_meta($order->ID(), $field['meta_key'], true);
		?>
		<select id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>">
			<option value="">선택</option>
			<?php for($time=mktime(9, 0, 0); $time<=mktime(21, 0, 0); $time=strtotime('+30 min', $time)):?>
			<option value="<?php echo date('H:i', $time)?>"<?php if(date('H:i', $time) == $meta_value):?> selected<?php endif?>><?php echo date('H:i', $time)?></option>
			<?php endfor?>
		</select>
		<?php
		if($meta_value){
			echo sprintf("(실제 저장된 데이터:%s)", $meta_value);
		}
		?>
	</td>
</tr>
<?php elseif($field_type == 'weekpicker'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<?php
		$meta_value = get_post_meta($order->ID(), $field['meta_key'], true);
		?>
		<select id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>">
			<option value="">선택</option>
			<option value="월요일"<?php if('월요일' == $meta_value):?> checked<?php endif?>>월요일</option>
			<option value="화요일"<?php if('화요일' == $meta_value):?> checked<?php endif?>>화요일</option>
			<option value="수요일"<?php if('수요일' == $meta_value):?> checked<?php endif?>>수요일</option>
			<option value="목요일"<?php if('목요일' == $meta_value):?> checked<?php endif?>>목요일</option>
			<option value="금요일"<?php if('금요일' == $meta_value):?> checked<?php endif?>>금요일</option>
		</select>
	</td>
</tr>
<?php elseif($field_type == 'hidden'):?>
<tr valign="top">
	<th scope="row"><label for="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>"><?php echo esc_html($field['label'])?></label></th>
	<td>
		<input type="text" id="cosmosfarm_members_subscription_order_<?php echo esc_attr($field['meta_key'])?>" class="regular-text" name="<?php echo esc_attr($field['meta_key'])?>" value="<?php echo esc_attr(get_post_meta($order->ID(), $field['meta_key'], true))?>">
	</td>
</tr>
<?php endif?>