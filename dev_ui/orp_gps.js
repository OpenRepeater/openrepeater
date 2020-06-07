// Main Scripts
$(function() {
	$(".gps_get_coordinates").live("click", function(e) {
		e.preventDefault();

		$('.gps_status').html('please wait...');

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_orp_helper.php',
			data: { type: 'get_gps' },
			dataType: 'JSON',
			success: function(response) {
				var status = response.status;

				console.log (response);

				if (status == 'gps_geo') {
					$('#gps_lat').val(response.lat);
					$('#gps_long').val(response.lon);
					$('.gps_status').html('GPS acquisition successful');

				} else if (status == 'ip_geo') {
					var ip_addr = response.ip;
					$('#gps_lat').val(response.lat);
					$('#gps_long').val(response.lon);
					$('.gps_status').html('Results based on IP: ' + ip_addr);

				} else if (status == 'nofix') {
					$('.gps_status').html('There was a problem getting a GPS or IP based fix');

				} else {
					$('.gps_status').html('No Results');					

				}
				
			}
		});

	});

});