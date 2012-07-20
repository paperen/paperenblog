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
            		<form method="post" action="./index.php?step_key=<?php echo $data['step_key'];?>">
                

			<div class="floorcm first-fl">
            	<span class="lefttitle">
                系统设置
                </span>
                <div class="check-box">
            		<form method="post" action="index2.html">
                	<table>
                    	<tr>
                        	<td>访问地址:</td>
                            <td><input name="base_url" class="inputtxt" type="text"  value="http://"/></td>
                        </tr>
                        <tr>
                        	<td>应用名称:</td>
                            <td><input name="app_name" class="inputtxt" type="text"  value=""/></td>
                        </tr>
                        <tr>
                        	<td>应用身份号码:</td>
                            <td><input name="app_authkey" class="inputtxt" type="text"  value=""/></td>
                        </tr>
                            <tr>
                        	<td>任务系统URL:</td>
                            <td><input name="taskman_url" class="inputtxt" type="text"  value=""/></td>
                        </tr>
                             <tr>
                        	<td>LYCENTER应用API URL:</td>
                            <td><input name="lycenter_api" class="inputtxt" type="text"  value=""/></td>
                        </tr>
                        <tr>
                        	<td>LYCENTER身份码:</td>
                            <td><input name="lycenter_authkey" class="inputtxt" type="text"  value=""/></td>
                        </tr>
                    </table>
                </div>
            </div>
			
			<div class="floorcm first-fl">
            	<span class="lefttitle">
				LYCENTER设置
                </span>
                <div class="check-box">
            		<form method="post" action="index2.html">
                	<table>
                    	<tr>
                        	<td>数据库地址:</td>
                            <td><input name="db_host" class="inputtxt" type="text"  value="localhost"/></td>
                        </tr>
                        <tr>
                        	<td>数据库用户名:</td>
                            <td><input name="db_username" class="inputtxt" type="text"  value="root"/></td>
                        </tr>
                        <tr>
                        	<td>数据库密码:</td>
                            <td><input name="db_pass" class="inputtxt" type="password"  value=""/></td>
                        </tr>
                            <tr>
                        	<td>数据库名:</td>
                            <td><input name="db_name" class="inputtxt" type="text"  value="lycenter"/></td>
                        </tr>
                    </table>
                </div>
            </div>
			
            <table class="bt-table" width="100%">
            	<tr>
                    <td align="center" style="padding-top:10px;">
      <span id="install-btn"><input class="button" type="submit" id="js-submit" value="确认"></span>
                    </td>
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
                <li class="bt-bg">数据库配置</li>
				<li class="rec-bg"></li>
				<li class="bt-bg">安装状态监测</li>
                <li class="rec-bg"></li>
                <li class="bt-ac">用户设置</li>
                <li class="rec-bg"></li>
                <li class="bt-bg">安装成功!</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
