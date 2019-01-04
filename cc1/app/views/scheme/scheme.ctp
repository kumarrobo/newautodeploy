<?php

/* 
 * * Created On 3 Jan 2018 by swapnilT
 * It used to schedule aand update schemes for distributors
  */
?>
<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<link rel="stylesheet" media="screen" href="/boot/css/select2.css">
<style>
    thead{
        background-color: #428bca;
        color: #fff;
    }
    .sales-report-container,.sales-report-filter{
        margin-top: 25px;
        margin-bottom: 25px;
    }
    th,td{
        text-align:center;
    }
    table.dataTable tbody td{padding:8px 2px !important;}
    
    #createscheme .col-md-4 input, #createscheme .col-md-4 select{width:100%;}
    #createscheme .col-md-12{padding:0px;}
    #createscheme .row{margin:10px 0 0 0;}
    
    #updatescheme .col-md-4 input, #createscheme .col-md-4 select{width:100%;}
    #updatescheme .col-md-12{padding:0px;}
    #updatescheme .row{margin:10px 0 0 0;}
    
    span.delete{border-radius: 10px;    padding: 4px;    font-size: small; cursor: pointer}
    
    .loader{color:#fff;position:fixed;box-sizing:border-box;left:-9999px;top:-9999px;width:0;height:0;overflow:hidden;z-index:999999}
    .loader:after,.loader:before{box-sizing:border-box;display:none}
    .loader.is-active{background-color:rgba(0,0,0,0.85);width:100%;height:100%;left:0;top:0}
    .loader.is-active:after,.loader.is-active:before{display:block}@keyframes rotation{from{transform:rotate(0)}to{transform:rotate(359deg)}}@keyframes blink{from{opacity:.5}to{opacity:1}}.loader[data-text]:before{position:fixed;left:0;top:50%;color:currentColor;font-family:Helvetica,Arial,sans-serif;text-align:center;width:100%;font-size:14px}.loader[data-text='']:before{content:'Loading'}.loader[data-text]:not([data-text='']):before{content:attr(data-text)}.loader[data-text][data-blink]:before{animation:blink 1s linear infinite alternate}.loader-default[data-text]:before{top:calc(50% - 63px)}.loader-default:after{content:'';position:fixed;width:48px;height:48px;border:solid 8px #fff;border-left-color:transparent;border-radius:50%;top:calc(50% - 24px);left:calc(50% - 24px);animation:rotation 1s linear infinite}.loader-default[data-half]:after{border-right-color:transparent}.loader-default[data-inverse]:after{animation-direction:reverse}
    
    .btn-group-toggle label.btn.active {
    background-color: #009688;
    color: #fff;
    border: 1px solid transparent;
}
.btn-group-toggle label.btn{border:1px solid #ccc;}
.select2-container--default .select2-selection--single .select2-selection__clear{
    float: none;
    padding: 4px;
    font-size: small;
}
</style>
<div>
        <?php if( ($validation_error) && !empty($validation_error) ){ ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo $validation_error; ?>
                    </div>
        <?php } ?>
    
        <div class="sales-report-filter">
            <div class="loader loader-default" data-text=""></div>
            <div class="row">
                <div id="filter-panel" class="filter-panel">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div>
                                   <?php //if(!empty($scheme_list)){  ?>
                                     <!--<a href="viewscheme" class="btn btn-info" role="button">View Schemes</a>-->
                                   <?php // } ?>
                                      <button type="button" class="btn btn-info " data-toggle="modal" data-target="#createscheme">Add Scheme</button>
                                       
                                      <a href="schemeReport" target="_blank" class="btn btn-info">Report</a>
                            </div>
                            <div class="container">
                              
                                </div>
                            </div>
                        </div>
                               <?php if(!empty($scheme_list)){  ?>
                                 <div style="margin-top:10px">
                                    <table class="table table-striped table-responseive">
                                            <thead>
                                                <th>Scheme</th>
                                                <th>Services</th>
                                                <th>Distributors</th>
                                                <th>Frequency</th>
                                                <!--<th>Priority</th>-->
                                                <th>Status</th>
                                                <th>&nbsp;</th>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                
                                                foreach($scheme_list as $key=>$val){ 
                                                    $id=  $val['schemes']['id'];
                                                    ?>
                                                <tr>
                                                    <td><a href="/scheme/schemeReport/<?php echo $id;?>"><?php echo $val['schemes']['name']?></a></td>
                                                    <td><?php if(!empty($val[0]['service'])) 
                                                        $i=0;
                                                        $services_list = '';
                                                        foreach(explode(',',$val[0]['service']) as $val2){
                                                            if($i>0)
                                                                $services_list .=',';
                                                            
                                                             $services_list.= $services[$val2];
                                                            $i++;
                                                    } 
                                                    echo $services_list;
                                                    ?>
                                                    <td>
                                                        <a href="getSchemeDistributor/<?php echo $id; ?>" target="_blank" class="btn btn-default"> Distributor List </a>
                                                        <?php
//                                                                                $distList = explode(',', $val[0]['dist_ids']);
//                                                                                $distarray = array();
//                                                                                foreach($distList as $row){
//                                                                                       
//                                                                                     echo $distributors[$row]['company'];  if(!empty($row)){ 
//                                                                                         $distarray[$row] = $distributors[$row]['company'];
  //                                                           ?>
                                                        <!--<span class="btn-primary delete" onclick='deleteDist("<?php echo $row; ?>","<?php echo $id; ?>")'> Delete </span> <br>-->
                                                                 <?php//  }  } ?>
                                                    </td>
                                                    <td><?php switch($val['schemes']['settlement']):
                                                                            case 0 : echo "daily";
                                                                                break;
                                                                            case 1 : echo  "scheme end";
                                                                                break;
                                                                          endswitch;
                                                                    ?></td>
                                                  <!--  <td><?php switch($val['schemes']['type']):
                                                                            case 0 : echo "Low";
                                                                                break;
                                                                            case 1 : echo  "High";
                                                                                break;
                                                                          endswitch;
                                                                    ?></td>-->
                                                    <td>
                                                                <?php echo empty($val['schemes']['isactive']) ? "In-active" : 'Active'; ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="getscheme(<?php echo $id; ?>)">Edit Scheme</button>
                                                        <button type="button" class="btn btn-info btn-sm" onclick="add_dist(<?php echo $id; ?>,'<?php echo $val[0]['dist_ids']; ?>')" >Add Distributor</button>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                    </table>
                    </div>
                    <?php }?>
                        <!--Create Scheme  Modal -->
                      <div class="modal fade" id="createscheme" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                         <form  method="post" name="frmaddscheme" id="frmaddscheme">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Create New Scheme</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 error-msg">
                                        
                                    </div>
                                </div>
                                      <div class="row">
                                      <div class="col-md-3">
                                          <input type="text" class="form-control" name="scheme" placeholder="scheme" required>
                                      </div>
                                      <div class="col-md-3">
                                          <select name="service[]" id="service" multiple="multiple" class="form-control" >
                                                 <?php foreach($services as $key=>$val){ ?>
                                                 <option value="<?php echo $key; ?>">
                                                               <?php echo $val; ?>
                                                 </option>
                                                 <?php } ?>
                                            </select>
                                      </div>
                                      <div class="col-md-3">
                                                    <select name="frequency" class="form-control">
                                                        <option value="0">daily</option>
                                                        <option value="1">Scheme End</option>
                                                    </select>
                                            </select>
                                      </div>
                                    <!--<div class="col-md-3">
                                            <select name="priority" class="form-control">
                                                <option value="0">Low</option>
                                                <option value="1">High</option>
                                            </select>
                                    </div>-->
                                      </div>
                                      <div class="row">
                                        <div class="form-group col-md-12 sellrange" id="0">
                                            <div class="row col-md-12 targetrange">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="sellrange[]" placeholder="sell range " value="0-0" required pattern="[0-9]{1,9}-[0-9]{1,9}" title="eg 0-10000">
                                                </div>
                                                <div class="col-md-3">
                                                        <input type="text" class="form-control" name="target[]" placeholder="target " required>
                                                </div>
                                                <div class="col-md-3">
                                                        <input type="text" class="form-control" name="incentive[]" placeholder="Incentive" required>
                                                </div>
                                                <div class="col-md-3">
                                                        <button type="button" class="btn btn-primary addtarget">Add Target</button>
                                                        <button type="button" class="btn btn-primary addsellrange">Add Sell Range</button>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                  

                                
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit </button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                              </form>
                          </div>

                        </div>
                      </div>
                <!-- Close Modal -->
                <!--Add Distributor  Modal -->
                <div id="adddistributor" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <form  method="post"  id="frmadddist">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Add Distributor</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 error-msg">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                        <input type="text" class="form-control " name="from" value="" placeholder="From date"required>
                                                  </div>
                                                  <div class="col-md-4">
                                                        <input type="text" class="form-control " name="to" value="" placeholder="To date" required>
                                                  </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="hidden" name="all_dist" value="0" />
                                                    <input type="checkbox" id="select_all_dist" name="all_dist" value="1">Select All Distributor
                                                    <p id="add_distributor">
                                                     
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                   
                                                </div>
                                               
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Add</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                            </form>
                          </div>

                        </div>
                      </div>
                <!--END Add Distributor  Modal -->
                 <!-- Update scheme Modal -->
                      <div class="modal fade" id="updatescheme" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <form  method="post" name="frmupdatescheme" id="frmupdatescheme">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Update Scheme</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 error-msg">
                                        
                                    </div>
                                </div>
                                      <div class="row">
                                      <div class="col-md-3">
                                          <input type="text" class="form-control" name="scheme" placeholder="scheme" required>
                                      </div>
                                      <div class="col-md-3 services-list">
                                            
                                      </div>
                                      <div class="col-md-3">
                                                    <select name="frequency" class="form-control">
                                                        <option value="0">daily</option>
                                                        <option value="1">Scheme End</option>
                                                    </select>
                                            </select>
                                      </div>
                                    <!--<div class="col-md-3">
                                            <select name="modal_priority" class="form-control">
                                                <option value="0">Low</option>
                                                <option value="1">High</option>
                                            </select>
                                    </div>-->
                                      </div>
                                      <div class="row">
                                        <div class="form-group col-md-12 sellrange" id="0">
                                            <div class="row col-md-12 targetrange">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="sellrange[]" placeholder="sell range " value="0-0" required pattern="[0-9]{1,9}-[0-9]{1,9}" title="eg 0-10000">
                                                </div>
                                                <div class="col-md-3">
                                                        <input type="text" class="form-control" name="target[]" placeholder="target " required>
                                                </div>
                                                <div class="col-md-3">
                                                        <input type="text" class="form-control" name="incentive[]" placeholder="Incentive" required>
                                                </div>
                                                <div class="col-md-3">
                                                     <button type="button" class="btn btn-primary addtarget">Add Target</button>
                                                    <button type="button" class="btn btn-primary addsellrange">Add Sell Range</button>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                       <div class="row">
                                        <div class=" col-md-12 " style="padding:0 15px;">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn active">
                                                      <input type="radio" name="schemeactivation" id="option1" autocomplete="off" value="1"> Active
                                                    </label>
                                                    <!--<label class="btn ">
                                                        <input type="radio" name="schemeactivation" id="option2" autocomplete="off" value="0"> In-active
                                                    </label>-->
                                            </div>
                                        </div>
                                    </div>

                                
                            </div>
                            <div class="modal-footer">
                                
                                <button type="submit" class="btn btn-primary">Submit </button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                              </form>
                        
                          </div>

                        </div>
                      </div>
                <!-- Update scheme  Close Modal -->
                </div>
            </div>
        </div>
    </div>
   
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/boot/js/select2.js"></script>
    <script>
        $(document).ready(function(){
      $('input[name="from"]').datepicker({
        minViewMode:3,
        format: 'yyyy-mm-dd',
        startDate: "-7d",
        orientation: 'bottom'
    });
     $('input[name="to"]').datepicker({
         minViewMode:3,
         format: 'yyyy-mm-dd',
         startDate: "1d",
     });
     
     var i=0;
     var p='';
     $(document).on('click','.removetarget',function(event){
         $(this).parent().parent().remove(".row")
     });
     
     $(document).on('click','.addtarget',function(event){
        var m='';
        var n='';
        
         if($(this).parent().parent().parent().attr('id')>0)
             m=$(this).parent().parent().parent().attr('id');
          n= $('#updatescheme').hasClass('in') ? $('form#frmupdatescheme input[name="target'+m+'[]"]').length : $('form#frmaddscheme input[name="target'+m+'[]"]').length
          //console.log(' length of index '+ n);
          if(n-1>0)
              p=n-1;
          else
              p='';
          //console.log('targetrange'+p);
          //console.log('div .sellrange'+m+' .targetrange'+p);
         $('div .sellrange'+m+' .targetrange'+p).after('<div class="row col-md-12 targetrange'+n+'"><div class="col-md-3"> </div><div class="col-md-3"><input type="text" required class="form-control" name="target'+m+'[]" placeholder="target"></div><div class="col-md-3"><input type="text" required class="form-control" name="incentive'+m+'[]" placeholder="incentive"></div><div class="col-md-3"><button class="btn btn-primary removetarget">Remove Target</button></div></div>');
         
     })
     $(document).on('click','.addsellrange',function(event){
     
         i= $('#updatescheme').hasClass('in') ? $('form#frmupdatescheme input[name="sellrange[]"]').length : $('form#frmaddscheme input[name="sellrange[]"]').length
         
         var k = '';
         if((i-1)>0){
             k=(i-1);
         }
         $('div .sellrange'+k).after('<div class="col-md-12 sellrange'+i+'" id="'+i+'"><div class="row col-md-12 targetrange"><div class="col-md-3">\n\
                                                        <input type="text" class="form-control" name="sellrange[]" placeholder="sell range " value="0-0" required pattern="[0-9]{1,9}-[0-9]{1,9}" title="eg 0-10000">\n\
                                                        </div>\n\
                                                        <div class="col-md-3"><input type="text" required class="form-control" name="target'+i+'[]" placeholder="target"></div>\n\
                                                <div class="col-md-3"><input type="text" required class="form-control" name="incentive'+i+'[]" placeholder="incentive"></div>\n\
                                                <div class="col-md-3"><button type="button" class="btn btn-primary addtarget">Add Target</button><button type="button" class="btn btn-primary" id="remove'+i+'" onclick="removeTaret('+i+')">Remove Sell Range</button></div></div></div>')
     })
     
     $('form#frmadddist').on('submit',function(event){
          event.preventDefault();
           var formData = $('form#frmadddist').serializeArray()
           //console.log(formData);
           $.ajax({
                url: './adddistToScheme',
                type: 'post',
                dataType: 'json',
                success: function (data) {
                   //console.log('data '+data.error);
                   $('form#frmadddist div.error-msg').html('');
                    if(data.error==1){
                        $('form#frmadddist div.error-msg').append('<div class="alert alert-danger">'+data.msg+'</div>')
                    }else{
                        $('form#frmadddist div.error-msg').append('<div class="alert alert-success">'+data.msg+'</div>')
                    }
                },
                data: formData
        });
     })
     
     $('#frmaddscheme').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#frmaddscheme').serializeArray()
        //console.log(formData);
        
        $.ajax({
            url: './addscheme',
            type: 'post',
            dataType: 'json',
            success: function (data) {
               //console.log('data '+data.error);
               $('form#frmaddscheme div.error-msg').html('');
                if(data.error==1){
                    $('form#frmaddscheme div.error-msg').append('<div class="alert alert-danger">'+data.msg+'</div>')
                }else{
                    $('form#frmaddscheme div.error-msg').append('<div class="alert alert-success">'+data.msg+'</div>')
                }
                
            },
            data: formData
        });
       
     });
     
     
     $('#frmupdatescheme').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#frmupdatescheme').serializeArray()
        //console.log(formData);
        
        $.ajax({
            url: './updatescheme',
            type: 'post',
            dataType: 'json',
            success: function (data) {
               //console.log('data '+data);
               $('form#frmupdatescheme div.error-msg').html('');
                if(data.error==1){
                    $('form#frmupdatescheme div.error-msg').append('<div class="alert alert-danger">'+data.msg+'</div>')
                }else{
                    $('form#frmupdatescheme div.error-msg').append('<div class="alert alert-success">'+data.msg+'</div>')
                }
                
            },
            data: formData
        });
       
     });
     
     
     $('#createscheme').on('hidden.bs.modal', function () {
          window.location.href = "";
    });
    
    $('#updatescheme').on('hidden.bs.modal', function () {
          window.location.href = "";
    });
    $('#adddistributor').on('hidden.bs.modal', function () {
          window.location.href = "";
    });
 });
 function showloader(params){
    $('div.loader').attr('data-text',params);
     $('div.loader').addClass('is-active');
 }
 function hideloader(){
     
     $('div.loader').attr('data-text','');
     $('div.loader').removeClass('is-active');
 }
 function removeTaret(id){
    $('div .sellrange'+id).remove();
 }
 
 function add_dist(schemeId,dist_ids){
            $('form#frmadddist input[name=from]').before('<input type="hidden" name="schemeId" value="'+schemeId+'">');
            $('#add_distributor').html('');
            var distributors  = [];
            //console.log(' dist id '+dist_ids);
            var operator_cell = '<select  id="distributor" name="distributor[]" style="width: 150px;" multiple="multiple">';
            if(dist_ids.length > 0)
               distributors  =dist_ids.split(',');
            var selected = '';
            
            <?php foreach($distributors as $key => $dist){ ?>
            operator_cell += "<option value='<?php echo $key;?>'><?php echo $dist['company']; ?></option>";
            
            <?php } ?>
            
             operator_cell += '</select>';

            $('#add_distributor').html(operator_cell);
            $('div#adddistributor').modal('show');
         
         
        $("#distributor").select2({
                placeholder: "Distributors",
                dropdownAutoWidth: 'true',
                width:'auto',
                allowClear: true
            });
 }
 
 $("form#frmaddscheme #service,form#frmupdatescheme #service").select2({
                placeholder: "Services",
                dropdownAutoWidth: 'true',
                width:'auto',
                allowClear: true
    });
            
 function deleteDist(id,schemeId){
     
     if(confirm("Are you sure, you want to delete this Distributor")){
            $.ajax({
                url: './deleteDistributorScheme',
                type: 'get',
                dataType: 'json',
                success: function (data) {
                   alert(data.msg);
                   window.location.href = "";
                },
                data: {'distId':id,'schemeId':schemeId}
        });
     }
     
    }
 
 function getscheme(schemeId){
     $.ajax({
            url: './getscheme',
            type: 'get',
            dataType: 'json',
            success: function (data) {
               
                    $('form#frmupdatescheme input[name=scheme]').val(data.name);
                    var operator_cell = '<select  id="service" name="service[]" style="width: 150px;" required multiple="multiple">';
                     var selected = '';
                     var servicelist = new Array();
                     servicelist = data.service.split(',');
                    $.each(JSON.parse('<?php echo json_encode($services); ?>'),function(id,name){
                            selected = '';
                            
                           if($.inArray(id,servicelist)!=-1){
                                selected = 'selected="selected"';
                                operator_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
                            }else{
                                operator_cell += '<option value="'+id+'" >'+name+'</option>';
                            }
                     });
             operator_cell += '</select>';
             $('form#frmupdatescheme .services-list').html(operator_cell)
                  //  $('form#frmupdatescheme select[name=service] option[value='+data.service+']').attr('selected','selected');
                    
                    $('form#frmupdatescheme select[name=modal_priority] option[value='+data.type+']').attr('selected','selected');
                    $('form#frmupdatescheme select[name=frequency] option[value='+data.settlement+']').attr('selected','selected');
                    $("input[name=schemeactivation][value='"+data.isactive+"']").attr('checked','checked');
                    $("input[name=schemeactivation][value='"+data.isactive+"']").click();
                    $('form#frmupdatescheme input[name=scheme]').before('<input type="hidden" name="schemeId" value="'+data.id+'">');
                    var j=0;
                    $("form#frmupdatescheme #service").select2({
                            placeholder: "Services",
                            dropdownAutoWidth: 'true',
                            width:'auto',
                            allowClear: true
                        });
                        var targetList = '';
                        if(typeof data.scheme !=='undefined' && data.scheme!='')    
                    $.each($.parseJSON(data.scheme),function(index,value){
                        
                        if(j==0){
                            $('form#frmupdatescheme input[name="sellrange[]"]').first().val(index);
                            var k=0;
                            $.each(value.target,function(index2,value2){
                                if(k==0){
                                    $('form#frmupdatescheme input[name="target[]"]').first().val(value.target[index2]);
                                    $('form#frmupdatescheme input[name="incentive[]"]').first().val(value.incentive[index2]);
                                }else{
                                    var m='';
                                    if(index2-1>0)
                                        m=index2-1;
                                    $('div .sellrange .targetrange'+m).after('<div class="row col-md-12 targetrange'+index2+'"><div class="col-md-3"> </div><div class="col-md-3"><input type="text" required class="form-control" name="target[]" placeholder="target" value="'+value.target[index2]+'"></div><div class="col-md-3"><input type="text" required class="form-control" name="incentive[]" placeholder="incentive"  value="'+value.incentive[index2]+'"></div><div class="col-md-3"><button class="btn btn-primary removetarget">Remove Target</button></div></div>');
                                }
                                k++;
                            })
                            
                        }else{
                            var k=0;
                                $.each(value.target,function(index2,value2){
                                        if(k==0){
                                            $('form#frmupdatescheme div .sellrange').after('<div id="'+j+'" class="col-md-12 sellrange'+j+'"><div class="row col-md-12 targetrange">\n\
                                                        <div class="col-md-3">\n\
                                                        <input type="text" class="form-control" name="sellrange[]" placeholder="sell range " value="'+index+'" required>\n\
                                                        </div>\n\
                                                        <div class="col-md-3">\n\
                                                        <input type="text" required class="form-control" name="target'+j+'[]" placeholder="target" value="'+value.target[index2]+'"></div>\n\
                                                       <div class="col-md-3">\n\
                                                        <input type="text" required class="form-control" name="incentive'+j+'[]" placeholder="incentive" value="'+value.incentive[index2]+'"></div>\n\
                                                       <div class="col-md-3"><button type="button" class="btn btn-primary addtarget">Add Target</button><button type="button" class="btn btn-primary" id="remove'+j+'" onclick="removeTaret('+j+')">Remove Sell Range</button></div></div></div>')
         
                                            }
                                            else{
                                                var m='';
                                                if(index2-1>0)
                                                    m=index2-1;
                                                $('div .sellrange'+j+' .targetrange'+m).after('<div class="row col-md-12 targetrange'+index2+'"><div class="col-md-3"> </div><div class="col-md-3"><input type="text" required class="form-control" name="target[]" placeholder="target" value="'+value.target[index2]+'"></div><div class="col-md-3"><input type="text" required class="form-control" name="incentive[]" placeholder="incentive"  value="'+value.incentive[index2]+'"></div><div class="col-md-3"><button class="btn btn-primary removetarget">Remove Target</button></div></div>');
                                            }
                                                k++;       
                                  })                        
                       }
                        j++;
                    })
                    $('div#updatescheme').modal('show');
                
                
            },
            data: {'schemeId':schemeId}
        });
       
 }
 
        
        $('#select_all_dist').on('click',function(){
            if($('#select_all_dist').is(':checked')){
                $('#add_distributor').hide();
            }
            else{
                $('#add_distributor').show();
            }
        })
    </script>
    
    
