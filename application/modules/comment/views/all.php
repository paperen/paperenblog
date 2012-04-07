<?php if( empty( $comment_data ) ) { ?>
<div class="alert alert-block alert-info">
	<h3 class="alert-heading">沒有任何評論</h3>
</div>
<?php } else { ?>
<ul class="comment-list">
	<?php foreach( $comment_data as $comment ) { ?>
	<li class="row-fluid single" id="comment-<?php echo $comment['id']; ?>">
		<div class="span2 comment-avatar">
			<?php if( $comment['url'] ) { ?>
			<a href="<?php echo add_http( $comment['url'] ); ?>" class="thumbnail"><img src="<?php echo gravatar_url($comment['email']); ?>" alt="<?php echo $comment['author']; ?>"></a>
			<?php } else { ?>
			<span class="thumbnail"><img src="<?php echo gravatar_url($comment['email']); ?>" alt="<?php echo $comment['author']; ?>"></sapn>
			<?php } ?>
		</div>
		<div class="span10 comment-entry">
			<h4><?php echo $comment['author']; ?> <small><?php echo get_time_diff( $comment['commenttime'] ); ?></small></h4>
			<p><?php echo $comment['content']; ?></p>
			<a href="javascript:void(0);" rel="#comment-<?php echo $comment['id']; ?>" class="btn btn-primary btn-small pull-right reply-comment-btn">回覆TA</a>
		</div>
	</li>
	<?php
	if ( isset( $reply_data[$comment['id']] ) )
	{
		$reply = $reply_data[$comment['id']];
		foreach( $reply as $single ) {
	?>
		<li class="row-fluid single reply" id="comment-<?php echo $single['id']; ?>">
			<div class="span2 comment-avatar">
				<?php if( $single['url'] ) { ?>
				<a href="<?php echo add_http( $single['url'] ); ?>" class="thumbnail"><img src="<?php echo gravatar_url($single['email']); ?>" alt="<?php echo $single['author']; ?>"></a>
				<?php } else { ?>
				<span class="thumbnail"><img src="<?php echo gravatar_url($single['email']); ?>" alt="<?php echo $single['author']; ?>"></span>
				<?php } ?>
			</div>
			<div class="span10 comment-entry">
				<h4><?php echo $single['author']; ?> <small><?php echo get_time_diff( $single['commenttime'] ); ?></small></h4>
				<p><?php echo $single['content']; ?></p>
			</div>
		</li>
	<?php
		}
	}
	}
	?>
</ul>
<?php } ?>