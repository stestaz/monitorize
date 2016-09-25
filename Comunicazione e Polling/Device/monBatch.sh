#!/bin/bash

#con questo script estraggo i dati di funzionamento di raspberry
#e li metto su file
DEVICENAME="raspMans"
DIRECTORY="/home/pi/Documents/RaspeinMon/Data/testRes"
FREECPU=$(vmstat | awk 'FNR==3{print $15}')
FREEMEM=$(free -m | grep Mem | awk '{print $4}')
FREEHD=$(df | grep /dev/root | awk '{print $5}' | sed s"/.$//")
TEMP=$(cat /sys/class/thermal/thermal_zone0/temp)
echo "0-"$DEVICENAME > $DIRECTORY
echo "1-"$FREECPU 	>>$DIRECTORY
echo "2-"$FREEMEM 	>>$DIRECTORY
echo "3-"$TEMP		>>$DIRECTORY
echo "4-"$FREEHD	>>$DIRECTORY
