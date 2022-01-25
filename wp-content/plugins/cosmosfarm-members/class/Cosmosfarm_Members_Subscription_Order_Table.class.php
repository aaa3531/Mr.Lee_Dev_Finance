<?php
/**
 * Cosmosfarm_Members_Subscription_Order_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Order_Table extends WP_List_Table {
	
	var $option;
	var $courier_company_list;
	var $status;
	var $subscription_next;
	var $order_archives;
	
	public function __construct(){
		$this->option = get_cosmosfarm_members_option();
		$this->courier_company_list = cosmosfarm_members_courier_company_list();
		$this->status = isset($_REQUEST['status']) ? sanitize_text_field($_REQUEST['status']) : '';
		$this->subscription_next = isset($_REQUEST['subscription_next']) ? sanitize_text_field($_REQUEST['subscription_next']) : '';
		$this->order_archives = isset($_REQUEST['order_archives']) ? sanitize_text_field($_REQUEST['order_archives']) : '';
		
		parent::__construct(array('screen'=>'cosmosfarm_members_subscription_order'));
	}
	
	public function get_views(){
		global $wpdb;
		
		$order = new Cosmosfarm_Members_Subscription_Order();
		$views = array();
		$class = '';
		
		/*
		 * 전체
		 */
		$count = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->posts}` WHERE `post_type`='{$order->post_type}'");
		$class = !$this->status ? ' class="current"' : '';
		$views['all'] = '<a href="' . add_query_arg(array(), admin_url('admin.php?page=cosmosfarm_subscription_order')) . '"' . $class . '>' . '전체' . " <span class=\"count\">({$count})</span></a>";
		
		/*
		 * 결제됨
		 */
		$args = array('post_type'=>$order->post_type, 'meta_query'=>array(array(
			'key'     => 'status',
			'value'   => 'paid',
			'compare' => 'LIKE'
		)));
		$query = new WP_Query($args);
		$count = $query->found_posts;
		
		$class = $this->status == 'paid' ? ' class="current"' : '';
		$views['paid'] = '<a href="' . add_query_arg(array('status'=>'paid'), admin_url('admin.php?page=cosmosfarm_subscription_order')) . '"' . $class . '>' . '결제됨' . " <span class=\"count\">({$count})</span></a>";
		
		/*
		 * 취소됨
		 */
		$args = array('post_type'=>$order->post_type, 'meta_query'=>array(array(
			'key'     => 'status',
			'value'   => 'cancelled',
			'compare' => 'LIKE'
		)));
		$query = new WP_Query($args);
		$count = $query->found_posts;
		
		$class = $this->status == 'cancelled' ? ' class="current"' : '';
		$views['cancelled'] = '<a href="' . add_query_arg(array('status'=>'cancelled'), admin_url('admin.php?page=cosmosfarm_subscription_order')) . '"' . $class . '>' . '취소됨' . " <span class=\"count\">({$count})</span></a>";
		
		return $views;
	}
	
	public function prepare_items(){
		$this->_column_headers = $this->get_column_info();
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		$per_page = 20;
		$order = new Cosmosfarm_Members_Subscription_Order();
		$args = array(
				'post_type'      => $order->post_type,
				'orderby'        => 'ID',
				'posts_per_page' => $per_page,
				'paged'          => $this->get_pagenum(),
		);
		
		$meta_query = array();
		
		if($keyword){
			if($target == 'buyer_name'){
				$meta_query[] = array(
						'key'     => 'buyer_name',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else if($target == 'buyer_email'){
				$meta_query[] = array(
						'key'     => 'buyer_email',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else if($target == 'buyer_tel'){
				$meta_query[] = array(
						'key'     => 'buyer_tel',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else if($target == 'merchant_uid'){
				$meta_query[] = array(
						'key'     => 'merchant_uid',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else{
				$args['s'] = $keyword;
			}
		}
		
		if($this->status){
			$meta_query[] = array(
				'key'     => 'status',
				'value'   => $this->status,
				'compare' => 'LIKE',
			);
		}
		
		if($this->subscription_next){
			$meta_query[] = array(
				'key'     => 'subscription_next',
				'value'   => $this->subscription_next,
				'compare' => 'LIKE',
			);
		}
		
		if($this->order_archives){
			$time = strtotime($this->order_archives . '01');
			$args['date_query'] = array(
				'year'  => date('Y', $time),
				'month' => date('m', $time),
			);
		}
		
		$args['meta_query'] = $meta_query;
		
		$query = new WP_Query($args);
		$this->items = $query->posts;
		
		$this->set_pagination_args(array('total_items'=>$query->found_posts, 'per_page'=>$per_page));
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'subscription-order';
		return $classes;
	}
	
	public function no_items(){
		echo __('No orders found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		if($this->option->subscription_courier_company){
			$columns = array(
				'cb' => '<input type="checkbox">',
				'title' => '상품 이름',
				'price' => '가격',
				'status' => '결제 상태',
				'subscription_next' => '정기결제 상태',
				'error_message' => '다음 정기결제 실패',
				'pay_count' => '정기결제 회차',
				'user' => '사용자',
				'buyer' => '주문자',
				//'buyer_name' => '주문자명',
				//'buyer_email' => '이메일',
				//'buyer_tel' => '전화번호',
				//'merchant_uid' => '거래번호',
				'tracking' => '배송정보',
				'datetime' => '날짜',
			);
		}
		else{
			$columns = array(
				'cb' => '<input type="checkbox">',
				'title' => '상품 이름',
				'price' => '가격',
				'status' => '결제 상태',
				'subscription_next' => '정기결제 상태',
				'error_message' => '다음 정기결제 실패',
				'pay_count' => '정기결제 회차',
				'user' => '사용자',
				'buyer' => '주문자',
				//'buyer_name' => '주문자명',
				//'buyer_email' => '이메일',
				//'buyer_tel' => '전화번호',
				//'merchant_uid' => '거래번호',
				'datetime' => '날짜',
			);
		}
		return $columns;
	}
	
	public function get_bulk_actions(){
		return array(
			'refund' => __('Cancel orders', 'cosmosfarm-members'),
			'refund_and_delete' => __('Cancel and permanently delete', 'cosmosfarm-members'),
			'delete' => __('Delete Permanently', 'cosmosfarm-members')
		);
	}
	
	public function display_tablenav($which){
		global $wpdb;
		?>
		<div class="tablenav <?php echo esc_attr($which)?>">
			<div class="alignleft actions bulkactions"><?php $this->bulk_actions($which)?></div>
			<?php if($which=='top'):?>
				<div class="alignleft actions">
					<input type="hidden" name="status" value="<?php echo esc_attr($this->status)?>">
					<label class="screen-reader-text" for="subscription_next">정기결제 상태</label>
					<select id="subscription_next" name="subscription_next">
						<option value="">정기결제 상태</option>
						<option value="wait"<?php if($this->subscription_next == 'wait'):?> selected<?php endif?>>진행중</option>
						<option value="expiry"<?php if($this->subscription_next == 'expiry'):?> selected<?php endif?>>만료됨</option>
						<option value="success"<?php if($this->subscription_next == 'success'):?> selected<?php endif?>>정기결제 아님</option>
					</select>
					<select id="order_archives" name="order_archives">
						<option value="">모든 날짜</option>
						<?php
						$results = $wpdb->get_results("SELECT REPLACE(MID(`post_date`, 1, 7), '-', '') AS `monthly`, COUNT(*) AS `total` FROM `{$wpdb->posts}` WHERE `post_type`='cosmosfarm_order' GROUP BY `monthly` ORDER BY `monthly` DESC");
						foreach($results as $order_archives):
						?>
						<option value="<?php echo esc_attr($order_archives->monthly)?>"<?php if($this->order_archives == $order_archives->monthly):?> selected<?php endif?>><?php echo date('Y년 m월', strtotime($order_archives->monthly . '01'))?> (<?php echo esc_html($order_archives->total)?>개)</option>
						<?php endforeach?>
					</select>
					<input type="button" name="filter_action" class="button" value="필터" onclick="cosmosfarm_subscription_order_filter(this.form)">
					
					<?php
					$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
					$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
					$download_url = array(
						'action'            => 'cosmosfarm_members_order_download',
						'subscription_next' => $this->subscription_next,
						'order_archives'    => $this->order_archives,
						'target'            => $target,
						'keyword'           => $keyword
					);
					?>
					<input type="button" class="button" value="주문 내보내기" onclick="window.location.href='<?php echo wp_nonce_url(add_query_arg($download_url, admin_url('admin-post.php')), 'cosmosfarm-members-order-download', 'cosmosfarm-members-order-download-nonce')?>'">
					<span class="spinner"></span>
				</div>
				<script>
				function cosmosfarm_subscription_order_filter(form){
					var url = '<?php echo admin_url('admin.php?page=cosmosfarm_subscription_order')?>';
					var status = jQuery('input[name=status]', form).val();
					var subscription_next = jQuery('select[name=subscription_next]', form).val();
					var order_archives = jQuery('select[name=order_archives]', form).val();
					if(status){
						url += '&status='+status;
					}
					if(subscription_next){
						url += '&subscription_next='+subscription_next;
					}
					if(order_archives){
						url += '&order_archives='+order_archives;
					}
					window.location.href = url;
				}
				</script>
			<?php endif?>
			<?php
			$this->extra_tablenav($which);
			$this->pagination($which);
			?>
			<br class="clear">
		</div>
	<?php }
	
	public function display_rows(){
		foreach($this->items as $post){
			$order = new Cosmosfarm_Members_Subscription_Order($post->ID);
			$this->single_row($order);
		}
	}
	
	public function single_row($order){
		$user = $order->user();
		$user->filter = 'display';
		
		$edit_url = admin_url("admin.php?page=cosmosfarm_subscription_order&order_id={$order->ID}");
		$user_url = admin_url("user-edit.php?user_id={$user->ID}");
		$product = new Cosmosfarm_Members_Subscription_Product($order->product_id());
		
		list($columns) = $this->get_column_info();
		$column_keys = array_keys($columns);
		
		echo '<tr data-order-id="'.$order->ID.'">';
		
		if(in_array('cb', $column_keys)){
			echo '<th scope="row" class="column-cb check-column">';
			echo '<input type="checkbox" name="order_id[]" value="'.$order->ID.'">';
			echo '</th>';
		}
		
		if(in_array('title', $column_keys)){
			echo '<td class="column-title column-primary">';
			echo "<strong><a href=\"{$edit_url}\" class=\"row-title\" title=\"주문 정보\">".$order->title()."</a></strong>";
			echo '<button type="button" class="toggle-row"><span class="screen-reader-text">상세보기</span></button>';
			echo '</td>';
		}
		
		if(in_array('price', $column_keys)){
			echo '<td class="column-price" data-colname="가격">';
			if($order->pay_count() == '1'){
				$price_description = array();
				
				if($order->subscription_first_free()){
					echo cosmosfarm_members_currency_format(0);
					
					$price_description[] = '첫 결제 무료';
				}
				else if($order->coupon_id()){
					echo cosmosfarm_members_currency_format($order->coupon_price());
				}
				else{
					echo cosmosfarm_members_currency_format($order->first_price());
				}
				
				if($order->coupon_id()){
					$coupon = new Cosmosfarm_Members_Subscription_Coupon();
					$coupon->init_with_id($order->coupon_id());
					
					if($coupon->ID()){
						$price_description[] = sprintf('%s 쿠폰 적용', $coupon->title());
					}
					else{
						echo cosmosfarm_members_currency_format($order->coupon_price());
						$price_description[] = '쿠폰 적용';
					}
				}
				
				if($price_description){
					echo sprintf(' (%s)', implode(', ', $price_description));
				}
			}
			else if($order->coupon_id()){
				echo cosmosfarm_members_currency_format($order->coupon_price());
				
				$coupon = new Cosmosfarm_Members_Subscription_Coupon();
				$coupon->init_with_id($order->coupon_id());
				
				echo sprintf(' (%s 쿠폰 적용)', $coupon->title());
			}
			else{
				echo cosmosfarm_members_currency_format($order->price());
			}
			echo '</td>';
		}
		
		if(in_array('status', $column_keys)){
			echo '<td class="column-status status-'.$order->status().'" data-colname="결제 상태">';
			echo $order->status_format();
			echo '</td>';
		}
		
		if(in_array('subscription_next', $column_keys)){
			echo '<td class="column-subscription_next subscription-next-'.$order->subscription_next().'" data-colname="정기결제 상태">';
			echo $order->subscription_next_format() ? $order->subscription_next_format() . '<br>' : '';
			if($order->subscription_active()){
				echo sprintf('<span title="만료일 : %s">(이용기간 만료 후 자동결제)</span>', date('Y-m-d H:i', strtotime($order->end_datetime())));
			}
			else{
				echo sprintf('<span title="만료일 : %s">(자동결제 없음)</span>', date('Y-m-d H:i', strtotime($order->end_datetime())));
			}
			echo '</td>';
		}
		
		if(in_array('error_message', $column_keys)){
			echo '<td class="column-error_message" data-colname="다음 정기결제 실패">';
			echo $order->error_message();
			echo '</td>';
		}
		
		if(in_array('pay_count', $column_keys)){
			echo '<td class="column-pay_count" data-colname="정기결제 회차">';
			echo sprintf('%s회차', $order->pay_count());
			echo '</td>';
		}
		
		if(in_array('user', $column_keys)){
			if($user && $user->ID){
				echo '<td class="column-user" data-colname="사용자">';
				echo "<a href=\"{$user_url}\" title=\"사용자 편집\">{$user->display_name} ({$user->user_login})</a>";
				echo '</td>';
			}
			else{
				echo '<td class="column-user" data-colname="사용자">';
				echo '정보 없음';
				echo '</td>';
			}
		}
		
		if(in_array('buyer', $column_keys)){
			echo '<td class="column-buyer" data-colname="주문자">';
			$buyer_data = array();
			if($order->buyer_name){
				$buyer_data[] = sprintf('<span title="주문자명">%s</span>', $order->buyer_name);
			}
			if($order->buyer_email){
				$buyer_data[] = sprintf('<span title="이메일">%s</span>', $order->buyer_email);
			}
			if($order->buyer_tel){
				$buyer_data[] = sprintf('<span title="전화번호">%s</span>', $order->buyer_tel);
			}
			if($buyer_data){
				echo implode('<br>', $buyer_data);
			}
			echo '</td>';
		}
		
		/*
		echo '<td>';
		echo $order->merchant_uid;
		echo '</td>';
		*/
		
		if(in_array('tracking', $column_keys)){
			echo '<td class="column-tracking" data-colname="배송정보">';
			if($order->tracking_code()){
				$tracking_data = array();
				$courier_company = $order->courier_company() ? $order->courier_company() : $this->option->subscription_courier_company;
				$tracking_data[] = sprintf('<span title="택배사">%s</span>', $this->courier_company_list[$courier_company]['name']);
				$tracking_data[] = sprintf('<span title="운송장 번호"><a href="%s" class="button button-small" onclick="window.open(this.href);return false;">%s</a></span>', sprintf($this->courier_company_list[$courier_company]['tracking_url'], $order->tracking_code()), $order->tracking_code());
				if($tracking_data){
					echo implode('<br>', $tracking_data);
				}
			}
			else{
				echo '';
			}
			echo '</td>';
		}
		
		if(in_array('datetime', $column_keys)){
			echo '<td class="column-datetime" data-colname="날짜">';
			echo $order->post_date;
			echo '</td>';
		}
		
		echo '</tr>';
	}
	
	public function search_box($text, $input_id){
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		?>
		<p class="search-box">
			<input type="hidden" name="status" value="<?php echo esc_attr($this->status)?>">
			<input type="hidden" name="subscription_next" value="<?php echo esc_attr($this->subscription_next)?>">
			<select name="target" style="float:left;height:28px;margin:0 4px 0 0">
				<option value="">상품이름</option>
				<option value="buyer_name"<?php if($target == 'buyer_name'):?> selected<?php endif?>>주문자명</option>
				<option value="buyer_email"<?php if($target == 'buyer_email'):?> selected<?php endif?>>이메일</option>
				<option value="buyer_tel"<?php if($target == 'buyer_tel'):?> selected<?php endif?>>전화번호</option>
				<option value="merchant_uid"<?php if($target == 'merchant_uid'):?> selected<?php endif?>>거래번호</option>
			</select>
			<input type="search" id="<?php echo $input_id?>" name="s" value="<?php _admin_search_query()?>">
			<?php submit_button($text, 'button', false, false, array('id'=>'search-submit'))?>
		</p>
	<?php }
}