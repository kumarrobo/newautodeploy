<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

<style>
    .tab {
        overflow: hidden;
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
    
    thead{
       background-color: #428bca;
       color: #fff;
    }
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <li><button class="tablinks" onclick="window.location='/accounting/txnUpload'">File Upload</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <li><button class="tablinks active" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div>
<br/><br/>

<div style="float: left; width: 350px; margin-left: 250px;">
    <table class='table table-bordered table-hover' style='width: 200px;'>
        <thead>
            <tr>
                <td colspan="2"><b><center>Incoming</center></b></td>
            </tr>
        </thead>
        <tbody style="color: #4e4e4e;">

    <?php
        foreach($data['incoming'] as $i) {
            $total += $i[0]['amount'];
    ?>
            <tr>
                <td><?php echo $i[0]['txn_date']; ?></td>
                <td><?php echo $i[0]['amount']; ?></td>
            </tr>
    <?php } ?>
            <tr>
                <td><b>Cash</b></td>
                <td><b><?php echo $total; ?></b></td>
            </tr>
        </tbody>
    </table>
</div>

<div style="float: left; width: 375px;">
    <table class='table table-bordered table-hover' style='width: 200px;'>
        <thead>
            <tr>
                <td colspan="4"><b><center>Outgoing</center></b></td>
            </tr>
        </thead>
        <tbody style="color: #4e4e4e;">
            <tr style="font-weight: bold;">
                <td>Primary</td>
                <td><?php echo $data['primary']; ?></td>
            </tr>
            <tr>
                <td>Cash</td>
                <td><?php echo $data['sd_to_d'][0][0]['amount'] + $data['netsys_to_r'][0][0]['amount']; ?></td>
            </tr>
            <tr>
                <td>Incentive</td>
                <td><?php echo $data['incentive_dist'][0][0]['incentive'] + $data['incentive_ret'][0][0]['incentive']; ?></td>
            </tr>
            <tr>
                <td>Commission</td>
                <td><?php echo $data['commission_dist'][0][0]['commission'] + $data['commission_ret'][0][0]['commission']; ?></td>
            </tr>
            <tr>
                <td>mPOS</td>
                <td><?php echo $data['mPos'][0][0]['amount'] ? $data['mPos'][0][0]['amount'] : 0; ?></td>
            </tr>
            <tr>
                <td>PayU</td>
                <td><?php echo $data['payU'][0][0]['amount'] ? $data['payU'][0][0]['amount'] : 0; ?></td>
            </tr>
            <tr>
                <td>Others</td>
                <td><?php echo $data['primary'] - ($data['sd_to_d'][0][0]['amount'] + $data['netsys_to_r'][0][0]['amount']) - ($data['incentive_dist'][0][0]['incentive'] + $data['incentive_ret'][0][0]['incentive']) - ($data['commission_dist'][0][0]['commission'] + $data['commission_ret'][0][0]['commission']) - $data['mPos'][0][0]['amount'] - $data['payU'][0][0]['amount']; ?></td>
            </tr>
        </tbody>
    </table>
</div>