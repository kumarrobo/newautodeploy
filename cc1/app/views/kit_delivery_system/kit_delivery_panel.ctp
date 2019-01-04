<html>
    <head>
        <title>Kit Delivery Panel</title>
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">        
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->         
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->         
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>-->
        
        <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-3.3.7.min.js"></script>
  
        <script type="text/javascript" src="/boot/js/kitdeliverysystem.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <style>
            .form-control{
            width:180px;}
        </style>
    </head>
    <body>
        <div class="row">
        <h2>Kit Delivery Panel</h2>
        </div>
        
        <form id="kit_deliveryForm" name="kit_deliveryForm" method="POST">
            <div class="panel panel-default">  
            <div class="panel-heading">Filters</div>
            <div class="panel-body">
                <div class="row">
                <div class="col-md-3">
                    <label for="kit_deliveryFrom" >From</label>
                    <input type="text" class="form-control" id="kit_deliveryFrom" name="kit_deliveryFrom" value="<?php echo $fromdate;?>">
                </div>                
                    
                <div class="col-md-3">
                    <label for="kit_deliveryTo" >To</label>
                    <input type="text"   class="form-control" id="kit_deliveryTo" name="kit_deliveryTo" value="<?php echo $todate;?>">
                </div>         
                <div class="col-md-3">
                    <label for="kit_deliveryServices" >Services</label>                    
                    <select   class="form-control" id="kit_deliveryServices" name="kit_deliveryServices">
                        <option value="0">Select</option>
                        <?php foreach($serviceName as $service){ ?>
                        <?php echo $service['services']['id'];  ?>
                        <option value="<?php echo $service['services']['id'];?>" <?php if($service['services']['id'] == $services) echo 'selected' ?> ><?php echo $service['services']['name'];?></option>
                        <?php } ?>
                    </select>
                </div>   
                <div class="col-md-3">
                    <label for="kit_deliveryStatus" >Delivery Status</label>
                    <select   class="form-control" id="kit_deliveryStatus" name="kit_deliveryStatus">
                        <option value="-1" <?php  if($deliverystatus == '') echo 'selected'; ?>>Select</option>
                        <option value="0" <?php  if($deliverystatus == '0') echo 'selected'; ?>>Pending</option>
                        <option value="1" <?php  if($deliverystatus == '1') echo 'selected'; ?>>Dispatched</option>                        
                        <option value="2" <?php  if($deliverystatus == '2') echo 'selected'; ?>>Delivered</option>
                    </select>
                </div>                                        
                </div>       
                <div class="row">
                  <div class="col-md-3">
                    <label for="kit_deliverySource" >Source</label>
                    <select   class="form-control" id="kit_deliverySource" name="kit_deliverySource">
                        <option value="0">Select</option>
                        <option value="6" <?php  if($src == 6) echo 'selected'; ?>>Retailer</option>
                        <option value="5" <?php  if($src == 5) echo 'selected'; ?>>Distributor</option>                                                
                    </select>
                </div>
                 <div class="col-md-3">
                    <label for="kit_deliveryRetailer" >Retailer Id</label>
                    <input type="text"   class="form-control" id="kit_deliveryRetailer" name="kit_deliveryRetailer" value="<?php echo $retId; ?>" >
                </div>                       
                 <div class="col-md-3">
                    <label for="kit_deliveryDistributor" >Distributor Id</label>
                    <input type="text"   class="form-control" id="kit_deliveryDistributor" name="kit_deliveryDistributor" value="<?php echo $distId; ?>">
                </div>   
                 <div class="col-md-3">                    
                     <button type="Submit"  style="margin-top: 24px;"class="btn btn-primary" id="kit_deliverySub" name="kit_deliverySub"> Submit</button>
                </div>                                           
                </div>
            </div>    
            </div>
            
  <div class="table-responsive">
  <table class="table table-responseive table-bordered">
      <thead>
          <tr>
              <th> Dist Id </th>
              <th> Retailer/Distributor Id </th>
              <th> Retailer/Distributor Firm Name </th>
              <th> Source </th>
              <th> Service </th>
              <th> Kit Number </th>
              <th> Kit Plan </th>
              <th> Purchase Date </th>
              <th> Delivery By </th>
              <th> Delivery Address </th>
              <th> Device id </th>
              <th> Dispatch Status </th>
              <th> Dispatch Date </th>
              <th> Delivery Date </th>
              <th> Tracking Details </th>
              <th> Comment </th>
              <th> Action </th>
          </tr>
      </thead>
      <tbody>          
    <?php foreach($kitDetails as $index => $kits   ){ ?>
          <tr>
              <td><?php echo $kits['dist_id']; ?></td>
              <td><?php echo $kits['id']; ?></td>
              <td><?php echo $kits['company']; ?></td>
              <td><?php echo $kits['source']; ?></td>
              <td><?php echo $kits['service']; ?></td>
              <td><?php echo $kits['kits']; ?></td>
              <td><?php echo $kits['kit_plan']; ?></td>
              <input type = hidden style="display:none" id="upPlans_<?php echo $kits['val']; ?>" name="upPlans_" class="form-control" value="<?php echo $kits['plan_val']; ?>">
              <input type = hidden style="display:none" id="upDist_id_<?php echo $kits['val']; ?>" name="upDist_id_" class="form-control" value="<?php echo $kits['dist_id']; ?>">
              <input type = hidden style="display:none" id="upDist_userid_<?php echo $kits['val']; ?>" name="upDist_userid_" class="form-control" value="<?php echo $kits['dist_userid']; ?>">
        
              <td><?php echo $kits['purchase_date']; ?></td>
              <td><select id="updDeliveryBy_<?php echo $kits['val']; ?>" name = "updDeliveryBy" class="form-control" style="width:120px;" disabled="true" required="true">
                      <option value="">Select</option>  
                      <option value="0" <?php  if($kits['delivery_by'] == 0) {echo 'selected';}?> >Inventory</option>  
                      <option value="1" <?php  if($kits['delivery_by'] == 1) {echo 'selected';}?> >Distributor</option>                        
                      </select>
              </td>
              <td><textarea id="upDeliveryAddr_<?php echo $kits['val']; ?>" name="upDeliveryAddr" class="form-control" disabled="true" required="true"><?php echo $kits['delivery_address']; ?></textarea></td>
              <td><textarea id="upDeviceId_<?php echo $kits['val']; ?>" name="upDeviceId" class="form-control" disabled="true"required="true"><?php echo $kits['device_id']; ?></textarea></td>
              <td><select id="updDeliveryStatus_<?php echo $kits['val']; ?>" name = "updDeliveryStatus" class="form-control" style="width:120px;" disabled="true">
                      <option value="">Select</option>  
                      <option value="0" <?php  if($kits['dispatch_status'] == 0) {echo 'selected';}?> >Pending</option>  
                      <option value="1" <?php  if($kits['dispatch_status'] == 1) {echo 'selected';}?> >Dispatched</option>                        
                    <?php if($kits['dispatch_status'] > 0){ ?>
                      <option value="2" <?php  if($kits['dispatch_status'] == 2) {echo 'selected';}?> >Delivered</option> 
                   <?php } ?>  
                  </select></td>                  
              <td><?php echo $kits['dispatch_date']; ?></td>
              <td><?php echo $kits['delivery_date']; ?></td>
              <input type="text" style="display:none" id="upDeliveryDate_<?php echo $kits['val']; ?>" name="upDeliveryDate" class="form-control" value=" <?php echo $kits['delivery_date']; ?>"> 
              <td><textarea id="upTrackingDet_<?php echo $kits['val']; ?>" name="upTrackingDet" disabled="true"><?php echo $kits['tracking_details']; ?></textarea></td>
              <td><textarea id="upComment_<?php echo $kits['val']; ?>" name="upComment"disabled="true"><?php echo $kits['comments']; ?></textarea></td>
              <td><button type="button" class="btn btn-primary" id="updBtn_<?php echo $kits['val']; ?>" name="updBtn" onclick="EnbUpdDetails(<?php echo $kits['val']; ?>)"> Update</button>                  
              <button type="button" class="btn btn-primary" id="updDelBtn_<?php echo $kits['val']; ?>" name="updDelBtn" disabled="true" onclick="UpdKitDetails(<?php echo $kits['val']; ?>,<?php echo $kits['serviceid']; ?>,<?php echo $kits['dist_id']; ?>,<?php echo $index; ?>)"> Submit</button>
              </td>
          </tr>      
          
          
 <div id="operation_field_<?php echo $kits['serviceid'].'_'.$index; ?>" class="operations-modal modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
      </div>
    </div>

  </div>
</div>
              <?php } ?>
      </tbody>
  </table>
    </div>            

                
        </form>
        

</body>
        
</html>


    <script>
     // When the document is ready
        $(document).ready(function () {
            $('#kit_deliveryFrom, #kit_deliveryTo').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
                endDate: "1d",
                multidate: false,
                autoclose: true,
                todayHighlight: true
            });
        });


   </script>
