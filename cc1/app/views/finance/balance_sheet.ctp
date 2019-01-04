<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/boot/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="/boot/css/buttons.dataTables.min.css">

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab a {
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

    .tab a:hover {
        background-color: #428bca!important;
        color: #fff!important;
    }

    .tab a.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    
    thead{
       background-color: #eee;
       color: #000;
    }
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <?php
        if($this->Session->read('Auth.User.group_id') == SUPER_ADMIN){
        ?>
            <li><a class="tablinks " href="/finance/overview">Overview PnL Report</a></li>
        <?php
        }
        ?>
        <li><a class="tablinks" href="/finance/pnl">PnL Report</a></li>
        <?php
        if($this->Session->read('Auth.User.group_id') == SUPER_ADMIN){
        ?>
        <li><a class="tablinks active" href="/finance/balanceSheet">Balance Sheet</a></li>
        <?php
        }
        ?>
    </ul>
</div>
<br/>
<form action="/finance/balanceSheet" method="post">
    <?php
    $error = $this->Session->flash();
    if($error != ""){
        ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php
    }
    ?>

<br/>
<div style="float:left;"><span style="font-weight:bold;">From Date</span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="from_date" name="from_date" value="<?php echo $from_date; ?>"></div>
<div style="float:left;"><span style="font-weight:bold;padding-left: 30px;">To Date</span></div>
<div style="float:left;"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: 15px;" id="to_date" name="to_date" value="<?php echo $to_date; ?>"></div>

<div style="float:left; margin-left: 30px; margin-top: -5px;"><input class="btn btn-primary" type="submit" value="Submit" style="padding: 5px 10px;"></div>
</form>
<br/><br/><br/>
<table class="table table-bordered table-responsive display" >            
    <thead>
    	<tr>   
            <th></th>
            <?php
            for($i=0;$i<$no_of_days;$i++){
                   echo "<th>".date('Y-m-d', strtotime($from_date. ' + '.$i.' day'))."</th>";
              }
            ?>
            <th>Total</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($bank_balance_exist || $recharge_utility_inventory_exist || $recharge_utility_advance_exist || $vendor_prepaid_postpaid_exist || $other_asset_exist){
        ?>
        <tr>   
            <th style="background-color: steelblue;color:white;" colspan = "<?php echo $no_of_days+3;?>">Assets</th>
            <?php
            for($i=0;$i<$no_of_days;$i++){
                   echo "<th style='display:none'></th>";
              }
            ?>
            <th style="display:none"></th>
            <th style="display:none"></th>
        </tr>
        <?php
        }

        if($bank_balance_exist){
        ?>
            <tr>   
                <td>Bank Balance</td>
                <?php
                $total = 0;
                foreach($bank_balance_array as $bank_balance){
                    $total += $bank_balance;
                    echo "<td>".$General->IND_money_format($bank_balance)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <td>Bank Balance</td>
            </tr>
        <?php
        }

        if($recharge_utility_inventory_exist){
        ?>
            <tr>   
                <td>Recharge & Utility Inventory</td>
                <?php
                $total = 0;
                foreach($recharge_utility_inventory_array as $recharge_utility_inventory){
                    $total += $recharge_utility_inventory;
                    echo "<td>".$General->IND_money_format($recharge_utility_inventory)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <td>Recharge & Utility Inventory</td>
            </tr>
        <?php
        }

        if($recharge_utility_advance_exist){
        ?>
            <tr>   
                <td>Recharge & Utility Advance</td>
                <?php
                $total = 0;
                foreach($recharge_utility_advance_array as $recharge_utility_advance){
                    $total += $recharge_utility_advance;
                    echo "<td>".$General->IND_money_format(abs($recharge_utility_advance))."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format(abs($total));?></th>
                <td>Recharge & Utility Advance</td>
            </tr>
        <?php
        }


        if(!empty($vendor_prepaid_postpaid_exist)){
            ?>

            <tr class="accordion-toggle" data-toggle="collapse" data-target="vendor_prepaid_postpaid">
              <th style="cursor: pointer;" colspan = "<?php echo $no_of_days+3;?>"><u>Vendor</u></th>
              <?php
                for($i=0;$i<$no_of_days;$i++){
                       echo "<th style='display:none'></th>";
                  }
                ?>
                <th style="display:none"></th>
                <th style="display:none"></th>
            </tr>

            <?php
                foreach($vendor_prepaid_postpaid_array as $vendor=>$date_array){
            ?>
                      <tr class="accordion-body vendor_prepaid_postpaid" style="background-color: #eee">
                            <td><?php echo $vendor;?></td>
                            <?php
                                $total = 0;
                                for($i=0;$i<$no_of_days;$i++){
                                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                                    $vendor_asset_data = isset($date_array[$date])?$date_array[$date]:0;
                                    $total += $vendor_asset_data;
                                    $total_assets[$date] += $vendor_asset_data;
                                ?>
                                    <td><?php echo $General->IND_money_format($vendor_asset_data);?></td>
                                <?php
                                }
                            ?>
                            <th><?php echo $General->IND_money_format($total);?></th>
                            <td><?php echo $vendor;?></td>
                      </tr>
            <?php 
                }
        }

        if(!empty($other_asset_exist)){
            ?>

            <tr class="accordion-toggle" data-toggle="collapse" data-target="other_asset">
              <th style="cursor: pointer;" colspan = "<?php echo $no_of_days+3;?>"><u>Others</u></th>
              <?php
                for($i=0;$i<$no_of_days;$i++){
                       echo "<th style='display:none'></th>";
                  }
                ?>
                <th style="display:none"></th>
                <th style="display:none"></th>
            </tr>

            <?php
                foreach($other_asset_array as $name=>$date_array){
            ?>
                      <tr class="accordion-body other_asset" style="background-color: #eee">
                            <td><?php echo $name;?></td>
                            <?php
                                $total = 0;
                                for($i=0;$i<$no_of_days;$i++){
                                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                                    $other_asset_data = isset($date_array[$date])?$date_array[$date]:0;
                                    $total += $other_asset_data;
                                    $total_assets[$date] += $other_asset_data;
                                ?>
                                    <td><?php echo $General->IND_money_format($other_asset_data);?></td>
                                <?php
                                }
                            ?>
                            <th><?php echo $General->IND_money_format($total);?></th>
                            <td><?php echo $name;?></td>
                      </tr>
            <?php 
                }
        }
        if($bank_balance_exist || $recharge_utility_inventory_exist || $recharge_utility_advance_exist || $vendor_prepaid_postpaid_exist || $other_asset_exist){
        ?>
                <tr>   
                    <th>Total Assets (A)</th>
                    <?php
                    $total = 0;
                    foreach($total_assets as $total_asset){
                        $total += $total_asset;
                        echo "<td>".$General->IND_money_format($total_asset)."</td>";
                    }
                    ?>
                    <th><?php echo $General->IND_money_format($total);?></th>
                    <th>Total Assets (A)</th>
                </tr>
        <?php
        }
        ?>

        <?php
        if($float_balance_exist || $pending_limit_exist || $other_liability_exist || $recharge_utility_credit_exist){
        ?>
        <tr>   
            <th style="background-color: steelblue;color:white;" colspan = "<?php echo $no_of_days+3;?>">Liabilities</th>
            <?php
            for($i=0;$i<$no_of_days;$i++){
                   echo "<th style='display:none'></th>";
              }
            ?>
            <th style="display:none"></th>
            <th style="display:none"></th>
        </tr>
        <?php
        }

        if($float_balance_exist){
        ?>
            <tr>   
                <td>Float Balance</td>
                <?php
                $total = 0;
                foreach($float_balance_array as $float_balance){
                    $total += $float_balance;
                    echo "<td>".$General->IND_money_format($float_balance)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <td>Float Balance</td>
            </tr>
        <?php
        }

        if($pending_limit_exist){
        ?>
            <tr>   
                <td>Pending Limit</td>
                <?php
                $total = 0;
                foreach($pending_limit_array as $pending_limit){
                    $total += $pending_limit;
                    echo "<td>".$General->IND_money_format($pending_limit)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <td>Pending Limit</td>
            </tr>
        <?php
        }

        if($recharge_utility_credit_exist){
        ?>
            <tr>   
                <td>Recharge & Utility Credit</td>
                <?php
                $total = 0;
                foreach($recharge_utility_credit_array as $recharge_utility_credit){
                    $total += $recharge_utility_credit;
                    echo "<td>".$General->IND_money_format($recharge_utility_credit)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <td>Recharge & Utility Credit</td>
            </tr>
        <?php
        }

        if(!empty($other_liability_exist)){
            ?>

            <tr class="accordion-toggle" data-toggle="collapse" data-target="other_liability">
              <th style="cursor: pointer;" colspan = "<?php echo $no_of_days+3;?>"><u>Others</u></th>
              <?php
                for($i=0;$i<$no_of_days;$i++){
                       echo "<th style='display:none'></th>";
                  }
                ?>
                <th style="display:none"></th>
                <th style="display:none"></th>
            </tr>

            <?php
                foreach($other_liability_array as $name=>$date_array){
            ?>
                      <tr class="accordion-body other_liability" style="background-color: #eee">
                            <td><?php echo $name;?></td>
                            <?php
                                $total = 0;
                                for($i=0;$i<$no_of_days;$i++){
                                    $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                                    $other_liability_data = isset($date_array[$date])?$date_array[$date]:0;
                                    $total += $other_liability_data;
                                    $total_liability[$date] += $other_liability_data;
                                ?>
                                    <td><?php echo $General->IND_money_format($other_liability_data);?></td>
                                <?php
                                }
                            ?>
                            <th><?php echo $General->IND_money_format($total);?></th>
                            <td><?php echo $name;?></td>
                      </tr>
            <?php 
                }
        }

        if($float_balance_exist || $pending_limit_exist || $other_liability_exist || $recharge_utility_credit_exist){
            ?>
                <tr>   
                    <th>Total Liability (L)</th>
                    <?php
                    $total = 0;
                    foreach($total_liability as $total_liab){
                        $total += $total_liab;
                        echo "<td>".$General->IND_money_format($total_liab)."</td>";
                    }
                    ?>
                    <th><?php echo $General->IND_money_format($total);?></th>
                    <th>Total Liability (L)</th>
                </tr>
            <?php
        }


        if($bank_balance_exist || $recharge_utility_inventory_exist || $recharge_utility_advance_exist || $vendor_prepaid_postpaid_exist || $other_asset_exist || $float_balance_exist || $pending_limit_exist || $other_liability_exist || $recharge_utility_credit_exist){
            ?>
                <tr>   
                    <th>A - L</th>
                    <?php
                    $total = 0;
                    for($i=0;$i<$no_of_days;$i++){
                        $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                        $total += ($total_assets[$date] - $total_liability[$date]);
                        echo "<td>".$General->IND_money_format($total_assets[$date] - $total_liability[$date])."</td>";
                    }
                    ?>
                    <th><?php echo $General->IND_money_format($total);?></th>
                    <th>A - L</th>
                </tr>
            <?php
        }
        ?>

        
        
        
    </tbody>
            
    
</table>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.FixedHeaderDataTables.min.js"></script>
<script type="text/javascript" src="/boot/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="/boot/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/boot/js/jszip.min.js"></script>
<script type="text/javascript" src="/boot/js/buttons.html5.min.js"></script>

<script>
    
    $('.accordion-toggle').click(function() {
        var attr = $(this).attr("data-target");
        $("."+attr).toggleClass('collapse');
    });

    $('#from_date,#to_date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });

    var table = $('.table').DataTable( {
        fixedHeader: {
            header: true,
            footer: false
        },
        "searching": false,
        "paging":   false,
        "ordering": false,
        "info":     false,
        dom: 'Bfrtip',
        buttons: [
            'excel'
        ]
    } );
    
</script>
