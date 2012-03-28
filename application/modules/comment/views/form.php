<!-- comment-form -->
<div class="comment-form" id="comment-form">
	<div class="row-fluid">
		<form class="span12">
			<input type="hidden" name="commentid" id="commentid" value="" />

			<div class="alert alert-block alert-success">
				<a class="close">&times;</a>
				<h4 class="alert-heading"><i class="icon-ok-sign"></i> 評論成功</h4>
				<p>您的評論已經發送給paperen</p>
			</div>
			<div class="alert alert-block alert-danger">
				<a class="close">&times;</a>
				<h4 class="alert-heading"><i class="icon-remove-sign"></i> 評論失敗</h4>
				<p>郵箱格式不合法</p>
			</div>
			<div class="control-group span6">
				<label class="control-label" for="nickname">暱稱</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<input class="span9" id="nickname" name="nickname" type="text" placeholder="Nickname">
					</div>
				</div>
			</div>
			<div class="control-group span6">
				<label class="control-label" for="email">郵箱</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<input class="span9" id="email" name="email" type="text" placeholder="Email">
					</div>
				</div>
			</div>
			<div class="c"></div>
			<div class="control-group">
				<label class="control-label" for="content">說點什麼</label>
				<div class="controls">
					<textarea class="span11" name="content" id="content" rows="3"></textarea>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-submit btn-large"><i class="icon-white icon-plus"></i> 發 表</button>
			</div>
		</form>
	</div>
</div>
<script>
	$(function(){
		$('.reply-comment-btn').click(function(){
			if ( $(this).html() == '回覆TA' ) {
				//reply
				//reback the text
				$('.reply-comment-btn').html('回覆TA');
				var comment_id = $(this).attr('rel');
				var reply_comment_form;
				if( $('#replyform').length > 0 ) {
					reply_comment_form = $('#replyform').clone();
					//kill the old one
					$('#replyform').remove();
				} else {
					//clone post-commentform
					reply_comment_form = $('#comment-form').clone()
					.attr('id', 'replyform')
					.addClass('replyform');
				}
				//fill the commentID
				reply_comment_form.find('#commentid').val( comment_id.split('-')[1] );
				$('#comment-form').hide();
				$(this).html('關閉');
				$(comment_id).append( reply_comment_form );
			} else {
				//close
				//reback the text
				$('.reply-comment-btn').html('回覆TA');
				$('#replyform').remove();
				$('#comment-form').show();
			}
		});
	});
</script>
<!-- comment-form -->