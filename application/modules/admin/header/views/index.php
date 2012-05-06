<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<?php echo page_title( ( isset( $page_title ) ? $page_title : '管理') ); ?>
<?php echo css('bootstrap.css'); ?>
<?php echo css('admin.css'); ?>
<?php echo js('jquery-1.7.1.min.js'); ?>
<?php echo js('bootstrap/bootstrap-fold.js'); ?>
</head>

<body>

<!-- header -->
<div class="header navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="brand" href="#"><?php echo config_item('sitename'); ?> 管理</a>
			<ul class="nav pull-right">
				<li class="dropdown">
					<a href="<?php echo base_url('user_setting'); ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i>个人设置</a>
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