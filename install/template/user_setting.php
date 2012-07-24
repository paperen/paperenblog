<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>站點基礎配置與管理員</title>
<link media="all" rel="stylesheet" href="template/static/css/bootstrap.css" type="text/css" />
<link media="all" rel="stylesheet" href="template/static/css/install.css" type="text/css" />
</head>

<body>

<div class="container install-wrap">
	<div class="span6 offset3">
		<div>
			<h1><span class="install-cog"></span> 站點基礎配置與管理員</h1>
			<hr>
			<?php if( isset( $data['err'] ) && $data['err'] ){ ?>
			<div class="alert alert-error">
				<ul>
					<?php foreach( $data['err'] as $error ) { ?>
					<li><?php echo $error; ?></li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
			<form method="post" class="well">
				<h3>管理員</h3>
				<hr>
				<div class="control-group">
					<label class="control-label" for="name">管理員</label>
					<input type="text" class="input-large" id="name" name="name">
				</div>
				<div class="control-group">
					<label class="control-label" for="blogemail">管理員Email</label>
					<input type="text" class="input-large" id="blogemail" name="blogemail">
				</div>
				<div class="control-group">
					<label class="control-label" for="password">密碼</label>
					<input type="password" class="input-large" id="password" name="password">
				</div>
				<div class="control-group">
					<label class="control-label" for="password2">密碼</label>
					<input type="password" class="input-large" id="password2" name="password2">
				</div>
				<hr>
				<div class="form-actions">
					<input type="submit" class="btn btn-success btn-large pull-right" value="繼續" name="submit">
					<div class="c"></div>
				</div>
			</form>
		</div>
	</div>
</div>

</body>
</html>