<!DOCTYPE html>
<html>
    <head>
        <title>Transaction Panel</title>   
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">               
        <script src="/boot/js/jquery-3.1.1.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">               
        <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
        <script type="boot/javascript" src="/src/js/footable.js"></script>
        <style>
            tfoot tr td {border:0px !important;}
        </style>
    </head>
    <body>
    <title>Transaction Panel</title>
    <nav class="navbar navbar-default">
      <div class="container-fluid">        
        <ul class="nav navbar-nav">
          <li><a href="/travel/index">Search</a></li>
          <li><a href="/travel/travelFromTo">All Transactions</a></li>
        </ul>
      </div>
    </nav>    
    <div class="wrapper trans-detail-main">
        <div class="container-fluid">
            <h1>Transaction Detail Screen</h1>
            <div class="row">
                <div class="col-md-12 trans-details">
                    <span class="amnt_trans_head">Transaction Details</span>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <th>Retailer Id</th>
                            <th>Pay1 Travel ID</th>
                            <th>Shop Trans ID</th>
                            <th>Vendor Trans ID</th>
                            <th>Vendor Name</th>
                            <th>PNR No </th>
                            <th>Passenger Count</th>
                            <th>Booking / Refund Amount</th>
                            <th>Mark up</th>
                            <th>Comm</th>                        
                            <th>Charges</th>
                            <th>TDS</th>
                            <th>GST</th>
                            <th>Date Time</th>
                            <th>Refund Date/Time</th>
                            <th>Service Name</th>
                            <th>Service Status</th>
                            
                            </thead>
                            <tbody>
                                <tr> <?php foreach ($pay1txnData as $txnData) { ?>                                                               
                                   <td> <?php echo $txnData['retId'];?> </td>
                                   <td><?php echo $txnData['pay1_txn_id']; ?></td>                    
                                    <td><?php echo $txnData['shop_txn_id']; ?></td>
                                    <td><?php echo $txnData['vendor_txn_id']; ?></td>
                                    <td><?php echo $txnData['vendor']; ?></td>
                                    <td><?php echo $txnData['pnr']; ?></td>
                                    <td><?php echo ($txnData['status'] == '6')?$txnData['cancel']:$txnData['pass']; ?></td>
                                    <td><?php echo floor($txnData['amount']); ?></td>                                        
                                    <td><?php echo $txnData['mark_up']; ?></td>
                                    <td><?php echo isset($txnData['comm'])?$txnData['comm']:0;?></td>                                                                                                     
                                    <td><?php echo isset($txnData['charges'])?$txnData['charges']:0;?></td>                                    
                                    <td><?php echo isset($txnData['tds'])?$txnData['tds']:0; ?></td>
                                    <td><?php echo isset($txnData['gst'])?$txnData['gst']:0;?></td>
                                    <td><?php echo $txnData['tdate']; ?></td>
                                    <td><?php if(($txnData['status'] == '5') || ($txnData['status'] == '6')) { echo $txnData['update']; } else { echo 'NA';}?></td>
                                    <td><?php echo $txnData['service_id']; ?></td>                                    
                                    <?php $pay1txn_status = Configure::read('Travel_pay1_status'); ?>
                                    <td><?php echo $pay1txn_status[$txnData['status']]; ?></td>                                          
                                </tr>                                
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>


