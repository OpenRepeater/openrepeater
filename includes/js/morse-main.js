// Morse stuff

function translateM(input) {
    var transError = document.getElementById("transError");
    if (input.length == 0) {
		$('#morseOutput').val('');
        morse = '';
        message = '';
//         transError.innerHTML = '';
        return;
    }
    var output = String(convert(input));
	$('#morseOutput').val(output)
    var inMorse = isMorse(input);
    var outMorse = isMorse(output);
    if (!inMorse && !outMorse) {
        // show error
        //transError.innerHTML = "Invalid characters in translation.";
        morse = '';
        message = '';
        return;
    } else if (inMorse && outMorse) {
        // if both input and output appear to be Morse then take the longer one
        if (input.length > output.length) {
            morse = input;
            message = output;
        } else {
            morse = output;
            message = input;
        }
    } else if (inMorse) {
        morse = input;
        message = output;
    } else {
        morse = output;
        message = input;
    }
    //transError.innerHTML = '';
    //error.innerHTML = '';
}

function playM(callsign, wpm, pitch) {
    translateM(callsign, wpm, pitch);
    var fwpm = wpm;
    var volume = "0.75";
    playMorse(morse, wpm, fwpm, pitch, volume);
    return true;
}

function stopM() {
    stopMorse();
}