<?php
#####################################################################################################
# SXVLink GPIO Config Class
#####################################################################################################

class SVXLink_TCL {

    private $settingsArray;    


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
		$proc_content = $this->proc_startup_msg();
		$proc_content .= $this->proc_short_id();
		$proc_content .= $this->proc_long_id();
		$proc_content .= $this->proc_orp_courtesy_tone();

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 0) . $this->indent($proc_content, 1) . $this->indent($proc_footer, 0);
	}



	###############################################
	# Proc Short ID
	###############################################

	private function proc_short_id() {
		$proc_header = '
		# ORP CUSTOM PROCEDURE
		# Executed when a short identification should be sent
		proc send_short_ident {{hour -1} {minute -1}} {
		';

		$proc_content = '
		    global mycall;
		    variable CFG_TYPE;
		    variable short_announce_file
		    variable short_announce_enable
		    variable short_voice_id_enable
		    variable short_cw_id_enable
		    variable CFG_ORP_CW_SUFFIX
		';

		$proc_content .= '
		    # Play voice id if enabled
		    if {$short_voice_id_enable} {
		      puts "Playing short voice ID"
		      spellWord $mycall;
		      if {$CFG_TYPE == "Repeater"} {
		        playMsg "Core" "repeater";
		      }
		      playSilence 500;
		    }
		';

		$proc_content .= '
		    # Play announcement file if enabled
		    if {$short_announce_enable} {
		      puts "Playing short announce"
		      if [file exist "$short_announce_file"] {
		        playFile "$short_announce_file"
		        playSilence 500
		      }
		    }
		';

		$proc_content .= '
		    # Play CW id if enabled
		    if {$short_cw_id_enable} {
		      if {$CFG_TYPE == "Repeater"} {
		        if {$CFG_ORP_CW_SUFFIX != ""} {
		          set call "$mycall"
		          append call "$CFG_ORP_CW_SUFFIX"
		          CW::play $call
		          puts "Playing short CW ID: $call"
		        } else {
		          CW::play $mycall
		          puts "Playing short CW ID: $mycall"
		        }
		      } else {
		        CW::play $mycall
		        puts "Playing short CW ID: $mycall"
		      }
		      playSilence 500;
		    }
		';

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}
	
	###############################################
	# Proc Online message
	###############################################
	private function proc_startup_msg() {
		$proc_header = '
		# ORP CUSTOM PROCEDURE
		# Executed when repeater starts to fix issue 
		# https://github.com/OpenRepeater/openrepeater/issues/67
		proc startup {} {
		';
		
		$proc_content = '
		    playMsg "Core" "online"
		';

		$proc_footer = "\n\t}\n";
		
		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
	}

	###############################################
	# Proc Long ID
	###############################################

	private function proc_long_id() {
		$proc_header = '
		# ORP CUSTOM PROCEDURE
		# Executed when a long identification (e.g. hourly) should be sent
		proc send_long_ident {hour minute} {
		';

		$proc_content = '
		    global mycall;
		    global loaded_modules;
		    global active_module;
		    variable CFG_TYPE;
		    variable long_announce_file
		    variable long_announce_enable
		    variable long_voice_id_enable
		    variable long_cw_id_enable
		    variable CFG_ORP_ANNC_TIME
		    variable CFG_ORP_CW_SUFFIX
		';

		$proc_content .= '
		    # Play the voice ID if enabled
		    if {$long_voice_id_enable} {
		      puts "Playing Long voice ID"
		      spellWord $mycall;
		      if {$CFG_TYPE == "Repeater"} {
		        playMsg "Core" "repeater";
		      }
		      playSilence 500;
		    }
		';

		$proc_content .= '
		    # Play announcement file if enabled
		    if {$long_announce_enable} {
		      puts "Playing long announce"
		      if [file exist "$long_announce_file"] {
		        playFile "$long_announce_file"
		        playSilence 500
		      }
		    }
		';

		$proc_content .= '
		    # Announce time if enabled
		    if {$CFG_ORP_ANNC_TIME == "1"} {
		      puts "Announcing Time"
		      playMsg "Core" "the_time_is";
		      playSilence 100;
		      playTime $hour $minute;
		      playSilence 500;
		    }
		';

		$proc_content .= '
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

		$proc_content .= '
		    # Play CW id if enabled
		    if {$long_cw_id_enable} {
		      if {$CFG_TYPE == "Repeater"} {
		        if {$CFG_ORP_CW_SUFFIX != ""} {
		          set call "$mycall"
		          append call "$CFG_ORP_CW_SUFFIX"
		          CW::play $call
		          puts "Playing long CW ID: $call"
		        } else {
		          CW::play $mycall
		          puts "Playing long CW ID: $mycall"
		        }
		      } else {
		        CW::play $mycall
		        puts "Playing long CW ID: $mycall"
		      }
		      playSilence 500;
		    }
		';

		$proc_footer = "\n\t}\n";

		return $this->indent($proc_header, 1) . $this->indent($proc_content, 2) . $this->indent($proc_footer, 1);
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
		$proc_content = '
		proc send_rgr_sound {} {
		  Logic::orp_courtesy_tone
		}
		';

		return $this->indent($proc_content, 1);
	}


	private function proc_orp_courtesy_tone() {
		$proc_header = '
		# ORP CUSTOM PROCEDURE
		# Custom Courtesy Tone/Roger Beep
		proc orp_courtesy_tone {} {
		';

		$proc_content = '
		    variable CFG_ORP_RGR_TYPE
		    variable CFG_ORP_RGR_FILE
		';

		$proc_content .= '
		    if {$CFG_ORP_RGR_TYPE == "none"} {
		      playSilence 100
		    }
		';

		$proc_content .= '
		    if {$CFG_ORP_RGR_TYPE == "beep"} {
		      playTone 660 500 200;
		      playSilence 200
		    }
		';

		$proc_content .= '
		    if {$CFG_ORP_RGR_TYPE == "custom"} {
		      playFile "$CFG_ORP_RGR_FILE"
		      playSilence 200
		    }
		';

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