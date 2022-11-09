<?php
#####################################################################################################
# Users Class
#####################################################################################################

class FileSystem {

	private $basePath = "/var/www/openrepeater/";

	private $courtesyTonePath = 'sounds/courtesy_tones/';
	private $identificationPath = 'sounds/identification/';
	private $modulePath = 'new_ui/dz/upload/';
	private $backupPath = 'backup/';


	public function __construct() {
// 		$this->Database = new Database();
	}



	###############################################
	# Upload Files
	###############################################

	public function uploadFiles($filesArray, $postArray) {
		$error_count = 0;
		
		// Build base downloaod URL
		$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
		$baseURL = $protocol .'://' . $_SERVER['HTTP_HOST'] . '/';

		if(!empty($filesArray)) {
			$uploadType = $postArray['uploadType'];
			switch($uploadType) {
				case 'courtesy_tone':
					$folder_path = $this->basePath . $this->courtesyTonePath;
					$baseURL = $baseURL . $this->courtesyTonePath;
					$convertAudio = true;
					break;
				case 'identification':
					$folder_path = $this->basePath . $this->identificationPath;
					$baseURL = $baseURL . $this->identificationPath;
					$convertAudio = true;
					break;
				case 'restore':
					$folder_path = $this->basePath . $this->backupPath;
					$baseURL = $baseURL . $this->backupPath;
					$convertAudio = false;
					break;

				case 'module':
					$folder_path = $this->basePath . $this->modulePath;
					$convertAudio = false;
					break;
			}

			$returnArray = [];
			foreach($filesArray['file']['name'] as $curKey => $curFile) {
				$returnArray[$curKey]['fileName'] = $curFile;
				$returnArray[$curKey]['fileLabel'] = $curFile; // Needs Fixed
				$returnArray[$curKey]['fileDate'] = date("Y-m-d\TH:i:s T");
				$returnArray[$curKey]['fileSize'] = $filesArray['file']['size'][$curKey];
				if ($uploadType != 'module') {
					$returnArray[$curKey]['downloadURL'] = $baseURL . $curFile;
				}
				$returnArray[$curKey]['full_path'] = $folder_path . $curFile;		
				$returnArray[$curKey]['tmp_name'] = $filesArray['file']['tmp_name'][$curKey];
		
		/*
				file_exists( $returnArray[$curKey]['full_path'] ) {
					$returnArray[$curKey]['status'] = 'exists';
					$error_count++;
				} else {
					//Move the upload file into place
					$returnArray[$curKey]['status'] = 'success';
				}
		*/
				move_uploaded_file($returnArray[$curKey]['tmp_name'], $returnArray[$curKey]['full_path']);
				
				// Convert audio foormat if audio upload type
// 				if ($convertAudio) { $this->convert_audio($returnArray[$curKey]['full_path']); }


// {"fileName":"n3mbh_2019-01-26_21-14-16.orp","fileDate":"2020-01-07T13:28:56 EST","fileSize":2105765,"downloadURL":"http://localhost/backup/n3mbh_2019-01-26_21-14-16.orp"}
// {"status":"success","data":[{"filename":"fake1.orp","full_path":"\/var\/www\/openrepeater\/backup\/fake1.orp","tmp_name":"\/tmp\/phpPofKiI","size":0,"datetime":"20221108130405"}]}
			}
			return json_encode($returnArray);
			

		} else {
			return 'no files sent';
		}


// 		return $this->endUserSession();
	}



	###############################################
	# Convert Audio File
	###############################################

	private function convert_audio($source, $destination) {
		$destination = '/var/lib/openrepeater/sounds/identification/123.wav';

		$soxCommand = 'sox "' . $source . '" -r16000 -b16 -esigned-integer -c1 "' . $destination . '"';
		exec($soxCommand);

		if (file_exists($destination)) {
			return true;
		} else {
			return false;			
		}
	}



	###############################################
	# Delete Files
	###############################################

	public function deleteFiles($inputArray) {
		$error_count = 0;
		if(!empty($inputArray['deleteFiles'])) {
			$uploadType = $inputArray['fileType'];
			switch($uploadType) {
				case 'courtesy_tone':
					$folder_path = $this->courtesyTonePath;
					break;
				case 'identification':
					$folder_path = $this->identificationPath;
					break;
				case 'module':
					$folder_path = $this->modulePath;
					break;
				case 'backup':
					$folder_path = $this->backupPath;
					break;
			}

			$returnArray = [];
			foreach($inputArray['deleteFiles'] as $curFile) {
				unlink($folder_path . $curFile);
				if (!file_exists($folder_path . $curFile)) {
					$returnArray[$curFile] = 'success';
				} else {
					$returnArray[$curFile] = 'error';
				}
			}
			return json_encode($returnArray);
		}
	}



}
?>