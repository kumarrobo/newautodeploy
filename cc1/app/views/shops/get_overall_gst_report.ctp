<?php if($page!='download' || $page!='monthlyreport'){?>
<html>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/invoice.js"></script>
</head>

<body>    
<div>
<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
  <div id="pageContent" style="min-height:500px;position:relative;">
  <div class="loginCont">
      <?php echo $this->element('shop_side_reports',array('side_tab' => 'gstreport'));?>
   <!--<div id="innerDiv">-->
   <div style="width:1500px">
      <form method="post" id="gstReport">
      <input type="hidden" name='download_gst' id ='download_gst' value="">
  <div>
      <div style="margin-top:10px;">
                <?php  $starting_year  = '2017';
                       $ending_year    = date('Y');  ?>   
              <span style="font-weight:bold;margin-right:10px;">Select Year: </span>
              <select id="inv_year" name="inv_year">
                    <?php   for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                            if($starting_year == $year) { $sel = 'selected'; }
                            echo "<option $sel >".$starting_year."</option>";
                            $sel = ''; } ?>
              </select>
        
            <?php $formattedMonthArray = array(
                    "01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr",
                    "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug",
                    "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec"); ?>    
                <span style="font-weight:bold;margin-right:10px;">Select Month: </span>
                <select id="inv_month" name="inv_month">
                 <?php foreach ($formattedMonthArray as $month_id => $month_val){
                         if($month_id == $month) { $sel = 'selected'; }
                         echo "<option value = '$month_id' $sel >".$month_val."</option>";
                          $sel = '';  }  ?>
                </select> 
                <span style="font-weight:bold;margin-right:10px;">Type: </span>
                <select name="type" id="type">    
                    <!--<option value="" <?php if(empty($this->params['form']['type'])){ echo "selected"; }?>>All</option>-->
                    <option value="0" <?php if(!empty($this->params['form']['type']) && $this->params['form']['type']==0){ echo "selected"; }?>>Purchase</option>
                    <option value="1" <?php if($this->params['form']['type']==1){ echo "selected"; }?>>Sale</option>
                </select>
                <button class="btn btn-primary btn-xs <?php echo $pull_class; ?>" style=""  type="submit" id="filerbtn" onclick="getGstReport()">Search</button>    
    <?php
    $pull_class = '';
    
    if( isset($gst_data) && !empty($gst_data) ){
//            $pull_class  = 'pull-right';
        ?>
        <!--<button class="btn btn-danger btn-xs pull-right" style="margin-right:50px"  type="button" id="btnsendmail" onclick='mailcheck(<?php echo $user_id ; ?>,<?php echo json_encode($invoice_ids); ?>,<?php echo $month; ?>,<?php echo $year;?>,2,1)'><span class="fa fa-envelope"></span>Mail</button>-->
        <button class="btn btn-success btn-xs" style=""  type="button" id="btndownload" onclick="downloadGSTReport()"><span class="glyphicon glyphicon-download" onclick="downloadZip()"></span>Download</button>
        <button class="btn btn-success btn-xs" style=""  type="button" id="btnmonthlyreport" onclick="downloadMonthlyReport()"><span class="glyphicon glyphicon-download" onclick="downloadZip()"></span>Monthly Report</button>
    <?php }?>
    
    </div>
      
<table class="table table-bordered table-hover" style="width:500px;margin-top:50px;">            
            
            
            <tbody>
                
                <?php if(!empty($gst_data))
                   {
                        $gross_amt = 0;$discount = 0;$net_amt = 0;$taxable_amt = 0;$input_gst = 0;$cgst = 0;$sgst = 0;$igst = 0;
                        
                        if(isset($gst_data['Overall_Maharashtra_purchase']) && !empty($gst_data['Overall_Maharashtra_purchase']))
                            { ?>
                            <tr><td colspan="9"><b>For Purchase Consolidated Summary</b></td></tr>
                            <tr>   
                            <th>Circle</th>
                            <!--<th>Net Purchase</th>-->
                            <th>To pay</th>
                            <th>Taxable</th>
                            <th>Input @18%</th>
                            <th>CGST</th>            
                            <th>SGST</th>            
                            <th>IGST</th>            
                            </tr>
                            <tr>
                            <td><b><?php echo 'Maharashtra'; ?></b></td>
                            <!--<td><?php // echo number_format($gst_data['Overall_Maharashtra_purchase'][''],2); ?></td>-->
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_purchase']['to_pay'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_purchase']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_purchase']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_purchase']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_purchase']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_purchase']['igst'],2); ?></td>
                            </tr>
                            
                       <?php }
                        if(isset($gst_data['Overall_Outside_purchase']) && !empty($gst_data['Overall_Outside_purchase']))
                        { ?>
                            <tr>
                            <td><b><?php echo 'Non Maharashtra'; ?></b></td>
                            <!--<td><?php // echo number_format($gst_data['Overall_Outside_purchase'][''],2); ?></td>-->
                            <td><?php echo number_format($gst_data['Overall_Outside_purchase']['to_pay'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_purchase']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_purchase']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_purchase']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_purchase']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_purchase']['igst'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Total</b></td>
                                <!--<td><b><?php // echo number_format(($gst_data['Overall_Maharashtra_purchase']['net_amt']+$gst_data['Overall_Outside_purchase']['net_amt']),2);?></b></td>-->
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_purchase']['to_pay']+$gst_data['Overall_Outside_purchase']['to_pay']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_purchase']['taxable_amt']+$gst_data['Overall_Outside_purchase']['taxable_amt']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_purchase']['input_gst']+$gst_data['Overall_Outside_purchase']['input_gst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_purchase']['cgst']+$gst_data['Overall_Outside_purchase']['cgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_purchase']['sgst']+$gst_data['Overall_Outside_purchase']['sgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_purchase']['igst']+$gst_data['Overall_Outside_purchase']['igst']),2);?></b></td>
                            </tr>
                       <?php }
                       if(isset($gst_data['P2P_Maharashtra_purchase']) && !empty($gst_data['P2P_Maharashtra_purchase']))
                        {?>
                            <tr><td colspan="9"></td></tr>                            
                            <tr>
                            <td><b><?php echo "P2P Maharashtra"; ?></b></td>
                            <!--<td><?php // echo number_format($gst_data['P2P_Maharashtra_purchase']['net_amt'],2); ?></td>-->
                            <td><?php echo number_format($gst_data['P2P_Maharashtra_purchase']['to_pay'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Maharashtra_purchase']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Maharashtra_purchase']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Maharashtra_purchase']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Maharashtra_purchase']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Maharashtra_purchase']['igst'],2); ?></td>
                            </tr>
                        <?php }
                        if(isset($gst_data['P2P_Outside_purchase']) && !empty($gst_data['P2P_Outside_purchase']))
                        { ?>
                            <tr>
                            <td><b><?php echo "P2P Non Maharashtra"; ?></b></td>
                            <!--<td><?php // echo number_format($gst_data['P2P_Outside_purchase'][0]['net_amt'],2); ?></td>-->
                            <td><?php echo number_format($gst_data['P2P_Outside_purchase']['to_pay'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Outside_purchase']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Outside_purchase']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Outside_purchase']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Outside_purchase']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2P_Outside_purchase']['igst'],2); ?></td>
                            </tr>
                            
                            <tr>
                                <td><b>Total</b></td>
                                <!--<td><b><?php // echo number_format(($gst_data['P2P_Maharashtra_purchase'][0]['net_amt']+$gst_data['P2P_Outside_purchase'][0]['net_amt']),2);?></b></td>-->
                                <td><b><?php echo number_format(($gst_data['P2P_Maharashtra_purchase']['to_pay']+$gst_data['P2P_Outside_purchase']['to_pay']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2P_Maharashtra_purchase']['taxable_amt']+$gst_data['P2P_Outside_purchase']['taxable_amt']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2P_Maharashtra_purchase']['input_gst']+$gst_data['P2P_Outside_purchase']['input_gst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2P_Maharashtra_purchase']['cgst']+$gst_data['P2P_Outside_purchase']['cgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2P_Maharashtra_purchase']['sgst']+$gst_data['P2P_Outside_purchase']['sgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2P_Maharashtra_purchase']['igst']+$gst_data['P2P_Outside_purchase']['igst']),2);?></b></td>
                            </tr>
                        <?php }
                        if(isset($gst_data['P2A_Maharashtra_purchase']) && !empty($gst_data['P2A_Maharashtra_purchase']))
                        {?>
                            <tr><td colspan="9"></td></tr>
                            <tr>
                            <td><b><?php echo "P2A Maharashtra"; ?></b></td>
                            <!--<td><?php // echo number_format($gst_data['P2A_Maharashtra_purchase'][0]['net_amt'],2); ?></td>-->
                            <td><?php echo number_format($gst_data['P2A_Maharashtra_purchase']['to_pay'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Maharashtra_purchase']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Maharashtra_purchase']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Maharashtra_purchase']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Maharashtra_purchase']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Maharashtra_purchase']['igst'],2); ?></td>
                            </tr>
                        <?php }
                        if(isset($gst_data['P2A_Outside_purchase']) && !empty($gst_data['P2A_Outside_purchase']))
                        { ?>
                            <tr>
                            <td><b><?php echo "P2A Non Maharashtra"; ?></b></td>
                            <!--<td><?php // echo number_format($gst_data['P2A_Outside_purchase'][0]['net_amt'],2); ?></td>-->
                            <td><?php echo number_format($gst_data['P2A_Outside_purchase']['to_pay'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Outside_purchase']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Outside_purchase']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Outside_purchase']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Outside_purchase']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['P2A_Outside_purchase']['igst'],2); ?></td>
                            </tr>
                            
                            <tr>
                                <td><b>Total</b></td>
                                <!--<td><b><?php // echo number_format(($gst_data['P2A_Maharashtra_purchase'][0]['net_amt']+$gst_data['P2A_Outside_purchase'][0]['net_amt']),2);?></b></td>-->
                                <td><b><?php echo number_format(($gst_data['P2A_Maharashtra_purchase']['to_pay']+$gst_data['P2A_Outside_purchase']['to_pay']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2A_Maharashtra_purchase']['taxable_amt']+$gst_data['P2A_Outside_purchase']['taxable_amt']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2A_Maharashtra_purchase']['input_gst']+$gst_data['P2A_Outside_purchase']['input_gst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2A_Maharashtra_purchase']['cgst']+$gst_data['P2A_Outside_purchase']['cgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2A_Maharashtra_purchase']['sgst']+$gst_data['P2A_Outside_purchase']['sgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['P2A_Maharashtra_purchase']['igst']+$gst_data['P2A_Outside_purchase']['igst']),2);?></b></td>
                            </tr>
                        <?php }
//                       $omh_discount = ($gst_data['Overall_Maharashtra_sale']['gross_amt']!=0)?($gst_data['Overall_Maharashtra_sale']['gross_amt']-$gst_data['Overall_Maharashtra_sale']['net_amt']):"-";
//                       $out_discount = ($gst_data['Overall_Outside_sale']['gross_amt']!=0)?($gst_data['Overall_Outside_sale']['gross_amt']-$gst_data['Overall_Outside_sale']['net_amt']):"-";
                        $omh_discount = $gst_data['Overall_Maharashtra_sale']['discount'];
                        $out_discount = $gst_data['Overall_Outside_sale']['discount'];
                        if(isset($gst_data['Overall_Maharashtra_sale']) && !empty($gst_data['Overall_Maharashtra_sale']))
                            {
                            
                            ?>
                            <tr><td colspan="9"><b>For Sales Consolidated Summary</b></td></tr>
                            <tr>   
                            <th>Circle</th>
                            <th>Sale</th>            
                            <th>Discount-Dist</th>
                            <th>Net Amount</th>
                            <th>Taxable</th>
                            <th>Input @18%</th>
                            <th>CGST</th>            
                            <th>SGST</th>            
                            <th>IGST</th>            
                            </tr>
                            <tr>
                            <td><b><?php echo 'Maharashtra'; ?></b></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['gross_amt'],2); ?></td>
                            <td><?php echo number_format($omh_discount,2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['net_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Maharashtra_sale']['igst'],2); ?></td>
                            </tr>
                            
                       <?php }
                        if(isset($gst_data['Overall_Outside_sale']) && !empty($gst_data['Overall_Outside_sale']))
                        { 
                            ?>
                            <tr>
                            <td><b><?php echo 'Non Maharashtra'; ?></b></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['gross_amt'],2); ?></td>
                            <td><?php echo number_format($out_discount,2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['net_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['input_gst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['cgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['sgst'],2); ?></td>
                            <td><?php echo number_format($gst_data['Overall_Outside_sale']['igst'],2); ?></td>
                            </tr>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['gross_amt']+$gst_data['Overall_Outside_sale']['gross_amt']),2);?></b></td>
                                <td><b><?php echo number_format(($omh_discount+$out_discount),2);?></b></td>                                
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['net_amt']+$gst_data['Overall_Outside_sale']['net_amt']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['taxable_amt']+$gst_data['Overall_Outside_sale']['taxable_amt']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['input_gst']+$gst_data['Overall_Outside_sale']['input_gst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['cgst']+$gst_data['Overall_Outside_sale']['cgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['sgst']+$gst_data['Overall_Outside_sale']['sgst']),2);?></b></td>
                                <td><b><?php echo number_format(($gst_data['Overall_Maharashtra_sale']['igst']+$gst_data['Overall_Outside_sale']['igst']),2);?></b></td>
                            </tr>
                       <?php }
                        if(isset($gst_data['P2P_Maharashtra_sale']) && !empty($gst_data['P2P_Maharashtra_sale']))
                        {
                            ?>
                            <tr><td colspan="9"></td></tr>
                            <tr><td colspan="9"><b>P2P Maharashtra</b></td></tr>
                            <tr>   
                            <th></th>
                            <th>Sale</th>            
                            <th>Discount-Dist</th>
                            <th>Net Amount</th>
                            <th>Taxable</th>
                            <th>Input @18%</th>
                            <th>CGST</th>            
                            <th>SGST</th>            
                            <th>IGST</th>            
                            </tr>
                            <?php foreach ($gst_data['P2P_Maharashtra_sale'] as $mh_p2p_data) {
                                $gross_amt += $mh_p2p_data[0]['gross_amt'];
                                $discount += ($mh_p2p_data[0]['gross_amt']!=0)?($mh_p2p_data[0]['gross_amt']-$mh_p2p_data[0]['net_amt']):"-";
                                $net_amt += $mh_p2p_data[0]['net_amt'];
                                $taxable_amt += $mh_p2p_data[0]['taxable_amt'];
                                $input_gst += $mh_p2p_data[0]['input_gst'];
                                $cgst += $mh_p2p_data[0]['cgst'];
                                $sgst += $mh_p2p_data[0]['sgst'];
                                $igst += $mh_p2p_data[0]['igst'];
                            ?>
                            <tr>
                            <td><b><?php echo $mh_p2p_data['tl']['description']; ?></b></td>
                            <td><?php echo number_format($mh_p2p_data[0]['gross_amt'],2); ?></td>
                            <td><?php echo ($mh_p2p_data[0]['gross_amt']!=0)?number_format(($mh_p2p_data[0]['gross_amt']-$mh_p2p_data[0]['net_amt']),2):"-"; ?></td>
                            <td><?php echo number_format($mh_p2p_data[0]['net_amt'],2); ?></td>
                            <td><?php echo number_format($mh_p2p_data[0]['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($mh_p2p_data[0]['input_gst'],2); ?></td>
                            <td><?php echo number_format($mh_p2p_data[0]['cgst'],2); ?></td>
                            <td><?php echo number_format($mh_p2p_data[0]['sgst'],2); ?></td>
                            <td><?php echo number_format($mh_p2p_data[0]['igst'],2); ?></td>
                            </tr>
                        <?php } ?>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($gross_amt,2);?></b></td>
                                <td><b><?php echo number_format($discount,2);?></b></td>
                                <td><b><?php echo number_format($net_amt,2);?></b></td>
                                <td><b><?php echo number_format($taxable_amt,2);?></b></td>
                                <td><b><?php echo number_format($input_gst,2);?></b></td>
                                <td><b><?php echo number_format($cgst,2);?></b></td>
                                <td><b><?php echo number_format($sgst,2);?></b></td>
                                <td><b><?php echo number_format($igst,2);?></b></td>
                            </tr>
                        
                           <?php }
                        if(isset($gst_data['P2P_Outside_sale']) && !empty($gst_data['P2P_Outside_sale']))
                        { ?>
                            <tr><td colspan="9"></td></tr>
                            <tr><td colspan="9"><b>P2P Non Maharashtra</b></td></tr>
                            <tr>   
                            <th></th>
                            <th>Sale</th>            
                            <th>Discount-Dist</th>
                            <th>Net Amount</th>
                            <th>Taxable</th>
                            <th>Input @18%</th>
                            <th>CGST</th>            
                            <th>SGST</th>            
                            <th>IGST</th>            
                            </tr>
                            <?php foreach ($gst_data['P2P_Outside_sale'] as $nm_p2p_data) {
                                $gross_amt2 += $nm_p2p_data[0]['gross_amt'];
                                $discount2 += ($nm_p2p_data[0]['gross_amt']!=0)?($nm_p2p_data[0]['gross_amt']-$nm_p2p_data[0]['net_amt']):"-";
                                $net_amt2 += $nm_p2p_data[0]['net_amt'];
                                $taxable_amt2 += $nm_p2p_data[0]['taxable_amt'];
                                $input_gst2 += $nm_p2p_data[0]['input_gst'];
                                $cgst2 += $nm_p2p_data[0]['cgst'];
                                $sgst2 += $nm_p2p_data[0]['sgst'];
                                $igst2 += $nm_p2p_data[0]['igst'];
                            ?>
                            <tr>
                            <td><b><?php echo $nm_p2p_data['tl']['description']; ?></b></td>
                            <td><?php echo number_format($nm_p2p_data[0]['gross_amt'],2); ?></td>
                            <td><?php echo ($nm_p2p_data[0]['gross_amt']!=0)?number_format(($nm_p2p_data[0]['gross_amt']-$nm_p2p_data[0]['net_amt']),2):"-"; ?></td>
                            <td><?php echo number_format($nm_p2p_data[0]['net_amt'],2); ?></td>
                            <td><?php echo number_format($nm_p2p_data[0]['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($nm_p2p_data[0]['input_gst'],2); ?></td>
                            <td><?php echo number_format($nm_p2p_data[0]['cgst'],2); ?></td>
                            <td><?php echo number_format($nm_p2p_data[0]['sgst'],2); ?></td>
                            <td><?php echo number_format($nm_p2p_data[0]['igst'],2); ?></td>
                            </tr>
                        <?php }
                        ?>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($gross_amt2,2);?></b></td>
                                <td><b><?php echo number_format($discount2,2);?></b></td>
                                <td><b><?php echo number_format($net_amt2,2);?></b></td>
                                <td><b><?php echo number_format($taxable_amt2,2);?></b></td>
                                <td><b><?php echo number_format($input_gst2,2);?></b></td>
                                <td><b><?php echo number_format($cgst2,2);?></b></td>
                                <td><b><?php echo number_format($sgst2,2);?></b></td>
                                <td><b><?php echo number_format($igst2,2);?></b></td>
                            </tr>
                        
                           <?php }
                        if(isset($gst_data['P2A_Maharashtra_sale']) && !empty($gst_data['P2A_Maharashtra_sale']))
                        {?>
                            <tr><td colspan="9"></td></tr>
                            <tr><td colspan="9"><b>P2A Maharashtra</b></td></tr>
                            <tr>   
                            <th></th>
                            <th>Sale</th>            
                            <th>Discount-Dist</th>
                            <th>Net Amount</th>
                            <th>Taxable</th>
                            <th>Input @18%</th>
                            <th>CGST</th>            
                            <th>SGST</th>            
                            <th>IGST</th>            
                            </tr>
                           <?php foreach ($gst_data['P2A_Maharashtra_sale'] as $mh_p2a_data) {
                                $gross_amt3 += $mh_p2a_data[0]['gross_amt'];
                                $discount3 += ($mh_p2a_data[0]['gross_amt']!=0)?($mh_p2a_data[0]['gross_amt']-$mh_p2a_data[0]['net_amt']):"-";
                                $net_amt3 += $mh_p2a_data[0]['net_amt'];
                                $taxable_amt3 += $mh_p2a_data[0]['taxable_amt'];
                                $input_gst3 += $mh_p2a_data[0]['input_gst'];
                                $cgst3 += $mh_p2a_data[0]['cgst'];
                                $sgst3 += $mh_p2a_data[0]['sgst'];
                                $igst3 += $mh_p2a_data[0]['igst'];
                            ?>
                            <tr>
                            <td><b><?php echo $mh_p2a_data['tl']['description']; ?></b></td>
                            <td><?php echo number_format($mh_p2a_data[0]['gross_amt'],2); ?></td>
                            <td><?php echo ($mh_p2a_data[0]['gross_amt']!=0)?number_format(($mh_p2a_data[0]['gross_amt']-$mh_p2a_data[0]['net_amt']),2):"-"; ?></td>
                            <td><?php echo number_format($mh_p2a_data[0]['net_amt'],2); ?></td>
                            <td><?php echo number_format($mh_p2a_data[0]['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($mh_p2a_data[0]['input_gst'],2); ?></td>
                            <td><?php echo number_format($mh_p2a_data[0]['cgst'],2); ?></td>
                            <td><?php echo number_format($mh_p2a_data[0]['sgst'],2); ?></td>
                            <td><?php echo number_format($mh_p2a_data[0]['igst'],2); ?></td>
                            </tr>
                        <?php }
                        ?>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($gross_amt3,2);?></b></td>
                                <td><b><?php echo number_format($discount3,2);?></b></td>
                                <td><b><?php echo number_format($net_amt3,2);?></b></td>
                                <td><b><?php echo number_format($taxable_amt3,2);?></b></td>
                                <td><b><?php echo number_format($input_gst3,2);?></b></td>
                                <td><b><?php echo number_format($cgst3,2);?></b></td>
                                <td><b><?php echo number_format($sgst3,2);?></b></td>
                                <td><b><?php echo number_format($igst3,2);?></b></td>
                            </tr>
                        
                           <?php }
                        if(isset($gst_data['P2A_Outside_sale']) && !empty($gst_data['P2A_Outside_sale']))
                        { ?>
                            <tr><td colspan="9"></td></tr>
                            <tr><td colspan="9"><b>P2A Non Maharashtra</b></td></tr>
                            <tr>   
                            <th></th>
                            <th>Sale</th>            
                            <th>Discount-Dist</th>
                            <th>Net Amount</th>
                            <th>Taxable</th>
                            <th>Input @18%</th>
                            <th>CGST</th>            
                            <th>SGST</th>            
                            <th>IGST</th>            
                            </tr>
                            <?php foreach ($gst_data['P2A_Outside_sale'] as $nm_p2a_data) {
                                $gross_amt4 += $nm_p2a_data[0]['gross_amt'];
                                $discount4 += ($nm_p2a_data[0]['gross_amt']!=0)?($nm_p2a_data[0]['gross_amt']-$nm_p2a_data[0]['net_amt']):"-";
                                $net_amt4 += $nm_p2a_data[0]['net_amt'];
                                $taxable_amt4 += $nm_p2a_data[0]['taxable_amt'];
                                $input_gst4 += $nm_p2a_data[0]['input_gst'];
                                $cgst4 += $nm_p2a_data[0]['cgst'];
                                $sgst4 += $nm_p2a_data[0]['sgst'];
                                $igst4 += $nm_p2a_data[0]['igst'];
                            ?>
                            <tr>
                            <td><b><?php echo $nm_p2a_data['tl']['description']; ?></b></td>
                            <td><?php echo number_format($nm_p2a_data[0]['gross_amt'],2); ?></td>
                            <td><?php echo ($nm_p2a_data[0]['gross_amt']!=0)?number_format(($nm_p2a_data[0]['gross_amt']-$nm_p2a_data[0]['net_amt']),2):"-"; ?></td>
                            <td><?php echo number_format($nm_p2a_data[0]['net_amt'],2); ?></td>
                            <td><?php echo number_format($nm_p2a_data[0]['taxable_amt'],2); ?></td>
                            <td><?php echo number_format($nm_p2a_data[0]['input_gst'],2); ?></td>
                            <td><?php echo number_format($nm_p2a_data[0]['cgst'],2); ?></td>
                            <td><?php echo number_format($nm_p2a_data[0]['sgst'],2); ?></td>
                            <td><?php echo number_format($nm_p2a_data[0]['igst'],2); ?></td>
                            </tr>
                        <?php }?>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($gross_amt4,2);?></b></td>
                                <td><b><?php echo number_format($discount4,2);?></b></td>
                                <td><b><?php echo number_format($net_amt4,2);?></b></td>
                                <td><b><?php echo number_format($taxable_amt4,2);?></b></td>
                                <td><b><?php echo number_format($input_gst4,2);?></b></td>
                                <td><b><?php echo number_format($cgst4,2);?></b></td>
                                <td><b><?php echo number_format($sgst4,2);?></b></td>
                                <td><b><?php echo number_format($igst4,2);?></b></td>
                            </tr>
                        
                           <?php }
                        
                   }
                   else
                   {
                       echo '<tr><td colspan="9"><b>No records found!</b></td></tr>';                       
                   }?>
            </tbody>
          
        </table>        
   </div>
  </form>
</div>
</div>
</div>
</div>
 
</body>
</html>
    
<?php }?>
