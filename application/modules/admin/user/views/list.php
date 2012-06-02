<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<div class="btn-group pull-right">
		<a href="<?php echo base_url('user/add'); ?>" class="btn btn-success"><i class="icon-plus icon-white"></i> 添加新作者</a>
	</div>
	<h3><i class="icon-edit"></i>用戶共有 <strong><?php echo $total; ?></strong> 個</h3>
	<hr>
	<table class="table table-condensed table-wordpress postlist-table">
		<thead>
			<tr>
				<th width="12%">&nbsp;</th>
				<th>用戶</th>
				<th>身份</th>
				<th>個人站點</th>
				<th>最近登陸時間</th>
				<th>最近登陸IP</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $user_data as $single ) { ?>
			<tr>
				<td><div class="thumbnail span8"><img src="<?php echo gravatar_url( $single['email'], 70 ); ?>" alt="<?php echo $single['name']; ?>"></div></td>
				<td><?php echo $single['name']; ?> [<?php echo $single['email']; ?>]</td>
				<td><?php echo get_role( $single['role'] ); ?></td>
				<td>
					<?php if( $single['url'] ) { ?>
					<a target="_blank" href="<?php echo add_http($single['url']); ?>"><?php echo $single['url']; ?></a>
					<?php } else { ?>
					木有
					<?php } ?>
				</td>
				<td><?php echo date('Y/m/d H:i:s', $single['lastlogin']); ?></td>
				<td><?php echo long2ip( $single['lastip'] ); ?></td>
				<td>
					<div class="btn-group">
						<a href="<?php echo base_url("user/edit/{$single['id']}"); ?>" class="btn">修改</a>
					</div>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>