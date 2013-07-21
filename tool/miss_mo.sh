#!/bin/sh
grep 6DFZYG1 mo_client_20130119.log|awk -F'|' '{if($21=="result:")print $6,$19,$4,$2,$12,$8,$1}' > miss_mo 
