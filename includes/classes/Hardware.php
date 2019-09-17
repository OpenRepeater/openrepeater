<?php
#####################################################################################################
# Hardware Class
#####################################################################################################

class Hardware {

	###############################################
	# Hidraw Devices
	###############################################

	public function get_HidrawInfo ($device) {
		$deviceName = end(explode('/',$device));
		$final[DEVICE] = $device;
		exec('cat /sys/class/hidraw/'.$deviceName.'/device/uevent', $hidInfoRaw);
		array_walk($hidInfoRaw, function($val,$key) use(&$final){
		    list($key, $value) = explode('=', $val);
		    $final[$key] = $value;
		});
		return $final;
	}



}
?>