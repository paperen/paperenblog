var save_timeout, save_formid, save_url;
function autosave_init( formid, url ) {
	save_formid = formid;
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
		type: "POST",
		url: save_url,
		data: $(save_formid).serialize(),
		success: function(msg){
			alert( "Data Saved: " + msg );
			save_timeout = clearInterval( save_timeout );
		}
	});
}
