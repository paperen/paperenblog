<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $already_sync ) && $already_sync ) { ?>
	<div class="alert alert-success">
		<h3>�Ѿ�����΢��</h3>
	</div>
	<?php } else { ?>
	<iframe frameborder="0" scrolling="no" name="weibo_frame" src="<?php echo $url; ?>" class="weibo_frame"></iframe>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>