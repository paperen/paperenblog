<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<div class="btn-group pull-right">
		<a href="<?php echo base_url('add_post'); ?>" class="btn btn-success"><i class="icon-plus icon-white"></i> 寫新文章</a>
	</div>
	<h3><i class="icon-edit"></i>我的文章 <strong>共<?php echo $total; ?></strong>篇</h3>
	<hr>
	<table class="table table-condensed table-wordpress postlist-table">
		<thead>
			<tr>
				<th>標題</th>
				<th>類別</th>
				<th>是否已發佈</th>
				<th>發佈時間</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if( empty( $post_data ) ) { ?>
			<tr>
				<td colspan="5">
					<div class="alert alert-block alert-info">
						<h4 class="alert-heading">悲催~竟然沒有一篇您寫的文章 <a href="<?php echo base_url('add_post'); ?>" class="btn"><i class="icon-plus icon-white"></i> 去寫一篇</a></h4>
					</div>
				</td>
			</tr>
			<?php } else { ?>
			<?php foreach( $post_data as $single ) { ?>
			<tr<?php if( $single['ispublic'] ) { ?> class="tr-publish"<?php } ?>>
				<td class="td-title">
					<a href="<?php echo post_permalink( $single['urltitle'] ); ?>" target="_blank"><strong class="title"><?php echo $single['title']; ?></strong></a>
					<hr>
					<ul class="unstyled meta">
						<li title="瀏覽數"><i class="icon-eye-open"></i><strong><?php echo $single['click']; ?></strong></li>
						<li title="頂"><i class="icon-heart"></i><strong><?php echo $single['good']; ?></strong></li>
						<li title="踩"><i class="icon-trash"></i><strong><?php echo $single['bad']; ?></strong></li>
						<li title="作者"><i class="icon-user"></i><strong><?php echo $single['author']; ?></strong></li>
					</ul>
				</td>
				<td><?php echo $single['category']; ?></td>
				<td>
					<?php if( $single['ispublic'] ) { ?>
					<span class="btn btn-success disabled btn-small">是</span>
					<?php } else { ?>
					<span class="btn disabled btn-small">否</span>
					<?php } ?>
				</td>
				<td><?php echo get_time_diff($single['posttime']); ?></td>
				<td>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a href="<?php echo post_permalink( $single['urltitle'] ); ?>" class="btn btn-small" target="_blank">查看</a>
							<a href="<?php echo comment_url( $single['urltitle'] ); ?>" target="_blank" class="btn btn-small">查看評論</a>
							<a href="<?php echo base_url("edit/{$single['id']}"); ?>" class="btn btn-small">更改</a>
						</div>
						<div class="btn-group">
						<a href="<?php echo base_url("trash_add/{$single['id']}"); ?>" class="btn btn-small btn-danger">放回收站</a>
						</div>
					</div>
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
		</tbody>
	</table>
	<?php echo $pagination; ?>
</div>
<script>
$(function(){
	$('.postlist-table td').hover(
		function() {
			$(this).parent().addClass('tr-active');
		},
		function() {
			$(this).parent().removeClass('tr-active');
		}
	);
});
</script>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>