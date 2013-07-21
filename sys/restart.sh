#!/bin/sh

function kill_name()
{
	for pid in `ps -ef|grep $1|grep -v grep|awk '{print $2}'`
	do
		kill -9 $pid
	done
}

path=`pwd`/

kill_name $path"watch.sh"

for i in `ls *.php`
do
	kill_name $path$i
done

chmod 755 ./watch.sh
$path"watch.sh" &
