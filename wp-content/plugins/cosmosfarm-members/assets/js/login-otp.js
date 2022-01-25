/**
 * @author https://www.cosmosfarm.com/
 */

function cosmosfarm_members_login_otp_send(form){
	var log = jQuery('input[name=log]').val();
	var pwd = jQuery('input[name=pwd]').val();
	
	jQuery.post(cosmosfarm_members_login_otp_send_ajax_url, {action:'cosmosfarm_members_login_otp_send', log:log}, function(res){
		if(res.message){
			alert(res.message);
		}
	});
}