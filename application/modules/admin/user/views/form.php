<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $success ) && $success ) { ?>
		<div class="alert alert-block alert-success">
			<h4 class="alert-heading">操作成功</h4>
			<p><a href="<?php echo base_url('user'); ?>" class="btn btn-small">返回用戶列表</a></p>
			<script>setTimeout('window.location.href="<?php echo base_url('user'); ?>";', 3000);</script>
		</div>
	<?php } else { ?>
		<?php if( isset( $isedit ) && $isedit ) { ?>
		<h3><i class="icon-edit"></i>修改<?php echo $user_data['name']; ?>信息</h3>
		<?php } else { ?>
		<h3><i class="icon-plus"></i>添加新用戶</h3>
		<?php } ?>
		<hr>
		<?php echo form_open(isset( $form_action ) ? $form_action : '', array('class' => 'user-form box box-radius', 'id' => 'user-form')); ?>
		<?php echo form_hidden('id', isset( $category_data['id'] ) ? $category_data['id'] : '' ); ?>
		<?php if( isset( $err ) && $err ) { ?>
		<div class="alert alert-block alert-error">
			<h4 class="alert-heading">操作失敗</h4>
			<ul><?php echo $err; ?></ul>
		</div>
		<?php } ?>
		<p>
			<?php echo form_label('用戶名', 'name'); ?>
			<?php echo form_input(array(
				'value' => isset( $user_data['name'] ) ? $user_data['name'] : '',
				'name' => 'name',
				'id' => 'name',
				'class' => 'span3',
			)); ?>
		</p>
		<p>
			<?php echo form_label('郵箱', 'email'); ?>
			<?php echo form_input(array(
				'value' => isset( $user_data['email'] ) ? $user_data['email'] : '',
				'name' => 'email',
				'id' => 'email',
				'class' => 'span3',
			)); ?>
		</p>
		<p>
			<?php echo form_label('個人站點', 'url'); ?>
			<?php echo form_input(array(
				'value' => isset( $user_data['url'] ) ? $user_data['url'] : '',
				'name' => 'url',
				'id' => 'url',
				'class' => 'span3',
			)); ?>
		</p>
		<p>
			<?php echo form_label('登陸密碼', 'password'); ?>
			<?php echo form_password(array(
				'name' => 'password',
				'id' => 'password',
				'class' => 'span3',
			)); ?>
		</p>
		<p>
			<?php echo form_label('確認密碼', 'password2'); ?>
			<?php echo form_password(array(
				'name' => 'password2',
				'id' => 'password2',
				'class' => 'span3',
			)); ?>
		</p>
		<p>
			<?php echo form_submit(array(
				'id' => 'submit_btn',
				'name' => 'submit_btn',
				'value' => isset( $isedit ) ? '修改' : '添加',
				'class' => 'btn btn-large btn-success'
			)); ?>
		</p>
		<?php echo create_token(); ?>
		<?php echo form_close(); ?>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>