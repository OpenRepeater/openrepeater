// Useful methods  
function toggle(element, label) {
    el = document.getElementById(element);
    la = document.getElementById(label);
    if (el.style.display == "none") {
        el.style.display = "block";
        la.innerHTML = 'hide';
    } else {
        el.style.display = "none";
        la.innerHTML = 'show';
    }
}
if(typeof(String.prototype.trim) === "undefined") {
    String.prototype.trim = function() {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}
function len(s) {
    return s.trim().length;
}

// Morse stuff

function translateM() {
    var input = document.morse_form.morseCallsign.value;
    var transError = document.getElementById("transError");
    if (len(input) == 0) {
        document.morse_form.output.value = '';
        morse = '';
        message = '';
        transError.innerHTML = '';
        return;
    }
    var output = String(convert(input));
    document.morse_form.output.value = String(convert(input));
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
        if (len(input) > len(output)) {
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

function playM() {
    translateM();
    var wpm = parseInt(document.morse_form.ID_Morse_WPM.value);
    //var fwpm = parseInt(document.morse_form.fwpm.value);
    var fwpm = wpm;
    var pitch = parseInt(document.morse_form.ID_Morse_Pitch.value);
    //var volume = parseFloat(document.morse_form.volume.value);
    var volume = "0.75";
    playMorse(morse, wpm, fwpm, pitch, volume);
    return true;
}
function stopM() {
    stopMorse();
}

function validateForm() {
    translateM();
    var input = document.getElementById("input").value;
    var name = document.getElementById("senderName").value;
//    var error = document.getElementById("error");
    if (input == "") {
//        error.innerHTML = "Please enter a message to send.";
    } else if (morse == "") {
//        error.innerHTML = "Please enter a valid message to send.";
    } else {
//        error.innerHTML = "";
        return true; 
    }
    return false;
}

// AJAX stuff

var req;

if (typeof XMLHttpRequest == "undefined")
    XMLHttpRequest = function () {
        try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
        catch (e) {}
        try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
        catch (e) {}
        try { return new ActiveXObject("Microsoft.XMLHTTP"); }
        catch (e) {}
        throw new Error("This browser is too old for the sharing functions to work.");
    };
function doAjaxRequest(url) {
    req = new XMLHttpRequest();
    req.onreadystatechange = processReqChange;
    req.open("GET", url, true);
    req.send(null);
}
function processReqChange() {
    if (req.readyState == 4) {
        spinner.stop();
        // only if "OK"
        if (req.status == 200) {
            response = req.responseText;
            shareLink(response)
        } else {
            alert("There was a problem getting the link:\n" + req.statusText);
        }
    }
}
