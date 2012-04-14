<?php if( empty( $comment_data ) ) { ?>
<div class="alert alert-block alert-info" id="comment-empty">
	<h3 class="alert-heading">沒有任何評論</h3>
</div>
<?php } else { ?>
<ul class="comment-list">
	<?php foreach( $comment_data as $comment ) { ?>
	<li class="row-fluid single" id="comment-<?php echo $comment['id']; ?>">
		<div class="span2 comment-avatar">
			<?php if( $comment['url'] || $comment['userurl'] ) { ?>
			<a href="<?php echo add_http( $comment['userurl']?$comment['userurl']:$comment['url'] ); ?>" class="thumbnail" rel="nofollow"><img src="<?php echo gravatar_url( $comment['useremail']?$comment['useremail']:$comment['email'] ); ?>" alt="<?php echo $comment['username']?$comment['username']:$comment['author']; ?>"></a>
			<?php } else { ?>
			<span class="thumbnail"><img src="<?php echo gravatar_url($comment['useremail']?$comment['useremail']:$comment['email']); ?>" alt="<?php echo $comment['username']?$comment['username']:$comment['author']; ?>"></sapn>
			<?php } ?>
		</div>
		<div class="span10 comment-entry">
			<h4><?php echo $comment['username']?$comment['username']:$comment['author']; ?> <small><?php echo get_time_diff( $comment['commenttime'] ); ?></small></h4>
			<p><?php echo $comment['content']; ?></p>
			<a href="javascript:void(0);" rel="#comment-<?php echo $comment['id']; ?>" class="btn btn-primary btn-small pull-right reply-comment-btn">回覆TA</a>
		</div>
		<div class="c"></div>
		<ul class="reply-list">
	<?php if ( isset( $reply_data[$comment['id']] ) ) {
		$reply = $reply_data[$comment['id']];
		foreach( $reply as $single ) {
	?>
		<li class="row-fluid single reply" id="comment-<?php echo $single['id']; ?>">
			<div class="span2 comment-avatar">
				<?php if( $single['url'] || $single['userurl'] ) { ?>
				<a href="<?php echo add_http( $single['userurl']?$single['userurl']:$single['url'] ); ?>" class="thumbnail" rel="nofollow"><img src="<?php echo gravatar_url($single['useremail']?$single['useremail']:$single['email']); ?>" alt="<?php echo $single['username']?$single['username']:$single['author']; ?>"></a>
				<?php } else { ?>
				<span class="thumbnail"><img src="<?php echo gravatar_url($single['useremail']?$single['useremail']:$single['email']); ?>" alt="<?php echo $single['username']?$single['username']:$single['author']; ?>"></span>
				<?php } ?>
			</div>
			<div class="span10 comment-entry">
				<h4><?php echo $single['username']?$single['username']:$single['author']; ?> <small><?php echo get_time_diff( $single['commenttime'] ); ?></small></h4>
				<p><?php echo $single['content']; ?></p>
			</div>
		</li>
	<?php
		}
	}
	?>
		</ul>
	</li>
	<?php }	?>
</ul>
<?php } ?>