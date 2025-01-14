set -xe 

if [ ! -d /volume/backup ]; then
cp -rp /home/judge/backup  /volume/backup; 
fi 
if [ ! -d /volume/data ]; then  
cp -rp /home/judge/data /volume/data;  
fi 
if [ ! -d /volume/etc ]; then   
cp -rp /home/judge/etc /volume/etc;
fi 
if [ ! -d /volume/eoj ]; then   
cp -rp /home/judge/eoj /volume/eoj;
fi 
if [ ! -d /volume/mysql ]; then 
cp -rp /var/lib/mysql /volume/mysql;   
fi 
chmod 755 /home/judge
chmod -R 755 /volume/etc
chmod -R 755 /volume/backup
find /volume/eoj/web -type d | xargs chmod 755
find /volume/eoj/web -type f | xargs chmod 644
chown -R www-data:www-data /volume/data
chown -R www-data:www-data /volume/eoj/web
chown -R www-data:www-data /var/log/eoj
chown -R mysql:mysql       /volume/mysql
rm -rf /home/judge/backup   
rm -rf /home/judge/data 
rm -rf /home/judge/etc  
rm -rf /home/judge/eoj
rm -rf /var/lib/mysql   
ln -s /volume/backup   /home/judge/backup 
ln -s /volume/data     /home/judge/data   
ln -s /volume/etc      /home/judge/etc
ln -s /volume/eoj      /home/judge/eoj
ln -s /volume/mysql    /var/lib/mysql 

RUNNING=`cat /home/judge/etc/judge.conf | grep OJ_RUNNING`
RUNNING=${RUNNING:11}
for i in `seq 1 $RUNNING`; do
    mkdir -p    /home/judge/run`expr ${i} - 1`;
    chown judge /home/judge/run`expr ${i} - 1`;
done 

ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log

service mariadb    start  
service php8.1-fpm start  
service judged     start
nginx -g "daemon off;"
