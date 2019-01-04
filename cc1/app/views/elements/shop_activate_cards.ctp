<?php echo $form->create('activate'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Activate cards for retailer</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Select Retailer</label></div>
                         <div class="fieldLabelSpace1">
                         	<select tabindex="1" id="username" name="data[Retailer][id]" style="width:148px">
							<option value="0"></option>
							<?php foreach($retailers as $retailer) {?>
								<option value="<?php echo $retailer['Retailer']['id'];?>" <?php if(isset($data) && $data['Retailer']['id'] ==  $retailer['Retailer']['id']) echo "selected";?>><?php echo $retailer['Retailer']['shopname'] . " - " . $retailer['Retailer']['id']; ?></option>
							<?php } ?>
						</select>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div style="padding-left:8px;padding-top:10px;">
            	 <table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<!-- <caption class="header">Transaction(s)</caption> -->
			        <thead>
			          <tr class="noAltRow altRow">
			            <th style="width:184px;">Select Product</th>
			            <th style="width:158px;">Start Serial Number</th>
			            <th style="width:158px;">End Serial Number</th>
			            <th class="number">Qty</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php for($i = 0;$i<NUM_PRODUCTS;$i++) {?>
                      <tr <?php if($i%2 == 1) echo "class='altRow'";?>>
			            <td>
				            <select style="width:180px" id="product" name="data[Product][id][]">
								<?php foreach($products as $product) {?>
									<option value="<?php echo $product['Product']['id'];?>" <?php if(isset($data) && $data['Product']['id'][$i] ==  $product['Product']['id']) echo "selected";?>><?php echo $product['Product']['name'] . " (Rs " . $product['Product']['price'] . ")"; ?></option>
								<?php } ?>
							</select>
			            </td>
			            <td><input class="start" id="start_<?php echo $i; ?>" type="text" style="width:154px" name="data[Product][serialStart][]" onChange="findQty(this,<?php echo $i; ?>)" value="<?php if(isset($data)) echo $data['Product']['serialStart'][$i];?>"></td>
			            <td><input class="end" id="end_<?php echo $i; ?>" type="text" style="width:154px" name="data[Product][serialEnd][]" onChange="findQty(this,<?php echo $i; ?>)" value="<?php if(isset($data)) echo $data['Product']['serialEnd'][$i];?>"></td>
			            <td id="qty_<?php echo $i; ?>" class="number"><?php if(isset($data)) echo ($data['Product']['serialEnd'][$i] - $data['Product']['serialStart'][$i]); else echo '0';?></td>
    			      </tr>
					<?php } ?>  
    			      <tfoot>
			         <!-- <tr>
			         	<td>Total</td>
			            <td>&nbsp;</td>
			            <td>&nbsp;</td>
			            <td class="number"></td>
			            </tr> -->
			         </tfoot>
			         </tbody>
			         			         
			        </table>
            	 </div>	
            	 <div id="sub_butt" style="padding-left:8px;">
                    <?php /* echo $ajax->submit('Activate Cards', array('id' => 'sub','url'=> array('controller'=>'shops', 'action'=>'activateRetailCards'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); */?>
                 	<div style="padding:8px 0px 0px 8px;">
            	 		<?php echo $this->Session->flash();?>
            	 	</div>
                 </div>
                 
		</fieldset>
<?php echo $form->end(); ?>