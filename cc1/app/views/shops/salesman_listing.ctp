<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'salesmanList'));?>
    		<div id="innerDiv">
    			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:20px">
				<div class="appTitle">Salesmen List</div>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Retailers">
				<thead>
			          <tr class="noAltRow altRow">
						<th width="15%">Name</th>
						<th width="10%">Mobile</th>
						<th width="25%">Subareas</th>
						<th width="15%">Topup Balance</th>
                                                <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
						<th width="10%">Total Pending Topup</th>
						<!--<th width="10%">Total Pending Setup</th>-->
						<th width="10%">Last Day Pending Topup</th>
                                                <?php } ?>
						<th width="5%">EDIT</th>
						<th width="5%">Action</th>
					  </tr>
				</thead>
                <tbody>	  	
				<?php if(isset($salesman))
					{$tot_top = 0; 	$tot_top_last =0; $tot_set =0;
					foreach($salesman as $sr)
						{
						$tot_top += $sr['sm']['tran_limit'] - $sr['sm']['balance'];
						if(isset($topups[$sr['sm']['id']]))$tot_top_last += $sr['sm']['tran_limit'] - $sr['sm']['balance'] - $topups[$sr['sm']['id']];
						else $tot_top_last += $sr['sm']['tran_limit'] - $sr['sm']['balance'];
						
						$tot_set += $sr['sm']['setup_pending'];
						
						echo "<tr>";
						echo "<td>".$sr['sm']['name']."</td>";
						echo "<td>".$sr['sm']['mobile']."</td>";
						
						echo "<td>".$sr['0']['subs']."</td>";
                                                if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) {
						echo "<td>".$sr['sm']['balance']." (".$sr['sm']['tran_limit'].")</td>";
                                                } else {
                                                        echo "<td>".$sr['sm']['balance']."</td>";
                                                }
                                                if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) {
						echo "<td>".($sr['sm']['tran_limit'] - $sr['sm']['balance'])."</td>";
						//echo "<td>".$sr['sm']['setup_pending']."</td>";
						if(!isset($topups[$sr['sm']['id']])){
							$topups[$sr['sm']['id']] = 0;
						}
						echo "<td>".($sr['sm']['tran_limit'] - $sr['sm']['balance'] - $topups[$sr['sm']['id']])."</td>";
                                                }
						echo "<td><a href='/shops/editSalesman/".$sr['sm']['mobile']."'>Edit</a></td>"; ?>
						<td><select name="block_salesmanDD" id="block_salesmanDD" onChange="saleEnable(<?php echo $sr['sm']['id']; ?>,this)">
							<?php if($sr['sm']['block_flag'] == '0'){ ?>
								<option value="0" selected>None</option>
								<option value="1">Block</option>
							<?php } else { ?>
								<option value="0">None</option>
								<option value="1" selected>Block</option>
							<?php } ?>
						</select>
					<?php		
						echo "</tr></td>";
						}
						
						echo "<tr>";
						echo "<td><b>Total</b></td>";
						echo "<td></td>";
						
						echo "<td></td>";
						echo "<td></td>";
                                                if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) {
						echo "<td><b>".$tot_top."</b></td>";
						//echo "<td><b>".$tot_set."</b></td>";
						echo "<td><b>".$tot_top_last."</b></td>";
                                                }
						echo "<td></td>";
						echo "<td></td>";
						echo "</tr>";
						
					}
				?>
				</tbody>
				</table>
				</fieldset>
			</div>
			<br class="clearLeft" />	
    	</div>
    </div>
    <br class="clearRight" />
 </div>   	
<script>
function findSalesman()
{
var sel=$('salesman').value;
var url = '/shops/salesmanListing/'+sel;
var pars='id='&sel;	
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{ 	
					alert(hi);
					var html = transport.responseText;
					 		
				}
			});
  


}

function saleEnable(rid,obj)
{
	var flag=obj.options[obj.selectedIndex].value;
	var r=confirm("You sure?");
	if(r==true){
		var url = '/salesmen/blockSalesman';
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
</script>