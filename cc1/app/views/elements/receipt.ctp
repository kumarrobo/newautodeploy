<div class="invoiceWidth">
<div class="invoiceHead">
	<div class="invoiceTitle strng">Receipt</div>
	<div class="rightFloat" style="text-align:left;width:410px">		
		<span class="invoiceCompName strng"><?php echo $data['company']; ?></span><br>
		<?php echo nl2br($data['address']); ?>
		<br><span class="strng">Contact:</span> <?php echo nl2br($data['mobile']); ?>
	</div>
	<div style="text-align:center; margin-right:410px;">
		<div><img alt="" src="/img/logo.png?211"></div>
		<span style="font-size:11px;">www.smstadka.com</span><br>
		<span style="font-size:10px;">(A product by MindsArray Technologies Pvt. Ltd.)</span>
	</div>
	
	<div class="clearBoth"></div>
</div>
<div>
			<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="cash">Receipt No.</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Receipt']['receipt_number'];?>
                         </div>                   
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cheque"><?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) echo "Distributor"; else echo "Retailer";?></label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['name'];?>&nbsp;
                         </div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="camount">Receipt Date</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo date('d-m-Y', strtotime($data['Receipt']['timestamp']));?>&nbsp;
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                    	<?php if($data['Receipt']['receipt_type'] == RECEIPT_INVOICE) { ?>
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Against Invoice</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['number'];?>&nbsp;
                         </div>
                        <?php } else if($data['Receipt']['receipt_type'] == RECEIPT_TOPUP) { ?>
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Against Top-up</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['number'];?>&nbsp;
                         </div>            
                        <?php }?>    
                 	</div>
            	 </div>
            	 </div>
            	 <hr/>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="cash">Cash Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if(!empty($data['Receipt']['cash_amount'])) echo '<img src="/img/rs.gif" class="rupeeBkt">' . sprintf('%.2f', $data['Receipt']['cash_amount']);?>
                         </div>                   
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cheque">Cheque Number</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['Receipt']['cheque_number'];?>&nbsp;
                         </div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="camount">Cheque Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if(!empty($data['Receipt']['cheque_amount'])) echo '<img src="/img/rs.gif" class="rupeeBkt">' . sprintf('%.2f', $data['Receipt']['cheque_amount']);?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Cheque Date</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php if($data['Receipt']['cheque_date'] != '0000-00-00')echo date('d-m-Y', strtotime($data['Receipt']['cheque_date']));?>&nbsp;
                         </div>                   
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                     <div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Transfer Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <?php if(!empty($data['Receipt']['transfer_amount'])) echo '<img src="/img/rs.gif" class="rupeeBkt">' . sprintf('%.2f', $data['Receipt']['transfer_amount']);?>
                         </div>                    
                 	</div>       	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Reference ID</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['Receipt']['transfer_ref_id'];?>&nbsp;
                         </div>                   
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
              	 	<div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Total</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <img src="/img/rs.gif" class="rupeeBkt"><?php echo  sprintf('%.2f', $data['Receipt']['total_amount']);?>
                         </div>                    
                 	</div>       	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Bank Name</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['Receipt']['cheque_bank'];?>&nbsp;
                         </div>                   
                 	</div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
              	 	<div class="fieldDetail leftFloat" style="width:300px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Outstanding Balance</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <img src="/img/rs.gif" class="rupeeBkt"><?php echo  sprintf('%.2f', $data['Receipt']['os_amount']);?>
                         </div>                    
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Bank Branch</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 450px;">
                         	<?php echo $data['Receipt']['cheque_branch'];?>&nbsp;
                         </div>                   
                 	</div>      	 
            	 </div>
            	 </div>
</div>
<hr/>
<div style="margin-top:20px;">
	<ul style="padding-left: 16px;">
		<li>Subject to realization of Cheque / D.D./ Transfers</li>
		<li><i>E. & O. E.</i></li>
	</ul>
</div>		
		<div style="margin-top:20px; text-align:center; font-size:12px">THIS IS COMPUTER GENERATED RECEIPT. NO SIGNATURE REQUIRED.</div>
<span id="printId"> <a href="javascript:void(0)" onclick="printChallan()"> Print </a> </span>
</div>
<script>
	function printChallan(){
		document.getElementById('printId').style.display='none';
		window.print();
	}
</script>