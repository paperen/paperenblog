var save_timeout, save_formid, save_url, save_tipid;
function autosave_init( formid, tipid, url ) {
	save_formid = formid;
	save_tipid = tipid;
	save_url = url;
	$(formid + ' .input-autosave').change(function(){
		autosave_on( 2000 );
	});
}
function autosave_on( timeout ) {
	save_timeout = clearInterval( save_timeout );
	save_timeout = setTimeout( "autosave_run();", timeout );
}
function autosave_run() {
	if ( typeof( save_url ) == 'undefined' || typeof( save_formid ) == 'undefined' ) return;
	$.ajax({
		dataType: 'json',
		type: "POST",
		url: save_url,
		data: $(save_formid).serialize(),
		success: function( ret ){
			//alert( ret );
			//ret = eval('(' + ret + ')');
			if ( typeof( ret ) === 'object' ) {
				if ( ret.err != 0 ) {
					// error
					$(save_tipid).toggleClass('alert-error').html('<h4>保存草稿失敗</h4>');
				} else {
					// ok
					$(save_tipid).removeClass('alert-success').addClass('alert-success').html('<h4>保存草稿成功</h4>');
					$('#postid').val( ret.msg );
				}
				$(save_tipid).slideDown();
				setTimeout('$("'+save_tipid+'").slideUp();', 2000);
				save_timeout = clearInterval( save_timeout );
			}
		}
	});
}