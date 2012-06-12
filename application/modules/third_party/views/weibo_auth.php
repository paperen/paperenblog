<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $already_sync ) && $already_sync ) { ?>
	<div class="alert alert-success">
		<h3>已经连接微博</h3>
	</div>
	<?php echo form_open_multipart( base_url('weibo_post') ); ?>
		<p>
			<?php echo form_label('微博内容'); ?>
			<?php echo form_textarea( array('name' => 'post', 'id' => 'post', 'class' => 'span5') ); ?>
		</p>
		<p>
			<?php echo form_label('附带图片'); ?>
			<?php echo form_upload( array('name' => 'image', 'id' => 'image') ); ?>
		</p>
		<p>
			<?php echo form_label('网络图片'); ?>
			<?php echo form_input( array('name' => 'image_url', 'id' => 'image_url', 'class' => 'span5') ); ?>
		</p>
		<p><?php echo form_submit( array('class' => 'btn', 'value' => '发微博', 'name' => 'submit_btn') ); ?></p>
		<?php echo create_token(); ?>
	<?php echo form_close(); ?>
	<?php } else { ?>
	<iframe frameborder="0" scrolling="no" name="weibo_frame" src="<?php echo $url; ?>" class="weibo_frame"></iframe>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>