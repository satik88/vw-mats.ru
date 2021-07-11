(function($){

	'use strict';

	// Create Base64 Object
	var pwc = {},

	encodeStr = function ( str ) {

		// Encode the String
		return $.base64.encode(str);

	},

	decodeStr = function ( encodedString ) {

		// Decode the String
		return $.base64.decode(encodedString);
	},

	updateQueryStringParameter = function ( uri, key, value ) {
		var re = new RegExp("([?&])" + key + "=.*?(&|#|$)", "i");
		if( value === undefined ) {
			if (uri.match(re)) {
				return uri.replace(re, '$1$2');
			} else {
				return uri;
			}
		} else {
			if ( uri && uri.match(re) ) {
				return uri.replace(re, '$1' + key + "=" + value + '$2');
			} else {
				var separator = uri && uri.indexOf('?') !== -1 ? "&" : "?";    
				return uri + separator + key + "=" + value;
			}
		}  
	},

	showOptions = function( $parent ) {

		$parent.parent().removeClass('hover-hide');

		$parent.find('li').each(function(i) {		    
			var $self = $(this);

			setTimeout(function(){
				$self.removeClass('fadeOutUp').addClass('animated fadeInUp');
			}, i * 100);

		});

	},

	hideOptions = function( $parent ) {

		$parent.find('li').each(function(i) {
			var $self = $(this);

			setTimeout(function(){
				$self.removeClass('fadeInUp').addClass('animated fadeOutUp');
			}, i * 100);

		});

		$parent.parent().addClass('hover-hide');

	},

	applyResponsive = function() {
		
		pwc.$config = $('.pwc-configurator-view');

		pwc.maxWidth  = pwc.entireWidth  - pwc.minLeft;
		pwc.maxHeight = pwc.entireHeight - pwc.minTop;

		var pageHeight = pwc.$config.height() - 104,
			pageWidth  = pwc.$config.width() - 80,
			scaleX = 1,
			scaleY = 1,
			scale = 1;

		if( pageWidth < pwc.maxWidth || (pageHeight < pwc.maxHeight && $(window).width() < 1680 ) ) {

			scaleX = pageWidth / pwc.maxWidth;
			scaleY = pageHeight / pwc.maxHeight;

			scale = ( scaleX > scaleY ) ? scaleY : scaleX;

			pwc.$config.find('.pwc-preview-inner').css('transform', 'scale(' + scale + ')');

		} else {
			pwc.$config.find('.pwc-preview-inner').css('transform', 'scale(1)');
		}

	},

	loadPreview = function( init, $preview ) {

		// console.log( 'loading' );

		var $preview = ('undefined' == typeof($preview) ) ?  $('.pwc-configurator') : $preview,
			// current active image view on preview
			curImageView  = 'front',
			// preview width and height
			previewWidth = $preview.width(),
			previewHeight = $preview.height();

		// 1. now apply left and top, depends on aligment and set data-init-x and data-init-y
		var $previewImagesCon = $preview.find('.subset');

		$previewImagesCon.each(function(index, el) {

			var $activeImage = $(this),
				x       = $activeImage.data('align-h'),
				y       = $activeImage.data('align-v'),
				z       = $activeImage.data('z-index'),
				offsetX = parseFloat( $activeImage.data('offset-x'), 10 ),
				offsetY = parseFloat( $activeImage.data('offset-y'), 10 ),
				width   = parseFloat( $(this).data('width'), 10 ),
				height  = parseFloat( $(this).data('height'), 10 ),
				valX, valY, entireWidth = 0, entireHeight = 0;

			/*pwc.maxWidth  = pwc.maxWidth  > width  ? pwc.maxWidth  : width;
			pwc.maxHeight = pwc.maxHeight > height ? pwc.maxHeight : height;*/

			$(this).css('position', 'absolute');

			// change image postion & set x and y
			if ( 'left' == x ) {

				valX = 0;
				$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

			} else if ( 'right' == x ) {

				valX = previewWidth - width;
				$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

			} else if ( 'center' == x ) {

				valX = ( previewWidth - width ) / 2;
				$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

			} 

			if ( 'middle' == y ) {
				valY = ( ( previewHeight - height ) / 2 );
				$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });
				
			} else if ( 'top' == y ) {

				valY = 0;
				$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });

			} else if ( 'bottom' == y ) {

				valY = previewHeight - height;
				$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });

			}

			$activeImage.css({'z-index': z });

			$activeImage.attr( { 'data-init-x': valX, 'data-init-y': valY } );			

			/* on calulate on load */
			if( init ) {
				pwc.minLeft = pwc.minLeft < ( valX + offsetX ) ? pwc.minLeft : valX + offsetX;
				pwc.minTop  = pwc.minTop  < ( valY + offsetY ) ? pwc.minTop  : valY + offsetY;

				pwc.$minLeft = pwc.$minLeft < ( valX + offsetX ) ? pwc.$minLeft : $(this);

				entireWidth = valX + offsetX + width;
				entireHeight = valY + offsetY + height;

				pwc.entireWidth  = pwc.entireWidth  > entireWidth  ? pwc.entireWidth  : entireWidth;
				pwc.entireHeight = pwc.entireHeight > entireHeight ? pwc.entireHeight : entireHeight;
			}

		});

		$preview.find('.pwc-preview-inner').removeClass('loading');

		var $hotspot = $preview.find('.pwc-hotspot');

		$hotspot.each(function(index, el) {

			var $activeImage = $(this),
				x       = $activeImage.data('align-h'),
				y       = $activeImage.data('align-v'),
				offsetX = parseFloat( $activeImage.data('offset-x'), 10 ),
				offsetY = parseFloat( $activeImage.data('offset-y'), 10 ),
				width   = $(this).width(),
				height  = $(this).height(),
				valX, valY, entireWidth, entireHeight;

			$(this).css('position', 'absolute');

			// change image postion & set x and y
			if ( 'left' == x ) {

				valX = 0;
				$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

			} else if ( 'right' == x ) {

				valX = previewWidth - width;
				$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

			} else if ( 'center' == x ) {

				valX = ( previewWidth - width ) / 2;
				$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

			} 

			if ( 'middle' == y ) {
				valY = ( ( previewHeight - height ) / 2 );
				$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });
				
			} else if ( 'top' == y ) {

				valY = 0;
				$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });

			} else if ( 'bottom' == y ) {

				valY = previewHeight - height;
				$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });

			}

			$activeImage.data( 'init-x', valX ).data( 'init-y', valY );

		});

		$('.pwc-hotspot').on( 'click', function() {

			var id = $(this).data('hotspot-uid');

			$('ul[data-parent-uid="' + id + '"]').prev('.pwc-layer-title').trigger('click');

		});

	},

	changePreview = function( $activeImage, obj ) {

		var $preview = $('.pwc-configurator'),
			// preview width and height
			previewWidth = $preview.width(),
			previewHeight = $preview.height(),
			// Get values and assign it to the variables
			x       = obj.align_h,
			y       = obj.align_v,
			z       = obj.z_index,
			offsetX = parseFloat( obj.pos_x.replace('px', '') ),
			offsetY = parseFloat( obj.pos_y.replace('px', '') ),
			width   = parseFloat( obj.width.replace('px', '') ),
			height  = parseFloat( obj.height.replace('px', '') ),
			valX, valY, entireWidth = 0, entireHeight = 0;

		$activeImage.css('position', 'absolute');

		/*pwc.maxWidth  = pwc.maxWidth  > width  ? pwc.maxWidth  : width;
		pwc.maxHeight = pwc.maxHeight > height ? pwc.maxHeight : height;*/

		// change image postion & set x and y
		if ( 'left' == x ) {

			valX = 0;
			$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

		} else if ( 'right' == x ) {

			valX = previewWidth - width;
			$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

		} else if ( 'center' == x ) {

			valX = ( previewWidth - width ) / 2;
			$activeImage.css({'left': ( valX + offsetX ) + 'px', 'right': 'auto' });

		} 

		if ( 'middle' == y ) {
			valY = ( ( previewHeight - height ) / 2 );
			$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });
			
		} else if ( 'top' == y ) {

			valY = 0;
			$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });

		} else if ( 'bottom' == y ) {

			valY = previewHeight - height;
			$activeImage.css({'top': ( valY + offsetY ) + 'px', 'bottom': 'auto' });

		}

		$activeImage.css({'z-index': z });

		$activeImage.data( 'init-x', valX ).data( 'init-y', valY );

		pwc.minLeft = ( pwc.minLeft < ( valX + offsetX ) ) ? pwc.minLeft : valX + offsetX;
		pwc.minTop  = ( pwc.minTop  < ( valY + offsetY ) ) ? pwc.minTop  : valY + offsetY;

		entireWidth = valX + offsetX + width;
		entireHeight = valY + offsetY + height;

		pwc.entireWidth  = pwc.entireWidth  > entireWidth  ? pwc.entireWidth  : entireWidth;
		pwc.entireHeight = pwc.entireHeight > entireHeight ? pwc.entireHeight : entireHeight;

		// uncomment this for plugin
		applyResponsive();

	},

	calculateTotalPrice = function () {

		var $wrap = $('.pwc-config-price');

		$wrap.each(function(index, el) {

			var $totalCart,
			tPrice = 0,
			newPrice;

			$(this).find('.total-price p span.value').each(function(index, el) {
				newPrice = parseFloat($(this).data('price'));
				tPrice += newPrice;
			});

			// Append total price value
			$totalCart = $(this).find('.calculation');

			// That function directly loads from WooCommerce
			tPrice = accounting.formatMoney( tPrice, lib.symbol, lib.precision, lib.thousand, lib.decimal, lib.format );

			$totalCart.html( tPrice );

		});
		
	},

	applyCurrencyPos = function( price ) {

		var symbol = pwc_plugin.currency_symbol,
			position = pwc_plugin.currency_position;

		if( 'left' == position ) {
			return symbol+price;
		}
		else if( 'right' == position ) {
			return price+symbol;
		}
		else if( 'left_space' == position ) {
			return symbol+' '+price;
		}
		else if( 'right_space' == position ) {
			return price+' '+symbol;
		}
	},

	appendPriceList = function( $self ) {

		var $wrap = $('.pwc-config-price-'+ $self.closest('.pwc-controls-wrap').data('config-id') +' .total-price p'),
			$template,
			$cartPrice,
			$totalCart,
			multiple = $self.parent().data('multiple'), // undefined, true
			layerUid = $self.data('uid'),
			parentUid = $self.parent().data('parent-uid'),
			icon = $self.find('img').attr('src'),
			parentTitle = $self.parent().data('optionset'),
			childTitle = $self.data('text'),
			price = $self.data('price'),
			desc = $self.find('.li-desc').html();

		var uid = ( true == multiple ) ? layerUid : parentUid;

		if( 'undefined' != typeof(price) ) {
			$template = '<span id="price-list-'+uid+'" class="value" data-price="'+price+'">';
				$template += ' <span class="sign">+</span> ';
				if( 'undefined' != typeof(parentTitle) ) {
					$template += parentTitle +' ';
				}
				$template += applyCurrencyPos( price );
			$template += '</span>';
		}

		if( $('#price-list-'+uid).length == 0 ){
			$wrap.append($template);
		}
		else {
			price = $($template).data('price');
			$('#price-list-'+uid).html( $($template).html() );
			$('#price-list-'+uid).data( 'price', price );	
		}

		calculateTotalPrice();

	},

	arrUnique = function ( list ) {
	    var result = [];
	    $.each(list, function(i, e) {
	        if ($.inArray(e, result) == -1) result.push(e);
	    });
	    return result;
	},

	appendImagetoPreview = function( $self ) {

		var uid             = $self.data('uid'),
			multiple        = $self.parent().data('multiple'),
			$parent         = $self.parent('.pwc-controls-img-list'),
			parentId        = $parent.data('parent-uid'),
			$con            = $('#configurator-view-' + $self.closest('.pwc-controls-wrap').data('config-id') ),
			optionsetParent = $parent.data('optionset-parent'),
			optionset       = $(this).data('open-optionset'),
			style           = $self.data('style'),
			$previewInner   = $con.find('.pwc-preview-inner'),
			imgLength       = $previewInner.length,
			imgLoaded       = 0;

		if( ! multiple && $(this).hasClass('current')) {
			return;
		}

		$previewInner.each(function( index, el ) {

			var type = $(el).data('type'),
				$wrap = $con.find('.pwc-'+type),
				$ImgCon = $wrap.find('[data-uid="'+uid+'"]'),
				object = $self.data(type);

			if( 'undefined' != typeof( object ) ) {

				if( ! $ImgCon.length ) {
					var $newset = $('<div />', {
						class: 'subset',
						style: style,
						'data-parent-uid' : parentId,
						'data-uid' : uid,
						'data-align-h' : object.align_h,
						'data-align-v' : object.align_v,
						'data-offset-x' : object.pos_x,
						'data-offset-y' : object.pos_y,
						'data-width' : object.width,
						'data-height' : object.height,
						'data-z-index' : object.z_index
					}).html('<img src="'+ object.src +'" alt="" width="' + object.width + '" height="' + object.height + '">').appendTo($wrap);

					if( $newset.find('img').length ) {
						$newset.addClass('image-loading-con');
						$self.addClass('image-loading');
					}

					$newset.find('img').on('load', function(e) {
						e.preventDefault();
						imgLoaded++;

						// if( imgLoaded == imgLength ) {

							$newset.removeClass('image-loading-con').addClass('loaded-con');						

							// $newset.addClass('active');
							$newset.addClass('active');

							if( ! multiple ) {
								$newset.siblings('[data-parent-uid="'+parentId+'"]').removeClass('active');
							}

							$self.removeClass('image-loading').addClass('image-loaded');

						// }

					});

					/*if( ! multiple ) {
						$('.subset[data-parent-uid="'+parentId+'"]').not($newset).removeClass('active');
					}*/

					changePreview( $newset, object );

				}
				else {
					
					var $subset = $(el).find('.subset[data-uid="'+uid+'"]');
					if( ! multiple ) {
						$(el).find('.subset[data-parent-uid="'+parentId+'"]').removeClass('active');
					}

					if( multiple ) {
						$subset.toggleClass('active');
					}
					else {						
						$subset.addClass('active');
					}
				}
				
			}				
			
		});

	},

	resetComponent = function( configurator_id, str ) {

	    var $controlCon = $('.pwc-controls-wrap[data-config-id="'+ configurator_id +'"]'),
	    	$li = $controlCon.find('.pwc-controls-img-list li'),
	    	$subsets = $('#configurator-view-' + configurator_id +' .subset'),
	    	$priceCon = $('.pwc-config-price-' + configurator_id +' .total-price p'),
	    	$str = str.split(',');

	    $str = $str.filter(Boolean);
	    $str = arrUnique($str);

	    // Reset it to empty, it looks nothing clicked
	    $li.removeAttr('data-open-optionset');
	    $li.removeClass('current');
	    $subsets.removeClass('active');
	    $priceCon.find('span[id]').remove();

	    // Trigger the li depends on the reset string
	    $.each($str, function( index, value ) {	

	    	var $changeImage = $controlCon.find('.pwc-controls-img-list li[data-key="'+value+'"]'),
	    		// Get value when the trigger done that value needs to re-apply
	    		openOptionset = $changeImage.data('open-optionset');

	    	// Remove this data attr to stop bubbling the child element
	    	$changeImage.removeAttr('data-open-optionset');

	    	$changeImage.trigger('click');
	    	// Re-apply the data attr
	    	$changeImage.attr('data-open-optionset', openOptionset);

		});
	},

	setComponentsUrl = function( config_id, str ) {

		var baseUrl = '',
			href = window.location.href,
			$shareWrap = $('.product-share[data-config-id="'+config_id+'"]'),
			$facebook = $shareWrap.find('.facebook'),
			facebookUrl = $facebook.attr('href'),
			$twitter = $shareWrap.find('.twitter'),
			twitterUrl = $twitter.attr('href'),
			$gplus = $shareWrap.find('.gplus'),
			gplusUrl = $gplus.attr('href'),
			$linkedin = $shareWrap.find('.linkedin'),
			linkedinUrl = $linkedin.attr('href'),
			$pinterest = $shareWrap.find('.pinterest'),
			pinterestUrl = $pinterest.attr('href'),
			$copy = $shareWrap.find('.copy-link');

		// It build the query string properly
		if( '' != str ) {
			baseUrl = updateQueryStringParameter( href, 'key', encodeStr( str ) );
		} else {
			baseUrl = href;
		}

		// update the encoded url in share options
		$facebook.attr( 'href', facebookUrl+baseUrl );
		$twitter.attr( 'href', twitterUrl+baseUrl );
		$gplus.attr( 'href', gplusUrl+baseUrl );
		$linkedin.attr( 'href', linkedinUrl+baseUrl );
		$pinterest.attr( 'href', pinterestUrl+baseUrl );
		$copy.attr( 'href', baseUrl );

		// update the key in input field
		$('.config-cart-form-'+config_id+' input.default-active-key').val( str );

		// add the encoded key to inpiration list wrapper for creating & updating inspiration list
		$('.inspiration-wrap[data-config-id='+config_id+']').data( 'key', str );

	},

	// Save inspiration
	saveInspiration = function( self, type, values ){

		var ajaxurl = pwc_plugin.ajaxurl,
			$con = $('.inspiration-wrap[data-config-id='+ values.configurator_id +']'),
			$tabWrapper = $con.find('.tab-wrapper');

		$.ajax({
			type: 'post',
            url: ajaxurl,
            data: {
				action : 'pwc_save_inspiration',
				type : type,
				values : values
            },
			beforeSend: function(){
				$con.addClass('inspiration-loading');
			},
			complete: function() {
			},
		}).done( function( data ) {

			$con.removeClass('inspiration-loading');

			if($con.hasClass('inspiration-loading')){
				return;
			}

			var $data              = $(data),
				$tab               = $data.find('.tab-wrapper'),
				$addNewInspiration = $con.find('.add-new-inspiration'),
				$fieldGroup        = $addNewInspiration.find('.ins-field-group'),
				$insData           = $data.find('#ins-data'),
				error              = $insData.data('error'),
				successText        = $insData.find('span.success').text(),
				errorText          = $insData.find('span.error').text(),
				nameError,
				groupError;

			if( error ) {
				nameError = $insData.find('span.name-error').text();
				groupError = $insData.find('span.group-error').text();

				$con.find('.name-error').text(nameError);
				$con.find('.group-error').text(groupError);

				$con.find('.notice').fadeIn(400).text(errorText).delay(400).fadeOut(400);

			}
			else {
				$con.find('.notice').fadeIn(400).text(successText).delay(400).fadeOut(400);
				$con.find('.form-notice').fadeIn(400).text(successText).delay(400).fadeOut(400);
				$tabWrapper.html($tab);

				$('.owl-carousel-slider').owlCarousel({
					responsive: {0:{'items': 1 },768:{'items': 2 },991:{'items': 3},1199:{'items': 3 }},
				});

				if( 'add-new' == type ) {
					$con.fadeOut(0);

					$fieldGroup.find('.custom-ins-name').val('');
					$fieldGroup.find('.custom-ins-desc').val('');
					$fieldGroup.find('.custom-ins-image').val('');
				}
			}
		
		}).always( function(){
		});
	},
	
	passArrayKeyforActive = function( $self ) {

		// if multiple true pass multiple key other than pass single key to php and change that key values active to true

		var str='', $obj = {},
			multiple = $self.parent().data('multiple'),
			addUid,
			uid,
			key = $self.data('key'),
			config_id = $self.closest('.pwc-controls-wrap').data('config-id'),
			$result = {},
			activeKey,
			activeKeyArr;

		if( true == multiple ) {
			addUid = $self.data('uid');
		}
		else {
			addUid = $self.parent().data('parent-uid');
		}

		addUid = ( 'undefined' == typeof(addUid) ) ? $self.data('random') : addUid;

		activeKey = $('.config-cart-form-' + config_id + ' input.default-active-key').val();

		// activeKeyArr = activeKey.split(',');
		activeKeyArr = activeKey ? activeKey.split(',') : [];

		for ( var i in activeKeyArr ) {

			multiple = $('li[data-key="'+activeKeyArr[i]+'"]').parent().data('multiple');
			if( multiple ) {
				uid = $('li[data-key="'+activeKeyArr[i]+'"]').data('uid');
			}
			else {
				uid = $('li[data-key="'+activeKeyArr[i]+'"]').parent().data('parent-uid');
			}

			if( key != activeKeyArr[i] ) {
				$result[uid] = activeKeyArr[i];
			}
			
		}

		$result[addUid] = key;

		for ( var i in $result ) {
			str += ( '' == str ) ? $result[i] : ',' + $result[i];
		}

		$('.config-cart-form-' + config_id + ' .default-active-key').val( str );

		// Pass encoded key to the url
		setComponentsUrl( config_id, str );

	},

	deselectLayer = function( $self ) {

		var $control = $('.pwc-controls-list-sec'),
			uid = $self.data('uid'),
			$li = $control.find('[data-uid="'+ uid +'"]'),
			key = $li.data('key'),
			removeUid,
			multiple = $li.parent().data('multiple'), // undefined, true
			layerUid = $li.data('uid'),
			parentUid = $li.parent().data('parent-uid'),
			required = $li.parent().data('required'),
			config_id = $self.closest('.pwc-controls-wrap').data('config-id'),
			defaultKey,
			activeKey,
			activeKeyArr,
			$result = [],
			str = '';

		removeUid = ( true == multiple ) ? layerUid : parentUid;
		
		$control.find('li[data-uid="'+uid+'"]').removeClass('current');
		$('#price-list-'+removeUid).remove();		

		calculateTotalPrice();
		
		$('.pwc-configurator-view .subset[data-uid="'+ uid +'"]').removeClass('active');
		

		// Get active key
		activeKey = $('.config-cart-form-' + config_id + ' input.default-active-key').val();
		
		activeKeyArr = activeKey.split(',');

		for ( var i in activeKeyArr ) {

			multiple = $('[data-key="'+activeKeyArr[i]+'"]').parent().data('multiple');
			if( multiple ) {
				uid = $('[data-key="'+activeKeyArr[i]+'"]').data('uid');
			}
			else {
				uid = $('[data-key="'+activeKeyArr[i]+'"]').parent().data('parent-uid');
			}

			if( key != activeKeyArr[i] ) {
				$result[uid] = activeKeyArr[i];
			}
			
		}

		for ( var i in $result ) {
			str += ( '' == str ) ? $result[i] : ',' + $result[i];
		}

		// Pass encoded key to the url
		setComponentsUrl( config_id, str );

	};

	$(document).ready(function($) {

		$('.pwc-controls-img-list .pwc-controls-list-img').on( 'mouseover', function() {
			var $this = $(this),
				$hoverText = $this.find( '.pwc-icon-hover-text' ),
				imgWidth = ( $this.outerWidth() / 2 ) + 2,
				halfWidth = ( parseInt( $hoverText.outerWidth() ) / 2 ),
				total = - halfWidth + imgWidth;

				$hoverText.css( 'left', total );

			}
		);
								 
		calculateTotalPrice();

		/* add/change image */
		$('.pwc-controls-img-list').on('click', '[data-changeimage]', function(e) {
			e.preventDefault();

			var $self = $(this),
				multiple = $self.parent().data('multiple'),
				required;	

			if( !$self.hasClass('current') ) {
				if( multiple ) {
					$self.addClass('current');
				}
				else{

					$self.addClass('current').siblings().removeClass('current');
				}

				// Append images for preview
				appendImagetoPreview( $(this) );

				// Send the array key
				passArrayKeyforActive( $(this) );
				
				// Price list
				appendPriceList( $(this) );
			}
			else {
				required = $self.parent().data('required');
				required = ( 'undefined' == typeof( required ) ) ? false : required;

				if( multiple ) {

					if( required ) {

						if( $self.siblings('li').hasClass('current') ) {
							if( $self.hasClass('current') ) {
								deselectLayer( $(this) );
							}
							else {
								$self.addClass('current');
							}
						}
						else {
							$self.addClass('current');
						}
						
					}
					else {
						if( $self.hasClass('current') ) {
							deselectLayer( $(this) );
						}
						else {
							$self.addClass('current');
						}
					}
				}
				else {
					if( required ) {
						$self.addClass('current');
					}
					else {
						if( $self.hasClass('current') ) {
							deselectLayer( $(this) );
						}
						else {
							$self.addClass('current');
						}
					}
					
					$self.siblings('li').removeClass('current');
				}
			}

		});

		// Add Inspiration
		$('.inspiration-wrap').on('click', '.save-inspiration', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self          = $(this),
				values         = {},
				$con           = $self.parents('.inspiration-wrap'),
				$popup         = $con.find('.inspiration-form'),
				$fieldGroup    = $con.find('.add-new-inspiration .ins-field-group'),
				group          = $fieldGroup.find('.custom-ins-group').val(),
				name           = $fieldGroup.find('.custom-ins-name').val(),
				desc           = $fieldGroup.find('.custom-ins-desc').val(),
				image          = $fieldGroup.find('.custom-ins-image').val(),
				configuratorId = $con.data('config-id'),
				key            = $con.data('key'),
				type           = $self.data('type');

			values = { 'group': group, 'configurator_id': configuratorId, 'name': name, 'desc': desc, 'key': key, 'image': image };

			// Save inspiration key via ajax
			saveInspiration( $self, type, values );

		});

		// Delete Inspiration List
		$('.inspiration-wrap').on('click', '.delete-inspiration', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self          = $(this),
				$sure = confirm( 'Are you sure? Do you want to remove this inspiration? ' ),
				values,
				$con           = $self.parents('.inspiration-wrap'),
				configuratorId = $con.data('config-id'),
				value          = $self.closest('.ins-list').data('value'),
				type           = $self.data('type');

			values = { 'configurator_id': configuratorId, 'index': value.index, 'group': value.group };

			// Save inspiration key via ajax
			if( $sure ) {
				saveInspiration( $self, type, values );
			}

		});

		// Delete Inspiration Group
		$('.inspiration-wrap').on('click', '.delete-inspiration-group', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self          = $(this),
				$sure = confirm( 'Are you sure? Do you want to remove the inspiration group? ' ),
				values,
				$con           = $self.parents('.inspiration-wrap'),
				configuratorId = $con.data('config-id'),
				group          = $self.data('group'),
				groupIndex          = $self.data('group-index'),
				type           = $self.data('type');

			values = { 'configurator_id': configuratorId, 'group': group, 'group-index': groupIndex };

			// Save inspiration key via ajax
			if( $sure ) {
				saveInspiration( $self, type, values );
			}

		});

		// Reset Inspiration List
		$('.inspiration-wrap').on('click', '.reset-inspiration', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self          = $(this),
				$sure = confirm( 'Are you sure? Do you want to overwrite the inspiration? ' ),
				values,
				$con           = $self.parents('.inspiration-wrap'),
				configuratorId = $con.data('config-id'),
				key            = $con.data('key'),
				value          = $self.closest('.ins-list').data('value'),
				type           = $self.data('type');

			if( undefined != typeof(key) ) {

				values = { 'configurator_id': configuratorId, 'index': value.index, 'group': value.group, 'key': key };

				// Save inspiration key via ajax
				if( $sure ) {
					saveInspiration( $self, type, values );
				}
			}

		});

		// Tab
		$('.inspiration-wrap').on('click', 'li', function(e) {
			e.preventDefault();

			if($(this).parent().hasClass('active')){
				return;
			}

			$('.owl-carousel-slider').owlCarousel({
				responsive: {0:{'items': 1 },768:{'items': 2 },991:{'items': 3},1199:{'items': 3 }},
			});

			var anchor = $(this).data('anchor'),
				$tab = $(this).parents('.tab-wrapper');

			$(this).addClass('active').siblings().removeClass('active');

			$tab.find('.tab-content .tab').fadeOut(0);

			$tab.find('.tab-content').find('.'+anchor).fadeIn(400);

			$tab.find('.tab-content').find('.'+anchor).addClass('current').siblings().removeClass('current');

			var carousel = $('.owl-carousel-slider');
		    carousel.trigger('refresh.owl.carousel');

		});

		// Update Form
		$('.inspiration-wrap').on('click', '.update-form', function(e) {

			var $self              = $(this),
				$con               = $self.parents('.inspiration-wrap'),
				configuratorId     = $con.data('config-id'),
				values              = $self.closest('.ins-list').data('value'),
				$updateInspiration = $con.find('.update-inspiration'),
				$fieldGroup        = $updateInspiration.find('.ins-field-group'),
				$fieldBtn          = $updateInspiration.find('.ins-field-btn a');

			// Update form values
			$fieldGroup.find('.custom-ins-name').val(values.name);
			$fieldGroup.find('.custom-ins-desc').val(values.desc);
			$fieldGroup.find('.custom-ins-image').val(values.image);

			// Pass the current inspiration list values to the update button for modifying the current inspiration
			$fieldBtn.data('value', values);

			$con.find('.add-new-inspiration').fadeOut(0);
			$con.find('.inspiration-lists').fadeOut(0);
			$con.find('.update-inspiration').fadeIn(400);

		});

		// Update the inspiration list
		$('.inspiration-wrap').on('click', '.update-inspiration-list', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self              = $(this),
				$con               = $self.parents('.inspiration-wrap'),
				$updateInspiration = $con.find('.update-inspiration'),
				$fieldGroup        = $updateInspiration.find('.ins-field-group'),
				configuratorId     = $con.data('config-id'),
				values             = $self.data('value'),
				type               = $self.data('type'),
				name               = $fieldGroup.find('.custom-ins-name').val(),
				desc               = $fieldGroup.find('.custom-ins-desc').val(),
				image              = $fieldGroup.find('.custom-ins-image').val();

			values = { 'group': values.group, 'configurator_id': configuratorId, 'index': values.index, 'name': name, 'desc': desc, 'image': image };

			// Save inspiration key via ajax
			saveInspiration( $self, type, values );

		});

		// Open Inspiration Popup
		$('.open-inspiration-popup').on('click', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self = $(this),
				configuratorId = $self.data('id'),
				$inspirationWrap = $('.inspiration-wrap[data-config-id="'+ configuratorId +'"]');

			$inspirationWrap.removeClass('inspiration-form');

			$inspirationWrap.fadeIn(400);
			$inspirationWrap.find('.inspiration-lists').fadeIn(400);
			$inspirationWrap.find('.update-inspiration').fadeOut(0);
			$inspirationWrap.find('.add-new-inspiration').fadeOut(0);

		});

		// Open Inspiration Popup
		$('.add-new-inspiration-form').on('click', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self = $(this),
				$inspirationWrap = $self.closest('.inspiration-wrap');

			$inspirationWrap.addClass('inspiration-form');

			$inspirationWrap.find('.inspiration-lists').fadeOut(0);
			$inspirationWrap.find('.add-new-inspiration').fadeIn(400);

		});

		// Cancel Inspiration Form
		$('.cancel-inspiration-form').on('click', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self = $(this),
				$inspirationWrap = $self.closest('.inspiration-wrap');

			$inspirationWrap.removeClass('inspiration-form');

			$inspirationWrap.find('.update-inspiration').fadeOut(0);
			$inspirationWrap.find('.add-new-inspiration').fadeOut(0);
			$inspirationWrap.find('.inspiration-lists').fadeIn(400);

		});

		// Reset Components
		$('.tab-wrapper').on('click', '.reset-components', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self = $(this),
				$inspirationWrap = $self.closest('.inspiration-wrap'),
				configurator_id = $inspirationWrap.data('config-id'),
				$insList = $self.parent('.ins-list'),
				value = $insList.data('value');

			$inspirationWrap.fadeOut(0);
			resetComponent( configurator_id, value.key );

		});

		$('.reset-config').on('click', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var $self = $(this),
				configurator_id = $self.data('id'),
				key = $self.data('key');

			resetComponent( configurator_id, key );

		});

		// Copy share Url
		$('.product-share').on('click', '.copy-link', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			clipboard.copy(url);

		});

		$('.existing-group').on('change', function(e) {
			e.preventDefault();
			var $self      = $(this),
				value      = $self.val(),
				$con       = $self.parents('.inspiration-wrap'),
				$nameField = $con.find('.custom-ins-group');

			if( 0 != value ) {
				$nameField.val(value);
			}
			else {
				$nameField.val('');
			}

		});

		// Close popup
		$('.popup').on('click', '.close-popup', function(e) {

			e.preventDefault();

			$(this).closest('.popup').fadeOut(400);

		});

		// Trigger share popup
		$('#left-bar-menu').on('click', '.share', function(e) {

			e.preventDefault();

			$('#share-wrap').fadeIn(400);

		});

		$("#cart-list-wrap").on('click', '.change-btn', function(e) {
			e.preventDefault();

			var $self = $(this),
				$control = $('.pwc-controls-list-sec'),
				parentUid = $self.data('parent-uid');

			// hide all control
			$control.parent('.pwc-controls-list-sec').addClass('hover-hide');
			$control.find('li').removeClass('fadeInUp').addClass('animated fadeOutUp');
			
			$control.find('li[data-uid="'+parentUid+'"]').trigger('click');
		});

	});

	$(window).resize(function () {
		$(".pwc-configurator .pwc-preview-inner").height( $(window).height() - 150 );
		loadPreview();
		applyResponsive();
	});

	$(window).load(function() {

		$(".pwc-configurator .pwc-preview-inner").height( $(window).height() - 150 );

		$(".right-icon").on("click",function(e){
			e.preventDefault();
			$(".right-icon-cart").toggleClass("right-0");
			$('body').toggleClass('body-right');
		});

		$(".right-icon-cart .close-icon").on("click",function(e){
			e.preventDefault();
			$('.right-icon').trigger( "click" );
		});

		$(".left-icon-menu .icon-view").on("click",function(e){
			e.preventDefault();
			$(".left-position-content").toggleClass("left-0");
			$('body').toggleClass('body-left');
		});

		$(".more-product-con .close-icon").on("click",function(e){
			e.preventDefault();
			$('.left-icon-menu .icon-view').trigger( "click" );
		});
		
		$("#cart-list-wrap").on('click', '.icon-add-close', function(e) {
			e.preventDefault();
			$(this).parent().next().slideToggle(400);
			$(this).toggleClass('close');
		});

		/* Option set (icons in bottom) */
		var $controls = $('.pwc-controls-img-list'),
			$configurator = $('.pwc-configurator');
		$controls.on('click', '[data-open-optionset]', function(e) {
			e.preventDefault();

			var $self = $(this),
				$parent = $self.parent('.pwc-controls-img-list'),
				$optionset = $('[data-optionset="'+ $self.data('open-optionset') +'"]');

			hideOptions( $parent );

			showOptions( $optionset );

			if( $optionset.width() > $(window).width() ) {

				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					$(this).closest('.pwc-controls-wrap').addClass('show-scroll mobile');
				} else {
					$(this).closest('.pwc-controls-wrap').addClass('show-scroll');
				}

			} else {

				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					$(this).closest('.pwc-controls-wrap').removeClass('show-scroll mobile');
				} else {
					$(this).closest('.pwc-controls-wrap').removeClass('show-scroll');
				}
				
			}

		});		

		/* hotspot trigger */
		$configurator.on('click', '.pwc-hotspot', function(e) {

			if( $(this).hasClass('active') ) {
				return;
			}

			var uid = $(this).data('hotspot-uid'),
			$parent = $controls.find('[data-uid="' + uid + '"]').parent('.pwc-controls-img-list');

			// Add active class
			$configurator.find('.pwc-hotspot').removeClass('active');
			$(this).addClass('active');

			// hide all control
			$controls.parent('.pwc-controls-list-sec').addClass('hover-hide');
			$controls.find('li').removeClass('fadeInUp').addClass('animated fadeOutUp');

			// trigger 
			$controls.find('[data-uid="' + uid + '"]').trigger('click');

		});

		$('.pwc-configurator-view').owlCarousel({
			loop: false,
			items: 1,
			touchDrag: false,
			mouseDrag: false,
			onInitialized: function() {
				loadPreview( true );
				applyResponsive();
			}
		});

		/* Take screenshot */
		$('.take-photo').on('click', function(e) {
			e.preventDefault();
		
			var $self = $(this),
				configurator_id = $self.data('id'),
				$elem 	  = $('#configurator-view-'+ configurator_id +' .active .pwc-preview-inner'),
				maxWidth  = pwc.entireWidth  - pwc.minLeft,
				maxHeight = pwc.entireHeight - pwc.minTop;

			var $elemCon = $(".pwc-configurator-view .active .pwc-preview-inner").clone(),
				$scaledElement = $('<div />', {id: "screenshot-con"}).html($elemCon.html());
				
			$scaledElement.width(maxWidth + 80 ).height(maxHeight + 80);
			$scaledElement.appendTo('body');

			loadPreview( false, $scaledElement );

			html2canvas($scaledElement, {
				onrendered: function(canvas) {
					var theCanvas = canvas,
						today = new Date(),
						date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate(),
						time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds(),
						dateTime = date+' '+time,
						filename;

					filename =  document.title + ' ' + dateTime + '.png'.toLowerCase();

					canvas.toBlob(function(blob) {
						saveAs(blob, filename);
					});

					$scaledElement.remove();

				}
			});

		});

		// select text inputs on click
		$("#share-wrap input[type='text']").on("focus", function () {
			$(this).select();
		});

	});

})(jQuery);

(function($){

	'use strict';

	$(document).ready(function($) {
		$('.pwc-skin-accordion-controls').find('.pwc-controls-list-sec').first().addClass('active')
										 .find('.pwc-controls-img-list').css('display', 'block');
	});

	$(window).load(function() {

		$('.pwc-skin-accordion-controls').on('click', '.pwc-layer-title', function(e) {
			e.preventDefault();
			var $parent = $(this).parent('.pwc-controls-list-sec');
			$parent.addClass('active').siblings().removeClass('active');
			$(this).next('.pwc-controls-img-list').slideDown();
			$parent.siblings('.pwc-controls-list-sec').find('.pwc-controls-img-list').slideUp();

		});

		$('.pwc-skin-accordion-controls').find('.pwc-controls-img-list').each(function(index, el) {
			var imgSrc = $(this).find('.current img').attr('src');
			if( imgSrc ) {
				$(this).prev('.pwc-layer-title').append('<span class="pwc-acc-active-icon"><img src="' + imgSrc + '" alt=""></span>');
			}
		});

		$('.pwc-skin-accordion-controls').on('click', '[data-changeimage]', function(e) {
			var imgSrc = $(this).find('img').attr('src');
			// TODO: check mutliple and active, remove icon from current.

			var $title = $(this).parent('.pwc-controls-img-list').prev('.pwc-layer-title'),
				$icon = $title.find('.pwc-acc-active-icon');

			if( $icon.length ) {
				$icon.find('img').attr('src', imgSrc);
			} else {
				$title.append('<span class="pwc-acc-active-icon"><img src="' + imgSrc + '" alt=""></span>')
			}

		});

	});

	// resize event
	$(window).resize(function(e){
		
	});

})(jQuery);

/* code to hide all other layers */
/* Todo add an option for this - this code will not update price,  */

/*

var $q = jQuery;
$q(window).load(function() {
    var $allControls = $q('.pwc-controls-img-list').find( '[data-changeimage]' );
    $q('.pwc-controls-img-list').on('click.one', '[data-changeimage]', function(e) {
        var $self           = $q(this),
            uid             = $self.data('uid'),
            $con            = $q('#configurator-view-' + $self.closest('.pwc-controls-wrap').data('config-id') ),
            $subset         = $con.find('.subset[data-uid="'+uid+'"]');
        $allControls.removeClass('current');
        $self.addClass('current');
        $subset.siblings().removeClass('active');
    });
});

*/
