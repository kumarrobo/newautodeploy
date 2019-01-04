<style>
    tbody td{text-align:center;font-family:arial;font-size:12px;}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <a style="float:right;" href="/servicemanagement/getActiveRetailers" target="_blank">Active Retailers</a>
            <a href="/servicemanagement/index">Service Activation</a>
            <h3>Distributor Kit Details</h3>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Search</div>
            <div class="panel-body">
            </div>
            <form name="distributorKits" id="distributorKits" class="form-inline" method="POST" action="/kitreport">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="control-label" for="service_id">Services</label>
                                <select class="form-control" id="service_id" name="service_id">
                                    <option value="">All</option>
                                    <?php foreach ($services as $id=>$name){ ?>
                                        <option value="<?php echo $id?>" <?php if($service_id == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php } ?>
                                </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Distributor</label>
                                <input type="text" class="form-control" name="distmob" id="distmob" placeholder="Mobile"  value="<?php echo $mobile; ?>">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label for="" class="col-lg-2 control-label"></label>
                            <button class="btn btn-primary btn-sm" type="submit" id="">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<table class="table table-hover table-stripped table-bordered">
    <thead style="background-color:#428bca;color:#fff;">
        <tr>
            <th>#</th>
            <th>Dist Id</th>
            <th>Name</th>
            <th>Total Kit Purchased</th>
            <th>Kits Assigned</th>
            <th>Kit Pending</th>
            <th>Kits Refunded</th>
            <!-- <th>Direct Buy DW/Reinit</th> -->
            <th>Direct Buy RW/InstaMojo</th>
        </tr>
    </thead>
    <?php $i = 1; ?>
    <tbody>
        <?php foreach ($distributors as $dist_id => $distributor) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $dist_id; ?></td>
                <td><?php echo $distributor['name']; ?></td>
                <td><?php echo (array_key_exists($dist_id,$dist_total_purchased_kits) && !empty($dist_total_purchased_kits[$dist_id]) ) ? '<a style="cursor:pointer;" onclick="getKitEntries('.$service_id.','.$dist_id.','.$distributor['user_id'].',\'dist_total_purchased_kits\')">'.$dist_total_purchased_kits[$dist_id].'</a>' : '0'; ?></td>
                <td><?php echo (array_key_exists($dist_id,$assigned_kits_to_retailer) && !empty($assigned_kits_to_retailer[$dist_id]) ) ? '<a style="cursor:pointer;" onclick="getKitEntries('.$service_id.','.$dist_id.','.$distributor['user_id'].',\'assigned_kits_to_retailer\')">'.$assigned_kits_to_retailer[$dist_id].'</a>' : '0'; ?></td>
                <td><?php echo (array_key_exists($dist_id,$dist_pending_kits) && !empty($dist_pending_kits[$dist_id]) ) ? '<a style="cursor:pointer;" onclick="getKitEntries('.$service_id.','.$dist_id.','.$distributor['user_id'].',\'dist_pending_kits\')">'.$dist_pending_kits[$dist_id].'</a>': '0'; ?></td>
                <td><?php
                    $total_dist_ret_refunded_kits = 0;
                    if( array_key_exists($dist_id,$dist_total_refunded_kits) && !empty($dist_total_refunded_kits[$dist_id]) ){
                        $total_dist_ret_refunded_kits += $dist_total_refunded_kits[$dist_id];
                    }
                    // if( array_key_exists($dist_id,$ret_total_refunded_kits) && !empty($ret_total_refunded_kits[$dist_id]) ){
                    //     $total_dist_ret_refunded_kits += $ret_total_refunded_kits[$dist_id];
                    // }
                    echo ( $total_dist_ret_refunded_kits > 0 ) ? '<a style="cursor:pointer;" onclick="getKitEntries('.$service_id.','.$dist_id.','.$distributor['user_id'].',\'total_dist_ret_refunded_kits\')">'.$total_dist_ret_refunded_kits.'</a>' : $total_dist_ret_refunded_kits;
                  ?>
                </td>
                <!-- <td><?php // echo (array_key_exists($distributor['user_id'],$direct_buy_kits_by_distributors) && !empty($direct_buy_kits_by_distributors[$distributor['user_id']]) ) ? '<a style="cursor:pointer;" onclick="getKitEntries('.$service_id.','.$dist_id.','.$distributor['user_id'].',\'direct_buy_kits_by_distributors\')">'.$direct_buy_kits_by_distributors[$distributor['user_id']].'</a>' : '0'; ?></td> -->
                <td><?php echo (array_key_exists($dist_id,$direct_buy_kits_by_retailers) && !empty($direct_buy_kits_by_retailers[$dist_id]) ) ? '<a style="cursor:pointer;" onclick="getKitEntries('.$service_id.','.$dist_id.','.$distributor['user_id'].',\'direct_buy_kits_by_retailers\')">'.$direct_buy_kits_by_retailers[$dist_id].'</a>' : '0'; ?></td>

            </tr>
            <?php $i++; ?>
        <?php } ?>
    </tbody>
</table>
<div class="modal fade" id="kits_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 0px;">
            <div class="modal-header"style="background-color:#428bca;color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <b>Kit Entries</b>
                </h4>
            </div>
            <div class="modal-body">
                <center><img src="/img/ajax-loader-2.gif"></img> Loading...</center>
            </div>
            <div class="modal-footer">
                <button type="button" style="font-size: 11px;color: #fff;background-color:#1cbc16;border-radius:0px;" class="btn btn-xs" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#service_id').find('option[selected="selected"]').each(function(){
        $(this).prop('selected', true);
    });

    function getKitEntries(service_id,dist_id,dist_user_id,type){
        // alert(dist_id+' '+dist_user_id+' '+type);
        // var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
        $('#kits_modal').modal('toggle');
        $.ajax({
            url : "/kitreport/getKitEntries",
            type: "POST",
            dataType:'json',
            data : {service_id:service_id,dist_id:dist_id,dist_user_id:dist_user_id,type:type},
            beforeSend: function () {
                $('#kits_modal').find('.modal-body').html('<center><img src="/img/ajax-loader-2.gif"></img> Please wait...</center>');
            },
            success:function(response){
                console.log(response);
                var kit_entries_html = '';
                if(response.status == 'success')
                {
                    kit_entries_html += '<table class="table table-bordered table-hover">';
                    if(response.type == 'dist_total_purchased_kits'){
                        kit_entries_html += '<thead><th style="text-align: center;">Payment Date</th><th style="text-align: center;">Plan</th><th style="text-align: center;">Kits Purchased</th><th style="text-align: center;">Amount Paid</th><th style="text-align: center;">By</th></thead><tbody>';
                        $.each(response.data,function(key,kit){
                            kit_entries_html += '<tr>';
                            kit_entries_html += '<td>'+kit.distributors_kits_log.timestamp+'</td>';
                            kit_entries_html += '<td>'+kit.service_plans.plan_name+'</td>';
                            kit_entries_html += '<td>'+kit.distributors_kits_log.kits+'</td>';
                            kit_entries_html += '<td>'+kit.distributors_kits_log.amount+'</td>';
                            kit_entries_html += '<td>'+kit.users.created_by+'</td>';
                            kit_entries_html += '</tr>';
                        });
                    }
                    if(response.type == 'direct_buy_kits_by_distributors'){
                        kit_entries_html += '<thead><th style="text-align: center;">Payment Date</th><th style="text-align: center;">Amount Paid</th><th style="text-align: center;">Description</th></thead><tbody>';
                        $.each(response.data,function(key,kit){
                            kit_entries_html += '<tr>';
                            kit_entries_html += '<td>'+kit.shop_transactions.date+'</td>';
                            kit_entries_html += '<td>'+kit.shop_transactions.amount+'</td>';
                            kit_entries_html += '<td>'+kit.shop_transactions.description+'</td>';
                            kit_entries_html += '</tr>';
                        });
                    }
                    if(response.type == 'direct_buy_kits_by_retailers'){
                        kit_entries_html += '<thead><th style="text-align: center;">Activation Date</th><th style="text-align: center;">Activated By</th><th style="text-align: center;">Activated Retailer ID</th><th style="text-align: center;">Plan Activated</th><th style="text-align: center;">Plan Amount</th><th style="text-align: center;">Service</th></thead><tbody>';
                        $.each(response.data,function(key,kit){
                            kit_entries_html += '<tr>';
                            kit_entries_html += '<td>'+kit.us.activated_on+'</td>';
                            kit_entries_html += '<td>'+kit.user.activated_by_name+'</td>';
                            kit_entries_html += '<td>'+kit.ret.ret_id+'</td>';
                            kit_entries_html += '<td>'+kit.us.plan+'</td>';
                            kit_entries_html += '<td>'+kit.us.plan_amount+'</td>';
                            kit_entries_html += '<td>'+kit[0]['service_status']+'</td>';
                            kit_entries_html += '</tr>';
                        });
                    }
                    if(response.type == 'assigned_kits_to_retailer'){
                        kit_entries_html += '<thead><th style="text-align: center;">Activation Date</th><th style="text-align: center;">Activated By</th><th style="text-align: center;">Activated Retailer ID</th><th style="text-align: center;">Plan Activated</th><th style="text-align: center;">Plan Amount</th><th style="text-align: center;">Service</th></thead><tbody>';
                        $.each(response.data,function(key,kit){
                            kit_entries_html += '<tr>';
                            kit_entries_html += '<td>'+kit.us.activated_on+'</td>';
                            kit_entries_html += '<td>'+kit.user.activated_by_name+'</td>';
                            kit_entries_html += '<td>'+kit.ret.ret_id+'</td>';
                            kit_entries_html += '<td>'+kit.us.plan+'</td>';
                            kit_entries_html += '<td>'+kit.us.plan_amount+'</td>';
                            kit_entries_html += '<td>'+kit[0]['service_status']+'</td>';
                            kit_entries_html += '</tr>';
                        });
                    }
                    if(response.type == 'total_dist_ret_refunded_kits'){
                        kit_entries_html += '<thead><th style="text-align: center;">Refund Date</th><th style="text-align: center;">Plan</th><th style="text-align: center;">Kits Refunded</th><th style="text-align: center;">Amount Refunded</th><th style="text-align: center;">By</th></thead><tbody>';
                        $.each(response.data,function(key,kit){
                            kit_entries_html += '<tr>';
                            kit_entries_html += '<td>'+kit.distributors_kits_log.timestamp+'</td>';
                            kit_entries_html += '<td>'+kit.service_plans.plan_name+'</td>';
                            kit_entries_html += '<td>'+kit.distributors_kits_log.kits+'</td>';
                            kit_entries_html += '<td>'+kit.distributors_kits_log.amount+'</td>';
                            kit_entries_html += '<td>'+kit.users.created_by+'</td>';
                            kit_entries_html += '</tr>';
                        });
                    }
                    if(response.type == 'dist_pending_kits'){
                        kit_entries_html += '<thead><th style="text-align: center;">Plan</th><th style="text-align: center;">Pending Kits</th></thead><tbody>';
                        $.each(response.data,function(key,kit){
                            kit_entries_html += '<tr>';
                            kit_entries_html += '<td>'+kit.service_plans.plan_name+'</td>';
                            kit_entries_html += '<td>'+kit.distributors_kits.kits+'</td>';
                            kit_entries_html += '</tr>';
                        });
                    }

                    kit_entries_html += '</tbody></table>';
                }
                else
                {
                    kit_entries_html += '<h3>No entries found</h3>';
                }
                console.log(kit_entries_html);
                $('#kits_modal').find('.modal-body').html(kit_entries_html);
            }
        });
    }
</script>
