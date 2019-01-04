<html>
    <head>
        <title>Pan Services Panel</title>
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">        
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">        
        <link   rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/panservices.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>         
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>         
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

       <style>
            .form-control{
            width:180px;}
        </style>
    </head>
    <body>
<!--        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">                   
                    <li class="active"><a href="/pan_services/panServicePanel">Pan Service</a></li>
                    <li><a   href="/pan_services/leadReport">Lead Report</a></li>
                </ul>
            </div>
        </nav>-->
        <div class="row">
        <h2>Pan Service Panel</h2>
        </div>        
        <form id="pan_serviceForm" name="pan_serviceForm" method="POST">
            <div class="panel panel-default">  
            <div class="panel-heading">Filters</div>
            <div class="panel-body">
                <div class="row">
                <div class="col-md-3">
                    <label for="pan_serviceFrom" >From</label>
                    <input type="text" class="form-control" id="pan_serviceFrom" name="pan_serviceFrom"  value="<?php echo $panfrm; ?>">
                </div>                
                <div class="col-md-3">
                    <label for="pan_serviceTo" >To</label>
                    <input type="text"   class="form-control" id="pan_serviceTo" name="pan_serviceTo" value="<?php echo $panto; ?>">
                </div>
                <div class="col-md-3">
                    <label for="pan_serviceRetid" >Retailer Id</label>
                    <input type="text"   class="form-control" id="pan_serviceRetid" name="pan_serviceRetid" value="<?php echo $ret_id; ?>">
                </div>   
                 <div class="col-md-3">
                    <label for="pan_serviceAgId" >Agent Id</label>
                    <input type="text"   class="form-control" id="pan_serviceAgId" name="pan_serviceAgId" value="<?php echo $act_id; ?>">
                </div>                                                               
                </div>       
                
                <div class="row">                  
                <div class="col-md-3">
                    <label for="pan_serviceStatus" >Status</label>
                    <select   class="form-control" id="pan_serviceStatus" name="pan_serviceStatus">
                        <option value="">Select</option>                     
                      <option value="-1" <?php if($status == -1) {echo 'selected';}?> >Pending</option>                        
                      <option value="1" <?php  if($status ==  1) {echo 'selected';}?> >Alloted</option> 
                      <option value="2" <?php  if($status ==  2) {echo 'selected';}?> >Refunded</option>                                              
                    </select>
                </div>                    
                 <div class="col-md-3">                    
                     <button type="Submit"  style="margin-top: 24px;"class="btn btn-primary" id="pan_serviceSub" name="pan_serviceSub"> Submit</button>
                     <input type="hidden"    id="pan_excel" name="pan_excel" value="">
                     <button type="Submit"  style="margin-top: 24px;"class="btn btn-primary" id="pan_serviceExcel" name="pan_serviceExcel"> Download </button>
                </div>                                           
                </div>
            </div>    
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="PendCoupon"> Pending Coupon : <?php echo isset($couponCount[-1])?$couponCount[-1]:0; ?></label> 
                </div>
                <div class="col-md-3">
                    <label for="AllotCoupon"> Alloted Coupon : <?php echo isset($couponCount[1])?$couponCount[1]:0; ?></label> 
                </div>
                <div class="col-md-3">
                    <label for="PendCoupon"> Alloted Amount : <?php echo isset($couponAmt[1])?$couponAmt[1]:0; ?></label> 
                </div>                
            </div>   
  <div class="table-responsive">
  <table class="table table-responseive table-bordered">
      <thead>
          <tr>
              <th> Index </th>
              <th> Retailer Id </th>
              <th> Retailer Mobile </th>
              <th> Retailer Shop Name </th>
              <th> Product Activation Id </th>
              <th> Coupon Count </th>
              <th> Coupon Amount </th>
              <th> Deducted Amount </th>
              <th> Coupon Request Time  </th>
              <th> Status </th>
              <th> Alloted Time </th>
              <th> Alloted By </th>
              <th> Process Action </th>
              <th> Comment </th>
              <th> Action </th>
          </tr>
      </thead>
      <tbody>          
              <?php $i = 1;
              foreach($pandetails as $pans){ ?>
          <tr>
              <td><?php echo $i; ?></td>
              <td> <a href="/panels/retInfo/<?php echo $pans['mobile']; ?>" target="_blank"><?php echo $pans['ret_id']; ?></a></td>              
              <td><?php echo $pans['mobile']; ?></td>
              <td><?php echo $pans['shop_name']; ?></td>
              <td><?php echo $pans['agent_id']; ?></td>
              <td><?php echo $pans['quantity']; ?></td>              
              <td><?php echo $pans['couponamount']; ?></td>
              <td><?php echo $pans['amount']; ?></td>
              <td><?php echo $pans['request_date']; ?></td>
<!--              <td><select id="updPanStatus_<?php echo $pans['id']; ?>" name = "updPanStatus" class="form-control" style="width:120px;" disabled="true">
                      <option value="">Select</option>  
                      <option value="-1" <?php if($pans['status'] == -1) {echo 'selected';}?> >Pending</option>                                     
                      <option value="1" <?php  if($pans['status'] ==  1) {echo 'selected';}?> >Alloted</option> 
                      <option value="2" <?php  if($pans['status'] ==  2) {echo 'selected';}?> >Refunded</option>                        
                  </select></td>                                -->
              <td><input type="text"   class="form-control" id="updPanStatus_<?php echo $pans['id']; ?>" name="updPanStatus" value="<?php echo $pans['status']; ?>" disabled="true"></td>
              <td><?php echo $pans['alloted_date']; ?></td>
              <td><?php echo $pans['alloted_by']; ?></td>
              <?php if($pans['statusval'] === '-1') { ?>
              <td><button type="button" class="btn btn-success" id="updAllotBtn_<?php echo $pans['id']; ?>" name="updAllotBtn" onclick="AllotCoupon(<?php echo $pans['id']; ?>,<?php echo $pans['quantity']; ?>)"> Allot</button>
                  <button type="button" class="btn btn-danger" id="updRefundBtn_<?php echo $pans['id']; ?>" name="updRefundBtn"  onclick="RefundCoupon(<?php echo $pans['id']; ?>,<?php echo $pans['quantity']; ?>)"> Refund</button>
                  <input type="hidden" id ="agent_id_<?php echo $pans['id']; ?>" name="agent_id" value="<?php echo $pans['agent_id']; ?>" >
              </td>
              <?php } else { ?>
              <td>
                  <button type="button" class="btn btn-success" id="updAllotBtn_<?php echo $pans['id']; ?>" name="updAllotBtn" onclick="AllotCoupon(<?php echo $pans['id']; ?>,<?php echo $pans['quantity']; ?>,<?php echo $pans['agent_id']; ?>)" disabled="true"> Allot</button>
                  <button type="button" class="btn btn-danger" id="updRefundBtn_<?php echo $pans['id']; ?>" name="updRefundBtn"  onclick="RefundCoupon(<?php echo $pans['id']; ?>,<?php echo $pans['quantity']; ?>,<?php echo $pans['agent_id']; ?>)" disabled="true"> Refund</button>
              </td>
              <?php } ?>
              <td><textarea id="upComment_<?php echo $pans['id']; ?>" name="upComment"disabled="true"><?php echo $pans['comment']; ?></textarea></td>
              <td><button type="button" class="btn btn-primary" id="updBtn_<?php echo $pans['id']; ?>" name="updBtn" onclick="EnbUpdDetails(<?php echo $pans['id']; ?>)"> Update</button>
              <button type="button" class="btn btn-primary" id="updDetBtn_<?php echo $pans['id']; ?>" name="updDetBtn" disabled="true" onclick="UpdDetails(<?php echo $pans['id']; ?>)"> Submit</button>
              </td>
          </tr>                 
              <?php $i++;
              } ?>
      </tbody>
  </table>
    </div>            
                
        </form>
    </body>
        
</html>
<script>
                $('#pan_serviceExcel').click(function (e) {
                    e.preventDefault();
                    $('#pan_excel').val('1');
                    $('#pan_serviceSub').click();
                    $('#pan_excel').val('');
                });
</script>