<?php if($page!='download'){?>
<html>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/invoice.js"></script>
<title>TDS Report</title>
</head>


<body>    
<div>
<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
  <div id="pageContent" style="min-height:500px;position:relative;">
  <div class="loginCont">
      <?php echo $this->element('shop_side_reports',array('side_tab' => 'tdsreport'));?>
   <div id="innerDiv">
       
       <!--<form method="post" id="tdsReport" action="/shops/getTDSReport/">-->
      <form method="post" id="tdsReport">
      <input type="hidden" name='download_tds' id ='download_tds' value="">
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
    
  <button class="btn btn-primary btn-xs <?php echo $pull_class; ?>" style=""  type="submit" id="filerbtn" onclick="getTDSReport()">Search</button>    
    <?php
    $pull_class = '';
    
    if( isset($tds_data) && !empty($tds_data) ){
//            $pull_class  = 'pull-right';
        ?>
        
        <button class="btn btn-success btn-xs" style=""  type="button" id="btndownload" onclick="downloadTDSReport()"><span class="glyphicon glyphicon-download" onclick="downloadZip()"></span>Download</button>
    <?php }?>
    </div>
    </form>
<h4><b>TDS Report</b></h4>
<table class="table table-bordered table-hover" style="width:900px;margin-top:20px;">            
            <thead>             
            <tr>   
            <th width="110px">Distributor Id</th>
            <th>Distributor Name </th>
            <th>Pan No</th>            
            <th>GST No</th>
            <th>Commission</th>
            <th>Incentive</th>
            <th>TDS</th>
            </tr>
            </thead>
            
            <tbody>
                
                <?php if(!empty($tds_data))
                   {
                        $total_commission = 0;
                        $total_tds = 0;
                       foreach($tds_data as $dist_id=>$data)  { 
                           $incentive = (isset($data['incentive']) && !empty($data['incentive']))?$data['incentive']:0;
                           $commission = (isset($data['commission']) && !empty($data['commission']))?($data['commission'] - $incentive):0;
                           $tds_amt = (isset($data['tds_amt']) && !empty($data['tds_amt']))?$data['tds_amt']:0;
                           $total_commission += $commission;
                           $total_incentive += $incentive;
                           $total_tds += $tds_amt;
                       ?>
                    <tr>
                        <td> <?php echo $data['dist_id'];?></td>
                        <td> <?php echo $data['dist_name'];?></td>
                        <td> <?php echo !empty($data['pan_number'])?$data['pan_number']:"-"; ?> </td>
                        <td> <?php echo !empty($data['dist_gst_no'])?$data['dist_gst_no']:"-"; ?> </td>
                        <td> <?php echo round($commission,2); ?> </td> 
                        <td> <?php echo round($incentive,2); ?> </td> 
                        <td> <?php echo round($tds_amt,2); ?> </td> 
                    </tr>                
                   
                   <?php } ?>     
                    <tr>
                        <td><b>Total</b></td>
                        <td><b></b></td>
                        <td><b></b></td>
                        <td><b></b></td>
                        <td><b><?php echo round($total_commission,2);?></b></td>
                        <td><b><?php echo round($total_incentive,2);?></b></td>
                        <td><b><?php echo round($total_tds,2);?></b></td>
                    </tr>
                   <?php }
                   else
                   {
                       echo '<tr><td colspan="6"><b>No records found!</b></td></tr>';
                       
                   }?>
            </tbody>
          
        </table>  
</div>
</div>
</div>
</div>
    
 
</body>
</html>
    
<?php }?>
