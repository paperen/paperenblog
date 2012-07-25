<?php $this->load->module( 'header/common', array( 'about', '关于博客' ) ); ?>
<div class="span9 main">

	<div class="about">
		<h2><i class="icon-bookmark"></i>关于<?php echo config_item('sitename'); ?></h2>
        <hr>
        <div class="about-con"><?php echo $about; ?></div>
	</div>

</div>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>