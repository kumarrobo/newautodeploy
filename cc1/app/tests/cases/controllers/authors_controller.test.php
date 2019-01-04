<?php
/* Authors Test cases generated on: 2010-05-18 12:05:59 : 1274165579*/
App::import('Controller', 'Authors');

class TestAuthorsController extends AuthorsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AuthorsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.author', 'app.user', 'app.message', 'app.category', 'app.package', 'app.log', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.tag', 'app.messages_tag');

	function startTest() {
		$this->Authors =& new TestAuthorsController();
		$this->Authors->constructClasses();
	}

	function endTest() {
		unset($this->Authors);
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