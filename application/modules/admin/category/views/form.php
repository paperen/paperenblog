<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $success ) && $success ) { ?>
		<div class="alert alert-block alert-success">
			<h4 class="alert-heading">添加 <?php echo $category_data['category']; ?> 類別成功</h4>
			<p><a href="<?php echo base_url('my_category'); ?>" class="btn btn-small">返回文章類別</a></p>
			<script>setTimeout('window.location.href="<?php echo base_url('my_category'); ?>";', 3000);</script>
		</div>
	<?php } else { ?>
		<h3><i class="icon-plus"></i>添加新類別</h3>
		<hr>
		<?php echo form_open('', array('class' => 'category-form box box-radius', 'id' => 'category-form')); ?>
		<?php if( isset( $err ) && $err ) { ?>
		<div class="alert alert-block alert-error">
			<h4 class="alert-heading">操作失敗</h4>
			<ul><?php echo $err; ?></ul>
		</div>
		<?php } ?>
		<p>
			<?php echo form_label('類別名稱', 'category'); ?>
			<?php echo form_input(array(
				'value' => isset( $category_data['category'] ) ? $category_data['category'] : '',
				'name' => 'category',
				'id' => 'category',
				'class' => 'span2',
			)); ?>
		</p>
		<p>
			<?php echo form_label('父級類別', 'pid'); ?>
			<select name="pid" id="pid" class="span2">
				<option value="0">無上級</option>
				<?php foreach( $category_all as $single ) { ?>
				<option value="<?php echo $single['id']; ?>"<?php if( isset( $category_data['pid'] ) && $category_data['pid'] == $single['id'] ){ ?> selected="true"<?php } ?>><?php echo $single['category']; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<?php echo form_submit(array(
				'id' => 'submit_btn',
				'name' => 'submit_btn',
				'value' => '添加',
				'class' => 'btn btn-large btn-success'
			)); ?>
		</p>
		<?php echo create_token(); ?>
		<?php echo form_close(); ?>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>