<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><i class="icon-cog"></i> 设置</h3>
	<hr>
	<?php if( isset( $err ) && $err ) { ?>
	<div class="alert alert-error">
		<h3>配置更新失败</h3>
		<ul><?php echo $err; ?></ul>
	</div>
	<?php } else { ?>
	<?php if( isset( $success ) && $success ) { ?>
		<div class="alert alert-success">
		<h3><i class="icon icon-ok"></i>配置更新成功</h3>
		<script>setTimeout("document.location.href='<?php echo base_url('config'); ?>';", 2000);</script>
		</div>
		<?php } else { ?>
		<?php echo form_open(); ?>

		<ul id="config-tab" class="nav nav-tabs">
			<li class="active"><a href="#basic" data-toggle="tab">基本配置</a></li>
			<li><a href="#weibo" data-toggle="tab">微博配置</a></li>
			<li><a href="#email" data-toggle="tab">邮箱配置</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="basic">
				<?php foreach($config_basic as $k => $single) { ?>
				<?php if( $single['key'] == 'about' ) continue; ?>
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
				<p>
				<label>关于站点描述</label>
				<?php echo form_textarea( array(
					'id' => 'about',
					'name' => 'about',
				), $about['value'] ); ?>
				</p>
				<?php echo js( base_url('editor/kindeditor-min.js') ); ?>
				<?php echo js( base_url('editor/lang/zh_CN.js') ); ?>
				<?php $this->load->module('static/common/kindeditor_mini_config'); ?>
				<script>
				// editor
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="about"]', DEFAULT_OPTIONS);
				});
				</script>
			</div>
			<div class="tab-pane" id="weibo">
				<?php foreach($config_weibo as $k => $single) { ?>
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
			</div>
			<div class="tab-pane" id="email">
				<?php foreach($config_email as $k => $single) { ?>
				<?php if( $single['key'] == 'email_protocol' ) { ?>
				<p>
					<label>发送邮件方法</label>
					<?php echo form_radio(
							array(
								'id' => 'email_protocol_mail',
								'name' => 'email_protocol',
								'value' => 'mail',
								'checked' => ( $single['value'] == 'mail' ) ? 'checked' : '',
							)
					); ?>
					mail&nbsp;
					<?php echo form_radio(
							array(
								'id' => 'email_protocol_smtp',
								'name' => 'email_protocol',
								'value' => 'smtp',
								'checked' => ( $single['value'] == 'smtp' ) ? 'checked' : '',
							)
					); ?>
					smtp
				</p>
				<?php continue; } ?>
				<p class="smtp hide">
				<label><?php echo $single['name']; ?></label>
				<?php echo form_input( array(
					'name' => $single['key'],
					'id' => $single['key'],
					'class' => 'span3',
					'value' => $single['value'],
				) ); ?>
				</p>
				<?php } ?>
			</div>
		</div>
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
	<?php echo js('bootstrap/bootstrap-tab.js'); ?>
	<script>
	$(function () {
		$('#mytab').tab();
		$('#email_protocol_smtp').click(function(){
			$('.smtp').show();
		});
		$('#email_protocol_mail').click(function(){
			$('.smtp').hide();
		});
		if ( $('#email_protocol_smtp').attr('checked') ) $('.smtp').show();
	})
	</script>
	<?php } ?>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>