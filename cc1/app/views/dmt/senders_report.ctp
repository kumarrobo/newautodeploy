<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Sender Panel</title>
        <!-- Font Awesome -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="/boot/css/reset.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/css/tether.min.css" rel="stylesheet">
        <link href="/boot/css/bootstrap.min.css" rel="stylesheet">
        <link href="/boot/css/bootstrap-datepicker3.min.css" rel="stylesheet">
        <link href="/boot/css/footable.bootstrap.css" rel="stylesheet">

        <!-- Your custom styles (optional) -->
        <link href="/src/css/style.css" rel="stylesheet">
    </head>

    <!-- for getting the rbl or eko name from url-->
    <?php
    $url1 = explode('/', $_SERVER['REQUEST_URI']);
    $banktype = $url1[3];
    ?>
    <body>
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

        <!-- Start your project here-->
        <div class="wrapper sender-main">
            <div class="container-fluid">
                <h1>Sender Panel</h1> 
                <div class="row">
                    <div class="col-md-6 sender-details">
                        <span class="amnt_trans_head"><b> Sender Details </b></span>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td class="head">Name :</td>
                                    <td class="data" ><?php echo $senderData[0]['sender_name']; ?></td>
                                </tr>
                                <tr>
                                    <td class="head">Contact No :</td>
                                    <td class="data"><?php echo $senderData[0]['sender_mob']; ?></td>
                                </tr>
                                <!--                                <tr>
                                <td class="head">Limit :</td>
                                <td class="data">10000 / 25000 (Month)</td>
                                </tr>-->
                            </table>
                        </div>
                    </div>


                    <div class="col-md-6 benef-details">
                        <span class="amnt_trans_head"><b>All Beneficiaries</b></span>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <th>Beneficiary Name</th>
                                <th>Beneficiary Account Number</th>
                                </thead>                                
                                <tbody>
                                    <?php foreach ($beneArray as $beneficiary) {
                                        ?>
                                        <tr>
                                            <td id="benname"><?php echo $beneficiary['benename']; ?><a data-hover="dropdown" onclick="filldata(<?php echo $beneficiary['bene_id']; ?>,<?php echo "'$banktype'"; ?>)" data-close-others="true" data-toggle="modal" class="dropdown-toggle active" href="#addClientPop"> More Details <span class="arrow"></span></a></td>
                                            <td><?php echo $beneficiary['beneacc']; ?></td>
                                        </tr>

<?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <script>

                    function filldata(id, type) {
                        $.ajax({
                            type: "POST",
                            url: '/dmt/beneficiaryData',
                            dataType: "json",
                            data: {benid: id, banktype: type},
                            success: function (data) {
                                $('#benfname').val((data['name']));
                                $('#benfacc').val((data['accno']));
                                $('#benfmob').val((data['mob']));
                                $('#benfemail').val((data['email']));
                                $('#benfbname').val((data['bank_name']));
                                $('#benfifsc').val((data['ifsc_code']));
                            },
                            error: function ()
                            {
                                alert(data);
                            }

                        })
                    }

                </script>
                <?php
                $senderTAmt = array();
                $senderTotamt = array();

                foreach ($senderData as $senderTot) {
                    $senderTAmt[] = $senderTot['gross_amt'];
                }
                $senderTotamt[] = array_sum($senderTAmt);
                ?>

                <span class="pull-right total-sender-amnt"> <b> Total : <?php echo implode("", $senderTotamt); ?> </b></span> <br>

                <div class="row">
                    <div class="col-md-12 sender-trans-details">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <th>Sr No.</th>
                                <th>Order ID</th>
                                <th>Bank Trans ID</th>
                                <th>Wallet ID</th>                                   
                                <th>Eko Trans ID </th>
                                <th>Retailer Mobile / Shop Name.</th>
                                <th>Beneficiary Acc No.</th>
                                <th>Beneficiary Name</th>
                                <th>Beneficiary Mob No.</th>
                                <th>Amount</th>                                   
                                <th>Status</th>
                                <th>Date Time</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php $i = 1; ?>
<?php foreach ($senderData as $sender) { ?>    
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $sender['order_id']; ?></td>
                                            <td><?php echo $sender['bank_refno']; ?></td>
                                            <td><?php echo "<a style='font-size:Normal;' target='_blank' href='/dmt/transactionReport/$banktype/" . $sender['wallet_id'] . "'>" . $sender['wallet_id'] . " </a>"; ?></td>                                       
                                            <td><?php echo $sender['bank_txn_id']; ?></td>
                                            <?php
                                            $retNo = $sender['ret_id'];
                                            $retName = $ret_array[$sender['ret_id']];
                                            $retMob = $ret_array_Mob[$sender['ret_id']];
                                            ?>
                                            <td><?php
                                            echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retNo . "'>" . $retMob . "</a> " . " /" . "\xA";
                                            echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retNo . "'>" . $retName . "</a> ";
                                            ?></td>                 
                                            <td><?php echo $sender['bene_accno']; ?></td>
                                            <td><?php echo $sender['bene_name']; ?></td>
                                            <td><?php echo $sender['bene_mobile']; ?></td>
                                            <td><?php echo $sender['gross_amt']; ?></td>                                       
    <?php $sendertxn_status = Configure::read('Remit_pay1_status'); ?>
                                            <td><?php echo $sendertxn_status[$sender['status']]; ?></td>
                                            <td><?php echo $sender['updated_at']; ?></td>
                                        </tr>
                                    </tbody>
<?php } ?>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- /Start your project here-->

        <!-- SCRIPTS -->
        <!-- JQuery -->
        <script src="/boot/js/jquery-3.1.1.min.js"></script>

        <!-- Bootstrap core JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
        <script src="/boot/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-dmt-datepicker.min.js"></script>
        <script type="text/javascript" src="/src/js/footable.js"></script>


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


        <div id="addClientPop" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h3>Beneficiary Details</h3>
                    </div>
                    <style>
                        input[type=text], select {
                            width: 100%;
                            padding: 8px 20px;
                            margin: 4px 0;
                            display: inline-block;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-sizing: border-box;


                        }

                    </style>

                    <div class="modal-body">
                        <div class="scroller" style="height:400px" data-always-visible="1" data-rail-visible1="1">
                            <div class="row-fluid">

                                <label for   ="benfname"> Name </label>
                                <input class ="well" type="text" id="benfname" readonly name="benfname"> <br>
                                <label for   ="benfacc"> Account No </label>
                                <input class ="well" type="text" id="benfacc" readonly name="benfacc"> <br>
                                <label for   ="benfmob"> Mobile </label>
                                <input class ="well" type="text" id="benfmob" readonly name="benfmob"><br>
                                <label for   ="benfemail"> Email </label>
                                <input class ="well"type="text" id="benfemail" readonly  name="benfemail">
                                <label for   ="benfbname"> Bank Name </label>
                                <input class ="well" type="text" id="benfbname" readonly  name="benfbname">
                                <label for   ="benfifsc"> Ifsc Code </label>
                                <input class ="well" type="text" id="benfifsc" readonly  name="benfifsc">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">   
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>


