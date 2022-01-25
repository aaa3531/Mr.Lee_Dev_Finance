<?php if(!defined('ABSPATH')) exit;?>
<?php if($field_type == 'buyer_name'):?>
<td>주문자명</td>
<td>
	<input type="hidden" name="product_field[type][]" value="buyer_name">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="buyer_name">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '주문자명(이름, 한글만)'?>" placeholder="필드 이름" required>
</td>
<td></td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : 'display_name'?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'buyer_email'):?>
<td>주문자 이메일</td>
<td>
	<input type="hidden" name="product_field[type][]" value="buyer_email">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="buyer_email">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '주문자 이메일'?>" placeholder="필드 이름" required>
</td>
<td></td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : 'user_email'?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'buyer_tel'):?>
<td>주문자 전화번호</td>
<td>
	<input type="hidden" name="product_field[type][]" value="buyer_tel">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="buyer_tel">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '주문자 전화번호(숫자만)'?>" placeholder="필드 이름" required>
</td>
<td></td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : 'billing_phone'?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'text'):?>
<td>텍스트</td>
<td>
	<input type="hidden" name="product_field[type][]" value="text">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'number'):?>
<td>숫자</td>
<td>
	<input type="hidden" name="product_field[type][]" value="number">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'select'):?>
<td>셀렉트</td>
<td>
	<input type="hidden" name="product_field[type][]" value="select">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td>
	<input type="text" name="product_field[data][]" value="<?php echo isset($field['data']) ? $field['data'] : ''?>" placeholder="콤마(,)로 구분" required>
</td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'radio'):?>
<td>라디오</td>
<td>
	<input type="hidden" name="product_field[type][]" value="radio">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td>
	<input type="text" name="product_field[data][]" value="<?php echo isset($field['data']) ? $field['data'] : ''?>" placeholder="콤마(,)로 구분" required>
</td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'checkbox'):?>
<td>체크박스</td>
<td>
	<input type="hidden" name="product_field[type][]" value="checkbox">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td>
	<input type="text" name="product_field[data][]" value="<?php echo isset($field['data']) ? $field['data'] : ''?>" placeholder="콤마(,)로 구분" required>
</td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'zip'):?>
<td>주소</td>
<td>
	<input type="hidden" name="product_field[type][]" value="zip">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="zip">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '우편번호'?>" placeholder="필드 이름" required>
	
	<br>
	
	<input type="hidden" name="product_field[type][]" value="addr1">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="addr1">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field2['label']) ? $field2['label'] : '주소'?>" placeholder="필드 이름" required>
	
	<br>
	
	<input type="hidden" name="product_field[type][]" value="addr2">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="addr2">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field3['label']) ? $field3['label'] : '상세주소'?>" placeholder="필드 이름" required>
</td>
<td></td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : 'billing_postcode'?>" placeholder="사용자 정보 자동 입력">
	
	<br>
	
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field2['user_meta_key']) ? $field2['user_meta_key'] : 'billing_address_1'?>" placeholder="사용자 정보 자동 입력">
	
	<br>
	
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field3['user_meta_key']) ? $field3['user_meta_key'] : 'billing_address_2'?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
	
	<br>
	
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field2['required']) && $field2['required']):?> selected<?php endif?>>필수</option>
	</select>
	
	<br>
	
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field3['required']) && $field3['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	
	<br>
	
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
	
	<br>
	
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'textarea'):?>
<td>텍스트에어리어</td>
<td>
	<input type="hidden" name="product_field[type][]" value="textarea">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'datepicker'):?>
<td>날짜선택</td>
<td>
	<input type="hidden" name="product_field[type][]" value="datepicker">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '날짜선택'?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'timepicker'):?>
<td>시간선택</td>
<td>
	<input type="hidden" name="product_field[type][]" value="timepicker">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '시간선택'?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'weekpicker'):?>
<td>요일선택</td>
<td>
	<input type="hidden" name="product_field[type][]" value="weekpicker">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : '요일선택'?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td>
	<select name="product_field[required][]">
		<option value="">생략가능</option>
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'agree'):?>
<td>동의하기</td>
<td>
	<input type="hidden" name="product_field[type][]" value="agree">
	<input type="hidden" name="product_field[meta_key][]" value="">
	<input type="hidden" name="product_field[user_meta_key][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td></td>
<td>
	<textarea name="product_field[data][]" rows="5" placeholder="동의하기 내용 입력"><?php echo isset($field['data']) ? $field['data'] : ''?></textarea>
</td>
<td></td>
<td>
	<select name="product_field[required][]">
		<option value="1"<?php if(isset($field['required']) && $field['required']):?> selected<?php endif?>>필수</option>
	</select>
</td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'hr'):?>
<td colspan="7">
	구분선
	<input type="hidden" name="product_field[type][]" value="hr">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[meta_key][]" value="">
	<input type="hidden" name="product_field[label][]" value="">
	<input type="hidden" name="product_field[user_meta_key][]" value="">
	<input type="hidden" name="product_field[required][]" value="">
	<input type="hidden" name="product_field[order_view][]" value="">
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php elseif($field_type == 'hidden'):?>
<td>숨김필드</td>
<td>
	<input type="hidden" name="product_field[type][]" value="hidden">
	<input type="hidden" name="product_field[data][]" value="">
	<input type="hidden" name="product_field[required][]" value="">
	<input type="text" name="product_field[label][]" value="<?php echo isset($field['label']) ? $field['label'] : ''?>" placeholder="필드 이름" required>
</td>
<td>
	<input type="text" name="product_field[meta_key][]" value="<?php echo isset($field['meta_key']) ? $field['meta_key'] : ''?>" placeholder="영문의 고유 키값" required>
</td>
<td></td>
<td>
	<input type="text" name="product_field[user_meta_key][]" value="<?php echo isset($field['user_meta_key']) ? $field['user_meta_key'] : ''?>" placeholder="사용자 정보 자동 입력">
</td>
<td></td>
<td>
	<select name="product_field[order_view][]">
		<option value="">주문내역에 숨기기</option>
		<option value="1"<?php if(isset($field['order_view']) && $field['order_view']):?> selected<?php endif?>>주문내역에 표시</option>
	</select>
</td>
<td>
	<button type="button" class="button" onclick="cosmosfarm_members_product_field_delete(this)">삭제</button>
</td>
<?php endif?>