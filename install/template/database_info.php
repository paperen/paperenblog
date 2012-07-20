<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Docman-install</title>
<link rel="stylesheet" href="./template/static/css/css.css" />
</head>

<body>
<div id="logos">
   <div class="inner-box">
   		<img src="./template/static/images/logos-bg.jpg" />
   </div>
</div>
<div id="commonbox">
	<div class="inner-box">
    	<div class="left-box">
        	<div class="floorcm first-fl">
            	<span class="lefttitle">
                	配置数据库
                </span>
                <div class="check-box">
 				<form method="post" action="./index.php?step_key=<?php echo $data['step_key'];?>" method="post">
                	<table>
                    	<tr>
                        	<td>数据库主机:</td>
                            <td><input name="db_host" class="inputtxt" type="text"  value="localhost"/></td>
                        </tr>
                        <tr>
                        	<td>用户名:</td>
                            <td><input name="db_user_name" class="inputtxt" type="text"  value="root"/></td>
                        </tr>
                        <tr>
                        	<td>密码:</td>
                            <td><input name="db_password" class="inputtxt" type="password" value=""/></td>
                        </tr>
                        <tr>
                        	<td>数据库名:</td>
                            <td><input name="db_name" class="inputtxt" type="text"  value="docman"/></td>
                        </tr>
                        <tr>
                        	<td>表前缀:</td>
                            <td><input name="table_pre" class="inputtxt" type="text"  value="dm_"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <table class="bt-table" width="100%">
            	<tr>
                	<td width="200"></td>
                    <td align="center" style="padding-top:10px;">
      <span id="install-btn"><a href="./index.php?step_key=<?php echo $data['step_key'] - 2;?>"><input class="button" type="submit" id="js-submit" value="上一步：检测环境"></a></span>
                    </td>
                    <td align="center" style="padding-top:10px;">
      <span id="install-btn"><input class="button" type="submit" id="js-submit" value="立即安装"></span>
                    </td>
                    <td align="center" style="padding-top:10px;">
                    </td>
                    <td width="200"></td>
				</tr>
                <tr></tr>
            </table>
            </form> 
        </div>
        <div class="right-box">
        	<ul>
            	<li class="bt-bg">欢迎使用</li>
                <li class="rec-bg"></li>
                <li class="bt-bg">检查配置</li>
                <li class="rec-bg"></li>
                <li class="bt-ac">数据库配置</li>
				<li class="rec-bg"></li>
				<li class="bt-bg">安装状态监测</li>
                <li class="rec-bg"></li>
                <li class="bt-bg">用户设置</li>
                <li class="rec-bg"></li>
                <li class="bt-bg">安装成功!</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
