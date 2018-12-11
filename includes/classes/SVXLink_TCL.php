<?php
#####################################################################################################
# SXVLink GPIO Config Class
#####################################################################################################

class SVXLink_TCL {

    private $settingsArray;    
    private $idPath = "/var/lib/openrepeater/sounds/identification/";
    private $courtesyPath = "/var/lib/openrepeater/sounds/courtesy_tones/";


	public function __construct($settingsArray) {
		$this->settingsArray = $settingsArray;
	}



	###############################################
	# Simplex Logic
	###############################################

	public function alias_SimplexLogic($new_namespace) {
		$orig_file = file_get_contents("/usr/share/svxlink/events.d/SimplexLogic.tcl");
		$new_file = str_replace("SimplexLogic", $new_namespace, $orig_file);
		return $new_file;
	}



	###############################################
	# Repeater Logic
	###############################################

	public function alias_RepeaterLogic($new_namespace) {
		$orig_file = file_get_contents("/usr/share/svxlink/events.d/RepeaterLogic.tcl");
		$new_file = str_replace("RepeaterLogic", $new_namespace, $orig_file);

		# Replace default tones at end of roger beep
		$search_beep = '/playTone 400 900 50[\s\S]+?playSilence 500\R/';
		$replace_beep = '';
		$new_file = preg_replace( $search_beep, $replace_beep, $new_file );

		return $new_file;
	}



	###############################################
	# Build Custom TCL
	###############################################

	public function logic_override() {

		$proc_header = '
			namespace eval Logic {
			';			

		$proc_content = $this->proc_short_id();
		$proc_content .= $this->proc_long_id();

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 0) . $this->indent($proc_content, 1) . $this->indent($proc_footer, 0);
	}



	###############################################
	# Proc Short ID
	###############################################

	private function proc_short_id() {
		$proc_header = '
		# Executed when a short identification should be sent
		proc send_short_ident {{hour -1} {minute -1}} {
		';

		$proc_content = '
		    global mycall;
		    variable CFG_TYPE;
		    playSilence 200;
		';
		
		$proc_content .= '
		    if {$CFG_TYPE == "Repeater"} {
		';

		switch ($this->settingsArray['ID_Short_Mode']) {
		    case "disabled":
		    	// Short ID - DISABLED
		        break;
		
		    case "morse":
		    	// Short ID - MORSE
				$proc_content .= $this->buildMorseID();
		        break;
		
		    case "voice":
		    	// Short ID - VOICE ID
				$proc_content .= $this->buildVoiceID();
				if ($this->settingsArray['ID_Short_AppendMorse'] == 'True') {
					$proc_content .= $this->buildMorseID();
				}
		        break;
		
		    case "custom":
		    	// Short ID - CUSTOM ID
				$proc_content .= $this->buildCustomID($this->settingsArray['ID_Short_CustomFile']);
				if ($this->settingsArray['ID_Short_AppendMorse'] == 'True') {
					$proc_content .= $this->buildMorseID();
				}
		        break;
		}

		$proc_content .= "\n\t    } else {\n";
		$proc_content .= $this->buildMorseID();
		$proc_content .= "\n\t    }\n";

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}



	###############################################
	# Proc Long ID
	###############################################

	private function proc_long_id() {
		$proc_header = '
		# Executed when a long identification (e.g. hourly) should be sent
		proc send_long_ident {hour minute} {
		';

		$proc_content = '
		    global mycall;
		    global loaded_modules;
		    global active_module;
		    variable CFG_TYPE;
		    playSilence 200;
		';

		$proc_content .= '
		    if {$CFG_TYPE == "Repeater"} {
		';

		switch ($this->settingsArray['ID_Long_Mode']) {
		    case "disabled":
		    	// Long ID - DISABLED
		        break;
		
		    case "morse":
		    	// Long ID - MORSE
				$proc_content .= $this->buildMorseID();
		        break;
		
		    case "voice":
		    	// Long ID - VOICE ID
				$proc_content .= $this->buildVoiceID();
				if ($this->settingsArray['ID_Long_AppendTime'] == 'True') {
					$proc_content .= $this->buildTime();
				}
				if ($this->settingsArray['ID_Long_AppendTone'] == 'True') {
					// FUTURE - Option to announce CTCSS / PL Tone;
				}
				if ($this->settingsArray['ID_Long_AppendMorse'] == 'True') {
					$proc_content .= $this->buildMorseID();
				}		
		        break;
		
		    case "custom":
		    	// Long ID - CUSTOM ID
				$proc_content .= $this->buildCustomID($this->settingsArray['ID_Long_CustomFile']);
				if ($this->settingsArray['ID_Long_AppendTime'] == 'True') {
					$proc_content .= $this->buildTime();
				}
				if ($this->settingsArray['ID_Long_AppendTone'] == 'True') {
					// FUTURE - Option to announce CTCSS / PL Tone;
				}
				if ($this->settingsArray['ID_Long_AppendMorse'] == 'True') {
					$proc_content .= $this->buildMorseID();
				}		
		        break;
		}

		$proc_content .= "\n\t    } else {\n";
		$proc_content .= $this->buildMorseID();
		$proc_content .= "\n\t    }\n";

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}



	###############################################
	# Identification Functions
	###############################################

	private function buildMorseID() {
		$morseID = '
		    CW::setAmplitude ' . $this->settingsArray['ID_Morse_Amplitude'] . '
		    CW::setWpm ' . $this->settingsArray['ID_Morse_WPM'] . '
		    CW::setPitch ' . $this->settingsArray['ID_Morse_Pitch'] . '
		    CW::play $mycall' . $this->settingsArray['ID_Morse_Suffix'] . '
		    playSilence 500;
		';
		return $morseID;
	}
	
	private function buildVoiceID() {
		$voiceID = '
		    spellWord $mycall;
		    if {$CFG_TYPE == "Repeater"} {
		        playMsg "Core" "repeater";
		    }
		    playSilence 500;
		';
		return $voiceID;
	}
	
	private function buildCustomID($filename) {
		$customID = '
		    playFile "' . $this->idPath . $filename . '"
		    playSilence 500
		';
		return $customID;
	}
	
	private function buildTime() {
		$time = '
		    playMsg "Core" "the_time_is";
		    playSilence 100;
		    playTime $hour $minute;
		    playSilence 500;
		';
		return $time;
	}



	###############################################
	# Courtesy Tone
	###############################################

	public function override_courtesy_tone($orig_file) {
		$search_for_function = '/proc send_rgr_sound {} {[\s\S]+?}\R/';
		
		$new_function = $this->proc_courtesy_tone();
	
		$new_file = preg_replace( $search_for_function, $new_function, $orig_file );

		return $new_file;
	}


	private function proc_courtesy_tone() {
		$proc_header = "
		proc send_rgr_sound {} {
		";

		$proc_content = "";

		switch ($this->settingsArray['courtesyMode']) {
		
		    case "disabled":
				// No Courtesy Tone Played 
				$proc_content .= '
					playSilence 100
					';
		        break;
		
		    case "beep":
				// Generic Beep Played
				$proc_content .= '
					playTone 660 500 200;
					playSilence 200
					';
		        break;
		
		    case "custom":
				// Play Custom Courtesy Tone
				$proc_content .= '
					playFile "' . $this->courtesyPath . $this->settingsArray['courtesy'] . '"
					playSilence 200
					';
		        break;
		}

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}



	###############################################
	# Indentation Level
	###############################################

	private function indent($string, $level = 0) {
		$string = preg_replace('/\t+/', '%%%%', $string);
		
		if ($level == 0) { $string = str_replace("%%%%", "", $string); }
		if ($level == 1) { $string = str_replace("%%%%", "\t", $string); }
		if ($level == 2) { $string = str_replace("%%%%", "\t\t", $string); }
		if ($level == 3) { $string = str_replace("%%%%", "\t\t\t", $string); }
		if ($level == 3) { $string = str_replace("%%%%", "\t\t\t\t", $string); }

		return $string;
	}	


}

?>