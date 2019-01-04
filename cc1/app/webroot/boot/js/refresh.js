var HOST= "http://"+window.location.hostname+"/";

$(document).ready(function(){
    
    var clickcount=1;
    
    // Added Listner on switch view
    $('body').on('click', '.switch .btn-group button', function (e) {
        
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
        
        var target=$(e.target).attr('href');
        
        if(clickcount < 2)
        {
            clickcount++;     
            generateOperatorwisereport(target).done(function(){
             $('button#btn-operator-refresh').removeClass('disabled').attr('disabled',false);
             $('button#btn-operator-refresh i').removeClass('glyphicon-refresh-animate');
              });
              
              loadSuccessFailure();
     
        }
        
 });
 
 // Added Listner in Reload button
 $(document).on('click','#btn-operator-refresh',function(e){
     
     var target="#operators_view";
     generateOperatorwisereport(target).done(function(){
            $('button#btn-operator-refresh').removeClass('disabled').attr('disabled',false);
            $('button#btn-operator-refresh i').removeClass('glyphicon-refresh-animate');
     });
     
 });
 
});

    // Load Operator Wise Success failure
    
    function loadSuccessFailure()
    {
           var isDistributer=$.cookie('isDistributer');
           
          if(isDistributer=="1")
              {
                  return 
              }
              
    var OperatorwisesuccessfailureJSON=$.getJSON(HOST+'sims/getOperatorWiseSuccessFailureReports/operatorview');
    
        OperatorwisesuccessfailureJSON.done(function(res){

           // res=$.parseJSON(res);

            $.each(res.successfailurereports,function(k,v){
                
                var bgcolor="";
                if((v.failure*100/v.total)>20 || v.count=="0"){bgcolor="#c73525"};
                
                var html="";
                html+="<div class='col-lg-3 divblock' style='background-color:"+bgcolor+"'>";
                html+="<ul class='divblockul'>";
                html+="<li><b>"+v.name+"</b></li>";
                html+="<li>"+v.vendor+" ("+((v.count*100)/v.total).toFixed(2)+"%)</li>";
                html+="<li> Failure ("+((v.failure*100)/v.total).toFixed(2)+"%)</li>";
                html+="</ul>";
                html+="</div>";
                
                $('div#successfailurereportsdiv').append(html);
                
            });        

        });
    
    }
    
// Core function that loads operatorwise report
function generateOperatorwisereport(target)
{
     var isDistributer=$.cookie('isDistributer');
     console.log("In Operator View : "+isDistributer);
     
       var d1 = $.Deferred();
            //Start 
        if(target=="#operators_view")
        {
         
          $('button#btn-operator-refresh').addClass('disabled').attr('disabled',true);
          $('button#btn-operator-refresh i').addClass('glyphicon-refresh-animate');
          
          var operators=getUrlParameter('operators');
          var saleamtFrom=getUrlParameter('saleamtFrom');
          var saleamtTo=getUrlParameter('saleamtTo');
          var suppliername=getUrlParameter('suppliername');
          var searchbymobile=getUrlParameter('searchbymobile');
          var colorname=getUrlParameter('color');
          var mode=getUrlParameter('mode');
          var searchbydate=getUrlParameter('searchbydate');
          
          
           var operatorsJSON=$.get(HOST+'sims/getOperatorsViewJSON',{operators:operators,saleamtFrom:saleamtFrom,saleamtTo:saleamtTo,suppliername:suppliername,searchbymobile:searchbymobile,color:colorname,mode:mode,searchbydate:searchbydate});
            
            operatorsJSON.done(function(response){
                
                var previousday=false;
                
                if(getUrlParameter('searchbydate')!="")
                {
                     previousday=getPreviousDayFlag(getUrlParameter('searchbydate'));
                }
                
                response=$.parseJSON(response);
                
                $('table#operator_view_table > tbody').empty();
                
                $.each(response.networks.operators,function(key,value){
                
                    var modem_class=value.info.id;
                    var row="";
                   
                    
                        row+="<tr>";
                        row+="<td class='level1'   data-operator-id='"+modem_class+"'><i  class='glyphicon glyphicon-plus'></i>"+key+"</td>";
                        row+="<td>"+value.info.totalActiveSims+'/'+value.info.totalSims+"</td>";
                        
                        var operatorwiserequestpermin=getOperatorwiseRequestPerMinute(response.requests,value.info.id);
                        
                        row+="<td>"+operatorwiserequestpermin+"</td>";
                        row+="<td>"+value.info.totalBlockedSims+'/'+value.info.totalStoppedSims+"</td>";
                        row+="<td>"+ReplaceNumberWithCommas(value.info.totalBalance,2)+"</td>";
                        row+="<td>"+ReplaceNumberWithCommas(value.info.totalOpening,2)+"</td>";
                        row+="<td>"+ReplaceNumberWithCommas(value.info.totalClosing,2)+"</td>";
                        row+="<td>"+ReplaceNumberWithCommas(value.info.totalIncoming,2)+"</td>";
                        
                         var totalsaleincludingapi=parseFloat(value.info.totalApisale)+parseFloat(value.info.totalSale);
                         
                        row+="<td>"+ReplaceNumberWithCommas(totalsaleincludingapi,2)+"</td>";
                        
                        if(!previousday)
                        {
                           var  diff=-(value.info.totalOpening-value.info.totalBalance+value.info.totalIncoming-value.info.totalSale+value.info.totalIncomingClo);
                        }
                        else
                        {
                           var  diff=-(value.info.totalOpening-value.info.totalClosing+value.info.totalIncoming-value.info.totalSale+value.info.totalIncomingClo);
                        }
                        row+="<td>"+ReplaceNumberWithCommas(diff,2)+"</td>" 
                        row+="<td>"+ReplaceNumberWithCommas(value.info.totalBlockedBalance)+"</td>" 
                        row+="</tr>";
                    
                    $('table#operator_view_table > tbody').append(row);
                    
                    
                          // Level 2 Start
                          
                          
                          $.each(value.modems,function(key1,value1){
                              
                               var row2="";
                               
                                var modemwiserequestpermin=getModemwiseRequestPerminute(response.requests,modem_class,key1);
                                var higlightrequestmodems="";
                                if(modemwiserequestpermin!="ND"){ higlightrequestmodems="background-color:#a2d246";  };
                                
                              if(typeof(value1.update_flag)!="undefined")
                              {
                                        row2+="<tr style='display: none;"+higlightrequestmodems+"' class='modem_"+modem_class+"'>";
                                        row2+=" <td><div class='level2'><i class='glyphicon glyphicon-plus'></i>"+value1.company+"</div></td>";
                                        row2+="<td>"+value1.totalActiveSims+'/'+value1.totalSims+"</td>";
                                        row2+="<td>"+modemwiserequestpermin+"</td>";
                                        row2+="<td>"+value1.totalBlockedSims+'/'+value1.totalStoppedSims+"</td>";
                                        row2+="<td>"+ReplaceNumberWithCommas(value1.totalBalance,2)+"</td>";
                                        row2+="<td>"+ReplaceNumberWithCommas(value1.totalOpening,2)+"</td>";
                                        row2+="<td>"+ReplaceNumberWithCommas(value1.totalClosing,2)+"</td>";
                                        row2+="<td>"+ReplaceNumberWithCommas(value1.totalIncoming,2)+"</td>";
                                        row2+="<td>"+ReplaceNumberWithCommas(value1.totalSale,2)+"</td>";
                                        if(!previousday)
                                        {
                                        var  diff2=-(value1.totalOpening-value1.totalBalance+value1.totalIncoming-value1.totalSale+value1.totalIncomingClo);
                                        }
                                        else
                                        {
                                        var  diff2=-(value1.totalOpening-value1.totalClosing+value1.totalIncoming-value1.totalSale+value1.totalIncomingClo);
                                        }
                                        row2+="<td>"+ReplaceNumberWithCommas(diff2,2)+"</td>"; 
                                        row2+="<td>"+ReplaceNumberWithCommas(value1.totalBlockedBalance)+"</td>"; 
                                        row2+="</tr>";
                              }
                              else
                              {
                                    var vendor_class = value1.company;
                                    var api = response.api[modem_class][vendor_class];
                                    row2+="<tr style='display: none;"+higlightrequestmodems+"' class='modem_"+modem_class+"'>";
                                    row2+=" <td><div class='apidiv'><i class='glyphicon glyphicon-plus'></i>"+value1.company+"</div></td>";
                                    row2+="<td>"+api+"</td>";
                                    row2+="<td>"+modemwiserequestpermin+"</td>";
                                    row2+="<td>--</td><td>--</td><td>--</td><td>--</td><td>--</td>";
                                    row2+="<td>"+ReplaceNumberWithCommas(value1.totalSale,2)+"</td>";
                                    row2+="<td>--</td><td>--</td>";
                                    row2+="</tr>";
                              }
                              
                              $('table#operator_view_table > tbody').append(row2);
                              
                               if(typeof(value1.update_flag)!="undefined" && typeof(value1.sims)!="undefined")
                               {
                              // Level 3 Start
                               var row3 = '<tr  class="operatorsims"  style="display:none">\n<td colspan="12">\n<table class="table table-condensed table-hover table-bordered">\n<thead>\n<th>S-D/M/P</th>\n<th>Vendor</th>\n<th>Number</th>\n<th>Margin</th>\n<th>Balance</th>\n<th>Opening</th>\n<th>Closing</th>\n<th>Incoming</th>\n<th>Sale</th>\n<th>H.Sale</th>\n<th>R.Sale</th>\n<th>Inc</th>\n<th>Diff</th>\n<th>Limit</th><th>Succ %</th>\n<th>Prcs time</th>\n<th>L Succ</th><th>Last Txn</th>\n<th>Flag</th>\n<th>Status</th>\n<th>Actions</th>\n</thead>\n<tbody>';


                                                $.each(value1.sims,function(key2,value2){

                                                    $.each(value2,function(simkey,simvalue){

                                                         var closing=0.00;
                                                         var success="";
                                                         var last="Last Sms";
                                                         var process_time="";
                                                         var sale="0.00";
                                                         var machine_id="";

                                                         var stop_flag_style="";
                                                         var clickfunction="";
                                                         var clickfunction2param=0;
                                                         var stop_flag_text="Stop";

                                                         if(simvalue.machine_id>0){machine_id=simvalue.machine_id;}
                                                         
                                                         color=getColor(simvalue);
                                                         row3+="<tr  style='background-color:"+color+"'>";
                                                         var greenscircle="TP";
                                                         if(simvalue.active_flag=="1"){ greenscircle='<div style="background: #12EB50;width: 10px;height: 10px;border-radius: 50%;"></div>'; }
                                                         row3+="<td>"+simvalue.signal+"-"+simvalue.id+"/"+machine_id+"/"+simvalue.device_num+"  "+greenscircle+" </td>";
                                                         row3+="<td>"+simvalue.vendor+"</td>";
                                                         row3+="<td>"+simvalue.mobile+"</td>";
                                                         row3+="<td>"+simvalue.commission+"</td>";
                                                         row3+="<td>"+simvalue.balance+"</td>";
                                                         row3+="<td>"+simvalue.opening+"</td>";
                                                         if(simvalue.closing===undefined){closing==0.00}else{closing=simvalue.closing};
                                                         row3+="<td>"+closing+"</td>";
                                                         row3+="<td>"+simvalue.tfr+"</td>";
                                                         if(simvalue.sale!==null){sale=simvalue.sale};
                                                         row3+="<td>"+sale+"</td>";
                                                         row3+="<td>"+(simvalue.sale-simvalue.roaming_today)+"</td>";
                                                          row3+="<td>"+simvalue.roaming_today+'/'+simvalue.roaming_limit+"</td>";
                                                          row3+="<td>"+ReplaceNumberWithCommas(simvalue.inc,2)+"</td>";
                                                        if(!previousday)
                                                        {
                                                        var  diff3 =  -(parseFloat(simvalue.opening)-parseFloat(simvalue.balance)+parseFloat(simvalue.tfr)-parseFloat(simvalue.sale)+parseFloat(simvalue.inc));
                                                        }
                                                        else
                                                        {
                                                        var  diff3 = -(parseFloat(simvalue.opening)-parseFloat(simvalue.closing)+parseFloat(simvalue.tfr)-parseFloat(simvalue.sale)+parseFloat(simvalue.inc));
                                                        }
                                                         row3+="<td>"+ReplaceNumberWithCommas(diff3,2)+"</td>";
                                                         row3+="<td>"+simvalue.limit+"/"+simvalue.roaming_limit+"</td>";
                                                          if(simvalue.success>0){success=simvalue.success+"%"}
                                                          row3+="<td>"+success+"</td>";

                                                          if(simvalue.process_time!=undefined){process_time=simvalue.process_time+" Secs"};
                                                          row3+="<td>"+process_time+"</td>";

                                                          var dateinlink=((typeof (simvalue.sync_date) != 'undefined')?simvalue.sync_date:(new Date()).toISOString().substring(0, 10));

                                                          if(simvalue.last!==null){last=simvalue.last};
                                                          row3+="<td><a href='/sims/lastModemSMSes/"+key1+"/"+simvalue.id+"/1/1500' target='_blank'>"+last+"</a></td>";
                                                      
                                                          if(isDistributer=="0"){
                                                          row3+="<td><a href='/sims/lastModemTransactions/"+key1+"/"+simvalue.id+"/1/1500' target='_blank'>Last txn</a></td>";
                                                           }
                                                     
                                                          if(simvalue.stop_flag!="0"){stop_flag_style="style='background-color: red;'";stop_flag_text="Start"};
                                                          if(simvalue.stop_flag=="0"){clickfunction2param=1}
                                                          clickfunction="<a onclick=\"stopDevice('"+simvalue.id+"','"+clickfunction2param+"','"+key1+"',this)\">"+stop_flag_text+"</a>";

                                                           row3+="<td  "+stop_flag_style+" >"+clickfunction+"</td>";
                                                           row3+="<td><div id='status'><button class='btn btn-default btn-xs' onclick=\"checkSimStatus('"+simvalue.id+"','"+key1+"',this)\">Status</button></div><div id='negdiff'><button class='btn btn-default btn-xs' onclick=\"checkNegDiff('"+simvalue.id+"','"+key1+"',this)\">NegDiff</button></div></td>";
                                                           if(isDistributer=="0"){
                                                            row3+="<td>"+getSimActions(simvalue,key1)+"</td>";
                                                              }
                                                              
                                                           row3+="</tr>";

                                                    });

                                                });
                                
                                    // Level 3 End
                                
                                row3+='</tbody></table></td></tr>';

                                $('table#operator_view_table > tbody').append(row3);
                                
                                 }
                          
                          });
                          // Level 2 End
                    
                });
                
                d1.resolve();
           });
            
            return d1.promise();
        
           
        }
        //End
    
}

function getSimActions(sim,vendor_id)
{
    var html="";
    
        html+="<div class='btn-group'>";
        html+="<button type='button' class='btn btn-primary dropdown-toggle btn-xs'  data-toggle='dropdown'> Action <span class='caret'></span></button>";
        html+="<ul class='dropdown-menu' role='menu'>";
        html+="<li><a class='open-smsmodal' href='#sendSmsModal' data-toggle='modal' data-vendorid='"+vendor_id+"' data-simid='"+sim.id+"' >SMS</a></li>";
        html+="<li><a class='open-ussdmodal' href='#sendUssdModal' data-toggle='modal' data-vendorid='"+vendor_id+"' data-simid='"+sim.id+"' >USSD</a></li>";
        //html+="<li><a class='open-statusmodal' href='#simStatusModal' data-toggle='modal' data-vendorid='"+vendor_id+"' data-simid='"+sim.id+"' >STATUS</a></li>";
        var clickfunction="<a onclick=\"runShowHide('"+sim.id+"','"+vendor_id+"')\">Hide</a>";
     //   html+="<li>"+clickfunction+"</li>";
        //html+="<li><a class='open-atmodal' href='#sendAtModal' data-toggle='modal' data-vendorid='"+vendor_id+"' data-simid='"+sim.id+"' >At</a></li>";
        //html+="<li><a class='open-resetmodal' href='#sendResetModal' data-toggle='modal' data-vendorid='"+vendor_id+"' data-simid='"+sim.id+"' >Reset</a></li>";
        html+="</ul>";
        html+="</div>";
    
    return html;
    
}
function getColor(sim)
{
    color="";
      var a=(getLast45MinTimestamp()-getJavascriptEquivalentPhpTimestamp(sim.last));
//      console.log("NUMBER : "+sim.mobile);
//      console.log("A : "+a);
        if (sim.active_flag =="1" && sim.balance < 3000){
               color = '#8c65e3';
           }
            else if (sim.active_flag =="0" && sim.balance > 3000){
               color = '#c73525';
           }
           // yellow
            else if ((sim.active_flag =="1") && (sim.balance > 3000) && (a > 0) && (a!==null)){
               color = '#f6ff00';
           }
           // green
            else if (sim.active_flag =="1"){    
               color = '#99ff99';
           }
            
          //current_date=date('Y-m-d H:i:s');
           
          if((sim.roaming_limit > 0) && (sim.roaming_today >=0) && (sim.roaming_today < (sim.roaming_limit-100)) && sim.active_flag != 1 && sim.balance>100 ){
                color = '#ffa500';
            }
            
            if((sim.roaming_limit > 0) && (sim.roaming_today >=0) && (sim.roaming_today < (sim.roaming_limit-100)) && sim.active_flag != 0 && sim.balance>100){
                color = '#c0c0c0';
            }
            
            if(sim.last!="" && sim.balance!="")
            {
                var t=(String(getJavascriptEquivalentPhpTimestamp(sim.last))-getLast36HrsTimestamp());
                
                if((t<=0) && (sim.balance>100) && t!==null){
                      color = '#19ffd1';
                  }
             }
            
            if(sim.block=="1"){
                color="#99ffcc";
            }
            
            return color;
}

function getJavascriptEquivalentPhpTimestamp(rowtimestamp)
{
    //2015-04-21 10:31:21
    if(rowtimestamp==null){return }
    
   // console.log(rowtimestamp);
    rowtimestamp=rowtimestamp.replace(/-/g,"/");
    
    var displayedrowtimestamp="";
    
    displayedrowtimestamp=new Date(rowtimestamp).getTime()/1000;
    
    return displayedrowtimestamp;
}

function getLast36HrsTimestamp()
{
    var last36hrstimestamp="";
    
    var mydate=new Date();
    mydate.setHours(mydate.getHours()-36);

    var month = ('0' + (mydate.getMonth() + 1)).substr(-2);
    var day = ('0' + mydate.getDate()).substr(-2);
    var hour = ('0' + mydate.getHours()).substr(-2);
    var minute = ('0' + mydate.getMinutes()).substr(-2);
    var second = ('0' + mydate.getSeconds()).substr(-2);

    // Same as in PHP date('Y-m-d H:i:s')
    dateInPhpFormat = mydate.getFullYear() + '/' + month + '/' + day + ' ' + hour + ':' + minute + ':' + second;
 
    last36hrstimestamp=new Date(dateInPhpFormat).getTime()/1000;
    
    return last36hrstimestamp;
}

function getLast45MinTimestamp()
{
    var last45Mintimestamp="";
    
    var mydate=new Date();
    mydate.setMinutes(mydate.getMinutes()- 45 );

    var month = ('0' + (mydate.getMonth() + 1)).substr(-2);
    var day = ('0' + mydate.getDate()).substr(-2);
    var hour = ('0' + mydate.getHours()).substr(-2);
    var minute = ('0' + mydate.getMinutes()).substr(-2);
    var second = ('0' + mydate.getSeconds()).substr(-2);

    // Same as in PHP date('Y-m-d H:i:s')
    dateInPhpFormat = mydate.getFullYear() + '/' + month + '/' + day + ' ' + hour + ':' + minute + ':' + second;
 
    last45Mintimestamp=new Date(dateInPhpFormat).getTime()/1000;
    
    return last45Mintimestamp;
}



function getModemwiseRequestPerminute(requests,id,modemkey)
{
//    console.log(requests);
//    console.log(id);
//    console.log(modemkey);
    
    var tobereturned="ND";
    
    //console.log("a");
     if(typeof(requests.operatorview)!="undefined")
    {
       // console.log("b");
      //  console.log("op");
      //  console.log(requests.operatorview[id]);
         if(typeof(requests.operatorview[id])!="undefined")
         {
             //console.log("c");
                if((typeof(requests.operatorview[id][modemkey])!="undefined"))
                {
                    //console.log("d");
                   // console.log("Total : "+requests.operatorview[id][modemkey].totalrequests);
                    //console.log("Success : "+requests.operatorview[id][modemkey].successrequests);
                    if(requests.operatorview[id][modemkey].totalrequests>0)
                    {
                    tobereturned=requests.operatorview[id][modemkey].successrequests+"/"+requests.operatorview[id][modemkey].totalrequests;
                    }
                }
                
             }
    }

   // console.log(tobereturned);
    return tobereturned;
}

function getOperatorwiseRequestPerMinute(requests,id)
{
    var operatorwiseTotalReq=0;
    var operatorwiseTotalSuccessReq=0;
    var tobereturned="ND";
    
    if(typeof(requests.operatorview)!="undefined")
    {
        if(typeof(requests.operatorview[id]) != "undefined" && requests.operatorview[id] !== null)
        {
            $.each(requests.operatorview[id],function(k,v){

                operatorwiseTotalReq+=v.totalrequests;
                operatorwiseTotalSuccessReq+=v.successrequests;
            });

            tobereturned=operatorwiseTotalSuccessReq+"/"+operatorwiseTotalReq;

        }
    }
    
    return tobereturned;
}

function getPreviousDayFlag(dateparam)
{
    var d=new Date();
    var month = d.getMonth()+1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' +((''+day).length<2 ? '0' : '') + day;
    var currentdaytimestampasperphp=new Date(output).getTime()/1000;
    
    var passed=new Date(dateparam).getTime()/1000;
    
    if(currentdaytimestampasperphp>passed)
    {
        return true;
    }
    
    return false;
    
}


// Added Listner to second level Click for operators View
   jQuery(document).on('click','table#operator_view_table td.level1',function(){
        //console.log(jQuery(this).attr('data-operator-id'));
        currentelement=jQuery(this);
        
        var operator_id=jQuery(this).attr('data-operator-id');
        
        var trclass='modem_'+operator_id;
        
        console.log(trclass);
        jQuery('tr.modem_'+jQuery(this).attr('data-operator-id')).toggle('fast',function(){
        
                if($(this).is(':visible'))
                {
                    $(currentelement).find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                }
                else
                {
                      $(currentelement).find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                      
                     $.each($('tr.'+trclass),function(){
                           if($(this).find('div.level2 i').hasClass('glyphicon-minus'))
                                {
                                    $(this).find('div.level2 i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                                }
                            if($(this).next('tr.operatorsims').is(':visible'))
                            {
                                $(this).next('tr.operatorsims').hide();
                            }
                     }) ;
                    
                }
      
        });
    });
    
