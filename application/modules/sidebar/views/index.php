<!-- sidebar -->
<div class="span3 sidebar">

    <div class="extra">
	<a href="#" class="icon-paperen icon-rss"></a>
	<div class="c"></div>
    </div>
	<!-- cmd -->
    <div class="cmd">
	<h3>Cmd <a href="#" id="what-is-cmd" rel="popover" data-content="作為一種脫離鼠標的操作方式，簡而言之就是命令行方式進行操作" data-original-title="CMD">What?</a></h3>
	<input type="text" class="span11">
    </div>
    <script>
	$('#what-is-cmd').popover({
	    placement : 'bottom'
	});
    </script>
	<!-- cmd -->
	<div class="post-image">
		<h3>附帶圖片 <small>5</small></h3>
		<div class="row-fluid">
			<ul class="thumbnails span3 col">
				<li class="single">
				<a href="#" class="thumbnail" title="查看大圖" data-original-title="查看大圖"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_2.jpg" alt=""></a>
				</li>
				<li class="single">
				<a href="#" class="thumbnail" title="查看大圖" data-original-title="查看大圖"><img src="http://localhost/ci-paperen/theme/paperen/image/large.jpg" alt=""></a>
				</li>
				<li class="single">
				<a href="#" class="thumbnail" title="查看大圖" data-original-title="查看大圖"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_3.jpg" alt=""></a>
				</li>
			</ul>
			<ul class="thumbnails span3 col">
				<li class="single">
				<a href="#" class="thumbnail" title="查看大圖" data-original-title="查看大圖"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_3.jpg" alt=""></a>
				</li>
				<li class="single">
				<a href="#" class="thumbnail" title="查看大圖" data-original-title="查看大圖"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_2.jpg" alt=""></a>
				</li>
			</ul>
			<ul class="thumbnails span3 col">
				<li class="single">
				<a href="#" class="thumbnail" title="查看大圖" data-original-title="查看大圖"><img src="http://localhost/ci-paperen/theme/paperen/image/180x180_1.jpg" alt=""></a>
				</li>
			</ul>
		</div>
		<script>
		$('.post-image .thumbnail').tooltip({
			placement: 'top'
		});
		</script>
	</div>

	<?php $this->load->module('calendar/common/locus', array()); ?>
	<?php $this->load->module('post/common/hot', array()); ?>
	<?php $this->load->module('comment/common/recent', array()); ?>
	<?php $this->load->module('link/common/all', array()); ?>
</div>
<!-- sidebar -->