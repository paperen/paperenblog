<?php if( isset( $is_archive ) && $is_archive ) { ?>
	<?php $this->load->module( 'header/common', array( 'archive', '歸檔' ) ); ?>
<?php } else { ?>
	<?php $this->load->module( 'header/common', array( 'home', '首頁' ) ); ?>
<?php } ?>
<div class="span9 main">

<?php if( isset( $by_category ) && $by_category ) { ?>
	<h3><?php echo $by_category; ?> 類別下的文章 <a href="<?php echo archive_category_url(); ?>" class="btn btn-primary btn-small pull-right margin-right15"><i class="icon-white icon-th-list"></i> 文章類別歸檔</a></h3>
	<hr>
<?php } else if( isset( $by_tag ) && $by_tag ) { ?>
	<h3>涉及 <?php echo $by_tag; ?> 标签的文章 <a href="<?php echo base_url('tag'); ?>" class="btn btn-primary btn-small pull-right margin-right15"><i class="icon-white icon-tag"></i> 所有标签</a></h3>
	<hr>
<?php } else if( isset( $by_time ) && $by_time ) { ?>
	<h3><?php echo $by_time; ?> 所有文章 <a href="<?php echo archive_url(); ?>" class="btn btn-primary btn-small pull-right margin-right15"><i class="icon-white icon-time"></i> 文章年份歸檔</a></h3>
	<hr>
<?php } else if( isset( $by_author ) && $by_author ) { ?>
    <div class="author-archive alert alert-info">
        <div class="span1 thumbnail">
            <img src="<?php echo gravatar_url( $author_data['email'] );?>" alt="<?php echo $author_data['name']; ?>">
        </div>
        <div class="span6">
            <h3>Hi，我是<?php echo $author_data['name']; ?> 以下是我发表的文章 <p>共<strong><?php echo $total; ?></strong>篇<p></h3>
        </div>
        <div class="c"></div>
    </div>
	<hr>
<?php } ?>

<?php if( isset( $posts_data ) && $posts_data ) { ?>
    <?php $this->load->module('post/common/display_bar', array()); ?>
    <div class="row-fluid">
<?php
if( isset( $display ) && $display == 'row' ) {
	foreach( $posts_data as $post ) {
?>
	<div class="post post-fragment post-row">
		<div class="row-fluid">
		    <?php $this->load->module('post/common/meta', array( $post, 'span2' )); ?>
		    <div class="post-entry">
				<h2><a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
				<div class="post-content"><?php echo get_post_fragment( $post['content'] ); ?></div>
		    </div>
		    <a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="btn btn-primary btn-more pull-right" rel="bookmark">阅读更多 <i class="icon-white icon-share-alt"></i></a>
		</div>
	</div>
<?php
	}
} else {
	foreach( $posts_data as $posts_col ) {
?>
	<div class="span6 col">
	<?php foreach( $posts_col as $post ) { ?>
	    <div class="post post-fragment">
		<div class="row-fluid">
		    <?php $this->load->module('post/common/meta', array( $post, 'span4' )); ?>
		    <div class="span8 post-entry">
				<h2><a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="post-title"><?php echo $post['title']; ?></a></h2>
				<div class="post-content"><?php echo get_post_fragment( $post['content'] ); ?></div>
		    </div>
		    <a href="<?php echo post_permalink( $post['urltitle'] ); ?>" class="btn btn-primary btn-more pull-right" rel="bookmark">阅读更多 <i class="icon-white icon-share-alt"></i></a>
		</div>
	    </div>
	<?php } ?>
	</div>
<?php
	}
}
?>
		<div class="c"></div>
		<?php echo $pagination; ?>
    </div>
	<script>
	$('.post-content .post-image').slimbox();
	</script>
<?php } else { ?>
	<div class="alert alert-block alert-error alert-empty">
		<h3 class="alert-heading">親，沒有任何文章喔</h3>
	</div>
<?php } ?>
</div>

<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>