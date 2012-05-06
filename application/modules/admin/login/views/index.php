<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>paperenblog博客管理登录</title>
		<?php echo css( 'http://fonts.googleapis.com/css?family=Asap:700|Righteous' );?>
		<?php echo css( 'bootstrap.css' );?>
		<?php echo css( 'login.css' );?>
	</head>

	<body class="login" onLoad="autoFocus();">

		<div class="container">
			<div class="row">
				<div class="span8 offset3">

					<h1 class="logo">Hello, Master</h1>
					<div class="login-form">
						<?php if( isset( $error ) && $error ) { ?>
						<!-- alert -->
						<div class="alert alert-danger">
							<h4 class="alert-heading">OMG！</h4>
							<p><?php echo $error; ?></p>
						</div>
						<!-- alert -->
						<?php } ?>
						<form method="post">
							<div class="control-group">
								<label for="username">Username</label>
								<div class="input-append">
									<input class="input-large" id="username" type="text" name="username" maxlength="30" placeholder="username 用户名" value="<?php echo isset( $user_data['username'] ) ? $user_data['username'] : ''; ?>">
									<label class="add-on" for="username"><i class="icon-user"></i></label>
								</div>
							</div>
							<div class="control-group">
								<label for="password">Password</label>
								<div class="input-append">
									<input class="input-large" type="password" name="password" id="password" placeholder="password 密码">
									<label class="add-on" for="password"><i class="icon-lock"></i></label>
								</div>
							</div>
							<hr>
							<div class="form-actions">
								<button type="submit" class="btn btn-success btn-large pull-right" name="loginbtn" value="login">Login</button>
							</div>
							<div class="c"></div>
							<?php echo create_token();?>
						</form>
					</div>

				</div>
			</div>
		</div>
		<?php if( !isset( $error ) ) { ?>
		<script>
			function autoFocus() {
				document.getElementById('username').focus();
			}
		</script>
		<?php } ?>
	</body>
</html>
