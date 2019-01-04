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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
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
        <div class="panel-heading">Add Service Vendor</div>
        <div class="panel-body">
            <form id='InsServicePartner' name="InsServicePartner" method="POST">
                <div class="row">
                    <div class="col-md-10">
                        <label for='vendorName'> Name <font color="red">*</font> :</label>
                        <input type="text" style="width:400px"id='vendorName' name="vendorName"  class="form-control"></input>
                    </div> 
                </div>
                <div class="row">
                        <div class="col-md-12" style="margin:10px;"><label for="servicevendor"> Type <font color="red">*</font> :</label><input class="form-check-input" type="radio" name="upvendor[<?php echo $vendor_index; ?>][optradio]" id="optradio1" value="0" <?php if($vendors[0]['product_vendors']['type_flag']==0){ echo "checked";} ?> style = "margin-left:35px;"><label class="form-check-label" style = "margin-left:25px;">Prepaid</label> <input class="form-check-input" type="radio" name="upvendor[<?php echo $vendor_index; ?>][optradio]" id="optradio2" value="1" <?php if($vendors[0]['product_vendors']['type_flag']==1){ echo "checked";} ?>  style = "margin-left:35px;"><label class="form-check-label" style = "margin-left:25px;">Postpaid</label></div>
                </div>
                <div class="row">
                    <div class="vendor">
                    <div class="col-md-7" style="margin:10px;width:400px">
                        <label for='servicePartner'> Service Partner <font color="red">*</font>:</label>
                        <select  class="form-control" id="servicePartner" name="servicePartner" onchange="getProduct(this.value);">                                                                        
                            <option value="">Select</option>
                            <?php foreach ($servicePartner as  $val): ?>                 
                                <option value="<?php echo $val['service_partners']['id']; ?>"><?php echo $val['service_partners']['name']; ?></option>                                                     
                            <?php endforeach; ?>
                        </select>                        
                    </div>
                </div>
                </div>
                <div class="row">
               <div class="vproduct"></div>
           <div class="row">
                <div class="col-md-7">
                     <button type="button" style=" margin-left :25px;" id="addVendor" class="btn btn-primary" onclick="InsVendors()">Submit</button>
                </div>
           </div>
       </div>                        
                       
            </form>            
          
  <div class="table-responsive">
  <table class="table">
      <thead>
          <tr>
              <th> Id </th>
              <th> Name </th>
              <th> Service Partner </th>
              <th> Type </th>
              <th> Action </th>
          </tr>
      </thead>
      <tbody>          
          <?php $i=1;
                     foreach($vendors as $vendorval) {?>
              <td>  <?php echo $vendorval['product_vendors']['id'];?></td>
              <td> <input type="text" class="form-control" id="upvendorname_<?php echo $vendorval['product_vendors']['id'];?>" name="upvendorname_<?php echo $vendorval['product_vendors']['id'];?>" value = "<?php echo $vendorval['product_vendors']['name']; ?>" disabled="true">  </td>
              <td>  <select  class="form-control" id="upvservcpart_<?php echo $vendorval['product_vendors']['id'];?>" name="upvservcpart_<?php echo $vendorval['product_vendors']['id'];?>" disabled="true">
                     <option value="">Select</option>
                            <?php foreach ($servicePartner as  $val): ?>                 
                                <option value="<?php echo $val['service_partners']['id']; ?>" <?php if($vendorval['product_vendors']['service_partner_id'] == $val['service_partners']['id']) echo "selected";  ?>><?php echo $val['service_partners']['name']; ?></option>                                                     
                            <?php endforeach; ?>
                        </select>                                     
              </td>
              <td style="font-weight: bold;font-size: 15px;padding-top: 15px;">
              <?php
              if( $vendorval['product_vendors']['type_flag']==0){
                  echo "Prepaid";
              }else if( $vendorval['product_vendors']['type_flag']==1){
                  echo "Postpaid";
              }
              ?>
              </td>
              <td><a type="button" style=" margin-left :-30px;"   href="/serviceintegration/setVendorDetails/<?php echo $vendorval['product_vendors']['id']; ?>" class="btn btn-primary" id="upvendor_enb_<?php echo $vendorval['product_vendors']['id'];?>" name="upvendor_enb_<?php echo $vendorval['product_vendors']['id'];?>">Update</a>
                 </td>             
      </tbody>  
      <?php $i++; } ?>
      </table>
  </div>            

</body>

</html>

<script>
    var k = 1;
  function InsVendors(){
      var datas = $( "form" ).serialize();
      if($('#vendorName').val() == ''){
          alert('Please Insert Mandatory Field');
      }else {
        $.ajax({
            url: '/serviceintegration/InsVendor',
            type: 'post',
            dataType: 'json',
            data: datas,
            
            success: function(data) {
                if(data.status == 'success'){
                    alert(data.msg);                    
                    location.reload();

                }else{
                    alert(data.description);                    

                }    
            },
            failure: function(data){                
            alert("Vendor not got Created");
        },
        });}
  }
  
  
      $(document).on('click','.removevendormargin',function(event){
         k--;
         $(this).parent().parent().remove(".row")
     });

         $(document).on('click','.addvendorSlab',function(event){
            var product_id = $(this).attr('data-product-id');
            var i= parseInt($('.vendorrange_'+product_id).children('.vendortarget').last().attr('data-index'))+1;            
             var new_vendorslab_html = '<div class="row col-md-9 vendortarget" data-index="'+i+'" >\n\
                                        <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="vendor['+product_id+'][slabs]['+i+'][slabs]" width="30px" ></div>\n\
                                           <div class="col-md-2"><label>Margin</label><br><input type="text" class="form-control" id="margin" name="vendor['+product_id+'][slabs]['+i+'][margin]" width="30px" ></div>\n\
                                     <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="vendor['+product_id+'][slabs]['+i+'][min]" width="30px"></div><div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="vendor['+product_id+'][slabs]['+i+'][max]" width="30px">\n\
          </div><div class="col-md-1"><button style="margin:30px 0px 0px 0px;"class="btn btn-primary removevendormargin" type="button">Remove Vendor slab </button></div></div>';             

         $('div .vendorrange_'+product_id).append(new_vendorslab_html);


     });

    $(document).on('click','.remproductslab',function(event){
        
         $(this).next().remove();
         $(this).remove();
     });

            var m=1;
              $(document).on('click','#addVendorProduct',function(event){                                    
                  var products_list = $('#products_list').val();
             var new_prodslab_html = ' <button type="button" style="float: right;" id="remProduct" class="btn btn-primary remproductslab">Remove Product Slab</button>\n\
                                        <div class="vproduct_'+m+'"><div class="col-md-7" style="margin:10px;width:400px"> <label for="servicevendor_'+m+'"> Product <font color="red">*</font> :</label><select class="form-control" id="servicevendor_'+m+'" name="vendor['+m+'][servicevendor]" ><option value="">Select</option>'+products_list+'</select></div>\n\
                                    <div id="vendmargin" class="form-group col-md-12 vendorrange_'+m+'" >\n\<div class="row col-md-9 vendortarget" data-index="0" >\n\
                                        <div class="col-md-2"><label>Slab</label><br><input type="text" class="form-control" id="slabs" name="vendor['+m+'][slabs][0][slabs]" width="30px" ></div>\n\
                                           <div class="col-md-2"><label>Margin</label><br><input type="text" class="form-control" id="margin" name="vendor['+m+'][slabs][0][margin]" width="30px" ></div>\n\
                                     <div class="col-md-2"> <label>Min</label><br><input type="text" class="form-control" id="min" name="vendor['+m+'][slabs][0][min]" width="30px"></div><div class="col-md-2"><label>Max</label><br><input type="text" class="form-control" id="max" name="vendor['+m+'][slabs][0][max]" width="30px"></div>\n\
                  <div class="col-md-1"><button type="button" id="addvendorSlab" data-product-id="'+m+'" class="btn btn-primary addvendorSlab"style="margin-top:30px;">Add Vendor Margin</button></div>\n\
                             </div>';             

m++;
         $('.vproduct').append(new_prodslab_html);


     });
    function getProduct(val){       
        var listItems = '';       
        $.ajax({
        type: "POST",
        url: '/serviceintegration/prodListing',
        dataType: "json",
        data: {id : val},
        success: function (data) {                                    
            var obj = data;         
                    if(obj.length > 0){
                           
                         var add_product_button= '<button type="button" style="float: right;" id="addVendorProduct" class="btn btn-primary addVendorProduct">Add Product </button>';
                         var vendor_margin= '<div class="vproduct_0"><div class="col-md-7" style="margin:10px;width:400px"><label for="servicevendor"> Product <font color="red">*</font> :</label><select class="form-control" id="servicevendor" name="vendor[0][servicevendor]">        <option value="">Select</option>    </select>    <input type="hidden" id="products_list" value=""/></div>            <div id="vendmargin" class="form-group col-md-12 vendorrange_0">              <div class="col-md-9 vendortarget"  data-index="0">            &nbsp;&nbsp;<h4> Vendor Product Margin</h4>                  <div class="col-md-2">  <label>Slab</label><br>  <input type="text" class="form-control" id="slabs" name="vendor[0][slabs][0][slabs]" width="30px"  onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 0-100" >                  </div>                  <div class="col-md-2">  <label>Margin</label><br>  <input type="text" class="form-control" id="margin" name="vendor[0][slabs][0][margin]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 10%"></div><div class="col-md-2">  <label>Min</label><br>  <input type="text" class="form-control" id="min" name="vendor[0][slabs][0][min]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00">                  </div>                  <div class="col-md-2">  <label>Max</label><br>  <input type="text" class="form-control" id="max" name="vendor[0][slabs][0][max]" width="30px" onkeypress="return isNumberKey(event)" data-toggle="tooltip" title="eg 100.00"></div><div class="col-md-1">  <button type="button" id="addvendorSlab" data-product-id="0" class="btn btn-primary addvendorSlab"style="margin-top:30px;">Add Vendor Margin</button></div></div></div></div>';
                            
                        $('.vproduct').html(add_product_button+vendor_margin);
                         
                        $('#vendmargin').show();
                        $.each(obj, function (index,field) {               
                          listItems += "<option value='" + field["p"]["id"] + "'>" + field["p"]["name"] + "</option>";                                                  
                        });     
                        $('#products_list').val(listItems);
                        $("#servicevendor").html(listItems);
                    }else{
                            $("#vendmargin").remove();
                             $('.vproduct').html('');
                            alert('No Service Mapped till now with Partner');                            

                    }   
        },
        failure: function (data) {
            alert('failed');
        }              
            
    }); }

</script>