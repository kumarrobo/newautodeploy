<?php echo $form->create('shop'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
    <div class="appTitle">New Relationship Manager ( RM )</div>
    <div>
        <div class="field" style="padding-top:5px;">
            <div class="fieldDetail leftFloat" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="username" class="compulsory">Name</label></div>
                <div class="fieldLabelSpace1">
                    <input tabindex="1" type="text" id="username" name="data[Rm][name]"  value="<?php if (isset($data))
    echo $data['Rm']['name']; ?>"/>
                </div>
            </div>
            <div class="clearLeft">&nbsp;</div>
            <div class="fieldDetail leftFloat" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                <div class="fieldLabelSpace1">
                    <input tabindex="2" type="text" id="mobile" name="data[Rm][mobile]" value ="<?php if (isset($data))
    echo $data['Rm']['mobile']; ?>"/>
                </div>                     
            </div>
            <div class="clearLeft">&nbsp;</div>
            <div class="fieldDetail leftFloat" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Show As</label></div>
                <div class="fieldLabelSpace1">
                <select name="data[Rm][show_sd]" style="width:168px;margin-top: 6px;height: 25px;border-radius: 5px;background-color: white;">
                    <option value="0" <?php echo ($data['Rm']['show_sd'] == '0') ?  'selected' : '' ; ?>>RM</option>
                    <option value="1" <?php echo ($data['Rm']['show_sd'] == '1') ?  'selected' : '' ; ?>>Master Distributor</option>
                </select>
                </div>                     
            </div> <div class="clearLeft">&nbsp;</div>
        </div>
    </div>
    <div class="field">               		
        <div class="fieldDetail">
            <div class="fieldLabel1 ">&nbsp;</div> 
            <div class="fieldLabelSpace1" id="sub_butt">
<?php echo $ajax->submit('Create RM', array('id' => 'sub', 'tabindex' => '13', 'url' => array('controller' => 'shops', 'action' => 'createRM'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
            </div>                         
        </div>
    </div>
    <div class="field">    
        <div class="fieldDetail">                         
            <div>
<?php echo $this->Session->flash(); ?>
            </div>   
        </div>
    </div>	
</div>
</fieldset>
<?php echo $form->end(); ?>
<script>
if($('username'))
    $('username').focus();	

</script>

