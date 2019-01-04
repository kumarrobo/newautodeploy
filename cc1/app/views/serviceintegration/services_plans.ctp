<!DOCTYPE html>
<html>
<head>
  <title>Services</title> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" media="screen" href="/boot/css/select2.css">
  <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
  <link rel="stylesheet" href="/boot/css/serviceintegration.css">
  <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="/boot/js/bootstrap-3.3.7.min.js"></script>  
  <script type="text/javascript" src="/boot/js/select2.js"></script>
  <script type="text/javascript" src="/boot/js/serviceintegration.js"></script>
</head>
<body>
    <div class="row">
        <div>
          <a type="button"  href="/serviceintegration/servicesForm" id='serviceIntegration' name="serviceIntegration" class="btn btn-primary" >Home</a>        </div>       
        <nav class="navbar navbar-default">
        <div>
            <h3 style="float:right;">Service : <?php echo $servicename[$service_id]; ?></h3> <br>
            <h2>Plans</h2> 
        </nav>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="<?php
                    if ($prodtype == "add") {
                        echo "active";
                    }
                    ?>"><a  href="/serviceintegration/servicesPlans/add/<?php echo $service_id; ?>">Add Plan</a></li>
                    <li class="<?php
                    if ($prodtype == "list") {
                        echo "active";
                    }
                    ?>"><a  href="/serviceintegration/servicesPlans/list/<?php echo $service_id; ?>" >List Plan</a></li>
                </ul>
            </div>
        </nav>
    </div>
<?php if($prodtype == 'add'){ ?>
                
            <form id="planform" name="planform" method="POST">
            <div class="row">
                <!--<button type="submit" style="float:right;" class="btn btn-primary addplan" id="addplan" name="addplan">Add Plan</button>-->
                               
                <div class="form-group col-md-12 planrange" >
                  <div class="col-md-12 plantarget">                
                 <div class="col-lg-3" style="display:none">
                <label for="servc_name">Service Names</label>
                <input type="text" id="servc_name" name="plans[0][servc_name]" hidden="true" class="form-control" value="<?php echo $service_id; ?>" >
                </div>
                <div class="col-lg-3">                    
                    <label for="plan_key">Plan Key<font color="red">*</font></label>
                    <input type="text" id="plan_key" name="plans[0][plan_key]" class="form-control">
                </div>                
                <div class="col-lg-3">
                    <label for="plan_name">Plan name<font color="red">*</font></label>
                    <input type="text" id="plan_name" name="plans[0][plan_name]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="settlement_amt">Setup Amount<font color="red">*</font></label>
                        <input type="text" id="settlement_amt" name="plans[0][settlement_amt]" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" class="form-control">
                    </div>
                 <div class="col-md-3">
                    <label for="rental_amt">Rental Amount<font color="red">*</font></label>
                    <input type="text" id="rental_amt" name="plans[0][rental_amt]" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" class="form-control">
                 </div>
                 <div class="col-md-3">
                    <label for="dist_comm">Dist Commission<font color="red">*</font></label>
                    <input type="text" id="dist_comm" name="plans[0][dist_comm]" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" class="form-control">
                 </div>
                  <div class="col-md-3">
                          <label for="rent_comm">Rental Commission<font color="red">*</font></label>
                          <input type="text" id="rent_comm" name="plans[0][rent_comm]" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" class="form-control">
                 </div>
                  </div>
                  </div>
                    </div>
                    <div class="row">
                 <div class="product">
                 <button type="button" style="float: right;" id="addProduct" class="btn btn-primary addproductslab">Add Product Slab</button>         
                 <div class="product_0">
                     <h3>Product Mapping</h3>
                 <div class="col-md-3">
                     <label for="prod_id">Product</label>                                        
                    <select  id="prod_id" name="prod[0][prod_id]" class="form-control">
                        <option value="">Select</option>
                        <?php foreach($prodDet as $prod){?>
                        <option value="<?php echo $prod['products']['id']; ?>"><?php echo $prod['products']['name']; ?></option>
                        <?php } ?>
                    </select>
                 </div>
                     
                                      
                          <div class="form-group col-md-12 retrange_0" >
                                <h3>Retailer Margin</h3>
                                <?php $i = 0;  ?>
                              <div class="row col-md-12 rettarget" data-index="<?php echo $i; ?>">
                                  <div class="col-md-2">
                                      <label>Slab</label><br>
                                      <input type="text" class="form-control" id="slabs" name="prod[0][slabs][<?php echo $i; ?>][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  >
                                  </div>
                                  <div class="col-md-2">
                                      <label>Margin</label><br>
                                      <input type="text" class="form-control" id="margin" name="prod[0][slabs][<?php echo $i; ?>][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>Min</label><br>
                                      <input type="text" class="form-control" id="min" name="prod[0][slabs][<?php echo $i; ?>][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10.00" >
                                  </div>                                  
                                  <div class="col-md-2">
                                      <label>Max</label><br>
                                      <input type="text" class="form-control" id="max" name="prod[0][slabs][<?php echo $i; ?>][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >
                                  </div>
                                  <div class="col-md-3">
                                      <button type="button" data-product-id="0" class="btn btn-primary addretslab"style="margin-top:25px;">Add Retailer Slab</button>
                                  </div>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12 distrange_0" >
                                <h3>Distributor Margin</h3>
                                <?php $j = 0;?>
                              <div class="row col-md-12 disttarget" data-index="<?php echo $j; ?>">
                                  <div class="col-md-2">
                                      <label>Slab</label><br>
                                      <input type="text" class="form-control" id="slabs" name="prod[0][dslabs][<?php echo $j; ?>][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  >
                                  </div>
                                  <div class="col-md-2">
                                          <label>Margin</label><br>
                                          <input type="text" class="form-control" id="margin" name="prod[0][dslabs][<?php echo $j; ?>][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>Min</label><br>
                                      <input type="text" class="form-control" id="dmin" name="prod[0][dslabs][<?php echo $j; ?>][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>Max</label><br>
                                      <input type="text" class="form-control" id="dmax" name="prod[0][dslabs][<?php echo $j; ?>][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >
                                  </div>
                                  <div class="col-md-3">
                                      <button type="button" data-product-id="0"  class="btn btn-primary adddistslab"style="margin-top:25px;">Add Distributor Slab</button>
                                  </div>
                              </div>
                          </div>
                        </div>
                        </div>
                  <!--</div>-->
                      
                <div class="col-md-3 fixed">
                    <button type="submit" class="btn btn-primary" style="margin-top:20px">Submit</button>
                </div>                    
            <br><br>                                
                
         <!--</div>-->
            </div>
                </form>
            
<?php } else {?>               
        <form id="service_PlanForm" method="POST">
            <div class="table-responsive">
            <table id="servc_plantb" class="table">
                <thead>
                <th> Index </th>
                <th>  </th>
                <th> Plan Key </th>
                <th> Name </th>
                <th> Status </th>
                <th> Settlement Amount </th>
                <th> Rental Amount </th>
                <th> Dist Commission </th>
                <th> Action </th>
                </thead>
                <?php $i = 1; ?>
                <?php foreach ($servicePlans as $servcPlans) { ?>
                <tr>
                    <td> <?php echo $servcPlans['service_plans']['id']; ?></td>                   
                    <td><input type="text" class="form-control" id="servc_plan_sname_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_sname_<?php echo $servcPlans['service_plans']['id'];?>" value = "<?php echo $servcPlans['service_plans']['service_id']; ?>"disabled="true" style = "display:none"></td>
                    <td><input type="text" class="form-control" id="servc_plan_key_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_key_<?php echo $servcPlans['service_plans']['id'];?>" value = "<?php echo $servcPlans['service_plans']['plan_key']; ?>"disabled="true"> </td>
                    <td><input type="text" class="form-control" id="servc_plan_name_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_name_<?php echo $servcPlans['service_plans']['id'];?>" value = "<?php echo $servcPlans['service_plans']['plan_name']; ?>" disabled="true"> </td>                    
                    <td> <label class="switch">
                          <input type="checkbox" id="servc_plan_status_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_status_<?php echo $servcPlans['service_plans']['id'];?>" <?php echo ($servcPlans['service_plans']['is_visible'] == '1')?"checked":""; ?> disabled="true"> <span class="slider round"></span>
                        </label>
                    </td>
                    <td><input type="text" class="form-control" id="servc_plan_settamt_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_settamt_<?php echo $servcPlans['service_plans']['id'];?>" value = "<?php echo $servcPlans['service_plans']['setup_amt']; ?>" disabled="true"> </td>
                    <td><input type="text" class="form-control" id="servc_plan_rentamt_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_rentamt_<?php echo $servcPlans['service_plans']['id'];?>" value = "<?php echo $servcPlans['service_plans']['rental_amt']; ?>" disabled="true"> </td>
                    <td><input type="text" class="form-control" id="servc_plan_distcomm_<?php echo $servcPlans['service_plans']['id'];?>" name="servc_plan_distcomm_<?php echo $servcPlans['service_plans']['id'];?>" value = "<?php echo $servcPlans['service_plans']['dist_commission']; ?>" disabled="true"> </td>
                    <td><a type="button" href="/serviceintegration/setPlansDetails/<?php echo $servcPlans['service_plans']['id']; ?>    " class="btn btn-primary" id="upservc_plan_enb<?php echo $servcPlans['service_plans']['id'];?>" name="upservc_plan_enb<?php echo $servcPlans['service_plans']['id'];?>">Update</a>                          
                    </td>
                    <t
                </tr>
                    <?php $i++; } ?>
            </table>
            </div>
        </form>
        
    
</body>
<?php } ?>
  
</html> 

<script>     
    var m=1; 
    $(document).on('click','.removeretslab',function(event){
        $(this).parent().parent().remove(".row")
     });

         $(document).on('click','.addretslab',function(event){
            var product_id = $(this).attr('data-product-id');
            var i= parseInt($('.retrange_'+product_id).children('.rettarget').last().attr('data-index'))+1;            
            var new_retslab_html = '<div class="row col-md-12 rettarget" data-index="'+i+'" >\n\
                                    <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="prod['+product_id+'][slabs]['+i+'][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  ></div>\n\
                                    <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="prod['+product_id+'][slabs]['+i+'][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" ></div> \n\
                                    <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="prod['+product_id+'][slabs]['+i+'][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" ></div>\n\
                                    <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="prod['+product_id+'][slabs]['+i+'][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;"class="btn btn-primary removeretslab" type="button">Remove Ret slab </button></div></div>';
         $('div .retrange_'+product_id).append(new_retslab_html);


     });

    $(document).on('click','.removedistslab',function(event){
         $(this).parent().parent().remove(".row");
     });

         $(document).on('click','.adddistslab',function(event){
             var product_id = $(this).attr('data-product-id');
             var j= parseInt($('.distrange_'+product_id).children('.disttarget').last().attr('data-index'))+1;
             
             var new_distslab_html = '<div class="row col-md-12 disttarget" data-index="'+j+'" >\n\
                                      <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="prod['+product_id+'][dslabs]['+j+'][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  ></div>\n\
                                      <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="prod['+product_id+'][dslabs]['+j+'][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" ></div> \n\
                                      <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="dmin" name="prod['+product_id+'][dslabs]['+j+'][min]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" ></div>\n\
                                     <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="dmax" name="prod['+product_id+'][dslabs]['+j+'][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;"class="btn btn-primary removedistslab" type="button">Remove Dist slab </button></div></div>';

         $('div .distrange_'+product_id).append(new_distslab_html);


     });

    $(document).on('click','.remproductslab',function(event){
        
        // $(this).parent().parent().remove(".product")
        $(this).parent().remove();
        $(this).remove();
     });



         $(document).on('click','.addproductslab',function(event){
             var new_prodslab_html = ' <div class="product_'+m+'"><button type="button" style="float: right;" id="remProduct" class="btn btn-primary remproductslab">Remove Product Slab</button>\n\
                                    <div class="col-md-3"><label for="prod_id">Product</label><select  id="prod_id" name="prod['+m+'][prod_id]" class="form-control"><option value="">Select</option><?php foreach($prodDet as $prod){?><option value="<?php echo $prod['products']['id']; ?>"><?php echo $prod['products']['name']; ?></option><?php } ?></select></div>\n\
                                    <div class="form-group col-md-12 retrange_'+m+'">\n\
                                    <h3>Retailer Margin</h3><div class="row col-md-12 rettarget" data-index="0">\n\
                                    <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="prod['+m+'][slabs][0][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  ></div>\n\
                                    <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="prod['+m+'][slabs][0][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" ></div> \n\
                                    <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="prod['+m+'][slabs][0][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" ></div>\n\
                                    <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="prod['+m+'][slabs][0][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;" data-product-id="'+m+'" class="btn btn-primary addretslab" type="button">Add Ret slab </button></div></div></div>\n\
                                    <div class="form-group col-md-12 distrange_'+m+'"><h3>Distributor Margin</h3>\n\
                                        <div class="row col-md-12 disttarget" data-index="0">\n\
                                        <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="prod['+m+'][dslabs][0][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  ></div>\n\
          \n\                          <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="prod['+m+'][dslabs][0][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" ></div> \n\
                                     <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="dmin" name="prod['+m+'][dslabs][0][min]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" ></div>\n\
                                    <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="dmax" name="prod['+m+'][dslabs][0][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;" data-product-id="'+m+'" class="btn btn-primary adddistslab" type="button">Add Dist slab </button></div></div>';
             m++;

         $('div .product').append(new_prodslab_html);


     });

       $('#planform').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#planform').serializeArray();
        if($('#plan_key').val() == "" || $('#plan_name').val() == "" || $('#dist_comm').val() == "" || $('#settlement_amt').val() == "" || $('#rental_amt').val() == "" || $('#rent_comm').val() == ""){
            
            alert("Please Enter Mandatory  Field !!!");
        }else {
        $.ajax({
            url: '/serviceintegration/InsPlanDetails',
            type: "POST",
            dataType: "json",
            data: formData,
            
            success: function (data) {              
               if(data.status == 'success'){
                    alert(data.msg);                         
                    	window.location = "/serviceintegration/servicesPlans/list/<?php echo $service_id ?>"
                }else{
                    alert(data.description);
                }    
            },
        });
       }
     });
       
</script>