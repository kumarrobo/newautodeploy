<?php
/* Slot Test cases generated on: 2010-05-18 12:05:35 : 1274165615*/
App::import('Model', 'Slot');

class SlotTestCase extends CakeTestCase {
	var $fixtures = array('app.slot', 'app.packages_user', 'app.package', 'app.log', 'app.user', 'app.message', 'app.category', 'app.categories_package', 'app.author', 'app.transaction', 'app.tag', 'app.messages_tag');

	function startTest() {
		$this->Slot =& ClassRegistry::init('Slot');
	}

	function endTest() {
		unset($this->Slot);
		ClassRegistry::flush();
	}

}
?>