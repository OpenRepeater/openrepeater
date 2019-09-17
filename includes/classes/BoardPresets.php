<?php
#####################################################################################################
# Board Presets Class
#####################################################################################################

class BoardPresets {

    public $documentRoot;
    public $boardPresetArray = [];
    public $selectedBoardArray = [];
    public $boardManufacturerArray = [];



	public function __construct() {
		$board_definitions = [];
		$this->documentRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

		include_once($this->documentRoot . '/includes/board_definitions.php');
		$this->boardPresetArray = $board_definitions;
	}



	private function orp_helper_call($section, $subfunc) {
		return shell_exec( "sudo orp_helper " . trim($section) . " " . trim($subfunc) );
	}



	public function get_board_definitions($id = null) {
		if(isset($id)) {
			# Return Single Board Preset
			$this->selectedBoardArray = $this->boardPresetArray[$id];			
		}
		return $this->selectedBoardArray;
	}


	
	public function get_manufacturers() {
		// Check if board array has been set, if not set it.
// 		if (empty($this->boardPresetArray)) { $this->get_board_definitions(); }

		foreach ($this->boardPresetArray as $board) {
			$this->boardManufacturerArray[] = $board['manufacturer'];
		}
		
		$this->boardManufacturerArray = array_unique($this->boardManufacturerArray);
		return $this->boardManufacturerArray;
	}



	public function get_select_options() {
		// Check if Arrays have been set, if not set them.
// 		if (empty($this->boardPresetArray)) { $this->get_board_definitions(); }
		if (empty($this->boardManufacturerArray)) { $this->get_manufacturers(); }

		$html_options = "";
		foreach ($this->boardManufacturerArray as $manufacturerID => $manufacturerName) {
			$html_options .= '<optgroup label="' . $manufacturerName . '">';
				foreach ($this->boardPresetArray as $boardID => $boardValues) {
					if ( $boardValues['manufacturer'] == $manufacturerName )
				    $html_options .= '<option value="' . $boardID . '">' . $boardValues['model'];
					if ( isset($boardValues['version']) ) {
					    $html_options .= ' (' . trim($boardValues['version']) . ')';
					}
				    $html_options .= '</option>';
				}
			$html_options .= '</optgroup>';
		}

		return $html_options;
	}



	public function alsa_mixer_settings() {
		# Loop through mixer settings by device, then settings and update ALSA settings
		foreach ($this->selectedBoardArray['alsa_settings'] as $current_alsa_dev => $curr_alsa_dev_values) {		
			foreach ($curr_alsa_dev_values as $device_setting => $device_value) {
				$alsa_args = '"' . $device_setting . '" "' . $device_value . '" ' . $current_alsa_dev;
				$this->orp_helper_call('set_mixer', $alsa_args);
			}		
		}
	}



	public function load_board_settings($id = null) {
		$this->get_board_definitions($id);

 		$classDB = new Database();
		$classDB->clear_ports_table();
		$classDB->clear_gpio_table();

		$fullBoardName = trim($this->selectedBoardArray['manufacturer'] . ' - ' . $this->selectedBoardArray['model']);
				
		// Build Preset Values to Save to Database
		$build_ports_table = array();
		$build_gpio_table = array();
		$build_module_table = array();
		$build_module_gpio_pins = array();
		
		if (isset($this->selectedBoardArray['ports'])) {
			// Build Ports
			foreach ($this->selectedBoardArray['ports'] as $current_port_id => $curr_port_columns) {
				// Add Port Values
				$build_ports_table[$current_port_id] = ['portNum' => $current_port_id];
				foreach ($curr_port_columns as $columnKey => $columnValue) {
					$build_ports_table[$current_port_id] += [$columnKey => $columnValue];
				}		
			}
		}

		$classDB->update_ports_table($build_ports_table);


		if (isset($this->selectedBoardArray['modules'])) {
			foreach ($this->selectedBoardArray['modules'] as $current_module_name => $curr_module_values) {
				$build_module_table[] = [
					'moduleKey' => $current_module_name,
					'moduleOptions' => serialize($curr_module_values)
				];

				// Set GPIOs for Remote Relay if supported by board
				if(isset($curr_module_values['relay'])) {
					foreach ($curr_module_values['relay'] as $curr_relay) {
						$build_gpio_table[] = [
							'gpio_num' => $curr_relay['gpio'],
							'direction' => 'out',
							'active' => $curr_module_values['relays_gpio_active_state'],
							'description' => 'RELAY: ' . $curr_relay['label'],
							'type' => 'RemoteRelay'
						];
					}
				}
			}
		}

		$classDB->update_gpio_table($build_gpio_table);

		$classDB->deactive_module();
		if (count($build_module_table) > 0) { $classDB->update_preset_modules($build_module_table); }
		
		// Update Alsa Mixer Settings
		if ( isset( $this->selectedBoardArray['alsa_settings'] ) ) {
			$this->alsa_mixer_settings();
		}

		// Rerun board definitions to reset Board Preset Array
		$this->get_board_definitions();

		return $fullBoardName;
	}
	
}

?>
