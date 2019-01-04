<?php 
$table = $_GET['table'];
$query = "mysqldump --single-transaction --host=pay1slave1.coyipz0wacld.us-east-1.rds.amazonaws.com --user=dipali --password='dipali@pay1' shops $table | mysql --host=db-optimization.coyipz0wacld.us-east-1.rds.amazonaws.com --user=root --password=vibhas_pay1 dboptimization";

shell_exec($query);

?>