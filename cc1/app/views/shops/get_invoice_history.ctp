<?php if($page!='download' && $page!='pdfdownload'):?>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/invoice.js"></script>
<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
                <?php echo $this->element('shop_side_reports',array('side_tab' => 'invoice'));?>
                <div id="innerDiv">
                    <form method="post" id="invoicehistory">
                        <input type="hidden" name='download' id ='download' value="">
                        <fieldset style="padding:0px;border:0px;margin:0px;">
                            <div>
                                <span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10"  id="fromDate" name="fromDate" value="<?php echo isset($fromdate)?$fromdate:date('Y-m-d');?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10"  id="toDate" name="toDate" value="<?php echo isset($todate)?$todate:date('Y-m-d');?>">
                                <input class="btn btn-primary" type="button" value="Submit" onclick="setAction();">
                                <button value="" onclick="exportdata();" ><i class="fa fa-file-excel-o" style="color:green"></i></button>
                                <button value="" onclick="exportPdfData();" ><i class="fa fa-file-pdf-o" style="color:red"></i></button>
                            </div>
                        </fieldset>

                    <?php
                        if(!empty($invoicedata)):
                            $topup=0;$discount=0;$gross_sum=0;
                    ?>
                    <div>
                        <h4>BILL TO : <?php echo $dist_name; ?></h4>
                       <h4>History for date : ( <?php echo $this->params['form']['fromDate']." - ".$this->params['form']['toDate']; ?> )</h4>
                           <table class="table table-bordered table-hover" style="width:900px">
                               <thead>
                                   <tr>
                                       <th   class="alignCenter">Date</th>
                                       <th   class="alignCenter">Invoice No</th>
                                       <th   class="alignCenter">Gross Amount</th>
                                       <th   class="alignCenter">Discount</th>
                                       <th   class="alignCenter">Net Amount</th>                                       
                                       <th   class="alignCenter">Invoice</th>                                       
                                   </tr>
                               </thead>
                               
                               <tbody>
                                   <?php foreach($invoicedata as $invoice):
                                       $fiscalyear=$invoice[0]['month']<4?($invoice[0]['year']-1)."-".$invoice[0]['year']:($invoice[0]['year'])."-".($invoice[0]['year']+1);
                                       $totalsale=$invoice['inv']['invoice_date']>='2017-04-01'?$invoice['inv']['topup_buy']:$invoice['inv']['topup_buy']+$invoice['inv']['earning'];$earning=$invoice['inv']['earning'];$net_amt=$invoice['inv']['invoice_date']>='2017-04-01'?ceil($totalsale-$earning):ceil($invoice['inv']['topup_buy']);
                                       $topup+=$invoice['inv']['topup_buy'];$gross_sum+=$totalsale;$discount+=$earning;$gross_amt+=$net_amt;                                   
                                   ?>
                                   <tr class="alignCenter">
                                       <td><?php echo date("d-m-Y",strtotime($invoice['inv']['invoice_date'])); ?></td>                                        
                                       <td><?php echo "PAY1/".$fiscalyear."/".$invoice['inv']['invoice_id']; ?></td>
                                       <td><?php echo $totalsale; ?></td>
                                       <td><?php echo $earning; ?></td>
                                       <td><?php echo $net_amt; ?></td>   
                                       <td><?php if($invoice['inv']['invoice_date']!=date('Y-m-d')): ?><a href="/shops/getInvoiceData/<?php echo $invoice['inv']['invoice_id']; ?>/<?php echo $invoice['inv']['yearmonth_id']; ?>">Get Invoice</a><?php else: echo "NA"; endif; ?></td>   
                                   </tr>                                   
                                   <?php endforeach;  ?>    
                                   <tr class="alignCenter appTitle">
                                       <td>Total</td>
                                       <td></td>
                                       <td><?php echo $gross_sum;?></td>
                                       <td><?php echo $discount;?></td>
                                       <td><?php echo $gross_amt;?></td>
                                       <td></td>
                                   </tr>
                               </tbody>
                           </table>
                    </div>
                <?php endif;?>
                <?php if(isset($fromdate) && isset($todate) && empty($invoicedata)): echo "No records found"; endif;?>
                    </form>
                </div>
            </div>
    </div>
</div>

<?php elseif ($page=='pdfdownload'):
     echo $this->element('invoiceSummary');
 endif;?>

