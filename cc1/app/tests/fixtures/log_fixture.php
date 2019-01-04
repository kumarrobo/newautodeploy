<?php
/* Log Fixture generated on: 2010-05-18 12:05:10 : 1274165350 */
class LogFixture extends CakeTestFixture {
	var $name = 'Log';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'package_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'message_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'transaction_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'mobile' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'report' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'timestamp' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'package_id' => 1,
			'message_id' => 1,
			'transaction_id' => 1,
			'mobile' => 1,
			'report' => 1,
			'timestamp' => '1274165350'
		),
	);
}
?>