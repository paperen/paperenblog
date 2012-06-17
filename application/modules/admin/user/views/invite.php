<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>paperen博客邀請函</title>
<body>

<h1>Hi,<?php echo $user_data['name']; ?> 我們邀請您成為我們博客的一名blogger</h1>
<ul>
	<li>您的帳號為 <strong><?php echo $user_data['name']; ?></strong></li>
	<li>您的密碼為 <strong><?php echo $user_data['password']; ?></strong></li>
	<li><a href="<?php echo base_url('manage'); ?>">博客作者入口</a></li>
</ul>
<p>個人信息與密碼的修改可以在登錄後臺操作，建議最好自己更換一下密碼</p>
<p>本郵件為系統發出</p>

</body>
</html>