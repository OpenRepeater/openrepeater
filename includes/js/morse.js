function log(message){
    if(typeof console == "object"){
		//console.log(message);
    }
}
// Globals
var DITS_PER_WORD = 50;  // based on "PARIS "
var SAMPLE_RATE = 8000;
var noAudio = false;  // set to true if audio doesn't work
var sample = [];
var samplePos = 0;
var finishedPlaying = true;

var text2morseH = {
    'A': ".-",
    'B': "-...",
    'C': "-.-.",
    'D': "-..",
    'E': ".",
    'F': "..-.",
    'G': "--.",
    'H': "....",
    'I': "..",
    'J': ".---",
    'K': "-.-",
    'L': ".-..",
    'M': "--",
    'N': "-.",
    'O': "---",
    'P': ".--.",
    'Q': "--.-",
    'R': ".-.",
    'S': "...",
    'T': "-",
    'U': "..-",
    'V': "...-",
    'W': ".--",
    'X': "-..-",
    'Y': "-.--",
    'Z': "--..",
    '1': ".----",
    '2': "..---",
    '3': "...--",
    '4': "....-",
    '5': ".....",
    '6': "-....",
    '7': "--...",
    '8': "---..",
    '9': "----.",
    '0': "-----",
    '.': ".-.-.-",
    ',': "--..--",
    ':': "---...",
    '?': "..--..",
    '\'': ".----.",
    '-': "-....-",
    '/': "-..-.",
    '(': "-.--.-",
    ')': "-.--.-",
    '"': ".-..-.",
    '@': ".--.-.",
    '=': "-...-",
    ' ': "/"
}  //Not morse but helps translation
                
var morse2textH = {};
for (var text in text2morseH) {
    morse2textH[text2morseH[text]] = text;
}

var prosign2morse = [
'<AA>', '.-.-',
'<AR>', '.-.-.',
'<AS>', '.-...',
'<BK>', '-...-.-',
'<BT>', '-...-',  // also <TV>
'<CL>', '-.-..-..',
'<CT>', '-.-.-',
'<DO>', '-..---',
'<KN>', '-.--.',
'<SK>', '...-.-',  // also <VA>
'<VA>', '...-.-',
'<SN>', '...-.',  // also <VE>
'<VE>', '...-.',
'<SOS>', '...---...'
]

function tidyText(text) {
    text = text.toUpperCase();
    text = text.trim();
    text = text.replace(/\s+/g, ' ');
    return text;                
}
            
function text2morse(text) {
    return text2morseGeneral(text, false);
}
function text2morsePro(text) {
    return text2morseGeneral(text, true);
}
function text2morseGeneral(text, useProsigns) {
    var morse = "";
    var c;
    text = tidyText(text);
    var tokens = [];
    var prosign;
    var i = 0;
    var token_length;
    while (text.length > 0) {
        token_length = 1;
        prosign = text.match(/^<...?>/);  // array of matches
        if (prosign && useProsigns) {
            token_length = prosign[0].length;
        }
        tokens.push(text.slice(0, token_length));
        text = text.slice(token_length, text.length);
    }
    for (i = 0; i < tokens.length; i++) {
        c = text2morseH[tokens[i]];
        if (c == undefined) {
            c = '?';
            if (useProsigns) {
                for (var j = 0; j < prosign2morse.length / 2; j++) {
                    if (prosign2morse[2*j] == tokens[i]) {
                        c = prosign2morse[2*j + 1];
                        break;
                    }
                }                
            }
        }
        morse += c + ' ';
    }
    return morse.trim();
}

function tidyMorse(morse) {
    morse = morse.trim();
    morse = morse.replace(/\|/g, "/");  // unify the word seperator
    morse = morse.replace(/\//g, " / ");  // make sure word seperators are spaced out
    morse = morse.replace(/\s+/g, " ");  // squash multiple spaces into single spaces
    morse = morse.replace(/(\/ )+\//g, "/");  // squash multiple word seperators
    morse = morse.replace(/^ \/ /, "");  // remove initial word seperators
    morse = morse.replace(/ \/ $/, "");  // remove trailing word seperators
    morse = morse.replace(/_/g, "-");  // unify the dash character
    return morse;
}
            
function morse2text(morse) {
    return morse2textGeneral(morse, false);
}
function morse2textPro(morse) {
    return morse2textGeneral(morse, true);
}
function morse2textGeneral(morse, useProsigns) {
    var text = "";
    var c;
    morse = tidyMorse(morse);
    var tokens = morse.split(" ");
    for (var i = 0; i < tokens.length; i++) {
        c = morse2textH[tokens[i]];
        if (c == undefined) {
            c = '?';
            if (useProsigns) {
                for (var j = 0; j < prosign2morse.length / 2; j++) {
                    if (prosign2morse[2*j + 1] == tokens[i]) {
                        c = prosign2morse[2*j];
                    }
                }
            }
        }
        text += c;
    }
    return text;    
}

function isMorse(input) {
    input = tidyMorse(input);
    if (input.match(/^[ /.-]*$/)) {
        return true;
    } else {
        return false;
    }
}

function convert(input) {
    if (isMorse(input)) {
        return morse2text(input);
    } else {
        return text2morse(input);
    }
}
function convertPro(input) {
    if (isMorse(input)) {
        return morse2textPro(input);
    } else {
        return text2morsePro(input);
    }
}

/**
 * Convert a morse string into an array of millisecond timings.
 * 
 * morse - the morse code string
 * wpm - the speed in words per minute ("PARIS " as one word)
 */
function morse2timings(morse, wpm) {
    return morse2timingsFarnsworth(morse, wpm, wpm);
}

/**
 * Convert a morse string into an array of millisecond timings.
 * With the Farnsworth method, the morse characters are played at one 
 * speed and the spaces between characters at a slower speed.
 * 
 * morse - the morse code string
 * wpm - the speed in words per minute ("PARIS " as one word)
 * farnsworth - the Farnsworth speed in words per minute
 */
function morse2timingsFarnsworth(morse, wpm, farnsworth) {	
    var dit = 60000 / (DITS_PER_WORD * wpm);
    var r = wpm / farnsworth;  // slow down the spaces by this ratio
    return morse2timingsGeneral(morse, dit, 3 * dit, dit, 3 * dit * r, 7 * dit * r);
}
        
/**
 * Convert a morse string into an array of millisecond timings.
 * morse - the morse code string
 * dit - the length of a dit in milliseconds
 * dah - the length of a dah in milliseconds (normally 3 * dit)
 * ditSpace - the length of an intra-character space in milliseconds (1 * dit)
 * charSpace - the length of an inter-character space in milliseconds (normally 3 * dit)
 * wordSpace - the length of an inter-word space in milliseconds (normally 7 * dit)
 */
function morse2timingsGeneral(morse, dit, dah, ditSpace, charSpace, wordSpace) {
    log("Morse: " + morse);
    log("Morse speeds/ms: " + dit + ", " + dah + ", " + ditSpace + ", " + charSpace + ", " + wordSpace);
    morse = tidyMorse(morse);
		
    // if there's something that couldn't be translated then abort
    if (morse.indexOf('?') != -1) {
        return [];
    }
                
    morse = morse.replace(/ \/ /g, '/');  // this means that a space is only used for inter-character
                
    var times = [];
    var c;
    for (var i = 0; i < morse.length; i++) {
        c = morse[i];
        if (c == "." || c == '-') {
            if (c == '.') {
                times.push(dit);
            } else  {
                times.push(dah);
            }
            times.push(ditSpace);
        } else if (c == " ") {
            times.pop();
            times.push(charSpace);
        } else if (c == "/") {
            times.pop();
            times.push(wordSpace);
        }
    }
    times.pop();  // take off the last ditSpace
    log("Timings: " + times);
    return times;
}

function playMorse(morse, wpm, fwpm, freq, volume) {
    stopMorse();
    var timings = morse2timingsFarnsworth(morse, wpm, fwpm);
    if (timings.length > 0) {
        timings.push(1000); // add in a second of silence on the end otherwise IE just carries on beeping
        sample = makeSample(timings, freq);
        samplePos = 0;
        finishedPlaying = false;
        audioServer.changeVolume(volume);
    }
}
function stopMorse() {
    finishedPlaying = true;
    audioServer.changeVolume(0);
    sample = [];
}

function makeSample(timings, freq) {
    var buf = [];
    var counterIncrementAmount = Math.PI * 2 * freq / SAMPLE_RATE;
    var on = 1;
    for (var t = 0; t < timings.length; t += 1) {
        var duration = SAMPLE_RATE * timings[t] / 1000;
        for (var i = 0; i < duration; i += 1) {
            buf.push(on * Math.sin(i * counterIncrementAmount));
        }
        on = 1 - on;
    }
    log("generated: " + buf.length);
    return buf;
}

function audioGenerator(samplesToGenerate) {
    if (samplesToGenerate == 0) {
        return [];
    }
    samplesToGenerate = Math.min(samplesToGenerate, sample.length - samplePos);
    if (samplesToGenerate > 0) {
        ret = sample.slice(samplePos, samplePos + samplesToGenerate);
        samplePos += samplesToGenerate;
        return ret;
    } else {
        finishedPlaying = true;
        return [];
    }
}
function failureCallback() {
    if (noAudio == false) {
    //alert("Sorry your browser is unable to play the audio on this page.");
    }
    noAudio = true;
}
/*
    The arguments:
    channels,
    sample rate,
    buffer low point for underrun callback triggering,
    internal ring buffer size,
    audio refill callback triggered when samples remaining < buffer low point,
    volume,
    callback triggered when the browser is found to not support any audio API.
 */
audioServer = new XAudioServer(1, SAMPLE_RATE, 
    SAMPLE_RATE >> 2, SAMPLE_RATE << 1, audioGenerator, 1, failureCallback);

setInterval(
    function () {
        //Runs the check to see if we need to give more audio data to the lib:
        if (!finishedPlaying) {
            audioServer.executeCallback();
        }
    }, 20);

