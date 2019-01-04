<?php
class IncentiveComponent extends Object{
    var $components = array('General', 'Shop','RequestHandler');
    var $Memcache = null;
    
    function addRetailerSchemes($scheme_name,$scheme_tag,$start_date,$end_date,$incentive,$service_ids)
    {
        $this->data['RetailerSchemes'] = array('name'=>$scheme_name,'scheme_tag'=>$scheme_tag,'start_date'=>$start_date,'end_date'=>$end_date,'incentive'=>$incentive,'service_ids'=>$service_ids);
        $transObj = ClassRegistry::init('RetailerSchemes');
        
        $transObj->create();        
        if($transObj->save($this->data,false))
        {   
            return $transObj->id;
        }
        else
        {
            return FALSE;
        }
    }
    
    
    function getData($ids, $data_points, $date_range, $service_ids){
        if(!in_array('total_sale',$data_points)){
            $data_points[] = 'total_sale';
        }
        $mapping_select = array('dist_id'=>'dist_user_id', 'total_sale'=>'sum(amount) as total_sale', 'total_earning'=>'sum(earning) as total_earning');
        
        $from_date = $date_range['from_date'];
        $to_date = $date_range['to_date'];
        
        $selects = array();
        foreach($data_points as $point){
            if(isset($mapping_select[$point])){
                $selects[$point] = $mapping_select[$point];
            }
        }
        $values = implode(",", $selects);
        
        $condition = 1;
        if( ! empty($ids)){
            $condition = " ret_user_id in (".implode(",",$ids).") ";
        }
        $query = "SELECT ret_user_id,date,$values FROM retailer_earning_logs WHERE service_id in ($service_ids) AND $condition AND date >= '$from_date' AND date <= '$to_date' group by date,ret_user_id";
        $userObj = ClassRegistry::init('Slaves');
        $data = $userObj->query($query);
        
        $data_ret = array();
        $data_ret_cons = array();
        foreach($data as $dt){
            $date = $dt['retailer_earning_logs']['date'];
            $ret_user_id = $dt['retailer_earning_logs']['ret_user_id'];
            $dist_user_id = $dt['retailer_earning_logs']['dist_user_id'];
            $data_ret[$ret_user_id][$date]['sale'] = $dt['0']['total_sale'];
            
            if(in_array('dist_id',$data_points)){
                $data_ret_cons[$ret_user_id]['dist_id'] = $dist_user_id;
            }
            
            
            if( ! isset($data_ret_cons[$ret_user_id]['total_sale'])) $data_ret_cons[$ret_user_id]['total_sale'] = 0;
            $data_ret_cons[$ret_user_id]['total_sale'] += $dt['0']['total_sale'];
            
            
            if(in_array('total_earning',$data_points)){
                $data_ret[$ret_user_id][$date]['earning'] = $dt['0']['total_earning'];
                
                if( ! isset($data_ret_cons[$ret_user_id]['total_earning'])) $data_ret_cons[$ret_user_id]['total_earning'] = 0;
                $data_ret_cons[$ret_user_id]['total_earning'] += $dt['0']['total_earning'];
            }
            
            if( ! isset($data_ret_cons[$ret_user_id]['days'])) $data_ret_cons[$ret_user_id]['days'] = 0;
            $data_ret_cons[$ret_user_id]['days'] ++ ;
        }
        
        foreach($data_ret as $ret_user_id=>$values){
            
            if(in_array('avg_sale',$data_points) || in_array('variance',$data_points)){
                $data_ret_cons[$ret_user_id]['avg_sale'] = $data_ret_cons[$ret_user_id]['total_sale'] / $data_ret_cons[$ret_user_id]['days'];
            }
            
            if(in_array('variance',$data_points) || in_array('datewise_sale',$data_points)){
                $data_ret_cons[$ret_user_id]['variance'] = 0;
                
                foreach($values as $date=>$values_date){
                    
                    if(in_array('variance',$data_points)){
                        $val = $values_date['sale'] - $data_ret_cons[$ret_user_id]['avg_sale'];
                        $data_ret_cons[$ret_user_id]['variance'] += $val * $val;
                    }
                    
                    if(in_array('datewise_sale',$data_points)){
                        $data_ret_cons[$ret_user_id]['datewise_data'][$date]['sale'] = $values_date['sale'];
                    }
                    
                    if(in_array('datewise_earning',$data_points)){
                        $data_ret_cons[$ret_user_id]['datewise_data'][$date]['earning'] = $values_date['earning'];
                    }
                }
                
                $data_ret_cons[$ret_user_id]['variance'] = intval(sqrt($data_ret_cons[$ret_user_id]['variance'] / ($data_ret_cons[$ret_user_id]['days'] - 1)));
            }
        }
        
        return $data_ret_cons;
    }
    
    function notify($data,$type=0,$notify_channel=0){
        //type = 1 means scheme & type = 2 means reminder
        //notify channel 1 = sms & 2 means notification
        $root_map = array('1'=>'shops','2'=>'notify');
        if($type == 1){
            $template = "<NAME>\nPay1 me <START_DATE> se <END_DATE> tak <SERVICES> ke Rs <TARGET> ki sale target achieve karne par puri sale par <INCENTIVE> extra commission payein\nHappy Recharging.\nPay1";
        }
        else if($type == 2){
            $template = "<NAME>\nAap ne abhi tak <SERVICES> ka Rs. <ACHIEVED> sale kar chuke hai aur Rs. <TARGET_LEFT> peeche chal rahe hai. Sale target achieve karne par puri sale par <INCENTIVE> extra commission payein\nHappy Recharging.\nPay1";
        }
        else if($type == 3){
            $template = "<NAME>\nCongrats !!\nAapka sale target complete ho gaya hai.\nTotal sale: Rs. <ACHIEVED>\nBonus received (<INCENTIVE>): Rs <INC_GIVEN>\nYour current balance is Rs <CLOSING>\nHappy Recharging.\nPay1";
        }
        
        
        foreach($data['old_data'] as $key => $dt){
            $col = $data['old_data_cols'][1];
            $target = str_replace(',', '', $dt[$col]);
            $achieved = !empty($data['new_data'][$key]['total_sale'])?$data['new_data'][$key]['total_sale']:0;
            $start_date = $data['scheme_dates']['from_date'];
            $end_date = $data['scheme_dates']['to_date'];
            $mobile = $data['old_data'][$key]['mobile'];
            $name = $data['scheme_info']['name'];
            $incentive = $data['scheme_info']['incentive'];
            $incentive_given = $data['old_data'][$key]['incentive_given'];
            $target_left = ($target - $achieved >= 0) ? $target - $achieved : 0;
            
            $service_ids = explode(",",$data['scheme_info']['service_ids']);
            $services = $this->Shop->getServices();
            $ser_names = array();
            foreach($service_ids as $s_id){
                $ser_names[] = $services[$s_id];
            }
            
            $msg = $this->General->ReplaceMultiWord(array('name'=>$name,'target'=>$target,'achieved'=>$achieved,'target_left'=>$target_left,'start_date'=>date('d M Y',strtotime($start_date)),'end_date'=>date('d M Y',strtotime($end_date)),'incentive'=>$incentive,'inc_given'=>$incentive_given,'services'=>implode(",",$ser_names)), $template);
            $this->General->sendMessage($mobile,$msg,$root_map[$notify_channel]);
        }
    }
 
    function schemePanelValidation($params)
    {
        $Object = ClassRegistry::init('RetailerSchemes');
        $Object->set($params);
        
        if (!$Object->validates(array('fieldList' => array('scheme_name','incentive','start_date','end_date')))) {
            $response = array('status' => 'failure','description' => $Object->validationErrors);
            return $response;
        }
        if(!isset($params['service_ids']))
        {
            $response = array('status' => 'failure','description' => 'Please select atleast one service');
            return $response;
        }
        return array('status'=>'success');
    }
}