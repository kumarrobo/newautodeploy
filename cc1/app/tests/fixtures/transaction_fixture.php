<?php
/* Transaction Fixture generated on: 2010-05-18 12:05:52 : 1274165632 */
class TransactionFixture extends CakeTestFixture {
	var $name = 'Transaction';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'package_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'message_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'amount' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
		'type' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
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
			'amount' => 1,
			'type' => 1,
			'timestamp' => '1274165632'
		),
	);
}
?>