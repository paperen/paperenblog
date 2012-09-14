<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<?php echo page_title( $page_title ); ?>
<?php echo css('http://fonts.googleapis.com/css?family=Montserrat'); ?>
<?php echo css('bootstrap.css'); ?>
<?php echo css('style.css'); ?>
<?php echo css(base_url('js/google-code-prettify/prettify.css')); ?>
<?php foreach( $extra_css as $single ) { ?>
<?php echo css($single); ?>
<?php } ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo config_item('sitename'); ?> Feed" href="<?php echo rss_url(); ?>"/>
<?php echo favicon_ico(); ?>
<?php echo js('jquery-1.7.1.min.js'); ?>
<?php echo js('google-code-prettify/prettify.js'); ?>
<?php echo js('bootstrap/bootstrap-tooltip.js'); ?>
<?php echo js('bootstrap/bootstrap-popover.js'); ?>
<?php echo js('bootstrap/bootstrap-fold.js'); ?>
<?php echo js('slimbox/slimbox2.js'); ?>
<?php echo js('blog.js'); ?>
</head>

<body>

<!-- navbar -->
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<?php echo base_url(); ?>"><?php echo ucfirst(config_item('sitename')); ?></a>
            <div class="nav-collapse">
                <ul class="nav">
		<?php foreach( $nav as $key => $single ) { ?>
                    <li<?php if( $key === $active ){ ?> class="active"<?php } ?>><a href="<?php echo $single['url']; ?>"><?php echo $single['text']; ?></a></li>
		<?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- navbar -->

<!-- container -->
<div class="container-fluid wrap">

    <div class="row-fluid">
