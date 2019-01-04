<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap-responsive.min.css?990' />
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />


<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
</script>

<style type="text/css">
    body .modal {
        /* new custom width */
        width: 800px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -370px;
    }

</style> 

<style type="text/css">
    .stopped{
        background-color: #c73525;
        color: #fffff;
    }
    .running{
        background-color: #99ff99;
        color:#ffffff;
    }
    .altRow1{
    color:#E6B8B8;
    }
</style>


<?php

$date1 = date('d-m-Y',strtotime($date));
$body = "";
$body.="<input type='text' style='height:30px;'name='from' id='from'  onmouseover='fnInitCalendar(this, \"from\",\"close=true\")' value=\"$date1\"><input style='height:30px;' type=\"button\" value=\"Submit\" onclick=\"setAction()\"/>";
$body.='<table  class="table">
        <thead>
            <th width="10%"></th>
            <th width="10%">Sims</th>
            <th width="10%">Working Sims</th>
            <th width="10%">Requests/min</th>
            <th width="10%">Curr Bal</th>
            <th width="10%">Opening</th>
            <th width="10%">Closing</th>
            <th width="10%">Incoming</th>
            <th width="10%">Sale</th>
            <th width="10%">Diff</th>
        </thead>
        <tbody></tbody>
       </table>';

            foreach ($operatorwiseModem as $oprkey => $oprval) {
             
                $totalactivesims = 0;
                $totalworkingsims = 0;
                $totalsims = 0;
                $totalopening = 0;
                $totalclosing = 0;
                $totalsale = 0;
                $totalincoming = 0;
                $totalinc = 0;
                $totalcurrbal = 0;
                $totaldiff = 0;
                $totalreq = 0;
                $totalsucc = 0;
           
                       foreach($oprval as $modemKey => $modemVal){
                       	  $totalworkingsims += $modemVal['workingsims'];
                          $totalactivesims+=$modemVal['activesims'];
                          $totalsims+=$modemVal['totalsims'];
                          $totalopening+=$modemVal['opening'];
                          $totalclosing+=$modemVal['closing'];
                          $totalsale+=$modemVal['sale'];
                          $totaldiff+=$modemVal['diff'];
                          $totalcurrbal+=$modemVal['curr_bal'];
                          $totalincoming+=$modemVal['incoming'];
                          $totalreq += $requests[$oprkey][$modemKey]['count'];
                          $totalsucc += $requests[$oprkey][$modemKey]['success'];
                        }
                     
              $body.="<table style='margin-bottom:20px;' class='table'><tr><td width='10%' onclick=\"showdata('".$oprkey."')\" ><a href='javascript:void(\"0\")'><img id='".$oprkey."' src=\"/img/plusIcon.jpg\" height=\"18px;\" width=\"18px;\"></img></a>".$operatorlist[$oprkey]."</td><td width='10%'>".$totalactivesims."/".$totalsims."</td><td width='10%'>".$totalworkingsims."</td><td width='10%'>".$totalsucc."/".$totalreq."</td><td width='10%'>".$totalcurrbal."</td><td width='10%'>". $totalopening."</td><td width='10%'>".$totalclosing."</td><td width='10%'>".$totalincoming."</td><td width='10%'>".$totalsale."</td><td width='10%'>".$totaldiff."</td></tr>";
                $j=1; 
                if(count($apiVendorSale[$oprkey])){
                    foreach($apiVendorSale[$oprkey] as $apikey => $apival){
                     if ($j % 2 == 0)
                       $class = '';
                   else
                       $class = 'altRow';
                   $body.="<table style='text-align:center;display:none;' class='table1 ".$oprkey."'>
                        <tr class='".$class."'><td width='10%'  style=\"text-align:center;\">".strtoupper($apival['company'])."</td><td width='10%'></td><td width='10%'>".$requests[$oprkey][$apikey]['success']."/".$requests[$oprkey][$apikey]['count']."</td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'>".$apival['sale']."</td><td width='10%'></td></tr></table>";
                   }
                   }
             foreach($oprval as $modemkey => $modemval){
                if ($j % 2 == 0)
                       $class = '';
                   else
                       $class = 'altRow';
                       $class1 =  'altRow1';
              $body.="<table style='text-align:center;display:none;' class='table1 ".$oprkey."'>";
              $body.="<tr class='".$class."'><td width='10%' onclick=\"showdata('".$oprkey."_".$modemkey."')\"  style=\"text-align:center;\"><a href='javascript:void(\"0\")'><img id='".$oprkey."_".$modemkey."' src=\"/img/plusIcon.jpg\" class='button_".$oprkey."' height=\"18px;\" width=\"18px;\"></img></a>".$map[$modemkey]."</td><td width=\"10%\">".$modemval['activesims']."/".$modemval['totalsims']."</td><td width='10%'>".$modemval['workingsims']."</td><td width=\"10%\">".$requests[$oprkey][$modemkey]['success']."/".$requests[$oprkey][$modemkey]['count']."</td><td width=\"10%\">".$modemval['curr_bal']."</td><td width=\"10%\">". $modemval['opening']."</td><td width=\"10%\">".$modemval['closing']."</td><td width=\"10%\">".$modemval['incoming']."</td><td width=\"10%\">".$modemval['sale']."</td><td width=\"10%\">".$modemval['diff']."</td></tr>";
              $body.="</table>";
               
               $body.="<table   style=\"display:none;\" class='table table-bordered ".$oprkey."_".$modemkey." hide".$oprkey."' bgcolor='" . $bg_table . "'>";
                $body.="<tr> 
				<th>Dev/ Machine/ Port</th>
				<th>Signal</th>
				<th>Vendor</th>
				<th>Operator</th>
				<th>Number</th>
				<th>Margin</th>
				<th>Curr Bal</th>
				<th>Opening</th>
	    		<th>Closing</th>
	    		<th>Incoming</th>
	    		<th>Sale</th>
	    		<th>Roaming/Limit</th>
                <th>Home Sale</th>
	    		<th>Inc</th>
	    		<th>Diff</th>
	    		<th>Succ %</th>
	    		<th>Last Succ</th>
	    		<th>Prcs time</th>
                        <th>Active</th>
	    		
               
			</tr>";
            foreach ($modemval['data'] as $vendorkey => $vendorval) {
                    
                    $color = '';
                    if ($vendorval['active_flag'] == 1 && $vendorval['balance'] < 3000)
                        $color = '#8c65e3';
                    else if ($vendorval['active_flag'] == 0 && $vendorval['balance'] > 3000)
                        $color = '#c73525';
                    else if ($vendorval['active_flag'] == 1 && $vendorval['balance'] > 3000 && date('Y-m-d H:i:s', strtotime('-45 minutes')) > $vendorval['last'])
                        $color = '#f6ff00';
                    else if ($vendorval['active_flag'] == 1)    
                        $color = '#99ff99';
                       $hideclass = '';
                      if($color ==''){
                         $hideclass = 'hidediv';
                      }
                    $homesale = $vendorval['sale'] - $vendorval['roaming_today'];  
                    $body .= "<tr id='device".$vendorval['id']."_".$vendorval['mobile']."' class='".$vendorval['vendor']." ".$vendorval['mobile']." ".$hideclass."' bgcolor='$color'>";
                    $body .= "<td id='device".$vendorval['id']."'>" . $vendorval['id']. "/" . $vendorval['machine_id'] . "/" . $vendorval['device_num'] . "</td>";
                    $body .= "<td>" . $vendorval['signal'] . "</td>";
                    $body .= "<td>" . $vendorval['vendor'] . "</td>";
                    $body .= "<td>" . $vendorval['operator'] . "</td>";
                    $body .= "<td>" . $vendorval['mobile'] . "</td>";
                    $body .= "<td>" . $vendorval['commission'] . "%</td>";
                    $body .= "<td>" . $vendorval['balance'] . "</td>";
                    $body .= "<td>" . ((isset($vendorval['opening'])) ? $vendorval['opening'] : "") . "</td>";
                    $body .= "<td>" . ((isset($vendorval['closing'])) ? $vendorval['closing'] : "") . "</td>";
                    $body .= "<td>" . $vendorval['tfr'] . "</td>";
                    $body .= "<td>" . $vendorval['sale'] . "</td>";
                    if ($vendorval['roaming_limit'] > 0 || $vendorval['limit'] > 0) {
                        $body .= "<td>" . $vendorval['roaming_today'] . "/" . $vendorval['limit_today'] . "</td>";
                    } else
                        $body .= "<td></td>";
                    $body .= "<td>" . $homesale. "</td>";
                    $body .= "<td>" . intval($vendorval['inc']) . "</td>";
                    $open = (isset($vendorval['opening'])) ? $vendorval['opening'] : 0;
                    $close = (isset($vendorval['closing'])) ? $vendorval['closing'] : 0;
                    $opening += $open;
                    $closing += $close;
                    $inc += intval($vendorval['inc']);
                    $tfr += $vendorval['tfr'];
                    $sale += $vendorval['sale'];
                    $tfr1+=$vendorval['tfr'];

                    if ($date != date('Y-m-d')) {
                        $diff = $vendorval['sale'] - ($open + $vendorval['tfr'] - $close);
                        $diff1 = $vendorval['sale'] - ($open + $vendorval['tfr'] - $close);
                    } else {
                        $diff = $vendorval['sale'] - ($open + $vendorval['tfr'] - $vendorval['balance']);
                        $diff1 = $vendorval['sale'] - ($open + $vendorval['tfr'] - $close);
                    }
                    $diff = $diff - $vendorval['inc'];
                    $diff1 = $diff - $vendorval['inc'];
                    $diff_tot += $diff;
                    $diff_tot1+= $diff;
                    
                    $body .= "<td>" . intval($diff) . "</td>";
                    if ($vendorval['success'] > 0) {
                        $body .= "<td>" . $vendorval['success'] . "%</td>";
                    } else {
                        $body .= "<td></td>";
                    }
                    if (!empty($vendorval['last'])) {
                        $body .= "<td>" . date('H:i:s', strtotime($vendorval['last'])) . "</td>";
                    } else {
                        $body .= "<td></td>";
                    }
                    if (!empty($vendorval['process_time'])) {
                        $body .= "<td>" . $vendorval['process_time'] . " secs</td>";
                    } else {
                        $body .= "<td></td>";
                    }

                    $body .= "<td>" . $vendorval['active_flag'] . "</td>";

                    //$body .= "<td id='reset" . $vendorval['id'] . "_" . $modemkey . "'><a href='javascript:void(0)' onclick='resetDevice(" . $vendorval['id'] . "," . $modemkey . ")'>Reset</a></td>";
                   
                    $modem_bal += $vendorval['balance'];
                    $mbal += $vendorval['balance'];
                    $body .= "</tr>";
                    $diff_tot = intval($diff_tot);
                    $diff_tot1 = intval($diff_tot1);
                    $total += $modem_bal;
                    $homesale = 0;
                   
                }
               $j++;
               $body.="</table>";
               
               }
               
             }
echo $body;
?>


<script type="text/javascript">
function showdata(vendorId){
 
   jQuery("."+vendorId).toggle("fast");
    var src = (jQuery("#"+vendorId).attr("src") === '/img/plusIcon.jpg')
            ? '/img/minus.png'
            : '/img/plusIcon.jpg'; 
   if((jQuery("#"+vendorId).attr("src") == '/img/minus.png')){
      var res = vendorId.split("_");
      jQuery(".hide"+res[0]).hide();
      jQuery(".button_"+vendorId).attr("src",src);
     }
     jQuery("#"+vendorId).attr("src",src);
   
  }

function setAction(){
   
	var frmDate = jQuery("#from").val();
    var fdate = frmDate.split("-");
    window.location.href ="/recharges/simOverview/0/"+fdate[2]+"-"+fdate[1]+"-"+fdate[0];
	
	
}

</script>
