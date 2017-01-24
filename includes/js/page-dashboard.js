$(function() {
	// Empty Wrap
});



// AJAX FUNCTIONS
var ajax_url = 'functions/ajax_system.php'; //Used by all ajax request in this script

function reboot_orp_system() {
    $.ajax({ type: 'POST', dataType: 'text', url: ajax_url, data: { post_service: 'system', post_option: 'restart' } });	   
	alert('Reboot Command Sent, Please wait up to a minute before loading/reloading pages.');

}

function shutdown_orp_system() {
    $.ajax({ type: 'POST', dataType: 'text', url: ajax_url, data: { post_service: 'system', post_option: 'stop' } });	   
	alert('Shutdown Command Sent.');
}


function toggleRepeaterState() {
	svxlink_toggle_check_status('svxlink','status');
}



function svxlink_toggle_check_status(service, option) {
    $.ajax({ async: true, type: 'POST', dataType: 'text', url: ajax_url,
        data: { post_service: service, post_option: option },
        success: function (status) {
			var status = $.trim(status);
			console.log("Status: " + status);			
			if(status==="active") {
				// Shut it down
				svxlink_toggle_state('svxlink','stop');
				console.log("Stop");
			} else {
				// Start it ups
				svxlink_toggle_state('svxlink','start');
				console.log("Start");
			}	
        }
    });	   
};

function svxlink_toggle_state(service, option) {
    $.ajax({ async: true, type: 'POST', dataType: 'text', url: ajax_url,
        data: { post_service: service, post_option: option },
        success: function (state) {
			var state = $.trim(state);
			if(state==="active") {
				$( "#rptControlBtn" ).html( '<i class="icon-stop"></i> Stop Repeater' );
				$( ".rptStatus" ).html( '<span class="label label-success">Active</span>' );
			} else if(state==="failed") {
				$( "#rptControlBtn" ).html( '<i class="icon-play"></i> Start Repeater' );
				$( ".rptStatus" ).html( '<span class="label label-warning">FAILED</span> - <a href="log.php">View Log</a>' );
			} else {
				$( "#rptControlBtn" ).html( '<i class="icon-play"></i> Start Repeater' );
				$( ".rptStatus" ).html( '<span class="label label">Deactivated</span>' );
			}	
        }
    });	   
};
