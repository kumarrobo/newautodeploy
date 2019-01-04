<html>
    <head>
    <title>DMT Admin Panel</title>    
    
        <link   rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <link rel="stylesheet" href="/boot/css/dmt.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/dmt.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>  
    </head>
  <style>

/*body {
    font-family: "Lato", sans-serif;
}

.sidenav {
    width: 180px;
    position: fixed;
    z-index: 1;
    top: 240px;
    left: 20px;
    background: #eee;
    overflow-x: hidden;
    padding: 8px 0;
}

.sidenav a {
    padding: 6px 8px 6px 16px;
    text-decoration: none;
    font-size: 15px;
    color: #2196F3;
    display: block;
}

.sidenav a:hover {
    color: #064579;
}

.main {
    margin-left: 140px;  Same width as the sidebar + left position in px 
    font-size: 28px;  Increased text to enable scrolling 
    padding: 0px 10px;
}

@media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 18px;}
}*/
table, th, td {
    border: 1px solid black;
}

</style>
    <body>
        <h2> Notification Panel</h2>        
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li><a href="/dmt/dmtFromto/ekonew" >All Transactions</a></li>
                <li class="active"><a href="/dmt/dmtAdminPanel" >Notification Panel</a></li>                
                <li><a href="/dmt/serviceToggle">Service Panel</a></li>
                <li><a href="/dmt/refundPanel">Refund Panel</a></li>
            </ul>
        </div>
    </nav>        
<!--    <div class="sidenavs">
      <a href="/dmt/dmtAdminPanel">Notification</a>
      <a href="/dmt/dmtAdminPanel">Notification Display/Update</a>
      <a href="#services">Refund</a> 
    </div>
        <br><br><br> -->
        <div class="container-fluid">
            <form id="notfForm" name="notfForm" method="POST">
             <div class="row">
               
                    <label for="notf_From">From :</label>
                    <input type="text" style="width:200px" id="notf_From" name="notf_From">
                    <input type ="text" style="width:200px" id="notf_ftime" name="notf_ftime" value="<?php echo $tod; ?>">
                               
                    <label for="notf_To" style="margin-left:20px">To :</label>
                    <input type="text" style="width:200px"  id="notf_To" name="notf_To">
                    <input type="text" style="width:200px"  id="notf_ttime" name="notf_ttime" value="<?php echo $tot; ?>"> 
                    </div><br>
                <?php $priority = array('0'=> "Low Priority","1"=>"High Priority");?>
                <div class="row">
                    <div class="col-md-3">
                        <label for="notf_Priority">Priority :</label>
                        <select id="notf_Priority" name="notf_Priority" class="form-control">
                            <option value="0">Select</option>                            
                            '<?php foreach($priority as $ids => $val){ ?>
                            <option value="<?php echo $ids;?>"><?php echo $val;?></option>                            
                            <?php } ?>
                        </select>
                    </div>   
                    <div class="col-md-3">
                        <label for="notf_Vendor">Vendor :</label>
                        <select id="notf_Vendor" name="notf_Vendor" class="form-control">
                            <option value="0">Select</option>                            
                            <?php foreach($vendorDet as $vend){ ?>
                            <option value="<?php echo $vend['vendor_master']['platform_vendor_id']?>"><?php echo $vend['vendor_master']['vendor_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>`
                </div>
                    <div class="row">
                    <div class="col-md-3">
                        <label for="notf_Plan">Plan :</label>
                        <select id="notf_Plan" name="notf_Plan" class="form-control">
                            <option value="0">Select</option>                            
                            <?php foreach($planDet as $plan){ ?>
                            <option value="<?php echo $plan['service_plans']['id']?>"><?php echo $plan['service_plans']['plan_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    </div>   
                <div class="row">
                        <label for="notf_Message" style="margin-top:20px;margin-bottom:-10px">Message :</label>
                </div><br>                       
                <div class="row">
                     <div class="col-md-5">
                        <textarea id="notf_Message" name="notf_Message" style="height:200px;width:400px"></textarea>
                        <button type="button" id="notf_submit" name="notf_submit" onclick="setNotification()" class="btn btn-primary">Submit</button>
                     </div>
                </div><br>
            </form>
        </div>
        <h2> Notification Update</h2>
        <div class="table-responsive">           
            <div class="tables table-bordered table-hover">
                <table>
                    <thead>
                    <th>id</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Priority</th>
                    <th>Vendor</th>
                    <th>Plan Name</th>
                    <th>Message</th>
                    <th>Show/Hide</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 1;
                    foreach($notfDet as $nDet){ ?>                    
                    <tr><td><?php echo $i; ?></td>
                        <td> <input type="text" class ="form-control" id="upnotf_From_<?php echo $nDet['communications']['id']; ?>" name="upnotf_From" value = "<?php echo $nDet['communications']['display_from']; ?>" disabled="true"></td>
                        <td> <input type="text" class ="form-control" id="upnotf_To_<?php echo $nDet['communications']['id']; ?>" name="upnotf_To" value = "<?php echo $nDet['communications']['display_to']; ?>" disabled="true"></td>
                            <td> <select id="upnotf_Priority_<?php echo $nDet['communications']['id']; ?>" name="upnotf_Priority" class="form-control" disabled="true">                                
                                   <option value="0" <?php if ($nDet['communications']['priority'] == 0) echo 'selected'; ?>>Low Priority</option>                                                       
                                   <option value="1" <?php if ($nDet['communications']['priority'] == 1) echo 'selected'; ?>>High Priority</option>                                                       
                            </select> </td>                        
                        <td><select id="upnotf_Vendor_<?php echo $nDet['communications']['id']; ?>" name="upnotf_Vendor" class="form-control" disabled="true">
                            <option value="0">Select</option>                            
                            <?php foreach($vendorDet as $vend){ ?>
                            <option value="<?php echo $vend['vendor_master']['platform_vendor_id']?>" <?php if($nDet['communications']['vendor_id'] == $vend['vendor_master']['platform_vendor_id']) echo 'selected' ?> ><?php echo $vend['vendor_master']['vendor_name'];?></option>
                            <?php } ?>
                            </select>
                        </td>
                        <td><select id="upnotf_Plan_<?php echo $nDet['communications']['id']; ?>" name="upnotf_Plan" class="form-control" disabled="true">
                                    <option value = "0">Select</option>                            
                                    <?php foreach ($planDet as $plan) { ?>
                                        <option value="<?php echo $plan['service_plans']['id'] ?>" <?php if ($nDet['communications']['plan'] == $plan['service_plans']['id']) echo 'selected' ?> ><?php echo $plan['service_plans']['plan_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><textarea id="upnotf_Message_<?php echo $nDet['communications']['id']; ?>" class="form-control" name="upnotf_Message" style="height:200px;width:400px" disabled="true"><?php echo $nDet['communications']['message']; ?></textarea></td>
                            <td><label class="switch">
                            <input type="checkbox" id="upnotf_ShowFlag_<?php echo $nDet['communications']['id']; ?>" name="upnotf_ShowFlag" <?php echo ($nDet['communications']['show_flag'] == '1') ? "checked" : ""; ?> disabled="true"> <span class="slider round"></span>
                             </label></td>
                        <td> 
                            <button type="button" class="btn btn-primary" id="upnotenb_<?php echo $nDet['communications']['id'];?>" name="upnotenb" onclick="notfEnable(<?php echo $nDet['communications']['id'];?>)">Update</button>
                            <button type="button" class="btn btn-primary" id="upnotf_<?php echo $nDet['communications']['id'];?>" name="upnotf" disabled="true" onclick="notfUpdate(<?php echo $nDet['communications']['id']; ?>)">Submit</button>
                        </td>
                    <?php  $i++; } ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>                
    </body>
</html>


<script>
$(document).ready(function () {
    $('#notf_From, #notf_to').datepicker({
        format: "yyyy-mm-dd",
        //startDate: "-365d",
        //endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
});    
</script>