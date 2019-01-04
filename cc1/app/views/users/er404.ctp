<?php if($response == '404'){ ?>
<table width="100%" border="0">
<tr>
<td bgcolor="#ff0000" align="left"><font face="arial,sans-serif" color="#ffffff" size="3px">&nbsp;&nbsp;<b>Sorry, the page you are looking for is no longer here [Error 404]</b></font></td>
<tr>
<tr>
<td align="left" style="padding-left:10px">
    We think you will find one of the following links useful:
    <div style="font-size:0.75em;font-family:Arial,Helvetica,sans-serif;text-decoration:none;padding-top:10px">
    <a href="/users/view">Back to Home</a> or <a href="javascript:void(0);" onclick="history.back();">Go back to the previous page</a><br>
    </div>  
    <div style="float:left;border-bottom:1px solid #cccccc;margin:10px 0px 10px 0px;width:100%"></div>
    <strong>You may not be able to find this page because:</strong>
<ul style="padding-left:15px;">
    <li>You may have clicked on an expired link.</li>
    <li>You may have mistyped the address.</li>
    <li>Some web addresses are case sensitive.</li>
   
</ul>
<div style="float:left;border-bottom:1px solid #cccccc;margin:10px 0px 10px 0px;width:100%"></div>    
     
</td>
</tr>
</table>
<?php }else if($response == '500'){ ?>

<table width="100%" border="0">
<tr>
<td bgcolor="#ff0000" align="left"><font face="arial,sans-serif" color="#ffffff" size="3px">&nbsp;&nbsp;<b>Internal Server Error [Error 500]</b></font></td>
<tr>
<tr>
<td align="left" style="padding-left:10px">
    The server has encountered an internal error. We think you can do following things:
    <div style="font-size:0.75em;font-family:Arial,Helvetica,sans-serif;text-decoration:none;padding-top:10px">
    You may <a href="/users/view">reload entire page</a> <br>You may reload the link which has caused the problem<br>
    </div>  
    <div style="float:left;border-bottom:1px solid #cccccc;margin:10px 0px 10px 0px;width:100%"></div>
    <strong>You may not be able to find this page because of:</strong>
<ul style="padding-left:15px;">
    <li>Internal server error.</li>
    <li>Server misconfiguration.</li>
    <li>Connection time-out.</li>
    <li>Syntax or coding errors.</li>
   
</ul>
<div style="float:left;border-bottom:1px solid #cccccc;margin:10px 0px 10px 0px;width:100%"></div>    
     
</td>
</tr>
</table>
<?php }else if($response == '503'){ ?>
<table width="100%" border="0">
		<tr>
		<td bgcolor="#ff0000" align="left"><font face="arial,sans-serif" color="#ffffff" size="3px">&nbsp;&nbsp;<b>Service Temporarily Unavailable [Error 503]</b></font></td>
		</tr>
		<tr>
		<td align="left" style="padding-left:10px">
		   The server is temporarily unable to service your request. We think you can do following things:
		    <div style="font-size:0.75em;font-family:Arial,Helvetica,sans-serif;text-decoration:none;padding-top:10px">
		    You may <a href="/users/view">reload entire page</a> <br>You may reload the link which has caused the problem<br>
		    </div>  
		    <div style="float:left;border-bottom:1px solid #cccccc;margin:10px 0px 10px 0px;width:100%"></div>
		    <strong>You may not be able to find this page because of:</strong>
		<ul style="padding-left:15px;">
			 <li>Server capacity problems.</li>
		    <li>Server maintenance.</li>
		    <li>Server downtime.</li>
		</ul>
		<div style="float:left;border-bottom:1px solid #cccccc;margin:10px 0px 10px 0px;width:100%"></div>    
		      
		</td>
		</tr>
		</table>
<?php } ?>