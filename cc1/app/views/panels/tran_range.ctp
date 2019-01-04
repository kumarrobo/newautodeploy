<?php if($page != "csv"){?>


   <link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>

<script>
$(document).ready(function() {
 $("select#vendorDD").multipleSelect({ selectAll: false, width: 290, multipleWidth: 120, multiple: true});
    $("select#opDD").multipleSelect({ selectAll: false, width: 290, multipleWidth: 120, multiple: true});
});    
    
function setAction(){
        
        var page = $("#pageDD").val();
	var transType = $("#transType").val();
        var limitPerPage = $("#limitPerPage").val();
	var from = $("#from").val();
        var from_time = $('#frm_time_hrs').val()+'.'+$('#frm_time_mins').val();
        var to = $("#to").val();
        var to_time = $('#to_time_hrs').val()+'.'+$('#to_time_mins').val();
        var vendorIds = $("select#vendorDD").multipleSelect("getSelects");
	var productIds = $("select#opDD").multipleSelect("getSelects");
        
        if (0 >= vendorIds.length) {
            vendorIds = 0;
        }
        if (0 >= productIds.length) {
            productIds = 0;
        }
        
        document.tranRange.action="/panels/tranRange/"+from+"/"+to+"/"+vendorIds+"/"+productIds+"/"+page+"/"+transType+"/"+limitPerPage+"/"+from_time+"/"+to_time;
	document.tranRange.submit();
	
}


</script>
<?php
$f_time = explode('.',$f_time);
$t_time = explode('.',$t_time);
?>
<form name="tranRange" method="POST">
From : 
<input type="text" name="from" id="from" onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php echo !is_null($frm) ? $frm : date('d-m-Y'); ?>" style="width: 100px;" />
<select name="frm_time_hrs" id="frm_time_hrs">
        <?php for($i=0;$i<24;$i++) { ?>
        <option <?php if($i == $f_time[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
        <?php } ?>
</select> :
<select name="frm_time_mins" id="frm_time_mins">
        <?php for($i=0;$i<60;$i++) { ?>
        <option <?php if($i == $f_time[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
        <?php } ?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;To : 
<input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" value="<?php echo !is_null($to) ? $to : date('d-m-Y'); ?>" style="width: 100px;" />
<select name="to_time_hrs" id="to_time_hrs">
        <?php for($i=0;$i<24;$i++) { ?>
        <option <?php if($i == $t_time[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
        <?php } ?>
</select> :
<select name="to_time_mins" id="to_time_mins">
        <?php for($i=0;$i<60;$i++) { ?>
        <option <?php if($i == $t_time[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ; ?></option>
        <?php } ?>
</select>&nbsp;&nbsp;
<br><br>
Vendors: 



            <select id="vendorDD" name="vendorDD">
                    <option value="0">All</option>
                    <?php foreach($vendorDDResult as $v): ?>
                            <option value="<?php echo $v['vendors']['id'] ?>" <?php if(in_array($v['vendors']['id'], $vendorIds)) echo "selected" ?> >
                                    <?php echo $v['vendors']['company'] ?>
                            </option>
                    <?php endforeach ?>
            </select>

Operator: 


            <select id="opDD" name="opDD">
                    <option value="0">All</option>
                    <?php 
                        foreach($opResult as $key=>$serviceDet)
			{
                          echo "<optgroup label='".($serviceDet["service_name"])."'>";
                          foreach($serviceDet["products"] as $p){ 
                            
                            ?>
                            <option value="<?php echo $p['id'] ?>" <?php if(in_array($p['id'], $operatorIds)) echo "selected" ?> >
                                    <?php echo $p['name'] ?>
                            </option>
                        <?php } }?>
            </select>

Page: 

<select name="pageDD" id="pageDD"  >
	<?php
			
			for($i=1;$i<=50;$i++)
			{
				$sel='';
				if($page==$i)
					$sel='selected';
			
				echo "<option ".$sel." value=$i >".$i."</option>";
			}
			
	?>
	
</select>
Type:<select name="transType" id="transType"  >
    <option value="all"  <?php echo ( $transType == "selected" ? "" : "" ) ;?> >All</option>
    <option value="success"  <?php echo ( $transType == "success" ? "selected" : "" ) ;?> >Success</option>
    <option  value="reverse"  <?php echo ( $transType == "reverse" ? "selected" : "" ) ;?> >Reverse</option>    
</select>
Trans Per Page:<select name="limitPerPage" id="limitPerPage"  >
    <option value="1000"  <?php echo ( $limitPerPage == "1000" ? "selected" : "" ) ;?> >1000</option>
    <option value="2000"  <?php echo ( $limitPerPage == "2000" ? "selected" : "" ) ;?> >2000</option>
    <option value="5000"  <?php echo ( $limitPerPage == "5000" ? "selected" : "" ) ;?> >5000</option>
    <option value="10000" <?php echo ( $limitPerPage == "10000" ? "selected" : "" ) ;?>>10000</option>   
</select> 

<br><br>
<input type="button" value="Submit" onclick="setAction();">
</form>
Transaction Report<a href="?res_type=csv" alt='transactionreport' title="transactionreport"><img id="export_csv" type="button" alt="xp" class="export_csv" src="/img/csv1.jpg" style="height:25px" /></a>


<table>
<tr>
<td valign="top" width="50%">
<h3>Successful Transactions</h3>
<table border="1" cellpadding="2" cellspacing="0" width="100%">

		<tr valign="top"> 
			<th>Row</th>
			<th>Tran Id</th>
                        <th>VendorTxn Id</th>
			<th>Ret Id / Retailer Name / shop</th>
			<th>Vendor</th>
		<!--	<th>Retailer Mobile</th> -->
  			<th>Cust Mob</th>
  		<!--	<th>Operator</th> -->
  			<th>Amt</th>
  			<th>Status</th> 
  			<th>Date</th>
  			<th>Updated Time</th>
  		</tr>
  		
  	
  		<?php 
  		$i=1;
  			
  		foreach($success as $d){
  		
  		if($d['va']['status'] == '2' || $d['va']['status'] == '3')
  		continue;
  		
  		
  		if(strcmp($d['r']['name'],'')!=0){
  		$retailerLink=$d['r']['name'];
  		}
  		else
  		{
  		$retailerLink=$d['r']['mobile'];
  		}
  		
  		
  		echo "<tr valign='top'>";
  		echo "<td>".$i."</td>";
  		echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."' >".$d['va']['txn_id']."</a></td>";
                echo "<td>".$d['va']['vendor_refid']."</td>";
  		echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."' >".$d['r']['id']." / ".$retailerLink." / ".$d['r']['shopname']."</a></td>";
  		// echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."' >".$d['r']['name']."</a></td>";
  		echo "<td>".$d['v']['shortForm']."</td>";
  		echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."' >".$d['va']['mobile']."</a></br>(".$d['p']['name'].")</td>";
  		//echo "<td>".$d['p']['name']."</td>";
  		echo "<td>".$d['va']['amount']."</td>";
  		
  		$ps = '';
  		if($d['va']['status'] == '0'){
			$ps = 'In Process';
		}else if($d['va']['status'] == '1'){
			$ps = 'Successful';
		}else if($d['va']['status'] == '2'){
			$ps = 'Failed';
		}else if($d['va']['status'] == '3'){
			$ps = 'Reversed';
		}else if($d['va']['status'] == '4'){
			$ps = 'Reversal In Process';
		}else if($d['va']['status'] == '5'){
			$ps = 'Reversal declined';
		}   
  		echo "<td>".$ps."</td>";   		
  		echo "<td>".$d['va']['timestamp']."</td>";
                echo "<td>".($d['va']['updated_time'] != '' ? $d['va']['updated_time'] : '<center>-</center>')."</td>";
  		/*if($d['va']['status']=='0' || $d['va']['status']== '4'){
  			echo "<td><a href=''>Accept</a></br></br><a href=''>Decline</a></td>";
  		}else{
  			echo "<td>FAILURE</td>";
  		}*/
  		$i++;	
  		echo "</tr>";
  		}
  		// 	echo "Total  successful  transaction :".($i-1)."</br></br>";
  		//echo "Sales result array :".$salesResultArray;
 ?> 
</table>
</td>


<td valign="top" width="50%">
<h3>Failed Transactions</h3>
<table border="1" cellpadding="2" cellspacing="0" width="100%">

		<tr valign="top"> 
			<th>Row</th>
			<th>Tran Id</th>
			<th>Ret Id / Retailer Name / shop</th>
			<th>Vendor</th>
		<!--	<th>Retailer Mobile</th> -->
  			<th>Cust Mob</th>
  			<th>Amt</th>
  		    <!-- <th>Operator</th> -->
  			<th>Status</th> 
  			<th>Date</th>
                        <th>Updated Time</th>
  		</tr>		
<?php  		
		$i = 1;
  		foreach($success as $d){
  		if($d['va']['status'] != '2' && $d['va']['status'] != '3')
  		continue;
  	
  		
  		if(strcmp($d['r']['name'],'')!=0){
  		$retailerLink=$d['r']['name'];
  		}
  		else{
  		$retailerLink=$d['r']['mobile'];
  		}
  		
  		
  		echo "<tr valign='top'>";
  		echo "<td>".$i."</td>";
  		echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."' >".$d['va']['txn_id']."</a></td>";
  		
     echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."'>".$d['r']['id']." / ".$retailerLink." / ".$d['r']['shopname']."</td>";
  		// echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."' >".$d['r']['mobile']."</a></td>";
  		echo "<td>".$d['v']['shortForm']."</td>";
  		echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."' >".$d['va']['mobile']."</a></br>(".$d['p']['name'].")</td>";
  		echo "<td>".$d['va']['amount']."</td>";
  		//echo "<td>".$d['p']['name']."</td>";
  		$ps = '';
  		if($d['va']['status'] == '0'){
			$ps = 'In Process';
		}else if($d['va']['status'] == '1'){
			$ps = 'Successful';
		}else if($d['va']['status'] == '2'){
			$ps = 'Failed';
		}else if($d['va']['status'] == '3'){
			$ps = 'Reversed';
		}else if($d['va']['status'] == '4'){
			$ps = 'Reversal In Process';
		}else if($d['va']['status'] == '5'){
			$ps = 'Reversal declined';
		}   
  		echo "<td>".$ps."</td>";   		
  		echo "<td>".$d['va']['timestamp']."</td>";
                echo "<td>".($d['va']['updated_time'] != '' ? $d['va']['updated_time'] : '<center>-</center>')."</td>";
  		/*if($d['va']['status']=='0' || $d['va']['status']== '4'){
  			echo "<td><a href=''>Accept</a></br></br><a href=''>Decline</a></td>";
  		}else{
  			echo "<td>FAILURE</td>";
  		}*/
  		$i++;	
  		echo "</tr>";
  		}
  			// echo "Total  failed transaction :".($i-1)."</br></br>";
 ?> 
</table>
</td>
</tr>
</table>

<?php }
else{


} ?>