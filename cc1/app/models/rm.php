<?php
class Rm extends AppModel {
	var $useTable  = 'rm';
        var $belongsTo = array(
		'MasterDistributor' => array(
			'className' => 'MasterDistributor',
			'foreignKey' => 'master_dist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>