<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

<style>
/*    table { border-collapse: collapse; }
    tr:nth-child(<?php // echo count($data['data']) + 3; ?>) { border: 1px solid thin; }*/
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
        <li><button class="tablinks active" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>
</div><br/><br/><br>

<div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">Select Date : </div><div><input type="text" class="form-control" style="width: 110px; margin-left: 15px;" id="date" onchange="window.location='/accounting/closingBalanceReport/' + this.value" value="<?php echo $date; ?>"></div>
<br/><br/><br/>

<table class='table table-bordered table-hover' style='width: 500px;'>
    <thead>
        <tr>
            <td><center>Bank</center></td>
            <td><center>Total</center></td>
            <?php foreach ($data['banks'] as $b) { ?>
            <td><center><?php echo $b['bank_name']; ?></center></td>
            <?php } ?>
        </tr>
    </thead>
    <tbody style="color: #4e4e4e;">
        <tr>
            <td style="font-weight: bold;">Opening</td>
            <td style="font-weight: bold;"><?php echo $data['total_closing'] ? $General->IND_money_format(round($data['total_closing'])) : 0; ?></td>
            <?php foreach ($data['banks'] as $bi=>$b) { ?>
            <td><center><?php echo !$data['yesterdays_closing'][$bi]['closing'] ? 0 : $General->IND_money_format(round($data['yesterdays_closing'][$bi]['closing'])); ?></center></td>
            <?php } ?>
        </tr>
    </tbody>
</table>

<br /><br />
    
<table class='table table-bordered table-hover' style='width: 500px;'>
    <thead>
        <tr>
            <td rowspan="2"><center>Txn Status</center></td>
            <td rowspan="2"><center>Category</center></td>
            <td colspan="2"><center>Total</center></td>
            <?php foreach ($data['banks'] as $b) { ?>
            <td colspan="2"><center><?php echo $b['bank_name']; ?></center></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Credit</td>
            <td>Debit</td>
            <?php foreach ($data['banks'] as $b) { ?>
            <td>Credit</td>
            <td>Debit</td>
            <?php } ?>
        </tr>
    </thead>
    <tbody style="color: #4e4e4e;">
        <?php foreach ($data['category'] as $idx=>$category) { if ($category['txn_type'] == 'Cr') { $total['all']['Cr'] = $total['all']['Cr'] + ($category['total_'.$idx] - ($idx == 1 ? $data['total_refund'] : 0)); ?>
        <tr>
            <td style="font-weight: bold;">Cr</td>
            <td style="font-weight: bold;"><?php echo $category['category'] . ($category['subcategory'] != '' ? ' - '.$category['subcategory'] : ''); ?></td>
            <td style="font-weight: bold;"><?php echo $category['total_'.$idx] - ($idx == 1 ? $General->IND_money_format(round($data['total_refund'])) : 0); ?></td>
            <td style="font-weight: bold;"><center>-</center></td>
            <?php foreach ($category['banks'] as $bi=>$b) { $total[$bi]['Cr']  = $General->IND_money_format(round($total[$bi]['Cr'] + ($b['amount'] - ($idx == 1 ? $data['refund'][$bi]['refund'] : 0)))); ?>
            <td><?php echo $General->IND_money_format(round($b['amount'] - ($idx == 1 ? $General->IND_money_format(round($data['refund'][$bi]['refund'])) : 0))); ?></td>
            <td><center>-</center></td>
            <?php } ?>
        </tr>
        <?php } } ?>
        <?php if ($data['total_refund'] > 0) { ?>
        <tr>
            <td style="font-weight: bold;">Cr</td>
            <td style="font-weight: bold;">Refund Pending</td>
            <td style="font-weight: bold;"><?php echo $General->IND_money_format(round($data['total_refund'])); ?></td>
            <td style="font-weight: bold;"><center>-</center></td>
            <?php foreach ($data['refund'] as $r) { ?>
            <td><?php echo !$r['refund'] ? 0 : $General->IND_money_format(round($r['refund'])); ?></td>
            <td><center>-</center></td>
            <?php } ?>
        </tr>
        <?php } ?>
        <?php if ($data['credit_suspense'] > 0) { ?>
        <tr>
            <td style="font-weight: bold;">Cr</td>
            <td style="font-weight: bold;">Credit Suspense</td>
            <td style="font-weight: bold;"><?php echo $General->IND_money_format(round($data['credit_suspense'])); ?></td>
            <td style="font-weight: bold;"><center>-</center></td>
            <?php
                foreach ($data['suspense'] as $si=>$s) {
                        $total[$si]['Cr']  = $General->IND_money_format(round($total[$si]['Cr'] + (!$s['Cr'] ? 0 : $s['Cr'])));
            ?>
            <td><?php if ($s['Cr']) { ?><a href="/accounting/autoUpload/0/<?php echo $date; ?>/<?php echo $date; ?>/<?php echo $si; ?>/0/Cr"><?php echo $General->IND_money_format(round($s['Cr'])); ?></a><?php } else { echo "0"; } ?></td>
            <td><center>-</center></td>
            <?php } ?>
        </tr>
        <?php } ?>
        <?php foreach ($data['category'] as $idx=>$cat) { if ($cat['txn_type'] == 'Dr') { $total['all']['Dr'] = $total['all']['Dr'] + $cat['total_'.$idx];
        ?>
        <tr>
            <td style="font-weight: bold;">Dr</td>
            <td style="font-weight: bold;"><?php echo $cat['category'] . ($cat['subcategory'] != '' ? ' - '.$cat['subcategory'] : ''); ?></td>
            <td style="font-weight: bold;"><center>-</center></td>
            <td style="font-weight: bold;"><?php echo $General->IND_money_format(round($cat['total_'.$idx])); ?></td>
            <?php foreach ($cat['banks'] as $bsi=>$bs) { $total[$bsi]['Dr']  = $General->IND_money_format(round($total[$bsi]['Dr'] + ($bs['amount'] ? $bs['amount'] : 0))); ?>
            <td><center>-</center></td>
            <td><?php echo $bs['amount'] ? $General->IND_money_format(round($bs['amount'])) : 0; ?></td>
            <?php } ?>
        </tr>
        <?php } } ?>
        <?php if ($data['debit_suspense'] > 0) { ?>
        <tr>
            <td style="font-weight: bold;">Dr</td>
            <td style="font-weight: bold;">Debit Suspense</td>
            <td style="font-weight: bold;"><center>-</center></td>
            <td style="font-weight: bold;"><?php echo $General->IND_money_format(round($data['debit_suspense'])); ?></td>
            <?php foreach ($data['suspense'] as $si=>$s) { $total[$si]['Dr']  = $total[$si]['Dr'] + (!$s['Dr'] ? 0 : $s['Dr']); ?>
            <td><center>-</center></td>
            <td><?php if ($s['Dr']) { ?><a href="/accounting/autoUpload/0/<?php echo $date; ?>/<?php echo $date; ?>/<?php echo $si; ?>/0/Dr"><?php echo $General->IND_money_format(round($s['Dr'])); ?></a><?php } else { echo "0"; } ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
        <tr style="font-weight: bold;">
            <td colspan="2"><center>Total</center></td>
            <td><?php echo $General->IND_money_format(round($total['all']['Cr'] + $data['credit_suspense'])); ?></td>
            <td><?php echo $General->IND_money_format(round($total['all']['Dr'] + $data['debit_suspense'])); ?></td>
            <?php foreach ($data['banks'] as $bi=>$b) { ?>
            <td><?php echo $total[$bi]['Cr'] ? $General->IND_money_format(round($total[$bi]['Cr'])) : 0; ?></td>
            <td><?php echo $total[$bi]['Dr'] ? $General->IND_money_format(round($total[$bi]['Dr'])) : 0; ?></td>
            <?php } ?>
        </tr>
    </tbody>
</table>

<br /><br />

<table class='table table-bordered table-hover' style='width: 500px;'>
    <thead>
        <tr>
            <td><center>Bank</center></td>
            <td><center>Total</center></td>
            <?php foreach ($data['banks'] as $b) { ?>
            <td><center><?php echo $b['bank_name']; ?></center></td>
            <?php } ?>
        </tr>
    </thead>
    <tbody style="color: #4e4e4e;">
<!--        <tr>
            <td style="font-weight: bold;">Closing</td>
            <td style="font-weight: bold;"><?php echo $General->IND_money_format(round($data['total_closing'] + ($total['all']['Cr'] + $data['credit_suspense']) - ($total['all']['Dr'] + $data['debit_suspense']))); ?></td>
            <?php foreach ($data['banks'] as $bi=>$b) { ?>
            <td><center id="closing_<?php echo $bi; ?>"><?php echo (!$data['yesterdays_closing'][$bi]['closing'] ? 0 : $General->IND_money_format(round($data['yesterdays_closing'][$bi]['closing'])) + $General->IND_money_format(round($total[$bi]['Cr'] - $total[$bi]['Dr']))); ?></center></td>
            <?php } ?>
        </tr>-->
        <tr>
            <td style="font-weight: bold;">Closing</td>
            <td style="font-weight: bold;"><?php echo $data['total_todays_closing'] ? $General->IND_money_format(round($data['total_todays_closing'], 2)) : 0; ?></td>
            <?php foreach ($data['banks'] as $bi=>$b) { ?>
            <td><center><?php echo !$data['todays_closing'][$bi]['closing'] ? 0 : $General->IND_money_format(round($data['todays_closing'][$bi]['closing'])); ?></center></td>
            <?php } ?>
        </tr>
        <tr style="color: red;">
            <td style="font-weight: bold;">Difference</td>
            <?php $final_closing = $General->IND_money_format(round($data['total_todays_closing'] - ($data['total_closing'] + ($total['all']['Cr'] + $data['credit_suspense']) - ($total['all']['Dr'] + $data['debit_suspense'])), 2)); ?>
            <td style="font-weight: bold;"><?php echo $final_closing == 0 ? "<span style='color: green;'>Closing Matched</span>" : $General->IND_money_format(round($final_closing)); ?></td>
            <?php foreach ($data['banks'] as $bi=>$b) { $diff_closing = $General->IND_money_format(round($data['todays_closing'][$bi]['closing'] - ($data['yesterdays_closing'][$bi]['closing'] + $total[$bi]['Cr'] - $total[$bi]['Dr']))); ?>
            <td><center><?php echo $diff_closing == 0 ? "<span style='color: green; font-weight: bold;'>Closing Matched</span>" : $General->IND_money_format(round($diff_closing)) . "<br/><br/><center><input type='text' id='manual_".$bi."' class='form-control' style='width: 125px;'><br><div id='diff_button_".$bi."'><button class='btn btn-primary' onclick='showDiff(".$bi.");'>Update Closing</button></div></center>"; ?></center></td>
            <?php } ?>
        </tr>
    </tbody>
</table>

<br/>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<script>
    
    $('#date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });

    function showDiff (val) {
        
        var date = '<?php echo $date ?>';
        var bank = val;
        var bal  = $('#manual_'+val).val();

        if (!isNaN(bal)) {
            $.post('/accounting/updateClosing/', {'date': date, 'bank': bank, 'balance': bal}, function(e) {
                if(e == 1) {
//                    var diff = parseFloat(bal) - parseFloat($('#closing_'+val).html());
//                    if (diff != 0) {
//                        $('#diff_button_'+val).html("<div style='color: red; font-weight: bold;'>Difference : " + diff + "</div>");
//                    } else {
//                        $('#diff_button_'+val).html("<div style='color: green; font-weight: bold;'><center>Balance Matched !!!</center></div>");
//                    }
                    location.reload();
                } else {
                    alert("* Can't update this closing balance now !!!");
                }
            }, 'json');
        }
    }

</script>
