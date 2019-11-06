( function( $ ){
	'use strict';
	function switch_tab( tab ){
		var tabs = $( 'span[data-tab]' ).map( function( i ,e ){ return $( e ).attr( 'data-tab' ); }).get();
		var previous_tab = tabs.includes( getUrlParam( "tab" ) )?getUrlParam( "tab" ):tabs[0];
		var active_tab = ( tabs.includes( tab ) )?tab:previous_tab;
		setUrlParam( 'tab', active_tab );

		$( '.nav-tab' ).removeClass( 'nav-tab-active' );
		$( 'span[data-tab="'+active_tab+'"]' ).addClass( 'nav-tab-active' );
		$( '.tab' ).hide();
		$( '.tab' ).removeClass( 'tab-active' );
		$( '#tab_'+active_tab ).show();
		$( '#tab_'+active_tab ).addClass( 'tab-active' );

		update_referer( previous_tab, active_tab, getUrlParam("page") );

	}

	function getUrlParam( param_name ){
		var regex = RegExp(param_name+'=([^&]+)','g');
		var r = regex.exec( location.href );
		if( null !== r ){
			return r[1];
		}
		return false;
	}

	function setUrlParam( param_name, param_value ){
		var tr = getUrlParam( param_name );
		var url = window.location.href;
		if( tr ){
			url = url.replace( param_name+'='+tr, param_name+'='+param_value );
		}else{
			var s = ( -1 === url.indexOf('?') )?'?':'&';
			url += s+param_name+'='+param_value;
		}
		history.replaceState( null, null, url );
	}

	function update_referer( previous_tab, active_tab, page_settings ){
		$( '.referer-link' ).each(function( i, e ) {
			var href = $(e).attr('href');
			if( -1 !== href.search( '%26tab%3D'+previous_tab ) ){
				$(e).attr( 'href', href.replace( previous_tab, active_tab ) );
			}else{
				$(e).attr( 'href', href.replace( page_settings, page_settings+'%26tab%3D'+active_tab ) );
			}
		});
	}
	
	function maybeHideFrequency() {
		$('.heartbeat_behavior input[type=radio]:checked').each(function() {
			if (this.value !== 'modify') {
				$(this).closest('.cmb-repeatable-grouping').find('.heartbeat_frequency').hide();
			} else {
				$(this).closest('.cmb-repeatable-grouping').find('.heartbeat_frequency').show();
			}
		});
	}

	function bindChangeEvent() {
		$('.heartbeat_behavior input[type=radio]').change(function() {
			maybeHideFrequency();
		});
	}

	//slider
	function initRow(row) {

		// Loop through all cmb-type-slider-field instances and instantiate the slider UI
		row.each(function() {
			var $this = $(this);
			var $value = $this.find('.slider-field-value');
			var $slider = $this.find('.slider-field');
			var $text = $this.find('.slider-field-value-text');
			var slider_data = $value.data();

			$slider.slider({
				range: 'min',
				value: slider_data.start,
				min: slider_data.min,
				max: slider_data.max,
				step: slider_data.step,
				slide: function(event, ui) {
					$value.val(ui.value);
					$text.text(ui.value);
				}
			});

			// Initiate the display
			$value.val($slider.slider('value'));
			$text.text($slider.slider('value'));
		});

		maybeHideFrequency();
		bindChangeEvent();
	}

	//initialisation
	$(document).ready(function() {
		$( '.cmb-type-slider' ).each(function(){ initRow( $(this) ); });
		$( '.nav-tab' ).click(function(){ switch_tab( $( this ).attr('data-tab') ); });
		maybeHideFrequency();
		bindChangeEvent();
		switch_tab();
					
		//video subtitles
		window._wq = window._wq || [];
		_wq.push({
			id: "s3jveyzr5h",
			options: {
				plugin: {
					"captions-v1": { onByDefault: true, }
				}
			}
		});
		
	});

})( jQuery );
