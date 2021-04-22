<?php
#####################################################################################################
# Users Class
#####################################################################################################

class FileSystem {

	private $courtesyTonePath = '/var/www/openrepeater/new_ui/dz/upload/';
	private $identificationPath = '/var/www/openrepeater/new_ui/dz/upload/';
	private $modulePath = '/var/www/openrepeater/new_ui/dz/upload/';
	private $backupPath = '/var/www/openrepeater/backup/';


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
					$folder_path = $this->courtesyTonePath;
					break;
				case 'identification':
					$folder_path = $this->identificationPath;
					break;
				case 'module':
					$folder_path = $this->modulePath;
					break;
				case 'restore':
					$folder_path = $this->backupPath;
					break;
			}
		
			$returnArray = [];
			foreach($filesArray['file']['name'] as $curKey => $curFile) {
				$returnArray[$curKey]['filename'] = $curFile;
				$returnArray[$curKey]['full_path'] = $folder_path . $curFile;		
				$returnArray[$curKey]['tmp_name'] = $filesArray['file']['tmp_name'][$curKey];
				$returnArray[$curKey]['size'] = $filesArray['file']['size'][$curKey];
				$returnArray[$curKey]['datetime'] = date('YmdHis');
		
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
			}
			return json_encode($returnArray);

		} else {
			return 'no files sent';
		}


// 		return $this->endUserSession();
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