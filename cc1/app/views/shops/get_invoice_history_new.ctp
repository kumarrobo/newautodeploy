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
</head>


<body>    
<div>
<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
  <div id="pageContent" style="min-height:500px;position:relative;">
  <div class="loginCont">
      <?php echo $this->element('shop_side_reports',array('side_tab' => 'invoicenew'));?>
   <div id="innerDiv">
      <form method="post" id="newinvoicehistory">
      <input type="hidden" name='download' id ='download' value="">
      <input type="hidden" name='invoice_ids' id ='invoice_ids' value='<?php echo json_encode($invoice_ids);?>'>
  <!--<div class="row">-->
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
                <span style="font-weight:bold;margin-right:10px;">Invoice Type: </span>
                <select name="inv_type" id="inv_type">    
                    <!--<option value="" <?php if(empty($this->params['form']['inv_type'])){ echo "selected"; }?>>All</option>-->
                    <option value="0" <?php if(!empty($this->params['form']['inv_type']) && $this->params['form']['inv_type']==0){ echo "selected"; }?>>Raised by Pay1</option>
                    <option value="1" <?php if($this->params['form']['inv_type']==1){ echo "selected"; }?>>Raised by Distributor</option>
                 <?php // foreach ($inv_type as $i => $value) { 
 //                      if($i == $type) { $sel = 'selected'; }
 //                      echo "<option value= '$i' $sel>".$value."</option>";
 //                      $sel = ''; } ?>
                </select>
               <!--</div>-->

<!--           <div class="col-md-2" style="margin:30px;">     
                <button  type="Submit" id = "filerbtn" name = "filterbtn" class="btn btn-primary search">Search</button>  
           </div>    -->
<!--      <div  class="pull-right" style="margin:45px;">
              <button  type="Submit" id = "sendall" name = "sendall" class="btn btn-primary search">Send all</button>  
              <button  type="Submit" id = "mailall" name = "mailall" class="btn btn-primary search">Mail all</button>  
      </div>-->
<!--<div class="col-lg-3">-->
<!--<div class="form-group">-->
    <!--<div class="col-md-4">-->
    
    <?php
    $pull_class = '';
    if( isset($invoicedata) && !empty($invoicedata) ){
            $pull_class  = 'pull-right';
        ?>
        <button class="btn btn-danger btn-xs pull-right" style="margin-right:50px"  type="button" id="btnsendmail" onclick='mailcheck(<?php echo $user_id ; ?>,<?php echo json_encode($invoice_ids); ?>,<?php echo $month; ?>,<?php echo $year;?>,2,1)'><span class="fa fa-envelope"></span>Mail</button>
        <button class="btn btn-success btn-xs pull-right" style="margin-right:10px"  type="button" id="btndownload" onclick="downloadZip()"><span class="glyphicon glyphicon-download" onclick="downloadZip()"></span>Download</button>
    <?php }?>
    <button class="btn btn-primary btn-xs <?php echo $pull_class; ?>" style="margin-right:10px"  type="submit" id="filerbtn" onclick="getInvoiceList()">Search</button>    
    </div>
<!--</div>-->
<!--</div>-->
  <!--</div>-->
<table class="table table-bordered table-hover" style="width:900px;margin-top:50px;">            
            <thead>             
            <tr>   
            <th>Date</th>
            <th>Invoice No</th>            
            <th>Supplier Name</th>
            <th>Consignee Name</th>
            <!--<th>Total Amount</th>-->
            <th>Amount</th>
            <th>Invoice</th>            
            </tr>
            </thead>
            
            <tbody>
                
                <?php if(!empty($invoicedata))
                   {
                       foreach($invoicedata as $invdata)  { 
                        $date=$invdata['invoice_date'];
                        $month1 = str_pad($invdata['month'],2, "0", STR_PAD_LEFT);
                        $fiscalyear=substr($date,5,2)< 4?((substr($invdata['year'],2,2)-1)."-".(substr($invdata['year'],2,2))):((substr($invdata['year'],2,2))."-".(substr($invdata['year'],2,2)+1));
                       ?>
                <tr>
                    <td> <?php echo $invdata['invoice_date'];?></td>
                    <td> <?php echo "PAY1/".$fiscalyear."/".$month1."/".$invdata['invoice_id']; ?> </td>
                    <td> <?php echo $invdata['source_name']; ?> </td>
                    <td> <?php echo $invdata['target_name']; ?> </td>
                    <!--<td> <?php // echo $invdata['total_amt']; ?> </td>--> 
                    <td> <?php echo $invdata['payable_amt']; ?> </td> 
                    <td> 
                        <a  target='_blank' href="/shops/getNewInvoice/<?php echo $user_id ; ?>/<?php echo $invdata['invoice_id']; ?>/<?php echo $month; ?>/<?php echo $year;?>/0"> View </a>
                        <a href="/shops/getNewInvoice/<?php echo $user_id ; ?>/<?php echo $invdata['invoice_id']; ?>/<?php echo $month; ?>/<?php echo $year;?>/<?php echo $mailid;?>/1">Download</a>                                           
                        <!--<a target="_blank" onclick="return mailcheck();"  href="/shops/getNewInvoice/<?php echo $user_id ; ?>/<?php echo $invdata['invoice_id']; ?>/<?php echo $month; ?>/<?php echo $year;?>/2" >Mail</a>--> 
                        <a onclick="mailcheck('<?php echo $user_id ; ?>','<?php echo $invdata['invoice_id']; ?>','<?php echo $month; ?>','<?php echo $year;?>',2,2)"  href="javascript:void(0);" id="sendmail_link_<?php echo $invdata['invoice_id'];?>">Mail</a> 
                        <!--<button type="button"  class="btn btn-link" onclick="mailcheck();">Mail</button>-->
                    </td>
                   </tr>                
                   
                   <?php }                   
                   }
                   else
                   {
                       echo '<tr><td colspan="6"><b>No records found!</b></td></tr>';
                       
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
