set -xe

# Database
mkdir -p /var/run/mysqld
chown -R mysql:mysql /var/run/mysqld
chmod -R 755         /var/run/mysqld
service mariadb start
mysql < /trunk/install/db.sql
mysql -e "INSERT INTO jol.privilege ( user_id, rightstr ) VALUES ('admin','administrator');"

# EOJ basic file system
useradd -m -u 1536 judge
mkdir -p /home/judge/etc
mkdir -p /home/judge/data
mkdir -p /home/judge/log
mkdir -p /home/judge/backup
mkdir -p /var/log/eoj
mv /trunk /home/judge/eoj
chmod -R 755 /home/judge/etc
chmod -R 755 /home/judge/backup
chown -R www-data:www-data /home/judge/data
chown -R www-data:www-data /home/judge/eoj/web

# Judge daemon and client
sh /home/judge/eoj/core/make.sh
pip3 install copydetect

# Adjust system configuration
CPU=`grep "cpu cores" /proc/cpuinfo |head -1|awk '{print $4}'`
USERNAME="jol"
PASSWORD=`cat /dev/urandom | tr -dc A-Za-z0-9 | head -c 8`
mysql -e "CREATE USER '$USERNAME'@'%' IDENTIFIED BY '$PASSWORD';"
mysql -e "GRANT ALL PRIVILEGES ON *.* TO '$USERNAME'@'%' IDENTIFIED BY '$PASSWORD' WITH GRANT OPTION;"
cp /home/judge/eoj/web/include/db_info.default.php /home/judge/eoj/web/include/db_info.inc.php
cp /home/judge/eoj/install/java0.policy  /home/judge/etc/
cp /home/judge/eoj/install/judge.conf    /home/judge/etc/
cp /home/judge/eoj/install/judged        /etc/init.d/judged
cp /home/judge/eoj/install/default.conf  /etc/nginx/sites-available/default
sed -i "s#DB_USER[[:space:]]*=[[:space:]]*\"jol\"#DB_USER=\"$USERNAME\"#g"       /home/judge/eoj/web/include/db_info.inc.php
sed -i "s#DB_PASS[[:space:]]*=[[:space:]]*\"\"#DB_PASS=\"$PASSWORD\"#g"          /home/judge/eoj/web/include/db_info.inc.php
sed -i "s#OJ_USER_NAME[[:space:]]*=[[:space:]]*root#OJ_USER_NAME=$USERNAME#g"    /home/judge/etc/judge.conf
sed -i "s#OJ_PASSWORD[[:space:]]*=[[:space:]]*root#OJ_PASSWORD=$PASSWORD#g"      /home/judge/etc/judge.conf
sed -i "s#OJ_RUNNING[[:space:]]*=[[:space:]]*1#OJ_RUNNING=$CPU#g"                /home/judge/etc/judge.conf
sed -i "s#OJ_INTERNAL_CLIENT[[:space:]]*=[[:space:]]*0#OJ_INTERNAL_CLIENT=1#g"   /home/judge/etc/judge.conf
sed -i "s#127.0.0.1:9000#unix:/var/run/php/php8.1-fpm.sock#g"    /etc/nginx/sites-available/default
chmod a+x /etc/init.d/judged
update-rc.d judged defaults
