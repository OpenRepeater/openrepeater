<?php
#####################################################################################################
# Board Presets Class
#####################################################################################################

require_once('Database.php');

class BoardPresets {

    public $documentRoot;
    public $boardPresetArray;
    public $boardManufacturerArray;



	public function __construct() {
		$this->documentRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
	}



	public function get_board_definitions($id = null) {
		include_once($this->documentRoot . '/includes/board_definitions.php');
		if(isset($id)) {
			# Return Single Board Preset
			$this->boardPresetArray = $board_definitions[$id];			
		} else {
			# Return All Board Presets
			$this->boardPresetArray = $board_definitions;			
		}
		return $this->boardPresetArray;
	}


	
	public function get_manufacturers() {
		// Check if board array has been set, if not set it.
		if (empty($this->boardPresetArray)) { $this->get_board_definitions(); }

		foreach ($this->boardPresetArray as $board) {
			$this->boardManufacturerArray[] = $board['manufacturer'];
		}
		
		$this->boardManufacturerArray = array_unique($this->boardManufacturerArray);
		return $this->boardManufacturerArray;
	}



	public function get_select_options() {
		// Check if Arrays have been set, if not set them.
		if (empty($this->boardPresetArray)) { $this->get_board_definitions(); }
		if (empty($this->boardManufacturerArray)) { $this->get_manufacturers(); }

		$html_options = "";
		foreach ($this->boardManufacturerArray as $manufacturerID => $manufacturerName) {
			$html_options .= '<optgroup label="' . $manufacturerName . '">';
				foreach ($this->boardPresetArray as $boardID => $boardValues) {
					if ( $boardValues['manufacturer'] == $manufacturerName )
				    $html_options .= '<option value="' . $boardID . '">' . $boardValues['model'] . ' (v' . $boardValues['version'] . ')</option>';
				}
			$html_options .= '</optgroup>';
		}

		return $html_options;
	}



	public function load_board_settings($id = null) {
		$this->get_board_definitions($id);

		$fullBoardName = trim($this->boardPresetArray['manufacturer'] . ' - ' . $this->boardPresetArray['model']);
				
		// Build Preset Values to Save to Database
		$build_ports_table = array();
		$build_gpio_table = array();
		$build_module_table = array();
		$build_module_gpio_pins = array();
		
		if (isset($this->boardPresetArray['ports'])) {
			// Build Ports
			foreach ($this->boardPresetArray['ports'] as $current_port_id => $curr_port_values) {
				// Add Port Values
				$build_ports_table[$current_port_id] = [
					'portNum' => $current_port_id,
					'portLabel' => $curr_port_values['portLabel'],
					'rxMode' => $curr_port_values['rxMode'],
					'rxGPIO' => $curr_port_values['rxGPIO'],
					'txGPIO' => $curr_port_values['txGPIO'],
					'rxAudioDev' => $curr_port_values['rxAudioDev'],
					'txAudioDev' => $curr_port_values['txAudioDev'],
					'rxGPIO_active' => $curr_port_values['rxGPIO_active'],
					'txGPIO_active' => $curr_port_values['txGPIO_active'],			
				];
				
				// Add GPIO Pin for RX for Port...if one is set
				if(isset($curr_port_values['rxGPIO']) && $curr_port_values['rxGPIO'] != '') {
					$build_gpio_table[] = [
						'gpio_num' => $curr_port_values['rxGPIO'],
						'direction' => 'in',
						'active' => $curr_port_values['rxGPIO_active'],
						'description' => 'PORT ' . $current_port_id . ' RX: ' . $board_definitions[$board_selected]['model'],
						'type' => 'Port'
					];			
				}
		
				// Add GPIO Pin for TX for Port...if one is set
				if(isset($curr_port_values['txGPIO']) && $curr_port_values['txGPIO'] != '') {
					$build_gpio_table[] = [
						'gpio_num' => $curr_port_values['txGPIO'],
						'direction' => 'out',
						'active' => $curr_port_values['txGPIO_active'],
						'description' => 'PORT ' . $current_port_id . ' TX: ' . $board_definitions[$board_selected]['model'],
						'type' => 'Port'
					];
				}
		
			}
		}



		if (isset($this->boardPresetArray['modules'])) {
			foreach ($this->boardPresetArray['modules'] as $current_module_name => $curr_module_values) {
				$build_module_table[] = [
					'moduleName' => $current_module_name,
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

		// Update Database
 		$classDB = new Database();
		$classDB->clear_ports_table();
		$classDB->update_ports_table($build_ports_table);

		$classDB->clear_gpio_table();
		$classDB->update_gpio_table($build_gpio_table);

		$classDB->deactive_module();
		if (count($build_module_table) > 0) { $classDB->update_preset_modules($build_module_table); }
		
		$classDB->set_update_flag(true);


		return $fullBoardName;
	}
	
}

?>
