

<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap-responsive.min.css?990' />
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />

<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 


<!-- Button to trigger modal 
<a href="#smsModal" role="button" class="badge badge-info" data-toggle="modal">SMS</a>
<a href="#cmdModal" role="button" class="badge badge-important" data-toggle="modal">CMD</a>-->
 
<!-- SMS Modal -->
<div id="smsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-info">Send SMS</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="sms_type" value="1" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="sms_dev_id" /><br/>
    <input placeholder="Vendor ID" type="text" disabled="disabled" id="sms_vendor" /><br/>
    <input placeholder="Mobile" type="text" id="sms_mob" /><br/>
    <textarea placeholder="Query" id="sms_qry" ></textarea>
    <textarea placeholder="Message" id="msg" ></textarea>
    <div id="response_sms" style="font-size:11px;overflow:auto;height:100px;">
            <table id="response_sms_table" class="table">
            <thead class="warning"><th width='15'></th><th style='line-height:11px'>Time</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="send_sms" type="button" class="btn btn-info" data-loading-text="Sending..." data-complete-text="Send">Send</button>
  </div>
</div>

<!-- AT Command Modal -->
<div id="cmdModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="cmdModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-error">Run AT Command</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="cmd_type" value="2" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="cmd_dev_id" /><br/>
    <input placeholder="Vendor ID" type="text" disabled="disabled" id="cmd_vendor" /><br/>
    <input placeholder="Time" type="text" id="cmd_time" /><br/>
    <textarea placeholder="Query" id="cmd_qry" ></textarea>
    <textarea placeholder="Command" id="cmd" ></textarea>
    <div id="response_cmd" style="font-size:11px;overflow:auto;height:100px;">
            <table  class="table" >
            <thead class="warning"><th width='15'></th><th style='line-height:11px'>Time</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            <tbody id="response_cmd_table"></tbody>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="run_cmd" class="btn btn-danger" data-loading-text="Executing..." data-complete-text="Run">Run</button>
  </div>
</div>

<!-- USSD Command Modal -->
<div id="ussdModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ussdModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-error">Run USSD Command</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="ussd_type" value="3" /><br/>
    <input placeholder="Device ID" type="text" disabled="disabled" id="ussd_dev_id" /><br/>
    <input placeholder="Vendor ID" type="text" disabled="disabled" id="ussd_vendor" /><br/>
    <input placeholder="Time" type="text" id="ussd_time" /><br/>
    <textarea placeholder="Query" id="ussd_qry" ></textarea>
    <textarea placeholder="Command" id="ussd" ></textarea>
    <div id="response_ussd" style="font-size:11px;overflow:auto;height:100px;">
            <table  class="table" >
            <thead class="warning"><th width='15'></th><th style='line-height:11px'>Time</th><th style='line-height:11px'>Input</th><th style='line-height:11px'>Output</th></thead>
            <tbody id="response_ussd_table"></tbody>
            </table>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="run_ussd" class="btn btn-danger" data-loading-text="Executing..." data-complete-text="Run">Run</button>
  </div>
</div>

<script>
     $.noConflict();
     function sendSms(dev , vendor){
       jQuery('#send_sms').button('reset');
        jQuery('#sms_dev_id').val(dev);
        jQuery('#sms_vendor').val(vendor)
        jQuery('#smsModal').modal('show');
     }
     
    function runCmd(dev , vendor){
        jQuery('#cmd_dev_id').val(dev);
        jQuery('#cmd_vendor').val(vendor)
        jQuery('#cmdModal').modal('show');
        /*jQuery('#cmdModal').on('shown', function () {
         // do something…
        })*/
    }
    
    function runUssd(dev , vendor){
        jQuery('#ussd_dev_id').val(dev);
        jQuery('#ussd_vendor').val(vendor)
        jQuery('#ussdModal').modal('show');
        /*jQuery('#cmdModal').on('shown', function () {
         // do something…
        })*/
    }
    jQuery('#send_sms').click(function(){
                       
                        var url = 'http://www.ashops.local/shops/modemRequest';
                        jQuery('#send_sms').button('loading');
                        var data = "query="+jQuery('#sms_qry').val()+"&type="+jQuery('#sms_type').val()+"&device="+jQuery('#sms_dev_id').val()+"&vendor="+jQuery('#sms_vendor').val()+"&mobile="+jQuery('#sms_mob').val()+"&msg="+encodeURIComponent(jQuery('#msg').val())+"";
                        jQuery.ajax({
                            type:"GET",
                            url:url,
                            datatype:"json",
                            data:data,
                            success:function(data){
                                jQuery('#send_sms').button('reset');
                                jQuery('#send_sms').button('complete');
                                try {
                                    data = JSON.parse(data);
                                } catch (e) {
                                    data = {'status':"failure",'errno':'00','error':'Some error occured'};
                                }
                                var currentdate = new Date(); 
                                var current_time = currentdate.getTime();
                                if( data.status && data.status == 'success' ){
                                    
                                    jQuery('#response_sms_table').prepend("<tr class='success' id='sms_"+current_time+"' ><td width='15'><i style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#sms_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#sms_mob').val()+":"+jQuery('#msg').val()+"</td><td style='line-height:11px'>"+data.response+"</td></tr>");
                                }else if( data.status && data.status == 'failure' ){
                                    jQuery('#response_sms_table').prepend("<tr class='error' id='sms_"+currentdate.getTime()+"' ><td width='15'><i style='cursor:pointer' class='icon-remove-circle'  onclick=\"jQuery('#sms_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#sms_mob').val()+":"+jQuery('#msg').val()+"</td><td style='line-height:11px'>"+data.errno+":"+data.error+"</td></tr>");
                                }else{
                                    jQuery('#response_sms_table').prepend("<tr class='error' id='sms_"+currentdate.getTime()+"' ><td width='15'><i style='cursor:pointer' class='icon-remove-circle'  onclick=\"jQuery('#sms_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#sms_mob').val()+":"+jQuery('#msg').val()+"</td><td style='line-height:11px'>Some error occured.</td></tr>");
                                }

                            }
                        });
                });
    jQuery('#run_cmd').click(function(){
                     var url = 'http://www.ashops.local/shops/modemRequest/json';
                    jQuery('#run_cmd').button('loading');
                    var data = "query="+jQuery('#cmd_qry').val()+"&type="+jQuery('#cmd_type').val()+"&wait="+jQuery('#cmd_time').val()+"&device="+jQuery('#cmd_dev_id').val()+"&vendor="+jQuery('#cmd_vendor').val()+"&cmd="+encodeURIComponent(jQuery('#cmd_qry').val())+"";
                    jQuery.ajax({
                        type:"GET",
                        url:url,
                        datatype:"json",
                        data:data,
                        success:function(data){
                            jQuery('#run_cmd').button('reset');
                            jQuery('#run_cmd').button('complete');
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                data = {'status':"failure",'errno':'00','error':'Some error occured'};
                            }
                            var currentdate = new Date(); 
                            var current_time = currentdate.getTime();
                            if( data.status && data.status == 'success' ){
                                jQuery('#response_cmd_table').prepend("<tr class='success' id='cmd_"+current_time+"' ><td width='15'><i class='icon-remove-circle' style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#cmd_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#cmd_time').val()+":"+jQuery('#cmd_qry').val()+"</td><td style='line-height:11px'>"+data.response+"</td></tr>");
                            }else if( data.status && data.status == 'failure' ){
                                jQuery('#response_cmd_table').prepend("<tr class='error' id='cmd_"+current_time+"' ><td width='15'><i class='icon-remove-circle' style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#cmd_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#cmd_time').val()+":"+jQuery('#cmd_qry').val()+"</td><td style='line-height:11px'>"+data.errno+":"+data.error+"</td></tr>");
                            }else{
                                jQuery('#response_cmd_table').prepend("<tr class='error' id='cmd_"+current_time+"' ><td width='15'><i class='icon-remove-circle' style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#cmd_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#cmd_time').val()+":"+jQuery('#cmd_qry').val()+"</td><td style='line-height:11px'>Some error occured.</td></tr>");
                            }
                        }
                    });

                });
                
                jQuery('#run_ussd').click(function(){
                     var url = 'http://www.ashops.local/shops/modemRequest/json';
                    jQuery('#run_ussd').button('loading');
                    var data = "query="+jQuery('#ussd_qry').val()+"&type="+jQuery('#ussd_type').val()+"&wait="+jQuery('#ussd_time').val()+"&device="+jQuery('#ussd_dev_id').val()+"&vendor="+jQuery('#ussd_vendor').val()+"&ussd="+encodeURIComponent(jQuery('#ussd_qry').val())+"";
                    jQuery.ajax({
                        type:"GET",
                        url:url,
                        datatype:"json",
                        data:data,
                        success:function(data){
                            jQuery('#run_ussd').button('reset');
                            jQuery('#run_ussd').button('complete');
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                data = {'status':"failure",'errno':'00','error':'Some error occured'};
                            }
                            var currentdate = new Date(); 
                            var current_time = currentdate.getTime();
                            if( data.status && data.status == 'success' ){
                                jQuery('#response_ussd_table').prepend("<tr class='success' id='ussd_"+current_time+"' ><td width='15'><i class='icon-remove-circle' style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#ussd_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#ussd_time').val()+":"+jQuery('#ussd_qry').val()+"</td><td style='line-height:11px'>"+data.response+"</td></tr>");
                            }else if( data.status && data.status == 'failure' ){
                                jQuery('#response_ussd_table').prepend("<tr class='error' id='ussd_"+current_time+"' ><td width='15'><i class='icon-remove-circle' style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#ussd_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#ussd_time').val()+":"+jQuery('#ussd_qry').val()+"</td><td style='line-height:11px'>"+data.errno+":"+data.error+"</td></tr>");
                            }else{
                                jQuery('#response_ussd_table').prepend("<tr class='error' id='ussd_"+current_time+"' ><td width='15'><i class='icon-remove-circle' style='cursor:pointer' class='icon-remove-circle' onclick=\"jQuery('#ussd_"+current_time+"').hide();\"></i></td><td style='line-height:11px'>"+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds()+"</td><td style='line-height:11px'>"+jQuery('#ussd_time').val()+":"+jQuery('#ussd_qry').val()+"</td><td style='line-height:11px'>Some error occured.</td></tr>");
                            }
                        }
                    });

                });
    
    //jQuery('#run_cmd').click(function(){
           
    //});
    
    //jQuery('#run_cmd').button('reset');
    
    
 </script>
 
 
 
 
<div>
    <table  class="table table-bordered">
        <thead><th>Device ID</th><th>SIM No</th><th>Action</th></thead>
        <tbody>
            <tr><td>2.4.1</td><td>7389018451</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.1',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.1',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.1',4);">USSD</a></td></tr>
            <tr><td>2.4.2</td><td>7389018452</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.2',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.2',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.2',4);">USSD</a></td></tr>
            <tr><td>2.4.3</td><td>7389018453</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.3',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.3',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.3',4);">USSD</a></td></tr>
            <tr><td>2.4.4</td><td>7389018454</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.4',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.4',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.4',4);">USSD</a></td></tr>
            <tr><td>2.4.5</td><td>7389018455</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.5',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.5',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.5',4);">USSD</a></td></tr>
            <tr><td>2.4.6</td><td>7389018456</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.6',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.6',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.6',4);">USSD</a></td></tr>
            <tr><td>2.4.7</td><td>7389018457</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.7',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.7',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.7',4);">USSD</a></td></tr>
            <tr><td>2.4.8</td><td>7389018458</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.8',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.8',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.8',4);">USSD</a></td></tr>
            <tr><td>2.4.9</td><td>7389018459</td><td><a role="button" class="badge badge-info" onclick="sendSms('2.2.9',4);">SMS</a><a role="button" class="badge badge-important" onclick="runCmd('2.2.9',4);">CMD</a><a role="button" class="badge badge-warning" onclick="runUssd('2.2.9',4);">USSD</a></td></tr>
            
        </tbody>
    </table>
</div>