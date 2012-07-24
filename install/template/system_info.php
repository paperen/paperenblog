<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>檢測系統必要環境</title>
<link media="all" rel="stylesheet" href="template/static/css/bootstrap.css" type="text/css" />
<link media="all" rel="stylesheet" href="template/static/css/install.css" type="text/css" />
</head>

<body>

<div class="container install-wrap">
	<div class="span6 offset3">
		<div>
			<h1><span class="install-system"></span> 系統環境檢測</h1>
			<h3>環境信息</h3>
			<table class="table table-condensed table-bordered">
				<tbody>
					<?php foreach( $data['info'] as $k => $v ) { ?>
					<tr>
						<td><?php echo $k; ?></td>
						<td><?php echo $v; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<h3>必須可寫的目錄</h3>
			<table class="table table-condensed table-bordered">
				<tbody>
					<?php foreach( $data['writeable'] as $dir => $v ) { ?>
					<tr>
						<td><?php echo $dir; ?></td>
						<td width="80">
							<?php if ( $v ) { ?>
							<div class="alert alert-success">可寫</div>
							<?php } else { ?>
							<div class="alert alert-error">不可寫</div>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<h3>必須開啟的模塊</h3>
			<table class="table table-condensed table-bordered">
				<tbody>
					<?php foreach( $data['required'] as $k => $v ) { ?>
					<tr>
						<td><?php echo $k; ?></td>
						<td width="80">
							<?php if ( $v ) { ?>
							<div class="alert alert-success">√</div>
							<?php } else { ?>
							<div class="alert alert-error">×</div>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<hr>
		<?php if( $data['allow_install'] ) { ?>
		<a href="<?php echo next_step(); ?>" class="btn btn-success btn-large pull-right">繼續</a>
		<?php } else { ?>
		<button class="btn btn-danger disabled">安裝條件不滿足</button>
		<?php } ?>
	</div>
</div>

</body>
</html>