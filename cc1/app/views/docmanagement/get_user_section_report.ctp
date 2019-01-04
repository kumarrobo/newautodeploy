<?php if($page!='download') { ?>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    thead{
        background-color: #428bca;
        color: #fff;
    }

    </style>
<div>

    <div class="incentive-report-container">
        <div><h3>User Section Report</h3></div>
        <div class="panel panel-default">
            <div class="panel-heading">Filter</div>
            <div class="panel-body">

                <form method="post" action="/docmanagement/getUserSectionReport" id="serviceactivationform">
        <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="txn_date">Date</label>
                            <div class="" id="sandbox-container">
                              <div class="input-daterange input-group" id="datepicker">
                                  <input type="text" class="form-control" name="from_date"  id="from_date" value="<?php echo $params['from_date']?$params['from_date']:date('Y-m-d'); ?>" />
                                  <span class="input-group-addon">to</span>
                                  <input type="text" class="form-control" name="to_date"  id="to_date"  value="<?php echo $params['to_date']?$params['to_date']:date('Y-m-d'); ?>"  />
                              </div>
                            </div>
                        </div>
                    </div>
<!--                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="service_id">Services</label>
                            <div class="">
                                <select class="form-control" id="service_id" name="service_id">
                                    <option value="">All</option>
                                    <?php foreach ($services as $id=>$name){ ?>
                                    <option value="<?php echo $id?>" <?php if($params['service_id'] == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>-->
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="status">Status</label>
                            <div class="">
                                <select class="form-control" id="status" name="status">
                                    <option value="">All</option>
                                    <?php foreach ($pay1_status as $id => $status) { ?>
                                    <option value="<?php echo $id?>" <?php if($params['status'] != '' && $params['status'] == $id){ echo "selected"; }?>><?php echo $status;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="user_type">User Type</label>
                            <div class="">
                                <select class="form-control" id="user_type" name="user_type" onchange="getUserList(this.value)">
                                    <option value="">All</option>
                                    <?php foreach ($user_types as $name => $group_id) { ?>
                                    <option value="<?php echo $group_id?>" <?php if($params['user_type'] == $group_id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="user_list">User List</label>
                            <div class="" id="user_list">
                                <select class="form-control" id="user_id" name="user_id" >
                                    <option value="">All</option>
                                    <?php if(!empty($params['user_type'])) { ?>
                                    <?php foreach ($user_list[$params['user_type']] as $id => $name){ ?>
                                    <option value="<?php echo $id?>" <?php if($params['user_id'] == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php  } ?>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
                    <div class="row">                        
                        <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="mobile">Retailer Mob</label>
                            <div class="">
                                <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $params['mobile'];?>"/>
                            </div>
                        </div>
                    </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-xs btn-primary" style="margin-top:0.75cm" onclick="getUserDetails()">Search</button>
                        <button type="button" class="btn btn-xs btn-success" style="margin-top:0.75cm" onclick="" id="btndownload">Download</button>
                    </div>
                </div>
                    </div>
            <input type="hidden" name='download' id ='download' value="">

            </form>
        </div>
    </div>

        <?php if( count($service_activation_details) > 0 ){ ?>
       <div class="table-responsive">
  <table class="table table-striped table-responsive table-bordered table-fixed" id="serviceactivationdetailsform">
      <thead>
          <tr>
              <th>Date</th>
              <th>Retailer Mobile</th>
              <th>Retailer ID</th>
              <th>Distributor ID</th>
              <th>User Type</th>
              <th>Documents</th>              
              <th>Uploaded by</th>
              <th>Uploaded Date</th>
              <th>Approve/Rejected by</th>
              <th>Approve/Rejected Date</th>
<!--              <th>Comment</th>             
              <th>Action</th>-->
          </tr>
      </thead>
      <tbody>          
              <?php              
              foreach($service_activation_details as $user_id => $section_details){
                  $count = 0;
                    if($params['status'] != ''){
                        foreach($section_details['sections'] as $section_name => $data){ 
                            if(strtolower($pay1_status[$params['status']]) == strtolower($data['status'])){
                                $count++;
                            }
                        }
                    }
                    if($params['status'] == '' || $count > 0)  { ?>
                    <tr>              
                        <td><?php echo $section_details['uploaded_date']; ?></td>
                        <td><?php echo '<a href="/docmanagement/getUserInformation?mobile='.$section_details['mobile'].'" target="blank">'.$section_details['mobile'].'</a>'; ?></td>
                        <td><?php echo $section_details['retailer_id']; ?></td>
                        <td><?php echo $section_details['distributor_id']; ?></td>
                        <td><?php echo $section_details['group_name']; ?></td>        
                        <td><?php foreach($section_details['sections'] as $section_name => $data){ 
//                            $section_status = isset($section_details['sections'][$section_name])?$section_details['sections'][$section_name]:'Pending';
                            if($params['status'] == ''){
                                echo $section_name.' => <b>';
                                echo $data['status'];
                                echo '</b><br/>';
                            }else{
                                if(strtolower($pay1_status[$params['status']]) == strtolower($data['status'])){
                                    echo $section_name.' => <b>';
                                    echo $data['status'];
                                    echo '</b><br/>';
                                }
                            }
                        }
                          ?></td>              
                        <td><?php echo $section_details['uploaded_by']; ?></td>              
                        <td><?php echo $section_details['uploaded_at']; ?></td>              
                        <td><?php echo $section_details['verified_by']; ?></td>              
                        <td><?php echo $section_details['verified_at']; ?></td>    
                    </tr>                 
              <?php }} ?>
      </tbody>
  </table>
    </div>

        <?php } else {
            echo '<h3>No records Found</h3>';
        }
    ?>
        <div class="modal fade" id="showcomments">
            <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #428bca;">
                    <h4 class="modal-title">Comments</h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12" id="loadcomments">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Ok</button>
                    </div>
            </div>
            </div>
        </div>
    </div>
<br class="clearRight" />
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>-->

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

        $('#serviceactivationdetailsform').dataTable({
//            "order": [[ 2, "desc" ]],
            "bSort" : false,
            "pageLength":1000,
            "lengthMenu": [ 10, 100, 1000 ]
        });

        $('button#btndownload').on('click',function(){
            $("#download").val('download');
            $('#serviceactivationform').submit();
        });

    });

    function getUserDetails()
    {
        $("#download").val('');
        $('#serviceactivationform').submit();
    }

    function getUserList(group_id)
    {
        var user_list = <?php echo json_encode($user_list); ?>;
        console.log(user_list);
        var user_list_html = "";
        var user_type = ['8','19','35','45'];
        $('#user_list').html('');
        if(user_list.length !== 0)
        {
            if(user_type.indexOf(group_id) > -1){                
                user_list_html = "<select id='user_id' name='user_id'>";
                user_list_html += "<option value=''>All</option>";
                $.each(user_list[group_id],function(user_id,user_name){
                    user_list_html += "<option value='"+user_id+"'>"+user_name+"</option>";
                });
                user_list_html += "</select>";
            }else{
                user_list_html = "<select class='form-control' id='user_id' name='user_id'><option value=''>All</option></select>";
            }
        }
        else
        {
            user_list_html = "<select class='form-control' id='user_id' name='user_id'><option value=''>All</option></select>";
        }
        $('#user_list').append(user_list_html);
    }
</script>
<?php } ?>