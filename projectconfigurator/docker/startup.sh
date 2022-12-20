#!/bin/sh

/usr/src/app/projectconfigurator/docker/wait-for-it.sh ngrok:4551 -s -t 10000
/usr/src/app/projectconfigurator/docker/wait-for-it.sh symfony:80 -s -t 10000 
python /usr/src/app/projectconfigurator/main.py
