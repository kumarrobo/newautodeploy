<?php
/* Log Test cases generated on: 2010-05-18 12:05:10 : 1274165350*/
App::import('Model', 'Log');

class LogTestCase extends CakeTestCase {
	var $fixtures = array('app.log', 'app.user', 'app.package', 'app.message', 'app.transaction');

	function startTest() {
		$this->Log =& ClassRegistry::init('Log');
	}

	function endTest() {
		unset($this->Log);
		ClassRegistry::flush();
	}

}
?>