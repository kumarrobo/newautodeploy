<?php
/* Logs Test cases generated on: 2010-05-18 12:05:38 : 1274167058*/
App::import('Controller', 'Logs');

class TestLogsController extends LogsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class LogsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.log', 'app.user', 'app.group', 'app.author', 'app.message', 'app.category', 'app.package', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.tag', 'app.messages_tag', 'app.comment');

	function startTest() {
		$this->Logs =& new TestLogsController();
		$this->Logs->constructClasses();
	}

	function endTest() {
		unset($this->Logs);
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