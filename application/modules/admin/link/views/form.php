<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $success ) && $success ) { ?>
		<div class="alert alert-block alert-success">
			<h4 class="alert-heading">操作成功</h4>
			<p><a href="<?php echo base_url('link'); ?>" class="btn btn-small">返回友链列表</a></p>
			<script>setTimeout('window.location.href="<?php echo base_url('link'); ?>";', 3000);</script>
		</div>
	<?php } else { ?>
		<?php if( isset( $isedit ) && $isedit ) { ?>
		<h3><i class="icon-edit"></i>修改<?php echo $link_data['name']; ?>友链信息</h3>
		<?php } else { ?>
		<h3><i class="icon-plus"></i>添加新友链</h3>
		<?php } ?>
		<hr>
		<?php echo form_open(isset( $form_action ) ? $form_action : '', array('class' => 'span6 link-form form-horizontal', 'id' => 'user-form')); ?>
		<?php echo form_hidden('id', isset( $link_data['id'] ) ? $link_data['id'] : '' ); ?>
		<?php if( isset( $err ) && $err ) { ?>
		<div class="alert alert-block alert-error">
			<h4 class="alert-heading">操作失敗</h4>
			<ul><?php echo $err; ?></ul>
		</div>
		<?php } ?>
		<div>
			<p>
				<?php echo form_label('名称', 'name'); ?>
				<?php echo form_input(array(
					'value' => isset( $link_data['name'] ) ? $link_data['name'] : '',
					'name' => 'name',
					'id' => 'name',
					'class' => 'span4',
				)); ?>
			</p>
			<p>
				<?php echo form_label('郵箱', 'email'); ?>
				<?php echo form_input(array(
					'value' => isset( $link_data['email'] ) ? $link_data['email'] : '',
					'name' => 'email',
					'id' => 'email',
					'class' => 'span4',
				)); ?>
			</p>
			<p>
				<?php echo form_label('URL', 'url'); ?>
				<?php echo form_input(array(
					'value' => isset( $link_data['url'] ) ? $link_data['url'] : '',
					'name' => 'url',
					'id' => 'url',
					'class' => 'span4',
				)); ?>
			</p>
			<p class="role">
				<?php echo form_label('排序(越高越靠前)'); ?>
				<?php echo form_input(array(
					'value' => isset( $link_data['order'] ) ? $link_data['order'] : '',
					'name' => 'order',
					'id' => 'order',
					'class' => 'span2',
				)); ?>
			</p>
			<p class="role">
				<?php echo form_label('附加信息'); ?>
				<?php echo form_input(array(
					'value' => isset( $link_data['meta'] ) ? $link_data['meta'] : '',
					'name' => 'meta',
					'id' => 'meta',
					'class' => 'span8',
				)); ?>
			</p>
		</div>
		<div class="form-actions">
			<?php echo form_submit( array(
				'id' => 'submit_btn',
				'name' => 'submit_btn',
				'value' => isset( $isedit ) ? '修改' : '添加',
				'class' => 'pull-right btn btn-large btn-success'
			) ); ?>
			<div class="c"></div>
		</div>
		<?php echo create_token(); ?>
		<?php echo form_close(); ?>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>
