/* ============================================================
 * bootstrap-fold.js v1.0.0
 * http://twitter.github.com/bootstrap/javascript.html#dropdowns
 * ============================================================ */
!function( $ ){

	"use strict"

	var foldattr = '[data-target]',
	Fold = function( el, options ) {
		$(el).on('click', foldattr, options, this.toggle);
	}

	Fold.prototype = {
		constructor: Fold,
		toggle: function ( e ) {
			var $this = $(this)
			, selector = $this.attr('data-target'),
			options = e.data;
			if ( typeof selector == 'undefined' ) return false;
			if ( typeof options == 'object' ) {
				$(selector).slideToggle( options.timer, typeof options.callback == 'function' && options.callback );
			} else {
				$(selector).slideToggle();
			}
			e.preventDefault();
		}
	};
	
	$.fn.fold = function( option ) {
		return this.each(function(){
			var $this = $(this),
			data = $this.data('fold'),
			options = $.extend({}, $.fn.fold.defaults, typeof option == 'object' && option)
      		if (!data) $this.data('fold', (data = new Fold(this, options)))
		});
	}
	
	$.fn.fold.defaults = {
		timer : 200,
		callback : false
	};
	
	$.fn.fold.Constructor = Fold;

$(function () {
	$('body').on('click.fold.data-api', '[data-target]', Fold.prototype.toggle)
})

}( window.jQuery )