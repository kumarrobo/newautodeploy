<html>
    <head>
        <title>Service Registration Panel</title>
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link  rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/boot/js/kitdeliverysystem.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
        <style>
            .form-control{
            width:180px;}
        </style>
    </head>
    <body>
        <div class="row">
        <h2>Service Registration Panel</h2>
        </div>

        <form id="servc_registrationForm" name="servc_registrationForm" method="POST">
            <div class="panel panel-default">
            <div class="panel-heading">Filters</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5" style="text-align:center">
                    <label for="servc_registrationFrom" >Kit Assigned</label>
                    </div>
                    <div class="col-md-7" style="text-align:center">
                    <label for="servc_registrationFrom" >Service Request</label>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-3">
                    <label for="servc_registrationFrom" >From</label>
                    <input type="text" class="form-control" id="servc_registrationAssgnFrom" name="servc_registrationAssgnFrom" value="<?php echo $fromassgndate;?>">
                </div>

                <div class="col-md-3">
                    <label for="servc_registrationTo" >To</label>
                    <input type="text"   class="form-control" id="servc_registrationAssgnTo" name="servc_registrationAssgnTo" value="<?php echo $toassgndate;?>">
                </div>

                <div class="col-md-3">
                    <label for="servc_registrationFrom" >From</label>
                    <input type="text" class="form-control" id="servc_registrationReqFrom" name="servc_registrationReqFrom" value="<?php echo $fromreqdate;?>">
                </div>

                <div class="col-md-3">
                    <label for="servc_registrationTo" >To</label>
                    <input type="text"   class="form-control" id="servc_registrationReqTo" name="servc_registrationReqTo" value="<?php echo $toreqdate;?>">
                </div>
                </div>
           <div class="row">
                <div class="col-md-3">
                    <label for="servc_registrationServices" >Services</label>
                    <select   class="form-control" id="servc_registrationServices" name="servc_registrationServices">
                         <option value="0">Select</option>
                        <?php foreach($serviceName as $service){ ?>
                        <option value="<?php echo $service['services']['id'];?>" <?php if($service['services']['id'] == $services) echo 'selected' ?> ><?php echo $service['services']['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="servc_registrationStatus" >Status</label>
                    <select   class="form-control" id="servc_registrationStatus" name="servc_registrationStatus">
                        <option value="-1">Select</option>
                        <?php foreach($service_status as $id => $name) {?>
                        <option value="<?php echo $id;?>" <?php if($status == $id) echo 'selected' ?>><?php echo $name;?></option>
                        <?php } ?>
                    </select>
                </div>
                  <div class="col-md-3">
                    <label for="servc_registrationSource" >Source</label>
                <input type="text"   class="form-control" id="servc_registrationSource" name="servc_registrationSource" value="<?php echo $src; ?>">
                </div>
                 <div class="col-md-3">
                    <label for="servc_registrationRetailer" >Retailer Id</label>
                    <input type="text"   class="form-control" id="servc_registrationRetailer" name="servc_registrationRetailer" value="<?php echo $retId; ?>" >
                </div>
                </div>
                <div class="row">
                 <div class="col-md-3">
                    <label for="servc_registrationDistributor" >Distributor Id</label>
                    <input type="text"   class="form-control" id="servc_registrationDistributor" name="servc_registrationDistributor" value="<?php echo $distId; ?>">
                </div>
                 <div class="col-md-3">
                    <label for="servc_registrationMobile" >Retailer Mobile</label>
                    <input type="text"   class="form-control" id="servc_registrationMobile" name="servc_registrationMobile" value="<?php echo $ret_mobile; ?>">
                </div>
                 <div class="col-md-3">
                     <button type="Submit"  style="margin-top: 24px;"class="btn btn-primary" id="servc_registrationSub" name="servc_registrationSub"> Submit</button>
                </div>
                </div>
            </div>
            </div>

  <div class="table-responsive">
  <table class="tablex table-responseive table-bordered">
      <thead>
          <tr>
              <th> Kit Purchase/Assign Date </th>
              <th> Service Request Date </th>
              <th> Retailer Id </th>
              <th> Retailer Name </th>
              <th> Retailer Mobile </th>
              <th> Service </th>
              <th> Source </th>
              <th> Document </th>
              <th> Vendor Activation </th>
              <th> Activation Status </th>
              <th> Activation Date </th>
              <th> Comment </th>
              <th> Action </th>
          </tr>
      </thead>
      <tbody>
              <?php foreach($serviceRegistration as $servc){ ?>
          <tr>
              <td><?php echo $servc['kit_purchase']; ?></td>
              <td><?php echo $servc['service_request']; ?></td>
              <td><?php echo $servc['retailer_id']; ?></td>
              <td><?php echo $servc['shop_name']; ?></td>
              <td><?php echo $servc['ret_mobile']; ?></td>
              <td><?php echo $servc['service']; ?></td>
              <td><?php echo $servc['source']; ?></td>
              <td><?php echo implode('<br>',$servc['doc_status']); ?></td>
              <td><textarea id="upVendoract_<?php echo $servc['id']; ?>" name="upVendoract" class="form-control"  ><?php echo $servc['vendor_activation']; ?></textarea></td>
              <?php if($servc['status'] == 1 ) { ?>
              <td><select   class="form-control" id="upservc_registrationStatus_<?php echo $servc['id']; ?>" name="upservc_registrationStatus" disabled="true">
                 <?php foreach ($service_status as $id => $name) { ?>
                      <option value="<?php echo $id; ?>"<?php if($servc['status'] == $id) echo  'selected';?> ><?php echo $name; ?></option>
                <?php } ?>
               </select></td>
              <?php } else { ?>
              <td><select   class="form-control" id="upservc_registrationStatus_<?php echo $servc['id']; ?>" name="upservc_registrationStatus">
              <?php foreach ($service_status as $id => $name) { ?>
                  <option value="<?php echo $id; ?>"<?php if ($servc['status'] == $id) echo 'selected'; ?> ><?php echo $name; ?></option>
              <?php } ?>
               </select></td>
            <?php } ?>

              <input type = hidden style="display:none" id="upServiceid_<?php echo $servc['id']; ?>" name="upServiceid" class="form-control" value="<?php echo $servc['service_id']; ?>">
              <input type = hidden style="display:none" id="upUserid_<?php echo $servc['id']; ?>" name="upUserid" class="form-control" value="<?php echo $servc['user_id']; ?>">
              <td><?php echo $servc['activation_date']; ?></td>
              <td><textarea id="upComment_<?php echo $servc['id']; ?>" name="upComment" ><?php echo $servc['comments']; ?></textarea></td>
              <td>
              <button type="button" class="btn btn-primary" id="updServcBtn_<?php echo $servc['id']; ?>" name="updServcBtn"   onclick="UpdServcDetails(<?php echo $servc['id']; ?>)"> Submit</button>
              </td>
          </tr>
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
            $('#servc_registrationAssgnFrom, #servc_registrationAssgnTo,#servc_registrationReqFrom,#servc_registrationReqTo').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
                endDate: "1d",
                multidate: false,
                autoclose: true,
                todayHighlight: true
            });
        });

     $('.tablex').dataTable({
        // "order": [[0, "desc" ]],
        "pageLength": 50,
        "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, 'All']],
    });
function UpdServcDetails(id){
    var vendorAct = $('#upVendoract_'+id).val();
    var status    = $('#upservc_registrationStatus_'+id).val();
    var comment   = $('#upComment_'+id).val();
    var user_id   = $('#upUserid_'+id).val();
    var service_id= $('#upServiceid_'+id).val();

        $.ajax({
        type: "POST",
        url: '/kit_delivery_system/UpdserviceRegistration',
        dataType: "json",
        data: {id : id, status: status, comment: comment,user_id : user_id , service_id : service_id , vendorAct: vendorAct},

        success: function (data) {

            if(data.status == 'success'){
                alert(data.msg);
                //location.reload();
            }else {
                alert(data.description);
            }
        },
        failure: function (data) {
            alert('failed');
        }

  })


}
   </script>
