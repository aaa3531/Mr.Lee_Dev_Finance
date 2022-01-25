<?php
/**
 * Cosmosfarm_Members_Mycred
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2020 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Mycred {
	
	public function __construct(){
		add_filter('mycred_add_finished', array($this, 'add_finished'), 999, 3);
		
		if(current_user_can('manage_options')){
			add_action('show_user_profile', array($this, 'edit_exclude_user_fields'));
			add_action('edit_user_profile', array($this, 'edit_exclude_user_fields'));
		
			add_action('personal_options_update', array($this, 'save_exclude_user_fields'));
			add_action('edit_user_profile_update', array($this, 'save_exclude_user_fields'));
		}
	}
	
	public function add_finished($reply, $request, $mycred){
		
		if($reply === false){
			return $reply;
		}
		
		$option = get_cosmosfarm_members_option();
		
		if(!$option->change_role_active){
			return $reply;
		}
		
		extract($request);
		
		$user = get_userdata($user_id);
		
		if(!$user){
			return $reply;
		}
		
		if(user_can($user_id, 'manage_options')){
			return $reply;
		}
		
		if($user->cosmosfarm_members_mycred_exclude){
			return $reply;
		}
		
		$balance['balance'] = $mycred->get_users_balance($user_id);
		$balance['kboard_document'] = intval($user->kboard_document_mycred_point);
		$balance['kboard_comments'] = intval($user->kboard_comments_mycred_point);
		
		$current_role = reset($user->roles);
		$new_role = false;
		
		foreach($option->change_role_thresholds as $role_key=>$threshold){
			
			if(isset($threshold['active']) && $threshold['active']){
				$role_filter = array();
				
				if($threshold['balance']['min'] != 0 || $threshold['balance']['max'] != 0){
					if($balance['balance'] >= $threshold['balance']['min'] && $balance['balance'] <= $threshold['balance']['max']){
						$role_filter[$role_key] = $role_key;
					}
					else{
						$role_filter[$current_role] = $current_role;
					}
				}
				
				if($threshold['kboard_document']['min'] != 0 || $threshold['kboard_document']['max'] != 0){
					if($balance['kboard_document'] >= $threshold['kboard_document']['min'] && $balance['kboard_document'] <= $threshold['kboard_document']['max']){
						$role_filter[$role_key] = $role_key;
					}
					else{
						$role_filter[$current_role] = $current_role;
					}
				}
				
				if($threshold['kboard_comments']['min'] != 0 || $threshold['kboard_comments']['max'] != 0){
					if($balance['kboard_comments'] >= $threshold['kboard_comments']['min'] && $balance['kboard_comments'] <= $threshold['kboard_comments']['max']){
						$role_filter[$role_key] = $role_key;
					}
					else{
						$role_filter[$current_role] = $current_role;
					}
				}
				
				if(count($role_filter) == 1){
					$role_filter = reset($role_filter);
					
					if(!in_array($role_filter, $user->roles)){
						$new_role = $role_filter;
					}
				}
			}
		}
		
		if($new_role !== false){
			$user->set_role($new_role);
		}
		
		return $reply;
	}
	
	public function edit_exclude_user_fields($user){ ?>
		<h3>코스모스팜 회원관리</h3>
		<table class="form-table">
			<tr>
				<th><label for="cosmosfarm_members_mycred_exclude">자동 등업 제외</label></th>
				<td>
					<label><input type="checkbox" id="cosmosfarm_members_mycred_exclude" name="cosmosfarm_members_mycred_exclude" value="1"<?php if(get_user_meta($user->ID, 'cosmosfarm_members_mycred_exclude', true)):?> checked<?php endif?>> 포인트에 상관없이 자동 등업을 하지 않고 현재 역할(Role)을 유지합니다.</label>
					<p class="description">관리자는 자동으로 항상 제외되어 역할(Role)이 바뀌지 않습니다.</p>
				</td>
			</tr>
		</table>
	<?php }
	
	public function save_exclude_user_fields($user_id){
		if(!current_user_can('manage_options')) return false;
		
		update_user_meta($user_id, 'cosmosfarm_members_mycred_exclude', isset($_POST['cosmosfarm_members_mycred_exclude'])?$_POST['cosmosfarm_members_mycred_exclude']:'');
	}
}
?>