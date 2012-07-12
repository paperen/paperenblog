<div class="row-fluid comment">
	<div class="span2 comment-header">
		<i class="icon-paperen icon-comment-large"></i>
		<h2>評論</h2><span id="comment-num"><?php echo $total; ?></span>
	</div>
	<div class="span10 comment-body">
		<?php if( isset( $err ) && $err ) { ?>
			<div class="alert alert-error">
				<h3 class="alert-heading"><?php echo $err; ?></h3>
			</div>
		<?php } else { ?>
			<div class="author-tip row-fluid">
				<div class="span1 thumbnail">
					<a href="<?php echo author_url( $post_data['author'] ); ?>"><img src="<?php echo gravatar_url($post_data['authoremail']); ?>" title="<?php echo $post_data['author']; ?>" alt="<?php echo $post_data['author']; ?>"></a>
				</div>
				<div class="span10 tip-txt">歡迎發表自己的評論</div>
				<div class="c"></div>
			</div>
			<div class="c"></div>
			<?php $this->load->module( 'comment/common/all', array( $post_id ) );?>
			<?php $this->load->module( 'comment/common/form', array( $post_id ) );?>
		<?php } ?>
	</div>
</div>