<?php
$board_definitions = array();

#################################################################################
# ICS Controllers - Pi Repeater 2X
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'ICS Controllers',
	'model' => 'Pi Repeater 2X',
	'version' => '2.1+',
	'ports' => [
		1 => [
			'portLabel' => 'Port 1',
			'rxMode' => 'gpio',
			'rxGPIO' => '26',
			'rxGPIO_active' => 'low',
			'txGPIO' => '506',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
		],
		2 => [
			'portLabel' => 'Port 2',
			'rxMode' => 'gpio',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'low',
			'txGPIO' => '507',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
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
			'txGPIO' => '506',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
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
			'txAudioDev' => 'alsa:plughw:0|1',
		],
		2 => [
			'portLabel' => 'Link',
			'rxMode' => 'gpio',
			'rxGPIO' => '18',
			'rxGPIO_active' => 'low',
			'txGPIO' => '17',
			'txGPIO_active' => 'high',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|1',
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

/*
Relay 1: 20 high
Relay 2: 21 high
Relay 3: 22 high
Relay 4: 23 high
*/

// a:7:{s:7:"timeout";s:3:"120";s:15:"momentary_delay";s:3:"200";s:10:"access_pin";s:4:"1234";s:23:"access_attempts_allowed";s:1:"3";s:23:"relays_off_deactivation";s:1:"1";s:24:"relays_gpio_active_state";s:4:"high";s:5:"relay";a:4:{i:1;a:2:{s:4:"gpio";s:2:"20";s:5:"label";s:7:"Relay 1";}i:2;a:2:{s:4:"gpio";s:2:"21";s:5:"label";s:7:"Relay 2";}i:3;a:2:{s:4:"gpio";s:2:"22";s:5:"label";s:7:"Relay 3";}i:4;a:2:{s:4:"gpio";s:2:"23";s:5:"label";s:7:"Relay 4";}}}




#################################################################################
# ICS Controllers - Pi Repeater 1X
#################################################################################

$board_definitions[] = [
	'manufacturer' => 'Richard Neese',
	'model' => 'SVXLink Basic Board',
	'version' => '1.0',
];

/*
SVXLink Basic Board (Ver 1, Rev 1A)
Main Port RX GPIO: 23 high
Main Port TX GPIO: 24 high
Link Port RX GPIO: 25 high
Link Port TX GPIO: 18 high
*/



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
			'txGPIO' => '506',
			'txGPIO_active' => 'low',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
		],
		2 => [
			'portLabel' => 'Port 2',
			'rxMode' => 'gpio',
			'rxGPIO' => '23',
			'rxGPIO_active' => 'low',
			'txGPIO' => '507',
			'txGPIO_active' => 'low',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
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