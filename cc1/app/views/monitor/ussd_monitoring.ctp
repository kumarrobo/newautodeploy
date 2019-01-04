<script>
function switch_ussd(switch1)
{
		
		var url='/monitor/switch_ussd';
		var pars="switch="+switch1;
			
		var myAjax= new Ajax.Request(url,{method: 'post',parameters:pars,
		onSuccess:function(transport)
			   {
			   	var html=transport.responseText;
			   	if(html == 'success'){
				   	if(switch1 == 1){
				   		col1 = '#99ff99';
				   		col2 = 'red';
				   	}
				   	else {
				   		col1 = 'red';
				   		col2 = '#99ff99';
				   	}
				   	
				   	$('ussd_1').style.backgroundColor=col1;
				   	$('ussd_2').style.backgroundColor=col2;
				   	
					alert("USSD server shifted to server "+switch1);
				
				}
				else {
					alert("Some problem in server");
				}
			 }
		});  
}

</script>

<div>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <div id="innerDiv">
                <div style ="font:10px">
                    <?php echo $this->element('monitoring_header'); ?>	
                    <div style ="font-size:20px;padding: 5px;"> USSD Reports (Last 30 mins report) </div>
                    
                    <div>
                    <?php $link_247 = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."/247"; ?>
                    	<a href="javascript:void(0)" title="Click to activate" onclick="switch_ussd(1)"><span id="ussd_1" style="background-color: <?php if($switch == '1')echo '#99ff99'; else echo 'red';?>">Hyderabad Server</span></a>
                    	| <a href="javascript:void(0)" title="Click to activate" onclick="switch_ussd(2)"><span id="ussd_2" style="background-color: <?php if($switch == '2')echo '#99ff99'; else echo 'red';?>">Delhi Backup Server</span></a>
                        | <a href="<?php echo $link_247; ?>" ><span id="ussd_1" style="background-color:yellow">247 USSD</span></a>
                    </div>
                    <div style ="font-size:15px;padding: 5px;"> Contact Details :-  Ravi (Number:  9008470088 ) ,Amit (Number:  9901124778 )  (Email:  ) </div>
              <table class="ListTable" width="100%" border="1" style="border-collapse:collapse;font-size: 14px;">
                        <thead style="font-weight: bold; background: none repeat scroll 0 0 #F3F3F3">
<!--                            <tr>
                                <td colspan="4" > USSD Request Report </td>                            
                            </tr>-->
                            <tr>
                                <td align="center">Status</td>
                                <td align="center">Count</td>
                                <td align="center">Total</td>
                                <td align="center">Percentage(%)</td>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr bgcolor="<?php echo ($countsPer['success'] <= 60 || $counts['total']==0 ? '#c73525' : '#99ff99'); ?>">
                                <td align="center">Success</td>
                                <td align="center"><?php echo $counts['success'] ;?></td>
                                <td align="center"><?php echo $counts['total'] ;?></td>
                                <td align="center"><?php echo $countsPer['success'] ;?> %</td>
                            </tr>
                            
                            <tr bgcolor="<?php echo ($countsPer['failed'] >= 25 || $counts['total']==0 ? '#c73525' : '#99ff99'); ?>">
                                <td align="center">Failed</td>
                                <td align="center"><?php echo $counts['failed'] ;?></td>
                                <td align="center"><?php echo $counts['total'] ;?></td>
                                <td align="center"><?php echo $countsPer['failed'] ;?> %</td>
                            </tr>
                            <tr bgcolor="<?php echo ($countsPer['lvl0Fails2t'] >= 25 || $counts['total']==0 ? '#c73525' : '#99ff99'); ?>">
                                <td align="center">Not Processed (TATA not connecting)</td>
                                <td align="center"><?php echo $counts['lvl0Fails2t'] ;?></td>
                                <td align="center"><?php echo $counts['total'] ;?></td>
                                <td align="center"><?php echo $countsPer['lvl0Fails2t'] ;?> %</td>
                            </tr>
                            <tr bgcolor="<?php echo ($countsPer['lvl0Failt2s'] >= 25 || $counts['total']==0 ? '#c73525' : '#99ff99'); ?>">
                                <td align="center">Not Processed (No response from TATA)</td>
                                <td align="center"><?php echo $counts['lvl0Failt2s'] ;?></td>
                                <td align="center"><?php echo $counts['total'] ;?></td>
                                <td align="center"><?php echo $countsPer['lvl0Failt2s'] ;?> %</td>
                            </tr>
                        </tbody>
                
                </table>
                </div>
            </div>
            <br class="clearLeft" />
        </div>
    </div>
</div>
<br class="clearRight" />
