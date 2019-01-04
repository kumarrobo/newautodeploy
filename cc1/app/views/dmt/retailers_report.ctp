<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Retailer Panel</title>   
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
        <script type="boot/javascript" src="/src/js/footable.js"></script>
        <style>
            tfoot tr td {border:0px !important;}
        </style>
    </head>
    <form name="form" id = "form" method="POST">
        <div>
            <input type="hidden" class="form-control" id="dmt_from"   value ="<?php echo isset($_POST['dmt_from']) ? $_POST['dmt_from'] : ''; ?>" />
            <input type="hidden" class="form-control" id="dmt_till"  value="<?php echo isset($_POST['dmt_till']) ? $_POST['dmt_till'] : ''; ?>" /> 
        </div>
    </form>     
    <!-- for getting the rbl or eko name from url-->
    <?php $url1 = explode('/', $_SERVER['REQUEST_URI']);
    $banktype = $url1[3];
    ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li><a href="/dmt/index/<?php echo $banktype; ?>">Search</a></li>
                <li><a href="/dmt/dmtFromto/<?php echo $banktype; ?>" >All Transactions</a></li>
            </ul>
        </div>
    </nav>
    <body>
        <!-- Start your project here-->
        <form name="retailet" id = "retailer" method="POST">
            <!--<div class="wrapper retailer-main">-->
            <div class="wrapper">
                <div class="container">
                    <h1>Retailer Panel</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="from_date">From Date</label>
                            <input type="text" class="form-control" style='width: 380px;'   id="dmt_from" name="dmt_from" value="<?php if (!empty($retfrom)) echo $retfrom; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="till_date">Till Date</label>

                            <input type="text" class="form-control" style='width: 380px;'  id="dmt_till" name="dmt_till" value="<?php if (!empty($rettill)) echo $rettill; ?>">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary search">Search</button>
                    <?php $arr = array(1 => 'Mobile Store', 2 => 'Stationery Shop', 3 => 'Medical Store', 4 => 'Grocery Store', 5 => 'Photocopy Store', 6 => 'Travel Agency', 7 => 'Hardware Shop', 8 => 'Others'); ?>
                    <div class="retailer-details">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div class="pull-right"><b>Available Balance : &nbsp; <span class="pull-right"> <?php echo $retailerdet[0]['us']['balance']; ?> </b></span></div>
                                <table class="table table-bordered table-hover">                                  
                                    <tr>

                                        <td class="head">Name :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['name']; ?></td>
                                        <td class="head">Shopname :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['shopname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Mobile / User ID :</td>
                                        <td class="data" id='retrno' name ='retrno'><?php echo $retailerdet[0]['r']['mobile']; ?></td>
                                        <td class="head">Shop Type :</td>
                                        <td class="data"><?php echo $arr[$retailerdet[0]['r']['shop_type']]; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Alternate Mob No. :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['alternate_number']; ?></td>
                                        <td class="head">Location :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Email :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['email']; ?></td>
                                        <td class="head">Address :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <?php
                                        if ($dmt_data[0]['users_services']['kit_flag'] == 1 && $dmt_data[0]['users_services']['service_flag'] == 1) {
                                            $service_status = 'Active';
//for getting the user device Id
                                            $dmt_status = $dmt_data[0]['users_services']['params'];
                                        } else {
                                            $service_status = 'Deactive';
                                        }
                                        ?>
                                        <td class="head">Services Status :</td>
                                        <td class="data"><?php echo $service_status; ?>
                                        <td class="head">City / Status :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['area']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">DMT Activation Date:</td>
                                        <td class="data"><?php echo $dmt_data[0]['users_services']['created_on']; ?></td>

                                        <td class="head">CSP ID   :</td>
                                        <td class="data"><?php echo $dmtdeviceId; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Created Date :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['created']; ?></td>
                                        <td class="head">Device info  :</td>
                                        <td class="data"><?php
                                            if (isset($user_profile) && !empty($user_profile)) {
                                                echo $user_profile['device_type'] . "-" . $user_profile['manufacturer'] . "-" . $user_profile['version'];
                                            }
                                            ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Distributor :</td>
                                        <td class="data"><?php echo $retailerdet[0]['ds']['company']; ?></td>
                                        <td class="head">Salesman :</td>
                                        <td class="data"><?php echo $retailerdet[0]['ss']['name']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!--                        <span class="divider"></span>
                        <span class="amnt_trans_head">Amount Transfered to Retailer</span>-->

                        <!--
                        <table class="table demo table-bordered table-hover">
                        <tr>
                        <th>Source</th>
                        <th>Amount</th>
                        <th>Date</th>
                        </tr>
                        <tr>
                        <td>Salesman Amit Gupta</td>
                        <td>1000</td>
                        <td>21/03/2017</td>
                        </tr>
                        <tr>
                        <td>Self Limit Netbanking HDFC</td>
                        <td>1000</td>
                        <td>21/03/2017</td>
                        </tr>
                        </table>
                        
                        <div class="row">
                        <div class="col-md-2">
                        <span>Call Type : </span>
                        </div>
                        <div class="col-md-2">
                        <select class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        </select>
                        </div>
                        <div class="col-md-2">
                        <span>Tags : </span>
                        </div>
                        <div class="col-md-2">
                        <select class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        </select>
                        </div>
                        <div class="col-md-2">
                        <span>Comment: </span>
                        </div>
                        <div class="col-md-2">
                        <textarea class="form-control" rows="2"></textarea>
                        </div>
                        </div>-->
                        <!--                        <a href="javascript:;" class="btn btn-primary search">Submit</a> -->
                        <span class="amnt_trans_head"><h3>  Transaction History</h3></span> 

                        <table class="table demo table-bordered table-hover">
                            <tr>
                                <th>Index</th>
                                <th>Order ID</th>
                                <th>Bank Trans ID</th>
                                <th>Wallet ID</th>
                                <th>Sender Name / Mobile No.</th>
                                <th>Amount</th>
                                <th>Charges</th>                                    
                                <th>Opening</th>
                                <th>Closing</th>
                                <th>Status</th>
                                <th>Date time</th>
                                <th>Return Date Time</th>
                            </tr>

                            <tr>
                                <?php $i = 0; ?>
                                <?php foreach ($retailertrans as $trans) { ?>
                                <?php $i++;
                                $senderId = $trans['sendermob']; ?>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $trans['order_id']; ?></td>
                                    <td><?php echo $trans['bank_txn_id']; ?></td>
                                    <td><?php echo "<a style='font-size:Normal;' target='_blank' href='/dmt/transactionReport/$banktype/" . $trans['wallet_id'] . "'>" . $trans['wallet_id'] . "</a>"; ?></td>
                                    <td><?php echo "<a style='font-size:Normal; 'target='_blank' href='/dmt/sendersReport/$banktype/" . $senderId . "'>" . $trans['sendername'] . "</a> " . " /" . "\xA";
                                    echo "<a style='font-size:Normal; 'target=_blank' href='/dmt/sendersReport/$banktype/" . $senderId . "'>" . $trans['sendermob'] . "</a>";
                                    ?></td>
                                    <td><?php echo $trans['amount']; ?></td>
                                    <td><?php echo $trans['pay1charges']; ?></td>                                    
                                    <td><?php echo $retopening[$trans['wallet_id']]; ?></td>
                                    <td><?php echo $retclosing[$trans['wallet_id']]; ?></td>
                                <?php $rettxn_status = Configure::read('Remit_pay1_status'); ?>
                                    <td><?php echo $rettxn_status[$trans['status']]; ?></td>
                                    <td><?php echo $trans['date']; ?></td>
                                    <td><?php echo $trans['updated_at']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php
// For Printing the Total Amount
                            $retTot = array();
                            $rettotalAmt = array();
                            foreach ($retailertrans as $total) {
                                $retTot[] = $total['amount'];
                            }
                            $rettotalAmt[] = array_sum($retTot);
                            ?>
                            <tfoot>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> <div class="pull-right"><b>Total  :  </b></span></div> </td>
                                    <td> <b>    <?php echo implode(" ", $rettotalAmt); ?></b> </td>                                        
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $('.retailer-main input').datepicker({
                    autoclose: true
                });
                $('.retailer-main input').datepicker({
                    autoclose: true
                });
                jQuery(function ($) {
                    $('.table.demo').footable();
                });
            </script>
    </body>
</form>
</html>
<script>
// When the document is ready
    $(document).ready(function () {
        $('#dmt_from, #dmt_till').datepicker({
            format: "yyyy-mm-dd",
//startDate: "-365d",
            endDate: "1d",
            multidate: false,
            autoclose: true,
            todayHighlight: true
        });

    });
</script>            