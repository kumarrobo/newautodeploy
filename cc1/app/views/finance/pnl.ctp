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
        <li><a class="tablinks active" href="/finance/pnl">PnL Report</a></li>
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
<form action="/finance/pnl" method="post">
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

<div style="float:left;"><span style="font-weight: bold; padding-left: 30px;">Service</span></div>
<div style="float:left;">
    <select class="form-control" id="service" name="service" style='width: 200px; margin-top: -5px; margin-left: 15px;'>
        <option value="">-Select Service-</option>
		<?php foreach ($services as $key=>$service):?>
		<option value="<?php echo $key; ?>" <?php  if($key==$serviceval){ echo"selected"; } ?>><?php echo $service; ?></option>
		<?php endforeach; ?>
    </select>
</div>
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
        if($serviceval == RECHARGE){
            if($modem_closing_exist || $total_modem_sale_exist || $total_modem_invested_exist || $modem_earning_exist || $modem_gst_asset_exist){
        ?>
            <tr>   
                <th style="background-color: steelblue;color:white;" colspan = "<?php echo $no_of_days+3;?>">Modem</th>
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
                 if($total_modem_sale_exist){
                ?>
                    <tr>   
                        <td>Sale</td>
                        <?php
                        $total_modem_sale = 0;
                        foreach($total_modem_sale_data as $modem_sale){
                            $total_modem_sale += $modem_sale['value'];
                            echo "<td>".$General->IND_money_format($modem_sale['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_modem_sale);?></th>
                        <td>Sale</td>
                    </tr>
                <?php
                }
                if($modem_closing_exist){
                ?>
                    <tr>   
                        <td>Closing</td>
                        <?php
                        $total_modem_closing = 0;
                        foreach($modem_closing_data as $modem_closing){
                            $total_modem_closing += $modem_closing['value'];
                            echo "<td>".$General->IND_money_format($modem_closing['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_modem_closing);?></th>
                        <td>Closing</td>
                    </tr>
                <?php
                }
                if($total_modem_invested_exist){
                ?>
                    <tr>   
                        <td>Invested</td>
                        <?php
                        $total = 0;
                        foreach($total_modem_invested_data as $total_modem_invested){
                            $total += $total_modem_invested['value'];
                            echo "<td>".$General->IND_money_format($total_modem_invested['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total);?></th>
                        <td>Invested</td>
                    </tr>
                <?php
                }
                if($modem_earning_exist){
                ?>
                    <tr>   
                        <td>Earning</td>
                        <?php
                        $total_modem_earning = 0;
                        foreach($modem_earning_data as $modem_earning){
                            $total_modem_earning += $modem_earning['value'];
                            echo "<td>".$General->IND_money_format($modem_earning['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_modem_earning);?></th>
                        <td>Earning</td>
                    </tr>
                    <tr>   
                        <td>Earning (%)</td>
                        <?php
                        foreach($modem_earning_in_percentage_data as $modem_earning_in_percentage){
                            echo "<td>".$modem_earning_in_percentage['value']."%</td>";
                        }
                        ?>
                        <th><?php echo ROUND(($total_modem_earning / $total_modem_sale) * 100,2);?>%</th>
                        <td>Earning (%)</td>
                    </tr>
                <?php
                }
                if($modem_gst_asset_exist){
                ?>
                    <tr>   
                        <td>GST Asset</td>
                        <?php
                        $total = 0;
                        foreach($modem_gst_asset_data as $modem_gst_asset){
                            $total += $modem_gst_asset['value'];
                            echo "<td>".$General->IND_money_format($modem_gst_asset['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total);?></th>
                        <td>GST Asset</td>
                    </tr>
                <?php
                }
                if($api_closing_exist){
            ?>
                <tr>   
                    <th style="background-color: steelblue;color:white;" colspan = "<?php echo $no_of_days+3;?>">API</th>
                    <?php
                    for($i=0;$i<$no_of_days;$i++){
                           echo "<th style='display:none'></th>";
                      }
                    ?>
                    <th style="display:none"></th>
                    <th style="display:none"></th>
                </tr>
                <tr>   
                    <td>Closing</td>
                    <?php
                    $total_api_closing = 0;
                    foreach($api_closing_data as $api_closing){
                        $total_api_closing += $api_closing['value'];
                        echo "<td>".$General->IND_money_format($api_closing['value'])."</td>";
                    }
                    ?>
                    <th><?php echo $General->IND_money_format($total_api_closing);?></th>
                    <td>Closing</td>
                </tr>
            <?php
            }
                if($total_api_p2p_sale_exist || $total_api_p2p_invested_exist || $api_p2p_earning_exist || $api_p2p_gst_asset_exist){
            ?>
                <tr>   
                    <th style="background-color: steelblue;color:white;" colspan = "<?php echo $no_of_days+3;?>">API (P2P)</th>
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
            if($total_api_p2p_sale_exist){
                ?>
                    <tr>   
                        <td>Sale</td>
                        <?php
                        $total_p2p_sale = 0;
                        foreach($total_api_p2p_sale_data as $total_api_p2p_sale){
                            $total_p2p_sale += $total_api_p2p_sale['value'];
                            echo "<td>".$General->IND_money_format($total_api_p2p_sale['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_p2p_sale);?></th>
                        <td>Sale</td>
                    </tr>
                <?php
                }
                if($total_api_p2p_invested_exist){
                ?>
                    <tr>   
                        <td>Invested</td>
                        <?php
                        $total = 0;
                        foreach($total_api_p2p_invested_data as $total_api_p2p_invested){
                            $total += $total_api_p2p_invested['value'];
                            echo "<td>".$General->IND_money_format($total_api_p2p_invested['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total);?></th>
                        <td>Invested</td>
                    </tr>
                <?php
                }
                if($api_p2p_earning_exist){
                ?>
                    <tr>   
                        <td>Earning</td>
                        <?php
                        $total_p2p_earning = 0;
                        foreach($api_p2p_earning_data as $api_p2p_earning){
                            $total_p2p_earning += $api_p2p_earning['value'];
                            echo "<td>".$General->IND_money_format($api_p2p_earning['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_p2p_earning);?></th>
                        <td>Earning</td>
                    </tr>
                    <tr>   
                        <td>Earning (%)</td>
                        <?php
                        foreach($api_p2p_earning_in_percentage_data as $api_p2p_earning_in_percentage){
                            echo "<td>".$api_p2p_earning_in_percentage['value']."%</td>";
                        }
                        ?>
                        <th><?php echo ROUND(($total_p2p_earning / $total_p2p_sale) * 100,2);?>%</th>
                        <td>Earning (%)</td>
                    </tr>
                <?php
                }
                if($api_p2p_gst_asset_exist){
                ?>
                    <tr>   
                        <td>GST Asset</td>
                        <?php
                        $total = 0;
                        foreach($api_p2p_gst_asset_data as $api_p2p_gst_asset){
                            $total += $api_p2p_gst_asset['value'];
                            echo "<td>".$General->IND_money_format($api_p2p_gst_asset['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total);?></th>
                        <td>GST Asset</td>
                    </tr>
                <?php
                }
            if($total_api_p2a_sale_exist || $total_api_p2a_invested_exist || $api_p2a_earning_exist || $api_p2a_gst_asset_exist){
            ?>
            <tr>   
                <th style="background-color: steelblue;color:white;" colspan = "<?php echo $no_of_days+3;?>">API (P2A)</th>
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
            if($total_api_p2a_sale_exist){
                ?>
                    <tr>   
                        <td>Sale</td>
                        <?php
                        $total_p2a_sale = 0;
                        foreach($total_api_p2a_sale_data as $total_api_p2a_sale){
                            $total_p2a_sale += $total_api_p2a_sale['value'];
                            echo "<td>".$General->IND_money_format($total_api_p2a_sale['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_p2a_sale);?></th>
                        <td>Sale</td>
                    </tr>
                <?php
                }
                if($total_api_p2a_invested_exist){
                ?>
                    <tr>   
                        <td>Invested</td>
                        <?php
                        $total = 0;
                        foreach($total_api_p2a_invested_data as $total_api_p2a_invested){
                            $total += $total_api_p2a_invested['value'];
                            echo "<td>".$General->IND_money_format($total_api_p2a_invested['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total);?></th>
                        <td>Invested</td>
                    </tr>
                <?php
                }
                if($api_p2a_earning_exist){
                ?>
                    <tr>   
                        <td>Earning</td>
                        <?php
                        $total_p2a_earning = 0;
                        foreach($api_p2a_earning_data as $api_p2a_earning){
                            $total_p2a_earning += $api_p2a_earning['value'];
                            echo "<td>".$General->IND_money_format($api_p2a_earning['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total_p2a_earning);?></th>
                        <td>Earning</td>
                    </tr>
                    <tr>   
                        <td>Earning (%)</td>
                        <?php
                        foreach($api_p2a_earning_in_percentage_data as $api_p2a_earning_in_percentage){
                            echo "<td>".$api_p2a_earning_in_percentage['value']."%</td>";
                        }
                        ?>
                        <th><?php echo ROUND(($total_p2a_earning / $total_p2a_sale) * 100,2);?>%</th>
                        <td>Earning (%)</td>
                    </tr>
                <?php
                }
                if($api_p2a_gst_asset_exist){
                ?>
                    <tr>   
                        <td>GST Asset</td>
                        <?php
                        $total = 0;
                        foreach($api_p2a_gst_asset_data as $api_p2a_gst_asset){
                            $total += $api_p2a_gst_asset['value'];
                            echo "<td>".$General->IND_money_format($api_p2a_gst_asset['value'])."</td>";
                        }
                        ?>
                        <th><?php echo $General->IND_money_format($total);?></th>
                        <td>GST Asset</td>
                    </tr>
                <?php
                }
            ?>
        <?php

        $total_sale = $total_modem_sale + $total_p2p_sale + $total_p2a_sale;

        }
        if($total_sale_exist){
        ?>
        	<tr>   
	           <td>GTV</td>
	           <?php
                $total_sale = 0;
	           foreach($total_sale_data as $sale){
                    $total_sale += $sale['value'];
	           	echo "<td>".$General->IND_money_format($sale['value'])."</td>";
	           }
	           ?>
	           <th><?php echo $General->IND_money_format($total_sale);?></th>
                <td>GTV</td>
	       </tr>
        <?php
        }
        ?>

        <?php
        if($ret_service_charge_exist || $rental_charge_exist || $kit_charge_exist || $vendor_commision_exist || $vendor_incentive_exist || $total_income_exist){
        ?>
        <tr>   
            <th style="background-color: greenyellow;" colspan = "<?php echo $no_of_days+3;?>">Income</th>
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

        if($rental_charge_exist){
        ?>
        <tr>   
            <td>Rental Charge (Without GST)</td>
            <?php
            $total_rental_charge = 0;
            foreach($rental_charge_data as $rental_charge){
                $total_rental_charge += $rental_charge['value'];
                echo "<td>".$General->IND_money_format($rental_charge['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_rental_charge);?></th>
            <td>Rental Charge (Without GST)</td>
        </tr>
        <!--tr>   
            <td>Rental Charge (%) (Without GST)</td>
            <?php
            foreach($rental_charge_in_percentage_data as $rental_charge_in_percentage){
                echo "<td>".$rental_charge_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_rental_charge / $total_sale) * 100,2);?>%</th>
            <td>Rental Charge (%) (Without GST)</td>
        </tr-->
        <?php
        }
        if($ret_service_charge_exist){
        ?>
        <tr>   
            <td>Retailer Service Charge (Without GST)</td>
            <?php
            $total_ret_service_charge = 0;
            foreach($ret_service_charge_data as $ret_service_charge){
                 $total_ret_service_charge += $ret_service_charge['value'];
            	echo "<td>".$General->IND_money_format($ret_service_charge['value'])."</td>";
            }
            ?>
             <th><?php echo $General->IND_money_format($total_ret_service_charge);?></th>
             <td>Retailer Service Charge (Without GST)</td>
        </tr>
        <tr>   
            <td>Retailer Service Charge (%) (Without GST)</td>
            <?php
            foreach($ret_service_charge_in_percentage_data as $ret_service_charge_in_percentage){
                echo "<td>".$ret_service_charge_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_ret_service_charge / $total_sale) * 100,2);?>%</th>
            <td>Retailer Service Charge (%) (Without GST)</td>
        </tr>
        <?php
        }

        if($kit_charge_exist){
        ?>  
        <tr>   
            <td>Kit Charge (Without GST)</td>
            <?php
            $total_kit_charge = 0;
            foreach($kit_charge_data as $kit_charge){
                $total_kit_charge += $kit_charge['value'];
            	echo "<td>".$General->IND_money_format($kit_charge['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_kit_charge);?></th>
            <td>Kit Charge (Without GST)</td>
        </tr>
        <!--tr>   
            <td>Kit Charge (%) (Without GST)</td>
            <?php
            foreach($kit_charge_in_percentage_data as $kit_charge_in_percentage){
                echo "<td>".$kit_charge_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_kit_charge / $total_sale) * 100,2);?>%</th>
            <td>Kit Charge (%) (Without GST)</td>
        </tr-->
        <?php
        }
        if($vendor_commision_exist){
        ?> 
        <tr>   
            <td>Vendor Commision (Without GST)</td>
            <?php
            $total_vendor_commision = 0;
            foreach($vendor_commision_data as $vendor_commision){
                $total_vendor_commision += $vendor_commision['value'];
                echo "<td>".$General->IND_money_format($vendor_commision['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_vendor_commision);?></th>
            <td>Vendor Commision (Without GST)</td>
        </tr>
        <tr>   
            <td>Vendor Commision (%) (Without GST)</td>
            <?php
            foreach($vendor_commision_in_percentage_data as $vendor_commision_in_percentage){
                echo "<td>".$vendor_commision_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_vendor_commision / $total_sale) * 100,2);?>%</th>
            <td>Vendor Commision (%) (Without GST)</td>
        </tr> 
        <?php
        }

        if($vendor_comm_adjustment_exist){
        ?> 
        <tr>   
            <td>Vendor Commision Adjustment (Without GST)</td>
            <?php
            $total_vendor_comm_adjustment = 0;
            foreach($vendor_comm_adjustment_data as $vendor_comm_adjustment){
                $total_vendor_comm_adjustment += $vendor_comm_adjustment['value'];
                echo "<td>".$General->IND_money_format($vendor_comm_adjustment['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_vendor_comm_adjustment);?></th>
            <td>Vendor Commision Adjustment (Without GST)</td>
        </tr>
        <?php
        }

        if($vendor_incentive_exist){
        ?> 
        <!--tr>   
            <td>Vendor Incentive (Without GST)</td>
            <?php
            $total_ = 0;
            foreach($vendor_incentive_data as $vendor_incentive){
                $total += $vendor_incentive['value'];
                echo "<td>".$General->IND_money_format($vendor_incentive['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total);?></th>
            <td>Vendor Incentive (Without GST)</td>
        </tr--> 
        <?php
        }

        if($total_income_exist){
        ?>
        <tr style="background-color: #ccc;">   
            <td>Total Income</td>
            <?php
            $total_income = 0;
            foreach($total_income_data as $income){
                 $total_income += $income['value'];
                echo "<td>".$General->IND_money_format($income['value'])."</td>";
            }
            ?>
             <th><?php echo $General->IND_money_format($total_income);?></th>
             <td>Total Income</td>
        </tr>
        <tr style="background-color: #ccc;">   
            <td>Total Income (%)</td>
            <?php
            foreach($total_income_in_percentage_data as $total_income_in_percentage){
                echo "<td>".$total_income_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_income / ($total_sale)) * 100,2);?>%</th>
            <td>Total Income (%)</td>
        </tr>
        <?php
        }
        ?>

        <?php
        if($ret_commision_exist || $ret_incentive_exist || $vendor_service_charge_exist || $vendor_kit_payment_exist || $dist_incentive_exist || $dist_commision_exist || $loss_exist || $adjustment_exist){
        ?>   
        <tr>   
            <th style="background-color: #ff722e" colspan = "<?php echo $no_of_days+3;?>">Expense</th>
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
        if($adjustment_exist){
        ?>
        <tr>   
            <td>Adjustment</td>
            <?php
            $total = 0;
            foreach($adjustment_data as $adjustment){
                $total += $adjustment['value'];
                echo "<td>".$General->IND_money_format($adjustment['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total);?></th>
            <td>Adjustment</td>
        </tr>
        <?php
        }
        if($ret_commision_exist){
        ?>
        <tr>   
            <td>Retailer Commision</td>
            <?php
            $total_ret_commision = 0;
            foreach($ret_commision_data as $ret_commision){
                $total_ret_commision += $ret_commision['value'];
            	echo "<td>".$General->IND_money_format($ret_commision['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_ret_commision);?></th>
            <td>Retailer Commision</td>
        </tr>
        <tr>   
            <td>Retailer Commision (%)</td>
            <?php
            foreach($ret_commision_in_percentage_data as $ret_commision_in_percentage){
                echo "<td>".$ret_commision_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_ret_commision / $total_sale) * 100,2);?>%</th>
            <td>Retailer Commision (%)</td>
        </tr>
        <?php
        }

        if($ret_incentive_exist){
        ?>
        <tr>   
            <td>Retailer Incentive</td>
            <?php
            $total_ret_incentive = 0;
            foreach($ret_incentive_data as $ret_incentive){
                $total_ret_incentive += $ret_incentive['value'];
            	echo "<td>".$General->IND_money_format($ret_incentive['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_ret_incentive);?></th>
            <td>Retailer Incentive</td>
        </tr>
        <tr>   
            <td>Retailer Incentive (%)</td>
            <?php
            foreach($ret_incentive_in_percentage_data as $ret_incentive_in_percentage){
                echo "<td>".$ret_incentive_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_ret_incentive / $total_sale) * 100,2);?>%</th>
            <td>Retailer Incentive (%)</td>
        </tr>
        <?php
        }
        if($vendor_service_charge_exist){
            ?>
        
        <tr>   
            <td>Vendor Service Charge (Without GST)</td>
            <?php
            $total_vendor_service_charge = 0;
            foreach($vendor_service_charge_data as $vendor_service_charge){
                $total_vendor_service_charge += $vendor_service_charge['value'];
                echo "<td>".$General->IND_money_format($vendor_service_charge['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total_vendor_service_charge);?></th>
            <td>Vendor Service Charge (Without GST)</td>
        </tr>
        <tr>   
            <td>Vendor Service Charge (%) (Without GST)</td>
            <?php
            foreach($vendor_service_charge_in_percentage_data as $vendor_service_charge_in_percentage){
                echo "<td>".$vendor_service_charge_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_vendor_service_charge / $total_sale) * 100,2);?>%</th>
            <td>Vendor Service Charge (%) (Without GST)</td>
        </tr>
         <?php
        }
        if($vendor_kit_payment_exist){
            ?>
        <tr>   
            <td>Vendor Kit Payment (Without GST)</td>
            <?php
            $total = 0;
            foreach($vendor_kit_payment_data as $vendor_kit_payment){
                $total += $vendor_kit_payment['value'];
                echo "<td>".$General->IND_money_format($vendor_kit_payment['value'])."</td>";
            }
            ?>
            <th><?php echo $General->IND_money_format($total);?></th>
            <td>Vendor Kit Payment (Without GST)</td>
        </tr>
        
        <?php
        }
        if($dist_incentive_exist){
        	?>
        	<tr>   
	           <td>Distributor Incentive</td>
	           <?php
                $total_dist_incentive = 0;
	           foreach($dist_incentive_data as $dist_incentive){
                    $total_dist_incentive += $dist_incentive['value'];
	           	echo "<td>".$General->IND_money_format($dist_incentive['value'])."</td>";
	           }
	           ?>
                <th><?php echo $General->IND_money_format($total_dist_incentive);?></th>
                <td>Distributor Incentive</td>
	       </tr>
            <tr>   
                <td>Distributor Incentive (%)</td>
                <?php
                foreach($dist_incentive_in_percentage_data as $dist_incentive_in_percentage){
                    echo "<td>".$dist_incentive_in_percentage['value']."%</td>";
                }
                ?>
                <th><?php echo ROUND(($total_dist_incentive / $total_sale) * 100,2);?>%</th>
                <td>Distributor Incentive (%)</td>
            </tr>
        	<?php
        }

        if($dist_commision_exist){
        	?>
        	<tr>   
	           <td>Distributor Commision</td>
	           <?php
                $total_dist_commision = 0;
	           foreach($dist_commision_data as $dist_commision){
                    $total_dist_commision += $dist_commision['value'];
	           	echo "<td>".$General->IND_money_format($dist_commision['value'])."</td>";
	           }
	           ?>
                <th><?php echo $General->IND_money_format($total_dist_commision);?></th>
                <td>Distributor Commision</td>
	       </tr>
            <tr>   
                <td>Distributor Commision (%)</td>
                <?php
                foreach($dist_commision_in_percentage_data as $dist_commision_in_percentage){
                    echo "<td>".$dist_commision_in_percentage['value']."%</td>";
                }
                ?>
                <th><?php echo ROUND(($total_dist_commision / $total_sale) * 100,2);?>%</th>
                <td>Distributor Commision (%)</td>
            </tr>
        	<?php
        }

        if($loss_exist){
            ?>
            <tr>   
                <td>Loss</td>
                <?php
                $total = 0;
                foreach($loss_data as $loss){
                    $total += $loss['value'];
                    echo "<td>".$General->IND_money_format($loss['value'])."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <td>Loss</td>
            </tr>
            <?php
        }



        if($total_expense_exist){
        ?>
        <tr style="background-color: #ccc;">   
            <td>Total Expense</td>
            <?php
            $total_expense = 0;
            foreach($total_expense_data as $expense){
                 $total_expense += $expense['value'];
                echo "<td>".$General->IND_money_format($expense['value'])."</td>";
            }
            ?>
             <th><?php echo $General->IND_money_format($total_expense);?></th>
             <td>Total Expense</td>
        </tr>
        <tr style="background-color: #ccc;">   
            <td>Total Expense (%)</td>
            <?php
            foreach($total_expense_in_percentage_data as $total_expense_in_percentage){
                echo "<td>".$total_expense_in_percentage['value']."%</td>";
            }
            ?>
            <th><?php echo ROUND(($total_expense / ($total_sale)) * 100,2);?>%</th>
            <td>Total Expense (%)</td>
        </tr>
        <?php
        }

        if(!empty($profit_loss_data)){
        ?>
       
        <tr>   
            <th style='background-color: steelblue;color:white'>Profit / Loss</th>
            <?php
            $total_profit_loss = 0;
	           foreach($profit_loss_data as $profit_loss){
                    $total_profit_loss += $profit_loss['value'];
                    if($profit_loss['value']>0){
                        $background = "greenyellow";
                    }else{
                        $background = "#ff722e";
                    }
	           	echo "<td style='background-color:".$background."'>".$General->IND_money_format($profit_loss['value'])."</td>";
	           }

                if($total_profit_loss>0){
                    $background = "greenyellow";
                }else{
                    $background = "#ff722e";
                }
	           ?>
                <th style='background-color:<?php echo $background;?>'><?php echo $General->IND_money_format($total_profit_loss);?></th>
                <th style='background-color: steelblue;color:white'>Profit / Loss</th>
        </tr>

        <tr>   
            <th style='background-color: steelblue;color:white'>Profit / Loss (%)</th>
            <?php
                foreach($profit_loss_in_percentage_data as $profit_loss_in_percentage){
                    if($profit_loss_in_percentage['value']>0){
                        $background = "greenyellow";
                    }else{
                        $background = "#ff722e";
                    }
                    echo "<td style='background-color:".$background."'>".$profit_loss_in_percentage['value']."%</td>";
                }
                $total_profit_loss = ROUND((($total_income - $total_expense) / ($total_sale)) * 100,2);

                if($total_profit_loss>0){
                    $background = "greenyellow";
                }else{
                    $background = "#ff722e";
                }
                ?>
                <th style='background-color:<?php echo $background;?>'><?php echo $total_profit_loss;?>%</th>
                <th style='background-color: steelblue;color:white'>Profit / Loss (%)</th>
        </tr>

        <?php
        }

        if(!empty($total_gst_liability)){
            ?>
            <tr>   
                <th style='background-color: steelblue;color:white'>GST Liabilty</th>
                <?php
                $total = 0;
                foreach($total_gst_liability as $gst_liability){
                    $total += $gst_liability['value'];
                    echo "<td>".$General->IND_money_format($gst_liability['value'])."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style='background-color: steelblue;color:white'>GST Liabilty</th>
            </tr>
            <?php
        }

        if(!empty($total_vendor_gst_liability)){
            ?>
            <tr>   
                <th style='background-color: steelblue;color:white'>Vendor GST Liabilty</th>
                <?php
                $total = 0;
                foreach($total_vendor_gst_liability as $vendor_gst_liability){
                    $total += $vendor_gst_liability['value'];
                    echo "<td>".$General->IND_money_format($vendor_gst_liability['value'])."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style='background-color: steelblue;color:white'>Vendor GST Liabilty</th>
            </tr>
            <?php
        }

        if(!empty($total_gst_asset)){
            ?>
            <tr>   
                <th style='background-color: steelblue;color:white'>Vendor GST Asset</th>
                <?php
                $total = 0;
                foreach($total_gst_asset as $gst_asset){
                    $total += $gst_asset['value'];
                    echo "<td>".$General->IND_money_format($gst_asset['value'])."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total);?></th>
                <th style='background-color: steelblue;color:white'>Vendor GST Asset</th>
            </tr>

            <?php
        }
        if($net_gst_exist){
            ?>
            <tr>   
                <th style='background-color: steelblue;color:white'>Net GST</th>
                <?php
                $total_net_gst = 0;
                foreach($net_gst_data as $net_gst){
                    $total_net_gst += $net_gst['value'];
                    echo "<td>".$General->IND_money_format($net_gst['value'])."</td>";
                }
                ?>
                <th><?php echo $General->IND_money_format($total_net_gst);?></th>
                <th style='background-color: steelblue;color:white'>Net GST</th>
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
