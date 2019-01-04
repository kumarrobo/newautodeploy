<!--<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
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

    .fix-width {
        width: 300px;
    }

    thead{
        background-color: #428bca;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f6f6f6;
    }
</style>
<div>
    <div class="tab">
        <button class="tablinks" onclick="window.location = '/accounting/txnUpload'">File Upload</button>
        <button class="tablinks" onclick="window.location = '/accounting/autoUpload'">Txn Entry</button>
        <button class="tablinks" onclick="window.location = '/accounting/bankTxnListing'">Txn Listing</button>
        <button class="tablinks" onclick="window.location = '/accounting/closingBalanceReport'">Closing Balance Report</button>
        <button class="tablinks" onclick="window.location = '/accounting/limitReconsilationReport'">Limit Reconsilation Report</button>
        <button class="tablinks" onclick="window.location = '/accounting/bankStatements'">Bank Statements</button>
        <button class="tablinks active" onclick="window.location = '/accounting/distributorsData'">Distributors Data</button>
    </div><br/>

    <h2> <tr><center><td>Distributors Data</td></center></tr></h2>
  
    <tr>
        <td>
            <table class="table table-bordered table-hover table-striped table-responsive" style="border-collapse: collapse;border: 1px solid black; width: 450px;">
      
                <thead>
                <tr>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Sr.Id</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>ID</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>User ID</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Name</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Mobile</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Reference</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Margin(%)</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Incentive(%)</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>SD/One Time</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>RM Name</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Opening Balance &nbsp;<span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Transferred Today <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Current Balance <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></center> </th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Kits Left</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Slab</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Area</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Commission Type</center></th>
                <th style="border: 1px solid black;color:#f6f6f6;padding: 10px;"><center>Created</center></th>
                </tr>
                </thead> 
<tbody>
    <?php
    $i = 0;
    foreach ($result as $datas) {
        echo '<tr><td style="border: 1px solid black;padding: 5px;"><b><center>' . ($i = $i + 1) . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['id'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['user_id'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['company'] ."-".$datas['distributors']['shopname']. '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['users']['mobile'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['reference'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['margin'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['incentive'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['sd_amt'] . "/" . $datas['distributors']['one_time'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['rm']['name'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['users']['opening_balance'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center></center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['users']['balance'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['kits'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['slabs']['name'] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['area_range'] . "-" . $datas['distributors']['city'] . '</center></b></td>';
        $commission_type = array(0 => 'Primary', 1 => 'Tertiary');
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $commission_type[$datas['distributors']['commission_type']] . '</center></b></td>';
        echo '<td style="border: 1px solid black;padding: 5px;"><b><center>' . $datas['distributors']['created'] . '</center></b></td></tr>';
    }
    
    ?>
</tbody>
</table>
        <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5 text-right">
                <?php echo $this->element('pagination'); ?>
                </div>
        </div>  
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        
            <script>
                
              function goToPage(page = 1) {
                  window.location="/accounting/distributorsData/?page="+page;
//                   
                }
            </script>
            

</div>  -->