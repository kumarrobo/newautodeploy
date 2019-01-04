<?php
/* Messages Test cases generated on: 2010-05-18 12:05:45 : 1274165565*/
App::import('Controller', 'Messages');

class TestMessagesController extends MessagesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class MessagesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.message', 'app.category', 'app.package', 'app.log', 'app.user', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.author', 'app.tag', 'app.messages_tag');

	function startTest() {
		$this->Messages =& new TestMessagesController();
		$this->Messages->constructClasses();
	}

	function endTest() {
		unset($this->Messages);
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