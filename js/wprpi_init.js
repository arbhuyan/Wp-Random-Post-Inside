(function( $ ) { 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });

    // show list of icons if checked show icon
    $('#wprpi_show_icon').on('click', function(){
    	if ($("#wprpi_show_icon").is(':checked')) {
	    	$('#show_wprpi_icon').removeClass('wprpi_hide');
	    } else {
	    	$('#show_wprpi_icon').addClass('wprpi_hide');
	    }
    });

})( jQuery );