<?php
/* MessagesTag Fixture generated on: 2010-05-18 12:05:45 : 1274165385 */
class MessagesTagFixture extends CakeTestFixture {
	var $name = 'MessagesTag';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tag_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'message_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'tag_id' => 1,
			'message_id' => 1
		),
	);
}
?>