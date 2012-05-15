<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">

	<div class="container-fluid">
		<h3><i class="icon-home"></i>管理面板</h3>
		<hr>
		<!-- alert -->
		<div class="alert alert-block alert-info">
			<h4 class="alert-heading">系統提示</h4>
			<p>上次登錄的IP為 200.200.200.117，本次登錄的IP為 200.200.200.117</p>
			<p>上次登錄日期為 2012-05-06 16:34</p>
			<p>您已經有大約 <strong>15</strong> 天沒發表文章了，<a href="#">去寫一篇</a></p>
		</div>
		<!-- alert -->
		<div class="draft">
			<h4 class="title">未處理文章草稿(3)</h4>
			<table class="table table-condensed table-wordpress">
				<thead>
					<tr>
						<th>保存時間</th>
						<th>標題</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2012-05-09 23:12</td>
						<td>激情四射的年代</td>
						<td><a href="#">完善</a> / <a href="#">刪掉</a></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="comment">
			<h4 class="title">未審批的評論</h4>
			<table class="table table-condensed table-wordpress">
				<thead>
					<tr>
						<th>文章</th>
						<th>評論時間</th>
						<th width="200">評論內容</th>
						<th>評論人</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="#">激情四射的年代</a></td>
						<td>2012-5-9 23:45</td>
						<td>無可否認的是這樣，這個年代都男不男女不女了，還有什麼奇怪的…</td>
						<td><a href="http://iamlze.cn">paperen</a></td>
						<td><a href="#">通過并回覆</a> / <a href="#">不通過</a> / <a href="#">刪除</a></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="recent-post">
			<h4 class="title">最近發文</h4>
			<table class="table table-condensed table-wordpress">
				<thead>
					<tr>
						<th>標題</th>
						<th>發文時間</th>
						<th>作者</th>
						<th>查看</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="#">激情四射的年代</a></td>
						<td>2012-5-9 23:45</td>
						<td>fatboy</td>
						<td><a href="#">查看</a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>