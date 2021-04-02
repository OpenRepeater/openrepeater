<?php

# Edit the values for the settings in this array as you wish them to be saved to DB
$fakeMacroArray = [
	0 => [
		'macroEnabled' => '1', // 0 or 1
		'macroNum' => '1', // Desired DTMF Number
		'macroLabel' => 'Sample EchoLink Macro', // Helpful Description
		'macroModuleKey' => '3', // "moduleKey" from modules table
		'macroString' => '9999#', // Marco String
		'macroPorts' => '1', // Use either a port number or 'ALL' to use for all ports.
	],
	1 => [
		'macroEnabled' => '0', // 0 or 1
		'macroNum' => '8', // Desired DTMF Number
		'macroLabel' => 'Sample Parrot Macro', // Helpful Description
		'macroModuleKey' => '2', // "moduleKey" from modules table
		'macroString' => '0123456789##', // Marco String
		'macroPorts' => '1', // Use either a port number or 'ALL' to use for all ports.
	]
];

################################################################################

$fakeCTCSS = ['67','69.3','71.9','74.4','77','79.7','82.5','85.4','88.5','91.5','94.8','97.4','100','103.5','107.2','110.9','114.8','118.8','123','127.3','131.8','136.5','141.3','146.3','151.4','156.7','159.8','162.2','165.5','167.9','171.3','173.8','177.3','179.9','183.5','186.2','189.9','192.8','196.6','199.5','203.5','206.5','210.7','218.1','225.7','229.1','233.6','241.8','250.3','254.1'];

################################################################################

$fakePorts = [
    1 => [
        'portNum' => '1',
        'portLabel' => 'ICS 2X Port 1',
        'rxAudioDev' => 'alsa:plughw:0|1',
        'txAudioDev' => 'alsa:plughw:0|1',
        'portType' => 'GPIO',
        'rxMode' => 'cos',
        'rxGPIO' => '26',
        'txGPIO' => '498',
        'rxGPIO_active' => 'low',
        'txGPIO_active' => 'high',

        'portDuplex'  => 'full',
        'portEnabled'  => '1',
        'linkGroup'  => '1,2,4',
        
		'SVXLINK_ADVANCED_LOGIC' => [
			'DTMF_CTRL_PTY' => '123',
			'OPEN_ON_1750' => 'maybe',
		],
		'SVXLINK_ADVANCED_RX' => [
			'AUDIO_DEV_KEEP_OPEN' => '456',
		],
		'SVXLINK_ADVANCED_TX' => [
			'AUDIO_DEV_KEEP_OPEN' => '789',
		],

    ],

    2 => [
        'portNum' => '2',
        'portLabel' => 'ICS 2X Port 2',
        'rxAudioDev' => 'alsa:plughw:0|0',
        'txAudioDev' => 'alsa:plughw:0|0',
        'portType' => 'GPIO',
        'rxMode' => 'cos',
        'rxGPIO' => '23',
        'txGPIO' => '499',
        'rxGPIO_active' => 'low',
        'txGPIO_active' => 'high',           

		'portDuplex'  => 'half',
        'portEnabled'  => '0',
        'linkGroup'  => '1',
    ],

	3 => [
	    'portNum' => '3',
	    'portLabel' => 'Hidraw/CM119 USB Device',
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
	
	    'portDuplex'  => 'full',
	    'portEnabled'  => '1',
	    'linkGroup'  => '2',
	
// 		    'hidrawRX_cos_invert' => false,
	],

/*
    3 => [
            'portNum' => '3',
            'portLabel' => 'DMK URI',
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

            'portDuplex'  => 'half',
            'portEnabled'  => '1',
            'linkGroup'  => '1',
            'rxAudioDev' => 'alsa:plughw:1|0',
            'txAudioDev' => 'alsa:plughw:1|0',
        ],
*/

    4 => [
        'portNum' => '4',
        'portLabel' => 'Test Serial',
        'rxAudioDev' => 'alsa:plughw:0|0',
        'txAudioDev' => 'alsa:plughw:0|0',
        'portType' => 'Serial',
        'rxMode' => 'cos',
        'serialDev' => '/dev/ttyUSB1',
        'serialRX_cos' => 'CTS',
        'serialRX_cos_invert' => true,
        'serialTX_ptt' => 'DTR',
        'serialTX_ptt_invert' => false,

        'portDuplex'  => 'half',
        'portEnabled'  => '1',
    ],

];

################################################################################

// DUMMY FUNCTION TO LATER BE REPLACE BY GETTEXT FOR TRANSLATIONS
function _M($input) {
	return $input;
}

$fakeModules = [
    '1' => [
        'moduleKey' => '1',
        'moduleEnabled' => '1',
        'svxlinkName' => 'Help',
        'displayName' => 'Help',
        'svxlinkID' => '0',
        'moduleOptions' => '',

        'desc' => 'A voice based help system that provides DTMF commands over the air on active modules. If you do not want this broadcast to the general public, then you should disable this module.',
        'type' => 'core',
        'dtmf' => 'false',

        'tempSubCommands' => [
	        '0#' => 'Overview of the Help Module',
	        '1#' => 'Help on Parrot Module',
	        '2#' => 'Help on EchoLink Module',
	        '4#' => 'Help on Remote Relay Module',
	        '#' => 'Exit Help',
        ],

    ],

    '2' => [
        'moduleKey' => '2',
        'moduleEnabled' => '0',
        'svxlinkName' => 'Parrot',
        'displayName' => 'Parrot',
        'svxlinkID' => '1',
        'moduleOptions' => '',

        'desc' => 'Play back everything that is received.',
        'type' => 'core',
        'dtmf' => 'true',

        'tempSubCommands' => [
	        '#' => 'Exit Parrot',
        ],
    ],

    '3' => [
        'moduleKey' => '3',
        'moduleEnabled' => '1',
        'svxlinkName' => 'EchoLink',
        'displayName' => 'EchoLink',
        'svxlinkID' => '2',
        'moduleOptions' => '',

        'desc' => 'The EchoLink® network allows licensed Amateur Radio stations to communicate with one another over the Internet using VOIP technology. This module allows worldwide connections to be made between this and other repeaters or to individuals using EchoLink nodes.',
        'type' => 'core',
        'settings' => 'true',
        'dtmf' => 'true',

        'tempSubCommands' => [
	        '9999#' => 'Connect to EchoLink by Node ID. (Node 9999 is ECHOTEST)',
	        '#' => 'Disconnect from last connected station',
	        '##' => 'Disconnect station and deactivate EchoLink Module',
	        'hr1' => 'hr',
	        '0#' => 'Play the help message',
	        '1#' => 'List all connected stations',
	        '2#' => 'Play local EchoLink node id',
	        '31#' => 'Connect to a random link or repeater',
	        '32#' => 'Connect to a random conference',
	        '4#' => 'Reconnect to the last disconnected station',
	        '50#' => 'Deactivate listen only mode',
	        '51#' => 'Activate listen only mode',
	        '7#' => 'Use to disconnect a particular connected station from list',
        ],
    ],

    '4' => [
        'moduleKey' => '4',
        'moduleEnabled' => '1',
        'svxlinkName' => 'RemoteRelay',
        'displayName' => 'Remote Relay',
        'svxlinkID' => '3',
        'moduleOptions' => '',

        'desc' => 'The ORP Remote Relay Module is a module to add the ability to control relays by DTMF tones remotely via GPIO pins. It will support up to 8 relays and offers the ability to restrict access with a pin code.',
        'settings' => 'true',
        'dtmf' => 'true',

        'tempSubCommands' => [

	        '1234+#' => 'DTMF Access Pin',
	        'divider0' => '-----------------',

	        'heading1' => 'Status Report',
	        '0#' => 'Speaks state of all relays (On/Off)',

	        'divider01' => '-----------------',
	        'heading01' => 'Relay 1 - Tower Light Override',
	        '1+0#' => 'Relay 1 Off',
	        '1+1#' => 'Relay 1 On',
	        '1+2#' => 'Relay 1 Momentary',

	        'divider02' => '-----------------',
	        'heading02' => 'Relay 2 - Solar Controller Reset',
	        '2+0#' => 'Relay 2 Off',
	        '2+1#' => 'Relay 2 On',
	        '2+2#' => 'Relay 2 Momentary',

	        'divider2' => '-----------------',
	        'heading2' => 'Group Relay Control',
	        '100#' => 'All Relays Off',
	        '101#' => 'All Relays On',
	        '102#' => 'All Relays Momentary',
	        'divider3' => '-----------------',
	        'heading3' => 'Diagnostics',
	        '999#' => 'Relay Test Procedure',
	        'divider4' => '-----------------',
	        '#' => 'Deactivate Relay Module',
        ],
    ],

    '5' => [
        'moduleKey' => '5',
        'moduleEnabled' => '1',
        'svxlinkName' => 'SiteStatus',
        'displayName' => 'Site Status',
        'svxlinkID' => '',
        'moduleOptions' => '',
        'settings' => 'true',

        'type' => 'daemon',
        'dtmf' => 'true',

        'desc' => 'The SiteStatus module is useful for monitoring the general status of a repeater site remotely. Through the use of Digital and Analog sensors, you can be audibly notified of events and things of interest from a maintenance perspective...',
    ],

    '6' => [
        'moduleKey' => '6',
        'moduleEnabled' => '1',
        'svxlinkName' => 'RigCtl',
        'displayName' => 'Rig Control',
        'svxlinkID' => '5',
        'moduleOptions' => '',

        'desc' => 'The ORP Rig Control Module is a module to add the ability to control supported radios remotely by DTMF tones. This is a more advanced modules and requires the use of the HamLib Library which must also be installed. Radio support is limited in part by what HamLib supports.',
        'settings' => 'true',
        'dtmf' => 'true',
    ],

    '7' => [
        'moduleKey' => '7',
        'moduleEnabled' => '1',
        'svxlinkName' => 'VoiceMail',
        'displayName' => 'Voice Mail',
        'svxlinkID' => '6',
        'moduleOptions' => '',

        'desc' => 'ORP Voicemail is an adaptation of the TclVoiceMail module design specifically to run on OpenRepeater.',
        'settings' => 'true',
        'dtmf' => 'true',

		'tempSubCommands' => [
			'2#' => 'Record a voice message',
			'#' => 'Exit Voice Mail',
		],
    ],

    '8' => [
        'moduleKey' => '8',
        'moduleEnabled' => '1',
        'svxlinkName' => 'Frn',
        'displayName' => 'FRN Gateway',
        'svxlinkID' => '7',
        'moduleOptions' => '',

        'desc' => 'The FRN module is used to connect to Free Radio Network (FRN) servers. This service is more common in Europe. Check the legality in your country prior to use.',
        'settings' => 'true',
        'dtmf' => 'true',

		'tempSubCommands' => [
			'#' => 'Exit FRN Gateway',
		],
	],

    '9' => [
        'moduleKey' => '9',
        'moduleEnabled' => '1',
        'svxlinkName' => 'MetarInfo',
        'displayName' => 'METAR',
        'svxlinkID' => '8',
        'moduleOptions' => '',

        'desc' => 'The METAR information module is used to get METAR (weather) information from ICAO locations, usually airports, and announces them over the air. You can request a METAR from an airport of your interest.',
        'settings' => 'true',
        'dtmf' => 'true',

        'tempSubCommands' => [
	        '0#' => 'Help general',
	        '01#' => 'Enumeration of configured weather stations',
	        '1#' => '1. configured weather station',
	        '2#' => '2. configured weather station',
	        '3#' => '3. configured weather station',
	        'Xx#' => _M('Xx. Weather station'),
        ],
    ],

    '10' => [
        'moduleKey' => '10',
        'moduleEnabled' => '1',
        'svxlinkName' => 'TxFan',
        'displayName' => 'TX Cooling Fan',
        'svxlinkID' => '',
        'moduleOptions' => '',

        'type' => 'daemon',
        'desc' => 'This module allows control of a cooling fan via a GPIO pin. It follows the PTT activity or comes on after a delay.',
        'settings' => 'true',
    ],

];

################################################################################
?>