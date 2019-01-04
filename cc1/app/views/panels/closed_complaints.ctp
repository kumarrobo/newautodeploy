<h1>Closed Complaints</h1>

<table border="1" cellpadding="0" cellspacing="0" style="text-align:center" >
					<tr> 
						<th>Index</th>
						<th>Tran Id</th>
                                                <th>VTransID</th>
                                                <th>Vendor</th>
						<!--	<th>Retailer Mobile</th> -->
	  					<th>Cust Mob</th>
	  					<th>Operator</th>
	  					<th>Amt</th>
                                                <th>Status</th> 
	  					<th>Transaction Time</th>
                                                <th>Closed by</th>
	  					<th>Complaint Time</th>
	  					<th>Complaint Close Time</th>
	  					<th>Difference</th>
	  					<th>Time Delay</th>
	  					<th>Complaint tag</th>
	  					<th>Resolution tag</th>
                                                
	  				</tr>
	  		
	  		<?php 
	  		$i=1;
            if(count($closed)>0){
 	  		foreach($closed as $d){
 	  		
 	  		if(isset($d['r'])){
		  		if(strcmp($d['r']['name'],'')!=0){
		  		$retailerLink=$d['r']['name'];
		  		}
		  		else{
		  		$retailerLink=$d['r']['mobile'];
		  		}
	  		}
	  		
	  		if(!empty($d['complaints']['takenby']))$color = '#99ff99';
	  		else $color = '';
	  		
	  		echo "<tr bgcolor='$color'>";
	  		echo "<td>".$i."</td>";
	  		echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."' >".$d['va']['txn_id']."</a></td>";
	  		echo "<td>".$d['va']['vendor_refid']."</td>";
                        echo "<td>".$d['v']['shortForm']."</td>";
                        echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."' >".$d['va']['mobile']."</a></td>";
	  		echo "<td>".$d['p']['name']."</td>";
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
                                $blank = 'Auto Reversed';
			}else if($d['va']['status'] == '4'){
				$ps = 'Complaint taken';
			}else if($d['va']['status'] == '5'){
				$ps = 'Complaint declined';
                                $blank = 'Auto Declined';
			}   
	  		echo "<td>".$ps."</td>";
	  		echo "<td>".$d['va']['timestamp']."</td>";
	  		echo "<td>".($d['users']['name'] != '' ? $d['users']['name'] : 'System')."</td>";
	  		echo "<td>".$d['complaints']['in_date']." ".$d['complaints']['in_time']."</td>";
	  		echo "<td>".$d['complaints']['resolve_date']." ".$d['complaints']['resolve_time']."</td>";
                        $diff = strtotime($d['complaints']['resolve_date']." ".$d['complaints']['resolve_time'])-strtotime($d['complaints']['in_date']." ".$d['complaints']['in_time']);
	  		echo "<td>".floor($diff/3600)." hrs, ".floor(($diff/60)%60)." mins, ".($diff%60)." secs"."</td>";
	  		$time_delay = date_diff(new DateTime($d['c']['created']), new DateTime($d['complaints']['turnaround_time']));
	  		$days = $time_delay->format('%d') ? $time_delay->format('%d')." day " : "";
	  		$hrs = $time_delay->format('%h') ? $time_delay->format('%h')." hrs " : "";
	  		$mins = $time_delay->format('%i') ? $time_delay->format('%i')." mins " : "";
	  		$secs = $time_delay->format('%s') ? $time_delay->format('%s')." secs " : "";
	  		$delay = $days.$hrs.$mins.$secs;
	  		if(!$time_delay->invert)
	  			echo "<td bgcolor='#98fb98'>".$delay." in advance</td>";
	  		else 	
	  			echo "<td bgcolor='#f0e0e2'>".$delay." delay</td>";
                        echo "<td>".$d['t']['name']."</td>";
                        echo "<td>".(trim($d['t']['name']) == trim($resolution_tag[$d['va']['txn_id']]) ? $blank : $resolution_tag[$d['va']['txn_id']])."</td>";
	  		$i++;	
	  		echo "</tr>";
	  		}
}
	  		echo "Total closed:".($i-1)."</br></br>";
		 ?> 
		</table>