<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>页面基本布局</title>
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>theme/paperen/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>theme/paperen/admin.css" rel="stylesheet">
    
	<script src="<?php echo base_url(); ?>js/jquery-1.7.1.min.js"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap/bootstrap-fold.js"></script>
	
  </head>

  <body>

	<!-- header -->
    <div class="header navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="#">Admin Platform</a>
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i>个人设置</a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="icon-lock"></i>密码设置</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="icon-exclamation-sign"></i>登陆记录</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-off"></i>注销</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- header -->
    

    <div class="container-fluid">
    	<!-- row-fluid -->
    	<div class="row-fluid wrap">
		
			<!-- main -->
			<div class="main span10">

				<div class="container-fluid">
					<h3>Admin</h3>
				</div>

			</div>
			<!-- main -->
			<!-- sidebar -->
			<div class="span2 sidebar">
				<div class="menu">
					
					<ul class="nav nav-tabs nav-stacked">
						<li class="header"><a href="javascript:void(0);" target="mainFrame"><i class="icon-inbox"></i>设备<span class="nav-sub-toggle" data-target="#instrument-subnav"></span></a></li>
						<li>
							<ul class="nav nav-tabs nav-stacked nav-sub" id="instrument-subnav">
								<li><a href="http://200.200.200.117/ocean/web/module/instrument/add/index" target="mainFrame">添加设备</a></li>
								<li><a href="http://200.200.200.117/ocean/web/module/instrument/main/index" target="mainFrame">设备列表</a></li>
							</ul>
						</li>
					</ul>
					
				</div>
			</div>
			<!-- sidebar -->

		</div>
		<!-- row-fluid -->
	</div>

</body>
</html>