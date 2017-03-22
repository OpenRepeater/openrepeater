<?php
# Copyright ©2017 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php

$svx_logic = '###############################################################################
# Main Simplex Logic "' . trim($ports['1']['portLabel']) . '"
###############################################################################
[SimplexLogic]
TYPE=Simplex
RX=Rx1
TX=Tx1
'.$modulesList.'
CALLSIGN='.$settings['callSign'].'
SHORT_IDENT_INTERVAL='.$settings['ID_Short_IntervalMin'].'
LONG_IDENT_INTERVAL='.$settings['ID_Long_IntervalMin'].'
EVENT_HANDLER=/usr/share/svxlink/events.tcl
DEFAULT_LANG=en_US
RGR_SOUND_DELAY=1
REPORT_CTCSS='.$settings['rxTone'].'
TX_CTCSS=ALWAYS
MACROS=Macros
FX_GAIN_NORMAL=0
FX_GAIN_LOW=-12
IDLE_TIMEOUT=1
OPEN_ON_SQL=1
OPEN_SQL_FLANK=OPEN
IDLE_SOUND_INTERVAL=0

';

?>
