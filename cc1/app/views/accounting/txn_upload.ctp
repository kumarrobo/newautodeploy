<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">

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
    
    .fix-width {
        width: 300px;
    }
</style>
<div class="tab">
    <ul class="nav nav-tabs">
        <li><button class="tablinks active" onclick="window.location='/accounting/txnUpload'">File Upload</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div>
<br/><br/>
<form action='/accounting/autoUpload' method='post' enctype="multipart/form-data">
    <div style="float:left; margin-top: 5px; width: 135px;">Bank : </div><div style="float:left;"><select name="bank" class="form-control fix-width">
        <option value='' disabled selected>Select Bank</option>
        <?php foreach($bank_accounts as $ba) { ?>
            <option value='<?php echo $ba['id']; ?>'><?php echo $ba['name']; ?></option>
        <?php } ?>
    </select></div><br/><br/><br/>
    <div style="float:left;margin-top: 5px; width: 135px;">Bank Statement : </div><div style="float:left;"><input type="file" name="bank_statement" class="form-control fix-width"></div><br/><br/><br/>
    <div style="float:left;"><input class="btn btn-primary" type="submit" value='Submit'></div><div style="float:left; margin-top: 5px; margin-left: 50px; color: red;"><?php echo $this->Session->flash(); ?></div>
</form>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
