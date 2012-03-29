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

	<?php $this->load->module('calendar/common/locus', array()); ?>
	<?php $this->load->module('post/common/hot', array()); ?>
	<?php $this->load->module('comment/common/recent', array()); ?>
	<?php $this->load->module('link/common/all', array()); ?>
</div>
<!-- sidebar -->