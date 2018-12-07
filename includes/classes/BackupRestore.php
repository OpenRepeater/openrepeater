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
	private $db_tables = ['settings','gpio_pins','ports','modules'];
	private $backup_ini_file = 'backup.ini';
	private $alsa_state_file = 'alsamixer.state';
	private $orp_sounds_dir = '/var/www/openrepeater/sounds/';
	
	private $Database;
	private $Modules;


	public function __construct() {
		$this->Database = new Database();
		$this->Modules = new Modules();

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
			return array(
				'msgType' => 'success',
				'msgText' => 'Successfully created backup: <strong>' . $this->backup_file_name . '</strong>'
			);	
		} else {
			return array(
				'msgType' => 'error',
				'msgText' => 'There was a problem creating the backup. Please try again. If the problem persists, it may be due to a permissions issue.'
			);				
		}

	}
	
	


	###############################################
	# Restore from Backup
	###############################################

	public function pre_restore_validation($selected_restore_file) {
		$data = [];
		$errorLevel = 0;

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
			$data['backup_date'] = date( "F j, Y, g:i a", strtotime( $ini_array['ORP_Backup']['backup_date'] ) );
			
		} else {
	        $data['status'] = 'error';
		}

        $data['errorLevel'] = $errorLevel;

	    //returns data as JSON format
	    echo json_encode($data);		
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

		return array(
			'msgType' => 'success',
			'msgText' => 'Backup was successfully restored.'
		);	

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
		    $archive->addFile($this->archive_build_dir . $this->alsa_state_file, $this->alsa_state_file); // alsamixer backup

			$archive->buildFromDirectory($this->orp_sounds_dir); // Sounds


			// Modules (non-core / add-ons)
			$non_core_modules = $this->Modules->get_non_core_modules_path();
			if ( count($non_core_modules)>0 ) {
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
	# Upload Backup Files
	###############################################

	public function upload_backup_files($fileNameArray) {

		$maxFileSize = 500000000; // size in bytes
		$allowedExts = array('orp');
	
		//Loop through each file
		for($i=0; $i<count($fileNameArray['name']); $i++) {
			//Get the temp file path
			$tmpFile1 = $fileNameArray['tmp_name'][$i];
			
			$temp_ext = explode(".", $fileNameArray['name'][$i]);
			$extension = end($temp_ext);

			$currFile = $this->backupPath . str_replace(" ","_",$fileNameArray['name'][$i]);
			

			# Check File Size isn't too large
			if($fileNameArray['size'][$i] > $maxFileSize){
				return array(
					'msgType' => 'error',
					'msgText' => 'Sorry, but the file you tried to upload is <strong>too large</strong>.'
				);	
			}

			# Check to see if file is allowed type
			if(!in_array($extension, $allowedExts)) {
				return array(
					'msgType' => 'error',
					'msgText' => 'Sorry, but the file you tried to upload is not in a supported format. Files must end with an .orp extension.'
				);	
			}

			# Check to see if system temp folder is writable
			if (!is_writable( sys_get_temp_dir() )) {
				return array(
					'msgType' => 'error',
					'msgText' => 'Sorry, it looks like there is a configuration issue. The system\'s temp folder is not writable'
				);	
			}

			# Check for error reported by file array
			if ($fileNameArray['error'][$i] > 0) {
				return array(
					'msgType' => 'error',
					'msgText' => 'There was a problem uploading the file'
				);	
			}

			# Check to see if file already exists
			if (file_exists($currFile)) {
				return array(
					'msgType' => 'error',
					'msgText' => 'Sorry but the file already exists.'
				);	
			}


			if ($tmpFile1 != ""){
				move_uploaded_file($tmpFile1, $currFile);

				if (file_exists($currFile)) {
					// SUCCESSFUL
					return array(
						'msgType' => 'success',
						'msgText' => 'Successfully uploaded the backup file to library.'
					);	
					
				} else {
					// Failure
					return array(
						'msgType' => 'error',
						'msgText' => 'There was a problem uploading the file.'
					);						
				}
				
			}

		}

		# Some how it got thru validation, but nothing was done.
		return array(
			'msgType' => 'error',
			'msgText' => 'Don\'t know what happened, but nothing appears to have been done.'
		);	

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
		$hidden_array = ['build','restore','.gitignore'];
		$total_dir_size = 0;		
		if ($backupLib) {			
			$displayHTML = '<table class="table table-striped table-condensed bootstrap-datatable">';
			$displayHTML .= '<table class="table table-striped table-condensed bootstrap-datatable">';
			$displayHTML .= '<thead><tr class="audio_row"><th>Name</th><th>Date</th><th>Size</th><th class="button_grp">Actions</th></tr></thead>   
	<tbody>';


			foreach($backupLib as $fileArray) {	
				if (!in_array($fileArray['fileName'], $hidden_array, true)) {
					$total_dir_size = $total_dir_size + $fileArray['fileSize'];
					
					$displayHTML .= '
					<tr id="shortIDsoundRow1" class="audio_row">
						<td><h3>' . $fileArray['fileName'] . '</h3></td>
						
						<td class="center">' . date("F d Y H:i:s",$fileArray['fileDate']) . '</td>
							
						<td class="center">' . $this->formatSize($fileArray['fileSize']) . '</td>

						<td class="button_grp">
						
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#restoreFile" onclick="restoreFile(\'' . $fileArray['fileName'] . '\'); return false;"><i class="icon-refresh icon-white"></i> Restore</button>
						
							<!-- Button triggered modal -->
							<button class="btn" onclick="location.href=\'' . $this->baseDownloadPath . $fileArray['fileName'] . '\'"><i class="icon-download-alt"></i></button>
			
							<!-- Button triggered modal -->
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteFile" onclick="deleteFile(\'' . $fileArray['fileName'] . '\'); return false;"><i class="icon-trash icon-white"></i></button>
						</td>
					</tr>
					';
				}
			}

			$displayHTML .= '</tbody></table>';

			$displayHTML .= 'TOTAL FILE SIZE: ' . $this->formatSize($total_dir_size);
			
			echo $displayHTML;		

		} else {
			echo "no files";
		}

	}



	###############################################
	# Delete Backup
	###############################################

	public function deleteBackup($file) {
	    unlink($this->backupPath . $file);

		if (!file_exists($this->backupPath . $file)) {
			return array(
				'msgType' => 'success',
				'msgText' => 'File successfully Deleted.'
			);	
		} else {
			return array(
				'msgType' => 'error',
				'msgText' => 'There was a problem deleting the file. Please try again. If the problem persists, it may be due to a permissions issue.'
			);				
		}
	}



	###############################################
	# Recursively Remove Directory
	###############################################

	// Only removes 2 directories deep
	public function removeDirectory($path) {
/*
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
*/
		exec('rm ' . $path . ' -R');
		return;
	}



	###############################################
	# Delete Backup
	###############################################

	public function is_dir_empty($dir) {
		if (!file_exists($dir)) { return NULL; }
		if (!is_readable($dir)) { return NULL; }
		return (count(scandir($dir)) == 2);
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