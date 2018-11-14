<?php
#####################################################################################################
# Backup & Restore Class
#####################################################################################################

class BackupRestore {

    private $dateString;
    private $callsign;
    private $orp_version;

    private $backupPath = "/var/www/openrepeater/backup/";
    private $archive_base_name = 'orp_backup';
    private $archive_build_dir;
    private $backup_restore_dir;
    private $backup_db_file = 'orp_test.sql';
    private $backup_ini_file = 'backup.ini';
    private $backup_sounds_dir = '/var/www/openrepeater/sounds';


//     private $backupPath = "/var/lib/openrepeater/sounds/";


	public function __construct() {
		$this->dateString = date('Y/m/d H:i:s');

		$Database = new Database();
		$this->callsign = $Database->get_settings('callSign');
		$this->orp_version = $Database->get_version();

		$this->archive_build_dir = $this->backupPath . 'build/';
		$this->backup_restore_dir = $this->backupPath . 'restore/';

	}



	###############################################
	# Create Backup
	###############################################

	public function create_backup() {
 		echo '<br>' . nl2br( $this->write_ini() );
		
 		# Create INI file with basic info
 		# Add INI to archive
 		# Dump the sqlite database (omit the user table is possible)
 		# Backup the sounds folder
 		# Add the sqlite dump to the archive, then remove the dump
 		# Compress the archive then remove the tarball
 		# rename archive with ORP extension
echo "DONE";

/*
		$Database = new Database();
		$Database->db_dump();
*/

		$this->read_ini();
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
			rename($this->archive_build_dir . $this->archive_base_name . '.tar.gz', $this->archive_build_dir . $this->archive_base_name . '.orp');

		} catch (Exception $e) {
		    echo "Exception : " . $e;
		}
	}


	public function unpack_archive() {
		try	{
			$orp_archive = $this->backupPath . $this->archive_base_name . '.orp';

			// Create restore directory if it doesn't exist
			if (!file_exists($this->backup_restore_dir)) {
			    mkdir($this->backup_restore_dir, 0777, true);
			}

			// Copy ORP package back to .tar.gz extension in restore directory
			copy($orp_archive, $this->backup_restore_dir . $this->archive_base_name . '.tar.gz');

			$restore = new PharData($this->backup_restore_dir . $this->archive_base_name . '.tar.gz');
			$restore->decompress(); // creates /path/to/my.tar

		    $restore = new PharData($this->backup_restore_dir . $this->archive_base_name . '.tar');
		    $restore->extractTo($this->backup_restore_dir); // extract all files

			// Remove .tar
		    unlink($this->backup_restore_dir . $this->archive_base_name . '.tar.gz');
		    unlink($this->backup_restore_dir . $this->archive_base_name . '.tar');

			echo 'Restore Complete';

		} catch (Exception $e) {
		    echo "Exception : " . $e;
		}
	}


	
	public function write_ini() {
		$backup_ini_array = [
			'ORP_Backup' => [
				'orp_version' => $this->orp_version,
				'orp_callsign' => $this->callsign,
				'backup_date' => $this->dateString				
			]
		];

		$backup_ini = "; This is the Backup INI file. It provide basic information to OpenRepeater upon restore.\n\n";

		foreach ($backup_ini_array as $section => $properties_array) {
			$backup_ini .= "[" . $section . "]\n";
			foreach ($properties_array as $key => $value) {
				$backup_ini .= $key . " = \"" . $value . "\"\n";
			}
		}
		
		$file = $this->backupPath . "backup.ini";
		file_put_contents($file,$backup_ini);

		return $backup_ini;
	}	



	###############################################
	# Restore from Backup
	###############################################

	public function restore_backup() {

		# Unpack Backup into Temp Folder
		# Verify min files exist: DB, INI file
		# Read INI 
		
	}

	public function read_ini() {
		$file = $this->backupPath . "backup.ini";

		$backup_ini_array = parse_ini_file($file, true);
		print_r($backup_ini_array);
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