<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<div class="btn-group pull-right">
		<a href="<?php echo base_url('my_category/add'); ?>" class="btn btn-success"><i class="icon-plus icon-white"></i> 添加新類別</a>
	</div>
	<h3><i class="icon-edit"></i>我的類別 <strong>共<?php echo $total; ?></strong></h3>
	<hr>
	<div class="box box-headtitle box-radius">
		<ul id="category-list" class="unstyled list_order">
			<?php foreach( $category_data as $category ) { ?>
			<li id="cat-<?php echo $category['id']; ?>" parent="<?php echo $category['pid']; ?>" level="<?php echo count( explode( '-', $category['pidlevel'] ) ) - 1; ?>">
				<?php if( $category['pid'] == 0 ) { ?>
				<i class="icon-folder-open"></i>&nbsp;
				<?php } ?>
				<?php echo $category['category']; ?>
				<a href="<?php echo base_url("my_category/edit/{$category['id']}"); ?>">修改</a>
				<a href="javascript:void(0);" class="btn-delete" rel="<?php echo $category['id']; ?>">刪除</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<script>
var padding_left = 20;
$(function(){
	list_order( '#category-list' );
});
function list_order( id ) {
	var li =$(id + ' li');
	li.each(function(i){
		var parent_id = $(this).attr('parent');
		if ( typeof( $(this).attr('ordered') ) == 'undefined' ) {
			if ( parent_id != 0 ) {
				$(this).attr('ordered', true)
				.css({'padding-left': padding_left*$(this).attr('level') + 'px'})
				.insertAfter( $('#cat-' + parent_id) );
				list_order( id );
			} else {
				$(this).attr('ordered', true)
				.css({'padding-left': padding_left*$(this).attr('level') + 'px'});
			}
		}
	});
}
</script>
<!-- main -->
<div id="confirm-delete" class="modal hide fade in">
<div class="modal-header">
	<a class="close" data-dismiss="modal">&times</a>
	<h3>親~請再次確定刪除這個類別</h3>
</div>
<div class="modal-body">
	<p>執行此動作將產生以下結果</p>
	<ul>
		<li>該類別文章會歸為父級類別，若該類別唔上級則歸為[默認類別]</li>
		<li>該類別的子類會繼承到父級</li>
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
	var delete_url = '<?php echo base_url('my_category/delete'); ?>/';
	// 徹底刪除
	$('.btn-delete').click(function(){
		$('#confirm-delete-url').attr('href', delete_url + $(this).attr('rel') );
		$('#confirm-delete').modal('show');
	});
});
</script>
<?php $this->load->module('admin/footer/common/index'); ?>