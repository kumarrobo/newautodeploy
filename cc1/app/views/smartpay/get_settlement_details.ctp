<?php if($page!='download' && $page!='txndownload'){?>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>


<div class="container-fluid">
    <div>
        <div class="row">
        <div class="col-lg-12 text-center">
            <h3>Settlement Details</h3>
        </div>
        </div>
    </div>

    <div class="row" style="margin-left:-115px;margin-right:-150px;">
        <div class="col-lg-12">
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
        <div class="col-lg-9">
            <div class="panel panel-default">
            <div class="panel-heading">Filters</div>

            <div class="panel-body">
                <form action="/smartpay/getSettlementDetails" method="post" id="settlementForm">
                        <div class="col-lg-5">
                            <div class="form-group">
                                        <label class="col-md-3 control-label" for="txn_date">Date</label>
                                        <div class="col-md-9" id="sandbox-container">
                                          <div class="input-daterange input-group" id="datepicker">
                                              <input type="text" class="form-control" name="from_date"  id="from_date" value="<?php echo $params['from_date']?$params['from_date']:date('Y-m-d'); ?>" />
                                              <span class="input-group-addon">to</span>
                                              <input type="text" class="form-control" name="to_date"  id="to_date"  value="<?php echo $params['to_date']?$params['to_date']:date('Y-m-d'); ?>"  />
                                          </div>
                                        </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="txn_status">Txn Status</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="txn_status" name="txn_status">
                                            <option value="" <?php if(trim($params['txn_status'])==""){ echo "selected"; }?>>All</option>
                                            <option value="P" <?php if($params['txn_status']=="P" && trim($params['txn_status'])!=""){ echo "selected"; }?>>Pending</option>
                                            <option value="S" <?php if($params['txn_status']=="S"){ echo "selected"; }?>>Success</option>
                                            <option value="F" <?php if($params['txn_status']=="F"){ echo "selected"; }?>>Failed</option>
                                    </select>
                                </div>
                            </div>
                             <br><br>
                             <div class="form-group">
                                <label class="col-md-4 control-label" for="settlement_mode">Settlement Mode</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="settlement_mode" name="settlement_mode">
                                            <option value="" <?php if(trim($params['settlement_mode'])==""){ echo "selected"; }?>>All</option>
                                            <option value="0" <?php if($params['settlement_mode']==0 && trim($params['settlement_mode'])!=""){ echo "selected"; }?>>Wallet</option>
                                            <option value="1" <?php if($params['settlement_mode']==1){ echo "selected"; }?>>Bank</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="txn_id">Txn ID</label>
                                        <div class="col-md-8">
                                            <input class="form-control input-sm" id="txn_id" name="txn_id" type="text" placeholder="" value="<?php echo isset($params['txn_id'])?$params['txn_id']:""; ?>">
                                        </div>
                            </div>

                            <br><br>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="txn_type">Settlement Status</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="txn_type" name="txn_type">
                                            <option value="" <?php if(trim($params['txn_type'])==""){ echo "selected"; }?>>All</option>
                                            <option value="P" <?php if($params['txn_type']=="P"){ echo "selected"; }?>>Unsettled</option>
                                            <option value="S" <?php if($params['txn_type']=="S"){ echo "selected"; }?>>Settled</option>
                                    </select>
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="error_code">Error Codes</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="error_code" name="error_code">
                                        <option value="">Select Code</option>
                                        <option value="91" <?php if(trim($params['error_code'])=="91"){ echo "selected"; }?>>91</option>
                                        <option value="ds" <?php if(trim($params['error_code'])=="ds"){ echo "selected"; }?>>DS</option>
                                        <option value="dd" <?php if(trim($params['error_code'])=="dd"){ echo "selected"; }?>>DD</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                        <label class="col-md-4 control-label" for="mobile_no">Retailer Mobile</label>
                                        <div class="col-md-8">
                                            <input id="mobile_no" name="mobile_no" type="text" placeholder="" class="form-control input-sm" value="<?php echo isset($params['mobile_no'])?$params['mobile_no']:""; ?>">
                                        </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="service_type">Services</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="service_type" name="service_type[]" multiple="multiple" data-selected-text-format="count">
                                            <!-- <option value="" <?php // if(trim($params['service_type'])==""){ echo "selected"; }?>>All</option> -->
                                            <?php foreach ($service_type as $service_id => $type){
                                                echo '<optgroup label="'.$services[$service_id].'">';
                                                foreach ($type as $k=>$v){ ?>
                                                    <option value="<?php echo $v;?>" <?php if(in_array($v,$params['service_type'])){ echo "selected"; }?>><?php echo $k;?></option>
                                            <?php }
                                                echo '</optgroup>';
                                            }?>
                                    </select>
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="device_type">Device Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="device_type" name="device_type[]" multiple="multiple" data-selected-text-format="count">
                                        <!-- <option value="" <?php // if(trim($params['device_type'])==""){ echo "selected"; }?>>All</option> -->
                                        <?php foreach ($device_type as $service_id => $types){
                                                echo '<optgroup label="'.$services[$service_id].'">';
                                                foreach ($types as $key => $value){ ?>
                                                    <option value="<?php echo $key;?>" <?php if(in_array($key,$params['device_type'])){ echo "selected"; }?>><?php echo $value;?></option>
                                                <?php }
                                                echo '</optgroup>';
                                            }?>
                                    </select>
                                </div>
                            </div>
                            <br><br>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="vendor_id" style="text-align:center;">Vendor</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="vendor_id" name="vendor_id[]" multiple="multiple" data-selected-text-format="count" style="text-align:right;">
                                            <!-- <option value="" <?php // if(trim($params['device_type'])==""){ echo "selected"; }?>>All</option> -->
                                            <?php foreach ($vendors as $service_id => $vendors){
                                                    echo '<optgroup label="'.$services[$service_id].'">';
                                                    foreach ($vendors as $key => $value){ ?>
                                                        <option value="<?php echo $key;?>" <?php if(in_array($key,$params['vendor_id'])){ echo "selected"; }?>><?php echo $value;?></option>
                                                    <?php }
                                                    echo '</optgroup>';
                                                }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="form-group">
                                <?php
                                if( isset($params['error_code']) && !empty($params['error_code']) && in_array(trim($params['error_code']),array('91','ds','dd')) ){
                                    echo '<button class="btn btn-danger btn-xs pull-right" style="padding: 3px;margin-right:30px"  type="button" id="btndisputedownload">
                                        <span class="glyphicon glyphicon-download">NPCI Report</span>
                                    </button>';
                                }
                                ?>
                                <button class="btn btn-success btn-xs pull-right" style="margin-right:30px"  type="button" id="btntxndownload"><span class="glyphicon glyphicon-download"></span>Bank Download</button>
                                <button class="btn btn-success btn-xs pull-right" style="margin-right:10px"  type="button" id="btndownload"><span class="glyphicon glyphicon-download"></span>Download</button>
                                <button class="btn btn-primary btn-xs pull-right" style="margin-right:10px"  type="submit" id="btnsearch" onclick="searchTxn()">Search</button>
                            </div>
                            <input type="hidden" name='download' id ='download' value="">
                            <input type="hidden" name='txn_ids' id ='txn_ids' value="">
                            <input type="hidden" name='from_bank' id ='from_bank' value="">
                </form>
             </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel panel-default">
                <div class="panel-heading">Update UTR</div>

                <div class="panel-body">
                <form action="/smartpay/uploadExcel" method="post" enctype="multipart/form-data" >
                            <div class="form-group">
                                <label class="control-label col-lg-3">Upload </label>
                                <div class="col-lg-9"><input type="file" name="utrfile" id="utrfile" /></div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2"><button type="submit" class="btn btn-default btn-primary btn-xs pull-left">Upload</button></div>
                            </div>
                            <input type="hidden" name='form_data' id ='form_data' value='<?php echo  json_encode($params); ?>'>
                </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php if(count($settlementDetails)>0){?>
    <div class="row" style="margin-left:-100px;">
        <div class="col-lg-12">
              <table class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th colspan="25">
                          <div class="col-sm-2 pull-left">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bank</label>
                                        <div class="col-sm-8">
                                            <label class="radio-inline"> <input type="radio" name="frombank" id="frombank" value="icici"  autocomplete="off">Icici</label>
                                        <label class="radio-inline"> <input type="radio" name="frombank" id="frombank" value="axis" autocomplete="off" > Axis</label>
                                        <input type="hidden" name="axisbank" id="axisbank" >
                                    </div>
                                   </div>
                            </div>
    </th>
                      </tr>
                      <tr class="bg-primary">
                                            <th><input type="button" id="select_all" value="Check" class="btn btn-xs btn-default"/></th>
                                            <th>Vendor</th>
                                            <th>Device Type</th>
                                            <th>Txn ID</th>
                                            <th>RRN</th>
                                            <th>Card no</th>
                                            <th>Card Brand</th>
                                            <th>TID</th>
                                            <th>Retailer Mobile</th>
                                            <th>Customer Mobile</th>
                                            <th>Retailer Id</th>
                                            <th>Distributor id</th>
                                            <th>Auth Code</th>
                                            <th>Txn Type</th>
                                            <th>Settlement Mode</th>
                                            <th>Bank Name</th>
                                            <th>Account No</th>
                                            <th>Account Name</th>
                                            <th>IFSC Code</th>
                                            <th>Amount</th>
                                            <th>Txn Status</th>
                                            <th>Settlement Status</th>
                                            <th>Time & Date</th>
                                            <th>Settlement Date Time</th>
                                            <th>Charges</th>
                                            <th>TDS</th>
                                            <th>Commision</th>
                                            <th>Plan</th>
                                            <th>Settlement Amount</th>
                                            <th>Partially Settled in Wallet</th>
                                            <th>Incentive</th>
                                            <th>Receipt URL</th>
                                            <th>UTR Id</th>
                                            <th>UTR Date</th>
                                            <th>UTR Comments</th>
                                            <th>Action</th>
                                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($settlementDetails as $detail):
                            if($detail['service_id']==8)
                            {
                                if( $detail['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'] )
                                {
                                    $txn_type="CW - DD : Non VISA";
                                } else if( $detail['product_id']==$service_type[8]['MPOS Withdrawal : VISA'] ){
                                    $txn_type="CW - DD : VISA";
                                }
                                elseif($detail['product_id']==$service_type[8]['Sale - CC : EMI'] || $detail['product_id']==$service_type[8]['Sale - CC'] || $detail['product_id']==$service_type[8]['Sale - DC'])
                                {
                                    // $res = array_search($detail['product_id'], $service_type[8]);
                                    // if($res){
                                    //     $txn_type=$res;
                                    // } else {
                                    //     $cardtype=$detail['payment_card_type']=="DEBIT"?"DC":"CC";
                                    //     $txn_type="Sale - ".$cardtype;
                                    // }

                                    $cardtype = '--';
                                    if( strtolower($detail['payment_card_type']) == "debit" ){
                                        $cardtype = "DC";
                                    } else if( strtolower($detail['payment_card_type']) == "credit" ){
                                        $cardtype = "CC";
                                        if( $detail['product_id']==$service_type[8]['Sale - CC : EMI'] ){
                                            $cardtype = "CC : EMI";
                                        }
                                    }
                                    $txn_type="Sale - ".$cardtype;

                                }
                            }
                            elseif($detail['service_id']==9)
                            {
                                $txn_type="UPI - ".$detail['vpa'];
                            }
                            elseif($detail['service_id']==10)
                            {
                                $txn_type="AEPS";
                                if(in_array($detail['product_id'],$service_type[$detail['service_id']])){
                                    $txn_type= array_search($detail['product_id'],$service_type[$detail['service_id']]);
                                }
                            }
                            $txn_status=$detail['txn_status']=="P"?"Pending":(($detail['txn_status']=="S")?"Success":"Failed - ".$detail['status_description']);
                            $settlement_flag=$detail['settlement_flag']==0?"W - ":"B - ";
                            $status = 'Failed';
                            if($detail['settlement_status']=="P"){
                                $status="Pending";
                            } else if($detail['settlement_status']=="S"){
                                $status="Settled";
                            } else if($detail['settlement_status']=="R"){
                                $status="Reversed";
                            }
                            $settlement_status=$settlement_flag.$status;
                            ?>
                        <tr>
                            <td><?php if($detail['settlement_status']=='P'){?><input type="checkbox"  name="chktxn" value="<?php echo $detail['txn_id'];?>"/><?php }?></td>
                            <td><?php echo $detail['vendor_id'];?></td>
                            <td><?php echo $detail['device_type'];?></td>
                            <td><?php echo $detail['txn_id'];?></td>
                            <td><?php echo $detail['rrn'];?></td>
                            <td><?php echo $detail['card_no'];?></td>
                            <td><?php echo $detail['paymentCardBrand'];?></td>
                            <td><?php echo $detail['tid'];?></td>
                            <td><?php echo $detail['mobile'];?></td>
                            <td><?php echo $detail['mobile_no'];?></td>
                            <td><?php echo $detail['user_details']['retailer_id'];?></td>
                            <td><?php echo $detail['user_details']['distributor_id'];?></td>
                            <td><?php echo $detail['auth_code'];?></td>
                            <td><?php echo $txn_type;?></td>
                            <td><?php echo ($detail['settlement_flag']==0)?"Wallet":"Bank";?></td>
                            <td><?php echo $detail['bank_details']['bank_name'];?></td>
                            <td><?php echo $detail['bank_details']['acc_no'];?></td>
                            <td><?php echo $detail['bank_details']['acc_name'];?></td>
                            <td><?php echo $detail['bank_details']['ifsc_code'];?></td>
                            <td><?php echo $detail['txn_amount'];?></td>
                            <td><?php echo $txn_status;?></td>
                            <td id="settlement_status_<?php echo $detail['txn_id'];?>"><?php echo $settlement_status;?></td>
                            <td><?php echo $detail['txn_time'];?></td>
                            <td id="settled_at_<?php echo $detail['txn_id'];?>"><?php echo $detail['settled_at'];?></td>
                            <?php
                            $charges = '';
                            $commission = '';
                            if(($detail['settlement_flag']==0) && ($detail['settlement_status']=="S")){
                                if($detail['wallet_details']['amt_settled'] > $detail['txn_amount']){
                                    $commission = $detail['wallet_details']['amt_settled'] - $detail['txn_amount'];
                                } else if( $detail['wallet_details']['amt_settled'] < $detail['txn_amount'] ){
                                    if( $detail['product_id']==$service_type[8]['Sale - CC : EMI'] || $detail['product_id']==$service_type[8]['Sale - CC'] || $detail['product_id']==$service_type[8]['Sale - DC'] ){
                                        $charges = $detail['txn_amount'] - $detail['wallet_details']['amt_settled']-$detail['settled_amount'];
                                    }
                                }
                            }
                            if( array_key_exists('commission',$detail['wallet_details']) ){
                                $commission = $detail['wallet_details']['commission'];
                                if( array_key_exists('tax',$detail['wallet_details']) && $detail['wallet_details']['tax'] > 0 ){
                                    $commission = $commission - $detail['wallet_details']['tax'];
                                }
                            }
                            ?>
                            <td><?php echo ( $charges > 0 ) ? round($charges,2) : $charges; ?></td>
                            <td><?php echo $detail[''];?></td>
                            <td><?php echo ( $commission > 0 ) ? round($commission,2) : $commission;?></td>
                            <td><?php echo $detail['plan'];?></td>
                            <td><?php echo $detail['wallet_details']['amt_settled'];?></td>
                            <td><?php echo $detail['settled_amount'];?></td>
                            <td><?php echo $detail['incentive_details']['amt_settled'];?></td>
                            <td><a href=<?php echo $detail['receipt_url'];?> target="_blank"><?php echo $detail['receipt_url'];?></a></td>
                            <td id="utr_id_<?php echo $detail['txn_id'];?>"><?php echo $detail['utr_id'];?></td>
                            <td id="utr_date_<?php echo $detail['txn_id'];?>"><?php echo $detail['utr_date'];?></td>
                            <td id="utr_comments_<?php echo $detail['txn_id'];?>"><?php echo $detail['utr_comments'];?></td>
                            <td id="utr_button_<?php echo $detail['txn_id'];?>" ><?php if(($detail['settlement_flag']==1) && ($detail['settlement_status']=="P")):?><button class="btn btn-default btn-success btn-xs" onclick="saveUtrMapping('<?php echo $detail['txn_id'];?>','<?php echo $detail['utr_id'];?>','<?php echo substr($detail['utr_date'],0,10);?>','<?php echo $detail['utr_comments'];?>')">Settle Now</button><?php endif;?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
              </table>
        </div>
    </div>
    <?php } else {  ?>
            <h3> No records found !</h3>
    <?php }?>

    <div id="settlementModal" class="modal fade" >
        <div class="modal-dialog" style="width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Map UTR ID</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">UTR Id</label></div>
                    <div class="col-lg-7"><input type="text" id="utr_id" name="utr_id"></div>
                </div>
                <div class="row">
                    <div class="col-lg-5"><label class="">UTR Date</label></div>
                    <div class="col-lg-7">
                            <input class="utr_date" name="utr_date" value="<?php echo isset($date)?$date:date("Y-m-d"); ?>" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5"><label class="">Comments</label></div>
                    <div class="col-lg-7"><textarea id="utr_comments" name="utr_comments"></textarea></div>
                </div>
                <input type="hidden" name="txn_id" id="txn_id" value="">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary col-lg-2 col-lg-offset-4" id="btnsavesettlements">Save</button>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/smartpay.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-multiselect.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#device_type').multiselect({
           enableClickableOptGroups: true,
           enableCollapsibleOptGroups: true,
        //    collapseOptGroupsByDefault: true,
           numberDisplayed: 1,
           maxHeight: 200
        });
        $('#vendor_id').multiselect({
           enableClickableOptGroups: true,
           enableCollapsibleOptGroups: true,
        //    collapseOptGroupsByDefault: true,
           numberDisplayed: 1,
           maxHeight: 200
        });
        $('#service_type').multiselect({
           enableClickableOptGroups: true,
           enableCollapsibleOptGroups: true,
        //    collapseOptGroupsByDefault: true,
           numberDisplayed: 1,
           maxHeight: 200
        });

    $('.table').dataTable({
    // "order": [[0, "desc" ]],
    "pageLength":10,
    "lengthMenu": [[10,100, 200, 500,-1],[10,100, 200, 500,'All']],
    });

    });
</script>
<?php }?>
