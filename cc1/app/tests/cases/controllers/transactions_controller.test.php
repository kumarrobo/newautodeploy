<?php
/* Transactions Test cases generated on: 2010-05-18 12:05:53 : 1274165633*/
App::import('Controller', 'Transactions');

class TestTransactionsController extends TransactionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TransactionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction', 'app.user', 'app.package', 'app.log', 'app.message', 'app.category', 'app.categories_package', 'app.author', 'app.tag', 'app.messages_tag', 'app.packages_user');

	function startTest() {
		$this->Transactions =& new TestTransactionsController();
		$this->Transactions->constructClasses();
	}

	function endTest() {
		unset($this->Transactions);
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