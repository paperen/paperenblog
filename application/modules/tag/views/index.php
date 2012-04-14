<?php $this->load->module( 'header/common', array( 'tag', '博客所有标签' ) ); ?>
<div class="span9 main">

	<div class="tag">
		<div class="row-fluid">
			<?php if( count($tag_data) ) { ?>
			<h3>标签云 <?php echo count( $tag_data ); ?></h3>
			<hr>
			<?php foreach( $tag_data as $single ) { ?>
			<a href="<?php echo tag_url( $single['tag'] ); ?>" rel="tag" style="font-size:<?php echo rand(14,35); ?>px;"><?php echo $single['tag']; ?></a>
			<?php } ?>
			<?php } else { ?>
			<div class="alert alert-block alert-info alert-empty">
				<h3 class="alert-heading">親~博客目前没有任何标签喔~</h3>
			</div>
			<?php } ?>
		</div>
	</div>

</div>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>