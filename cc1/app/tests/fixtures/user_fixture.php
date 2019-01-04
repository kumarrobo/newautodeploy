<?php
/* User Fixture generated on: 2010-05-18 12:05:02 : 1274165642 */
class UserFixture extends CakeTestFixture {
	var $name = 'User';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'mobile' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'unique'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
		'balance' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40),
		'dob' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'gender' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'mobile' => array('column' => 'mobile', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'mobile' => 1,
			'password' => 'Lorem ipsum dolor sit amet',
			'balance' => 1,
			'group_id' => 1,
			'email' => 'Lorem ipsum dolor sit amet',
			'dob' => '2010-05-18',
			'gender' => 1,
			'created' => '2010-05-18 12:24:02',
			'modified' => '2010-05-18 12:24:02'
		),
	);
}
?>