<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<?php if( isset( $illegal_msg ) && $illegal_msg ) { ?>
	<div class="alert alert-block alert-error">
		<h4 class="alert-heading"><?php echo $illegal_msg; ?></h4>
		<p><a href="javascript:window.history.go(-1);">返回</a></p>
	</div>
	<?php } else { ?>
	<h3><i class="icon-edit"></i>發表新文章</h3>
	<hr>
	<div class="row-fluid">
		<?php echo form_open('', array('class' => 'post-form form-inline form-horizontal', 'id' => 'post-form')); ?>
		<?php echo form_hidden('postid', isset( $post_data['id'] ) ? $post_data['id'] : '', 'id="postid"'); ?>
		<div class="span8">
		<?php if( isset( $error ) && $error ) { ?>
		<div class="alert alert-block alert-error">
			<h4>發佈文章失敗</h4>
			<ul><?php echo $error; ?></ul>
		</div>
		<?php } ?>
		<?php if( isset( $success ) && $success ) { ?>
		<div class="alert alert-block alert-success">
			<h4><?php echo $success['title']; ?></h4>
			<p><a href="<?php echo $success['post_url']; ?>" target="_blank">查看文章</a></p>
		</div>
		<?php } ?>
		<p><?php echo form_input(array(
			'value' => isset( $post_data['title'] ) ? $post_data['title'] : '',
			'name' => 'title',
			'id' => 'title',
			'class' => 'span11 input-autosave',
			'placeholder' => '文章標題',
		)); ?></p>
		<p id="permalink"<?php if ( !isset( $post_data['urltitle'] ) ){ ?> class="hide"<?php } ?>>固定鏈接 <?php echo post_permalink(''); ?>
		<?php echo form_input(array(
			'id' => 'urltitle',
			'name' => 'urltitle',
			'value' => isset( $post_data['urltitle'] ) ? $post_data['urltitle'] : '',
			'readonly' => 'true',
			'class' => 'input-small input-autosave',
			'title' => '臨時固定鏈接，點擊可修改',
		)); ?>
		</p>
		<p><label>文章類別 <?php echo form_dropdown('categoryid', $category_data, (isset( $post_data['categoryid'] ) ? $post_data['categoryid'] : ''), 'class="input-medium input-autosave"'); ?></label></p>
		<?php echo form_textarea( array(
			'id' => 'content',
			'name' => 'content',
			'class' => 'input-autosave',
		), isset( $post_data['content'] ) ? $post_data['content'] : '' ); ?>
		</div>
		<div class="span3 post-sidebar">
			<div id="autosave-tip" class="alert alert-block hide">
			</div>
<!--			<div class="box box-radius box-headtitle">
				<h4 class="title">草稿</h4>
				<p>
				<?php echo form_button(array(
					'id' => 'save_btn',
					'class' => 'btn'
				), '保存草稿'); ?>
				</p>
			</div>-->
			<div class="box box-radius box-headtitle margin-top10">
				<h4 class="title">標籤</h4>
				<ul class="unstyled" id="tag_list"></ul>
				<?php echo form_input( array(
					'id' => 'tag_input',
					'class' => 'input-small'
				) ); ?>
				<?php echo form_button(array(
					'id' => 'tag_btn',
					'class' => 'btn btn-small'
				), '添加'); ?>
				<?php echo form_hidden('tag', isset( $post_data['tag'] ) ? $post_data['tag'] : '', 'id="tag"'); ?>
			</div>
<!--				<div class="box box-radius box-headtitle margin-top10">
				<h4 class="title">特色圖像</h4>
				<?php echo form_link_button(array(
					'id' => 'thumbimg_btn',
					'class' => 'btn btn-small'
				), '設置'); ?>
				<?php echo form_hidden('thumbimg', isset( $post_data['thumbimg'] ) ? $post_data['thumbimg'] : '', 'id="thumbimg"'); ?>
			</div>-->
			<hr>
			<p>
			<?php if( $token ) { ?> 
			<label class="checkbox"><?php echo form_checkbox( array('name' => 'syncweibo', 'id' => 'syncweibo', 'value' => 1) ); ?>同步微博</label>
			<?php } else { ?>
			您尚未连接微博，<a href="<?php echo base_url('weibo_auth'); ?>">去绑定</a>
			<?php } ?>
			</p>
			<p><label class="checkbox"><?php echo form_checkbox( array('name' => 'ispublic', 'id' => 'ispublic', 'value' => 1, 'checked' => isset( $post_data['ispublic'] ) && $post_data['ispublic'] ? 'true' : '' ) ); ?>是否公开</label></p>
			<hr>
			<div>
			<?php echo form_button(array(
				'id' => 'preview_btn',
				'class' => 'btn btn-large'
			), '預覽'); ?>
			<?php echo form_submit(array(
				'id' => 'post_btn',
				'name' => 'post_btn',
				'value' => '确定',
				'class' => 'btn btn-large btn-success'
			)); ?>
			</div>
		</div>
		<?php echo create_token(); ?>
		<?php echo form_close(); ?>
	</div>
	<?php echo js( base_url('editor/kindeditor-min.js') ); ?>
	<?php echo js( base_url('editor/lang/zh_CN.js') ); ?>
	<?php $this->load->module('static/common/kindeditor_config', isset( $kindeditor_config ) ? array( $kindeditor_config ) : array() ); ?>
	<?php echo js( 'jquery-ui-highlight.js' ); ?>
	<?php echo js( 'admin/post.js' ); ?>
	<?php echo js( 'admin/tag.js' ); ?>
	<script>
	$(function(){
		$('#title').blur(function(){
			if ( $(this).val() == '' ) return;
			$('#urltitle').val( $(this).val() );
			$('#permalink').slideDown(100);
		});
		$('#urltitle').bind({
			click: function(){
				$(this).attr('readonly', false);
			},
			blur: function(){
				$(this).attr('readonly', true);
			}
		});
		// editor
		var editor;
		KindEditor.ready(function(K) {
			editor = K.create('textarea[name="content"]', DEFAULT_OPTIONS);
			//
			editor.edit.doc.onkeyup = function() {
				editor.sync();
				autosave_on( 5000 );
			}
		});
		// auto save
		autosave_init('#post-form', '#autosave-tip', '<?php echo base_url('save_draft'); ?>');
		// tag
		$('#tag').tag();
	});
	</script>
	<?php } ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>