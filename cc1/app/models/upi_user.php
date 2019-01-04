<?php
class Upi_user extends AppModel {
    var $name = 'Upi_user';
    var $useTable  = 'upi_users';
    var $validate = array(
        'vpa' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        'mobile' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
//        'amount' => array(
//            'required' => true,
//            'rule' => 'notEmpty'
//        ),
//        'remarks' => array(
//            'required' => true,
//            'rule' => 'notEmpty'
//        ),
//        'status' => array(
//            'required' => true,
//            'rule' => 'notEmpty'
//        ),
//        'merchant_txn_id' => array(
//            'required' => true,
//            'rule' => 'notEmpty'
//        ),
        
    );
}
?>