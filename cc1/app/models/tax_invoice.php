<?php
class TaxInvoice extends AppModel {
	var $name = 'TaxInvoice';
        var $useTable = 'tax_invoices';
        
        /*var $validate = array(
        'user_id'=>array(
            'required' => true,
            'rule' => 'notEmpty',
            'message' => 'User id Required'
        ),
        'invoice_id'=>array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Invoice id Required'
                        )
            ),
        'month' => array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Month required'
                        )
        )
        ,
        'year' => array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Year required'
                        )
            ),
        'email_id' => array(
            'email' => array(
                        'rule' => array('email', true),
                        'message' => 'Please provide a valid email address.'
                        )
        ),
        'type' => array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Type required'
                        ),
            'allowedTypes'=>array(
                        'required' => true,
                        'rule' => array('inList', array(0,1,2)),
                        'message' => 'Type should be 0,1 or 2'
                        )
        )
    );*/

}
?>
