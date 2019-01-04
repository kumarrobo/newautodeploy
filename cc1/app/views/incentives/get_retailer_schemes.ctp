<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
    <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
    <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
    <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
    <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <h2>Retailer Schemes</h2>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#retailer_schemes_list" id="list">List Schemes</a></li>
    <li><a data-toggle="tab" href="#add_retailer_schemes">Add Schemes</a></li>
  </ul>
  
  <div class="tab-content">
    <div id="retailer_schemes_list" class="tab-pane fade in active">
    <form action="/incentives/getRetailerSchemes" method="post" id="scheme_list">
    <table class="table table-hover table-stripped table-bordered">
    <thead style="background-color:#428bca;color:#fff;">
        <tr>
            <th>Id</th>
            <th>Scheme Name</th>
            <th>Scheme Tag</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Incentive</th>
            <th>Services</th>
            <th>No. of Retailers</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($ret_schemes))
            {
            foreach ($ret_schemes as $scheme) {
                $service_names = array();?>
                <tr>
                    <td><?php echo $scheme['rs']['id']; ?></td>
                    <td><a target="_blank" href="/incentives/schemePanel/<?php echo $scheme['rs']['id'];?>"><?php echo $scheme['rs']['name']; ?></a></td>
                    <td><?php echo $scheme['rs']['scheme_tag']; ?></td>
                    <td><?php echo $scheme['rs']['start_date']; ?></td>
                    <td><?php echo $scheme['rs']['end_date']; ?></td>
                    <td><?php echo $scheme['rs']['incentive']; ?></td>
                    <?php
                    $service_ids = explode(',',$scheme['rs']['service_ids']); 
                    foreach ($service_ids as $id) {
                        $service_names[] = $services[$id]['name'];
                    }
                    ?>
                    <td><?php echo implode(',', $service_names)?></td>
                    <td><?php echo $scheme[0]['ret_count']; ?></td>
                    <td><input type="button" value="Delete" id="btndeletescheme" onclick="deleteScheme('<?php echo $scheme['rs']['id']; ?>')"></td>
                </tr>
                <?php $i++; ?>
            <?php } }
            else{?>
                <tr><td colspan="6">No scheme found</td></tr>
        <?php }?>
    </tbody>
    </table>
    </form>
    </div>
    <div id="add_retailer_schemes" class="tab-pane fade">
        <!--<div class="col-lg-12">-->
            <div class="panel panel-default">
            <div class="panel-heading">Add scheme</div>
            <div class="panel-body">
            <!--<form action="/incentives/uploadRetailerSchemesDetails" method="post" id="retailerSchemeForm" enctype="multipart/form-data">-->
            <form method="post" id="retailerSchemeForm">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label class="col-md-5 control-label" for="scheme_name">Scheme Name</label>
                                        <div class="col-md-7">
                                            <input id="scheme_name" name="scheme_name" type="text" placeholder="" class="form-control input-sm" value="<?php echo isset($params['scheme_name'])?$params['scheme_name']:""; ?>">
                                        </div>
                            </div>
                        </div>
                            <!--<br><br>-->                        

                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label class="col-md-5 control-label" for="start_date">Start Date</label>
                                        <div class="col-md-7">
                                          <input type="text" class="form-control" style=""  id="start_date" name="start_date"   value="<?php echo isset($this->params['form']['start_date'])?$this->params['form']['start_date']:date('Y-m-d'); ?>">
                                        </div>
                            </div>
                        </div>
                            
                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label class="col-md-5 control-label" for="end_date">End Date</label>
                                        <div class="col-md-7">
                                          <input type="text" class="form-control" style=""  id="end_date" name="end_date" value="<?php echo isset($this->params['form']['end_date'])?$this->params['form']['end_date']:date('Y-m-d'); ?>">
                                        </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-md-5 control-label" for="services">Services</label>
                                <div class="col-md-7">
                                    <select class="form-control" id="service_ids[]" name="service_ids[]" multiple="">
                                            <?php
                                            foreach($services as $id => $service)
                                            { ?>
                                            <option value="<?php echo $id;?>" <?php if($params['service_ids'] == $id){ echo "selected"; }?>><?php echo $service['name'];?></option>
                                            <?php }?>
                                            ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label class="col-md-5 control-label" for="incentive">Incentive</label>
                                        <div class="col-md-7">
                                            <input id="incentive" name="incentive" type="text" placeholder="" class="form-control input-sm" value="<?php echo isset($params['incentive'])?$params['incentive']:""; ?>">
                                        </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                        <label class="col-md-5 control-label" for="scheme_tag">Scheme Tag</label>
                                        <div class="col-md-7">
                                            <input id="scheme_name" name="scheme_tag" type="text" placeholder="" class="form-control input-sm" value="<?php echo isset($params['scheme_tag'])?$params['scheme_tag']:""; ?>">
                                        </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                    <label class="control-label col-lg-5">Upload</label>
                                    <div class="col-lg-7"><input type="file" name="retfile" id="retfile" /></div>
                            </div>
                        </div>
                        <div class="form-group">
                        <!--<button class="btn btn-success btn-xs pull-right" style="margin-right:10px"  type="submit" id="btnupload" onclick="uploadFile()"><span class="glyphicon glyphicon-download"></span>Upload</button>-->                                
                        <button class="btn btn-success btn-xs pull-right" style="margin-right:10px"  id="btnupload"><span class="glyphicon glyphicon-download"></span>Upload</button>                                
                        </div>
                    </div>
                    
                </form>
            </div>
            </div>
        <!--</div>-->
    </div>    
  </div>
</div>

</body>
</html>
<script>
    $(document).ready(function () {
            $('#start_date').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
//                endDate: "1d",
                multidate: false,
                autoclose: true,
                orientation: 'top right',
                todayHighlight: true
            });  
            $('#end_date').datepicker({
                format: "yyyy-mm-dd",
//                startDate: "-365d",
//                endDate: "1d",
                multidate: false,
                autoclose: true,
                orientation: 'top right',
                todayHighlight: true
            });  
            
            $('#list').on('click',function(e){
                e.preventDefault();
                $('#scheme_list').submit();
            });
            
            $('button#btnupload').on('click',function(e){
                e.preventDefault();
                var form = $("#retailerSchemeForm")[0];
                var form_data = new FormData(form); 
                var loader_html = $('#btnupload');
                var btn_html = $('#btnupload').html();
                var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";

                $.ajax({
                            url : "/incentives/uploadRetailerSchemesDetails",
                            type: "POST",
                            data : form_data,
                            processData: false,
                            contentType: false,
                            dataType:'json',
                            beforeSend: function () {
                                loader_html.html(loading_gif);
                            },
                            success : function(res){
                                loader_html.html(btn_html);                                
                                
                                alert(res.description);
                                return false;
                            }
                        });            
            });
        });
        
        $(document).ajaxStop(function(){
            window.location.reload();
        });
        
        function deleteScheme(scheme_id)
        {
            var loader_html = $('#btndeletescheme');
            var btn_html = $('#btndeletescheme').html();
            var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
            $.ajax({
                        url : "/incentives/deleteScheme",
                        type: "POST",
                        data : {scheme_id : scheme_id},
                        dataType:'json',
                        beforeSend: function () {
                            loader_html.html(loading_gif);
                        },
                        success : function(res){
                            loader_html.html(btn_html);

                            alert(res.description);
                            return false;
                        }
                    });  
        }
</script>