<?php if(!defined('ABSPATH')) exit;?>
<div class="wrap">
	<div style="float:left;margin:7px 8px 0 0;width:36px;height:34px;background:url(<?php echo COSMOSFARM_MEMBERS_URL . '/images/icon-big.png'?>) left top no-repeat;"></div>
	<h1 class="wp-heading-inline">코스모스팜 회원관리</h1>
	<a href="<?php echo admin_url("admin.php?page=cosmosfarm_members_message&action=new")?>" class="page-title-action">쪽지 등록하기</a>
	<a href="https://www.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">홈페이지</a>
	<a href="https://www.cosmosfarm.com/threads" class="page-title-action" onclick="window.open(this.href);return false;">커뮤니티</a>
	<a href="https://www.cosmosfarm.com/support" class="page-title-action" onclick="window.open(this.href);return false;">고객지원</a>
	<a href="https://blog.cosmosfarm.com/" class="page-title-action" onclick="window.open(this.href);return false;">블로그</a>
	
	<hr class="wp-header-end">
	
	<form method="get">
		<input type="hidden" name="page" value="cosmosfarm_members_message">
		<?php $table->search_box(__('Search', 'cosmosfarm-members'), 'message_search')?>
	</form>
	<form method="post">
		<?php $table->display()?>
	</form>
</div>