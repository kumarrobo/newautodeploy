$(document).ready(function(){
    
    generateYellowstrip();
    
    function generateYellowstrip(){
        var modemid=[];
        $("table[id*='modem_']").each(function(k,v){
        var tableid=$(v).attr('id').split('_');
        modemid.push(tableid[1]);
        });
        var id= modemid.join(',');
        
        var getyellowsims=$.getJSON(HOST+'sims/getyellowsimsbymodemid',{modemid:id});

        getyellowsims.done(function(res){
           
               if(res.data!=""){
               
                    $.each(res.data,function(k,v){
                              
                            $('div.yellowstrip'+k).remove();
                            var html="";
                            html+="<div class='row yellowstrip"+k+"' style='margin-top: 5px; '>";
                            html+="<div class='col-lg-10 col-lg-offset-1' style='background-color: yellow;border-radius: 5px;word-wrap: break-word; border: 1px solid red;'>";
                            //html+="<marquee direction='right' scrollamount='5'>";
                                    $.each(v,function(k1,v1){
                                    html+="<a target='_blank' href='/sims/lastModemTransactions/"+v1.vendor_id+"/"+v1.device_id+"/1'>"+v1.mobile+"</a>&nbsp;&nbsp;";
                                     });
                            // html+='</marquee>';
                            html+="</div>";
                            html+="</div>";
                            
                            $(html).insertBefore($('table#modem_'+k).parent());
                                       
                        });
                  }
            });
    }
  
 setInterval(function(){ generateYellowstrip(); }, 300000);

 
});


