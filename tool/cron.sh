#sp status matching
*/10 * * * * cd /home/sp/cron/; /usr/local/php5/bin/php matching.php > /dev/null 2>&1

#sp set cache
*/10 * * * * cd /home/sp/cron/; /usr/local/php5/bin/php set_cache.php > /dev/null 2>&1

#sp service checker
* * * * * cd /home/sp/cron/; /usr/local/php5/bin/php checker.php > /dev/null 2>&1

#sp set adjust data
*/10 * * * * cd /home/sp/cron/; /usr/local/php5/bin/php adjust.php > /dev/null 2>&1

#sp make stat data
1 1 */1 * * cd /home/sp/cron/; /usr/local/php5/bin/php stat.php > /dev/null 2>&1
