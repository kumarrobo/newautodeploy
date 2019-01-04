<?php error_reporting(0);?>
 <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
 <script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>
 <link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
<script>
$.noConflict();
function setAction(){
    vendor_id
	modemsIds=jQuery("select#vendor_id").multipleSelect("getSelects");
	document.tranProcess.action="/panels/tranProcess/"+$('from').value+"/"+$('to').value+"/"+modemsIds;
	document.tranProcess.submit();
}
</script>
<script>
jQuery(document).ready(function(){ jQuery('select#vendor_id').multipleSelect({ selectAll: false, width: 290,multipleWidth: 120,multiple: true});});
</script>


<form name="tranProcess" method="POST" onSubmit="setAction()">
From Date <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!is_null($frm))echo $frm;?>" />
To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" value="<?php if(isset($to))echo $to;?>" />
Vendor: <?php //    print_r($vendors); ?>
        <select id="vendor_id" name="vendor_id[]">
         
        <?php foreach($vendors as $key => $vendor){ ?>
            <option value="<?php echo $vendor["vendors"]["id"]; ?>"  <?php if(in_array($vendor["vendors"]["id"],$vendorId))echo "selected"; ?>><?php echo $vendor["vendors"]["company"]; ?></option>
       <?php } ?>
        </select>
<input type="checkbox" name ="b2c_flag" <?php if($b2c_flag=="true"){ ?> checked="checked"<?php } ?> value="true">B2C
<input type="button" value="Submit" onclick="setAction()">
</form>
</br>

<table width="100%" border="0">
<tr>
<td valign="top" >
	<h3>Transactions in Process</h3>
	<table border="1" cellpadding="0" cellspacing="0" >
					<tr> 
						<th>Index</th>
						<th>Tran Id</th>
						<!--<th>Retailer Name/ShopName</th>-->                        
						<th>Vendor</th>
                        <th>Vendor Txn ID</th>
						<th>Cust Mob</th>
	  					<th>Operator</th>
	  					<th>Amt</th>
	  		    		<th>Trans Date</th>
	  		    		<th>Status Check</th>
	  		    		<th>Status Update</th>
	  		    		<th>Manual Success</th>
	  		    		<th>Time left</th>
	  				</tr>
	  		
	  		<?php 
	  		$i=1;
	  			
	  		foreach($process as $d){
	  		/*if(strcmp($d['r']['name'],'')!=0){
	  		$retailerLink=$d['r']['name'];
	  		}
	  		else{
	  		$retailerLink=$d['r']['mobile'];
	  		}*/
				
				
				
		  if (!empty($newRetailer) && array_key_exists($d['va']['txn_id'], $newRetailer)) {
		    $color = '#ADD8E6';
       	   } else if($d['va']['retailer_id'] == 13)$color = '#DBEB23';
	  		else if($d[0]['complaint_flag']) $color = '#D3D3D3';
	  		else $color = '';
	  		
	  		echo "<tr bgcolor='$color'>";
	  		echo "<td>".$i."</td>";
	  		echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."' >".$d['va']['txn_id']."</a></td>";
	  		//echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."' >".$retailerLink."</br>".$d['r']['shopname']."</td>";
            echo "<td>".$d['v']['company']."</td>";
	  		echo "<td>".$d['va']['vendor_refid']."</td>";
	  		echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."' >".$d['va']['mobile']."</a></td>";
	  		echo "<td>".$d['p']['name']."</td>";
	  		echo "<td>".$d['va']['amount']."</td>";
	  		
	  		echo "<td>".$d['va']['timestamp']."</td>";
	  		
	  		echo "<td> <a target='_blank' href='/recharges/tranStatus/".$d['va']['txn_id']."/".$d['v']['shortForm']."/".$d['va']['date']."/".$d['va']['vendor_refid']."'>Status Check</a></td>";
  			if($d['v']['update_flag'] == 1){
	  			echo "<td id='su_".$d['va']['txn_id']."'><a href='javascript:void(0)' onclick='statusUpdate(\"".$d['va']['txn_id']."\",".$d['v']['id'].")'>Status Update</a></td>";
	  		}
	  		else {
	  			echo "<td></td>";
	  		}
  			echo "<td id='ms_".$d['va']['txn_id']."'><a href='javascript:void(0)' onclick='manualSuccess(\"".$d['va']['txn_id']."\")'>Manual Success</a></td>";
  			
  			$secs = (strtotime($d['va']['timestamp']) + (15 * 60) - time()); 
  			$mins = $secs / 60;
  			if($secs < 60){
  				$secs = (time() - strtotime($d['va']['timestamp']) - (15 * 60));
  				if($mins < 0){
  					$mins = $secs / 60;
  					$hours = round($mins / 60);
  					$mins = round($mins % 60);
  					echo "<td style='color:red'>".$hours." Hrs ".$mins." mins delayed </td>";
  				}
  				else
  					echo "<td style='color:orange'>".round($secs)." secs left </td>";
  			}
  			else {
  				$hours = intval($mins / 60);
  				$mins = intval($mins % 60);
  				echo "<td>".$hours." Hrs ".$mins." mins left </td>";
  			}
  			
	  		$i++;	
	  		echo "</tr>";
	  		}
	  		echo "Total in process:".($i-1)."</br></br>";
		 ?> 
		</table>
</td>
</tr>
</table>
<script>

function statusUpdate(id,vendor){
	$('su_'+id).innerHTML='Submitting';
	var url = DOMAIN_TYPE.'://'.$_SERVER['SERVER_NAME'].'/recharges/modemUpdateStatus';
		var params = {'id' : id,'vendor' : vendor};
		var myAjax = new Ajax.Request(url, {
		method: 'post', parameters: params,contentType:"application/x-www-form-urlencoded",
		onSuccess:function(transport)
				{		
					var html = transport.responseText;
					$('su_'+id).innerHTML= html;
				}
		,
		 onCreate: function(response) { // here comes the fix
                var t = response.transport; 
                t.setRequestHeader = t.setRequestHeader.wrap(function(original, k, v) { 
                    if (/^(accept|accept-language|content-language)$/i.test(k)) 
                        return original(k, v); 
                    if (/^content-type$/i.test(k) && 
                        /^(application\/x-www-form-urlencoded|multipart\/form-data|text\/plain)(;.+)?$/i.test(v)) 
                        return original(k, v); 
                    return; 
                }); 
            }});
}

function manualSuccess(id){
	var r=confirm("Confirm?");
	if(r==true){
		$('ms_'+id).innerHTML='Submitting';
	
		var url = '/panels/manualSuccess';
			var params = {'id' : id};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
			onSuccess:function(transport)
					{		
						var html = transport.responseText;
						$('ms_'+id).innerHTML= html;
					}
			});
	}
}

//statusUpdate1('302213377656',4);
</script>