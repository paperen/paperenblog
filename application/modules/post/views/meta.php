<?php if( isset( $post ) && $post ) { ?>
<div class="post-attr<?php echo $extra_class; ?>">
	<a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="thumbnail"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_1.jpg" alt=""></a>
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
		<li>阅 <a href="<?php echo post_permalink( $post['urltitle'] ); ?>"><strong><?php echo $post['click']; ?></strong></a></li>
		<li>评 <a href="<?php echo comment_url( $post['urltitle'] ); ?>"><strong><?php echo isset( $post['commentnum'] ) ? $post['commentnum'] : 0; ?></strong></a></li>
	</ul>
	<ul class="post-meta">
		<li>作者 <a href="<?php echo author_url( $post['author'] ); ?>"><?php echo $post['author']; ?></a></li>
		<li>类别 <a href="<?php echo category_url( $post['category'] ); ?>"><?php echo $post['category']; ?></a></li>
		<?php if( isset( $post['tags'] ) && $post['tags'] ) { ?>
		<li>标签
			<?php foreach( $post['tags'] as $tag ) { ?>
			<a href="<?php echo tag_url( $tag ); ?>"><?php echo $tag; ?></a>
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
	<?php if( isset( $display_op ) && $display_op ) { ?>
	<div class="post-op">
		<a href="#" class="btn btn-small btn-ding btn-success"><i class="icon-paperen icon-ding"></i>頂</a>
		<a href="#" class="btn btn-small btn-cai"><i class="icon-paperen icon-cai"></i>踩</a>
	</div>
	<?php } ?>
</div>
<?php } ?>