<?php

class DocManagement extends AppModel {
    var $name = 'DocManagement';
//    var $name = 'Doc_management';
    var $useTable  = 'imp_label_upload_history';
    var $validate=array(
        'user_id'=>array(
            'required' => true,
            'rule' => 'notEmpty',
            'message' => 'Payone User id Required'
        ),
        'service_id'=>array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Service id Required'
                        )
            ),
        'label_id' => array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Label id required'
                        )
        )
        ,
        'label_description' => array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Label description required'
                        )
            ),
        'email_id' => array(
            'ruleRequired'=>array(
                        'required' => true,
                        'rule' => 'notEmpty',
                        'message' => 'Email id required'
                        )
        )
//        ,
//        'ref_code' => array(
//            'ruleRequired'=>array(
//                        'required' => true,
//                        'rule' => 'notBlank',
//                        'message' => 'Reference code required'
//                        )
//        )
//        ,
//        'document' => array(
//            'ruleRequired'=>array(
//                        'required' => true,
//                        'rule' => 'notBlank',
//                        'message' => 'Document missing'
//                    ),
//            'allowedExtension' => array(
//                        'rule' => array(
//                            'extension',
//                            array('jpeg', 'png', 'jpg')
//                        ),
//                        'message' => 'Uploaded document should be image.'
//                        ),
//            'allowedSize' => array(
//                        'rule' => array('fileSize', '<=', '3MB'),
//                        'message' => 'Document size allowed : 3 MB'
//                        )
//        )
    );
}