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
	# Build Custom TCL
	###############################################

	public function build_custom_tcl() {
		$tclOverride = $this->namespace_wrap( 'Logic', $this->proc_short_id() . $this->proc_long_id() . $this->proc_courtesy_tone() );
// 		$tclOverride .= $this->namespace_wrap('RepeaterLogic','CODE...');
/*
		$tclOverride .= $this->alias_RepeaterLogic('RepeaterLogic1');
		$tclOverride .= $this->namespace_wrap( 'RepeaterLogic1', $this->override_RepeaterLogic() );
		$tclOverride .= $this->alias_RepeaterLogic('RepeaterLogic2');
*/
		return $tclOverride;
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

	private function override_RepeaterLogic() {
		$proc_content = '
        # Executed when the repeater is activated
        proc repeater_up {reason} {
          global mycall;
          global active_module;
          variable repeater_is_up;
        
          set repeater_is_up 1;
        
          if {($reason != "SQL_OPEN") && ($reason != "CTCSS_OPEN") && ($reason != "SQL_RPT_REOPEN")} {
            set now [clock seconds];
            if {$now-$Logic::prev_ident < $Logic::min_time_between_ident} {
              return;
            }
          set Logic::prev_ident $now;
          playSilence 250;

          spellWord $mycall;
          if {$CFG_TYPE == "Repeater"} {
            playMsg "Core" "repeater";
          }
          playSilence 500;
        
          CW::setAmplitude 200
          CW::setWpm 25
          CW::setPitch 600
          CW::play $mycall/R
          playSilence 500;
        

          if {$active_module != ""} {
            playMsg "Core" "active_module";
            playMsg $active_module "name";
          }
        }
        }

        # Executed when the repeater is deactivated
        proc repeater_down {reason} {
                global mycall;
                set CFG_TYPE "Repeater";
                variable repeater_is_up;
                
                set repeater_is_up 0;
                
                if {$reason == "SQL_FLAP_SUP"} {
                        playSilence 500;
                        playMsg "Core" "interference";
                        playSilence 500;
                        return;
                }

                set now [clock seconds];
                        if {$now-$Logic::prev_ident < $Logic::min_time_between_ident} {
                        #                               playTone 400 900 50
                        #                               playSilence 100
                        #                               playTone 360 900 50
                        playSilence 500
                        return;
                }

                set Logic::prev_ident $now;

                playSilence 250;


                CW::setAmplitude 200
                CW::setWpm 25
                CW::setPitch 600
                CW::play $mycall/R
                playSilence 500;

                }
		';

		return $this->indent($proc_content, 1);
	}

	###############################################
	# Proc Short ID
	###############################################

	private function proc_short_id() {
		$proc_header = "
		# Executed when a short identification should be sent
		proc send_short_ident {{hour -1} {minute -1}} {
		";

		$proc_content = "
			global mycall;
			variable CFG_TYPE;
			playSilence 200;
		";
		
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

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}


	###############################################
	# Proc Long ID
	###############################################

	private function proc_long_id() {
		$proc_header = "
		# Executed when a long identification (e.g. hourly) should be sent
		proc send_long_ident {hour minute} {
		";

		$proc_content = "
			global mycall;
			global loaded_modules;
			global active_module;
			variable CFG_TYPE;
			playSilence 200;
		";

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
					$longIdString .= $this->buildTime();
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


		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}


	###############################################
	# Proc Courtesy Tone
	###############################################

	private function proc_courtesy_tone() {
		$proc_header = "
		# Executed when the squelch has closed and the RGR_SOUND_DELAY timer has expired.
		proc send_rgr_sound {} {
		";

		$proc_content = "";

		switch ($this->settingsArray['courtesyMode']) {
		
		    case "disabled":
				// No Courtesy Tone Played 
				$proc_content .= $this->playSilence();
		        break;
		
		    case "beep":
				// Generic Beep Played
				$proc_content .= $this->playBeep();
		        break;
		
		    case "custom":
				// Play Custom Courtesy Tone
				$proc_content .= $this->playCustomTone($this->settingsArray['courtesy']);
		        break;
		}

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}


	###############################################
	# Namespace Wrapper
	###############################################

	private function namespace_wrap($namespace, $input) {
		$namespace_header = "
		### Overridden event handlers created by OpenRepeater
		namespace eval " . $namespace . " {
		"; 

		$namespace_footer = "
		# end of namespace
		}
		
		";
		
		return $this->indent($namespace_header,0) . $input . $this->indent($namespace_footer,0);
	}


	###############################################
	# Courtesy Tone Functions
	###############################################
	
	private function playSilence() {
		$playSilence = '
			playSilence 100
		';
		return $playSilence;
	}
	
	private function playBeep() {
		$playBeep = '
		playTone 660 500 200;
		playSilence 200
		';
		return $playBeep;
	}
	
	private function playCustomTone($filename) {
		$playTone = '
		playFile "' . $this->courtesyPath . $filename . '"
		playSilence 200
		';
		return $playTone;
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

#######


/* ---------------------------------------------------------- */
/* BUILD CUSTOM TCL OVERRIDES...ie COURTESY TONES, IDENTIFICATION, ETC */

// Define Strings Variables for TCL Namespaces. 
// $tclLogicNameSpace = '';
// $tclRepeaterLogicNameSpace = '';

// Include PHP files that build custom TCL Logic for the namespaces below
/*
include('svxlink_update_functions/tcl_identification.php');
include('svxlink_update_functions/tcl_courtesy_tones.php');
include('svxlink_update_functions/tcl_TEMP.php');
*/

// TCL Logic Namespace Override
/*
$tclOverride = '
### Overridden Core Logic event handlers created by OpenRepeater
namespace eval Logic {
' . $tclLogicNameSpace . '
# end of namespace
}
*/


/*
### Overridden Repeater Logic event handlers created by OpenRepeater
namespace eval RepeaterLogic {
' . $tclRepeaterLogicNameSpace . '
# end of namespace
}

';
*/

/*
namespace eval EchoLink {

		# Executed when an incoming connection is accepted
		proc remote_greeting {call} {
			playSilence 1000;
			playFile "/usr/share/svxlink/sounds/en_US/EchoLink/greeting.wav"
#			playMsg "greeting";
		}

 end of namespace
}
*/

?>