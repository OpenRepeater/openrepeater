<?php
#####################################################################################################
# SXVLink GPIO Config Class
#####################################################################################################

class SVXLink_Devices {

    private $deviceArray;


	public function __construct($deviceArray) {
		$this->deviceArray = $deviceArray;
	}


	###############################################
	# Build Devices Config File
	###############################################


	public function build_devices_config() {

		if ( !empty($this->deviceArray) ) { 
			$outputArray = [];
			foreach ($this->deviceArray as $key => $val) {	
				$outputArray[$key] = $val['device_path'];
			}
			$devicesString = implode(' ', $outputArray);

		} else {
			$devicesString = '';
		}

		$devicesConfigFile = '
			###############################################################################
			#
			# Configuration file for the SvxLink server Device Pins
			#
			###############################################################################
			
			# The purpose of this file is to define a list of device paths (either hidraw
			# or serial) that SvxLink will need access to. Devices defined here will have
			# their ownership and permissions set upon SvxLink startup so that they may be
			# properly accessed by the SvxLink service.
			
			# Space separated list of device paths. Typically hidraw and serial devices.
			# Examples: /dev/hidraw0 /dev/ttyUSB0
			DEV_LIST="' . $devicesString . '"
			
			# User that should own the devices
			DEV_USER="svxlink"
			
			# Group for the devices
			DEV_GROUP="daemon"
			
			# File access mode for the devices
			DEV_MODE="0664"
		';

		#Clean up tabs/white spaces
		$devicesConfigFile = trim(preg_replace('/\t+/', '', $devicesConfigFile));

		return $devicesConfigFile;
	}


}
?>