;(function($, window, document, undefined){
	'use strict';

	$.pwcMediaInsert = function( el, options ){
		this.el = el;
		this.$el = $(el);
		this.selectedItems = '';
		this.selectedImg = '';
		this.init( options );
	};
	
	$.pwcMediaInsert.prototype = {

		//Default Options
		defaults : {
			title: 'Select Files',
			fileType: undefined, //video, audio, image
			imgview: 'none',
			addToPreview: false
		},

		init: function( options ) {
			var self = this,
				title = self.$el.data('title'),
				fileType = self.$el.data('file-type'),
				imgview = self.$el.data('img-view'),
				addToPreview = self.$el.data('add-to-preview');

			//Merge options
			self.options = $.extend({}, self.defaults, options);
			
			//Check data attributes set and override current options.
			self.options.title 			= (title != undefined) 	? title 		: self.options.title;
			self.options.fileType 		= (fileType != undefined && (fileType=="image" || fileType=="video" || fileType=="audio")) ? fileType : self.options.fileType;
			self.options.imgview 			= (imgview != undefined) ? imgview 	: self.options.imgview;
			self.options.addToPreview   = (addToPreview != undefined) ? true 	: self.options.addToPreview;
			
			self.openMedia();
		},

		openMedia: function(){
			var pix_file_frame, attachment, self = this, container = this.$el.prev('.selected-con');
			
			//Upload Image
			this.$el.on('click',function(e) {

				e.preventDefault();
				e.stopPropagation();

				// If the media frame already exists, reopen it.
				var btn = $(this), uploaderBtnText = uploaderTitle = self.options.title;
				if ( pix_file_frame ) {
					pix_file_frame.open();
					return;
				}
				var uploaderTitle = (uploaderTitle) ? uploaderTitle : 'Insert Media',
					uploaderBtnText = (uploaderBtnText) ? uploaderBtnText : 'Select';
					
				// Create the media frame.
				pix_file_frame = wp.media.frames.pix_file_frame = wp.media({
					title: uploaderTitle,
					button: {
						text: uploaderBtnText,
					},
				  	multiple: false,  // Set to true to allow multiple files to be selected
				  	library: {
				  		type: self.options.fileType
					},
				});

				pix_file_frame.on('open',function() {
					var selection = pix_file_frame.state().get('selection'), 
						selectedImg = self.$el.parent().find('.pix-saved-val').val();

					//console.log( selectedImg );

					//console.log( self.$el.parent() );

					if( selectedImg ) {
						var selImg = wp.media.attachment( selectedImg );
						selection.add( selImg ? selImg : '' );
					} else {
						selection.add('');
					}

				});

				// When an image is selected, run a callback.
				pix_file_frame.on( 'select', function() {

					var selection = pix_file_frame.state().get('selection'),
						selectedItems, attachment;

					container.html('');
					self.selectedItems = '';

					attachment = pix_file_frame.state().get('selection').first().toJSON();
					self.selectedImg = '';
					self.selectedImg = attachment.id;

					//save and insert html
					self.saveData(attachment);
					
				});

				// Finally, open the modal
				pix_file_frame.open();				

				e.preventDefault();
			});
		},

		saveData:function( attachment ){
			var self= this,
				$con = self.$el.prev('.selected-con'),
				options = {};

			// console.log(attachment);

			$con.prev('input').val( self.selectedImg ).change();

			self.selectedItems = '';

			options.$btn = self.$el; //current btn
				options.imgview = self.options.imgview; // imgview = front, lat, pres, up
				options.imgid = self.selectedImg;
				options.imgurl = attachment.url;

			if( self.options.addToPreview ) {

				options.width = attachment.width;
				options.height = attachment.height;

				$('#pwc-preview').trigger('preview:imageAdded', options );

			}

			self.$el.trigger('preview:imageAdded', options );
			
		},

	};

	$.fn.pwcMediaInsert = function(options){
		
		return this.each(function() {
			// prevent multiple instantiation
			if (!$(this).data('pwcmediainsert'))
				$(this).data('pwcmediainsert', new $.pwcMediaInsert(this, options));
		});
	};

})(jQuery, window, document);

jQuery(document).ready(function($) {
	'use strict';

	$( '.select-image' ).pwcMediaInsert();

});