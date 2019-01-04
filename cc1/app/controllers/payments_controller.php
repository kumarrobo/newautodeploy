<?php

class PaymentsController  extends AppController
{
    
    var $name = 'Payments';
    var $components = array('RequestHandler','General');
 
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('*');
     }
        
     public function get($bank_name=null)
    {
         $this->General->logData("/mnt/logs/payments.log",date('Y-m-d H:i:s')." :: ".  json_encode($this->params));
         $this->autoRender=false;
	 echo '{"status":"success","type":true,"pay1_tran_id":"SOME ID WITH TYPE INT","message":"SOME MESSAGE TYPE STRING"}';
	 exit();
    }
}

