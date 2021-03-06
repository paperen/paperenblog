<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<div class="btn-group pull-right">
		<a href="<?php echo base_url('user/add'); ?>" class="btn btn-success"><i class="icon-plus icon-white"></i> 添加新用戶</a>
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
				<th>职业</th>
				<th>出没领域</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $user_data as $single ) { ?>
			<tr>
				<td rowspan="2"><div class="thumbnail span8"><img src="<?php echo gravatar_url( $single['email'], 70 ); ?>" alt="<?php echo $single['name']; ?>"></div></td>
				<td><?php echo $single['name']; ?><br>[<?php echo $single['email']; ?>]</td>
				<td><?php echo get_role( $single['role'] ); ?></td>
				<td>
					<?php if( $single['url'] ) { ?>
					<a target="_blank" href="<?php echo add_http($single['url']); ?>"><?php echo $single['url']; ?></a>
					<?php } else { ?>
					木有
					<?php } ?>
				</td>
				<td><?php echo $single['lastlogin'] ? date('Y/m/d H:i:s', $single['lastlogin']) : ''; ?></td>
				<td><?php echo $single['lastip'] ? long2ip( $single['lastip'] ) : ''; ?></td>
				<td><?php echo ( $single['data']['job'] ) ?$single['data']['job'] : ''; ?></td>
				<td>
				<?php if( $single['data']['socialname'] ) { ?>
				<ul class="unstyled">
				<?php foreach( $single['data']['socialname'] as $s => $s_url ) { ?>
				<li><a href="<?php echo $s_url; ?>"><?php echo $s; ?></a></li>
				<?php } ?>
				</ul>
				<?php } ?>
				</td>
				<td>
					<div class="btn-group">
						<a href="<?php echo base_url("user/edit/{$single['id']}"); ?>" class="btn">修改</a>
					</div>
				</td>
			</tr>
			<tr>
			<td colspan="7"><?php echo ( $single['data']['content'] ) ? $single['data']['content'] : '无任何介绍'; ?></td>
			<td>&nbsp;</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>
