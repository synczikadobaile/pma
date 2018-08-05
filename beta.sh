#!/bin/sh
./ss 80 -a $[(RANDOM %254 )] -i venet0:0 -s 10
./zmeu bios.txt vuln.txt cgi 1000
./boss

