<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><i class="icon-trash"></i>我的回收站 <strong>共<?php echo $total; ?></strong>篇</h3>
	<hr>
	<table class="table table-condensed table-wordpress postlist-table">
		<thead>
			<tr>
				<th width="20%">標題</th>
				<th>類別</th>
				<th width="55%">片段</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if( empty( $post_data ) ) { ?>
			<tr>
				<td colspan="4">
					<div class="alert alert-block alert-info">
						<h4 class="alert-heading">您的回收站沒有任何文章</h4>
					</div>
				</td>
			</tr>
			<?php } else { ?>
			<?php foreach( $post_data as $single ) { ?>
			<tr<?php if( $single['ispublic'] ) { ?> class="tr-publish"<?php } ?>>
				<td>
					<strong class="title"><?php echo $single['title']; ?></strong>
					<p><?php echo $single['author']; ?></p>
				</td>
				<td><?php echo $single['category']; ?></td>
				<td><?php echo gbk_substr( strip_tags( $single['content'] ), 300); ?></td>
				<td>
					<div class="btn-toolbar">
						<div class="btn-group">
							<a href="<?php echo base_url("trash_revoke/{$single['id']}"); ?>" class="btn btn-small btn-primary">撤銷</a>
						</div>
						<div class="btn-group">
							<a href="javascript:void(0);" class="btn btn-small btn-danger btn-delete" rel="<?php echo $single['id']; ?>"><i class="icon-white icon-warning-sign"></i> 徹底刪除</a>
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
<div id="confirm-delete" class="modal hide fade in">
<div class="modal-header">
	<a class="close" data-dismiss="modal">&times</a>
	<h3>親~請再次確定刪除這篇文章</h3>
</div>
<div class="modal-body">
	<p>執行此動作將產生以下結果</p>
	<ul>
		<li>該文章徹底刪除</li>
		<li>該文章相關的附件會被刪除</li>
		<li>該文章相關的評論會被刪除</li>
	</ul>
</div>
<div class="modal-footer">
	<a href="#" class="btn" data-dismiss="modal">取消</a>
	<a href="#" class="btn btn-primary" id="confirm-delete-url">確定</a>
</div>
</div>
<?php echo js('bootstrap/bootstrap-modal.js'); ?>
<script>
$(function(){
	var delete_url = '<?php echo base_url('delete'); ?>/';
	$('.postlist-table td').hover(
		function() {
			$(this).parent().addClass('tr-active');
		},
		function() {
			$(this).parent().removeClass('tr-active');
		}
	);
	// 徹底刪除
	$('.btn-delete').click(function(){
		$('#confirm-delete-url').attr('href', delete_url + $(this).attr('rel') );
		$('#confirm-delete').modal('show');
	});
});
</script>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>