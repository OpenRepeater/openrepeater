<?php
#####################################################################################################
# AudioFiles Class
#####################################################################################################

class AudioFiles {

    private $path = "/var/lib/openrepeater/sounds/";
    private $temp_dir = "/tmp/";
    private $sound_dir = '/sounds/';


	###############################################
	# Get Audio Files by Type
	###############################################

	### DEPRECIATED FUNCTION - ORP 2.2.X AND PRIOR ###
	public function get_audio_files($type) {
	
		$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->sound_dir . $type . '/';
		
		// Read Files into 1 dimensional array
		if ($handle = opendir($this->path . $type)) {
			while (false !== ($file = readdir($handle))) {
				if ('.' === $file) continue;
				if ('..' === $file) continue;
				$fileList[] = $file;
			}
			closedir($handle);	
		}
	
		// Sort and reindex array
		natsort($fileList);
		$fileList = array_values($fileList);
	
		// Write into multidimensional array with clean file labels
		foreach($fileList as $fileName) {	
			$fileLabel = str_replace("_"," ",$fileName); //replace underscores with spaces for file labels
			$fileLabel = preg_replace('/\\.[^.\\s]{2,5}$/', '', $fileLabel); //remove extention
			$fileURL = $url . $fileName;
			$filePath = $this->path . $type . '/' . $fileName;
			$filesArray[] = array('fileName' => $fileName, 'fileLabel' => $fileLabel, 'fileURL' => $fileURL, 'filePath' => $filePath);			
		}
	
		return $filesArray;

	}

	# New function for 3.0
	public function get_audio_filesJSON($type) {
	
		$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->sound_dir . $type . '/';
		
		// Read Files into 1 dimensional array
		if ($handle = opendir($this->path . $type)) {
			while (false !== ($file = readdir($handle))) {
				if ('.' === $file) continue;
				if ('..' === $file) continue;
				$fileList[] = $file;
			}
			closedir($handle);	
		}

		// Remove hidden files
		$fileList = array_filter($fileList, create_function('$a','return ($a[0]!=".");'));
	
		// Sort and reindex array
		natsort($fileList);
		$fileList = array_values($fileList);
	
		// Write into multidimensional array with clean file labels
		foreach($fileList as $fileName) {	
			$fileLabel = str_replace("_"," ",$fileName); //replace underscores with spaces for file labels
			$fileLabel = preg_replace('/\\.[^.\\s]{2,5}$/', '', $fileLabel); //remove extention
			$fileURL = $url . $fileName;
			$filePath = $this->path . $type . '/' . $fileName;
			$filesArray[] = array('fileName' => $fileName, 'fileLabel' => $fileLabel, 'fileURL' => $fileURL, 'filePath' => $filePath);			
		}
	
		return json_encode($filesArray);

	}

}
?>