<?php
class UserProfileComponent extends Object{
    var $components = array('General','Shop','Documentmanagement','Bridge','Serviceintegration');
    var $Memcache = null;
    
    function getLastTxnDetails($user_id = null)
    {
        $Obj = ClassRegistry::init('Slaves');
//        $last_txn_details = $Obj->query('SELECT ret_user_id,service_id,MAX(date) AS txn_date,type,SUM(amount) as total_sale '
//                                            . 'FROM retailer_earning_logs rl '
//                                            . 'WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) '
//                                            . 'AND ret_user_id IN ('.$user_id.')'
//                                            . 'AND type IN (4,16,17) '
//                                            . 'GROUP BY ret_user_id,service_id');
        $last_txn_details = $Obj->query('SELECT ret_user_id,service_id,MAX(date) AS txn_date,type,SUM(amount) as total_sale '
                                            . 'FROM retailer_earning_logs rl '
                                            . 'WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) '
                                            . 'AND ret_user_id IN ('.$user_id.')'
                                            . 'AND type IN (16,17) '
                                            . 'GROUP BY ret_user_id,service_id');
        
        $response = array();
        foreach ($last_txn_details as $data)
        {
            $response[$data['rl']['ret_user_id']][$data['rl']['service_id']] = $data;
            $response[$data['rl']['ret_user_id']][$data['rl']['service_id']]['avg_sale'] = $data[0]['total_sale']/30;
        }
        
        return $response;
    }
    
    function getLastRentalPaidData($user_id = null)
    {
        $Obj = ClassRegistry::init('Slaves');
        $last_rental_paid_data = $Obj->query('SELECT * FROM '
                                                     . '(SELECT ul.user_id,ul.service_id,ul.amount,ul.date AS rental_paid_date '
                                                     . 'FROM users_nontxn_logs ul '
                                                     . 'JOIN retailers r ON (ul.user_id = r.user_id) '
                                                     . 'WHERE ul.type = 20 '
                                                     . 'AND ul.user_id IN ('.$user_id.') '
                                                     . 'ORDER BY ul.date desc) as rl '
                                                     . 'GROUP BY ul.user_id,ul.service_id'); 
        $response = array();
        foreach ($last_rental_paid_data as $data)
        {
            $response[$data['rl']['user_id']][$data['rl']['service_id']] = $data['rl'];
        }
        return $response;
    }
    
    function getTopupInfo($user_id = null)
    {
        $Obj = ClassRegistry::init('Slaves');
        $avg_topup = $Obj->query('SELECT r.user_id,SUM(rl.topup_buy) AS total_topup '
                                        . 'FROM users_logs rl'
                                        . 'JOIN retailers r ON (rl.user_id = r.user_id) '
                                        . 'WHERE date >= DATE_SUB(CURDATE(), INTERVAL 60 DAY) '
                                        . 'AND r.user_id IN ('.$user_id.') '
                                        . 'GROUP BY r.user_id');
        $response = array();
        foreach ($avg_topup as $data)
        {
            $response[$data['r']['user_id']]['avg_topup'] = $data[0]['total_topup']/60;
        }
        return $response;
    }
    
    function getUserProfile($user_id = null){
        $user_details = array();
       
        if($user_id){
            $user_details['last_txn_details'] = $this->getLastTxnDetails($user_id);
            $user_details['last_rental_paid_data'] = $this->getLastRentalPaidData($user_id);
            $user_details['avg_topup'] = $this->getTopupInfo($user_id);        
            $user_details['imp_textual_data'] = $this->Shop->getUserLabelData($user_id,2,0);
            $doc_info = $this->Documentmanagement->userStatusCheck($user_id,null,1);
            $user_details['imp_doc_data'] = $doc_info[$user_id];
        }
        
        return $user_details;
    }
}
