<?php if(!defined('ABSPATH')) exit;?>
<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="robots" content="noindex,follow">
	<title><?php wp_title('')?></title>
	
	<?php if($pg->is_test()):?>
	<script type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
	<?php else:?>
	<script type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js"charset="UTF-8"></script>
	<?php endif?>
	
	<style>
	html, body { margin: 0; padding: 0; width: 1px; min-width: 100%; *width: 100%; }
	</style>
</head>
<body onload="inicis_start()">
	<div style="display:none">
		<form id="send-pay-form" method="post">
			<?php foreach(apply_filters('cosmosfarm_members_init_values_inicis_pc', $pg->get_init_values(), $pg) as $name=>$value):?>
			<input type="hidden" name="<?php echo esc_attr($name)?>" value="<?php echo esc_attr($value)?>">
			<?php endforeach?>
		</form>
		<script>
		function inicis_start(){
			INIStdPay.pay('send-pay-form');
		}
		</script>
	</div>
</body>
</html>