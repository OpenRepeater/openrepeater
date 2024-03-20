function setCustomShortID (fileName, activeNum, totalFiles) {
	$('#ID_Short_CustomFile').val(fileName);

	for (i = 1; i <= totalFiles; i++) {
		$('#shortIDsoundRow'+i).removeClass('active');
	}

	$('#shortIDsoundRow'+activeNum).addClass('active');
	updateShortIdSettings ();
}


function setCustomLongID (fileName, activeNum, totalFiles) {
	$('#ID_Long_CustomFile').val(fileName);

	for (i = 1; i <= totalFiles; i++) {
		$('#longIDsoundRow'+i).removeClass('active');
	}

	$('#longIDsoundRow'+activeNum).addClass('active');
	updateLongIdSettings ();
}



$(function() {

// Show items hidden on page load if they are suppose to be visible
if(!($('#ID_Short_Mode').val() == 'disabled' && $('#ID_Long_Mode').val() == 'disabled')) { $('#morse-id-grp-enable').show(); } 

if($('#ID_Short_Mode').val() != 'disabled') { $('#short-id-grp-enable #general').show(); } 
if($('#ID_Short_Mode').val() == 'morse') { $('#short-id-grp-enable #morse').show();}
if($('#ID_Short_Mode').val() == 'voice') { $('#short-id-grp-enable #voice').show(); }
if($('#ID_Short_Mode').val() == 'custom') { $('#short-id-grp-enable #custom').show(); }
if($($('#ID_Short_Mode').val() == 'morse' || '#ID_Short_Mode').val() == 'voice' || $('#ID_Short_Mode').val() == 'custom') {
    $('#short-id-grp-enable #shortID_Options').show(); 
	$('#short-id-grp-enable #activeID').show();
}
if($('#ID_Short_Mode').val() == 'voice' || $('#ID_Short_Mode').val() == 'custom') { $('#short-id-grp-enable #appendShortMorseID').show(); }


if($('#ID_Long_Mode').val() != 'disabled') { $('#long-id-grp-enable #general').show(); }
if($('#ID_Long_Mode').val() == 'morse') { $('#long-id-grp-enable #morse').show(); } 
if($('#ID_Long_Mode').val() == 'voice') { $('#long-id-grp-enable #voice').show(); }
if($('#ID_Long_Mode').val() == 'custom') { $('#long-id-grp-enable #custom').show(); }
if($('#ID_Long_Mode').val() == 'voice' || $('#ID_Long_Mode').val() == 'custom') { $('#long-id-grp-enable #append').show(); }

$('#morseCallsign').val($('#callsign').val() + $('input[name=ID_Morse_Suffix]:checked').val());


	// Show/Hide applicable Short ID Settings, Update via AJAX
    $('#ID_Short_Mode').change(function(){

        if($('#ID_Short_Mode').val() == 'disabled' && $('#ID_Long_Mode').val() == 'disabled') {
            $('#morse-id-grp-enable').hide(); 
        } else {
            $('#morse-id-grp-enable').show(); 
        } 
        

        if($('#ID_Short_Mode').val() == 'disabled') {
            $('#short-id-grp-enable #general').hide(); 
            $('#short-id-grp-enable #appendShortMorseID').hide(); 
            $('#short-id-grp-enable #activeID').hide(); 
        } else {
            $('#short-id-grp-enable #general').show(); 
        } 

        if($('#ID_Short_Mode').val() == 'morse') {
            $('#short-id-grp-enable #morse').show(); 
            $('#short-id-grp-enable #appendShortMorseID').hide(); 
            $('#short-id-grp-enable #activeID').show(); 
        } else {
            $('#short-id-grp-enable #morse').hide(); 
        } 

        if($('#ID_Short_Mode').val() == 'voice') {
            $('#short-id-grp-enable #voice').show(); 
            $('#short-id-grp-enable #appendShortMorseID').show();
            $('#short-id-grp-enable #activeID').show(); 
        } else {
            $('#short-id-grp-enable #voice').hide();
        } 

        if($('#ID_Short_Mode').val() == 'custom') {
            $('#short-id-grp-enable #custom').show(); 
            $('#short-id-grp-enable #appendShortMorseID').show(); 
            $('#short-id-grp-enable #activeID').show(); 
        } else {
            $('#short-id-grp-enable #custom').hide();
        } 

    });

	// Show/Hide applicable Long ID Settings, Update via AJAX
    $('#ID_Long_Mode').change(function(){

        if($('#ID_Short_Mode').val() == 'disabled' && $('#ID_Long_Mode').val() == 'disabled') {
            $('#morse-id-grp-enable').hide(); 
        } else {
            $('#morse-id-grp-enable').show(); 
        } 

        if($('#ID_Long_Mode').val() == 'disabled') {
            $('#long-id-grp-enable #general').hide(); 
            $('#long-id-grp-enable #append').hide();
        } else {
            $('#long-id-grp-enable #general').show(); 
        } 

        if($('#ID_Long_Mode').val() == 'morse') {
            $('#long-id-grp-enable #morse').show(); 
            $('#long-id-grp-enable #append').hide();
        } else {
            $('#long-id-grp-enable #morse').hide(); 
        } 

        if($('#ID_Long_Mode').val() == 'voice') {
            $('#long-id-grp-enable #voice').show(); 
            $('#long-id-grp-enable #append').show();
        } else {
            $('#long-id-grp-enable #voice').hide(); 
        } 

        if($('#ID_Long_Mode').val() == 'custom') {
            $('#long-id-grp-enable #custom').show(); 
            $('#long-id-grp-enable #append').show();
        } else {
            $('#long-id-grp-enable #custom').hide(); 
        } 

    });
});

// UPDATE SUB FORMS VIA AJAX
// ----------------------------------------------------------

function updateShortIdSettings () {
    //submit changes to db
    var $form = $("#short_ID_settings");
    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        type: method,
        success: function() {
			$('.server_bar_wrap').show(); 
        }
    });
}

$('#short_ID_settings').on('change', function() {
	updateShortIdSettings ();
});

// ----------------------------------------------------------

function updateLongIdSettings () {
    //submit changes to db
    var $form = $("#long_ID_settings");
    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        type: method,
        success: function() {
			$('.server_bar_wrap').show(); 
        }
    });	
}

$('#long_ID_settings').on('change', function() {
	updateLongIdSettings ();
});

// ----------------------------------------------------------

function updateMorseIdSettings () {
	//update hidden field for morse code preview
	$('#morseCallsign').val($('#callsign').val() + $('input[name="ID_Morse_Suffix"]:checked', '#morse_ID_settings').val());

    //submit changes to db
    var $form = $("#morse_ID_settings");
    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        type: method,
        success: function() {
			$('.server_bar_wrap').show(); 
        }
    });
}

$('#morse_ID_settings').on('change', function() {
	updateMorseIdSettings ();
});