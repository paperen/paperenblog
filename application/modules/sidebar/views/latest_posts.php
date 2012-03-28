<?php if( isset( $posts_data ) && $posts_data ) { ?>
<!-- latest post -->
<div class="latest">
	<h3>最近文章</h3>
	<ul>
		<?php foreach( $posts_data as $post ) { ?>
		<li><a href="<?php echo base_url("post/{$post['urltitle']}"); ?>"><?php echo $post['title']; ?></a></li>
		<?php } ?>
	</ul>
</div>
<!-- latest post -->
<?php } ?>