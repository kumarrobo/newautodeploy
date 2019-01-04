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
            <h2>Plans Update Form</h2> 
        </nav>
    </div>
              <form id="upplanform" name="upplanform" method="POST">
               <div class="row">                               
                <div class="form-group col-md-12 planrange" >
                  <div class="row col-md-12 plantarget">                
                  <input  type="hidden" name="up[plan_id]" value="<?php echo $planDetails[0]['sp']['id'];?>">
                <div class="col-lg-3">                    
                    <label for="plan_key">Plan Key<font color="red">*</font></label>
                    <input type="text" id="plan_key" name="up[plan_key]" class="form-control" value = "<?php echo $planDetails[0]['sp']['plan_key']; ?>">
                </div>                
                <div class="col-lg-3">
                    <label for="plan_name">Plan name<font color="red">*</font></label>
                    <input type="text" id="plan_name" name="up[plan_name]" class="form-control" value = "<?php echo $planDetails[0]['sp']['plan_name']; ?>">
                </div>
                <div class="col-md-3">
                    <label for="settlement_amt">Setup Amount<font color="red">*</font></label>
                        <input type="text" id="settlement_amt" name="up[settlement_amt]" class="form-control" value = "<?php echo $planDetails[0]['sp']['setup_amt']; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                    </div>
                 <div class="col-md-3">
                     <label for="rental_amt">Rental Amount<font color="red">*</font></label>
                    <input type="text" id="rental_amt" name="up[rental_amt]" class="form-control" value = "<?php echo $planDetails[0]['sp']['rental_amt']; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                 </div>
                 <div class="col-md-3">
                     <label for="dist_comm">Dist Commission<font color="red">*</font></label>
                    <input type="text" id="dist_comm" name="up[dist_comm]" class="form-control" value = "<?php echo $planDetails[0]['sp']['dist_commission']; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                 </div>
                  <div class="col-md-3">
                      <label for="rent_comm">Rental Commission<font color="red">*</font></label>
                      <input type="text" id="rent_comm" name="up[rent_comm]" class="form-control" value = "<?php echo $planDetails[0]['sp']['dist_rental_commission']; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                  </div>
                  </div>
                </div>
               </div>
             <div class="row">  
                 <h3>Product Mapping</h3> 
                 <div class="product">
                     <button type="button" style="float: right;" id="addProduct" class="btn btn-primary addproductslab">Add Product Slab</button>
            <?php 
            foreach($planDetails  as  $product_index => $plan) {
                ?> 
                 <?php if($product_index != 0)  { ?>
                <button type="button" style="float: right;" id="remProduct" class="btn btn-primary remproductslab">Remove Product Slab</button>                     
                 <?php } ?>
                 <div class="products-row product_<?php echo $product_index; ?>" data-index="<?php echo $product_index; ?>">
                    <div class="col-md-3">
                        <label for="prod_id">Product</label>                                        
                       <select  id="prod_id" name="uprod[<?php echo $product_index; ?>][prod_id]" class="form-control">                        
                           <option value="">Select</option>
                           <?php                        
                           foreach($prodDet as $prod){ ?>                       
                               <option value="<?php echo $prod['products']['id']; ?>" <?php echo ( $prod['products']['id'] == $plan['sm']['product_id']) ? "selected": ""; ?>><?php echo $prod['products']['name']; ?></option>
                           <?php } ?>
                       </select>
                    </div>                     
                                <?php if($plan['sm']['ret_params'] == ''){
                                    $i=0;
                                 ?>                                                                                                  
                              <div class="form-group col-md-12 retrange_<?php echo $product_index; ?>" >
                                <h3>Retailer Margin</h3>
                              <div class="extra-row col-md-12 rettarget" data-index="<?php echo $i; ?>">
                                  <div class="col-md-2">
                                      <label>Slab</label><br>
                                      <input type="text" class="form-control" id="slabs" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][slabs]" width="30px" value="<?php echo ''; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Margin</label><br>
                                      <input type="text" class="form-control" id="margin" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][margin]" width="30px"value="<?php echo ''; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Min</label><br>
                                      <input type="text" class="form-control" id="min" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][min]" width="30px" value="<?php echo ''; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                  </div>                                  
                                  <div class="col-md-2">
                                      <label>Max</label><br>
                                      <input type="text" class="form-control" id="max" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][max]" width="30px" value="<?php echo ''; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                  </div>
                                  <?php  if($i == 0) {?>
                                  <div class="col-md-3">
                                      <button type="button" data-product-id=<?php echo $product_index; ?>  class="btn btn-primary addretslab"style="margin-top:25px;">Add Retailer Slab</button>
                                  </div>
                                  <?php }else{ ?>
                                  <div class="col-md-3">
                                      <button style="margin:22px 0px 0px 0px;"class="btn btn-primary removeretslab" type="button">Remove Ret slab </button>
                                  </div>                                  
                                  <?php } ?>
                                  
                              </div>
                          </div>
                                <?php } else { ?>
                                                   <div class="form-group col-md-12 retrange_<?php echo $product_index; ?>" >
                                <h3>Retailer Margin</h3>                            
                                <?php  
                                $i=0;                                
                                foreach( json_decode($plan['sm']['ret_params'],true) as $slab_key => $slab ){ ?>                           
                                <div class="extra-row col-md-12 rettarget" data-index="<?php echo $i; ?>">
                                  <div class="col-md-2">
                                      <label>Slab</label><br>
                                      <input type="text" class="form-control" id="slabs" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][slabs]" width="30px" value="<?php echo ( isset($slab_key) && !empty($slab_key) ) ? $slab_key:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Margin</label><br>
                                      <input type="text" class="form-control" id="margin" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][margin]" width="30px"value="<?php echo ( isset($slab['margin'])) ? $slab['margin']:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Min</label><br>
                                      <input type="text" class="form-control" id="min" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][min]" width="30px" value="<?php echo ( isset($slab['min'])) ? $slab['min']:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                  </div>                                  
                                  <div class="col-md-2">
                                      <label>Max</label><br>
                                      <input type="text" class="form-control" id="max" name="uprod[<?php echo $product_index; ?>][slabs][<?php echo $i; ?>][max]" width="30px" value="<?php echo ( isset($slab['max'])) ? $slab['max']:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                  </div>
                                  <?php  if($i == 0) {?>
                                  <div class="col-md-3">
                                      <button type="button" data-product-id="<?php echo $product_index; ?>" class="btn btn-primary addretslab"style="margin-top:25px;">Add Retailer Slab</button>
                                  </div>
                                  <?php }else{ ?>
                                  <div class="col-md-3">
                                      <button style="margin:22px 0px 0px 0px;"class="btn btn-primary removeretslab" type="button">Remove Ret slab </button>
                                  </div>                                  
                                  <?php } ?>                                  
                                  </div>
                                <?php $i++; 
                                 }?>  
                              </div> 
                                <?php } ?>
                           <?php if($plan['sm']['dist_params'] == '' ) { ?>
                          <div class="form-group col-md-12 distrange_<?php echo $product_index; ?>" >
                                <h3>Distributor Margin</h3>
                                  <?php 
                                  $l=0;?>                                                                  
                                <div class="extra-row col-md-12 disttarget" data-index="<?php echo $l; ?>">
                                  <div class="col-md-2">
                                      <label>Slab</label><br>
                                      <input type="text" class="form-control" id="slabs" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $l; ?>][slabs]" width="30px" value="<?php echo ''; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100">
                                  </div>
                                  <div class="col-md-2">
                                          <label>Margin</label><br>
                                          <input type="text" class="form-control" id="margin" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $l; ?>][margin]" width="30px" value="<?php echo ''; ; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Min</label><br>
                                      <input type="text" class="form-control" id="dmin" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $l; ?>][min]" width="30px" value="<?php echo '';  ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                    </div>
                                  <div class="col-md-2">
                                      <label>Max</label><br>
                                      <input type="text" class="form-control" id="dmax" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $l; ?>][max]" width="30px" value="<?php echo ''; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                  </div>
                                  
                                  <?php if($l == 0) {?>
                                  <div class="col-md-3">
                                      <button type="button" data-product-id =<?php echo $product_index; ?> class="btn btn-primary adddistslab"style="margin-top:25px;">Add Distributor Slab</button>
                                  </div>
                                  <?php }else{ ?>
                                  <div class="col-md-3">
                                      <button style="margin:22px 0px 0px 0px;"class="btn btn-primary removedistslab" type="button">Remove Dist slab </button>
                                  </div>                                  
                                  <?php } ?>
                                </div>                                  
                                
                              </div>

                                <?php } else { ?>

                          <div class="form-group col-md-12 distrange_<?php echo $product_index; ?>" >
                                <h3>Distributor Margin</h3>
                                  <?php                                                                    
                                   $k = 0;
                                  foreach( json_decode($plan['sm']['dist_params'],true) as $slab_key => $slab ){ ?>                                 
                                <div class="extra-row col-md-12 disttarget" data-index="<?php echo $k; ?>">
                                  <div class="col-md-2">
                                      <label>Slab</label><br>
                                      <input type="text" class="form-control" id="slabs" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $k; ?>][slabs]" width="30px" value="<?php echo ( isset($slab_key) && !empty($slab_key) ) ? $slab_key:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100">
                                  </div>
                                  <div class="col-md-2">
                                          <label>Margin</label><br>
                                          <input type="text" class="form-control" id="margin" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $k; ?>][margin]" width="30px" value="<?php echo ( isset($slab['margin'])) ? $slab['margin']:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%">
                                  </div>
                                  <div class="col-md-2">
                                      <label>Min</label><br>
                                      <input type="text" class="form-control" id="dmin" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $k; ?>][min]" width="30px" value="<?php echo ( isset($slab['min'])) ? $slab['min']:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                    </div>
                                  <div class="col-md-2">
                                      <label>Max</label><br>
                                      <input type="text" class="form-control" id="dmax" name="uprod[<?php echo $product_index; ?>][dslabs][<?php echo $k; ?>][max]" width="30px" value="<?php echo ( isset($slab['max'])) ? $slab['max']:null; ?>" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                                  </div>
                                  
                                  <?php if($k == 0) {?>
                                  <div class="col-md-3">
                                      <button type="button" data-product-id ="<?php echo $product_index; ?>" class="btn btn-primary adddistslab"style="margin-top:25px;">Add Distributor Slab</button>
                                  </div>
                                  <?php }else{ ?>
                                  <div class="col-md-3">
                                      <button style="margin:22px 0px 0px 0px;"class="btn btn-primary removedistslab" type="button">Remove Dist slab </button>
                                  </div>                                  
                                  <?php } ?>
                                </div>
                                  <?php $k++; } ?>
                                
                              </div>
                          <!--</div>-->
                        <!--</div>-->
                                <?php }?>
             </div>
<?php  } ?></div>
                 
                <div class="col-md-3 fixed">
                    <button type="submit" class="btn btn-primary" style="margin-top:20px" on>Submit</button>
                </div>                    
             </div>
        </form>
    
</html>


<script>
       $('#upplanform').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#upplanform').serializeArray();
         if($('#plan_key').val() == ""){
             alert("Plan key should not be empty");
         }
         else if($('#plan_name').val() == ""){
             alert("Plan name should not be empty");
         }
         else if($('#settlement_amt').val() == ""){
             alert("Setup Amount should not be empty");
         }
         else if($('#rental_amt').val() == ""){
             alert("Rental Amount should not be empty");
         }
         else if($('#dist_comm').val() == ""){
             alert("Distibutor Commision should not be empty");
         }
         else if($('#rent_comm').val() == ""){
             alert("Rental Commision should not be empty");
         }         
         else {
        $.ajax({
            url: '/serviceintegration/updPlansDetails',
            type: "POST",
            dataType: "json",
            data: formData,
            
            success: function (data) {              
               if(data.status == 'success'){
                    alert(data.msg);                                             
                }else{
                    alert(data.description);
                }    
            },
        });
       }
     });
    
    
    
    $(document).on('click','.removeretslab',function(event){
        $(this).parent().parent().remove(".extra-row")
     });

         $(document).on('click','.addretslab',function(event){             
            var product_id = $(this).attr('data-product-id');                 
             var i= parseInt($('.retrange_'+product_id).children('.rettarget').last().attr('data-index'))+1;             
             
             var new_retslab_html = '<div class="extra-row col-md-12 rettarget" data-index="'+i+'" >\n\
                                    <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="uprod['+ product_id +'][slabs]['+i+'][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"></div>\n\
                                    <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="uprod['+ product_id +'][slabs]['+i+'][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%"></div> \n\
                                    <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="uprod['+ product_id +'][slabs]['+i+'][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00"></div>\n\
                                    <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="uprod['+ product_id +'][slabs]['+i+'][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;"class="btn btn-primary removeretslab" type="button">Remove Ret slab </button></div></div>';
         $('div .retrange_'+product_id).append(new_retslab_html);


     });
                          
    $(document).on('click','.removedistslab',function(event){    
         $(this).parent().parent().remove(".extra-row")
     });

         $(document).on('click','.adddistslab',function(event){
             var product_id = $(this).attr('data-product-id');             
             var j= parseInt($('.distrange_'+product_id).children('.disttarget').last().attr('data-index'))+1;             
             var new_distslab_html = '<div class="extra-row col-md-12 disttarget" data-index="'+j+'">\n\
                                      <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="uprod['+product_id+'][dslabs]['+j+'][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"></div>\n\
                                      <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="uprod['+product_id+'][dslabs]['+j+'][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%"></div> \n\
                                      <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="dmin" name="uprod['+product_id+'][dslabs]['+j+'][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00"></div>\n\
                                      <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="dmax" name="uprod['+product_id+'][dslabs]['+j+'][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;"class="btn btn-primary removedistslab" type="button">Remove Dist slab </button></div></div>';             

         $('div .distrange_'+product_id).append(new_distslab_html);


     });

    $(document).on('click','.remproductslab',function(event){        
           $(this).next().remove();
           $(this).remove();
     
    });


         $(document).on('click','.addproductslab',function(event){
             var m = parseInt($('.product').children('.products-row').last().attr('data-index'))+1; 
             var new_prodslab_html = '<div class=" products-row product_'+m+'" data-index="'+m+'"><button type="button" style="float: right;" id="remProduct" class="btn btn-primary remproductslab">Remo Product Slab</button><div class="col-md-3"><label for="prod_id">Product</label><select  id="prod_id" name="uprod['+m+'][prod_id]" class="form-control"><option value="">Select</option><?php foreach($prodDet as $prod){?><option value="<?php echo $prod['products']['id']; ?>"><?php echo $prod['products']['name']; ?></option><?php } ?></select></div>\n\
                                    <div class="form-group col-md-12 retrange_'+m+'">\n\
                                    <h3>Retailer Margin</h3><div class="extra-row col-md-12 rettarget" data-index="0">\n\
                                    <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="uprod['+m+'][slabs][0][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  ></div>\n\
                                    <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="uprod['+m+'][slabs][0][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" ></div> \n\
                                    <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="uprod['+m+'][slabs][0][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" ></div>\n\
                                    <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="uprod['+m+'][slabs][0][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;" data-product-id="'+m+'" class="btn btn-primary addretslab" type="button">Add Ret slab </button></div></div></div>\n\
                                    <div class="form-group col-md-12 distrange_'+m+'"><h3>Distributor Margin</h3>\n\
                                        <div class="extra-row col-md-12 disttarget" data-index="0">\n\
                                        <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="uprod['+m+'][dslabs][0][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100"  ></div>\n\
          \n\                          <div class="col-md-2"> <label>Margin</label><br><input type="text" class="form-control" id="margin" name="uprod['+m+'][dslabs][0][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%" ></div> \n\
                                     <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="dmin" name="uprod['+m+'][dslabs][0][min]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" ></div>\n\
                                    <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="dmax" name="uprod['+m+'][dslabs][0][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00" >\n\
                                    </div><div class="col-md-2"><button style="margin:22px 0px 0px 0px;" data-product-id="'+m+'" class="btn btn-primary adddistslab" type="button">Add Dist slab </button></div></div>';


         $('div .product').append(new_prodslab_html);


     });
     
     
</script>    
    
