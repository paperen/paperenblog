<?php $this->load->module('admin/header/common/index'); ?>
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
<?php $this->load->module('admin/footer/common/index'); ?>