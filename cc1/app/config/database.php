<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behaviour ofCake.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * In this file you set up your database connection details.
 *
 * @package       cake
 * @subpackage    cake.config
 */
/**
 * Database configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * driver => The name of a supported driver; valid options are as follows:
 *		mysql 		- MySQL 4 & 5,
 *		mysqli 		- MySQL 4 & 5 Improved Interface (PHP5 only),
 *		sqlite		- SQLite (PHP5 only),
 *		postgres	- PostgreSQL 7 and higher,
 *		mssql		- Microsoft SQL Server 2000 and higher,
 *		db2			- IBM DB2, Cloudscape, and Apache Derby (http://php.net/ibm-db2)
 *		oracle		- Oracle 8 and higher
 *		firebird	- Firebird/Interbase
 *		sybase		- Sybase ASE
 *		adodb-[drivername]	- ADOdb interface wrapper (see below),
 *		odbc		- ODBC DBO driver
 *
 * You can add custom database drivers (or override existing drivers) by adding the
 * appropriate file to app/models/datasources/dbo.  Drivers should be named 'dbo_x.php',
 * where 'x' is the name of the database.
 *
 * persistent => true / false
 * Determines whether or not the database should use a persistent connection
 *
 * connect =>
 * ADOdb set the connect to one of these
 *	(http://phplens.com/adodb/supported.databases.html) and
 *	append it '|p' for persistent connection. (mssql|p for example, or just mssql for not persistent)
 * For all other databases, this setting is deprecated.
 *
 * host =>
 * the host you connect to the database.  To add a socket or port number, use 'port' => #
 *
 * prefix =>
 * Uses the given prefix for all the tables in this database.  This setting can be overridden
 * on a per-table basis with the Model::$tablePrefix property.
 *
 * schema =>
 * For Postgres and DB2, specifies which schema you would like to use the tables in. Postgres defaults to
 * 'public', DB2 defaults to empty.
 *
 * encoding =>
 * For MySQL, MySQLi, Postgres and DB2, specifies the character encoding to use when connecting to the
 * database.  Uses database default.
 *
 */
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => true,
		'host' => DB_HOST,
		'login' => 'panel_user',
		'password' => 'HFQx5eCG6HebzeDW',
		'database' => 'shops',
		'prefix' => '',
	);

	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'shops',
		'prefix' => '',
	);

	 var $other = array(
		'driver' => 'mysql',
		'persistent' => true,
		'host' => 'prod-recharge-s1.coyipz0wacld.us-east-1.rds.amazonaws.com',
		'login' => 'slave_user',
		'password' => 'XQ7cWzwtD5YBBqB6',
		'database' => 'shops',
		'prefix' => '',
	);



	var $c2db = array(
		'driver' => 'mysql',
		'persistent' => true,
		'host' => 'prod-pay1-services.coyipz0wacld.us-east-1.rds.amazonaws.com',
		'login' => 'c2db2c',
		'password' => 'c2dDBpwdselect',
		'database' => 'c2d',
		'prefix' => '',
	);

	var $dmt = array(
                'driver' => 'mysql',
                'persistent' => true,
                'host' => 'prod-pay1-services.coyipz0wacld.us-east-1.rds.amazonaws.com',
                'login' => 'remit',
                'password' => 'Dmt#r$m1t',
                'database' => 'dmt',
                'prefix' => ''
        );

	var $eko = array(
                'driver' => 'mysql',
                'persistent' => true,
                'host' => 'prod-pay1-services.ci1xrrgancwk.ap-south-1.rds.amazonaws.com',
                'login' => 'readonly',
                'password' => 'readonlyaccessccstaging',
                'database' => 'pay1eko',
                'prefix' => ''
        );

	var $limit = array(
                'driver' => 'mysql',
                'persistent' => true,
                'host' =>  DB_HOST,
                'login' => 'dev_pay1',
                'password' => 'DEV@PASSWD',
                'database' => 'limits',
                'prefix' => '',
        );


#var $smartpay = array(
#'driver' => 'mysql',
#'persistent' => false,
#'host'=> 'prod-pay1-services.coyipz0wacld.us-east-1.rds.amazonaws.com',
#'login'=> 'smartpay',
#'password'=> 'P@y1$mart',
#'database' => 'smartpay',
#'prefix' => '',
var $smartpay = array(
'driver' => 'mysql',
'persistent' => true,
'host'=> 'prod-pay1-services.ci1xrrgancwk.ap-south-1.rds.amazonaws.com',
'login'=> 'pay1digi',
'password'=> 'D1hc3g6d9Gt3Xd',
'database' => 'pay1digi',
'prefix' => '',


);

var $microfinance = array(
                'driver' => 'mysql',
                'persistent' => true,
                'host'=> 'prod-pay1-services.coyipz0wacld.us-east-1.rds.amazonaws.com',
                'login'=> 'microfin_user',
                'password'=> '3D9FXbxGeHFanGDh',
                'database' => 'microfinance2',
                'prefix' => ''

  );

 var $insurance = array(
       'driver' => 'mysql',
       'persistent' => true,
       'host' => 'prod-pay1-services.coyipz0wacld.us-east-1.rds.amazonaws.com',
       'login' => 'insurance',
       'password' => 'NoC6X43M0X1',
       'database' => 'insurance',
       'prefix' => '',
   );

var $ekonew = array(
        'driver' => 'mysql',
        'persistent' => false,
        'host' => 'prod-pay1-services.ci1xrrgancwk.ap-south-1.rds.amazonaws.com',
        'login' => 'readonly',
        'password' => 'readonlyaccessccstaging',
        'database' => 'dmtdb',
        'prefix' => '',
    );


        var $travel = array(
       'driver' => 'mysql',
       'persistent' => false,
       'host' => 'prod-pay1-services.coyipz0wacld.us-east-1.rds.amazonaws.com',
       'login' => 'travel_read',
       'password' => 'T3V2lR8A3Dq',
       'database' => 'travel',
       'prefix' => '',
   );    


}

