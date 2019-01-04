<?php
/* CategoriesPackages Test cases generated on: 2010-05-18 12:05:43 : 1274165323*/
App::import('Controller', 'CategoriesPackages');

class TestCategoriesPackagesController extends CategoriesPackagesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class CategoriesPackagesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.categories_package', 'app.category', 'app.message', 'app.package');

	function startTest() {
		$this->CategoriesPackages =& new TestCategoriesPackagesController();
		$this->CategoriesPackages->constructClasses();
	}

	function endTest() {
		unset($this->CategoriesPackages);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>