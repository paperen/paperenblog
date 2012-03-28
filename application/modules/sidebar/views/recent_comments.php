<?php if ( isset( $comments_data ) && $comments_data ){ ?>
<!-- recent-comment -->
<div class="recent-comment">
	<h3>最近评论</h3>
	<ul>
		<?php foreach( $comments_data as $comment ) { ?>
		<li>
			<a href="<?php echo base_url("post/{$comment['urltitle']}"); ?>">
				<strong><?php echo ( $comment['author'] ) ? $comment['author'] : $comment['username']; ?></strong><?php echo strip_tags( $comment['content'] ); ?>
			</a>
			<?php echo get_time_diff($comment['commenttime']); ?>
		</li>
		<?php } ?>
	</ul>
</div>
<!-- recent-comment -->
<?php } ?>