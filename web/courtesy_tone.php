<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------


if (isset($_POST['action'])){
	if ($_POST['action'] == "select_file") {
		$fileName = $_POST['file'];
		$fileTitle = str_replace('.wav','',$_POST['file']);

		$query = 'UPDATE settings SET value=? WHERE keyID= ?';
        $GLOBALS['app']['db']->executeUpdate($query, [$fileName, 'courtesy']);

		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>Select: '.$fileTitle.'</div>';

		/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
		$memcache_obj = new Memcache;
		$memcache_obj->connect('localhost', 11211);
		$memcache_obj->set('update_settings_flag', 1, false, 0);

	} else if ($_POST['action'] == "upload_file") {
		// This is the handler for file uploads. It uploads the file to a temporary path then
		// convert is to the appropriate WAV formate and puts it in the courtesy tones folder.
		
		$temp_dir = "/var/www/admin/temp_uploads/";
		$final_file_dir = "/var/www/admin/courtesy_tones/";
		$maxFileSize = 45000000; // size in bytes

		$allowedExts = array("wav", "mp3");
		$temp_ext = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp_ext);
		$filename_no_ext = pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME);

		if ((($_FILES["file"]["type"] == "audio/wav") || ($_FILES["file"]["type"] == "audio/mpeg") || ($_FILES["file"]["type"] == "audio/mp3")) && ($_FILES["file"]["size"] < $maxFileSize) && in_array($extension, $allowedExts)) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
			} else {

				$soxInFile = $temp_dir . $_FILES["file"]["name"];
				$soxOutFile = $final_file_dir . $filename_no_ext . ".wav";

				if (file_exists($soxOutFile)) {
					$alert = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>' . $filename_no_ext . ' already exists.</div>';

					echo $filename_no_ext . " already exists. ";
				} else {
					move_uploaded_file($_FILES["file"]["tmp_name"], $temp_dir . $_FILES["file"]["name"]);

					$soxCommand = 'sox "'.$soxInFile.'" -r16000 -b16 -esigned-integer -c1 "'.$soxOutPath.$soxOutFile.'"';
					exec($soxCommand);

					unlink($temp_dir . $_FILES["file"]["name"]);

					$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>You have successfully uploaded '.$_FILES["file"]["name"].' and it has been converted to the proper sound format.</div>';
				}
			}
		} else {
			echo "Invalid file";
		}

	} else if ($_POST['action'] == "rename_file") {
		// NEED TO VALIDATE ACCEPTABLE CHARACTERS

		$path = "courtesy_tones/";
		$oldfile = $path . $_POST["oldfile"] . ".wav"; 
		$newfile = $path . $_POST["newfile"] . ".wav";
		rename($oldfile, $newfile);
		echo "rename: " . $oldfile;
		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>Rename ' . $oldfile . ' to ' . $newfile . '.</div>';
	} else if ($_POST['action'] == "delete_file") {
		// NEED TO BUILD IN CHECK TO SEE IF FILE IS CURRENTLY SELECTED 
		// COURTESY TONE. IF IT IS RETURN AND ERROR.

		$file = $_POST["delfile"].".wav"; 
		$path = "courtesy_tones/";
		unlink($path . $file);
		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>File Deleted: ' . $file . '</div>';
	}
}

?>

<?php
$pageTitle = "Courtesy Tones"; 
include_once("_includes/get_settings.php");
include('_includes/header.php'); 
?>

			<div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a> <span class="divider">/</span></li>
					<li class="active">Courtesy Tones</li>
				</ul>
			</div>

			
			<?php if (isset($alert)) { echo $alert; } ?>


			<div class="row-fluid sortable">

				<div class="box span6">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-music"></i> Current Courtesy Tone</h2>
					</div>
					<div class="box-content">
					<h2><?php echo str_replace('.wav','',$settings['courtesy']);?></h2>
					</div>
				</div><!--/span-->

				
				<div class="box span6">
					<div class="box-header well">
						<h2><i class="icon-arrow-up"></i> Upload New Tone</h2>
					</div>
					<div class="box-content">

					<form action="courtesy_tone.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="action" value="upload_file">
					<input type="file" name="file" id="file" required>
					<input type="submit" name="submit" value="Upload">
					</form>	

					</div>
				</div><!--/span-->
				
			</div><!--/row-->






			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th-list"></i> Courtesy Tone Library</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-condensed bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Name</th>
								  <th>Preview</th>
								  <th>Status</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>


<?php

$url = 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].'/admin/courtesy_tones/';
$files = array();

if ($handle = opendir("./courtesy_tones")) {
	
	while (false !== ($file = readdir($handle))) {
		if ('.' === $file) continue;
		if ('..' === $file) continue;
		$files[] = $file;	
	}
	closedir($handle);

	
	natsort($files);

	$file_counter = 0;

	foreach($files as $file) {	

	$file_counter++;
	$fullurl = $url . $file;

	// Check Status
	$status = "";	
	if ($settings['courtesy'] == $file) {	
		$status = '<span class="label label-success">Active</span>';
	}

	$html_string = '

	<tr>
		<td><h2>' . str_replace('.wav','',$file) . '</h2></td>

		<td class="center">
		<audio controls>
			<source src="'.$fullurl.'" type=audio/mpeg>
			Your browser does not support the audio element.
		</audio>
		</td>

		<td class="center">
			'.$status.'
		</td>

		<td class="center">

			<form action="courtesy_tone.php" method="post" style="position:block;float:left;">
			<input type="hidden" name="action" value="select_file">
			<input type="hidden" name="file" value="'.$file.'">
			<button class="btn btn-success" type="submit"><i class="icon-ok icon-white"></i> Select</button>
			</form>

			<!-- Button triggered modal -->
			<button class="btn" data-toggle="modal" data-target="#renameFile'.$file_counter.'">
				<i class="icon-pencil"></i> 
				Rename
			</button>

			<!-- Button triggered modal -->
			<button class="btn btn-danger" data-toggle="modal" data-target="#deleteFile'.$file_counter.'">
				<i class="icon-trash icon-white"></i> 
				Delete
			</button>


		</td>
	</tr>';


$html_modal .= '


	<!-- Modal - RENAME DIALOG -->
	<form action="courtesy_tone.php" method="post">

	<div class="modal fade" id="renameFile'.$file_counter.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
		<h3 class="modal-title" id="myModalLabel">Rename Courtesy Tone</h3>
	      </div>
	      <div class="modal-body">
		<input type="hidden" name="action" value="rename_file">
		<input class="input disabled" id="disabledInput" type="text" placeholder="' . str_replace('.wav','',$file) . '" disabled="">
		<input type="hidden" name="oldfile" value="' . str_replace('.wav','',$file) . '">
		<span style="margin-right:5px;margin-left:5px;margin-top:-12px;" class="icon32 icon-arrowthick-e"/></span>		
		<input type="text" name="newfile" value="' . str_replace('.wav','',$file) . '">
	      </div>
	      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<button type="submit" class="btn btn-success"><i class="icon-pencil icon-white"></i> Rename</button>

	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	</form>									
	<!-- /.modal -->


	<!-- Modal - DELETE DIALOG -->
	<form action="courtesy_tone.php" method="post">

	<div class="modal fade" id="deleteFile'.$file_counter.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal-title" id="myModalLabel">Delete Courtesy Tone</h3>
	      </div>
	      <div class="modal-body">
		Are you sure that you want to delete the courtesy tone <strong>' . str_replace('.wav','',$file) . '</strong>? This cannot be undo!
		<input type="hidden" name="delfile" value="' . str_replace('.wav','',$file) . '">
		<input type="hidden" name="action" value="delete_file">
	      </div>
	      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Delete</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	</form>
	<!-- /.modal -->';

	echo $html_string;
    }
}
?>
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

			
<?php if (isset($html_modal)) { echo $html_modal; }	?>
    
<?php include('_includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>