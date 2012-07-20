<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Docman-install</title>
        <link rel="stylesheet" href="./template/static/css/css.css" />
        <script type="text/javascript" src="./javascript/jquery.js"></script>
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
				安装状态监测
                        </span>
                        <div class="check-box" id="database_info">
                        </div>
                    </div>
                </div>
                <div class="right-box">
                    <ul>
                        <li class="bt-bg">欢迎使用</li>
                        <li class="rec-bg"></li>
                        <li class="bt-bg">检查配置</li>
                        <li class="rec-bg"></li>
                        <li class="bt-bg">数据库配置</li>
                        <li class="rec-bg"></li>
                        <li class="bt-ac">安装状态监测</li>
                        <li class="rec-bg"></li>
                        <li class="bt-bg">用户设置</li>
                        <li class="rec-bg"></li>
                        <li class="bt-bg">安装成功!</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
    <?php runquery($data['sql']); ?>
    <?php
    runquery($data['sql2'],'n$$',FALSE);
    ?>
</html>
