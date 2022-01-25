<?php if(!defined('ABSPATH')) exit;?>
<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="robots" content="noindex,follow">
	<title><?php wp_title('')?></title>
	<style>
	html, body { margin: 0; padding: 0; width: 1px; min-width: 100%; *width: 100%; overflow: hidden; }
	</style>
	<script src="https://web.nicepay.co.kr/v3/webstd/js/nicepay-3.0.js"></script>
	<script>
	//결제창 최초 요청시 실행됩니다.
	function nicepayStart(){
		goPay(document.payForm);
	}
	
	//[PC 결제창 전용]결제 최종 요청시 실행됩니다. <<'nicepaySubmit()' 이름 수정 불가능>>
	function nicepaySubmit(){
		document.payForm.submit();
	}
	
	//[PC 결제창 전용]결제창 종료 함수 <<'nicepayClose()' 이름 수정 불가능>>
	function nicepayClose(){
		parent.parent.cosmosfarm_members_builtin_pg.close_dialog({
			success : false,
			message : '',
			error_msg : '결제를 취소했습니다.'
		});
	}
	</script>
</head>
<body onload="nicepayStart()">
	<form name="payForm" method="post" action="<?php echo esc_url($pg->get_callback_url())?>" accept-charset="euc-kr">
		<?php foreach(apply_filters('cosmosfarm_pay_init_values_nicepay_pc', $pg->get_init_values(), $pg) as $name=>$value):?>
		<input type="hidden" name="<?php echo esc_attr($name)?>" value="<?php echo esc_attr($value)?>">
		<?php endforeach?>
	</form>
</body>
</html>