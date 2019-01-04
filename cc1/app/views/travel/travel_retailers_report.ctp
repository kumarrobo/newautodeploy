<!DOCTYPE html>
<html>
    <head>
        <title>Retailer Panel</title>   
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
    <form name="form" id = "form" method="POST">
        <div>
            <input type="hidden" class="form-control" id="travel_from"   value ="<?php echo isset($_POST['travel_from']) ? $_POST['travel_from'] : ''; ?>" />
            <input type="hidden" class="form-control" id="travel_till"  value="<?php echo isset($_POST['travel_till']) ? $_POST['travel_till'] : ''; ?>" /> 
        </div>
    </form>     

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li><a href="/travel/index">Search</a></li>
                <li><a href="/travel/travelFromTo" >All Transactions</a></li>
            </ul>
        </div>
    </nav>
    <body>        
        <form name="retailetab" id = "retailertab" method="POST">
            <!--<div class="wrapper retailer-main">-->
            <div class="wrapper">
                <div class="container">
                    <h1>Retailer Panel</h1>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="from_date">From Date</label>
                            <input type="text" class="form-control"    id="travel_from" name="travel_from" value="<?php if (!empty($retfrom)) echo $retfrom; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="till_date">Till Date</label>

                            <input type="text" class="form-control"   id="travel_till" name="travel_till" value="<?php if (!empty($rettill)) echo $rettill; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="ret_service">Service</label>
                            <select class="form-control" id="ret_service" name="ret_service" >
                                <option value=""> ALL</option>           
                               <?php foreach ($services as $s): ?>
                                <option value="<?php echo $s['services']['id']; ?>" <?php if ($s['services']['id'] == $service) echo "selected" ?> >
                                    <?php echo $s['services']['name'] ?>
                                </option>
                    <?php endforeach ?>
                            </select>
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
                                        <td class="data"><?php echo $ret_imp[$retailerdet[0]['r']['id']]['imp']['name']; ?></td>
                                        <td class="head">Shopname :</td>
                                        <td class="data"><?php echo $ret_imp[$retailerdet[0]['r']['id']]['imp']['shop_est_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Mobile / User ID :</td>
                                        <td class="data" id='retrno' name ='retrno'><?php echo $ret_imp[$retailerdet[0]['r']['id']]['ret']['mobile']; ?><?php echo ' / '. $retailerdet[0]['r']['user_id']; ?></td>
                                        <td class="head">Shop Type :</td>
                                        <td class="data"><?php echo $retailerdet[0]['r']['shop_type_value']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Alternate Mob No. :</td>
                                        <td class="data"><?php echo isset($ret_imp[$retailerdet[0]['r']['id']]['imp']['alternate_mobile_no'])?$ret_imp[$retailerdet[0]['r']['id']]['imp']['alternate_mobile_no']:$retailerdet[0]['r']['alternate_number']; ?></td>
                                        <td class="head">Location :</td>
                                        <td class="data"><?php echo $objShop->location_typeTypes($ret_imp[$retailerdet[0]['r']['id']]['imp']['location_type']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Email :</td>
                                        <td class="data"><?php echo $ret_imp[$retailerdet[0]['r']['id']]['imp']['email_id']; ?></td>
                                        <td class="head">Address :</td>
                                        <td class="data"><?php echo $ret_imp[$retailerdet[0]['r']['id']]['imp']['address'] . " - " . $ret_imp[$retailerdet[0]['r']['id']]['ret']['pin']  ?></td>
                                    </tr>
                                    <tr>            
                                        <?php
                                        if ($travel_data[0]['users_services']['kit_flag'] == 1) {
                                            $service_status = 'Active';
//for getting the user device Id
                                            $travel_status = $travel_data[0]['users_services']['params'];
                                        } else {
                                            $service_status = 'Deactive';
                                        }
                                        ?>
                                        <td class="head">Services Status :</td>
                                        <td class="data"><?php echo $service_status; ?>
                                        <td class="head">City  :</td>
                                        <td class="data"><?php echo $ret_imp[$retailerdet[0]['r']['id']]['ret']['area']; ?><?php // echo ' / ' . $ret_imp[$retailerdet[0]['r']['id']]['ret']['shop_city']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Activation Date:</td>
                                        <td class="data"><?php echo $travel_data[0]['users_services']['created_on']; ?></td>
                                        <td class="head">Salesman :</td>
                                        <td class="data"><?php echo $salesman[$ret_imp[$retailerdet[0]['r']['id']]['ret']['salesman']]; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="head">Created Date :</td>
                                        <td class="data"><?php echo $ret_imp[$retailerdet[0]['r']['id']]['ret']['date_created']; ?></td>
                                        <td class="head">Distributor :</td>
                                        <td class="data"><?php echo $retailerdet[0]['ds']['company']; ?></td>
                                    </tr>
                                                                        <tr>
                                        <td class="head">Plan Name :</td>
                                        <td class="data"><?php echo $service_plans[$travel_data[0]['users_services']['service_plan_id']]; ?></td>
                                        <td class="head"> </td>
                                        <td class="data"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <span class="amnt_trans_head"><h3>  Transaction History</h3></span> 
                        <table class="table  table-bordered table-hover">
                            <tr>
                                <th>Index</th>
                                <th>Shop Txn Id</th>
                                <th>Pay1 Travel ID</th>
                                <th>PNR No.</th>
                                <th>Passenger Count</th>
                                <th>Booking/Refunded Amount</th>
                                <th>Markup</th>
                                <th>Comm</th>                        
                                <th>Charges</th>
                                <th>TDS</th>
                                <th>GST</th>
                                <th>Date Time</th>                                
                                <th>Refund Processed Date Time</th>     
                                <th>Service Name</th>     
                                <th>Service Status</th>     
                                
                            </tr>

                            <tr>
                                <?php $i = 0; ?>
                                <?php foreach ($retailertrans as $trans) { ?>
                                <?php $i++; ?>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $trans['shop_txn_id']; ?></td>
                                    <td><?php echo $trans['pay1_txn_id']; ?></td>
                                    <td><?php echo $trans['pnr']; ?></td>
                                    <td><?php echo ($trans['status'] == '6')?$trans['cancel']:$trans['pass']; ?></td>
                                    <td><?php echo floor($trans['amount']); ?></td>
                                    <td><?php echo $trans['mark_up']; ?></td>
                                    <td><?php echo isset($trans['comm'])?$trans['comm']:0; ?></td>                                    
                                    <td><?php echo isset($trans['charges'])?$trans['charges']:0; ?></td>
                                    <td><?php echo isset($trans['tds'])?$trans['tds']:0; ?></td>                                    
                                    <td><?php echo isset($trans['gst'])?$trans['gst']:0; ?></td>
                                    <td><?php echo $trans['tdate']; ?></td>
                                    <td><?php if(($trans['status'] == '5') || ($trans['status'] == '6')) { echo $trans['update']; } else { echo 'NA';}?></td>                                     
                                    <td><?php echo $trans['service']; ?></td>
                                <?php $rettxn_status = Configure::read('Travel_pay1_status'); ?>
                                    <td><?php echo $rettxn_status[$trans['status']]; ?></td>
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
    </body>
</form>
</html>
<script>
// When the document is ready
    $(document).ready(function () {
        $('#travel_from, #travel_till').datepicker({
            format: "yyyy-mm-dd",
//startDate: "-365d",
            endDate: "1d",
            multidate: false,
            autoclose: true,
            todayHighlight: true
        });

    });
</script>            