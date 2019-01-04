<?php

class FinanceComponent extends Object{
	var $components = array('General', 'Shop', 'Auth');

    function modem_closing($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 

        $modem_closing_array = array(); 
        $get_date_wise_modem_closing = $dbObj->query("
            SELECT  
               ROUND(SUM(closing)) as value,
               `date` 
            FROM 
                earnings_logs el
            JOIN
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_modem_closing)){
            foreach($get_date_wise_modem_closing as $modem_closing){
                if(!empty($modem_closing[0]['value'])){
                    $date = $modem_closing['el']['date'];

                    $modem_closing_array[$date] = $modem_closing[0]['value'];
                }

            }
        }
        return $modem_closing_array;
    }

    function total_modem_sale($from_date,$to_date){

    	$dbObj = ClassRegistry::init('Slaves'); 

    	$total_modem_sale_array = array();
        $get_date_wise_total_modem_sale = $dbObj->query("
            SELECT  
               ROUND(SUM(sale)) as value,
               `date` 
            FROM 
                earnings_logs el
            JOIN
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_total_modem_sale)){
            foreach($get_date_wise_total_modem_sale as $total_modem_sale){
                if(!empty($total_modem_sale[0]['value'])){
                    $date = $total_modem_sale['el']['date'];

                    $total_modem_sale_array[$date] = $total_modem_sale[0]['value'];
                }

            }
        }
        return $total_modem_sale_array;
    }

    function total_modem_invested($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 

        $total_modem_invested_array = array();
        $get_date_wise_total_modem_invested = $dbObj->query("
            SELECT  
               ROUND(SUM(invested)) as value,
               `date` 
            FROM 
                earnings_logs el
            JOIN
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_total_modem_invested)){
            foreach($get_date_wise_total_modem_invested as $total_modem_invested){
                if(!empty($total_modem_invested[0]['value'])){
                    $date = $total_modem_invested['el']['date'];

                    $total_modem_invested_array[$date] = $total_modem_invested[0]['value'];
                }

            }
        }
        return $total_modem_invested_array;
    }

    function modem_earning($from_date,$to_date){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$modem_earning_array = array();
        $get_date_wise_modem_earning = $dbObj->query("
            SELECT  
               ROUND(SUM(sale - opening - invested + closing)) as value,
               `date` 
            FROM 
                earnings_logs el
            JOIN
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_modem_earning)){
            foreach($get_date_wise_modem_earning as $modem_earning){
                if(!empty($modem_earning[0]['value'])){
                    $date = $modem_earning['el']['date'];
                    $modem_earning_array[$date] = $modem_earning[0]['value'];
                }
            	
            }
        }
        return $modem_earning_array;
    }

    function modem_gst_asset($from_date,$to_date){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$modem_gst_asset_array = array();
        $get_date_wise_modem_gst_asset = $dbObj->query("
            SELECT  
               ROUND(SUM((invested/1.18) * 0.18)) as value,
               `date` 
            FROM 
                earnings_logs el
            JOIN
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                v.update_flag = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_modem_gst_asset)){
            foreach($get_date_wise_modem_gst_asset as $modem_gst_asset){
                if(!empty($modem_gst_asset[0]['value'])){
                    $date = $modem_gst_asset['el']['date'];
                    $modem_gst_asset_array[$date] = $modem_gst_asset[0]['value'];
                }
            	
            }
        }
        return $modem_gst_asset_array;
    }

    function api_closing($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 

        $api_closing_array = array();
        $get_date_wise_api_closing = $dbObj->query("
            SELECT  
               ROUND(SUM(closing)) as value,
               `date` 
            FROM 
                earnings_logs el
            JOIN
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 0
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_api_closing)){
            foreach($get_date_wise_api_closing as $api_closing){
                if(!empty($api_closing[0]['value'])){
                    $date = $api_closing['el']['date'];

                    $api_closing_array[$date] = $api_closing[0]['value'];
                }

            }
        }
        return $api_closing_array;
    }

    function total_api_p2p_sale($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$total_api_p2p_sale_array = array();
        $get_date_wise_total_api_p2p_sale = $dbObj->query("
            SELECT  
               ROUND(SUM(sale)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 0
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_total_api_p2p_sale)){
            foreach($get_date_wise_total_api_p2p_sale as $total_api_p2p_sale){
                if(!empty($total_api_p2p_sale[0]['value'])){
                    $date = $total_api_p2p_sale['avsd']['date'];

                    $total_api_p2p_sale_array[$date] = $total_api_p2p_sale[0]['value'];
                }
            	
            }
        }
        return $total_api_p2p_sale_array;
    }

    function total_api_p2p_invested($from_date,$to_date,$service){

        $dbObj = ClassRegistry::init('Slaves'); 
        $total_api_p2p_invested_array = array();
        $get_date_wise_total_api_p2p_invested = $dbObj->query("
            SELECT  
               ROUND(SUM(sale - commission)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 0
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_total_api_p2p_invested)){
            foreach($get_date_wise_total_api_p2p_invested as $total_api_p2p_invested){
                if(!empty($total_api_p2p_invested[0]['value'])){
                    $date = $total_api_p2p_invested['avsd']['date'];

                    $total_api_p2p_invested_array[$date] = $total_api_p2p_invested[0]['value'];
                }
                
            }
        }
        return $total_api_p2p_invested_array;
    }

    function api_p2p_earning($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$api_p2p_earning_array = array();
        $get_date_wise_api_p2p_earning = $dbObj->query("
            SELECT  
               ROUND(SUM(commission)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 0
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_api_p2p_earning)){
            foreach($get_date_wise_api_p2p_earning as $api_p2p_earning){
                if(!empty($api_p2p_earning[0]['value'])){
                    $date = $api_p2p_earning['avsd']['date'];

                    $api_p2p_earning_array[$date] = $api_p2p_earning[0]['value'];
                }
            	
            }
        }
        return $api_p2p_earning_array;
    }

    function api_p2p_gst_asset($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$api_p2p_gst_asset_array = array();
        $get_date_wise_api_p2p_gst_asset = $dbObj->query("
            SELECT  
               ROUND(SUM(((sale - commission)/1.18) * 0.18)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 0
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_api_p2p_gst_asset)){
            $api_p2p_gst_asset_exist = true;
            foreach($get_date_wise_api_p2p_gst_asset as $api_p2p_gst_asset){
                if(!empty($api_p2p_gst_asset[0]['value'])){
                    $date = $api_p2p_gst_asset['avsd']['date'];
                    $api_p2p_gst_asset_array[$date] = $api_p2p_gst_asset[0]['value'];
                }
                
            }
        }
         return $api_p2p_gst_asset_array;
    }

    function total_api_p2a_sale($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$total_api_p2a_sale_array = array();
        $get_date_wise_total_api_p2a_sale = $dbObj->query("
            SELECT  
               ROUND(SUM(sale)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_total_api_p2a_sale)){
            foreach($get_date_wise_total_api_p2a_sale as $total_api_p2a_sale){
                if(!empty($total_api_p2a_sale[0]['value'])){
                    $date = $total_api_p2a_sale['avsd']['date'];
                    $total_api_p2a_sale_array[$date] = $total_api_p2a_sale[0]['value'];
                }
                
            }
        }
        return $total_api_p2a_sale_array;
    }

    function total_api_p2a_invested($from_date,$to_date,$service){

        $dbObj = ClassRegistry::init('Slaves'); 
        $total_api_p2a_invested_array = array();
        $get_date_wise_total_api_p2a_invested = $dbObj->query("
            SELECT  
               ROUND(SUM(sale - commission)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_total_api_p2a_invested)){
            foreach($get_date_wise_total_api_p2a_invested as $total_api_p2a_invested){
                if(!empty($total_api_p2a_invested[0]['value'])){
                    $date = $total_api_p2a_invested['avsd']['date'];

                    $total_api_p2a_invested_array[$date] = $total_api_p2a_invested[0]['value'];
                }
                
            }
        }
        return $total_api_p2a_invested_array;
    }

    function api_p2a_earning($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$api_p2a_earning_array = array();
        $get_date_wise_api_p2a_earning = $dbObj->query("
            SELECT  
               ROUND(SUM(commission)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_api_p2a_earning)){
            foreach($get_date_wise_api_p2a_earning as $api_p2a_earning){
                if(!empty($api_p2a_earning[0]['value'])){
                    $date = $api_p2a_earning['avsd']['date'];
                    $api_p2a_earning_array[$date] = $api_p2a_earning[0]['value'];
                }
                
            }
        }
        return $api_p2a_earning_array;
    }

    function api_p2a_gst_asset($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$api_p2a_gst_asset_array = array();
        $get_date_wise_api_p2a_gst_asset = $dbObj->query("
            SELECT  
               ROUND(SUM(((commission)/1.18) * 0.18)) as value,
               `date` 
            FROM 
                api_vendors_sale_data avsd
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND
                product_type = 1
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_api_p2a_gst_asset)){
            $api_p2a_gst_asset_exist = true;
            foreach($get_date_wise_api_p2a_gst_asset as $api_p2a_gst_asset){
                if(!empty($api_p2a_gst_asset[0]['value'])){
                    $date = $api_p2a_gst_asset['avsd']['date'];
                    $api_p2a_gst_asset_array[$date] = $api_p2a_gst_asset[0]['value'];
                }
                
            }
        }
         return $api_p2a_gst_asset_array;
    }

    function total_sale($from_date,$to_date,$service,$table,$column,$join){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$total_sale_array = array();
        $get_date_wise_total_sale = $dbObj->query("
            SELECT  
               ROUND(SUM(".$column.")) as value,
               s.parent_id as service_id,
               `date` 
            FROM 
                ".$table." vr
            ".$join."
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($get_date_wise_total_sale)){
            $total_sale_exist = true;
            //print_r($get_date_wise_total_sale);exit;
            foreach($get_date_wise_total_sale as $total_sale){
                if(!empty($total_sale[0]['value'])){
                    $date = $total_sale['vr']['date'];
                    $service_id = $total_sale['s']['service_id'];
                    $total_sale_array[$service_id][$date] = $total_sale[0]['value'];
                }
                
            }
        }
         return $total_sale_array;
    }

    function ret_service_charge($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves');
 
    	$ret_service_charge_array = array();
        $gst_liability_ret_service_charge_array = array();

        if($service != DMT){
            $query_ret_service_charge = $dbObj->query("
                SELECT  
                   ROUND(SUM(service_charge/1.18)) as value,
                   ROUND(SUM(service_charge - (service_charge/1.18))) as gst_liability,
                   s.parent_id as service_id,
                   `date` 
                FROM 
                    retailer_earning_logs rel
                JOIN
                    services s ON s.id = rel.service_id
                WHERE 
                    `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                    rel.service_id IN (".$service.") AND
                    rel.service_id NOT IN (".DMT.")
                GROUP BY 
                    s.parent_id,date
                ");
            if(!empty($query_ret_service_charge)){
                foreach($query_ret_service_charge as $ret_service_charge){
                    if(!empty($ret_service_charge[0]['value'])){
                        $service_id = $ret_service_charge['s']['service_id'];
                        $date = $ret_service_charge['rel']['date'];

                        $ret_service_charge_array[$service_id][$date] = $ret_service_charge[0]['value'];

                        $gst_liability_ret_service_charge_array[$service_id][$date] = $ret_service_charge[0]['gst_liability'];
                    }

                    
                }
            }
        }else{
            $query_ret_service_charge = $dbObj->query("
                SELECT 
                    ROUND(SUM(service_charge/1.18)) as value,
                    ROUND(SUM(service_charge - (service_charge/1.18))) as gst_liability,
                    `date` 
                FROM 
                    `wallets_transactions` rel 
                LEFT JOIN products ON (products.id = product_id) 
                WHERE 
                    `product_id` not in (84,215) AND 
                    products.service_id IN ('$service') AND 
                    `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                    rel.status = '1' 
                GROUP BY 
                    date
                ");
            if(!empty($query_ret_service_charge)){
                foreach($query_ret_service_charge as $ret_service_charge){
                    if(!empty($ret_service_charge[0]['value'])){
                        $service_id = DMT;
                        $date = $ret_service_charge['rel']['date'];

                        $ret_service_charge_array[$service_id][$date] = $ret_service_charge[0]['value'];

                        $gst_liability_ret_service_charge_array[$service_id][$date] = $ret_service_charge[0]['gst_liability'];
                    }

                    
                }
            }
        }

    	$array = array(
            	'ret_service_charge_array'=>$ret_service_charge_array,
            	'gst_liability_ret_service_charge_array' => $gst_liability_ret_service_charge_array
            );
         return $array;
    }

    function rental_charge($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$rental_charge_array = array();
        $gst_liability_rental_array = array();
        $query_rental_charge = $dbObj->query("
            SELECT  
               ROUND(SUM(amount/1.18)) as value,
               ROUND(SUM(amount - (amount/1.18))) as gst_liability,
               s.parent_id as service_id,
               `date` 
            FROM 
                users_nontxn_logs untl
            JOIN
                services s ON s.id = untl.service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                untl.service_id IN (".$service.") AND 
                type = ".RENTAL."
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_rental_charge)){
            foreach($query_rental_charge as $rental_charge){
                if(!empty($rental_charge[0]['value'])){
                    $date = $rental_charge['untl']['date'];
                    $service_id = $rental_charge['s']['service_id'];

                    $rental_charge_array[$service_id][$date] = $rental_charge[0]['value'];

                    $gst_liability_rental_array[$service_id][$date] = $rental_charge[0]['gst_liability'];
                }
                
            }
        }

            $array = array('rental_charge_array'=>$rental_charge_array,'gst_liability_rental_array' => $gst_liability_rental_array);
         return $array;
    }

    function kit_charge($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$kit_charge_array = array();
        $gst_liability_kit_array = array();
        $query_kit_charge = $dbObj->query("
            SELECT  
               ROUND(SUM(amount/1.18)) as value,
               ROUND(SUM(amount - (amount/1.18))) as gst_liability,
               s.parent_id as service_id,
               `date` 
            FROM 
                users_nontxn_logs untl
            JOIN
                services s ON s.id = untl.service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                untl.service_id IN (".$service.") AND 
                type = ".KITCHARGE."
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_kit_charge)){
            foreach($query_kit_charge as $kit_charge){
                if(!empty($kit_charge[0]['value'])){
                    $date = $kit_charge['untl']['date'];
                    $service_id = $kit_charge['s']['service_id'];
                    $kit_charge_array[$service_id][$date] = $kit_charge[0]['value'];

                    $gst_liability_kit_array[$service_id][$date] = $rental_charge[0]['gst_liability'];
                }
                
            }
        }

            $array = array('kit_charge_array'=>$kit_charge_array,'gst_liability_kit_array' => $gst_liability_kit_array);
         return $array;
    }

    function vendor_commision($from_date,$to_date,$service,$table,$join){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$vendor_commision_array = array();
        $gst_liability_vendor_commision_array = array();
        $query_vendor_commision = $dbObj->query("
            SELECT  
               ROUND(SUM((commission-commission_adjustment)/1.18)) as value,
               ROUND(SUM(commission - commission_adjustment  - ((commission - commission_adjustment) /1.18))) as gst_liability,
               s.parent_id as service_id,
               `date` 
            FROM 
                ".$table." vr
            ".$join."
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");

        	
        if(!empty($query_vendor_commision)){
            foreach($query_vendor_commision as $vendor_commision){
                if(!empty($vendor_commision[0]['value'])){
                    $date = $vendor_commision['vr']['date'];
                    $service_id = $vendor_commision['s']['service_id'];
                    $vendor_commision_array[$service_id][$date] = $vendor_commision[0]['value'];

                    $gst_liability_vendor_commision_array[$service_id][$date] = $vendor_commision[0]['gst_liability'];
                }
                     
            }
        }

            $array = array('vendor_commision_array'=>$vendor_commision_array,'gst_liability_vendor_commision_array' => $gst_liability_vendor_commision_array);
         return $array;
    }

    function vendor_comm_adjustment($from_date,$to_date,$service){

        $dbObj = ClassRegistry::init('Slaves'); 
        $vendor_comm_adjustment_array = array();
        $gst_liability_vendor_comm_adjustment_array = array();
        $query_vendor_comm_adjustment = $dbObj->query("
            SELECT  
               ROUND(SUM(comm_adjustment/1.18)) as value,
               ROUND(SUM(comm_adjustment - (comm_adjustment/1.18))) as gst_liability,
               s.parent_id as service_id,
               `date` 
            FROM 
                vendor_recon vr
            JOIN 
                product_vendors pv ON vr.vendor_id = pv.id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_vendor_comm_adjustment)){
            foreach($query_vendor_comm_adjustment as $vendor_comm_adjustment){
                if(!empty($vendor_comm_adjustment[0]['value'])){
                    $date = $vendor_comm_adjustment['vr']['date'];
                    $service_id = $vendor_comm_adjustment['s']['service_id'];
                    $vendor_comm_adjustment_array[$service_id][$date] = $vendor_comm_adjustment[0]['value'];

                    $gst_liability_vendor_comm_adjustment_array[$service_id][$date] = $vendor_comm_adjustment[0]['gst_liability'];
                }
                
            }
        }

            $array = array('vendor_comm_adjustment_array'=>$vendor_comm_adjustment_array,'gst_liability' => $gst_liability_vendor_comm_adjustment_array);
         return $array;
    }

    function vendor_set_up_fee($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$vendor_settle_array = array();
        $gst_liability_vendor_settle_array = array();
        $query_vendor_settle = $dbObj->query("
            SELECT  
               ROUND(SUM(setup_fee)) as value,
               ROUND(SUM(setup_fee - (setup_fee/1.13))) as gst_liability,
               s.parent_id as service_id,
               `date` 
            FROM 
                vendor_recon vr
            JOIN 
                product_vendors pv ON vr.vendor_id = pv.id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_vendor_settle)){
            foreach($query_vendor_settle as $vendor_settle){
                if(!empty($vendor_settle[0]['value'])){
                    $date = $vendor_settle['vr']['date'];
                    $service_id = $vendor_settle['s']['service_id'];
                    $vendor_settle_array[$service_id][$date] = $vendor_settle[0]['value'];

                    $gst_liability_vendor_settle_array[$service_id][$date] = $vendor_settle[0]['gst_liability'];     
                }
                  
            }
        }

            $array = array('vendor_settle_array'=>$vendor_settle_array,'gst_liability_vendor_settle_array' => $gst_liability_vendor_settle_array);
         return $array;
    }

    function vendor_incentive($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$vendor_incentive_array = array();
        $gst_liability_vendor_incentive_array = array();
        $query_vendor_incentive = $dbObj->query("
            SELECT  
               ROUND(SUM(extra_incentive/1.18)) as value,
               ROUND(SUM(extra_incentive - (extra_incentive/1.18))) as gst_liability,
               s.parent_id as service_id,
               `date` 
            FROM 
                vendor_recon vr
            JOIN 
                product_vendors pv ON vr.vendor_id = pv.id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_vendor_incentive)){
            foreach($query_vendor_incentive as $vendor_incentive){
                if(!empty($vendor_incentive[0]['value'])){
                    $date = $vendor_incentive['vr']['date'];
                    $service_id = $vendor_incentive['s']['service_id'];
                    $vendor_incentive_array[$service_id][$date] = $vendor_incentive[0]['value'];

                    $gst_liability_vendor_incentive_array[$service_id][$date] = $vendor_incentive[0]['gst_liability'];
                }
                      
            }
        }

            $array = array('vendor_incentive_array'=>$vendor_incentive_array,'gst_liability_vendor_incentive_array' => $gst_liability_vendor_incentive_array);
         return $array;
    }

    function vendor_service_charge($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$vendor_service_charge_array = array();
        $gst_asset_vendor_sc_array = array();

        if($service != DMT){

            $query_vendor_service_charge = $dbObj->query("
                SELECT  
                   ROUND(SUM((service_charge-service_charge_adjustment)/1.18)) as value,
                   ROUND(SUM(service_charge -service_charge_adjustment - ((service_charge - service_charge_adjustment)/1.18))) as gst_asset,
                   s.parent_id as service_id,
                   `date` 
                FROM 
                    vendor_recon vr
                JOIN 
                    product_vendors pv ON vr.vendor_id = pv.id
                JOIN
                    services s ON s.id = service_id
                WHERE 
                    `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                    service_id IN (".$service.") AND
                    service_id NOT IN (".DMT.")

                GROUP BY 
                    s.parent_id,date
                ");
            if(!empty($query_vendor_service_charge)){
                foreach($query_vendor_service_charge as $vendor_service_charge){
                    if(!empty($vendor_service_charge[0]['value'])){
                        $date = $vendor_service_charge['vr']['date'];
                        $service_id = $vendor_service_charge['s']['service_id'];
                        $vendor_service_charge_array[$service_id][$date] = $vendor_service_charge[0]['value'];

                        $gst_asset_vendor_sc_array[$service_id][$date] = $vendor_service_charge[0]['gst_asset'];
                    }
                    
                }
            }
        }else{
            $query_vendor_service_charge = $dbObj->query("
                SELECT 
                    ROUND(SUM(vendor_service_charge/1.18)) as value,
                    ROUND(SUM(vendor_service_charge - (vendor_service_charge/1.18))) as gst_asset,
                    `date` 
                FROM 
                    `wallets_transactions` vr
                JOIN products ON (products.id = product_id)
                WHERE 
                    `product_id` != 84 AND 
                    `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                    products.service_id in ('$service') AND  
                    vr.status = '1' 
                GROUP BY 
                    date
                ");
            
            $query_vendor_servicecharge_adjustment = $dbObj->query("
            SELECT
               ROUND(SUM(service_charge_adjustment/1.18)) as value,
               ROUND(SUM(service_charge_adjustment - (service_charge_adjustment/1.18))) as gst_liability,
               s.parent_id as service_id,
               `date`
            FROM
                vendor_recon vr
            JOIN
                product_vendors pv ON vr.vendor_id = pv.id
            JOIN
                services s ON s.id = service_id
            WHERE
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                service_id IN (".$service.")
            GROUP BY
                s.parent_id,date
            ");
            
            if(!empty($query_vendor_service_charge)){
                foreach($query_vendor_service_charge as $vendor_service_charge){
                    if(!empty($vendor_service_charge[0]['value'])){
                        $date = $vendor_service_charge['vr']['date'];
                        $service_id = DMT;
                        $vendor_service_charge_array[$service_id][$date] = $vendor_service_charge[0]['value'];

                        $gst_asset_vendor_sc_array[$service_id][$date] = $vendor_service_charge[0]['gst_asset'];
                    }
                    
                }
                
                foreach($query_vendor_servicecharge_adjustment as $vendor_service_charge){
                    if(!empty($vendor_service_charge[0]['value'])){
                        $date = $vendor_service_charge['vr']['date'];
                        $vendor_service_charge_array[$service_id][$date] = $vendor_service_charge_array[$service_id][$date] - $vendor_service_charge[0]['value'];
                        
                        $gst_asset_vendor_sc_array[$service_id][$date] = $gst_asset_vendor_sc_array[$service_id][$date] - $vendor_service_charge[0]['gst_asset'];
                    }
                    
                }
            }
        }

            $array = array('vendor_service_charge_array'=>$vendor_service_charge_array,'gst_asset_vendor_sc_array' => $gst_asset_vendor_sc_array);
         return $array;
    }

    function vendor_kit_payment($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$vendor_kit_payment_array = array();
        $gst_asset_vendor_kit_array = array();
        $query_vendor_kit_payment = $dbObj->query("
            SELECT  
               ROUND(SUM(kit_payment/1.18)) as value,
               ROUND(SUM(kit_payment - (kit_payment/1.18))) as gst_asset,
               s.parent_id as service_id,
               `date` 
            FROM 
                vendor_recon vr
            JOIN 
                product_vendors pv ON vr.vendor_id = pv.id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_vendor_kit_payment)){
            foreach($query_vendor_kit_payment as $vendor_kit_payment){
                if(!empty($vendor_kit_payment[0]['value'])){
                    $date = $vendor_kit_payment['vr']['date'];
                    $service_id = $vendor_kit_payment['s']['service_id'];
                    $vendor_kit_payment_array[$service_id][$date] = $vendor_kit_payment[0]['value'];

                    $gst_asset_vendor_kit_array[$service_id][$date] = $vendor_kit_payment[0]['gst_asset'];
                }
                
            }
        }

            $array = array('vendor_kit_payment_array'=>$vendor_kit_payment_array,'gst_asset_vendor_kit_array' => $gst_asset_vendor_kit_array);
         return $array;
    }

    function adjustment($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 


        $adjustment_array = array();
        /*Get Vendor Expected earning*/
        $expected_earning_array = array();
        $get_all_vendor_expected_earning = $dbObj->query("
            SELECT
               v.update_flag,
               el.vendor_id,
               el.invested,
               el.expected_earning,
               `date` 
            FROM 
                earnings_logs el
            JOIN 
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 0 AND
                v.id NOT IN (".SAAS_VENDORS.")
            GROUP BY 
                el.vendor_id,date
            ");
        if(!empty($get_all_vendor_expected_earning)){
            $data['earnings_logs'] = array();
            foreach($get_all_vendor_expected_earning as $vendor_expected_earning){

                    $update_flag = $vendor_expected_earning['v']['update_flag'];
                    $date = $vendor_expected_earning['el']['date'];
                    $vendor_id = $vendor_expected_earning['el']['vendor_id'];
                    $invested = $vendor_expected_earning['el']['invested'];
                    $expected_earning = $vendor_expected_earning['el']['expected_earning'];

                    $data['earnings_logs'] = array(
                        "update_flag" => $update_flag,
                        "date" => $date,
                        "vendor_id" => $vendor_id,
                        "invested" => $invested,
                        "expected_earning" => $expected_earning
                    );
                    $expected_earning_array[$date] += $this->Shop->calculateExpectedEarning($data);
            }
        }
        
        
        /*Get Vendor Earning*/
        $vendor_earning_array = array();
        $query_vendor_earning = $dbObj->query("
            SELECT  
               SUM(sale - (opening + invested - closing)) AS value,
               `date` 
            FROM 
                earnings_logs el
            JOIN 
                vendors v ON v.id = el.vendor_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                v.update_flag = 0 AND
                v.id NOT IN (".SAAS_VENDORS.")
            GROUP BY 
                date
            ");
        if(!empty($query_vendor_earning)){
            foreach($query_vendor_earning as $vendor_earning){
                if(!empty($vendor_earning[0]['value'])){
                    $date = $vendor_earning['el']['date'];
                    $vendor_earning_array[$date] = $vendor_earning[0]['value'];

                    $adjustment_array[$date] = ROUND($vendor_earning_array[$date] - $expected_earning_array[$date]);
                }
            }
        }

        /*echo "<pre>";
        print_r($expected_earning_array);
        print_r($vendor_earning_array);
        exit;*/

         return $adjustment_array;
    }


    function loss($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$loss_array = array();
        $query_loss = $dbObj->query("
            SELECT  
               ROUND(SUM(loss)) as value,
               s.parent_id as service_id,
               `date` 
            FROM 
                vendor_recon vr
            JOIN 
                product_vendors pv ON vr.vendor_id = pv.id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_loss)){
            foreach($query_loss as $loss){
                if(!empty($loss[0]['value'])){
                    $date = $loss['vr']['date'];
                    $service_id = $loss['s']['service_id'];
                    $loss_array[$service_id][$date] = $loss[0]['value'];
                }
                
            }
        }
         return $loss_array;
    }

    function ret_commision($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$ret_commision_array = array();
        $query_ret_commision = $dbObj->query("
            SELECT  
               ROUND(SUM(commission)) as value,
               s.parent_id as service_id,
               `date` 
            FROM 
                retailer_earning_logs rel
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.")
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_ret_commision)){
            foreach($query_ret_commision as $ret_commision){
                if(!empty($ret_commision[0]['value'])){
                    $date = $ret_commision['rel']['date'];
                    $service_id = $ret_commision['s']['service_id'];
                    $ret_commision_array[$service_id][$date] = $ret_commision[0]['value'];
                }
                
            }
        }
         return $ret_commision_array;
    }

    function ret_incentive($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$ret_incentive_array = array();
        $query_ret_incentive = $dbObj->query("
            SELECT  
               ROUND(SUM(untl.amount - untl.txn_reverse_amt)) as value,
               s.parent_id as service_id,
               untl.date,
               d.id 
            FROM 
                users_nontxn_logs untl
            JOIN
                retailers r ON r.user_id = untl.user_id
            LEFT JOIN
                distributors d ON r.user_id = d.user_id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND 
                type = ".REFUND." AND
                d.id IS NULL
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_ret_incentive)){
            foreach($query_ret_incentive as $ret_incentive){
                if(!empty($ret_incentive[0]['value'])){
                    $date = $ret_incentive['untl']['date'];
                    $service_id = $ret_incentive['s']['service_id'];
                    $ret_incentive_array[$service_id][$date] = $ret_incentive[0]['value'];
                }
                
            }
        }
         return $ret_incentive_array;
    }

    function dist_incentive($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$dist_incentive_array = array();
        $query_dist_incentive = $dbObj->query("
            SELECT  
               ROUND(SUM(amount - txn_reverse_amt)) as value,
               s.parent_id as service_id,
               `date` 
            FROM 
                users_nontxn_logs untl
            JOIN
                distributors d ON d.user_id = untl.user_id
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND 
                type = ".REFUND."
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_dist_incentive)){
            foreach($query_dist_incentive as $dist_incentive){
                if(!empty($dist_incentive[0]['value'])){
                    $date = $dist_incentive['untl']['date'];
                    $service_id = $dist_incentive['s']['service_id'];
                    $dist_incentive_array[$service_id][$date] = $dist_incentive[0]['value'];
                }
                
            }
        }
         return $dist_incentive_array;
    }

    function dist_commision($from_date,$to_date,$service){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$dist_commision_array = array();
        $query_dist_commision = $dbObj->query("
            SELECT  
               ROUND(SUM(amount - txn_reverse_amt)) as value,
               s.parent_id as service_id,
               `date` 
            FROM 
                users_nontxn_logs untl
            JOIN
                services s ON s.id = service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND 
                service_id IN (".$service.") AND 
                type = ".COMMISSION_DISTRIBUTOR."
            GROUP BY 
                s.parent_id,date
            ");
        if(!empty($query_dist_commision)){
            foreach($query_dist_commision as $dist_commision){
                if(!empty($dist_commision[0]['value'])){
                    $date = $dist_commision['untl']['date'];
                    $service_id = $dist_commision['s']['service_id'];
                    $dist_commision_array[$service_id][$date] = $dist_commision[0]['value'];
                }
                
            }
        }
         return $dist_commision_array;
    }


    function recharge_income($from_date,$to_date){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$recharge_income_array = array();
        $get_date_wise_recharge_income = $dbObj->query("
            SELECT  
               ROUND(SUM(sale - opening - invested + closing)) as value,
               `date` 
            FROM 
                earnings_logs el
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."'
            GROUP BY 
                date
            ");
        if(!empty($get_date_wise_recharge_income)){
            foreach($get_date_wise_recharge_income as $recharge_income){
                if(!empty($recharge_income[0]['value'])){
                    $date = $recharge_income['el']['date'];
                    $recharge_income_array[$date] = $recharge_income[0]['value'];
                }
                
            }
        }
        return $recharge_income_array;
    }

    function get_accounting_categories(){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$accounting_categories_array = array();
        $query_get_accounting_categories = $dbObj->query("
            SELECT  
               * 
            FROM 
                accounting_categories ac
            WHERE 
                txn_type = 'Dr' AND 
                is_active = 1
            ");
        if(!empty($query_get_accounting_categories)){
            foreach($query_get_accounting_categories as $accounting_categories){

            	$id = $accounting_categories['ac']['id'];
            	$category = $accounting_categories['ac']['category'];
            	$subcategory = $accounting_categories['ac']['subcategory']; 

                $accounting_categories_array[$category][$id] = $subcategory;
            }
        }
        return $accounting_categories_array;
    }

    function accounting_categories_expense($from_date,$to_date){

    	$dbObj = ClassRegistry::init('Slaves'); 
    	$accounting_categories_expense_array = array();
        $query_accounting_categories_expense = $dbObj->query("
            SELECT
	            ac.id,
	            ac.category,
	            ac.subcategory,
                ROUND(SUM(amount)) as value,
                DATE_FORMAT(txn_date, '%Y-%m-%d') as date
            FROM 
                account_txn_details atd
            JOIN 
            	accounting_categories ac ON ac.id = atd.account_category_id
            WHERE 
                txn_type = 'Dr' AND                 
                DATE_FORMAT(txn_date, '%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' AND
                is_active = 1 AND 
                category IN ('Internal Expenses','Statutory Payment','Vendor Setup Payment')
            GROUP BY
            	date, atd.account_category_id
            ");
        if(!empty($query_accounting_categories_expense)){
            foreach($query_accounting_categories_expense as $accounting_categories_expense){

                if(!empty($accounting_categories_expense[0]['value'])){
                    $id = $accounting_categories_expense['ac']['id'];
                    $category = $accounting_categories_expense['ac']['category'];
                    $subcategory = $accounting_categories_expense['ac']['subcategory']; 
                    $value = $accounting_categories_expense[0]['value']; 
                    $date = $accounting_categories_expense[0]['date']; 

                    if($subcategory == ""){
                        $subcategory = "Expense";
                    }

                    $total_expense[$date] += $value;

                    $accounting_categories_expense_array[$category][$subcategory][$date] = $value;
                    $accounting_categories_expense_array['total_expense'][$date] = $total_expense[$date];
                }
            	
            }
        }
        return $accounting_categories_expense_array;
    }

    function bank_balance($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $bank_balance_array = array();
        $query_bank_balance = $dbObj->query("
            SELECT  
               ROUND(SUM(closing)) as value,
               `date` 
            FROM 
                bank_closing bc
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."'
            GROUP BY 
                date
            ");
        if(!empty($query_bank_balance)){
            foreach($query_bank_balance as $bank_balance){
                if(!empty($bank_balance[0]['value'])){
                    $date = $bank_balance['bc']['date'];
                    $bank_balance_array[$date] = $bank_balance[0]['value'];
                }
                
            }
        }
         return $bank_balance_array;
    }

    function recharge_utility_inventory($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $recharge_utility_inventory_array = array();
        $query_recharge_utility_inventory = $dbObj->query("
            SELECT  
               ROUND(SUM(closing)) as value,
               `date` 
            FROM 
                 earnings_logs el
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."'
            GROUP BY 
                date
            ");
        if(!empty($query_recharge_utility_inventory)){
            foreach($query_recharge_utility_inventory as $recharge_utility_inventory){
                if(!empty($recharge_utility_inventory[0]['value'])){
                    $date = $recharge_utility_inventory['el']['date'];
                    $recharge_utility_inventory_array[$date] = $recharge_utility_inventory[0]['value'];
                }
                
            }
        }
         return $recharge_utility_inventory_array;
    }

    function recharge_utility_advance($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $recharge_utility_advance_array = array();
        $query_recharge_utility_advance = $dbObj->query("
            SELECT  
               ROUND(IF(iso.commission_type=2,SUM(pending/(100 + margin)),SUM(pending/(100 - margin)))) as value,
               pending_date as date 
            FROM 
                inv_pendings ip
            JOIN 
                inv_supplier_operator iso ON ip.supplier_operator_id = iso.id
            WHERE 
                pending < 0 AND
                pending_date BETWEEN '".$from_date."' AND '".$to_date."'
            GROUP BY 
                pending_date
            ");
        if(!empty($query_recharge_utility_advance)){
            foreach($query_recharge_utility_advance as $recharge_utility_advance){
                if(!empty($recharge_utility_advance[0]['value'])){
                    $date = $recharge_utility_advance['ip']['date'];
                    $recharge_utility_advance_array[$date] = $recharge_utility_advance[0]['value'];
                }
                
            }
        }
         return $recharge_utility_advance_array;
    }

    function recharge_utility_credit($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $recharge_utility_credit_array = array();
        $query_recharge_utility_credit = $dbObj->query("
            SELECT  
               ROUND(IF(iso.commission_type=2,SUM(pending/(100 + margin)),SUM(pending/(100 - margin)))) as value,
               pending_date as date 
            FROM 
                inv_pendings ip
            JOIN 
                inv_supplier_operator iso ON ip.supplier_operator_id = iso.id
            WHERE 
                pending >= 0 AND
                pending_date BETWEEN '".$from_date."' AND '".$to_date."'
            GROUP BY 
                pending_date
            ");
        if(!empty($query_recharge_utility_credit)){
            foreach($query_recharge_utility_credit as $recharge_utility_credit){
                if(!empty($recharge_utility_credit[0]['value'])){
                    $date = $recharge_utility_credit['ip']['date'];
                    $recharge_utility_credit_array[$date] = $recharge_utility_credit[0]['value'];
                }
                
            }
        }
         return $recharge_utility_credit_array;
    }

    function vendor_prepaid_postpaid($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $vendor_prepaid_postpaid_array = array();
        $query_vendor_prepaid_postpaid = $dbObj->query("
            SELECT  
               ROUND(SUM(closing)) as value,
               pv.service_id,
               pv.type_flag,
               pv.name as vendor_name,
               s.name as service_name,
               `date` 
            FROM 
                vendor_recon vr
            JOIN
                product_vendors pv ON pv.id = vr.vendor_id
            JOIN
                services s ON s.id = pv.service_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."'
            GROUP BY 
                vr.vendor_id,date
            ");
        if(!empty($query_vendor_prepaid_postpaid)){
            foreach($query_vendor_prepaid_postpaid as $vendor_prepaid_postpaid){

                if(!empty($vendor_prepaid_postpaid[0]['value'])){
                    $vendor_name = $vendor_prepaid_postpaid['pv']['vendor_name'];
                    $service_name = $vendor_prepaid_postpaid['s']['service_name'];
                    $type_flag = $vendor_prepaid_postpaid['pv']['type_flag'];
                    $date = $vendor_prepaid_postpaid['vr']['date'];

                    if($type_flag){
                        $type = "Advance";
                    }else{
                        $type = "Settlement pending";
                    }

                    $vendor_prepaid_postpaid_array[$service_name." : ".$vendor_name." ( ".$type." )"][$date] = $vendor_prepaid_postpaid[0]['value'];
                }

                
            }
        }
         return $vendor_prepaid_postpaid_array;
    }

    function float_balance($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $float_balance_array = array();
        $query_float_balance = $dbObj->query("
            SELECT  
               ROUND(SUM(`float`)) as value,
               `date` 
            FROM 
                float_logs fl
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                hour = 24
            GROUP BY 
                date
            ");
        if(!empty($query_float_balance)){
            foreach($query_float_balance as $float_balance){
                if(!empty($float_balance[0]['value'])){
                    $date = $float_balance['fl']['date'];
                    $float_balance_array[$date] = $float_balance[0]['value'];
                }
                
            }
        }
         return $float_balance_array;
    }

    function pending_limit($from_date,$to_date){

        $dbObj = ClassRegistry::init('Slaves'); 
        $pending_limit_array = array();
        $query_pending_limit = $dbObj->query("
            SELECT  
               ROUND(SUM(amount)) as value,
               DATE_FORMAT(txn_date, '%Y-%m-%d') as `date` 
            FROM 
                account_txn_details atd
            WHERE 
                operation_date > DATE_FORMAT(txn_date, '%Y-%m-%d') AND
                DATE_FORMAT(txn_date, '%Y-%m-%d') BETWEEN '".$from_date."' AND '".$to_date."' AND
                txn_status = 'Cr' AND
                account_category_id = 1
            GROUP BY 
                date
            ");
        if(!empty($query_pending_limit)){
            foreach($query_pending_limit as $pending_limit){
                if(!empty($pending_limit[0]['value'])){
                    $date = $pending_limit[0]['date'];
                    $pending_limit_array[$date] = $pending_limit[0]['value'];
                }
                
            }
        }
         return $pending_limit_array;
    }



    function master_asset_liability($from_date,$to_date,$type=1){

        $dbObj = ClassRegistry::init('Slaves'); 
        $master_asset_liability_array = array();
        $query_master_asset_liability = $dbObj->query("
            SELECT  
               ROUND(SUM(closing)) as value,
               mal.parent_name,
               `date` 
            FROM 
                asset_liability_logs al
            JOIN
                master_assets_liability mal ON mal.id = al.master_id
            WHERE 
                `date` BETWEEN '".$from_date."' AND '".$to_date."' AND
                type = $type AND
                status = 1 
            GROUP BY 
                mal.parent_id,date
            "); 
        if(!empty($query_master_asset_liability)){
            foreach($query_master_asset_liability as $master_asset_liability){
                if(!empty($master_asset_liability[0]['value'])){
                    $name = $master_asset_liability['mal']['parent_name'];
                    $date = $master_asset_liability['al']['date'];


                    $master_asset_liability_array[$name][$date] = $master_asset_liability[0]['value'];
                }
                
            }
        }
         return $master_asset_liability_array;
    }

    function sendOTPViaEmail($id,$mobile){

        $dbObj = ClassRegistry::init('Slaves'); 

        $getEmailID = $dbObj->query("
            SELECT  
               email
            FROM 
                users u
            WHERE 
                id = $id
            ");
        if(!empty($getEmailID)){
            $email_id = $getEmailID[0]['u']['email'];
            if(!empty($email_id)){
                $mail_subject = "Finance Password";

                $mail_email = array($email_id);

                $otp = $this->General->generatePassword(6);
                $this->Shop->setMemcache("otp_financePassword_$mobile", $otp, 30 * 60);

                $mail_body = "Please use OTP : <b>".$otp."</b> to view finance module.";
                $this->General->sendMails($mail_subject, $mail_body,$mail_email,'mail');
            }
        }
        
        return true;
    }






}