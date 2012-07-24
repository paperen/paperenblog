<hr>
<?php if( isset( $posts_data ) && $posts_data ) { ?>
<div class="alert alert-block alert-info">
	<h3 class="alert-heading">該作者最近發表的文章</h3>
</div>
<div class="row-fluid">
<?php
if( isset( $display ) && $display == 'row' ) {
	foreach( $posts_data as $post ) {
?>
	<div class="post post-fragment post-row">
		<div class="row-fluid">
			<?php $this->load->module('post/common/meta', array( $post, 'span2' )); ?>
			<div class="post-entry">
				<h2><a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
				<div class="post-content"><?php echo get_post_fragment( $post['content'] ); ?></div>
			</div>
			<a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="btn btn-primary btn-more pull-right" rel="bookmark">阅读更多 <i class="icon-white icon-share-alt"></i></a>
		</div>
	</div>
<?php
	}
} else {
	foreach( $posts_data as $posts_col ) {
?>
	<div class="span6 col">
	<?php foreach( $posts_col as $post ) { ?>
		<div class="post post-fragment">
			<div class="row-fluid">
				<?php $this->load->module('post/common/meta', array( $post, 'span4' )); ?>
				<div class="span8 post-entry">
					<h2><a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
					<div class="post-content"><?php echo get_post_fragment( $post['content'] ); ?></div>
				</div>
				<a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="btn btn-primary btn-more pull-right" rel="bookmark">阅读更多 <i class="icon-white icon-share-alt"></i></a>
			</div>
		</div>
	<?php } ?>
	</div>
<?php
	}
}
?>
	<div class="c"></div>
</div>
<script>
$('.post-content .post-image').slimbox();
</script>
<?php } else { ?>
<div class="alert alert-block alert-error alert-empty">
	<h3 class="alert-heading">該作者太懶了，一篇文章都沒有</h3>
</div>
<?php } ?>