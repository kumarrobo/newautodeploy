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
        <div><h3>Summary Report</h3></div>
        <div class="panel panel-default">
            <div class="panel-heading">Filter</div>
            <div class="panel-body">

                <form method="post" action="/docmanagement/sectionStatusSummaryReport" id="sectionstatussummaryform">
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
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="" for="user_list">User List</label>
                            <div class="" id="user_list">
                                <select class="form-control" id="user_id" name="user_id" >
                                    <option value="">All</option>
                                    <?php // if(!empty($params['user_type'])) { ?>
                                    <?php foreach ($user_list[35] as $id => $name){ ?>
                                    <option value="<?php echo $id?>" <?php if($params['user_id'] == $id){ echo "selected"; }?>><?php echo $name;?></option>
                                    <?php  } ?>
                                    <?php //  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-xs btn-primary" style="margin-top:0.75cm" onclick="getUserDetails()">Search</button>
                    </div>
                    </div>
            </div>
            <input type="hidden" name='download' id ='download' value="">

            </form>
        </div>
    </div>

        <?php if( count($section_status) > 0 ){ ?>
       <div class="table-responsive">
  <table class="table table-striped table-responsive table-bordered table-fixed" id="serviceactivationdetailsform">
      <thead>
          <tr>
              <th>Sections</th>
              <th>Pending</th>
              <th>Approved</th>
              <th>Rejected</th>
          </tr>
      </thead>
      <tbody>          
              <?php              
              foreach($section_status as $section_name => $status){ ?>
                    <tr>              
                        <td><?php echo $section_name; ?></td>
                        <td><?php echo $status['pending_count']; ?></td>
                        <td><?php echo $status['approved_count']; ?></td>
                        <td><?php echo $status['rejected_count']; ?></td>                        
                    </tr>                 
                  <?php } ?>
      </tbody>
  </table>
    </div>

        <?php } else {
            echo '<h3>No records Found</h3>';
        }
    ?>
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

    });

    function getUserDetails()
    {
        $("#download").val('');
        $('#sectionstatussummaryform').submit();
    }
</script>