<?php
/* Comments Test cases generated on: 2010-05-18 12:05:52 : 1274165332*/
App::import('Controller', 'Comments');

class TestCommentsController extends CommentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class CommentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.comment', 'app.user');

	function startTest() {
		$this->Comments =& new TestCommentsController();
		$this->Comments->constructClasses();
	}

	function endTest() {
		unset($this->Comments);
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