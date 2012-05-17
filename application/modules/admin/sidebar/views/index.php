<!-- sidebar -->
<div class="span2 sidebar">
	<div class="menu">
		<ul class="nav nav-tabs nav-stacked">
			<li class="header"><a href="<?php echo base_url('manage'); ?>"><i class="icon-home"></i>管理面板</a></li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="header"><a href="javascript:void(0);"><i class="icon-file"></i>文章管理</a></li>
			<li>
				<ul class="nav nav-tabs nav-stacked nav-sub">
					<li><a href="<?php echo base_url('add_post'); ?>">發表新文章</a></li>
					<li><a href="<?php echo base_url('my_post'); ?>">我的文章</a></li>
					<li><a href="<?php echo base_url('my_category'); ?>">文章類別</a></li>
				</ul>
			</li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="header"><a href="javascript:void(0);"><i class="icon-inbox"></i>附件管理</a></li>
			<li>
				<ul class="nav nav-tabs nav-stacked nav-sub">
					<li><a href="<?php echo base_url('my_category'); ?>">我傳的附件</a></li>
				</ul>
			</li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="header"><a href="javascript:void(0);"><i class="icon-heart"></i>友鏈管理</a></li>
			<li>
				<ul class="nav nav-tabs nav-stacked nav-sub">
					<li><a href="<?php echo base_url('add_link'); ?>">添加友鏈</a></li>
					<li><a href="<?php echo base_url('show_link'); ?>">友鏈列表</a></li>
				</ul>
			</li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="header"><a href="javascript:void(0);"><i class="icon-user"></i>用戶管理</a></li>
			<li>
				<ul class="nav nav-tabs nav-stacked nav-sub">
					<li><a href="<?php echo base_url('add_user'); ?>">添加用戶</a></li>
					<li><a href="<?php echo base_url('show_user'); ?>">用戶列表</a></li>
				</ul>
			</li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="header"><a href="javascript:void(0);"><i class="icon-cog"></i>設置</a></li>
			<li>
				<ul class="nav nav-tabs nav-stacked nav-sub">
					<li><a href="#">基本設置</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<!-- sidebar -->