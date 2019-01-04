
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'recheckTrans'));?>
    		<div id="innerDiv">
                    <div>
	  				<span style="font-weight:bold;margin-right:10px;">Enter Transaction Id: </span><input type="text"  name="trans_id"  onclick = id="trans_id" value="">
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="recheckTrans();"></span>
    </div>
 </div>
</div>
 </div>
<br class="clearRight" />
</div>
<script>

  function recheckTrans(){
      
        var transId = $('trans_id').value;
        if(transId == ''){
        alert("Please Enter Transaction Id");
        return false;
        }
        var url = '/shops/recheckTrans';
		var params = {'shop_transid': transId};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{
					alert(transport.responseText);
				}
		});
   
  }
</script>
