<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Transfer Balance</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:820px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Transfer Amount</label></div>
                         <div >
                            <input type="text" id="amount" autocomplete="off" onkeyup="numinwrd('amount')"/><span id="p_amount_word" style="margin-left:10px;font-weight:bold"></span>
                           <br/> <span style="color:green;font-size:11px;margin-left:150px;" id="amount_word"></span>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 
            	 <div >         	 
            	 <div class="field">
                    <div class="fieldDetail" style="width:750px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile"><?php echo $modelName;?></label></div>
                         <div >
<!--                            <select tabindex="1" id="shop_select"  >
                                        <option value="0_0"></option>
                                        <?php /*foreach($records as $distributor) {*/?>
                                                <option value="<?php //echo $distributor[$modelName]['id']."_".$distributor[$modelName]['margin'];?>" <?php //if(isset($data) && $data['shop'] == $distributor[$modelName]['id']) echo "selected";?>><?php //echo $distributor[$modelName]['company'] . " - " . $distributor[$modelName]['id'] ; ?></option>
                                        <?php// } ?>
                                </select>
                             <input type="hidden" value="" id="shop" />-->
                             <span style="margin-left:10px;font-weight:bold"><?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) echo $data['shopData']['shopname']; else echo $data['shopData']['company']; echo " - " . $data['shopData']['id'];?></span>
                                        
                         </div>               
                 	</div>
            	 </div>
            	 </div>
                 <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){ ?>
            	 <div>
		<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:500px;">
                         <div class="fieldLabel1 leftFloat"><label for="commission">Discount</label></div>
                         <span style="margin-left:10px;font-weight:bold"><?php echo $data['commission'];?></span>
                         <br/>
                 	</div>
            	 </div>
                 <div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:500px;">
                         <div class="fieldLabel1 leftFloat"><label for="commission">Discount %</label></div>
                         <span style="margin-left:10px;font-weight:bold"><?php echo $data['commission_per'];?></span>
<!--                         <div >
                            <input type="text" id="commission_per"/><br/><label for="commission">&nbsp;</label> &nbsp;&nbsp;<a href="javascript:void(0)" onclick="setCommissionAndPer()">Auto calculate</a>
                            
                         </div>-->
                         <br/>
                 	</div>
            	 </div>
            	 </div>
            	 <?php } ?>
            	 <?php if($_SESSION['Auth']['User']['group_id'] != ADMIN) { ?>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state"> Balance Amount </label></div>
                    	<div class="fieldLabelSpace1 strng">
							<?php echo $balance;?>
						</div>                    
                 	</div>            	 
            	 </div>
            	 </div>
            	 <?php }?>
            	 <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
            	 <div>
				 <div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:500px;">
                         <div class="fieldLabel1 leftFloat"><label for="type">Transfer Type</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if($data['typeRadio'] == 1) echo "Cash"; else if($data['typeRadio'] == 2) echo "NEFT"; else if($data['typeRadio'] == 3) echo "ATM Transfer"; else if($data['typeRadio'] == 4) echo "Cheque"; echo " - " . $data['description'] . "&nbsp;";?>
                         </div>                     
                 	</div>
            	 </div>
                     
            	 </div>
            	 <?php } if($this->Session->read('Auth.User.id') == 1 || $_SESSION['Auth']['User']['group_id'] == ADMIN){ ?>
                   <div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:820px;">
                         <div class="fieldLabel1 leftFloat"><label for="type">Enter Password</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <input type="password" size="13" name="password" id="password" autocomplete="off" value="">
                         </div>                     
                 	</div>
            	 </div>
                     
            	 </div>
                 <?php } ?>
                 <div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:10px;" id="sub_butt">
                             <input type="button" class="retailBut enabledBut" onclick="checkConfirm()" value="Confirm" id="tran_confirm" />
							<?php //echo $ajax->submit('Confirm Transfer', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'amountTransfer'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backTransfer'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
						</div>                       
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace inlineErr1" id="loading_sym">
                            <?php //echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>	
		</fieldset>

<input type="hidden" name="data[confirm]" value="1">
<input type="hidden" id="p_amount" name="data[amount]" autocomplete="off" value="<?php echo $data['amount'];?>"/>
<?php if($this->Session->read('Auth.User.id')==1 || $_SESSION['Auth']['User']['group_id'] == ADMIN){ ?>
<input type="hidden" id="pass" name="data[pass]" autocomplete="off" value="<?php echo $this->Session->read('Auth.User.id');?>"/>
<?php } ?>
<input type="hidden" id="p_amount" name="data[amount]" autocomplete="off" value="<?php echo $data['amount'];?>"/>
<input type="hidden" id="p_shop" name="data[shop]" autocomplete="off" value="<?php echo $data['shop'];?>"/>
<input type="hidden" id="p_shop1" name="data[shop1]" autocomplete="off" value="<?php echo $data['shop1'];?>"/>

<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
<?php if($_SESSION['Auth']['User']['group_id'] != DISTRIBUTOR) { ?>
<input type="hidden" id="p_commission" name="data[commission]" autocomplete="off" value="<?php echo $data['commission'];?>"/>
<input type="hidden" id="p_commission_per" name="data[commission_per]" autocomplete="off" value="<?php echo $data['commission_per'];?>"/>
<?php }?>
<input type="hidden" name="data[typeRadio]" autocomplete="off" value="<?php echo $data['typeRadio'];?>"/>
<input type="hidden" name="data[description]" autocomplete="off" value="<?php echo $data['description'];?>"/>
<?php if(isset($data['bank_name'])){ ?>
<input type="hidden" name="data[bank_name]" autocomplete="off" value="<?php echo $data['bank_name'];?>"/>
<?php }} ?>
<?php echo $form->end(); ?>
<script>
//    Event.observe($("shop_select"),'change', function(){  
//    
//    var val = $("shop_select").value;
//    var arr = val.split("_");
//    var shop_id = arr[0];
//    $("shop").value = shop_id;
//    var shop_commission = arr[1];
//    $("commission_per").value = shop_commission;
//    
//    var amt =  $("amount").value;
//    //alert(parseFloat($("commission_per").value));
//    amt = amt=="" ? 0 : amt;
//    $("amount").value =  parseInt( amt ) ;
//    var comm =  parseFloat($("commission_per").value) * parseFloat( $("commission_per").value ) / 100 ;
//    
//    $("commission").value =  parseInt( comm ) ;
//});
numinwrd('p_amount');

  </script>
