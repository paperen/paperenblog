<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><i class="icon"></i>设置</h3>
	<hr>
	<?php if( isset( $err ) && $err ) { ?>
	<div class="alert alert-error">
		<h3>配置更新失败</h3>
		<ul><?php echo $err; ?></ul>
	</div>
	<?php } else { ?>
	<?php if( isset( $success ) && $success ) { ?>
	<div class="alert alert-success">
		<h3>配置更新成功</h3>
		<script>setTimeout("document.location.href='<?php echo base_url('setting'); ?>';", 2000);</script>
	</div>
	<?php } else { ?>
	<?php echo form_open(); ?>
		<?php foreach($config as $k => $single) { ?>
		<p>
		<label><?php echo $single['name']; ?></label>
		<?php echo form_input( array(
			'name' => $single['key'],
			'id' => $single['key'],
			'class' => 'span3',
			'value' => $single['value'],
		) ); ?>
		</p>
		<?php } ?>
		<div class="form-actions">
			<?php echo form_submit( array(
				'id' => 'submit_btn',
				'name' => 'submit_btn',
				'value' => '更新',
				'class' => 'pull-right btn btn-large btn-success'
			) ); ?>
			<div class="c"></div>
		</div>
	<?php echo create_token(); ?>
	<?php echo form_close(); ?>
	<?php } ?>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>