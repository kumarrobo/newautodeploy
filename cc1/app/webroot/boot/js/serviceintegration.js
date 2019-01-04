    /* For adding more field in the field modal */
    var counter = 1;
    var limit = 6; 
 function servcupdEnable(id){            
        $('#upservcPartner_'+id).removeAttr("disabled");
        $('#upservStatus_'+id).removeAttr("disabled");
        $('#upservcType_'+id).removeAttr("disabled");
        $('#upservcRegist_'+id).removeAttr("disabled");
        $('#upsserv_'+id).removeAttr("disabled");
        $('#upservcGst_'+id).removeAttr("disabled");
        $('#upservcIncservice_'+id).removeAttr("disabled");
        $('#upservcInc_'+id).removeAttr("disabled");
        $('#upserv_'+id).prop("disabled","true");
    }
    
    function fldupdEnable(fid){
    $('#upfielddef_' + fid).removeAttr("disabled");
    $('#upfieldlab_' + fid).removeAttr("disabled");
    $('#upfieldkey_' + fid).removeAttr("disabled");
    $('#upfieldtype_' + fid).removeAttr("disabled");
    $('#upfieldregex_' + fid).removeAttr("disabled");
    $('#upfieldvalid_' + fid).removeAttr("disabled");
    $('#upfield_' +fid).removeAttr("disabled");
    $('#upfieldenb_'+ fid).prop("disabled", "true");    
        
    }
    
    function servcplanupdEnable(spid){        
    $('#servc_plan_sname_'+spid).removeAttr("disabled");
    $('#servc_plan_key_'+spid).removeAttr("disabled");
    $('#servc_plan_name_'+spid).removeAttr("disabled");
    $('#servc_plan_status_'+spid).removeAttr("disabled");
    $('#servc_plan_settamt_'+spid).removeAttr("disabled");
    $('#servc_plan_rentamt_'+spid).removeAttr("disabled");
    $('#servc_plan_distcomm_'+spid).removeAttr("disabled");
    $('#upservc_plan_'+spid).removeAttr("disabled");
    $('#upservc_plan_enb'+spid).prop("disabled","true");
    
    
    // For plan fields
    
    $('#upplankey_' + spid).removeAttr("disabled");
    $('#upplanname_' + spid).removeAttr("disabled");
    $('#upplanstatus_' + spid).removeAttr("disabled");
    $('#upsettlementamt_' + spid).removeAttr("disabled");
    $('#uprentalamt_' + spid).removeAttr("disabled");
    $('#upplanenb_' + spid).removeAttr("disabled");    
//  $('#upplan_'+ spid).prop("disabled", "true");
    }
    
    function produpdEnable(pid){
    $('#upprodname_'+pid).removeAttr("disabled");
    $('#upprodmin_'+pid).removeAttr("disabled");
    $('#upprodmax_'+pid).removeAttr("disabled");
    $('#upprodStatus_'+pid).removeAttr("disabled");
    $('#upprodGST_'+pid).removeAttr("disabled");
    $('#upprodEarning_'+pid).removeAttr("disabled");
    $('#upprodtype_'+pid).removeAttr("disabled");
    $('#upprodearningflag_'+pid).removeAttr("disabled");
    $('#upprodtds_'+pid).removeAttr("disabled");
    $('#upprodemargin_'+pid).removeAttr("disabled");
    
    $('#upsserv_'+pid).removeAttr("disabled");
    $('#upserv_'+pid).prop("disabled","true");        
    }
    
    function servcPartnerupdEnable(spaid){
        $('#upservPKey_'+spaid).removeAttr("disabled");
        $('#upservPName_'+spaid).removeAttr("disabled");
        $('#upservPSalt_'+spaid).removeAttr("disabled");
        $('#upservPCallback_'+spaid).removeAttr("disabled");
        $('#upservPRedirect_'+spaid).removeAttr("disabled");
        $('#upservPParams_'+spaid).removeAttr("disabled");
        $('#upservPSecKey_'+spaid).removeAttr("disabled");
        $('#upservpartner_'+spaid).removeAttr("disabled");
        $('#upservpartner_enb_'+spaid).prop("disabled","true");        

    }
   
    function servcVendorupdEnable(vid){
        $('#upvendorname_'+vid).removeAttr("disabled");
        $('#upvservcpart_'+vid).removeAttr("disabled");

        $('#upvendor_'+vid).removeAttr("disabled");
        $('#upvendor_enb_'+vid).prop("disabled","true");        
        
    }    
    
    //function for entering only number
    
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 37 || charCode > 57))
            return false;

         return true;
      }
    
    
    
    function createService(){
        var servcName       = $('#servcName').val();
        var servcType       = $('#servcType').val();
        var servcRegist     = $('#servcRegist').val();
        var servcPartner    = $('#servcPartner').val();
        var servcInc        = $('#servcInc').val();
        var servcIncservice = $('#servcIncservice').val();
        var servcGst        = $('#servcGST').val();

    if ($('#servcName').val() == "") {
        alert("Please Enter Name");
    } else if ($('servcType').val() == "") {
        alert("Please Select Type");
    } else if ($('#servcRegist').val() == "") {
        alert("Please Select Registration");
    } else if ($('#servcInc').val() == "") {
        alert("Please Select Incentive type");
    }
    else {        
    $.ajax({
        type: "POST",
        url: '/serviceintegration/servicesInsert',
        dataType: "json",
        data: {servcName: servcName, servcType: servcType, servcRegist: servcRegist,servcPartner : servcPartner,servcGST : servcGst,servcInc: servcInc,servcIncservice:servcIncservice},

        success: function (data) {
            alert("Service Inserted  successfully");
            location.reload();
        }

    });
    }    
        
    }
    function servcdetailsUpdate(id){
        var servStatus = 0;
        var servType = $('#upservcType_'+id).val();
        var servRegist = $('#upservcRegist_'+id).val();
        var servcPartner    = $('#upservcPartner_'+id).val();
        var servcInc        = $('#upservcInc_'+id).val();
        var servcIncservice = $('#upservcIncservice_'+id).val();
        var servcGst        = $('#upservcGst_'+id).val();
        
      if($('#upservStatus_'+id).is(":checked"))
        {
            servStatus = 1;
        }        
        $.ajax({
            type : "POST",
            url  : '/serviceintegration/servicesForm',
            dataType : "json",            
            data : {upservID : id ,servStatus : servStatus,servType : servType,servRegist : servRegist,
                    servcPartner : servcPartner ,servcInc : servcInc,servcIncservice : servcIncservice,servcGst : servcGst},
           
           success : function(data){
               if(data.status == 'success')
                {
                    alert(data.msg);                         
                }else{
                    alert(data.description);
                }
          }

        });
    }
    
    function getServiceFields(service_id){        
        $('.operations-modal').attr('id','operation_field_'+service_id);        
        $('#operation_field_'+service_id).find('.modal-title').html('Service Fields');
        var service_fields_template = '<button  style="float:left;"class="btn btn-primary" id="addFieldbtn" name="addFieldbtn" data-toggle="modal" data-target="#addFieldModal" onclick="showAddField('+ service_id + ')" >Add fields</button><button  style="float:left;"class="btn btn-primary" id="addKYCbtn" name="addKYCbtn" data-toggle="modal" data-target="#KYCmappingModal" onclick="showKYCmapping('+ service_id + ')" >KYC Mapping</button> <br><br>';
        //$('#addFieldbtn').attr("data-id",service_id);
        
        
    $.ajax({
        type: "POST",
        url: '/serviceintegration/getFieldData',
        dataType: "json",
        data: {id: service_id},
        success: function (data) {            
            var service_fields_template_head = '<table id="fieldtb" name="fieldtb">  <thead> <th>  </th> <th> Label  </th> <th> Key  </th> <th> Type </th> <th> Regex </th> <th> Validation </th> <th> Default </th> <th> Action </th> </thead><tbody>';
            var flddata = data; 
            var service_fields_template_body = '';
            
            
            if(flddata.length > 0){                            
            $.each(flddata, function (index,field) {                
                
        var tr = '<tr>';
        var fieldId = '<input type="text" id="field_id" name="field_id" hidden="true" value="'+ field["sf"]["id"] +'" disabled required/>';  
        var fieldLabel = '<input type="text" id="upfieldlab_'+ field["sf"]["id"] +'" name="upfieldlab" value="'+ field["sf"]["label"] +'" disabled/>';  
        var fieldKey   = '<input type="text" id="upfieldkey_'+ field["sf"]["id"] +'" name="upfieldkey" value="'+ field["sf"]["key"] +'" disabled/>';  

         var checkbox_selected  = '';
         var text_selected      = '';
         var dropdown_selected  = '';
         var label_selected     = '';
         if(field["sf"]["type"] == 'checkbox'){
          checkbox_selected = 'selected';
         }else if(field["sf"]["type"] == 'text'){
             text_selected = 'selected';
         }else if(field["sf"]["type"] == 'dropdown'){
             dropdown_selected = 'selected';
         }else if(field["sf"]["type"] == 'label'){
             label_selected = 'selected';
         }
                         
        var fieldtype  ='<select id="upfieldtype_'+ field["sf"]["id"] +'" name="upfieldtype" disabled\n\
                   <option>Select</option> \n\
                   <option value="checkbox" ' + checkbox_selected + '>Check Box</option>\n\
                  <option value="text" '+ text_selected +'>Text</option>\n\
                  <option value="dropdown" '+ dropdown_selected +'>Dropdown</option>\n\
                  <option value="label" '+ label_selected +'>Label</option>\n\
                   </select>';                            
         var fieldRegex = '<input type="text" id="upfieldregex_'+ field["sf"]["id"] +'" name="upfieldregex" value="'+ field["sf"]["regex"] +'" disabled />';
         
         var readonly_selected = '';
         var require_selected = '';
         var unique_selected = '';
         if(field["sf"]["validation"] == 'readonly'){
          readonly_selected = 'selected';
         }else if(field["sf"]["validation"] == 'require'){
             require_selected = 'selected';
         }else if(field["sf"]["validation"] == 'unique'){
             unique_selected = 'selected';
         }
         
         
         var fieldValid  = '<select id="upfieldvalid_'+ field["sf"]["id"] +'" name="upfieldvalid" disabled>\n\                            \n\
                            <option value="readonly" '+readonly_selected+'>Read Only</option>\n\
                            <option value="require" '+require_selected+'>Required</option>\n\
                            <option value="unique" '+unique_selected+'>Unique</option>\n\
                             </select>';                                          

         var fieldDefault = '<textarea id="upfielddef_'+ field["sf"]["id"] +'" name="upfielddef"  disabled>' +field["sf"]["default_values"]+'</textarea>';
         var fieldUpdateBtn = '<button type="button" class="btn btn-primary" id="upfieldenb_'+ field["sf"]["id"] +'" name="upfieldenb" onclick="fldupdEnable('+ field["sf"]["id"] +')">Update</button> <button type="submit" class="btn btn-primary" id="upfield_'+ field["sf"]["id"] +'" name="upfield" disabled="true" onclick="UpdServcfield('+field["sf"]["id"]+')">Submit</button>';
         tr += '<td>' + fieldId + '</td>';  
         tr += '<td>' + fieldLabel + '</td>';  
         tr += '<td>' + fieldKey + '</td>';  
         tr += '<td>' + fieldtype + '</td>';  
         tr += '<td>' + fieldRegex + '</td>';  
         tr += '<td>' + fieldValid + '</td>';  
         tr += '<td>' + fieldDefault + '</td>';  
         tr += '<td>' + fieldUpdateBtn + '</td>';  
            tr +='</tr>';
         service_fields_template_body += tr ;

            });
            service_fields_template += '</tbody>';
            $('#operation_field_'+service_id).find('.modal-body').html(service_fields_template+service_fields_template_head+service_fields_template_body);
        } else {
            service_fields_template += '<tr>No records found</tr>';
            service_fields_template += '</tbody>';
            $('#operation_field_'+service_id).find('.modal-body').html(service_fields_template);
        }
            
             
             $('#operation_field_'+service_id).find('.modal-dialog').css({"width":"1020px","height":"2000px"});
            $('#operation_field_'+service_id).find('.modal-dialog').css({"width":"1020px","height":"2000px"});
            $('#operation_field_'+service_id).modal('toggle');
            $("#addFieldModal").val( service_id );
            

        },
        failure: function (data) {
            alert('failed');
        }

    });   
                       
    }
    function getServicePlans(service_id){
        $('.operations-modal').attr('id','operation_plan_'+service_id);
        $('#operation_plan_'+service_id).find('.modal-title').html('Service Plans');
        var service_plans_template = '<button  style="float:left;"class="btn-primary" id="addPlanbtn" name="addPlansbtn" data-toggle="modal" data-target="#addFieldModal" onclick="showAddField('+ service_id + ')" >Add Plan</button> <br><br>';
          $.ajax({
        type: "POST",
        url: '/serviceintegration/servicesPlans',
        dataType: "json",
        data: {id: service_id},
        success: function (data) {
            
            var service_plan_template_head = '<table id="plantb" name="plantb">  <thead><th> </th> <th> id </th>  <th>Plan Key  </th> <th> Name </th> <th> Status </th> <th> Settlement Amount </th> <th> Rental Amount </th> <th> Action </th> </thead><tbody>';
//            var plandata = JSON.parse(data); 
            var plandata = data; 
            var service_plans_template_body = '';
            
            if(plandata.length > 0){                            
            $.each(plandata, function (index,plan) {                
                var i =1;
         var tr = '<tr>';
         
         var planId   = '<input type="text" id="upplanid_'+ plan["sp"]["id"] +'" name="upplanid_" value="'+ plan["sp"]["id"] +'" hidden />';  
         var planKey   = '<input type="text" id="upplankey_'+ plan["sp"]["id"] +'" name="upplankey" value="'+ plan["sp"]["plan_key"] +'" disabled/>';  
         var planname  =  '<input type="text" id="upplanname_'+ plan["sp"]["id"] +'" name="upplanname" value="'+ plan["sp"]["plan_name"] +'" disabled />'; 
         var planstatus = '<input type="text" id="upplanstatus_'+ plan["sp"]["id"] +'" name="upplanstatus" value="'+ plan["sp"]["is_active"] +'" disabled />';
         var settlementamt  =  '<input type="text" id="upsettlementamt_'+ plan["sp"]["id"] +'" name="upsettlementamt" value="'+ plan["sp"]["setup_amt"] +'" disabled />';           
         var rentalamount = '<input type="text" id="uprentalamt_'+ plan["sp"]["id"] +'" name="uprentalamt" value="'+ plan["sp"]["rental_amt"] +'" disabled/>';
         var planUpdateBtn = '<button type="button" class="btn btn-primary" id="upplanenb_'+ plan["sp"]["id"] +'" name="upplanenb" onclick="servcplanupdEnable('+ plan["sp"]["id"] +')">Update</button> <button type="submit" class="btn btn-primary" id="upplan_'+ plan["sp"]["id"] +'" name="upfield"  onclick="servcplansUpdate('+plan["sp"]["id"]+')">Submit</button>';
         tr += '<td>' + planId + '</td>';  
         tr += '<td>' + (index + i) + '</td>';  
         tr += '<td>' + planKey + '</td>';  
         tr += '<td>' + planname + '</td>';  
         tr += '<td>' + planstatus + '</td>';  
         tr += '<td>' + settlementamt + '</td>';  
         tr += '<td>' + rentalamount + '</td>';  
         tr += '<td>' + planUpdateBtn + '</td>';  
         tr +='</tr>';
         i++ ;
         service_plans_template_body += tr ;

            });
            $('#operation_plan_'+service_id).find('.modal-body').html(service_plans_template+service_plan_template_head+service_plans_template_body);
        } else {
            service_plans_template += '<tr>No records found</tr>';
            $('#operation_plan_'+service_id).find('.modal-body').html(service_plans_template);
        }
            service_plans_template += '</tbody>';
        },
        failure: function (data) {
            alert('failed');
        }

    });   
            $('#operation_plan_'+service_id).find('.modal-body').html(service_plans_template);
            $('#operation_plan_'+service_id).modal('toggle');
            $("#addFieldModal").val( service_id );
            $('#operation_plan_'+service_id).find('.modal-dialog').css({"width":"1020px","height":"2000px"});
            $('#operation_plan_'+service_id).find('.modal-dialog').css({"width":"1020px","height":"2000px"});

        
        
    }
    
    function InsServcfield(){              
        if($('#fieldkey').val() == '' || $('#fieldlab').val() == ''){
        
            alert("Please Enter Mandatory Field");
        }
        else {
    
        var formData = $('form#fieldform').serializeArray();
        console.log(formData);
        console.log("ajay");
        $.ajax({                
                url  : '/serviceintegration/serviceFields',                
                type : 'post',
                dataType: 'json',            
            success: function (data) {              
                alert("Fields Inserted Successfully");
                location.reload();
            },
            data: formData
        });}
    }
    
    function UpdServcfield(fid){        
        var upfield_key   = $('#upfieldkey_'+ fid).val();
        var upfield_lab   = $('#upfieldlab_'+fid).val();
        var upfield_type  = $('#upfieldtype_'+fid).val();
        var upfield_regex = $('#upfieldregex_'+fid).val();
        var upfield_valid = $('#upfieldvalid_'+fid).val();
        var upfield_def   = $('#upfielddef_'+fid).val();        
        $.ajax({
                type : "POST",
                url  : '/serviceintegration/updserviceFields',
                dataType : "json",
                data : {upfid : fid, upfield_key : upfield_key, upfield_lab : upfield_lab, upfield_type : upfield_type,
                    upfield_regex : upfield_regex,upfield_valid : upfield_valid,upfield_def : upfield_def},
                        success:function(data){
                         alert("Changes added Successfully");
                            $('#upfielddef_' + fid).attr("disabled","true");
                            $('#upfieldlab_' + fid).attr("disabled","true");
                            $('#upfieldkey_' + fid).attr("disabled","true");
                            $('#upfieldtype_' + fid).attr("disabled","true");
                            $('#upfieldregex_' + fid).attr("disabled","true");
                            $('#upfieldvalid_' + fid).attr("disabled","true");
                            $('#upfieldenb_' +fid).removeAttr("disabled");
                            $('#upfield_'+ fid).attr("disabled", "true");
                        },
                        failure:function(data){
                            alert('failed');
                        }
        });
    }

 

function showAddField(service_id){
    $("#fieldval").val(service_id);
    $('#operation_field_'+service_id).modal('toggle');
}

function showProductField(service_id){
    $("#product_srvcid").val(service_id);
    $('#operation_field_'+service_id).modal('toggle');
}

function showKYCmapping(service_id){
    $("#kycserviceval").val(service_id);
    $('#operation_field_'+service_id).modal('toggle');
    
    $.ajax({
    type : "POST",
                url  : '/serviceintegration/getKYCData',
                dataType : "json",
                data : {kycid : service_id},
                
                        success:function(data){
                        var kyc_table_head = '<table id="kyctb" name="kyctb">  <thead><th> Service </th> <th>  Document  </th> <th> Access  </th> <th> Action </th> </thead> <tbody>';
                          var kycdata = data;                           
                          var kyc_table_body = '';
                          var kyc_fields_template = '';
                          if(kycdata !== "") {                                      
            $.each(kycdata, function(i, item) {
            
            var tr = '<tr>';
             var  service_name =  item["services"]["name"];
             var label_name   = item["il"]["label"];
             var has_access   = item["ilsa"]["has_access"];
             var kycdelete    = '<button type="submit" class="btn btn-danger" id="delkycfields_'+ item["ilsa"]["label_id"] +'" name="delkycfields" onclick="delKYCfield(' + item["ilsa"]["label_id"]  + ',' + service_id +')">Delete</button>';

                        tr += '<td>' + service_name + '</td>';
                        tr += '<td>' + label_name + '</td>';
                        tr += '<td>' + has_access + '</td>';
                        tr += '<td>' + kycdelete + '</td>';  
                       tr += '</tr>';
                       kyc_table_body += tr;
                       
                   });
                   
                $('#KYCmappingModal').find('.kycservicebody').html(kyc_table_head + kyc_table_body );
            } else {
                kyc_fields_template += '<tr>No records found</tr>';
                $('#KYCmappingModal').find('.kycservicebody').html(kyc_fields_template);
            }
            kyc_fields_template += '</tbody>';
        },           
                        
           failure: function(data) {
            alert('failed');
        }
    });
    
}

function addKYCdetail(){    
    var kycid   = $('#kycserviceval').val();
    var kycdata = $('#kycfield').val();    
    $.ajax({
    type : "POST",
                url  : '/serviceintegration/insKYCData',
                dataType : "json",
                data : {kycid : kycid,kycdata : kycdata},
                        success:function(data){
                            alert("KYC Detail added");
                        },
           failure: function (data) {
            alert('failed');
        }
    });
    
}
    
    function delKYCfield(kycservid,kyclabel){        
        if (confirm("Are you really want to delete ?")) {     
    
    $.ajax({
        type: "POST",
        url: '/serviceintegration/setKYCData',
        dataType: "json",
        data: {kycdservid: kycservid, kycddata: kyclabel},
        success: function (data) {
            alert("Entry deleted successfully !!!!");
            
        },
        failure: function (data) {
            alert('failed');
        }
    });
     } else {
        alert("Something went wrong");
    }
           
    }
    
    
//    function getServiceProducts(service_id){           
//           
//        $('.operations-modal').attr('id','operation_field_'+service_id);
//        $('#operation_field_'+service_id).find('.modal-title').html('Products Fields');
//        var service_fields_template = '<button  style="float:left;"class="btn-primary" id="addProductbtn" name="addProductbtn" data-toggle="modal" data-target="#addProductModal" onclick="showProductField('+ service_id + ')" >Add Products</button><br><br>';
//        $('#addFieldbtn').attr("data-id",service_id);
//        
//        
//    $.ajax({
//        type: "POST",
//        url: '/serviceintegration/getFieldData',
//        dataType: "json",
//        data: {id: service_id},
//        success: function (data) {
//            
//            var service_fields_template_head = '<table id="fieldtb" name="fieldtb">  <thead> <th>  </th> <th> Label  </th> <th> Key  </th> <th> Type </th> <th> Regex </th> <th> Validation </th> <th> Default </th> <th> Action </th> </thead><tbody>';
//            var flddata = data; 
//            var service_fields_template_body = '';
//            
//            if(flddata !== ''){                            
//            $.each(flddata, function (index,field) {                
//                
//        var tr = '<tr>';
//         var fieldId = '<input type="text" id="field_id" name="field_id" hidden="true" value="'+ field["sf"]["id"] +'" disabled />';  
//         var fieldLabel = '<input type="text" id="upfieldlab_'+ field["sf"]["id"] +'" name="upfieldlab" value="'+ field["sf"]["label"] +'" disabled/>';  
//         var fieldKey   = '<input type="text" id="upfieldkey_'+ field["sf"]["id"] +'" name="upfieldkey" value="'+ field["sf"]["key"] +'" disabled/>';  
//         var fieldtype  =  '<input type="text" id="upfieldtype_'+ field["sf"]["id"] +'" name="upfieldtype" value="'+ field["sf"]["type"] +'" disabled />'; 
//         var fieldRegex = '<input type="text" id="upfieldregex_'+ field["sf"]["id"] +'" name="upfieldregex" value="'+ field["sf"]["regex"] +'" disabled />';
//         var fieldValid  =  '<input type="text" id="upfieldvalid_'+ field["sf"]["id"] +'" name="upfieldvalid" value="'+ field["sf"]["validation"] +'" disabled />';           
//         var fieldDefault = '<input type="text" id="upfielddef_'+ field["sf"]["id"] +'" name="upfielddef" value="'+ field["sf"]["default_values"] +'" disabled/>';
//         var fieldUpdateBtn = '<button type="button" class="btn btn-primary" id="upfieldenb_'+ field["sf"]["id"] +'" name="upfieldenb" onclick="fldupdEnable('+ field["sf"]["id"] +')">Update</button> <button type="submit" class="btn btn-primary" id="upfield_'+ field["sf"]["id"] +'" name="upfield" disabled="true" onclick="UpdServcfield('+field["sf"]["id"]+')">Submit</button>';
//         tr += '<td>' + fieldId + '</td>';  
//         tr += '<td>' + fieldLabel + '</td>';  
//         tr += '<td>' + fieldKey + '</td>';  
//         tr += '<td>' + fieldtype + '</td>';  
//         tr += '<td>' + fieldRegex + '</td>';  
//         tr += '<td>' + fieldValid + '</td>';  
//         tr += '<td>' + fieldDefault + '</td>';  
//         tr += '<td>' + fieldUpdateBtn + '</td>';  
//            tr +='</tr>';
//         service_fields_template_body += tr ;
//
//            });
//            $('#operation_field_'+service_id).find('.modal-body').html(service_fields_template+service_fields_template_head+service_fields_template_body);
//        } else {
//            service_fields_template += '<tr>No records found</tr>';
//            $('#operation_field_'+service_id).find('.modal-body').html(service_fields_template);
//        }
//            service_fields_template += '</tbody>';
//        },
//        failure: function (data) {
//            alert('failed');
//        }
//
//    });   
//            $('#operation_field_'+service_id).find('.modal-body').html(service_fields_template);
//            $('#operation_field_'+service_id).modal('toggle');
//            $("#addFieldModal").val( service_id );
//            $('#operation_field_'+service_id).find('.modal-dialog').css({"width":"1020px","height":"2000px"});
//            $('#operation_field_'+service_id).find('.modal-dialog').css({"width":"1020px","height":"2000px"});    
//    }
    
    
    
    function InsProduct(){
     var servcId = $('#product_srvcid').val();
     var productname = $('#product_name').val();   
     
         $.ajax({
        type: "POST",
        url: '/serviceintegration/setProductData',
        dataType: "json",
        data: {prodservid: servcId, productname: productname},
        success: function (data) {
            alert("Product created successfully !!!!");
            location.reload();

        },
        failure: function (data) {
            alert('failed');
        }
    });          
    }
    
    
   
    // For Plans Update //
    
    function servcplansUpdate(spid){    
    var planstatus = 0;
    var serviceid  = $('#servc_plan_sname_' + spid).val();
    var plankey    = $('#servc_plan_key_' + spid).val();
    var plan       = $('#servc_plan_name_' + spid).val();
//    var planstatus = $('#servc_plan_status_' + spid).val();
    var settleamt  = $('#servc_plan_settamt_' + spid).val();
    var rentamt    = $('#servc_plan_rentamt_' + spid).val();    
    var distcomm   = $('#servc_plan_distcomm_'+ spid).val();    
          if($('#servc_plan_status_'+spid).is(":checked"))
        {
            planstatus = 1;
        }
                
    $.ajax({
        type: "POST",
        url: '/serviceintegration/updPlanDetails',
        dataType: "json",
        data: {id : spid, serviceid: serviceid, plankey: plankey, plan : plan, planstatus : planstatus, settleamt : settleamt, rentamt : rentamt,distcomm : distcomm },
        success: function (data) {           
            alert("Plans Updated successfully !!!!");
            location.reload();

        },
        failure: function (data) {
            alert('failed');
        }              
            
    });
    
    }
    
   function produpdUpdate(pid){    
    var prodstatus  = 0; 
    var prodname    = $('#upprodname_' + pid).val();
    var prodmin     = $('#upprodmin_' + pid).val();
    var prodmax     = $('#upprodmax_' + pid).val();        
    var prodearn    = $('#upprodEarning_' + pid).val();    
    var prodtype    = $('#upprodtype_' + pid).val();
    var prodearningf= $('#upprodearningflag_' + pid).val();
    var prodtds     = $('#upprodtds_' + pid).val();
    var prodmargin  = $('#upprodemargin_'+pid).val();
      if($('#upprodStatus_'+pid).is(":checked"))
        {
            prodstatus = 1;
        }           
    if(prodname  == ''){
        alert("Name cannot be empty");
    }
    else if(prodmin  == ''){
        alert("Minimum amount cannot be empty");
    }    
    else if(prodmax  == ''){
        alert("Maximum cannot be empty");
    }
    else if(prodtds  == ''){
        alert("Tds cannot be empty");
    }
    else if(prodmargin  == ''){
        alert("Margin cannot be empty");
    }
    else {
    $.ajax({
        type: "POST",
        url: '/serviceintegration/updProductsDetails',
        dataType: "json",
        data: {id : pid, upprodname: prodname, upprodmin: prodmin, upprodmax : prodmax, upprodstatus : prodstatus, upprodearningf : prodearningf ,upprodtds : prodtds,upprodmargin:prodmargin,
                upprodearn : prodearn, upprodtype : prodtype},
        success: function (data) {
            alert("Products Updated successfully !!!!");
            location.reload();

        },
        failure: function (data) {
            alert('failed');
        }              
            
    });
    
    }
}
    function produpdDelete(id){
        
    if (confirm("Are you really want to delete ?")) {

        $.ajax({
            type: "POST",
            url: '/serviceintegration/updProductsDetails',
            dataType: "json",
            data: {delprodid: id},
            success: function (data) {
                alert("Entry deleted successfully !!!!");
                location.reload();
            },
            failure: function (data) {
                alert('failed');
            }
        });
    } else {
        alert("Something went wrong");
    }

   
    }
    
    function servcPartnerUpdate(spaid){
    var spkey       = $('#upservPKey_' + spaid).val();
    var spskey      = $('#upservPSecKey_' + spaid).val();
    var spname      = $('#upservPName_' + spaid).val();
    var spsalt      = $('#upservPSalt_' + spaid).val();    
    var spcallback  = $('#upservPCallback_' + spaid).val();
    var spparams    = $('#upservPParams_' + spaid).val();    
    var spredirect  = $('#upservPRedirect_' + spaid).val();   
      if($('#upprodStatus_'+spaid).is(":checked"))
        {
            prodstatus = 1;
        }           
    if($('#upservPKey_' + spaid).val() == "" ){ 
            alert("Please Enter Key");}
  else if($('#upservPName_' + spaid).val() == ""){
            alert("Please Enter Name");
        }
      else if($('#upservPSalt_' + spaid).val() == "" ){
          alert("Please Enter Salt");
        }
    else {
    $.ajax({
        type: "POST",
        url: '/serviceintegration/updServicePartner',
        dataType: "json",
        data: {id : spaid, key : spkey,name: spname, salt : spsalt, callback : spcallback, params : spparams, redirect : spredirect,seckey : spskey},
        success: function (data) {
            alert("Service partner Updated successfully !!!!");
            location.reload();

        },
        failure: function (data) {
            alert('failed');
        }              
            
    });
    }   
    }

    function servcVendorUpdate(vid){
        
    var name       = $('#upvendorname_' + vid).val();
    var partnerid      = $('#upvservcpart_' + vid).val();
    
      
    $.ajax({
        type: "POST",
        url: '/serviceintegration/updVendor',
        dataType: "json",
        data: {id : vid, name : name,partnerid: partnerid},
        success: function (data) {
            alert("Vendor Updated successfully !!!!");
            location.reload();

        },
        failure: function (data) {
            alert('failed');
        }              
            
    });
        
    }
    
    
    
       function addInput(divName) {

     var i=1;
     var p='';
     
     $(document).on('click','.removefield',function(event){
         $(this).parent().parent().remove(".row")
     });

    
         
             var new_field_html = '<div class="row col-md-12 targetrange"><div class="col-md-3"><label for="prodname">Name :</label> <input type="text" required class="form-control" name="products['+i+'][prodname]" ></div><div class="col-md-3"><label for="prodmin">Min :</label><input type="text" required class="form-control" name="products['+i+'][prodmin]" ></div><div class="col-md-3"><label for="prodmax">Max :</label><input type="text" required class="form-control" name="products['+i+'][prodmax]"></div><div class="col-md-3"><label for="prodgst">Gst :</label><select id="prodgst" name="products['+i+'][prodgst]" class="form-control"><option>Select</option><option value = "0">Inclusive</option><option value = "1">Exclusive</option></select></div>\n\
                                                         <div class="col-md-3"><label for="prodearningtype">Earning Type :</label><select id="prodearningtype" name="products['+i+'][prodearningtype]" class="form-control"><option>Select</option><option value = "0">Discount</option><option value = "1">Commission</option><option value = "2">Service Charge</option></select></div>\n\
                                                          <div class="col-md-3"><label for="prodtype">Type :</label><select id="prodtype" name="products['+i+'][prodtype]" class="form-control"><option>Select</option>\n\
                                                            <option value = "0">P2P</option> <option value = "1">P2A</option></select><br></div><input type="text" id="serviceid" name=products['+i+'][serviceid]" value="<?php echo $service_id;?>" hidden="true">\n\
                                                            <input type="text" id="parentid" name=products['+i+'][parentid]" value="' + (parseInt(prodif) + parseInt(i)) +'"  hidden="true">\n\
                                                            <div class="col-md-3"><button style="margin:22px 0px 0px 0px;"class="btn btn-primary removeproduct">Remove Product</button></div></div>';
             i++;
                    
         $('div .fieldcontainer').append(new_field_html);
    
    
     

//    if (counter == limit) {
//        alert("You have reached the limit of adding " + counter + " inputs");
//    } else {
//        var newdiv = document.createElement('div');
//        newdiv.innerHTML = '<div class="form-group"> <input type="text" name ="fields[' + counter + '][fieldval]" id="fieldval" class="form-control" hidden="true"> <div class="col-lg-3"> <label for="fieldkey" style="margin-right:58px;"> Field Key :</label> <input type="text" name="fields['+ counter +'][fieldkey]" id="fieldkey" class="form-control"></div> <div class="col-lg-3"><label for="fieldlab"> Field Label :</label> \n\
//                               <input type="text" id="fieldlab" name="fields['+ counter +'][fieldlab]" class="form-control"></div> <div class="col-lg-3"><label for="fieldtype">Type :</label> <select  id="fieldtype" name="fields['+ counter +'][fieldtype]" class="form-control"> <option>Select</option> <option value="checkbox">Checkbox</option> <option value="text">Text</option><option value="dropdown">Dropdown</option></select></div>\n\
//                                <div class="col-lg-3"> <label for="fieldregex"> Regular Expression:</label> <input type="text" id="fieldregex" name="fields[' + counter + '][fieldregex]" class="form-control"> </div> <div class="col-lg-3"><label for="fieldvalid"> Validation :</label> <input type="text" id="fieldvalid" name="fields['+ counter +'][fieldvalid]"> <div class="col-lg-3"><label for="fielddef"> Default Value :</label> <input type="text" id="fielddef" name="fields['+ counter +'][fielddef]" </div> \n\
//                                <div class="col-md-3"><button style="margin:22px 0px 0px 0px;"class="btn btn-primary removeproduct">Remove Field</button><br><br></div>\n\
//                                ';
//        $('div'.divName).append(newdiv);
//        counter++;
//    }
}
    
    
    function addInputProduct(divName){

    if (counter == limit) {
        alert("You have reached the limit of adding " + counter + " inputs");
    } else {
        var newdiv = document.createElement('div');
        newdiv.innerHTML = "<div class='form-group'>  <label for='product_name' style='margin-right:58px;'> Name :</label> <input type='text' name='product_name' id='product_name'></div> ";
        document.getElementById(divName).appendChild(newdiv);
        counter++;
    }
        
    }
    
    
       
 
