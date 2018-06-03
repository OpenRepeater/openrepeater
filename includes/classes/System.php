<?php
#####################################################################################################
# System Class
#####################################################################################################

class System {

	private function orp_helper_call($section, $subfunc) {
		return shell_exec( "sudo orp_helper " . trim($section) . " " . trim($subfunc) );
	}

	###############################################
	# System Time
	###############################################

	public function system_time() {
		$systemTimeZone = $this->orp_helper_call('info','timezone');
		date_default_timezone_set( trim($systemTimeZone) ); 

		return [
			'datetime' => date( 'Y/m/d H:i:s', time() ),
			'date' => date( 'd M Y', time() ),
			'time' => date( 'H:i:s', time() ),
			'tz_long' => $systemTimeZone,
			'tz_short' => date( 'T', time() ),
		];
	}



	###############################################
	# System Info
	###############################################

	public function system_info() {
 		list($system, $host, $kernel) = explode(" ", $this->orp_helper_call('info','os'), 4);

		$cpuTempF = $this->getCPU_Temp('F');
		$cpuTempC = $this->getCPU_Temp('C');

		return [
			'host' => $host,
			'kernel' => $system . ' ' . $kernel,
			'cpu_cores' => $this->getCPU_Type(),
			'cpu_speed' => $this->getCPU_Speed(),
			'cpu_load' => $this->getCPU_Load(),
			'cpuTempF' => $cpuTempF,
			'cpuTempC' => $cpuTempC,
			'cpuTempBoth' => $cpuTempF . ' / ' . $cpuTempC,
			'uptime' => $this->getUptime(),
		];
	}


	private function getCPU_Type() {
		$proc_list = str_replace( "processor	: ", "", $this->orp_helper_call('info','cpu_type') );
		$proc_array = explode( "\n", $proc_list );
		$proc_array = array_diff($proc_array, [""]);
		return count($proc_array);
	}


	private function getCPU_Speed() {
		$frequency = number_format($this->orp_helper_call('info','cpu_speed') / 1000);
		return $frequency . 'MHz';
	}


	private function getCPU_Load() {
		//CPU Usage
		$cpuload = $this->orp_helper_call('info','cpu_load');
		return trim($cpuload) . '%';
	}


	private function getCPU_Temp($unit) {
		if(!$unit) { $unit = 'F'; }

		$celsius = round($this->orp_helper_call('info','cpu_temp') / 1000, 1);
		$fahrenheit = $celsius * 1.8 + 32;

		if ($unit == 'C') {
			// Return CPU Temperature in Celsius 
			$cpu_temperature = $celsius . '°C';
		} else {
			// Return CPU Temperature in Fahrenheit
			$cpu_temperature = $fahrenheit . '°F';
		}
		
		if ($celsius > 75) {
			$cpu_temperature = '<span style="color:red;"><strong>' . $cpu_temperature . '</strong></span>';
		}
		return $cpu_temperature;
	}


	private function getUptime() {
		//Uptime
		$uptime_array = explode(" ", $this->orp_helper_call('info','uptime'));
		$seconds = round($uptime_array[0], 0);
		$minutes = $seconds / 60;
		$hours = $minutes / 60;
		$days = floor($hours / 24);
		$hours = sprintf('%02d', floor($hours - ($days * 24)));
		$minutes = sprintf('%02d', floor($minutes - ($days * 24 * 60) - ($hours * 60)));
		if ($days == 0):
			$uptime = $hours . ":" .  $minutes . " (hh:mm)";
		elseif($days == 1):
			$uptime = $days . " day, " .  $hours . ":" .  $minutes;
		else:
			$uptime = $days . " days, " .  $hours . ":" .  $minutes;
		endif;
		return $uptime;
	}



	###############################################
	# Memory Usage
	###############################################

	public function memory_usage() {
		$meminfo = $this->orp_helper_call('info','memory_usage');
		$meminfo = explode( "\n", $meminfo );
		$meminfo = array_diff($meminfo, [""]);

		for ($i = 0; $i < count($meminfo); $i++)
		{
			list($item, $data) = explode(":", $meminfo[$i], 2);
			$item = trim(chop($item));
			$data = intval(preg_replace("/[^0-9]/", "", trim(chop($data)))); //Remove non numeric characters
			switch($item)
			{
				case "MemTotal": $total_mem = $data; break;
				case "MemFree": $free_mem = $data; break;
				case "Buffers": $buffer_mem = $data; break;
				case "Cached": $cache_mem = $data; break;
				default: break;
			}
		}

		$used_mem = $total_mem - $free_mem;

		return [
			'percent_used' => round(($used_mem / $total_mem) * 100),
			'percent_free' => round(($free_mem / $total_mem) * 100),
			'percent_buff' => round(($buffer_mem / $total_mem) * 100),
			'percent_cach' => round(($cache_mem / $total_mem) * 100),
			'used_mem' => $this->capacity($used_mem / 1000),
			'free_mem' => $this->capacity($free_mem / 1000),
			'buffer_mem' => $this->capacity($buffer_mem / 1000),
			'cache_mem' => $this->capacity($cache_mem / 1000),
			'total_mem' => $this->capacity($total_mem / 1000),
		];
	}
	


	###############################################
	# Disk Usage
	###############################################

	public function disk_usage() {
		$diskfree = $this->orp_helper_call('info','disk_usage');
		$diskfree = explode( "\n", $diskfree );
		$diskfree = array_diff($diskfree, [""]);

		$count = 1;
		
		while ($count < sizeof($diskfree))
		{
			$parts = preg_split('/\s+/', $diskfree[$count]);

			list($drive[$count], $typex[$count], $size[$count], $used[$count], $avail[$count], $percent[$count], $mount[$count]) = $parts;

			$diskArray[$count] = [
				'drive' => $drive[$count],
				'typex' => $typex[$count],
				'size' => $size[$count],
				'used' => $used[$count],
				'avail' => $avail[$count],
				'percent' => $percent[$count],
				'mount' => $mount[$count],
				'capacity' => $this->capacity($size[$count]),
			];

			$percent_part[$count] = $percent[$count];
			$count++;
		}
		
		return $diskArray;
	}


	private function capacity($raw_size) {
		$clean_size = trim($raw_size);

		if ($clean_size > 1000000000 ) {
			$capacity = number_format(($clean_size * .000000001), 2, '.', ',') . " PB";
		} elseif ($clean_size > 1000000 ) {
			$capacity = number_format(($clean_size * .000001), 2, '.', ',') . " TB";
		} elseif ($clean_size > 1000 ) {
			$capacity = number_format(($clean_size * .001), 1, '.', ',') . " GB";
		} else {
			$capacity = number_format($clean_size, 1, '.', ',') . " MB";
		}

		return $capacity;
	}


}
?>