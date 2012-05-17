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
			<tr>
				<td rowspan="2">新版博客的面世</td>
				<td>吹水</td>
				<td><span class="btn btn-success disabled btn-small">是</span></td>
				<td>2012-05-07 14:34</td>
				<td><span class="btn disabled btn-small">否</span></td>
				<td>
					<div class="btn-group">
						<a href="#" class="btn btn-small">查看</a>
						<a href="#" class="btn btn-small">查看評論</a>
						<a href="#" class="btn btn-small ">更改</a>
						<a href="#" class="btn btn-small btn-danger">回收站</a>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<ul class="unstyled meta">
						<li title="瀏覽數"><i class="icon-eye-open"></i><strong>58</strong></li>
						<li title="頂"><i class="icon-heart"></i><strong>5</strong></li>
						<li title="踩"><i class="icon-trash"></i><strong>2</strong></li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $pagination; ?>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>