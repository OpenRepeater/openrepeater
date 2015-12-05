<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php');
} else { // If they are, show the page.
// --------------------------------------------------------
	
$path = "/var/lib/openrepeater/sounds/";
$temp_dir = "/tmp/";
$sound_dir = '/sounds/';
$dbLoc = '/var/lib/openrepeater/db/openrepeater.db';

/*
FUNCTIONS TO MANAGE AUDIO SOUND FILES FOR OPEN REPEATER STORED IN DIRECTORIES
This will allow retrieving of files as well as uploading, renaming, and deleting
of files. When files are uploaded they are also processed into the correct format.

TYPES: courtesy_tones, identification


audio_current('current_file')
audio_get_files('courtesy_tones')
audio_select('courtesy_tones','File_Name')
audio_upload_files('courtesy_tones', array('file1.ext','file2.ext'))
audio_rename_file('courtesy_tones','old_File_Name','new_File_Label')
audio_delete_files(type,array('file1.ext','file2.ext'))

*/

// -----------------------------------------------------------------------------
// PRETTY UP CURRENT FILENAME
function audio_current($file) {
	$fileLabel = str_replace("_"," ",$file); //replace underscores with spaces for file labels
	$fileLabel = preg_replace('/\\.[^.\\s]{2,5}$/', '', $fileLabel); //remove extention
	return $fileLabel;
}

// -----------------------------------------------------------------------------
// GET AUDIO FILES FROM DIRECTORY BY TYPE
function audio_get_files($type) {
	global $path;
	global $sound_dir;

	$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $sound_dir . $type . '/';


	// Read Files into 1 dimensional array
	if ($handle = opendir($path . $type)) {
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
		$filesArray[] = array('fileName' => $fileName, 'fileLabel' => $fileLabel, 'fileURL' => $fileURL);			
	}

	return $filesArray;

}

// -----------------------------------------------------------------------------
// UPDATE DATABASE WITH SELECTED FILE
function audio_select($type,$filename) {
	global $dbLoc;

	switch ($type) {
	    case 'courtesy_tones':
	        $sql = "UPDATE settings SET value='$filename' WHERE keyID='courtesy'";
	        $typeLabel = "Courtesy Tone";
	        break;
	    case 'id_short':
	        $sql = "UPDATE settings SET value='$filename' WHERE keyID='ID_Short_CustomFile'";
	        $typeLabel = "Short Identification Clip";
	        break;
	    case 'id_long':
	        $sql = "UPDATE settings SET value='$filename' WHERE keyID='ID_Long_CustomFile'";
	        $typeLabel = "Long Identification Clip";
	        break;
	}

	$db = new SQLite3($dbLoc);	
	$query = $db->exec($sql);
	$db->close();

	/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
	$memcache_obj = new Memcache;
	$memcache_obj->connect('localhost', 11211);
	$memcache_obj->set('update_settings_flag', 1, false, 0);
	
	$msgType = 'success';
	$msgText = 'New '.$typeLabel.' Selected: <strong>'.audio_current($filename).'</strong>';
	return array('msgType' => $msgType, 'msgText' => $msgText);
}

// -----------------------------------------------------------------------------
// UPLOAD AUDIO FILES TO DIRECTORY BY TYPE
// This is the handler for file uploads. It uploads the file to a temporary path then
// converts it to the appropriate WAV formate and puts it in the sounds folder by chosen type.


function audio_upload_files($type, $fileNameArray) {
	global $path;
	global $temp_dir;

	$final_file_dir = $path . $type . '/';
	$maxFileSize = 45000000; // size in bytes

	$allowedExts = array('wav','mp3');

	//Loop through each file
	for($i=0; $i<count($fileNameArray['name']); $i++) {
		//Get the temp file path
		$tmpFile1 = $fileNameArray['tmp_name'][$i];
		$tmpFile2 = $temp_dir . str_replace(" ","_",$fileNameArray['name'][$i]);
		
		$temp_ext = explode(".", $fileNameArray['name'][$i]);
		$extension = end($temp_ext);
		$filename_no_ext = pathinfo($fileNameArray['name'][$i], PATHINFO_FILENAME);
		

		if (($fileNameArray['size'][$i] < $maxFileSize) && in_array($extension, $allowedExts)) {
			if ($fileNameArray['error'][$i] > 0) {
				// THERE WAS A PROBLEM
				$msgType = 'error';
				$msgText = '<strong>There was a problem</strong>';

			} else {
				$soxOutFile = $final_file_dir . str_replace(" ","_",$filename_no_ext) . ".wav";
	
				if (file_exists($soxOutFile)) {
					// FILE ALREADY EXISTS
					$msgType = 'error';
					$msgText = 'Sorry but the file <strong>'. audio_current($fileNameArray['name'][$i]).'</strong> already exists.';
				} else {
					if ($tmpFile1 != ""){
						move_uploaded_file($tmpFile1, $tmpFile2);
		
						$soxCommand = 'sox "'.$tmpFile2.'" -r16000 -b16 -esigned-integer -c1 "'.$soxOutFile.'"';
						exec($soxCommand);
		
						unlink($tmpFile2);
						
						// SUCCESSFUL
						$msgType = 'success';
						$msgText = 'Successfully uploaded <strong>' . audio_current($fileNameArray['name'][$i]) . '</strong> and converted into the proper format.';
					}
				}
			}
		} else {
			if($fileNameArray['size'][$i] > $maxFileSize){
				// TOO LARGE
				$msgType = 'error';
				$msgText = 'Sorry, but the file you tried to upload is <strong>too large</strong>.';
			} else if(!in_array($extension, $allowedExts)) {
				// INVALID FORMAT
				$msgType = 'error';
				$msgText = 'Sorry, but the file you tried to upload is not in a supported format. Supported formats are <strong>WAV</strong> and <strong>MP3</strong>.';
			}

		}
	}

	if (!isset($msgText)) {
		$msgType = 'error';
		$msgText = '<strong>NO MESSAGE SET.</strong>';		
	}
	return array('msgType' => $msgType, 'msgText' => $msgText);
}


// -----------------------------------------------------------------------------
// RENAME AUDIO FILES IN DIRECTORY BY TYPE
function audio_rename_file($type, $oldFileName, $newFileLabel) {
	global $path;
	$renamePath = $path . $type. "/";
	
	$oldfile = $renamePath . $oldFileName; 
	$ext = pathinfo($oldfile, PATHINFO_EXTENSION);
		
	$newfile = $renamePath . str_replace(" ","_",$newFileLabel) . "." . $ext;
	rename($oldfile, $newfile);

	$msgType = 'success';
	$msgText = 'Successfully renamed <strong>'.audio_current($oldFileName).'</strong> to <strong>'.$newFileLabel.'</strong>.';
	return array('msgType' => $msgType, 'msgText' => $msgText);
}

// -----------------------------------------------------------------------------
// DELETE AUDIO FILES FROM DIRECTORY BY TYPE
function audio_delete_files($type, $fileNameArray) {
	global $path;
	$deletePath = $path . $type. "/";
	
	if (!is_array($fileNameArray)) { $fileNameArray = array($fileNameArray); } // if string is passed, turn into array

	foreach($fileNameArray as $currentFile) {
		unlink($deletePath . $currentFile);
		
	}
	
	$msgType = 'success';
	$msgText = 'Successfully deleted <strong>'.audio_current($fileNameArray[0]).'</strong>.';
	return array('msgType' => $msgType, 'msgText' => $msgText);
}




// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>