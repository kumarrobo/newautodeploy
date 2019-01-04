<html>
<style>   
    #Yestbutton{
    width: 5em; height : 2em;
    font-weight: bold;
    font-size: 20px; 
    font-family: ariel;
               }

    #Tombutton{
    width: 5em; height : 2em;
    font-weight: bold;
    font-size: 20px; 
    font-family: ariel; 
               }
               
    #blankbutton{
    visibility: hidden;
                }


</style>


    <div  style="float:right; width:50%">         
        <button type="submit" id="tombutton" <?php if ($dates > date("Y-m-d") or $dates == date("Y-m-d")) { ?> disabled <?php } ?>onclick="tomdate()"> > </button>       
    </div>

    <div style="float:right;width :3%">
        <input type="hidden"  id = "blankbutton"> </button>
    </div>

    <div style="float:right; ">
        <button type="submit" id="yestbutton"  onclick="yest()"> < </button>
    </div>

    
    <?php

    /* Yesterday and Today date defining*/
    $dateyeslink = date_create($dates); 
    date_sub($dateyeslink, date_interval_create_from_date_string('1 days'));

    
    $datetolink = date_create($dates); 
    date_add($datetolink, date_interval_create_from_date_string('1 days'));
    ?>

    <?php
    
   /* echo "<pre>";
print_r($this->params['pass']);
echo "</pre>"; 
?>
<script>
    $(document).ready(function(){
        
                $('#selectdate').datepicker({
                    format: "yyyy-mm-dd",
                    startDate: "-365d",
                    endDate: "1d",
                    multidate: false,
                    autoclose: true,
                    todayHighlight: true
                   });
       
       
                    $('#btnSearch').click(function()
                    {
window.location.href = "http://cc.pay1.com/sims/lastModemTransactions/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/<?php echo $this->params['pass'][2] ?>/"+$("#selectdate").val();
                     });
    });
       
 </script>
                      
 <div class="col-lg-4 col-lg-offset-2">
    <div class="form-group">
       <div class="col-md-6">
            <input id="selectdate" name="searchbydate" type="text" placeholder="Date" class="form-control input-md input-sm"/>
       </div>
         <div class="col-md-3">
        <input type="button" id="btnSearch" class="btn btn-default btn-primary btn-sm" value="Search" onclick=""/>
    </div>
      </div>
 </div>
    */
    ?>
<?php if($pageType != 'csv'){?>

<?php 
	$body = "Last (".(($page-1)*10 + 1)."-".($page*10).") transactions from device: $device<br/>";
	$body .= "<table border=1>";
	$body .= "<tr> 
				<th>Sr. No</th>
				<th>Mobile/Sub Id</th>
				<th>Amount</th>
				<th>Ref Id</th>
				<th>Status</th>
				<th>Incentive</th>
				<th>Trials</th>
				<th>Cause</th>
				<th>SIM Balance</th>
				<th>Diff</th>
				<th>SMS Received</th>
				<th>Added at</th>
				<th>Processed at</th>
				<th>Status updated at</th>
			</tr>";
	$i=0;
	$prev_amt = 0;
	$next_amt = 0;
	$prev_txn = 0;				
	if(!empty($data)) foreach($data as $md){
		$i++;
		$diff = 0;
		$body .= "<tr>";
		$body .= "<td>$i</td>";
               // echo "<td><a href='/panels/transaction/".$d['va']['ref_code']."' >".$d['va']['ref_code']."</a></td>";

		$body .= "<td><a target='_blank' href='/panels/userInfo/".$md['mobile']."'>".$md['mobile']."</a>/<a target='_blank' href='/panels/userInfo/".$md['param']."/subid'>".$md['param']."</a></td>";

		$body .= "<td>".$md['amount']."</td>";
		$body .= "<td><a target='_blank' href='/panels/transaction/".$md['vendor_refid']."'>".$md['vendor_refid']."</a></td>";
		if($md['status'] == 0){
			$body .= "<td>In Process</td>";
		}
		else if($md['status'] == 1){
			$body .= "<td>Successful</td>";
		}
		else {
			$body .= "<td>Failed</td>";
		}
		if(!empty($md['sim_balance']) && $md['status'] != 2){
			$prev_amt = $next_amt;
			$next_amt = $md['sim_balance'];
		}
		
		if(!empty($prev_amt) && $md['status'] != 2 && !empty($prev_txn)){
			$diff = $prev_amt + $prev_txn -$next_amt;
		}
		
		if($md['status'] != 2)
		$prev_txn = $md['amount'];
		
		$body .= "<td>".$md['incentive']."</td>";
		$body .= "<td>".$md['trials']."</td>";
		$body .= "<td>".$md['cause']."</td>";
		$body .= "<td>".$md['sim_balance']."</td>";
		$body .= "<td>".$diff."</td>";
		$body .= "<td>".$md['message']."</td>";
		$body .= "<td>".$md['timestamp']."</td>";
		$body .= "<td>".$md['processing_time']."</td>";
		$body .= "<td>".$md['updated']."</td>";
		$body .= "</tr>";
	}
	$body .= "</table>";
	echo $body;
?>


<?php } ?>

    
<script>
    
 function yest(){
    window.location ='/sims/lastModemTransactions/<?php echo $vendor; ?>/<?php echo $device; ?>/1/1500/<?php echo date_format($dateyeslink, 'Y-m-d'); ?>';
}
        
function tomdate(){
    window.location = '/sims/lastModemTransactions/<?php echo $vendor; ?>/<?php echo $device; ?>/1/1500/<?php echo date_format($datetolink, 'Y-m-d'); ?>';
}
        
</script>