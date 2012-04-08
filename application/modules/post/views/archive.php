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
	<?php foreach( $result as $year => $year_post ) { ?>
	<div class="archive archive-by-time border-top">
		<h2>
			<span class="timeline-txt"><?php echo $year; ?>年</span>
			<?php
			$total = 0;
			foreach( $year_post as $month_post ) $total += count( $month_post );
			echo $total;
			?>篇
		</h2>
		<div id="archive-<?php echo $year; ?>">
			<?php foreach( $year_post as $month => $month_post ) { ?>
			<div class="single span5">
				<div class="row-fluid">
					<div class="span3 archive-attr">
						<h3>
						<?php echo $month; ?>月<br>
						<!--<span class="total"><?php echo count( $month_post ); ?> 篇</span>-->
						</h3>
					</div>
					<div class="span8 archive-list">
						<ul class="unstyled">
							<?php foreach( $month_post as $single ) { ?>
							<li><a href="<?php echo post_permalink($single['urltitle']); ?>"><?php echo $single['title']; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="c"></div>
		</div>
	</div>
	<?php } ?>
<?php } ?>
</div>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>