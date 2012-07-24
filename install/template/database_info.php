<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>數據庫配置信息</title>
	<link media="all" rel="stylesheet" href="template/static/css/bootstrap.css" type="text/css" />
	<link media="all" rel="stylesheet" href="template/static/css/install.css" type="text/css" />
	<script src="http://lib.sinaapp.com/js/jquery/1.7.2/jquery.min.js"></script>
</head>

<body>
<div class="container install-wrap">
	<div class="span6 offset3">
		<h1><span class="install-db"></span> 數據庫配置</h1>
		<hr>
<?php if( !isset( $data['process'] ) ) { ?>
		<form method="post" class="well">
		<div class="control-group">
			<label class="control-label" for="dbhost">數據庫服務器</label>
			<input type="text" class="input-large" id="dbhost" name="dbhost">
		</div>
		<div class="control-group">
			<label class="control-label" for="dbname">數據庫名</label>
			<input type="text" class="input-large" id="dbname" name="dbname">
		</div>
		<div class="control-group">
			<label class="control-label" for="dbuser">數據庫用戶</label>
			<input type="text" class="input-large" id="dbuser" name="dbuser">
		</div>
		<div class="control-group">
			<label class="control-label" for="dbpwd">密碼</label>
			<input type="password" class="input-large" id="dbpwd" name="dbpwd">
		</div>
		<hr>
		<div class="form-actions">
			<input type="submit" class="btn btn-success btn-large pull-right" value="繼續" name="submit">
			<a href="<?php echo prev_step(); ?>" class="btn btn-large pull-right">上一步</a>
			<div class="c"></div>
		</div>
		</form>
<?php } else { ?>
		<div class="well">
			<div class="process" id="process"></div>
		</div>
<?php } ?>
	</div>
</div>
</body>
</html>
