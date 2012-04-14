<div class="pull-right btn-group margin-right15 display-style">
	<a href="javascript:void(0);" rel="column" class="btn btn-small btn-display<?php if($display == 'column'){ ?> active<?php } ?>" title="列呈現" data-original-title="列呈現"><i class="icon-th"></i></a>
	<a href="javascript:void(0);" rel="row" class="btn btn-small btn-display<?php if($display == 'row'){ ?> active<?php } ?>" title="行呈現" data-original-title="行呈現"><i class="icon-th-list"></i></a>
</div>
<script>
	$('.display-style a').tooltip({
		placement: 'right'
	});
</script>
<div class="c"></div>