<?php $this->load->module( 'header/common', array( 'author', "关于{$author_data['name']}" ) ); ?>
<div class="span9 main">

	<div class="author">
		<?php if( isset( $err ) && $err ) { ?>
		<div class="alert alert-error">
			<h3 class="alert-heading"><?php echo $err; ?></h3>
		</div>
		<?php } else { ?>
		<div class="row-fluid">
			<div class="span2">
				<div class="thumbnail"><img src="<?php echo gravatar_url( $author_data['email'], '150' );?>" alt="<?php echo $author_data['name']; ?>" /></div>
				<ul class="vote">
					<li>文章数 <strong><a href="<?php echo archive_author_url( $author_data['name'] ); ?>"><?php echo $author_data['postnum']; ?></a></strong></li>
				</ul>
			</div>
			<div class="span10 about-entry">
				<h2>Paperen <i class="icon-user"></i></h2>
				<div class="span3">
					<h3>職業</h3>
					<ul>
						<li><?php echo $author_data['data']['job']; ?></li>
					</ul>
				</div>
				<div class="span4">
					<h3>聯繫我</h3>
					<p><?php echo str_replace('@', 'X', $author_data['email']); ?> <small>[X=@]</small></p>
				</div>
				<?php if( !empty( $author_data['data']['socialname'] ) ) { ?>
				<div class="span3">
					<h3>出沒領域</h3>
					<ul>
						<?php foreach( $author_data['data']['socialname'] as $name => $url ) { ?>
								<li><a href="<?php echo $url; ?>" rel="nofollow" target="_blank"><?php echo $name; ?></a></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
				<?php if( $author_data['data']['content'] ) { ?>
				<div class="span11">
					<h3>自我介紹</h3>
					<?php echo $author_data['data']['content']; ?>
				</div>
				<?php } ?>
			</div>
			<div class="c"></div>

		</div>
		<?php $this->load->module('post/common/author_post', array( $author_data['id'] ) ); ?>
		<?php } ?>
	</div>

</div>
<script>
$(function(){
	prettyPrint();
});
</script>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>