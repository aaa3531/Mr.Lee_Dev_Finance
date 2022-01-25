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
	html, body { margin: 0; padding: 0; width: 1px; min-width: 100%; *width: 100%; }
	</style>
	<script>
	function inicis_callback(){
		parent.parent.cosmosfarm_members_builtin_pg.close_dialog({
			success : <?php echo $success?>,
			message : '<?php echo esc_js($message)?>',
			error_msg : '<?php echo esc_js($error_msg)?>'
		});
	};
	</script>
</head>
<body onload="inicis_callback()">
</body>
</html>