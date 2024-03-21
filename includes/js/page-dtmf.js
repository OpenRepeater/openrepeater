$(function() {
    // If anchor link passed in url, open accordion and scroll to location
/*
	if(window.location.hash) {
	    var urlHash = window.location.hash;
	    var anchorID = urlHash.substring(urlHash.indexOf("#")+1);
		var collapseID = '#collapse-' + anchorID;
		$(collapseID).collapse('show'); // Open Accordion
 		$("body,html").animate( { scrollTop: $('#'+anchorID).offset().top }, 800 ); // Scroll to section anchor ID		
	}
*/

	$('#dialPadBtn').click(function(e) {
		var modal_DialPadTitle = 'Dial Pad';

		var modal_DialPadBody = '<label for="logic_section">Logic Section</label>';
		modal_DialPadBody += '<select id="logic_section" name="logic_section" class="form-control">'+portOpitonList+'</select>';
		modal_DialPadBody += '<div>';
		modal_DialPadBody += '<label for="dtmf_input">DTMF Codes</label>';
		modal_DialPadBody += '<textarea id="dtmf_input" name="dtmf_input" class="form-control" style="text-transform:uppercase;" placeholder="Enter DTMF codes" required></textarea>';
		modal_DialPadBody += '</div>';
		modal_DialPadBody += '';

		var modal_DialPadBtnCancel = 'Close';


		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-th"></i> ' + modal_DialPadTitle,
			body: '<h4>' + modal_DialPadBody + '</h4>',
 			btnOK: 'Send',
			btnOKshow: true,
			btnCancel: modal_DialPadBtnCancel,
		};

		orpModalDisplay(modalDetails);

		$('#dtmf_input').focus();
		// $('#orp_modal_ok').off('click'); // Remove other click events
	});




	$("#dtmf_input").keyup(function(e){
		var keyCode = e.which;

		if (keyCode == 13) { // Enter
			e.preventDefault();
			$('#send_dtmf').click()
		} else if (keyCode == 27) { // Esc
			e.preventDefault();
			$('#dtmf_input').val('');
			$('#dtmf_input').focus();
		}
	});


	$("#dtmf_input").keypress(function(e){
		var keyCode = e.which;

		/*  35 - #, 42 - *, 48-57 - 0-9, 65-68 - A-D, 97-100 - a-z, 8 - (backspace) */
	
		if ( 
			!( (
				keyCode >= 48 && keyCode <= 57) 
				||(keyCode >= 65 && keyCode <= 68) 
				|| (keyCode >= 97 && keyCode <= 100)
			) 
			&& keyCode != 35 
			&& keyCode != 42 
			&& keyCode != 8 
		) {
			e.preventDefault();
    	}
	});



	$('#send_dtmf').live('click', function(e) {
		var logic_path = $('#logic_section').val();
		var dtmf = $('#dtmf_input').val().toUpperCase();

/*
		$.ajax({
			type: 'POST',
			url: '/functions/ajax_orp_helper.php',
			data: { type: 'send_dtmf', logicPath: logic_path, dtmfString: dtmf },
			dataType: 'JSON',
			success: function(response) {
				var status = response.status;
				if(status == 'true') {
					console.log ('Command Sent to SVXLink');
				} else {
					console.log ('Command Failed to Send');
				}
			}
		});
*/

		$('#dtmf_input').val('');
		$('#dtmf_input').focus();
	});



});




// polyfill
var AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext;

function Tone(context, freq1, freq2) {
	this.context = context;
	this.status = 0;
	this.freq1 = freq1;
	this.freq2 = freq2;
}

Tone.prototype.setup = function(){
	this.osc1 = context.createOscillator();
	this.osc2 = context.createOscillator();
	this.osc1.frequency.value = this.freq1;
	this.osc2.frequency.value = this.freq2;

	this.gainNode = this.context.createGain();
	this.gainNode.gain.value = 0.25;

	this.filter = this.context.createBiquadFilter();
	this.filter.type = "lowpass";
	this.filter.frequency = 8000;

	this.osc1.connect(this.gainNode);
	this.osc2.connect(this.gainNode);

	this.gainNode.connect(this.filter);
	this.filter.connect(context.destination);
}

Tone.prototype.start = function(){
	this.setup();
	this.osc1.start(0);
	this.osc2.start(0);
	this.status = 1;
}

Tone.prototype.stop = function(){
	this.osc1.stop(0);
	this.osc2.stop(0);
	this.status = 0;
}

var dtmfFrequencies = {
	"1": {f1: 697, f2: 1209},
	"2": {f1: 697, f2: 1336},
	"3": {f1: 697, f2: 1477},
	"4": {f1: 770, f2: 1209},
	"5": {f1: 770, f2: 1336},
	"6": {f1: 770, f2: 1477},
	"7": {f1: 852, f2: 1209},
	"8": {f1: 852, f2: 1336},
	"9": {f1: 852, f2: 1477},
	"*": {f1: 941, f2: 1209},
	"0": {f1: 941, f2: 1336},
	"#": {f1: 941, f2: 1477},
	"A": {f1: 697, f2: 1633},
	"B": {f1: 770, f2: 1633},
	"C": {f1: 852, f2: 1633},
	"D": {f1: 941, f2: 1633}
}  

var context = new AudioContext();

// Create a new Tone instace. (We've initialised it with 
// frequencies of 350 and 440 but it doesn't really matter
// what we choose because we will be changing them in the 
// function below)
var dtmf = new Tone(context, 350, 440);


/*
$(".js-dtmf-interface li").on("mousedown touchstart", function(e){
	e.stopPropagation();

	var keyPressed = $(this).html(); // this gets the number/character that was pressed
	var frequencyPair = dtmfFrequencies[keyPressed]; // this looks up which frequency pair we need

	// this sets the freq1 and freq2 properties
	dtmf.freq1 = frequencyPair.f1;
	dtmf.freq2 = frequencyPair.f2;

	if (dtmf.status == 0){
		dtmf.start();
	}
});

// we detect the mouseup event on the window tag as opposed to the li
// tag because otherwise if we release the mouse when not over a button,
// the tone will remain playing
$(window).on("mouseup touchend", function(e){
	if (typeof dtmf !== "undefined" && dtmf.status){
	  	dtmf.stop();
	}
});
*/