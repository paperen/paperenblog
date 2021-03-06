<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<div class="btn-group pull-right">
		<a href="<?php echo base_url('link/add'); ?>" class="btn btn-success"><i class="icon-plus icon-white"></i> 添加新链接</a>
	</div>
	<h3><i class="icon-heart"></i>链接共有 <strong><?php echo $total; ?></strong> 個</h3>
	<hr>
	<table class="table table-condensed table-wordpress postlist-table">
		<thead>
			<tr>
				<th width="12%">图片</th>
				<th>名称</th>
				<th>排序</th>
				<th>备注</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $link_data as $single ) { ?>
			<tr>
				<td>
				<?php if( $single['image'] ) { ?>
				<div class="thumbnail span8"><img src="<?php echo file_url( $single['image'] ); ?>" alt="<?php echo $single['name']; ?>"></div>
				<?php } else { ?>
				没有任何图片
				<?php } ?>
				</td>
				<td><a href="<?php echo add_http( $single['url'] ); ?>" target="_blank"><?php echo $single['name']; ?></a><br>[<?php echo $single['email']; ?>]</td>
				<td><?php echo $single['order']; ?></td>
				<td><?php echo $single['meta']; ?></td>
				<td>
					<div class="btn-group">
						<a href="<?php echo base_url("link/edit/{$single['id']}"); ?>" class="btn">修改</a>
						<a href="<?php echo base_url("link/delete/{$single['id']}"); ?>" class="btn">删除</a>
					</div>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>
