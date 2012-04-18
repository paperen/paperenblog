<?php $this->load->module( 'header/common', array( 'archive', '抱歉，資源不存在' ) ); ?>
<div class="span9 main">

	<div class="404">
		<div class="alert alert-block margin-right15">
			<h3>抱歉，該資源或連接已經不存在</h3>
			<p><a href="<?php echo base_url(); ?>">返回主頁</a></p>
		</div>
		<p class="qtm" title="……"></p>
	</div>

</div>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>