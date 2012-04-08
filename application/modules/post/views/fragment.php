<?php $this->load->module( 'header/common', array( 'home', '首页' ) ); ?>
<div class="span9 main">

    <div class="pull-right btn-group margin-right15 display-style">
	<a href="#" class="btn btn-small active" title="網格呈現" data-original-title="網格呈現"><i class="icon-th"></i></a>
	<a href="#" class="btn btn-small" title="列表呈現" data-original-title="列表呈現"><i class="icon-th-list"></i></a>
    </div>
    <script>
	$('.display-style a').tooltip({
	    placement: 'right'
	});
    </script>
    <div class="c"></div>

    <div class="row-fluid">
<?php foreach( $posts_data_by_col as $posts_col ) { ?>
	<div class="span6 col">
	<?php foreach( $posts_col as $post ) { ?>
	    <div class="post post-fragment">
		<div class="row-fluid">
		    <?php $this->load->module('post/common/meta', array( $post, 'span4' )); ?>
		    <div class="span8 post-entry">
			<h2><a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
			<div class="post-content"><?php echo get_post_fragment( $post['content'] ); ?></div>
		    </div>
		    <a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="btn btn-primary btn-more pull-right">阅读更多 <i class="icon-white icon-share-alt"></i></a>
		</div>
	    </div>
	<?php } ?>
	</div>
<?php } ?>
		<div class="c"></div>
		<ul class="pager">
			<li class="previous"><a href="#">&laquo; Older</a></li>
			<li class="next"><a href="#">Newer &raquo;</a></li>
		</ul>
    </div>

</div>
<script>
$('.post-content .post-image').slimbox();
</script>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>