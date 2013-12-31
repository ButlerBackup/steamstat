SteamStat
=========

Simple tool for fetching and parsing Steam stats from http://store.steampowered.com/stats/

Based on https://github.com/bkaradzic/steamstats

+ Language : PHP

+ Output :JSON

##Setup
1. Clone this repo or download `steamstat.php`
2. Run file

##Cron
Alow cron to run this file every hour. Stats will be appended into its respective file.

`0 */1 * * * php /home/root/steamstat.php`
