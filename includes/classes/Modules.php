<?php
#####################################################################################################
# Modules Framework Class
#####################################################################################################

class Modules {

    public $Database;
	public $modules_path;
	public $modulesUploadTempDir;
	private $includes_path;
	private $core_modules = ['Help','Parrot','EchoLink'];
	
	// SVXLink Locations
	private $svxlink_events_d_path = '/usr/share/svxlink/events.d/';
	private $svxlink_modules_d_path = '/usr/share/svxlink/modules.d/';
	private $svxlink_sounds = '/usr/share/svxlink/sounds/en_US/'; // Need to read config for language folder.


	public function __construct() {
		$this->Database = new Database();
		$this->modules_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/modules/';
		$this->includes_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/';
		$this->modulesUploadTempDir = $this->modules_path . 'tempModuleDir/';
	}


	###############################################
	# Get Modules from DB
	###############################################

	public function get_modules() {
		# REWRITE PENDING
		# Currently Redirects to Database Class
		# Plan to pull logic form those classes once everything points here
		return $this->Database->get_modules();
	}



	###############################################
	# Get Settings for Selected Module
	###############################################

	public function get_module_settings($id) {
		$sql = 'SELECT moduleOptions FROM "modules" WHERE "moduleKey" = ' . $id;
		$module = $this->Database->select_single($sql);
		$moduleOptionsArray = unserialize($module['moduleOptions']);
		return $moduleOptionsArray;	
	}



	###############################################
	# Submit Settings for Selected Module
	###############################################

	public function save_module_settings($settings_array, $type = 'normal') {
		$moduleID = $settings_array['moduleKey'];
		unset($settings_array['moduleKey']); // Remove id after extracted above
		if (isset($settings_array['updateModuleSettings'])) {
			unset($settings_array['updateModuleSettings']); // Remove Action			
		}

		##########
		if ($type == 'normal') {
			$modules = $this->get_modules();

			// Check for custom form processor, if one exists...then insert inline with IN and OUT arrays for external processing
			$mod_custom_submit_file = $this->modules_path . $modules[$moduleID]['svxlinkName'] . '/custom_submit.php';
			if (file_exists($mod_custom_submit_file)) { 
				$inputArray = $settings_array;
				include($mod_custom_submit_file);
				$settings_array = $outputArray;
			}
		}
		##########

		$serializedSettings = serialize($settings_array);
		$sql = "UPDATE modules SET moduleOptions = '$serializedSettings' WHERE moduleKey = '$moduleID';";
		$insert_result = $this->Database->insert($sql);

		if ($insert_result) { 
			$this->Database->set_update_flag(true);
			return array(
				'msgType' => 'success',
				'msgText' => 'Successfully saved module settings.'
			);
		} else { 
			return array(
				'msgType' => 'error',
				'msgText' => 'There was a problem saving the module settings.'
			);
		}

	}



	###############################################
	# Activate Module
	###############################################

	public function activateMod($id) {
		$sql = 'SELECT * FROM "modules" WHERE "moduleKey" = "'.$id.'";';
		$select_result = $this->Database->select_key_value($sql, 'moduleKey', 'svxlinkName');
		$svxlink_name = $select_result[$id];

		# REWRITE PENDING
		# Currently Redirects to Database Class
		# Plan to pull logic form those classes once everything points here
		$this->Database->active_module($id);

		$this->initialize_module($svxlink_name);		
		$this->Database->set_update_flag(true);

		return array(
			'msgType' => 'success',
			'msgText' => 'The module has been successfully <strong>activated</strong>.'
		);
	}



	###############################################
	# Deactivate Module
	###############################################

	public function deactivateMod($id) {
		# REWRITE PENDING
		# Currently Redirects to Database Class
		# Plan to pull logic form those classes once everything points here
		$this->Database->deactive_module($id);
		$this->Database->set_update_flag(true);

		return array(
			'msgType' => 'success',
			'msgText' => 'The module has been successfully <strong>deactivated</strong>.'
		);
	}



	###############################################
	# Upload Module
	###############################################

	public function upload_module($fileNameArray) {

		$maxFileSize = 500000000; // size in bytes
		$allowedExts = array('zip');
	
		//Loop through each file
		for($i=0; $i<count($fileNameArray['name']); $i++) {
			//Get the temp file path
			$tmpFile1 = $fileNameArray['tmp_name'][$i];
			
			$temp_ext = explode(".", $fileNameArray['name'][$i]);
			$extension = end($temp_ext);

			$uploadedModuleZip = $this->modules_path . 'tempModule.' . $extension;
			
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
					'msgText' => 'Sorry, but the file you tried to upload is not in a supported format. Files must modules packaged up with a .zip extension.'
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


			if ($tmpFile1 != ""){
				if(file_exists($uploadedModuleZip)) {
				    unlink($uploadedModuleZip); //remove orphan
				}
				move_uploaded_file($tmpFile1, $uploadedModuleZip);

				if (file_exists($uploadedModuleZip)) {
					### SUCCESSFUL UPLOAD ###
					$unzip = $this->unzip_module($uploadedModuleZip);
					if ($unzip == true) {
						### SUCCESSFUL UNZIP ###
						$install_results = $this->install_module($this->modulesUploadTempDir);
						if ( is_array($install_results) ) {
							if (isset($install_results['Module_Info']['display_name'])) {
								$currDisplayName = trim($install_results['Module_Info']['display_name']);
								return array(
									'msgType' => 'success',
									'msgText' => 'The ' . $currDisplayName . ' module has been successfully installed. To use it, you must first activate it.'
								);	
							} else {
								return array(
									'msgType' => 'success',
									'msgText' => 'Module has been successfully installed. To use it, you must first activate it.'
								);	
							}

						} else {
							return array(
								'msgType' => 'error',
								'msgText' => 'There was a problem installing the module. Either this is not an OpenRepeater module, or the zip file was improperly constructed, or the module already exists.'
							);	
							
						}

							
					} else {
						### FAILED UNZIP ###
						return array(
							'msgType' => 'error',
							'msgText' => 'There was a problem Unzipping the file'
						);	
					}
										
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
	# Archive Functions
	###############################################

	public function unzip_module($selected_archive) {
		try	{
			if (!file_exists($this->modulesUploadTempDir)) {
			    mkdir($this->modulesUploadTempDir, 0777, true);
			}

			// Clean up any Mac OS trash in user zip file if it exists
			exec('zip -d '.$selected_archive.' "__MACOSX*"');

			$zip = new ZipArchive;
			$res = $zip->open($selected_archive);
			if ($res === TRUE) {
				$zip->extractTo($this->modulesUploadTempDir);
				$zip->close();
				unlink($selected_archive);

				// If zip contains parent folder, move child files and remove folder
				$folder_list  = glob($this->modulesUploadTempDir."*", GLOB_ONLYDIR);
				$file_count  = count( glob($this->modulesUploadTempDir."*") ) - count($folder_list);
				if (count($folder_list) == 1 && $file_count == 0) {
					$get_sub_dir = glob($this->modulesUploadTempDir."*", GLOB_ONLYDIR);
					$get_sub_dir = $get_sub_dir[0];
					exec('mv ' . $get_sub_dir . '/* ' . $this->modulesUploadTempDir);
					exec('rm ' . $get_sub_dir . ' -R');
				}

				return true;

			} else {
				return false;
			}

		} catch (Exception $e) {
		    echo "Exception : " . $e;
		}
	}


	
	###############################################
	# Install Module
	###############################################

	public function initialize_module($svxlink_name, $enabled = 0) {
		$svxlink_name = trim($svxlink_name);

		// Preceed only if module is not listed as a core module
		if ( !in_array($svxlink_name, $this->core_modules) ) {

			### Check for SVXLink Components and link into place ###
	
			// events.d directory
			$mod_events_d_path = $this->modules_path . $svxlink_name . '/svxlink/events.d/';
			if (file_exists($mod_events_d_path)) {
				$fileArray = $this->read_dir($mod_events_d_path);
				
				// Create link for each file
				// Files stay in ORP module folder and a link is created in SVXLink folder
				foreach($fileArray as $file) {
					$currTarget = $file['filePath'];
					$currLink = $this->svxlink_events_d_path . $file['fileName'];
					if (file_exists($currLink)) { unlink($currLink); } // Clean old link/file
					symlink($currTarget, $currLink); // Set New Link
				}
			}
	
			// modules.d directory
			$mod_modules_d_path = $this->modules_path . $svxlink_name . '/svxlink/modules.d/';
			if (file_exists($mod_modules_d_path)) {
				$fileArray = $this->read_dir($mod_modules_d_path);
				
				// Create link for each file
				// Files stay in ORP module folder and a link is created in SVXLink folder
				foreach($fileArray as $file) {
					$currTarget = $file['filePath'];
					$currLink = $this->svxlink_modules_d_path . $file['fileName'];
					if (file_exists($currLink)) { unlink($currLink); } // Clean old link/file
					symlink($currTarget, $currLink); // Set New Link
				}
			}
	
	
			### Check for SVXLink Sounds and link into place ###
	
			$svxlink_sounds_path = $this->svxlink_sounds . $svxlink_name;
			$mod_sounds_path = $this->modules_path . $svxlink_name . '/svxlink/sounds/en_US/';
			if (file_exists($mod_sounds_path)) {
				if (file_exists($svxlink_sounds_path)) { exec('rm ' . $svxlink_sounds_path . ' -R'); }
				symlink($mod_sounds_path, $svxlink_sounds_path); // Set New Link
			}

		}


		### Created DB record in modules table, if it doesn't exist, and set as deactive ###

		if ($this->Database->exists('modules','svxlinkName',$svxlink_name) == false) { 
			$sql = 'INSERT INTO "modules" ("moduleKey","moduleEnabled","svxlinkName","svxlinkID") VALUES (NULL,'.$enabled.',\''.$svxlink_name.'\',\''.$this->find_next_svxlink_id().'\')';
			$insert_result = $this->Database->insert($sql);
		}

	
		### If no options are set in DB, check for default settings file and load if one exists ###

		$sql = 'SELECT * FROM "modules" WHERE "svxlinkName" = \''.$svxlink_name.'\';';
		$module = $this->Database->select_single($sql);
		$module_key = $module['moduleKey'];
		$module_options = $module['moduleOptions'];

		if ($module_options == NULL || $module_options == '') {
			$default_settings_file = $this->modules_path . $svxlink_name . '/default_settings.php';
			if (file_exists($default_settings_file)) {
				include($default_settings_file);
				$default_settings['moduleKey'] = $module_key;
				$this->save_module_settings($default_settings, 'install');
			}
			
		}

	}


	public function install_module($tempModulePath) {
		$error_level = 0;

		# Verify minimum files required exist
		$ini_path = $tempModulePath . 'info.ini';
		$build_config_path = $tempModulePath . 'build_config.php';
		$events_d_path = $tempModulePath . 'svxlink/events.d/';
		$modules_d_path = $tempModulePath . 'svxlink/modules.d/';

		if (!file_exists($ini_path)) { $error_level++; }
		if (!file_exists($build_config_path)) { $error_level++; }
		if (!file_exists($events_d_path)) { $error_level++; }
		if (!file_exists($modules_d_path)) { $error_level++; }

		if ($error_level > 0) {
			exec('rm ' . $tempModulePath. ' -R');
			return false;
		}

		# Read INI and get SVXLink Name
		if (file_exists($ini_path)) {
			$mod_ini_array = parse_ini_file($ini_path, true);
		} else {
		    $error_level++;
		}

		if ( isset($mod_ini_array['Module_Info']['mod_name']) ) {
			$svxlink_name = trim($mod_ini_array['Module_Info']['mod_name']);
			$new_module_path = $this->modules_path . $svxlink_name;
		} else {
			exec('rm ' . $tempModulePath. ' -R');
			return false;			
		}
		
		# Check module doesn't already exist
		if (file_exists($new_module_path)) { $error_level++; }

		if ($error_level > 0) {
			exec('rm ' . $tempModulePath. ' -R');
			return false;
		}
		
		# Move module into place
		rename($tempModulePath, $new_module_path);

		if (!file_exists($new_module_path)) { $error_level++; }

		if ($error_level > 0) {
			exec('rm ' . $tempModulePath. ' -R');
			return false;
		}
		
		# Initiate Install and set module as inactive
		$this->initialize_module($svxlink_name, 0);

		# Final Cleanup
		if (file_exists($tempModulePath)) { exec('rm ' . $tempModulePath. ' -R'); }
		
		return $mod_ini_array;

	}



	###############################################
	# Remove Module
	###############################################

	public function remove_module($svxlink_name) {
		$svxlink_name = trim($svxlink_name);

		### Prevent Removal of Core Modules ###
		if ( in_array($svxlink_name, $this->core_modules) ) {
			exit("This is a core module and cannot be removed!");
		}

		### Check for SVXLink Components and remove ###

		// events.d directory
		$mod_events_d_path = $this->modules_path . $svxlink_name . '/svxlink/events.d/';
		if (file_exists($mod_events_d_path)) {
			$fileArray = $this->read_dir($mod_events_d_path);
			
			// Remove link for each file
			foreach($fileArray as $file) {
				$currLink = $this->svxlink_events_d_path . $file['fileName'];
				if (file_exists($currLink)) { unlink($currLink); }
			}
		}

		// modules.d directory
		$mod_modules_d_path = $this->modules_path . $svxlink_name . '/svxlink/modules.d/';
		if (file_exists($mod_modules_d_path)) {
			$fileArray = $this->read_dir($mod_modules_d_path);
			
			// Remove link for each file
			foreach($fileArray as $file) {
				$currLink = $this->svxlink_modules_d_path . $file['fileName'];
				if (file_exists($currLink)) { unlink($currLink); }
			}
		}

		### Check for SVXLink Sounds and remove ###

		$svxlink_sounds_path = $this->svxlink_sounds . $svxlink_name;
		$mod_sounds_path = $this->modules_path . $svxlink_name . '/svxlink/sounds/en_US/';
		if (file_exists($mod_sounds_path)) {
			if (file_exists($svxlink_sounds_path)) { exec('rm ' . $svxlink_sounds_path . ' -R'); }
		}

		### Remove module dir ###
		
		exec('rm ' . $this->modules_path . $svxlink_name . ' -R');
		if ( !file_exists( $this->modules_path . $svxlink_name ) ) {

		### Remove GPIO pins if module uses them ###
		$this->delete_gpios($svxlink_name);

		### Remove DB record in modules table ###

		$sql = 'DELETE FROM "modules" WHERE svxlinkName = "' . $svxlink_name . '";';
		$delete_result = $this->Database->delete_row($sql);
			if ($delete_result) { 
				$this->Database->set_update_flag(true);
				return array(
					'msgType' => 'success',
					'msgText' => 'Successfully deleted the module.'
				);
			} else {
				return array(
					'msgType' => 'error',
					'msgText' => 'There was a problem fully deleting the module.'
				);
			}
		} else {
			return array(
				'msgType' => 'error',
				'msgText' => 'There was a problem deleting the module.'
			);
		}
		
	}



	###############################################
	# Display All Modules
	###############################################

	public function display_all() {
		$modules = $this->get_modules();

		$return_html = '
		<table class="table table-striped">
			<thead>
				<tr>
					<th><div style="width:200px">Module</div></th>
					<th>Description</th>
				</tr>
			</thead>
			
			<tbody>
			';

		foreach($modules as $cur_mod) { 
			$mod_ini_file = $this->modules_path.$cur_mod['svxlinkName'].'/info.ini';
			$mod_settings_file = $this->modules_path.$cur_mod['svxlinkName'].'/settings.php';
			$dtmf_help_file = $this->modules_path.$cur_mod['svxlinkName'].'/dtmf.php';

			$curr_mod_ini = $this->read_ini($cur_mod['svxlinkName']);

			if (isset($curr_mod_ini['Module_Info']['display_name'])) {
				$currDisplayName = $curr_mod_ini['Module_Info']['display_name'];
			} else {
			    $currDisplayName = $cur_mod['svxlinkName'];
			}

			$return_html .= '
			<tr>
				<td>
					<div><h3>' . $currDisplayName . ' (' . $cur_mod['svxlinkID'] .'#)</h3></div>
 					<div>';
 			
 			if ($cur_mod['moduleEnabled']==1) { 
	 			$return_html .= '<span class="label-success label label-default">Active</span>';
	 		} else {
		 		$return_html .= '<span class="label-default label">Inactive</span>';
		 	}
			
			$return_html .= '</div><div>';
			
			// Activate / Deactiveate Link
			if ($cur_mod['moduleEnabled']==1) {
				$return_html .= '<a href="?deactivate='.$cur_mod['moduleKey'].'">Deactivate</a>';
			} else {
				$return_html .= '<a href="?activate='.$cur_mod['moduleKey'].'">Activate</a>';													
			}

			// Settings Link...if Applicable
			if ($cur_mod['moduleEnabled']==1 && file_exists($mod_settings_file)) {
				$return_html .= ' | <a href="modules.php?settings='.$cur_mod['moduleKey'].'">Settings</a>';
			}

			// Delete Link...if not core module			
			if ( $cur_mod['moduleEnabled']==0 && !in_array($cur_mod['svxlinkName'], $this->core_modules) ) {
				$return_html .= ' | <a href="#" data-toggle="modal" data-target="#deleteModule" onclick="deleteModule(\'' . $cur_mod['svxlinkName'] . '\',\'' . $currDisplayName . '\'); return false;">Delete</a>';
			}

			// DTMF Link...if Applicable
			if ($cur_mod['moduleEnabled']==1 && file_exists($dtmf_help_file)) {
				$return_html .= ' | <a href="dtmf.php#'.$cur_mod['svxlinkName'].'">DTMF</a>';
			}

			$return_html .= '</div></td><td>';
			
			if (isset($curr_mod_ini['Module_Info']['mod_desc'])) {
				$return_html .= $curr_mod_ini['Module_Info']['mod_desc'];
			} else {
			    $return_html .= "<em>(No Description)</em>";
			}

			// Version / Author Info
			if ( isset($curr_mod_ini['Module_Info']['version']) || isset($curr_mod_ini['Module_Info']['authors']) ) { $return_html .= '<br><br>'; }
			if (isset($curr_mod_ini['Module_Info']['version'])) { $return_html .= 'Version: ' . $curr_mod_ini['Module_Info']['version'] . '&nbsp;&nbsp;&nbsp;'; }
			if (isset($curr_mod_ini['Module_Info']['version'])) { $return_html .= 'Authors: ' . $curr_mod_ini['Module_Info']['authors']; }


			$return_html .= '</td></tr>';

		} /* End Current Module */

		$return_html .= '</tbody></table>';
		
		return $return_html;	
	}



	###############################################
	# Display Module Settings Page
	###############################################

	public function display_settings($id) {
		$modules = $this->get_modules();

		// If modules settings page is request, display that if it exist
		$mod_settings_file = $this->modules_path . $modules[$id]['svxlinkName'] . '/settings.php';
		if (file_exists($mod_settings_file)) {
			$mod_ini = $this->read_ini($modules[$id]['svxlinkName']);

			if (isset($mod_ini['Module_Info']['display_name'])) {
				$displayName = $mod_ini['Module_Info']['display_name'];
			} else {
			    $displayName = $modules[$id]['svxlinkName'];
			}

			// Modules Includes: CSS & JS (if they exist)
			$mod_css_file = $this->modules_path . $modules[$id]['svxlinkName'] . '/module.css';
			$mod_js_file = $this->modules_path . $modules[$id]['svxlinkName'] . '/module.js';
			if (file_exists($mod_css_file)) { $moduleCSS = '/modules/' . $modules[$id]['svxlinkName'] . '/module.css'; }
			if (file_exists($mod_js_file)) { $moduleJS = '/modules/' . $modules[$id]['svxlinkName'] . '/module.js'; }

			$moduleSettings = $this->get_module_settings($id);

			// Construct Page Title
			$pageTitle = $displayName . " Module Settings";

			// Built Top of Form
			$form_top = '
			<form class="form-inline" role="form" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" id="moduleSettingsUpdate">

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2>' . $displayName . ' Module Settings</h2>
					</div>
					<div class="box-content">
			';

			// Built Bottom of Form
			$form_bottom = '
						<div class="form-actions">
						  <!-- PASS MODULE KEY FOR UPDATE DATABASE AND REDIRECT BACK TO SETTINGS PAGE -->
						  <input type="hidden" name="moduleKey" value="' . $id . '">
						  <input type="hidden" name="updateModuleSettings">		
						  <input type="submit">
						</div>
				
					</div>
				</div><!--/span-->
			</div><!--/row-->
			</form>			
			';

			// ***************************************************************** //
			// Construct Page Content
			ob_start();
			include($this->includes_path . 'header.php');
			echo $form_top;
			include($mod_settings_file);
			echo $form_bottom;
			include($this->includes_path . 'footer.php');
			$moduleHTML = ob_get_clean();
			// ***************************************************************** //

			return $moduleHTML;

		} else {
			// Construct Page Title
			$pageTitle = "Modules";

			// Construct Page Content
			ob_start(); include($this->includes_path . 'header.php'); $moduleHTML = ob_get_clean();
		    $moduleHTML .= "<h2>No Settings Page found.</h2>";
			ob_start(); include($this->includes_path . 'footer.php'); $moduleHTML .= ob_get_clean();

			return $moduleHTML;
		}

	}



	###############################################
	# Display Nav Settings Links
	###############################################

	public function nav_setting_links() {

		$modules = $this->get_modules();
		
		$modulesActive = array();
		foreach($modules as $cur_mod) { 
			if ($cur_mod['moduleEnabled']==1) {
				$curr_mod_ini = $this->read_ini($cur_mod['svxlinkName']);
				if (isset($curr_mod_ini['Module_Info']['display_name'])) {
					$currDisplayName = $curr_mod_ini['Module_Info']['display_name'];
				} else {
				    $currDisplayName = $cur_mod['svxlinkName'];
				}

				$module_settings_file = $this->modules_path . $cur_mod['svxlinkName'] . '/settings.php';
				if (file_exists($module_settings_file)) {
					$modulesActive[$cur_mod['moduleKey']] = $currDisplayName;
				}
			} 
		}

		if (!empty($modulesActive)) {
			// Render Parent and Child menus
			$return_html = '<li><a class="ajax-link" href="modules.php"><i class="icon-align-justify"></i><span class="hidden-tablet"> Modules</span></a>';
			$return_html .= ' <ul class="nav nav-pills nav-stacked">';
			foreach ($modulesActive as $mod_id => $mod_name) {
				$return_html .= '<li><a href="modules.php?settings='.$mod_id.'">&nbsp;&nbsp;&nbsp;<i class="icon-chevron-right"></i><span class="hidden-tablet"> '.$mod_name.'</span></a></li>';
			}
			$return_html .= '  </ul>';
			$return_html .= '</li>';
		} else {
			// Render Parent menu only
			$return_html = '<li><a class="ajax-link" href="modules.php"><i class="icon-align-justify"></i><span class="hidden-tablet"> Modules</span></a></li>';
		}
		
		echo $return_html;
	}



	###############################################
	# Display DTMF Codes
	###############################################

	public function display_dtmf_codes() {
		$modules = $this->get_modules();
		$return_html = '';
		if ($modules) {
			foreach($modules as $cur_mod_loop) {
				$currDisplayName = '';
				if ($cur_mod_loop['moduleEnabled']==1) { 
					$curr_mod_ini = $this->read_ini($cur_mod_loop['svxlinkName']);

					if (isset($curr_mod_ini['Module_Info']['display_name'])) {
						$currDisplayName = $curr_mod_ini['Module_Info']['display_name'];
					} else {
					    $currDisplayName = $cur_mod_loop['svxlinkName'];
					}

					$return_html .= '<a name="' . $cur_mod_loop['svxlinkName'] . '"></a>';			
					$return_html .= '<legend>' . $cur_mod_loop['svxlinkID'] . '# - ' . $currDisplayName . ' Module</legend>
					<p>Pressing ' . $cur_mod_loop['svxlinkID'] . '# will enable the ' . $currDisplayName . ' module.</p>';
			
					$dtmf_help_file = $this->modules_path . $cur_mod_loop['svxlinkName'] . '/dtmf.php';
					include($dtmf_help_file);
					if ($cur_mod_loop['moduleEnabled']==1 && file_exists($dtmf_help_file)) {
						$return_html .= '<h4>Sub Commands:</h4>
						<pre>'.$sub_subcommands.'</pre>
						<br>';
					}
				}
			} /* End Current Module */
		}
		
		return $return_html;
	}



	###############################################
	# Read Module INI
	###############################################

	public function read_ini($svxlink_name) {
		$ini_path = $this->modules_path . $svxlink_name . '/info.ini';
		
		if (file_exists($ini_path)) {
			$mod_ini_array = parse_ini_file($ini_path, true);
			return $mod_ini_array;
		} else {
		    return false;
		}

	}



	###############################################
	# Get Next SVXLink ID Number
	###############################################

	public function find_next_svxlink_id() {
		$maxID = 99;

		$sql = 'SELECT * FROM "modules";';
		$existingIDs = $this->Database->select_key_value($sql, 'svxlinkName', 'svxlinkID');

		$x = 1; 
		while( !isset($nextNumber) && $maxID >= $x ) {
		    if (!in_array($x,$existingIDs)) {
			    $nextNumber = $x;
				return $nextNumber;
		    }
		    $x++;
		}
		
	}



	###############################################
	# Update Module GPIO Pins
	###############################################

	private function update_gpios($gpio_type, $gpio_array) {
		// Purge all GPIO Pins for this TYPE
		$sql = 'DELETE FROM "gpio_pins" WHERE type = "'.$gpio_type.'";';
		$delete_result = $this->Database->delete_row($sql);

		// Loop through provided array and set new GPIO Pins
		foreach($gpio_array as $curr_GPIO) {	
			$sql = 'INSERT INTO "gpio_pins" ("gpio_num","direction","active","description","type") VALUES ("'.$curr_GPIO['gpio_num'].'","'.$curr_GPIO['direction'].'","'.$curr_GPIO['active'].'","'.$curr_GPIO['description'].'","'.$gpio_type.'");';;
			$insert_result = $this->Database->insert($sql);
		}
	}


	private function delete_gpios($gpio_type) {
		// Purge all GPIO Pins for this TYPE
		$sql = 'DELETE FROM "gpio_pins" WHERE type = "'.$gpio_type.'";';
		$delete_result = $this->Database->delete_row($sql);
	}



	###############################################
	# Read Directory and Generate Paths to Files
	###############################################

	private function read_dir($path) {
	
		// Read Files into 1 dimensional array
		if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
				if ('.' === $file) continue;
				if ('..' === $file) continue;
				$fileList[] = $file;
			}
			closedir($handle);	
		}
	
		// Build array with full file paths
		foreach($fileList as $fileName) {	
			$filePath = $path . $fileName;
			$filesArray[] = array('fileName' => $fileName, 'filePath' => $filePath);	
		}

		return $filesArray;
	}



	###############################################
	# Non-Core Modules
	###############################################

	public function get_non_core_modules_path() {	
		$mod_folder_list  = glob($this->modules_path."*", GLOB_ONLYDIR);
		foreach($mod_folder_list as $currPath) {
			if (!in_array(basename($currPath), $this->core_modules)) {
				$addon_modules[basename($currPath)] = $currPath;
			}
		}
		
		if(isset($addon_modules)) {
			return $addon_modules;		
		} else {
			return NULL;
		}
	}


}