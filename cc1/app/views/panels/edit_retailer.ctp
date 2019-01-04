<script>
function editRetailer1()
{

var mobile=$('rMobile').value;
var email=$('rEmail').value;
var name=$('rName').value;
var shopName=$('rShopName').value;
var address=$('rAddress').value;
var pin=$('rPin').value;
var area=$('rArea').value;
var rId = $('rId').value;

var url='/panels/editRetailer1';
	
		var pars   = "mobile="+mobile+"&email="+email+"&name="+name+"&shopName="+shopName+"&address="+address+"&pin="+pin
		+"&area="+area+"&rId="+rId;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{ 	
						var html = transport.responseText;
										
					}
				});

}

</script>
<form action="/panels/editRetailer/<?php echo $retMobile; ?>/1" method="post">
<table border="0" cellpadding="0" cellspacing="0"  width="100%" align="center">
     
	<tr>
		<td valign="top" width="50%">
			<div id="retailerEditInformation" style="display:block">
			<h3>Edit Retailer Information</h3>
			<table id="editRetailerInformation" border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<tr>
				<td>Retailer Id</td><td><?php echo $info[0]['retailers']['id']; ?></td>
			</tr>
			<tr>
				<td>Retailer Name</td><td><input type="text" id="rName" name="retailerName" value="<?php echo $info[0]['retailers']['name']; ?>" /></td>
			</tr>
			<tr>
				<td>User Id</td><td><?php echo $info[0]['retailers']['user_id']; ?></td>
			</tr>
			<tr>
				<td>Balance</td><td><?php echo $info[0]['retailers']['balance']; ?></td>
			</tr>
			<!--<tr>
				<td>Distributor</td><?php echo $info[0]['distributors']['company']; ?></td>
			</tr>-->
			<tr>
				<td>Mobile</td><td><?php echo $info[0]['retailers']['mobile']; ?></td>
			</tr>
			<tr>
				<td>Alternate Mobile</td><td><input type="text" id="alternate" name="alternate" value="<?php echo $info[0]['retailers']['alternate_number']; ?>" /></td>
			</tr>
			<tr>
				<td>Email</td><td><input type="text" id="rEmail" name="retailerEmail" value="<?php echo $info[0]['retailers']['email']; ?>" /></td>
			</tr>
			<tr>
				<td>Shopname</td><td><input type="text" id="rShopName" name="retailerShopName" disabled="disabled" value="<?php echo $info[0]['retailers']['shopname']; ?>" /></td>
			</tr>
			<tr>
	            <td>Shop Type</td>
	            <td>
	               <select id="shopTypeSelect" name="retailerShopType" >
	                <?php
	                foreach($shop_types as $value => $label) {
	                    $selected = '';
	                    if($value==$info[0]['retailers']['shop_type']){
	                        $selected = 'selected';
	                     }
	                    echo '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';                                        
	                }
	                ?>
	              </select>
	            </td>
            </tr>
			<!-- turnover -->
			<tr>
	            <td>Annual Turnover</td>
	            <td>
	               <select id="annualTurnoverSelect" name="retailerAnnualTurnover" >
	                <?php
	                foreach($turnover_types as $value => $label) {
	                    $selected = '';
	                    if($value==$info[0]['retailers']['annual_turnover']){
	                        $selected = 'selected';
	                     }
	                    echo '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';                                        
	                }
	                ?>
	              </select>
	            </td>
            </tr>
			
			<!-- -->
			
			<!-- shop area type -->
			<tr>
	            <td>Shop Area Type</td>
	            <td>
	               <select id="shopAreaType" name="retailerShopAreaType" >
	                <?php
	                foreach($shop_area_types as $value => $label) {
	                    $selected = '';
	                    if($value==$info[0]['retailers']['shop_area_type']){
	                        $selected = 'selected';
	                     }
	                    echo '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';                                        
	                }
	                ?>
	              </select>
	            </td>
            </tr>
			
			<!-- -->
			<!-- shop ownership type -->
			<tr>
	            <td>Shop Ownership</td>
	            <td>
	               <select id="shopOwnershipType" name="retailerShopOwnership" >
	                <?php
	                foreach($shop_ownership_types as $value => $label) {
	                    $selected = '';
	                    if($value==$info[0]['retailers']['shop_ownership']){
	                        $selected = 'selected';
	                     }
	                    echo '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';                                        
	                }
	                ?>
	              </select>
	            </td>
            </tr>
			
			<!-- -->
			
			<tr>
				<td>Area</td><td><input type="text" id="rArea" name="retailerArea" disabled="disabled" value="<?php echo $info[0]['retailers']['area']; ?>" /></td>
			</tr>
			<tr>
				<td>Address</td><td><input type="text" id="rAddress" name="retailerAddress" disabled="disabled" value="<?php echo $info[0]['retailers']['address']; ?>" /></td>
			</tr>
			<tr>
				<td>Pin</td><td><input type="text" id="rPin" name="retailerPin" disabled="disabled" value="<?php echo $info[0]['retailers']['pin']; ?>" /></td>
			</tr>
			<tr>
				<td>Created On</td><td><?php echo $info[0]['retailers']['created']; ?></td>
			</tr>
                        <tr>
				<td>Status</td>
                                <td>
                                   
                   <select id="retailerBlockFlag" name="retailerBlockFlag" >
			
				<option <?php echo ($info[0]['retailers']['block_flag'] == '0' ? "selected" : ""); ?> value="0">Active</option>
				<option <?php echo ($info[0]['retailers']['block_flag'] == '1' ? "selected" : ""); ?> value="1">Partially Blocked</option>
				<option <?php echo ($info[0]['retailers']['block_flag'] == '2' ? "selected" : ""); ?> value="2">Fully Blocked</option>
			
		</select>

                                </td>
			</tr>

		</table>
		</td>
		</tr>
		</table>	
	<input type="submit" value="Save" /></td>	
 </form>