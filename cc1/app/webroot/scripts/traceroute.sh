#!/bin/bash
echo "--------------" >> /mnt/logs/traceroute.log
echo $2
date  >> /mnt/logs/traceroute.log
traceroute $1 >> /mnt/logs/traceroute.log