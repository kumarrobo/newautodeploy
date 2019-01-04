<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/boot/js/invoice.js"></script>
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>  
<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>  

<style>
    .tab {
        /*overflow: hidden;*/
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    ul.nav li.dropdown:hover > ul.dropdown-menu {
        display: block;    
    }
    thead{
       background-color: #428bca;
       color: #fff;
    }
    .modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .select:hover,
    .select:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    a:hover{
        background-color: #428bca;
        color: #fff;
    }
</style>
<?php $ledger_types = array(1=>'Vendor Ledger : Modem',2=>'Vendor Ledger : Api',3=>'Distributor Ledger',4=>'Retailer Ledger'); ?>

<div class="tab">
    <ul class="nav nav-tabs">
        <li><button class="tablinks" onclick="window.location='/accounting/txnUpload'">File Upload</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li class="dropdown">
            <button class="tablinks active" onclick="window.location='/accounting/ledger'">Ledger</button>
            <ul class="dropdown-menu" role="menu">
                <?php foreach ($ledger_types as $idx=>$l_t) { ?>
                <li><a href="/accounting/ledger/<?php echo $idx ?>"><?php echo $l_t ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div>
<br/><br/>
<div style="font-weight: bold; text-align: center; font-size: 20px;"><?php echo $ledger_types[$type]; ?> </div>
<!--<b><a href="/accounting/ledger/1">Vendor Ledger : Modem</a> <span style="margin:0 50px;">.</span> <a href="/accounting/ledger/2">Vendor Ledger : Api</a> <span style="margin:0 50px;">.</span> <a href="/accounting/ledger/3">Distributor Ledger</a> <span style="margin:0 50px;">.</span> <a href="/accounting/ledger/4">Retailer Ledger</a></b>-->
<br/><br/>

<div>

    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">Search : </div><input type="hidden" id="search_id" value="<?php echo $id?>"><div style="float: left; width: 350px;"><input type="text" class="form-control" style="width: 300px;" id="search" placeholder="Search by ID / Name / Mobile" autocomplete="off" value="<?php echo isset($details[0][0]['name']) ? trim($details[0][0]['name']) : trim($details[0]['l']['name']); ?>"></div>
    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">From Date : </div><div style="float: left; width: 150px;"><input type="text" class="form-control" style="width: 110px;" id="from_date" placeholder="From" value="<?php echo $from; ?>"></div>
    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">To Date : </div><div style="float: left; width: 150px;"><input type="text" class="form-control" style="width: 110px;" id="to_date" placeholder="To" value="<?php echo $to; ?>"></div>
    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;"><input class="btn btn-primary" type="button" value="Submit" style="padding: 5px 10px;" onclick="window.location = '/accounting/ledger/<?php echo $type; ?>/' + ($('#from_date').val() == '' ? 0 : $('#from_date').val()) + '/' + ($('#to_date').val() == '' ? 0 : $('#to_date').val()) + '/' + $('#search_id').val()"></div>
    <div style="float:left;  width: 50px;  margin-top: 5px; "><button value="" onclick="window.location = '/accounting/ledger/<?php echo $type; ?>/' + ($('#from_date').val() == '' ? 0 : $('#from_date').val()) + '/' + ($('#to_date').val() == '' ? 0 : $('#to_date').val()) + '/' + $('#search_id').val() + '/1' "><i class="fa fa-file-pdf-o fa-2x" style="color:red"></i></button></div>
<!--    <div><button value="" onclick="window.location = '/accounting/ledger/<?php echo $type; ?>/' + ($('#from_date').val() == '' ? 0 : $('#from_date').val()) + '/' + ($('#to_date').val() == '' ? 0 : $('#to_date').val()) + '/' + $('#search_id').val() + '/1' "><i class="fa fa-file-pdf-o" style="color:red"></i></button></div>-->
    <div style="clear: both;"></div>
    <div id='livesearch'></div>
</div>
<br/><br/><br/>
<div id="myModal" class="modal">
    <div class="modal-content">
    <span class="close">&times;</span>
    <h3><center>Salesmen List</center> </h3>
    
    <center>   
        <table class="table table-bordered table-hover table-striped table-responsive" id="salesmentable" style="border-collapse: collapse;border: 1px solid black;">
        <thead>
            <tr>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>ID</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Name</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Amount</center></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($salesmen as $key => $value){
                    echo '<tr>';
                    echo '<td><center>'.$value['salesmen']['id'].'</center></td>';
                    echo '<td><center>'.$value['salesmen']['name'].'</center></td>';
                    echo '<td><center>'.$value['st']['amount'].'</center></td>';
                    echo '</tr>';
                }
            ?>
        </tbody>   
        </center>
            </table>
    </center>
    </div>
</div>  
<div id="retailerModal" class="modal">
    <div class="modal-content">
    <span class="close retailerclose">&times;</span>
    <h3><center>Retailers List</center> </h3>
    
    <center>   
    <table class="table table-bordered table-hover table-striped table-responsive" id="retailertable" style="border-collapse: collapse;border: 1px solid black;">
        <thead>
            <tr>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>ID</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Name</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Amount</center></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($retailers as $key => $value){
                    echo '<tr>';
                    echo '<td><center>'.$value['retailers']['id'].'</center></td>';
                    echo '<td><center>'.$value['retailers']['name'].'</center></td>';
                    echo '<td><center>'.$value['st']['amount'].'</center></td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
                
        </center>
            </table>
    </center>
    </div>
</div>  

<div>   
<div style="float: left; width: 33%; visibility: hidden;">.</div>
<div style="float: left;">
<?php if ($id) { if ($type == 1) { ?>
    
    <table class='table table-bordered table-hover' style='width: 500px;'>
        <thead>
            <tr>
                <td colspan="4"><center><b>Vendor Ledger : Modem <?php echo $details[0]['l']['name'] ? '[ '.$details[0]['l']['name'].' ]' : ''; ?></b></center></td>
            </tr>
            <tr>
                <td colspan="2"><center><b>DR</b></center></td>
                <td colspan="2"><center><b>CR</b></center></td>
            </tr>
            <tr>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
            </tr>
        </thead>
        <tbody style="color: #4e4e4e;">
            <tr>
                <td style="font-weight: bold;">By Opening Balance</td>
                <td class="select"  style="font-weight: bold;" id="click"><?php echo round($data['vendor']['modem']['o'], 2); ?></td>
                <td style="font-weight: bold;">By Payment</td>
                <td class="select"  style="font-weight: bold;" id="click1"><?php echo round($data['vendor']['modem']['to_pay'][0][0]['pay'],2); ?></td>
                
           </tr>
            
            <tr>
                <td style="font-weight: bold;">To Purchase</td>
                <td class="select" style="font-weight: bold;" id="click2"><?php echo round($data['vendor']['modem']['purchase'],2); ?></td>
                <td style="font-weight: bold;">To Comm Paid</td>
                <td class="select" style="font-weight: bold;" id="click3"><?php echo round($data['vendor']['modem']['commission'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;">By Closing Balance</td>
                <?php $closing = round($data['vendor']['modem']['o'] - $data['vendor']['modem']['to_pay'][0][0]['pay'] + $data['vendor']['modem']['purchase'] - $data['vendor']['modem']['commission']); ?>
                <td class="select" style="font-weight: bold;" id="click4"><?php echo $closing; ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['modem']['o'] + $data['vendor']['modem']['purchase'],2); ?></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['modem']['to_pay'][0][0]['pay'] + $data['vendor']['modem']['commission'] + $closing, 2); ?></td>
            </tr>
        </tbody>
    </table>
   
<?php } else if ($type == 2) { ?>

    <table class='table table-bordered table-hover' style='width: 500px;'>
        <thead>
            <tr>
                <td colspan="4"><center><b>Vendor Ledger : Api <?php echo $details[0][0]['name'] ? '[ '.$details[0][0]['name'].' ]' : ''; ?></b></center></td>
            </tr>
            <tr>
                <td colspan="2"><center><b>DR</b></center></td>
                <td colspan="2"><center><b>CR</b></center></td>
            </tr>
            <tr>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
            </tr>
        </thead>
        <tbody style="color: #4e4e4e;">
            <tr>
                <td style="font-weight: bold;">To Purchase</td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['api'][0][0]['sale'],2); ?></td>
                <td style="font-weight: bold;">By Opening Balance</td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['api']['o'][0]['el']['opening'], 2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;">By Payment</td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['api']['purchase'][0][0]['purchase'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">By Closing Balance</td>
                <?php $closing = round($data['vendor']['api']['o'][0]['el']['opening'] + $data['vendor']['api']['purchase'][0][0]['purchase'] + $data['vendor']['api'][0][0]['commission'] - $data['vendor']['api'][0][0]['sale'], 2); ?>
                <td style="font-weight: bold;"><?php echo $closing; ?></td>
                <td style="font-weight: bold;">To Comm Paid</td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['api'][0][0]['commission'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['api'][0][0]['sale'] + $closing,2); ?></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($data['vendor']['api']['o'][0]['el']['opening'] + $data['vendor']['api']['purchase'][0][0]['purchase'] + $data['vendor']['api'][0][0]['commission'],2); ?></td>
            </tr>
        </tbody>
    </table>

<?php 

        } else if ($type == 3) {

		$data['distributor']['commission'][0][0]['commission'] = $data['distributor']['commission'][0][0]['commission'] - $data['distributor']['commission'][0][0]['excluded_commission'];
            
                $distributor_dr = $data['distributor']['o'][0]['dl']['opening'] + $data['distributor']['limit'][0][0]['limit'] + $data['distributor']['commission'][0][0]['commission'] + $data['distributor']['incentive'][0][0]['incentive'];
                
                $distributor_cr = $data['distributor']['trf_ret'][0][0]['transfer_retailer'] + $data['distributor']['trf_sal'][0][0]['transfer_salesmen'] + $data['distributor']['tds'][0][0]['tds'] + $data['distributor']['kit_charge'][0][0]['kit_charge'] + $data['distributor']['sd'][0][0]['security_deposit'] + $data['distributor']['one_time'][0][0]['one_time'];

?>
    <table class='table table-bordered table-hover' style='width: 500px;'>
        <thead>
            <tr>
                <td colspan="4"><center><b>Distributor Ledger <?php echo $details[0][0]['name'] ? '[ '.$details[0][0]['name'].' ]' : ''; ?></b></center></td>
            </tr>
            <tr>
                <td colspan="2"><center><b>DR</b></center></td>
                <td colspan="2"><center><b>CR</b></center></td>
            </tr>
            <tr>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
            </tr>
        </thead>
        <tbody style="color: #4e4e4e;">
            <tr>
                <td style="font-weight: bold;">By Opening Balance</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['o'][0]['dl']['opening'], 2); ?></td>
                <td style="font-weight: bold;">By Limit Given to retailers</td>
                <td style="font-weight: bold;" id="retailerdetail"><?php echo round($data['distributor']['trf_ret'][0][0]['transfer_retailer'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">To Cash/Limits</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['limit'][0][0]['limit'],2); ?></td>
                <td style="font-weight: bold;">By Limit Given to Salesman</td>
                <td style="font-weight: bold;" id="salesmandetail"><?php echo round($data['distributor']['trf_sal'][0][0]['transfer_salesmen'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">To Commission Received</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['commission'][0][0]['commission'],2); ?></td>
                <td style="font-weight: bold;">By TDS Paid</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['tds'][0][0]['tds'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">By Incentives</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['incentive'][0][0]['incentive'],2); ?></td>
                <td style="font-weight: bold;">By Kit Charges</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['kit_charge'][0][0]['kit_charge'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;">Security Deposit</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['sd'][0][0]['security_deposit'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;">One Time</td>
                <td style="font-weight: bold;"><?php echo round($data['distributor']['one_time'][0][0]['one_time'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;">By Closing Balance</td>
                <?php $closing = round($distributor_dr - $distributor_cr, 2); ?>
                <td style="font-weight: bold;"><?php echo $closing; ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($distributor_dr, 2); ?></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($distributor_cr + $closing, 2); ?></td>
            </tr>
        </tbody>
    </table>

<?php

        } else if ($type == 4) {
            
                $retailer_dr = $data['retailer']['o'][0]['rl']['opening'] + $data['retailer']['transfer_nn'][0][0]['transfer'] + $data['retailer']['trf_net_lmt'][0][0]['transfer'] + $data['retailer']['trf_net_lmt_p'][0][0]['transfer'] + $data['retailer']['commission'][0][0]['commission'] + $data['retailer']['incentive'][0][0]['incentive'];

                $retailer_cr = $data['retailer']['kit_charge'][0][0]['kit_charge'] + $data['retailer']['service_chrge'][0][0]['service_charge'] + $data['retailer']['one_time'][0][0]['one_time'] + $data['retailer']['rental'][0][0]['rental'];
            
?>

    <table class='table table-bordered table-hover' style='width: 500px;'>
        <thead>
            <tr>
                <td colspan="4"><center><b>Retailer Ledger <?php echo $details[0][0]['name'] ? '[ '.$details[0][0]['name'].' ]' : ''; ?></b></center></td>
            </tr>
            <tr>
                <td colspan="2"><center><b>DR</b></center></td>
                <td colspan="2"><center><b>CR</b></center></td>
            </tr>
            <tr>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
                <td><center><b>Particulars</b></center></td>
                <td><center><b>Amount</b></center></td>
            </tr>
        </thead>
        <tbody style="color: #4e4e4e;">
            <tr>
                <td style="font-weight: bold;">By Opening Balance</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['o'][0]['rl']['opening'], 2); ?></td>
                <td style="font-weight: bold;">By Kit Charges</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['kit_charge'][0][0]['kit_charge'],2); ?></td>
            </tr>
            <tr>
                <?php if ($data['retailer']['transfer_nn']) { ?>
                <td style="font-weight: bold;">To Limit from Distributors</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['transfer_nn'][0][0]['transfer'],2); ?></td>
                <?php } else { ?>
                <td style="font-weight: bold;">To Cash/Limits</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['trf_net_lmt'][0][0]['transfer'],2); ?></td>
                <?php }  ?>
                <td style="font-weight: bold;">Service Charges</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['service_chrge'][0][0]['service_charge'],2); ?></td>
            </tr>
            <tr>
                <?php if ($data['retailer']['transfer_nn']) { ?>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <?php } else { ?>
                <td style="font-weight: bold;">To Pay U Limits</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['trf_net_lmt_p'][0][0]['transfer'],2); ?></td>
                <?php }  ?>
                <td style="font-weight: bold;">One Time</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['one_time'][0][0]['one_time'],2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">To Commission Received</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['commission'][0][0]['commission'],2); ?></td>
                <td style="font-weight: bold;">Rental</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['rental'][0][0]['rental'],2); ?></td>
            </tr>
            <?php foreach ($data['retailer']['services'][CREDIT_NOTE] as $res) { $retailer_dr += $res['amount']; ?>
            <tr>
                <td style="font-weight: bold;"><?php echo $res['name']; ?></td>
                <td style="font-weight: bold;"><?php echo round($res['amount'], 2); ?></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
            </tr>
            <?php } ?>
            <?php foreach ($data['retailer']['services'][DEBIT_NOTE] as $res) { $retailer_cr += $res['amount']; ?>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo $res['name']; ?></td>
                <td style="font-weight: bold;"><?php echo round($res['amount'], 2); ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td style="font-weight: bold;">By Incentives</td>
                <td style="font-weight: bold;"><?php echo round($data['retailer']['incentive'][0][0]['incentive'],2); ?></td>
                <td style="font-weight: bold;">By Closing Balance</td>
                <?php $closing = round($retailer_dr - $retailer_cr, 2); ?>
                <td style="font-weight: bold;"><?php echo $closing; ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($retailer_dr, 2); ?></td>
                <td style="font-weight: bold;"></td>
                <td style="font-weight: bold;"><?php echo round($retailer_cr + $closing, 2); ?></td>
            </tr>
        </tbody>
    </table>

<?php } } ?>
    
    </div>
</div>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<script>
   
    
    $('#from_date, #to_date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "-1d",
        multidate: false,
        autoclose: true
    });
    
    $("#search").keyup(function() {
        
        clearOptions();
        
        var str = $("#search").val();
        
        if (str.length > 1) {
            var type = $('#type').val();
            
            $('#livesearch').html("Loading ...");
            $('#livesearch').css({'border':'1px solid #A5ACB2','width':'250px'});
                
            $.post('/accounting/typeList', {'type': '<?php echo $type; ?>', 'str': str, 'request_from' : "ledger"}, function(e) {
                var list = "";

                for (var x in e) {
                    if (e[x].length == undefined) {
                        list = list + "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ e[x].id +",\""+ e[x].name +"\");'>"+ e[x].name +"</a></div>"; 
                    }
                }

                if (list != '') {
                    $('#livesearch').html(list);
                } else {
                    $('#livesearch').html('<center>No Record Found !!!</center>');
                }
                $('#livesearch').css({'width':'400px'});
                
            }, 'json');
        }
    });
    
    function clearOptions () {
        $('#livesearch').html('');
        $('#livesearch').css({'border':'0px'});
    }

    function selectType (id, name) {
        $('#search_id').val(id);
        $('#search').val(name);

        clearOptions();
    }
    $('#retailertable').DataTable();
    $('#salesmentable').DataTable();

    var modal          = document.getElementById('myModal');
    var btn            = document.getElementById("salesmandetail");
    var retailerdetail = document.getElementById("retailerdetail");
    var retailerModal  = document.getElementById("retailerModal");
    var retailerclose  = document.getElementsByClassName("retailerclose")[0];
    var span           = document.getElementsByClassName("close")[0];  

    btn.onclick = function() {
        <?php if(!(round($data['distributor']['trf_sal'][0][0]['transfer_salesmen'],2)) == '0'){ ?>
            modal.style.display = "block";
        <?php } ?>
    }
    
    retailerdetail.onclick = function() {
        <?php if(!(round($data['distributor']['trf_ret'][0][0]['transfer_retailer'],2)) == '0'){ ?>
            retailerModal.style.display = "block";
        <?php } ?>
    }
    
    span.onclick = function() {
        modal.style.display = "none";
    }
    
    retailerclose.onclick = function() {
        retailerModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == retailerModal) {
            retailerModal.style.display = "none";
        }
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
</script>
