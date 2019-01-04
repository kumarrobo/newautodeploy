<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">New Salesman</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username" class="compulsory">Name</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="1" type="text" id="username" name="data[Salesman][name]"  value="<?php if(isset($data))echo $data['Salesman']['name']; ?>"/>
                         </div>
                 	</div>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                         <div class="fieldLabelSpace1">
                             <input tabindex="2" type="text" id="mobile" name="data[Salesman][mobile]" maxlength="10" value ="<?php if(isset($data))echo $data['Salesman']['mobile']; ?>"/>
                         </div>                     
                 	</div> <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 
            	 <div class="altRow">         	 
            	 <div class="field">
                     <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Transaction Limit</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="3" type="text" id="mobile" name="data[Salesman][tran_limit]" value ="<?php if(isset($data))echo $data['Salesman']['tran_limit']; ?>"/>
                         </div>                     
                 	</div>            	 
                     <?php } else { ?>
                    <input tabindex="3" type="hidden" id="mobile" name="data[Salesman][tran_limit]" value ="<?php if(isset($data)) { echo $data['Salesman']['tran_limit']; } else { echo "0"; } ?>"/>
                     <?php } ?>
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address" class="compulsory">Extra</label></div>
                         <div class="fieldLabelSpace1"">
                            <textarea tabindex="4" id="address" name="data[Salesman][extra]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['Salesman']['extra']; ?></textarea>
                         </div>
                    </div>
                    </div>
                
                    	
						<div class="fieldLabel2 leftFloat"><label for="subarea">Subarea Name </label></div>
	                         <div class="fieldLabelSpace1">
	                         	<input type="text" value="" autocomplete="off" name="data[Salesman][subarea]" id="subareaauto" tabindex="5" style="width:200px">
	                         	<div class="autoComplete position2" id="AppStockFor_autoComplete" style="display: none;"></div>
							        <script> new Ajax.Autocompleter("subareaauto", "AppStockFor_autoComplete", "/shops/autoCompleteSubarea", {paramName: "data[Salesmen][subarea]", 
							  minChars: 3,  afterUpdateElement : changeSubarea
							  });</script>
	                            <br>
	                            <span class="hints">Start with the first 3 chars of subarea name</span>
	                            <input type="text" id="subareaOptions" name="data[subArea1]" style="margin-top:10px"/>
	                         </div>     
							</div>	  
												  
            	 
				 
                 <div class="field">               		
                    <div class="fieldDetail">
                        <div class="fieldLabel1 ">&nbsp;</div> 
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Create Salesman', array('id' => 'sub', 'tabindex'=>'13','url'=> array('controller'=>'shops', 'action'=>'createSalesman'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
					<div class="field">    
                    <div class="fieldDetail">                         
                         <div>
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>	
            	 </div>
		</fieldset>
<?php echo $form->end(); ?>
<script>
		if($('username'))
			$('username').focus();	
		if($('autocomplete1'))
			$('autocomplete1').focus();
	</script>
	
