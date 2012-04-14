// 作為前臺使用的JS
$(document).ready(function(){
	$('.btn-display').click(function(){
		$.get(base_url + $(this).attr('rel'), {}, function(){
			window.location.reload();
		});
	});
});