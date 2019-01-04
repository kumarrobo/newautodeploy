
$(function(){

    var clickcount = 2;
    
    var  HTML=compressedHTML;
    
    /* Add one operator div by default  */
    
    /* START */
    HTML.find('div.col-lg-12').wrap("<div id='so_1'></div>");

    $('div#mappingdiv').append(HTML.html());

    HTML.find('div.col-lg-12').unwrap("<div id='so_2'></div>");
  /* END */

    
    
    $('button#btn_map_operators').on('click',function(){
        
          
             HTML.find('div.col-lg-12').wrap("<div id='so_"+clickcount+"'></div>");

             $( "<button type='button' class='removeme' onClick='removeMe(\""+clickcount+"\")'>Remove Me</button>" ).insertBefore( HTML.find('div.col-lg-12') );

                            $('div#mappingdiv').append(HTML.html());
             
            HTML.find('div.col-lg-12').unwrap("<div id='so_"+clickcount+"'></div>");
            
            HTML.find('button.removeme').remove();

            clickcount++;
            
       
});



});


$(function(){
    
     var clickcount = 2;
    
    var  HTML=contactHTML;
    
    $('button#btn_addcontact').on('click',function(){
    
            HTML.find('div.row').wrap("<div id='contact_"+clickcount+"'></div>");
    
            HTML.find('div.col-sm-2').append("<button type='button'  class='removeContact btn btn-sm btn-default' onClick='removeContact(\""+clickcount+"\")'>(-)</button>");
            
                     $('div#alternateContactDiv').append(HTML.html());
                     
             HTML.find('div.row').unwrap("<div id='contact_"+clickcount+"'></div>");
            
            HTML.find('button.removeContact').remove();         
                     
             clickcount++;         
    
        });
    
})

function removeMe(id)
{
    $('div#so_'+id).next('p').remove();
    $('div#so_'+id).slideUp('slow',function(){$(this).remove(); });
}

function removeContact(id)
{
    $('div#contact_'+id).slideUp('slow',function(){$(this).remove(); });
}



