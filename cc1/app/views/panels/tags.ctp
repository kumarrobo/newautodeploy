<script>

function setAction()
{

	var sel=$('tagDD');
	var tagName=sel.options[sel.selectedIndex].value;
//	alert(tagName);
	document.tagResults.action="/panels/tags/"+tagName;
	document.tagResults.submit();
	

}
</script>



<form name="tagResults" method="POST" onSubmit="setAction()">

Tag Name : <select name="tagDD" id="tagDD">
	<?php

		
			foreach($tagNames as $d)
			{
			
				$sel='';
				if(strcmp($selectedTagName,$d['t']['name'])==0)
					$sel='selected';			
			
				echo "<option ".$sel." value='".$d['t']['name']."'>".$d['t']['name']."</option>";
	
			}
			
	?>
</select>

<input type="button" value="Submit" onclick="setAction()"/>
</form>
</br>
</br>




<table border="1" cellspacing="0" cellpadding="0" width="50%">
<th>Retailer Name</th>
<th>Retailer Shop Name</th>
<th>Type</th>
<th>Tag</th>
<th>Transaction Id</th>
<th>Amount</th>
<th>Date</th>

<?php 
if(isset($tagsResult))
{
	foreach ($tagsResult as $t)
	{
	if(empty($t['tu']['transaction_id']))
	{
	$var='Not Applicable';
	$amt='Not Applicable';
	}else
	{
	$var=$t['tu']['transaction_id'];
	$amt=$t['va']['amount'];
	}
	if($t['u']['group_id']==6)
		$type='Retailer';
	else
	    $type='User';
	    	
		echo "<tr>";
			echo "<td><a href='/panels/userInfo/".$t['u']['mobile']."'>".$t['u']['name']."/".$t['u']['mobile']."</a></td>";	
			echo "<td>".$t['r']['shopname']."</td>";
			echo "<td>".$type."</td>";
			echo "<td>".$t['t']['name']."</td>";
			
			if(empty($t['tu']['transaction_id']))
			{
				echo "<td>".$var."</td>";
				echo "<td>".$amt."</td>";
			}else
			{
			echo "<td><a href='/panels/transaction/".$var."'>".$var."</a></td>";
			echo "<td>".$amt."</td>";
			}
			echo "<td>".$t['tu']['timestamp']."</td>";
		echo "</tr>";
	}
}
?>	

</table>

