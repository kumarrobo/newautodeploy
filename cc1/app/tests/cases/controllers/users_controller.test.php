<?php
/* Users Test cases generated on: 2010-05-18 12:05:25 : 1274167045*/
App::import('Controller', 'Users');

class TestUsersController extends UsersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UsersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.group', 'app.author', 'app.message', 'app.category', 'app.package', 'app.log', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.tag', 'app.messages_tag', 'app.comment');

	function startTest() {
		$this->Users =& new TestUsersController();
		$this->Users->constructClasses();
	}

	function endTest() {
		unset($this->Users);
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