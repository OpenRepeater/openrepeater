<?php
$board_definitions = array();

#################################################################################
# ICS Controllers - Pi Repeater 2X
#################################################################################

$board_definitions[170923] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 2X',
	'version' => 'v3.1+',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '498',
			'txGPIO_active' => 'high',
			'rxGPIO_ctcss' => '24',
			'rxGPIO_ctcss_active' => 'low',
			'txGPIO_ctcss' => '13',
			'txGPIO_ctcss_active' => 'low',
		],
		2 => [
			'portLabel' => 'Port 2',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'low',
			'txGPIO' => '499',
			'txGPIO_active' => 'high',
			'rxGPIO_ctcss' => '25',
			'rxGPIO_ctcss_active' => 'low',
			'txGPIO_ctcss' => '27',
			'txGPIO_ctcss_active' => 'low',
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


#################################################################################
# ICS Controllers - Pi Repeater 1X
#################################################################################

$board_definitions[171231] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 1X',
	'version' => 'v1.0+',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '498',
			'txGPIO_active' => 'high',
			'rxGPIO_ctcss' => '24',
			'rxGPIO_ctcss_active' => 'low',
			'txGPIO_ctcss' => '13',
			'txGPIO_ctcss_active' => 'low',
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


#################################################################################
# F5UII/F8ASB - SVXLink Card
#################################################################################

$board_definitions[160430] = [
	'manufacturer' => 'F5UII/F8ASB',
	'model' => 'SVXLink Card',
	'version' => 'v1.11+',	
	'ports' => [
		1 => [
			'portLabel' => 'Repeater',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '19',
			'rxGPIO_active' => 'low',
			'txGPIO' => '16',
			'txGPIO_active' => 'high',
		],
		2 => [
			'portLabel' => 'Link',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '18',
			'rxGPIO_active' => 'low',
			'txGPIO' => '17',
			'txGPIO_active' => 'high',
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

$board_definitions[161021] = [
	'manufacturer' => 'Richard Neese',
	'model' => 'SVXLink Basic Board',
	'version' => 'v1.0',
	'ports' => [
		1 => [
			'portLabel' => 'Main Port',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'high',
			'txGPIO' => '24',
			'txGPIO_active' => 'high',
		],
		2 => [
			'portLabel' => 'Link Port',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '25',
			'rxGPIO_active' => 'high',
			'txGPIO' => '18',
			'txGPIO_active' => 'high',
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
# DMK Engineering Inc. - URI
#################################################################################

$board_definitions[110326] = [
	'manufacturer' => 'DMK Engineering Inc.',
	'model' => 'URI',
	'version' => '9095',
	'ports' => [
		1 => [
			'portLabel' => 'Main Port',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
			'portType' => 'HiDraw',
			'hidrawDev' => '/dev/hidraw0',
			'hidrawRX_cos' => 'VOL_DN',
			'hidrawRX_cos_invert' => true,
			'hidrawRX_ctcss' => 'VOL_UP',
			'hidrawRX_ctcss_invert' => true,
			'hidrawTX_ptt' => 'GPIO3',
			'hidrawTX_ptt_invert' => false,
		],
	],
	'gpios' => [
		1 => 'GPIO1',
		2 => 'GPIO2',
		4 => 'GPIO4',
		5 => 'GPIO5',
		6 => 'GPIO6',
		7 => 'GPIO7',
		8 => 'GPIO8',
	],
	'alsa_settings' => [
		0 => [
			'Speaker' => '75%',
			'Mic' => '18dB'
		]
	]
];


#################################################################################
# Repeater Builder / Scott Zimmerman (N3XCC) - USB-RIM Lite
#################################################################################

$board_definitions[170306] = [
	'manufacturer' => 'Repeater Builder',
	'model' => 'USB-RIM Lite',
	'ports' => [
		1 => [
			'portLabel' => 'Main Port',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
			'portType' => 'HiDraw',
			'hidrawDev' => '/dev/hidraw0',
			'hidrawRX_cos' => 'VOL_DN',
			'hidrawRX_cos_invert' => true,
			'hidrawRX_ctcss' => 'VOL_UP',
			'hidrawRX_ctcss_invert' => true,
			'hidrawTX_ptt' => 'GPIO3',
			'hidrawTX_ptt_invert' => false,
		],
	],
	'alsa_settings' => [
		0 => [
			'Speaker' => '75%',
			'Mic' => '18dB'
		]
	]
];


#################################################################################
# ICS Controllers - Pi Repeater 2X PROTOTYPE
#################################################################################

$board_definitions[170402] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 2X',
	'version' => 'v2.1 - PROTOTYPE',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '498',
			'txGPIO_active' => 'low',
			'rxGPIO_ctcss' => '24',
			'rxGPIO_ctcss_active' => 'low',
			'txGPIO_ctcss' => '13',
			'txGPIO_ctcss_active' => 'low',
		],
		2 => [
			'portLabel' => 'Port 2',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'low',
			'txGPIO' => '499',
			'txGPIO_active' => 'low',
			'rxGPIO_ctcss' => '25',
			'rxGPIO_ctcss_active' => 'low',
			'txGPIO_ctcss' => '27',
			'txGPIO_ctcss_active' => 'low',
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


?>