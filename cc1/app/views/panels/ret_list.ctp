<script>



function changeDistributor(rid,obj)
{
	var distId=obj.options[obj.selectedIndex].value;
		
	var r=confirm("Please check if retailer pending is cleared from last distributor. If everything is ok, go ahead");
	if(r==true){
		var url = '/panels/changeDistributor';
		var params = {'rid' : rid,'sid':distId};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					alert('done');
				}
		});
	}
}








function findRet(flag,obj)
{
	if(flag==1){
		var distId=obj.options[obj.selectedIndex].value;
		var url="/panels/retList/"+distId;
	}
	else {
		var salesManId=obj.options[obj.selectedIndex].value;
		var distId=$('distributor').options[$('distributor').selectedIndex].value;
		var url="/panels/retList/"+distId+"/"+salesManId;
	}
	window.location.href = url;
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

jQ = jQuery.noConflict();

function filterVerificationStatus(){
	var dist_id = $("distributor").value;
	var verify_flag = $("verify_flag").value;

	window.location.href = "/panels/retList/" + dist_id + "?verify_flag=" + verify_flag;
}
</script>


    <form name="retailerCollection" id="retailerCollection" action ="/palenls/retList/" method="post">
    <input type='hidden' name ="retailer_id" id ="retailer_id">
    <input type='hidden' name ="mobile" id ="mobile">
    <input type='hidden' name ="shop" id ="shop">
    <input type='hidden' name ="address" id ="address">
    <input type='hidden' name ="area" id ="area">
    <input type='hidden' name ="city" id ="city">
    <input type='hidden' name ="state" id ="state">
    <input type='hidden' name ="latitude" id ="latitude">
    <input type='hidden' name ="longitude" id ="longitude">
    <input type='hidden' name ="pincode" id ="pincode">
    <input type='hidden' name ="dist_mobile" id ="dist_mobile">
Distributor : <select name="distributor" id="distributor" onChange="findRet(1,this)">
	<?php
		echo "<option selected value=''>All</option>";
		foreach($distList as $d)
					{										
						$sel = '';
						if($distId == $d['distributors']['id'])
						{
							$sel = 'selected';							
						}
						
				 		echo "<option ".$sel." value='".$d['distributors']['id']."' >".$d['distributors']['company']."-".$d['users']['mobile']."</option>";
					}
	?>
					</select>
					
Verification Status: <select name="verify_flag" id="verify_flag" onChange="filterVerificationStatus();">
						<option value="" <?php if(!in_array($verify_flag, array(0, 1, 2))) echo "selected" ?> >All</option>
						<option value="0" <?php if($verify_flag === "0") echo "selected" ?> >Unverified</option>
						<option value="1" <?php if($verify_flag == 1) echo "selected" ?> >Verified</option>
						<option value="2" <?php if($verify_flag == 2) echo "selected" ?> >Documents submitted</option>
					</select>
 
<!-- Acquisition Salesman : <select name="acq_salesman_retailer" id="acq_salesman_retailer" onChange="findRet('2',this)">
	<?php
		/*echo "<option value='0'>None</option>";
		foreach($salesmenList as $d)
		{
			$sel = '';
			if($sid == $d['salesmen']['id'] && $flag == 2)
			$sel = 'selected';			
	 		echo "<option ".$sel." value='".$d['salesmen']['id']."' >".$d['salesmen']['name']."-".$d['salesmen']['mobile']."-".$d['salesmen']['id']."</option>";
		}*/
	?>	
						</select> -->
</form>

<br/><br/>
<table border="1" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>Index</td><td>Retailer Mobile</td><td>Shop Name</td><td>Sale</td><td>Address</td><td>Area</td><td>City</td><td>State</td><td>Latitude</td><td>Longitude</td><td>Pin</td><td>Device Type</td><td>Action</td><td></td>
	</tr>
	<?php
              
                
		$i = 1;
         $str = '';
		foreach($retList as $at){
                 if($at[1]['retailers']['verify_flag']=='0'){
                  $str = "<a href ='/panels/retList/".$distId."/".$at[1]['retailers']['id']."'>Verify</a>";
                 }
                 else {
                         $str = 'Verified';
                       }
				$getData = "retailer_id=".$at[1]['retailers']['id']."&mobile=".$at[1]['retailers']['mobile']."&shop=".urlencode($at[1]['retailers']['shopname'])."&address=".urlencode($at[1]['retailers']['address'])."&area=".urlencode($at[1]['retailers']['areaname'])."&city=".urlencode($at[1]['retailers']['cityname'])."&state=".urlencode($at[1]['retailers']['statename'])."&latitude=".urlencode($at[1]['retailers']['latitude'])."&longitude=".urlencode($at[1]['retailers']['longitude'])."&pincode=".urlencode($at[1]['retailers']['pin'])."&dist_mobile=".$distMobileNumber[$distId];
				
      			echo "<tr><td>".$i."</td><td><a  href='/panels/retInfo/".$at[1]['retailers']['mobile']."'>".$at[1]['retailers']['mobile']."</a>&nbsp;</td><td><a href='/panels/retInfo/".$at[1]['retailers']['mobile']."'>".$at[1]['retailers']['shopname']."</a>&nbsp;</td><td>".intval($at[0]).".00"."</td><td>".$at[1]['retailers']['address']."</td><td>".$at[1]['retailers']['areaname']."</td><td>".$at[1]['retailers']['cityname']."</td><td>".$at[1]['retailers']['statename']."</td><td>".$at[1]['retailers']['latitude']."</td><td>".$at[1]['retailers']['longitude']."</td><td>".$at[1]['retailers']['pin']."</td><td>".$at[1]['retailers']['device_type']."</td><td><a href='/shops/retailer/?".$getData."' target=\"_blank\">Edit</a></td><td>".$str."</td>";
			echo "</tr>";
			$i++;
		}
		//echo "<tr><td colspan='5' align='right'><b>Total</b>&nbsp;</td><td><b>".$setupColl."</b></td><td><b>".$amtBal."</b></td><td><b>".$amtTran."</b></td><td><b>".round($avg1,2)."</b></td><td><b>".round($avg2,2)."</b></td></tr>";
	?>
</table>