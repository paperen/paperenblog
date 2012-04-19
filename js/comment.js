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
			beforeSend: function() {
				if ( $('#comment-alert').length ) $('#comment-alert').remove();
				disabled_comment_form( true );
			},
			success: function( msg ){
				//alert( msg );
				if ( msg.success ) {
					// success
					$('#comment-num').html( parseInt( $('#comment-num').html() ) + 1 ).effect('highlight', {}, 2500);
					$('#comment-empty').hide();
					$('#comment-form #content').val('');
					setTimeout(function(){
						disabled_comment_form( false );
						$('#comment-alert').fadeOut();
					}, 4000);
				} else {
					// error
					disabled_comment_form( false );
				}
				$('#comment-form').before( msg.data );
			}
		});
	});
});
function disabled_comment_form( ac ) {
	if ( ac ) {
		$('#comment-form :text').attr('disabled', true);
		$('#comment-form #content').attr('disabled', true);
		$('#comment-form #comment-submit-btn').attr('disabled', true);
	} else {
		$('#comment-form :text').removeAttr('disabled');
		$('#comment-form #content').removeAttr('disabled');
		$('#comment-form #comment-submit-btn').removeAttr('disabled');
	}
}