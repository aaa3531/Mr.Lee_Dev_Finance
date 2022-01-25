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
</head>
<body onload="document.payForm.submit()">
	<form name="payForm" method="post" action="https://web.nicepay.co.kr/v3/v3Payment.jsp" accept-charset="euc-kr">
		<?php foreach(apply_filters('cosmosfarm_pay_init_values_nicepay_mobile', $pg->get_init_values(), $pg) as $name=>$value):?>
		<input type="hidden" name="<?php echo esc_attr($name)?>" value="<?php echo esc_attr($value)?>">
		<?php endforeach?>
	</form>
</body>
</html>