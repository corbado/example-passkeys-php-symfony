#!/bin/sh

/usr/src/app/projectconfigurator/docker/wait-for-it.sh ngrok:4551 -s -t 1000 -- python /usr/src/app/projectconfigurator/main.py
