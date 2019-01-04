 $(document).ready(function() {
  
    /*
     * Initialize Datepicker
     * Start
     */
    
        $('#selectdate').datepicker({
        format: "yyyy-mm-dd",
        startDate: "-365d",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
        });
        
    /*
     * End
     */    
        
        
        
     /*
      * Level 1 Click function
      * Start
      */   
     jQuery(document).on('click','table.modem_view_table td.level1',function(){
       
      
       
       currentelement=jQuery(this);
       
        supplier=jQuery(this).attr('data-operator').split('_');
     

        jQuery('tr.supplier_'+supplier[1]+'_'+supplier[2]).toggle('fast',function(){
        
               
                
                
                if($(this).is(':visible'))
                {
                    $(currentelement).find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                }
                else
                {
                   
                      $(currentelement).find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                      
                    /*
                    * Hide all next avaliable class sims , tr if parent class operators is closed
                    */  
                     if(jQuery('tr.supplier_'+supplier[1]+'_'+supplier[2]).next().filter('.sims').is(':visible'))
                     {
                       jQuery('tr.supplier_'+supplier[1]+'_'+supplier[2]).next().filter('.sims').hide();
                     }
                     /*
                      * Replace all - with  + when parent tr is directly closed without closing all child tr's 
                      */
                     if(jQuery('tr.supplier_'+supplier[1]+'_'+supplier[2]).find('i').hasClass('glyphicon-minus'))
                     {
                         jQuery('tr.supplier_'+supplier[1]+'_'+supplier[2]).find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                     }
                     
                
                }
                
              
                
        });
        
        
        
//        if(jQuery(this).find('i').hasClass('glyphicon-plus'))
//        {
//            console.log("Closed");
//        }
//        if(jQuery(this).find('i').hasClass('glyphicon-minus'))
//        {
//            console.log("Opened");
//            
//        }
        
        
        
        
       
});

    /*
     * End
     */


    /*
     * Level2 Click function
     * Start
     */
    jQuery(document).on('click','table div.level2',function(){
    
    currentelement=jQuery(this);
    
    $(this).parent().parent().next().toggle('fast',function(){
        
                if($(this).is(':visible'))
                {
                    $(currentelement).find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                }
                else
                {
                      $(currentelement).find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                }
      
        });
});

/*
 * End
 */

/*
 * Handling Switch views 
 * Start
 */


//    $('body').on('click', '.switch .btn-group button', function (e) {
//        $(this).addClass('active');
//        $(this).siblings().removeClass('active');
//           console.log($(e.target).attr('href'));
//        console.log("Hello");
//    });

/*
 * End
 */
    
    
/*
 * Handling Search by mobile
 * Start
 */  
      jQuery('input[name=searchbymobile]').keypress(function(e){
        
        mobile=$(this).val();
        
        if(e.keyCode==13)
        {
         jQuery('table.sims > tbody  > tr').each(function(){

           // if(jQuery(this).attr('data-mobile-no')===mobile)
            if(jQuery(this).attr('data-mobile-no').indexOf(mobile)>5 || jQuery(this).attr('data-mobile-no')==mobile)
            {
              
               /* 
                *  Triggers Click 
                */
                jQuery(this).parents('tr').prevAll().filter(':visible').not('.'+jQuery(this).attr('data-supplier-tr')).first().find('td:first.level1').trigger('click');
                
                jQuery(this).parents('tr').prev().find('div.level2').trigger('click');
               
               /*
                * Fixed Problem of replacing + sign to - 
                */
                jQuery(this).parents('tr').prevAll().filter(':visible').not('.'+jQuery(this).attr('data-supplier-tr')).first().find('td:first.level1').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                
                jQuery(this).parents('tr').prev().find('div.level2').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                
                
            }
            else
            {
                jQuery(this).hide();
            }
          });
         
        }
    });
    
/*
 * End
 */
    

/*
 * Handling Operator,Saleamt,Color Filter
 * Start
 */

$('input#filterbtn').on('click',function(){
    
    var operators=$('select#operators').val();
    
    var saleamtfrom=parseFloat($('input#saleamtFrom').val());
    var saleamtto=parseFloat($('input#saleamtTo').val());
    
    var color=$('select#color').val();
    
   var diffcheckbox=($('input#diffcheckbox:checked').val()=="on")?"on":"";
    
 


     function filterSaleamt(saleamtfrom,saleamtto)
     {
           var defer = $.Deferred();
           
                if((saleamtfrom > 0) && (saleamtto > 0) && (saleamtfrom < saleamtto))
                {

                    $('table.sims > tbody > tr').each(function(){

                      saleamt=$(this).attr('data-saleamt');

                               if($(this).parents('tr').prevAll().filter('tr.operators').first().is(':visible'))
                               {
                                            if(saleamt>=saleamtfrom && saleamt<=saleamtto)    
                                                {

                                                            if($(this).is(':visible'))
                                                            {

                                                            }
                                                            else
                                                            {
                                                            // Open Operator row  if not open

                                                                if( $(this).parents('tr').prevAll('.suppliers').filter(':visible').not('.'+$(this).attr('data-supplier-tr')).first().find('td:first.level1 i').hasClass('glyphicon-plus')) 
                                                                {
                                                                $(this).parents('tr').prevAll().filter(':visible').not('.'+$(this).attr('data-supplier-tr')).first().find('td:first.level1').trigger('click');

                                                                }

                                                            // Open Supplier row if not open

                                                                if($(this).parents('tr').prev('.suppliers').find('div.level2 i').hasClass('glyphicon-plus'))
                                                                {
                                                                $(this).parents('tr').prev('.suppliers').find('div.level2').trigger('click');

                                                                }

                                                                //Show particular sim

                                                                  $(this).show();

                                                                // Fix + - Signs

                                                                    fixSigns($(this));
                                                            }

                                                  


                                                }
                                            else
                                              {

                                                   $(this).attr('data-sale-flag','0');


                                                   $(this).hide();

                                                  // console.log($(this).attr('data-sale-flag'));


                                              }
                                }

                        saleamt=0;

                    }).promise().done(function(){

                                             console.log("Completed SaleAmt Loop");

                                             defer.resolve();

                                         });
                }
                 else
                         {
                              console.log("No SaleAmt  Selected");
                              
                              defer.resolve();

                         }
                         
                           return defer.promise();
    }
 


function filterColor(color)
{
       var defer = $.Deferred();
       
       
     if(color!="")
     {
         
            $('table.sims > tbody > tr').each(function(){
                    
                    // Check if parent operator row is visible
                    
                     if($(this).parents('tr').prevAll().filter('tr.operators').first().is(':visible'))
                     {
                         // Check if color matches 
                        
                         if(getHashCode($(this).css("background-color"))==color && $(this).attr('data-sale-flag')=="1" || ($(this).css("background-color")==color))
                         {
                                    // Check if already open
                           
                                    if($(this).is(':visible'))
                                    {
                                        
                                    }
                                    else
                                    {
                                         // Open Operator row  if not open
                                         
                                         if( $(this).parents('tr').prevAll().filter(':visible').not('.'+$(this).attr('data-supplier-tr')).first().find('td:first.level1 i').hasClass('glyphicon-plus')) 
                                         {
                                             $(this).parents('tr').prevAll().filter(':visible').not('.'+$(this).attr('data-supplier-tr')).first().find('td:first.level1').trigger('click');
                                        
                                         }
                                          
                                         // Open Supplier row if not open
                                         
                                         if($(this).parents('tr').prev('.suppliers').find('div.level2 i').hasClass('glyphicon-plus'))
                                         {
                                             $(this).parents('tr').prev('.suppliers').find('div.level2').trigger('click');
                                         
                                         }
                                         
                                         //Show particular sim
                                         
                                         $(this).show();
                                         
                                         // Fix + - Signs
                                         
                                         fixSigns($(this));
                                    }
                            
                         }
                         else
                         {
                                     // Check if already hidden
                                     $(this).hide();
                         }
                         
                     }
            }).promise().done(function(){

                                             console.log("Completed Color Loop");

                                             defer.resolve();

                                         });;
         
     }
     else
                         {
                              console.log("No Color  Selected");
                              
                              defer.resolve();

                         }
                         
    return defer.promise();
     
    }
    
        /*
         * Function to handle filter by operator,saleamt,color
         * Works like AND conditions
         * Start
         */
      
        $.when(resetSims(),resetLevels()).done(function(){
            
       console.log("All Done");
       setLoadingAnimation("Completing  Resetting . . . .",false);

                    $.when().then(function(){
                        setLoadingAnimation("Filtering Operators",true);  
                        filterOperators(operators);
                    }).then(function(){
                         setLoadingAnimation("Filtering Sale Amount",true);
                        filterSaleamt(saleamtfrom,saleamtto);
                    }).then(function(){
                         setLoadingAnimation("Filtering Colors",true);  
                        filterColor(color);
                    }).done(function(){
                         setLoadingAnimation("Performing Post filter activities",true);
                         doPostFilterActivities();
                         setLoadingAnimation("Preparing View . . .",false);
                    });
         }); 
         
         /*
          * End
          */
         
         /*
          * Function to handle filter by diff
          * Note : Diff filter works individually 
          */
         if(diffcheckbox=="on")
         {
             $.when(resetSims(),resetLevels()).done(function(){
                 
                 setLoadingAnimation("Completing  Resetting . . . .",false);
                  
                 $.when(filterdiff()).then(function(){
                     setLoadingAnimation("Done Filtering Diff's",true);  
                 }).done(function(){
                         setLoadingAnimation("Performing Post filter activities",true);
                         doPostFilterActivities();
                         setLoadingAnimation("Preparing View . . .",false);
                    });
                 
             });
         }
         
         /*
          * End
          */
         
         function filterdiff()
         {
              var defer = $.Deferred();
              
              $('table.sims > tbody > tr').each(function(){
                  
                   diff=0;
                    
                  diff=parseInt($(this).data('diff'));
                  
                        if((diff!=0) && ((diff<=-100) || (diff>=90)))
                        {
                                   console.log(diff);    
                                   
                                  // Open Operator row  if not open

                                     if( $(this).parents('tr').prevAll().filter(':visible').not('.'+$(this).attr('data-supplier-tr')).first().find('td:first.level1 i').hasClass('glyphicon-plus')) 
                                     {
                                     $(this).parents('tr').prevAll().filter(':visible').not('.'+$(this).attr('data-supplier-tr')).first().find('td:first.level1').trigger('click');

                                     }

                                 // Open Supplier row if not open

                                     if($(this).parents('tr').prev('.suppliers').find('div.level2 i').hasClass('glyphicon-plus'))
                                     {
                                      
                                        $(this).parents('tr').prev('.suppliers').find('div.level2').trigger('click');
                                       

                                     }


                                     //Show particular sim

                                     $(this).show();

                                     // Fix + - Signs

                                      fixSigns($(this));
                                      
                            
                        }
                        else
                        {
                                     $(this).hide();
                        }
                        
                
                  
              }).promise().done(function(){

                                             console.log("Completed filter Diff Loop");

                                             defer.resolve();

                                         });
                                         
               return defer.promise();                            
         }
         
         
         


   
function doPostFilterActivities()
{
    $('table.sims > tbody').each(function(){

        /*
         * Check if individual tr is display none because by default all sims tr are visible but their parent are invisble so they dont appear
         */
        if($(this).filter(function(){return $(this).css('display') !== 'none';}).length<=0)
         {
             $(this).parents('tr.sims').hide();
             $(this).parents('tr.sims').prev('.suppliers').first().find('div.level2 i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
             $(this).parents('tr.sims').prev('.suppliers').first().hide(); 
         }

    });

}
   

    
});

/*
 * End
*/
    
/*
 * Filter Operators Function
 * Start
 */
   function filterOperators(operators)
                {
                      var defer = $.Deferred();
                      
                      if(operators > 0)
                            {
                                
                                $('table.modem_view_table > tbody > tr').each(function(){


                                                if($(this).hasClass('operators') && $(this).attr('data-operator-id')===operators)
                                                {
                                                    $(this).show();
                                                }
                                                else
                                                {
                                                      $(this).hide();
                                                }


                                         }).promise().done(function(){

                                             console.log("Completed Operator Loop");

                                             defer.resolve();

                                         });
                         }
                         else
                         {
                              console.log("No Operator Filter Selected");
                              
                              defer.resolve();

                          }
                         
                           return defer.promise();
                 }
/*
 * End
 */


/*
 * Fix + - Signs depending upon open & close state
 * Start
 */    


function fixSigns(ele)
{
   
        ele.parents('tr').prevAll().filter(':visible').not('.' + ele.attr('data-supplier-tr')).first().find('td:first.level1').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');

        ele.parents('tr').prev().find('div.level2').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        
}

/*
 * End
 */


/*
 * Reset Button task
 * Start
 */


$('.resetbtn').on('click',function(){
    
 $('select#operators').val('');
 $('select#color').val('');
 $('input#saleamtFrom').val('');
 $('input#saleamtTo').val('');
 $('input#suppliername').val('');
 $('input#mobile').val('');
 $('#diffcheckbox').attr('checked',false);
 
   $.when(resetSims(),resetLevels()).done(function(){
       console.log("All Done");
       setLoadingAnimation("Completing  Resetting . . . .",false);
   });

});

    function resetSims()
    {
           var d = $.Deferred();
           
         $('table.sims > tbody > tr ').each(function(){$(this).show(); $(this).attr('data-sale-flag','1');}).promise().done(function(){

                                             console.log("Completed Resetting Sims");
                                             
                                              setLoadingAnimation("Resetting Sims . . . ",true);

                                             d.resolve();

                                        

                                         });
                                         
                                         
               return d.promise();                               
                                         
                                       
    }
    
    
    function resetLevels()
    {
       var d2 = $.Deferred();
       
    $('table.modem_view_table > tbody > tr').each(function(){
        
        if($(this).hasClass('operators'))
        {
            $(this).show();
            
            if($(this).find('td.level1 i').hasClass('glyphicon-minus'))
            {
                $(this).find('td.level1').trigger('click');
                $(this).find('td.level1').find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
            }
         
        }
        
        if($(this).hasClass('suppliers'))
        {
            
            if($(this).is(':visible'))
            {
               $(this).find('div.level2').trigger('click');
               $(this).find('div.level2').find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
               $(this).hide();
            }
        
           
        }
             
        
       if($(this).hasClass('sims') && $(this).is(':visible'))
        {
         
            $(this).hide();
            
        }
        
        
     }).promise().done(function(){

                                             console.log("Completing Resetting Levels ");
                                             
                                               setLoadingAnimation("Resetting Levels . . . ",true);

                                             d2.resolve();

                                        

                                         });
                                         
         return d2.promise();                                     
     
    }

/*
 * End
 */


/*
 * FIlter by Suppliername
 * Start
 */
$('input[name=suppliername]').keypress(function(e){
    
        var suppliername=$('input#suppliername').val();
        var operators=$('select#operators').val();
         if(e.keyCode==13)
         {
//             $.when(filterSupplier(suppliername)).done(function(){
//                    console.log("Done Filtering by Name");
//             });


          $.when(filterOperators(operators)).then(function(){
                filterSupplier(suppliername).done(function(){
                    console.log("Done Filtering by Name . . ");
             });
          });

           

        
         }
});


  function filterSupplier(suppliername)
    {
        
        var defer = $.Deferred();
        
            if(suppliername!="")
            {
                 $('table.modem_view_table > tbody').children('tr.suppliers').each(function(){
                     
              
                 
                   
                     if($(this).prevAll('.operators').first().is(':visible'))
                     {
                     
                         if($(this).attr('data-supplier-name')==suppliername ||  $(this).attr('data-supplier-name').indexOf(suppliername)>=0)
                         {
                             
                             
                             $(this).show();
                             
                             $(this).prevAll('.operators').filter(':visible').first().find('td:first.level1').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                             
                         }
                         else
                          {
                              
                                $(this).hide();
                              
                          } 
                         
                     }
                  

                     
                 }).promise().done(function(){
                     
                         console.log("Completed Suppliername Loop");

                          defer.resolve();
                 });
            }
            else
            {
                console.log("No Supplier Selected");
                
                   defer.resolve();
            }
        
        return defer.promise();
    }
    
    
    
/*
 * End
 */

$('#addcommentmodal').on('show.bs.modal', function(e) {

     
      var oprid = $(e.relatedTarget).data('oprid');
      var vendorid = $(e.relatedTarget).data('vendorid');
      var scid = $(e.relatedTarget).data('scid');
      var commentdate = $(e.relatedTarget).data('commentdate');
            
     $(e.currentTarget).find('input#opr_id').val(oprid) ;
     $(e.currentTarget).find('input#vendor_id').val(vendorid) ;
     $(e.currentTarget).find('input#scid').val(scid) ;
     $(e.currentTarget).find('input#commentdate').val(commentdate) ;

     $(e.currentTarget).find('textarea[name=comment]').val('');
     $(e.currentTarget).find('div#simcommentmsg').empty();
     $(e.currentTarget).find('div#loadcomments').empty();
     
     var comments=$.getJSON(HOST+'sims/getSimComments',{opr_id:oprid,vendor_id:vendorid,scid:scid,comment_date:commentdate});
     
     comments.done(function(res){
         
          if(res.type && res.status)
          {
              $.each(res.comments,function(k,v){
                         var HTML="";
                         HTML="<div class='row' style='margin-bottom: 5px;'>";
                         HTML+="<small class='pull-right time'><i class='fa fa-clock-o'></i>&nbsp;"+v.comment_date+" / "+v.comment_timestamp+"</small>";
                         HTML+="<h5 class='media-heading notesusername'>"+v.name+"</h5>";
                         HTML+="<small class='col-lg-l0'>"+v.comment+"</small>";
                         HTML+="</div>";
                         
                         $('div#loadcomments').append(HTML);
                         
              })
          }
          else
          {
               $('div#loadcomments').html("No Comments Yet");
          }
          
     });
    });
    
    $(document).on('click','button#addcommentbtn',function(){
    
    
       var  oprid=$('div#addcommentmodal').find('input#opr_id').val();
       var  vendorid=$('div#addcommentmodal').find('input#vendor_id').val();
       var  scid=$('div#addcommentmodal').find('input#scid').val();
       var  commentdate=$('div#addcommentmodal').find('input#commentdate').val();
       var  comment=$('div#addcommentmodal').find('textarea#comment').val();
  
        if(comment==""){
           alert("Invalid Comment");
           return;
       }
       var addComment=$.post(HOST+'sims/saveComments',{opr_id:oprid,vendor_id:vendorid,scid:scid,comment_date:commentdate,comment:comment});
       
       addComment.done(function(res){
           
             res=$.parseJSON(res);
             
             if(res.type && res.status)
             {
                    alert(res.msg);
                    $('div#addcommentmodal').modal('hide');
             }
             else
             {
                     alert("Error");
             }
           
       });
    });


    

   });
   
   /*
 * Handling Progress Bar events
 * Start
 */

function setLoadingAnimation(msg,status)
{
    
    var $bar=$('div#progressdiv');
    if(status)
    {
    
        $bar.show();
        $bar.find('span').text(msg);
    }
    else
    {
        $bar.find('span').text(msg);
        
        setTimeout(function(){ $bar.hide();
         $bar.find('span').text('');},500);
        
    }
    
   
}

/*
 * Get HashCode based on RGB
 * Start
 */

function getHashCode(colorval) {
 
if(colorval=="transparent" || colorval==null || typeof colorval==="undefined" || colorval=="rgba(0, 0, 0, 0)")
{
    return ;
}    
var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

delete(parts[0]);

for (var i = 1; i <= 3; ++i) {
parts[i] = parseInt(parts[i]).toString(16);
if (parts[i].length == 1) parts[i] = '0' + parts[i];
}
color = '#' + parts.join('');

return color;

}

/*
 *  End 
 */



/*
 *Get Last 36 hrs Timestamp 
 */
//moment().format('YYYY-MM-DD HH:m:s'); 

function getLast36HrsTimestamp()
{
    var mydate=new Date();
    mydate.setHours(mydate.getHours()-36);

    var month = ('0' + (mydate.getMonth() + 1)).substr(-2);
    var day = ('0' + mydate.getDate()).substr(-2);
    var hour = ('0' + mydate.getHours()).substr(-2);
    var minute = ('0' + mydate.getMinutes()).substr(-2);
    var second = ('0' + mydate.getSeconds()).substr(-2);

    // Same as in PHP date('Y-m-d H:i:s')
    dateInPhpFormat = mydate.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;

    last36hrstimestamp=new Date(dateInPhpFormat).getTime()/1000;
    
    return last36hrstimestamp;
}

function getJavascriptEquivalentPhpTimestamp(rowtimestamp)
{
    //2015-04-21 10:31:21
    
    displayedrowtimestamp=new Date(rowtimestamp).getTime()/1000;
    
    return displayedrowtimestamp
}

/*
 * End
 */



/*
 * Send Sms Click Event 
 * Start
 */

$(document).on("click", ".open-smsmodal", function () {
    
     var vendorid = $(this).data('vendorid');
     var simid = $(this).data('simid');
     
     $("#sendSmsModal input[name=vendorid]").val(vendorid);
     $("#sendSmsModal input[name=simid]").val(simid);
     
});



 $(document).ready(function() {
    $( "form[name=sendsmsForm]" ).submit( function( event ) {
      event.preventDefault();
       
        dataString={type:1,device:$('#sendSmsModal input[name=simid]').val(),vendor:$('#sendSmsModal input[name=vendorid]').val(),mobile:$('input[name=recipient]').val(),msg:encodeURIComponent($('textarea[name=message]').val())};
         var url = '/panels/modemRequest/';
            $.ajax({
                             type:"GET",
                             url:url,
                             datatype:"json",
                             data:dataString,
                             success:function(data){
                          
                                 try {
                                        data = JSON.parse(data);
                                        } 
                             catch (e)
                                       {
                                     data = {'status':"failure",'errno':'00','response':'Some error occured'};
                                      }
                                      
                                 if( data.status && data.status == 'success' ){
                                     alert("Done !");
                                 }else if( data.status && data.status == 'failure' ){
                                     alert(data.data);
                                 }else{
                                     alert("Some error occured !");
                                 }

                             }
                         });
        
      return false;
    });
});

/*
 * End
 */

/*
 * Send Atcmd  Click Event 
 * Start
 */

$(document).on("click", ".open-atmodal", function () {
    
     var vendorid = $(this).data('vendorid');
     var simid = $(this).data('simid');
     
     $("#sendAtModal input[name=vendorid]").val(vendorid);
     $("#sendAtModal input[name=simid]").val(simid);
     
});



$(document).ready(function() {
    $( "form[name=sendatForm]" ).submit( function( event ) {
      event.preventDefault();
       
        dataString={type:2,device:$('#sendAtModal input[name=simid]').val(),vendor:$('#sendAtModal input[name=vendorid]').val(),wait:$('input[name=cmd_time]').val(),cmd:encodeURIComponent($('textarea[name=cmd]').val())};
         var url = '/panels/modemRequest/';
            $.ajax({
                             type:"GET",
                             url:url,
                             datatype:"json",
                             data:dataString,
                             success:function(data){
                          
                                 try {
                                        data = JSON.parse(data);
                                        } 
                             catch (e)
                                       {
                                     data = {'status':"failure",'errno':'00','response':'Some error occured'};
                                      }
                                      
                                 if( data.status && data.status == 'success' ){
                                     alert("Done !");
                                 }else if( data.status && data.status == 'failure' ){
                                     alert(data.data);
                                 }else{
                                     alert("Some error occured !");
                                 }

                             }
                         });
        
      return false;
    });
});

/*
 * End
 */

/*
 * Send USSD  Click Event 
 * Start
 */

$(document).on("click", ".open-ussdmodal", function () {
    
     var vendorid = $(this).data('vendorid');
     var simid = $(this).data('simid');
     
     $("#sendUssdModal input[name=vendorid]").val(vendorid);
     $("#sendUssdModal input[name=simid]").val(simid);
     
});



 $(document).ready(function() {
    $( "form[name=sendussdForm]" ).submit( function( event ) {
      event.preventDefault();
       
        dataString={type:3,device:$('#sendUssdModal input[name=simid]').val(),vendor:$('#sendUssdModal input[name=vendorid]').val(),wait:$('input[name=ussd_time]').val(),ussd:encodeURIComponent($('textarea[name=ussd]').val())};
         var url = '/panels/modemRequest/';
            $.ajax({
                             type:"GET",
                             url:url,
                             datatype:"json",
                             data:dataString,
                             success:function(data){
                          
                                 try {
                                        data = JSON.parse(data);
                                        } 
                             catch (e)
                                       {
                                     data = {'status':"failure",'errno':'00','response':'Some error occured'};
                                      }
                                      
                                 if( data.status && data.status == 'success' ){
                                     alert("Done !");
                                 }else if( data.status && data.status == 'failure' ){
                                     alert(data.data);
                                 }else{
                                     alert("Some error occured !");
                                 }

                             }
                         });
        
      return false;
    });
});

/*
 * End
 */


/*
 * Send Reset   Click Event 
 * Start
 */

$(document).on("click", ".open-resetmodal", function () {
    
     var vendorid = $(this).data('vendorid');
     var simid = $(this).data('simid');
     
     $("#sendResetModal input[name=vendorid]").val(vendorid);
     $("#sendResetModal input[name=simid]").val(simid);
     
});



 $(document).ready(function() {
    $( "form[name=sendresetForm]" ).submit( function( event ) {
      event.preventDefault();
       
        dataString={type:4,device:$('#sendResetModal input[name=simid]').val(),vendor:$('#sendResetModal input[name=vendorid]').val()};
         var url = '/panels/modemRequest/';
            $.ajax({
                             type:"GET",
                             url:url,
                             datatype:"json",
                             data:dataString,
                             success:function(data){
                          
                                 try {
                                        data = JSON.parse(data);
                                        } 
                             catch (e)
                                       {
                                     data = {'status':"failure",'errno':'00','response':'Some error occured'};
                                      }
                                      
                                 if( data.status && data.status == 'success' ){
                                     alert("Done !");
                                 }else if( data.status && data.status == 'failure' ){
                                     alert(data.data);
                                 }else{
                                     alert("Some error occured !");
                                 }

                             }
                         });
        
      return false;
    });
});

/*
 * End
 */

/*
 * Run Show/Hide
 * Start
 */
    function runShowHide(dev , vendor ,opr,bal,flag){
         //var url = '/shops/modemRequest/';
            var i = true;
            var url = '/panels/modemRequest/';
            var operator = parseInt(opr);
            var opr_arr = [16,17,18,19,20,21];//DTH operators id
            var found = $.inArray(operator, opr_arr);
            
            if (found != -1&& bal > 100)//For DTH operator, blnc < 100
            {
                alert("Balance should be less than 100 to hide this sim");
                i = false;
                //return false;
            }
            else if(found == -1 && bal >10)//For non DTH operator, blnc < 10
            {
               alert("Balance should be less than 10 to hide this sim");
               i = false;
               //return false;  
            }
            else
            {
                i=true;
                //alert('Done');
            }
            if(!i)
            {
                return;
            }
            
        var data = "type=7"+"&device="+dev+"&vendor="+vendor+"&opr_id="+opr+"&flag="+flag;//query="+jQuery('#ussd_qry').val()+"&
        jQuery.ajax({
                type:"GET",
                url:url,
                data:data,
                success:function(data){
                    if( data == 'success' )
                    {
                        alert("Success!");
                    }
                    else if( data == 'failure' )
                    {
                        alert("Unable to hide");
                    }
                    else
                    {
                        alert("Some error occured !"); 
                    }
                }
            });
    }
/*
 * End
 */

/*
 * Download Transactions event
 * Start 
 */


$(document).on("click", ".open-downloadtransactionsmodal", function () {
    
     var address = $(this).data('address');
  
     
     $("#downloadTransactionsModal input[name=address]").val(address);
    
     
});

$(document).ready(function() {
    $( "form[name=sendDownloadTransactionsForm]" ).submit( function( event ) {
      
       event.preventDefault();
      
      address=$('#downloadTransactionsModal input[name=address]').val()
      date=$('#downloadTransactionsModal input[name=transactionDate]').val()
      
       var url = "http://"+address+"/start.php?query=download&date="+date;
        
       window.location.href=url;
  });
  });

/*
 * End
 */


/*
 * Start/Stop Device
 * Start 
 */
         function stopDevice(id,flag,vendor,ele){
             

                               parenttd=$(ele).parent();
                               
                                $(ele).html('Submitting');
                                
                                var url = '/sims/stopModemDevice';
                                
                                var params = {'device' : id,'stop': flag,'vendor': vendor};
                                 jQuery.ajax({
                                                    type:"POST",
                                                    url:url,
                                                    data:params,
                                                    success:function(data)
                                                    {
                                                      //  var html = data.responseText;
//                                                        alert(data);
                                                        $(ele).remove();
                                                        $(parenttd).html('Done');
                                                    },
                                                    error:function()
                                                    {
                                                        alert("Error");
                                                    }
                                                });
	}   

/*
 * End
 */



/*
 * Submiting Search Form 
 * Start
 */
$(document).ready(function(){ $('select#modem_id').multipleSelect({ selectAll: true, width: 380,multipleWidth: 170,multiple: true});});
      
        
//function submitForm()
//{
//    modemsIds=$("select#modem_id").multipleSelect("getSelects");
//    selectedDate=$("input[name=searchbydate]").val();
//    
//    console.log(modemsIds);
//    console.log(selectedDate);
//    
//    window.location.href="/sims/"+"?modem_id="+modemsIds+"&searchbydate="+selectedDate;
//}

/*
 * End
 */
   
 $(function(){
     
      $("#filterform").on('submit',function(e) {       
              e.preventDefault();
              var urlstring=$("#filterform").serialize();
              var  modemsIds=$("select#modem_id").multipleSelect("getSelects");
              var selectedDate=$("input[name=searchbydate]").val();
              
              filter = $("#filterform").serializeArray();
              var flag = 1;
              for(var i=1; i <= 8; i++) {
                  if(filter[i]['value'] != '') {
                      flag = 0;
                      break;
                  }
              }
              
//             console.log("/sims/"+"?modem_id="+modemsIds+"&searchbydate="+selectedDate+"&"+urlstring+"&mem="+flag);
             window.location.href="/sims/"+"?modem_id="+modemsIds+"&searchbydate="+selectedDate+"&"+urlstring+"&mem="+flag;
             return false;
    });
     
 });  
   
   
   $(window).load(function(){
       
       
//       $.when(getApiModemList()).done(function(vendors){
//           
//                var deferreds = calculateApiBalances(vendors.data);
//            
//                 var apibalance=0;
//                
//                 var modembalance=0;
//            
//                $.when.apply($,$.map(deferreds,function(n){
//                 return n.then(null,function(){
//                     return $.Deferred().resolveWith(this, arguments);
//                 });
//                })).done(function() {
//                                
//                                                        $.each(deferreds,function(k,v){
//
//                                                            if(v.status=='200' && v.responseJSON.balance!="")
//                                                            {
//                                                                apibalance+=parseInt(v.responseJSON.balance);
//                                                            }
//
//                                                        });
//                                                  
//                                               $('ul#apibalancesul').append('<li><b>Total Api Modem Balance [A]: </b>'+ReplaceNumberWithCommas(apibalance.toFixed(2))+'</li>');        
//                                                  
//                                               $('span.permodembalance').each(function() {
//                                                    modembalance+=parseInt($(this).data('balance'));
//                                                });
//                                                
//                                                  $('ul#apibalancesul').append('<li><b>Total  Modem Balance [B] : </b>'+ReplaceNumberWithCommas((modembalance).toFixed(2))+'</li>');
//                                                
//                                                    $('ul#apibalancesul').append('<li><b>Total  Balance [A+B]: </b>'+ReplaceNumberWithCommas((apibalance+modembalance).toFixed(2))+'</li>');       
//                         
//                                             });
//           
//       });
//       
//      
//       
//      
//        
//           function getApiModemList()
//            {
//                return $.getJSON("/sims/getApiVendorsDetails");
//            }
//            
//            function calculateApiBalances(vendors)
//            {
//                  var defer=[];
//                  
//                  var apibalancetotal;
//                  
//                   $.each(vendors,function(k,v){
//                       
//                       defer.push($.getJSON("/sims/"+v.vendors.shortForm+"Balance"+"/1").done(function(res){
//                           
//                           $('ul#apibalancesul').append('<li>'+v.vendors.company+' Balance : '+ReplaceNumberWithCommas(res.balance)+'</li>');
//                           
//                       }));
//
//      
//                   });
//                   
//                   
//                   return defer;
//            }
        
   });
   
   
   function ReplaceNumberWithCommas(yourNumber) {
    //Seperates the components of the number
    var n= yourNumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

function showAllSims()
{

   $('table.modem_view_table > tbody > tr.operators').each(function(){
        $(this).find('td:first.level1').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
    });
    
    $('table.modem_view_table > tbody > tr.suppliers').each(function(){
        $(this).show();
        $(this).find('td:first div.level2').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        $(this).next('.sims').show();
    });
    

}

/*
 * Edit Incoming
 * Start
 */


    

function showUpdateHtml(device_id)
{
    
    $(document).ready(function(){
    
    jQuery('div#original_val_'+device_id).hide();
   
    jQuery('#ele_'+device_id).remove();
    
    originalIncoming=$('div#original_val_'+device_id).data('originalincoming');
            
    html="<div id='ele_"+device_id+"'><input type='text' value='"+originalIncoming+"' name='text_"+device_id+"'  /><button class='btn btn-xs btn-info'  id=\"btn_"+device_id+"\" onclick=\"saveUpdatedincoming('"+device_id+"',this);\">Update</button><button onclick=\"jQuery('div#original_val_'+'"+device_id+"').show();jQuery('div#ele_'+'"+device_id+"').hide();jQuery('span#updated_val_'+'"+device_id+"').find('a').show();\">Cancel</button></div>";
    
    
     jQuery('span#updated_val_'+device_id).find('a').hide();
    
    jQuery('span#updated_val_'+device_id).append(html);
    
    });
}

function saveUpdatedincoming(device_id,btn)
{

    $(document).ready(function(){
    
   
     
     amount=parseFloat($('input[name=text_'+device_id+']').val());
     
     diff=$('td#diff_'+device_id).data('diff')==""?0:parseFloat($('td#diff_'+device_id).data('diff')); 
   //  cur=parseFloat(jQuery('td#cur_'+device_id).text()); 
     cur=$('td#cur_'+device_id).data('cur')==""?0:parseFloat($('td#cur_'+device_id).data('cur'));  
     sale=$('td#sale_'+device_id).data('sale')==""?0:parseFloat($('td#sale_'+device_id).data('sale')); 
     opening=$('td#opening_'+device_id).data('opening')==""?0:parseFloat($('td#opening_'+device_id).data('opening')); 
     
    closing=$('td#closing_'+device_id).data('closing')==""?0:parseFloat($('td#closing_'+device_id).data('closing')); 
    
    vendor_id=jQuery('td#cur_'+device_id).data('vendorid');
    
    // Get Old Incoming
   var oldbal=$('div#original_val_'+device_id).attr('data-originalincoming');

    // Extra info for inserting data into inv_simdata
        console.log("Device Id "+device_id);
    $('button#btn_'+device_id).addClass('disabled');
       console.log('button#btn_'+device_id);
    mobile=jQuery('td#mobile_'+device_id).data('mobile');
    operator_id=jQuery('td#mobile_'+device_id).data('oprid');
    
        console.log("Mobile : "+mobile);
        console.log("Operator Id  : "+operator_id);
    
        console.log("Diff : "+diff);
        console.log("Cur : "+cur);
        console.log("Sale : "+sale);
        console.log("Opening : "+opening);
        console.log("Closing : "+closing);
        console.log("Amount : "+amount);
        var sendmailflag='0';
        var tempdiff=0;
    if(closing>0)
    {
        // expectedIncoming=sale-opening+closing-diff;
         console.log("Previous");
         
         expectedIncoming=sale+closing-opening-amount;
         
         if(expectedIncoming<0){
             expectedIncoming=expectedIncoming+500;
         }
         tempdiff=parseFloat(sale+closing-opening-amount);
         
         if(tempdiff<-100){sendmailflag='1'};
         
         console.log("Expected Incoming : "+expectedIncoming);
         console.log("Entered Amount : "+amount);
        
    }
    else
    {
        console.log("Current");
     
      expectedIncoming=sale+cur-opening-amount;
      
      if(expectedIncoming<0){
             expectedIncoming=expectedIncoming+500;
         }
         
          tempdiff=parseFloat(sale+cur-opening-amount);
          
         if(tempdiff<-100){sendmailflag='1'};
         
         console.log("Expected Incoming : "+expectedIncoming);
         console.log("Entered Amount : "+amount);
    }
    
    var isValidated=false;
    var isRoot=$.cookie('isRoot');
    
      if((amount>=0)  &&  ((expectedIncoming)>=0) && (amount%1== 0)){
          isValidated=true;
      }else{
           isValidated=false;
      }
     
    console.log("Is Validated : "+isValidated); 
    console.log("Is Root : "+isRoot); 
        
    if(((amount>=0)  &&  ((expectedIncoming)>=0) && (amount%1== 0)) ||  isRoot=="1")
   // if( ((expectedIncoming)>=0) && (amount%1== 0))
    {
        
       var r=true;
       
        if(!isValidated){
            r=confirm('Your Incoming doesnot pass validation .Do you still want to continue ? ');
        }
        
        if(!r){ return};
        
        console.log({device_id:device_id,bal:$('input[name=text_'+device_id+']').val(),date:$('#selectdate').val(),vendor_id:vendor_id});
        
        var actualdevice_id=device_id.split('_');
        
        if(!($.isNumeric(actualdevice_id[1])) ||  !(actualdevice_id[1]>0) || !(actualdevice_id[0]>0) )
        {
            alert("Error : No Machine Id  detected for particular sim");
            return;
        }
        
                    console.log({device_id:actualdevice_id[0],oldbal:oldbal,bal:$('input[name=text_'+device_id+']').val(),date:$('#selectdate').val(),vendor_id:vendor_id,mobile:mobile,operator_id:operator_id,sendmail:sendmailflag,diffamount:tempdiff,expectedIncoming:expectedIncoming});
                    
    $.ajax({
                    type:'post',
                     url   :'/sims/updateIncomingManually',
                     data:{device_id:actualdevice_id[0],oldbal:oldbal,bal:$('input[name=text_'+device_id+']').val(),date:$('#selectdate').val(),vendor_id:vendor_id,mobile:mobile,operator_id:operator_id,sendmail:sendmailflag,diffamount:tempdiff,expectedIncoming:expectedIncoming},
                     dataType:"json",
                     success:function(res)
                     {
                         
                        if(res.data=="Incoming Update Success")
                        {
                            $('div#original_val_'+device_id).attr('data-originalincoming',$('input[name=text_'+device_id+']').val());
                        }
                        
                        alert(res.data);
                        
                        if(res.data=="Unable to find records")
                        {
                            $('div#original_val_'+device_id).show();
                            $('div#ele_'+device_id).hide();
                            $('span#updated_val_'+device_id).find('a').show();
                            return ;
                        }   
                        
                        $('#ele_'+device_id).hide();    
                        $('#original_val_'+device_id).show();
                     
                        $('#original_val_'+device_id).text(amount);
                        $('#updated_val_'+device_id+' a ').show();
                        
                       
                    }
               });
               
            //  $('button#btn_'+device_id).removeClass('disabled');
      }
      
      else
      {
          alert(" Invalid Incoming");
          
        $('button#btn_'+device_id).removeClass('disabled');
    }
    });
}


/*
 * End
 */

/*
 * Individual Show/Hide All functionality
 * Start
 */
$(document).ready(function(){
    
        $('button.btn-showall').on('click',function(){

         
            id=$(this).data('modemid');

             $('table.modem_view_table#'+id+' > tbody > tr.operators').each(function(){
                $(this).find('td:first.level1').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            });

            $('table.modem_view_table#'+id+' > tbody > tr.suppliers').each(function(){
                $(this).show();
                $(this).find('td:first div.level2').find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                $(this).next('.sims').show();
            });

        });
        
        $('button.btn-hideall').on('click',function(){
             
              id=$(this).data('modemid');
              
                //Resetting Sims individually
                $('table.sims_'+id+' > tbody > tr ').each(function(){$(this).show();});
               
               
               //Resetting Levels individually
                $('table.modem_view_table#'+id+'  > tbody > tr').each(function () {

                    if ($(this).hasClass('operators'))
                    {
                        $(this).show();

                        if ($(this).find('td.level1 i').hasClass('glyphicon-minus'))
                        {
                            $(this).find('td.level1').trigger('click');
                            $(this).find('td.level1').find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                        }

                    }

                    if ($(this).hasClass('suppliers'))
                    {

                        if ($(this).is(':visible'))
                        {
                            $(this).find('div.level2').trigger('click');
                            $(this).find('div.level2').find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                            $(this).hide();
                        }


                    }


                    if ($(this).hasClass('sims') && $(this).is(':visible'))
                    {

                        $(this).hide();

                    }


                })
             
         });
         
})
/*
 * End
 */


/*
 * Individual Modem Refresh
 * Start
 */
$(document).ready(function(){
    
    $('button.btn-refresh-individual-modem').on('click',function(){
        
        modem_id=$(this).data('modemid');
        
    });
    
});
/*
 * End
 */


/*
 * Start Loading Success Failure Tiles of Modems
 * Start
 */
$(document).ready(function(){

    var isDistributer=$.cookie('isDistributer');
    console.log("isDistributer : "+isDistributer);
   if(isDistributer=="0")
    {
    var ModemwisesuccessfailureJSON=$.getJSON(HOST+'sims/getOperatorWiseSuccessFailureReports/modemview');
    
    ModemwisesuccessfailureJSON.done(function(res){
        
         var currenttimestamp=new Date().getTime()/1000;
       
        $.each(res.modemwisesuccessfailure,function(k,v){
            
                 var bgcolor="";
                
                if(currenttimestamp-getJavascriptEquivalentPhpTimestamp(v.timestamp)>900){bgcolor="#c73525"}else{bgcolor="#99ff99"};
                
                var html="";
                html+="<div class='col-lg-3 divblockmodemsuccessfailure' style='background-color:"+bgcolor+"'>";
                html+="<ul class='divblockul'>";
                html+="<li><b>"+v.shortForm+"</b></li>";
                html+="<li>"+v.timestamp+"</li>";
                html+="</ul>";
                html+="</div>";
                
                $('div#modemwisesuccessfailurediv').append(html);
                
        });
        
    });
    
    }
    
    function getJavascriptEquivalentPhpTimestamp(rowtimestamp)
{
    //2015-04-21 10:31:21
    rowtimestamp=rowtimestamp.replace(/-/g,"/");
    
    var displayedrowtimestamp="";
    
    displayedrowtimestamp=new Date(rowtimestamp).getTime()/1000;
    
    return displayedrowtimestamp;
}
    
});

$(document).on("click", ".open-rtmodal", function () {
    
        var vendorid = $(this).data('vendorid');
        var simid = $(this).data('simid');
        var operatorid = $(this).data('operatorid');
        var rt = $(this).data('rt');

        $("#rechargeType input[name=recharge_type]").prop('checked', false);
        $("#rechargeType input[name=vendorid]").val(vendorid);
        $("#rechargeType input[name=simid]").val(simid);
        $("#rechargeType input[name=operatorid]").val(operatorid);
        $("#rechargeType #rt_"+rt).prop('checked', true);
     
});

$(document).ready(function() {
    $( "form[name=rechargeType]" ).submit( function( event ) {
        event.preventDefault();
       
        dataString = {
            recharge_type: $('#rechargeType input[name=recharge_type]:checked').val(),
            device: $('#rechargeType input[name=simid]').val(),
            vendor: $('#rechargeType input[name=vendorid]').val(),
            operatorid: $('#rechargeType input[name=operatorid]').val()
        }
        
        var url = '/sims/rechargeType/';
        
            $.ajax({
                    url: url,
                    data: dataString,
                    type: "POST",

                    success:function(data){
//                        $('form[name=rechargeType] .clo').click();
                        if(data == 'success') {
                            alert('Data Submitted Successfully !!!');
                        } else {
                            alert('Error');
                        }
                    },
                    
                    error: function() {
//                        $('form[name=rechargeType] .clo').click();
                        alert('Error');
                    }
            });
        
      return false;
    });
});
/*
 * End
 */






   
   
