<?php
/**
 * Cosmosfarm_Members_Meta_Box
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Meta_Box {
	
	var $subscription_item;
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 99, 2);
		add_action('save_post', array($this, 'save_page_restriction'), 10, 2);
		add_action('save_post', array($this, 'save_subscription_item'), 10, 2);
		
		add_action('category_add_form_fields', array($this, 'new_category_meta_field'), 99, 2);
		add_action('category_edit_form_fields', array($this, 'edit_category_meta_field'), 99, 2);
		
		add_action('edited_category', array($this, 'save_category_meta_field'), 99, 2);
		add_action('create_category', array($this, 'save_category_meta_field'), 99, 2);
	}
	
	public function add_meta_boxes($post_type, $post){
		if($post_type == 'cosmosfarm_item'){
			$this->subscription_item = new Cosmosfarm_Members_Subscription_Item();
			$this->subscription_item->init_with_id($post->ID);
		}
		else{
			add_meta_box('cosmosfarm-members-page-restriction', __('Page Restriction', 'cosmosfarm-members'), array($this, 'render_page_restriction'), array(), 'side', 'default');
		}
		
		add_meta_box(uniqid(), '사용자', array($this, 'render_subscription_item_user_info'), array('cosmosfarm_item'), 'side', 'default');
		add_meta_box(uniqid(), '상품정보', array($this, 'render_subscription_item_product_info'), array('cosmosfarm_item'), 'side', 'default');
		add_meta_box(uniqid(), '주문정보', array($this, 'render_subscription_item_order_info'), array('cosmosfarm_item'), 'side', 'default');
		add_meta_box(uniqid(), '종료일', array($this, 'render_subscription_item_end_datetime_info'), array('cosmosfarm_item'), 'side', 'default');
	}
	
	public function render_page_restriction($post, $box){
		$page_restriction = get_post_meta($post->ID, 'cosmosfarm_members_page_restriction', true);
		$restriction_roles = get_post_meta($post->ID, 'cosmosfarm_members_page_restriction_roles', true);
		
		wp_nonce_field(basename(__FILE__), 'cosmosfarm_members_page_restriction_nonce');
		?>
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label" for="cosmosfarm_members_page_restriction">공개</label>
		</p>
		<select id="cosmosfarm_members_page_restriction" name="cosmosfarm_members_page_restriction">
			<option value="">전체 공개</option>
			<option value="1"<?php if($page_restriction):?> selected<?php endif?>>선택된 사용자만 공개</option>
		</select>
		
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label">사용자 선택</label>
		</p>
		<ul>
		<?php foreach(get_editable_roles() as $key=>$value):?>
			<li><label><input type="checkbox" name="cosmosfarm_members_page_restriction_roles[]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator' || (is_array($restriction_roles) && in_array($key, $restriction_roles))):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label></li>
		<?php endforeach?>
		</ul>
		
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label">추가 설정</label>
		</p>
		<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_setting')?>">코스모스팜 회원관리 설정으로 이동</a>
	<?php }
	
	public function render_subscription_item_user_info($post, $box){
		wp_nonce_field(basename(__FILE__), 'cosmosfarm_members_subscription_item_nonce');
		
		$user = get_userdata($post->post_author);
		if($user && $user->ID):
		?>
		
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label"><?php echo esc_html($user->display_name)?></label>
		</p>
		<a href="<?php echo get_edit_user_link($user->ID)?>" class="button button-small">상세 정보 보기</a>
		
		<?php else:?>
		
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label">정보 없음</label>
		</p>
		
		<?php endif?>
	<?php }
	
	public function render_subscription_item_product_info($post, $box){
		?>
		
		<?php if($this->subscription_item->product->ID()):?>
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label"><?php echo $this->subscription_item->product->title()?></label>
		</p>
		<a href="<?php echo admin_url('admin.php?page=cosmosfarm_subscription_product&product_id=' . $this->subscription_item->product->ID())?>" class="button button-small">상세 정보 보기</a>
		<?php else:?>
		<p class="post-attributes-label-wrapper">
			상품 정보가 없습니다.
		</p>
		<?php endif?>
		
	<?php }
	
	public function render_subscription_item_order_info($post, $box){
		?>
		
		<?php if($this->subscription_item->order->id()):?>
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label"><?php echo $this->subscription_item->order->title()?></label>
		</p>
		<a href="<?php echo admin_url('admin.php?page=cosmosfarm_subscription_order&order_id=' . $this->subscription_item->order->ID())?>" class="button button-small">상세 정보 보기</a>
		<?php else:?>
		<p class="post-attributes-label-wrapper">
			주문 정보가 없습니다.
		</p>
		<?php endif?>
		
	<?php }
	
	public function render_subscription_item_end_datetime_info($post, $box){
		$datetime = $this->subscription_item->get_end_datetime();
		$time = strtotime($datetime);
		?>
		
		<p class="post-attributes-label-wrapper">
			<label class="post-attributes-label">정기결제 만료일</label><br>
		</p>
		<?php if($time):?>
		<input type="text" name="order_end_year" size="4" maxlength="4" value="<?php echo date('Y', $time)?>">년
		<input type="text" name="order_end_month" size="2" maxlength="2" value="<?php echo date('m', $time)?>">월
		<input type="text" name="order_end_day" size="2" maxlength="2" value="<?php echo date('d', $time)?>">일
		<input type="text" name="order_end_hour" size="2" maxlength="2" value="<?php echo date('H', $time)?>">시
		<input type="text" name="order_end_minute" size="2" maxlength="2" value="<?php echo date('i', $time)?>">분
		<?php else:?>
		<input type="text" name="order_end_year" size="4" maxlength="4" value="0">년
		<input type="text" name="order_end_month" size="2" maxlength="2" value="0">월
		<input type="text" name="order_end_day" size="2" maxlength="2" value="0">일
		<input type="text" name="order_end_hour" size="2" maxlength="2" value="0">시
		<input type="text" name="order_end_minute" size="2" maxlength="2" value="0">분
		<?php endif?>
		
	<?php }
	
	function save_page_restriction($post_id, $post){
		if(!isset($_POST['cosmosfarm_members_page_restriction_nonce']) || !wp_verify_nonce($_POST['cosmosfarm_members_page_restriction_nonce'], basename(__FILE__))){
			return $post_id;
		}
		
		$post_type = get_post_type_object($post->post_type);
		if(!current_user_can($post_type->cap->edit_post, $post_id)){
			return $post_id;
		}
		
		$new_meta_value = isset($_POST['cosmosfarm_members_page_restriction']) ? $_POST['cosmosfarm_members_page_restriction'] : '';
		$this->meta_update($post_id, 'cosmosfarm_members_page_restriction', $new_meta_value);
		
		$new_meta_value = isset($_POST['cosmosfarm_members_page_restriction_roles']) ? $_POST['cosmosfarm_members_page_restriction_roles'] : array();
		$this->meta_update($post_id, 'cosmosfarm_members_page_restriction_roles', $new_meta_value);
	}
	
	function save_subscription_item($post_id, $post){
		if(!isset($_POST['cosmosfarm_members_subscription_item_nonce']) || !wp_verify_nonce($_POST['cosmosfarm_members_subscription_item_nonce'], basename(__FILE__))){
			return $post_id;
		}
		
		$post_type = get_post_type_object($post->post_type);
		if(!current_user_can($post_type->cap->edit_post, $post_id)){
			return $post_id;
		}
		
		$post_type = get_post_type($post_id);
		if(!in_array($post_type, array('cosmosfarm_item'))){
			return $post_id;
		}
		
		/*
		 * 정기결제 만료일 업데이트
		 */
		$subscription_item_id = isset($_POST['post_ID']) ? intval($_POST['post_ID']) : '';
		$subscription_item = new Cosmosfarm_Members_Subscription_Item();
		$subscription_item->init_with_id($subscription_item_id);
		
		if($subscription_item->ID()){
			$order_end_year = isset($_POST['order_end_year']) ? sanitize_text_field($_POST['order_end_year']) : '';
			$order_end_month = isset($_POST['order_end_month']) ? sanitize_text_field($_POST['order_end_month']) : '';
			$order_end_day = isset($_POST['order_end_day']) ? sanitize_text_field($_POST['order_end_day']) : '';
			$order_end_hour = isset($_POST['order_end_hour']) ? sanitize_text_field($_POST['order_end_hour']) : '';
			$order_end_minute = isset($_POST['order_end_minute']) ? sanitize_text_field($_POST['order_end_minute']) : '';
			
			if($subscription_item->get_end_datetime() && $order_end_year && $order_end_month && $order_end_day && $order_end_hour && $order_end_minute){
				$order_end_second = date('s', strtotime($subscription_item->get_end_datetime()));
				$next_datetime = date('YmdHis', mktime($order_end_hour, $order_end_minute, $order_end_second, $order_end_month, $order_end_day, $order_end_year));
				
				$subscription_item->set_end_datetime($next_datetime);
				
				// 주문 정보도 업데이트한다. (정보가 있을 경우)
				if($subscription_item->order->ID() && $subscription_item->order->end_datetime()){
					$subscription_item->order->set_end_datetime($next_datetime);
				}
				
				cosmosfarm_members_subscription_again_now();
			}
		}
	}
	
	public function meta_update($post_id, $meta_key, $new_meta_value){
		$meta_value = get_post_meta($post_id, $meta_key, true);
		
		if($new_meta_value){
			if(!$meta_value){
				add_post_meta($post_id, $meta_key, $new_meta_value, true);
			}
			else if($new_meta_value != $meta_value){
				update_post_meta($post_id, $meta_key, $new_meta_value);
			}
		}
		else if($meta_value){
			delete_post_meta($post_id, $meta_key, $meta_value);
		}
	}
	
	function new_category_meta_field(){ ?>
		<div class="form-field term-category-restriction-wrap">
			<label for="term_meta[cosmosfarm_members_category_restriction]"><?php echo __('Category Restriction', 'cosmosfarm-members')?></label>
			
			<select id="term_meta[cosmosfarm_members_category_restriction]" name="term_meta[cosmosfarm_members_category_restriction]">
				<option value="">전체 공개</option>
				<option value="1">선택된 사용자만 공개</option>
			</select>
			
			<label>사용자 선택</label>
			<ul style="margin:0">
			<?php foreach(get_editable_roles() as $key=>$value):?>
				<li><label><input type="checkbox" name="term_meta[cosmosfarm_members_category_restriction_roles][]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator'):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label></li>
			<?php endforeach?>
			</ul>
			
			<label>추가 설정</label>
			<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_setting')?>">코스모스팜 회원관리 설정으로 이동</a>
		</div>
	<?php }
	
	function edit_category_meta_field($term){
    	$term_meta = get_option("taxonomy_{$term->term_id}");
    	$category_restriction = isset($term_meta['cosmosfarm_members_category_restriction'])?$term_meta['cosmosfarm_members_category_restriction']:'';
    	$restriction_roles = isset($term_meta['cosmosfarm_members_category_restriction_roles'])?$term_meta['cosmosfarm_members_category_restriction_roles']:array();
    	?>
		<tr class="form-field term-category-restriction-wrap">
			<th scope="row" valign="top"><label for="term_meta[cat_icon]"><?php echo __('Category Restriction', 'cosmosfarm-members')?></label></th>
			<td>
				<select id="term_meta[cosmosfarm_members_category_restriction]" name="term_meta[cosmosfarm_members_category_restriction]">
					<option value="">전체 공개</option>
					<option value="1"<?php if($category_restriction):?> selected<?php endif?>>선택된 사용자만 공개</option>
				</select>
				
				<div>
					<label>사용자 선택</label>
					<br>
					<ul style="margin:0">
					<?php foreach(get_editable_roles() as $key=>$value):?>
						<li><label><input type="checkbox" name="term_meta[cosmosfarm_members_category_restriction_roles][]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator' || (is_array($restriction_roles) && in_array($key, $restriction_roles))):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label></li>
					<?php endforeach?>
					</ul>
				</div>
				
				<div class="description">
					<label>추가 설정</label>
					<br>
					<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_setting')?>">코스모스팜 회원관리 설정으로 이동</a>
				</div>
			</td>
		</tr>
	<?php }
	
	function save_category_meta_field($term_id){
		if(isset($_POST['term_meta'])){
			$term_meta = get_option("taxonomy_{$term_id}");
			$cat_keys = array_keys($_POST['term_meta']);
			foreach($cat_keys as $key){
				if(isset($_POST['term_meta'][$key])){
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
			update_option("taxonomy_{$term_id}", $term_meta);
		}
	}
}