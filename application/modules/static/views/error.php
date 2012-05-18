<?php $this->load->module( 'header/common', array( 'archive', '抱歉，站點發生了錯誤' ) ); ?>
<div class="span9 main">

	<div class="error">
		<div class="alert alert-block margin-right15">
			<h3>抱歉，站點發生了錯誤</h3>
			<p><a href="<?php echo base_url(); ?>">返回主頁</a></p>
		</div>
		<p class="qtm" title="……"></p>
	</div>

</div>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>