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
		
		if(!empty($filesArray)) {
			$uploadType = $postArray['uploadType'];
			switch($uploadType) {
				case 'courtesy_tone':
					$folder_path = $this->basePath . $this->courtesyTonePath;
					break;
				case 'identification':
					$folder_path = $this->basePath . $this->identificationPath;
					break;
				case 'restore':
					$folder_path = $this->basePath . $this->backupPath;
					break;

				case 'module':
					$folder_path = $this->basePath . $this->modulePath;
					break;
			}

			$returnArray = [];
			foreach($filesArray['file']['name'] as $curKey => $curFile) {
				$newFile = str_replace(' ','_',$curFile);

				$returnArray[$curKey]['fileName'] = $newFile;
				$returnArray[$curKey]['fileLabel'] = str_replace( '_', ' ' , pathinfo($newFile, PATHINFO_FILENAME) );
				$returnArray[$curKey]['fileDate'] = date("Y-m-d\TH:i:s T");
				$returnArray[$curKey]['fileSize'] = $filesArray['file']['size'][$curKey];
				$returnArray[$curKey]['full_path'] = $folder_path . $newFile;		
				$returnArray[$curKey]['tmp_name'] = $filesArray['file']['tmp_name'][$curKey];
		
				// Handle Uploaded File According to Type
				switch($uploadType) {
					case 'courtesy_tone':
					case 'identification':
						move_uploaded_file($returnArray[$curKey]['tmp_name'], $returnArray[$curKey]['full_path']);
	
						// Convert audio format if audio upload type
						$inputFile = $folder_path . $newFile;
						$outputFile = $folder_path . pathinfo($newFile, PATHINFO_FILENAME) . '.wav';
	
						$result = $this->convert_audio($inputFile, $outputFile);
						
						// Update return array with new info.
						if ($result) {
							$returnArray[$curKey]['full_path'] = $outputFile;
							$returnArray[$curKey]['fileName'] = pathinfo($outputFile, PATHINFO_BASENAME);
							$returnArray[$curKey]['fileLabel'] = str_replace( '_', ' ' , pathinfo($outputFile, PATHINFO_FILENAME) );
							$returnArray[$curKey]['fileSize'] = filesize($outputFile);
							$returnArray[$curKey]['downloadURL'] = $this->buildURL( pathinfo($outputFile, PATHINFO_BASENAME), $uploadType );
						}
						return json_encode($returnArray);
						break;
	
					case 'restore':
						$returnArray[$curKey]['downloadURL'] = $this->buildURL($newFile, $uploadType);
						move_uploaded_file($returnArray[$curKey]['tmp_name'], $returnArray[$curKey]['full_path']);
						return json_encode($returnArray);
						break;
	
					case 'module':
// 						move_uploaded_file($returnArray[$curKey]['tmp_name'], $returnArray[$curKey]['full_path']);
$Modules = new Modules();

						$result = $Modules->process_upload_module($returnArray[$curKey]['tmp_name']);
						return json_encode($result);
						break;
				}
			}

		} else {
			return '{"status":"error";"msgText":"no files sent"}';
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
		}

		if ( file_exists($folder_path . $oldFile) && $oldFile != $newFile ) {
			$ext = strtolower( pathinfo($oldFile, PATHINFO_EXTENSION) );
			$newFile = pathinfo($newFile, PATHINFO_FILENAME); // remove extension if passed
			$newFile = str_replace(' ','_',$newFile) . '.' . $ext; // remove spaces and add extension of original file
			rename($folder_path . $oldFile, $folder_path . $newFile);
			if (file_exists($folder_path . $newFile)) {
				$newURL = $this->buildURL($newFile, $fileType);
				return json_encode(['status'=>'success','newURL'=>$newURL]);
			} else {
				return json_encode(['status'=>'error']);
			}
		} else {
			return json_encode(['status'=>'error']);
		}
	}



	###############################################
	# Return URL path
	###############################################

	public function buildURL($fileName, $fileType) {
		// Build base downloaod URL
		$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
		$baseURL = $protocol .'://' . $_SERVER['HTTP_HOST'] . '/';

		switch($fileType) {
			case 'courtesy_tone':
				$baseURL = $baseURL . $this->courtesyTonePath;
				break;
			case 'identification':
				$baseURL = $baseURL . $this->identificationPath;
				break;
			case 'restore':
				$baseURL = $baseURL . $this->backupPath;
				break;
			case 'module':
				$baseURL = $baseURL . $this->modulePath;
				break;
		}
		
		return $baseURL . $fileName;
	}



}
?>