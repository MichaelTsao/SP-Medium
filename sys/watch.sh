#!/bin/sh

function run()
{
        num=`ps -ef|grep $1|grep -v grep|wc -l|awk '{print $1}'`
        if [ $num -lt $2 ]; then
                diff=`expr $2 - $num`;
                i=1;
                while [ $i -le $diff ];
                do
                        /usr/local/php5/bin/php $1 > /dev/null &
                        i=`expr $i + 1`;
                done;
        fi
}

trap "" SIGHUP SIGPIPE SIGTERM
path=`pwd`/
while [ 1 ]
do
	run $path"mo_kernel.php" 1

	run $path"ivr_kernel.php" 1

	run $path"sub_kernel.php" 1
	
	run $path"apay_kernel.php" 1
	
	run $path"mo_sender.php" 50
	
	run $path"ivr_sender.php" 10
	
	run $path"sub_sender.php" 10
	
	run $path"apay_sender.php" 10

	run $path"mt_kernel.php" 1

	run $path"sr_sender.php" 50

	run $path"db.php" 1

	run $path"wait.php" 100

	sleep 5;
done
