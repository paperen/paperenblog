<!-- sidebar -->
<div class="span3 sidebar">

    <div class="extra">
	<a href="<?php echo rss_url(); ?>" class="icon-paperen icon-rss" rel="rss" title="RSS源"></a>
	<div class="c"></div>
    </div>
	<!-- cmd -->
    <!--<div class="cmd">
	<h3>Cmd <a href="#" id="what-is-cmd" rel="popover" data-content="作為一種脫離鼠標的操作方式，簡而言之就是命令行方式進行操作" data-original-title="CMD">What?</a></h3>
	<input type="text" class="span11" value="該功能暫未完成，請熱切期待中" disabled="true">
    </div>-->
    <script>
	$('#what-is-cmd').popover({
	    placement : 'bottom'
	});
    </script>
	<!-- cmd -->

	<?php $this->hooks->_call_hook('post_images'); ?>

	<?php $this->load->module('calendar/common/locus', array()); ?>
	<?php $this->load->module('post/common/hot', array()); ?>
	<?php //$this->load->module('comment/common/recent', array()); ?>
	<?php $this->load->module('link/common/all', array()); ?>
</div>
<!-- sidebar -->