<div>
<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo date('d-m-Y', strtotime($from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo date('d-m-Y', strtotime($to));?>">

<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findData();"></span>
</div>
<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				
<?php 
    echo $this->GChart->start('test');
    echo $this->GChart->visualize('test', $data);
    
    if(isset($data0)){
    	echo $this->GChart->start('test0');
    	echo $this->GChart->visualize('test0', $data0);
    }
    
    if(isset($data1)){
    	echo $this->GChart->start('test1');
    	echo $this->GChart->visualize('test1', $data1);
    }
    
    if(isset($data2)){
    	echo $this->GChart->start('test2');
    	echo $this->GChart->visualize('test2', $data2);
    }
?>
<script>
function findData(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	if(date_from == '' || date_to == ''){
		$('date_err').show();
		$('submit').innerHTML = html;
	}
	else {
		$('date_err').hide();
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		window.location.href = "/shops/graphRetailer/?type=<?php echo $type;?>&id=<?php echo $id; ?>&from="+date_from+"&to="+date_to;
	}
}
</script>