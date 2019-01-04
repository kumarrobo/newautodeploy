<?php
class RetailerSchemes extends AppModel {
	var $name = 'RetailerSchemes';
        var $useTable = 'retailer_schemes';
        var $validate = array(
        'scheme_name'=>array(
            'required' => true,
            'rule' => 'notEmpty',
            'message' => 'Scheme name Required'
        ),
        'incentive'=>array(
//            'ruleRequired'=>array(
//                        'required' => true,
//                        'rule' => 'notEmpty',
//                        'message' => 'Incentive Required'
//                        ),
            'regex'=>array(
                        'required' => true,
                        'rule' => '/^[0-9]\d*(\.\d+)?%?$/',
                        'message' => 'Only numbers,decimal point and % sign allowed'
                        )
            ),
        'start_date'=>array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Please enter start date'
                        )
            ),
        'end_date'=>array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Please enter end date'
                        )
            )
);
}
