<?php
#####################################################################################################
# Hardware Class
#####################################################################################################

class Hardware {

	###############################################
	# Hidraw Devices
	###############################################

	public function get_HidrawDevices () {
		exec('ls /dev/hidraw*', $deviceArray);
		return $deviceArray;
	}

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

	public function listHidraw () {
		$deviceArray = $this->get_HidrawDevices();
		$outputHTML = '<ul>';
		foreach($deviceArray as $currDevice) {
			$currDeviceDetail = $this->get_HidrawInfo($currDevice);
			$outputHTML .= '<li>';
			$outputHTML .= $currDeviceDetail['HID_NAME'] . ' (' . $currDevice . ')<br>';
			$outputHTML .= '</li>';
		}
		$outputHTML .= '</ul>';
		return $outputHTML;
	}



	###############################################
	# Serial Devices
	###############################################

	public function get_SerialDevices () {
		exec('ls /dev/ttyUSB*', $deviceArray);
		return $deviceArray;
	}

	public function get_SerialInfo ($device) {
		$deviceName = end(explode('/',$device));
		$final[DEVICE] = $device;
		exec('udevadm info /dev/'.$deviceName, $serialInfoRaw);
		array_walk($serialInfoRaw, function($val,$key) use(&$final){
		    list($key, $value) = explode('=', $val);
		    $final[substr($key, 3)] = $value;
		});
		return $final;
	}

	public function listSerial () {
		$deviceArray = $this->get_SerialDevices();
		$outputHTML = '<ul>';
		foreach($deviceArray as $currDevice) {
			$currDeviceDetail = $this->get_SerialInfo($currDevice);
			$outputHTML .= '<li>';
			$outputHTML .= $currDeviceDetail['ID_MODEL_FROM_DATABASE'] . ' (' . $currDevice . ')<br>';
			$outputHTML .= '</li>';
		}
		$outputHTML .= '</ul>';
		return $outputHTML;
	}

}
?>