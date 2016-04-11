#!/bin/bash
UP=$(pgrep mysql | wc -l);
if [ "$UP" -ne 2 ];
then
echo "MySQL is down.";
sudo service mysqld start
mail -s "mysql restarted" user@gmail.com <<< "message"

else
echo "All is well.";

fi