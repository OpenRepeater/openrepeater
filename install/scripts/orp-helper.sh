#!/bin/bash
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

