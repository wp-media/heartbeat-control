( function( $ ){
    'use strict';
    function switch_tab( tab ){
        var active_tab = ( tab )?tab:( window.location.hash )?window.location.hash:'#tab1';
		history.replaceState(null, null, active_tab);
		$( '.nav-tab' ).removeClass( 'nav-tab-active' );
        $( 'a[href^="'+active_tab+'"]' ).addClass( 'nav-tab-active' );
        $( '.tab' ).hide();
        $( '.tab' ).removeClass( 'tab-active' );
        $( active_tab ).show();
        $( active_tab ).addClass( 'tab-active' );
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
        $( '.nav-tab' ).click(function(){ 
			event.preventDefault();
			switch_tab( $( this ).attr('href') ); });
        maybeHideFrequency();
        bindChangeEvent();
        switch_tab();
    });


    //video subtitles
    window._wq = window._wq || [];
    _wq.push({
      id: "s3jveyzr5h",
      options: {
        plugin: {
          "captions-v1": {
            onByDefault: true,
            
          }
        }
      }
    });

})( jQuery );
