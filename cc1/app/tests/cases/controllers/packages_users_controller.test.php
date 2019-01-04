<?php
/* PackagesUsers Test cases generated on: 2010-05-18 12:05:26 : 1274165606*/
App::import('Controller', 'PackagesUsers');

class TestPackagesUsersController extends PackagesUsersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PackagesUsersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.packages_user', 'app.package', 'app.log', 'app.user', 'app.message', 'app.category', 'app.categories_package', 'app.author', 'app.transaction', 'app.tag', 'app.messages_tag', 'app.slot');

	function startTest() {
		$this->PackagesUsers =& new TestPackagesUsersController();
		$this->PackagesUsers->constructClasses();
	}

	function endTest() {
		unset($this->PackagesUsers);
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