<div style="min-height:600px;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
		<tr><td width="60%" valign="top">
         <div>
         <select name ="rettype" id = "rettype" onChange="findRet(this.value)">
            <option value="">-----Select Retailer----</option>
            <option value="Retailer" <?php if($type=="Retailer" ){?> selected='selected' <?php } ?>>Retailer</option>
            <option value="Distributor" <?php if($type=="Distributor" ){?> selected='selected' <?php } ?>>Distributor</option>
            <option value="Retailer Delhi" <?php if($type=="Retailer Delhi" ){?> selected='selected' <?php } ?>>Retailer Delhi</option>
            <option value="Recharge Card" <?php if($type=="Recharge Card" ){?> selected='selected' <?php } ?>>Recharge Card</option>
            <option value="Toll-free Call" <?php if($type=="Toll-free Call" ){?> selected='selected' <?php } ?>>Toll-free Call</option>
            <option value="Online Leads" <?php if($type=="Online Leads" ){?> selected='selected' <?php } ?>>Online Leads</option>
            <option value="Wholesaler" <?php if($type=="Wholesaler" ){?> selected='selected' <?php } ?>>Wholesaler</option>
            <option value="Limit" <?php if($type=="Limit" ){?> selected='selected' <?php } ?>>Limit</option>
            <option value="Marketing" <?php if($type=="Marketing" ){?> selected='selected' <?php } ?>>Marketing</option>
            <option value="Smartpay" <?php if($type=="Smartpay" ){?> selected='selected' <?php } ?>>Smartpay</option>
            <option value="Dmt" <?php if($type=="Dmt" ){?> selected='selected' <?php } ?>>Dmt</option>
            <option value="Travel" <?php if($type=="Travel" ){?> selected='selected' <?php } ?>>Travel</option>
          </select>
        </div>
		<div class="appTitle">Calls Dropped</div>
         
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
		<thead>
          <tr class="noAltRow altRow">
            <th style="width:30px;">S. No.</th>
            <th style="width:60px;">Demo Number</th>
            <th style="width:100px;">Shop Name</th>
            <th style="width:60px;">Type</th>
            <th style="width:60px;">Time of call</th>
            <th style="width:30px">Calls today</th>
            <th style="width:30px;"></th>
            <th style="width:30px"></th>
          </tr>
        </thead>
        <tbody id="callbody">
        	<?php $i = 0; foreach($callData['data'] as $call) { 
                if($call['cc_call_logging']['call_status']==0){
                $i++;
        	
        	
        	$val1 = "Done?";
			$val2 = "Not Picked";
			$func1 = "calls(".$call['cc_call_logging']['id'].",'done','".$call['cc_call_logging']['number']."')";
			$func2 = "calls(".$call['cc_call_logging']['id'].",'npicked','".$call['cc_call_logging']['number']."')";
        	
        	
        	?>
        	  <tr>
        	  	<td><?php echo $i; ?></td>
	            <td id="number_<?php echo $call['cc_call_logging']['number']; ?>"><a href="/panels/retInfo/<?php echo $call['cc_call_logging']['number'];?>" target="Retailer Info"><?php echo "0".$call['cc_call_logging']['number']; ?></a></td>
	            <td><?php echo $call['0']['caller_name']; ?></td>
	            <td><?php echo $call['cc_call_logging']['type']; ?></td>
	            <td><?php echo $call['cc_call_logging']['date'] . " " .$call['cc_call_logging']['time']; ?></td>
	            <td><?php echo $call['0']['calls']; ?></td>
	            <td id="act1<?php echo $call['cc_call_logging']['id'];?>"><input type="button" value="<?php echo $val1; ?>" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub1<?php echo $call['cc_call_logging']['id'];?>" onclick="<?php echo $func1;?>"></td>
	            <td id="act2<?php echo $call['cc_call_logging']['id'];?>">
	            <?php if(!empty($val2)){ ?>
	            <input type="button" value="<?php echo $val2; ?>" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub2<?php echo $call['cc_call_logging']['id'];?>" onclick="<?php echo $func2;?>">
	          	<?php } ?>
	          	</td>
	          </tr>
	       <?php } }?>
        </tbody>	         
   	</table>
   	</td>
   	<td width="40%" valign="top">
   		<div class="appTitle">Calls Done</div>
		
   		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
		<thead>
          <tr class="noAltRow altRow">
            <th style="width:30px;">S. No.</th>
            <th style="width:60px;">Demo Number</th>
            <th style="width:100px;">Shop Name</th>
            <th style="width:30px;">Count</th>
            <th style="width:100px;">Call Type</th>
            <th style="width:60px;">Handled by</th>
            <th style="width:100px;">Status</th>
            <!--<th style="width:100px;">Call duration</th>
            <th style="width:30px;">Note</th>-->
            <th style="width:30px;">Timestamp</th>
            <th style="width:30px;">CallBack Time</th>
          </tr>
        </thead>
        <tbody id="callbody">
        	<?php $i = 0; foreach($callDone as $call) { 
                    if($call['cc_call_logging']['call_status']!=0){
                    $i++; 
                
                    ?>
        	
        	  <tr>
        	  	<td><?php echo $i; ?></td>
	            <td><a href="/panels/retInfo/<?php echo $call['cc_call_logging']['number'];?>" target="Retailer Info"><?php echo $call['cc_call_logging']['number']; ?></a></td>
	            <td><?php echo $call['0']['caller_name']; ?></td>
	            <td><?php echo $call[0]['c_count'] ?></td>
	             <td><?php echo $call['cc_call_logging']['type']; ?></td>
	            <td><?php echo $call['users']['name']; ?></td>
	            <td><?php if($call['cc_call_logging']['call_status']==1) echo "Success"; else if($call['cc_call_logging']['call_status']==2) echo "Call Not Picked"; else if($call['cc_call_logging']['call_status']==3) echo "Call Dropped";?></td>
	            <!--<td><?php if($call['cc_call_logging']['call_status']==1)echo intval($call['0']['duration']/60) . " mins " . ($call['0']['duration']%60) . " secs"; ?></td>
	            <td><?php echo $call['cc_call_logging']['note']; ?></td>-->
	            <td><?php echo $call['cc_call_logging']['date'] . " " . $call['cc_call_logging']['time']; ?></td>
	          	<td><?php echo $call['cc_call_logging']['call_start'];?></td>
	          </tr>
	       <?php 
                }
                }
                 ?>
        </tbody>	         
   	</table>
   	
   	</td>
  </tr>
</table>
</div>   	
   	<div><input type="hidden" value="<?php echo $callData['cc_id']; ?>" id="ccid"></div>
<script>




function RunExe(id,mobile,type) {
	var params = {};
	var url = "http://localhost/calls/call.php?query="+type+"&number="+mobile+"&host=192.168.1.3";
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
	onSuccess:function(transport)
			{		
				var response = transport.responseText;
				if(type == 'start'){
					calls(id,type);
				}
				else if(type == 'end'){
					calls(id,type);
				}
			},
	onFailure:function()
			{		
				alert('Something went wrong...');
			}
	});
}
   	
function calls(id,type,mobile){
	var r= true;
	var url = '';
	var html = '';
	if(type == 'done'){
		url = '/cc/callDone';
		html = $('act1'+id).innerHTML;
		showLoader2('act1'+id);
	}
	else if(type == 'start'){
		url = '/cc/callStart';
		html = $('act1'+id).innerHTML;
		showLoader2('act1'+id);
	}
	else if(type == 'end'){
		url = '/cc/callEnd';
		html = $('act1'+id).innerHTML;
		showLoader2('act1'+id);
	}
	else if(type == 'npicked'){
		url = '/cc/callNotPicked';
		html = $('act1'+id).innerHTML;
		showLoader2('act1'+id);
		//r=confirm("Confirm?");
	}
	else if(type == 'callback'){
		$('messagePopUpDiv').innerHTML = '<fieldset>'+
			'<div class="field">'+
				'<label style=" display:block; float:left; width:94px; padding-top:10px;" for="userMobile">Time</label><input type="text" style="width: 150px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, \'fromDate\',\'restrict=true,open=true\')" id="fromDate" name="fromDate" value="">'+
 			'</div>'+
            '<div class="field">'+				
				'<label style=" display:block; float:left; width:94px; padding-top:10px;" for="userPassword">Comment</label><textarea id="area" cols="10" rows="10"></textarea>'+
			'</div>'+
			'<div style="padding-top:16px;" class="field">'+
               '<label style=" display:block; float:left; width:94px;">&nbsp;</label><div style="float:left" id="loginSignIn"><input type="button" value="Submit" class="retailBut enabledBut" style="padding: 0 5px 3px" id="onclick="calls(id,\'callback_next\');"></div>'+
	       	'</div>'+
	       	'<div class="clearLeft"></div>'+
            '<div class="field">'+
               '<label style=" display:block; float:left; width:94px;">&nbsp;</label><span class="errMessage" id="UloginErrMessage"></span>'+ 
	       	'</div>'+    
		'</fieldset>';
		centerPos('popUpDiv');
		r = false;
	}
	else if(type == 'callback_next'){
		url = '/cc/callBack';
		params = 'date='
		html = $('back1'+id).innerHTML;
		showLoader2('back1'+id);
	}
	else if(type == 'callbackdone'){
		url = '/cc/callBackDone';
		html = $('back1'+id).innerHTML;
		showLoader2('back1'+id);
	}
	else if(type == 'callbackcancel'){
		url = '/cc/callBackCancel';
		html = $('back2'+id).innerHTML;
		showLoader2('back2'+id);
	}
	
	if(r==true){
		var params = {'id' : id,'mobile':mobile};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{		
					var response = transport.responseText.trim();
					
					if(type == 'done'){
						$('act1'+id).innerHTML = html;
							
						if(response == '1'){
							$('sub1'+id).value = "Call done";
							$('sub1'+id).onclick="";
						}
						else {
							alert(response);
						}
					}
					else if(type == 'start'){
						$('act1'+id).innerHTML = html;
							
						if(response == '1'){
							$('sub1'+id).value = "End Call";
							$('sub1'+id).onclick="";
							$('sub1'+id).observe('click', function() {
								RunExe(id,'','end');
				     		});
				     		var mobile = $('number_'+id).innerHTML;
				     		$('act2'+id).innerHTML = '<input type="button" value="Restart Call" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub2'+id+'" onclick="RunExe(id,mobile,\'start\');">';
						}
						else {
							alert(response);
						}
					}
					else if(type == 'end'){
						$('act1'+id).innerHTML = html;
							
						if(response == '1'){
							/*$('sub1'+id).value = "Call Back";
							$('sub1'+id).onclick="";
							$('sub1'+id).observe('click', function() {
				     			calls(id,'callback');
				     		});*/
				     		$('act1'+id).innerHTML = '<input type="button" value="Call Not Picked" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub1'+id+'" onclick="calls(id,\'npicked\');">';
				     		/*$('sub2'+id).value = "";
				     		$('sub2'+id).onclick="";
							$('sub2'+id).observe('click', function() {
				     			calls(id,'npicked');
				     		});*/
						}
						else {
							alert(response);
						}
					}
					else if(type == 'npicked'){
						$('act1'+id).innerHTML = 'Done';
						//$('act2'+id).innerHTML = '';
						alert(response);
							
					}
					else if(type == 'callback'){
						$('act1'+id).innerHTML = 'Done';
						$('act2'+id).innerHTML = '';
						alert(response);
					}
					else if(type == 'callbackdone'){
						$('back1'+id).innerHTML = 'Done';
						$('back2'+id).innerHTML = '';
						alert(response);
					}
					else if(type == 'callbackcancel'){
						$('back1'+id).innerHTML = 'Cancelled';
						$('back2'+id).innerHTML = '';
						alert(response);
					}
				}
		});
	}
}

function findRet(ret)
{
	var url="/cc/panel/"+ret;
	window.location.href = url;
}
</script>
