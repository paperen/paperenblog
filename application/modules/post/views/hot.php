<?php if( isset( $posts_data ) && $posts_data ) { ?>
<!-- hotpost -->
<div class="hotpost">
	<h3>顶得火热博文</h3>
	<ul>
		<?php foreach( $posts_data as $post ) { ?>
		<li><a href="<?php echo base_url("post/{$post['urltitle']}"); ?>"><?php echo $post['title']; ?></a></li>
		<?php } ?>
	</ul>
</div>
<!-- hotpost -->
<?php } ?>