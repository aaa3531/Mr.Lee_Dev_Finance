/**
 * @author https://www.cosmosfarm.com/
 */

var cosmosfarm_members_ajax_lock = false;

function cosmosfarm_members_open_postcode(target){
	if(cosmosfarm_members_settings.use_postcode_service_iframe){
		var element_layer_background = jQuery('#postcode-layer-background');
		var element_layer = jQuery('#postcode-layer');
		
		var element_layer_resize = function(){
			if(window.innerWidth <= 600){
				element_layer.css({
					'top':jQuery(window).scrollTop(), 'left':'0', 'margin-left':'0', 'width':'100%', 'height':jQuery(window).height(), 'border':'3px solid',
					'box-sizing':'border-box'
				});
			}
			else{
				var width = 500;
				var height = 600;
				element_layer.css({
					'top':(jQuery(window).scrollTop() + ((jQuery(window).height()-height)/2)) + 'px',
					'left':'50%', 'margin-left':-(width/2)+'px', 'width':width+'px', 'height':height+'px', 'border':'3px solid',
					'box-sizing':'border-box'
				});
			}
		}
		
		var postcode_close = function(){
			jQuery('#postcode-layer').hide();
			jQuery('#postcode-layer-background').hide();
			return false;
		}
		
		if(jQuery('#postcode-layer').length == 0){
			element_layer_background = jQuery('<div id="postcode-layer-background" style="position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: black; z-index: 1000000; opacity: 0.5;"></div>');
			element_layer = jQuery('<div id="postcode-layer" style="position:absolute; background-color:white; z-index:1000000; text-align:right"><div class="postcode-layer-wrap" style="position:relative; height:100%;"></div></div>');
			var close_button = jQuery('<div id="postcode-close-button-wrap" style="position:absolute; left:0; top:0; width:100%; background:black;"><a href="#" class="close-button" style="float:right; display:block; margin:0; padding:0 7px; width:auto; height:36px; line-height:36px; font-size:13px; font-weight:normal; cursor:pointer; color:white; border:0;">닫기</a></div>');
			var postcode_search_area = jQuery('<div id="postcode-search-area" style="position:absolute; left:0; top:36px; bottom:0; width:100%; overflow-y:auto; -webkit-overflow-scrolling:touch;"></div>');
			
			element_layer_background.click(postcode_close);
			close_button.find('.close-button').click(postcode_close);
			
			element_layer.find('.postcode-layer-wrap').append(close_button);
			element_layer.find('.postcode-layer-wrap').append(postcode_search_area);
			
			jQuery('body').append(element_layer_background);
			jQuery('body').append(element_layer);
			
			jQuery(window).resize(element_layer_resize);
			jQuery(window).scroll(element_layer_resize);
		}
		
		element_layer_resize();
		jQuery(element_layer).css('z-index', '1000000');
		
		element_layer_background.show();
		element_layer.show();
		
		new daum.Postcode({
			oncomplete: function(data){
				var road_address = data.roadAddress;
				if(data.buildingName){
					road_address = data.roadAddress + ' (' + data.buildingName + ')';
				}
				
				if(target == 'billing'){
					jQuery('#billing_postcode').val(data.zonecode);
					jQuery('#billing_address_1').val(road_address);
				}
				else if(target == 'shipping'){
					jQuery('#shipping_postcode').val(data.zonecode);
					jQuery('#shipping_address_1').val(road_address);
				}
				else if(target == 'subscription_checkout'){
					jQuery('#cosmosfarm_members_subscription_checkout_zip').val(data.zonecode);
					jQuery('#cosmosfarm_members_subscription_checkout_addr1').val(road_address);
				}
				else{
					jQuery('.cosmosfarm-members-form input[name="zip"]').val(data.zonecode);
					jQuery('.cosmosfarm-members-form input[name="addr1"]').val(road_address);
				}
				
				postcode_close();
			},
			width : '100%',
			height : '100%',
			maxSuggestItems : 5
		}).embed(document.getElementById('postcode-search-area'));
	}
	else{
		var width = 500;
		var height = 600;
		new daum.Postcode({
			width: width,
			height: height,
			oncomplete: function(data){
				var road_address = data.roadAddress;
				if(data.buildingName){
					road_address = data.roadAddress + ' (' + data.buildingName + ')';
				}
				
				if(target == 'billing'){
					jQuery('#billing_postcode').val(data.zonecode);
					jQuery('#billing_address_1').val(road_address);
				}
				else if(target == 'shipping'){
					jQuery('#shipping_postcode').val(data.zonecode);
					jQuery('#shipping_address_1').val(road_address);
				}
				else if(target == 'subscription_checkout'){
					jQuery('#cosmosfarm_members_subscription_checkout_zip').val(data.zonecode);
					jQuery('#cosmosfarm_members_subscription_checkout_addr1').val(road_address);
				}
				else{
					jQuery('.cosmosfarm-members-form input[name="zip"]').val(data.zonecode);
					jQuery('.cosmosfarm-members-form input[name="addr1"]').val(road_address);
				}
			}
		}).open({
			left: (screen.availWidth-width)*0.5,
			top: (screen.availHeight-height)*0.5
		});
	}
	return false;
}

function cosmosfarm_members_add_query_arg(key, value, url){
	if(url.indexOf('?')>-1){
		url += '&' + key + '=' + value;
	}
	else{
		url += '?' + key + '=' + value;
	}
	return url;
}

function cosmosfarm_members_notifications_more(button){
	var request_url = jQuery('input[name=notifications_request_url]').val();
	var paged = jQuery('input[name=notifications_list_page]').val();
	var keyword = jQuery('input[name=notifications_list_keyword]').val();
	var notifications_view = jQuery('input[name=notifications_list_view]').val();
	
	if(paged == -1){
		alert(cosmosfarm_members_localize_strings.no_notifications_found);
		return false;
	}
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	paged++;
	
	jQuery.post(request_url, {action:'cosmosfarm_members_skin_notifications_list', paged:paged, keyword:keyword, notifications_view:notifications_view, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(!res){
			alert(cosmosfarm_members_localize_strings.no_notifications_found);
			jQuery('input[name=notifications_list_page]').val(-1);
		}
		else{
			jQuery('.notifications-list').append(res);
			jQuery('input[name=notifications_list_page]').val(paged);
		}
		jQuery(button).data('submitted', '');
	}, 'text');
	
	return false;
}

function cosmosfarm_members_notifications_toggle(button, post_id){
	if(jQuery(button).parents('.notifications-list-item').hasClass('item-status-unread')){
		cosmosfarm_members_notifications_read(button, post_id);
	}
	else{
		cosmosfarm_members_notifications_unread(button, post_id);
	}
	
	return false;
}

function cosmosfarm_members_notifications_read(button, post_id){
	var request_url = jQuery('input[name=notifications_request_url]').val();
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	jQuery.post(request_url, {action:'cosmosfarm_members_notifications_read', post_id:post_id, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			jQuery('.notifications-list-item.item-post-id-'+post_id).removeClass('item-status-unread');
			jQuery('.notifications-list-item.item-post-id-'+post_id).addClass('item-status-read');
			cosmosfarm_members_unread_notifications_count_update(res.unread_count);
		}
		else{
			alert(res.message);
		}
		jQuery(button).data('submitted', '');
	});
	
	return false;
}

function cosmosfarm_members_notifications_unread(button, post_id){
	var request_url = jQuery('input[name=notifications_request_url]').val();
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	jQuery.post(request_url, {action:'cosmosfarm_members_notifications_unread', post_id:post_id, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			jQuery('.notifications-list-item.item-post-id-'+post_id).removeClass('item-status-read');
			jQuery('.notifications-list-item.item-post-id-'+post_id).addClass('item-status-unread');
			cosmosfarm_members_unread_notifications_count_update(res.unread_count);
		}
		else{
			alert(res.message);
		}
		jQuery(button).data('submitted', '');
	});
	
	return false;
}

function cosmosfarm_members_notifications_delete(button, post_id){
	var request_url = jQuery('input[name=notifications_request_url]').val();
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	
	if(confirm(cosmosfarm_members_localize_strings.are_you_sure_you_want_to_delete)){
		jQuery(button).data('submitted', 'submitted');
		jQuery.post(request_url, {action:'cosmosfarm_members_notifications_delete', post_id:post_id, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
			if(res.result == 'success'){
				jQuery('.notifications-list-item.item-post-id-'+post_id).remove();
				cosmosfarm_members_unread_notifications_count_update(res.unread_count);
			}
			else{
				alert(res.message);
			}
			jQuery(button).data('submitted', '');
		});
	}
	
	return false;
}

function cosmosfarm_members_notifications_subnotify_update(input){
	var request_url = jQuery('input[name=notifications_request_url]').val();
	var notifications_subnotify_email = jQuery('input[name=notifications_subnotify_email]:checked').val();
	var notifications_subnotify_sms = jQuery('input[name=notifications_subnotify_sms]:checked').val();
	
	if(jQuery(input).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(input).data('submitted', 'submitted');
	
	jQuery.post(request_url, {action:'cosmosfarm_members_notifications_subnotify_update', notifications_subnotify_email:notifications_subnotify_email, notifications_subnotify_sms:notifications_subnotify_sms, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			alert(res.message);
		}
		else{
			alert(res.message);
		}
		jQuery(input).data('submitted', '');
	});
	
	return false;
}

function cosmosfarm_members_unread_notifications_count_update(count){
	if(count < 0) count = 0;
	jQuery('.cosmosfarm-members-unread-notifications-count').text(count);
	
	if(count == 0){
		jQuery('.cosmosfarm-members-unread-notifications-count').addClass('display-hide');
	}
	else{
		jQuery('.cosmosfarm-members-unread-notifications-count').removeClass('display-hide');
	}
}

function cosmosfarm_members_messages_more(button){
	var request_url = jQuery('input[name=messages_request_url]').val();
	var paged = jQuery('input[name=messages_list_page]').val();
	var keyword = jQuery('input[name=messages_list_keyword]').val();
	var messages_view = jQuery('input[name=messages_list_view]').val();
	
	if(paged == -1){
		alert(cosmosfarm_members_localize_strings.no_messages_found);
		return false;
	}
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	paged++;
	
	jQuery.post(request_url, {action:'cosmosfarm_members_skin_messages_list', paged:paged, keyword:keyword, messages_view:messages_view, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(!res){
			alert(cosmosfarm_members_localize_strings.no_messages_found);
			jQuery('input[name=messages_list_page]').val(-1);
		}
		else{
			jQuery('.messages-list').append(res);
			jQuery('input[name=messages_list_page]').val(paged);
		}
		jQuery(button).data('submitted', '');
	}, 'text');
	
	return false;
}

function cosmosfarm_members_messages_toggle(button, post_id){
	if(jQuery(button).parents('.messages-list-item').hasClass('item-status-unread')){
		cosmosfarm_members_messages_read(button, post_id);
	}
	else{
		cosmosfarm_members_messages_unread(button, post_id);
	}
	
	return false;
}

function cosmosfarm_members_messages_read(button, post_id){
	var request_url = jQuery('input[name=messages_request_url]').val();
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	jQuery.post(request_url, {action:'cosmosfarm_members_messages_read', post_id:post_id, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			jQuery('.messages-list-item.item-post-id-'+post_id).removeClass('item-status-unread');
			jQuery('.messages-list-item.item-post-id-'+post_id).addClass('item-status-read');
			cosmosfarm_members_unread_messages_count_update(res.unread_count);
		}
		else{
			alert(res.message);
		}
		jQuery(button).data('submitted', '');
	});
	
	return false;
}

function cosmosfarm_members_messages_unread(button, post_id){
	var request_url = jQuery('input[name=messages_request_url]').val();
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	jQuery.post(request_url, {action:'cosmosfarm_members_messages_unread', post_id:post_id, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			jQuery('.messages-list-item.item-post-id-'+post_id).removeClass('item-status-read');
			jQuery('.messages-list-item.item-post-id-'+post_id).addClass('item-status-unread');
			cosmosfarm_members_unread_messages_count_update(res.unread_count);
		}
		else{
			alert(res.message);
		}
		jQuery(button).data('submitted', '');
	});
	
	return false;
}

function cosmosfarm_members_messages_delete(button, post_id){
	var request_url = jQuery('input[name=messages_request_url]').val();
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	
	if(confirm(cosmosfarm_members_localize_strings.are_you_sure_you_want_to_delete)){
		jQuery(button).data('submitted', 'submitted');
		jQuery.post(request_url, {action:'cosmosfarm_members_messages_delete', post_id:post_id, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
			if(res.result == 'success'){
				jQuery('.messages-list-item.item-post-id-'+post_id).remove();
				cosmosfarm_members_unread_messages_count_update(res.unread_count);
			}
			else{
				alert(res.message);
			}
			jQuery(button).data('submitted', '');
		});
	}
	
	return false;
}

function cosmosfarm_members_messages_subnotify_update(input){
	var request_url = jQuery('input[name=messages_request_url]').val();
	var messages_subnotify_email = jQuery('input[name=messages_subnotify_email]:checked').val();
	var messages_subnotify_sms = jQuery('input[name=messages_subnotify_sms]:checked').val();
	var messages_subnotify_alimtalk = jQuery('input[name=messages_subnotify_alimtalk]:checked').val();
	
	if(jQuery(input).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(input).data('submitted', 'submitted');
	
	jQuery.post(request_url, {action:'cosmosfarm_members_messages_subnotify_update', messages_subnotify_email:messages_subnotify_email, messages_subnotify_sms:messages_subnotify_sms, messages_subnotify_alimtalk:messages_subnotify_alimtalk, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(res.result == 'success'){
			alert(res.message);
		}
		else{
			alert(res.message);
		}
		jQuery(input).data('submitted', '');
	});
	
	return false;
}

function cosmosfarm_members_unread_messages_count_update(count){
	if(count < 0) count = 0;
	jQuery('.cosmosfarm-members-unread-messages-count').text(count);
	
	if(count == 0){
		jQuery('.cosmosfarm-members-unread-messages-count').addClass('display-hide');
	}
	else{
		jQuery('.cosmosfarm-members-unread-messages-count').removeClass('display-hide');
	}
}

function cosmosfarm_members_users_more(button){
	var request_url = jQuery('input[name=users_request_url]').val();
	var paged = jQuery('input[name=users_list_page]').val();
	var keyword = jQuery('input[name=users_list_keyword]').val();
	
	if(paged == -1){
		alert(cosmosfarm_members_localize_strings.no_users_found);
		return false;
	}
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	paged++;
	
	jQuery.post(request_url, {action:'cosmosfarm_members_skin_users_list', paged:paged, keyword:keyword, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(!res){
			alert(cosmosfarm_members_localize_strings.no_users_found);
			jQuery('input[name=users_list_page]').val(-1);
		}
		else{
			jQuery('.users-list').append(res);
			jQuery('input[name=users_list_page]').val(paged);
		}
		jQuery(button).data('submitted', '');
	}, 'text');
	
	return false;
}

function cosmosfarm_members_orders_more(button){
	var request_url = jQuery('input[name=orders_request_url]').val();
	var paged = jQuery('input[name=orders_list_page]').val();
	var keyword = jQuery('input[name=orders_list_keyword]').val();
	var orders_view = jQuery('input[name=orders_list_view]').val();
	
	if(paged == -1){
		alert(cosmosfarm_members_localize_strings.no_orders_found);
		return false;
	}
	
	if(jQuery(button).data('submitted')){
		alert(cosmosfarm_members_localize_strings.please_wait);
		return false;
	}
	jQuery(button).data('submitted', 'submitted');
	
	paged++;
	
	jQuery.post(request_url, {action:'cosmosfarm_members_skin_orders_list', paged:paged, keyword:keyword, orders_view:orders_view, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
		if(!res){
			alert(cosmosfarm_members_localize_strings.no_orders_found);
			jQuery('input[name=orders_list_page]').val(-1);
		}
		else{
			jQuery('.orders-list').append(res);
			jQuery('input[name=orders_list_page]').val(paged);
		}
		jQuery(button).data('submitted', '');
	}, 'text');
	
	return false;
}

function cosmosfarm_members_send_message(args, callback){
	if(!cosmosfarm_members_ajax_lock){
		cosmosfarm_members_ajax_lock = true;
		jQuery.post(cosmosfarm_members_settings.site_url, {action:'cosmosfarm_members_messages_send', to_user_id:args.to_user_id, title:args.title, content:args.content, security:cosmosfarm_members_settings.ajax_nonce}, function(res){
			cosmosfarm_members_ajax_lock = false;
			if(typeof callback === 'function'){
				callback(res);
			}
			else{
				alert(res.message);
			}
		});
	}
	else{
		alert(cosmosfarm_members_localize_strings.please_wait);
	}
	return false;
}

function cosmosfarm_members_orders_toggle(button, post_id){
	var parent = jQuery(button).parents('.orders-list-item');
	if(parent.hasClass('item-more-area-hide')){
		parent.removeClass('item-more-area-hide');
	}
	else{
		parent.addClass('item-more-area-hide');
	}
	return false;
}