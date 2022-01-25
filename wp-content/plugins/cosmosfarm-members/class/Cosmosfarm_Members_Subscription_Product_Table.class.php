<?php
/**
 * Cosmosfarm_Members_Subscription_Product_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Product_Table extends WP_List_Table {
	
	public function __construct(){
		parent::__construct(array('screen'=>'cosmosfarm_members_subscription_product'));
	}
	
	public function prepare_items(){
		$this->_column_headers = $this->get_column_info();
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		$per_page = 20;
		$product = new Cosmosfarm_Members_Subscription_Product();
		$args = array(
			'post_type'      => $product->post_type,
			'orderby'        => 'ID',
			'posts_per_page' => $per_page,
			'paged'          => $this->get_pagenum()
		);
		if($keyword){
			$args['s'] = $keyword;
		}
		$query = new WP_Query($args);
		$this->items = $query->posts;
		
		$this->set_pagination_args(array('total_items'=>$query->found_posts, 'per_page'=>$per_page));
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'subscription-product';
		return $classes;
	}
	
	public function no_items(){
		echo __('No products found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
			'cb' => '<input type="checkbox">',
			'title' => '상품 이름',
			'thumbnail' => '썸네일',
			'price' => '가격',
			'type' => '이용기간',
			'active' => '정기결제',
			'first_free' => '첫 결제 무료 이용기간',
			'role' => '사용자 역할(Role)',
			'multiple_pay' => '여러번 결제 가능',
		);
	}
	
	function get_bulk_actions(){
		return array(
			'delete' => __('Delete Permanently', 'cosmosfarm-members')
		);
	}
	
	public function display_rows(){
		foreach($this->items as $post){
			$product = new Cosmosfarm_Members_Subscription_Product($post->ID);
			$this->single_row($product);
		}
	}
	
	public function single_row($product){
		$edit_url = admin_url("admin.php?page=cosmosfarm_subscription_product&product_id={$product->ID}");
		
		list($columns) = $this->get_column_info();
		$column_keys = array_keys($columns);
		
		echo '<tr data-product-id="'.$product->ID.'">';
		
		if(in_array('cb', $column_keys)){
			echo '<th scope="row" class="column-cb check-column">';
			echo '<input type="checkbox" name="product_id[]" value="'.$product->ID.'">';
			echo '</th>';
		}
		
		if(in_array('title', $column_keys)){
			echo '<td class="column-title column-primary">';
			echo "<div><strong><a href=\"{$edit_url}\" class=\"row-title\" title=\"상품 정보\">{$product->post_title}</a></strong></div>";
			echo '<div>'.mb_strimwidth(wp_strip_all_tags($product->excerpt()), 0, 50, '...', 'UTF-8').'</div>';
			echo '<button type="button" class="toggle-row"><span class="screen-reader-text">상세보기</span></button>';
			echo '</td>';
		}
		
		if(in_array('thumbnail', $column_keys)){
			echo '<td class="column-thumbnail" data-colname="썸네일">';
			echo get_the_post_thumbnail($product->ID(), 'thumbnail');
			echo '</td>';
		}
		
		if(in_array('price', $column_keys)){
			echo '<td class="column-price" data-colname="가격">';
			$first_price = get_post_meta($product->ID(), 'first_price', true);
			if($first_price){
				echo sprintf('<div>%s</div>', cosmosfarm_members_currency_format($product->price()));
				echo sprintf('<div>(첫 결제 가격:%s)</div>', cosmosfarm_members_currency_format($first_price));
			}
			else{
				echo cosmosfarm_members_currency_format($product->price());
			}
			echo '</td>';
		}
		
		if(in_array('type', $column_keys)){
			echo '<td class="column-type" data-colname="이용기간">';
			echo $product->subscription_type_format();
			echo '</td>';
		}
		
		if(in_array('active', $column_keys)){
			echo '<td class="column-active" data-colname="정기결제">';
			echo $product->subscription_active() ? '이용기간 만료 후 자동 결제' : '자동 결제 없음';
			echo '</td>';
		}
		
		if(in_array('first_free', $column_keys)){
			echo '<td class="column-first_free" data-colname="첫 결제 무료 이용기간">';
			echo $product->subscription_first_free_format();
			echo '</td>';
		}
		
		if(in_array('role', $column_keys)){
			echo '<td class="column-role" data-colname="사용자 역할(role)">';
			echo $product->subscription_role() ? _x(wp_roles()->roles[$product->subscription_role()]['name'], 'User role') : '역할 변경 없음';
			echo '</td>';
		}
		
		if(in_array('multiple_pay', $column_keys)){
			echo '<td class="column-multiple_pay" data-colname="여러번 결제 가능">';
			echo $product->subscription_multiple_pay() ? '활성화' : '비활성화';
			echo '</td>';
		}
		
		echo '</tr>';
	}
	
	public function search_box($text, $input_id){
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		?>
		<p class="search-box">
			<input type="search" id="<?php echo $input_id?>" name="s" value="<?php _admin_search_query()?>">
			<?php submit_button($text, 'button', false, false, array('id'=>'search-submit'))?>
		</p>
	<?php }
}