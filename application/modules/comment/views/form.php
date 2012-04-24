<!-- comment-form -->
<div>
	<div class="row-fluid" id="comment-div">
		<?php echo form_open('', array(
			'class' => 'span12 comment-form',
			'id' => 'comment-form',
		)); ?>
		<?php echo form_hidden('postid', $postid, 'id="postid"'); ?>
		<?php echo form_hidden('pid', '', 'id="pid"'); ?>
			<div class="control-group span6">
				<label class="control-label" for="nickname">暱稱</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<?php echo form_input(
								array(
									'class' => 'span9',
									'id' => 'nickname',
									'name' => 'nickname',
									'placeholder' => 'Nickname',
									'value' => isset( $comment_author['author'] ) ? $comment_author['author'] : '',
								)
						); ?>
					</div>
				</div>
			</div>
			<div class="control-group span6">
				<label class="control-label" for="email">郵箱</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<?php echo form_input(
								array(
									'class' => 'span9',
									'id' => 'email',
									'name' => 'email',
									'placeholder' => 'Email',
									'value' => isset( $comment_author['email'] ) ? $comment_author['email'] : '',
								)
						); ?>
					</div>
				</div>
			</div>
			<div class="c"></div>
			<div class="control-group">
				<label class="control-label" for="content">說點什麼</label>
				<div class="controls">
					<?php echo form_textarea(
							array(
								'class' => 'span11',
								'id' => 'content',
								'name' => 'content',
								'rows' => 3,
							)
					); ?>
				</div>
			</div>
			<div class="control-group span6">
				<label class="control-label" for="blog">個人博客/站點 [選填]</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-home"></i></span>
						<?php echo form_input(
								array(
									'class' => 'span9',
									'id' => 'blog',
									'name' => 'blog',
									'placeholder' => 'Your Blog',
									'value' => isset( $comment_author['url'] ) ? $comment_author['url'] : '',
								)
						); ?>
					</div>
				</div>
			</div>
			<div class="c"></div>
			<div class="form-actions">
				<?php echo form_button(
						array(
							'id' => 'comment-submit-btn',
							'class' => 'btn btn-primary btn-submit btn-large',
						),
						'<i class="icon-white icon-plus"></i> 發 表'
				); ?>
			</div>
			<?php echo form_hidden('comment-submit-btn', md5( time() )); ?>
			<?php echo create_token(); ?>
		</form>
	</div>
</div>
<script>
var comment_url = '<?php echo base_url('comment'); ?>';
var comment_token_url = '<?php echo base_url('commenttoken'); ?>';
</script>
<?php echo js('comment.js'); ?>
<?php echo js('jquery-ui-highlight.js'); ?>
<!-- comment-form -->