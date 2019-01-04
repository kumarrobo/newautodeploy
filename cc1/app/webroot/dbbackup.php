<?php
include("/var/www/html/shops/app/config/bootstrap.php");

$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mail('tadka@mindsarray.com','VMN: DB connection refused','Reason: '.mysql_error(),$headers));
mysql_select_db(DB_DB);

echo "Taking the backup\n";

exec("mysqldump -h".DB_HOST." -u".DB_USER." -p".DB_PASS." ".DB_DB." > /mnt/db/shop-backup.sql");
exec('tar -zcf /mnt/db/shop-backup.tar.gz /mnt/db/shop-backup.sql');

echo "Taken the backup\n";

$result = mysql_query('SHOW TABLES');
$tables = array();
while($row = mysql_fetch_row($result))
{
	$tables[] = $row[0];
}

foreach($tables as $table){
	echo "Altering Table $table\n";
	mysql_query("ALTER TABLE $table ENGINE = INNODB");
}

exec("mysqldump shops | mysql --host=pay1.coyipz0wacld.us-east-1.rds.amazonaws.com --user=root --s1smstadka shops");

?>