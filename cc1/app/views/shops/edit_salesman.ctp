<script>
function removeSubArea(sa,saName)
{
	
	var sel=sa;
	var smId=$('smId').value;
	//alert(smId);
	var cfm=confirm("Are you sure you want to delete subarea "+saName+" ?");
	if(cfm===false)
		return;
	else	{
			 var url = '/shops/deleteSubarea/'+sel;
			 var pars   = "subAreaId="+sel+"&smId="+smId;
			 var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
							onSuccess:function(transport)
							{
								var html = transport.responseText;
								$('subareaauto').value='';
								window.location.reload();
							}
						});
		
			}
}

function saveEditSm()
{
		 var subareaList=$('subareaOptions').innerHTML;
		 var smId=$('smId').value;
		 var smName=$('smName').value;
                 <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
		 var smLimit=$('smTranLimit').value;
                 <?php } ?>
		 var smMobile=$('smMobile').value;
		 
		 var url = '/shops/saveEditSm';
			 var pars   = "subAreaList="+subareaList+"&smId="+smId+"&smName="+smName+"&smMobile="+smMobile;
                 <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
                         pars = pars+"&smLimit="+smLimit;
                 <?php } ?>
			 var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
							onSuccess:function(transport)
							{
								var html = transport.responseText;
								if(html==1)
								alert("Salesman details are saved");
								else if(html==0)
								alert("New salesman mobile already exits.");
								$('subareaOptions').value='';
								window.location.reload();
							}
						});
		
}
</script>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'salesmanList'));?>
    		<div id="innerDiv">
    			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:20px">
					<div class="appTitle">Edit Salesman</div>
					<div>
					  <div class="field">
					    <div class="fieldDetail leftFloat" style="width:350px;" >
					      <div class="fieldLabel1 leftFloat">
					        <label for="smMobile">Mobile</label>
					      </div>
					      <div class="fieldLabelSpace1 strng"> <input type="text" id="smMobile" value="<?php echo $smR['0']['sm']['mobile'];?>"/> </div>
					      <input type="hidden" id="smId" value=<?php echo $smR['0']['sm']['id'];?>>
					    </div>
					    <div class="fieldDetail">
					      <div class="fieldLabel1 leftFloat">
					        <label for="smName">Name</label>
					      </div>
					      <div class="fieldLabelSpace1 strng">
					        <input type="text" id="smName" value="<?php echo $smR['0']['sm']['name'];?>"/>
					        &nbsp; </div>
					    </div>
					  </div>
					  <div class="field">
                                              <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
					    <div class="fieldDetail leftFloat" style="width:350px;">
					      <div class="fieldLabel1 leftFloat">
					        <label for="smTranLimit">Transaction Limit</label>
					      </div>
					      <div class="fieldLabelSpace1 strng">
					      	<input type="text" id="smTranLimit" value="<?php echo $smR['0']['sm']['tran_limit'];?>"/>
					      </div>
					    </div>
                                              <?php } ?>
					    <div class="fieldDetail">
					      <div class="fieldLabel1 leftFloat">
					        <label for="smBalance">Balance</label>
					      </div>
					      <div class="fieldLabelSpace1 strng">
								<?php echo $smR['0']['sm']['balance'];?>
					      </div>
					    </div>
					  </div>
					  <div class="field">
					    <div class="fieldDetail leftFloat" style="width:350px;">
					      <div class="fieldLabel1 leftFloat">
					        <label for="newSubArea">New Subarea</label>
					      </div>
					      <div class="fieldLabelSpace1 strng"> 
					          <input type="text" value="" autocomplete="off" name="data[Salesman][subarea]" id="subareaauto" tabindex="5" style="width:150px">
					          <div class="autoComplete position2" id="AppStockFor_autoComplete" style="display: none;"></div>
					          <script> new Ajax.Autocompleter("subareaauto", "AppStockFor_autoComplete", "/shops/autoCompleteSubarea", {paramName: "data[Salesmen][subarea]", 
													  	minChars: 3,  afterUpdateElement : changeSubarea
													  });</script>
					          <br>
					          <span class="hints">Start with the first 3 chars of subarea name</span>
					          <input type="text" id="subareaOptions" name="data[subArea1]" style="margin-top:10px"/>
					       
					      </div>
					    </div>
					    <div class="fieldDetail">
					      <div class="fieldLabel1 leftFloat">
					        <label for="existingSubareas">Existing Subarea</label>
					      </div>
					      <div class="fieldLabelSpace1 strng">
							<?php 
					            foreach($existingSA as $sa)
					            {
					                echo "&nbsp;";
					                echo "<input type='button'  value='".$sa['sub']['name']." | &nbsp;X' onClick='removeSubArea(".$sa['sub']['id'].",\"".$sa['sub']['name']."\")'/>";
					            }
					        ?>
					      </div>
					    </div>
					  </div>
					  <div class="field">
					    <div class="fieldDetail" style="width:350px;">
					      <div class="fieldLabel1 leftFloat">&nbsp;</div>
					      <div class="fieldLabelSpace1" id="sub_butt">
					        <input type="button" class="retailBut enabledBut" value="SAVE" onClick="saveEditSm()"/>
					      </div>
					    </div>
					  </div>
					</div>
				</fieldset>
			</div>
			<br class="clearLeft" />
		</div>
    </div>
    <br class="clearRight" />
 </div>		
<!--

<table>
<tr>
<td>Salesman Mobile</td>
<td><?php echo $smR['0']['sm']['mobile']; ?></td>
<td><input type="hidden" id="smId" value="<?php echo $smR['0']['sm']['id']; ?>"/></td>
</tr>

<tr>
<td>Salesman Name</td>
<td><input type="text" id="smName" value="<?php echo $smR['0']['sm']['name']; ?>"/></td>
</tr>

<tr>
<td>Salesman Balance</td>
<td><input type="text" id="smBalance" value="<?php echo $smR['0']['sm']['balance']; ?>"</td>
</tr>

<tr>
<td>Existing Salesman Subarea</td>

	<?php 
	foreach($existingSA as $sa)
		{
			echo "<td>";
			echo "<input type='button'  value='".$sa['sub']['name']."' onClick='removeSubArea(".$sa['sub']['id'].")'/>";
			echo "</td>";
		}
	?>

</tr>

<tr>
			<td>Subarea Name:</td>
            <td>
             <div class="fieldLabelSpace2">
             	<input type="text" value="" autocomplete="off" name="data[Salesman][subarea]" id="subareaauto" tabindex="5" style="width:120px">
             	<div class="autoComplete position2" id="AppStockFor_autoComplete" style="display: none;"></div>
			        <script> new Ajax.Autocompleter("subareaauto", "AppStockFor_autoComplete", "/shops/autoCompleteSubarea", {paramName: "data[Salesmen][subarea]", 
			  minChars: 3,  afterUpdateElement : changeSubarea
			  });</script>
                <br>
                <span class="hints">Start with the first 3 chars of subarea name</span>
                <input type="text" id="subareaOptions" name="data[subArea1]" style="margin-top:10px"/>
             </div>     
		</td>		
</tr>




<tr>
	<td><input type="button" value="SAVE" onClick="saveEditSm()"/></td>
</tr>

</table>

-->
