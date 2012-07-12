<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>paperen博客邀請函</title>
<style>
body, p, label, textarea, input {
	font-family: "Montserrat", "Microsoft YaHei", "arial", "sans-serif";
	line-height: 1.6em;
	font-size: 12px;
}
.con {
	width: 500px;
	margin: 10% auto;
	padding: 15px;
}
.alert {
  padding: 8px 35px 8px 14px;
  margin-bottom: 18px;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
  background-color: #fcf8e3;
  border: 1px solid #fbeed5;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
}
.alert, .alert-heading {
  color: #c09853;
}
.alert .close {
  position: relative;
  top: -2px;
  right: -21px;
  line-height: 18px;
}
.alert-success {
  background-color: #dff0d8;
  border-color: #d6e9c6;
}
.alert-success, .alert-success .alert-heading {
  color: #468847;
}
.alert-danger, .alert-error {
  background-color: #f2dede;
  border-color: #eed3d7;
}
.alert-danger,
.alert-error,
.alert-danger .alert-heading,
.alert-error .alert-heading {
  color: #b94a48;
}
.alert-info {
  background-color: #d9edf7;
  border-color: #bce8f1;
}
.alert-info, .alert-info .alert-heading {
  color: #3a87ad;
}
.alert-block {
  padding-top: 14px;
  padding-bottom: 14px;
}
.alert-block > p, .alert-block > ul {
  margin-bottom: 0;
}
.alert-block p + p {
  margin-top: 5px;
}
.footer {
	font-size: 11px;
	color: #999;
}
a {
	color: #000;
	text-decoration: none;
}
.logo {
	display: block;
	margin-bottom: 8px;
}
</style>
<body>

<div class="con">
	<a href="<?php echo base_url(); ?>" class="logo"><img src="<?php echo base_url('theme') . '/' . config_item('theme') . '/image/logo.gif'; ?>" alt="logo" title="<?php echo config_item('sitename'); ?>"></a>
	<div class="alert alert-info">
		<h3 class="alert-heading">Hi,<?php echo $user_data['name']; ?> 我們邀請您成為我們博客的一名blogger</h3>
		<ul>
			<li>您的帳號為 <strong><?php echo $user_data['name']; ?></strong></li>
			<li>您的密碼為 <strong><?php echo $user_data['password']; ?></strong></li>
			<li><a href="<?php echo base_url('manage'); ?>">博客作者入口</a></li>
		</ul>
		<p>個人信息與密碼的修改可以在登錄後在个人设置进行操作，建議最好自己更換一下密碼</p>
	</div>
	<div class="footer">本郵件為系統于 <?php echo date('Y-m-d H:i:s'); ?> 發出</div>
</div>

</body>
</html>