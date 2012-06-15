<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><i class="icon-picture"></i>我的附件 <strong>共<?php echo $total; ?></strong>篇</h3>
	<hr>
	<table class="table table-condensed table-wordpress filelist-table">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>关联文章</th>
				<th>大小</th>
				<th>是否图片</th>
				<th>是否文章特色图</th>
				<th>上传时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>&nbsp;</td>
				<td>关联文章</td>
				<td>大小</td>
				<td>是否图片</td>
				<td>是否文章特色图</td>
				<td>上传时间</td>
				<td>操作</td>
			</tr>
		</tbody>
	</table>
	<?php echo $pagination; ?>
</div>
<script>
$(function(){
	$('.filelist-table td').hover(
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