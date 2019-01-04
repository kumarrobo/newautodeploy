<?php
/* Transaction Test cases generated on: 2010-05-18 12:05:52 : 1274165632*/
App::import('Model', 'Transaction');

class TransactionTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction', 'app.user', 'app.package', 'app.log', 'app.message', 'app.category', 'app.categories_package', 'app.author', 'app.tag', 'app.messages_tag', 'app.packages_user');

	function startTest() {
		$this->Transaction =& ClassRegistry::init('Transaction');
	}

	function endTest() {
		unset($this->Transaction);
		ClassRegistry::flush();
	}

}
?>