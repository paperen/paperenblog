<?php if( isset( $success ) && $success ) { ?>
<div class="alert alert-block alert-success comment-alert" id="comment-alert">
	<h4 class="alert-heading">評論成功</h4>
	<p>您的評論已經發送給<?php echo $post_data['author']; ?></p>
</div>
<li class="row-fluid single<?php if( $comment_data['pid'] ){ ?> reply<?php } ?> hide" id="comment-<?php echo $comment_data['id']; ?>">
	<div class="span2 comment-avatar">
		<?php if( $comment_data['url'] ) { ?>
		<a href="<?php echo add_http( $comment_data['url'] ); ?>" class="thumbnail"><img src="<?php echo gravatar_url($comment_data['email']); ?>" alt="<?php echo $comment_data['author']; ?>"></a>
		<?php } else { ?>
		<span class="thumbnail"><img src="<?php echo gravatar_url($comment_data['email']); ?>" alt="<?php echo $comment_data['author']; ?>"></span>
		<?php } ?>
	</div>
	<div class="span10 comment-entry">
		<h4><?php echo $comment_data['author']; ?> <small>剛剛</small></h4>
		<p><?php echo $comment_data['content']; ?></p>
	</div>
</li>
<script>
<?php if( $comment_data['pid'] ) { ?>
	$('#comment-<?php echo $comment_data['id']; ?>').removeClass('hide').appendTo( $('#comment-<?php echo $comment_data['pid']; ?> .reply-list') ).effect('highlight', {}, 2000);
<?php } else { ?>
	$('#comment-<?php echo $comment_data['id']; ?>').removeClass('hide').appendTo( '.comment-list' ).effect('highlight', {}, 2000);
<?php } ?>
</script>
<?php } else { ?>
<div class="alert alert-block alert-danger comment-alert" id="comment-alert">
	<h4 class="alert-heading">評論失敗</h4>
	<ul><?php echo $message; ?></ul>
</div>
<?php } ?>