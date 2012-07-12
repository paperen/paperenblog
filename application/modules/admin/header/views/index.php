<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<?php echo page_title( ( isset( $page_title ) ? $page_title : '管理') ); ?>
<?php echo css('bootstrap.css'); ?>
<?php echo css('admin.css'); ?>
<?php echo js('jquery-1.7.1.min.js'); ?>
<?php echo js('admin/common.js'); ?>
</head>

<body>

<!-- header -->
<div class="header navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="brand" href="<?php echo base_url('manage'); ?>"><?php echo config_item('sitename'); ?> 管理</a>
			<ul class="nav pull-right">
				<li><a href="<?php echo base_url('user/setting'); ?>">Hi，<?php echo $username; ?></a></li>
				<li>
					<a href="<?php echo base_url(); ?>" target="_blank"><i class="icon-home"></i>站点</a>
				</li>
				<li>
					<a href="<?php echo base_url('user/setting'); ?>"><i class="icon-cog"></i>个人设置</a>
				</li>
				<li><a href="<?php echo base_url('logout'); ?>"><i class="icon-off"></i>注销</a></li>
			</ul>
		</div>
	</div>
</div>
<!-- header -->
<div class="container-fluid">
	<!-- row-fluid -->
	<div class="row-fluid wrap">