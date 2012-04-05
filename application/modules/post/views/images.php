<?php if( isset( $post_images ) && $post_images ) { ?>
<div class="post-image">
	<h3>附帶圖片 <small><?php echo $total; ?></small></h3>
	<div class="row-fluid">
		<?php foreach( $post_images as $col_images ) { ?>
		<ul class="thumbnails span3 col">
			<?php foreach( $col_images as $single ) { ?>
			<li class="single">
			<a href="<?php echo file_url( $single['id'] ); ?>" class="thumbnail" title="<?php echo $single['name']; ?>" data-original-title="查看大圖"><img src="<?php echo file_url( $single['id'] ); ?>" alt="<?php echo $single['name']; ?>"></a>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>
</div>
<script>
$('.post-image .thumbnail').tooltip({
	placement: 'top'
});
$('.thumbnails a').slimbox();
</script>
<?php } ?>