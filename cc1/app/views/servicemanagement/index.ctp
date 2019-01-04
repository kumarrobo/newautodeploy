<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/servicemanagement.js"></script>
<style>
    form{
        font-size: 13px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <a style="float:right;" href="/activeretailers" target="_blank">Active Retailers</a>
            <a href="/kitreport" target="_blank">Distributor Kits</a>
            <h3>Service Activation</h3>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Search</div>
                <div class="panel-body">
                    <?php $messages=$this->Session->flash(); ?>
                        <?php if(!empty($messages) && preg_match('/Error/',$messages)): ?>
                            <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <p><?php  echo $messages; ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($messages) && preg_match('/Success/',$messages)): ?>
                            <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <p><?php  echo $messages; ?></p>
                            </div>
                        <?php endif; ?>
                    <div class="row">
                        <form action="/servicemanagement/getServices" method="get">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-4">Enter Mobile</label>
                                    <div class="col-lg-8">
                                        <input class="form-control" type="text" value="<?php echo isset($mobile)?$mobile:"";?>" id="mobile" name="mobile">
                                    </div>
                                </div>
                            </div>
                            <?php /* <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" name="role" value="1" checked="checked"><b>Retailer</b></label>
                                    <label class="radio-inline"><input type="radio" name="role" value="2"><b>Distributor</b></label>
                                </div>
                            </div> */ ?>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="" class="col-lg-2 control-label"></label>
                                    <button class="btn btn-primary btn-sm" type="submit" id="">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if(!empty($service_fields)){
          foreach ($service_fields as $service_id => $fields){ ?>
        <div class="row" id="updateServices_<?php echo $service_id; ?>">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading bg-success"><b><?php echo $services[$service_id]['name']; ?></b></div>
                    <div class="panel-body">
                    <?php  if( ($services[$service_id]['registration_type'] == 3 && ($fields['service_flag']['value'] != 'NA') ) || ( $services[$service_id]['registration_type'] == 2 && array_key_exists('kit_flag',$fields) && ($fields['kit_flag']['value'] == 1) ) ){

                        // if(  ){ // active kit ?>
                                <form class="form-inline" method="" action="" id="service_form_<?php echo $service_id; ?>">
                            <?php
                            $service_flag_value = $fields['service_flag']['value'];
                            $kit_flag_value = $fields['kit_flag']['value'];
                            foreach ($fields as $key => $field){

                                if( $services[$service_id]['registration_type'] == 3 ){
                                    if( ($service_flag_value != 1) && !in_array($key,array('service_flag')) ){
                                        continue;
                                    }
                                }

                                if( $services[$service_id]['registration_type'] == 2 ){
                                    if( ($service_flag_value != 1) && !in_array($key,array('service_flag','plan','payment_mode')) ){
                                        continue;
                                    }
                                }
                                ?>
                                <div class="form-group" style="margin-top:10px;">
                            <?php    if( !in_array($key,array('service_flag','kit_flag')) ){ ?>

                                <label for="<?php echo $key; ?>"><?php echo $field['label']; ?></label>
                            <?php }
                                if( $key == 'service_flag' ){

                                    if( in_array($field['value'],array(0,7)) ){ ?>
                                        <span class="activate-submit-button" id="activate_submit_button_<?php echo $service_id; ?>">
                                            <input type="button" class="btn btn-sm btn-success" onclick="activateService('<?php echo $service_id; ?>')" value="Activate Service">
                                        </span>
                                 <?php } else if( in_array($field['value'],array(1)) ){ ?>
                                        <span class="deactivate-submit-button" id="deactivate_submit_button_<?php echo $service_id; ?>">
                                            <input type="button" class="btn btn-sm btn-success" onclick="deactivateService('<?php echo $service_id; ?>')" value="Deactivate Service">
                                        </span>

                                 <?php  }
                                 // else { ?>
                                    <label for="<?php echo $key; ?>">Service Status : </label><span style="margin-right:10px;"><?php echo $service_statuses[$field['value']]; ?></span>
                                <?php  // } ?>

                                    <input type="hidden" name="service_flag" value="<?php echo $field['value'];?>">
                                    <!-- <div class="hidden" id="hidden_<?php echo $key; ?>">
                                        <input  class=""
                                                id="<?php // echo $key; ?>"
                                                type="<?php // echo $field['type']; ?>"
                                                name="<?php // echo $key; ?>"
                                                value="<?php // echo $field['value'];?>"
                                                onclick="check(this)"
                                                checked
                                            >
                                    </div> -->
                                <?php } else if($field['type'] == 'dropdown'){ ?>
                                    <select class="form-control" name="<?php echo $key; ?>">
                                        <?php
                                        $plan_key = 'default_values';
                                        $options = array();
                                            foreach ($field[$plan_key] as $value => $label) {
                                                $selected = '';
                                                if( (string)$value == (string)$field['value'] ){
                                                    $selected = 'selected="selected"';
                                                    if( ($services[$service_id]['registration_type'] == 2) && in_array($key,array('plan','payment_mode')) ){ ?>
                                                        <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                                                <?php  } ?>
                                                <?php }
                                                    if( ($services[$service_id]['registration_type'] != 2) || !in_array($key,array('plan','payment_mode')) || empty($field['value']) ){
                                                        $options[] = '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
                                                    }
                                                }
                                            if( !empty($options) ){
                                                if( ($key != 'settlement_options') || ($services[$service_id]['registration_type'] == 2) ){ ?>
                                                    <option value="">--select <?php echo $field['label']; ?>--</option>
                                                <?php }
                                                echo implode('<br>',$options);
                                            }
                                        ?>

                                    </select>

                                <?php } else if( $field['type'] == 'label' ) { ?>
                                    <span style="margin-right:10px;">: <?php echo $field['value']; ?></span>
                                <?php } else if( !in_array($key,array('service_flag','kit_flag')) ){ ?>
                                        <input  class="<?php echo (in_array($field['type'],array('text'))) ? 'form-control' : '' ?>"
                                                id="<?php echo $key; ?>"
                                                type="<?php echo $field['type']; ?>"
                                                name="<?php echo $key; ?>"
                                                value="<?php echo $field['value']; ?>"
                                            <?php if( $field['type'] == 'checkbox' ){?>
                                                onclick="check(this)"
                                            <?php
                                                if($field['value'] == 1){ ?>
                                                    checked
                                            <?php }
                                            }
                                            ?>
                                            >
                                <?php  } ?>
                                            </div>
                                <?php } ?>

                                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                    <input type="hidden" name="mobile" value="<?php echo $mobile;?>">
                                    <input type="hidden" name="service_id" id="service_id" value="<?php echo $service_id; ?>">
                                    <input type="hidden" name="retailer_id" value="<?php echo $retailer_id;?>">
                                    <input type="hidden" name="dist_id" value="<?php echo $dist_id;?>">
                                    <input type="hidden" name="distributor_id" value="<?php echo $distributor_id;?>">

                                <?php if( $service_flag_value == 1 ){ ?>
                                    <span class="submit-button" id="submit_button_<?php echo $service_id; ?>">
                                        <input type="button" class="btn btn-sm btn-primary" onclick="saveDetails('<?php echo $service_id; ?>')" value="Save">
                                    </span>
                                <?php }

                                if( $services[$service_id]['registration_type'] == 2 && ($kit_flag_value == 1 ) ){
                                ?>
                                    <span class="submit-button" id="deactivate_mpos_<?php echo $service_id; ?>">
                                        <input type="button" class="btn btn-sm btn-primary" id="deactive" onclick="deactivateKit('<?php echo $service_id; ?>')" value="Deactivate Kit">
                                    </span>
                                <?php } ?>
                                <?php  if( $services[$service_id]['registration_type'] == 2 ) {?>
                                    <div id="plans_modal_<?php echo $service_id; ?>" class="modal fade" >
                                        <div class="modal-dialog" style="width:400px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Select Plan to Upgrade </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-2"><label class="">Plans</label></div>
                                                    <div class="col-lg-6">
                                                        <select class="form-control" id="new_plan_<?php echo $service_id; ?>">
                                                        <option value="">--select Plan--</option>
                                                            <?php
                                                            foreach( $fields['plan']['default_values'] as $plan_key => $plan_name ) { ?>
                                                                <option value="<?php echo $plan_key; ?>"><?php echo $plan_name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary col-lg-3 col-lg-offset-4" onclick="upgradePlan('<?php echo $service_id; ?>')">Upgrade</button>
                                                <button type="button" class="btn btn-default col-lg-3" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div id="refund_kit_modal_<?php echo $service_id; ?>" class="modal fade" >
                                        <div class="modal-dialog" style="width:400px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Enter Amount to Refund</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-2"><label class="">Amount</label></div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="refund_amount" id="refund_amount" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <div id="refundamt_div" style="display: none;float: left"><img src='/img/ajax-loader-2.gif' style='width:10px;height:5px;'></img> <b>loading..</b></div>
                                                <button type="button" class="btn btn-primary col-lg-3 col-lg-offset-4" id="btnrefundamt" onclick="refundKit('<?php echo $service_id; ?>','<?php echo $user_id;?>')">Upgrade</button>
                                                <button type="button" class="btn btn-default col-lg-3" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <span class="submit-button" id="upgrade_plan_<?php echo $service_id; ?>">
                                        <input type="button" class="btn btn-sm btn-success" id="upgrade_plan_button" onclick="showPlans('<?php echo $service_id; ?>')" value="Upgrade Plan">
                                    </span>
                                    <span class="submit-button" id="refund_kit_<?php echo $service_id; ?>">
                                        <input type="button" class="btn btn-sm btn-success" id="refund_kit__button" onclick="showRefundKitDialogue('<?php echo $service_id; ?>')" value="Refund Kit">
                                    </span>
                                <?php  } ?>
                                </form>
                    <?php    } // } else if ( array_key_exists('kit_flag',$fields) && array_key_exists('value',$fields['kit_flag']) && ($fields['kit_flag']['value'] == 2)  ){ // Kit refunded  ?>

                    <?php     // } else if( array_key_exists('kit_flag',$fields) && array_key_exists('value',$fields['kit_flag']) && ($fields['kit_flag']['value'] == 0) ){  // Deactivated kit ?>



                    <?php //    } else
                                if( $services[$service_id]['registration_type'] == 2 && array_key_exists('kit_flag',$fields) && ($fields['kit_flag']['value'] != 1) ){  // If there is no entry in users_services: new kit, Kit refunded ?>
                                    <form class="form-inline" method="" action="" id="service_form_<?php echo $service_id; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                        <input type="hidden" name="mobile" value="<?php echo $mobile;?>">
                                        <input type="hidden" name="service_id" id="service_id" value="<?php echo $service_id; ?>">
                                        <input type="hidden" name="retailer_id" value="<?php echo $retailer_id;?>">
                                        <input type="hidden" name="dist_id" value="<?php echo $dist_id;?>">
                                        <input type="hidden" name="distributor_id" value="<?php echo $distributor_id;?>">
                                    </form>
                                    <div id="plans_modal_<?php echo $service_id; ?>" class="modal fade" >
                                        <div class="modal-dialog" style="width:400px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Select Plan</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-2"><label class="">Plans</label></div>
                                                    <div class="col-lg-6">
                                                        <select class="form-control" id="plan_<?php echo $service_id; ?>">
                                                        <option value="">--select Plan--</option>
                                                            <?php
                                                            foreach( $fields['plan']['default_values'] as $plan_key => $plan_name ) { ?>
                                                                <option value="<?php echo $plan_key; ?>"><?php echo $plan_name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                    <div class="col-lg-2"><label class="">Payment Mode</label></div>
                                                    <div class="col-lg-8">
                                                        <select class="form-control" id="payment_mode_<?php echo $service_id; ?>">
                                                        <option value="">--select Payment Mode--</option>
                                                            <?php
                                                            foreach( $fields['payment_mode']['default_values'] as $id => $value ) { ?>
                                                                <option value="<?php echo $id; ?>"><?php echo $value; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary col-lg-3 col-lg-offset-4" onclick="purchaseKit('<?php echo $service_id; ?>')">Purchase</button>
                                                <button type="button" class="btn btn-default col-lg-3" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <span class="submit-button" id="purchase_kit_<?php echo $service_id; ?>">
                                        <?php
                                        $label = 'Purchase Kit';
                                        if( $fields['kit_flag']['value'] == 2 ){
                                            $label = 'Repurchase Kit';
                                        }
                                        ?>
                                        <input type="button" class="btn btn-sm btn-success" id="purchase_kit_button" onclick="showPlans('<?php echo $service_id; ?>')" value="<?php echo $label; ?>">
                                    </span>

                                    <?php if( $fields['kit_flag']['value'] == '0' ){ // Deactivated kit ?>
                                        <span class="submit-button" id="pullback_kit_<?php echo $service_id; ?>">
                                            <input type="button" class="btn btn-sm btn-success" id="pullback_kit_button" onclick="pullbackKit('<?php echo $service_id; ?>','<?php echo $user_id;?>')" value="Pullback Kit">
                                        </span>
                                    <?php }
                                }

                            if( $services[$service_id]['registration_type'] == 3 && array_key_exists('service_flag',$fields) && ($fields['service_flag']['value'] == 'NA') && ($services[$service_id]['activation_type'] == 1) ){
                                // not kit based and no entry in users services
                                // generate service request if activation type is manual ?>
                                <span class="submit-button" id="request_service_<?php echo $service_id; ?>">
                                    <input type="button" class="btn btn-sm btn-success" id="request_service__button" onclick="requestService('<?php echo $service_id; ?>','<?php echo $user_id;?>')" value="Request Service">
                                </span>

                          <?php  }


                            // } else if( $services[$service_id]['registration_type'] == 3 ){ // service based ?>


                            <?php // } ?>
                      </div>
                   </div>
            </div>
        </div>
<?php } }?>
