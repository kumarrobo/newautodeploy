var HOST= "http://"+window.location.hostname+"/";

$(document).ready(function()
{
//    $('input[name="label_id_12_1"]').datepicker({
    $('.datepicker').datepicker({
//        format: "yyyy-mm-dd",
        format: "mm/dd/yyyy",
        //startDate: "-365d",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    
    //format date in mm/dd/yyyy format
    var date = ($('input[name="label_id_12_1"]').val()).length ? new Date($('input[name="label_id_12_1"]').val()) : '';
    var formatted_date = '';
    if(date){
        formatted_date = ('0'+(date.getMonth() + 1)).slice(-2) + '/' + ('0'+(date.getDate())).slice(-2) + '/' +  date.getFullYear();
    } 
    $('input[name="label_id_12_1"]').val(formatted_date);
  
    $('input[type=checkbox][name="checkall_labels"]').on('click',function(){

        $('#textualinfoForm_'+$(this).val()).find('input[type=checkbox][name="label_ids[]"]').prop('checked',$(this).prop('checked'));

    });
});

  function saveComment(){
        var pay1_comment = $.trim($('#pay1comment').val());  
        if(pay1_comment=="")
        {
            alert("Enter comments");
            return false;
        }
        var label_info = {};
        
        var user_id = $('#user_id').val();
        var label_id = $('#label_id').val();
        var label_type = $('#label_type').val();
        var curr_desc = $('#curr_desc').val();
        var prev_desc = $('#prev_desc').val();
        var section_id = $('#section_id').val();
        label_info[label_id] = {};
        label_info[label_id]['label_id'] = label_id;
        label_info[label_id]['label_type'] = label_type;
        label_info[label_id]['curr_desc'] = curr_desc;
        label_info[label_id]['prev_desc'] = prev_desc;
        var submit_button = $('#rejectdocsmodal_'+label_id+'_'+section_id+' #save_comments').html();
        var reject_doc_html = $('#rejectdocsmodal_'+label_id+'_'+section_id+' #reject_docs_div_'+label_id+'_'+section_id).html();
        var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
      
        $.ajax({
          url : "/docmanagement/verifyUserDocs",
          type: "POST",
          dataType:'json',
          data : {user_id:user_id,label_id:label_id,pay1_status:'2',pay1_comment:pay1_comment,label_type:label_type,curr_desc:curr_desc,prev_desc:prev_desc,label_info:label_info},
          beforeSend: function () {
//              $('#save_comments').html(loading_gif);
          },
          success:function(res){
              if(res.status=='success'){
                  if(label_type == 1){
                    $('#doc_status_'+label_id+'_'+section_id).html('status: <b>REJECTED</b>');

                    var doc_upload_html = '<label for="document">Upload</label>';
                        doc_upload_html +=     '<input type="file" name="document[]" id="document_'+label_id+'_'+section_id+'" accept="image/*" multiple="multiple" alt="Upload Docs"/>';
                        doc_upload_html +=   '<div class="submit-button" id="btn_upload_documents_'+label_id+'_'+section_id+'">';
                        doc_upload_html +=       '<input class="btn btn-default btn-primary btn-sm" type="button" value="Upload" onclick="uploadDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                        doc_upload_html +=   '</div>';
                    $('#upload_docs_div_'+label_id+'_'+section_id).html(doc_upload_html);

                    $('#reject_docs_div_'+label_id+'_'+section_id).html('');

                    var approve_html = '<a id="acceptdocs_'+label_id+ '_'+section_id+'" class="accept" onclick="approveDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                    approve_html += '<i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>';
                    approve_html += '</a>';
                    $('#accept_docs_div_'+label_id+'_'+section_id).html(approve_html);
                  }else if(label_type == 2){
                      if(res.data[label_id]['input_type'] == 'dropdown'){                          
                          $('select[name="label_id_'+label_id+'_'+section_id+'"]').val(res.data[label_id]['curr_desc']).attr("selected","selected");
                      }else{
                          $('#label_id_'+label_id+'_'+section_id).val(res.data[label_id]['curr_desc']);
                      }
                        $('#last_ver_label_id_'+label_id+'_'+section_id).html('');
                        $('#reject_docs_div_'+label_id+'_'+section_id).html('');
                    }
//                  $('#save_comments').html(submit_button);
                  $('#rejectdocsmodal_'+label_id+'_'+section_id).modal('hide');
                  alert(res.msg);
              }
              else
              {
                  $('#reject_docs_div_'+label_id+'_'+section_id).html(reject_doc_html);
//                  $('#save_comments').html(submit_button);
                  alert(res.msg);
              }
              
            }
        });
    }

function uploadDocs(label_id,user_id,label_type,section_id)
{
//    var user_id = $('#user_id').val();
    var form = $("#uploadForm_"+label_id+"_"+section_id)[0];
    var form_data = new FormData(form); 
    var submit_button = $('#btn_upload_documents_'+label_id+'_'+section_id).html();
    var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
    
    $.ajax({
                url : "/docmanagement/uploadDocs",
                type: "POST",
                data : form_data,
                processData: false,
                contentType: false,
                dataType:'json',
                beforeSend: function () {
                    $('#btn_upload_documents_'+label_id+'_'+section_id).html(loading_gif);
                },
                success:function(res){                    
                    if((res.status=='success'))
                    {                        
                        var verify_docs_div = '<div id="reject_docs_div_'+label_id+'_'+section_id+'">';
                            verify_docs_div += '<a id="rejectdocs_'+label_id+ '_'+section_id+'" class="reject" onclick="rejectDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                            verify_docs_div += '<i style="cursor: pointer;" title="click to reject docs" class="fa fa-times" aria-hidden="true"></i>';
                            verify_docs_div += '</a></div>';    
                            verify_docs_div += '<div id="accept_docs_div_'+label_id+'_'+section_id+'">';    
                            verify_docs_div += '<a id="acceptdocs_'+label_id+ '_'+section_id+'" class="accept" onclick="approveDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                            verify_docs_div += '<i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>';
                            verify_docs_div += '</a></div>';
                        $('#verify_docs_div_'+label_id+'_'+section_id).html(verify_docs_div);   
                        $('div#image_container_'+label_id+'_'+section_id).html(''); 
                        $('#doc_status_'+label_id+'_'+section_id).html('status: <b>INPROCESS</b>');
                        $('#document_'+label_id+'_'+section_id).val('');
                        
                        $.each(res.data.urls,function(index,url)
                        {
                            var images = '<div class="col-md-3">';
				images += '<a class="test-popup-link" href="'+url+'">';
                                images += '<img src="'+url+'" id="img_url">';
				images += '</a>';
				images += '<span>';                                            
				images += '<a href="'+url+'" class="download" download><i class="fa fa-download" aria-hidden="true"></i></a>';
				images += '<a class="test-popup-link view" href="'+url+'"><i class="fa fa-search-plus" aria-hidden="true"></i></a>';
				images += '</span>';
				images += '</div>';
                                
                                $('div#image_container_'+label_id+'_'+section_id).append(images);                                
                        });
//                        alert(res.msg);                       
                    }
                    else
                    {
                        alert(res.description);
                    }  
                    $('#btn_upload_documents_'+label_id+'_'+section_id).html(submit_button);
                }
            });
}

/*function updateTextualInfo(label_id){
    var form_data = $("#textualinfoForm_"+label_id).serialize();
    var submit_button = $('#submit_button_'+label_id).html();
    var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
    $.ajax({
        url : "/docmanagement/uploadDocs",
        type: "POST",
        dataType:'json',
        data : form_data,
        beforeSend: function () {
            $('#submit_button_'+label_id).html(loading_gif);
        },
        success:function(data){
            $('#submit_button_'+label_id).html(submit_button);
            if(data.status == 'success')
            {
                alert(data.msg);
            }
            else
            {
                alert(data.description);
            } 
        }
    });
}*/

function approveDocs(label_id,user_id,label_type,section_id)
{
    var label_val = $('#label_id_'+label_id+'_'+section_id).val();    
    var curr_desc = $('#curr_desc_'+label_id+'_'+section_id).val();
    
    if(label_val == ''){
        alert('Please enter some value.');
        return false;
    }
    var prev_desc = $('#prev_desc_'+label_id+'_'+section_id).val();
    var flag = confirm('Do you want to approve this label?');
    var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
    var accept_doc_html = $('#accept_docs_div_'+label_id+'_'+section_id).html();
    var dynamic_flag = $('#dynamic_flag_'+label_id+'_'+section_id).val();
    
    if(flag)
    {
        $.ajax({
        url : "/docmanagement/verifyUserDocs",
        type: "POST",
        dataType:'json',
        data : {user_id:user_id,label_id:label_id,pay1_status:'1',label_type:label_type,curr_desc:label_val,prev_desc:prev_desc},
        beforeSend: function () {
            $('#accept_docs_div_'+label_id+'_'+section_id).html(loading_gif);
        },
        success:function(res){
            if(res.status == 'success')
            {
                if(dynamic_flag == 1){
                var doc_upload_html = '<label for="document">Upload</label>';
                    doc_upload_html +=     '<input type="file" name="document[]" id="document_'+label_id+'_'+section_id+'" accept="image/*" multiple="multiple" alt="Upload Docs"/>';
                    doc_upload_html +=   '<div class="submit-button" id="btn_upload_documents_'+label_id+'_'+section_id+'">';
                    doc_upload_html +=       '<input class="btn btn-default btn-primary btn-sm" type="button" value="Upload" onclick="uploadDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                    doc_upload_html +=   '</div>';
                }else{
                    doc_upload_html = '';
                }
                $('#upload_docs_div_'+label_id+'_'+section_id).html(doc_upload_html);
//                $('#upload_docs_div_'+label_id).html('');
                $('#doc_status_'+label_id+'_'+section_id).html('status: <b>APPROVED</b>');
                $('#accept_docs_div_'+label_id+'_'+section_id).html('');
                if(label_type == 1){
                    var reject_html = '<a id="rejectdocs_'+label_id+ '_'+section_id+'" class="reject" onclick="rejectDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                    reject_html += '<i style="cursor: pointer;" title="click to reject docs" class="fa fa-times" aria-hidden="true"></i>';
                    reject_html += '</a>';
                    $('#reject_docs_div_'+label_id+'_'+section_id).html(reject_html);
                    
                }else if(label_type == 2){
                    if(res.data.input_type == 'dropdown'){
                        $('select[name="label_id_'+label_id+'_'+section_id+'"]').val(res.data.curr_desc).attr("selected","selected");
                    }else{
                        $('#label_id_'+label_id+'_'+section_id).val(res.data.curr_desc);                        
                    }
                    $('#last_ver_label_id_'+label_id+'_'+section_id).html('');
                    $('#reject_docs_div_'+label_id+'_'+section_id).html('');
                    var accept_html = '<a id="acceptdocs_'+label_id+'_'+section_id+'" class="accept" onclick="approveDocs('+label_id+','+user_id+','+label_type+','+section_id+')">';
                    accept_html += '<i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>';
                    accept_html += '</a>';
                    $('#accept_docs_div_'+label_id+'_'+section_id).html(accept_html);
                }
                alert(res.msg);
            }
            else
            {
                $('#accept_docs_div_'+label_id+'_'+section_id).html(accept_doc_html);
                alert(res.description);
            } 
        }
    });        
    }
    else
    {
        return false;
    }
}

function rejectDocs(label_id,user_id,label_type,section_id)
{
    $('#user_id').val(user_id);
    $('#label_id').val(label_id);
    $('#label_type').val(label_type);
    var curr_desc = $('#curr_desc_'+label_id+'_'+section_id).val();
    var prev_desc = $('#prev_desc_'+label_id+'_'+section_id).val();
    $('#curr_desc').val(curr_desc);
    $('#prev_desc').val(prev_desc);
    $('#section_id').val(section_id);
    $('#pay1comment').val('');
//    $('#rejectdocsmodal'_id).modal('toggle');
    $('.rejectdocsmodal').attr('id','rejectdocsmodal_'+label_id+'_'+section_id);
    $('#rejectdocsmodal_'+label_id+'_'+section_id).modal('show');
    
}

function updateTextualInfo(user_id,label_type,section_id)
{
    var label_info = {};
    var count = 0;
    $('#textualinfoForm_'+section_id).find('input[type=checkbox][name="label_ids[]"]:checked').each(function(){
        label_info[this.value] = {};
        label_info[this.value]['label_id']  = this.value;
        label_info[this.value]['curr_desc'] = $('#label_id_'+this.value+'_'+section_id).val();
        label_info[this.value]['prev_desc'] = $('#prev_desc_'+this.value+'_'+section_id).val();
        count++;
    });
    
    if(count == 0){
        alert('Kindly select atleast one checkbox');
        return false;
    }
    var flag = confirm('Do you want to approve these labels?');
    var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
    var button_html = $('#submit_button_'+section_id+' #btnsavetextualinfo').html();
    
    if(flag)
    {
        $.ajax({
        url : "/docmanagement/verifyUserDocs",
        type: "POST",
        dataType:'json',
        data : {user_id:user_id,label_info:label_info,pay1_status:'1',label_type:label_type},
        beforeSend: function () {
            $('#submit_button_'+section_id+' #btnsavetextualinfo').html(loading_gif);
        },
        success:function(res){
            if(res.status == 'success')
            {
                $.each(res.data,function(label_id,label_info)
                {
                    if(label_info.input_type == 'dropdown'){
                        $('select[name="label_id_'+label_id+'_'+section_id+'"]').val(label_info.curr_desc).attr("selected","selected");
                    }else{
                        $('#label_id_'+label_id+'_'+section_id).val(label_info.curr_desc);                        
                    }
                    $('#last_ver_label_id_'+label_id+'_'+section_id).html('');
                    $('#reject_docs_div_'+label_id+'_'+section_id).html('');                    
                });
//                $("#textualinfoForm_"+section_id+" input[name='label_ids']:checkbox").prop('checked',false);
                $('.chk_label').attr('checked', false);
                $('#submit_button_'+section_id+' #btnsavetextualinfo').html(button_html);    
                var invalid_labels = res.invalid_labels != ''?'Invalid labels : '+res.invalid_labels:'';
                alert(res.msg+'\n'+invalid_labels);
            }
            else
            {
                $('#submit_button_'+section_id+' #btnsavetextualinfo').html(button_html);
                alert('Invalid labels : '+res.invalid_labels);
            } 
        }
    });        
    }
    else
    {
        return false;
    }
}

function getPanStatus(label_id,user_id,section_id){
    var pan_no = $('#label_id_'+label_id+'_'+section_id).val();
    if(pan_no == ''){
        alert('Please enter Pan No');
        return false;
    }
    var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
    var button_html = $('#btn_id_'+label_id+'_'+section_id).html();
    $.ajax({
        url : "/docmanagement/getPanStatus",
        type: "POST",
        dataType:'json',
        data : {user_id:user_id,pan_no:pan_no},
        beforeSend: function () {
            $('#btn_id_'+label_id+'_'+section_id).html(loading_gif);
        },
        success:function(res){
            if(res.status == 'success'){
//                console.log(res.data);
                $("#user_pan_details").css('display','block');
                var pan_data_html = '<b><span>'+res.data[pan_no]['first_name']+' '+res.data[pan_no]['middle_name']+' '+res.data[pan_no]['last_name']+'</span></b>';
                $('#user_pan_details').html(pan_data_html);
                $('#btn_id_'+label_id+'_'+section_id).attr("disabled", "disabled");
            }else{
                alert(res.status);
            }
            $('#btn_id_'+label_id+'_'+section_id).html(button_html);
        }
    });
}