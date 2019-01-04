<script>

	function setAction()
	{
		var sel=$('vendorDD');
		var seldd=$('distDD');
        var froh = $('frm_time_hrs');
        var from = $('frm_time_mins');
        var toh  = $('to_time_hrs');
        var tom  = $('to_time_mins');
        var from_time = froh.options[froh.selectedIndex].value + "." + from.options[from.selectedIndex].value;
        var to_time = toh.options[toh.selectedIndex].value + "." + tom.options[tom.selectedIndex].value;

		var tagName=sel.options[sel.selectedIndex].value;
		var tagName_dis=seldd.options[seldd.selectedIndex].value;
		document.retailerSale.action="/panels/retailerSale/"+$('from').value+"/"+$('to').value+"/"+from_time+"/"+to_time+"/"+tagName+"/"+tagName_dis;
		document.retailerSale.submit();	
	}

</script>

    
<?php
    $frms_time = explode(':',$frms_time);
    $tos_time = explode(':',$tos_time);
?>
<form name="retailerSale" method="POST" onSubmit="setAction()">
From Date <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!is_null($frm))echo $frm;?>" />

<select name="frm_time_hrs" id="frm_time_hrs">
    <?php for($i=0;$i<24;$i++){ ?>
        <option <?php if($i == $frms_time[0]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ;?></option>
    <?php } ?>                         
</select> :
<select name ="frm_time_mins" id="frm_time_mins">
    <?php for($i=0;$i<60;$i++){ ?>
       <option <?php if($i == $frms_time[1]) { echo 'selected'; } ?>><?php echo $i < 10 ? 0 . $i : $i ;?></option>
    <?php } ?>
</select>&nbsp;&nbsp;&nbsp;

To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" value="<?php if(isset($to))echo $to;?>" />

<select name ="to_time_hrs" id="to_time_hrs">
      
         <?php for($i=0;$i<24;$i++){ ?>
            
        <option <?php if($i == $tos_time[0]) { echo 'selected'; }  ?> > <?php echo $i < 10 ? 0 . $i : $i ;?> </option>
       
            <?php } ?>
       
   </select>
   :
   <select name ="to_time_mins" id="to_time_mins">
    
   <?php for($i=0;$i<60;$i++){ ?>
       <option <?php if($i == $tos_time[1]) { echo 'selected'; }  ?>><?php echo $i < 10 ? 0 . $i : $i ;?>  </option>
       <?php } ?>
       
   </select>&nbsp;&nbsp;&nbsp;
 


Vendors: 
	
	<select name="vendorDD" id="vendorDD"  >
	<?php
			
			echo "<option value='0' >All</option>";
			
			foreach($vendorDDResult as $tr)
			{
				$sel='';
				if($vendor==$tr['vendors']['id'])
					$sel='selected';
			
				echo "<option ".$sel." value='".$tr['vendors']['id']."' >".$tr['vendors']['company']."</option>";
			}
			
	?>
	
</select>
<br></br>

Distributors: 
	
	<select name="distDD" id="distDD"  >
	<?php
			
			echo "<option value='0'>All</option>";
			
			foreach($distDDResult as $tr)
			{
				$sel1='';
				if($dist==$tr['distributors']['id'])
					$sel1='selected';
			
				echo "<option ".$sel1." value='".$tr['distributors']['id']."' >".$tr['distributors']['company']."</option>";
			}
			
	?>
	
</select>
<input type="button" value="Submit" onclick="setAction()">
</form>

	
<br/>
<?php if($days <= 8) { ?>
<table border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top">
<table border="1" cellspacing="0" cellpadding="0">
<h4> Sale Report (<a href="/shops/floatGraph" target="_blank">Graphs</a>)</h4>
	<tr>
		<?php
			echo "<td>Total sale</td><td><strong> Rs.".$success['sale']."</strong></td>";
			
		?>
	</tr>
	<tr>
		<?php
			if($days > 1)
			echo "<td>Avg sale/day</td><td><strong> Rs.".intval($success['sale']/$days)."</strong></td>";
		?>
	</tr>
	<?php 
			$total = $success['success'] + $success['failed'];
			$app_succ = $total==0 ? 0 : round($success['app']*100/$total,2);
                        
                        $android_succ = $total==0 ? 0 : round($success['android']*100/$total,2);
                        $java_succ = $total==0 ? 0 : round($success['java']*100/$total,2);
                        $windows7_succ = $total==0 ? 0 : round($success['windows7']*100/$total,2);
                        $windows8_succ = $total==0 ? 0 : round($success['windows8']*100/$total,2);
                        $windows_succ = $windows7_succ + $windows8_succ;
			$ussd_succ = $total==0 ? 0 : round($success['ussd']*100/$total,2);
                        $web_succ = $total==0 ? 0 : round($success['web']*100/$total,2);
			$sms_succ = 100 - $app_succ - $ussd_succ - $android_succ - $java_succ - $windows_succ - $web_succ;

                   
			echo "<tr><td>Transactions/day</td><td><strong>".intval($success['success']/$days)."</strong></td></tr>";
			echo "<tr><td>Via USSD</td><td><strong>$ussd_succ % ( ".(isset($retCountArr[2])?$retCountArr[2]:0)." )</strong></td></tr>";
			echo "<tr><td>Via APP</td><td><strong>$app_succ %( ".(isset($retCountArr[1])?$retCountArr[1]:0)." )</strong></td></tr>";
                        
                        echo "<tr><td>Via Android</td><td><strong>$android_succ %( ".(isset($retCountArr[3])?$retCountArr[3]:0)." )</strong></td></tr>";
                        echo "<tr><td>Via Java</td><td><strong>$java_succ % ( ".(isset($retCountArr[5])?$retCountArr[5]:0)." )</strong></td></tr>";
                        echo "<tr><td>Via Windows7</td><td><strong>$windows7_succ % ( ".(isset($retCountArr[7])?$retCountArr[7]:0)." ) </strong></td></tr>";
                        echo "<tr><td>Via Windows8</td><td><strong>$windows8_succ % ( ".(isset($retCountArr[8])?$retCountArr[8]:0)." ) </strong></td></tr>";
                        
                        echo "<tr><td>Via Web</td><td><strong>$web_succ % ( ".(isset($retCountArr[9])?$retCountArr[9]:0)." )</strong></td></tr>";
                        
			echo "<tr><td>Via SMS</td><td><strong>$sms_succ % ( ".(isset($retCountArr[0])?$retCountArr[0]:0)." )</strong></td></tr>";
			echo "<tr><td>Total failed transaction </td><td><strong>".( $total==0 ? 0 : round($success['failed']*100/$total,2) )."%</strong></td></tr>";
			echo "<tr><td>Avg Comm</td><td><strong>".round($success['comm']/$success['tot'],2)."</strong></td></tr>";
		?>
</table>

</td><td valign="top">
<table border="0" cellpadding="3" cellspacing="0">
	<tr valign="top">
		<td>
			<?php if (isset($operatorSale)) {  ?>
	
			<table style="margin-left:20px" border="1" cellpadding="0" cellspacing="0">
				<h4> Operator Wise Sale Report </h4>
					<th>Operator</th>
					<th>Total Sale</th>
					<?php if($days > 1) { ?><th>Average Sale</th><?php } ?>
					<th>Sale %</th>
					<th>Failure %</th>
					<th>Comm %</th>
					<th>api sale</th>
					<th>modem sale</th>
			<?php 
				foreach($operatorSale as $d)
				{
				echo "<tr>";
				echo "<td>".$d['p']['name']."</td>";
				echo "<td>".$d['0']['success']."</td>";
				if($days > 1) {
					echo "<td>".intval($d['0']['success']/$days)."</td>";
				}
				echo "<td>".round($d['0']['success']*100/$success['sale'],2)."%</td>";
				echo "<td>".round(($d['0']['count']-$d['0']['scount'])*100/$d['0']['count'],2)."%</td>";
				echo "<td>".round($d['0']['comm']/$d['0']['tot'],2)."%</td>";
				echo "<td>".round($d['0']['api_success']*100/$d['0']['success'],2)."%</td>";
				echo "<td>".round($d['0']['modem_success']*100/$d['0']['success'],2)."%</td>";
				echo "</tr>";
				}
			?>
			</table>
			<?php	}	?>
		</td>
		
	</tr>
</table>
</td></tr>
</table>
<?php } else { ?>
Diff between dates cannot be greater that 8
<?php } ?>