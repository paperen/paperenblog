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
		<?php echo form_open(isset( $form_action ) ? $form_action : '', array('class' => 'user-form form-horizontal', 'id' => 'user-form')); ?>
		<?php echo form_hidden('id', isset( $user_data['id'] ) ? $user_data['id'] : '' ); ?>
		<?php if( isset( $err ) && $err ) { ?>
		<div class="alert alert-block alert-error">
			<h4 class="alert-heading">操作失敗</h4>
			<ul><?php echo $err; ?></ul>
		</div>
		<?php } ?>
		<div class="user-form-l span5">
			<p>
				<?php echo form_label('用戶名', 'name'); ?>
				<?php echo form_input(array(
					'value' => isset( $user_data['name'] ) ? $user_data['name'] : '',
					'name' => 'name',
					'id' => 'name',
					'class' => 'span12',
				)); ?>
			</p>
			<p>
				<?php echo form_label('郵箱', 'email'); ?>
				<?php echo form_input(array(
					'value' => isset( $user_data['email'] ) ? $user_data['email'] : '',
					'name' => 'email',
					'id' => 'email',
					'class' => 'span12',
				)); ?>
			</p>
			<p>
				<?php echo form_label('個人站點', 'url'); ?>
				<?php echo form_input(array(
					'value' => isset( $user_data['url'] ) ? $user_data['url'] : '',
					'name' => 'url',
					'id' => 'url',
					'class' => 'span12',
				)); ?>
			</p>
			<p>
				<?php echo form_label('登陸密碼', 'password'); ?>
				<?php echo form_password(array(
					'name' => 'password',
					'id' => 'password',
					'class' => 'span12',
				)); ?>
			</p>
			<p>
				<?php echo form_label('確認密碼', 'password2'); ?>
				<?php echo form_password(array(
					'name' => 'password2',
					'id' => 'password2',
					'class' => 'span12',
				)); ?>
			</p>
			<p>
				<?php echo form_label('职业', 'job'); ?>
				<?php echo form_input(array(
					'value' => isset( $user_data['job'] ) ? $user_data['job'] : '',
					'name' => 'job',
					'id' => 'job',
					'class' => 'span12',
				)); ?>
			</p>
			<p class="role">
				<?php echo form_label('身份'); ?>
				<?php foreach( $all_roles as $v => $role ) { ?>
				<label>
					<?php echo form_checkbox( array(
						'name' => 'role[]',
						'value' => $v,
						'checked' => ( isset( $user_data['role'] ) && in_array( $v, $user_data['role'] ) ) ? true : false,
					) ); ?> <?php echo $role; ?>
				</label>
				<?php } ?>
			</p>
			<div class="social-zone">
				<?php echo form_label('出沒領域'); ?>
				<div class="social-con">
					<div class="single default hide">
					<?php echo form_input(array(
						'name' => 'socialname[]',
						'class' => 'span3',
						'placeholder' => '名稱',
						'disabled' => true,
					)); ?>
					<?php echo form_input(array(
						'name' => 'socialurl[]',
						'class' => 'span7',
						'placeholder' => '地址',
						'disabled' => true,
					)); ?>
					<?php echo form_link_button( array(
					'class' => 'social_del_btn pull-right btn btn-small'
				), 'X' ); ?>
					</div>
					<?php
						if ( isset( $user_data['socialname'] ) && is_array( $user_data['socialname'] ) ) {
						foreach( $user_data['socialname'] as $k => $v ) {
					?>
						<div class="single" id="added_<?php echo $k; ?>">
						<?php echo form_input(array(
							'name' => 'socialname[]',
							'class' => 'span3',
							'placeholder' => '名稱',
							'value' => $v,
						)); ?>
						<?php echo form_input(array(
							'name' => 'socialurl[]',
							'class' => 'span7',
							'placeholder' => '地址',
							'value' => $user_data['socialurl'][$k],
						)); ?>
						<?php echo form_link_button( array(
						'class' => 'social_del_btn pull-right btn btn-small',
						'rel' => 'added_' . $k,
					), 'X' ); ?>
						</div>
					<?php
						}
						}
					?>
				</div>
				<div class="c"></div>
				<?php echo form_link_button( array(
					'id' => 'social_add_btn',
					'class' => 'btn btn-small'
				), '添加一個' ); ?>
			</div>
		</div>
		<div class="user-form-r span6">
			<label>自我介绍</label>
			<?php echo form_textarea( array(
				'name' => 'content',
				'id' => 'content',
			), isset( $user_data['content'] ) ? $user_data['content'] : '' ); ?>
			<hr>
			<?php if( !isset( $isedit ) ) { ?>
			<div class="btn-group" data-toggle="buttons-checkbox">
				<a href="#" class="btn" id="set_notice"><i class="icon-envelope"></i> 同時發送郵件邀請</a>
			</div>
			<?php } ?>
			<?php echo form_radio( array(
				'name' => 'notice',
				'id' => 'notice',
				'value' => 1,
				'checked' => isset( $user_data['notice'] ) && $user_data['notice'] ? TRUE : FALSE,
				'style' => 'display:none',
			) ); ?>
		</div>
		<div class="c"></div>
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
<?php echo js( 'bootstrap/bootstrap-button.js' ); ?>
<?php echo js( base_url('editor/kindeditor-min.js') ); ?>
<?php echo js( base_url('editor/lang/zh_CN.js') ); ?>
<?php $this->load->module('static/common/kindeditor_mini_config'); ?>
<script>
	var social_num = 1;
	$(document).ready(function(){
		$('#social_add_btn').click(function(){
			var copy_social_id = 'social-con' + social_num;
			var copy_social = $('.social-con .default').clone(true).removeClass('hide').removeClass('default').attr('id', copy_social_id);
			copy_social.find(':text').attr('disabled', false);
			copy_social.find('.social_del_btn').removeClass('hide').attr('rel', copy_social_id);
			$('.social-con').append( copy_social );
			social_num++;
		});
		$('.social_del_btn').click(function(){
			var copy_social_id = '#' + $(this).attr('rel');
			$(copy_social_id).remove();
		});
		$('#set_notice').click(function(){
			var checked = $('#notice').attr('checked');
			if ( checked ) {
				$('#notice').attr('checked', false);
			} else {
				$('#notice').attr('checked', true);
			}
		});
	});
	var editor;
	KindEditor.ready(function(K){
		editor = K.create('textarea[name="content"]', DEFAULT_OPTIONS);
	});
</script>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>
