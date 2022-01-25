<?php if(!defined('ABSPATH')) exit;?>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1 class="wp-heading-inline">코스모스팜 회원관리</h1>
	<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
	<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
	<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
	<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	<p>코스모스팜 회원관리는 한국형 회원가입 레이아웃과 기능을 제공합니다.</p>
	
	<hr class="wp-header-end">
	
	<form method="post" action="<?php echo admin_url('admin-post.php')?>">
		<?php wp_nonce_field('cosmosfarm-members-change-role-save', 'cosmosfarm-members-change-role-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_change_role_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_change_role_active">자동 등업</label></th>
					<td>
						<select id="cosmosfarm_members_change_role_active" name="cosmosfarm_members_change_role_active">
							<option value="">사용중지</option>
							<option value="1"<?php if($option->change_role_active):?> selected<?php endif?>>사용</option>
						</select>
						<p class="description"><a href="https://wordpress.org/plugins/mycred/" onclick="window.open(this.href);return false;">myCRED</a> 플러그인의 포인트를 기반으로 동작합니다.</p>
						<p class="description">사용자 역할(Role)을 추가하거나 관리는 <a href="https://wordpress.org/plugins/user-role-editor/" onclick="window.open(this.href);return false;">User Role Editor</a> 플러그인을 사용해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="">조건 설정</label></th>
					<td>
						<p class="description">역할(Role)을 체크하고 포인트 범위를 설정해주세요.</p>
						<p class="description">1. 총 합계 포인트, 2. KBoard 게시글 포인트, 3. KBoard 댓글 포인트 등 3가지 옵셥을 설정할 수 있습니다.</p>
						<p class="description">옵션은 1개, 2개 또는 3개 모두 설정이 가능하며 설정한 모든 조건이 맞아야 사용자가 해당 역할(Role)로 변경됩니다.</p>
						<p class="description">최소 포인트와 최대 포인트를 모두 입력해야 하며, 다른 역할(Role)의 포인트와 겹치지 않게 설정해주세요.</p>
						<ul>
						<?php foreach(get_editable_roles() as $key=>$value):?>
							<li><p>
								<label title="자동 등업 사용">
									<input type="checkbox" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][active]" value="1"<?php if(isset($option->change_role_thresholds[$key]['active']) && $option->change_role_thresholds[$key]['active']):?> checked<?php endif?>>
									<?php echo _x($value['name'], 'User role')?>
								</label>
								<br>
								총 합계 포인트 <input type="text" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][balance][min]" style="width:100px" placeholder="최소 포인트" value="<?php echo isset($option->change_role_thresholds[$key]['balance']['min'])?$option->change_role_thresholds[$key]['balance']['min']:''?>">~<input type="text" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][balance][max]" style="width:100px" placeholder="최대 포인트" value="<?php echo isset($option->change_role_thresholds[$key]['balance']['max'])?$option->change_role_thresholds[$key]['balance']['max']:''?>">,
								KBoard 게시글로 쌓은 포인트 <input type="text" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][kboard_document][min]" style="width:100px" placeholder="최소 포인트" value="<?php echo isset($option->change_role_thresholds[$key]['kboard_document']['min'])?$option->change_role_thresholds[$key]['kboard_document']['min']:''?>">~<input type="text" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][kboard_document][max]" style="width:100px" placeholder="최대 포인트" value="<?php echo isset($option->change_role_thresholds[$key]['kboard_document']['max'])?$option->change_role_thresholds[$key]['kboard_document']['max']:''?>">,
								KBoard 댓글로 쌓은 포인트 <input type="text" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][kboard_comments][min]" style="width:100px" placeholder="최소 포인트" value="<?php echo isset($option->change_role_thresholds[$key]['kboard_comments']['min'])?$option->change_role_thresholds[$key]['kboard_comments']['min']:''?>">~<input type="text" name="cosmosfarm_members_change_role_thresholds[<?php echo $key?>][kboard_comments][max]" style="width:100px" placeholder="최대 포인트" value="<?php echo isset($option->change_role_thresholds[$key]['kboard_comments']['max'])?$option->change_role_thresholds[$key]['kboard_comments']['max']:''?>">
							</p></li>
						<?php endforeach?>
						</ul>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<ul class="cosmosfarm-members-news-list">
							<?php
							foreach(get_cosmosfarm_members_news_list() as $news_item):?>
							<li>
								<a href="<?php echo esc_url($news_item->url)?>" target="<?php echo esc_attr($news_item->target)?>" style="text-decoration:none"><?php echo esc_html($news_item->title)?></a>
							</li>
							<?php endforeach?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
	</form>
</div>
<div class="clear"></div>