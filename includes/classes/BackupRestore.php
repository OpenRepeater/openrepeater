<?php
#####################################################################################################
# Backup & Restore Class
#####################################################################################################

class BackupRestore {

	private $dateString;
	private $callsign;
	private $orp_version;

	private $backupPath = "/var/www/openrepeater/backup/";
	private $baseDownloadPath = '/backup/'; // This will get read and rewritten in construct below
	private $archive_base_name;
	private $backup_file_name;
	private $archive_build_dir;
	private $backup_restore_dir;
	private $backup_db_file = 'backup.sql';
	private $db_tables = ['settings','gpio_pins','ports','modules','devices','macros'];
	private $backup_ini_file = 'backup.ini';
	private $alsa_state_file = 'alsamixer.state';
	private $orp_sounds_dir = '/var/www/openrepeater/sounds/';

	private $Database;
	private $Modules;
	private $FileSystem;


	public function __construct() {
		$this->Database = new Database();
		$this->Modules = new Modules();
		$this->FileSystem = new FileSystem();

		$this->dateString = date('Y/m/d H:i:s');

		$this->callsign = $this->Database->get_settings('callSign');
		$this->orp_version = $this->Database->get_version();

		$this->archive_build_dir = $this->backupPath . 'build/';
		$this->backup_restore_dir = $this->backupPath . 'restore/';

		$this->archive_base_name = strtolower($this->callsign) . date( "_Y-m-d_H-i-s", strtotime( $this->dateString ) );

		// Construct base download url
		$path = $this->baseDownloadPath; // Read starting path
		$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
		$this->baseDownloadPath = $protocol .'://' . $_SERVER['HTTP_HOST'] . $path; // Rewrite as full url

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
		$this->Database->db_export( $this->db_tables, $sql_file );

		// Backup of ALSA settings
		exec('sudo orp_helper alsa backup "' . $this->archive_build_dir . $this->alsa_state_file . '"');

		// Add build files to archive, including sounds, then package as ORP file
		$this->build_archive();

		// Clean and remove build folder & contents
		$this->removeDirectory($this->archive_build_dir);

		// Verify Backup File was Created and return results.
		if (file_exists($this->backupPath . $this->backup_file_name)) {
			$returnArray = ['status' => 'success'];

			$returnArray['fileName'] = $this->backup_file_name;
			$returnArray['fileLabel'] = str_replace( '_', ' ' , pathinfo($this->backup_file_name, PATHINFO_FILENAME) );
			$returnArray['fileDate'] = date( 'Y-m-d\TH:i:s T', filemtime( $this->backupPath . $this->backup_file_name ) );
			$returnArray['fileSize'] = filesize( $this->backupPath . $this->backup_file_name );
			$returnArray['downloadURL'] = $this->FileSystem->buildURL($this->backup_file_name, 'restore');;
			$returnArray['full_path'] = $this->backupPath . $this->backup_file_name;		

		} else {
			$returnArray = ['status' => 'error'];
		}

		return json_encode($returnArray);

	}




	###############################################
	# Restore from Backup
	###############################################

	public function pre_restore_validation($selected_restore_file) {
		$data = [];
		$errorLevel = 0;

		// Check full file path exists before continuing
		if (!file_exists($this->backupPath . $selected_restore_file)) {
			$returnArray = ['status' => 'error_noFile'];
			return json_encode($returnArray);
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
		if (!file_exists($this->backup_restore_dir . $this->backup_ini_file)) { $errorLevel++; }
		if (!file_exists($this->backup_restore_dir . $this->backup_db_file)) { $errorLevel++; }

		if ($errorLevel == 0) {
			$data['status'] = 'ok';

			// Read INI and compare
			$curr_orp_verion = $this->Database->get_version();
			$ini_array = $this->read_ini( $this->backup_restore_dir . $this->backup_ini_file );
			if ($curr_orp_verion == $ini_array['ORP_Backup']['orp_version']) {
				$data['versionMatch'] = true;
			} else {
				$data['versionMatch'] = false;
			}
			$data['curr_orp_verion'] = $curr_orp_verion;
			$data['backup_orp_verion'] = $ini_array['ORP_Backup']['orp_version'];
			$data['backup_callsign'] = $ini_array['ORP_Backup']['orp_callsign'];
			$data['backup_date'] = date( 'Y-m-d\TH:i:s T', strtotime($ini_array['ORP_Backup']['backup_date']) );
			echo json_encode($data);

		} else {
			$returnArray = ['status' => 'error_incomplete'];
			return json_encode($returnArray);
		}

	}


	public function restore_backup() {

		########################################
		# Copy Sounds into place

		$existing_courtesy_tones = $this->orp_sounds_dir . 'courtesy_tones/';
		$existing_identification = $this->orp_sounds_dir . 'identification/';
		$restore_courtesy_tones = $this->backup_restore_dir . 'courtesy_tones/';
		$restore_identification = $this->backup_restore_dir . 'identification/';

		// If Courtesy Tones exist in backup, then restore
		if (file_exists($restore_courtesy_tones)) {
			$this->removeDirectory($existing_courtesy_tones);
			exec("cp $restore_courtesy_tones $existing_courtesy_tones -R");
		}

		// If Identification exist in backup, then restore
		if (file_exists($restore_identification)) {
			$this->removeDirectory($existing_identification);
			exec("cp $restore_identification $existing_identification -R");
		}


		########################################
		# Restore Modules (non-core / add-ons)

		$restore_module_path = $this->backup_restore_dir . 'modules/';
		
		if (file_exists($restore_module_path)) {
			// Remove existing modules first (non-core)
			$non_core_modules = $this->Modules->get_non_core_modules_path();
			if(isset($non_core_modules)) {
				foreach($non_core_modules as $mod_name => $mod_path) {
					$this->Modules->remove_module($mod_name);
				}
			}

			// Restore backed up modules
			$mod_folder_list  = glob($restore_module_path . '*', GLOB_ONLYDIR);
			foreach($mod_folder_list as $mod_path) {
				$mod_name = basename($mod_path);
				exec('cp "' . $mod_path . '" "' . $this->Modules->modules_path . $mod_name . '" -R');

				// Init module just to create symlinks. DB will get overwritten below
				$this->Modules->initialize_module($mod_name, 0);
			}

		}

		########################################

		// Restoration of ALSA settings
		if (file_exists($this->backup_restore_dir . $this->alsa_state_file)) {
			exec('sudo orp_helper alsa restore "' . $this->backup_restore_dir . $this->alsa_state_file . '"');
		}

		// Empty affected DB tables and import SQL file to DB.
		$sql_file = $this->backup_restore_dir . $this->backup_db_file;
		$this->Database->db_import( $this->db_tables, $sql_file );

		// Cleanup/Delete Restore directory
		$this->removeDirectory($this->backup_restore_dir);

		// Set Rebuild Flag
		$this->Database->set_update_flag(true);

		$returnArray = ['status' => 'restore_success'];
		return json_encode($returnArray);

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

			if ( file_exists($this->archive_build_dir . $this->alsa_state_file) ) {
				$archive->addFile($this->archive_build_dir . $this->alsa_state_file, $this->alsa_state_file); // alsamixer backup
			}

			$archive->buildFromDirectory($this->orp_sounds_dir); // Sounds


			// Modules (non-core / add-ons)
			$non_core_modules = $this->Modules->get_non_core_modules_path();
			if ( isset($non_core_modules) ) {
				$mod_build_dir = $this->archive_build_dir . 'mod_build/modules';
				if (!file_exists($mod_build_dir)) { mkdir($mod_build_dir, 0777, true); }

				foreach($non_core_modules as $mod_name => $mod_path) {
					exec('cp "' . $mod_path . '" "' . $mod_build_dir . '/' . $mod_name . '" -R');
				}
				$archive->buildFromDirectory($this->archive_build_dir . 'mod_build/');
			}


			// Compress Archive
			$archive->compress(Phar::GZ);

			// Remove .tar
			unlink($this->archive_build_dir . $this->archive_base_name . '.tar');

			// Rename Compressed format with ORP extension
			rename($this->archive_build_dir . $this->archive_base_name . '.tar.gz', $this->backupPath . $this->archive_base_name . '.orp');

			// Set file name as variable for verification and display
			$this->backup_file_name = $this->archive_base_name . '.orp';

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

		// Remove hidden files
		$fileList = array_filter($fileList, create_function('$a','return ($a[0]!=".");'));

		// Sort and reindex array
		natsort($fileList);
		$fileList = array_values($fileList);

		// Write into multidimensional array with clean file labels
		foreach($fileList as $fileName) {	
			$fileLabel = str_replace("_"," ",$fileName); //replace underscores with spaces for file labels
			$fileLabel = preg_replace('/\\.[^.\\s]{2,5}$/', '', $fileLabel); //remove extention
			$downloadURL = $baseURL . $fileName;
			$filePath = $this->backupPath . $fileName;
			$fileSize = filesize($filePath);
			$fileDate = filemtime($filePath);
			$filesArray[] = array('fileName' => $fileName, 'fileLabel' => $fileLabel, 'downloadURL' => $downloadURL, 'filePath' => $filePath, 'fileSize' => $fileSize, 'fileDate' => $fileDate);		
		}

	return $filesArray;
	}



	public function getBackupFilesJSON() {
		$backupLib = $this->get_backup_files();
		$hidden_array = ['build','restore','.gitignore'];
		$total_dir_size = 0;		
		if ($backupLib) {			
			$returnFileArray = [];
			$curFileNum = 0;
			$displayHTML = '';

			foreach($backupLib as $fileArray) {
				if (!in_array($fileArray['fileName'], $hidden_array, true)) {
					$curFileNum++;
					
					$total_dir_size = $total_dir_size + $fileArray['fileSize'];

					$returnFileArray[$curFileNum]['fileName'] = $fileArray['fileName'];
					$returnFileArray[$curFileNum]['fileDate'] = date("Y-m-d\TH:i:s T",$fileArray['fileDate']);
					$returnFileArray[$curFileNum]['fileSize'] = $fileArray['fileSize'];
					$returnFileArray[$curFileNum]['downloadURL'] = $this->baseDownloadPath . $fileArray['fileName'];
				}
			}
			$returnFileArray['totalDirSize'] = $total_dir_size;

		} else {
			$returnFileArray['totalDirSize'] = 0;
		}
		return json_encode($returnFileArray);
	}



	###############################################
	# Recursively Remove Directory
	###############################################

	public function removeDirectory($path) {
		exec('rm ' . $path . ' -R');
		return;
	}


}