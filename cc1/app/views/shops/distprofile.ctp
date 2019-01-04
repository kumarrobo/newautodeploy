<link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<!-- <link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"> -->
<style>
     thead{
        background-color: #428bca;
        color: #fff;
    }
    .sales-report-container,.sales-filter{
        margin-top: 25px;
        margin-bottom: 25px;
    }
    h2{
        background-color: #337ab7;
        height: 36px;
        color: #fff;
        padding: 7px;
        font-size: 19px;
    }

</style>
<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'target_report'));?>
        <div class="sales-filter">
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get" action="/shops/distProfile">
                                <div class="form-group">
                                    <label class="filter-col"  for="dists">Distributor</label>
                                    <select id="dist" class="form-control" name="dist">
                                        <option value="" >--Select Distributor--</option>
                                        <?php foreach ($all_dists as $id => $name) {
                                            $selected = null;
                                            if($dist_id == $id){
                                                $selected = 'selected="selected"';
                                            }
                                            echo '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
                                        }?>
                                    </select>
                                </div>
                                <div class="form-group" style="float:right;">
                                    <button type="submit" class="btn btn-primary" >
                                        <span class="glyphicon glyphicon-search"></span> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sales-container">


            <?php
                if( ($validation_error) && !empty($validation_error) ){ ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo $validation_error; ?>
                    </div>
				<?php } else if( count($dist_details) > 0 ){
                    ?>
                    <form method="post" action="/shops/updateDistProfile/<?php echo $dist_details['dist']['user_id']; ?>/<?php echo $dist_details['dist']['id']; ?>">
                        <h2>Personal Details</h2>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>ID:</label>
                                <input disabled class="form-control" value="<?php echo $dist_details['dist']['id']; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Name:</label>
                                <input name="name" class="form-control" value="<?php echo ( isset($dist_details['imp']['name']) && !empty($dist_details['imp']['name']) ) ? $dist_details['imp']['name'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mobile:</label>
                                <input class="form-control" value="<?php echo $dist_details['dist']['mobile']; ?>" type="text" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Alternate Mobile:</label>
                                <input name="alternate_mobile_no" class="form-control" value="<?php echo ( isset($dist_details['imp']['alternate_mobile_no']) && !empty($dist_details['imp']['alternate_mobile_no']) ) ? $dist_details['imp']['alternate_mobile_no'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Email:</label>
                                <input name="email_id" class="form-control" value="<?php echo ( isset($dist_details['imp']['email_id']) && !empty($dist_details['imp']['email_id']) ) ? $dist_details['imp']['email_id'] :null; ?>" type="text">
                            </div>
                        </div>
                        <h2>Shop Details</h2>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Shop Name:</label>
                                <input name="shop_est_name" class="form-control" value="<?php echo ( isset($dist_details['imp']['shop_est_name']) && !empty($dist_details['imp']['shop_est_name']) ) ? $dist_details['imp']['shop_est_name'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Shop Ownership Type:</label>
                                <input name="shop_ownership" class="form-control" value="<?php echo ( isset($dist_details['imp']['shop_ownership']) && !empty($dist_details['imp']['shop_ownership']) ) ? $dist_details['imp']['shop_ownership'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Shop Category:</label>
                                <input name="business_nature" class="form-control" value="<?php echo ( isset($dist_details['imp']['business_nature']) && !empty($dist_details['imp']['business_nature']) ) ? $dist_details['imp']['business_nature'] : null; ?>" type="text">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Shop Address:</label>
                                <textarea name="shop_est_address" cols="3" rows="3" class="form-control"><?php echo ( isset($dist_details['imp']['shop_est_address']) && !empty($dist_details['imp']['shop_est_address']) ) ? $dist_details['imp']['shop_est_address'] : null; ?></textarea>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>City:</label>
                                <input name="shop_est_city" class="form-control" value="<?php echo ( isset($dist_details['imp']['shop_est_city']) && !empty($dist_details['imp']['shop_est_city']) ) ? $dist_details['imp']['shop_est_city'] : $dist_details['dist']['city']; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State:</label>
                                <input name="shop_est_state" class="form-control" value="<?php echo ( isset($dist_details['imp']['shop_est_state']) && !empty($dist_details['imp']['shop_est_state']) ) ? $dist_details['imp']['shop_est_state'] : $dist_details['dist']['state']; ?>" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Pin Code:</label>
                                <input name="pin_code" class="form-control" value="<?php echo ( isset($dist_details['imp']['pin_code']) && !empty($dist_details['imp']['pin_code']) ) ? $dist_details['imp']['pin_code'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Allocated Area:</label>
                                <input name="allocated_area" class="form-control" value="<?php echo ( isset($dist_details['imp']['allocated_area']) && !empty($dist_details['imp']['allocated_area']) ) ? $dist_details['imp']['allocated_area'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Preferred Courier Service:</label>
                                <input name="pref_courier_service" class="form-control" value="<?php echo ( isset($dist_details['imp']['pref_courier_service']) && !empty($dist_details['imp']['pref_courier_service']) ) ? $dist_details['imp']['pref_courier_service'] : null; ?>" type="text">
                            </div>
                        </div>
                        <h2>KYC Details</h2>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>PAN Number:</label>
                                <input name="pan_no" class="form-control" value="<?php echo ( isset($dist_details['imp']['pan_no']) && !empty($dist_details['imp']['pan_no']) ) ? $dist_details['imp']['pan_no'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Aadhar Number:</label>
                                <input name="aadhar_no" class="form-control" value="<?php echo ( isset($dist_details['imp']['aadhar_no']) && !empty($dist_details['imp']['aadhar_no']) ) ? $dist_details['imp']['aadhar_no'] : null; ?>" type="text">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>GST Number:</label>
                                <input name="gst_no" class="form-control" value="<?php echo ( isset($dist_details['imp']['gst_no']) && !empty($dist_details['imp']['gst_no']) ) ? $dist_details['imp']['gst_no'] : null; ?>" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="submit" class="form-control input-lg btn-success" value="Save">
                            </div>
                        </div>

                        </form>
                <?php } else {
                    echo '<h3>No records Found</h3>';
                } ?>
        </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>