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
		$moduleOptionsArray = json_decode($module['moduleOptions']);
		return $moduleOptionsArray;	
	}



	###############################################
	# Write Module Record
	###############################################

	// Accepts multiple rows and/or columns
	public function write_modules($moduleArray = []) {

		// Remove moduleKey child element if sent. No need to rewrite that to DB.
		foreach(array_keys($moduleArray) as $key) { unset($moduleArray[$key]['moduleKey']); }

		// Build SQL Update Strings
		foreach($moduleArray as $currModuleKey => $currModuleValue) {
			$colKeyValArray = [];
			foreach($currModuleValue as $colKey => $colValue) { $colKeyValArray[] = $colKey . " = '" . $colValue . "'"; }
			$colKeyValPairs  = implode(", ", $colKeyValArray);
			$sql = "UPDATE modules SET $colKeyValPairs WHERE moduleKey = $currModuleKey;";
			$update_result = $this->Database->update($sql);
			if ($update_result == false) { return false; } // Break if failure with individual row update
		}
		return true; // true on last row update.
	}



	###############################################
	# Get Module SVXLink Name by ID
	###############################################

	public function get_module_svxlink_name($id) {
		$sql = 'SELECT * FROM "modules" WHERE "moduleKey" = "'.$id.'";';
		$select_result = $this->Database->select_key_value($sql, 'moduleKey', 'svxlinkName');
		$svxlink_name = $select_result[$id];
		return $svxlink_name;	
	}



	###############################################
	# Get Module SVXLink ID by ID (Key)
	###############################################

	public function get_module_svxlink_id($id) {
		$sql = 'SELECT * FROM "modules" WHERE "moduleKey" = "'.$id.'";';
		$select_result = $this->Database->select_key_value($sql, 'moduleKey', 'svxlinkID');
		$svxlinkID = $select_result[$id];
		return $svxlinkID;	
	}



	###############################################
	# Get Module ID by SVXLink Name
	###############################################

	public function get_module_id($svxlink_name) {
		$sql = 'SELECT * FROM "modules" WHERE "svxlinkName" = "'.$svxlink_name.'";';
		$select_result = $this->Database->select_key_value($sql, 'svxlinkName', 'moduleKey');
		$id = $select_result[$svxlink_name];
		return $id;	
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

		$jsonSettings = json_encode($settings_array);
		$sql = "UPDATE modules SET moduleOptions = '$jsonSettings' WHERE moduleKey = '$moduleID';";
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
		$svxlink_name = $this->get_module_svxlink_name($id);
		# REWRITE PENDING
		# Currently Redirects to Database Class
		# Plan to pull logic form those classes once everything points here
		$this->Database->active_module($id);

		$this->initialize_module($svxlink_name);		
		$this->Database->set_update_flag(true);

		// FUTURE: More error checking
		return true;
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

		// FUTURE: More error checking
		return true;
	}



	###############################################
	# Process Upload Module
	###############################################

	public function process_upload_module($tmpFilePath) {

		$extension = pathinfo($tmpFilePath, PATHINFO_EXTENSION);
		$uploadedModuleZip = $this->modules_path . 'tempModule.' . $extension;
		
		# Check to see if system temp folder is writable
		if (!is_writable( sys_get_temp_dir() )) {
			$statusArray = ['status' => 'error', 'msgText' => _('Sorry, it looks like there is a configuration issue. The system\'s temp folder is not writable')];
			return $statusArray;
		}

		if ($tmpFilePath != ""){
			if(file_exists($uploadedModuleZip)) {
				unlink($uploadedModuleZip); //remove orphan
			}
			rename($tmpFilePath, $uploadedModuleZip);
// 			move_uploaded_file($returnArray[$curKey]['tmp_name'], $returnArray[$curKey]['full_path']);

			if (file_exists($uploadedModuleZip)) {
				### SUCCESSFUL UPLOAD ###
				$unzip = $this->unzip_module($uploadedModuleZip);
				if ($unzip == true) {
					### SUCCESSFUL UNZIP ###
					$install_results = $this->install_module($this->modulesUploadTempDir);
					if ( is_array($install_results) ) {
						if (isset($install_results['Module_Info']['display_name'])) {
							$moduleID = $this->get_module_id($install_results['Module_Info']['mod_name']);
							$moduleSVXLinkID = $this->get_module_svxlink_id($moduleID);
							$currDisplayName = trim($install_results['Module_Info']['display_name']);
							$statusArray = ['status' => 'success', 'msgText' => $currDisplayName . ': ' . _('Module has been successfully installed. To use it, you must first activate it.')];
							$statusArray = array_merge($statusArray, $install_results['Module_Info']);
							$statusArray['moduleKey'] = $moduleID;
							$statusArray['svxlinkID'] = $moduleSVXLinkID;
							return $statusArray;	
						} else {
							$statusArray = ['status' => 'success', 'msgText' => _('Module has been successfully installed. To use it, you must first activate it.')];
							return $statusArray;
						}

					} else {
						$statusArray = ['status' => 'error', 'msgText' => _('There was a problem installing the module. Either this is not an OpenRepeater module, or the zip file was improperly constructed, or the module already exists.')];
						return $statusArray;
					}


				} else {
					### FAILED UNZIP ###
					$statusArray = ['status' => 'error', 'msgText' => _('There was a problem Unzipping the file')];
					return $statusArray;
				}

			} else {
				// Failure
				$statusArray = ['status' => 'error', 'msgText' => _('There was a problem uploading the file.')];
				return $statusArray;
			}
			
		}


		# Some how it got thru validation, but nothing was done.
		$statusArray = ['status' => 'error', 'msgText' => _('Don\'t know what happened, but nothing appears to have been done.')];
		return $statusArray;

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
					if (!file_exists($this->svxlink_events_d_path)) { mkdir($this->svxlink_events_d_path, 0777, true); } // Create events.d directory if for some reason it doesn't exist
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
					if (!file_exists($this->svxlink_modules_d_path)) { mkdir($this->svxlink_modules_d_path, 0777, true); } // Create modules.d directory if for some reason it doesn't exist
					symlink($currTarget, $currLink); // Set New Link
				}
			}


			### Check for SVXLink Sounds and link into place ###

			$svxlink_sounds_path = $this->svxlink_sounds . $svxlink_name;
			$mod_sounds_path = $this->modules_path . $svxlink_name . '/svxlink/sounds/en_US/';
			if (file_exists($mod_sounds_path)) {
				if (file_exists($svxlink_sounds_path)) { exec('rm ' . $svxlink_sounds_path . ' -R'); } // remove orphan first
				symlink($mod_sounds_path, $svxlink_sounds_path); // Set New Link
			}

		}


		### Created DB record in modules table, if it doesn't exist, and set as deactive ###

		if ($this->Database->exists('modules','svxlinkName',$svxlink_name) == false) { 
			// Reset auto increment of modules table
			$sql = 'UPDATE SQLITE_SEQUENCE SET SEQ=0 WHERE NAME="modules";';
			$insert_result = $this->Database->insert($sql);

			// Insert new record
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
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
		
	}



	###############################################
	# Display All Modules
	###############################################

	public function getModulesJSON($listType = 'full') {
		$modules = $this->get_modules();

		foreach($modules as $cur_mod) { 
			$curID = $cur_mod['moduleKey'];

			unset($modules[$curID]['moduleOptions']);

			$mod_ini_file = $this->modules_path.$cur_mod['svxlinkName'].'/info.ini';
			$mod_settings_file = $this->modules_path.$cur_mod['svxlinkName'].'/settings.php';
			$dtmf_help_file = $this->modules_path.$cur_mod['svxlinkName'].'/dtmf.php';

			$curr_mod_ini = $this->read_ini($cur_mod['svxlinkName']);

			// Moudle Display Name
			if (isset($curr_mod_ini['Module_Info']['display_name'])) {
				$modules[$curID]['displayName'] = $curr_mod_ini['Module_Info']['display_name'];
			} else {
				$modules[$curID]['displayName'] = $currDisplayName = $cur_mod['svxlinkName'];
			}

			// Module type		
			if ($listType == 'full') {
				if ( in_array($cur_mod['svxlinkName'], $this->core_modules) ) {
					$modules[$curID]['type'] = 'core';
				} else if ( isset($curr_mod_ini['Module_Info']['mod_type']) ) {
					if ($curr_mod_ini['Module_Info']['mod_type'] == 'daemon') { $modules[$curID]['type'] = 'daemon'; }
				} else {
					$modules[$curID]['type'] = 'add-on';				
				}				
			}


			// Settings Link...if Applicable
			if ($listType == 'full') {
				if (file_exists($mod_settings_file)) {
					$modules[$curID]['settings'] = true;
				} else {
					$modules[$curID]['settings'] = false;
				}
			}

			// DTMF Link...if Applicable
			if ($listType == 'full') {
				if (file_exists($dtmf_help_file)) {
					$modules[$curID]['dtmf'] = true;
				} else {
					$modules[$curID]['dtmf'] = false;
				}
			}

			// Module Description
			if ($listType == 'full') {
				if (isset($curr_mod_ini['Module_Info']['mod_desc'])) {
					$curDesc = $curr_mod_ini['Module_Info']['mod_desc'];
					$modules[$curID]['desc'] = $curDesc;
				} else {
					$modules[$curID]['desc'] = '<em>(' . _('No Description') . ')</em>';
				}
			}

			// Version / Author Info
			if ($listType == 'full') {
				$versionInfo = '';
				if (isset($curr_mod_ini['Module_Info']['version'])) { $versionInfo .= _('Version') . ': ' . $curr_mod_ini['Module_Info']['version']; }
				if ( isset($curr_mod_ini['Module_Info']['version']) && isset($curr_mod_ini['Module_Info']['authors'])) { $versionInfo .= ' | '; }
				if (isset($curr_mod_ini['Module_Info']['authors'])) { $versionInfo .= _('Authors') . ': ' . $curr_mod_ini['Module_Info']['authors']; }
				$modules[$curID]['version'] = $versionInfo;
			}
		} /* End Current Module */

		return json_encode($modules);
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
			<form class="form-horizontal form-label-left input_mask" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" id="moduleSettingsUpdate">
			
			<div class="page-title">
				<div class="title_full">
					<h3><i class="fa fa-plug"></i> ' . $displayName . ' Module Settings</h3>
				</div>
			</div>
			
			<div class="clearfix"></div>
			';


			// Built Bottom of Form
			$form_bottom = '
			<div class="clearfix"></div>
			
			<div class="form-actions">
				<!-- PASS MODULE KEY FOR UPDATE DATABASE AND REDIRECT BACK TO SETTINGS PAGE -->
				<input type="hidden" name="moduleKey" value="' . $id . '">
				<input type="hidden" name="updateModuleSettings">
				<button type="submit" id="saveModuleSettingsBtn" class="btn btn-primary"><i class="fa fa-save"></i> Save & Exit</button>
			</div>
			
			</form>			
			';


			// ***************************************************************** //
			// Construct Page Content
			ob_start();
			$customCSS = 'page-moduleSettings.css'; // Inserted in header
			$customJS = 'page-moduleSettings.js'; // Inserted in footer
			echo "<script>var newPageTitle = '" . $pageTitle . "';</script>";
			include($this->includes_path . 'module_header.php');
			echo $form_top;
			include($mod_settings_file);
			echo $form_bottom;
			include($this->includes_path . 'module_footer.php');
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
			// Render Setting Menus for Active Modules
			foreach ($modulesActive as $mod_id => $mod_name) {
				$curSettingsURL = 'modules.php?settings=' . $mod_id;
				$return_html .= '<li><a class="navLink" href="'.$curSettingsURL.'">'.$mod_name.'</a></li>';
			}
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
					if ($cur_mod_loop['moduleEnabled']==1 && file_exists($dtmf_help_file)) {
						include($dtmf_help_file);
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