<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<iframe frameborder="0" scrolling="no" name="weibo_frame" src="<?php echo $url; ?>" class="sync_weibo"></iframe>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>