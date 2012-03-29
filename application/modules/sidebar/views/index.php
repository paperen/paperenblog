<!-- sidebar -->
<div class="span3 sidebar">

    <div class="extra">
	<a href="#" class="icon-paperen icon-rss"></a>
	<div class="c"></div>
    </div>

    <div class="cmd">
	<h3>Cmd <a href="#" id="what-is-cmd" rel="popover" data-content="作為一種脫離鼠標的操作方式，簡而言之就是命令行方式進行操作" data-original-title="CMD">What?</a></h3>
	<input type="text" class="span11">
    </div>
    <script>
	$('#what-is-cmd').popover({
	    placement : 'bottom'
	});
    </script>

	<?php $this->load->module('sidebar/common/calendar', array()); ?>
	<?php $this->load->module('sidebar/common/hot_posts', array()); ?>
	<?php $this->load->module('sidebar/common/recent_comments', array()); ?>

    <!-- links -->
    <div class="links">
	<h3>链接</h3>
	<ul>
	    <li><a href="#"  rel="popover" data-content="小清新的文藝青年" data-original-title="anyonedy">anyonedy</a></li>
	    <li><a href="#" data-original-title="catman"  data-content="年輕有為的農村合作社社長">catman</a></li>
	</ul>
    </div>
    <script>
	$('.links a').popover({
	    placement : 'top'
	});
    </script>
    <!-- links -->

</div>
<!-- sidebar -->