<?php
/* CategoriesPackage Test cases generated on: 2010-05-18 12:05:42 : 1274165322*/
App::import('Model', 'CategoriesPackage');

class CategoriesPackageTestCase extends CakeTestCase {
	var $fixtures = array('app.categories_package', 'app.category', 'app.message', 'app.package');

	function startTest() {
		$this->CategoriesPackage =& ClassRegistry::init('CategoriesPackage');
	}

	function endTest() {
		unset($this->CategoriesPackage);
		ClassRegistry::flush();
	}

}
?>