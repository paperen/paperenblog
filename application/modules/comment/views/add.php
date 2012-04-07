<?php if( isset( $success ) && $success ) { ?>
<div class="alert alert-block alert-success" id="comment-alert">
	<h4 class="alert-heading">評論成功</h4>
	<p>您的評論已經發送給<?php echo $post_data['author']; ?></p>
</div>
<?php } else { ?>
<div class="alert alert-block alert-danger" id="comment-alert">
	<h4 class="alert-heading">評論失敗</h4>
	<ul><?php echo $message; ?></ul>
</div>
<?php } ?>