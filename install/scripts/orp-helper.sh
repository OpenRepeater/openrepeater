#!/bin/bash
# Debian like server start/stop script/restart/status/enable/disable, svxlink
# Copyright (c) 2017 - Richard, Richard Neese <kb3vgw@gmail.com>
# Licended under GPL v2 or later

SERVICE=svxlink

disable() {
sudo service $SERVICE stop
sudo systemctl disable svxlink.service
}

enable() {
sudo systemctl enable svxlink.service
sudo service $SERVICE start
}

start() {
sudo service $SERVICE start
}

restart() {
sudo service $SERVICE restart
}

stop() {
sudo service $SERVICE stop
}

*() {
echo "Usage: $0 {start|stop|restart|enable|disable|status}"
exit 2
}

status() {
sudo service $SERVICE status
}
