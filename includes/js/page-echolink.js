$(function() {

	$('#echolinkSettings').on('change', function() {
	    //submit changes to db
	    var $form = $("#echolinkSettings");
	    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
	    $.ajax({
	        url: $form.attr("action"),
	        data: $form.serialize(),
	        type: method,
	        success: function() {
				$('.server_bar_wrap').show(); 
	        }
	    });
    });

});