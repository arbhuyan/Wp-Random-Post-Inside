!(function($){
	$(document).ready(function(){
		$('.wprpi-notice').on('click', '.notice-dismiss', function(){
	        var url = new URL(location.href);
	        url.searchParams.append("wprpi_notice_dismiss", 1);
	        location.href = url;
	    });

	    // show list of icons if checked show icon
	    $('#wprpi_show_icon').on('click', function(){
	    	if ($("#wprpi_show_icon").is(':checked')) {
		    	$('#show_wprpi_icon').removeClass('wprpi_hide');
		    } else {
		    	$('#show_wprpi_icon').addClass('wprpi_hide');
		    }
	    });

	    // checbox selection
	    $(".select_all_cpt").click(function(){
	        $(".cpt_checkbox").prop("checked", $(this).prop("checked"));
	    });

	    $('.cpt_checkbox').click(function(){
	        if($('.cpt_checkbox:checked').length == $('.cpt_checkbox').length){
	            $('.select_all_cpt').prop('checked',true);
	        }else{
	            $('.select_all_cpt').prop('checked',false);
	        }
	    });

	    $( '.wprpi-expand-faq' ).click(function() {
	        var faq_content = $(this).closest('.wprpi_info').find('.wprpi-faq-info');
	        $( faq_content ).slideToggle( 'slow' );
	    });
	});
})(jQuery);