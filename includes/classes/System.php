<?php
#####################################################################################################
# System Class
#####################################################################################################

class System {

	###############################################
	# System Time
	###############################################

	public function system_time() {
		$systemTimeZone = shell_exec("cat /etc/timezone");
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
		list($system, $host, $kernel) = split(" ", exec("uname -a"), 4);

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
		$processor = str_replace("-compatible processor", "", explode(": ", exec("cat /proc/cpuinfo | grep processor"))[1]);
		$processor++; //Increment by 1 since processors start numbering at zero
		return $processor;
	}


	private function getCPU_Speed() {
		$frequency = number_format(exec("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq") / 1000);
		return $frequency . 'MHz';
	}


	private function getCPU_Load() {
		//CPU Usage
		$output1 = null;
		$output2 = null;
		//First sample
		exec("cat /proc/stat", $output1);
		//Sleep before second sample
		sleep(1);
		//Second sample
		exec("cat /proc/stat", $output2);
		$cpuload = 0;
		for ($i=0; $i < 1; $i++) {
			//First row
			$cpu_stat_1 = explode(" ", $output1[$i+1]);
			$cpu_stat_2 = explode(" ", $output2[$i+1]);
			//Init arrays
			$info1 = array("user"=>$cpu_stat_1[1], "nice"=>$cpu_stat_1[2], "system"=>$cpu_stat_1[3], "idle"=>$cpu_stat_1[4]);
			$info2 = array("user"=>$cpu_stat_2[1], "nice"=>$cpu_stat_2[2], "system"=>$cpu_stat_2[3], "idle"=>$cpu_stat_2[4]);
			$idlesum = $info2["idle"] - $info1["idle"] + $info2["system"] - $info1["system"];
			$sum1 = array_sum($info1);
			$sum2 = array_sum($info2);
			//Calculate the cpu usage as a percent
			$load = (1 - ($idlesum / ($sum2 - $sum1))) * 100;
			$cpuload += $load;
		}
		$cpuload = round($cpuload, 1); //One decimal place
		return $cpuload . '%';
	}


	private function getCPU_Temp($unit) {
		if(!$unit) { $unit = 'F'; }

		$celsius = round(exec("cat /sys/class/thermal/thermal_zone0/temp ") / 1000, 1);
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
		$uptime_array = explode(" ", exec("cat /proc/uptime"));
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
		$meminfo = file("/proc/meminfo");
		for ($i = 0; $i < count($meminfo); $i++)
		{
			list($item, $data) = split(":", $meminfo[$i], 2);
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
		exec("df -T -l -BM -x tmpfs -x devtmpfs -x rootfs -x vfat", $diskfree);
		$count = 1;
		
		while ($count < sizeof($diskfree))
		{
			list($drive[$count], $typex[$count], $size[$count], $used[$count], $avail[$count], $percent[$count], $mount[$count]) = split(" +", $diskfree[$count]);

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