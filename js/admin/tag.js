!function( $ ) {

    var Tag = function( element, options ) {
        this.e = $(element);
        this.options = options;
		this.tags = ( $(element).val() != '' ) ? $(element).val().split( options.delimiter ) : [];
    }

    Tag.prototype = {

		// init
		init: function() {
			var self = this;
			$(this.e).data('tag', this.options);
			$(this.options.btn).bind( 'click', function(e){
                self.add( self );
            });
			self.refresh( self );
		}

		// add
		,add: function( self ) {
			var tag_input = $( self.options.input );
			var tag_text = tag_input.val();
			var tag_current = self.tags;
			if ( tag_text != '' ) {
				self.tags = self.uniquetag( tag_current.concat( tag_text.split( self.options.delimiter ) ) );
				self.refresh( self );
			}
			tag_input.val('');
		}

		// unique
		,uniquetag: function( tags ) {
			var newArr = []
			,obj = {}
			,len = tags.length;
			for(var i=0;i<len;i++) {
				if( !obj[tags[i]] && tags[i] != '' ) {
					newArr.push( tags[i] );
					obj[tags[i]] = 'new';
				}
			}
			return newArr;
		}

		// refresh
		,refresh: function( self ) {
			var tag_list = $( self.options.list )
			,tags = self.tags
			,tmp = '';
			for( var i=0;i<tags.length; i++ ) {
				tmp += '<li>' + tags[i] + ' <a href="javascript:void(0);" rel="' + tags[i] + '" title="删除此标签"><i class="icon-remove"></i></a></li>'
			}
			tag_list.html( tmp );
			tag_list.find('a').each(function(i){
				$(this).bind( 'click', function(){
					self.del( this );
				});
			});
			$(self.e).val( self.tags );
		}

		// delete tag
		,del: function( obj ) {
			var self = this
			,deltag = $(obj).attr('rel')
			,tags = self.tags
			,index;
			for( var i=0; i<tags.length; i++ ) {
				if ( tags[i] == deltag ) {
					index = i;
				}
			}
			if ( typeof( index ) !== 'undefined' ) {
				tags.splice(index, 1);
				self.tags = tags;
				self.refresh( self );
			}
		}
    }

    /* CMD PLUGIN DEFINITION */

    $.fn.tag = function(options) {
        var tag = new Tag( this, $.extend({}, options, $.fn.tag.defaults ) );
        tag.init();
    }

	/* default options */
    $.fn.tag.defaults = {
        btn: '#tag_btn',
		input: '#tag_input',
		list: '#tag_list',
		delimiter: /,|\，|\s/
    }

}( window.jQuery || window.ender );