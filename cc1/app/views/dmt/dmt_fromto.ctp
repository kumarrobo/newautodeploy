
<html>
    <head>
        <title>From To Report</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link   rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <style>
            .btn.btn-primary.search {
                margin: 24px;
            }
            .type .btn-group{display: block;}
            .type .multiselect.dropdown-toggle.btn.btn-default {
                display: block;
                overflow: hidden;
                width: 100%;
            }
        </style>
        <script>
            $(document).ready(function () {
                $('#transtatus').multiselect({
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeSelectAllOption: true
                });
            });
        </script>
    </head>
    <style>   
        tfoot tr td{border:0px !important;}
    </style>
    <body>
        <?php $banktype = 'ekonew'; ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li><a href = "/dmt/index/<?php echo $banktype; ?>" >Search</a></li>
                    <li class="active"><a href="/dmt/dmtFromto/<?php echo $banktype; ?>" >All Transactions</a></li>
                    <li><a   href="/dmt/accvalidationreport/<?php echo $banktype; ?>" >A/c Validation</a></li>
                    <li><a  href="/dmt/dmtAdminPanel" >Notification Panel</a></li>      
                    <li><a  href="/dmt/dmtCommentSystem" >Comment Panel</a></li>
                </ul>
            </div>
        </nav>
        <style>
            tfoot {border:none !important;}
        </style>
        <?php
        $fldno = 0;
        $fldname = 0;
        ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li class="<?php
                        if ($banktype == "ekonew") {
                        echo "active";
                    }
                    ?>"><a href = "/dmt/dmtFromto/ekonew/" >New Eko</a></li>
<!--                    <li class="<?php
                    if ($banktype == "eko") {
                        echo "active";
                    }
                    ?>"><a href = "/dmt/dmtFromto/eko/">Eko </a></li>-->
                </ul>
            </div>
        </nav>

    </head>
    <form name="index" id = "index" method="POST">
        <div>
            <input type="hidden" class="form-control" id="dmt_from"   value ="<?php echo isset($_POST['dmt_from']) ? $_POST['dmt_from'] : ''; ?>" />
            <input type="hidden" class="form-control" id="dmt_till"   value="<?php echo isset($_POST['dmt_till']) ? $_POST['dmt_till'] : ''; ?>" /> 
        </div>
    </form>     
    <?php
    if ($this->params['url']['page'] == '') {
        $this->params['url']['page'] = 1;
    }
    ?>
<body>


    <form name="reportform" id = "reportform" method="POST" >
        <div class="wrapper">
            <div class="container">
                <h1>Reports</h1>
                <div class="row">
                    <div class="col-md-2">
                        <label for="from_date">From Date</label>
                        <input type="text" class="form-control" style='width:180px;float:left;'  id="dmt_from" name="dmt_from" value="<?php if (!empty($frm)) echo $frm; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="till_date">Till Date</label>
                        <input type="text" class="form-control" style='width:180px;float:left;'  id="dmt_till" name="dmt_till" value="<?php if (!is_null($tos)) echo $tos; ?>">
                    </div>
                    <div class="col-md-2 type">                                                                                    
                        <label for="transtatus">Status Type</label>                                                        
                        <select  class="form-control" style='width:300px;float:left;' id="transtatus" name="transtatus[]"  multiple="multiple"   value="<?php echo $s; ?>">                                                
                            <?php $txn_status = Configure::read('Remit_pay1_status'); ?>
                            <?php foreach ($txn_status as $txnno => $txnval): ?>                 
                                <option value="<?php echo $txnno ?>" <?php if (in_array($txnno, $transtatus)) echo "selected" ?> >
                                    <?php echo $txnval ?></option>                                                     
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="dmt_groupid">Group Id</label> 
                        <input type="text" class="form-control" style='width:170px;float:left;'  id="dmt_groupid" name="dmt_groupid" value="<?php if (!is_null($txngrp_id)) echo $txngrp_id; ?>">                        
                    </div>       
                    <?php $trantypearr = array('-1' => 'All', '0' => 'App', '1' => 'Web'); ?>
                    <div class="col-md-1">
                        <label for="transtype"> Type </label>
                        <select class="form-control" style='width:90px;' id ="trantype" name="trantype">                            
                            <?php foreach ($trantypearr as $typeval => $typename): ?>
                                <option value ="<?php echo $typeval; ?>" <?php if ($typeval == $txntype) echo "selected" ?>><?php echo $typename; ?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>                      
                    <?php $tranmodearr = array('0' => 'All', '1' => 'NEFT', '2' => 'IMPS'); ?>
                    <div class="col-md-1">
                        <label for="transmode"> Mode  </label>
                        <select class="form-control" style='width:90px;' id ="transmode" name="transmode">                            
                            <?php foreach ($tranmodearr as $modeval => $modename): ?>
                                <option value ="<?php echo $modeval; ?>" <?php if ($modeval == $transmode) echo "selected" ?>><?php echo $modename; ?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>                      
                    <div class="col-md-1">
                        <label for="vendorType"> Vendor  </label>
                        <select class="form-control" style='width:90px;' id ="vendorType" name="vendorType">                                                        
                            <option value="">Select</option>
                            <?php foreach ($vendorDet as $vend) {?>
                            <?php echo $vend['vendor_master']['platform_vendor_id'] ?>
                                <option value ="<?php echo $vend['vendor_master']['platform_vendor_id']; ?>" <?php if ($vend['vendor_master']['platform_vendor_id'] == $vendor) echo "selected" ?>><?php echo $vend['vendor_master']['vendor_name']; ?></option>
                            <?php } ?> 
                        </select>
                    </div>                    
                    
                    <div class="col-md-1">
                        <?php
                        $url1 = explode('/', $_SERVER['REQUEST_URI']);
                        $url = explode('?', $url1[4]);
                        $url[4] = !$url[0] ? 100 : $url[0];
                        ?>
                        <label for="ivr_no">Pages</label>                       
                        <select class="form-control"  style='width:90px;' id="ivr_no" name="ivr_no" onchange="javascript:goToPage(1, this.value);">            
                            <option <?php
                            if ($url[4] == 10) {
                                echo "selected";
                            }
                            ?>>10</option>
                            <option <?php
                            if ($url[4] == 20) {
                                echo "selected";
                            }
                            ?>>20</option>
                            <option <?php
                            if ($url[4] == 30) {
                                echo "selected";
                            }
                            ?>>30</option>
                            <option <?php
                            if ($url[4] == 50) {
                                echo "selected";
                            }
                            ?>>50</option>
                            <option <?php
                            if ($url[4] == 100) {
                                echo "selected";
                            }
                            ?>>100</option>
                            <option <?php
                            if ($url[4] == 1000) {
                                echo "selected";
                            }
                            ?>>1000</option>
                        </select>
                    </div>
                    <div class="col-md-1">     
                        <button  class="btn btn-primary search"  id = "filerbtn" name = "filterbtn" >Search</button>  
                    </div>   
                    <div class="pull-right">              
                        <input type="hidden" name="fer_fld" id ="fer_fld">                                   
                        <a href='#' class="btn btn-primary search"  id='rtbtn' name ='rtbtn' >Download Excel</a>
                    </div>
                </div>
            </div>
            <div class="retailer-details">
                <div class="col-md-12">
                    <!--For getting No of Records -->                                                
                    <?php
                    foreach ($report_data as $srno) {
                        $totRec[] = $srno['id'];
                    }
                    ?>
                    <?php $Rec[] = count($totRec); ?>
                    <div class="container-fluid"> 
                        <?php if($days <= 10) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" >
                                <div class="pull-left"><b>No of Records : <span class="pull-right"> <?php echo implode(" ", $Rec); ?> </b></span></div><br></br>
                                <div class="pull-left">
                                    <label for="pending">  Pending : </label>
                                    <label for="pending_Txn"><?php echo $pendingTxn[0][0]['success_txn']; ?> => </label>
                                    <label for="pending_amount"><?php echo floor($pendingTxn[0][0]['amount']); ?></label>
                                </div>

                                <div class="pull-right">
                                    <label for="success"> Success : </label>
                                    <label for="success_txn"><?php echo $successTxn[0][0]['success_txn']; ?> => </label>
                                    <label for="success_amount"><?php echo floor($successTxn[0][0]['amount']); ?></label> <br>
                                </div>
                                <thead>
                                <th>Retailer Mobile No./Shop Name</th>
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
                                <th>Transaction Amt</th>
                                <th>Comm</th>
                                <th>Service Charge</th>
                                <th> TDS </th>
                                <th>Pay1 Status</th>
                                <th>Bank Status</th>                                    
                                <th>Group Id</th>  
                                <th>Type</th>
                                <th>Opening</th>
                                <th>Closing</th>
                                <th>Transaction Date </th>
                                <th>Created</th>
                                <th>Updated</th>                                
                                </thead>
                                <tbody>

                                    <?php foreach ($report_data as $data) { ?>
                                        <tr>
                                            <?php
                                            $retNo = $data['ret_id'];
                                            $retName = $ret_array[$data['ret_id']];
                                            ?>
                                            <td><?php
                                                echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retNo . "'>" . $ret_arrayMobid[$data['ret_id']] . "</a> " . " /" . "\xA";
                                                echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retNo . "'>" . $retName . "</a> ";
                                                ?></td> 
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
                                            <td><?php echo $data['gross_amount']; ?></td>
                                            <td><?php echo $data['commission']; ?></td>
                                            <td><?php echo $data['pay1_charge']; ?></td>
                                            <td><?php echo $data['tds']; ?></td>
                                            <?php $pay1txn_status = Configure::read('Remit_pay1_status'); ?>
                                            <td><?php echo $pay1txn_status[$data['pay1_status']]; ?></td>                                            
                                            <?php $banktxn_status = Configure::read('Remit_bank_status.eko') ?>                                            
                                            <td><?php echo $banktxn_status[$data['bank_status']]; ?></td>                                            
                                            <td><?php echo $data['group_id']; ?></td>
                                            <?php $transbifurcation = array(1 => 'Web', 0 => 'App'); ?>
                                            <td><?php echo $transbifurcation[$data['type']]; ?></td>
                                            <td><?php echo $data['opening']; ?></td>
                                            <td><?php echo $data['closing']; ?></td>
                                            <td><?php echo $data['date']; ?></td>
                                            <td><?php echo $data['created_at']; ?></td>
                                            <td><?php echo $data['updated_at']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <!--For gett`ing Sum of Amt-->
                                    <?php
                                    foreach ($report_data as $repo) {
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
                    </div>
                        
                </div>
            </div>    
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5 text-right">
                    <?php echo $this->element('pagination'); ?>
                </div>
            <?php } else { ?>            
                        <br>
                        <div class="alert alert-danger">
                          <strong>Message</strong> Data Range Cannot be greater than 10 days
                        </div>                        
            
            <?php } ?>
            </div>
    </form>
    
    
        <div class="table-responsive">           
            <div class="tables table-bordered table-hover">
                <table>
                    <thead>
                    <th>id</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Priority</th>
                    <th>Vendor</th>
                    <th>Message</th>
                    <th>Show/Hide</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 1;
                    foreach($notfDet as $nDet){ ?>                    
                    <tr><td><?php echo $i; ?></td>
                        <td> <input type="text" class ="form-control" id="upnotf_From_<?php echo $nDet['communications']['id']; ?>" name="upnotf_From" value = "<?php echo $nDet['communications']['display_from']; ?>" disabled="true"></td>
                        <td> <input type="text" class ="form-control" id="upnotf_To_<?php echo $nDet['communications']['id']; ?>" name="upnotf_To" value = "<?php echo $nDet['communications']['display_to']; ?>" disabled="true"></td>
<!--                        <td> <select id="upnotf_Priority_<?php echo $nDet['communications']['id']; ?>" name="upnotf_Priority" class="form-control" disabled="true">
                            <option value="">Select</option>                            
                            <?php foreach($priority as $ids => $val){ ?>                            
                            <option value="<?php echo $ids;?> <?php if($nDet['communications']['priority'] == $ids) echo 'selected'; ?>" ><?php echo $val;?></option>                           
                            <?php } ?>
                        </select> </td>-->
                        <td> <input type="text" class ="form-control" id="upnotf_Priority_<?php echo $nDet['communications']['id']; ?>" name="upnotf_Priority_" value = "<?php echo $nDet['communications']['priority']; ?>" disabled="true"></td>
                        <td><select id="upnotf_Vendor_<?php echo $nDet['communications']['id']; ?>" name="notf_Vendor" class="form-control" disabled="true">
                            <option value="">Select</option>                            
                            <?php foreach($vendorDet as $vend){ ?>
                            <option value="<?php echo $vend['vendor_master']['platform_vendor_id']?>" <?php if($nDet['communications']['vendor_id'] == $vend['vendor_master']['platform_vendor_id']) echo 'selected' ?> ><?php echo $vend['vendor_master']['vendor_name'];?></option>
                            <?php } ?>
                            </select></td>
                            <td><textarea id="upnotf_Message_<?php echo $nDet['communications']['id']; ?>" class="form-control" name="upnotf_Message" style="height:200px;width:400px" disabled="true"><?php echo $nDet['communications']['message']; ?></textarea></td>
                            <td><label class="switch">
                            <input type="checkbox" id="upnotf_ShowFlag_<?php echo $nDet['communications']['id']; ?>" name="upnotf_ShowFlag" <?php echo ($nDet['communications']['show_flag'] == '1') ? "checked" : ""; ?> disabled="true"> <span class="slider round"></span>
                             </label></td>
                        <td> 
                            <button type="button" class="btn btn-primary" id="upnotenb_<?php echo $nDet['communications']['id'];?>" name="upnotenb" onclick="notfEnable(<?php echo $nDet['communications']['id'];?>)">Update</button>
                            <button type="button" class="btn btn-primary" id="upnotf_<?php echo $nDet['communications']['id'];?>" name="upnotf" disabled="true" onclick="notfUpdate(<?php echo $nDet['communications']['id']; ?>)">Submit</button>
                        </td>
                    <?php  $i++; } ?>
                    </tr>
                    </tbody>
                </table>
            <script>
                $('#rtbtn').click(function (e) {
                    e.preventDefault();
                    $('#fer_fld').val('1');
                    $('#filerbtn').click();
                    $('#fer_fld').val('');
                });
                function goToPage(page = 1, recs =<?php echo $url[4]; ?>) {
                    $('#reportform').attr('action', '/dmt/dmtFromto/<?php echo $banktype; ?>/' + recs + '?page=' + page);
                    $('#reportform').submit();
                }
            </script>
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
        function dmtcheck(id, mobile) {
           var type = '<?php echo $banktype; ?>'
           
            $.ajax({
                type: "POST",
                url: '/dmt/dmtCheckRefund',
//                dataType: "json",
                data: {txn_id: id, ret_mob: mobile,type:type},
                success: function (data) {

                    if (data == 1) {
                        alert("Eligible for Refund.");
                    } else {
                        alert("Not Eligible for Refund.");
                    }

                },
                error: function (err) {
                    console.log(err);

                }
            });
        }
    </script>         
    <script>
        function bankType(name) {
            $.ajax({
                type: "POST",
                url: '/dmt/dmtFromto',
                dataType: "json",
                data: {bankType: name},
                success: function (data) {
                    alert(data);
                }
            });
        }
    </script>     
