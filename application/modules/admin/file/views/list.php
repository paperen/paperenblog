<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><i class="icon-picture"></i>我的附件
		<strong><?php echo $total; ?></strong>篇
		<strong><?php echo $total_size; ?></strong>kb
	</h3>
	<hr>
	<table class="table table-condensed table-wordpress postlist-table">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>关联文章</th>
				<th>大小</th>
				<th>是否图片</th>
				<th>是否文章特色图</th>
				<th>上传时间</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $attachment_data as $single ) { ?>
			<tr>
				<td width="120">
					<?php if( $single['isimage'] ) { ?>
					<div class="span12"><a href="<?php echo file_url( $single['id'] ); ?>" class="thumbnail" target="_blank"><img src="<?php echo file_url( $single['id'] ); ?>" alt="<?php echo $single['name']; ?>" title="<?php echo $single['name']; ?>"></a></div>
					<?php } else { ?>
					<a href="<?php echo file_url( $single['id'] ); ?>" target="_blank"><i class="icon-file"></i> <?php echo $single['name']; ?></a>
					<?php } ?>
				</td>
				<td><a href="<?php echo post_permalink($single['urltitle']); ?>"><?php echo $single['title']; ?></a></td>
				<td><?php echo $single['size']; ?>kb</td>
				<td><?php echo ( $single['isimage'] ) ? '是' : '否'; ?></td>
				<td><?php echo ( $single['isthumbnail'] ) ? '是' : '否'; ?></td>
				<td><?php echo date('Y-m-d H:i', $single['addtime']); ?></td>
			</tr>
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