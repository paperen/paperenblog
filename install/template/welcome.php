<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link media="all" rel="stylesheet" href="template/static/css/bootstrap.css" type="text/css" />
<link media="all" rel="stylesheet" href="template/static/css/install.css" type="text/css" />
</head>

<body>

<div class="container install-wrap">
	<div class="span6 offset3">
		<div class="alert alert-success alert-block">
			<h1 class="alert-heading"><span class="install-logo"></span> 安裝paperenblog</h1>
			<h3>特點</h3>
			<ul>
				<?php foreach( $data['feature'] as $single ) { ?>
				<li><?php echo $single; ?></li>
				<?php } ?>
			</ul>
		</div>
		<hr>
		<a href="<?php echo next_step(); ?>" class="btn btn-success btn-large pull-right">進行安裝</a>
	</div>
</div>

</body>
</html>
