<?php $this->load->module( 'header/common', array( 'archive', '文章歸檔' ) ); ?>
<div class="span9 main">
<?php if ( $result == NULL ) { ?>
	<h2>暫沒有任何文章</h2>
<?php } else { ?>
	<div class="btn-group pull-right margin-right15">
		<a href="<?php echo archive_url(); ?>" class="btn btn-small<?php if( $order_by == 'time' ){ ?> active<?php } ?>"><i class="icon-time"></i> 按年份</a>
		<a href="<?php echo archive_category_url(); ?>" class="btn btn-small<?php if( $order_by == 'category' ){ ?> active<?php } ?>"><i class="icon-th-list"></i> 按類別</a>
	</div>
	<div class="c"></div>
	<div class="row-fulid margin-top15">
		<?php foreach( $result as $category => $posts ) { ?>
		<div class="archive archive-by-time border-top span5">
			<h2>
				<span class="timeline-txt"><?php echo $category; ?></span>
				<?php echo count( $posts ); ?>篇
			</h2>
			<div class="single">
				<ul class="unstyled">
					<?php foreach( $posts as $single ) { ?>
					<li><a href="<?php echo post_permalink($single['urltitle']); ?>"><?php echo $single['title']; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
	</div>
<?php } ?>
</div>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>