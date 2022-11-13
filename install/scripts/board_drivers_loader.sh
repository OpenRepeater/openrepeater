#!/bin/bash
FILE_PATH_PRECONFIG=/usr/sbin/svxlink_rpi_preconfig
FILE_PATH_GPIO_UP=/usr/sbin/svxlink_gpio_up
FILE_PATH_SERVICE=/lib/systemd/system/svxlink_gpio_setup.service
Pattern="Type=oneshot"
Var="ExecStart=$FILE_PATH_PRECONFIG"

function load_drivers_pi_repeater_1x_v1 {
	# empty the file
	echo "" > "$FILE_PATH_PRECONFIG"
	# put in the desired contents
	cat >> "$FILE_PATH_PRECONFIG" <<- DELIM
		#! /bin/bash
		#Configuration for Pi-Repeater-1X Version 1
		dtoverlay fe-pi-audio
		dtoverlay mcp23017 addr=0x20 gpiopin=12
		dtoverlay mcp3008 spi0-0-present spi0-0-speed=1000000
	DELIM
}

function load_drivers_pi_repeater_2x_v3 {
	# empty the file
	echo "" > "$FILE_PATH_PRECONFIG"
	# put in the desired contents
	cat >> "$FILE_PATH_PRECONFIG" <<- DELIM
		#! /bin/bash
		#Configuration for Pi-Repeater-2X Version 3
		dtoverlay fe-pi-audio
		dtoverlay mcp23017 addr=0x20 gpiopin=12
		dtoverlay mcp3008 spi0-0-present spi0-0-speed=1000000
	DELIM
}

function load_drivers_pi_repeater_4x_v2 {
	# empty the file
	echo "" > "$FILE_PATH_PRECONFIG"
	# put in the desired contents
	cat >> "$FILE_PATH_PRECONFIG" <<- DELIM
		#! /bin/bash
		#Configuration for Pi-Repeater-4X Version 2
		dtoverlay mcp23017 addr=0x27 gpiopin=23
		dtoverlay mcp23017 addr=0x26 gpiopin=25
		dtoverlay sc16is752-i2c int_pin=24 addr=0x4D xtal=3686400
		
		dtoverlay=ads1015,
		dtparam=cha_enable=true
		dtparam=chb_enable=true
		dtparam=chc_enable=true
		dtparam=chd_enable=true
		dtparam=addr=0x48
		dtparam=cha_cfg=4
		dtparam=chb_cfg=4
		dtparam=chc_cfg=4
		dtparam=chd_cfg=4
		dtparam=cha_datarate=4
		dtparam=chb_datarate=4
		dtparam=chc_datarate=4
		dtparam=chd_datarate=4
		dtparam=cha_gain=2
		dtparam=cha_gain=2
		dtparam=cha_gain=2
		dtparam=cha_gain=2

		dtoverlay=ads1015,
		dtparam=cha_enable=true
		dtparam=chb_enable=true
		dtparam=chc_enable=true
		dtparam=chd_enable=true
		dtparam=addr=0x49
		dtparam=cha_cfg=4
		dtparam=chb_cfg=4
		dtparam=chc_cfg=4
		dtparam=chd_cfg=4
		dtparam=cha_datarate=4
		dtparam=chb_datarate=4
		dtparam=chc_datarate=4
		dtparam=chd_datarate=4
		dtparam=cha_gain=2
		dtparam=cha_gain=2
		dtparam=cha_gain=2
		dtparam=cha_gain=2

	DELIM
}

function prepare_svxlink_gpio_up {
	# edit /lib/systemd/system/svxlink_gpio_setup.service to insert the new dependency
	# for the hardware preconfig script
	sed -i "/${Pattern}/a${Var}" $FILE_PATH_SERVICE
}
load_drivers_pi_repeater_4x_v2