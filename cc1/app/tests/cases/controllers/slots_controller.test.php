<?php
/* Slots Test cases generated on: 2010-05-18 12:05:37 : 1274165617*/
App::import('Controller', 'Slots');

class TestSlotsController extends SlotsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SlotsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.slot', 'app.packages_user', 'app.package', 'app.log', 'app.user', 'app.message', 'app.category', 'app.categories_package', 'app.author', 'app.transaction', 'app.tag', 'app.messages_tag');

	function startTest() {
		$this->Slots =& new TestSlotsController();
		$this->Slots->constructClasses();
	}

	function endTest() {
		unset($this->Slots);
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