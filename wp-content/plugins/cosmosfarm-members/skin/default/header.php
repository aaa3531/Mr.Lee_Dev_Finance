<?php if(!defined('ABSPATH')) exit;?>
<div class="cosmosfarm-members-header">
	<ul class="header-menu">
		<?php foreach($menu_items as $item):?>
		<li class="menu-item-<?php echo $item['id']?><?php if($current_page == $item['id']):?> header-menu-selected<?php endif?>"><a href="<?php echo $item['url']?>"><?php echo $item['title']?></a></li>
		<?php endforeach?>
	</ul>
</div>