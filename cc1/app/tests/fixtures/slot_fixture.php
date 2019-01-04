<?php
/* Slot Fixture generated on: 2010-05-18 12:05:35 : 1274165615 */
class SlotFixture extends CakeTestFixture {
	var $name = 'Slot';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'start' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'end' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'start' => '12:23:35',
			'end' => '12:23:35'
		),
	);
}
?>