<?php
/**
 * Cosmosfarm_Members_Subscription_Checkout_Fields_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Checkout_Fields_Table extends WP_List_Table {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function prepare_items(){
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$per_page = 10;
		
		$product_id = isset($_GET['product_id']) ? sanitize_text_field($_GET['product_id']) : '';
		$product = new Cosmosfarm_Members_Subscription_Product($product_id);
		
		$this->items = $product->fields();
		$fields_count = count($this->items);
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'subscription-fields';
		return $classes;
	}
	
	public function no_items(){
		echo __('No checkout fields found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
			'field' => '필드',
			'label' => '라벨',
			'meta_key' => 'Meta Key',
			'data' => '데이터',
			'user_meta_key' => 'User Meta Key',
			'input' => '입력',
			'hide' => '주문내역',
			'delete' => '삭제'
		);
	}
	
	public function get_bulk_actions(){
		return array();
	}
	
	public function display_tablenav($which){
		return array();
	}
	
	public function display_rows(){
		$product_id = isset($_GET['product_id']) ? sanitize_text_field($_GET['product_id']) : '';
		$product = new Cosmosfarm_Members_Subscription_Product($product_id);
		
		$fields = $product->fields();
		$fields_count = count($fields);
		
		$args = array('product'=>$product);
		
		for($index=0; $index<$fields_count; $index++){
			if($fields[$index]['type'] == 'zip'){
				echo '<tr class="cosmosfarm-members-subscription-fields">' . $product->get_admin_field_template($fields[$index++], $fields[$index++], $fields[$index]) . '</tr>';
			}
			else{
				echo '<tr class="cosmosfarm-members-subscription-fields">' . $product->get_admin_field_template($fields[$index]) . '</tr>';
			}
		}
	}
	
	public function search_box($text, $input_id){
		$product_id = isset($_GET['product_id']) ? sanitize_text_field($_GET['product_id']) : '';
		$product = new Cosmosfarm_Members_Subscription_Product($product_id);
		?>
		<div class="tablenav top">
			<select name="new_field_type">
				<?php foreach($product->get_admin_field_template_list() as $key=>$value):?>
				<option value="<?php echo $key?>"><?php echo $value?></option>
				<?php endforeach?>
			</select>
			<button type="button" class="button" onclick="cosmosfarm_members_product_field_new()">새로운 필드 추가</button>
		</div>
		<?php
	}
}