<?php
// This is a sub-function file and gets included into svxlink_update.php

/* ---------------------------------------------------------- */
// ADDITIONAL LOGIC

$tclLogicNameSpace .= '

		# Call the "status_report" function in all modules if no module is active
		if {$active_module == ""} {
			foreach module [split $loaded_modules " "] {
				set func "::";
				append func $module "::status_report";
				if {"[info procs $func]" ne ""} {
					$func;
				}
			}

			playSilence 500;
		}
';

/* ---------------------------------------------------------- */
// ADDITIONAL REPEATER

$tclRepeaterLogicNameSpace .= '
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
';

  if ($settings['ID_Long_Mode'] != "disabled") {
	// If Long ID is enable use first and load setting from previously define string in included file
	$tclRepeaterLogicNameSpace .= $longIdString;
  } else if ($settings['ID_Short_Mode'] != 'disabled') {
	// Otherwise load Short ID setting from previously define string in included file
	$tclRepeaterLogicNameSpace .= $shortIdString;
  } else {
	// If both are disabled, play morse ID 
	$tclRepeaterLogicNameSpace .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);  
  }

$tclRepeaterLogicNameSpace .= '

				if {$active_module != ""} {
					playMsg "Core" "active_module";
					playMsg $active_module "name";
				}
			}
		}
';

$tclRepeaterLogicNameSpace .= '
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
				#				playTone 400 900 50
				#				playSilence 100
				#				playTone 360 900 50
				playSilence 500
				return;
			}

			set Logic::prev_ident $now;

			playSilence 250;

';

  if ($settings['ID_Short_Mode'] != 'disabled') {
	// If not disabled, load Short ID setting from previously define string in included file
	$tclRepeaterLogicNameSpace .= $shortIdString;
  } else {
	// If disabled, play morse ID 
	$tclRepeaterLogicNameSpace .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);  
  }

$tclRepeaterLogicNameSpace .= '
			}
';


/* ---------------------------------------------------------- */
?>