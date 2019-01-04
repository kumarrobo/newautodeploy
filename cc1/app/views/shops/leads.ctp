
<?php if($page!='download'){ ?>
<style>
    #autoCompleteCity ul {
        border: 1px solid black;
        background-color: #ffb;
        list-style-type: none;
    }
    
    #autoCompleteCity li {
        line-height: 2em;
    }
    
    #autoCompleteCity li:hover {
        background-color: black;
        color: white;
    }
</style>
<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script>
<script src="/js/prototype.js" type="text/javascript"></script>

<form name="leadsform" id="leadsform" action ="/panels/leads/" method="post">
<input type="hidden" name='download' id ='download' value="">

From Date <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" <?php if(isset($fromdate)){ ?>value="<?php echo $fromdate; ?>" <?php }else{ ?>value=""<?php }?>" style="width: 10%" />
To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" <?php if(isset($todate)){ ?>value="<?php echo $todate; ?>" <?php }else{ ?>value=""<?php }?> style="width: 10%" />
Partner Interest: <select id="interest" name="interest">
	<option>Both</option>
	<option <?php if($interest == "Retailer") echo "selected='selected'" ?> value="Retailer">Retailer</option>
	<option <?php if($interest == "Distributor") echo "selected='selected'" ?> value="Distributor">Distributor</option>
</select>&nbsp;
Search : <input type="text" id="search" name="search" value="<?php echo $_POST['search']; ?>" placeholder="Search by Name or Email or Phone No" style="width: 18%;">&nbsp;
City : <input type="text" id="city" name="city" placeholder="Enter City" value="<?php echo $_POST['city']; ?>" >
<div id="autoCompleteCity"></div>
<input type="button" value="Submit" onclick="setAction();">
<input type="button" value="Download" onclick="exportdata();" >
<br/><br/>
<table style="border:1px solid #ccc;"cellspacing="0" cellpadding="0">
	<tr style="border:1px solid;">
		<th style="width:20px;border:1px solid #ccc;">Index</th><th style="width:135px;border:1px solid #ccc;">Name</th><th style="width:135px;border:1px solid #ccc;">Email</th><th style="width:135px;border:1px solid #ccc;">City</th><th style="width:135px;border:1px solid #ccc;">Message</th><th style="width:135px;border:1px solid #ccc;">Phone</th><th style="width:135px;border:1px solid #ccc;">Date</th><th style="width:135px;border:1px solid #ccc;">Timestamp</th><th style="width:135px;border:1px solid #ccc;">Comment</th><th style="width:135px;border:1px solid #ccc;">Required by</th><th style="width:135px;border:1px solid #ccc;">Interest</th><th style="width:135px;border:1px solid #ccc;">Remark</th><th style="width:135px;border:1px solid #ccc;">Status</th><th style="width:135px;border:1px solid #ccc;">Agent Name</th><th style="width:135px;border:1px solid #ccc;">Is_retailer</th><th style="width:135px;border:1px solid #ccc;">Follow up</th><th style="width:135px;border:1px solid #ccc;">Action</th>
	</tr>
     

	<?php
		$i = 1;
		foreach($leadData as $val){
                 if($i%2 == 0)$class = '';
                  else $class = 'altRow';
                  if($val['leads']['status']=='0'){
                   $status ="Open";
                   } else {
                      $status ="Closed";
                   }
                   
                 if($val['leads']['req_by']=="0"){
                    $requireby = '';
                  } else {
                          $requireby = $val['leads']['req_by'];
                     }     
        
    
                        
          echo "<tr class=".$class."><td style=\"width:20px;border:1px solid #ccc;\">$i</td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['name']."</td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['email']."</td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['city']."</td><td style=\"width:20px;border:1px solid #ccc;\"><textarea disabled=\"disabled\">".$val['leads']['messages']."</textarea></td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['phone']."</td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['date']."</td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['timestamp']."</td><td style=\"width:20px;border:1px solid #ccc;\"><textarea disabled=\"disabled\" id=".$val['leads']['id'].">".$val['leads']['comment']."</textarea></td><td style=\"width:20px;border:1px solid #ccc;\">".$requireby."</td><td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['interest']."<br /><a href='#' onclick=\"changeInterest('". $val['leads']['id'] ."','". strtoupper($val['leads']['name']) ."','". $val['leads']['email'] ."','". $val['leads']['interest'] ."');\">Change</a></td>";?>
                <td style="width:20px;border:1px solid #ccc;"> 
                     <select id='remark_<?php echo $val['leads']['id']?>' name='remark_<?php echo $val['leads']['id']?>'>
                       <option value='Select'>Select</option>
                       <option value='1' <?php if($val[0]['remark'] === '1') echo 'selected="selected"'?>>Interested</option>                
                       <option value='4' <?php if ($val[0]['remark'] === '4') echo 'selected="selected"'?>>Not Interested</option>
                       <option value='2' <?php if($val[0]['remark'] === '2') echo 'selected="selected"'?>>Not Contactable</option>
                       <option value='3' <?php if ($val[0]['remark'] === '3') echo 'selected="selected"'?>>Fake Lead</option>
                     </select>
                </td>

          <?php echo "<td style=\"width:20px;border:1px solid #ccc;\" id="."status_".$val['leads']['id'].">".$status."</td>";?>
              <td style="width:20px;border:1px solid #ccc;">
                <select id='agentname_<?php echo $val['leads']['id']?>' name='agentname_<?php echo $val['leads']['id']?>'>
                  <option value='Select'>Select</option>
                  <option value='Hitesh B' <?php if($val['leads']['agentname'] === 'Hitesh B') echo 'selected="selected"'?>>Hitesh B</option>                
                  <option value='Bala' <?php if ($val['leads']['agentname'] === 'Bala') echo 'selected="selected"'?>>Bala</option>
                  <option value='Amit S' <?php if($val['leads']['agentname'] === 'Amit S') echo 'selected="selected"'?>>Amit S</option>
                  <option value='Sagar' <?php if ($val['leads']['agentname'] === 'Sagar') echo 'selected="selected"'?>>Sagar</option>
                  <option value='Divyesh' <?php if ($val['leads']['agentname'] === 'Divyesh') echo 'selected="selected"'?>>Divyesh</option>
                  <option value='Sohail' <?php if ($val['leads']['agentname'] === 'Sohail') echo 'selected="selected"'?>>Sohail</option>
                  <option value='Noor' <?php if ($val['leads']['agentname'] === 'Noor') echo 'selected="selected"'?>>Noor</option>
                  <option value='Shoaib' <?php if ($val['leads']['agentname'] === 'Shoaib') echo 'selected="selected"'?>>Shoaib</option>
                  <option value='Vishvesh' <?php if ($val['leads']['agentname'] === 'Vishvesh') echo 'selected="selected"'?>>Vishvesh</option>
                  <option value='Arokiya' <?php if ($val['leads']['agentname'] === 'Arokiya') echo 'selected="selected"'?>>Arokiya</option>
                  <option value='Yasmin' <?php if ($val['leads']['agentname'] === 'Yasmin') echo 'selected="selected"'?>>Yasmin</option>
                  <option value='Krunal' <?php if ($val['leads']['agentname'] === 'Krunal') echo 'selected="selected"'?>>Krunal</option>
                </select>
              </td>
          <?php  echo "<td style=\"width:20px;border:1px solid #ccc;\">".$val['leads']['is_retailer']."</td>";?>
              <td style="width:20px;border:1px solid #ccc;">
                  <input type="text" id="datepicker_<?php echo $val['leads']['id']?>" style="width:75px;cursor: pointer;" onmouseover="fnInitCalendar(this, 'datepicker_<?php echo $val['leads']['id']?>','close=true')" value='<?php echo (isset($val['leads']['followup_date']))?$val['leads']['followup_date']:""; ?>'/>
              </td>
          <?php echo"<td style=\"width:20px;border:1px solid #ccc;\"><a href='javascript:void(0)' onclick='editform(" . $val['leads']['id'] . ");'>Edit&nbsp;&nbsp</a><a href='javascript:void(0)' onclick='submitform(".$val['leads']['id'].");'>Submit</a></td></tr>";
		$i++;
            }
		
	?>
        
       
</table>
</form>

<script type="text/javascript">

    function setAction()
    {
        jQuery("#download").val('');
        jQuery("#leadsform").submit();
    }

    function editform(id)
    {
    if($("#"+id).is(':disabled')){
         $("#"+id).removeAttr("disabled");
    }
    else {
    $("#"+id).attr("disabled","disabled");
    }
    }

    function exportdata(){
             jQuery("#download").val('download');
             jQuery("#leadsform").submit();
    }

    function submitform(id){
        var url = '/panels/leads/';
        var comment = $("#"+id).val();
        var name = $('#agentname_'+id).val();
        var remark = $('#remark_'+id).val();
        var date = $('#datepicker_'+id).val();
        var followup = date.split("-").reverse().join("-")

        if($("#"+id).attr('disabled')!='disabled'){
    //        if(comment==''){
    //            alert('Please Enter Comment');
    //             return false;
    //         }
            var data = "id=" + id+"&comm="+comment + "&name="+name + "&rem="+remark+"&followup="+followup;
            jQuery.ajax({
                type: "POST",
                url: url,
                datatype: "json",
                data: data,
                success: function(data) {
                   // console.log(data);
                 vJSONResp = JSON.parse(data);
                 if(vJSONResp.status=="success"){
                    $("#"+id).attr("disabled","disabled");
                    $("#status_"+id).html('Closed');
                    alert('Done');
                   }
                }
            });
        }
    }
    
    function changeInterest(id, name, email, interest) {
        
        var changeinterest = {Retailer: 'Distributor', Distributor: 'Retailer'};
        
        if(confirm("Are you sure you want to change the status of "+name+" ("+email+") from '"+interest+"' to '"+changeinterest[interest]+"' ?")) {
            jQuery.post('/panels/changeInterest', {id: id, interest: changeinterest[interest]}, function(e) {
                window.location.reload();
            });
        }
    }
    
    var cities = ['\'<?php echo implode("','", $cities); ?>\''];

    new Autocompleter.Local("city", "autoCompleteCity", cities, { });

</script>
<?php  } ?>