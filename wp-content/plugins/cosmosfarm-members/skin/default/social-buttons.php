<?php if(!defined('ABSPATH')) exit;?>
<div class="cosmosfarm-social-login">
	<div class="cosmosfarm-social-login-title"><?php echo __('Social Login', 'cosmosfarm-members')?></div>
	<?php foreach($option->social_login_active as $channel):?>
		<a href="<?php echo home_url('?action=cosmosfarm_members_social_login&channel=' . $channel)?>&redirect_to=<?php echo urlencode(esc_url_raw($redirect_to))?>" title="<?php echo $channel?>"><img src="<?php echo $skin_path?>/images/icon-<?php echo $channel?>.png" alt="<?php echo $channel?>"></a>
	<?php endforeach?>
</div>