<!DOCTYPE html>
<html>
<head>
  <title>Services</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
  <link rel="stylesheet" href="/boot/css/serviceintegration.css">
  <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="/boot/js/bootstrap-3.3.7.min.js"></script>
  <script type="text/javascript" src="/boot/js/serviceintegration.js"></script>
</head>
<body>
    <style>
        div.fixed {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 120px;

        }
    </style>

    <div class="row">
        <div>
          <a type="button"  href="/serviceintegration/servicesForm" id='serviceIntegration' name="serviceIntegration" class="btn btn-primary" >Home</a>                  </div>
        <div>
            <h3 style="float:right;">Service : <?php echo $servicename[$service_id]; ?></h3> <br>
            <h2>Products</h2>
        </div>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="<?php
                    if ($prodtype == "add") {
                        echo "active";
                    }
                    ?>"><a  href="/serviceintegration/servicesProducts/add/<?php echo $service_id; ?>">Add Product</a></li>
                    <li class="<?php
                    if ($prodtype == "list") {
                        echo "active";
                    }
                    ?>"><a  href="/serviceintegration/servicesProducts/list/<?php echo $service_id; ?>" >List Product</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <?php if($prodtype == "list") { ?>
    <div class="row">
          <form id="servcProducts" name="servcProducts" method="POST">
  <div class="table-responsive">
  <table class="table">
      <thead>
          <tr>
              <th> Id </th>
              <th>Product Id</th>
              <th> Name </th>
              <th> Min </th>
              <th> Max  </th>
              <th> Status </th>
              <th> Earning Type </th>
              <th> Earning Flag </th>
              <th> TDS </th>
              <th> Earning Margin </th>
              <th> Product Type </th>
              <th> Action </th>
         </tr>
      </thead>
      <tbody>
          <?php $i = 1; ?>
          <?php foreach($serviceProductDet as $servcprod) {?>
              <td>  <?php echo $servcprod['products']['id']; ?></td>
              <td><input type="text" class="form-control" style="width:130px;" name="upprodid_<?php echo $servcprod['products']['id']; ?>" id="upprodid_<?php echo $servcprod['products']['id']; ?>" value = "<?php echo $servcprod['products']['id']; ?>" disabled="true"></td>
              <td><input type="text" class="form-control" style="width:130px;" name="upprodname_<?php echo $servcprod['products']['id']; ?>" id="upprodname_<?php echo $servcprod['products']['id']; ?>" value = "<?php echo $servcprod['products']['name']; ?>" disabled="true" required></td>
              <td><input type="text" class="form-control" style="width:90px;" name="upprodmin_<?php echo $servcprod['products']['id']; ?>" id="upprodmin_<?php echo $servcprod['products']['id']; ?>" value = "<?php echo $servcprod['products']['min']; ?>" disabled="true" required></td>
              <td><input type="text" class="form-control" style="width:90px;" name="upprodmax_<?php echo $servcprod['products']['id']; ?>" id="upprodmax_<?php echo $servcprod['products']['id']; ?>" value = "<?php echo $servcprod['products']['max']; ?>" disabled="true" required></td>
              <td> <label class="switch">
                      <input type="checkbox" id="upprodStatus_<?php echo $servcprod['products']['id'];?>" name="upprodStatus_<?php $servcprod['products']['id'];?>" <?php echo ($servcprod['products']['active'] == '1')?"checked":""; ?> disabled="true"> <span class="slider round"></span>
                  </label>
              </td>
              <td>
                  <select class="form-control" style="width:150px;"id="upprodEarning_<?php echo $servcprod['products']['id'];?>" name="upprodEarning_<?php $servcprod['products']['id'];?>" disabled="true">
                      <option value="">Select</option>
                      <option value="0" <?php if($servcprod['products']['earning_type'] == '0') { echo "selected"; } ?>>Discount</option>
                      <option value="1" <?php if($servcprod['products']['earning_type'] == '1') { echo "selected"; } ?>>Commission</option>
                      <option value="2" <?php if($servcprod['products']['earning_type'] == '2') { echo "selected"; } ?>>Service Charge</option>
                  </select>
              </td>
              <td>
                  <select class="form-control" id="upprodearningflag_<?php echo $servcprod['products']['id'];?>" name="upprodearningflag_<?php $servcprod['products']['id'];?>" disabled="true" style="width:100px;">
                      <option value="">Select</option>
                      <option value="0" <?php if($servcprod['products']['earning_type_flag'] == '0') { echo "selected"; } ?>>Commission</option>
                      <option value="1" <?php if($servcprod['products']['earning_type_flag'] == '1') { echo "selected"; } ?>>Referral</option>
                  </select>
              </td>
              <td><input type="text" class="form-control" style="width:100px;" name="upprodtds_<?php echo $servcprod['products']['id']; ?>" id="upprodtds_<?php echo $servcprod['products']['id']; ?>" value = "<?php echo $servcprod['products']['tds']; ?>" disabled="true"></td>
              <td><input type="text" class="form-control" style="width:100px;" name="upprodemargin_<?php echo $servcprod['products']['id']; ?>" id="upprodemargin_<?php echo $servcprod['products']['id']; ?>" value = "<?php echo $servcprod['products']['expected_earning_margin']; ?>" disabled="true"></td>
              <td>
                  <select class="form-control" id="upprodtype_<?php echo $servcprod['products']['id'];?>" name="upprodtype_<?php $servcprod['products']['id'];?>" disabled="true" style="width:90px;">
                      <option value="">Select</option>
                      <option value="0" <?php if($servcprod['products']['type'] == '0') { echo "selected"; } ?>>P2P</option>
                      <option value="1" <?php if($servcprod['products']['type'] == '1') { echo "selected"; } ?>>P2A</option>
                  </select>
              </td>
              <td>
                  <div>
                  <button type="button" class="btn btn-primary" id="upserv_<?php echo $servcprod['products']['id'];?>" name="upserv_<?php echo $servcprod['products']['id'];?>" onclick="produpdEnable(<?php echo $servcprod['products']['id']; ?>)">Update</button>
                  <button type="button" class="btn btn-primary" id="upsserv_<?php echo $servcprod['products']['id'];?>" name="upsserv_<?php echo $servcprod['products']['id'];?>" disabled="true" onclick="produpdUpdate(<?php echo $servcprod['products']['id']; ?>)">Submit</button>  </div>
                  <!--<div>  <button type="button" class="btn btn-danger" id="fields_<?php echo $servcprod['products']['id'];?>" name="fields_<?php echo $servcprod['products']['id'];?>" onclick='produpdDelete(<?php echo $servcprod['products']['id']; ?>)'>Delete</button> </div>-->
              </td>
              </td>
      </tbody>
      <?php $i++; } ?>
      </table>
  </div>
  </form>
    </div>
    <?php }  else {?>
        <form method="post" name="frmaddproduct" id="frmaddproduct">
            <button type="submit" style="float:right;" class="btn btn-primary addproduct" id="prodadd" name="prodadd">Add</button>
         <div class="form-group col-md-12 prodrange" >
          <div class="row col-md-12 targetrange">
         <div class="row">
        <div class="col-lg-3">
            <label for="prodname">Name :</label>
            <input type="text" class="form-control" id="prodname" name="products[0][prodname]" required="true">
        </div>
         <div class="col-lg-3">
            <label for="prodmin">Min :</label>
            <input type="text" class="form-control" id="prodmin" name="products[0][prodmin]" required="true">
         </div>
         <div class="col-lg-3">
            <label for="prodmax">Max :</label>
            <input type="text" class="form-control" id="prodmax" name="products[0][prodmax]" required="true">
         </div>
        <div  class="col-md-3">
            <label for="prodearningtype">Earning Type :</label>
            <select class="form-control" id="prodearningtype" name="products[0][prodearningtype]" required="true">
                <option value = "">Select</option>
                <option value = "0">Discount</option>
                <option value = "1">Commission</option>
                <option value = "2">Service Charge</option>
            </select>
        </div>
         </div>
          <div class="row"> 
         <div  class="col-md-3">
             <label for="prodearningflag">Earning Type Flag :</label>
             <select class="form-control" id="prodgst" name="products[0][prodearningflag]" required="true">
                 <option value = "">Select</option>
                 <option value = "0">Commission</option>
                 <option value = "1">Referral</option>
             </select> <br>
         </div>             
         <div class="col-lg-3">
            <label for="prodtds">TDS :</label>
            <input type="text" class="form-control" id="prodtds" name="products[0][prodtds]" required="true">
         </div>
         <div class="col-lg-3">
            <label for="prodemargin">Margin :</label>
                <input type="text" class="form-control" id="emargin" name="products[0][emargin]" required="true">
         </div>             
        <div  class="col-md-3">
            <label for="prodtype">Type :</label>
            <select class="form-control" id="prodtype" name="products[0][prodtype]" required="true">
                <option value="">Select</option>
                <option value = "0">P2P</option>
                <option value = "1">P2A</option>
            </select> <br>
        </div></div>
             <input type="text" id="serviceid" name="products[0][serviceid]" value="<?php echo $service_id;?>" hidden="true">
             <input type="text" id="parentid" name="products[0][parentid]" value="<?php echo $product_id + 1;?>" hidden="true">
         <div  class="fixed">
             <button type="submit" class="btn btn-primary" style="padding:10px 20x 20px 10px;" id="prodcreate" name="prodcreate">Submit</button>
         </div>
        </div></div></div>
    <?php } ?>
</form>

</body>

</html>


<script>
    
   function getProductMapping(productId){             
        $.ajax({
            type: "POST\n\
",
            url: '/serviceintegration/getProductPlanDetails',
            dataType: "json",
            data: {'productId': productId},
            
            success: function (data) {
                        alert(data);
                            var flddata = JSON.parse(data); 
                     console.log(flddata);
                     console.log(data.service_product_plans[0].service_plan_id);
            },
        });
    }      
 
     $(document).on("click", ".open-prodMapping", function () {
         $("#prod_id").val($(this).data('id'));
      });

     $(document).on("click", ".open-vendorMapping", function () {
         $("#prods_id").val($(this).data('id'));
      });


     var i=1;
     var j=1;
     var k=1;
     var p='';


     $(document).on('click','.removeproduct',function(event){

        $(this).parent().parent().remove(".row")
     });

         $(document).on('click','.addproduct',function(event){
             var prodif = $('#parentid').val();
             console.log(parseInt(prodif) + parseInt(i));
             var new_product_html = '<div class="row col-md-12 targetrange"><div class="col-md-3"><label for="prodname">Name :</label> <input type="text" required class="form-control" name="products['+i+'][prodname]" ></div><div class="col-md-3"><label for="prodmin">Min :</label><input type="text" required class="form-control" name="products['+i+'][prodmin]" ></div><div class="col-md-3"><label for="prodmax">Max :</label><input type="text" required class="form-control" name="products['+i+'][prodmax]"></div>\n\
                                                         <div class="col-md-3"><label for="prodearningtype">Earning Type :</label><select id="prodearningtype" name="products['+i+'][prodearningtype]" class="form-control"><option value = "">Select</option><option value = "0">Discount</option><option value = "1">Commission</option><option value = "2">Service Charge</option></select></div>\n\
                                                         <div class="col-md-3"><label for="prodearningtypeflag">Earning Flag :</label><select id="prodearningtypeflag" name="products['+i+'][prodearningtypeflag]" class="form-control"><option value = "">Select</option><option value = "0">Commission</option><option value = "1">Refferal</option></select></div>\n\
                                                            <div class="col-md-3"><label for="prodtds">TDS   :</label><input type="text" required class="form-control" name="products['+i+'][prodtds]" ></div><div class="col-md-3"><label for="emargin">Earning Margin :</label><input type="text" required class="form-control" name="products['+i+'][emargin]"></div>\n\
                                                          <div class="col-md-3"><label for="prodtype">Type :</label><select id="prodtype" name="products['+i+'][prodtype]" class="form-control"><option value = "" >Select</option>\n\
                                                            <option value = "0">P2P</option> <option value = "1">P2A</option></select><br></div>\n\
                                                            <input type="text" id="serviceid" name=products['+i+'][serviceid]" value="<?php echo $service_id;?>" hidden="true">\n\
                                                            <input type="text" id="parentid" name=products['+i+'][parentid]" value="' + (parseInt(prodif) + parseInt(i)) +'"  hidden=sla"true">\n\
                                                            <div class="col-md-3"><button type="button" style="margin:22px 0px 0px 0px;"class="btn btn-primary removeproduct">Remove </button></div></div>';
             i++;

         $('div .prodrange').append(new_product_html);


     });

 

       $('#frmaddproduct').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#frmaddproduct').serializeArray();


        $.ajax({
            url: '/serviceintegration/InsProductDetails',
            type: 'post',
            dataType: 'json',

            success: function (data) {
                alert("Product Inserted Successfully");
                location.reload();
            },
            data: formData
        });

     });

</script>