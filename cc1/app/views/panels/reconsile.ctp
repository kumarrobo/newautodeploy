<script src="/js/lib/jquery-1.9.0.min.js"></script>
<script>
var $j = jQuery.noConflict();
function setAction()
{
	var sel=$('tagDD');
	var tagName=sel.options[sel.selectedIndex].value;
	//alert(tagName);
	document.tagResults.action="/panels/reconsile/"+tagName;
	document.tagResults.submit();
}

function call_urls(ser_url,resdata){
    $j.ajax({
      url: ser_url,
      type: 'get',
      contentType: "application/x-www-form-urlencoded",
      data: resdata,
      success: function (data, status) {
        alert("modified successfully");
      },
      error: function (xhr, desc, err) {
        console.log(xhr);
        console.log("Desc: " + desc + "\nErr:" + err);
      }
    });
}

function update_resolve_flag(id,valObj){
    var group = "input:checkbox[id='"+$j(valObj).attr("id")+"']";
    $j(group).attr("checked",false);
    $j(valObj).prop('checked', true);
    var vid = $j("#h_vendor_id").val();
    var rdata = 'id='+id+'&r_flag='+valObj.value+'&r_vendor='+vid;
    var url = "/panels/update_reconsile";
    call_urls(url,rdata);
}

function update_comments(id){
    var cmt_id = "#"+id+"_comment";
    var cmt_txt = $j("textarea"+cmt_id).val();
    var vid = $j("#h_vendor_id").val();
    var rdata = 'id='+id+'&r_comment='+cmt_txt+'&r_vendor='+vid;
    var url = "/panels/update_reconsile";
    call_urls(url,rdata);
}

function show_detail(id){
    //alert("hello");
    var tabid = "#itr_"+id;
    var div_id = "#idiv_"+id;
    var sign_id = ".exp_coll_"+id;
    $j(tabid).toggle();
    $j(div_id).toggle();
    if($j(sign_id+' span').text() == '-'){
        $j(sign_id+' span').text('+');   
    } else {
        $j(sign_id+' span').text('-');
    }
}
</script>

<form name="tagResults" method="POST" onSubmit="setAction()">

Date <input type="text" name="rdate" id="rdate" value="<?php echo (isset($_REQUEST['rdate'])?$_REQUEST['rdate']:'');?>" onmouseover="fnInitCalendar(this, 'rdate','close=true')" value="<?php if(!is_null($rdate))echo $rdate;?>" />

Vendor Name : <select name="tagDD" id="tagDD">
	<?php
            $vlist = array();
            foreach($vendor_list as $k=>$v){
                 //array_push($vlist,$v['vendors']);
                 $vlist[$v['vendors']['id']] = $v['vendors']['company'];
            }

            if(isset($_REQUEST['tagDD'])){
                  echo "<option value='".$_REQUEST['tagDD']."'>".$vlist[$_REQUEST['tagDD']]."</option>";	
             }else{
                  $_REQUEST['tagDD'] = 0;
             }

            foreach($vlist as $dk=>$dv)
            {                 
                 if($dk == $_REQUEST['tagDD']) continue;
                 echo "<option value='".$dk."'>".$dv."</option>";	
            }

	?>
</select>

<input type="button" value="Submit" onclick="setAction()"/>
</form>
</br>
</br>


<?php
    //print_r($data_Result);
//-----------------
$diff_sims_array = array();
$sale_sims_array = array();
//-----------------
if(isset($vendor_id)){
    echo "<input type='hidden' name='h_vendor_id' id='h_vendor_id' value='$vendor_id' >";
}else{
    echo "<input type='hidden' name='h_vendor_id' id='h_vendor_id' value='0' >";
}

if(isset($data_Result1)){    
    ?>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr style="background-color:lightgreen;text-align:left">
        <th width="2%">&nbsp;</th>
        <th>Sim Number</th>
        <th>Operator</th>
        <th>Total Amount</th>
        <th>count</th>
    </tr>
    
    <?php
    /*echo "<pre>";
        print_r($data_Result1);
      echo "</pre>";*/

    $opr_list = array();    
    foreach($operator_list as $k=>$v){
         $opr_list[$v['products']['id']] = $v['products']['name'];
    }

    /*echo "<pre>";
        print_r($opr_list);
    echo "</pre>";    */

    $i = 1;
    foreach($data_Result1 as $k=>$v){                
            foreach($v as $k1=>$v1){
                $opr = ((strlen(trim($v1['operator']))>0 && isset($opr_list[$v1['operator']]))?$opr_list[$v1['operator']]:'N.A');
                array_push($diff_sims_array,$v1['sim_num']."_".$opr);
                    //print_r($v1);
                    $style = ($i%2==0)?"style='background-color:yellow'":"style='background-color:gray'";
                    if($v1['count'] == 0){
                        continue;
                    }
                    echo "<tr $style>
                            <td style='cursor:pointer;font-size:18px;text-align:center;' onclick='show_detail($i)'><div id='exp_coll_$i' class='exp_coll_$i'><span>+</span></div></td>
                            <td>".$v1['sim_num']."</td>
                            <td>".((strlen(trim($v1['operator']))>0 && isset($opr_list[$v1['operator']]))?$opr_list[$v1['operator']]:'N.A')."</td>
                            <td>".$v1['total']."</td>
                            <td>".$v1['count']."</td>
                        </tr>";                    
                    ?>
                    <tr style="display:none" id="<?php echo 'itr_'.$i;?>"><td></td><td colspan="4">
                        <div style="display:none" id="<?php echo 'idiv_'.$i;?>">
                            <table border="1" cellpadding="0" cellspacing="0" width="100%" align="center">
                                <tr style="text-align:left;background-color:lightblue;">
                                    <th>Sim Number</th>
                                    <th>Operator</th>
                                    <th>Amount</th>
                                    <th>Transaction ID</th>
                                    <th>Admin Status</th>
                                    <th>Vendor Status</th>
                                    <th>Timestamp</th>
                                    <th>Resolve Flag</th>
                                    <th>Comment</th>
                                </tr>
                            <?php
                            foreach ($v1['data'] as $dt)
                            {
                                ?>
                                <tr>
                                    <td><?php echo (strlen(trim($dt['t']['sim_num']))>0?$dt['t']['sim_num']:"N.A"); ?></td>
                                    <td><?php echo ((strlen(trim($dt['t']['operator']))>0 && isset($opr_list[$dt['t']['operator']]))?$opr_list[$dt['t']['operator']]:"N.A"); ?></td>
                                    <td><?php echo (strlen(trim($dt['t']['amount']))>0?$dt['t']['amount']:"N.A"); ?></td>
                                    <td><a href="<?php echo '/panels/transaction/'.$dt['t']['txnid']; ?>" target="_blank" ><?php echo (strlen(trim($dt['t']['txnid']))>0?$dt['t']['txnid']:"N.A"); ?></a></td>
                                    <td><?php echo (strlen(trim($dt['t']['ser_status']))>0?$status_arr['va'][$dt['t']['ser_status']]:"N.A"); ?></td>
                                    <td><?php echo (strlen(trim($dt['t']['vend_status']))>0?$status_arr['vt'][$dt['t']['vend_status']]:"N.A"); ?></td>
                                    <td><?php echo (strlen(trim($dt['t']['timestamp']))>0?$dt['t']['timestamp']:"N.A"); ?></td>
                                    <td>
                                        <?php //echo (strlen(trim($dt['t']['resolve_flag']))>0?$dt['t']['resolve_flag']:"N.A"); ?>
                                        <?php $r_flag = array(0,1);?>
                                        <!--<select id="<?php echo $dt['t']['txnid']; ?>_select" class="resolve_flag_class" name="resolve_flag_class" onchange="update_resolve_flag(<?php echo $dt['t']['txnid']; ?>,this.value)">
                                            <option value="<?php echo $dt['t']['resolve_flag'];?>"><?php echo $dt['t']['resolve_flag'];?></option>
                                            <?php foreach($r_flag as $rflag){
                                                if($rflag == $dt['t']['resolve_flag']){ continue; }
                                                echo "<option value='$rflag'>$rflag</option>";
                                             } ?>
                                        </select>-->
                                        <?php 
                                        $r_flag_ck_y = "";$r_flag_ck_n = "";    
                                        if($dt['t']['resolve_flag'] == 0){ $r_flag_ck_n="checked";}else{$r_flag_ck_y="checked";} ?>
                                        <input type='checkbox' name='r_flag' id="<?php echo $dt['t']['txnid']; ?>_select" value='1' <?php echo $r_flag_ck_y;?> onclick="update_resolve_flag(<?php echo $dt['t']['txnid']; ?>,this)">Y 
                                        <input type='checkbox' name='r_flag' id="<?php echo $dt['t']['txnid']; ?>_select" value='0' <?php echo $r_flag_ck_n;?> onclick="update_resolve_flag(<?php echo $dt['t']['txnid']; ?>,this)">N
                                    </td>
                                    <td>
                                        <textarea name="comment" id="<?php echo trim($dt['t']['txnid']); ?>_comment"><?php echo (strlen(trim($dt['t']['comment']))>0?$dt['t']['comment']:""); ?></textarea>                    
                                        <input type="button" name="comment_update" value="update" onclick="update_comments(<?php echo $dt['t']['txnid']; ?>)">
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                       </table>
                        </div>
                    </td></tr>
                    <tr style="display:none" id="<?php echo 'itr_'.$i;?>"><td colspan="4">&nbsp;</td></tr>
                    <?php
                    $i++;
                }                
    }    
    echo "</table>";
echo "<br/>";
echo "<br/>";
?>
<h2 align="center">SIM WISE SALE REPORT</h2>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
    <tr style="background-color:lightgreen;text-align:left">
        <th>Operator</th>
        <th>Vendor Name</th>
        <th>Sim Number</th>
        <th>Opening</th>
        <th>Closing</th>
        <th>Incomming</th>
        <th>Approx Incomming</th>
        <th>Sale at modem</th>
        <th>Sale at server</th>
        <th>Modem diff</th>
        <th>Server diff</th>
    </tr>
    <?php 
        $server_sim_num  = array();
        $appx_total = 0;
        foreach($sim_reports as $k=>$v){         
            array_push($server_sim_num,$v['sim_num']);
            array_push($sale_sims_array,$v['sim_num']."_".$v['operator']);
            $appx_total += $v['approx_sale'];
            echo "<tr>";
            echo "<td>".($v['operator'])."</td>";
            echo "<td>".($v['vendor'])."</td>";
            echo "<td>".($v['sim_num'])."</td>";
            echo "<td>".($v['opening'])."</td>";
            echo "<td>".($v['closing'])."</td>";
            echo "<td>".($v['incomming'])."</td>";
            echo "<td>".($v['approx_sale'])."</td>";
            echo "<td>".($v['modem_sale'])."</td>";
            echo "<td>".($v['server_sale'])."</td>";
            echo "<td>".($v['modem_diff'])."</td>";
            echo "<td>".($v['server_diff'])."</td>";
            echo "</tr>";
        }
        //$remaining_sim_num = array_diff(array_keys($sims_details),$server_sim_num);
        $remaining_sim_num1 = array_diff($diff_sims_array,$sale_sims_array);
        $remaining_sim_num = array_merge($remaining_sim_num1,$opng_clg_diff);
        echo "<tr><th colspan='10'>Important differences</th></tr>";
        foreach($remaining_sim_num as $sims_n){
            $sims = substr($sims_n,0,10);
            foreach($sims_details[$sims] as $opr=>$val){
                $opr_list_n = explode("_",$sims_n);
                $opr_n = (((strlen($opr))>0 && isset($opr_list[$opr]))?$opr_list[$opr]:'N.A');
                if($opr_list_n[0] == $sims && $opr_list_n[1] != $opr_n ){
                    continue;
                }
                if($val['opening'] != $val['closing']){
                    $modem_diff = $val['sale'] +  $val['closing'] - $val['opening']- $val['diff'] - $val['inc'];
                    $server_diff = "N.A";
                    $incomming = $val['diff'];
                    $vendor = $val['vendor'];
                    $approx_sale = $incomming + $modem_diff - $server_diff;
                    echo "<tr>";
                    echo "<td>".(((strlen($opr))>0 && isset($opr_list[$opr]))?$opr_list[$opr]:'N.A')."</td>";
                    echo "<td>".(isset($vendor)?$vendor:'N.A')."</td>";
                    echo "<td>".$sims."</td>";
                    echo "<td>".$val['opening']."</td>";
                    echo "<td>".$val['closing']."</td>";
                    echo "<td>".(isset($incomming)?$incomming:'N.A')."</td>";
                    echo "<td>".(isset($approx_sale)?$approx_sale:'N.A')."</td>";
                    echo "<td>".((strlen($val['sale']) > 0)?$val['sale']:'N.A')."</td>";
                    echo "<td> N.A </td>";
                    echo "<td>".($modem_diff)."</td>";
                    echo "<td>".($server_diff)."</td>";
                    echo "</tr>";
                    $appx_total += $approx_sale;
                }
            }
        }
        echo "<tr><td colspan='6'></td><td>$appx_total</td><td colspan='4'></td></tr>";
    ?>
</table>
<?php
}
?>
