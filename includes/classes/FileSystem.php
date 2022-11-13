<?php
#####################################################################################################
# Users Class
#####################################################################################################

class FileSystem {

	private $basePath = "/var/www/openrepeater/";

	private $courtesyTonePath = 'sounds/courtesy_tones/';
	private $identificationPath = 'sounds/identification/';
	private $modulePath = 'modules/';
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
				$newFile = str_replace(' ','_',$curFile);

				$returnArray[$curKey]['fileName'] = $newFile;
				$returnArray[$curKey]['fileLabel'] = str_replace( '_', ' ' , pathinfo($newFile, PATHINFO_FILENAME) );
				$returnArray[$curKey]['fileDate'] = date("Y-m-d\TH:i:s T");
				$returnArray[$curKey]['fileSize'] = $filesArray['file']['size'][$curKey];
				if ($uploadType != 'module') {
					$returnArray[$curKey]['downloadURL'] = $baseURL . $newFile;
				}
				$returnArray[$curKey]['full_path'] = $folder_path . $newFile;		
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
				if ($convertAudio) { 
					$inputFile = $folder_path . $newFile;
					$outputFile = $folder_path . pathinfo($newFile, PATHINFO_FILENAME) . '.wav';

					$result = $this->convert_audio($inputFile, $outputFile);
					
					// Update return array with new info.
					if ($convertAudio) {
						$returnArray[$curKey]['full_path'] = $outputFile;
						$returnArray[$curKey]['fileName'] = pathinfo($outputFile, PATHINFO_BASENAME);
						$returnArray[$curKey]['fileLabel'] = str_replace( '_', ' ' , pathinfo($outputFile, PATHINFO_FILENAME) );
						$returnArray[$curKey]['fileSize'] = filesize($outputFile);
						$returnArray[$curKey]['downloadURL'] = $baseURL . pathinfo($outputFile, PATHINFO_BASENAME);
					}
				}

			}
			return json_encode($returnArray);
			

		} else {
			return 'no files sent';
		}

	}



	###############################################
	# Convert Audio File
	###############################################

	private function convert_audio($source, $destination) {
		$tmpSource = '/tmp/' . pathinfo($source, PATHINFO_BASENAME);
		rename($source, $tmpSource); // move source file to tmp
		$soxCommand = 'sox "' . $tmpSource . '" -r16000 -b16 -esigned-integer -c1 "' . $destination . '"';
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
			$fileType = $inputArray['fileType'];
			switch($fileType) {
				case 'courtesy_tone':
					$folder_path = $this->basePath . $this->courtesyTonePath;
					break;
				case 'identification':
					$folder_path = $this->basePath . $this->identificationPath;
					break;
				case 'backup':
					$folder_path = $this->basePath . $this->backupPath;
					break;
				// Note: Modules not included as they must be uninstalled. See Module class.
			}

			$returnArray = [];
			foreach($inputArray['deleteFiles'] as $curFile) {
				if (file_exists($folder_path . $curFile)) {
					unlink($folder_path . $curFile);
					if (!file_exists($folder_path . $curFile)) {
						$returnArray[$curFile] = 'success';
					} else {
						$returnArray[$curFile] = 'error';
					}
				} else {
					$returnArray[$curFile] = 'error';
				}
			}
			return json_encode($returnArray);
		} else {
			return json_encode(['status'=>'empty']);
		}
	}



	###############################################
	# Rename Files
	###############################################

	public function renameFile($inputArray) {
		$oldFile = $inputArray['renameFile'];
		$newFile = $inputArray['newName'];
		$fileType = $inputArray['fileType'];

		// Build base downloaod URL
		$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
		$baseURL = $protocol .'://' . $_SERVER['HTTP_HOST'] . '/';

		switch($fileType) {
			case 'courtesy_tone':
				$folder_path = $this->basePath . $this->courtesyTonePath;
				$baseURL = $baseURL . $this->courtesyTonePath;
				break;
			case 'identification':
				$folder_path = $this->basePath . $this->identificationPath;
				$baseURL = $baseURL . $this->identificationPath;
				break;
			case 'backup':
				$folder_path = $this->basePath . $this->backupPath;
				$baseURL = $baseURL . $this->backupPath;
				break;
		}

		if ( file_exists($folder_path . $oldFile) && $oldFile != $newFile ) {
			$ext = strtolower( pathinfo($oldFile, PATHINFO_EXTENSION) );
			$newFile = pathinfo($newFile, PATHINFO_FILENAME); // remove extension if passed
			$newFile = str_replace(' ','_',$newFile) . '.' . $ext; // remove spaces and add extension of original file
			rename($folder_path . $oldFile, $folder_path . $newFile);
			if (file_exists($folder_path . $newFile)) {
				$newURL = $baseURL . $newFile;
				return json_encode(['status'=>'success','newURL'=>$newURL]);
			} else {
				return json_encode(['status'=>'error']);
			}
		} else {
			return json_encode(['status'=>'error']);
		}
	}


}
?>