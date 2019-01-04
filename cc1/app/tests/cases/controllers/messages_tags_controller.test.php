<?php
/* MessagesTags Test cases generated on: 2010-05-18 12:05:12 : 1274165592*/
App::import('Controller', 'MessagesTags');

class TestMessagesTagsController extends MessagesTagsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class MessagesTagsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.messages_tag', 'app.tag', 'app.message', 'app.category', 'app.package', 'app.log', 'app.user', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.author');

	function startTest() {
		$this->MessagesTags =& new TestMessagesTagsController();
		$this->MessagesTags->constructClasses();
	}

	function endTest() {
		unset($this->MessagesTags);
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