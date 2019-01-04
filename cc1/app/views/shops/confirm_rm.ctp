<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Create <?php echo $data['Rm']['show_sd'] == '0' ? 'Relationship Manager' : 'Master Distributor'; ?></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Rm']['name'];?>
                         </div>
                 	</div>
                        <div class="clearLeft">&nbsp;</div>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Mobile</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Rm']['mobile'];?>&nbsp;
                         </div>
                 	</div>
                        <div class="clearLeft">&nbsp;</div>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="showas">Show As</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Rm']['show_sd'] == '0' ? 'Relationship Manager' : 'Master Distributor'; ?>
                         </div>
                 	</div>

                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>

                 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
                                                        <?php $confirm = $data['Rm']['show_sd'] == '0' ? 'Relationship Manager' : 'Master Distributor'; ?>
							<?php echo $ajax->submit('Confirm '.$confirm, array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'createRm'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backRm'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
						</div>
                    </div>
                </div>
                <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>
                    </div>
            	 </div>
		</fieldset>

<input type="hidden" name="data[confirm]" value="1"/>
<input type="hidden" name="data[Rm][name]" value="<?php echo $data['Rm']['name'];?>"/>
<input type="hidden" name="data[Rm][mobile]" value="<?php echo $data['Rm']['mobile'];?>"/>
<input type="hidden" name="data[Rm][show_sd]" value="<?php echo $data['Rm']['show_sd'];?>"/>
<?php echo $form->end(); ?>