<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap-responsive.min.css?990' />
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />

<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("#content").removeClass("container");
jQuery("#content").addClass("container-fluid");
});
</script>
<a href="/sims" target="_blank" class="btn btn-default btn-info btn-sm" style="color: white">Click here to view new Panel</a><br/>
Vendor Status:
<style type="text/css">
    body .modal {
        /* new custom width */
        width: 800px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -370px;
    }
</style>

<table style="margin-bottom:20px;" class="table table-bordered"><tr width='100%'>
<?php $i=0; if(isset($prods)){foreach($prods as $prod => $val) { if($i%8 == 0) echo "</tr><tr width='100%'>";
	if(intval($val['failure']*100/$val['total']) > 20 || $val['count'] == 0) $bgcolor = '#c73525';
	else $bgcolor = '';
?>
	<td width='150px' bgcolor='<?php echo $bgcolor; ?>'><b><?php echo $val['name'];?></b><br/><?php echo $val['vendor'] . " (" . intval($val['count']*100/$val['total']) . "%)" ;?><br/><span><?php echo "Failure: (" . intval($val['failure']*100/$val['total']) . "%)" ;?></span></td>
<?php $i++;}} ?>
<td width='150px'></td>
<td width='150px'></td>
<td width='150px'></td>
<td width='150px'></td>
</tr>
</table>

<style type="text/css">
    .stopped{
      background-color: #c73525;
      color: #fffff;
    }
    .running{
        background-color: #99ff99;
        color:#ffffff;
    }
</style>
<table style="margin-bottom:20px;" class="table table-bordered">
<tr width='100%'>
<?php
$c_tmp = time();
if(isset($last)){
foreach($last as $lt){
$sts = ( $c_tmp - strtotime($lt['timestamp']) ) > 900 ? "stopped" : "running";
?>
<td width='150px' class="<?php echo $sts; ?>"><?php echo strtoupper($lt['shortForm']) . " (" . date("H:i:s",strtotime($lt['timestamp'])); ?> )</td>
<?php }} ?>
</tr>
</table>

<?php 
	$body = "";
    $total = '';
	foreach($modems as $key=>$modem){
	if($_SESSION['Auth']['User']['group_id'] != 9){
		$body .= "<br/><a href='http://".$ips[$key]."/phpmyadmin' target='_blank'>" . $map[$key] . " Balances:</a> <a href='/sims/allBalance/".$key."' target='_blank'> Sims:</a> | <a href='javascript:void(0)' onclick='runReboot(".$key.")' id='reboot_".$key."'>reboot</a>&nbsp;&nbsp;&nbsp;<a href='#deviceInsertModal' role='button' data-toggle='modal'  id='insertdev_".$key."' >Insert Device</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='downloadLastTrans(\"".$ips[$key]."\")' id='_".$key."'>Download Transactions</a>";
	}
	if(isset($modem['lasttime']))$body .= " (".$modem['lasttime'].")";
	if(isset($modem['ports'])){
		$ports_data = json_decode($modem['ports'],true);
		$body .= " [Total Ports: ".$ports_data['total']. ", Detected: ".$ports_data['ports'] . "]";
	}
	$modem_bal = 0;
	$opening =0; $closing=0; $tfr = 0; $sale =0; $diff_tot = 0;$inc=0;
	$bg_table = "";
	if(isset($modem['inactive']) && $modem['inactive'] == 1)$bg_table = "#f9e1e1";
	$body .= "<br/><table class='table table-bordered' bgcolor='".$bg_table."'>";
	$body .= "<tr> 
				<th>Dev/ Machine/ Port</th>
				<th>Signal</th>
				<th>Vendor</th>
				<th>Operator</th>
				<th>Number</th>
				<th>Margin</th>
				<th>Curr Bal</th>
				<th>Opening</th>
	    		<th>Closing</th>
	    		<th>Incoming</th>
	    		<th>Sale</th>
	    		<th>Roaming/Limit</th>
	    		<th>Inc</th>
	    		<th>Diff</th>
	    		<th>Succ %</th>
	    		<th>Last Succ</th>
	    		<th>Prcs time</th>
                        <th>Active</th>
	    		<th>Action1</th>
	    		<th>Action2</th>
	    		<th>Action3</th>
	    		<th>Action4</th>
                <!--        <th>Action5</th>-->
			</tr>";
	foreach($modem as $md){
		if(!is_array($md))continue;
		$color = '';
		if($md['active_flag'] == 1 && $md['balance'] < 3000)$color = '#8c65e3';
		else if($md['active_flag'] == 0 && $md['balance'] > 3000) $color = '#c73525';
		else if($md['active_flag'] == 1 && $md['balance'] > 3000 && date('Y-m-d H:i:s',strtotime('-45 minutes')) > $md['last'])$color = '#f6ff00';
		else if($md['active_flag'] == 1)$color = '#99ff99';
		$body .= "<tr id='device".$md['id']."_".$key."' bgcolor='$color'>";
		$body .= "<td>".$md['id']."/".$md['machine_id']."/".$md['device_num']."</td>";
		$body .= "<td>".$md['signal']."</td>";
		$body .= "<td>".$md['vendor_tag']."/".$md['vendor']."</td>";
		$body .= "<td>".$md['operator']."</td>";
		$body .= "<td>".$md['mobile']."</td>";
		$body .= "<td>".$md['commission']."%</td>";
		$body .= "<td>".$md['balance']."</td>";
		$body .= "<td>".((isset($md['opening']))?$md['opening']:"")."</td>";
		$body .= "<td>".((isset($md['closing']))?$md['closing']:"")."</td>";
		$body .= "<td>".$md['tfr']."</td>";
		$body .= "<td>".$md['sale']."</td>";
		if($md['roaming_limit'] > 0 || $md['limit'] > 0){
			$body .= "<td>".$md['roaming_today']."/".$md['limit_today']."</td>";
		}
		else $body .= "<td></td>"; 
		$body .= "<td>".intval($md['inc'])."</td>";
		
		$open = (isset($md['opening']))?$md['opening']:0;
		$close = (isset($md['closing']))?$md['closing']:0;
		$opening += $open;
		$closing += $close;
		$inc += intval($md['inc']);
		$tfr += $md['tfr'];
		$sale += $md['sale'];
		
		if($date != date('Y-m-d')){
			$diff = $md['sale'] - ($open + $md['tfr'] -  $close);
		}
		else {
			$diff = $md['sale'] - ($open + $md['tfr'] -  $md['balance']);
		}
		
		$diff = $diff - $md['inc'];
		$diff_tot += $diff;
		$body .= "<td>".intval($diff)."</td>";
		if($md['success'] > 0){
			$body .= "<td>".$md['success']."%</td>";
		}
		else {
			$body .= "<td></td>";
		}
		if(!empty($md['last'])){
			$body .= "<td>".date('H:i:s',strtotime($md['last']))."</td>";
		}
		else {
			$body .= "<td></td>";
		}
		if(!empty($md['process_time'])){
			$body .= "<td>".$md['process_time']." secs</td>";
		}
		else {
			$body .= "<td></td>";
		}
                
                $body .= "<td>".$md['active_flag']."</td>";
                
		//$body .= "<td id='reset".$md['id']."_".$key."'><a href='javascript:void(0)' onclick='resetDevice(".$md['id'].",".$key.")'>Reset</a></td>";
		if($md['stop_flag'] == 0){
			$body .= "<td id='stop".$md['id']."_".$key."'><a href='javascript:void(0)' onclick='stopDevice(".$md['id'].",1,".$key.")'>Stop</a></td>";
		}
		else {
			$body .= "<td id='stop".$md['id']."_".$key."' bgcolor='red'><a href='javascript:void(0)' onclick='stopDevice(".$md['id'].",0,".$key.")'>Start</a></td>";
		}
		$body .= "<td><a target='_blank' href='/recharges/lastModemSMSes/".$key."/".$md['id']."/1'>Last SMSes</a></td>";
		
		///if($_SESSION['Auth']['User']['group_id'] != 9){
		$body .= "<td><a target='_blank' href='/recharges/lastModemTransactions/".$key."/".$md['id']."/1'>Last Txns</a></td>";
		
                $body .= "<td>   
                            <div class='btn-group btn-small'>
                            <a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
                            Action
                            <span class='caret'></span>
                            </a>
                            <ul class='dropdown-menu'>
                                <!-- dropdown menu links -->
                                <li><a onclick='sendSms(".$md['id'].",".$key.");'>SMS</a><li>
                                <!---<li><a onclick='runCmd(".$md['id'].",".$key.");'>AT</a><li>-->
                                <li><a onclick='runUssd(".$md['id'].",".$key.");'>USSD</a><li>
                                 <!--<li><a onclick='runReset(".$md['id'].",".$key.");'>Reset</a><li> -->
                               <!-- <li><a onclick='runShowHide(".$md['id'].",".$key.",0);'>Hide</a><li> -->
                            </ul>
                            </div>
                          </td>";
       // }
       // else {
        	$body .= "<td></td><td></td>";
       // }
               
		$modem_bal += $md['balance'];
		$body .= "</tr>";
	}
	$diff_tot = intval($diff_tot);
	$body .= "<tr><td></td><td></td><td></td><td></td><td></td><td><b>Total</b></td><td><b>$modem_bal</b></td><td><b>$opening</b></td><td><b>$closing</b></td><td><b>$tfr</b></td><td><b>$sale</b></td><td></td><td><b>$inc</b></td><td><b>$diff_tot</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	$body .= "</table>";
	$total += $modem_bal;
	$body .= "<b>Total ".$map[$key]." Balance: $modem_bal (".($tfr + $diff_tot).")</b><br/>";
	
	}
	if(count($balances)>0){
	foreach($balances as $vend=>$bal){
		$body .= "<br/><br/>".strtoupper($vend)." Balance: ".$bal['balance'] ." (" . $bal['last'] . ")";
		$total += isset($bal['balance'])? $bal['balance'] : "";
	}
  }
	$body .= "<br/><br/>Total Balance: $total";
	
	echo $body;
?>

<script>
	function resetDevice(id,vendor){
		var r=confirm('Confirm?');
		if(r==true){
			$('reset'+id+'_'+vendor).innerHTML='Submitting';
		
			var url = '/recharges/resetModemDevice';
				var params = {'device' : id,'vendor': vendor};
				var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
				onSuccess:function(transport)
						{		
							var html = transport.responseText;
							$('reset'+id+'_'+vendor).innerHTML='Wait for 3 mins';
							$('device'+id+'_'+vendor).style.backgroundColor = '';
						}
				});
		}
	}
	
	function stopDevice(id,flag,vendor){
		var r=confirm('Confirm?');
		if(r==true){
			$('stop'+id+'_'+vendor).innerHTML='Submitting';
		
			var url = '/recharges/stopModemDevice';
				var params = {'device' : id,'stop': flag,'vendor': vendor};
				var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
				onSuccess:function(transport)
						{		
							var html = transport.responseText;
							$('stop'+id+'_'+vendor).innerHTML=html;
						}
				});
		}
	}
</script>
<!-- Button to trigger modal 
<a href="#smsModal" role="button" class="badge badge-info" data-toggle="modal">SMS</a>
<a href="#cmdModal" role="button" class="badge badge-important" data-toggle="modal">CMD</a>-->
 
<?php 
$smsReqs = "";
$atReqs = "";
$ussdReqs = "";
$resetReqs = "";
$smsArr = array();
$commandArr = array();
$ussdArr = array();
$resetArr = array();
$rebootArr = array();
if(!empty($modemRequests)) foreach($modemRequests as $mreq){
    
    $inputArr = json_decode($mreq['modem_request_log']['input'],1);
    //echo ($mreq['modem_request_log']['input']);
    if($inputArr['type']=='1' && count($smsArr)<5){
        // sms
        array_push($smsArr, $mreq['modem_request_log']);
    }else if($inputArr['type']=='2'){
        // command
        array_push($commandArr, $mreq['modem_request_log']);
    }else if($inputArr['type']=='3'){
        // USSD command
        array_push($ussdArr, $mreq['modem_request_log']);
    }else if($inputArr['type']=='4'){
        // Reset
        
        array_push($resetArr, $mreq['modem_request_log']);
    }else if($inputArr['type']=='5'){
        // Reboot
        array_push($rebootArr, $mreq['modem_request_log']);
    }
}

?>
<!-- Download  Last Transactions Modal -->
<div id="lastTransModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="lastTransModalHead" class="text-info">Download  Last Transactions</h3>
  </div>
  <div class="modal-body">
      
   
    
    <!--<input placeholder="Vendor ID" type="hidden" id="lt_vendor_id" value="" />
    <input placeholder="Device ID" type="hidden" id="lt_dev_id" value="" />-->
    <input placeholder="Date" type="text" id="lt_trans_date" value="<?php echo date('Y-m-d');?>" /><br/>
    <!--<input placeholder="Page No" type="text"  id="lt_page_no" value="" /><br/>
    <input placeholder="Per Page Trans" type="text"  id="lt_trans_per_page" /><br/>-->
     
    <hr/>
    <div id="response_" style="font-size:11px;overflow:auto;height:100px;">
           
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <a id="send_req_last_trans" href="" target="_blank" class="btn btn-info" data-loading-text="Downloading ..." data-complete-text="Send">Download</a>
  </div>
</div>


<!-- SMS Modal -->
<div id="smsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-info">Send SMS</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="sms_type" value="1" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="sms_dev_id" /><br/>
    <input placeholder="Vendor ID" type="text" disabled="disabled" id="sms_vendor" /><br/>
    <input placeholder="Mobile" type="text" id="sms_mob" /><br/>
    
    <textarea placeholder="Message" id="msg" ></textarea>
    <hr/>
    <div id="response_sms" style="font-size:11px;overflow:auto;height:100px;">
            <table id="response_sms_table" class="table table-bordered">
            <thead class="warning"><th style='line-height:11px'>Time</th><th style='line-height:11px'>ReqID</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            <tbody>
             <?php foreach ($smsArr as $key => $value) {?>
                    <tr>
                    <td><?php echo $value['modified']?></td>
                    <td><?php echo $value['req_id']?></td>
                    <td><?php echo $value['input']?></td>
                    <td><?php echo is_null($value['output'])|| $value['output']=="" ? "Pending" : $value['output'] ;?></td>
                    </tr>
              <?php  }?>   
            </tbody>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="send_sms" type="button" class="btn btn-info" data-loading-text="Sending..." data-complete-text="Send">Send</button>
  </div>
</div>

<!-- AT Command Modal -->
<div id="cmdModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cmdModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-error">Run AT Command</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="cmd_type" value="2" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="cmd_dev_id" /><br/>
    <input placeholder="Vendor ID" type="text" disabled="disabled" id="cmd_vendor" /><br/>
    <input placeholder="Time" type="text" id="cmd_time" /><br/>
    
    <textarea placeholder="Command" id="cmd" ></textarea>
    <hr/>
    <div id="response_cmd" style="font-size:11px;overflow:auto;height:100px;">
            <table  class="table table-bordered" >
            <thead class="warning"><th style='line-height:11px'>Time</th><th style='line-height:11px'>ReqId</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            <tbody id="response_cmd_table">
              <?php foreach ($commandArr as $key => $value) {?>
                    <tr>
                    <td><?php echo $value['modified']?></td>
                    <td><?php echo $value['req_id']?></td>
                    <td><?php echo $value['input']?></td>
                    <td><?php echo is_null($value['output'])|| $value['output']=="" ? "Pending" : $value['output'] ;?></td>
                    </tr>
              <?php  }?>   
            </tbody>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="run_cmd" class="btn btn-danger" data-loading-text="Executing..." data-complete-text="Run">Run</button>
  </div>
</div>

<!-- USSD Command Modal -->
<div id="ussdModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ussdModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-error">Run USSD Command</h3>
  </div>
  <div class="modal-body">    
    <input placeholder="Type" type="hidden" id="ussd_type" value="3" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="ussd_dev_id" /><br/>
    <input placeholder="Vendor ID" type="text" disabled="disabled" id="ussd_vendor" /><br/>
    <input placeholder="Time" type="text" id="ussd_time" /><br/>
    
    <textarea placeholder="USSD" id="ussd" ></textarea>
    
    <hr/>
    <div id="response_ussd" style="font-size:11px;overflow:auto;height:100px;">
            <table  class="table table-bordered" >
            <thead class="warning"><th style='line-height:11px'>Time</th><th style='line-height:11px'>ReqId</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            <tbody id="response_ussd_table">
                <?php foreach ($ussdArr as $key => $value) {?>
                    <tr>
                    <td><?php echo $value['modified']?></td>
                    <td><?php echo $value['req_id']?></td>
                    <td><?php echo $value['input']?></td>
                    <td><?php echo is_null($value['output'])|| $value['output']=="" ? "Pending" : $value['output'] ;?></td>
                    </tr>
               <?php  }?>
            </tbody>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="run_ussd" class="btn btn-danger" data-loading-text="Executing..." data-complete-text="Run">Run</button>
  </div>
</div>

<!-- Reset Command Modal -->
<div id="resetModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ussdModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-error">Run Reset Command</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="reset_type" name="reset_type" value="4" /><br/>
    <input placeholder="Vendor ID" type="hidden" id="reset_vendor" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="reset_dev_id" /><br/>
    <hr/>
    <div id="response_reset" style="font-size:11px;overflow:auto;height:100px;">
            <table  class="table table-bordered" >
            <thead class="warning"><th style='line-height:11px'>Time</th><th style='line-height:11px'>ReqId</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            <tbody id="response_reset_table">
               <?php foreach ($resetArr as $key => $value) {?>
                    <tr>
                    <td><?php echo $value['modified']?></td>
                    <td><?php echo $value['req_id']?></td>
                    <td><?php echo $value['input']?></td>
                    <td><?php echo is_null($value['output'])|| $value['output']=="" ? "Pending" : $value['output'] ;?></td>
                    </tr>
               <?php  }?>
            </tbody>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="run_reset" class="btn btn-danger" data-loading-text="Processing..." data-complete-text="Reset">Reset</button>
  </div>
</div>
<!-- Save Device Setting -->
<style type="text/css">
    body .modal {
        /* new custom width */
        width: 500px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -250px;
         /*height: 800px;
        must be half of the width, minus scrollbar on the left (30px) 
        margin-left: -400px;*/
    }
</style>
<div id="deviceInsertModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deviceInsertModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-error">Insert New Device</h3>
  </div>
  <div class="modal-body">
<!--    <input placeholder="Type" type="hidden" id="device_insert_type" name="device_insert_type" value="7" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="dev_id" /><br/>
    <hr/>-->
    <div id="response_device_insert" >
        <form id ="from_device_insert" name="from_device_insert">
            
            <fieldset>
            <div class="control-group">
                <div class="controls">
                  <input placeholder="SIM ID" type="text"  id="dev_sim_id" name="dev_sim_id" />
                  <input placeholder="Mobile" type="text"  id="dev_mobile" name="dev_mobile"/>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                  <input placeholder="Operator Name" type="text"  id="dev_opr_name" name="dev_opr_name"/>
                  <input placeholder="Type ID" type="text"  id="dev_type_id" name="dev_type_id"/>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                  <input placeholder="PIN" type="text"  id="dev_pin" name="dev_pin"/>
                  <input placeholder="Balance" type="text"  id="dev_balance" name="dev_balance"/>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                  <input placeholder="Operator ID" type="text"  id="dev_opr_id" name="dev_opr_id"/>
                  <input placeholder="Commission" type="text"  id="dev_commission" name="dev_commission"/>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                  <input placeholder="Vendor Name" type="text"  id="dev_vendor_nm" name="dev_vendor_nm"/>
                  <input placeholder="Par Bal" type="text"  id="dev_par_bal" name="dev_par_bal"/>
                </div>
            </div>
            <!--<div class="control-group">
                <div class="controls">
                  <input placeholder="Active Flag" type="text"  id="dev_act_flag" name="dev_act_flag"/>
                  <input placeholder="Recharge Flag" type="text"  id="dev_rch_flag" name="dev_rch_flag"/>
                </div>
            </div>-->
            
            
<!--            <label>OPR ID</label><input placeholder="Operator ID" type="text"  id="opr_id" /><br/> @TODO Select box 
            <label>Commission</label><input placeholder="Commission" type="text"  id="commission" /><br/>
            <label>Vendor</label><input placeholder="Vendor Name" type="text"  id="vendor_nm" /><br/>
            <label>ParBal</label><input placeholder="Par Bal" type="text"  id="par_bal" /><br/>
            <label>Active Flag</label><input placeholder="Active Flag" type="text"  id="act_flag" /><br/>
            <label>Recharge Flag</label><input placeholder="Recharge Flag" type="text"  id="rch_flag" /><br/> -->
            </fieldset>
        </form>
    </div>
    
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="insert_dev_button" class="btn btn-danger" data-loading-text="Processing..." data-complete-text="INSERTED">INSERT</button>
  </div>
</div>
<script>
     $.noConflict();
     
     function downloadLastTrans(vendor){
        jQuery('#send_req_last_trans').button('reset');
        //jQuery('#lt_dev_id').val(dev);
        //jQuery('#lt_vendor_id').val(vendor);
        jQuery('#lastTransModal').modal('show');  
        var url = "http://"+vendor+"/start.php?query=download&date="+jQuery('#lt_trans_date').val();
        jQuery('#send_req_last_trans').attr('href',url);
     }
     
     function sendSms(dev , vendor){
        jQuery('#send_sms').button('reset');
        jQuery('#sms_dev_id').val(dev);
        jQuery('#sms_vendor').val(vendor);
        jQuery('#smsModal').modal('show');
     }
     
    function runCmd(dev , vendor){
        jQuery('#cmd_dev_id').val(dev);
        jQuery('#cmd_vendor').val(vendor);
        jQuery('#cmdModal').modal('show');
        /*jQuery('#cmdModal').on('shown', function () {
         // do something…
        })*/
    }
    
    function runUssd(dev , vendor){
        jQuery('#ussd_dev_id').val(dev);
        jQuery('#ussd_vendor').val(vendor);
        jQuery('#ussdModal').modal('show');
        /*jQuery('#cmdModal').on('shown', function () {
         // do something…
        })*/
    }
    function runReset(dev , vendor){
        jQuery('#reset_dev_id').val(dev);
        jQuery('#reset_vendor').val(vendor);
        jQuery('#resetModal').modal('show');
        
    }
    function runReboot(vendor){
        //jQuery('#reboot_vendor').val(vendor);
        if(!confirm("Do you really want to reboot ?")){
            return ;
        }
        //var url = '/shops/modemRequest/';
        var url = '/panels/modemRequest/';
        var data = "type=5&vendor="+vendor;
        jQuery.ajax({
            type:"GET",
            url:url,
            datatype:"json",
            data:data,
            success:function(data){
                alert("Your request has been sent successfully . Device will be reboot in few minutes .");
            }
        });

        
    }
    function runShowHide(dev , vendor , flag){
         //var url = '/shops/modemRequest/';
         var url = '/panels/modemRequest/';
         //jQuery('#run_ussd').button('loading');
        var data = "type=7"+"&device="+dev+"&vendor="+vendor+"&flag="+flag;//query="+jQuery('#ussd_qry').val()+"&
        jQuery.ajax({
                type:"GET",
                url:url,
                datatype:"json",
                data:data,
                success:function(data){
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        data = {'status':"failure",'errno':'00','response':'Some error occured'};
                    }
                    if( data.status && data.status == 'success' ){
                        alert("Done !");
                        var id = "#device"+dev+"_"+vendor;
                        jQuery(id).hide();
                    }else if( data.status && data.status == 'failure' ){
                        alert(data.data);
                    }else{
                        alert("Some error occured !"); 
                    }
                }
            });
    }
    jQuery('#send_sms').click(function(){
                       
                        //var url = '/shops/modemRequest/';
                        var url = '/panels/modemRequest/';
                        jQuery('#send_sms').button('loading');
                        var data = "type="+jQuery('#sms_type').val()+"&device="+jQuery('#sms_dev_id').val()+"&vendor="+jQuery('#sms_vendor').val()+"&mobile="+jQuery('#sms_mob').val()+"&msg="+encodeURIComponent(jQuery('#msg').val())+"";//query="+jQuery('#sms_qry').val()+"&
                        jQuery.ajax({
                            type:"GET",
                            url:url,
                            datatype:"json",
                            data:data,
                            success:function(data){
                                jQuery('#send_sms').button('reset');
                                jQuery('#send_sms').button('complete');
                                try {
                                    data = JSON.parse(data);
                                } catch (e) {
                                    data = {'status':"failure",'errno':'00','response':'Some error occured'};
                                }
                                if( data.status && data.status == 'success' ){
                                    alert("Done !");
                                }else if( data.status && data.status == 'failure' ){
                                    alert(data.data);
                                }else{
                                    alert("Some error occured !");
                                }

                            }
                        });
                });
    jQuery('#run_cmd').click(function(){
                     //var url = '/shops/modemRequest/';
                     var url = '/panels/modemRequest/';
                    jQuery('#run_cmd').button('loading');
                    var data = "type="+jQuery('#cmd_type').val()+"&wait="+jQuery('#cmd_time').val()+"&device="+jQuery('#cmd_dev_id').val()+"&vendor="+jQuery('#cmd_vendor').val()+"&cmd="+encodeURIComponent(jQuery('#cmd').val())+"";//query="+jQuery('#cmd_qry').val()+"&
                    jQuery.ajax({
                        type:"GET",
                        url:url,
                        datatype:"json",
                        data:data,
                        success:function(data){
                            jQuery('#run_cmd').button('reset');
                            jQuery('#run_cmd').button('complete');
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                data = {'status':"failure",'errno':'00','response':'Some error occured'};
                            }
                            if( data.status && data.status == 'success' ){
                                alert("Done !");
                            }else if( data.status && data.status == 'failure' ){
                                alert(data.data);
                            }else{
                                alert("Some error occured !");                            }
                            }
                    });

                });
                
    jQuery('#run_ussd').click(function(){
         //var url = '/shops/modemRequest/';
         var url = '/panels/modemRequest/';
        jQuery('#run_ussd').button('loading');
        var data = "type="+jQuery('#ussd_type').val()+"&wait="+jQuery('#ussd_time').val()+"&device="+jQuery('#ussd_dev_id').val()+"&vendor="+jQuery('#ussd_vendor').val()+"&ussd="+encodeURIComponent(jQuery('#ussd').val())+"";//query="+jQuery('#ussd_qry').val()+"&
        jQuery.ajax({
            type:"GET",
            url:url,
            datatype:"json",
            data:data,
            success:function(data){
                jQuery('#run_ussd').button('reset');
                jQuery('#run_ussd').button('complete');
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    data = {'status':"failure",'errno':'00','response':'Some error occured'};
                }
                var currentdate = new Date(); 
                var current_time = currentdate.getTime();

                if( data.status && data.status == 'success' ){
                    alert("Done !");
                }else if( data.status && data.status == 'failure' ){
                    alert(data.data);
                }else{
                    alert("Some error occured !");
                }
            }
        });

    });
    
    jQuery('#run_reset').click(function(){
        //var url = '/shops/modemRequest/';
        var url = '/panels/modemRequest/';
        jQuery('#run_reset').button('loading');
        var data = "type="+jQuery('#reset_type').val()+"&device="+jQuery('#reset_dev_id').val()+"&vendor="+jQuery('#reset_vendor').val();//query="+jQuery('#ussd_qry').val()+"&
        jQuery.ajax({
            type:"GET",
            url:url,
            datatype:"json",
            data:data,
            success:function(data){
                jQuery('#run_reset').button('reset');
                jQuery('#run_reset').button('complete');
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    data = {'status':"failure",'errno':'00','response':'Some error occured'};
                }
                var currentdate = new Date(); 
                var current_time = currentdate.getTime();
                // here we will fetch data from table modem_request_log
                if( data.status && data.status == 'success' ){
                    alert("Done !");
                }else if( data.status && data.status == 'failure' ){
                    alert(data.data);
                }else{
                    alert("Some error occured !");
                }
            }
        });

    });
    
    
    jQuery('#insert_dev_button').click(function(){
        
        //var url = '/shops/modemRequest/';
        var url = '/panels/modemRequest/';
        //jQuery('#run_reset').button('loading');
        var  dataStr = jQuery("#from_device_insert").serialize();
        jQuery.ajax({
            type:"GET",
            url:url,
            datatype:"json",
            data:dataStr,
            success:function(data){
                alert("Your request has been sent successfully . Device will be insert in few minutes .");
            }
        });

    });
   
    
    
    
 </script>