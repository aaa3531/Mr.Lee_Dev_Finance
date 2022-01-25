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
	function inicis_close(){
		parent.parent.cosmosfarm_members_builtin_pg.close_dialog({
			success : false,
			message : '',
			error_msg : '결제를 취소했습니다.'
		});
	};
	</script>
</head>
<body onload="inicis_close()">
</body>
</html>