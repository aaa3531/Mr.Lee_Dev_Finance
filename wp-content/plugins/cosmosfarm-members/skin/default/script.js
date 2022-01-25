/**
 * @author https://www.cosmosfarm.com/
 */

function cosmosfarm_find_japan_address(){
	var zip = jQuery('.cosmosfarm-members-form input[name="zip"]').val();
	
	if(!zip){
		alert(cosmosfarm_members_localize_strings.please_enter_the_postcode);
		jQuery('.cosmosfarm-members-form input[name="zip"]').focus();
		return false;
	}
	
	jQuery.get('https://api.zipaddress.net/', {zipcode:zip, lang:'ja', callback:'cosmosfarm_japan_address_callback'});
}

function cosmosfarm_japan_address_callback(data){
	if(data.code == '400'){
		alert(data.message);
		jQuery('.cosmosfarm-members-form input[name="zip"]').focus();
	}
	else{
		jQuery('.cosmosfarm-members-form input[name="thestate"]').val(data.pref);
		jQuery('.cosmosfarm-members-form input[name="city"]').val(data.address);
	}
}

function cosmosfarm_members_avatar_form_submit(input){
	jQuery('#cosmosfarm_members_avatar_form').submit();
}

function cosmosfarm_members_check_password_strength(){
	var password1;
	var password2;

	if(jQuery('.cosmosfarm-members-form input[name="password"]').length){
		password1 = jQuery('.cosmosfarm-members-form input[name="password"]').val();
		password2 = jQuery('.cosmosfarm-members-form input[name="confirm_password"]').val();
	}
	else if(jQuery('.cosmosfarm-members-form input[name="pass1"]').length){
		password1 = jQuery('.cosmosfarm-members-form input[name="pass1"]').val();
		password2 = jQuery('.cosmosfarm-members-form input[name="pass2"]').val();
	}
	
	var strength = cosmosfarm_members_get_password_strength(password1, password2);
	switch(strength){
		case 'mismatch':
			jQuery('.password-strength-meter-display').text(cosmosfarm_members_localize_strings.your_password_is_different);
			jQuery('.password-strength-meter-display').removeClass('good');
			jQuery('.password-strength-meter-display').addClass('bad');
			break;
		case 'short':
			jQuery('.password-strength-meter-display').text(cosmosfarm_members_localize_strings.password_must_consist_of_8_digits);
			jQuery('.password-strength-meter-display').removeClass('good');
			jQuery('.password-strength-meter-display').addClass('bad');
			break;
		case 'space':
			jQuery('.password-strength-meter-display').text(cosmosfarm_members_localize_strings.please_enter_your_password_without_spaces);
			jQuery('.password-strength-meter-display').removeClass('good');
			jQuery('.password-strength-meter-display').addClass('bad');
			break;
		case 'bad':
			jQuery('.password-strength-meter-display').text(cosmosfarm_members_localize_strings.password_must_consist_of_8_digits);
			jQuery('.password-strength-meter-display').removeClass('good');
			jQuery('.password-strength-meter-display').addClass('bad');
			break;
		default:
			jQuery('.password-strength-meter-display').text(cosmosfarm_members_localize_strings.it_is_a_safe_password);
			jQuery('.password-strength-meter-display').addClass('good');
			jQuery('.password-strength-meter-display').removeClass('bad');
	}
	return strength;
}

function cosmosfarm_members_get_password_strength(password1, password2){
	if(password1){
		var number = password1.search(/[0-9]/g);
		var english = password1.search(/[a-z]/ig);
		var special = password1.search(/[`~!@\#$%<>^&*\()\{\}\[\]\-=+_\,.?;'"|\\]/ig);
	}
	
	if(!password1 || !password2){
		return 'bad';
	}
	else if((password1 && password2) && password1 != password2){
		return 'mismatch';
	}
	else if(password1.length < 8){
		return 'short';
	}
	else if(password1.search(/\s/g) != -1){
		return 'space';
	}
	else if(number < 0 || english < 0 || special < 0){
		return 'bad';
	}
	return 'good';
}

function cosmosfarm_members_certification(){
	IMP.certification({
		merchant_uid:'merchant_' + new Date().getTime(),
		min_age:cosmosfarm_members_settings.certification_min_age
	},
	function(rsp){
		if(rsp.success){
			jQuery.post('?action=cosmosfarm_members_certification_confirm', {imp_uid:rsp.imp_uid, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
				if(res.error_message){
					setTimeout(function(){
						alert(res.error_message);
					});
				}
				else{
					if(cosmosfarm_members_settings.certification_name_field){
						jQuery('input[name="'+cosmosfarm_members_settings.certification_name_field+'"]').val(res.name);
					}
					if(cosmosfarm_members_settings.certification_gender_field){
						jQuery('input[name="'+cosmosfarm_members_settings.certification_gender_field+'"]').val(res.gender=='male'?cosmosfarm_members_localize_strings.male:cosmosfarm_members_localize_strings.female);
					}
					if(cosmosfarm_members_settings.certification_birth_field){
						jQuery('input[name="'+cosmosfarm_members_settings.certification_birth_field+'"]').val(res.birth);
					}
					if(cosmosfarm_members_settings.certified_phone && cosmosfarm_members_settings.certification_carrier_field){
						jQuery('input[name="'+cosmosfarm_members_settings.certification_carrier_field+'"]').val(res.carrier);
					}
					if(cosmosfarm_members_settings.certified_phone && cosmosfarm_members_settings.certification_phone_field){
						jQuery('input[name="'+cosmosfarm_members_settings.certification_phone_field+'"]').val(res.phone);
					}
					setTimeout(function(){
						alert(cosmosfarm_members_localize_strings.certificate_completed);
					});
				}
			});
		}
		else{
			setTimeout(function(){
				alert(rsp.error_msg);
			});
		}
	});
}

function cosmosfarm_members_exists_check(input_name, form){
	if(jQuery("[name='"+input_name+"']", form).length > 0){
		if(!jQuery("[name='"+input_name+"']", form).val()){
			alert(cosmosfarm_members_localize_strings.please_fill_out_this_field);
			jQuery("[name='"+input_name+"']", form).focus();
		}
		else{
			jQuery.post('?action=cosmosfarm_members_exists_check', {meta_key:input_name, meta_value:jQuery("[name='"+input_name+"']", form).val(), security:cosmosfarm_members_settings.ajax_nonce}, function(res){
				if(!res.exists){
					jQuery('input[type="hidden"].'+input_name, form).val('1');
				}
				else{
					jQuery('input[type="hidden"].'+input_name, form).val('');
				}
				
				if(res.message){
					alert(res.message);
				}
				else if(res.exists){
					alert(cosmosfarm_members_localize_strings.already_in_use);
				}
				else{
					alert(cosmosfarm_members_localize_strings.available);
				}
			});
		}
	}
}

function cosmosfarm_members_send_message_submit(form){
	var to_user_id = jQuery('[name=to_user_id]', form).val();
	var redirect_to = jQuery('[name=redirect_to]', form).val();
	var title = jQuery('[name=title]', form).val();
	var content = jQuery('[name=content]', form).val();
	
	var button_text = jQuery('[type=submit]', form).text();
	jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.please_wait);
	
	cosmosfarm_members_send_message({
		to_user_id:to_user_id,
		title:title,
		content:content
	}, function(res){
		if(res.result == 'success'){
			alert(res.message);
			
			if(redirect_to){
				window.location.href = redirect_to;
			}
			else{
				window.location.reload();
			}
		}
		else{
			alert(res.message);
		}
		
		jQuery('[type=submit]', form).text(button_text);
	});
	
	return false;
}

function cosmosfarm_members_form_submit(){
	if(cosmosfarm_members_settings.use_strong_password && jQuery('input[name=a]').val() != 'update'){
		var strength = cosmosfarm_members_check_password_strength();
		switch(strength){
			case 'mismatch':
				alert(cosmosfarm_members_localize_strings.your_password_is_different);
				break;
			case 'short':
				alert(cosmosfarm_members_localize_strings.password_must_consist_of_8_digits);
				break;
			case 'space':
				alert(cosmosfarm_members_localize_strings.please_enter_your_password_without_spaces);
				break;
			case 'bad':
				alert(cosmosfarm_members_localize_strings.password_must_consist_of_8_digits);
				break;
			default:
				break;
		}
		if(strength != 'good'){
			return false;
		}
	}
	
	if(cosmosfarm_members_settings.exists_check){
		for(var meta_key in cosmosfarm_members_settings.exists_check){
			if(jQuery('.cosmosfarm-members-form.signup-form .'+meta_key).length && !jQuery('.cosmosfarm-members-form.signup-form .'+meta_key).val()){
				var field_label = jQuery('.cosmosfarm-members-form label[for="'+meta_key+'"]');
				field_label = jQuery(field_label).contents().not(jQuery(field_label).children()).text();
				var alert_message = cosmosfarm_members_localize_strings.please_exists_check.replace('%s', field_label);
				
				alert(alert_message);
				
				return false;
			}
		}
	}
	
	return true;
}

jQuery(document).ready(function(){
	jQuery('.cosmosfarm_members_raw_content').each(function(){
		jQuery(this).appendTo('#'+jQuery(this).data('target'));
	});
	
	if(cosmosfarm_members_settings.locale == 'ko_KR' && !cosmosfarm_members_settings.postcode_service_disabled){
		jQuery('#billing_address_1').attr('readonly', true);
		jQuery('#billing_address_1').css({cursor:'pointer'});
		jQuery('#billing_address_1').click(function(){
			cosmosfarm_members_open_postcode('billing');
			return false;
		});
		jQuery('#billing_postcode').attr('readonly', true);
		jQuery('#billing_postcode').css({cursor:'pointer'});
		jQuery('#billing_postcode').click(function(){
			cosmosfarm_members_open_postcode('billing');
			return false;
		});
		jQuery('#shipping_address_1').attr('readonly', true);
		jQuery('#shipping_address_1').css({cursor:'pointer'});
		jQuery('#shipping_address_1').click(function(){
			cosmosfarm_members_open_postcode('shipping');
			return false;
		});
		jQuery('#shipping_postcode').attr('readonly', true);
		jQuery('#shipping_postcode').css({cursor:'pointer'});
		jQuery('#shipping_postcode').click(function(){
			cosmosfarm_members_open_postcode('shipping');
			return false;
		});
	}
	
	if(typeof jQuery.datepicker == 'object'){
		jQuery('input[name="birthday"]').datepicker({
				closeText : '닫기',
				prevText : '이전달',
				nextText : '다음달',
				currentText : '오늘',
				monthNames : [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
				monthNamesShort : [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
				dayNames : [ '일', '월', '화', '수', '목', '금', '토' ],
				dayNamesShort : [ '일', '월', '화', '수', '목', '금', '토' ],
				dayNamesMin : [ '일', '월', '화', '수', '목', '금', '토' ],
				weekHeader : 'Wk',
				dateFormat : 'yy-mm-dd',
				firstDay : 0,
				isRTL : false,
				duration : 200,
				showAnim : 'fadeIn',
				showMonthAfterYear : true,
				yearSuffix : '년'
		});
	}
	
	if(cosmosfarm_members_settings.use_strong_password){
		jQuery('.cosmosfarm-members-form input[name="password"]').keyup(function(){
			cosmosfarm_members_check_password_strength();
		});
		jQuery('.cosmosfarm-members-form input[name="confirm_password"]').keyup(function(){
			cosmosfarm_members_check_password_strength();
		});
		jQuery('.cosmosfarm-members-form input[name="pass1"]').keyup(function(){
			cosmosfarm_members_check_password_strength();
		});
		jQuery('.cosmosfarm-members-form input[name="pass2"]').keyup(function(){
			cosmosfarm_members_check_password_strength();
		});
	}
	
	if(cosmosfarm_members_settings.exists_check){
		for(var meta_key in cosmosfarm_members_settings.exists_check){
			jQuery('.cosmosfarm-members-form.signup-form #'+meta_key).keyup(function(){
				var exists_check_id = jQuery(this).attr('id');
				jQuery('.cosmosfarm-members-form.signup-form input[type="hidden"].'+exists_check_id).val('');
			});
		}
	}
	
	jQuery('.cosmosfarm-members-form.signup-form form').submit(cosmosfarm_members_form_submit);
	jQuery('.cosmosfarm-members-form.pwdchange-form form').submit(cosmosfarm_members_form_submit);
});