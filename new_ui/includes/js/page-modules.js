$(function() {

	$("#moduleListSort").sortable({
		placeholder: "ui-sortable-placeholder",
		update: function(event, ui) {
			$('#moduleListSort .moduleRow').each(function(i) {
				var pid = $(this).attr('data-module-id');
				var svxid = (i + 1) + '';
				$(this).attr('data-svxlink-id', svxid);
				$(this).find('.svxlinkID').html(svxid);
				$(this).find('.largeDigit').html(svxid);
/*
                var humanNum = i + 1;
                $(this).html(humanNum + '');
				var parentID = $(this).parent('.moduleRow').attr('data-module-id');
*/
				// 						console.log(this);
			});
		},
	});



});

$(document).ready(function() {
    $('.modActive').change(function() {
        if(this.checked) {
/*
            var returnVal = confirm("Are you sure?");
            $(this).prop("checked", returnVal);
*/
	        console.log('checked');
	        $(this).parents('.moduleRow').removeClass('deactive');
        } else {
	        console.log('not checked');
	        $(this).parents('.moduleRow').addClass('deactive');
        }
    });
});
