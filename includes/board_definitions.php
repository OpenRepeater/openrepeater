<?php
$board_definitions = array();

#################################################################################
# ICS Controllers - Pi Repeater 2X
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 2X',
	'version' => '3.1+',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxMode' => 'gpio',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '498',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
		],
		2 => [
			'portLabel' => 'Port 2',
			'rxMode' => 'gpio',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'low',
			'txGPIO' => '499',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
		]
	],
	'alsa_settings' => [
		0 => [
			'Headphone' => '86%',
			'PCM' => '75%',
			'Lineout' => '80% unmute',
			'Mic' => '59%',
			'Capture Mux' => 'LINE_IN'
		]
	]
];
/*
(Not Yet Supported by ORP)
CTCSS1: 24 low
CTCSS2: 25 low
CTCSS_ENC1: 13 low
CTCSS2_ENC: 27 low
*/


#################################################################################
# ICS Controllers - Pi Repeater 1X
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 1X',
	'version' => '1.0+',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxMode' => 'gpio',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '498',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
		]
	],
	'alsa_settings' => [
		0 => [
			'Headphone' => '86%',
			'PCM' => '75%',
			'Lineout' => '80% unmute',
			'Mic' => '59%',
			'Capture Mux' => 'LINE_IN'
		]
	]
];
/*
(Not Yet Supported by ORP)
CTCSS1: 24 low
CTCSS_ENC1: 13 low
*/


#################################################################################
# F5UII/F8ASB - SVXLink Card
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'F5UII/F8ASB',
	'model' => 'SVXLink Card',
	'version' => '1.11+',	
	'ports' => [
		1 => [
			'portLabel' => 'Repeater',
			'rxMode' => 'gpio',
			'rxGPIO' => '19',
			'rxGPIO_active' => 'low',
			'txGPIO' => '16',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
		],
		2 => [
			'portLabel' => 'Link',
			'rxMode' => 'gpio',
			'rxGPIO' => '18',
			'rxGPIO_active' => 'low',
			'txGPIO' => '17',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
		]
	],
	'alsa_settings' => [
		0 => [
			'Speaker' => '75%',
			'Mic' => '69%'
		],
		1 => [
			'Speaker' => '75%',
			'Mic' => '69%'
		]
	],
	'modules' => [
		'Remote Relay' => [
			'timeout' => '120',
			'momentary_delay' => '200',
			'access_pin' => '1234',
			'access_attempts_allowed' => '3',
			'relays_off_deactivation' => '1',
			'relays_gpio_active_state' => 'high',
			'relay' => [
				1 => [
			        'gpio' => '20',
			        'label' => 'Relay 1'
				],
				2 => [
			        'gpio' => '21',
			        'label' => 'Relay 2'
				],
				3 => [
			        'gpio' => '22',
			        'label' => 'Relay 3'
				],
				4 => [
			        'gpio' => '23',
			        'label' => 'Relay 4'
				],
			]	
		],		
	],

	
	
];


#################################################################################
# Richard Neese - SVXLink Basic Board
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'Richard Neese',
	'model' => 'SVXLink Basic Board',
	'version' => '1.0',
	'ports' => [
		1 => [
			'portLabel' => 'Main Port',
			'rxMode' => 'gpio',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'high',
			'txGPIO' => '24',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
		],
		2 => [
			'portLabel' => 'Link Port',
			'rxMode' => 'gpio',
			'rxGPIO' => '25',
			'rxGPIO_active' => 'high',
			'txGPIO' => '18',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
		]
	],
	'alsa_settings' => [
		0 => [
			'Speaker' => '75%',
			'Mic' => '69%'
		],
		1 => [
			'Speaker' => '75%',
			'Mic' => '69%'
		]
	]
];


#################################################################################
# ICS Controllers - Pi Repeater 2X PROTOTYPE
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 2X',
	'version' => '2.1 - PROTOTYPE',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxMode' => 'gpio',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '498',
			'txGPIO_active' => 'low',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
		],
		2 => [
			'portLabel' => 'Port 2',
			'rxMode' => 'gpio',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'low',
			'txGPIO' => '499',
			'txGPIO_active' => 'low',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
		]
	],
	'alsa_settings' => [
		0 => [
			'Headphone' => '86%',
			'PCM' => '75%',
			'Lineout' => '80% unmute',
			'Mic' => '59%',
			'Capture Mux' => 'LINE_IN'
		]
	]
];
/*
(Not Yet Supported by ORP)
CTCSS1: 24 low
CTCSS2: 25 low
CTCSS_ENC1: 13 low
CTCSS2_ENC: 27 low
*/


?>