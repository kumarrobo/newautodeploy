<html>
    <head>
        <title>Leads Panel</title>
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
                    <li><a href="/pan_services/panServicePanel">Pan Service</a></li>
                    <li  class="active"><a   href="/pan_services/leadReport">Lead Report</a></li>
                </ul>
            </div>
        </nav>-->
        <div class="row">
        <h2>Lead Panel</h2>
        </div>        
        <form id="lead_serviceForm" name="lead_serviceForm" method="POST">
              
  <div class="table-responsive">
  <table class="table table-responseive table-bordered">
      <thead>
          <tr>
              <th> Index </th>
              <th> Service Name</th>
              <th> Retailer Number </th>
              <th> Entered Number</th>
              <th> Date Time </th>
              <th> Status </th>
              <th> Comment </th>
              <th> Action </th>
          </tr>
      </thead>
      <tbody>          
              <?php $i = 1;
              foreach($leadDetails as $leads){ ?>
          <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $leads['service']; ?></td>
              <td><?php echo $leads['retailer_mob']; ?></td>
              <td><?php echo $leads['lead_mob']; ?></td>              
              <td><?php echo $leads['date']; ?></td>
              <td><select id="updStatus_<?php echo $leads['id']; ?>" name = "updStatus" class="form-control" style="width:120px;" onchange="leadStatus(<?php echo $leads['id']; ?>,this.value)" disabled="true">
                      <option value="">Select</option>  
                      <option value="0" <?php  if($leads['status'] ==  0) {echo 'selected';}?> >Pending</option> 
                      <option value="1" <?php if($leads['status'] ==   1) {echo 'selected';}?> >Converted</option>                                     
                      <option value="2" <?php  if($leads['status'] ==  2) {echo 'selected';}?> >Followup</option>                        
                      <option value="3" <?php  if($leads['status'] ==  3) {echo 'selected';}?> >Not Interested</option>                        
                  </select></td>                                
              <td><textarea id="upComment_<?php echo $leads['id']; ?>" name="upComment"disabled="true"><?php echo $leads['comment']; ?></textarea></td>
              <td><button type="button" class="btn btn-primary" id="updBtn_<?php echo $leads['id']; ?>" name="updBtn" onclick="EnbUpdlDetails(<?php echo $leads['id']; ?>)"> Update</button>
              <button type="button" class="btn btn-primary" id="updDetBtn_<?php echo $leads['id']; ?>" name="updDetBtn" disabled="true" onclick="UpdlDetails(<?php echo $leads['id']; ?>)"> Submit</button>
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
