<?php
/* CategoriesPackage Fixture generated on: 2010-05-18 12:05:42 : 1274165322 */
class CategoriesPackageFixture extends CakeTestFixture {
	var $name = 'CategoriesPackage';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'package_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'category_id' => 1,
			'package_id' => 1,
			'created' => '2010-05-18 12:18:42',
			'modified' => '2010-05-18 12:18:42'
		),
	);
}
?>