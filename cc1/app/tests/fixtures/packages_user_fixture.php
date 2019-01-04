<?php
/* PackagesUser Fixture generated on: 2010-05-18 12:05:20 : 1274165420 */
class PackagesUserFixture extends CakeTestFixture {
	var $name = 'PackagesUser';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'package_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'review' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'slot_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'start' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'end' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'package_id' => 1,
			'user_id' => 1,
			'review' => 1,
			'active' => 1,
			'slot_id' => 1,
			'start' => '2010-05-18 12:20:20',
			'end' => '2010-05-18 12:20:20'
		),
	);
}
?>