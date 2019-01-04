<?php echo $form->create('confirm_allot'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Confirm allot cards to distributor</div>
				<div>
				<div class="field" style="padding-top:5px;">
					<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Distributor</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $distributor['Distributor']['company'] . " - " . $distributor['Distributor']['id'];?>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div style="padding-left:8px;padding-top:20px;">
            	 <table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:184px;">Product</th>
			            <th style="width:158px;">Start Serial Number</th>
			            <th style="width:158px;">End Serial Number</th>
			            <th class="number">Price</th>
			            <th class="number">Qty</th>
			            <th class="number">Total Amount</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php $tot = 0; $amount = 0; for($i=0;$i<NUM_PRODUCTS;$i++) { if(!isset($data['Product']['empty'][$i])) { ?>
                      <tr>
			            <td><?php echo $data['Product']['name'][$i]; ?></td>
			            <td><?php echo $data['Product']['serialStart'][$i]; ?></td>
			            <td><?php echo $data['Product']['serialEnd'][$i]; ?></td>
			            <td class="number"> <?php echo $data['Product']['price'][$i]; ?> </td>
			            <td class="number"> <?php echo ($data['Product']['serialEnd'][$i] - $data['Product']['serialStart'][$i] + 1); ?> </td>
			            <td class="number"> <?php echo $data['Product']['price'][$i] * ($data['Product']['serialEnd'][$i] - $data['Product']['serialStart'][$i] + 1); ?> </td>
    			      </tr>
    			    <?php 
    			    $tot += $data['Product']['serialEnd'][$i] - $data['Product']['serialStart'][$i] + 1;
    			    $amount += $data['Product']['price'][$i] * ($data['Product']['serialEnd'][$i] - $data['Product']['serialStart'][$i] + 1);
    			    } 
    			    
    			    }?>
					 <tfoot>
			         <tr>
			         	<td>Total</td>
			            <td>&nbsp;</td>
			            <td>&nbsp;</td>
			            <td>&nbsp;</td>
			            <td class="number"><?php echo $tot; ?></td>
			            <td class="number"><?php echo $amount; ?></td>
			            </tr>
			         </tfoot>
			         </tbody>
			         			         
			         </table>
            	 </div>
            	 <div class="field">
            	  	<div class="fieldDetail">                         
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Allotment', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'allotRetailCards'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backAllotment'), 'class' => 'retailBut disabledBut',  'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
						</div>                       
                    </div>
        		</div>
		</fieldset>
		<input type="hidden" name="data[confirm]" value="1">
		<input type="hidden" name="data[Distributor][id]" value="<?php echo $data['Distributor']['id'];?>">
		<?php for($i=0;$i<NUM_PRODUCTS;$i++) { ?>
		<input type="hidden" name="data[Product][id][]" value="<?php echo $data['Product']['id'][$i];?>">
		<input type="hidden" name="data[Product][serialStart][]" value="<?php echo $data['Product']['serialStart'][$i];?>">
		<input type="hidden" name="data[Product][serialEnd][]" value="<?php echo $data['Product']['serialEnd'][$i];?>">
		
		<?php } ?>
		
<?php echo $form->end(); ?>		
		