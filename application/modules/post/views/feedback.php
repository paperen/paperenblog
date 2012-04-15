<div id="ding-cai-modal" class="modal hide fade in p-modal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<?php if( isset( $post_data['author'] ) ) { ?>
		<h3>Hi，我是<?php echo $post_data['author']; ?></h3>
		<?php } else { ?>
		<h3>系統檢測到您在進行邪惡的事情</h3>
		<?php } ?>
	</div>
	<div class="modal-body row-fluid">
		<div class="span2">
			<?php if( isset( $post_data['authoremail'] ) && $post_data['authoremail'] ) { ?>
			<span class="thumbnail"><img src="<?php echo gravatar_url('paperen@gmail.com'); ?>" alt="<?php echo $post_data['author']; ?>" title="<?php echo $post_data['author']; ?>"></sapn>
			<?php } else { ?>
				<span class="thumbnail"><img src="<?php echo bxjg_random(); ?>"></sapn>
			<?php } ?>
		</div>
		<div class="span9">
			<?php if( isset( $error ) && $error ) { ?>
			<div class="well alert alert-block alert-danger"><?php echo $error; ?></div>
			<?php } else { ?>
			<div class="well alert alert-block alert-success"><?php echo $message; ?></div>
			<?php } ?>
		</div>
		<div class="c"></div>
	</div>
</div>
<script>
$('#ding-cai-modal').on('show', function(){
	setTimeout(function(){
		$('#ding-cai-modal').modal('hide');
	}, 5000);
});
<?php if ( !isset( $error ) ){ ?>
$('#ding-cai-modal').on('hidden', function(){
	$('#post-ding-num').html( '<?php echo $post_data['good']; ?>' );
	$('#post-cai-num').html( '<?php echo $post_data['bad']; ?>' );
	$('.vote').effect('highlight', {}, 2500);
});
<?php } ?>
$('#ding-cai-modal').modal('show');
</script>