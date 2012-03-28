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
		    <div class="span4 post-attr">
			<a href="#" class="thumbnail"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_1.jpg" alt=""></a>
			<div class="post-date">
			    <ul class="col2">
				<li class="weekday"><?php echo get_weekday_from_unixtime( $post['posttime'] ); ?></li>
			    </ul>
			    <ul class="col1">
				<li class="year"><?php echo date('Y', $post['posttime']); ?></li>
				<li class="month-day"><?php echo date('m-d', $post['posttime']); ?></li>
				<li class="time"><?php echo date('H:i', $post['posttime']); ?></li>
			    </ul>
			    <div class="c"></div>
			</div>
			<div class="post-sincetime">
			    <?php echo get_time_diff( $post['posttime'] ); ?>
			</div>
			<ul class="vote">
			   <li>顶 <strong><?php echo $post['good']; ?></strong></li>
			   <li>踩 <strong><?php echo $post['bad']; ?></strong></li>
			</ul>
			<ul class="post-data">
			    <li>阅 <strong><?php echo $post['click']; ?></strong></li>
			    <li>评 <strong><?php echo $post['commentnum']; ?></strong></li>
			</ul>
			<ul class="post-meta">
			    <li>作者 <a href="<?php echo base_url("author/{$post['author']}/"); ?>"><?php echo $post['author']; ?></a></li>
			    <li>类别 <a href="<?php echo base_url("category/{$post['category']}/"); ?>"><?php echo $post['category']; ?></a></li>
			    <li>标签
					<?php foreach( $post['tags'] as $tag ) { ?>
					<a href="<?php echo base_url("tag/{$tag}/"); ?>"><?php echo $tag; ?></a>
					<?php } ?>
				</li>
			</ul>
		    </div>
		    <div class="span8 post-entry">
			<h2><a href="<?php echo base_url("post/{$post['urltitle']}/"); ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
			<?php echo get_post_fragment( $post['content'] ); ?>
		    </div>
		    <a href="<?php echo base_url("post/{$post['urltitle']}/"); ?>" class="btn btn-primary btn-more pull-right">阅读更多 <i class="icon-white icon-share-alt"></i></a>
		</div>
	    </div>
	<?php } ?>
	</div>
<?php } ?>
	<ul class="pager">
	    <li class="previous"><a href="#">&laquo; Older</a></li>
	    <li class="next"><a href="#">Newer &raquo;</a></li>
	</ul>

    </div>

</div>
<script>
$(function(){
	prettyPrint();
});
</script>