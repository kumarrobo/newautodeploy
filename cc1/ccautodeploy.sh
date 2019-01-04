#!/bin/sh
#live process for standalone server
cd  /var/www/html/cc.pay1.in/configdata
git remote set-url origin git@bitbucket.org:vibhasmindsarray/allconfig.git
git fetch --all ; git checkout ccconf ; git pull origin ccconf
tag=`cat /var/www/html/cc.pay1.in/configdata/version.txt`
echo "$tag"
cd /var/www/html/cc.pay1.in/git/
git remote set-url origin git@bitbucket.org:vibhasmindsarray/shops.git
git fetch --tags
git checkout tags/$tag ; git pull origin tag "$tag"
cd  /var/www/html/cc.pay1.in
version=`cat /var/www/html/cc.pay1.in/configdata/version.txt`
mkdir "$version"
rsync -avz git/ "$version"/
rsync -avz --exclude "app/config/server_ip.conf" --exclude "app/webroot/pub_keys/bank_prod.key" --exclude "app/webroot/pub_keys/secret_cp.key"  git/ $version/ && chown -R apache:shops "$version"/

cd  /var/www/html/cc.pay1.in
chown -R apache:shops "$version"/

cd /var/www/html/cc.pay1.in/"$version"

rsync -avz --exclude "bank_prod.key" --exclude "secret_cp.key" /var/www/html/cc.pay1.in/configdata/* /var/www/html/cc.pay1.in/"$version"/app/config/
rsync -avz /var/www/html/cc.pay1.in/configdata/tmp/* /var/www/html/cc.pay1.in/"$version"/app/tmp/
rsync -avz /var/www/html/cc.pay1.in/configdata/*.key /var/www/html/cc.pay1.in/"$version"/app/webroot/pub_keys/


cd /var/www/html/cc.pay1.in/
chown -R apache:shops "$version"
unlink live && ln -s "$version" live
