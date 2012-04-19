<div class="row-fluid comment">
	<div class="span2 comment-header">
		<i class="icon-paperen icon-comment-large"></i>
		<h2>評論</h2><span id="comment-num"><?php echo $total; ?></span>
	</div>
	<div class="span10 comment-body">
		<?php $this->load->module( 'comment/common/all', array( $post_id ) );?>
		<?php $this->load->module( 'comment/common/form', array( $post_id ) );?>
	</div>
</div>