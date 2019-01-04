<?php
session_name('CAKEPHP');
session_start();

date_default_timezone_set('Asia/Kolkata');

if(!isset($_SESSION['Auth']['User'])) {
	echo "You are not authorized";exit;
}

if($_REQUEST['date']!=""){
    $date = $_REQUEST['date'];
}else{
    $date = date('Ymd');
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<style>
body
{
        width: 100%;
        margin: 0 auto;
}
#chats
{
        height: 500px;
        overflow-y: scroll;
}
#msg
{
        font-size: 25px;
        width: 100%;
        border: 1px solid #DDD;
        border-radius: 5px;
        -moz-border-radius: 5px;
        font-family: Arial, Helvetica, sans-serif;
        margin: 5px auto 20px;
        padding: 5px;
        border-image: initial;
}
.msgln
{
        padding: 3px;
        float: left;
        width: 250px;
}
.msglx
{
        padding: 3px;
        float: left;
        width: 250px;
        margin-right:10px;
}

.msglx_new
{
        padding: 3px;
        float: left;
        width: 320px;
        margin-right:10px;
}
.msgrn
{
        padding: 3px;
        float: left;
        width: 250px;

}
.msgrn_new
{
        padding: 3px;
        float: left;
        width: 320px;

}
.msgrn_new_hidden
{
        padding: 3px;
        float: left;
        width: 320px;
        background-color: #FFEBCD;
        text-decoration: line-through;
        opacity:0.6;
}
.msgln_hidden
{
        padding: 3px;
        float: left;
        width: 250px;
        background-color: #FFEBCD;
        text-decoration: line-through;
        opacity:0.6;
        border-style:solid;
        border-width:1px;
}

.msgun
{
        padding: 3px;
        float: left;
        width: 250px;
        clear: both;
}

.msgln:nth-child(odd)
{
        background-color: #EAEAEA;
}
.msgrn:nth-child(odd)
{
        background-color: #EFEAEA;
}

.msgrn_new:nth-child(odd)
{
        background-color: #EFEAEA;
}
.msgun:nth-child(odd)
{
        background-color: #EAEFEA;
}

footer
{
        position: fixed;
        bottom: 10px;
}
</style>
</head>
<body>
<h6>To view specific date entry make url like: cc.pay1.in/limits/index.php?date=yyyymmdd</h6>
<h4>Pay1 - Limit Team Collaboration System</h4>
<div id="chats"></div>
<form onsubmit="javascript:sendMsg();return false;">
        <input type="text" name="text" id="msg" autocomplete="off" />
</form>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
var username = ""
if(!username)
{
//      var username = prompt("Hey there, good looking stranger!  What's your name?", "");
}
function sendMsg(){
/*      if(!username)
        {
                username = prompt("Hey there, good looking stranger!  What's your name?", "");
                if(!username)
                {
                        return;
                }
        }
*/
        var msg = document.getElementById("msg").value;
        if(!msg)
        {
                return;
        }
        
        document.getElementById("uchat").innerHTML+=strip('<div class="msgun"><b>'+username+'</b>: '+msg+'<br/></div>');
        $("#uchat").animate({ scrollTop: 2000 }, 'normal');

        $.get('server.php?msg='+msg+'&sender='+username+'&process=limits', function(data)
        {
                document.getElementById("msg").value = '';
                
        });
}

var old = '';
//alert('server.php?process=limits&date1=<?php echo $date; ?>');
var source = new EventSource('server.php?process=limits&date1=<?php echo $date; ?>');

source.onmessage = function(e)
{   //alert("=="+e.data); 
        if(old!=e.data){
                document.getElementById("chats").innerHTML='<span>'+e.data+'</span>';
                old = e.data;
        }
        $.ajax({url:'amt_in_wrds.php?date1='+<?php echo $date; ?>,success:function(result){
                    //alert("result="+result);
            var valList = result.split('|<>|');
            var len = valList.length;
            for (var i = 0; i < len; i++) {
                //alert(valList[i]);
                var idAmt = valList[i].split('|');
                numinwrd(idAmt[0],idAmt[1]);
                //alert(idAmt[0],idAmt[1]);
            }
            
        }});
                
};
function strip(html)
{
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent||tmp.innerText;
}


function smspay1hide(divtype,dbid,setFlag){ 
    //alert(divtype+","+id+","+dbid+","+setFlag);
    var toHideDiv = divtype+"_sms_"+dbid;
    $.ajax({url:'flagchange.php?id='+dbid+'&setFlag='+setFlag,success:function(result){
        if(setFlag=='N'){
            //alert(toHideDiv);
            //alert("hidden_"+divtype+"_sms");
            var toHideDiv1 = document.getElementById(toHideDiv);
            document.getElementById("hidden_"+divtype+"_sms").appendChild(toHideDiv1);
            //alert("Hello");

        }else{
            toHideDiv = "hidden_"+toHideDiv;
            //alert(toHideDiv);
            //alert("div_"+divtype+"_sms");
            var toHideDiv1 = document.getElementById(toHideDiv);

            document.getElementById("div_"+divtype+"_sms").appendChild(toHideDiv1);
        }
    }});
   
}
//numinwrd('20','1,90,000.0');
function numinwrd(id,numbr)
{   
    //alert(id+"|"+numbr);
     numbr = numbr.split(".");
     numbr = numbr[0];
     numbr = numbr.replace(/,/g,"");
     numbr = numbr.replace("/-","");
     numbr = numbr.replace("(","");
     numbr = numbr.replace(")","");
     
     var splt=numbr.split("");
     var rev=splt.reverse();
     var once=['Zero', ' One', 'Two', 'Three', 'Four',  'Five', 'Six', 'Seven', 'Eight', 'Nine'];
     var twos=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
     var tens=[ '', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];
     numlen=rev.length;
     var word=new Array();
     
     var j=0;   
     for(i=0;i<numlen;i++)
       {
          switch(i)
           {
            case 0:
                  if((rev[i]==0) || (rev[i+1]==1))
                   {
                      word[j]=' ';                    
                   }
                   else
                   {
                     word[j]=once[rev[i]];
                    }
                   word[j]=word[j] ;
                   
                   break;
            case 1:
                abovetens();  
                   break;
              case 2:
                if(rev[i]==0)
                {
                  word[j]='';
                } 
               else if((rev[i-1]==0) || (rev[i-2]==0) )
                {
                   word[j]=once[rev[i]]+"Hundred ";                
                }
                else 
                {
                    word[j]=once[rev[i]]+"Hundred and ";
                } 
               break;
             case 3:
                    if(rev[i]==0 || rev[i+1]==1)
                   {
                      word[j]='';                    
                   } 
                   else
                   {
                     word[j]=once[rev[i]];
                   }
                if((rev[i+1]!=0) || (rev[i] > 0))
                {
	                 word[j]= word[j]+" Thousand ";
	              }
                  break;  
             case 4:
                  abovetens(); 
                    break;  
           
              case 5:
                   if((rev[i]==0) || (rev[i+1]==1))
                   {
                      word[j]='';                    
                   } 
                   else
                   {
                     word[j]=once[rev[i]];
                   }
                word[j]=word[j]+"Lakhs ";
                  break;  
          
           case 6:
                  abovetens(); 
                    break;
         
          case 7:
                   if((rev[i]==0) || (rev[i+1]==1))
                   {
                      word[j]='';                    
                   } 
                   else
                   {
                     word[j]=once[rev[i]];
                   }
              word[j]= word[j]+"Crore ";
                    break;  
          
           case 8:
                  abovetens(); 
                    break;    
                 default:
	               break;
              }
       
          j++;  
       
       }   
  
function abovetens()
{
	if(rev[i]==0)
    {
        word[j]='';
    }
	else if(rev[i]==1)
    {
    	word[j]=twos[rev[i-1]];
    }
   	else
    {
    	word[j]=tens[rev[i]];
    }
}

word.reverse();
var finalw='';
for(i=0;i<numlen;i++)
{

  finalw= finalw+word[i];
  
}
//alert("finalw="+finalw);
finalw= "["+finalw+"]";
$('#amtwrds_'+id).html(finalw);

}


</script>
</body>
</html
