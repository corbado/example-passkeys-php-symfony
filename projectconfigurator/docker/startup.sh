#!/bin/sh

/usr/src/app/projectconfigurator/docker/wait-for-it.sh ngrok:4551 -s -t 100000
/usr/src/app/projectconfigurator/docker/wait-for-it.sh symfony:80 -s -t 100000 
python /usr/src/app/projectconfigurator/main.py



