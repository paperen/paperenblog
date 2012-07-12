<?php if( isset( $post ) && $post ) { ?>
<div class="post-attr<?php echo $extra_class; ?>">
	<?php if( isset( $post['thumbnail'] ) && $post['thumbnail'] ) { ?>
	<a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="thumbnail" rel="bookmark"><img src="<?php echo file_url( $post['thumbnail'] ); ?>" alt="<?php echo $post['title']; ?>"></a>
	<?php } else { ?>
	<a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="thumbnail" rel="bookmark"><img src="<?php echo bxjg_random(); ?>" alt="<?php echo $post['title']; ?>"></a>	<?php } ?>
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
		<li>顶 <strong id="post-ding-num"><?php echo $post['good']; ?></strong></li>
		<li>踩 <strong id="post-cai-num"><?php echo $post['bad']; ?></strong></li>
	</ul>
	<ul class="post-data">
		<li>阅 <a href="<?php echo post_permalink( $post['urltitle'] ); ?>" rel="bookmark"><strong><?php echo $post['click']; ?></strong></a></li>
		<li>评 <a href="<?php echo comment_url( $post['urltitle'] ); ?>" rel="bookmark"><strong><?php echo isset( $post['commentnum'] ) ? $post['commentnum'] : 0; ?></strong></a></li>
	</ul>
	<ul class="post-meta">
		<li>作者 <a href="<?php echo author_url( $post['author'] ); ?>" rel="author"><?php echo $post['author']; ?></a></li>
		<li>类别 <a href="<?php echo category_url( $post['category'] ); ?>" rel="category"><?php echo $post['category']; ?></a></li>
		<?php if( isset( $post['tags'] ) && $post['tags'] ) { ?>
		<li>标签
			<?php foreach( $post['tags'] as $tag ) { ?>
			<a href="<?php echo tag_url( $tag ); ?>" rel="tag"><?php echo $tag; ?></a>
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
	<?php if( isset( $display_op ) && $display_op ) { ?>
	<div class="post-op">
		<a href="javascript:void(0);" class="btn btn-small btn-ding btn-success" rel="tooltip" data-original-title="覺得寫得不錯？果斷頂一個吧~" data-original-action="ding"><i class="icon-paperen icon-ding"></i>頂</a>
		<a href="javascript:void(0);" class="btn btn-small btn-cai" rel="tooltip" data-original-title="覺得寫得很差？應該踩" data-original-action="cai"><i class="icon-paperen icon-cai"></i>踩</a>
	</div>
<?php echo js('bootstrap/bootstrap-modal.js'); ?>
<?php echo js('bootstrap/bootstrap-transition.js'); ?>
<script>
var feedback_url = '<?php echo post_permalink( $post['id'] ); ?>';
$('.post-op a').tooltip({
	placement: 'right'
}).click(function(){
	var action = $(this).attr('data-original-action');
	$.ajax({
		type: "POST",
		url: feedback_url + '/' + action,
		success: function(msg){
			$('.footer').prepend( msg );
		}
	});
});
</script>
	<?php } ?>
</div>
<?php } ?>