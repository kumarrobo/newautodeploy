<?php
/* Tag Test cases generated on: 2010-05-18 12:05:43 : 1274165623*/
App::import('Model', 'Tag');

class TagTestCase extends CakeTestCase {
	var $fixtures = array('app.tag', 'app.message', 'app.category', 'app.package', 'app.log', 'app.user', 'app.transaction', 'app.categories_package', 'app.packages_user', 'app.author', 'app.messages_tag');

	function startTest() {
		$this->Tag =& ClassRegistry::init('Tag');
	}

	function endTest() {
		unset($this->Tag);
		ClassRegistry::flush();
	}

}
?>