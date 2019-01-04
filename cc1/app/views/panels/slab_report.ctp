<html>
    <head>        
        <title> Slab Admin Panel</title>        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            #create_slab{
                background-color: #337ab7;
                border: none;
                color: white;
                padding: 14px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 19px;
                margin: 5px 2px;
                float:right;
                border-radius: 50%;
            }
    
.slab_state_switch {
  position: relative;
  display: inline-block;
  width: 53px;
  height: 22px;
  margin:  auto;
}


.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 20px;
  left: 2px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

    
    .flash_message { text-align: center; color: #808080; margin-top: -20px;}
   
            
        </style>

    </head>    
    <body>
        <div class="container">
            <button id ="create_slab" name="create_slab" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#CreateSlabModal">+</button>                
            <div class="page-header">
                <h1> Slab Admin Panel</h1>
            </div>                

            <div class="table-responsive table-hover table-bordered">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Slabs</th>
                        <th>Distributor Commission</th>
                        <th>Distributor's</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                    <?php $i = 1;
                    foreach ($slab_det as $slab_name) {
                        ?>
                        <tr>
                            <td style="width:200px;"> <a href="/panels/slabInfo/<?php echo $slab_name['slabs']['id']; ?>"><?php echo $i; ?></a></td>                    
                            <td> <input type="input" name="slab_name_<?php echo $slab_name['slabs']['id']; ?>" id="slab_name_<?php echo $slab_name['slabs']['id']; ?>" style="width:260px;" value="<?php echo $slab_name['slabs']['name']; ?>" class="form-control" disabled="true"></td>
                            <td> <input type="input" name="slab_comm_<?php echo $slab_name['slabs']['id']; ?>" id="slab_comm_<?php echo $slab_name['slabs']['id']; ?>" style="width:150px;"
                                        onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="<?php echo $slab_name['slabs']['commission_dist']; ?>" class="form-control"  disabled="true"></td>                        
                            <td><label name="dist_count" id="dist_count" ><?php echo(isset($slab_detid[$slab_name['slabs']['id']])) ? $slab_detid[$slab_name['slabs']['id']] : 0; ?></label></td>
                            <td><button type="button" class="btn btn-primary"  id ="slab_name_edit_<?php echo $slab_name['slabs']['id']; ?>" onclick="edit(<?php echo $slab_name['slabs']['id']; ?>)">Update</button>
                                <button type="button" class="btn btn-primary"  id ="slab_name_upd_<?php echo $slab_name['slabs']['id']; ?>" onclick="slab_name_change(<?php echo $slab_name['slabs']['id']; ?>)" disabled="true">Submit</button></td>                    
                            <td> <label class="slab_state_switch" data-id="<?php echo $slab_name['slabs']['id']; ?>">
                                    <input type="checkbox" class="flag" id="slab_state"  <?php if ($slab_name['slabs']['active_flag'] == 1) {
                                                        echo "checked";
                                    } ?> data-size="mini">
                                        <div class="slider round"></div>
                                    </label></td>             
                        </tr>
    <?php $i++; } ?> 
                </table>
            </div>
        </div>

        <div id="CreateSlabModal" class="modal fade" role="dialog">
            <div class="modal-dialog">        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Create Slab</h4>
                    </div>
                    <div class="modal-body">
                        <label for="slabname">Slab Name :</label>
                        <input type="input" id="cslab_name" name ="cslab_name"><br> <br>     
                    </div>
                    <div class="modal-footer">
                        <div style="text-align: left;">
                            <button type="button" class="btn btn-primary" onclick="createSlab()" data-dismiss="modal">submit</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script>
            function edit(id) {
                $('#slab_name_' + id).removeAttr('disabled');
                $('#slab_comm_' + id).removeAttr('disabled');
                $('#slab_name_upd_' + id).removeAttr('disabled');
                $('#slab_name_edit_' + id).prop('disabled', true);
            }

            function slab_name_change(id) {
                var name = $('#slab_name_' + id).val();
                var comm = $('#slab_comm_' + id).val();
                $.ajax({
                    type: "POST",
                    url: '/panels/slabDataUpdate',
                    dataType: "json",
                    data: {slab_id: id, slab_name: name, slab_comm: comm},

                    success: function (data) {
                        $('#slab_name_' + id).prop('disabled', true);
                        $('#slab_comm_' + id).prop('disabled', true);
                        alert("Changes Updated Successfully");
                        $('#slab_name_upd_' + id).prop('disabled', true);
                        location.reload();
                    },
                    error: function (data) {
                        alert("error found");
                    }
                });
            }

            function createSlab() {
                var newname = $('#cslab_name').val();                
                $.ajax({
                    url: '/panels/slabCreation',
                    type: "POST",
                    dataType: "json",
                    data: {name: newname},

                    success: function (data) {
                        alert("Slab Created Successfully");
                        location.reload();
                    },
                    error: function (data) {
                        alert("error found");
                    }
                });
            }
            
           $('label.slab_state_switch').change(function(e) {
          var id = $(this).data('id');
     
            $.post('/panels/slabchangeFlag', {id: id, flag: 'active_flag'}, function(e) { if(e != 1) { alert("Something Went Wrong"); }}, 'json');
    });
    
        </script>        
    </body>
</html>

