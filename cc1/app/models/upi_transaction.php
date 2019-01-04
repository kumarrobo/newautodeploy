<?php
class Upi_transaction extends AppModel {
    var $name = 'Upi_transaction';
    var $useTable  = 'upi_transactions';
    var $validate = array(
        'payer_vpa' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        'payer_mobile_no' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        'amount' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        'remarks' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        'status' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        'merchant_txn_id' => array(
            'required' => true,
            'rule' => 'notEmpty'
        ),
        
    );
}
?>