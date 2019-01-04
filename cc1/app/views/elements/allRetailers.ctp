<script type="text/javascript" src="/js/modalbox.js"></script> 
<link rel="stylesheet" href="/js/modalbox.css" type="text/css" />
<style type="text/css" media="screen">
		#MB_loading {
			font-size: 13px;
		}
	</style>

<script>
function saveMaintenanceSm(rid,obj)
{
	var salesManId=obj.options[obj.selectedIndex].value;
		
	var r=confirm("You sure?");
	if(r==true){
		var url = '/salesmen/mapSalesman';
		var params = {'rid' : rid,'sid':salesManId};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					alert('done');
				}
		});
	}
}

function retEnable(rid,obj)
{
	var flag=obj.options[obj.selectedIndex].value;
	var r=confirm("You sure?");
	if(r==true){
		var url = '/salesmen/blockRetailer';
		var params = {'rid' : rid,'flag':flag};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					if(transport.responseText == 'success'){
						alert('done');
					}else{
						alert('Try again');
					}
				}
		});
		
	}
}

function findRet(flag,obj)
{
	if(flag==1){
		var distId=obj.options[obj.selectedIndex].value;
		var url="/panels/retColl/"+distId;
	}
	else {
		var salesManId=obj.options[obj.selectedIndex].value;
		var distId=$('distributor').options[$('distributor').selectedIndex].value;
		var url="/panels/retColl/"+distId+"/"+salesManId;
	}
	window.location.href = url;
}

function filter(obj)
{
	var filter=obj.options[obj.selectedIndex].value;
	var distId=$('id').options[$('id').selectedIndex].value;
	
	if(filter == 0){
		var table = document.getElementById("tableRet");
		for (var i = 0, row; row = table.rows[i]; i++) {
		   row.style.display='';
		}
		$('count').innerHTML = "All Retailers - Total: " + table.rows.length;
		$('head').style.display='';
	}
	else {
		var url = '/shops/retFilter';
		var params = {'id' : distId,'filter':filter};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
			onSuccess:function(transport)
				{
					var table = document.getElementById("tableRet");
					for (var i = 0, row; row = table.rows[i]; i++) {
					   row.style.display='none';
					}
					
					var ids = transport.responseText;
					if(ids != ''){
						ids = ids.split(",");
						$('head').style.display='';
						for(var j=0;j<ids.length;j++){
							if($('ret_'+ids[j]))$('ret_'+ids[j]).style.display='';
						}
						$('count').innerHTML = obj.options[obj.selectedIndex].text + " - Total: " + ids.length;
					}
					else {
						$('count').innerHTML = obj.options[obj.selectedIndex].text + " - Total: 0";
					}
				}
		});
	}
}

function findRetailers(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var obj = $('id');
	var salesman = obj.options[obj.selectedIndex].value;
                 
	<?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
		 var retailerid = $("retailerid").value;
         var serviceid  = $("serviceid").value;                 
	<?php } else { ?>
	var retailerid = 0;
        var serviceid  = 0;
	<?php } ?>
		
        <?php 
            if($retailer_type == 'deleted'){
                $c_type = 'deletedRetailer';
            }else{
                $c_type = 'allRetailer';
            }
        ?>
	if(salesman == 0 && retailerid == 0 && serviceid == 0){
		window.location.href ="/shops/<?php echo $c_type; ?>";
	}
	else {
		window.location.href ="/shops/<?php echo $c_type; ?>/"+salesman+"/"+retailerid+"/"+serviceid;
	}
        
}
function delRetailer(typ , rid , flag){
        var toShow , block , msg;
        if(flag == 'delete'){
                toShow = 0 ;
                block = 1;                
                
        }else if(flag == 'revert'){
                toShow = 1;
                block = 0;
        }else{
            return false;
        }
       
	if(confirm("Do you want to "+flag+" these retailer ?")){
          
            var url = '/shops/deleteRetailer';
            var params = {'id' : rid,'type':typ , 'toShow' : toShow , 'block' : block };
            var myAjax = new Ajax.Request(url, {method: 'post',type: 'JSON', parameters: params,
                    onSuccess:function(transport)
                            {
                                    if(transport.status == 200){
                                        $("ret_"+rid).hide();
                                    }
                            }
            });
            
        }
}

        function changeNumber(mobileNo , dist_mobileNo){
            $("old_mob").value = mobileNo;

            var url = '/apis/receiveWeb/mindsarray/mindsarray/json?method=sendOTPToRetDistLeads';
            
            var pars = {'mobile':dist_mobileNo,'interest':'Distributor','changeMobile':true};
            var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
                    onSuccess:function(transport)
                    {
                        var res = transport.responseText;
                        var arr = res.split('"');
//                        console.log(arr);
                        if(arr[3] == "success"){
                          Modalbox.show($('checkData'), {title: this.title, height: 250 }); return false;            
                        }else{
                            alert('OTP sent '+arr[3]+' Try again.');

                        }
                   						
	            }
                });
        }
		      


  function submit(){
         
//	   alert("Can not change Number!!!!");
//	   return false;
       var otpMob = $("otp_mob").value;
       var newMob = $("new_mob").value;
       var oldMob = $("old_mob").value;
       var disMob = $("dist_mob").value;
       
        if(otpMob==''){
        alert("Please enter OTP sent to your Mobile Number");
        return false;
        }else if(newMob==''){
        alert("Please Enter New Mobile Number");
        return false;
        }
        else if(oldMob==''){
        alert("Please Enter Old Mobile Number");
        return false;
        }

        else if (mobileValidate(newMob)==false)
        {
        alert("Please enter a valid mobile number.");
        return;
        }
        else if(newMob==oldMob){
           alert ("Old number and new number provided are same. Please give a new number.");
	       return false;
        }
        
        var url = '/apis/receiveWeb/mindsarray/mindsarray/json?method=verifyOTP';
        var pars = {'dist_mobile':disMob,'newMobile':newMob,'otp':otpMob,'interest':'Distributor','changeMobile':true};
        var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
                onSuccess:function(transport)
                {
                                    
                var res = transport.responseText;
                var arr = res.split('"');
//                console.log(arr);
                if(arr[3] == "success"){
                    
                    var url = '/panels/addNewNumber/';
                    var pars = {'newNumber':newMob,'oldNumber':oldMob};
                    
                    var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
                        onSuccess:function(transport)
                        {
                            var res = transport.responseText;
                            var arr = res.split('^^^');
                            if(arr[0] == 1){
                                    alert(arr[1]);
                            }else{
                                    alert(arr[1]+'Try again.');

                            }
                            Modalbox.hide($('checkData'));
                            location.reload();
                   						
		        }
		    });
        		    
    
                }else{
//                    console.log('---'+arr[3]+'---'+arr[4]);
			alert('OTP match '+arr[3]+' Try again.');
										
		    }
		}
        });

    }
</script>
            <div id="checkData" style="display:none;">
             <table>
             
                <tr align="left">
                    <td>OTP</td>
                    <td><input  type="text" id="otp_mob" name="otpMobile" maxlength="6" placeholder="OTP"  /></td>
                </tr>
                <tr align="left">
                    <td>New Mobile</td>
                    <td><input  type="text" id="new_mob" name="newNumber" maxlength="10" placeholder="Enter New Mobile No"  /></td>
                </tr>
                <tr align="left">
                    <td>Old Mobile</td>
                    <td><input  value="" disabled="disabled" type="text" id="old_mob"  name="oldNumber"/></td>
                </tr>
                <tr align="left">
                    <td><input  value="<?php echo $this->Session->read('Auth.User.mobile'); ?>" type="hidden" id="dist_mob"  name="dist_mob"/></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td> <input type="button" class="retailBut enabledBut" value="submit" onclick="submit();"></td>
                </tr>
                </table>
            </div>
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
	  			<?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
    			<span style="font-weight:bold;margin-right:10px;">Select Salesman: </span>
    			<select name="salesmanDD" id="id">
					<option value="0">None</option>
					<?php 
					foreach($salesmen as $d)
					{		
								$sel = '';
								if($sid == $d['salesmen']['id'])
								$sel = 'selected';
								
						 		echo "<option $sel value='".$d['salesmen']['id']."' >".$d['salesmen']['name']." (".$d['salesmen']['mobile'].")</option>";
						 		
					}
					?>				
					
				</select>
				<span style="font-weight:bold;margin-right:10px;">Select Retailer: </span>
				<?php //echo "<pre>";					print_r($retailers);die; ?>
    			<select name="retailerid" id="retailerid" style="width:200px;">
					<option value="0">None</option>
					<?php 
					foreach($retailers as $d)
					{		
						$sel = '';
						if($retId == $d['Retailer']['id'])
						$sel = 'selected';
						
				 		echo "<option $sel value='".$d['Retailer']['id']."'>".$d['Retailer']['shopname']."(".$d['Retailer']['mobile'].")</option>";
					}
					?>				
					
				</select>
                                <span style="font-weight:bold;margin-right:10px;">Select Services: </span>
                          <select name="serviceid" id="serviceid" style="width:200px;">
					<option value="0">All</option>
					<?php 
                                            foreach ($serviceDet as $servc) {
                                                
                                                $sel = '';
                                                if ($servId == $servc['services']['id'])
                                                    $sel = 'selected';

                                                echo "<option $sel value='" . $servc['services']['id'] . "'>" . $servc['services']['name'] . "</option>";
                                            }
                                            ?>									 

				</select>
				<?php } else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) { ?>
				<span style="font-weight:bold;margin-right:10px;">Select Distributor: </span>
    			<select name="salesmanDD" id="id">
					<option value="0">None</option>
					<?php
					foreach($distributors as $d)
					{		
						$sel = '';
						if($dist == $d['Distributor']['id'])
						$sel = 'selected';
						
				 		echo "<option $sel value='".$d['Distributor']['id']."'>".$d['Distributor']['company']."</option>";
					}
					?>
					
				</select>
				<?php } ?>
				
				<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findRetailers();"></span>
    			</div>
            <?php 
                if($retailer_type != 'deleted'){ 
            ?>
    			<div class="rightFloat">
	    			<span style="font-weight:bold;">Filter: </span>
	    			<select name="filter" id="filter" onChange="filter(this)">
						<option value="0">None</option>
						<option value="1">Top Transacting in last 7 days</option>
						<option value="2">Avg Transacting in last 7 days</option>
						<option value="3">Low Transacting in last 7 days</option>
						<option value="4">Dropped in last 2 days</option>
						<option value="5">Dropped in last 7 days</option>
						<option value="6">Dropped between last 7-14 days</option>
						<option value="7">Dropped between last 14-30 days</option>
						<option value="8">Dropped before 30 days</option>
					</select>
    			</div>
                         
         <?php       $c_type = 'deletedRetailer';
               }
          ?>
    			<div class="clearRight"></div>
				
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select salesman</span></div>
				
				<div class="appTitle" style="margin-top:10px;"><span id="count">All Retailers - Total: <?php echo count($records); ?></span></div>
				<table id="tableRet" width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
        			<tr id="head" class="noAltRow altRow">
			          	<!--<th style="width:20px;">Sr. No.</th>-->
			            <th style="width:100px;">Name</th>
			            <!--<th style="width:50px;">Mobile</th>-->
			            <?php if(($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) && (isset($servId)))  { ?>                                    
                                    <th style="width:50px;">Service</th>
                                    <?php } ?>
			            <th class="number" style="width:35px">Rental/Kit</th>
			            <?php if(($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) && (isset($servId)))  { ?>  
                                    <th class="number" style="width:30px">Status</th>
                                    <th class="number" style="width:30px">Margin</th>
                                    <?php } ?>                               
                                    <th class="number" style="width:30px">Balance</th>
			            <th class="number" style="width:30px">Topup Today</th>
			            <th class="number" style="width:35px">Sale Today</th>
			            <th class="number" style="width:40px">Avg Sale (Last 30 days)</th>
			            <th class="number" style="width:40px">Last Transaction</th>
			            <?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
			            <th style="width:30px;">Status</th>
			            <th style="width:30px;">Salesman</th>
                        <th style="width:20px;">&nbsp;</th>
			            <th style="width:20px;">&nbsp;</th>
                                    <th style="width:20px;">&nbsp;</th>
			            <?php 
			        	}
			            if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) {
			             ?>
			            <th style="width:20px;">&nbsp;</th>
			            <?php
						}
			            ?>
			          </tr>
			        </thead>
                    <tbody>
                        
                    <?php $i=0; $totBal = 0; $totTran = 0; $totSale = 0;$totAvg = 0; foreach($records as $rec){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    	           //print_r($rec) ;          
			        $type = 'r';
		            	$totBal = $totBal + $rec[$modelName]['balance'];
                                $totTran = $totTran + $rec[0]['xfer'];
//                                if($_SESSION['Auth']['system_used'] == 0) {
//                                } else {
//                                        $totTran = $totTran + $topupToday[$rec['Retailer']['id']];
//                                }
		            	$totSale = $totSale + (isset($amounts[$rec[$modelName]['id']]['sale'])?$amounts[$rec[$modelName]['id']]['sale']:0);
		            	$avg = isset($amounts[$rec[$modelName]['id']]['average'])?$amounts[$rec[$modelName]['id']]['average']:0;
                                $totAvg = $totAvg + $avg;
			            			            
                   	 	if($avg >= 1000)
			      			$color='#008000';
			      		else if($avg >= 500 && $avg < 1000)
			      			$color='#000066';
			      		else if($avg < 500)
			      			$color='#FF0000';
			      		
			      		if(!empty($lastTrans[$rec[$modelName]['id']])){	
				      		$date1 = new DateTime(date('Y-m-d'));
							$date2 = new DateTime($lastTrans[$rec[$modelName]['id']]);
							$interval = $date1->diff($date2);
							$days = ($interval->y)*365 + ($interval->m)*30 + $interval->d;
							
							if($days == 0) $lastTr = "Today";
							else if($days == 1) $lastTr = "Yesterday";
							else $lastTr = "$days days back";
						}
						else {
							$lastTr = "Never";
						}
    			    ?> 
    			      <tr id="ret_<?php echo $rec[$modelName]['id']; ?>" style="color:<?php echo $color; ?>" class="<?php echo $class; ?>"> 
    			      	<!--<td><?php echo ($i+1); ?></td>-->
			            <td><a href="<?php echo $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER ? "#" : "/shops/showDetails/".$type."/".$rec[$modelName]['id'] ; ?>"><?php echo $rec['ur']['shopname'] != '' ? $rec['ur']['shopname'] : $rec[$modelName]['shopname']; ?></a></td>
			            <td><a href="<?php echo $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER ? "#" : "/shops/showDetails/".$type."/".isset($rec[$modelName]['id'])?$rec[$modelName]['id']:''; ?>"><?php echo $rec['users']['id']; if(!empty($rec[$modelName]['pin']) && !empty($rec[$modelName]['area_id']) && !empty($rec[$modelName]['address']) && !empty($rec[0]['longitude'])) echo "<img src='/img/success.png' alt='Address updated' height='18px' width='18px'>"; ?></a></td>
			            <?php if(($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) && (isset($servId)))  { ?>                                    
                                    <td><?php echo $servname[$rec['us']['service_id']]; ?></td>
                                    <?php } ?>
                                    <td class="number"><?php if($rec[$modelName]['rental_flag'] == 0)echo "Kit"; else echo "Rental"; ?> </td>
                                    <?php if(($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) && (isset($servId)))  { ?>                                    
                                            <td><?php if(($rec['us']['kit_flag'] == '1') && ($rec['us']['service_flag'] == '1')) { echo 'Active'; } else { echo 'Deactive'; } ?></td>
                                            <td><?php echo $rec['us']['param1']; ?></td>
                                    <?php } ?>
			            <!--<td class="number"><?php if(isset($amounts[$rec[$modelName]['id']]['setup']))echo $amounts[$rec[$modelName]['id']]['setup']; ?></td>-->
			            <td class="number"><?php echo round($rec[$modelName]['balance'],1); ?></td>
			            <td class="number"><?php echo intval($rec[0]['xfer']); ?></td>
                                <?php // if($_SESSION['Auth']['system_used'] == 0) { ?>
                                <?php // } else { ?>
			            <!--<td class="number"><?php // echo isset($topupToday[$rec['Retailer']['id']]) ? round($topupToday[$rec['Retailer']['id']]) : 0; ?></td>-->
                                <?php // } ?>
			            <td class="number"><?php if(isset($amounts[$rec[$modelName]['id']]['sale']))echo round($amounts[$rec[$modelName]['id']]['sale'],1); ?></td>
			            <td class="number"><?php if(isset($amounts[$rec[$modelName]['id']]['average']))echo round($amounts[$rec[$modelName]['id']]['average'],1); ?></td>
			            <td class="number"><?php echo $lastTr; ?></td>
			            <?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
			            <td>
			            	<select style="font-size:10px" name="block_salesmanDD" id="block_salesmanDD" onChange="retEnable('<?php echo $rec[$modelName]['id']; ?>',this)">
							<?php if($rec[$modelName]['block_flag'] == '0'){ ?>
								<option value="0" selected>None</option>
								<option value="1">Partially Blocked</option>
								<option value="2">Fully Blocked</option>
							<?php } else if($rec[$modelName]['block_flag'] == '1'){ ?>
								<option value="0">None</option>
								<option value="1" selected>Partially Blocked</option>
								<option value="2">Fully Blocked</option>
							<?php } else if($rec[$modelName]['block_flag'] == '2'){ ?>
								<option value="0">None</option>
								<option value="1">Partially Blocked</option>
								<option value="2" selected>Fully Blocked</option>
							<?php } ?>
			
							</select>
						</td>
						<td>
							<select style="font-size:10px" name="maintenance_salesmanDD" id="maintenance_salesmanDD" onChange="saveMaintenanceSm('<?php echo $rec[$modelName]['id']; ?>',this)">
								<option value="0">None</option>
								<?php if(!empty($salesmen)) foreach($salesmen as $d) {		
								$sel = '';
								if($rec[$modelName]['maint_salesman'] == $d['salesmen']['id'])
								$sel = 'selected'; ?>
															
						 		<option <?php echo $sel;?> value='<?php echo $d['salesmen']['id']; ?>' ><?php echo $d['salesmen']['name']." (".$d['salesmen']['mobile'];?>)</option>";
						 		
								<?php } ?>
							</select>
						</td>
		
			            <td class="number"><a href="/shops/editRetailer/<?php echo $type; ?>/<?php echo $rec[$modelName]['id']; ?>">edit</a></td>
                                    <?php if( $rec[$modelName]['toshow'] == 0){ ?>
                                        <td class="number"><a onclick="delRetailer('<?php echo $type; ?>',<?php echo $rec[$modelName]['id']; ?> , 'revert');return false ;" href="javascript:void(0)">revert</a></td>
                                    <?php }else{ ?>
                                        <td class="number"><a onclick="delRetailer('<?php echo $type; ?>',<?php echo $rec[$modelName]['id']; ?> , 'delete');return false ;" href="javascript:void(0)">delete</a></td>
                                    <?php } } 
                                    if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) {
                                    ?>
			            <td class="number"><a target="Sale Report" href="/shops/graphRetailer/?type=<?php echo $type; ?>&id=<?php echo $rec[$modelName]['id']; ?>">Analyze</a></td>
                        <?php
                    	}
                         if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { $dist_mobileNo = $this->Session->read('Auth.User.mobile'); ?>
                        <td class="number"><a href="#" onclick="changeNumber('<?php echo $rec['users']['mobile']; ?>' , '<?php echo $dist_mobileNo; ?>' )">Change Number</a></td>
    			      </tr>
    			  	<?php $i++; } } ?> 					    			      
			         </tbody>
			         <tfoot>
			         	<tr>
			         		<td></td><td></td><td></td><td class="number"><?php echo round($totBal,2); ?></td><td class="number"><?php echo $totTran; ?></td><td class="number"><?php echo round($totSale,2); ?></td><td class="number"><?php echo round($totAvg,2); ?></td>
			         	</tr>
			         </tfoot>         
			   	</table>
			</fieldset>
