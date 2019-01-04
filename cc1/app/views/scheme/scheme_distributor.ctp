<link type='text/css' rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel='stylesheet' type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<link rel="stylesheet" media="screen" href="/boot/css/select2.css">
<div class="loader loader-default" data-text=""></div>
<div>
    <span><h3> Scheme : <?php echo $schemeDist[0]['schemes']['name']; ?></h3></span>
    <span><h4>Services : <?php echo $services; ?></h4></span>
    <br/>
    <div class="row">
        <div class="col-md-12 error-msg"><?php if(!empty($message)){ if($message['error']==0) { ?> <div class="alert alert-success"><?php echo $message['msg']; ?></div> <?php }else{ ?><div class="alert alert-danger"><?php echo $message['msg']; ?></div> <?php } }?></div>
    </div>
    <?php if(empty($schemeDist)){ echo "No Distributor registered under this scheme"; }else{ ?>
    <form method="post" action="">
        <div class="row">
            <input type="hidden" name="scheme_id" value="<?php echo $row['distributor_schemes']['scheme_id']; ?>">
            <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary selectall-dist" >Select All</button>
            </div>
            <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary reset" >Reset</button>
            </div>
            <div class="form-group col-md-3">
                <button type="submit" class="btn btn-danger pull-left" >Delete</button>
            </div>
        </div>
        <div class="row">
            <?php foreach($schemeDist as $row){  ?> 
            <div class="form-group col-md-6">
                <input type="checkbox"  class="check"  name="distributor_schemes[]" value="<?php echo $row['distributor_schemes']['id']; ?>">
                <label><?php echo $distributors[$row['distributor_schemes']['distributor_id']]['company']." ( ".$row['distributor_schemes']['validfrom']." -  ".$row['distributor_schemes']['validto']." ) "; ?></label>
                <button type="button" class="btn btn-info btn-sm" onclick="edit_dates(<?php echo $row['distributor_schemes']['id']; ?>,'<?php echo $row['distributor_schemes']['validfrom']; ?>','<?php echo $row['distributor_schemes']['validto']; ?>')" >Edit DateRange</button>
            </div>
            <?php } ?>
       </div>
        
    </form>
    
    <!--Edit DateRange SCheme  Modal -->
                <div id="editdates" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <form method="post" id="edit-dates">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Edit DateRange</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 error-msg">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                        <input id='from_date' type="text" class="form-control " name="from" value="" placeholder="From date"required>
                                                  </div>
                                                  <div class="col-md-4">
                                                        <input id='to_date' type="text" class="form-control " name="to" value="" placeholder="To date" required>
                                                  </div>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                        <input id='dist_scheme_id' type="hidden" name="dist_scheme_id" value="">
                                            <button type="submit" class="btn btn-primary">Edit</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                            </form>
                          </div>

                        </div>
                      </div>
                <!--END Add Distributor  Modal -->
    
    <?php } ?>
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
        startDate: "1d",
        orientation: 'bottom'
    });
     $('input[name="to"]').datepicker({
         minViewMode:3,
         format: 'yyyy-mm-dd',
         startDate: "1d",
     });
     
     });

    $(function() {
         //select / unselect checkbox value
         $('.selectall-dist').on('click',function(){ 
             
                $('input:checkbox[name="distributor_schemes[]"]').prop('checked',true);
            
         })
         $('.reset').on('click',function(){
             
                $('input:checkbox[name="distributor_schemes[]"]').prop('checked',false);
            
         })
    });
    
    function edit_dates(dist_scheme_id,from,to){
    	$('#from_date').val(from);
    	$('#to_date').val(to);
    	$('#dist_scheme_id').val(dist_scheme_id);
    	$('div#editdates').modal('show');
    }
    
    $('form#edit-dates').on('submit',function(event){
          event.preventDefault();
           var formData = $('form#edit-dates').serializeArray()
           $.ajax({
                url: window.location.protocol+'//'+window.location.hostname+'/scheme/editSchemeDistributor',
                type: 'post',
                dataType: 'json',
                success: function (data) {
                   $('form#edit-dates div.error-msg').html('');
                    if(data.error==1){
                        $('form#edit-dates div.error-msg').append('<div class="alert alert-danger">'+data.msg+'</div>')
                    }else{
                        $('form#edit-dates div.error-msg').append('<div class="alert alert-success">'+data.msg+'</div>')
                    }
                },
                data: formData
        });
     })
     
    
</script>