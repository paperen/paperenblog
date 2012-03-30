<div class="span9 main">

	<div class="post">
		<div class="row-fluid">
			<?php $this->load->module('post/common/meta', array($post, 'span2', TRUE)); ?>
			<div class="span10 post-entry">
				<h2><?php echo $post['title']; ?></h2>
				<div class="post-content"><?php echo $post['content']; ?></div>
			</div>
			<div class="c"></div>
			<?php $this->load->module( 'comment/common/index', array( $post['id'] ) );?>
		</div>
	</div>

</div>