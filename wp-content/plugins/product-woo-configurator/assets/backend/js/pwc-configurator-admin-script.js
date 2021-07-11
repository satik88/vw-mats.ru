;
( function( $, window, document, undefined ) {
	"use strict";

	$.PWC = function() {
		this.$preview = $( '#pwc-preview' );
		this.$settingsPanel = $( '#pwc-settings-panel' );
		this.views = this.$settingsPanel.data( 'views' );
		this.$layerGroup = $( '#pwc-settings' );
		this.$imageOptions = $( '#pwc-image-options' );
		this.$layerOptions = $( '#pwc-layer-options' );
		this.$tranformGroups = $( '#transform-groups' );
		this.$hsbtn = this.$imageOptions.find( '#add-remove-hotspot' ); //hotspot btn

		this.$activeInPreview = ''; // this should be image or hotspot

		// current active image view on preview
		this.curImageView = $( '#select-image-view' ).val();
		this.previewfocus = false;

		// preview width and height
		this.previewWidth = this.$preview.width();
		this.previewHeight = this.$preview.height();
		this.init();
	};

	$.PWC.prototype = {

		init: function() {
			window.pwc = this;

			/*Input cursor position*/
			$.fn.setCursorPosition = function( pos ) {
				if ( $( this ).get( 0 ).setSelectionRange ) {
					$( this ).get( 0 ).setSelectionRange( pos, pos );
				} else if ( $( this ).get( 0 ).createTextRange ) {
					var range = $( this ).get( 0 ).createTextRange();
					range.collapse( true );
					range.moveEnd( 'character', pos );
					range.moveStart( 'character', pos );
					range.select();
				}
			}

			$( window ).on( 'load', function() {
				pwc.loadPreview();
				pwc.$layerGroup.find( '.pwc-group-field' ).first().trigger( 'click' );
			} );

			this.bindEvents();
			this.resizeEvents();
			this.initSortable();


		},

		initSortable: function() {

			this.$layerGroup.sortable( {
				opacity: .9,
				revert: true,
				cursor: 'move',
				handle: '.pwc-name-icon',
				axis: 'y',
				//	connectWith: "#pwc-settings"
			} ).disableSelection();

			$( '.pwc-settings-sub-group-wrapper', this.$layerGroup ).sortable( {
				opacity: .9,
				revert: true,
				cursor: 'move',
				handle: '.pwc-name-icon',
				axis: 'y',
				connectWith: "#pwc-settings .pwc-settings-sub-group-wrapper",
				update: function( event, ui ) {
					var $this = ui.item;
				}
			} ).disableSelection();

			$( '.pwc-settings-sub-group-wrapper .pwc-settings-sub-value-group', this.$layerGroup ).sortable( {
				opacity: .9,
				revert: true,
				cursor: 'move',
				handle: '.pwc-name-icon',
				axis: 'y'
			} ).disableSelection();

		},

		applySortable: function( elem, connectWith ) {

			var options = {
				opacity: .9,
				revert: true,
				cursor: 'move',
				handle: '.pwc-name-icon',
				axis: 'y'
			};

			if ( connectWith ) {
				options.connectWith = elem;
			}

			$( elem ).sortable( options ).disableSelection();

		},

		refreshSortable: function() {

			$( '.pwc-settings-sub-group-wrapper', this.$layerGroup ).sortable( 'refresh' );

			$( '.pwc-settings-sub-group-wrapper .pwc-settings-sub-value-group', this.$layerGroup ).sortable( 'refresh' );

		},

		showImageOptions: function() {
			this.$settingsPanel.find( '.pwc-check-active' ).removeClass( 'not-active' );

			if ( !pwc.$activeInPreview.is( '.pwc-hotspot' ) ) {
				this.$settingsPanel.find( '#preview-settings' ).removeClass( 'hide-fields' );
			}

		},

		hideImageOptions: function() {
			this.resetImageOptions();
			this.$settingsPanel.find( '.pwc-check-active' ).addClass( 'not-active' );
			this.$settingsPanel.find( '#preview-settings' ).removeClass( 'hide-fields' );
		},

		resetImageOptions: function() {
			this.$settingsPanel.find( '#transform-groups input' ).val( '' );
			this.$settingsPanel.find( '#transform-groups #align-groups div div' ).removeClass( 'active' );
			this.$hsbtn.removeClass( 'has-hotspot' );
		},

		loadPreview: function() {

			var self = this;

			// 1. now apply left and top, depends on aligment and set data-init-x and data-init-y
			var $previewImagesCon = self.$preview.find( '.pwc-preview-imgcon' );

			$previewImagesCon.each( function( index, el ) {

				var $activeImage = $( this ),
					x = $activeImage.data( 'align-h' ),
					y = $activeImage.data( 'align-v' ),
					offsetX = parseFloat( $activeImage.data( 'offset-x' ), 10 ),
					offsetY = parseFloat( $activeImage.data( 'offset-y' ), 10 ),
					width = parseFloat( $( this ).data( 'width' ), 10 ),
					height = parseFloat( $( this ).data( 'height' ), 10 ),
					valX, valY;

				$( this ).css( 'position', 'absolute' );

				// change image postion & set x and y
				if ( 'left' == x ) {

					valX = 0;
					$activeImage.css( {
						'left': ( valX + offsetX ) + 'px',
						'right': 'auto'
					} );

				} else if ( 'right' == x ) {

					valX = self.previewWidth - width;
					$activeImage.css( {
						'left': ( valX + offsetX ) + 'px',
						'right': 'auto'
					} );

				} else if ( 'center' == x ) {

					valX = ( self.previewWidth - width ) / 2;
					$activeImage.css( {
						'left': ( valX + offsetX ) + 'px',
						'right': 'auto'
					} );

				}

				if ( 'middle' == y ) {
					valY = ( ( self.previewHeight - height ) / 2 );
					$activeImage.css( {
						'top': ( valY + offsetY ) + 'px',
						'bottom': 'auto'
					} );

				} else if ( 'top' == y ) {

					valY = 0;
					$activeImage.css( {
						'top': ( valY + offsetY ) + 'px',
						'bottom': 'auto'
					} );

				} else if ( 'bottom' == y ) {

					valY = self.previewHeight - height;
					$activeImage.css( {
						'top': ( valY + offsetY ) + 'px',
						'bottom': 'auto'
					} );

				}

				$activeImage.attr( {
					'data-init-x': valX,
					'data-init-y': valY
				} );

			} );

			self.$preview.find( '.pwc-preview-inner' ).removeClass( 'loading' );

			var $hotspot = self.$preview.find( '.pwc-hotspot' );

			$hotspot.each( function( index, el ) {

				var $activeImage = $( this ),
					x = $activeImage.data( 'align-h' ),
					y = $activeImage.data( 'align-v' ),
					offsetX = parseFloat( $activeImage.data( 'offset-x' ), 10 ),
					offsetY = parseFloat( $activeImage.data( 'offset-y' ), 10 ),
					width = $( this ).width(),
					height = $( this ).height(),
					valX, valY;

				$( this ).css( 'position', 'absolute' );

				// change image postion & set x and y
				if ( 'left' == x ) {

					valX = 0;
					$activeImage.css( {
						'left': ( valX + offsetX ) + 'px',
						'right': 'auto'
					} );

				} else if ( 'right' == x ) {

					valX = self.previewWidth - width;
					$activeImage.css( {
						'left': ( valX + offsetX ) + 'px',
						'right': 'auto'
					} );

				} else if ( 'center' == x ) {

					valX = ( self.previewWidth - width ) / 2;
					$activeImage.css( {
						'left': ( valX + offsetX ) + 'px',
						'right': 'auto'
					} );

				}

				if ( 'middle' == y ) {
					valY = ( ( self.previewHeight - height ) / 2 );
					$activeImage.css( {
						'top': ( valY + offsetY ) + 'px',
						'bottom': 'auto'
					} );

				} else if ( 'top' == y ) {

					valY = 0;
					$activeImage.css( {
						'top': ( valY + offsetY ) + 'px',
						'bottom': 'auto'
					} );

				} else if ( 'bottom' == y ) {

					valY = self.previewHeight - height;
					$activeImage.css( {
						'top': ( valY + offsetY ) + 'px',
						'bottom': 'auto'
					} );

				}

				$activeImage.data( 'init-x', valX ).data( 'init-y', valY );

			} );

		},

		resizeEvents: function() {
			var pwc = this;
			/* update preview width and height on resize */
			$( window ).resize( function( e ) {
				pwc.previewWidth = pwc.$preview.width();
				pwc.previewHeight = pwc.$preview.height();

				pwc.loadPreview();
			} );

		},

		randomText: function( len ) {
			var text = "";
			var charset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

			for ( var i = 0; i < len; i++ ) {
				text += charset.charAt( Math.floor( Math.random() * charset.length ) );
			}

			return text;
		},

		removeImage: function() {

			var $curView,
				$activeLayerImage = this.$preview.find( '#pwc-' + this.curImageView + ' .active-layer-image' ).first();

			if ( $activeLayerImage.length ) {

				$curView = this.$layerGroup.find( '.active-layer .' + this.curImageView + '-con' );

				this.$layerOptions.find( '#product-image [data-img-view="' + this.curImageView + '"]' ).closest( '.group-icon' )
					.find( 'input.pix-saved-val' ).val( '' ).change();

				$curView.find( '.field-pos-x' ).val( '' );
				$curView.find( '.field-pos-y' ).val( '' );
				$curView.find( '.field-z-index' ).val( '' );
				$curView.find( '.field-width' ).val( '' );
				$curView.find( '.field-height' ).val( '' );
				$curView.find( '.field-align-h' ).val( '' );
				$curView.find( '.field-align-v' ).val( '' );

				$activeLayerImage.remove();

				pwc.hideImageOptions();

			}

		},

		updateTransformGroupFields: function( e, keyCode, $active ) {

			var addVal = ( e.shiftKey == 1 ) ? 10 : 1,
				curFieldVal;

			curFieldVal = $active.val();

			curFieldVal = ( curFieldVal ) ? parseInt( curFieldVal, 10 ) : 0;

			if ( keyCode == 38 || keyCode == 40 ) {

				e.preventDefault();

				// add 'addVal' 
				curFieldVal += addVal;
				curFieldVal -= addVal;

				curFieldVal = ( keyCode == 38 ) ? curFieldVal - addVal : ( keyCode == 40 ) ? curFieldVal + addVal : curFieldVal;

				curFieldVal = ( $active.is( "#z-index" ) ) ? curFieldVal : curFieldVal + 'px';

				$active.val( curFieldVal ).change();

				$active.setCursorPosition( curFieldVal.length );

				$active[ 0 ].select();
				//this.setSelectionRange(0, this.value.length)

				return false;
			}

		},

		// when mouse cursor is in preview window move image
		transformPreviewImage: function( e, keyCode, $active ) {

			var addVal = ( e.shiftKey == 1 ) ? 10 : 1,
				// $activeImage = this.$preview.find( '#pwc-' + this.curImageView + ' .active-layer-image' ),
				$activeImage = this.$activeInPreview,
				$input, curFieldVal;

			if ( !$activeImage.length ) {
				return;
			}

			switch ( keyCode ) {
				case 38: // Up

					// add y field and trigger change
					$input = this.$tranformGroups.find( '#transform-y' );

					curFieldVal = $input.val();
					curFieldVal = ( curFieldVal ) ? parseInt( curFieldVal, 10 ) : 0;

					$input.val( ( curFieldVal - addVal ) + 'px' ).change();

					e.preventDefault();
					break;

				case 40: // Down

					// subtract y field and trigger change
					$input = this.$tranformGroups.find( '#transform-y' );

					curFieldVal = $input.val();
					curFieldVal = ( curFieldVal ) ? parseInt( curFieldVal, 10 ) : 0;

					$input.val( ( curFieldVal + addVal ) + 'px' ).change();

					e.preventDefault();

					break;

				case 37: // left

					// subtract x field and trigger change
					$input = this.$tranformGroups.find( '#transform-x' );

					curFieldVal = $input.val();
					curFieldVal = ( curFieldVal ) ? parseInt( curFieldVal, 10 ) : 0;

					$input.val( ( curFieldVal - addVal ) + 'px' ).change();

					e.preventDefault();

					break;

				case 39: // right

					// add x field and trigger change
					$input = this.$tranformGroups.find( '#transform-x' );

					curFieldVal = $input.val();
					curFieldVal = ( curFieldVal ) ? parseInt( curFieldVal, 10 ) : 0;

					$input.val( ( curFieldVal + addVal ) + 'px' ).change();

					e.preventDefault();

					break;
			}

		},

		pwcRepeatSection: function( self, repeatableField, structure, con ) {

			var $repeatableFieldStructure = $( $( structure ).html() ),
				$con = $( con ),
				$repeatableField = $con.find( repeatableField ),
				currentCount = $repeatableField.length,
				$lastRepeatingGroup = $repeatableField.last(),
				mainIndex = self.data( 'index' ),
				randomIndex = this.randomText( 4 ) + '-' + this.randomText( 4 ),
				$mediaInsert = $( '#pwc-settings-panel' ),
				mediaInsertVal = $mediaInsert.data( 'media-insert' );

			// Set unique id
			var uid1 = this.randomText( 4 ),
				uid2 = this.randomText( 4 );

			$repeatableFieldStructure.find( '.set-uid' ).val( uid1 + '-' + uid2 );
			// $repeatableFieldStructure.attr('data-uid', uid1+'-'+uid2);

			// Set data id to the active layer
			$repeatableFieldStructure.find( '.pwc-group-field' ).attr( 'data-uid', uid1 + '-' + uid2 );

			// Build html
			$repeatableFieldStructure.find( '.component-input' ).each( function( index, html ) {

				var mainIndex = $( html ).data( 'index' ),
					key = $( html ).data( 'key' );

				var $appendField = $( html ).attr( 'name', mainIndex + '[' + randomIndex + ']' + key );
			} );

			if ( currentCount > 0 ) {
				$lastRepeatingGroup.after( $repeatableFieldStructure );
			} else {
				$con.html( $repeatableFieldStructure );
			}

			var $btn = $con.find( '.pwc-settings-group' ).eq( currentCount ).find( '.pwc-values' ),
				mainIndex = $btn.data( 'index' ),
				subIndex = $btn.data( 'sub-index' );

			$btn.data( 'parent-index', mainIndex + '[' + randomIndex + '][values]' );

		},

		// Initial group main values
		pwcRepeatSectionInside: function( self, repeatableField, structure, con ) {

			var $repeatableFieldStructure = $( $( structure ).html() ),
				$con = self.closest( '.pwc-group-field' ).next( con ),
				$repeatableField = $con.find( repeatableField ),
				currentCount = $repeatableField.length,
				randomIndex = this.randomText( 4 ) + '-' + this.randomText( 4 ),
				$lastRepeatingGroup = $repeatableField.last(),
				parentIndex = self.data( 'parent-index' );

			// Set unique id
			var uid1 = this.randomText( 4 ),
				uid2 = this.randomText( 4 );
			$repeatableFieldStructure.find( '.set-uid' ).val( uid1 + '-' + uid2 );

			// Set data id to the active layer
			$repeatableFieldStructure.find( '.pwc-sub-group-field' ).attr( 'data-uid', uid1 + '-' + uid2 );

			// Build html
			$repeatableFieldStructure.find( '.component-input' ).each( function( key, html ) {

				var $input = $( html ),
					key = $( html ).data( 'key' );

				var $appendField = $input.attr( 'name', parentIndex + '[' + randomIndex + ']' + key );

			} );

			if ( currentCount > 0 ) {
				$lastRepeatingGroup.after( $repeatableFieldStructure );
			} else {
				$con.html( $repeatableFieldStructure );
				pwc.applySortable( $con[ 0 ] );
			}

			var $btn = $con.find( '.pwc-settings-sub-group' ).eq( currentCount ).find( '.pwc-sub-values' );

			// Set parent array index
			$btn.data( 'parent-index', parentIndex + '[' + randomIndex + '][values]' );

		},

		// Initial group sub values
		pwcRepeatSectionInsideSubValue: function( self, repeatableField, structure ) {

			// check "sub value group wrap" Already exist
			var $subGroupField = self.closest( '.pwc-sub-group-field' ),
				$groupWrap = $subGroupField.next( 'div.pwc-settings-sub-value-group-wrapper' ),
				$repeatableFieldStructure = $( $( structure ).html() ),
				parentIndex = self.data( 'parent-index' ),
				randomIndex = this.randomText( 4 ) + '-' + this.randomText( 4 ),
				$repeatableField, currentCount = 0;

			// Set unique id
			var uid1 = this.randomText( 4 ),
				uid2 = this.randomText( 4 );
			$repeatableFieldStructure.find( '.set-uid' ).val( uid1 + '-' + uid2 );

			// Set data id to the active layer
			$repeatableFieldStructure.find( '.pwc-sub-group-field' ).attr( 'data-uid', uid1 + '-' + uid2 );

			// group wrap not found so create one & insert after '.pwc-sub-group-field'.
			if ( $groupWrap.length == 0 ) {
				$groupWrap = $( '<div />', {
					class: 'pwc-settings-sub-value-group-wrapper'
				} );
				$groupWrap.insertAfter( $subGroupField );

				pwc.applySortable( $groupWrap[ 0 ] );
			}

			// Insert html to group wrap
			$repeatableField = $groupWrap.find( repeatableField );
			currentCount = $repeatableField.length;

			// Change input attr
			$repeatableFieldStructure.find( '.component-input' ).each( function( key, input ) {

				var $input = $( input ),
					key = $input.data( 'key' );

				var $appendField = $input.attr( 'name', parentIndex + '[' + randomIndex + ']' + key );

			} );

			$groupWrap.append( $repeatableFieldStructure );

			// Set parent array index to button
			$groupWrap.find( '.pwc-settings-sub-value-group' ).eq( currentCount ).find( '.pwc-sub-values' )
				.data( 'parent-index', parentIndex + '[' + randomIndex + '][values]' );

			// Add unique id to the parent
			var uid1 = this.randomText( 4 ),
				uid2 = this.randomText( 4 );
			$groupWrap.find( '.pwc-settings-sub-value-group' ).eq( currentCount ).find( '.pwc-sub-group-field' ).data( 'uid', uid1 + '-' + uid2 );

		},

		// Collapse settings
		pwcCollapse: function( self, selector, collapseDiv ) {
			var collapse = self.data( 'collapse' ),
				$con = self.closest( selector ),
				$collapseDiv = $con.next( collapseDiv );

			if ( collapse ) {
				self.data( 'collapse', false );
				self.prev( 'input' ).val( false );
				self.find( 'i' ).addClass( 'pwc-sort-asc' ).removeClass( 'pwc-sort-desc' );
			} else {
				self.data( 'collapse', true );
				self.prev( 'input' ).val( true );
				self.find( 'i' ).addClass( 'pwc-sort-desc' ).removeClass( 'pwc-sort-asc' );
			}

			if ( $collapseDiv.length > 0 ) {
				$collapseDiv.slideToggle( 400 );
			}
		},

		// Apply the dummy values to the main input
		getValFromLayers: function( self ) {

			var $layerOption = this.$layerOptions,
				$sizeGroup = $( '#size-groups' ),
				$alignGroup = $( '#align-groups' ),
				$views = this.views,

				// image option depends on current view
				$curView = self.find( '.' + this.curImageView + '-con' ),
				PosX = $curView.find( '.field-pos-x' ).val(),
				PosY = $curView.find( '.field-pos-y' ).val(),
				zIndex = $curView.find( '.field-z-index' ).val(),
				width = $curView.find( '.field-width' ).val(),
				height = $curView.find( '.field-height' ).val(),
				AlignH = $curView.find( '.field-align-h' ).val(),
				AlignV = $curView.find( '.field-align-v' ).val(),

				// Other values
				price = self.find( '.field-price' ).val(),
				icon = self.find( '.field-icon' ).val(),
				description = self.find( '.field-description' ).val(),
				label = self.find( '.field-label' ).val(),
				hideControlVal = self.find( '.field-hide_control' ).val(),
				hideControlVal = ( 'true' == hideControlVal ) ? true : false,
				active = self.find( '.field-active' ).val(),
				active = ( 'true' == active ) ? true : false,
				required = self.find( '.field-required' ).val(),
				required = ( 'true' == required ) ? true : false,
				multiple = self.find( '.field-multiple' ).val(),
				multiple = ( 'true' == multiple ) ? true : false,
				iconSrc = self.find( '.icon-con' ).data( 'src' ),
				$alignGroupHor,
				$alignGroupVer,
				hs;

			$.each( $views, function( i, item ) {

				var $viewCon = self.find( '.' + i + '-con' ),
					image = $viewCon.find( '.field-image' ).val(),
					imageSrc = $viewCon.data( 'src' );

				$layerOption.find( '.show-' + i + '-image' ).val( image );

				if ( imageSrc ) {
					$layerOption.find( '.show-' + i + '-image' ).next().html( '<img src="' + imageSrc + '" alt=""><span class="remove-img"><i class="pwc-cross"></i></span>' ) // add image position (Hcenter, Vcenter is default)
				} else {
					$layerOption.find( '.show-' + i + '-image' ).next().html( '' );
				}

			} );

			// Apply values
			$layerOption.find( '.show-price' ).val( price );
			$layerOption.find( '.show-description' ).val( description );
			$layerOption.find( '.show-label' ).val( label );
			$layerOption.find( '.show-hide_control' ).prop( 'checked', hideControlVal );
			$layerOption.find( '.show-active' ).prop( 'checked', active );
			$layerOption.find( '.show-multiple' ).prop( 'checked', multiple );
			$layerOption.find( '.show-required' ).prop( 'checked', required );
			$layerOption.find( '.show-icon' ).val( icon );

			$sizeGroup.find( '.show-pos-x' ).val( PosX );
			$sizeGroup.find( '.show-pos-y' ).val( PosY );
			$sizeGroup.find( '.show-z-index' ).val( zIndex );
			$sizeGroup.find( '.show-width' ).val( width );
			$sizeGroup.find( '.show-height' ).val( height );


			$alignGroupHor = $alignGroup.find( '.align-left-right' );
			$alignGroupVer = $alignGroup.find( '.align-top-bottom' );

			// horizontal align
			$alignGroup.find( '.show-align-h' ).val( AlignH );
			$alignGroupHor.find( 'div[data-value]' ).removeClass( 'active' );
			$alignGroupHor.find( 'div[data-value="' + AlignH + '"]' ).addClass( 'active' );

			// Vertical Align
			$alignGroup.find( '.show-align-v' ).val( AlignV );
			$alignGroupVer.find( 'div[data-value]' ).removeClass( 'active' );
			$alignGroupVer.find( 'div[data-value="' + AlignV + '"]' ).addClass( 'active' );

			// Apply Src
			if ( iconSrc ) {
				$layerOption.find( '.show-icon' ).next().html( '<img src="' + iconSrc + '" alt=""><span class="remove-img"><i class="pwc-cross"></i></span>' ) // add image position (Hcenter, Vcenter is default)
			} else {
				$layerOption.find( '.show-icon' ).next().html( '' );
			}

			// Addtional fields added using actions			
			var $extraFields = self.find( '.pwc-additional-fields' );

			if ( $extraFields.length ) {
				var slf = this;
				$extraFields.each( function( index, el ) {

					var id = $( this ).data( 'id' ),
						val = $( this ).val(),
						view = $( this ).data( 'viewfield' ),
						$input;

					if ( view && view != slf.curImageView ) {
						return;
					}

					$input = $layerOption.find( '.field-' + id );

					// checkbox
					if ( 'checkbox' == $input.attr( 'type' ) ) {

						if ( val == 'true' ) {
							$input.prop( 'checked', true );
						} else {
							$input.prop( 'checked', false );
						}

					}
					// image field
					else if ( 'hidden' == $input.attr( 'type' ) && $input.hasClass( 'pix-saved-val' ) ) {

						var src = $( this ).data( 'src' );

						$input.val( val );

						if ( src ) {
							$input.next().html( '<img src="' + src + '" alt=""><span class="remove-img"><i class="pwc-cross"></i></span>' );
						} else {
							$input.next().html( '' );
						}

					} else {
						$input.val( val );
					}

				} );

			}

			// Check this layer have hotspot added
			hs = $curView.find( '.field-hs-enable' ).val();

			if ( hs ) {
				this.$hsbtn.addClass( 'has-hotspot' );
			} else {
				this.$hsbtn.removeClass( 'has-hotspot' );
			}

		},

		// Apply the dummy values to the main input
		onChangeVal: function( self, val, fieldPrefix ) {

			var con = self.data( 'con' ),
				input = self.data( 'input' ),
				$activeLayers = $( '#pwc-settings' ).find( '.active-layer' ),
				$fieldCon = $activeLayers.find( '.' + con + '-con' );

			// fields inside view group (for additional fields)
			if ( !$fieldCon.length ) {
				$fieldCon = $activeLayers.find( '.' + this.curImageView + '-con' );
			}

			$fieldCon.find( '.field-' + fieldPrefix + input ).val( val );

		},

		// When the select view changed its triggered
		onChangeView: function( self, val, prefix ) {

			var $sizeGroup = $( '#size-groups' ),
				$alignGroup = $( '#align-groups' ),
				$con = $( '.active-layer' ).find( '.' + val + '-con' ),
				PosX = $con.find( '.field-' + prefix + 'pos-x' ).val(),
				PosY = $con.find( '.field-' + prefix + 'pos-y' ).val(),
				zIndex = $con.find( '.field-' + prefix + 'z-index' ).val(),
				width = $con.find( '.field-' + prefix + 'width' ).val(),
				height = $con.find( '.field-' + prefix + 'height' ).val(),
				AlignH = $con.find( '.field-' + prefix + 'align-h' ).val(),
				AlignV = $con.find( '.field-' + prefix + 'align-v' ).val();

			$sizeGroup.find( '.show-pos-x' ).val( PosX );
			$sizeGroup.find( '.show-pos-y' ).val( PosY );

			if ( !prefix ) {
				$sizeGroup.find( '.show-z-index' ).val( zIndex );
				$sizeGroup.find( '.show-width' ).val( width );
				$sizeGroup.find( '.show-height' ).val( height );
			}

			$alignGroup.find( '.show-align-h' ).val( AlignH );
			$alignGroup.find( '.show-align-v' ).val( AlignV );

			// Build html
			$( '#size-groups, #align-groups' ).find( '.transform-input' ).each( function( index, html ) {
				$( html ).data( 'con', val );
			} );

			this.$tranformGroups.find( '[data-value="' + AlignH + '"]' ).addClass( 'active' ).siblings().removeClass( 'active' );
			this.$tranformGroups.find( '[data-value="' + AlignV + '"]' ).addClass( 'active' ).siblings().removeClass( 'active' );

		},

		// draggable options obj		
		previewDragOptions: {
			// stack: "#pwc-preview div .pwc-preview-imgcon",
			addClasses: false,
			// containment: "parent",
			stop: function( event, ui ) {
				var x, y, initX, initY, self = $( this );

				// initX & initY position should be calculated from alignment
				initX = parseFloat( self.data( 'init-x' ) );
				initY = parseFloat( self.data( 'init-y' ) );

				x = ui.position.left - initX;
				y = ui.position.top - initY;

				pwc.$tranformGroups.find( '#transform-x' ).val( x + 'px' ).trigger( 'change:onDrag' ).end()
					.find( '#transform-y' ).val( y + 'px' ).trigger( 'change:onDrag' );
			}
		},

		// Draggable and resizable
		pwcPreviewDragAndResize: function( $elem ) {

			$elem.draggable( this.previewDragOptions ); // .resizable()

		},

		// Add or Remove Hotspot
		addRemoveHotspot: function() {

			// check for any layer active
			if ( !pwc.$activeLayer ) {
				alert( 'Please select a layer first!' );
				return;
			}

			var uid = pwc.$activeLayer.data( 'uid' ),
				$curPreview = this.$preview.find( '#pwc-' + this.curImageView ),
				$curLayerHs = $curPreview.find( '#hotspot-' + this.curImageView + '-' + uid );

			// hotspot found remove it
			if ( $curLayerHs.length ) {
				this.removeHotSpot( $curLayerHs );
				return;
			}

			// hotspot not found so create & add it
			this.createHotspot( $curPreview, uid );


			this.hotspotBtnStatus( 'remove' );

		},

		createHotspot: function( $curPreview, uid ) {

			// create product
			var $transGroup = this.$tranformGroups,
				$hotspot = $( '<div />', {
					class: 'pwc-hotspot',
					id: 'hotspot-' + this.curImageView + '-' + uid,
					'data-hotspot-uid': uid
				} ).html( '<span></span>' );

			this.pwcPreviewDragAndResize( $hotspot );

			if ( !pwc.$activeLayer.data( 'locked' ) ) {
				$hotspot.addClass( 'active-layer-hotspot' );
				pwc.$preview.find( '[data-hotspot-uid="' + uid + '"]' ).addClass( 'active-layer-hotspot' );

				// enable controls
				this.$settingsPanel.find( '.pwc-check-active' ).removeClass( 'not-active' );
				pwc.$imageOptions.find( '#preview-settings' ).addClass( 'hide-fields' );

			} else {
				$hotspot.draggable( 'disable' );
				$hotspot.addClass( 'hotspot-locked' );
			}

			if ( pwc.$activeLayer.data( 'hidden' ) ) {
				$hotspot.addClass( 'hotspot-hidden' );
			}

			// remove .active-layer-image and append to current preview
			pwc.$preview.find( '.pwc-preview-imgcon' ).removeClass( 'active-layer-image' );

			$curPreview.append( $hotspot );

			this.$activeInPreview = $hotspot;

			// set hotspot to true
			pwc.$activeLayer.find( '.' + this.curImageView + '-con' ).find( '.field-hs-enable' ).val( 'true' );
			this.$hsbtn.addClass( 'has-hotspot' );

			// set center center as default by trigger align controls and pass newly added image con to align
			$transGroup.find( '.align-left-right [data-value="center"]' ).trigger( 'click', [ $hotspot, this.curImageView, 'hs-' ] );
			$transGroup.find( '.align-top-bottom [data-value="middle"]' ).trigger( 'click', [ $hotspot, this.curImageView, 'hs-' ] );

			// change button to remove
			this.hotspotBtnStatus( 'remove' );
		},

		removeHotSpot: function( $curLayerHs ) {
			if ( confirm( 'Are you sure? Do you want to remove HotSpot for this layer?' ) ) {

				// append to current preview
				$curLayerHs.remove();

				// set hotspot to false
				pwc.$activeLayer.find( '.' + this.curImageView + '-con' ).find( '.field-hs-enable' ).val( '' );
				this.$hsbtn.removeClass( 'has-hotspot' );

				// disable image option ad reset options
				// reset values in layer
				this.hideImageOptions();

				// change button to add
				this.hotspotBtnStatus( 'add' );
			}
		},

		// change data-hotspot
		hotspotBtnStatus: function( status ) {
			this.$hsbtn.data( 'hotspot', status );

			( status == 'add' ) ?
			this.$hsbtn.addClass( 'pwc-hotspot-add' ).removeClass( 'pwc-hotspot-remove' ):
				this.$hsbtn.removeClass( 'pwc-hotspot-add' ).addClass( 'pwc-hotspot-remove' );
		},

		// Delete View
		deleteView: function( configurator_id, index ) {

			$.ajax( {
				type: 'post',
				url: ajaxurl,
				data: {
					action: 'pwc_delete_view',
					configurator_id: configurator_id,
					index: index
				},
				beforeSend: function() {},
				complete: function() {},
			} ).done( function( data ) {

				location.reload();

			} ).always( function() {} );
		},

		bindEvents: function() {

			var pwc = this;

			/* Add HotSpot */
			this.$hsbtn.on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				pwc.addRemoveHotspot();

			} );

			/* Hotspot todo */
			// 1. on layer delete, delete hotspot as well

			/* Add Layer */
			$( '#pwc-settings-panel' ).on( 'click', '#pwc-options', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var self = $( this );

				pwc.pwcRepeatSection( self, '.pwc-settings-group', '#pwc-settings-field', '#pwc-settings' ); // $(this), repeatable field class, hidden html structure wrapper, where to append

			} );

			/* Add sub Layer */
			$( '#pwc-settings' ).on( 'click', '.pwc-values', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var self = $( this ),
					valueIndex = self.data( 'array-key' );

				pwc.pwcRepeatSectionInside( self, '.pwc-settings-sub-group', '.pwc-settings-sub-field', '.pwc-settings-sub-group-wrapper' ); // $(this), repeatable field class, hidden html structure wrapper, where to append

			} );

			/* Add sub Layer > sublayers */
			$( '#pwc-settings' ).on( 'click', '.pwc-sub-values', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var self = $( this );

				pwc.pwcRepeatSectionInsideSubValue( self, '.pwc-settings-sub-value-group', '.pwc-settings-sub-value-field' ); // $(this), array index, repeatable field class, hidden html structure wrapper

			} );

			// Remove subGroup
			$( '#pwc-settings' ).on( 'click', '.trash-icon', function( e ) {

				var $sure = confirm( 'Are you sure? This will remove all this sub group as well? ' );

				if ( $sure ) {
					var $parent = $( this ).parent().parent().parent(),
						$uids = $parent.find( '[data-uid]' );

					// Remove all images
					$uids.each( function( index, el ) {
						pwc.$preview.find( '[data-preview-uid="' + $( this ).data( 'uid' ) + '"]' ).remove();
						pwc.$preview.find( '[data-hotspot-uid="' + $( this ).data( 'uid' ) + '"]' ).remove();
					} );

					pwc.hideImageOptions();

					$parent.remove();

					pwc.$activeLayer = '';

				}
			} );

			// Collapse configuration
			$( '#pwc-settings' ).on( 'click', '.collapse-values', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var self = $( this );

				pwc.pwcCollapse( self, '.pwc-group-field', '.pwc-settings-sub-group-wrapper' ); // $(this), parent, collapse div

			} );

			$( '#pwc-settings' ).on( 'click', '.collapse-sub-values', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var self = $( this );

				pwc.pwcCollapse( self, '.pwc-sub-group-field', '.pwc-settings-sub-value-group-wrapper' ); // $(this), parent, collapse div

			} );

			/* layer: set current to layer and show option data on right side fields */
			$( '#pwc-settings' ).on( 'click', '.pwc-group-field, .pwc-sub-group-field', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var self = $( this ),
					uid = self.data( 'uid' ),
					$preview = pwc.$preview,
					$previewImg = $preview.find( '[data-preview-uid="' + uid + '"]' ),
					$settingsPanel = pwc.$settingsPanel;

				// Add active layer class
				$( '#pwc-settings .pwc-group-field, #pwc-settings .pwc-sub-group-field' ).removeClass( 'active-layer' );
				self.addClass( 'active-layer' );
				pwc.$activeLayer = self;

				var layerName = self.find( '.pwc-form-name .pwc-form-name-inner .component-input' ).val();

				pwc.$imageOptions.find( '#active-layer-name' ).text( layerName );

				// Add active layer to image
				$preview.find( '.pwc-preview-imgcon' ).removeClass( 'active-layer-image' );

				// remove active hotspot
				$preview.find( '.pwc-hotspot' ).removeClass( 'active-layer-hotspot' );

				if ( !self.data( 'locked' ) ) {

					if ( $previewImg.length ) {

						$previewImg.addClass( 'active-layer-image' );
						pwc.$activeInPreview = $previewImg;

					}

					if ( $preview.find( '#pwc-' + pwc.curImageView + ' .active-layer-image' ).length ) {
						pwc.showImageOptions();
					} else {
						pwc.hideImageOptions();
					}

				}

				// Get the value from the layers and apply to the dummy inputs
				pwc.getValFromLayers( self );

			} );

			/* Trigger layer when image clicked on preview */
			pwc.$preview.on( 'mousedown', '.pwc-preview-imgcon', function( e ) {
				e.stopPropagation();

				if ( $( this ).hasClass( 'active-layer-image' ) || $( this ).hasClass( 'preview-img-locked' ) ) {
					return;
				}

				var uid = $( this ).data( 'preview-uid' );

				pwc.$activeInPreview = '';

				pwc.$layerGroup.find( '[data-uid="' + uid + '"]' ).first().trigger( 'click' );
			} );

			// 2. on clicking hotspot, automatically select corresponding layer
			/* on clicking hotspot */
			pwc.$preview.on( 'mousedown', '.pwc-hotspot', function( e ) {
				e.stopPropagation();

				// if ( $( this ).hasClass( 'active-layer-hotspot' ) || $( this ).hasClass( 'hotspot-locked' ) ) {
				// 	return;
				// }


				pwc.$activeInPreview = $( this );

				var uid = $( this ).data( 'hotspot-uid' ),
					$oldActiveLayer = pwc.$layerGroup.find( '.active-layer' ),
					$activeLayer = pwc.$layerGroup.find( '[data-uid="' + uid + '"]' ).first(),
					$curActiveField = $activeLayer.find( '.' + pwc.curImageView + '-con' ),
					$sizeGroup = pwc.$tranformGroups.find( '#size-groups' ),
					$alignGroup = pwc.$tranformGroups.find( '#align-groups' ),
					AlignH, AlignV, $alignGroupHor, $alignGroupVer;

				pwc.$preview.find( '.pwc-hotspot' ).removeClass( 'active-layer-hotspot' ).end()
					.find( '[data-hotspot-uid="' + uid + '"]' ).addClass( 'active-layer-hotspot' ).end()
					.find( '.active-layer-image' ).removeClass( 'active-layer-image' );

				$oldActiveLayer.removeClass( 'active-layer' );
				$activeLayer.addClass( 'active-layer' );

				pwc.$activeLayer = $activeLayer;
				// Get the value from the layers and apply to the dummy inputs
				pwc.getValFromLayers( $activeLayer );

				

				$sizeGroup.find( '.show-pos-x' ).val( $curActiveField.find( '.field-hs-pos-x' ).val() );
				$sizeGroup.find( '.show-pos-y' ).val( $curActiveField.find( '.field-hs-pos-y' ).val() );

				AlignH = $curActiveField.find( '.field-hs-align-h' ).val();
				AlignV = $curActiveField.find( '.field-hs-align-v' ).val();

				$alignGroupHor = $alignGroup.find( '.align-left-right' );
				$alignGroupVer = $alignGroup.find( '.align-top-bottom' );

				// horizontal align
				$alignGroup.find( '.show-align-h' ).val( AlignH );
				$alignGroupHor.find( 'div[data-value]' ).removeClass( 'active' );
				$alignGroupHor.find( 'div[data-value="' + AlignH + '"]' ).addClass( 'active' );

				// Vertical Align
				$alignGroup.find( '.show-align-v' ).val( AlignV );
				$alignGroupVer.find( 'div[data-value]' ).removeClass( 'active' );
				$alignGroupVer.find( 'div[data-value="' + AlignV + '"]' ).addClass( 'active' );

				// enable image options
				pwc.$tranformGroups.removeClass( 'not-active' );
				pwc.$imageOptions.find( '#preview-settings' ).addClass( 'hide-fields' );

			} );

			/* Change image view (Front, lat, etc) on preview */
			$( '#pwc-image-options' ).on( 'change', '#select-image-view', function( e ) {

				var self = $( this ),
					val = self.val(),
					$activeImg = pwc.$preview.find( '#pwc-' + val + ' .active-layer-image' ),
					$activeHotspot = pwc.$preview.find( '#pwc-' + val + ' .active-layer-hotspot' ),
					prefix, uid, $hotspot;

				pwc.curImageView = val;

				pwc.$activeInPreview = ( $activeImg.length ) ? $activeImg : ( $activeHotspot.length ) ? $activeHotspot : '';

				//update hotspot btn
				if ( pwc.$activeLayer ) {
					uid = pwc.$activeLayer.data( 'uid' );
					$hotspot = pwc.$preview.find( '#pwc-' + val + ' [data-hotspot-uid="' + uid + '"]' );

					if ( $hotspot.length ) {
						pwc.$hsbtn.addClass( 'has-hotspot' );
					} else {
						pwc.$hsbtn.removeClass( 'has-hotspot' );
					}

				}

				// active layer image is in image view enable image option else disable
				if ( pwc.$activeInPreview.length ) {

					if ( pwc.$activeInPreview.is( '.pwc-hotspot' ) ) {
						prefix = 'hs-';
						pwc.$imageOptions.find( '#preview-settings' ).addClass( 'hide-fields' );
					} else {
						prefix = '';
						pwc.$imageOptions.find( '#preview-settings' ).removeClass( 'hide-fields' );
					}
					// Change input values to the appropriate layers
					pwc.onChangeView( self, val, prefix );

					pwc.showImageOptions();

				} else {
					pwc.hideImageOptions();
				}

				pwc.$preview.find( '#pwc-' + val ).addClass( 'active' ).siblings().removeClass( 'active' );

				pwc.$layerGroup.find( '.active-layer' ).trigger( 'click' );

			} );

			$( '#pwc-layer-options, #pwc-image-options' ).on( 'change', 'input, textarea, select', function( e ) {

				var self = $( this ),
					src = undefined,
					prefix = ( pwc.$activeInPreview && pwc.$activeInPreview.is( '.pwc-hotspot' ) ) ?
					'hs-' : '';

				if ( 'checkbox' == self.attr( 'type' ) ) {
					var val = self.prop( 'checked' );
				} else {
					var val = self.val();
				}

				// Change input values to the appropriate layers
				pwc.onChangeVal( self, val, prefix );

			} );

			//update value on draggable
			pwc.$tranformGroups.on( 'change:onDrag', 'input', function( e ) {

				var self = $( this ),
					src = undefined,
					prefix = '';

				if ( 'checkbox' == self.attr( 'type' ) ) {
					var val = self.prop( 'checked' );
				} else {
					var val = self.val();
				}

				if ( pwc.$activeInPreview && pwc.$activeInPreview.is( '.pwc-hotspot' ) ) {
					prefix = 'hs-'
				}

				// Change input values to the appropriate layers
				pwc.onChangeVal( self, val, prefix );

			} );

			$( '#pwc-settings-panel .select-image' ).pwcMediaInsert();

			$( '#pwc-settings-panel .select-image' ).on( 'preview:imageAdded', function( e, options ) {

				var show;

				$( this ).prev( '.selected-con' ).html( '<img src="' + options.imgurl + '" alt=""><span class="remove-img"><i class="pwc-cross"></i></span>' );

				// Add data src for preview
				show = $( this ).data( 'show' );
				var $activeLayer = $( '#pwc-settings' ).find( '.active-layer' );
				$activeLayer.find( '.' + show + '-con' ).data( 'src', options.imgurl );

				// for addtion input added via actions
				var $input = $activeLayer.find( '.field-' + show );

				if ( $input.data( 'viewfield' ) ) {
					$activeLayer.find( '.' + pwc.curImageView + '-con .field-' + show ).data( 'src', options.imgurl );
				} else {
					$input.data( 'src', options.imgurl );
				}

			} );

			$( '#pwc-settings-panel' ).on( 'click', '.remove-img', function( e ) {

				var $imgCon = $( this ).closest( '.selected-con' ),
					show;

				$imgCon.html( '' ).prev( '.pix-saved-val' ).val( '' ).change();

				show = $imgCon.next( '.select-image' ).data( 'show' );

				pwc.$activeLayer.find( '.' + show + '-con' ).removeAttr( 'data-src' );
			} );

			pwc.$preview.on( 'preview:imageAdded', function( e, options ) {

				var uid, zIndex = '',
					divID, $imgCon, $curLayer, $typeCon, $transGroup = pwc.$tranformGroups;

				// check is base image button
				if ( options.$btn.hasClass( 'base-img-btn' ) ) {
					uid = options.imgview + 'base-image';
					divID = 'pwc-base-' + options.imgview;
				} else {

					// current layer active, if not active do nothing
					if ( !pwc.$activeLayer ) {
						alert( 'choose a layer first!' );
						return;
					}

					uid = pwc.$activeLayer.data( 'uid' );
					zIndex = pwc.$activeLayer.find( '.' + options.imgview + '-con .field-z-index' ).val();
					divID = ( uid ) ? 'pwc-' + options.imgview + '-' + uid : 'id-not-set';
				}

				$imgCon = $( this ).find( '#' + divID );
				if ( !$imgCon.length ) {
					$imgCon = $( '<div />', {
						id: divID,
						class: 'pwc-preview-imgcon',
						'data-preview-uid': uid
					} );

					pwc.pwcPreviewDragAndResize( $imgCon );

				}

				if ( !pwc.$activeLayer.data( 'locked' ) ) {
					$imgCon.addClass( 'active-layer-image' );
					pwc.$preview.find( '[data-preview-uid="' + uid + '"]' ).addClass( 'active-layer-image' );

					if ( pwc.curImageView != options.imgview ) {
						pwc.$activeInPreview = pwc.$preview.find( '#pwc-' + pwc.curImageView + ' .active-layer-image' );
					}

				} else {
					$imgCon.draggable( 'disable' );
					$imgCon.addClass( 'preview-img-locked' );
				}

				if ( pwc.$activeLayer.data( 'hidden' ) ) {
					$imgCon.addClass( 'preview-img-hidden' );
				}

				$typeCon = pwc.$activeLayer.find( '.' + options.imgview + '-con' );
				$typeCon.find( '.field-width' ).val( options.width + 'px' );
				$typeCon.find( '.field-height' ).val( options.height + 'px' );
				$typeCon.find( '.field-z-index' ).val( zIndex );

				// Add values in image options
				if ( pwc.curImageView == options.imgview ) {

					// update width and height
					$transGroup.find( '.size-position #transform-w' ).val( options.width + 'px' );
					$transGroup.find( '.size-position #transform-h' ).val( options.height + 'px' );
					$transGroup.find( '.position-size #z-index' ).val( zIndex ).change();

					pwc.$activeInPreview = $imgCon;

				}

				$imgCon.html( '<img src="' + options.imgurl + '" width="' + options.width + 'px' + '" height="' + options.height + 'px' + '" alt="">' ) // add image position (Hcenter, Vcenter is default)
					.appendTo( $( this ).find( $( '#pwc-' + options.imgview ) ) );

				// remove active hotspot
				pwc.$preview.find( '[data-hotspot-uid="' + uid + '"]' ).removeClass( 'active-layer-hotspot' );

				// enable controls
				pwc.$settingsPanel.find( '.pwc-check-active' ).removeClass( 'not-active' );

				// set center center as default by trigger align controls and pass newly added image con to align
				$transGroup.find( '.align-left-right [data-value="center"]' ).trigger( 'click', [ $imgCon, options.imgview ] );
				$transGroup.find( '.align-top-bottom [data-value="middle"]' ).trigger( 'click', [ $imgCon, options.imgview ] );


			} );

			/* Align panel */
			$( '#pwc-image-options' ).on( 'click', '#align-groups div div', function( e, $curImage, addedImgView, prefix ) {

				var self = $( this ),
					value = self.data( 'value' ),
					$input = self.parent().find( 'input' ),
					$activeImage = $curImage,
					curview = addedImgView,
					$transGroup = pwc.$tranformGroups,
					fieldPrefix = prefix,
					$fieldCon;

				if ( typeof( curview ) == 'undefined' ) {
					curview = pwc.curImageView;
				}

				fieldPrefix = ( typeof( fieldPrefix ) == 'undefined' ) ? '' : fieldPrefix;

				$fieldCon = pwc.$activeLayer.find( '.' + curview + '-con' );

				self.addClass( 'active' ).siblings().removeClass( 'active' );

				// Add the value to the closest input
				$input.val( value );

				// query active image (if not passed on trigger)
				if ( typeof( $activeImage ) == 'undefined' ) {
					$activeImage = pwc.$activeInPreview;

					// .active-layer-image is not found check for .active-layer-hotspot
					// $activeImage = ( $activeImage.length ) ? $activeImage : pwc.$preview.find('#pwc-' + pwc.curImageView + ' .active-layer-hotspot');

					// .active-layer-image & .active-layer-hotspot is not found then return
					if ( !$activeImage.length ) {
						return;
					}

					if ( $activeImage.is( '.pwc-hotspot' ) ) {
						// set prefix to hs_
						fieldPrefix = 'hs-';
					}

				}

				$activeImage.css( 'position', 'absolute' );

				// change image postion
				if ( 'left' == value ) {

					$activeImage.css( {
						'left': '0px',
						'right': 'auto'
					} );

				} else if ( 'right' == value ) {

					$activeImage.css( {
						'right': '0px',
						'left': 'auto'
					} );

				} else if ( 'center' == value ) {

					$activeImage.css( {
						'left': ( ( pwc.previewWidth - $activeImage.width() ) / 2 ) + 'px',
						'right': 'auto'
					} );

				} else if ( 'middle' == value ) {

					$activeImage.css( {
						'top': ( ( pwc.previewHeight - $activeImage.height() ) / 2 ) + 'px',
						'bottom': 'auto'
					} );

					if ( pwc.curImageView == addedImgView ) {
						pwc.$tranformGroups.find( '#transform-y' ).val( '0px' );
					}

				} else if ( 'top' == value ) {

					$activeImage.css( {
						'top': '0px',
						'bottom': 'auto'
					} );
					pwc.$tranformGroups.find( '#transform-y' ).val( '0px' ).change();

				} else if ( 'bottom' == value ) {

					$activeImage.css( {
						'bottom': '0px',
						'top': 'auto'
					} );
					pwc.$tranformGroups.find( '#transform-y' ).val( '0px' ).change();

				}

				// save vaule in layer and image option
				if ( 'left' == value || 'right' == value || 'center' == value ) {

					$fieldCon.find( '.field-' + fieldPrefix + 'align-h' ).val( value );
					$fieldCon.find( '.field-' + fieldPrefix + 'pos-x' ).val( '0px' );
					if ( pwc.curImageView == curview ) {
						pwc.$tranformGroups.find( '#transform-x' ).val( '0px' );
					}

				} else if ( 'top' == value || 'bottom' == value || 'middle' == value ) {

					$fieldCon.find( '.field-' + fieldPrefix + 'align-v' ).val( value );
					$fieldCon.find( '.field-' + fieldPrefix + 'pos-y' ).val( '0px' );

					if ( pwc.curImageView == curview ) {
						pwc.$tranformGroups.find( '#transform-y' ).val( '0px' );
					}

				}

				// set left value in data so that we can update offest vaules easily
				$activeImage.attr( {
					'data-init-x': $activeImage.position().left,
					'data-init-y': $activeImage.position().top
				} );

				// Change input values to the appropriate layers
				pwc.onChangeVal( $input, value, fieldPrefix );

			} );

			// apply draggable for already saved and loaded element
			pwc.pwcPreviewDragAndResize( $( "#pwc-preview .pwc-preview-imgcon" ) );
			pwc.pwcPreviewDragAndResize( $( "#pwc-preview .pwc-hotspot" ) );

			$( '#pwc-settings-panel' ).on( 'click', '#pwc-save-btn', function( e ) {
				e.preventDefault();
				$( '#publish' ).trigger( 'click' ); // or we can use ajax to save data.
			} );

			$( '#pwc-settings-panel' ).on( 'click', '#pwc-add-view', function( e ) {
				e.preventDefault();
				$( '#publish' ).trigger( 'click' ); // or we can use ajax to save data.
			} );

			$( '#pwc-settings-panel' ).on( 'click', '.delete-view', function( e ) {
				e.preventDefault();
				var $self = $( this ),
					configurator_id = $self.closest( 'ul' ).data( 'config-id' ),
					index = $self.data( 'index' );

				if ( confirm( 'Please make sure you save the configurator!' ) ) {
					pwc.deleteView( configurator_id, index );
				}

			} );

			// remove publish on 'enter' key press
			$( '#post' ).on( 'keyup keypress', function( e ) {

				var $active = $( document.activeElement ),
					keyCode = e.keyCode || e.which;

				if ( keyCode === 13 ) {

					if ( $active.is( "input" ) ) {
						$active.blur();
					}

					if ( !$active.is( "textarea" ) ) {
						e.preventDefault();
						return false;
					}

				}

			} );

			// preview add or edit image
			pwc.$imageOptions.find( '#pwc-preview-add-edit-image' ).on( 'click', function() {

				if ( pwc.$preview.find( '.active-layer-image' ).length || pwc.$layerGroup.find( '.active-layer' ).length ) {

					var imageView = pwc.$imageOptions.find( '#select-image-view' ).val();

					pwc.$layerOptions.find( '#product-image [data-img-view="' + pwc.curImageView + '"]' ).trigger( 'click' );

				} else {
					alert( 'Please select layer first!' );
				}

			} );

			// preview remove image
			pwc.$imageOptions.find( '#pwc-preview-remove-image' ).on( 'click', function() {

				pwc.removeImage();

			} );

			// lock layers 
			pwc.$layerGroup.on( 'click', '.layer-lock-unlock', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var $this = $( this ),
					$parent = $( this ).parent().parent(),
					uid = $parent.data( 'uid' ),
					$previewImg = pwc.$preview.find( '[data-preview-uid="' + uid + '"]' ),
					$hotspot = pwc.$preview.find( '[data-hotspot-uid="' + uid + '"]' ),
					activateImgOptions = false;

				if ( !$previewImg.length ) {
					alert( "This layer doesn't contain product image to lock/unlock" );
					return;
				}

				if ( !$parent.data( 'locked' ) ) {

					$parent.data( 'locked', true );

					$previewImg.removeClass( 'active-layer-image' ).draggable( "disable" ).addClass( 'preview-img-locked' );
					$hotspot.removeClass( 'active-layer-hotspot' ).draggable( "disable" ).addClass( 'hotspot-locked' );

					pwc.hideImageOptions();

				} else {

					$parent.data( 'locked', false );

					$previewImg.draggable( "enable" ).removeClass( 'preview-img-locked' );
					$hotspot.draggable( "enable" ).removeClass( 'hotspot-locked' );

					if ( $parent.hasClass( 'active-layer' ) ) {
						$previewImg.addClass( 'active-layer-image' );
						activateImgOptions = true;
					}

				}

				$( this ).toggleClass( 'active' );

				if ( $parent.hasClass( 'active-layer' ) && activateImgOptions ) {
					$parent.trigger( 'click' );
				}

			} );

			// Lock option in preview
			pwc.$settingsPanel.find( '#pwc-preview-lock-unlock' ).on( 'click', function( event ) {
				event.preventDefault();

				var $curPreviewImage = pwc.$preview.find( '#pwc-' + pwc.curImageView + ' .active-layer-image' ),
					uid;

				if ( !$curPreviewImage.length ) {
					return;
				}

				uid = $curPreviewImage.data( 'preview-uid' );

				pwc.$layerGroup.find( '[data-uid="' + uid + '"]' ).find( '.layer-lock-unlock' ).trigger( 'click' );
			} );

			// Hide layers 
			pwc.$layerGroup.on( 'click', '.layer-show-hide', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var $this = $( this ),
					$parent = $( this ).parent().parent(),
					uid = $parent.data( 'uid' ),
					$previewImg = pwc.$preview.find( '[data-preview-uid="' + uid + '"]' ),
					$hotspot = pwc.$preview.find( '[data-hotspot-uid="' + uid + '"]' ),
					activateImgOptions = false;

				if ( !$previewImg.length ) {
					alert( "This layer doesn't contain product image to show/hide" );
					return;
				}

				if ( !$parent.data( 'hidden' ) ) {

					$parent.data( 'hidden', true );

					$previewImg.removeClass( 'active-layer-image' ).addClass( 'preview-img-hidden' );
					$hotspot.removeClass( 'active-layer-hotspot' ).addClass( 'hotspot-hidden' );

					pwc.hideImageOptions();

				} else {

					$parent.data( 'hidden', false );

					$previewImg.removeClass( 'preview-img-hidden' );
					$previewImg.removeClass( 'hotspot-hidden' );

					if ( $parent.hasClass( 'active-layer' ) ) {
						activateImgOptions = true;
					}

				}

				$( this ).toggleClass( 'active' );

				if ( $parent.hasClass( 'active-layer' ) && activateImgOptions ) {
					$parent.trigger( 'click' );
				}

			} );

			// key press remove image
			$( document ).on( 'keydown', function( e ) {

				var $active = $( document.activeElement ),
					keyCode = e.keyCode || e.which;

				// remove image if delete pressed
				if ( keyCode == 46 && !( $active.is( "input" ) || $active.is( "textarea" ) ) ) {
					pwc.removeImage();
				}

				// update Image options on change
				if ( ( $active.is( "#transform-groups input" ) ) ) {
					pwc.updateTransformGroupFields( e, keyCode, $active );
				} else {

					if ( pwc.previewfocus && !( $active.is( "input" ) || $active.is( "textarea" ) ) ) {
						pwc.transformPreviewImage( e, keyCode );
					}

				}

			} );

			/* when tranform input changes, update active image on */
			pwc.$tranformGroups.on( 'change', '#transform-x, #transform-y', function( e ) {

				var val = parseFloat( $( this ).val(), 10 ),
					initVal,
					$curPreviewImage = pwc.$activeInPreview;

				if ( !$curPreviewImage.length ) {
					return;
				}

				if ( $( this ).hasClass( 'show-pos-x' ) ) {

					initVal = parseFloat( $curPreviewImage.data( 'init-x' ) );

					$curPreviewImage.css( {
						left: initVal + val
					} );

				} else if ( $( this ).hasClass( 'show-pos-y' ) ) {

					initVal = parseFloat( $curPreviewImage.data( 'init-y' ) );

					$curPreviewImage.css( {
						top: initVal + val
					} );

				}

			} );

			/* when width/height input changes, update active image on */
			pwc.$tranformGroups.on( 'change', '#transform-w, #transform-h', function( e ) {

				var val = parseFloat( $( this ).val(), 10 ),
					initVal,
					$curPreviewImage = pwc.$preview.find( '#pwc-' + pwc.curImageView + ' .active-layer-image' );

				if ( !$curPreviewImage.length ) {
					return;
				}

				if ( $( this ).hasClass( 'show-width' ) ) {

					$curPreviewImage.find( 'img' ).css( {
						width: val
					} );

				} else if ( $( this ).hasClass( 'show-height' ) ) {

					$curPreviewImage.find( 'img' ).css( {
						height: val
					} );

				}

			} );

			/* change focus */
			pwc.$preview.on( 'mouseenter', function( e ) {
				pwc.previewfocus = true;
			} );

			pwc.$tranformGroups.on( 'mouseenter', function( e ) {
				pwc.previewfocus = true;
			} );

			/* change focus */
			pwc.$preview.on( 'mouseleave', function( e ) {
				pwc.previewfocus = false;
			} );

			pwc.$tranformGroups.on( 'mouseleave', function( e ) {
				pwc.previewfocus = false;
			} );

			// Added px to transform inputs
			pwc.$tranformGroups.find( 'input' ).on( 'blur', function( e ) {
				var val = $( this ).val();

				if ( val && $.isNumeric( parseInt( val, 10 ) ) ) {

					if ( $( this ).is( '.show-z-index' ) ) {
						$( this ).val( val );
					} else {
						$( this ).val( parseInt( val, 10 ) + 'px' ).change();
					}

				} else {

					if ( $( this ).is( '.show-z-index' ) ) {
						$( this ).val( '' ).change();
					} else {
						$( this ).val( '0px' ).change();
					}

				}

			} );

			// select text inputs on click
			$( document ).on( "focus", "input[type='text']", function() {
				$( this ).select();
			} );

			$( '#inspiration-wrap' ).on( 'click', '.ins-list', function( e ) {

				e.preventDefault();
				e.stopPropagation();

				var $self = $( this );

				$self.toggleClass( 'selected' );
				$self.find( 'span' ).toggleClass( 'pwc-tick' );

			} );


			/* Code Editor */
			var $textarea = $( '#css_editor' );

			if ( $textarea.length ) {

				var editor = wp.codeEditor.initialize( $textarea );

				// Improve the editor accessibility.
				$( editor.codemirror.display.lineDiv )
					.attr( {
						role: 'textbox',
						'aria-multiline': 'true',
						'aria-describedby': 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4'
					} );

			}


		},

	};

	$( document ).ready( function( $ ) {
		window.initPWC = new $.PWC();
	} );

} )( jQuery, window, document );