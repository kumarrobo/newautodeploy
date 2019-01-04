<?php
/* User Test cases generated on: 2010-05-18 12:05:02 : 1274165642*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.group', 'app.author', 'app.message', 'app.category', 'app.package', 'app.log', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.tag', 'app.messages_tag', 'app.comment');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function endTest() {
		unset($this->User);
		ClassRegistry::flush();
	}

}
?>