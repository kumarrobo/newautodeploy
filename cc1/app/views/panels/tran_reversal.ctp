
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
   <link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>
<script>jQuery.noConflict();</script>

<script>
jQuery(document).ready(function() {    
 jQuery("select#vendorDD").multipleSelect({ selectAll: false, width: 290, multipleWidth: 120, multiple: true});
    jQuery("select#productDD").multipleSelect({ selectAll: false, width: 290, multipleWidth: 120, multiple: true});   
});    
    function setAction() {

        var vendorDD = jQuery('select#vendorDD').multipleSelect("getSelects");
        var productDD = jQuery('select#productDD').multipleSelect("getSelects");
        var tsel = $('typeDD');
        var typeDD = tsel.options[tsel.selectedIndex].value;        
        if (0 >= vendorDD.length) {
            vendorDD = 0;
        }
        if (0 >= productDD.length) {
            productDD = 0;
        }
        document.tranReversal.action = "/panels/tranReversal/" + $('from').value + "/" + $('to').value + "/" + vendorDD + "/" + productDD +"/" + typeDD;
        document.tranReversal.submit();
    }


</script>
<form name="tranReversal" method="POST" onSubmit="setAction()">
    From Date <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from', 'close=true')" value="<?php if (!is_null($frm)) echo $frm; ?>" />
    To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to', 'close=true')" value="<?php if (isset($to)) echo $to; ?>" />

    Vendors: 

    <select name="vendorDD" id="vendorDD"  >
                            <option value="0">All</option>
                    <?php foreach($vendorDDResult as $tr): ?>
                            <option value="<?php echo $tr['vendors']['id'] ?>" <?php if(in_array($tr['vendors']['id'], $vendorss)) echo "selected" ?> >
                                    <?php echo $tr['vendors']['company'] ?>
                            </option>
                    <?php endforeach ?>

    </select>
    
        Products: 

    <select name="productDD" id="productDD"  >
        
        <option value="0">All</option>
        <?php foreach ($productDDResult as $tr): ?>
            <option value="<?php echo $tr['products']['id'] ?>" <?php if (in_array($tr['products']['id'], $productss)) echo "selected" ?> >
                <?php echo $tr['products']['name'] ?>
            </option>
        <?php endforeach ?>


    </select>
        <br><br>
       Complaint type: 
        
    <select name="typeDD" id="typeDD"  >
        
        <option value="0">All</option>
        <option value="1" <?php  if($type ==  1) {echo 'selected';}?> style="background:white">None</option>                        
        <option value="2" <?php  if($type ==  2) {echo 'selected';}?> style="background:#E6BE8A">New Retailer</option> 
        <option value="3" <?php  if($type ==  3) {echo 'selected';}?> style="background:#F7D4D4">New Retailer With Call Complaint</option>                        
        <option value="4" <?php  if($type ==  4) {echo 'selected';}?> style="background:#99ff99">Call Complaint</option>                        


    </select>        
    <input type="checkbox" name ="b2c_flag" <?php if ($b2c_flag == "true") { ?> checked="checked"<?php } ?> value="true">B2C
    <input type="button" value="Submit" onclick="setAction()">
</form>
<br>

<marquee behavior="alternate" scrollamount="2" class="row">
    <div class="alert alert-danger" role="alert">
        <label style="font-size:large;">Top apis:</label>
<?php
foreach ($top_vendors as $tv) {
    //for converting vendorid array to string
    $v = implode(",", $vendors);


    echo "<a style='font-size:large;' target='_blank' href='/panels/inProcessTransactions/" . $frm . "/" . $to . "/" . $vendors[$tv] . "'  class='alert-link'>" . $in_process_vendors[$tv] . "(" . $in_process_vendors_count[$tv] . ")</a> ";
}
?>
        <br/>
        <label style="font-size:large;">Top operators:</label>
        <?php
        foreach ($top_products as $tp) {
            echo "<a style='font-size:large;' target='_blank' href='/panels/inProcessTransactions/" . $frm . "/" . $to . "/" . $v . "/" . $product[$tp] . "' class='alert-link'>" . $in_process_products[$tp] . "(" . $in_process_products_count[$tp] . ")</a> ";
        }
        ?>
    </div>
</marquee>



<table width="100%" border="0">
    <tr>
        <td valign="top" >

            <h4><a target="_blank" href="/panels/closedComplaints/<?php echo $frm . "/" . $to . "/" . $vendor . "/" . $products . "/" . $b2c_flag ?>">Closed complaints</a>
                (<?php echo $closed_count ?>)</h3>
                <table border="1" cellpadding="0" cellspacing="0" style="text-align:center">
                    <tr> 
                        <th>Index</th>
                        <th>Tran Id</th>
                        <!--	<th>Retailer Name/ShopName</th> -->
                        <th>VTransID</th>
                        <th>Vendor</th>
                        <!--	<th>Retailer Mobile</th> -->
                        <th>Cust Mob</th>
                        <th>Operator</th>
                        <th>Amt</th>
                        <th>Status</th>
                        <th>Complaint Date</th> 
                        <th>Trans Date</th>
                        <th>Difference</th>
                        <th>Time Left</th>
                        <th>Complaint Tag</th>
                    </tr>

<?php
$i = 1;

foreach ($success as $d) {
    if (strcmp($d['r']['name'], '') != 0) {
        $retailerLink = $d['r']['name'];
    } else {
        $retailerLink = $d['r']['mobile'];
    }

    if ($d['r']['id'] == 13)
        $color = '#DBEB23';
    else if ((in_array($d['r']['id'], $retailerData)) && !empty($d['complaints']['takenby']))
        $color = '#F7D4D4';    
    else if (!empty($d['complaints']['takenby']))
        $color = '#99ff99';
    else if (in_array($d['r']['id'], $retailerData))
        $color = '#E6BE8A';
    else
        $color = '';

    
    echo "<tr bgcolor='$color'>";
    echo "<td>" . $i . "</td>";
    echo "<td><a href='/panels/transaction/" . $d['va']['txn_id'] . "' >" . $d['va']['txn_id'] . "</a></td>";
    //echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."' >".$retailerLink."</br>".$d['r']['shopname']."</td>";
    echo "<td>" . $d['va']['vendor_refid'] . "</td>";
    // echo "<td><a href='/panels/retInfo/".$d['r']['mobile']."' >".$d['r']['name']."</a></td>";
    echo "<td>" . $d['v']['shortForm'] . "</td>";
    echo "<td><a href='/panels/userInfo/" . $d['va']['mobile'] . "' >" . $d['va']['mobile'] . "</a></td>";
    echo "<td>" . $d['p']['name'] . "</td>";
    echo "<td>" . $d['va']['amount'] . "</td>";

    $ps = '';
    if ($d['va']['status'] == '0') {
        $ps = 'In Process';
    } else if ($d['va']['status'] == '1') {
        $ps = 'Successful';
    } else if ($d['va']['status'] == '2') {
        $ps = 'Failed';
    } else if ($d['va']['status'] == '3') {
        $ps = 'Reversed';
    } else if ($d['va']['status'] == '4') {
        $ps = 'Complaint taken';
    } else if ($d['va']['status'] == '5') {
        $ps = 'Complaint declined';
    }
    echo "<td>" . $ps . "</td>";
    echo "<td>" . $d['complaints']['in_date'] . " " . $d['complaints']['in_time'] . "</td>";
    echo "<td>" . $d['va']['timestamp'] . "</td>";
    $diff = strtotime($d['complaints']['in_date'] . " " . $d['complaints']['in_time']) - strtotime($d['va']['timestamp']);
    echo "<td>" . floor($diff / 3600) . " hrs, " . floor(($diff / 60) % 60) . " mins, " . ($diff % 60) . " secs" . "</td>";
    /* if($d['va']['status']=='0' || $d['va']['status']== '4'){
      echo "<td><a href=''>Accept</a></br></br><a href=''>Decline</a></td>";
      }else{
      echo "<td>FAILURE</td>";
      } */
    if (strtotime($d['complaints']['turnaround_time']) > 0) {
        $secs = (strtotime($d['complaints']['turnaround_time']) - time());
        $mins = $secs / 60;
        if ($secs < 60) {
            if ($mins < 0) {
                $hours = round(-$mins / 60);
                $mins = round(-$mins % 60);
                echo "<td style='color:red'>" . $hours . " Hrs " . $mins . " mins delayed </td>";
            } else
                echo "<td style='color:orange'>" . round($secs) . " secs left </td>";
        }
        else {
            $hours = intval($mins / 60);
            $mins = intval($mins % 60);
            echo "<td>" . $hours . " Hrs " . $mins . " mins left </td>";
        }
    } else
// 	  			echo "<td><div>
// 							<select id='tat_hr_".$d['complaints']['id']."'>";
// 								for($i = 0; $i < 25; $i++){ 
// 								 	echo "<option value='";
// 								 	echo $i."'>";
// 								 	if($i < 10){ echo "0".$i; }else{ echo $i; } 
// 								 		echo "</option>";
// 								}
// 								echo "
// 								<option value='48'>48</option>
// 							</select> Hr 
// 							<select id='tat_min_".$d['complaints']['id']."'>
// 								<option value='0'>00</option>
// 								<option value='0.5'>30</option>
// 							</select> Min
// 	  						<button value='Set' onclick='setTAT(".$d['complaints']['id'].");'>Set</button>
// 							</div></td>";
        echo "<td></td>";
    echo "<td>" . $d['t']['name'] . "</td>";

    $i++;
    echo "</tr>";
   //echo "Sales result array :".$salesResultArray;
}
?> 
                    <h4>Complaints in Process <?php echo "" . ($i - 1) . "</br></br>"; ?> </h4> 	
                </table>
        </td>
        <td valign="top" >
       </td>	
    </tr>
</table>