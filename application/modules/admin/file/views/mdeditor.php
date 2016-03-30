<script>
<?php if($error){ ?>
parent.alert('<?php echo $message; ?>');
<?php } else { ?>
parent.MDE_insertFile('<?php echo $url; ?>');
<?php } ?>
</script>