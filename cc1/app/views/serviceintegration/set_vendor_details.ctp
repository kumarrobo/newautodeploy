<!DOCTYPE html>
<html>
<head>
  <title>Service Vendors</title> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
  <link rel="stylesheet" href="/boot/css/serviceintegration.css">
  <script type="text/javascript" src="/boot/js/serviceintegration.js"></script>
  <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="/boot/js/bootstrap-3.3.7.min.js"></script>    
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-stylesheetmultiselect/0.9.13/css/bootstrap-multiselect.css">
  <style>
      label{
          margin-top       : 10px;
          margin-bottom    : 5px;
      }
             
  </style>

</head>
<body>
    <div class="row">
          <a type="button"  href="/serviceintegration/servicesForm" id='serviceIntegration' name="serviceIntegration" class="btn btn-primary" >Home</a>          
    </div>
    <h2>Service Vendors</h2>
    <div class="panel panel-default">
        <div class="panel-heading">Update Service Vendor</div>
        <div class="panel-body">
            <form id='InsServicePartner' name="InsServicePartner" method="POST">
                <div class="row">
                    <div class="col-md-10" style="margin:25px;width:400px;">
                        <label for='vendorName'> Name <font color="red">*</font>:</label>
                        <input type="text" style="width:400px"id='upvendorName' name="upvendorName"  class="form-control" value="<?php echo $vendorDet['0']['pv']['name'];?>"></input>
                    </div> 
                </div>
                <input type="hidden" name='vendor_id' value="<?php echo $vendorDet['0']['pv']['id']; ?>">
                <input type="hidden" name='margin_id' value="<?php echo $vendorDet['0']['pvm']['id']; ?>">

                <div class="row">
                    <div class="col-md-12" style="margin:10px;width:400px;"><label for="servicevendor"> Type <font color="red">*</font> :</label><input class="form-check-input" type="radio" name="upvendor[<?php echo $vendor_index; ?>][optradio]" id="optradio1" value="0" <?php if($vendorDet[0]['pv']['type_flag']==0){ echo "checked";} ?> style = "margin-left:35px;"><label class="form-check-label" style = "margin-left:25px;">Prepaid</label> <input class="form-check-input" type="radio" name="upvendor[<?php echo $vendor_index; ?>][optradio]" id="optradio2" value="1" <?php if($vendorDet[0]['pv']['type_flag']==1){ echo "checked";} ?>  style = "margin-left:35px;"><label class="form-check-label" style = "margin-left:25px;">Postpaid</label></div>
                </div>

                <div class="row">
                    <div class="vendor">
                    <div class="col-md-7" style="margin:10px;width:400px">
                        <label for='servicePartner'> Service Partner <font color="red">*</font>:</label>
                        <select  class="form-control" id="servicePartner" name="servicePartner" disabled="true">                                                                        
                            <option value="">Select</option>
                            <?php foreach ($servicePartner as  $val): ?>                 
                                <option value="<?php echo $val['service_partners']['id']; ?>" <?php echo($val['service_partners']['id'] == $vendorDet['0']['pv']['service_partner_id'])? 'selected':''; ?>><?php echo $val['service_partners']['name']; ?></option>                                                     
                            <?php endforeach; ?>
                        </select>                        
                    </div>
   
                </div>
                </div>
                <div class="row">
               <div class="vproduct">
                   <button type="button" style="float: right;" id="addVendorProduct" class="btn btn-primary addVendorProduct">Add Product </button>
                   <!--<button type="button" style="float: right;" id="addVendorProduct" class="btn btn-primary addVendorProduct" onclick="getProduct()">Add Product </button>-->
            <?php                
                foreach($vendorDet  as  $vendor_index => $vendor) { ?>                                                                
                   <?php if($vendor_index != 0) { ?>
                   <button type="button" style="float: right;" id="remProduct" class="btn btn-primary remproductslab">Remove Product Slab</button>
                   <?php } ?>
                   <div class="products-row vproduct_<?php echo $vendor_index; ?>" data-index="<?php echo $vendor_index; ?>">       
                
                    <div class="col-md-7" style="margin:10px;width:400px">
                        <label for='servicevendor'> Product :</label>
                        <select class="form-control" id="servicevendor" name="upvendor[<?php echo $vendor_index; ?>][servicevendor]">                            
                            <?php 
                                foreach ($prodDet as $prod) { 
                                $listItems .= '<option value="'.$prod['p']['id'].'" >'.$prod['p']['name'].'</option>';    
                                         
                            ?>
                            <option value="<?php echo $prod['p']['id'];?>" <?php if(($prod['p']['id'] == $vendor['pvm']['product_id'])){ echo "selected";} ?>><?php echo $prod['p']['name'];?></option>
                            <?php } ?>
                        </select>

                         <?php if($vendor_index == 0) { ?>
                       
                            <input type="hidden" id="products_list" value='<?php echo $listItems; ?>'/>
                        <?php } ?>
                    </div>                    
                
            
          <?php if($vendor['pvm']['margin'] == "") { ?>
                    <?php  
                        $i=0;?>                
                <div class="form-group col-md-12 vendorrange_<?php echo $vendor_index; ?>" >
              <div class="extra-row col-md-9 vendortarget"  data-index="<?php echo $i; ?>">
                  <div class="col-md-2">
                      <label>Slab</label><br>
                      <input type="text" class="form-control" id="slabs" name="upvendor[0][slabs][<?php echo $i; ?>][slabs]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100">
                  </div>
                  <div class="col-md-2">
                      <label>Margin</label><br>
                      <input type="text" class="form-control" id="margin" name="upvendor[0][slabs][<?php echo $i; ?>][margin]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%">
                  </div>
                  <div class="col-md-2">
                      <label>Min</label><br>
                      <input type="text" class="form-control" id="min" name="upvendor[0][slabs][<?php echo $i; ?>][min]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                  </div>
                  <div class="col-md-2">
                      <label>Max</label><br>
                      <input type="text" class="form-control" id="max" name="upvendor[0][slabs][<?php echo $i; ?>][max]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                  </div>
                  <?php if($i == 0 ){?>
                  <div class="col-md-1">
                      <button type="button" id="addvendorSlab" data-product-id="<?php echo $vendor_index; ?>" class="btn btn-primary addvendorSlab"style="margin-top:32px;">Add Vendor Margin</button>
                  </div>
                  <?php }else { ?>
                      <div class="col-md-1">
                         <button style="margin:32px 0px 0px 0px;"class="btn btn-primary removevendormargin" type="button">Remove Vendor slab </button>
                      </div>
                  <?php } ?>
              </div>
              </div>
          <?php $i++; } else { 
              $i=0;
              ?>
              <div  class="form-group col-md-12 vendorrange_<?php echo $vendor_index; ?>">
                <?php foreach(json_decode($vendor['pvm']['margin'],true) as $slab_key => $slab ){ ?>
               
                <div class="extra-row col-md-9 vendortarget"  data-index="<?php echo $i; ?>">                
                  <div class="col-md-2">
                      <label>Slab</label><br>                          
                      <input type="text" class="form-control" id="slabs" name="upvendor[<?php echo $vendor_index; ?>][slabs][<?php echo $i; ?>][slabs]" width="30px" value='<?php echo $slab_key; ?>' onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100">
                  </div>
                  <div class="col-md-2">
                      <label>Margin</label><br>
                      <input type="text" class="form-control" id="margin" name="upvendor[<?php echo $vendor_index; ?>][slabs][<?php echo $i; ?>][margin]" width="30px" value='<?php echo $slab['margin']; ?>' onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%">
                  </div>
                  <div class="col-md-2">
                      <label>Min</label><br>
                      <input type="text" class="form-control" id="min" name="upvendor[<?php echo $vendor_index; ?>][slabs][<?php echo $i; ?>][min]" width="30px" value='<?php echo $slab['min']; ?>' onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                  </div>
                  <div class="col-md-2">
                      <label>Max</label><br>
                      <input type="text" class="form-control" id="max" name="upvendor[<?php echo $vendor_index; ?>][slabs][<?php echo $i; ?>][max]" width="30px" value='<?php echo $slab['max']; ?>' onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">
                  </div>
                  <?php if($i == 0 ){?>
                  <div class="col-md-1">
                      <button type="button" id="addvendorSlab" data-product-id="<?php echo $vendor_index; ?>" class="btn btn-primary addvendorSlab"style="margin-top:32px;">Add Vendor Margin</button>
                  </div>
                  <?php }else { ?>
                      <div class="col-md-1">
                         <button style="margin:32px 0px 0px 0px;"class="btn btn-primary removevendormargin" type="button">Remove Vendor slab </button>
                      </div>
                  <?php } ?>
              </div>
               
          <?php $i++;
          
                }?>
                  </div>
          <?php } ?>
          </div>          
       <?php  } ?></div>              
     
           <div class="row">
                <div class="col-md-7" style="margin:25px;width:400px;">
                     <button type="button" id="addVendor" class="btn btn-primary" onclick="updVendors()">Submit</button>                        
                </div>
           </div>
            </div>
            </form>
        </div>
    </div>

<script>    
    

  function updVendors(){
      
      var disabled = $( "form" ).find(':input:disabled').removeAttr('disabled');
      var datas = $( "form" ).serialize();
      $("#servicePartner").removeAttr('disabled');
      if($('#upvendorName').val() == ''){
          alert('Please Insert Mandatory Field');
      }else {
        $.ajax({
            url: '/serviceintegration/updVendors',
            type: "POST",
            dataType: "json",
            data: datas,
            
            
            success: function(data) {                                
                if(data.status == 'success'){
                    alert(data.msg);
                    disabled.attr('disabled','disabled');
                    location.reload();

                }else{
                    alert(data.description);
                    disabled.attr('disabled','disabled');

                }    
            }            
         
        });
    }
  }
  

  
  
      $(document).on('click','.removevendormargin',function(event){         
         $(this).parent().parent().remove(".extra-row")
     });
     
         $(document).on('click','.addvendorSlab',function(event){             
            var product_id = $(this).attr('data-product-id');            
            var i= parseInt($('.vendorrange_'+product_id).children('.vendortarget').last().attr('data-index'))+1;                        
             var new_vendorslab_html = '<div class="extra-row  col-md-9 vendortarget" data-index="'+i+'" >\n\
                                        <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="upvendor['+product_id+'][slabs]['+i+'][slabs]" width="30px" ></div>\n\
                                           <div class="col-md-2"><label>Margin</label><br><input type="text" class="form-control" id="margin" name="upvendor['+product_id+'][slabs]['+i+'][margin]" width="30px" ></div>\n\
                                         <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="upvendor['+product_id+'][slabs]['+i+'][min]" width="30px"></div>\n\
                                        <div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="upvendor['+product_id+'][slabs]['+i+'][max]" width="30px">\n\
                                     </div><div class="col-md-1"><button style="margin:30px 0px 0px 0px;"class="btn btn-primary removevendormargin" type="button">Remove Vendor slab </button></div></div>';             
    
         $('div .vendorrange_'+product_id).append(new_vendorslab_html);


     });

    $(document).on('click','.remproductslab',function(event){
        
         $(this).next().remove();
         $(this).remove();
     });

              $(document).on('click','.addVendorProduct',function(event){  
                       var products_list = $('#products_list').val();                           
                  var m = parseInt($('.vproduct').children('.products-row').last().attr('data-index'))+1; 
                var new_prodslab_html = '<button type="button" style="float: right;" id="remProduct" class="btn btn-primary remproductslab">Remove Product Slab</button><div class="products-row vproduct_'+m+'" data-index="'+m+'" ><div class="col-md-7" style="margin:10px;width:400px"> <label for="servicevendor"> Product <font color="red">*</font> :</label><select class="form-control" id="servicevendor" name="upvendor['+m+'][servicevendor]" ><option value="">Select</option>'+products_list+'</select><input type="hidden" id="products_list" value=""/></div>\n\
                                       <div id="vendmargin" class="form-group col-md-12 vendorrange_'+m+'" >\n\<div class="extra-row col-md-9 vendortarget" data-index="0" >\n\
                                           <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="upvendor['+m+'][slabs][0][slabs]" width="30px" ></div>\n\
                                              <div class="col-md-2"><label>Margin</label><br><input type="text" class="form-control" id="margin" name="upvendor['+m+'][slabs][0][margin]" width="30px" ></div>\n\
                                        <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="upvendor['+m+'][slabs][0][min]" width="30px"></div><div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="upvendor['+m+'][slabs][0][max]" width="30px"></div>\n\
                     <div class="col-md-1"><button type="button" id="addvendorSlab" data-product-id="'+m+'" class="btn btn-primary addvendorSlab"style="margin-top:30px;">Add Vendor Margin</button></div>\n\
                                </div>';             


         $('div .vproduct').append(new_prodslab_html);


     });

</script>
</body>
</html>