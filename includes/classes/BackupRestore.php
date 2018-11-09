<?php
#####################################################################################################
# Backup & Restore Class
#####################################################################################################

class BackupRestore {

    private $dateString;
    private $callsign;
    private $backupPath = "/var/www/openrepeater/backup";

//     private $backupPath = "/var/lib/openrepeater/sounds/";


	public function __construct() {
		$this->dateString = date('Ymd-His');
		$this->callsign = strtolower("T3EST");

		//echo 'orp-' . $this->callsign . '-' . $this->dateString;
	}



	###############################################
	# Get Local Backed Up Files
	###############################################

	public function create_backup() {
		echo "DO THE BACKUP";
		$categories = Database::get_settings();
	}

	###############################################
	# Get Local Backed Up Files
	###############################################

	public function get_backup_files() {
	
		$baseURL = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->backupPath . '/';
		
		// Read Files into 1 dimensional array
		if ($handle = opendir($this->backupPath)) {
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
			$downloadURL = $baseURL . $fileName;
// 			$downloadURL = '#';
			$filePath = $this->backupPath . '/' . $fileName;
			$fileSize = filesize($filePath);
			$fileDate = filemtime($filePath);
			$filesArray[] = array('fileName' => $fileName, 'fileLabel' => $fileLabel, 'downloadURL' => $downloadURL, 'filePath' => $filePath, 'fileSize' => $fileSize, 'fileDate' => $fileDate);			
		}
	
		return $filesArray;
	}



	###############################################
	# Display Backup Files
	###############################################

	public function display_backup_files() {

		$backupLib = $this->get_backup_files();
		
		if ($backupLib) {			
			$displayHTML = '<table width="100%"><tr><th>File Name</th><th>Size</th><th>Date</th><th>&nbsp;</th></tr>';

			foreach($backupLib as $fileArray) {	
				// Development Output
				$displayHTML .= '
				  <tr>
				    <td>' . $fileArray['fileName'] . '</td>
				    <td>' . $this->formatSize($fileArray['fileSize']) . '</td>
				    <td>' . date("F d Y H:i:s",$fileArray['fileDate']) . '</td>
				    <td><a href="#">Restore</a> <a href="#">Download</a> <a href="#">Delete</a></td>
				  </tr>';
			}

			$displayHTML .= '</table>';
			
			echo $displayHTML;		

		} else {
			echo "no files";
		}

	}



	###############################################
	# Format File Size
	###############################################

	public function formatSize($bytes, $precision = 2) { 
	    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
	
	    $bytes = max($bytes, 0); 
	    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
	    $pow = min($pow, count($units) - 1); 
	    $bytes /= pow(1024, $pow);
	
	    return round($bytes, $precision) . ' ' . $units[$pow]; 
	}

}