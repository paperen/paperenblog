// 僅在評論表單引用此JS
// @version 0.1
// @author paperen
$(function(){
	var comment_form = $('#comment-form');
	var comment_div = $('#comment-div');
	$('.reply-comment-btn').click(function(){
		var comment_id = $(this).attr('rel');
		if ( $(this).html() == '回覆TA' ) {
			//reply
			//fill the pid
			comment_form.find('#pid').val( comment_id.split('-')[1] );
			comment_form.appendTo( comment_id );
			$(this).html('關閉');
		} else {
			//close
			//reback the text
			comment_form.find('#pid').val( '' );
			$('.reply-comment-btn').html('回覆TA');
			comment_form.appendTo( comment_div );
		}
	});
	// 發表評論按鈕
	$('#comment-submit-btn').click(function(){
		$.ajax({
			dataType: 'json',
			type: "POST",
			url: comment_url,
			data: $('#comment-form').serialize(),
			success: function( msg ){
				if ( msg.success ) {
					// success
					setTimeout(function(){
						$('#comment-alert').fadeOut();
					}, 4000);
				} else {
					// error
					if ( $('#comment-alert').length ) $('#comment-alert').remove();
				}
				$('#comment-form').prepend( msg.data );
			}
		});
	});
});