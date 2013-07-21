cat ../log/sr_debug_20110830.log|awk -F'|' '{if(NF==9 && $3="client:39"){c++;d+=substr($9, 12)}}END{print d/c}'
