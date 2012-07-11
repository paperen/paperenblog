<?php $this->load->module( 'header/common', array( 'author', '博客所有作者' ) );?>
<div class="span9 main">

	<div class="author">
		<div class="row-fluid">

			<?php foreach( $author_col_data as $k => $col_data ) { ?>
			<div class="span6">
				<?php foreach( $col_data as $single ) { ?>
				<div class="author-con">
					<div class="span2 about-attr">
						<a href="<?php echo author_url( $single['name'] );?>" class="thumbnail"><img src="<?php echo gravatar_url( $single['email'] );?>" alt="<?php echo $single['name']; ?>"></a>
						<ul class="vote">
							<li>文章数 <strong><a href="<?php echo archive_author_url( $single['name'] ); ?>"><?php echo $single['postnum']; ?></a></strong></li>
						</ul>
					</div>
					<div class="span9 about-entry">
                        <div class="alert alert-info">
						<h2><?php echo $single['name']; ?> <i class="icon-user"></i></h2>
                        </div>
						<?php if( $single['data']['job'] ) { ?>
						<div class="span5">
							<h3>職業</h3>
							<ul>
								<li><?php echo $single['data']['job']; ?></li>
							</ul>
						</div>
						<?php } ?>
						<?php if( !empty( $single['data']['socialname'] ) ) { ?>
						<div class="span5">
							<h3>出沒領域</h3>
							<ul>
								<?php foreach( $single['data']['socialname'] as $name => $url ) { ?>
								<li><a href="<?php echo $url; ?>" rel="nofollow" target="_blank"><?php echo $name; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
						<div class="span10">
							<h3>聯繫我</h3>
							<p><?php echo str_replace('@', 'X', $single['email']); ?> <small>[X=@]</small></p>
						</div>
						<?php if( $single['data']['content'] ) { ?>
						<div class="span10">
							<h3>自我介紹</h3>
							<?php echo $single['data']['content']; ?>
						</div>
						<?php } ?>
					</div>
                    <div class="c"></div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

		</div>

	</div>
</div>
<script>
$(function(){
	prettyPrint();
});
</script>
<?php $this->load->module( 'sidebar/common', array( ) );?>
<?php $this->load->module( 'footer/common', array( ) );?>