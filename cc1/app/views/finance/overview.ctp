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
            <li><a class="tablinks active" href="/finance/overview">Overview PnL Report</a></li>
        <?php
        }
        ?>
        <li><a class="tablinks" href="/finance/pnl">PnL Report</a></li>
        <?php
        if($this->Session->read('Auth.User.group_id') == SUPER_ADMIN){
        ?>
        <li><a class="tablinks " href="/finance/balanceSheet">Balance Sheet</a></li>
        <?php
        }
        ?>
    </ul>
</div>
<br/>
<form action="/finance/overview" method="post">
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
<table class="table table-bordered table-responsive display" style="width:100%;">            
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
        $all_service_pnl_array = array();
        foreach ($all_services as $service_id=>$service_name){
            if (array_key_exists($service_id,$gross_sale_array) || array_key_exists($service_id,$service_income_array) || array_key_exists($service_id,$service_expense_array)){
            ?>

            <tr>   
                <th style="background-color: #b44646;color:white;" colspan = "<?php echo $no_of_days+3;?>"><?php echo $service_name;?></th>
                <?php
                for($i=0;$i<$no_of_days;$i++){
                       echo "<th style='display:none'></th>";
                  }
                ?>
                <th style="display:none"></th>
                <th style="display:none"></th>
                

            </tr>
            <?php
            if(array_key_exists($service_id,$gross_sale_array)){
                ?>
                <tr>   
                    <th>Gross Sale</th>
                    <?php
                    $total = 0;
                    foreach($gross_sale_array as $key => $service_date_wise_sale){
                        if($key == $service_id){
                            foreach($service_date_wise_sale as $date => $sale){
                                $sale_value = !empty($sale)?$sale:0;
                                $total += $sale_value;
                                echo "<td>".$General->IND_money_format($sale_value)."</td>";
                            }
                        }
                    }
                    ?>
                    <th><?php echo $General->IND_money_format($total);?></th>
                    <th>Gross Sale</th>
                </tr>
                <?php
            }
            if(array_key_exists($service_id,$service_income_array)){
                ?>
                <tr>   
                    <th>Total Income</th>
                    <?php
                    $total = 0;
                    foreach($service_income_array as $key => $service_date_wise_income){
                        if($key == $service_id){
                            foreach($service_date_wise_income as $date => $income){
                                $total += $income;
                                echo "<td>".$General->IND_money_format($income)."</td>";
                            }
                        }
                    }
                    ?>
                    <th><?php echo $General->IND_money_format($total);?></th>
                    <th>Total Income</th>
                </tr>
                <?php
            }
            if(array_key_exists($service_id,$service_expense_array)){
            ?>
            <tr>   
                <th>Total Expense</th>
                <?php
                $total = 0;
                foreach($service_expense_array as $key => $service_date_wise_expense){
                    if($key == $service_id){
                        foreach($service_date_wise_expense as $date => $expense){
                            $total += $expense;
                            echo "<td>".$General->IND_money_format($expense)."</td>";
                        }
                    }
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th>Total Expense</th>
            </tr>

            <?php
            }
            ?>
            <tr>   
                <th>Profit / Loss</th>
                <?php
                $total = 0;
                foreach($service_pnl_array as $key => $service_date_wise_pnl){
                    if($key == $service_id){
                        foreach($service_date_wise_pnl as $date => $pnl){
                            $all_service_pnl_array[$date] = $all_service_pnl_array[$date] + $pnl;
                            $total += $pnl;
                            echo "<td>".$General->IND_money_format($pnl)."</td>";
                    
                        }
                    }
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th>Profit / Loss</th>
            </tr>
        
        <?php
        }
        }
        if(!empty($all_service_pnl_array)){
            ?>
            <tr>   
            <th style='background-color: steelblue;color:white'>Overall Income</th>
            <?php
            $total = 0;
                foreach($all_service_pnl_array as $all_service_pnl){
                    $total += $all_service_pnl;
                    if($all_service_pnl>0){
                        $background = "greenyellow";
                    }else{
                        $background = "#ff722e";
                    }
                    echo "<td style='background-color:".$background."'>".$General->IND_money_format($all_service_pnl)."</td>";
                }

                if($total>0){
                    $background = "greenyellow";
                }else{
                    $background = "#ff722e";
                }
                ?>
                <th style='background-color:<?php echo $background;?>'><?php echo $General->IND_money_format($total);?></th>
            <th style='background-color: steelblue;color:white'>Overall Income</th>
        </tr>
            <?php
        }
        if(!empty($accounting_categories_expense['Internal Expenses'])){
            ?>
                <tr>
                    <th style="background-color:#b44646;color: white;" colspan="<?php echo $no_of_days+3;?>">All Expenses</th>
                    <?php
                    for($i=0;$i<$no_of_days;$i++){
                           echo "<th style='display:none'></th>";
                      }
                    ?>
                    <th style="display:none"></th>
                    <th style="display:none"></th>
                </tr>
            <?php
                foreach($accounting_categories_expense as $category_name=>$subcategory_array){

                    if($category_name != "total_expense"){
                    ?>
                        <tr class="accordion-toggle" data-toggle="collapse" data-target="<?php echo str_replace(array(' ','/'),"_",$category_name);?>">
                          <th style="cursor: pointer;" colspan = "<?php echo $no_of_days+3;?>"><u><?php echo $category_name;?></u></th>
                          <?php
                            for($i=0;$i<$no_of_days;$i++){
                                   echo "<th style='display:none'></th>";
                              }
                            ?>
                          <th style="display:none"></th>
                          <th style="display:none"></th>
                        </tr>
                        <?php
                        
                        foreach($subcategory_array as $sub_category_name=>$date_array){
                            ?>
                              <tr class="accordion-body <?php echo str_replace(array(' ','/'),"_",$category_name);?>" style="background-color: #eee">
                                    <td><?php echo $sub_category_name;?></td>
                                    <?php
                                        $total = 0;
                                        for($i=0;$i<$no_of_days;$i++){
                                            $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));
                                            $accounting_expense = isset($date_array[$date])?$date_array[$date]:0;
                                            $total += $accounting_expense;
                                        ?>
                                            <td><?php echo $General->IND_money_format($accounting_expense);?></td>
                                        <?php
                                        }
                                    ?>
                                    <th><?php echo $General->IND_money_format($total);?></th>
                                    <td><?php echo $sub_category_name;?></td>
                              </tr>
                            <?php                        
                        }
                   
                    }
                }
                ?>
                <tr>   
                    <th style="background-color:steelblue;color:white">Overall Expense</th>
                    <?php
                    $total = 0;
                    foreach($accounting_categories_expense['total_expense'] as $total_expense){
                        $total += $total_expense;
                        ?>
                        <td style="background-color: #ff722e"><?php echo $General->IND_money_format($total_expense);?></td>
                        <?php
                    }
                    ?>
                    <th style="background-color: #ff722e"><?php echo $General->IND_money_format($total);?></th>
                    <th style="background-color:steelblue;color:white">Overall Expense</th>
                </tr>
                <?php
        }
        ?>

         <tr>   
                    <th style="background-color:#4c6314;color:white">Overall Profit / Loss</th>
                    <?php
                    $total = 0;
                    for($i=0;$i<$no_of_days;$i++){
                        $date = date('Y-m-d', strtotime($from_date. ' + '.$i.' day'));

                        $overall_pnl = $all_service_pnl_array[$date] - $accounting_categories_expense['total_expense'][$date];
                        $total += $overall_pnl;

                        if($overall_pnl>0){
                            $background = "greenyellow";
                        }else{
                            $background = "#ff722e";
                        }
                    ?>
                        <td style='background-color:<?php echo $background;?>'><?php echo $General->IND_money_format($overall_pnl);?></td>
                    <?php
                        if($total>0){
                            $background = "greenyellow";
                        }else{
                            $background = "#ff722e";
                        }
                    }
                ?>
                    <th style="background-color: <?php echo $background;?>"><?php echo $General->IND_money_format($total);?></th>
                    <th style="background-color:#4c6314;color:white">Overall Profit / Loss</th>
        </tr>

        <tr>   
                <th style="background-color:#4c6314;color:white">GST Liability</th>
                <?php
                $total = 0;
                foreach($gst_liability_array as $gst_liability){
                    $total += $gst_liability;
                    echo "<td>".$General->IND_money_format($gst_liability)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style="background-color:#4c6314;color:white">GST Liability</th>
        </tr>

        <tr>   
                <th style="background-color:#4c6314;color:white">GST Vendor Liability</th>
                <?php
                $total = 0;
                foreach($gst_vendor_liability_array as $gst_vendor_liability){
                    $total += $gst_vendor_liability;
                    echo "<td>".$General->IND_money_format($gst_vendor_liability)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style="background-color:#4c6314;color:white">GST Vendor Liability</th>
        </tr>

        <tr>   
                <th style="background-color:#4c6314;color:white">Vendor GST Asset</th>
                <?php
                $total = 0;
                foreach($gst_asset_array as $gst_asset){
                    $total += $gst_asset;
                    echo "<td>".$General->IND_money_format($gst_asset)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style="background-color:#4c6314;color:white">Vendor GST Asset</th>
        </tr>

        <tr>   
                <th style="background-color:#4c6314;color:white">Net GST</th>
                <?php
                $total = 0;
                foreach($net_gst_array as $net_gst){
                    $total += $net_gst;
                    echo "<td>".$General->IND_money_format($net_gst)."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style="background-color:#4c6314;color:white">Net GST</th>
        </tr>
        

        
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
