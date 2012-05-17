<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><i class="icon-edit"></i>我的文章 <strong>共<?php echo $total; ?></strong>篇</h3>
	<hr>
	<table class="table table-condensed table-wordpress postlist-table">
		<thead>
			<tr>
				<th>標題</th>
				<th>類別</th>
				<th>是否已發佈</th>
				<th>發佈時間</th>
				<th>是否草稿</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $post_data as $single ) { ?>
			<tr>
				<td rowspan="2"><?php echo $single['title']; ?></td>
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
					<?php if( $single['isdraft'] ) { ?>
					<span class="btn btn-success disabled btn-small">是</span>
					<?php } else { ?>
					<span class="btn disabled btn-small">否</span>
					<?php } ?>
				</td>
				<td>
					<div class="btn-group">
						<a href="<?php echo post_permalink( $single['urltitle'] ); ?>" class="btn btn-small" target="_blank">查看</a>
						<a href="<?php echo comment_url( $single['urltitle'] ); ?>" target="_blank" class="btn btn-small">查看評論</a>
						<a href="<?php echo base_url("edit/{$single['id']}"); ?>" class="btn btn-small">更改</a>
						<a href="<?php echo base_url("trash/{$single['id']}"); ?>" class="btn btn-small btn-danger">回收站</a>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<ul class="unstyled meta">
						<li title="瀏覽數"><i class="icon-eye-open"></i><strong><?php echo $single['click']; ?></strong></li>
						<li title="頂"><i class="icon-heart"></i><strong><?php echo $single['good']; ?></strong></li>
						<li title="踩"><i class="icon-trash"></i><strong><?php echo $single['bad']; ?></strong></li>
						<li title="作者"><i class="icon-user"></i><strong><?php echo $single['author']; ?></strong></li>
					</ul>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php echo $pagination; ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>