$(document).ready(function(){
    
    var gethightlights=$.getJSON(HOST+'sims/getHighlights');
    
    gethightlights.done(function(res){
        
        if(res.status && res.type)
        {
           $('#showhighlightfieldset').show();
           var count=0;
           
           $.each(res.data,function(k,v){
             
                var HTML="";
                 HTML+="<div class='row'>";
                HTML+="<h5><b>"+k+"</b></h5>";
                
                    $.each(v,function(k1,v1){
                            count=count+1;
                             var link="/sims/index?modem_id="+v1.devices_highlights.vendor_id+"&searchbydate="+v1.devices_highlights.sync_date+"&mode=search&searchbymobile="+v1.devices_highlights.mobile;
                             HTML+="<a target='_blank' href='"+link+"' class='hl' title='Operator : "+v1.devices_highlights.operator+"&#13; Vendor : "+v1.devices_highlights.company+" &#13; Diff : "+v1.devices_highlights.diff+" '>"+(v1.devices_highlights.mobile)+"</a>";
                    });
            
               HTML+="</div>";
              $('div#showhighlightdiv').append(HTML);
              
           });
           
            $('#showhighlightfieldset span').text("("+count+")");
         
        }
        else
        {
            $('#showhighlightfieldset').hide();
            console.log("No Diff highlights detected");
        }
    });
    
});


