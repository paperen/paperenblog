<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<div class="btn-group pull-right">
		<a href="<?php echo base_url('add_category'); ?>" class="btn btn-success"><i class="icon-plus icon-white"></i> 添加新類別</a>
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
				<a href="<?php echo base_url("edit_category/{$category['id']}"); ?>">修改</a> /
				<a href="#">刪除</a>
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
<?php $this->load->module('admin/footer/common/index'); ?>