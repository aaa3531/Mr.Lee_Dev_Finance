/**
 * @author https://www.cosmosfarm.com/
 */

jQuery(document).ready(function(){
	if(typeof jQuery('.cosmosfarm-members-datepicker').datepicker === 'function'){
		jQuery('.cosmosfarm-members-datepicker').datepicker({
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
			duration : 0,
			showAnim : 'show',
			showMonthAfterYear : true,
			yearSuffix : '년'
		});
	}
});

function cosmosfarm_members_subscription_pay(form){
	/*
	 * 잠시만 기다려주세요.
	 */
	if(jQuery(form).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	
	// 회원가입 폼이 있을 경우
	if(jQuery('input[name=sign_up_id]', form).length || jQuery('input[name=sign_up_pw]', form).length){
		cosmosfarm_members_subscription_sign_up(form);
		return false;
	}
	
	var product_id = jQuery('[name="product_id"]', form).val();
	
	if(product_id){
		for(var i=0; i<jQuery('input[name=agree]', form).length; i++){
			if(!jQuery('input[name=agree]', form).eq(i).prop('checked')){
				alert(cosmosfarm_members_localize_strings.please_agree);
				jQuery('input[name=agree]', form).eq(i).focus();
				return false;
			}
		}
		
		if(cosmosfarm_members_subscription_checkout_settings.builtin_pg == 'inicis'){
			cosmosfarm_members_builtin_pg.pay('inicis', form);
		}
		else if(cosmosfarm_members_subscription_checkout_settings.builtin_pg == 'nicepay'){
			cosmosfarm_members_builtin_pg.pay('nicepay', form);
		}
		else if(cosmosfarm_members_subscription_checkout_settings.subscription_pg_type == 'general'){
			cosmosfarm_members_subscription_pay_general(form, function(){
				
			});
		}
		else{
			cosmosfarm_members_subscription_pay_billing(form, function(){
				
			});
		}
		
		jQuery(form).data('submitted', 'submitted');
		jQuery('[type=submit]', form).text(cosmosfarm_members_localize_strings.please_wait);
		jQuery('[type=submit]', form).val(cosmosfarm_members_localize_strings.please_wait);
	}
	return false;
}

function cosmosfarm_members_subscription_pay_general(form, callback){
	var product_id = jQuery('[name="product_id"]', form).val();
	
	cosmosfarm_members_pre_subscription_request_pay(form, product_id, function(res){
		if(res.result == 'success'){
			var custom_data = {};
			var subscription_pg = cosmosfarm_members_subscription_checkout_settings.subscription_general_pg;
			
			if(cosmosfarm_members_subscription_checkout_settings.iamport_pg_mid){
				subscription_pg += '.'+ cosmosfarm_members_subscription_checkout_settings.iamport_pg_mid;
			}
			
			custom_data['pay_success_url'] = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
			
			jQuery.each(jQuery(form).serializeArray(), function(){
				if(this.name != 'security' && this.name != 'imp_uid'){
					custom_data[this.name] = this.value;
				}
			});
			
			// 결제 수단에 따라서 iamport_id, pg 등의 값을 함께 변경한다.
			var iamport_id = cosmosfarm_members_subscription_checkout_settings.iamport_id;
			var payment_method = 'card';
			if(typeof cosmosfarm_members_subscription_iamport_pg_list !== 'undefined'){
				if(jQuery('select[name="payment_method"]').length && jQuery('select[name="payment_method"]').val()){
					payment_method = jQuery('select[name="payment_method"]').val();
					
					if(cosmosfarm_members_subscription_iamport_pg_list[payment_method].iamport_id){
						iamport_id = cosmosfarm_members_subscription_iamport_pg_list[payment_method].iamport_id;
					}
					
					if(cosmosfarm_members_subscription_iamport_pg_list[payment_method].pg){
						subscription_pg = cosmosfarm_members_subscription_iamport_pg_list[payment_method].pg;
					}
					
					if(cosmosfarm_members_subscription_iamport_pg_list[payment_method].payment_method){
						payment_method = cosmosfarm_members_subscription_iamport_pg_list[payment_method].payment_method;
					}
				}
			}
			
			IMP.init(iamport_id);
			IMP.request_pay({
				pg             : subscription_pg,
				pay_method     : payment_method,
				merchant_uid   : cosmosfarm_members_subscription_checkout_settings.merchant_uid,
				name           : cosmosfarm_members_subscription_checkout_settings.product_title,
				amount         : cosmosfarm_members_subscription_checkout_settings.product_price,
				period : {
					interval : cosmosfarm_members_subscription_checkout_settings.product_period_interval,
					from     : cosmosfarm_members_subscription_checkout_settings.product_period_from,
					to       : cosmosfarm_members_subscription_checkout_settings.product_period_to
				},
				buyer_email    : jQuery('[name="buyer_email"]', form).val(),
				buyer_name     : jQuery('[name="buyer_name"]', form).val(),
				buyer_tel      : jQuery('[name="buyer_tel"]', form).val(),
				buyer_addr     : jQuery.trim(jQuery('[name="addr1"]', form).val() + ' ' + jQuery('[name="addr2"]', form).val()),
				buyer_postcode : jQuery('[name="zip"]', form).val(),
				custom_data    : custom_data,
				m_redirect_url : cosmosfarm_members_subscription_checkout_settings.m_redirect_url,
				app_scheme     : cosmosfarm_members_subscription_checkout_settings.app_scheme
			}, function(res){
				if(res.success && res.imp_uid){
					cosmosfarm_members_subscription_request_pay_complete({form:form, product_id:product_id, imp_uid:res.imp_uid}, function(res){
						if(res.result == 'success'){
							alert(res.message);
							window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
						}
						else{
							alert(res.message);
							
							jQuery(form).data('submitted', '');
							jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
							jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						}
					});
				}
				else{
					if(res.error_msg){
						alert(res.error_msg);
					}
					else{
						alert('결제 실패');
					}
					
					jQuery(form).data('submitted', '');
					jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
					jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
				}
			});
		}
		else{
			alert(res.message);
			
			jQuery(form).data('submitted', '');
			jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
			jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
		}
	});
}

function cosmosfarm_members_subscription_pay_billing(form, callback){
	var product_id = jQuery('[name="product_id"]', form).val();
	
	cosmosfarm_members_pre_subscription_request_pay(form, product_id, function(res){
		if(res.result == 'success'){
			if(cosmosfarm_members_subscription_checkout_settings.subscription_pg == 'nice'){
				var card_number = jQuery('[name="cosmosfarm_members_subscription_checkout_card_number"]', form).val();
				
				var expiry = '';
				expiry += jQuery('[name="cosmosfarm_members_subscription_checkout_expiry_year"]', form).val();
				expiry += jQuery('[name="cosmosfarm_members_subscription_checkout_expiry_month"]', form).val();
				
				var birth = jQuery('[name="cosmosfarm_members_subscription_checkout_birth"]', form).val();
				
				var pwd_2digit = jQuery('[name="cosmosfarm_members_subscription_checkout_pwd_2digit"]', form).last().val();
				
				jQuery.post('?action=cosmosfarm_members_subscription_register_card', {cosmosfarm_members_subscription_checkout_card_number:card_number, cosmosfarm_members_subscription_checkout_expiry:expiry, cosmosfarm_members_subscription_checkout_birth:birth, cosmosfarm_members_subscription_checkout_pwd_2digit:pwd_2digit, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
					if(res.result == 'success'){
						if(res.error_message){
							alert(res.error_message);
							
							jQuery(form).data('submitted', '');
							jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
							jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						}
						else{
							cosmosfarm_members_subscription_request_pay({form:form, product_id:product_id, imp_uid:''}, function(res){
								if(res.result == 'success'){
									alert(res.message);
									window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
								}
								else{
									alert(res.message);
									
									jQuery(form).data('submitted', '');
									jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
									jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
								}
							});
						}
					}
					else{
						alert(res.message);
						
						jQuery(form).data('submitted', '');
						jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
					}
				});
			}
			else{
				var custom_data = {};
				var subscription_pg = cosmosfarm_members_subscription_checkout_settings.subscription_pg;
				
				if(cosmosfarm_members_subscription_checkout_settings.iamport_pg_mid){
					subscription_pg += '.'+ cosmosfarm_members_subscription_checkout_settings.iamport_pg_mid;
				}
				
				custom_data['pay_success_url'] = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
				
				jQuery.each(jQuery(form).serializeArray(), function(){
					if(this.name != 'security' && this.name != 'imp_uid'){
						custom_data[this.name] = this.value;
					}
				});
				
				// 결제 수단에 따라서 iamport_id, pg 등의 값을 함께 변경한다.
				var iamport_id = cosmosfarm_members_subscription_checkout_settings.iamport_id;
				var payment_method = 'card';
				if(typeof cosmosfarm_members_subscription_iamport_pg_list !== 'undefined'){
					if(jQuery('select[name="payment_method"]').length && jQuery('select[name="payment_method"]').val()){
						payment_method = jQuery('select[name="payment_method"]').val();
						
						if(cosmosfarm_members_subscription_iamport_pg_list[payment_method].iamport_id){
							iamport_id = cosmosfarm_members_subscription_iamport_pg_list[payment_method].iamport_id;
						}
						
						if(cosmosfarm_members_subscription_iamport_pg_list[payment_method].pg){
							subscription_pg = cosmosfarm_members_subscription_iamport_pg_list[payment_method].pg;
						}
						
						if(cosmosfarm_members_subscription_iamport_pg_list[payment_method].payment_method){
							payment_method = cosmosfarm_members_subscription_iamport_pg_list[payment_method].payment_method;
						}
					}
				}
				
				IMP.init(iamport_id);
				IMP.request_pay({
					pg             : subscription_pg,
					pay_method     : payment_method,
					merchant_uid   : cosmosfarm_members_subscription_checkout_settings.merchant_uid,
					name           : cosmosfarm_members_subscription_checkout_settings.product_title,
					amount         : cosmosfarm_members_subscription_checkout_settings.product_price,
					period : {
						interval : cosmosfarm_members_subscription_checkout_settings.product_period_interval,
						from     : cosmosfarm_members_subscription_checkout_settings.product_period_from,
						to       : cosmosfarm_members_subscription_checkout_settings.product_period_to
					},
					customer_uid   : cosmosfarm_members_subscription_checkout_settings.customer_uid,
					buyer_email    : jQuery('[name="buyer_email"]', form).val(),
					buyer_name     : jQuery('[name="buyer_name"]', form).val(),
					buyer_tel      : jQuery('[name="buyer_tel"]', form).val(),
					buyer_addr     : jQuery.trim(jQuery('[name="addr1"]', form).val() + ' ' + jQuery('[name="addr2"]', form).val()),
					buyer_postcode : jQuery('[name="zip"]', form).val(),
					custom_data    : custom_data,
					m_redirect_url : cosmosfarm_members_subscription_checkout_settings.m_redirect_url,
					app_scheme     : cosmosfarm_members_subscription_checkout_settings.app_scheme
				}, function(res){
					if(res.success){
						cosmosfarm_members_subscription_request_pay({form:form, product_id:product_id, imp_uid:res.imp_uid}, function(res){
							if(res.result == 'success'){
								alert(res.message);
								window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
							}
							else{
								alert(res.message);
								
								jQuery(form).data('submitted', '');
								jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
								jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
							}
						});
					}
					else{
						if(res.error_msg){
							alert(res.error_msg);
						}
						else{
							alert('빌링키 발급 실패');
						}
						
						jQuery(form).data('submitted', '');
						jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
					}
				});
			}
		}
		else{
			alert(res.message);
			
			jQuery(form).data('submitted', '');
			jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
			jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
		}
	});
}

function cosmosfarm_members_pre_subscription_request_pay(form, product_id, callback){
	jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
	jQuery('[name="product_id"]', form).val(product_id);
	jQuery.post('?action=cosmosfarm_members_pre_subscription_request_pay', jQuery(form).serialize() + '&checkout_nonce=' + cosmosfarm_members_subscription_checkout_settings.checkout_nonce, function(res){
		callback(res);
	});
}

function cosmosfarm_members_subscription_request_pay(args, callback){
	var form = args.form;
	var product_id = args.product_id;
	var imp_uid = args.imp_uid;
	jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
	jQuery('[name="product_id"]', form).val(product_id);
	jQuery.post('?action=cosmosfarm_members_subscription_request_pay', jQuery(form).serialize() + '&builtin_pg=' + cosmosfarm_members_subscription_checkout_settings.builtin_pg + '&pg_type=' + cosmosfarm_members_subscription_checkout_settings.subscription_pg_type + '&imp_uid=' + imp_uid + '&checkout_nonce=' + cosmosfarm_members_subscription_checkout_settings.checkout_nonce, function(res){
		callback(res);
	});
}

function cosmosfarm_members_subscription_request_pay_complete(args, callback){
	var form = args.form;
	var product_id = args.product_id;
	var imp_uid = args.imp_uid;
	jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
	jQuery('[name="product_id"]', form).val(product_id);
	jQuery.post('?action=cosmosfarm_members_subscription_request_pay_complete&display=pc', jQuery(form).serialize() + '&builtin_pg=' + cosmosfarm_members_subscription_checkout_settings.builtin_pg + '&pg_type=' + cosmosfarm_members_subscription_checkout_settings.subscription_pg_type + '&imp_uid=' + imp_uid + '&checkout_nonce=' + cosmosfarm_members_subscription_checkout_settings.checkout_nonce, function(res){
		callback(res);
	});
}

function cosmosfarm_members_subscription_sign_up(form){
	if(!jQuery('input[name=sign_up_id]', form).val()){
		var label_text = jQuery('label[for=cosmosfarm_members_subscription_checkout_sign_up_id]', form).text().replace(' *', '');
		var alert_message = cosmosfarm_members_localize_strings.required.replace('%s', label_text);
		alert(alert_message);
		jQuery('input[name=sign_up_id]', form).focus();
		return false;
	}
	if(!jQuery('input[name=sign_up_pw]', form).val()){
		var label_text = jQuery('label[for=cosmosfarm_members_subscription_checkout_sign_up_pw]', form).text().replace(' *', '');
		var alert_message = cosmosfarm_members_localize_strings.required.replace('%s', label_text);
		alert(alert_message);
		jQuery('input[name=sign_up_pw]', form).focus();
		return false;
	}
	
	if(confirm(cosmosfarm_members_localize_strings.this_page_will_refresh_do_you_want_to_continue)){
		jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
		jQuery.post('?action=cosmosfarm_members_pre_subscription_request_pay', jQuery(form).serialize(), function(res){
			if(res.result == 'success'){
				alert(res.message);
				window.location.href = cosmosfarm_members_subscription_checkout_settings.sign_up_success_url;
			}
			else{
				alert(res.message);
			}
		});
	}
	return false;
}

function cosmosfarm_members_subscription_apply_coupon(form){
	/*
	if(!jQuery('input[name=cosmosfarm_members_subscription_coupon_code]', form).val()){
		var label_text = jQuery('label[for=cosmosfarm_members_subscription_coupon_code]', form).text().replace(' *', '');
		var alert_message = cosmosfarm_members_localize_strings.required.replace('%s', label_text);
		alert(alert_message);
		jQuery('input[name=cosmosfarm_members_subscription_coupon_code]', form).focus();
		return false;
	}
	*/
	
	if(confirm(cosmosfarm_members_localize_strings.this_page_will_be_refreshed_to_apply_the_coupon_do_you_want_to_continue)){
		jQuery('[name="security"]', form).val(cosmosfarm_members_settings.ajax_nonce);
		jQuery.post('?action=cosmosfarm_members_subscription_apply_coupon', jQuery(form).serialize(), function(res){
			if(res.result == 'success'){
				alert(res.message);
				window.location.reload();
			}
			else{
				alert(res.message);
			}
		});
	}
	return false;
}

function cosmosfarm_members_subscription_update(obj, order_id, subscription_active){
	/*
	 * 잠시만 기다려주세요.
	 */
	if(jQuery(obj).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	
	jQuery.post('?action=cosmosfarm_members_subscription_update', {order_id:order_id, subscription_active:subscription_active, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			window.location.reload();
		}
		else{
			alert(res.message);
			jQuery(obj).data('submitted', '');
			
			if(jQuery(obj).prop('checked')){
				jQuery(obj).prop('checked', false);
			}
			else{
				jQuery(obj).prop('checked', true);
			}
		}
	});
	
	jQuery(obj).data('submitted', 'submitted');
	return false;
}


var cosmosfarm_members_builtin_pg = (function(){
	var pg;
	var form;
	var product_id = jQuery('[name="product_id"]', form).val();
	var callback = function(){
		
	}
	
	var open_dialog = function(args, _callback){
		callback = _callback;
		
		var dialog = jQuery('#cosmosfarm-members-subscription-request-pay-open-dialog');
		if(!dialog.length){
			jQuery('body').append(jQuery('<div id="cosmosfarm-members-subscription-request-pay-open-dialog"></div>'));
			dialog = jQuery('#cosmosfarm-members-subscription-request-pay-open-dialog');
		}
		else{
			dialog.html('');
		}
		
		setTimeout(function(){
			dialog.css({'display':'block', 'position':'fixed', 'top':'0', 'bottom':'0', 'left':'0', 'right':'0', 'width':'100%', 'height':'100%', 'z-index':'99999'});
			
			var iframe = '<iframe id="cosmosfarm-members-subscription-request-pay-open-dialog-iframe" name="cosmosfarm-members-subscription-request-pay-open-dialog-iframe" width="100%" height="100%" frameborder="0" style="position:absolute;left:0;right:0;top:0;bottom:0;width:100%;height:100%;"></iframe>';
			dialog.append(iframe);
			
			var form = jQuery('<form action="?action=cosmosfarm_members_subscription_request_pay_open_dialog&pg='+cosmosfarm_members_subscription_checkout_settings.builtin_pg+'&security='+cosmosfarm_members_settings.ajax_nonce+'" target="cosmosfarm-members-subscription-request-pay-open-dialog-iframe" method="post" style="display:none"></form>');
			dialog.append(form);
			
			for(var name in args){
				jQuery('<input type="hidden">').attr('name', name).attr('value', args[name]).appendTo(form);
			}
			
			form.submit();
		});
	}
	
	var serialize = function(obj){
		var str = [];
		for(var p in obj){
			if(obj.hasOwnProperty(p)){
				str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
			}
		}
		return str.join('&');
	}
	
	var inicis_pay = function(_pg, _form){
		pg = _pg;
		form = _form;
		
		var product_id = jQuery('[name="product_id"]', form).val();
		
		cosmosfarm_members_pre_subscription_request_pay(form, product_id, function(res){
			if(res.result == 'success'){
				var custom_data = {};
				custom_data['pay_success_url'] = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
				
				jQuery.each(jQuery(form).serializeArray(), function(){
					if(this.name != 'security' && this.name != 'imp_uid'){
						custom_data[this.name] = this.value;
					}
				});
				
				var pg_type = cosmosfarm_members_subscription_checkout_settings.subscription_pg_type;
				
				open_dialog({
					pg_type         : pg_type,
					pg              : pg,
					pay_method      : '',
					merchant_uid    : cosmosfarm_members_subscription_checkout_settings.merchant_uid,
					name            : cosmosfarm_members_subscription_checkout_settings.product_title,
					amount          : cosmosfarm_members_subscription_checkout_settings.product_price,
					period_interval : cosmosfarm_members_subscription_checkout_settings.product_period_interval,
					period_from     : cosmosfarm_members_subscription_checkout_settings.product_period_from,
					period_to       : cosmosfarm_members_subscription_checkout_settings.product_period_to,
					customer_uid    : cosmosfarm_members_subscription_checkout_settings.customer_uid,
					buyer_email     : jQuery('[name="buyer_email"]', form).val(),
					buyer_name      : jQuery('[name="buyer_name"]', form).val(),
					buyer_tel       : jQuery('[name="buyer_tel"]', form).val(),
					buyer_addr      : jQuery.trim(jQuery('[name="addr1"]', form).val() + ' ' + jQuery('[name="addr2"]', form).val()),
					buyer_postcode  : jQuery('[name="zip"]', form).val(),
					custom_data     : serialize(custom_data),
					m_redirect_url  : cosmosfarm_members_subscription_checkout_settings.m_redirect_url,
					app_scheme      : cosmosfarm_members_subscription_checkout_settings.app_scheme
				}, function(res){
					if(res.success){
						if(pg_type == 'general'){
							cosmosfarm_members_subscription_request_pay_complete({form:form, product_id:product_id, imp_uid:''}, function(res){
								if(res.result == 'success'){
									alert(res.message);
									window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
								}
								else{
									alert(res.message);
									
									jQuery(form).data('submitted', '');
									jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
									jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
								}
							});
						}
						else{
							cosmosfarm_members_subscription_request_pay({form:form, product_id:product_id, imp_uid:''}, function(res){
								if(res.result == 'success'){
									alert(res.message);
									window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
								}
								else{
									alert(res.message);
									
									jQuery(form).data('submitted', '');
									jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
									jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
								}
							});
						}
					}
					else{
						if(res.error_msg){
							alert(res.error_msg);
						}
						else{
							alert('결제를 취소했습니다.');
						}
						
						jQuery(form).data('submitted', '');
						jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
					}
				});
			}
			else{
				alert(res.message);
				
				jQuery(form).data('submitted', '');
				jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
				jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
			}
		});
	}
	
	var nicepay_billing = function(_pg, _form){
		pg = _pg;
		form = _form;
		
		var product_id = jQuery('[name="product_id"]', form).val();
		
		cosmosfarm_members_pre_subscription_request_pay(form, product_id, function(res){
			if(res.result == 'success'){
				var card_number = jQuery('[name="cosmosfarm_members_subscription_checkout_card_number"]', form).val();
				
				var expiry = '';
				expiry += jQuery('[name="cosmosfarm_members_subscription_checkout_expiry_year"]', form).val();
				expiry += jQuery('[name="cosmosfarm_members_subscription_checkout_expiry_month"]', form).val();
				
				var birth = jQuery('[name="cosmosfarm_members_subscription_checkout_birth"]', form).val();
				
				var pwd_2digit = jQuery('[name="cosmosfarm_members_subscription_checkout_pwd_2digit"]', form).last().val();
				
				jQuery.post('?action=cosmosfarm_members_subscription_register_card', {cosmosfarm_members_subscription_checkout_card_number:card_number, cosmosfarm_members_subscription_checkout_expiry:expiry, cosmosfarm_members_subscription_checkout_birth:birth, cosmosfarm_members_subscription_checkout_pwd_2digit:pwd_2digit, builtin_pg:cosmosfarm_members_subscription_checkout_settings.builtin_pg, pg_type:cosmosfarm_members_subscription_checkout_settings.subscription_pg_type, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
					if(res.result == 'success'){
						if(res.error_message){
							alert(res.error_message);
							
							jQuery(form).data('submitted', '');
							jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
							jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						}
						else{
							cosmosfarm_members_subscription_request_pay({form:form, product_id:product_id, imp_uid:''}, function(res){
								if(res.result == 'success'){
									alert(res.message);
									window.location.href = cosmosfarm_members_subscription_checkout_settings.pay_success_url;
								}
								else{
									alert(res.message);
									
									jQuery(form).data('submitted', '');
									jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
									jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
								}
							});
						}
					}
					else{
						alert(res.message);
						
						jQuery(form).data('submitted', '');
						jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
						jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
					}
				});
			}
			else{
				alert(res.message);
				
				jQuery(form).data('submitted', '');
				jQuery('[type=submit]', form).text(cosmosfarm_members_subscription_checkout_settings.button_display_text);
				jQuery('[type=submit]', form).val(cosmosfarm_members_subscription_checkout_settings.button_display_text);
			}
		});
	}
	
	var nicepay_general = function(_pg, _form){
		inicis_pay(_pg, _form);
	}
	
	this.pay = function(_pg, _form){
		if(_pg == 'inicis'){
			inicis_pay(_pg, _form);
		}
		else if(_pg == 'nicepay'){
			if(cosmosfarm_members_subscription_checkout_settings.subscription_pg_type == 'general'){
				nicepay_general(_pg, _form);
			}
			else{
				nicepay_billing(_pg, _form);
			}
		}
	}
	
	this.close_dialog = function(res){
		var dialog = jQuery('#cosmosfarm-members-subscription-request-pay-open-dialog');
		if(dialog.length){
			dialog.html('');
			dialog.css({'display':'none'});
		}
		callback(res);
	}
	
	return this;
})();