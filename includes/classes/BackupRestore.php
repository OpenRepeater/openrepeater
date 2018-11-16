<?php
#####################################################################################################
# Backup & Restore Class
#####################################################################################################

class BackupRestore {

	private $dateString;
	private $callsign;
	private $orp_version;
	
	private $backupPath = "/var/www/openrepeater/backup/";
	private $archive_base_name;
	private $archive_build_dir;
	private $backup_restore_dir;
	private $backup_db_file = 'backup.sql';
	private $db_tables = ['settings','gpio_pins','ports','modules'];
	private $backup_ini_file = 'backup.ini';
	private $backup_sounds_dir = '/var/www/openrepeater/sounds';


	public function __construct() {
		$this->dateString = date('Y/m/d H:i:s');

		$Database = new Database();
		$this->callsign = $Database->get_settings('callSign');
		$this->orp_version = $Database->get_version();

		$this->archive_build_dir = $this->backupPath . 'build/';
		$this->backup_restore_dir = $this->backupPath . 'restore/';

		$this->archive_base_name = strtolower($this->callsign) . date( "_Y-m-d_H-i-s", strtotime( $this->dateString ) );
	}



	###############################################
	# Create Backup
	###############################################

	public function create_backup() {
		// Create build directory if it doesn't exist
		if (!file_exists($this->archive_build_dir)) {
		    mkdir($this->archive_build_dir, 0777, true);
		}

		// Create INI file with basic info
		$this->write_ini( $this->archive_build_dir . $this->backup_ini_file );
		
 		// Dump the sqlite database, specified tables only
		$sql_file = $this->archive_build_dir . $this->backup_db_file;
		$Database = new Database();
		$Database->db_export( $this->db_tables, $sql_file );

		# FUTURE: Add backup of ALSA settings

		// Add build files to archive, including sounds, then package as ORP file
		$this->build_archive();

		// Clean and remove build folder & contents
		$this->removeDirectory($this->archive_build_dir);

	}
	


	###############################################
	# Restore from Backup
	###############################################

	public function pre_restore_validation($selected_restore_file) {

		// Check full file path exists before continuing
		if (!file_exists($this->backupPath . $selected_restore_file)) {
			exit('Unable to locate file for restoration');
		}

		// Create Restore directory if it doesn't exist
		if (!file_exists($this->backup_restore_dir)) {
		    mkdir($this->backup_restore_dir, 0777, true);
		} else {
			// OR if it does exist, then remove it recursively and recreate
			$this->removeDirectory($this->backup_restore_dir);
		    mkdir($this->backup_restore_dir, 0777, true);
		}

		// Unpack Backup into Temp Folder
		$this->unpack_archive($this->backupPath . $selected_restore_file);

		// Verify min files exist: DB, INI file
		if (file_exists($this->backup_restore_dir . $this->backup_ini_file)) {
			echo 'INI Exists<br>';
		} else {
			echo 'INI Doesn\'t Exists<br>';
		}

		if (file_exists($this->backup_restore_dir . $this->backup_db_file)) {
			echo 'DB Exists<br>';
		} else {
			echo 'DB Doesn\'t Exists<br>';
		}

		// Read INI and compare
		$Database = new Database();
		$curr_orp_verion = $Database->get_version();
		$ini_array = $this->read_ini( $this->backup_restore_dir . $this->backup_ini_file );
		if ($curr_orp_verion == $ini_array['ORP_Backup']['orp_version']) {
			echo 'Version Matches<br>';
		} else {
			echo 'Mismatched Version<br>';
		}
		echo 'Other Version Info: ' . $ini_array['ORP_Backup']['orp_callsign'] . ' | ' . date( "F j, Y, g:i a", strtotime( $ini_array['ORP_Backup']['backup_date'] ) ) . '<br>';

		echo '<br>End Pre-Restore';
		
	}


	public function restore_backup() {

		# Unpack Backup into Temp Folder
		# Verify min files exist: DB, INI file
		# Read INI 


		# FUTURE: Add restoration of ALSA settings


		// Empty affected DB tables and import SQL file to DB.
		$sql_file = $this->backup_restore_dir . $this->backup_db_file;

		$Database = new Database();
		$Database->db_export( $this->db_tables, $sql_file );

		$Database->db_import( $this->db_tables, '/var/www/openrepeater/backup/build/backup.sql' ); // Temp build location

		
	}



	###############################################
	# Archive Functions
	###############################################

	public function build_archive() {
		try	{
		    $archive = new PharData($this->archive_build_dir . $this->archive_base_name . '.tar');
		
		    // Add Files/Folders to Archive
		    $archive->addFile($this->archive_build_dir . $this->backup_db_file, $this->backup_db_file); // DB
		    $archive->addFile($this->archive_build_dir . $this->backup_ini_file, $this->backup_ini_file); // INI
			$archive->buildFromDirectory($this->backup_sounds_dir); // Sounds
		
		    // Compress Archive
		    $archive->compress(Phar::GZ);
		
			// Remove .tar
		    unlink($this->archive_build_dir . $this->archive_base_name . '.tar');

			// Rename Compressed format with ORP extension
			rename($this->archive_build_dir . $this->archive_base_name . '.tar.gz', $this->backupPath . $this->archive_base_name . '.orp');

		} catch (Exception $e) {
		    echo "Exception : " . $e;
		}
	}


	public function unpack_archive($selected_archive) {
		try	{
			// Create restore directory if it doesn't exist
			if (!file_exists($this->backup_restore_dir)) {
			    mkdir($this->backup_restore_dir, 0777, true);
			}

			// Copy ORP package back to .tar.gz extension in restore directory
			copy($selected_archive, $this->backup_restore_dir . $this->archive_base_name . '.tar.gz');

			// Decompress and extract
			$restore = new PharData($this->backup_restore_dir . $this->archive_base_name . '.tar.gz');
			$restore->decompress();

		    $restore = new PharData($this->backup_restore_dir . $this->archive_base_name . '.tar');
		    $restore->extractTo($this->backup_restore_dir); // extract all files

			// Remove .tar.gz and .tar
		    unlink($this->backup_restore_dir . $this->archive_base_name . '.tar.gz');
		    unlink($this->backup_restore_dir . $this->archive_base_name . '.tar');

		} catch (Exception $e) {
		    echo "Exception : " . $e;
		}
	}


	
	###############################################
	# INI File Functions
	###############################################

	public function write_ini($ini_file) {
		$backup_ini_array = [
			'ORP_Backup' => [
				'orp_version' => $this->orp_version,
				'orp_callsign' => $this->callsign,
				'backup_date' => $this->dateString				
			]
		];

		$backup_ini_contents = "; This is the Backup INI file. It provide basic information to OpenRepeater upon restore.\n\n";

		foreach ($backup_ini_array as $section => $properties_array) {
			$backup_ini_contents .= "[" . $section . "]\n";
			foreach ($properties_array as $key => $value) {
				$backup_ini_contents .= $key . " = \"" . $value . "\"\n";
			}
		}
		
		file_put_contents($ini_file,$backup_ini_contents);
	}	


	public function read_ini($ini_file) {
		$backup_ini_array = parse_ini_file($ini_file, true);
		return $backup_ini_array;
	}



	###############################################
	# Get Local Backed Up Files
	###############################################

	public function get_backup_files() {
	
		$baseURL = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->backupPath;
		
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
			$filePath = $this->backupPath . $fileName;
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
		$hidden_array = ['build','restore'];
		$total_dir_size = 0;		
		if ($backupLib) {			
			$displayHTML = '<table width="100%"><tr><th>File Name</th><th>Size</th><th>Date</th><th>&nbsp;</th></tr>';

			foreach($backupLib as $fileArray) {	
				if (!in_array($fileArray['fileName'], $hidden_array, true)) {
					$total_dir_size = $total_dir_size + $fileArray['fileSize'];
					
					// Development Output
					$displayHTML .= '
					  <tr>
					    <td>' . $fileArray['fileName'] . '</td>
					    <td>' . $this->formatSize($fileArray['fileSize']) . '</td>
					    <td>' . date("F d Y H:i:s",$fileArray['fileDate']) . '</td>
					    <td><a href="#">Restore</a> <a href="#">Download</a> <a href="#">Delete</a></td>
					  </tr>';
				}
			}

			$displayHTML .= '</table>';

			$displayHTML .= 'TOTAL FILE SIZE: ' . $this->formatSize($total_dir_size);
			
			echo $displayHTML;		

		} else {
			echo "no files";
		}

	}



	###############################################
	# Recursively Remove Directory
	###############################################

	// Only removes 2 directories deep
	public function removeDirectory($path) {
		$files = glob($path . '*');
		foreach ($files as $file) {
			if (is_dir($file)) { 
				$subfiles = glob($file . '/*');
				foreach ($subfiles as $subfile) {
					unlink($subfile);
				}
				rmdir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($path);
		return;
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