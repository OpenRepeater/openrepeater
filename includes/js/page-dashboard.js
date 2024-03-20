$(function() {
	// Initial data load
	updateSVXLink();
	updateMemory();
	updateDisk();
	updateSystemStatic();
	updateSystemDynamic();

	// Begin update cycle
		updateTimeInterval = setInterval(updateTime, 1000);
		updateInfoInterval = setInterval(refreshData, 15000); // Every 15 seconds
		updateSVXLinkInterval = setInterval(updateSVXLink, 60000); // Every 1 minute
	
	// Timeout Functions
	timoutMinutes = 10;

	setTimeout(() => { 
		clearInterval(updateTimeInterval); 
		clearInterval(updateInfoInterval); 
		clearInterval(updateSVXLinkInterval ); 
	}, timoutMinutes * 60000); // Timeout after number of minutes X millisections per minute;

	setTimeout(() => { 
		alert('You\'ve been on this page too long. To reduce demand on the system, auto updating of information has stopped. To start auto updating again, please reload this page.'); 
	}, timoutMinutes * 60000 + 10000); // Timeout after number of minutes X millisections per minute + delay for alert;
});



/*****************************************************************************/
/* TIME FUNCTIONS */

var startTime = 0;

function updateTime(){
	if ( $( '#cur_time' ).length ) {
		// Time section loaded...update values
		startTime.setSeconds(startTime.getSeconds() + 1);
		$('#cur_date').html($.datepicker.formatDate('dd M yy', startTime));
		$('#cur_time').html( formatTime(startTime) );
	}
}

function formatTime(unixTimestamp){
    var dt = new Date(unixTimestamp * 1);

    var hours = dt.getHours();
    var minutes = dt.getMinutes();
    var seconds = dt.getSeconds();

    // prepend the zero here when needed
    if (hours < 10) 
     hours = '0' + hours;

    if (minutes < 10) 
     minutes = '0' + minutes;

    if (seconds < 10) 
     seconds = '0' + seconds;

    return hours + ":" + minutes + ":" + seconds;
}



/*****************************************************************************/
/* UPDATE SECTION FUNCTIONS */

function refreshData() {
		updateSystemDynamic();
		updateMemory();

	// Wait for intial data to load before allowing updates. 
/*
	if ( $( '#cur_time' ).length ) {
		updateSystemDynamic();
		updateMemory();
	}
*/
}


var ajax_handler_path = "/functions/ajax_system.php";

function updateSystemStatic(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    var sysStatic = JSON.parse(this.responseText);
 	 		startTime = new Date(sysStatic.datetime);
console.log('SYSTEM STATTIC: ' + this.responseText);
$( '#sysStatic' ).html(this.responseText);
//  	 		$('#system_static').html( sysStatic_structure(sysStatic) ); 	 		
		}
	};
	xmlhttp.open("GET", ajax_handler_path + "?update=systemStatic", true);
	xmlhttp.send();
}

function updateSystemDynamic(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    var sysDynamic = JSON.parse(this.responseText);
console.log('SYSTEM INFO: ' + this.responseText);
$( '#sysDynamic' ).html(this.responseText);
/*
			if ( $( '#cpu_speed' ).length ) {
				// Dynamic system section loaded...update values
	 	 		$('#cpu_speed').html(sysDynamic.cpu_speed);
		 		$('#cpu_load').html(sysDynamic.cpu_load);
		 		$('#bar5').width( sysDynamic.cpu_load );	
		 		$('#cpuTempBoth').html(sysDynamic.cpuTempBoth);
		 		$('#uptime').html(sysDynamic.uptime);

			} else {
				// Dynamic system section not loaded yet...load it
	 	 		$('#system_dynamic').html( sysDynamic_structure(sysDynamic) );
 	 		}
*/
		}
	};
	xmlhttp.open("GET", ajax_handler_path + "?update=systemDynamic", true);
	xmlhttp.send();
}

function updateSVXLink(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    var status = JSON.parse(this.responseText);
console.log('SVXLINK INFO: ' + status);
$( '#svxlinkInfo' ).html(status);
//  	 		$('#svxlink_info').html( svxlink_structure(status) );
		}
	};
	xmlhttp.open("GET", ajax_handler_path + "?update=svxlink", true);
	xmlhttp.send();
}

function updateMemory(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    var memoryInfo = JSON.parse(this.responseText);
console.log('MEMORY: ' + this.responseText);
$( '#memoryInfo' ).html(this.responseText);
/*
			if ( $( '#total_mem' ).length ) {
				// Memory section loaded...update values
		 		$('#used_mem').html(memoryInfo.used_mem);
		 		$('#percent_used').html(memoryInfo.percent_used);
		 		$('#bar1').width( memoryInfo.percent_used+'%' );
	
		 		$('#free_mem').html(memoryInfo.free_mem);
		 		$('#percent_free').html(memoryInfo.percent_free);
		 		$('#bar2').width( memoryInfo.percent_free+'%' );
	
		 		$('#buffer_mem').html(memoryInfo.buffer_mem);
		 		$('#percent_buff').html(memoryInfo.percent_buff);
		 		$('#bar3').width( memoryInfo.percent_buff+'%' );
	
		 		$('#cache_mem').html(memoryInfo.cache_mem);
		 		$('#percent_cach').html(memoryInfo.percent_cach);
		 		$('#bar4').width( memoryInfo.percent_cach+'%' );
	 						
			} else {
				// Memory section not loaded yet...load it
	 	 		$('#memory_info').html( memory_structure(memoryInfo) );				
			}
*/

		}
	};
	xmlhttp.open("GET", ajax_handler_path + "?update=memory", true);
	xmlhttp.send();
}

function updateDisk(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		    var diskInfo = JSON.parse(this.responseText);
console.log('DISK INFO: ' + this.responseText);
$( '#diskInfo' ).html(this.responseText);
//  	 		$('#disk_info').html( disk_structure(diskInfo) );
		}
	};
	xmlhttp.open("GET", ajax_handler_path + "?update=disk", true);
	xmlhttp.send();
}



/*****************************************************************************/
/* MISC FUNCTIONS */


// AJAX FUNCTIONS
var ajax_url = 'functions/ajax_system.php'; //Used by all ajax request in this script

function reboot_orp_system() {
    $.ajax({ type: 'POST', dataType: 'text', url: ajax_url, data: { post_service: 'system', post_option: 'restart' } });	   
	clearInterval(updateTimeInterval); 
	clearInterval(updateInfoInterval); 
	reboot_countdown();
}

function reboot_countdown() {
	$('#overlay').show();
	$('#overlay .msg_1').html('Rebooting');

	$('#overlay .msg_2').html('60');

	var doCountdown = function() {
		$('#overlay .msg_2').each(function() {
			var count = parseInt($(this).html());
			if (count !== 1) {
				$(this).html(count - 1);
			} else {
				$('#overlay .msg_1').html('Please Wait...<br>Reloading Page');
				$('#overlay .msg_2').hide();
				window.location.reload(true);
				clearInterval(countDownInterval);
			}
		});
	};
	countDownInterval = setInterval(doCountdown, 1000);
}



function shutdown_orp_system() {
    $.ajax({ type: 'POST', dataType: 'text', url: ajax_url, data: { post_service: 'system', post_option: 'stop' } });	   
	clearInterval(updateTimeInterval); 
	clearInterval(updateInfoInterval); 
	shutdown_countdown();
}

function shutdown_countdown() {
	$('#overlay').show();
	$('#overlay .msg_1').html('Shutdown');

	$('#overlay .msg_2').html('30');

	var doCountdown = function() {
		$('#overlay .msg_2').each(function() {
			var count = parseInt($(this).html());
			if (count !== 1) {
				$(this).html(count - 1);
			} else {
				$('#overlay .msg_1').html('<strong>System Shutdown</strong><br><em>It is now safe to power down the controller</em>');
				$('#overlay .msg_2').hide();
				clearInterval(countDownInterval);
			}
		});
	};
	countDownInterval = setInterval(doCountdown, 1000);
}



function toggleRepeaterState() {
	$('#rptStatus').html('<img src="theme/img/ajax-loaders/ajax-loader-1.gif">');
	svxlink_toggle_check_status('svxlink','status');
}


function svxlink_toggle_check_status(service, option) {
    $.ajax({ async: true, type: 'POST', dataType: 'text', url: ajax_url,
        data: { post_service: service, post_option: option },
        success: function (status) {
			var status = $.trim(status);
			if(status==="active") {
				// Shut it down
				svxlink_toggle_state('svxlink','stop');
			} else {
				// Start it ups
				svxlink_toggle_state('svxlink','start');
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
				$( "#rptStatus" ).html( '<span class="label label-success">Active</span>' );
			} else if(state==="failed") {
				$( "#rptControlBtn" ).html( '<i class="icon-play"></i> Start Repeater' );
				$( "#rptStatus" ).html( '<span class="label label-warning">FAILED</span> - <a href="log.php">View Log</a>' );
			} else {
				$( "#rptControlBtn" ).html( '<i class="icon-play"></i> Start Repeater' );
				$( "#rptStatus" ).html( '<span class="label label">Deactivated</span>' );
			}	
        }
    });	   
};


/*****************************************************************************/
/* BUILD INTIAL DISPLAY FUNCTIONS */

/*
function sysStatic_structure(systemInfo){
	var sysinfo_html = '<div class="info_label">Hostname:</div><div class="info_value" id="host">' + systemInfo.host + '</div><div class="info_clear"></div>';
	sysinfo_html += '<div class="info_label">System Time:</div><div class="info_value"><span id="cur_date">' + systemInfo.date + '</span><br>';
	sysinfo_html += '<span id="cur_time">' + systemInfo.time + '</span> ' + systemInfo.tz_short + '</div><div class="info_clear"></div>';
	sysinfo_html += '<div class="info_label">Kernel:</div><div class="info_value" id="kernel">' + systemInfo.kernel + '</div><div class="info_clear"></div>';
	sysinfo_html += '<div class="info_label">CPUs/Cores:</div><div class="info_value" id="cpu_cores">' + systemInfo.cpu_cores + '</div><div class="info_clear"></div>';

	return sysinfo_html;
}
*/


/*
function sysDynamic_structure(systemInfo){
	var sysinfo_html = '<div class="info_label">CPU Frequency:</div><div class="info_value" id="cpu_speed">' + systemInfo.cpu_speed + '</div><div class="info_clear"></div>';
	sysinfo_html += '<div class="mem_group"><div>';
	sysinfo_html += '<span class="bar_left"><strong>CPU Load:</strong></span>';
	sysinfo_html += '<span class="bar_right"><span id="cpu_load">' + systemInfo.cpu_load + '</span></span></div>';
	sysinfo_html += '<div class="bar_wrap"><div id="bar5" style = "width:' + systemInfo.cpu_load + '%"></div></div>'
	sysinfo_html += '</div>';

	// Hide on boards that don't support this
	if (systemInfo.cpuTempF != '32°F') {
		sysinfo_html += '<div class="info_label">CPU Temperature:</div>';
		sysinfo_html += '<div class="info_value" id="cpuTempBoth">' + systemInfo.cpuTempBoth + '</div>';
		sysinfo_html += '<div class="info_clear"></div>';
	}

	sysinfo_html += '<div class="info_label">Uptime:</div><div class="info_value" id="uptime">' + systemInfo.uptime + '</div><div class="info_clear"></div>';
	
	return sysinfo_html;
}
*/


/*
function svxlink_structure(svxlink_status){
	if (svxlink_status == 'active') {
		var status_string = '<span class="label label-success">Active</span>';
		var control_btn_text = '<i class="icon-stop"></i> Stop Repeater';
	} else if (svxlink_status == 'failed') {
		var status_string = '<span class="label label-warning">FAILED</span> - <a href="log.php">View Log</a>';							
		var control_btn_text = '<i class="icon-play"></i> Start Repeater';
	} else {
		var status_string = '<span class="label label">Deactivated</span>';
		var control_btn_text = '<i class="icon-play"></i> Start Repeater';
	}

	var svxlink_html = '<div class="info_label">SVXLink Status:</div>';
	svxlink_html += '<div id="rptStatus" class="info_value" >' + status_string + '</div>';
	svxlink_html += '<div class="info_clear"></div>';
	svxlink_html += '<button id="rptControlBtn" class="btn" onclick="toggleRepeaterState();">' + control_btn_text + '</button>';

	return svxlink_html;
}
*/


/*
function memory_structure(memArray){
	var memory_html = '<div class="mem_group"><div>';
	memory_html += '<span class="bar_left"><strong>Used: <span id="percent_used">' + memArray.percent_used + '</span>%</strong></span>';
	memory_html += '<span class="bar_right"><strong>Free: <span id="percent_free">' + memArray.percent_free + '</span>%</strong></span>';
	memory_html += '</div>';
	memory_html += '<div class="bar_wrap ram"><div id="bar1" style = "width:' + memArray.percent_used + '%"></div>';
	memory_html += '</div><div>';
	memory_html += '<span class="bar_left"><span id="used_mem">' + memArray.used_mem + '</span></span>';
	memory_html += '<span class="bar_right"><span id="free_mem">' + memArray.free_mem + '</span></span>';
	memory_html += '</div></div>';
	memory_html += '<div id="total_mem">Total Memory: <span>' + memArray.total_mem + '</span></div>';
	memory_html += '<hr>';
	memory_html += '<div class="left_col"><div class="mem_group"><div>';
	memory_html += '<span class="bar_left"><strong>Buffered:</strong></span>';
	memory_html += '<span class="bar_right"><strong><span id="percent_buff">' + memArray.percent_buff + '</span>%</strong></span>';
	memory_html += '</div>';
	memory_html += '<div class="bar_wrap"><div id="bar3" style = "width:' + memArray.percent_buff + '%"></div></div>';
	memory_html += '<div><span class="bar_left"><span id="buffer_mem">' + memArray.buffer_mem + '</span></span></div>';
	memory_html += '</div></div>';
	memory_html += '<div class="right_col"><div class="mem_group"><div>';
	memory_html += '<span class="bar_left"><strong>Cached:</strong></span>';
	memory_html += '<span class="bar_right"><strong><span id="percent_cach">' + memArray.percent_cach + '</span>%</strong></span>';
	memory_html += '</div>';
	memory_html += '<div class="bar_wrap"><div id="bar4" style = "width:' + memArray.percent_cach + '%"></div></div>';
	memory_html += '<div><span class="bar_left"><span id="cache_mem">' + memArray.cache_mem + '</span></span></div>';
	memory_html += '</div></div>';
	memory_html += '<div class="info_clear"></div>';
	
	return memory_html;
}
*/


/*
function disk_structure(disks){
	var disk_html = '';

	for(curDisk in disks){
		if (curDisk > 1) { disk_html += '<hr>'; }
		disk_html += '<div class="drive_label">' + disks[curDisk].mount + ' (' + disks[curDisk].typex + ')' + '</div>';
		disk_html += '<span class="bar_left"><strong>Used: <span id="percent_used">' + disks[curDisk].percent + '</span></strong></span>';
		disk_html += '<span class="bar_right"><strong>Free: <span id="percent_free">' + disks[curDisk].percentFree + '</span></strong></span>';
		disk_html += '</div>';
		disk_html += '<div class="bar_wrap ram"><div id="bar1" style = "width:' + disks[curDisk].percent + '"></div>';
		disk_html += '</div><div>';
		disk_html += '<span class="bar_left"><span id="used_mem">' + disks[curDisk].used + '</span></span>';
		disk_html += '<span class="bar_right"><span id="free_mem">' + disks[curDisk].avail + '</span></span>';
		disk_html += '</div></div>';
		disk_html += '<div class="total_size">Capacity: <span>' + disks[curDisk].capacity + '</span></div>';

	}

	return disk_html;
}
*/