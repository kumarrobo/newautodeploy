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
  <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

    <script src="/boot/js/bootstrap-datepicker.min.js"></script>
    </head>
    <style>
        .typo .btn-group{display: block;}
        .typo .multiselect.dropdown-toggle.btn.btn-default {
            display: block;
            overflow: hidden;
            width: 100%;
        }
    </style>
<script>
//    $(document).ready(function () {
//        $('#upservcIncservice').multiselect({
//            enableFiltering: true,
//            enableCaseInsensitiveFiltering: true,
//            includeSelectAllOption: true
//        });
//    });
//
//    $(document).ready(function () {
//        $('#servcIncservice').multiselect({
//            enableFiltering: true,
//            enableCaseInsensitiveFiltering: true,
//            includeSelectAllOption: true
//        });
//    });



        $("#kycfield").select2({
        placeholder: "Select Distributor Shopname",
        dropdownAutoWidth: 'true',
        width:'250px',
        allowClear: true
    });

    </script>
</head>
<body>


<div class="container">

  <div class="col-md-12">
      <div class="col-md-10">
          <a  href='InsServicePartner'   style="float: right;" class="btn btn-primary" id="addServc" name="addServc">Add Service Partner</a><br><br>
      </div>
      <div class="col-md-2">
          <a  href='serviceVendor' style="float: right;" class=" btn btn-primary" id="addVendor" > Add Vendor</a><br><br>
      </div>
  </div>
  <div class="col-md-12">
      <div class="col-md-10">
          <a  href='ListServicePartner' style="float: right;" class=" btn btn-primary" id="listServc" >List Service Partner</a><br><br>
        </div>
      <div class="col-md-2">
                    <a  href='serviceVendor' style="float: right;" class=" btn btn-primary" id="listVendor" > List Vendor</a><br><br>
      </div>
  </div>

  <h2>Services List</h2><br>

  <div class="float-right">
      <button  style="float: right;"class="btn btn-primary" id="addServc" name="addServc" data-toggle="modal"
               data-target="#addService">Add Service</button><br><br>
  </div>
  <form id="servcForm" name="servcForm" method="POST">
  <div class="table-responsive">
  <table class="table">
      <thead>
          <tr>
              <th> Id </th>
              <th> Name </th>
              <th> Partner name </th>
              <th> Status </th>
              <th> Type  </th>
              <th> Dependency </th>
              <th> Referral/Incentive </th>
              <th> Incentive Adjustment Service </th>
              <th> GST </th>
              <th> Action </th>
              <th> Other Operations </th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($servcDetails as $servc) {?>
              <td> <?php echo $servc['services']['id']; ?></td>
              <td> <?php echo $servc['services']['name']; ?>  </td>
              <td> <select class="form-control" style="width:120px;" id="upservcPartner_<?php echo $servc['services']['id'];?>" name="upservcPartner_<?php echo $servc['services']['id'];?>" disabled="true">
                     <option value="">Select</option>
                     <?php foreach ($servcPartner as $id=>$name) { ?>
                    <option value="<?php echo $id; ?>"<?php  if($id == $servc['services']['partner_id'] ) echo 'selected';?> ><?php echo $name; ?></option>
                        <?php } ?>
                  </select>
              </td>
              <td> <label class="switch">
                      <input type="checkbox" id="upservStatus_<?php echo $servc['services']['id'];?>" name="upservStatus_<?php echo $servc['services']['id'];?>" <?php echo ($servc['services']['toShow'] == '1')?"checked":""; ?> disabled="true"> <span class="slider round"></span>
                  </label>
              </td>
              <td>
                  <select class="form-control" id="upservcType_<?php echo $servc['services']['id'];?>" name="upservcType_<?php echo $servc['services']['id'];?>" disabled="true" style="width:120px;">
                      <option value="">Select</option>
                      <option value="1" <?php if($servc['services']['service_type'] == '1') { echo "selected"; } ?>>Recharge Services</option>
                      <option value="2" <?php if($servc['services']['service_type'] == '2') { echo "selected"; } ?>>Internal Platform</option>
                      <option value="3" <?php if($servc['services']['service_type'] == '3') { echo "selected"; } ?>>External Platform</option>
                  </select>
              </td>
              <td>
                  <select class="form-control" id="upservcRegist_<?php echo $servc['services']['id'];?>" name="upservcRegist_<?php echo $servc['services']['id'];?>" disabled="true" style="width:120px;">
                      <option value="">Select</option>
                      <option value="1" <?php if($servc['services']['registration_type'] == '1') { echo "selected"; } ?>>Open</option>
                      <option value="2" <?php if($servc['services']['registration_type'] == '2') { echo "selected"; } ?>>Kit Based</option>
                      <option value="3" <?php if($servc['services']['registration_type'] == '3' || $servc['services']['registration_type'] == '4') { echo "selected"; } ?>>Non Kit Based</option>
                  </select>
              </td>
              <td>
                  <select class="form-control" id="upservcInc_<?php echo $servc['services']['id'];?>" name="upservcInc_<?php echo $servc['services']['id'];?>" disabled="true" style="width:70px;">
                      <option value="">Select</option>
                      <option value="0" <?php if($servc['services']['inc_type_flag'] == '0') { echo "selected"; } ?>>Incentive</option>
                      <option value="1" <?php if($servc['services']['inc_type_flag'] == '1') { echo "selected"; } ?>>Referral</option>
                  </select>
              </td>
              <td>
                  <div class="row typo">
                  <div class="col-md-2 ">
                      <select class="form-control" id="upservcIncservice_<?php echo $servc['services']['id'];?>" name="upservcIncservice[]"  style="width:120px;" value="<?php echo $s; ?>" multiple="true" disabled="true">
                      <option value="">Select</option>
                      <?php foreach ($servcDetail as $serv) { ?>
                      <?php if($servc['services']['id'] != $serv['services']['id']) { ?>
                      <option value="<?php echo $serv['services']['id']; ?>" <?php if(in_array($serv['services']['id'],explode(',', $servc['services']['inc_adj_services']))){ echo "selected"; }?> ><?php echo $serv['services']['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                  </select>
                  </div></div>
              </td>
              <td>
                  <select class="form-control" id="upservcGst_<?php echo $servc['services']['id'];?>" name="upservcGst_<?php echo $servc['services']['id'];?>" disabled="true" style="width:110px;">
                          <option value="">Select</option>
                          <option value="0" <?php if($servc['services']['gst'] == '0') { echo "selected"; } ?>>Inclusive</option>
                          <option value="1" <?php if($servc['services']['gst'] == '1') { echo "selected"; } ?>>Exclusive</option>
                  </select>
                </td>
              <td>
                  <button type="button" class="btn btn-primary" id="upserv_<?php echo $servc['services']['id'];?>" name="upserv_<?php echo $servc['services']['id'];?>" onclick="servcupdEnable(<?php echo $servc['services']['id']; ?>)">Update</button>
                  <button type="button" class="btn btn-primary" id="upsserv_<?php echo $servc['services']['id'];?>" name="upsserv_<?php echo $servc['services']['id'];?>" disabled="true" onclick="servcdetailsUpdate(<?php echo $servc['services']['id']; ?>)">Submit</button>
              </td>
              <td>
                  <button type="button" class="btn btn-info" id="fields_<?php echo $servc['services']['id'];?>" name="fields_<?php echo $servc['services']['id'];?>" onclick='getServiceFields(<?php echo $servc['services']['id']; ?>)'>Fields</button>
                  <button type="button" class="btn btn-info" id="plans_<?php echo $servc['services']['id'];?>" name="plans<?php echo $servc['services']['id'];?>" onclick="location.href='/serviceintegration/servicesPlans/list/<?php echo $servc['services']['id']; ?>'">Plans</button>
                  <button type="button" class="btn btn-info" id="product_<?php echo $servc['services']['id'];?>" name="products_<?php echo $servc['services']['id'];?>" onclick="location.href='/serviceintegration/servicesProducts/list/<?php echo $servc['services']['id'];?>'">Products</button>
              </td>
      </tbody>
      <?php } ?>
      </table>
  </div>
  </form>
</div>
<!-- Modal for Add Services -->
<div id="addService" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="height: 780px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Services</h4>
      </div>
      <div class="modal-body" style="padding:40px 50px;">
          <form name="form" id="form" role="form" method="POST">
           <div class="form-group"  style="width:350px;">
          <label for="servcName"> Name <font color="red">*</font> :</label>
          <input type="text" class="form-control" name="servcName" id="servcName" required>
          </div>
        <div class="form-group">
          <label for="servcType"> Service Type :</label>
          <select id="servcType" name="servcType" class="form-control"  style="width:350px;">
              <option value="">Select</option>
              <option value="2">Internal Platform</option>
              <option value="3">External Platform</option>
          </select>
        </div>
            <div class="form-group" >
          <label for="servcRegist"> Registration Type :</label>
          <select id="servcRegist" name="servcRegist" class="form-control" style="width:350px;">
              <option value="">Select</option>
              <option value="1">Open</option>
              <option value="2">Kit Based</option>
              <option value="3">Non Kit Based</option>
          </select>
            </div>

              <div class="row">
                <div class="col-md-5">
                      <label for="servcPartner">Service Partner :</label>
                      <select id="servcPartner" name="servcPartner" class="form-control" style="width:350px;">
                          <option value="">Select</option>
                         <?php
                        foreach ($servcPartner as $partner_id => $partner_name) { ?>                             
                          <option value="<?php echo $partner_id; ?>"><?php echo $partner_name; ?></option>
                         <?php } ?>
                      </select>
                </div></div><br>
              <div class="row">
                <div class="col-md-5">
                  <label for="servcInc">Incentive:</label>
                  <select class="form-control" id="servcInc" name="servcInc"  style="width:350px;">
                      <option value="">Select</option>
                      <option value="0" >Incentive</option>
                      <option value="1" >Referral</option>
                  </select>
                </div>
              </div><br>
                <div class="row type">
                    <div class="col-md-5">
                        <label for="servcIncservice">Incentive Service:</label>
                        <select class="form-control" id="servcIncservice" name="servcIncservice[]" style='width:300px;float:left;' multiple="true">
                            <option value="">Select</option>
                            <?php foreach ($servcDetails as $servcDet) { ?>
                                <option value=<?php echo $servcDet['services']['id']; ?>><?php echo $servcDet['services']['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div><br>
              <div class="row">
                  <div class="col-md-5">
                      <label for="servcGst">Gst :</label>
                  <select class="form-control" id="servcGST" name="servcGST" style="width:350px;">
                      <option value="">Select</option>
                      <option value="0">Inclusive</option>
                      <option value="1">Exclusive</option>
                  </select>
                  </div>
              </div><br>
              <button type="button" class="btn btn-primary" onclick="createService()">Submit</button>
          </form>
      </div>
    </div>

  </div>
</div>

<!--Modal for KYC MAPPING -->

<div id="" class="operations-modal modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">

      </div>
    </div>

  </div>
</div>



<div class="modal" id="KYCmappingModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">KYC Mapping</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
          <div class="row">
        <div class ="col-lg-2">
          <label for="kycfield">ADD KYC DOC:</label>
          <button type="submit" class="btn btn-primary" onclick="addKYCdetail()">Submit</button>
        </div>
        <div class ="col-lg-4 type">
          <select class="form-control" style='width:300px;float:left' id="kycfield"  name="kycfield[]" multiple="multiple" onchange="$('#doc_name').val($('#kycfield').val());">
              <?php foreach ($getKYCname as $kycname): ?>
                  <option value="<?php echo $kycname['imp_labels']['id']; ?>">
                      <?php echo $kycname['imp_labels']['label'] ?>
                  </option>
              <?php endforeach ?>
          </select>
              <input type="hidden" name="doc_name" id="doc_name" >
              <input type="hidden" name="kycserviceval" id="kycserviceval" >
        </div><br><br><br><br><br><br><br>
        <div class="kycservicebody" >

        </div>
      </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Add Services -->
<div id="addFieldModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="height: 1080px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Fields</h4>
      </div>
      <div class="modal-body">
          <form name="fieldform" id="fieldform" role="form" method="POST">
              <div>
           <button style="float :left" type="button" class="btn btn-primary addfields" id="AddField" name="Addfield" >Add Fields</button></div><br><br>
            <div class="form-group col-md-12 prodfields">
            <div class="row">
            <div class="col-lg-3">
                <input type="text"   name ="fields[0][fieldval]" style="display:none" id="fieldval" >
            </div>
          <div class="row col-md-12 fieldrange">
           <div  class="col-lg-3">
          <label for="fieldkey" style="margin-right:58px;"> Field Key<font color="red">*</font>:</label>
          <input type="text" class="form-control" name="fields[0][fieldkey]" id="fieldkey">
           </div>
           <div  class="col-lg-3">
          <label for="fieldlab"> Field Label<font color="red">*</font> :</label>
          <input type="text" class="form-control" id="fieldlab" name="fields[0][fieldlab]">
           </div>
           <div  class="col-lg-3">
          <label for="fieldtype">  Type :</label>
          <select id="fieldtype" class="form-control" name="fields[0][fieldtype]">
              <option value="">Select</option>
              <option value="checkbox">Checkbox</option>
              <option value="text">Text</option>
              <option value="dropdown">Dropdown</option>
              <option value="label">Label</option>
          </select>
           </div>
        <div  class="col-lg-3">
          <label for="fieldregex"> Regular Expression:</label>
          <input type="text" class="form-control" id="fieldregex" name="fields[0][fieldregex]">
        </div><br>
         <div  class="col-lg-3">
          <label for="fieldvalid"> Validation :</label>
          <select id="fieldvalid" class="form-control" name="fields[0][fieldvalid]">
              <option value="">Select</option>
              <option value="readonly">Read Only</option>
              <option value="require">Required</option>
              <option value="unique">Unique</option>
          </select>
         </div>
          <div  class="col-lg-3">
          <label for="fielddef"> Default Value:</label>
            <input type="text" class="form-control fieldcontainer" id="fielddef" name="fields[0][fielddef]">
          </div><br><br>
          <div class="fixed">
           <button  type="button" class="btn btn-primary"  id="InsField" name="Insfield"  onclick="InsServcfield();" style="float:right;">Submit</button>
          </div>
      </div>
    </div>
   </div>
</form>
  </div>
</div>
</div>
</div>
</div>
    <!-- Modal for Add Products -->
<div id="addProductModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Products</h4>
      </div>
      <div class="modal-body">
          <form name="productform" id="productform" role="form" method="POST">
              <div>
                  <button style="float : left" type="button" class="btn btn-primary" id="AddProduct" name="AddProduct" onClick="addInputProduct('productcontainer');">Add Products</button></div><br><br>
                  <div id='productcontainer'>
                      <div class="form-group">
                          <input type="text"   name ="product_srvcid" id="product_srvcid" hidden="true">
                          <label for="product_name" style="margin-right:58px;"> Name :</label>
                          <input type="text" name="product_name" id="product_name" multiple="true">
                  </div></div>
              <button style="float : right" type="submit" class="btn btn-primary" id="Insproduct" name="Insproduct" onclick="InsProduct();">Submit</button>
          </form>
      </div>
    </div>

  </div>
</div>

</body>
</html>

<script>
    var i = 1;

 $(document).on('click','.removefields',function(event){
         $(this).parent().parent().remove(".row")
     });

     $(document).on('click','.addfields',function(event){

             var new_fields_html = '<br><br><div class="row col-md-12  fieldrange"><div class="col-lg-3"> <label for="fieldkey" style="margin-right:58px;"> Field Key :</label> <input type="text" name="fields['+i+'][fieldkey]" id="fieldkey" class="form-control" ></div> <div class="col-lg-3"><label for="fieldlab"> Field Label :</label> \n\
                               <input type="text" id="fieldlab" name="fields['+i+ '][fieldlab]" class="form-control"></div> <div class="col-lg-3"><label  ="fieldtype">Type :</label> <select id="fieldtype" name="fields[' +i+ '][fieldtype]" class="form-control"> <option value="">Select</option> <option value="checkbox">Checkbox</option> <option value="text">Text</option><option value="dropdown">Dropdown</option></select></div>\n\
                                <div class="col-lg-3"> <label for="fieldregex"> Regular Expression:</label> <input type="text" id="fieldregex" name="fields['+i+'][fieldregex]" class="form-control"> </div> \n\
                                <div class="col-lg-3"><label for="fieldvalid"> Validation :</label>\n\
                                <select id="fieldvalid" "fields[' +i+ '][fieldvalid]" class="form-control"><option value="">Select</option><option value="readonly">Read Only</option><option value="require">Required</option><option value="unique">Unique</option></select>\n\
                                </div><div class="col-lg-3"><label for="fielddef"> Default Value :</label> <input type="text" id="fielddef" name="fields['+i+'][fielddef]" class="form-control"></div> <div class="col-md-3"><button type="button" style="margin:22px 0px 0px 0px;"class="btn btn-primary removefields">Remove Field</button><br><br></div>';


          i++;

         $('div .prodfields').append(new_fields_html);

     });

    </script>