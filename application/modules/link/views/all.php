<?php if( isset( $links ) && $links ) { ?>
<!-- links -->
<div class="links">
<h3>朋友</h3>
<ul>
	<?php foreach( $links as $single ) { ?>
	<li><a href="<?php echo add_http($single['url']); ?>" rel="popover" data-content="<?php echo $single['meta']; ?>" data-original-title="<?php echo $single['name']; ?>" rel="friend"><?php echo $single['name']; ?></a></li>
	<?php } ?>
</ul>
</div>
<script>
$('.links a').popover({
	placement : 'left'
});
</script>
<!-- links -->
<?php } ?>