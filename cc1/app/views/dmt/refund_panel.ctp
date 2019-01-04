<html>
    <head>
        <title>Refund Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">        
        <link  rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">        
        <link rel="stylesheet" href="/boot/css/dmt.css">
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/dmt.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-4.1.0.min.js"></script>          
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

        
        <style>
        .table th{
            align-self: center;
            background: #93E5DD;
        }
        .btn.btn-primary.search {
            margin: 24px;
        }        
        </style>

    </head>
    <body>
        <h2> Refund Panel</h2>        
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="/dmt/dmtFromto/ekonew" >All Transactions</a></li>
                    <li><a  href="/dmt/dmtAdminPanel" >Notification Panel</a></li>                    
                    <li><a href="/dmt/serviceToggle">Service Panel</a></li>
                    <li class="active"><a href="/dmt/refundPanel">Refund Panel</a></li>
                </ul>
            </div>
        </nav>                
         <div class="container-fluid">
            <form id="servinceTogForm" name="serviceTogForm" method="POST">
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover" >                          
                                <thead>
                                <!--<th>Retailer Mobile No./Shop Name</th>-->
                                <th>Order Id</th>                                    
                                <th>Eko Trans Id</th>
                                <th>Wallet Id</th>
                                <?php if($banktype == 'ekonew'){ ?>
                                    <th>Vendor</th>
                                <?php }?>
                                <th>Sender Name/Mobile No.</th>
                                <th>Beneficiary Acc No.</th>
                                <th>Mode</th>
                                <th>Amount</th>
                                <th>Pay1 Status</th>
                                <th>Bank Status</th>                                    
                                <th>Group Id</th>  
                                <th>Type</th>
                                <th>Transaction Date </th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Action</th>
                                </thead>
                                <tbody>

                                    <?php foreach($result as $data) { ?>
                                        <tr>
                                            <?php
                                            $retNo = $data['ret_id'];
                                            $retName = $ret_array[$data['ret_id']];
                                            ?>
<!--                                            <td><?php
                                                echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retNo . "'>" . $ret_arrayMobid[$data['ret_id']] . "</a> " . " /" . "\xA";
                                                echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retNo . "'>" . $retName . "</a> ";
                                                ?></td> -->
                                            <?php if ($data['pay1_status'] == '1') { ?>
                                                <td><a href='javascript:dmtcheck(<?php echo $data['order_id']; ?>,<?php echo $data['mobile']; ?>)'><?php echo $data['order_id']; ?></a></td>
                                            <?php } else { ?>
                                                <td><?php echo $data['order_id']; ?></td>              
                                            <?php } ?>
                                            <td><?php echo $data['bank_txn_id']; ?></td>        
                                            <td><?php echo "<a style='font-size:Normal;' target='_blank' href='/dmt/transactionReport/" . $banktype . '/' . $data['wallet_id'] . "'>" . $data['wallet_id'] . " </a>"; ?></td>
                                             <?php if($banktype == 'ekonew'){ ?>
                                            <td><?php echo $data['vendor']; ?></td> 
                                            <?php } ?>
                                            <?php
                                            $sendNo = $data['send_mob'];
                                            $sendName = $data['send_name'];
                                            ?>
                                            <td><?php
                                                echo "<a style='font-size:Normal;' target='_blank' href='/dmt/sendersReport/$banktype/" . $sendNo . "'/0'>" . $data['send_mob'] . "</a> " . " /" . "\xA";
                                                echo "<a style='font-size:Normal;' target='_blank' href='/dmt/sendersReport/$banktype/" . $sendNo . "'/0'>" . $data['send_name'] . "</a> ";
                                                ?></td>
                                            <td><?php echo $data['bene_accntno']; ?></td>
                                            <td><?php echo ($data['trans_type'] == 1) ? 'NEFT' : 'IMPS'; ?></td>
                                            <td><?php echo $data['amount']; ?></td>
                                            <?php $pay1txn_status = Configure::read('Remit_pay1_status'); ?>
                                            <td><?php echo $pay1txn_status[$data['pay1_status']]; ?></td>                                            
                                            <?php $banktxn_status = Configure::read('Remit_bank_status.eko') ?>                                            
                                            <td><?php echo $banktxn_status[$data['bank_status']]; ?></td>                                            
                                            <td><?php echo $data['group_id']; ?></td>
                                            <?php $transbifurcation = array(1 => 'Web', 0 => 'App'); ?>
                                            <td><?php echo $transbifurcation[$data['type']]; ?></td>
                                            <td><?php echo $data['date']; ?></td>
                                            <td><?php echo $data['created_at']; ?></td>
                                            <td><?php echo $data['updated_at']; ?></td>
                                            <td> <button type ="button" id="dmt_Refund_<?php echo $data['id']?>" name="dmt_Refund" onclick="dmtCTMORefund(<?php echo $data['order_id']; ?>)" > Check Refund</button> </td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <!--For gett`ing Sum of Amt-->
                                    <?php
                                    foreach ($result as $repo) {
                                        $totAmt[] = $repo['gross_amount'];
                                    }
                                    ?>
                                    <?php $amount[] = array_sum($totAmt); ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td> <div class="pull-right"><b>Total  :  </b></span></div> </td>
                                        <td><b><?php echo implode(" ", $amount); ?></</td>
                                    </tr>
                                </tfoot>
                   </table>
        </div>
            </form>
         </div>
    </body>
</html>

<script>

   //Refund Panel 
   
    function dmtCTMORefund(txn_id){        
        $.ajax({
            type: "POST",
            url: '/dmt/refundPanel',
            dataType: "json",
            data: {id: txn_id},
            success: function (data) {                
                console.log(data);
                if (data.status == 'success') {
                    alert(data.description);
                } else {
                    alert(data.description);
                }
           //     location.reload();

            },
            failure: function (data) {
                alert('Something Went Wrong');
            }
        });       
    }
    
</script>    