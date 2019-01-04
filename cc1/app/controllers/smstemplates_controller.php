<?php
class SmstemplatesController extends AppController {

	var $name = 'Smstemplates';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator');
	var $uses = array('User','Group', 'Slaves');
	var $components = array('RequestHandler','Shop');
        
        
        
	function beforeFilter() {
                $this->layout = 'plain';
                $providers = $this->Slaves->query('SELECT id,name FROM products WHERE active = 1 AND to_show = 1');
                
                foreach ($providers as $provider) {
                    $this->providers[$provider['products']['id']] = $provider['products']['name'];
                }
                $this->types = array(
                        'failure'=>'failure',
                        'success'=>'success',
                        'balance'=>'balance',
                        'stop'=>'stop',
                        'sale'=>'sale'
                );
                $this->type_flags = array(
                    'failure'=> array(
                        '1'=>'Invalid Amount',
                        '2'=>'Try after some time',
                        '3'=>'Wrong mobile number/subsriber ID',
                        '4'=>'No balance in sim',
                        '5'=>'Reason not known',
                        '6'=>'Special Recharge'
                    ),
                    'success'=>array(
                      '1' => 'success',  
                    ),
                    'balance'=> array(
                        '1' => 'Balance Transfer',
                        '2' => 'Sim Balance',
                        '3' => 'Incentive'
                    ),
                    'stop' => array(
                        '1' => 'stop'
                    ),
                    'sale' => array(
                        '1' => 'sale'
                    )
                );
		parent::beforeFilter();
		
        }
        function index(){
            $query = trim($this->params['url']['q']);
            $query_condition = '';
            if($query != ''){
                $query_condition = 'AND (template LIKE "%'.$query.'%" OR template1 LIKE "%'.$query.'%" OR id = "'.str_ireplace('#','',$query).'"';
                
                if( in_array(strtolower($query),array_map('strtolower',$this->providers)) ){
                    $opr_id = array_search( strtolower($query),array_map('strtolower',$this->providers) );
                    if( is_numeric($opr_id) ){
                        $query_condition .= ' OR opr_id = '.$opr_id;
                    }
                    
                }
                if( in_array(strtolower($query),array_map('strtolower',$this->types)) ){
                    $type = array_search( strtolower($query),array_map('strtolower',$this->types) );
                    if( is_numeric($type) ){
                        $query_condition .= ' OR type = '.$type;
                    }
                    
                }
                foreach ($this->type_flags as $type => $flags) {
                    if( in_array(strtolower($query),array_map('strtolower',$flags)) ){
                        $type_flag = array_search( strtolower($query),array_map('strtolower',$flags) );
                        if( is_numeric($type_flag) ){
                            $query_condition .= ' OR (type_flag = '.$type_flag.' AND type = '.$type.')';
                        }
                    }
                }
                $query_condition .= ')';
            }
    
           
            $templates = $this->paginate_query('SELECT * FROM sms_templates WHERE 1=1 AND deleted_at IS NULL '.$query_condition.' order by id', 10 );
            $this->set('providers',$this->providers);
            $this->set('types',$this->types);
            $this->set('type_flags',$this->type_flags);
            $this->set('templates',$templates);
        }
        function add(){
            if($this->RequestHandler->isPost()){
                $operator_id = $this->params['form']['operator'];
                $sms = $this->params['form']['sms'];
                $template = $this->params['form']['template'];
                $type = $this->params['form']['type'];
                $type_flag = $this->params['form']['type_flag'];
                $page = $this->params['form']['page'];
                $query = '';
                if($this->params['form']['query'] != ''){
                    $query = '&q='.$this->params['form']['query'];
                }
                
                $response = $this->User->query('INSERT INTO sms_templates (opr_id,template,template1,type,type_flag,created_at,datetime) '
                        . 'VALUES ('.$operator_id.',"'.$sms.'","'.$template.'","'.$type.'",'.$type_flag.',"'.date('Y-m-d H:i:s').'","'.date('Y-m-d H:i:s',strtotime("+30 minutes")).'")');
                
                if($response){
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong>Template has been saved.', 'default',array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                } else {
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Fail! </strong>Couldn\'t save the template. Please try again.', 'default',array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                }
            }
            $this->set('types',$this->types);
            $this->set('providers',$this->providers);
            $this->set('type_flags',$this->type_flags);
            $this->set('mode','add');
            $this->render('add_edit');
        }
        function edit(){
            if($this->RequestHandler->isGet()){
                $id = $this->params['pass'][0];
                $template = $this->Slaves->query('SELECT * FROM sms_templates WHERE id='.$id.' LIMIT 1');
                $this->set('types',$this->types);
                $this->set('providers',$this->providers);
                $this->set('type_flags',$this->type_flags);
                $this->set('template',$template[0]['sms_templates']);
                $this->set('mode','edit');
                $this->render('add_edit');
            }
        }
        function update(){
            if($this->RequestHandler->isPost()){
                $id = $this->params['form']['template_id'];
                $operator_id = $this->params['form']['operator'];
                $sms = $this->params['form']['sms'];
                $template = $this->params['form']['template'];
                $type = $this->params['form']['type'];
                $type_flag = $this->params['form']['type_flag'];
                $page = $this->params['form']['page'];
                $query = '';
                if($this->params['form']['query'] != ''){
                    $query = '&q='.$this->params['form']['query'];
                }
                
                $response = $this->User->query('UPDATE sms_templates SET opr_id = '.$operator_id.','
                                        . 'template = "'.$sms.'",'
                                        . 'template1 = "'.$template.'",'
                                        . 'type = "'.$type.'",'
                                        . 'type_flag = '.$type_flag.','
                                        . 'datetime = "'.date('Y-m-d H:i:s',strtotime("+30 minutes")).'",'
                                        . 'updated_at = "'.date('Y-m-d H:i:s').'" '
                                        . 'WHERE id='.$id);
                if($response){
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong>Template #'.$id.' has been updated.', 'default',array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                } else {
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Fail! </strong>Couldn\'t update the template. Please try again.', 'default',array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                }
                
            }
        }
        function delete(){
            if($this->RequestHandler->isGet()){
                $id = $this->params['pass'][0];
                $page = $this->params['url']['page'];
                $query = '';
                if($this->params['url']['q'] != ''){
                    $query = '&q='.$this->params['url']['q'];
                }
          
                $response = $this->User->query('UPDATE sms_templates SET deleted_at = "'.date('Y-m-d H:i:s').'",deleted_by = '.$this->Session->read('Auth.User.id').' '
                                        . 'WHERE id='.$id);
                if($response){
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong>Template deleted successfully.', 'default',array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                } else {
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Fail! </strong>Couldn\'t delete the template. Please try again.', 'default',array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                }
            }
        }
        
        function verify(){
            if($this->RequestHandler->isGet()){
                
                    $id = $this->params['pass'][0];
                    $page = $this->params['url']['page'];
                    $query = $this->params['url']['q'];
                    $query = '';
                    if($this->params['url']['q'] != ''){
                        $query = '&q='.$this->params['url']['q'];
                    }
                    $smstemplate = $this->Slaves->query('SELECT template,template1 FROM sms_templates WHERE id='.$id.' LIMIT 1');
                    if($smstemplate){
                        $varStart="@__";
                        $varEnd="__@";

                        $sms = trim($smstemplate[0]['sms_templates']['template']);
                        $template = trim($smstemplate[0]['sms_templates']['template1']);

                        $template = str_replace($varStart,"|~|",$template);
                        $template = str_replace($varEnd,"|~|",$template);

                        $t=explode("|~|",$template);

                        $vars = array();
                        $ret = true;
                        $i = 0;
                        $start = 0;
                        $log = "";

                        $out['sms'] = $sms;
                        for($i=0;$i<=count($t);$i=$i+2){
                            if($t[$i] == null){
                                if($i != 0){
                                    $vars[$start] = $sms;
                                    $vars[$t[$i-1]] = $sms;
                                }
                            }
                            else {
                                $log .= "Checking ".$t[$i];
                                $index = stripos($sms,$t[$i]);
                                $log .= ": $index\n";
                                if($index === false){
                                    $ret = false;
                                    break;
                                }
                                else {
                                    $var = substr($sms,0,$index);
                                    if($i != 0){
                                        $vars[$start] = trim($var);
                                        $vars[$t[$i-1]] = trim($var);
                                        $start++;
                                    }
                                    $sms = substr($sms,$index+strlen($t[$i]));
                                }
                            }
                        }

                        if(count($t) == 1 && $out['sms'] != $template){
                            $ret = false;
                        }

                        if($ret){
                            $out['status'] = 'success';
                            $out['vars'] = $vars;
                        }
                        else {
                            $out['status'] = 'failure';
                            $out['vars'] = $vars;
                        }
                        $out['logs'] = $log;
                        
                       
                        if($out['status'] == 'success'){
                            foreach ($out['vars'] as $key => $value) {
                                if (is_numeric($key)) {
                                    unset($out['vars'][$key]);
                                }
                            }
                            $out['vars']['class'] = 'alert alert-success';
                            $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! Message </strong>and <strong>Template</strong> match.', 'default',$out['vars']);
                        } else {
                            $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Fail! Message </strong> and <strong>Template</strong> does not match.', 'default',array('class' => 'alert alert-danger'));
                        }
                        
                        $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                    }
                }
            }
            
            function verifyWithOthers(){
                if($this->RequestHandler->isGet()){
                
                    $id = $this->params['pass'][0];
                    $page = $this->params['url']['page'];
                    $query = '';
                    if($this->params['url']['q'] != ''){
                        $query = '&q='.$this->params['url']['q'];
                    }
                    $sms_result = $this->Slaves->query('SELECT template FROM sms_templates WHERE id='.$id.' LIMIT 1');
                    $other_templates = $this->Slaves->query('SELECT id,template1 FROM sms_templates WHERE id != '.$id);
                    
                   
                    foreach ($other_templates as  $smstemplate) {
                        $sms = trim($sms_result[0]['sms_templates']['template']);
                        if($sms){



                            $varStart="@__";
                            $varEnd="__@";

                            $template = trim($smstemplate['sms_templates']['template1']);
                            $template = str_replace($varStart,"|~|",$template);
                            $template = str_replace($varEnd,"|~|",$template);

                          

                            $t=explode("|~|",$template);

                            $vars = array();
                            $ret = true;
                            $i = 0;
                            $start = 0;
                            $log = "";

                            $out['sms'] = $sms;
                            for($i=0;$i<=count($t);$i=$i+2){
                                if($t[$i] == null){
                                    if($i != 0){
                                        $vars[$start] = $sms;
                                        $vars[$t[$i-1]] = $sms;
                                    }
                                }
                                else {
                                    $log .= "Checking ".$t[$i];
                                    $index = stripos($sms,$t[$i]);
                                    $log .= ": $index\n";
                                    if($index === false){
                                        $ret = false;
                                        break;
                                    }
                                    else {
                                        $var = substr($sms,0,$index);
                                        if($i != 0){
                                            $vars[$start] = trim($var);
                                            $vars[$t[$i-1]] = trim($var);
                                            $start++;
                                        }
                                        $sms = substr($sms,$index+strlen($t[$i]));
                                    }
                                }
                            }

                            if(count($t) == 1 && $out['sms'] != $template){
                                $ret = false;
                            }

                            if($ret){
                                $out['status'] = 'success';
                                $out['vars'] = $vars;
                            }
                            else {
                                $out['status'] = 'failure';
                                $out['vars'] = $vars;
                            }
                            $out['logs'] = $log;

                            if($out['status'] == 'success'){
                                foreach ($out['vars'] as $key => $value) {
                                    if (is_numeric($key)) {
                                        unset($out['vars'][$key]);
                                    }
                                }
                                $out['vars']['class'] = 'alert alert-success';
                                $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong>Message matches with <strong>Template</strong> #'.$smstemplate['sms_templates']['id'].'.', 'default',$out['vars']);
                                $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                            } 
                        }
                    }
                    $this->Session->setFlash('<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Fail! Message </strong> does not match with any other <strong>Template</strong>.', 'default',array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'smstemplates', 'action' => 'index?page='.$page.$query));
                }
            }
            
        }