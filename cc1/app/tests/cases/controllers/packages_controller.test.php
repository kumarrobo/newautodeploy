<?php
/* Packages Test cases generated on: 2010-05-18 12:05:19 : 1274165599*/
App::import('Controller', 'Packages');

class TestPackagesController extends PackagesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PackagesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.package', 'app.log', 'app.user', 'app.message', 'app.category', 'app.categories_package', 'app.author', 'app.transaction', 'app.tag', 'app.messages_tag', 'app.packages_user');

	function startTest() {
		$this->Packages =& new TestPackagesController();
		$this->Packages->constructClasses();
	}

	function endTest() {
		unset($this->Packages);
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