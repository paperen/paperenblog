<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $error ) && $error ) { ?>
	<div class="alert alert-block alert-error">
		<h3 class="alert-heading"><?php echo $error; ?></h3>
		<a href="javascript:window.history.go(-1);" class="btn btn-small">返回</a>
	</div>
	<?php } else { ?>
	<div class="alert alert-block alert-success"">
		<h3 class="alert-heading">刪除成功</h3>
		<a href="<?php echo base_url('trash'); ?>" class="btn btn-small">返回 回收站</a>
	</div>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>