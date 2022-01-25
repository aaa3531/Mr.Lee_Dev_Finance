<?php
/**
 * Cosmosfarm_Members_Subscription_Notification_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Notification_Table extends WP_List_Table {
	
	public function __construct(){
		parent::__construct(array('screen'=>'cosmosfarm_members_subscription_notification'));
	}
	
	public function prepare_items(){
		$this->_column_headers = $this->get_column_info();
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		$per_page = 20;
		$notification = new Cosmosfarm_Members_Subscription_Notification();
		$args = array(
			'post_type'      => $notification->post_type,
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
		$classes[] = 'subscription-notification';
		return $classes;
	}
	
	public function no_items(){
		echo __('No notifications found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
			'cb' => '<input type="checkbox">',
			'title' => '쿠폰 이름',
			'coupon_code' => '쿠폰 코드',
			'coupon_active' => '사용 가능 상태',
			'usage_limit' => '최대 사용 횟수',
			'usage_count' => '현재까지의 사용 횟수',
			'usage_date' => '사용 기간',
			'discount' => '적용 기준',
			'discount_amount' => '할인금액 / 할인율',
			'discount_cycle' => '적용 주기',
			'product' => '적용 상품',
		);
	}
	
	function get_bulk_actions(){
		return array(
			'delete' => __('Delete Permanently', 'cosmosfarm-members')
		);
	}
	
	public function display_rows(){
		foreach($this->items as $post){
			$coupon = new Cosmosfarm_Members_Subscription_Coupon($post->ID);
			$this->single_row($coupon);
		}
	}
	
	public function single_row($coupon){
		$edit_url = admin_url("admin.php?page=cosmosfarm_subscription_coupon&coupon_id={$coupon->ID()}");
		
		list($columns) = $this->get_column_info();
		$column_keys = array_keys($columns);
		
		echo '<tr data-coupon-id="'.$coupon->ID().'">';
		
		echo '<th scope="row" class="check-column">';
		echo '<input type="checkbox" name="coupon_id[]" value="'.$coupon->ID().'">';
		echo '</th>';
		
		echo '<td class="column-primary">';
		echo "<div><strong><a href=\"{$edit_url}\" class=\"row-title\" title=\"쿠폰 정보\">{$coupon->title()}</a></strong></div>";
		echo '<button type="button" class="toggle-row"><span class="screen-reader-text">상세보기</span></button>';
		echo '</td>';
		
		echo '<td data-colname="쿠폰 코드">';
		echo $coupon->content();
		echo '</td>';
		
		echo '<td data-colname="사용 가능 상태">';
		echo $coupon->active() ? '사용 가능' : '만료됨';
		echo '</td>';
		
		echo '<td data-colname="최대 사용 횟수">';
		echo $coupon->usage_limit() ? $coupon->usage_limit() : '무제한';
		echo '</td>';
		
		echo '<td data-colname="현재까지의 사용 횟수">';
		echo $coupon->usage_count() ? sprintf('%d회', $coupon->usage_count()) : '0회';
		echo '</td>';
		
		echo '<td data-colname="사용 기간">';
		echo $coupon->usage_date() == 'continue' ? '무제한' : $coupon->usage_start_date() . ' ~ ' . $coupon->usage_end_date();
		echo '</td>';
		
		echo '<td data-colname="적용 기준">';
		echo $coupon->discount() == 'amount' ? '할인금액' : '할인율';
		echo '</td>';
		
		echo '<td data-colname="할인금액/할인율">';
		echo $coupon->discount_amount();
		echo '</td>';
		
		echo '<td data-colname="적용 주기">';
		echo $coupon->discount_cycle() == 'subscription' ? '모든 정기결제 적용' : '첫 결제만 적용';
		echo '</td>';
		
		$post_title = array();
		foreach($coupon->product_ids() as $product_id){
			$product = new Cosmosfarm_Members_Subscription_Product($product_id);
			$post_title[] = $product->title();
		}
		echo '<td data-colname="적용 상품">';
		echo implode(', ', $post_title);
		echo '</td>';
		
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