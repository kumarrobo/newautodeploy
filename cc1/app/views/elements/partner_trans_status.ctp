<?php //if(isset($tran)){ ?>
<font size="5" style="color:#ff0000"><strong><?php //echo $partnerTransaction[0]['services']['name']; ?></strong></font></br></br>
<table  id="userTransactions" border="1" cellpadding="2" cellspacing="0" width="100%">
		<tr valign="top"> 
			<th width="100px">Partner Name</th>
			 
  			<!-- <th>Product</th> -->
  			<!-- <th>Provider / ref_id</th> -->
  			<th>Operator Id</th>
  			<th>Customer </br>Number</th>
  			<th>Operator</th>
  			<th>Amount</th>
  			<th>Cause</th>
  			
  		<!--	<th>Internal Status</th>
  			<th>Provider Response</th> -->
  			<th>Current Status</th>
  			<th>Time</th>
   			<th width="200px">Action</th>
   		<!--	<th>Reason for reversal</th> -->
   		
   			
  		</tr>		
               <tbody>
                <?php  		 
                
  		foreach($partnerLog as $d){//id 	partner_req_id 	partner_id 	vendor_actv_id 	err_code 	description 	created
  		
  		echo "<tr>";
  		echo "<td><a href='/panels/retInfo/".$d['p']['acc_id']."'>".$d['r']['name']." </a> / ".$d['p']['acc_id']." </td>";
  		//echo "<td>".$d['r']['shopname']."</td>";
  		//echo "<td>".$d['services']['name']."</td>";
  		
	  	//echo "<td> <a target='_blank' href='#'>Status</a> <br />".$d['vendors']['company']."<br/>/".$d['va']['vendor_refid']."</td>";
  		echo "<td align='center'> -- </td>";// for operator id
                echo "<td align='center'>".$d['pl']['mob_dth_no']."</td>"; // for partner mobile no
  		
                $opr = $objShop->smsProdCodes($d['pl']['product_id']);
  		echo "<td align='center'>".$opr['method']."</td>";//operator name
  		
  		echo "<td align='center'>".$d['pl']['amount']."</td>";// for amount
  		
  		echo "<td>".$d['pl']['description']."</td>";
  		
		if($d['pl']['err_code'] == 0) echo "<td> Successful </td>";
		else echo "<td> Failed (".$d['pl']['err_code'].") </td>";
                
                $dt = Date("d-M-Y H:i:s",  strtotime($d['pl']['created']));
                
                echo "<td>".$dt."</td>";
  		echo "<td align='center'> -- </td>";//action
                echo "</tr>";
                } ?>
                   </tbody>
</table>

<?php //} ?>