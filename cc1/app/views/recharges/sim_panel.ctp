<?php
  
?>
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
$bg_table = '';
$total1 = 0;
$j=0;
$date1 = date('d-m-Y',strtotime($date));


 $body.="<div><input type='text' style='height:30px;' placeholder='Enter Mobile Number' id='search_number'><input type='button' style='height:30px;' onclick='searchNumber();' value='Search'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' id='hidesims' onclick = 'hidesims()'>&nbsp;&nbsp;&nbsp;<b>HideSims</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
 $body.="<select id='modem_id' name='modem_id'><option value=\"0\">---Select Modem----</option>";
 foreach($modems as $mkey => $mval){
     $selected = "";
                if ($mkey == $modemId) {
                    $selected = "selected";
                }
 
 $body.="<option value='".$mkey."'$selected>".$map[$mkey]."</option>";
 }
 $body.="</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' style='height:30px;'name='from' id='from'  onmouseover='fnInitCalendar(this, \"from\",\"close=true\")' value=\"$date1\">";
 $body.="<input type=\"button\" value=\"Submit\" onclick=\"setAction()\"/></div>";
foreach ($operatorwiseVendor as $modemkey => $modemval) {
   
    if ($_SESSION['Auth']['User']['group_id'] != 9) {
        $body .= "<br/><a href='http://" . $ips[$modemkey] . "/phpmyadmin' target='_blank'>" . $map[$modemkey] . " Balances:</a> <a href='/recharges/simPanel/" . $modemkey . "' target='_blank'> Sims:</a> | <a href='javascript:void(0)' onclick='runReboot(" . $modemkey . ")' id='reboot_" . $modemkey . "'>reboot</a>&nbsp;&nbsp;&nbsp;<a href='#deviceInsertModal' role='button' data-toggle='modal'  id='insertdev_" . $modemkey . "' >Insert Device</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='downloadLastTrans(\"" . $ips[$modemkey] . "\")' id='_" . $modemkey . "'>Download Transactions</a>&nbsp;&nbsp;&nbsp;<a href='/recharges/getModemsimsDetails/".$modemkey."' target='_blank'>Edit Sims</a>";
       
    }
    
    if(isset($modems[$modemkey]['lasttime']))$body .= " (".$modems[$modemkey]['lasttime'].")";
	if(isset($modems[$modemkey]['ports'])){
		$ports_data = json_decode($modems[$modemkey]['ports'],true);
		$body .= " [Total Ports: ".$ports_data['total']. ", Detected: ".$ports_data['ports'] . "]";
	}

           if ($j % 2 == 0)
                       $class = '';
                   else
                       $class = 'altRow';
                       $class1 =  'altRow1';

        
     if(isset($modems[$modemkey]['inactive']) && $modems[$modemkey]['inactive'] == 1)$bg_table = "#f9e1e1";

    $body.='<table style="margin-bottom:20px;" class="table" bgcolor=".$bg_table.">
        <thead>
            <th width="10%"></th>
            <th width="10%">Sims</th>
            <th width="10%">Curr Bal</th>
            <th width="10%">Opening</th>
            <th width="10%">Closing</th>
            <th width="10%">Incoming</th>
            <th width="10%">Roaming Sale</th>
            <th width="10%">Home Sale</th>
            <th width="10%">Sale</th>
            <th width="10%">Diff</th>
        </thead>
        <tbody></tbody></table>';
    $modem_bal = 0;
    $opening = 0;
    $closing = 0;
    $tfr = 0;
    $sale = 0;
    $diff_tot = 0;
    $inc = 0;
    $mbal = 0;
    $tfr1 = 0;
    $diff_tot1 = 0; 
    $totalactiveSims = 0;
    $totalNonactiveSims = 0;
    $totalCurrBal = 0;
    $totalOpening = 0;
    $totalClosing = 0;
    $totalIncoming = 0;
    $totalSale = 0;
    $totalDiff = 0;
    $totalRoamingsale = 0;
    $totalHomesale = 0;
    
    if (isset($modemkey) && !empty($modemkey)) {
        foreach ($modemval as $oprkey => $oprval) {
            $operatordata = $oprval['data']['operator'];
            if (isset($operatordata['opr_name']) && !empty($operatordata['opr_name'])) {
                $totalactiveSims+=$operatordata['activesims'];
                 $totalNonactiveSims+=$operatordata['totalsims'];
                 $totalCurrBal+=$operatordata['curr_bal'];
                 $totalOpening+=$operatordata['opening'];
                 $totalClosing+=$operatordata['closing'];
                 $totalIncoming+=$operatordata['incoming'];
                 $totalSale+=$operatordata['sale'];
                 $totalDiff+=$operatordata['diff'];
                 $totalRoamingsale+=$operatordata['roamingsale'];
                 $totalHomesale+=$operatordata['homesale'];
                 $body.="<div><table class='table ".$class."'>";
                 $body.="<tr><td width=\"10%\"  onclick=\"showdata('".$modemkey."_".$oprkey."')\"><a href='javascript:void(\"0\")'><img id='".$modemkey."_".$oprkey."' src=\"/img/plusIcon.jpg\" height=\"18px;\" width=\"18px;\"></img></a>".$operatorlist[$operatordata['opr_id']]."</td><td width=\"10%\">" . $operatordata['activesims']."/".$operatordata['totalsims'] . "</td><td width=\"10%\">" . $operatordata['curr_bal'] . "</td><td width=\"10%\">" . $operatordata['opening'] . "</td><td width=\"10%\">" . $operatordata['closing'] . "</td><td width=\"10%\">" . $operatordata['incoming'] . "</td><td width=\"10%\">" . $operatordata['roamingsale'] . "</td><td width=\"10%\">" . $operatordata['homesale'] . "</td><td width=\"10%\">" . $operatordata['sale'] . "</td><td width=\"10%\">".$operatordata['diff']."</td></tr>";
                 
               unset($oprval['data']);
            foreach ($oprval as $k => $v) {
                $vendordata = $v['data']['vendor'];
                if (isset($vendordata)) {
               $body.="<table  style=\"display:none;\"  class='table1 ".$modemkey."_".$oprkey."'>";
                $body.="<tr><td width=\"10%\" id='show_".$modemkey."_".$oprkey.str_replace(' ','',  str_replace('/','_',$v[0]['vendor']))."'  style=\"text-align:center;\" onclick=\"showdata('".$modemkey."_".$oprkey.str_replace(' ','',  str_replace('/','_',$v[0]['vendor']))."')\" bgcolor='" . $bg_table . "'><a href='javascript:void(\"0\")'><img src=\"/img/plusIcon.jpg\" height=\"18px;\" width=\"18px;\" class='button_".$modemkey."_".$oprkey."' id='".$modemkey."_".$oprkey.str_replace(' ','',  str_replace('/','_',$v[0]['vendor']))."'></img></a>" . $k . "</td><td width=\"10%\">" . $vendordata['activesims'] . "/".$vendordata['totalsims']."</td><td width=\"10%\">" . $vendordata['curr_bal'] . "</td><td width=\"10%\">" . $vendordata['opening'] . "</td><td width=\"10%\">" . $vendordata['closing'] . "</td><td width=\"10%\">" . $vendordata['incoming'] . "</td><td width=\"10%\">" . $vendordata['roamingsale'] . "</td><td width=\"10%\">" . $vendordata['homesale'] . "</td><td width=\"10%\">".$vendordata['sale']."</td><td width=\"10%\">".$vendordata['diff']."</td></tr></table>";
                }
                $body.="</table>";
               
                $body.="<table style=\"display:none;\" class='table table-bordered  ".$modemkey."_".$oprkey.str_replace(' ','',str_replace('/','_',$v[0]['vendor']))." ".$modemkey._.$oprkey._show."' bgcolor='" . $bg_table . "'>";
                $body.="<tr> 
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
                <th>Home Sale</th>
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

                unset($v['data']);
                foreach ($v as $vendorkey => $vendorval) {
                    
                    $color = '';
                    if ($vendorval['active_flag'] == 1 && $vendorval['balance'] < 3000)
                        $color = '#8c65e3';
                    else if ($vendorval['active_flag'] == 0 && $vendorval['balance'] > 3000)
                        $color = '#c73525';
                    else if ($vendorval['active_flag'] == 1 && $vendorval['balance'] > 3000 && date('Y-m-d H:i:s', strtotime('-45 minutes')) > $vendorval['last'])
                        $color = '#f6ff00';
                    else if ($vendorval['active_flag'] == 1)    
                        $color = '#99ff99';
                       $hideclass = '';
                      if($color ==''){
                         $hideclass = 'hidediv';
                      }
                    $homesale = $vendorval['sale'] - $vendorval['roaming_today'];  
                    $body .= "<tr id='device".$vendorval['id']."_".$vendorval['mobile']."' class='".$vendorval['vendor']." ".$vendorval['mobile']." ".$hideclass."' bgcolor='$color'>";
                    $body .= "<td id='device".$vendorval['id']."'>" . $vendorval['id']. "/" . $vendorval['machine_id'] . "/" . $vendorval['device_num'] . "</td>";
                    $body .= "<td>" . $vendorval['signal'] . "</td>";
                    $body .= "<td>" . $vendorval['vendor'] . "</td>";
                    $body .= "<td>" . $vendorval['operator'] . "</td>";
                    $body .= "<td>" . $vendorval['mobile'] . "</td>";
                    $body .= "<td>" . $vendorval['commission'] . "%</td>";
                    $body .= "<td>" . $vendorval['balance'] . "</td>";
                    $body .= "<td>" . ((isset($vendorval['opening'])) ? $vendorval['opening'] : "") . "</td>";
                    $body .= "<td>" . ((isset($vendorval['closing'])) ? $vendorval['closing'] : "") . "</td>";
                    $body .= "<td>" . $vendorval['tfr'] . "</td>";
                    $body .= "<td>" . $vendorval['sale'] . "</td>";
                    if ($vendorval['roaming_limit'] > 0 || $vendorval['limit'] > 0) {
                        $body .= "<td>" . $vendorval['roaming_today'] . "/" . $vendorval['limit_today'] . "</td>";
                    } else
                        $body .= "<td></td>";
                    $body .= "<td>" . $homesale. "</td>";
                    $body .= "<td>" . intval($vendorval['inc']) . "</td>";
                    $open = (isset($vendorval['opening'])) ? $vendorval['opening'] : 0;
                    $close = (isset($vendorval['closing'])) ? $vendorval['closing'] : 0;
                    $opening += $open;
                    $closing += $close;
                    $inc += intval($vendorval['inc']);
                    $tfr += $vendorval['tfr'];
                    $sale += $vendorval['sale'];
                    $tfr1+=$vendorval['tfr'];

                    if ($date != date('Y-m-d')) {
                        $diff = $vendorval['sale'] - ($open + $vendorval['tfr'] - $close);
                        $diff1 = $vendorval['sale'] - ($open + $vendorval['tfr'] - $close);
                    } else {
                        $diff = $vendorval['sale'] - ($open + $vendorval['tfr'] - $vendorval['balance']);
                        $diff1 = $vendorval['sale'] - ($open + $vendorval['tfr'] - $close);
                    }
                    $diff = $diff - $vendorval['inc'];
                    $diff1 = $diff - $vendorval['inc'];
                    $diff_tot += $diff;
                    $diff_tot1+= $diff;
                    
                    $body .= "<td>" . intval($diff) . "</td>";
                    if ($vendorval['success'] > 0) {
                        $body .= "<td>" . $vendorval['success'] . "%</td>";
                    } else {
                        $body .= "<td></td>";
                    }
                    if (!empty($vendorval['last'])) {
                        $body .= "<td>" . date('H:i:s', strtotime($vendorval['last'])) . "</td>";
                    } else {
                        $body .= "<td></td>";
                    }
                    if (!empty($vendorval['process_time'])) {
                        $body .= "<td>" . $vendorval['process_time'] . " secs</td>";
                    } else {
                        $body .= "<td></td>";
                    }

                    $body .= "<td>" . $vendorval['active_flag'] . "</td>";

                    //$body .= "<td id='reset" . $vendorval['id'] . "_" . $modemkey . "'><a href='javascript:void(0)' onclick='resetDevice(" . $vendorval['id'] . "," . $modemkey . ")'>Reset</a></td>";
                    if ($vendorval['stop_flag'] == 0) {
                        $body .= "<td id='stop" . $vendorval['id'] . "_" . $modemkey . "'><a href='javascript:void(0)' onclick='stopDevice(" . $vendorval['id'] . ",1," . $modemkey . ")'>Stop</a></td>";
                    } else {
                        $body .= "<td id='stop" . $vendorval['id'] . "_" . $modemkey . "' bgcolor='red'><a href='javascript:void(0)' onclick='stopDevice(" . $vendorval['id'] . ",0," . $modemkey . ")'>Start</a></td>";
                    }
                    $body .= "<td><a target='_blank' href='/recharges/lastModemSMSes/" . $modemkey . "/" . $vendorval['id'] . "/1'>Last SMSes</a></td>";

                    if ($_SESSION['Auth']['User']['group_id'] != 9) {
                        $body .= "<td><a target='_blank' href='/recharges/lastModemTransactions/" . $modemkey . "/" . $vendorval['id'] . "/1'>Last Txns</a></td>";

                        $body .= "<td>   
                            <div class='btn-group btn-small'>
                            <a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
                            Action
                            <span class='caret'></span>
                            </a>
                            <ul class='dropdown-menu'>
                                <!-- dropdown menu links -->
                                <li><a onclick='sendSms(" . $vendorval['id'] . "," . $modemkey . ");'>SMS</a><li>
                                <li><a onclick='runCmd(" . $vendorval['id'] . "," . $modemkey . ");'>AT</a><li>
                                <li><a onclick='runUssd(" . $vendorval['id'] . "," . $modemkey . ");'>USSD</a><li>
                                <li><a onclick='runReset(" . $vendorval['id'] . "," . $modemkey . ");'>Reset</a><li>
                                <li><a onclick='runShowHide(" . $vendorval['id'] . "," . $modemkey . ",0);'>Hide</a><li>
                            </ul>
                            </div>
                          </td>";
                    } else {
                        $body .= "<td></td><td></td>";
                    }
                    $modem_bal += $vendorval['balance'];
                    $mbal += $vendorval['balance'];
                    $body .= "</tr>";
                    $diff_tot = intval($diff_tot);
                    $diff_tot1 = intval($diff_tot1);
                    $total += $modem_bal;
                    $homesale = 0;
                   
                }
                $body .= "<tr><td></td><td></td><td></td><td></td><td></td><td><b>Total</b></td><td><b>$modem_bal</b></td><td><b>$opening</b></td><td><b>$closing</b></td><td><b>$tfr</b></td><td><b>$sale</b></td><td></td><td></td><td><b>$inc</b></td><td><b>$diff_tot</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                $modem_bal = 0;
                $opening = 0;
                $closing = 0;
                $tfr = 0;
                $sale = 0;
                $diff_tot = 0;
                $inc = 0;
                $total = 0;
            }
           
        }
       }
$body.="<table style='margin-bottom:20px;' class='table'>
        <thead>
            <th width='10%'>Total</th>
            <th width='10%'>".$totalactiveSims."/".$totalNonactiveSims."</th>
            <th width='10%'>".$totalCurrBal."</th>
            <th width='10%'>".$totalOpening."</th>
            <th width='10%'>".$totalClosing."</th>
            <th width='10%'>".$totalIncoming."</th>
            <th width='10%'>".$totalRoamingsale."</th>  
            <th width='10%'>".$totalHomesale."</th>
            <th width='10%'>". $totalSale."</th>
            <th width='10%'>".$totalDiff."</th>
           </thead>
        <tbody></tbody></table>";
   $body.="</table></div>";
     $total1 += $mbal;
     $body .= "<b>Total ".$map[$modemkey]." Balance: $mbal (".($tfr1 + $diff_tot1).")</b><br/>";
    }
   $j++;
}
    if (count($balances) > 0) {
    foreach ($balances as $vend => $bal) {
     $body .= "<br/><br/>".strtoupper($vend)." Balance: ".$bal['balance'] ." (" . $bal['last'] . ")";
     $total1 += isset($bal['balance']) ? $bal['balance'] : "";
      }
    }
$body .= "<br/><br/>Total Balance: $total1";

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
    
 function showdata(vendorId){

   jQuery("."+vendorId).toggle("fast");
   jQuery("."+vendorId+"_show").hide();
   var source = jQuery("#"+vendorId).attr("src");
   var src = (jQuery("#"+vendorId).attr("src") === '/img/plusIcon.jpg')
            ? '/img/minus.png'
            : '/img/plusIcon.jpg'; 
     //alert(jQuery(".button_"+vendorId).attr("src"));
     if((jQuery("#"+vendorId).attr("src") == '/img/minus.png')){
     jQuery(".button_"+vendorId).attr("src",src);
     }
     jQuery("#"+vendorId).attr("src",src);
   
  }

   function searchNumber(){
    var number = jQuery("#search_number").val();
      var x = document.getElementsByClassName(number);
       for (i = 0; i < x.length; i++) {
       var rowid  = x[i].id;
       var classname = jQuery("#"+rowid).parents('table').attr('class');
       var res = classname.split(" ");
       var classname1 = jQuery("#show_"+res[3]).parents('table').attr('class');
       jQuery("+rowid+").focus();
       var res1  = classname1.split(" ");
       jQuery("."+res1[1]).show();
       jQuery("#show_"+res[3]).show();
       jQuery("."+res[3]).show();
       var src = (jQuery("#"+res1[1]).attr("src") === '/img/plusIcon.jpg' || jQuery("#show_"+res[3]).attr("src") === '/img/plusIcon.jpg' )
            ? '/img/minus.png'
            : '/img/plusIcon.jpg'; 
     jQuery("#"+res1[1]).attr("src",src);
     jQuery("#"+res[3]).attr("src",src);
     
    
    }
}

function hidesims(){
if (jQuery("#hidesims").is(":checked")) {
     jQuery(".hidediv").hide();
    } else {
         jQuery(".hidediv").show();
   }
}

function setAction(){
   
	var frmDate = jQuery("#from").val();
    var fdate = frmDate.split("-");
    var modemId = jQuery("#modem_id").val();
    //alert("/recharges/simPanel/"+modemId+"/"+fdate[2]+"-"+fdate[1]+"-"+fdate[0]);
	window.location.href ="/recharges/simPanel/"+modemId+"/"+fdate[2]+"-"+fdate[1]+"-"+fdate[0];
	
}
 </script>