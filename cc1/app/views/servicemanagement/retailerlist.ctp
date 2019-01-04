<?php if($page == ''){?>
<link rel="stylesheet" media="screen" href="/boot/css/select2.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/select2.js"></script>
<script type="text/javascript" src="/boot/js/servicemanagement.js"></script>

<style>
    table{
        font-size: 14px;
    }

    input{
        margin: 0px 4px 0px 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear{
    float: none;
    padding: 4px;
    font-size: small;
}

</style>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <h3>Active Retailers</h3>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Search</div>
            <div class="panel-body">
                <form action="/activeretailers" method="get" class="form-inline">
                    <div class="form-group col-lg-2">
                        <select class="form-control" id="services" name="service" style="width:120px">
                            <option value="">Select Service</option>
                            <?php
                            foreach ($services as $service_id => $service_name) {
                                $ifselected = '';
                                if (($selected_service) && ($selected_service == $service_id)) {
                                    $ifselected = 'selected';
                                }
                                echo '<option ' . $ifselected . ' value="' . $service_name . '">' . $service_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activationfrom" > From </label>
                        <input type="text" class="form-control" id ="activationfrom" name="activationfrom" value="<?php echo!isset($activated_from) ? date("Y-m-d") : $activated_from; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="activationto" >To</label>
                        <input type="text" class="form-control" id ="activationto" name="activationto" value="<?php echo!isset($activated_to) ? date("Y-m-d") : $activated_to; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="_" > Name </label>
                          <select id="distributor_filter" multiple="multiple" onchange="$('#dist_name').val($('#distributor_filter').val());">
                              <?php foreach ($distributor_list as $lists) { ?>
                                  <option value="<?php echo $lists['distributors']['id'] ?>" <?php if (in_array($lists['distributors']['id'], explode(',', $filter_distributors))) {
                                  echo "selected";
                              } ?>><?php echo $lists['distributors']['name']?></option>
                        <?php } ?>
                          </select>
                          <input type="hidden" name="dist_name" id="dist_name" value="<?php echo isset($filter_distributors) ? $filter_distributors : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="status_type"> Status </label>
                        <select class="form-control" id="status_type" name="status_type" style="width:105px">
                            <?php $sel = '';?>
                            <option value="">All</option>
                            <option value="0" <?php echo $status_type == '0'?$sel="selected":''; ?>> Active   </option>
                            <option value="1" <?php echo $status_type == '1'?$sel="selected":''; ?>> Inactive </option>
                            <option value="2" <?php echo $status_type == '2'?$sel="selected":''; ?>> Transacting </option>
                        </select>
                    </div>

                    <div class="form-group" style="margin:10 0 0 20;">
                        <button class="btn btn-primary btn-sm" type="submit" id="actretailers" name="actretailers" >Search</button>
                        <button class="btn btn-primary btn-sm"   id="actret_page" name="actret_page">Download</button>
                       <input type="hidden" name="activeret_download" id ="activeret_download">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (($validation_error) && !empty($validation_error)) { ?>
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <p><?php echo $validation_error; ?></p>
    </div>
<?php } else { ?>


    <div class="row" id="active_retailers_<?php echo $services[$selected_service]; ?>">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading bg-success"><b><?php echo $services[$selected_service]; ?>(<?php echo count($active_retailers); ?>)</b></div>
                <div class="panel-body">
                    <?php if (count($active_retailers) > 0) { ?>
                        <table id="active_Ret_Servicetb" class="table table-hover table-stripped table-bordered">
                            <thead style="background-color:#428bca;color:#fff;">
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Mobile</th>
                                    <th>Ret ID</th>
                                    <th>Retailer Name</th>
                                    <th>Shop Name</th>
                                    <th>Dist ID</th>
                                    <th>Distributor Name</th>
                                   <?php if($selected_service == '12') {?>
                                    <th>Margin</th>
                                   <?php } ?>
                                    <th>Fields</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                </tr>
                            </thead>
                            <tbody>


        <?php foreach ($active_retailers as $index => $retailer) { ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><a href="/servicemanagement/getServices?mobile=<?php echo $retailer['retailer_mobile']; ?>" target="_blank"><?php echo $retailer['retailer_user_id']; ?></a></td>
                                        <td><a href="/servicemanagement/getServices?mobile=<?php echo $retailer['retailer_mobile']; ?>" target="_blank"><?php echo $retailer['retailer_mobile']; ?></a></td>
                                        <td><?php echo $retailer['retailer_id']; ?></td>
                                        <td><?php echo $retailer['retailer_name']; ?></td>
                                        <td><?php echo $retailer['retailer_shopname']; ?></td>
                                        <td><?php echo $retailer['distributor_id']; ?></td>
                                        <td><?php echo $retailer['distributor_name']; ?></td>
                                             <?php if ($selected_service == '12') { ?>
                                              <td><?php echo ($retailer['param1'] == ' ')?0.4:$retailer['param1']; ?></td>
                                              <?php } ?>
                                        <td><?php foreach($retailer['params'] as $field_name => $field_value) {
                            $field_value = ($field_name == 'plan') ? $plans[$field_value] : $field_value;
                            $field_name = ($field_name == 'plan') ? "Active Plan" : str_ireplace('_', ' ', $field_name);
                            echo '<strong>' . ucfirst($field_name) . ': </strong>' . json_encode($field_value) . '<br>';
                        }
            ?></td>

                                        <td> <?php echo (($retailer['service_flag'] == 1) && ($retailer['kit_flag'] == 1))?'Active':'Deactive'; ?></td>
                                        <td><?php echo $retailer['service_created_on']; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <p>No active retailers in this service !!</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
        $("#distributor_filter").select2({
        placeholder: "Select Distributor Shopname",
        dropdownAutoWidth: 'true',
        width:'250px',
        allowClear: true
    });

    $('#actret_page').click(function (e) {
    e.preventDefault();
    $('#activeret_download').val('1');
    $('#actretailers').click();
    $('#activeret_download').val('');
});

    </script>
<?php } ?>