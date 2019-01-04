<?php
/* Author Test cases generated on: 2010-05-18 12:05:57 : 1274165577*/
App::import('Model', 'Author');

class AuthorTestCase extends CakeTestCase {
	var $fixtures = array('app.author', 'app.user', 'app.message', 'app.category', 'app.package', 'app.log', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.tag', 'app.messages_tag');

	function startTest() {
		$this->Author =& ClassRegistry::init('Author');
	}

	function endTest() {
		unset($this->Author);
		ClassRegistry::flush();
	}

}
?>