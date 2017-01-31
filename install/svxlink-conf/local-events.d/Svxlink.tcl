
###############################################################################
#
# Generic Logic event handlers
#
###############################################################################

#
# Add spi library
#
package require spi
#
# This is the namespace in which all functions and variables below will exist.
#
namespace eval Logic {

#
# A global variable for emergency mode
#

variable emergency 0;

#
# A variable used to store a timestamp for the last identification.
#
variable prev_ident 0;

#
# A constant that indicates the minimum time in seconds to wait between two
# identifications. Manual and long identifications is not affected.
#
variable min_time_between_ident 120;

#
# Short and long identification intervals. They are setup from config
# variables below.
#
variable short_ident_interval 0;
variable long_ident_interval 0;

#
# The ident_only_after_tx variable indicates if identification is only to
# occur after the node has transmitted. The variable is setup below from the
# configuration variable with the same name.
# The need_ident variable indicates if identification is needed.
#
variable ident_only_after_tx 0;
variable need_ident 0;

#
# A list of functions that should be called once every whole minute
#
variable timer_tick_subscribers [list];

#
#Function to read SPI voltage value with MCP3204 and bridge divider
#

proc conversion {canal R1 R2} {
        set spi [spi #auto /dev/spidev0.0]

        $spi read_mode 0
        $spi write_mode 0
        $spi write_bits_word 8
        $spi read_bits_word 8
        $spi write_maxspeed 500000
        $spi read_maxspeed 500000

        # commande binaire pour le convertisseur: bit de start, bit "Single Ended", canal 0
        set adcCommand [expr 0b11000000 + $canal * 0x08]

        # Conversion texte -> binaire 
        set transmitString [binary format c $adcCommand] 

        # Ajout de 3 caractères 0 (pour envoyer 4 en tout), réception de 4 caractères
        set receiveString [$spi transfer "$transmitString\x00\x00\x00" 50]

       #Uncomment for test
       # puts "sent: $transmitString"
       # puts "recd: $receiveString"

        # affichage de la réception en binaire
        binary scan $receiveString B* affBin
        #Uncomment for test
        #puts $affBin

        # Conversion binaire vers entier
        binary scan $receiveString I convert
        #Uncomment for test
        #puts $convert

        # extraction de la valeur 
        set receivedValue [expr ($convert & 0b00000001111111111110000000000000) / 0x2000] 

        #Uncomment for test
        #puts $receivedValue


        $spi delete

        set voltage [expr $receivedValue * (3.3/4096) * ($R1 + $R2)/$R2]
        return [string range $voltage 0 3]
}

#
# Contains the ID of the last receiver that indicated squelch activity
#
variable sql_rx_id 0;

proc status_emergency {} {
global emergency;
#set emergency 0;
return $emergency
}

#Executed with weather dtmf code #88 Epinal #54 Nancy

proc prevision_meteo {ville} {

    set runcmd [exec sudo python /usr/share/svxlink/modules.d/meteo.py $ville 2>&1];
    puts "Meteo ville de $ville : $runcmd"
    if  {$runcmd == "retour=200 OK"} {
    playMsg "SVXCard" "meteo";
    return 1
    }
    else {
    playMsg "SVXCard" "meteooffline";
    return 0
    }

}

#
# Executed when the SvxLink software is started
#proc startup {} {
  #playMsg "Core" "online"
  #send_short_ident
#}

#
# Executed when a specified module could not be found
#   module_id - The numeric ID of the module
#
proc no_such_module {module_id} {
  playMsg "Core" "no_such_module";
  playNumber $module_id;
}

#
# Executed when a manual identification is initiated with the * DTMF code
#
proc manual_identification {} {
  global mycall;
  global report_ctcss;
  global active_module;
  global loaded_modules;
  variable CFG_TYPE;
  variable prev_ident;

  set epoch [clock seconds];
  set hour [clock format $epoch -format "%k"];
  regexp {([1-5]?\d)$} [clock format $epoch -format "%M"] -> minute;
  set prev_ident $epoch;

  playMsg "Core" "online";
  spellWord $mycall;
  if {$CFG_TYPE == "Repeater"} {
    playMsg "Core" "repeater";
  }
  playSilence 250;
  playMsg "Core" "the_time_is";
  playTime $hour $minute;
  playSilence 250;
  if {$report_ctcss > 0} {
    playMsg "Core" "pl_is";
    playNumber $report_ctcss;
    playMsg "Core" "hz";
    playSilence 300;
  }
  if {$active_module != ""} {
    playMsg "Core" "active_module";
    playMsg $active_module "name";
    playSilence 250;
    set func "::";
    append func $active_module "::status_report";
    if {"[info procs $func]" ne ""} {
      $func;
    }
  } else {
    foreach module [split $loaded_modules " "] {
      set func "::";
      append func $module "::status_report";
      if {"[info procs $func]" ne ""} {
	$func;
      }
    }
  }
  playMsg "Default" "press_0_for_help";
  playSilence 250;
}

#
# Executed when a short identification should be sent
#   hour    - The hour on which this identification occur
#   minute  - The hour on which this identification occur
# MESSAGE BALISE COURTE
proc send_short_ident {{hour -1} {minute -1}} {
  global mycall;
  variable CFG_TYPE;

# ENVOI L'INDICATIF EN EPELLATION
#  spellWord $mycall;
if ![Logic::status_emergency] {
 if {$CFG_TYPE == "Repeater"} {
playMsg "$MYCALL" "BALISE";
  }
  playSilence 500;
# K EN MORSE
playTone 1000 80 100;
playSilence 50;
playTone 1000 80 50;
playSilence 50;
playTone 1000 80 100;
playSilence 50;
}
}

#
# Executed when a long identification (e.g. hourly) should be sent
#   hour    - The hour on which this identification occur
#   minute  - The hour on which this identification occur
#  BALISE LONGUE
proc send_long_ident {hour minute} {
  global mycall;
  global loaded_modules;
  global active_module;
  variable CFG_TYPE;
if ![Logic::status_emergency] {
#ENVOI INDICATIF EPELLER 
# spellWord $mycall;
  if {$CFG_TYPE == "Repeater"} {
  #  playMsg "Core" "repeater";
#playMsg "F1ZBV" "VECOUTE";
#playMsg "F1ZBV" "SITUE";
#playMsg "F1ZBV" "VFREQUEN";
#playMsg "MESSAGE" "VINFO";
#playMsg "MESSAGE" "INFO";
#playMsg "F1ZBV" "BONTRAFI";
playMsg "$MYCALL" "BALISE";
  }
}
#K EN MORSE
playSilence 500;
playTone 800 80 100;
playSilence 50;
playTone 800 80 50;
playSilence 50;
playTone 800 80 100;
playSilence 50;

  #playMsg "Core" "the_time_is";
  #playSilence 100;
  #playTime $hour $minute;
  #playSilence 500;

    # Call the "status_report" function in all modules if no module is active
  if {$active_module == ""} {
    foreach module [split $loaded_modules " "] {
      set func "::";
      append func $module "::status_report";
      if {"[info procs $func]" ne ""} {
        $func;
      }
    }
  }

  playSilence 500;
}

#
# Executed when the squelch just have closed and the RGR_SOUND_DELAY timer has
# expired.
#
proc send_rgr_sound {} {
  variable sql_rx_id;
  variable signal;
  #variable filename;
 
#lecture du signal
#   set output [exec python /etc/svxlink/smeter/smeter_1mes.py]
 
   if { [file exists /tmp/smeter.tcl]  } {
           source "/usr/share/svxlink/events.d/local/SVXCard/smeter.tcl";
           set son "";
                  if {$signal >=0} {
                        if {$signal>=10} {
                        set son "S9+" ; } else {
                        append son "S" $signal ;
                        }
                  playMsg "SVXCard/SMeter" $son;
                  }
           puts "Signal level on RX ID $sql_rx_id : $son";
        }
 
  playTone 440 200 100;
#CW::setPitch 600; # Sets the CW Tone to ~750 Hz
#CW::setAmplitude 100;
#CW::setCpm 125
#CW::play "k";
 
  playSilence 200;
 
  for {set i 0} {$i < $sql_rx_id} {incr i 1} {
    playTone 880 500 50;
    playSilence 50;
  }
  playSilence 100;
}

#
# Executed when an empty macro command (i.e. D#) has been entered.
#
proc macro_empty {} {
  playMsg "Core" "operation_failed";
}

#
# Executed when an entered macro command could not be found
#
proc macro_not_found {} {
  playMsg "Core" "operation_failed";
}

#
# Executed when a macro syntax error occurs (configuration error).
#
proc macro_syntax_error {} {
  playMsg "Core" "operation_failed";
}

#
# Executed when the specified module in a macro command is not found
# (configuration error).
#
proc macro_module_not_found {} {
  playMsg "Core" "operation_failed";
}

#
# Executed when the activation of the module specified in the macro command
# failed.
#
proc macro_module_activation_failed {} {
  playMsg "Core" "operation_failed";
}

#
# Executed when a macro command is executed that requires a module to
# be activated but another module is already active.
#
proc macro_another_active_module {} {
  global active_module;

  playMsg "Core" "operation_failed";
  playMsg "Core" "active_module";
  playMsg $active_module "name";
}

#
# Executed when an unknown DTMF command is entered
#   cmd - The command string
#
proc unknown_command {cmd} {
  spellWord $cmd;
  playMsg "Core" "unknown_command";
}

#
# Executed when an entered DTMF command failed
#   cmd - The command string
#
proc command_failed {cmd} {
  spellWord $cmd;
  playMsg "Core" "operation_failed";
}

#
# Executed when a link to another logic core is activated.
#   name  - The name of the link
#
proc activating_link {name} {
  if {[string length $name] > 0} {
    playMsg "Core" "activating_link_to";
    spellWord $name;
  }
}

#
# Executed when a link to another logic core is deactivated.
#   name  - The name of the link
#
proc deactivating_link {name} {
  if {[string length $name] > 0} {
    playMsg "Core" "deactivating_link_to";
    spellWord $name;
  }
}

#
# Executed when trying to deactivate a link to another logic core but the
# link is not currently active.
#   name  - The name of the link
#
proc link_not_active {name} {
  if {[string length $name] > 0} {
    playMsg "Core" "link_not_active_to";
    spellWord $name;
  }
}

#
# Executed when trying to activate a link to another logic core but the
# link is already active.
#   name  - The name of the link
#
proc link_already_active {name} {
  if {[string length $name] > 0} {
    playMsg "Core" "link_already_active_to";
    spellWord $name;
  }
}

#
# Executed each time the transmitter is turned on or off
#   is_on - Set to 1 if the transmitter is on or 0 if it's off
#
proc transmit {is_on} {
  publishStateEvent Logic:transmit "tx=$is_on"
  #puts "Turning the transmitter $is_on";
  variable prev_ident;
  variable need_ident;
  if {$is_on && ([clock seconds] - $prev_ident > 5)} {
    set need_ident 1;
  }
}

#
# Executed each time the squelch is opened or closed
#   rx_id   - The ID of the RX that the squelch opened/closed on
#   is_open - Set to 1 if the squelch is open or 0 if it's closed
#
proc squelch_open {rx_id is_open} {
  variable sql_rx_id;
  #puts "The squelch is $is_open on RX $rx_id";
  set sql_rx_id $rx_id;
}

#Transmit emergency mode information to RepeaterLogic.tcl
proc status_emergency {} {
  variable emergency;
  return $emergency;
}

#
# Executed when a DTMF digit has been received
#   digit     - The detected DTMF digit
#   duration  - The duration, in milliseconds, of the digit
#
# Return 1 to hide the digit from further processing in SvxLink or
# return 0 to make SvxLink continue processing as normal.
#

proc dtmf_digit_received {digit duration} {
  variable strdtmf;
 #  puts "DTMF digit \"$digit\" detected with duration $duration ms";

#when DTMF is coming truth a echo command, the durations are always 100ms. 
# events can by trigger by 
# 1- launch svxlink by this command :    nc -lk 10000 | sudo svxlink
# 2- give interruption command via DTMF long string code with : echo 212654321### | nc 127.0.0.1 10000
# the point 2 can by done via a network - replacing 127.0.0.1 by the distant ip of svxlink
# if {$duration==100 } {
 #concatenation with global variable (declaration in namespace)
#  append strdtmf  $digit;
  # value to find in the code. If find, execute a command ou playing a wav sound. Reset the memory string
#  if { [string first 123# $strdtmf 0 ] != -1   } {
#  playMsg "SVXCard/Door" "DoorOpen"
#  set strdtmf ""
#  return 1;
# }
# if { [string first 345# $strdtmf 0 ] != -1   } {
#  playMsg "SVXCard" "marc"
#  set strdtmf ""
#  return 1;
# }
#
# if { [string first 103# $strdtmf 0 ] != -1   } {
#  playMsg "SVXCard" "christian"
# set strdtmf ""
#  return 1;
# }
#
# if { [string first 101# $strdtmf 0 ] != -1   } {
#  playMsg "SVXCard" "juan"
#  set strdtmf ""
#  return 1;
# }
#
# if { [string first 102# $strdtmf 0 ] != -1   } {
#  playMsg "SVXCard" "inconnu"
#  set strdtmf ""
#  return 1;
# }
#
#}
  return 0;
}

#
# Executed when a DTMF command has been received
#   cmd - The command
#
# Return 1 to hide the command from further processing is SvxLink or
# return 0 to make SvxLink continue processing as normal.
#
# This function can be used to implement your own custom commands or to disable
# DTMF commands that you do not want users to execute.
#proc dtmf_cmd_received {cmd} {variable emergency;

  #global active_module

  # Example: Ignore all commands starting with 3 in the EchoLink module
  #if {$active_module == "EchoLink"} {
  #  if {[string index $cmd 0] == "3"} {
  #    puts "Ignoring random connect command for module EchoLink: $cmd"
  #    return 1
  #  }
  #}

  # Handle the "force core command" mode where a command is forced to be
  # executed by the core command processor instead of by an active module.
  # The "force core command" mode is entered by prefixing a command by a star.
  #if {$active_module != "" && [string index $cmd 0] != "*"} {
  #  return 0
  #}
  #if {[string index $cmd 0] == "*"} {
  #  set cmd [string range $cmd 1 end]
  #}

  # Example: Custom command executed when DTMF 99 is received
#  if {$cmd == "99"} {
#    puts "Executing external command"
#    playMsg "Core" "online"
  #  exec ls &#
#    return 1
#  }

  # Example: Custom command executed when DTMF 99 is received

proc dtmf_cmd_received {cmd} {variable emergency}

 if {$cmd == "181"} {
    set emergency 1
    puts "Emergency actived status: $emergency";
    playMsg "SVXCard/Emergency" "EmergencyMode";
    playMsg "repeater_commands" "activating";
    exec echo 1 > /sys/class/gpio/gpio22/value
    playSilence 500
    playMsg "SVXCard" "Emergency/EmergencyBeacon";
    return 1
  }
if {$cmd == "180"} {
    set emergency 0
    puts "Emergency desactived status: $emergency";
    exec echo 0 > /sys/class/gpio/gpio22/value
    playMsg "SVXCard/Emergency" "EmergencyMode";
    playMsg "repeater_commands" "deactivating";
    return 1
  }

if {$cmd == "123"} { 
    puts "Porte armoire ouverte";
    return 1
  }

#MESURE TENSION ALIM
if {$cmd == "10"} { 
    set alim [conversion 2 10 1.5]

    puts "Tension Alim:$alim V";
    playMsg "SVXCard/Mesurement" "PowerSupplyVoltage";
    playVoltage $alim;
    return 1
  }

#PRONOSTIQUES METEO EPINAL
if {$cmd == "88"} { 
    prevision_meteo "Epinal";
    return 1
  }
#PRONOSTIQUES METEO NANCY
if {$cmd == "54"} { 
    prevision_meteo "Nancy";
    return 1
  }

#
#STATION METEO DAVIS Lécture du fichier via le réseau
#

if {$cmd == "8"} {
set fileID [open "/home/meteo/statIC_hohneck.txt"]
set fileData [read $fileID];
set fileLines [split $fileData "\n"];

# Fonction de recherche
proc extraitValeur {nom fileLines} {
return [lindex [split [lsearch -inline $fileLines "$nom=*"] "="] 1]
}

#les differentes recherches:
set temperature [extraitValeur "temperature" $fileLines];
set humidite [extraitValeur "humidite" $fileLines];
set vent [extraitValeur "vent" $fileLines];
set direction_vent [extraitValeur "vent_dir_txt" $fileLines];
set ptRosee [extraitValeur "point_de_rosee" $fileLines];
set pression [extraitValeur "pression" $fileLines];

#MESSAGE INTRO METEO
playMsg "SVXCard/Weatherstation" "weatherinfo";

#TEMPERATURE EXTERIEUR:
puts "Température: $temperature °C";
playMsg "SVXCard/Weatherstation" "tempext";
playTemp $temperature;

#HUMIDITY OUT
puts "Humidité $humidite %";
playMsg "SVXCard/Weatherstation" "humidityout";
playNumber $humidite;
playMsg "Default" "percent";


#WIND DIRECTION
puts "Direction: $direction_vent";
playMsg "SVXCard/Weatherstation" "winddir";
playWindDir $direction_vent;

#WINDSPEED
puts "Vent $vent km/h";
playMsg "SVXCard/Weatherstation" "windspeed";
playNumber $vent;
playMsg "SVXCard/Weatherstation" "kmh";

#PRESSURE
puts "Pression $pression hPa";
playMsg "SVXCard/Weatherstation" "pressure";
playFourDigitNumber $pression;
playMsg "SVXCard/Weatherstation" "hpa";

#POINT DE ROSEE
puts "Point de rosée $ptRosee °C";

    return 1
  }

#INFORMATION SYSTEM
if {$cmd == "12"} {
  set result [catch {exec ping -c 1 google.com }];
  if {$result == 0} {
     puts "Internet online :Connected";
     playMsg "Default/internet_status" "Internet_Connection_Active";  

#
#Décommenter pour avoir l'adresse ip internet
#

#  set runcmd [exec curl http://ip.42.pl/short 2>&1];  #Internet IP adress
#     puts "Internet IP Adress : $runcmd"
#
#  set splitip [split $runcmd .]
#lassign $splitip ip1 ip2 ip3 ip4
#puts $ip1
#playNumber $ip1;
#playMsg "Default" "decimal";
#puts $ip2
#playNumber $ip2;
#playMsg "Default" "decimal";
#puts $ip3
#playNumber $ip3;
#playMsg "Default" "decimal";
#puts $ip4
#playNumber $ip4;

} else {
     puts "Internet online :Disconnected"
     playMsg "Default/internet_status" "Internet_Connection_Inactive";
}
    puts "Reading temperature..."

foreach file [glob -nocomplain -directory /sys/bus/w1/devices/ */w1_slave] {
    set r [exec cat $file | grep t= | cut -f2 -d= ]
    set r [format {%0.1f} [expr {$r / 1000.0}]]
    lappend temps $r;
  }

set temp1 [lindex $temps 0];
set temp2 [lindex $temps 1];
set temp3 [lindex $temps 2];
#lecture capteur 1
puts "$temp1";
playMsg "SVXCard/Temperature" "temp1";
playTemp $temp1;
# lecture capteur 2
puts "$temp2";
playMsg "SVXCard/Temperature" "temp2";
playTemp $temp2;
# lecture capteur 3
puts "$temp3";
playMsg "SVXCard/Temperature" "temp3";
playTemp $temp3;

#Annonce de la tension d'alimentation
#set alim [conversion 2 10 1.5]
#
#    puts "Tension Alim:$alim V"
#    playMsg "SVXCard/Mesurement" "PowerSupplyVoltage"
#    playVoltage $alim;

    return 1
  }

#
#LECTURE DES TEMPERATURES ARMOIRE
#

if {$cmd == "27"} {
    puts "Reading temperature...";

foreach file [glob -nocomplain -directory /sys/bus/w1/devices/ */w1_slave] {
    set r [exec cat $file | grep t= | cut -f2 -d= ];
    set r [format {%0.1f} [expr {$r / 1000.0}]];
    lappend temps $r;
    }

set temp1 [lindex $temps 0];
set temp2 [lindex $temps 1];
set temp3 [lindex $temps 2];

puts "$temp1";
playMsg "SVXCard/Temperature" "temp1";
playTemp $temp1;

puts "$temp2";
playMsg "SVXCard/Temperature" "temp2";
playTemp $temp2;

puts "$temp3";
playMsg "SVXCard/Temperature" "temp3";
playTemp $temp3;

  return 1
 }

  return 0 
}

#
# Executed once every whole minute. Don't put any code here directly
# Create a new function and add it to the timer tick subscriber list
# by using the function addTimerTickSubscriber.
#
proc every_minute {} {
  variable timer_tick_subscribers;
  #puts [clock format [clock seconds] -format "%Y-%m-%d %H:%M:%S"];
  foreach subscriber $timer_tick_subscribers {
    $subscriber;
  }
}

#
# Use this function to add a function to the list of functions that
# should be executed once every whole minute. This is not an event
# function but rather a management function.
#
proc addTimerTickSubscriber {func} {
  variable timer_tick_subscribers;
  lappend timer_tick_subscribers $func;
}

#
# Should be executed once every whole minute to check if it is time to
# identify. Not exactly an event function. This function handle the
# identification logic and call the send_short_ident or send_long_ident
# functions when it is time to identify.
#
proc checkPeriodicIdentify {} {
  variable prev_ident;
  variable short_ident_interval;
  variable long_ident_interval;
  variable min_time_between_ident;
  variable ident_only_after_tx;
  variable need_ident;
  global logic_name;


  if {[Logic::status_emergency]} {
    set now [clock seconds];
    set hour [clock format $now -format "%k"];
    regexp {([1-5]?\d)$} [clock format $now -format "%M"] -> minute;

    set emergency_ident_interval  5; #period of emergency beacon (in minutes)
    set emergency_ident_now \
              [expr {($hour * 60 + $minute) % $emergency_ident_interval == 0}];
    puts "$hour $now $minute $emergency_ident_now";

    if { $emergency_ident_now } {
      puts "$logic_name: Sending Emergency identification...";
      playMsg "SVXCard" "Emergency/EmergencyBeacon";
    }

  return;  #Following standard identification no more executed
  }


  if {$short_ident_interval == 0} {
    return;
  }
  if {$short_ident_interval == 0} {
    return;
  }

  set now [clock seconds];
  set hour [clock format $now -format "%k"];
  regexp {([1-5]?\d)$} [clock format $now -format "%M"] -> minute;

  set short_ident_now \
      	    [expr {($hour * 60 + $minute) % $short_ident_interval == 0}];
  set long_ident_now 0;
  if {$long_ident_interval != 0} {
    set long_ident_now \
      	    [expr {($hour * 60 + $minute) % $long_ident_interval == 0}];
  }

  if {$long_ident_now} {
    puts "$logic_name: Sending long identification...";
    send_long_ident $hour $minute;
    set prev_ident $now;
    set need_ident 0;
  } else {
    if {$now - $prev_ident < $min_time_between_ident} {
      return;
    }
    if {$ident_only_after_tx && !$need_ident} {
      return;
    }

    if {$short_ident_now} {
      puts "$logic_name: Sending short identification...";
      send_short_ident $hour $minute;
      set prev_ident $now;
      set need_ident 0;
    }
  }
}

#
# Executed when the QSO recorder is being activated
#
proc activating_qso_recorder {} {
  playMsg "Core" "activating";
  playMsg "Core" "qso_recorder";
}

#
# Executed when the QSO recorder is being deactivated
#
proc deactivating_qso_recorder {} {
  playMsg "Core" "deactivating";
  playMsg "Core" "qso_recorder";
}

#
# Executed when trying to deactivate the QSO recorder even though it's
# not active
#
proc qso_recorder_not_active {} {
  playMsg "Core" "qso_recorder";
  playMsg "Core" "not_active";
}

#
# Executed when trying to activate the QSO recorder even though it's
# already active
#
proc qso_recorder_already_active {} {
  playMsg "Core" "qso_recorder";
  playMsg "Core" "already_active";
}

#
# Executed when the timeout kicks in to activate the QSO recorder
#
proc qso_recorder_timeout_activate {} {
  playMsg "Core" "timeout"
  playMsg "Core" "activating";
  playMsg "Core" "qso_recorder";
}

#
# Executed when the timeout kicks in to deactivate the QSO recorder
#
proc qso_recorder_timeout_deactivate {} {
  playMsg "Core" "timeout"
  playMsg "Core" "deactivating";
  playMsg "Core" "qso_recorder";
}

#
# Executed when the user is requesting a language change
#
proc set_language {lang_code} {
  global logic_name;
  puts "$logic_name: Setting language $lang_code (NOT IMPLEMENTED)";

}

#
# Executed when the user requests a list of available languages
#
proc list_languages {} {
  global logic_name;
  puts "$logic_name: Available languages: (NOT IMPLEMENTED)";

}

#
# Executed when the node is being brought online or offline
#
proc logic_online {online} {
  global mycall
  variable CFG_TYPE

  if {$online} {
    playMsg "Core" "online";
    spellWord $mycall;
    if {$CFG_TYPE == "Repeater"} {
      playMsg "Core" "repeater";
    }
  }
}

##############################################################################
#
# Main program
#
##############################################################################

if [info exists CFG_SHORT_IDENT_INTERVAL] {
  if {$CFG_SHORT_IDENT_INTERVAL > 0} {
    set short_ident_interval $CFG_SHORT_IDENT_INTERVAL;
  }
}

if [info exists CFG_LONG_IDENT_INTERVAL] {
  if {$CFG_LONG_IDENT_INTERVAL > 0} {
    set long_ident_interval $CFG_LONG_IDENT_INTERVAL;
    if {$short_ident_interval == 0} {
      set short_ident_interval $long_ident_interval;
    }
  }
}

if [info exists CFG_IDENT_ONLY_AFTER_TX] {
  if {$CFG_IDENT_ONLY_AFTER_TX > 0} {
    set ident_only_after_tx $CFG_IDENT_ONLY_AFTER_TX;
  }
}

# end of namespace
}

#
# This file has not been truncated
#
