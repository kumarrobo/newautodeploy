<?php

class ScriptController extends AppController {
        var $name = 'Script';
        var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
        var $components = array('RequestHandler','Shop');
        var $uses = array('Retailer');
        
        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('*');
            set_time_limit(0);
            ini_set("memory_limit", "2048M");
        }
       
        function updateOldRetLogs($date = NULL){
            $this->autoRender = false;
            $last_date = (empty($date)) ? date('Y-m-d',strtotime('-1 days')) : $date;
            $days_90_older_date = date('Y-m-d', strtotime($last_date.'-30 days'));

            $getOpeningBalance = $this->Retailer->query('SELECT * FROM '
                                    . '(SELECT retailer_id,date,closing_balance '
                                    . 'FROM retailers_logs '
                                    . 'WHERE date >= "'.$days_90_older_date.'" AND date <= "'.$last_date.'"'
                                    . 'ORDER BY date desc) AS rl '
                                    . 'GROUP BY retailer_id ');
            $datas = array();
            foreach($getOpeningBalance as $dt){
                $datas[$dt['rl']['retailer_id']]['opening_balance'] = $dt['rl']['closing_balance'];
            }
            
            foreach($datas as $ret=>$val){
                $opening_balance = isset($val['opening_balance'])?$val['opening_balance']:0;

                $this->Retailer->query('UPDATE retailers_logs '
                        . 'SET opening_balance = '.$opening_balance.' '
                        . 'WHERE retailer_id = '.$ret.' AND date = "'.$last_date.'" ');
            }
        }

        function updateTds($date = NULL,$flag = 0){
            $this->autoRender = false;
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';

            $commission_data = $this->Retailer->query('SELECT d.user_id AS dist_user_id,st.source_id,st.user_id AS service_id,st.id AS txn_id,st.date,st.type '
                    . 'FROM '.$tbl.' AS st '
                    . 'JOIN distributors d ON (st.source_id = d.id) '
                    . 'WHERE st.type = 6 AND st.user_id != 0 AND st.date = "'.$date.'" '
                    . 'GROUP BY d.user_id,st.user_id');

            $response = array();

            foreach($commission_data as $data){
                $target_id = $data['st']['txn_id'];
                $source_id = $data['d']['dist_user_id'];
                $service_id = $data['st']['service_id'];

                $tds = $this->Retailer->query('UPDATE '.$tbl.' '
                        . 'SET target_id = '.$target_id.' '
                        . 'WHERE source_id = '.$source_id.' AND user_id = '.$service_id.' AND date = "'.$date.'" AND type = 31 AND target_id = 0 ');
            }
        }
        
       /* function updateProductsServices(){
            $this->autoRender = false;
            //update products table
            $this->Retailer->query('ALTER TABLE products DROP invoice_type');
            $this->Retailer->query("ALTER TABLE products ADD earning_type TINYINT NULL DEFAULT '-1' COMMENT '0-discount;1-commission;2-service charge' AFTER type,ADD tds TINYINT NULL DEFAULT '0' COMMENT '1:deduct tds',ADD gst TINYINT NULL DEFAULT '0' COMMENT '0-inclusive;1-exclusive',ADD earning_type_flag TINYINT NOT NULL DEFAULT '0' AFTER earning_type");
            $this->Retailer->query('UPDATE products SET earning_type = 0 WHERE service_id in (1,2,5,7)');
            $this->Retailer->query('UPDATE products SET earning_type = 2 WHERE service_id in (4,6,12)');
            $this->Retailer->query('UPDATE products SET earning_type = 2 WHERE id in (56,72,77)');
            $this->Retailer->query('UPDATE products SET earning_type = 1 WHERE id in (57,73,74)');
            $this->Retailer->query('UPDATE products SET earning_type = 1,earning_type_flag = 1 WHERE service_id = 14');
            
            //update services table
            $this->Retailer->query("ALTER TABLE services ADD inc_type_flag TINYINT NOT NULL DEFAULT '0',ADD inc_adj_services VARCHAR( 20 ) NULL DEFAULT NULL,ADD gst TINYINT NOT NULL DEFAULT '0' COMMENT '0-inclusive,1-exclusive'");
            $this->Retailer->query('UPDATE services SET inc_adj_services = "1,2" WHERE id = 1');
            $this->Retailer->query('UPDATE services SET inc_adj_services = "2,1" WHERE id = 2');
            $this->Retailer->query('UPDATE services SET inc_adj_services = "12" WHERE id = 12');
            $this->Retailer->query('UPDATE services SET inc_type_flag = 1 WHERE id IN (8,10,11,14,15)');
            
            //Alter wallets_transactions table
            
            $this->Retailer->query('ALTER TABLE wallets_transactions ADD reversal_date DATE NULL DEFAULT NULL AFTER date');
            //Alter retailers_logs
            $this->Retailer->query('ALTER TABLE retailers_logs ADD opening_balance FLOAT( 10, 2 ) NULL AFTER earning');
        }*/
        
        function updateWalletTxnReversalDate(){
            $this->autoRender = false;
            $this->Retailer->query('UPDATE wallets_transactions wt '
                                . 'JOIN shop_transactions st ON (wt.shop_transaction_id = st.target_id AND st.type = 32) '
                                . 'SET wt.reversal_date = st.date '
                                . 'WHERE st.date >= "2018-04-01"');
            
            $this->Retailer->query('UPDATE wallets_transactions wt '
                                . 'JOIN shop_transactions_logs st ON (wt.shop_transaction_id = st.target_id AND st.type = 32) '
                                . 'SET wt.reversal_date = st.date '
                                . 'WHERE st.date <= "2018-03-31"');
        }
        
        function updateOpeningClosing($date = NULL){
            $this->autoRender = false;
            
            $this->Retailer->query('UPDATE users_logs ul '                    
                    . 'JOIN distributors d ON (ul.user_id = d.user_id) '
                    . 'JOIN distributors_logs dl ON (dl.distributor_id = d.id AND ul.date = dl.date) '
                    . 'SET ul.user_id = d.user_id,ul.retailers = dl.retailers,ul.opening = dl.opening_balance,ul.closing = dl.closing_balance '
                    . 'WHERE ul.date = "'.$date.'"');
            
            $this->Retailer->query('INSERT IGNORE INTO users_logs (user_id,retailers,opening,closing,date) '
                    . 'SELECT d.user_id,retailers,opening_balance,closing_balance,date '
                    . 'FROM distributors_logs dl '
                    . 'JOIN distributors d ON (dl.distributor_id = d.id) '
                    . 'WHERE date = "'.$date.'"');
            
            $this->Retailer->query('UPDATE users_logs ul '                    
                    . 'JOIN retailers r ON (ul.user_id = r.user_id) '
                    . 'JOIN retailers_logs rl ON (rl.retailer_id = r.id AND ul.date = rl.date) '
                    . 'SET ul.user_id = r.user_id,ul.opening = rl.opening_balance,ul.closing = rl.closing_balance '
                    . 'WHERE ul.date = "'.$date.'"');
            
            $this->Retailer->query('INSERT IGNORE INTO users_logs (user_id,opening,closing,date) '
                    . 'SELECT r.user_id,opening_balance,closing_balance,date '
                    . 'FROM retailers_logs rl '
                    . 'JOIN retailers r ON (rl.retailer_id = r.id) '
                    . 'WHERE date = "'.$date.'"');
            
        }
        
        
        function updateUsersNontxnLogs($date = NULL,$flag = 0){
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d') : ($date == 1?date('Y-m-d',strtotime('-1 days')):$date);
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';
            $response = array();
            
            $txn_details = $this->Retailer->query('SELECT st1.source_id,st1.user_id AS service_id,SUM(st1.amount) AS amount,SUM(st2.amount) AS tds,st1.type '
                    . 'FROM '.$tbl.' st1 '
                    . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.') '
                    . 'WHERE st1.type IN ('.REFUND.','.RENTAL.','.KITCHARGE.','.SECURITY_DEPOSIT.','.ONE_TIME_CHARGE.') '
                    . 'AND st1.date = "'.$date.'" '
                    . 'GROUP BY st1.source_id,st1.user_id,st1.type');
            
            foreach($txn_details as $data){
                $service_id = $data['st1']['service_id'] == 0?1:$data['st1']['service_id'];
                $key = $data['st1']['source_id'].'_'.$service_id.'_'.$data['st1']['type'];
                $response[$data['st1']['source_id']][$key]['user_id'] = $data['st1']['source_id'];
                $response[$data['st1']['source_id']][$key]['service_id'] = $service_id;
                $response[$data['st1']['source_id']][$key]['amount'] += $data[0]['amount'];
                $response[$data['st1']['source_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['st1']['source_id']][$key]['type'] = $data['st1']['type'];
            }
            
            $comm_dist = $this->Retailer->query('SELECT d.user_id,st1.user_id as service_id,sum(st1.amount) as amount,SUM(st2.amount) AS tds,st1.type '
                    . 'FROM '.$tbl.' st1 '
                    . 'JOIN distributors d ON (st1.source_id = d.id) '
                    . 'LEFT JOIN '.$tbl.' st2 ON (st1.id = st2.target_id AND st2.type = '.TDS.')  '
                    . 'WHERE st1.type IN ('.COMMISSION_DISTRIBUTOR.','.COMMISSION_DISTRIBUTOR_REVERSE.') '
                    . 'AND st1.confirm_flag = 0 '
                    . 'AND st1.date = "'.$date.'" '
                    . 'GROUP BY d.user_id,st1.user_id,st1.type');
            
            foreach($comm_dist as $data){
                $service_id = $data['st1']['service_id'] == 0?1:$data['st1']['service_id'];
                $key = $data['d']['user_id'].'_'.$service_id.'_'.COMMISSION_DISTRIBUTOR;
                $response[$data['d']['user_id']][$key]['user_id'] = $data['d']['user_id'];
                $response[$data['d']['user_id']][$key]['service_id'] = $service_id;
                if($data['st1']['type'] == COMMISSION_DISTRIBUTOR){
                    $response[$data['d']['user_id']][$key]['amount'] += $data[0]['amount'];
                    $response[$data['d']['user_id']][$key]['tds'] += $data[0]['tds'];
                }
                else {
                    $response[$data['d']['user_id']][$key]['amount'] -= $data[0]['amount'];
                    $response[$data['d']['user_id']][$key]['tds'] -= $data[0]['tds'];
                }
                $response[$data['d']['user_id']][$key]['type'] = COMMISSION_DISTRIBUTOR;
            }
            
            $comm_tds = $this->Retailer->query('SELECT st1.source_id,st1.user_id as service_id,SUM(st1.amount) AS tds '
                    . 'FROM '.$tbl.' st1 '
                    . 'WHERE st1.type = '.TDS.' '
                    . 'AND st1.confirm_flag = 0 '
                    . 'AND st1.date = "'.$date.'" '
                    . 'AND st1.target_id = 0 '
                    . 'GROUP BY st1.source_id,st1.user_id');
            
            foreach($comm_tds as $data){
                $service_id = $data['st1']['service_id'] == 0?1:$data['st1']['service_id'];
                $key = $data['st1']['source_id'].'_'.$service_id.'_'.COMMISSION_DISTRIBUTOR;
                $response[$data['st1']['source_id']][$key]['user_id'] = $data['st1']['source_id'];
                $response[$data['st1']['source_id']][$key]['service_id'] = $data['st1']['service_id'];
                $response[$data['st1']['source_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['st1']['source_id']][$key]['type'] = COMMISSION_DISTRIBUTOR;
            }
            
            $txn_reversed = $this->Retailer->query('SELECT st.source_id,st.user_id as service_id,sum(st.amount) as txn_reverse_amt,st.type,st.type_flag '
                    . 'FROM '.$tbl.' st '
                    . 'WHERE st.type = '.TXN_REVERSE.' '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.source_id,st.user_id,st.type_flag');
            
            foreach($txn_reversed as $data){
                $key = $data['st']['source_id'].'_'.$data['st']['service_id'].'_'.$data['st']['type_flag'];
                $response[$data['st']['source_id']][$key]['service_id'] = $data['st']['service_id'];
                $response[$data['st']['source_id']][$key]['type'] = $data['st']['type_flag'];
                $response[$data['st']['source_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reverse_amt'];
            }
            
            $parent_ids = $this->Shop->getParentIds(array_filter(array_keys($response)));
            
            $i=1;
            $batch_qry = array();
            
            foreach ($response as $user_id => $earning_data)
            {
                $parent_user_id = isset($parent_ids[$user_id])?$parent_ids[$user_id]:0;
                
                foreach($earning_data as $data)
                {
                    $tds = isset($data['tds']) && !empty($data['tds'])?$data['tds']:0;
                    $txn_reverse_amt = isset($data['txn_reverse_amt']) && !empty($data['txn_reverse_amt'])?$data['txn_reverse_amt']:0;
                    $batch_qry['type'] = 'INSERT';
                    $batch_qry['predata'] = 'REPLACE INTO users_nontxn_logs(date,user_id,parent_user_id,service_id,amount,tds,txn_reverse_amt,type,closing)VALUES';
                    
                    $batch_qry['data'][] = '("'.$date.'","'.$user_id.'","'.$parent_user_id.'","'.$data['service_id'].'",'
                            . '"'.$data['amount'].'","'.$tds.'","'.$txn_reverse_amt.'","'.$data['type'].'","'.$data['amount'].'")';
                            
                            if (($i % 100) == 0){ //process batch of 100 queries together
                                $this->run_batch($batch_qry);
                                $batch_qry = array();
                            }
                            $i++;
                }
            }
            
            if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
            {
                $this->run_batch($batch_qry);
                $batch_qry = array();
            }
        }
        
        function updateUsersLogs($date = NULL,$flag = 0){
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d') : ($date == 1?date('Y-m-d',strtotime('-1 days')):$date);
            $tbl = $flag == 0?'shop_transactions':'shop_transactions_logs';
            
            $datas = array();
            //topup buy
            $data = $this->Retailer->query('SELECT SUM(st.amount) as amts,st.target_id,COUNT(st.id) as primary_txn,date,IF(st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.'),r.user_id,IF(st.type = '.MDIST_DIST_BALANCE_TRANSFER.',d.user_id,IF(st.type = '.DIST_SLMN_BALANCE_TRANSFER.',s.user_id,0))) as user_id '
                    . 'FROM '.$tbl.' st '
                    . 'LEFT JOIN retailers r ON (st.target_id = r.id AND st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.')) '
                    . 'LEFT JOIN distributors d ON (st.target_id = d.id AND st.type = '.MDIST_DIST_BALANCE_TRANSFER.') '
                    . 'LEFT JOIN salesmen s ON (st.target_id = s.id AND st.type = '.DIST_SLMN_BALANCE_TRANSFER.' AND d.user_id != s.user_id) '
                    . 'WHERE st.confirm_flag != 1 '
                    . 'AND st.type IN ('.MDIST_DIST_BALANCE_TRANSFER.','.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.') '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.target_id,st.type');
            
            foreach($data as $dt){
                $datas[$dt[0]['user_id']]['buy'] += $dt['0']['amts'];
                $datas[$dt[0]['user_id']]['primary_txn'] += $dt['0']['primary_txn'];
            }
            
            //topup sold
            $data = $this->Retailer->query('SELECT SUM(st.amount) as amts,st.source_id,COUNT(st.id) as cts,st.date,IF(st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.'),d.user_id,IF(st.type = '.SLMN_RETL_BALANCE_TRANSFER.',s.user_id,IF(st.type = '.MDIST_DIST_BALANCE_TRANSFER.',sd.user_id,0))) as user_id '
                    . 'FROM '.$tbl.' st '
                    . 'LEFT JOIN distributors d ON (st.source_id = d.id AND st.type IN ('.DIST_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.')) '
                    . 'LEFT JOIN salesmen s ON (st.source_id = s.id AND st.type = '.SLMN_RETL_BALANCE_TRANSFER.' AND d.user_id != s.user_id) '
                    . 'LEFT JOIN master_distributors sd ON (st.source_id = sd.id AND st.type = '.MDIST_DIST_BALANCE_TRANSFER.') '
                    . 'WHERE st.confirm_flag != 1 '
                    . 'AND st.type IN ('.MDIST_DIST_BALANCE_TRANSFER.','.DIST_RETL_BALANCE_TRANSFER.','.SLMN_RETL_BALANCE_TRANSFER.','.DIST_SLMN_BALANCE_TRANSFER.') '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY st.source_id,st.type');
            
            foreach($data as $dt){
                $datas[$dt[0]['user_id']]['sold'] += $dt['0']['amts'];
                $datas[$dt[0]['user_id']]['unique'] += $dt['0']['cts'];
            }
            
            //total retailers
            $data = $this->Retailer->query('SELECT COUNT(r.id) as cts,d.user_id '
                    . 'FROM retailers r '
                    . 'JOIN distributors d ON (r.parent_id = d.id) '
                    . 'GROUP BY r.parent_id');
            
            foreach($data as $dt){
                $datas[$dt['d']['user_id']]['retailers'] = $dt['0']['cts'];
            }
            
            //total transacting retailers
            $data = $this->Retailer->query('SELECT COUNT(DISTINCT rel.ret_user_id) as cts,rel.dist_user_id '
                    . 'FROM retailer_earning_logs rel '
                    . 'LEFT JOIN distributors d ON (rel.dist_user_id = d.user_id) '
                    . 'WHERE rel.date = "'.$date.'" '
                    . 'GROUP BY rel.dist_user_id');
            
            foreach($data as $dt){
                $datas[$dt['rel']['dist_user_id']]['transacting'] = $dt['0']['cts'];
            }
            
            $pg_topup = $this->Retailer->query('SELECT st.target_id,SUM(st.amount) as pg_topup,st.date,r.user_id '
                    . 'FROM '.$tbl.' st '
                    . 'JOIN retailers r ON (st.target_id = r.id) '
                    . 'WHERE st.date = "'.$date.'" '
                    . 'AND st.type IN ("'.DIST_RETL_BALANCE_TRANSFER.'","'.SLMN_RETL_BALANCE_TRANSFER.'") '
                    . 'AND st.type_flag = 5 '
                    . 'AND st.confirm_flag != 1 '
                    . 'GROUP BY st.target_id');
            
            foreach($pg_topup as $data){
                $datas[$data['r']['user_id']]['pg_topup'] = $data[0]['pg_topup'];
            }
            
            //topup reversed
            $topup_reversed = $this->Retailer->query('SELECT SUM(st1.amount) AS topup_reversed,st1.date AS pullback_date,st2.date AS txn_date,st1.source_id,IF(st1.type = '.PULLBACK_RETAILER.',r.user_id,IF(st1.type = '.PULLBACK_DISTRIBUTOR.',d.user_id,IF(st1.type = '.PULLBACK_SALESMAN.',s.user_id,sd.user_id))) as user_id '
                    . 'FROM '.$tbl.' st1 '
                    . 'JOIN '.$tbl.' st2 ON (st1.target_id = st2.id) '
                    . 'LEFT JOIN retailers r ON (st1.source_id = r.id AND st1.type = '.PULLBACK_RETAILER.') '
                    . 'LEFT JOIN distributors d ON (st1.source_id = d.id AND st1.type = '.PULLBACK_DISTRIBUTOR.') '
                    . 'LEFT JOIN salesmen s ON (st1.source_id = s.id AND st1.type = '.PULLBACK_SALESMAN.' AND d.user_id != s.user_id) '
                    . 'LEFT JOIN master_distributors sd ON (st1.source_id = sd.id AND st1.type = '.PULLBACK_MASTERDISTRIBUTOR.') '
                    . 'WHERE st1.type IN ('.PULLBACK_RETAILER.','.PULLBACK_DISTRIBUTOR.','.PULLBACK_MASTERDISTRIBUTOR.','.PULLBACK_SALESMAN.') '
                    . 'AND st1.date = "'.$date.'" '
                    . 'AND st1.date != st2.date '
                    . 'GROUP BY st1.source_id,st1.type');
            
            foreach($topup_reversed as $data){
                $datas[$data[0]['user_id']]['topup_reversed'] += $data[0]['topup_reversed'];
            }
            
            $users_data = $this->Retailer->query('SELECT * FROM users_logs WHERE date = "'.$date.'" ');
            $users_logs = array();
            foreach($users_data as $data){
                $users_logs[$data['users_logs']['user_id']] = $data['users_logs'];
                $datas[$data['users_logs']['user_id']]['opening'] = $data['users_logs']['opening'];
                $datas[$data['users_logs']['user_id']]['closing'] = $data['users_logs']['closing'];
            }
            $parent_ids = $this->Shop->getParentIds(array_filter(array_keys($datas)));
            
            foreach($datas as $user_id=>$val){
                if(empty($user_id))$user_id = 0;
                if(!isset($val['buy']))$val['buy'] = 0;
                if(!isset($val['sold']))$val['sold'] = 0;
                if(!isset($val['unique']))$val['unique'] = 0;
                if(!isset($val['retailers']))$val['retailers'] = 0;
                if(!isset($val['transacting']))$val['transacting'] = 0;
                if(!isset($val['primary_txn']))$val['primary_txn'] = 0;
                if(!isset($val['topup_reversed']))$val['topup_reversed'] = 0;
                if(!isset($val['pg_topup']))$val['pg_topup'] = 0;
                if(!isset($val['opening']))$val['opening'] = 0;
                if(!isset($val['closing']))$val['closing'] = 0;
                $parent_user_id = isset($parent_ids[$user_id])?$parent_ids[$user_id]:0;
                
                //$opening = (isset($users_logs[$user_id]['opening'])) ? $users_logs[$user_id]['opening'] : 0;
                //$closing = (isset($users_logs[$user_id]['closing'])) ? $users_logs[$user_id]['closing'] : 0;
                
                //                if(isset($users_logs[$user_id])){
                //                    $this->Retailer->query('UPDATE users_logs '
                //                            . 'SET parent_user_id = '.$parent_user_id.',topup_buy = '.$val['buy'].',topup_sold = '.$val['sold'].',retailers = '.$val['retailers'].',transacting = '.$val['transacting'].',pg_topup = '.$val['pg_topup'].',topup_unique = '.$val['unique'].',primary_txn = '.$val['primary_txn'].',topup_reversed = '.$val['topup_reversed'].' '
                //                            . 'WHERE user_id = "'.$user_id.'" AND date = "'.$date.'" ');
                //                }else{
                //                    $bal = $this->Retailer->query('SELECT opening_balance,balance FROM users WHERE id = '.$user_id.' AND balance > 0');
                //                    $opening_bal = isset($bal[0]['users']['opening_balance'])?$bal[0]['users']['opening_balance']:0;
                //                    $closing_bal = isset($bal[0]['users']['balance'])?$bal[0]['users']['balance']:0;
                $this->Retailer->query("REPLACE INTO users_logs (user_id,parent_user_id,retailers,transacting,topup_buy,topup_sold,pg_topup,topup_unique,primary_txn,date,topup_reversed,opening,closing) VALUES ($user_id,$parent_user_id,".$val['retailers'].",".$val['transacting'].",".$val['buy'].",".$val['sold'].",".$val['pg_topup'].",".$val['unique'].",'".$val['primary_txn']."','$date','".$val['topup_reversed']."','".$val['opening']."','".$val['closing']."')");
                //                }
            }
        }
        
        function updateRetailerEarningLogs($date = NULL,$flag = 0)
        {
            $this->autoRender = false;
            $date = empty($date)?date('Y-m-d') : ($date == 1?date('Y-m-d',strtotime('-1 days')):$date);
            $tbl1 = $flag == 0?'vendors_activations':'vendors_activations_logs';
            $tbl2 = $flag == 0?'shop_transactions':'shop_transactions_logs';
            
            //Get total sale and retailer commission of service ids 1,2,5,7 i.e P2P services
            $retailer_data = $this->Retailer->query('SELECT COUNT(st.id) AS txn_count,SUM(st.amount) as total_sale,SUM(st.retailer_margin) as ret_commission,SUM(s.amount) AS tds, st.api_flag, r.user_id, p.id, p.name, p.service_id,p.type,p.earning_type,p.earning_type_flag,vc.type,p.expected_earning_margin '
                    . 'FROM '.$tbl1.' st '
                    . 'JOIN retailers r ON (st.retailer_id = r.id) '
                    . 'JOIN vendors_commissions vc ON (st.vendor_id = vc.vendor_id AND st.product_id = vc.product_id) '
                    . 'JOIN products p ON (st.product_id = p.id) '
                    . 'LEFT JOIN '.$tbl2.' s ON (st.shop_transaction_id = s.target_id AND s.type = '.TDS.') '
                    . 'WHERE 1 '
                    . 'AND st.status NOT IN (2,3) '
                    . 'AND st.date = "'.$date.'" '
                    . 'GROUP BY r.user_id,p.id,p.service_id,vc.type,st.api_flag,p.earning_type,p.earning_type_flag');
            
            foreach($retailer_data as $data)
            {
                $earning_type = $data['vc']['type'] == 1 && $data['p']['earning_type'] == 0?1:$data['p']['earning_type'];
                $api_flag = $data['st']['api_flag'];
                $settlement_flag = 0;
                $key = $data['r']['user_id'].'_'.$data['p']['service_id'].'_'.$api_flag.'_'.DEBIT_NOTE.'_'.$earning_type.'_'.$data['p']['earning_type_flag'].'_'.$settlement_flag;
                $response[$data['r']['user_id']][$key]['ret_user_id'] = $data['r']['user_id'];
                $response[$data['r']['user_id']][$key]['service_id'] = $data['p']['service_id'];
                $response[$data['r']['user_id']][$key]['api_flag'] = $api_flag;
                $response[$data['r']['user_id']][$key]['type'] = DEBIT_NOTE;
                $response[$data['r']['user_id']][$key]['txn_type'] = $earning_type;    //0->discount,1->commission,2->service charges
                $response[$data['r']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];    //For commission - 0->commission,1->referral fee
                $response[$data['r']['user_id']][$key]['amount'] += $data[0]['total_sale'];
                $response[$data['r']['user_id']][$key]['closing'] += $data[0]['total_sale'];
                $response[$data['r']['user_id']][$key]['earning'] += ($data[0]['ret_commission'] < 0)?($data[0]['ret_commission'] * (-1)):$data[0]['ret_commission'];
                if($earning_type == 2){ //if service charge,calculate expected earning
                    $percent_pos = strpos($data['p']['expected_earning_margin'], '%');
                    $percent = substr($data['p']['expected_earning_margin'], 0, $percent_pos);
                    $response[$data['r']['user_id']][$key]['expected_earning'] += $percent_pos !== false?($data[0]['total_sale']*$percent)/100:$data['p']['expected_earning_margin']*$data[0]['txn_count'];
                }else{
                    $response[$data['r']['user_id']][$key]['expected_earning'] += $data[0]['ret_commission'];
                }
                $response[$data['r']['user_id']][$key]['txn_count'] += $data[0]['txn_count'];
                $response[$data['r']['user_id']][$key]['tds'] += $data[0]['tds'];
            }
            
            $reversed_txns = $this->Retailer->query('SELECT SUM(st.amount) AS txn_reverse_amt,st.api_flag, r.user_id, p.id, p.name, p.service_id,p.type,p.earning_type,p.earning_type_flag,vc.type '
                    . 'FROM '.$tbl1.' st '
                    . 'JOIN retailers r ON (st.retailer_id = r.id) '
                    . 'LEFT JOIN vendors_commissions vc ON (st.vendor_id = vc.vendor_id AND st.product_id = vc.product_id) '
                    . 'JOIN products p ON (st.product_id = p.id) '
                    . 'WHERE st.date != st.reversal_date '
                    . 'AND st.reversal_date = "'.$date.'" '
                    . 'AND st.date < "'.$date.'" '
                    . 'GROUP BY r.user_id,p.service_id,vc.type,st.api_flag,p.earning_type,p.earning_type_flag');
            
            foreach ($reversed_txns as $key => $data)
            {
                $earning_type = $data['vc']['type'] == 1 && $data['p']['earning_type'] == 0?1:$data['p']['earning_type'];
                $api_flag = $data['st']['api_flag'];
                $settlement_flag = 0;
                $key = $data['r']['user_id'].'_'.$data['p']['service_id'].'_'.$api_flag.'_'.DEBIT_NOTE.'_'.$earning_type.'_'.$data['p']['earning_type_flag'].'_'.$settlement_flag;
                $response[$data['r']['user_id']][$key]['service_id'] = $data['p']['service_id'];
                $response[$data['r']['user_id']][$key]['api_flag'] = $api_flag;
                $response[$data['r']['user_id']][$key]['txn_type'] = $earning_type;
                $response[$data['r']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
                $response[$data['r']['user_id']][$key]['type'] = DEBIT_NOTE;
                $response[$data['r']['user_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reverse_amt'];
            }
            
            $ret_debit_credit_data = $this->Retailer->query('SELECT wt.user_id,COUNT(wt.txn_id) AS txn_count,SUM(wt.amount) AS amount,SUM(wt.service_charge) AS service_charge,SUM(st.amount) AS tds,p.service_id,p.earning_type,p.earning_type_flag,wt.source,wt.cr_db,wt.settlement_mode,if(wt.settlement_mode = 1,SUM(wt.amount),0) AS bank_settlement,p.expected_earning_margin '
                    . 'FROM wallets_transactions wt '
                    . 'LEFT JOIN '.$tbl2.' st ON (wt.shop_transaction_id = st.target_id AND st.type = '.TDS.') '
                    . 'JOIN products p ON (wt.product_id = p.id) '
                    . 'WHERE wt.date = "'.$date.'" '
                    . 'AND wt.reversal_date IS NULL '
                    . 'GROUP BY wt.user_id,p.id,p.service_id,wt.cr_db,wt.source,p.earning_type,p.earning_type_flag,wt.settlement_mode');
            
            foreach ($ret_debit_credit_data as $data)
            {
                $shop_txn_type = $data['wt']['cr_db'] == 'db'?DEBIT_NOTE:CREDIT_NOTE;
                $key = $data['wt']['user_id'].'_'.$data['p']['service_id'].'_'.$data['wt']['source'].'_'.$shop_txn_type.'_'.$data['p']['earning_type'].'_'.$data['p']['earning_type_flag'].'_'.$data[0]['bank_settlement'];
                $response[$data['wt']['user_id']][$key]['ret_user_id'] = $data['wt']['user_id'];
                $response[$data['wt']['user_id']][$key]['service_id'] = $data['p']['service_id'];
                $response[$data['wt']['user_id']][$key]['api_flag'] = $data['wt']['source'];
                $response[$data['wt']['user_id']][$key]['amount'] += $data[0]['amount'];
                $response[$data['wt']['user_id']][$key]['closing'] += $data[0]['amount'];
                $response[$data['wt']['user_id']][$key]['earning'] += $data[0]['service_charge'];
                if($data['p']['earning_type'] == 2){
                    $percent_pos = strpos($data['p']['expected_earning_margin'], '%');
                    $percent = substr($data['p']['expected_earning_margin'], 0, $percent_pos);
                    $response[$data['wt']['user_id']][$key]['expected_earning'] += $percent_pos !== false?($data[0]['amount']*$percent)/100:$data['p']['expected_earning_margin']*$data[0]['txn_count'];
                }else{
                    $response[$data['wt']['user_id']][$key]['expected_earning'] += $data[0]['service_charge'];
                }
                $response[$data['wt']['user_id']][$key]['tds'] += $data[0]['tds'];
                $response[$data['wt']['user_id']][$key]['bank_settlement'] = $data[0]['bank_settlement'];
                $response[$data['wt']['user_id']][$key]['txn_type'] = $data['p']['earning_type'];
                $response[$data['wt']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
                $response[$data['wt']['user_id']][$key]['type'] = $shop_txn_type;
                $response[$data['wt']['user_id']][$key]['txn_count'] += $data[0]['txn_count'];
            }
            
            $reversed_trans = $this->Retailer->query('SELECT wt.user_id,SUM(wt.amount) AS txn_reversed,p.service_id,p.earning_type,p.earning_type_flag,wt.source,wt.cr_db,wt.settlement_mode,if(wt.settlement_mode = 1,SUM(wt.amount),0) AS bank_settlement '
                    . 'FROM wallets_transactions wt '
                    . 'JOIN products p ON (wt.product_id = p.id) '
                    . 'WHERE wt.date != wt.reversal_date '
                    . 'AND wt.reversal_date = "'.$date.'" '
                    . 'AND date < "'.$date.'" '
                    . 'GROUP BY wt.user_id,p.service_id,wt.cr_db,wt.source,p.earning_type,p.earning_type_flag,wt.settlement_mode');
            
            foreach($reversed_trans as $data){
                $shop_txn_type = $data['wt']['cr_db'] == 'db'?DEBIT_NOTE:CREDIT_NOTE;
                $key = $data['wt']['user_id'].'_'.$data['p']['service_id'].'_'.$data['wt']['source'].'_'.$shop_txn_type.'_'.$data['p']['earning_type'].'_'.$data['p']['earning_type_flag'].'_'.$data[0]['bank_settlement'];
                $response[$data['wt']['user_id']][$key]['service_id'] = $data['p']['service_id'];
                $response[$data['wt']['user_id']][$key]['api_flag'] = $data['wt']['source'];
                $response[$data['wt']['user_id']][$key]['txn_type'] = $data['p']['earning_type'];
                $response[$data['wt']['user_id']][$key]['txn_type_flag'] = $data['p']['earning_type_flag'];
                $response[$data['wt']['user_id']][$key]['type'] = $shop_txn_type;
                $response[$data['wt']['user_id']][$key]['bank_settlement'] = $data[0]['bank_settlement'];
                $response[$data['wt']['user_id']][$key]['txn_reverse_amt'] = $data[0]['txn_reversed'];
                
            }
            
            $get_dist_user_ids = 'SELECT d.user_id,r.user_id '
                    . 'FROM distributors d '
                            . 'JOIN retailers r ON (d.id = r.parent_id) '
                                    . 'WHERE r.user_id IN ('.implode(',', array_keys($response)).') ';
                                    
                                    $dist_user_ids = $this->Retailer->query($get_dist_user_ids);
                                    
                                    foreach ($dist_user_ids as $data)
                                    {
                                        $response[$data['r']['user_id']]['dist_user_id'] = $data['d']['user_id'];
                                    }
                                    
                                    $i=1;
                                    $batch_qry = array();
                                    
                                    foreach ($response as $ret_user_id => $earning_data)
                                    {
                                        $dist_user_id = $earning_data['dist_user_id'];
                                        unset($earning_data['dist_user_id']);
                                        foreach($earning_data as $data)
                                        {
                                            $bank_settlement = isset($data['bank_settlement']) && !empty($data['bank_settlement'])?$data['bank_settlement']:0;
                                            $batch_qry['type'] = 'INSERT';
                                            $batch_qry['predata'] = 'REPLACE INTO retailer_earning_logs(date,ret_user_id,dist_user_id,service_id,amount,earning,expected_earning,tds,bank_settlement,txn_type,txn_type_flag,type,txn_reverse_amt,api_flag,txn_count,closing)VALUES';
                                            
                                            $batch_qry['data'][] = '("'.$date.'","'.$ret_user_id.'","'.$dist_user_id.'","'.$data['service_id'].'",'
                                                    . '"'.$data['amount'].'","'.$data['earning'].'","'.$data['expected_earning'].'","'.$data['tds'].'","'.$bank_settlement.'","'.$data['txn_type'].'","'.$data['txn_type_flag'].'","'.$data['type'].'","'.$data['txn_reverse_amt'].'","'.$data['api_flag'].'","'.$data['txn_count'].'","'.$data['closing'].'")';
                                                    
                                                    if (($i % 100) == 0){ //process batch of 100 queries together
                                                        $this->run_batch($batch_qry);
                                                        $batch_qry = array();
                                                    }
                                                    $i++;
                                        }
                                    }
                                    
                                    if(!empty($batch_qry)) // handled cases if last batch consist less then 100 queries
                                    {
                                        $this->run_batch($batch_qry);
                                        $batch_qry = array();
                                    }
        }
        
        
        private function run_batch($querydata = array()){
            $mainQry = "";
            $curTimestamp = date("Y-m-d H:i:s");
            if($querydata['type']){
                switch (strtoupper($querydata['type'])):
                case 'INSERT':
                    $mainQry .= $querydata['predata'];
                    $mainQry .= implode(",", $querydata['data']);
                    break;
                case 'UPDATE':
                    $mainQry .= $querydata['predata'];
                    $mainQry .= (isset($querydata['vendor']) && $querydata['vendor'])? " vendor_status=(CASE CONCAT(txn_id,'_',vendor_id) ":" vendor_status=(CASE txn_id ";
                    
                    foreach ($querydata['data'] as $txnId => $txndata):
                    $txnId = isset($txndata['vendor_id'])? $txnId."_".$txndata['vendor_id']:$txnId;
                    $mainQry .= "WHEN '$txnId' THEN '".$txndata['vendor_status']."' ";
                    endforeach;
                    
                    $mainQry .= " END) ,";
                    $mainQry .= (isset($querydata['vendor']) && $querydata['vendor'])? " server_status=(CASE CONCAT(txn_id,'_',vendor_id) ":" server_status=(CASE txn_id ";
                    
                    foreach ($querydata['data'] as $txnId => $txndata):
                    $txnId = isset($txndata['vendor_id'])? $txnId."_".$txndata['vendor_id']:$txnId;
                    $mainQry .= "WHEN '$txnId' THEN '".$txndata['server_status']."' ";
                    endforeach;
                    
                    $mainQry .= " END) ,";
                    $mainQry .= (isset($querydata['vendor']) && $querydata['vendor'])? " flag=(CASE CONCAT(txn_id,'_',vendor_id) ":" flag=(CASE txn_id ";
                    
                    foreach ($querydata['data'] as $txnId => $txndata):
                    $txnId = isset($txndata['vendor_id'])? $txnId."_".$txndata['vendor_id']:$txnId;
                    $mainQry .= "WHEN '$txnId' THEN '".$txndata['flag']."' ";
                    endforeach;
                    
                    $mainQry .= " END) , updated_time='$curTimestamp' ";
                    $mainQry .= "WHERE txn_id in (".implode(",",array_keys($querydata['data'])).")";
                    
                    break;
                    endswitch;
                    if(!empty($mainQry)):
                    file_put_contents('/mnt/logs/api_auto_recon.txt', "\n Query :: $mainQry  \n" , FILE_APPEND | LOCK_EX);
                    $this->Retailer->query($mainQry);
                    endif;
            }
        }
        
        function updateAadharDetails(){
            $this->autoRender = false;
//            $aadhar_old1 = $this->Retailer->query('SELECT * FROM imp_label_status_history lsh join imp_label_upload_history luh on (luh.ref_code = lsh.ref_code and luh.label_id = lsh.label_id and luh.user_id = lsh.user_id) WHERE lsh.label_id = 2 and lsh.is_latest = "1" and lsh.user_id not in (79772704,79771066,79770212,79768079,71018359,79773588,44345630,41558652,47805944,79771590,64303740,502444,79766663,79763575,79765673,79748342,79766760,27140631,50331730,23996505,79767218,55436238,18751145,66090121,59317575,23888367,36816267,79711900,43139248,4055602,40555148,79716705,56975142,79718260,78284389,79709798,61963415,79715302,63781908,64444095,79775177,79774999,79771576,76329346,79753036,79729944,79775732,76936078,53107362,79776161,79783426,79783403,79782923,79783514,79783279,79782660,13368604,79785212,68881575,79775733)');
            $aadhar_old1 = $this->Retailer->query('SELECT * FROM imp_label_status_history lsh join imp_label_upload_history luh on (luh.ref_code = lsh.ref_code and luh.label_id = lsh.label_id and luh.user_id = lsh.user_id) WHERE lsh.label_id = 2 and lsh.is_latest = "1" and lsh.updated_date >= "2018-08-22" and lsh.user_id not in (10564847,79781217,79785629)');
            
            foreach ($aadhar_old1 as $data) {
                $label_id1 = '47';
                $label_id2 = '48';
                $date = date('Y-m-d');
                $datetime = date("YmdHis");
                $ref_code1 = $label_id1.$data['lsh']['user_id'].$datetime;
                $ref_code2 = $label_id2.$data['lsh']['user_id'].$datetime;
                $this->Retailer->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code1','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                $this->Retailer->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code2','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                $this->Retailer->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['lsh']['user_id']."','$ref_code1','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");
                $this->Retailer->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['lsh']['user_id']."','$ref_code2','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");
            }
            
//            $aadhar_old2 = $this->Retailer->query('SELECT * FROM imp_label_status_history lsh join imp_label_upload_history luh on (luh.ref_code = lsh.ref_code and luh.label_id = lsh.label_id and luh.user_id = lsh.user_id) WHERE lsh.label_id = 2 and lsh.is_latest = "1" and lsh.user_id in (79772704,79771066,79770212,79768079,71018359,79773588,44345630,41558652,47805944,79771590,64303740,502444,79766663,79763575,79765673,79748342,79766760,27140631,50331730,23996505,79767218,55436238,18751145,66090121,59317575,23888367,36816267,79711900,43139248,4055602,40555148,79716705,56975142,79718260,78284389,79709798,61963415,79715302,63781908,64444095,79775177,79774999,79771576,76329346,79753036,79729944,79775732,76936078,53107362,79776161,79783426,79783403,79782923,79783514,79783279,79782660,13368604,79785212,68881575,79775733)');
            $aadhar_old2 = $this->Retailer->query('SELECT * FROM imp_label_status_history lsh join imp_label_upload_history luh on (luh.ref_code = lsh.ref_code and luh.label_id = lsh.label_id and luh.user_id = lsh.user_id) WHERE lsh.label_id = 2 and lsh.is_latest = "1" and lsh.updated_date >= "2018-08-22" and lsh.user_id in (10564847,79781217,79785629)');
            foreach ($aadhar_old2 as $data) {
                $description = $data['luh']['description'];
                $label_id1 = '47';
                $label_id2 = '48';
                $date = date('Y-m-d');
                $datetime = date("YmdHis");
                $ref_code1 = $label_id1.$data['lsh']['user_id'].$datetime;
                $ref_code2 = $label_id2.$data['lsh']['user_id'].$datetime;
                $str = '_2_2_';
                if(strpos($description,$str) !== false){
                    $this->Retailer->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code2','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                    $this->Retailer->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id2','".$data['lsh']['user_id']."','$ref_code2','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");
                    
                }else{
                    $this->Retailer->query("INSERT INTO imp_label_upload_history(label_id,user_id,service_id,ref_code,description,uploaded_by,ip_address,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['luh']['user_id']."','".$data['luh']['service_id']."','$ref_code1','".$data['luh']['description']."','".$data['luh']['uploaded_by']."','".$data['luh']['ip_address']."','" . $date . "','" . $datetime . "') ");
                
                    $this->Retailer->query("INSERT INTO imp_label_status_history(label_id,user_id,ref_code,pay1_status,bank_status,uploaded_by,created_date,created_at)"
                                                . "VALUES('$label_id1','".$data['lsh']['user_id']."','$ref_code1','".$data['lsh']['pay1_status']."','".$data['lsh']['bank_status']."','".$data['lsh']['uploaded_by']."','" . $date . "','" . $datetime . "')");
                }
            }
            
        }
}