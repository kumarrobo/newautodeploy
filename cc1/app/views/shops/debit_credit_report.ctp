<?php if($page!='download'){?>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php // echo $this->element('shop_side_reports',array('side_tab' => 'debitcreditreport'));?>
    		<div id="innerDiv">
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
                    <form method="post" id="debitCreditReport">
                        <input type="hidden" name='download_txns' id ='download_txns' value="">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" style="margin-left:-25px" for="">Date</label>
                                    <div class="col-md-10"  id="sandbox-container">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input type="text" class="form-control" name="from_date"  id="from_date" value="<?php echo $params['from_date']?$params['from_date']:date('Y-m-d'); ?>" />
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" name="to_date"  id="to_date"  value="<?php echo $params['to_date']?$params['to_date']:date('Y-m-d'); ?>"  />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="txn_date">Txn Type</label>
                                    <div class="col-md-8">
					<select id="txn_type" name="txn_type">
                                        <option value="">All</option>
					<?php foreach($txn_types as $key => $val) {?>
						<option value="<?php echo $val;?>" <?php if($params['txn_type'] == $val) echo "selected";?>><?php echo $key; ?></option>
					<?php } ?>
					</select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-2">
                        <select class="form-control" id="selected_service" name="selected_service" style="width:120px">
                            <option value="">Select Service</option>
                            <?php
                            foreach ($services as $service_id => $service_name) {
                                $ifselected = '';
                                if (($selected_service) && ($selected_service == $service_id)) {
                                    $ifselected = 'selected';
                                }
                                echo '<option ' . $ifselected . ' value="' . $service_id . '">' . $service_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-xs" onclick="getTxnReport()">Search</button>
                            <?php if(!empty($transactions)) {?>
                            <button type="submit" class="btn btn-success btn-xs" onclick="downloadTxnReport()"><span class="glyphicon glyphicon-download"></span>Download</button>
                            <?php }?>
    			</div>
                    </form>
                    <br>
                    <h4><b>Transaction History (<?php echo $params['from_date'];?> - <?php echo $params['to_date'];?>)</b></h4>
                    <table class="table table-bordered table-hover" style="width:900px;margin-top:20px;">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Service</th>
                            <th>Type</th>
                            <th>Dr</th>
                            <th>Cr</th>
                            <th>Description</th>
                            <!--<th>Date</th>-->
                            <th>Updated Time</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($transactions)) {
                                foreach($transactions as $transaction){ ?>
                                <tr>
                                    <td><?php echo $transaction[0]['uid'];?></td>
                                    <td><?php echo $transaction[0]['source_id'];?></td>
                                    <td><?php echo $transaction[0]['user_name'];?></td>
                                    <td><?php echo $services[$transaction[0]['user_id']];?></td>
                                    <td><?php echo array_search($transaction[0]['type'],$txn_types);?></td>
                                    <td><?php echo ($transaction[0]['source_opening'] < $transaction[0]['source_closing']) ? $transaction[0]['amount']:'0.00';?></td>
                                    <td><?php echo ($transaction[0]['source_opening'] > $transaction[0]['source_closing']) ? $transaction[0]['amount']:'0.00';?></td>
                                    <td><?php echo $transaction[0]['note'];?></td>
                                    <!--<td><?php echo $transaction[0]['date'];?></td>-->
                                    <td><?php echo $transaction[0]['timestamp'];?></td>
                                </tr>
                                <?php }
                                }else{ ?>
                                    <tr>
                                        <td colspan="10"><span class="success">No Records Found !!</span></td>
                                    </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>
 		</div>

    </div>
 </div>
<script>
    $(document).ready(function()
    {
        $('#sandbox-container .input-daterange').datepicker({
        format: "yyyy-mm-dd",
        startDate: "-365d",
        endDate: "1d",
        todayHighlight: true,
         orientation: "top right"
       });
    });

    function getTxnReport()
    {
        $("#download_txns").val('');
        $("#debitCreditReport").submit();
    }

    function downloadTxnReport()
    {
        $("#download_txns").val('download');
        $("#debitCreditReport").submit();
    }
</script>
<?php } ?>