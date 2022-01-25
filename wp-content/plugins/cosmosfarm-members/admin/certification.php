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
		<?php wp_nonce_field('cosmosfarm-members-certification-save', 'cosmosfarm-members-certification-save-nonce')?>
		<input type="hidden" name="action" value="cosmosfarm_members_certification_save">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_policy_privacy">아임포트</label></th>
					<td>
						<p class="description">실제 본인인증 기능을 사용하기 위해서 아임포트와 다날의 SMS본인인증 가입이 필요합니다.</p>
						<p class="description">먼저 <a href="https://www.iamport.kr/pgForm/danal_tpay" onclick="window.open(this.href);return false;">다날 SMS본인인증 가입</a> 신청해주세요.</p>
						<p class="description">아임포트에 로그인 후 <a href="https://admin.iamport.kr/settings" onclick="window.open(this.href);return false;">시스템설정</a>에 있는 정보를 입력해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_id">가맹점 식별코드</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_id" name="cosmosfarm_members_iamport_id" class="regular-text" value="<?php echo $option->iamport_id?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_api_key">REST API 키</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_api_key" name="cosmosfarm_members_iamport_api_key" class="regular-text" value="<?php echo $option->iamport_api_key?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_iamport_api_secret">REST API secret</label></th>
					<td>
						<input type="text" id="cosmosfarm_members_iamport_api_secret" name="cosmosfarm_members_iamport_api_secret" class="regular-text" value="<?php echo $option->iamport_api_secret?>">
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="변경 사항 저장">
		</p>
		
		<hr>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_use_certification">본인인증 사용</label></th>
					<td>
						<select id="cosmosfarm_members_use_certification" name="cosmosfarm_members_use_certification">
							<option value="">사용중지</option>
							<option value="sms"<?php if($option->use_certification == 'sms'):?> selected<?php endif?>>휴대폰 본인인증 사용</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_certification_min_age">나이제한</label></th>
					<td>
						<select id="cosmosfarm_members_certification_min_age" name="cosmosfarm_members_certification_min_age">
							<option value="">모두허용</option>
							<?php for($min_age=1; $min_age<120; $min_age++):?>
							<option value="<?php echo $min_age?>"<?php if($option->certification_min_age == $min_age):?> selected<?php endif?>>만<?php echo $min_age?>세 이상 인증</option>
							<?php endfor?>
						</select>
						<p class="description">선택된 나이 이상만 인증을 허용합니다. 미성년자의 본인인증을 제한할 수 있습니다.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_certification_name_field">실명 저장 필드</label></th>
					<td>
						<select id="cosmosfarm_members_certification_name_field" name="cosmosfarm_members_certification_name_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->certification_name_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">본인인증 후 확인된 실명을 선택된 필드에 저장합니다. 원하는 필드가 없다면 새로 추가해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_certification_gender_field">성별 저장 필드</label></th>
					<td>
						<select id="cosmosfarm_members_certification_gender_field" name="cosmosfarm_members_certification_gender_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->certification_gender_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">본인인증 후 확인된 성별을 선택된 필드에 저장합니다. 원하는 필드가 없다면 새로 추가해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_certification_birth_field">생년월일 저장 필드</label></th>
					<td>
						<select id="cosmosfarm_members_certification_birth_field" name="cosmosfarm_members_certification_birth_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->certification_birth_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">본인인증 후 확인된 생년월일을 선택된 필드에 저장합니다. 원하는 필드가 없다면 새로 추가해주세요.</p>
					</td>
				</tr>
				<?php if(COSMOSFARM_MEMBERS_CERTIFIED_PHONE):?>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_certification_carrier_field">통신사 저장 필드</label></th>
					<td>
						<select id="cosmosfarm_members_certification_carrier_field" name="cosmosfarm_members_certification_carrier_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->certification_carrier_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">본인인증 후 확인된 통신사를 선택된 필드에 저장합니다. 원하는 필드가 없다면 새로 추가해주세요.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cosmosfarm_members_certification_phone_field">휴대폰번호 저장 필드</label></th>
					<td>
						<select id="cosmosfarm_members_certification_phone_field" name="cosmosfarm_members_certification_phone_field">
							<option value="">사용안함</option>
							<?php foreach(wpmem_fields() as $key=>$field):?>
								<?php if($field['type'] != 'text') continue?>
								<?php if(!$field['register']) continue?>
								<option value="<?php echo $key?>"<?php if($option->certification_phone_field == $key):?> selected<?php endif?>><?php echo $field['label']?></option>
							<?php endforeach?>
						</select>
						<p class="description">본인인증 후 확인된 휴대폰번호를 선택된 필드에 저장합니다. 원하는 필드가 없다면 새로 추가해주세요.</p>
					</td>
				</tr>
				<?php endif?>
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
		
		<hr>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="">필드 추가 예제</label></th>
					<td>
						<a href="<?php echo COSMOSFARM_MEMBERS_URL . '/images/certification.png'?>" onclick="window.open(this.href);return false;" title="크게보기"><img src="<?php echo COSMOSFARM_MEMBERS_URL . '/images/certification.png'?>" alt="" style="max-width:100%;vertical-align:middle"></a>
						<p class="description">필요한 경우 <a href="<?php echo admin_url('options-general.php?page=wpmem-settings&tab=fields')?>">WP-Members</a> 플러그인에서 새로운 텍스트 필드를 추가하세요.</p>
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