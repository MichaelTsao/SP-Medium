cat ../log/mo_client_20110926.log | awk '{print substr($0,17,5)}' | sort | uniq -c
