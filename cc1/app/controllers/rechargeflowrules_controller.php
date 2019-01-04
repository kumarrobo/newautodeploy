<?php
class RechargeFlowRulesController extends AppController {

    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
    var $uses = array('User', 'Slaves');
    var $components = array('RequestHandler','Shop','General');



    function beforeFilter() {
        $this->layout = 'plain';
        $operators = $this->Slaves->query('SELECT id,name FROM products WHERE active = 1 AND to_show = 1');
        $vendors = $this->Slaves->query('SELECT id,company FROM vendors WHERE show_flag = 1');
        $circles = $this->Slaves->query('SELECT area_code,area_name FROM mobile_numbering_area');

        foreach ($operators as $operator) {
            $this->operators[$operator['products']['id']] = $operator['products']['name'];
        }
        foreach ($vendors as $vendor) {
            $this->vendors[$vendor['vendors']['id']] = $vendor['vendors']['company'];
        }
        foreach ($circles as $circle) {
            $this->circles[$circle['mobile_numbering_area']['area_code']] = $circle['mobile_numbering_area']['area_name'];
        }

        $this->primary_denomination_options = array(
            '0'=> array(
                'value'=>10,
                'label'=>'10'
            ),
            '1'=>array(
                'value'=>20,
                'label'=>'20'
            ),
            '2'=> array(
                'value'=>200,
                'label'=>'200'
            ),
            '3' => array(
               'value'=>150,
                'label'=>'150'
            )
        );
        $this->active_denomination_options = array(
            '0'=> array(
                'value'=>10,
                'label'=>'10'
            ),
            '1'=>array(
                'value'=>20,
                'label'=>'20'
            ),
            '2'=> array(
                'value'=>200,
                'label'=>'200'
            ),
            '3' => array(
               'value'=>150,
                'label'=>'150'
            )
        );
        $this->inactive_denomination_options = array(
            '0'=> array(
                'value'=>10,
                'label'=>'10'
            ),
            '1'=>array(
                'value'=>20,
                'label'=>'20'
            ),
            '2'=> array(
                'value'=>200,
                'label'=>'200'
            ),
            '3' => array(
               'value'=>150,
                'label'=>'150'
            )
        );

        $this->statuses = array(
            '0' => 'Active',
            '1' => 'Inactive'
        );
        $this->types = array(
            '1' => 'Basic Rules',
            '2' => 'Advanced Rules'
        );

        parent::beforeFilter();
    }

    function index($recs = 100){
//        echo 'asdf';exit;

//        echo 'File: ' . __FILE__ . ' LINE: ' . __LINE__ . '<pre>';
//       print_r($this->Shop->getProdInfo(6));
//        echo '</pre>';
//       exit();

//            $query = trim($this->params['url']['q']);
            $query_condition = '';
            if($this->params['form']['vendors'] != '') {
                $query_condition .= ' AND vendor_id IN ('.$this->params['form']['vendors'].')';
                $this->set('filter_vendors',$this->params['form']['vendors']);
            }
            if($this->params['form']['operators'] != '') {
                $query_condition .= ' AND product_id IN ('.$this->params['form']['operators'].')';
                $this->set('filter_operators',$this->params['form']['operators']);
            }
            if($this->params['form']['status'] != '') {
                if( $this->params['form']['status'] == 0 ){
                    $query_condition .= ' AND oprDown = 0';
                } else {
                    $query_condition .= ' AND oprDown != 0';
                }
                $this->set('filter_status',$this->params['form']['status']);
            }
            if($this->params['form']['type'] != '') {
                if( $this->params['form']['type'] == 2 ){ // advanced rules
                    $query_condition .= ' AND ( (circles_yes IS NOT NULL AND circles_yes  != "")'
                            . ' OR  (circles_no IS NOT NULL AND circles_no  != "")'
                            . ' OR  (denom_circle IS NOT NULL AND denom_circle  != "")'
                            . ' OR  (denom_primary IS NOT NULL AND denom_primary  != "")'
                            . ' OR  (denom_yes IS NOT NULL AND denom_yes  != "")'
                            . ' OR  (denom_no IS NOT NULL AND denom_no  != "")'
                            . ' OR  (from_STD != "00:00:00")'
                            . ' OR  (to_STD != "00:00:00")'
                            . ' OR  (distributor_ids IS NOT NULL AND distributor_ids  != "")'
                            . ' OR  (retailer_ids IS NOT NULL AND retailer_ids  != "")'
                            . ' )';
                } else {                                    // basic rules
                    $query_condition .= ' AND ( (circles_yes IS NULL OR circles_yes = "")'
                            . ' AND (circles_no IS NULL OR circles_no = "")'
                            . ' AND (denom_circle IS NULL OR denom_circle = "")'
                            . ' AND (denom_primary IS NULL OR denom_primary = "")'
                            . ' AND (denom_yes IS NULL OR denom_yes = "")'
                            . ' AND (denom_no IS NULL OR denom_no = "")'
                            . ' AND (from_STD = "00:00:00")'
                            . ' AND (to_STD = "00:00:00")'
                            . ' AND (distributor_ids IS NULL OR distributor_ids = "")'
                            . ' AND (retailer_ids IS NULL OR retailer_ids = "") )'
                            . ' AND ((vendor_id IS NOT NULL AND vendor_id != "")'
                            . ' OR (product_id IS NOT NULL AND product_id != "")'
                            . ' OR (circle IS NOT NULL AND circle != "")'
                            . ' )';
                }
                $this->set('filter_type',$this->params['form']['type']);
            }
//            if($query != ''){
//                $query_condition = 'AND (template LIKE "%'.$query.'%" OR template1 LIKE "%'.$query.'%" OR id = "'.str_ireplace('#','',$query).'"';
//
//                if( in_array(strtolower($query),array_map('strtolower',$this->providers)) ){
//                    $opr_id = array_search( strtolower($query),array_map('strtolower',$this->providers) );
//                    if( is_numeric($opr_id) ){
//                        $query_condition .= ' OR opr_id = '.$opr_id;
//                    }
//
//                }
//                $query_condition .= ')';
//            }

        $recharge_flow_rules = $this->paginate_query('SELECT * FROM vendors_commissions WHERE 1=1 '.$query_condition.' order by id DESC', $recs );
        $this->set('operators',$this->operators);
        $this->set('vendors',$this->vendors);
        $this->set('circles',$this->circles);
        $this->set('statuses',$this->statuses);
        $this->set('types',$this->types);
        $this->set('primary_denomination_options',$this->primary_denomination_options);
        $this->set('active_denomination_options',$this->active_denomination_options);
        $this->set('inactive_denomination_options',$this->inactive_denomination_options);
        $this->set('recharge_flow_rules',$recharge_flow_rules);

    }
    function getDistributors(){
        if($this->RequestHandler->isAjax()){
            $this->autoRender = false;
            $distributors = $this->Slaves->query('SELECT id,company FROM distributors WHERE toshow=1 AND active_flag = 1 order by company');

            /** IMP DATA ADDED : START**/
            $dist_ids = array_map(function($element){
                return $element['distributors']['id'];
            },$distributors);
            $imp_data = $this->Shop->getUserLabelData($dist_ids,2,3);
            foreach($distributors as $key => $d){
                $distributors[$key]['distributors']['company'] = $imp_data[$d['distributors']['id']]['imp']['shop_est_name'];
            }
            /** IMP DATA ADDED : END**/

            echo json_encode($distributors);
        }
    }
    function getRetailers(){
        if($this->RequestHandler->isAjax()){
            $this->autoRender = false;
            $distributor_condition = '';
            if($this->params['form']['distributor_id']){
                $distributor_condition = 'AND ret.parent_id IN('.$this->params['form']['distributor_id'].')';
            }
            $retailers_condition = '';
            if($this->params['form']['retailers']){
                $retailers_condition = 'AND ret.id IN('.$this->params['form']['retailers'].')';
            }
//            $retailers = $this->Slaves->query('SELECT '
//                    . 'ret.id as id,ret.parent_id,ret.name as name,ret.area as area,loc.name as circle '
//                    . 'FROM retailers ret '
//                    . 'LEFT JOIN locator_area loc '
//                    . 'ON(loc.id = ret.area_id) '
//                    . 'WHERE 1=1 '.$distributor_condition.' '.$retailers_condition);
            $retailers = $this->Slaves->query('SELECT '
                    . 'ret.id as id,ret.parent_id,ret.name as name,ret.area as area,LEFT(ret.mobile,5) as mobile '
                    . 'FROM retailers ret '
                    . 'WHERE toshow = 1 '.$distributor_condition.' '.$retailers_condition . " order by shopname asc, mobile asc");

            $ret_ids = array_map(function($element){
                return $element['ret']['id'];
            },$retailers);

            /** IMP DATA ADDED : START**/
            $imp_data = $this->Shop->getUserLabelData($ret_ids,2,2);
            /** IMP DATA ADDED : END**/


            $mobiles = array();
            foreach ($retailers as $key => $retailer) {
                $mobiles[] = $retailer[0]['mobile'];
                $retailers[$key]['ret']['name'] = $imp_data[$retailer['ret']['id']]['imp']['name'];
            }
            $mobile_condition = '';

            if( count($mobiles) > 0 ){
                $mobile_condition = 'AND mnam.number IN('.implode(',',$mobiles).')';
            }
            $circles = $this->Slaves->query('SELECT '
                    . 'mna.area_name as circle,mnam.number as number '
                    . 'FROM mobile_numbering_area mna '
                    . 'LEFT JOIN mobile_operator_area_map mnam '
                    . 'ON(mna.area_code = mnam.area) '
                    . 'WHERE 1=1 '.$mobile_condition);
            $circle_numbers = array();
            foreach ($circles as $circle) {
                $circle_numbers[$circle['mnam']['number']] = $circle['mna']['circle'];
            }
//            if( count($circle_numbers) > 0 ){
                foreach ($retailers as $key => $retailer) {
                    $retailers[$key]['loc']['circle'] = '';
                    if(array_key_exists($retailer[0]['mobile'], $circle_numbers)){
                        $retailers[$key]['loc']['circle'] = $circle_numbers[$retailer[0]['mobile']];
                    }
                    unset( $retailers[$key][0]);
                }
//            }
            echo json_encode($retailers);
        }
    }
    function saveRule(){

        if($this->RequestHandler->isAjax()){
            $this->autoRender = false;
            $retailers_to_be_mapped = $this->params['form']['retailers_to_be_mapped'];
            $distributors_to_be_mapped = $this->params['form']['distributors_to_be_mapped'];
            $vendor = $this->params['form']['vendor'];
            $operator = $this->params['form']['operator'];
            $primary_circle = $this->params['form']['primary_circle'];
            $active_circle = $this->params['form']['active_circle'];
            $inactive_circle = $this->params['form']['inactive_circle'];
            $denomination_circle = $this->params['form']['denomination_circle'];
            $primary_denomination = $this->params['form']['primary_denomination'];
            $denomination_on = $this->params['form']['denomination_on'];
            $denomination_off = $this->params['form']['denomination_off'];
            $operation_from = $this->params['form']['operation_from'];
            $operation_to = $this->params['form']['operation_to'];
            $id = $this->params['form']['index'];
            if( $id == 0 ){
//                try {
                    $response = $this->User->query('INSERT INTO vendors_commissions('
                    . 'vendor_id,'
                    . 'product_id,'
                    . 'retailer_ids,'
                    . 'distributor_ids,'
                    . 'circle,'
                    . 'circles_yes,'
                    . 'circles_no,'
                    . 'denom_circle,'
                    . 'denom_primary,'
                    . 'denom_yes,'
                    . 'denom_no,'
                    . 'from_STD,'
                    . 'to_STD'
                    . ') VALUES ('
                    . ''.$vendor.','
                    . ''.$operator.','
                    . '"'.implode(',',$retailers_to_be_mapped).'",'
                    . '"'.implode(',',$distributors_to_be_mapped).'",'
                    . '"'.$primary_circle.'",'
                    . '"'.implode(',',$active_circle).'",'
                    . '"'.implode(',',$inactive_circle).'",'
                    . '"'.$denomination_circle.'",'
                    . '"'.implode(',',$primary_denomination).'",'
                    . '"'.implode(',',$denomination_on).'",'
                    . '"'.implode(',',$denomination_off).'",'
                    . '"'.$operation_from.'",'
                    . '"'.$operation_to.'")');

//                } catch (Exception $exc) {
//                    return json_encode($exc->getTraceAsString());
//                }
                    if($response){
                        $this->Shop->setProdInfo($operator);

//                        $this->notify($this->params['form']);

                        $result = $this->Slaves->query('SELECT MAX(id) as rule_id FROM vendors_commissions');
                        return json_encode($result[0][0]['rule_id']);

                    }

            } else {
//                try {
                    $response = $this->User->query('UPDATE vendors_commissions SET'
                    . ' vendor_id = '.$vendor.','
                    . ' product_id = '.$operator.','
                    . ' retailer_ids = "'.implode(',',$retailers_to_be_mapped).'",'
                    . ' distributor_ids = "'.implode(',',$distributors_to_be_mapped).'",'
                    . ' circle = "'.$primary_circle.'",'
                    . ' circles_yes = "'.implode(',',$active_circle).'",'
                    . ' circles_no = "'.implode(',',$inactive_circle).'",'
                    . ' denom_circle = "'.$denomination_circle.'",'
                    . ' denom_primary = "'.implode(',',$primary_denomination).'",'
                    . ' denom_yes = "'.implode(',',$denomination_on).'",'
                    . ' denom_no = "'.implode(',',$denomination_off).'",'
                    . ' from_STD = "'.$operation_from.'",'
                    . ' to_STD = "'.$operation_to.'" '
                        . 'WHERE id='.$id);
//                } catch (Exception $exc) {
//                    echo $exc;
//                    exit;
//                    return json_encode($exc->getTraceAsString());
//                }
//                echo mysqli_errno();
                    if($response){
                        $this->Shop->setProdInfo($operator);
//                        $this->notify($this->params['form']);
                        return json_encode($id);
                    }
            }
            return json_encode(false);
        }
    }
    function notify($other_details){


        $vendor_id = $other_details['vendor'];
        $operator_id = $other_details['operator'];
        $primary_circle_code = $other_details['primary_circle'];
        $active_circle_codes = $other_details['active_circle'];
        $inactive_circle_codes = $other_details['inactive_circle'];
        $denomination_circle_code = $other_details['denomination_circle'];
        $primary_denomination = $other_details['primary_denomination'];
        $denomination_on = $other_details['denomination_on'];
        $denomination_off = $other_details['denomination_off'];
        $operation_from = $other_details['operation_from'];
        $operation_to = $other_details['operation_to'];

        $vendor = array_key_exists($vendor_id,$this->vendors) ? $this->vendors[$vendor_id] : '';
        $operator = array_key_exists($operator_id,$this->operators) ? $this->operators[$operator_id] : '';
        $primary_circle = array_key_exists($primary_circle_code,$this->circles) ? $this->circles[$primary_circle_code] : '';
        $active_circles = array();
        $inactive_circles = array();
        foreach ($active_circle_codes as $active_circle_code) {
            $active_circles[] = array_key_exists($active_circle_code,$this->circles) ? $this->circles[$active_circle_code] : '';
        }
        foreach ($inactive_circle_codes as $inactive_circle_code) {
            $inactive_circles[] = array_key_exists($inactive_circle_code,$this->circles) ? $this->circles[$inactive_circle_code] : '';
        }

        $denomination_circle = array_key_exists($denomination_circle_code,$this->circles) ? $this->circles[$denomination_circle_code] : '';

        if( ($vendor != '') && ($operator != '') ){
            $sms = 'Vendor: '.$vendor.'
Operator: '.$operator.'
PC: '.$primary_circle.'
AC: '.implode(',',$active_circles).'
IC: '.implode(',',$inactive_circles).'
DC: '.$denomination_circle.'
PD: '.implode(',',$primary_denomination).'
AD: '.implode(',',$denomination_on).'
ID: '.implode(',',$denomination_off).'
OPF: '.$operation_from.'
OPT: '.$operation_to;
//            $sms = "Denomination Circle has been updated to <b>".$denomination_circle."</b> for \n vendor <b>".$vendor."</b>, operator <b>".$operator."</b>.";
            $receivers = array('8898502638');
            $this->General->sendMessage($receivers,$sms,'shops');
            return TRUE;
        }
        return FALSE;

    }
//    function toggleRule(){
//        if($this->RequestHandler->isAjax()){
//            $this->autoRender = false;
//            $rule_id = $this->params['form']['rule_id'];
//            $status = $this->params['form']['status'];
//
//            if( $rule_id && is_numeric($rule_id) ){
//
//                $response = $this->User->query('UPDATE vendors_commissions SET'
//                    . ' is_disabled = '.$status
//                    . ' WHERE id='.$rule_id);
//                $this->Shop->setProdInfo($operator);
//                return json_encode($response);
//            }
//        }
//    }
    function toggleRuleForRetailers(){
        if($this->RequestHandler->isAjax()){
            $this->autoRender = false;
            $rule_id = $this->params['form']['rule_id'];
            $status = $this->params['form']['status'];
            $operator = $this->params['form']['product'];

            if( $rule_id && is_numeric($rule_id) ){

                $response = $this->User->query('UPDATE vendors_commissions SET'
                    . ' is_disabled = '.$status
                    . ' WHERE id='.$rule_id);
                $this->Shop->setProdInfo($operator);
                return json_encode($response);
            }
        }
    }
}
