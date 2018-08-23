#!/bin/bash

#This is seperated because of the time it would take to run this command on some devices.
#This will get all the routes and from each device in the device list and prepare
#it for the free-ip.sh script to extract the necessary information. You can run this once
#and then run the free-ip script as many times for as many blocks as you want.

now=$(date +%T.%N)
echo -e "\n"
echo "START: $now"
echo "Make sure the .cloginrc file is in the same directory you are running this script."
echo -e "This really should only be ran once. The free-ip.sh script can be ran multiple times\nwith different /16 blocks"

mkdir route > /dev/null 2>&1

#Collect routing tables, add device prefix, then format further for later
while read devices
       do (
               /usr/local/rancid/bin/clogin -c "sh ip route" $devices > route/$devices-route-raw.txt
               /usr/local/rancid/bin/clogin -c "sh int desc" $devices > route/$devices-desc-raw.txt
               sed -e "s/^/$devices~/" route/$devices-route-raw.txt > route/$devices-route.txt
               sed -e "s/^/$devices~/" route/$devices-desc-raw.txt > route/$devices-desc.txt
       )
done < device-list

#Clean and format all the collected data for matching
cat route/*-route.txt |grep "~C \|~S \|~L "|tr -s " "|sed -e "s/[[:blank:]]//" -e "s/is directly connected//" -e "/local/d" -e "/Codes/d" -e "s/via /via-/" -e "s/1[[:digit:]]w[[:digit:]]d//" -e "s/,//g" -e "s/[[:digit:]]w[[:digit:]]d//" -e "s/\[[[:digit:]]\/[[:digit:]]\]//" |tr -s " "|sed -e "s/ /~/g"|sed -e "0,/~C/ s/~C/~C~/" -e "0,/~S/ s/~S/~S~/" -e "0,/~L/ s/~L/~L~/" -e "s/~L~oopback/~Loopback/" -e "s/~\[[0-9]*\/[0-9]*\]//"> route/cleaned-up-routes.txt
cat route/*-desc.txt| grep " up \| down "| tr -s " "|sed -e "s/admin//" -e "s/down down//" -e "s/up up//"| tr -s " "|sed -e "s/[[:blank:]]/~/"|grep -v '~\s*$' > route/cleaned-up-descs.txt

#Cleanup unnecessary files
rm route/*-raw.txt route/*-desc.txt route/*-route.txt

#Print STOP time.
now=$(date +%T.%N)
echo -e "\n"
echo "STOP: $now"
echo -e "\n"
