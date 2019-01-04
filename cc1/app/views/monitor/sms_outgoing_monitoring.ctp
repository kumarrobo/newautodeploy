<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<div>
    <?php //echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <?php //echo $this->element('shop_side_activities',array('side_tab' => 'allretailer'));?>
            <div id="innerDiv">
                
                <?php echo $this->element('monitoring_header'); ?>	 
                <div style ="font:10px">
					<div>
						<label>Vendor Priority:</label>
						<select name="type" id="type" onchange="switchtype(this.value)">
							<option value="">---Select Service Provider----</option>
							<option value="all" <?php if($switchtype == 'all'){ echo 'selected'; } ?>>Both</option>
							<option value="tata" <?php if($switchtype == 'tata'){ echo 'selected'; } ?>>Tata</option>
							<option value="247" <?php if($switchtype == '247'){ echo 'selected'; } ?>>247</option>
							
						</select>
					</div>
                    <div style ="font-size:20px;padding: 5px;"> SMS Outgoing Reports (Last 30 mins report) </div>
                <?php foreach($vendorsOutGoing as $key => $vo){ ?>	 
                    <table class="ListTable" width="100%" border="1" style="border-collapse:collapse;font-size: 14px;">
                        <thead style="font-weight: bold; background: none repeat scroll 0 0 #F3F3F3">
                            <tr>
                                <td colspan="1" ><?php echo $key."Report" ; ?>	  </td>  
                                <td colspan="2" ><?php echo "&nbsp;&nbsp;&nbsp; ".$contactDetails[$key] ; ?>	  </td>  
                            </tr>
                            <tr>
                                <td align="center">Status</td>
                                <td align="center">Count</td>
                                <td align="center">Percentage(%)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($vo as $k => $v){ ?>
                                
                             <?php if($k == "MSG"){ ?>
                                <tr>
                                    <td align="right" colspan="3" style="font-size: 12px;color: red;" ><?php echo $v; ?></td>                                   
                                </tr>
                            <?php }else{ ?>
                                <tr >
                                    <td align="center"><?php echo empty($v['status']) ? "SENT" : $v['status']; ?></td>
                                    <td align="center"><?php echo $v['count'] ; ?></td>
                                    <td align="center"><?php echo round($v['per'],0) . " %" ; ?></td>
                                </tr>
                            <?php }?>
                                
                                
                            <?php } ?>
                            
                                
                        </tbody>
                    </table>
                <?php } ?>
                </div>
            </div>
            <br class="clearLeft" />
        </div>

    </div>
</div>
<br class="clearRight" />
</div>

<script type="text/javascript">
	
	function switchtype(type){
		
		if(type!=''){
			
			if(confirm("Are you sure you want to switch to "+type)){
				
			$.ajax({
            url: '/monitor/setType/',
            type: "POST",
            data: {"switch_type": type},
            dataType: "text",
            success: function(data) {
				 alert(data);
                
            }
            });
				
			}
		} 
		
	}
	
</script>
	