SteamStat
=========

Simple tool for fetching and parsing Steam stats from http://store.steampowered.com/stats/

Based on https://github.com/bkaradzic/steamstats

+ Language : PHP

+ Output : JSON

+ Sample : https://github.com/shaunidiot/steamstat/blob/master/stats/2013/12/2013-12-31.json

##Setup
1. Clone this repo or just download `steamstat.php`
2. Run file

##Cron
Alow cron to run this file every hour. Stats will be appended into its respective file.

`0 */1 * * * php /home/root/steamstat.php`

###steamstat.php
What does this file do exactly?

1. Create folder depending on year in `stats/` and month in `stats/YEAR/`
2. Grab and parse data from http://store.steampowered.com/stats/
3. Print data to respective folder and file.