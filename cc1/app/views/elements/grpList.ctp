<div style="padding: 0px 20px 20px 10px;">
	<div class="rightFloat"> 
		<span id="back">
			<?php echo $ajax->link( 
    				'<< Back to Personal Alerts', 
    				array('controller' => 'apps' ,'action' => 'getApps' ), 
    				array( 'update' => 'pageContent')
					); ?>
		</span>
	</div>
	<span>
		<?php echo $ajax->link( 
    				'Personal Alerts', 
    				array('controller' => 'apps' ,'action' => 'getApps' ), 
    				array( 'update' => 'pageContent')
					); ?> 
	</span> &nbsp;&gt;&gt;&nbsp; <span><?php echo $data['SMSApp']['name']; ?></span>
  </div>
  <!-- Right Col -->
  <div class="appColRight">
  	<div class="appDataGrp">
  		<div class="appDataTitle">Group List</div>
  		<div>
  			<div id="blankStateGrpList">
	  			<?php if(count($groupList) > 0) {?>
	  			<div class="strng">
	  				<table cellpadding="0px" cellspacing="0px" border="0" class="dataTable2" style="margin-bottom:0" >                    	
                      	<tr>                       
                        	<th width="195px">Names</th>                        	
                      	</tr>                     	
                    </table>
	  			</div>
	  			<?php }else{ ?>
	  			<div class="dataTable2"  style="font-size:0.9em">You don't have any groups created yet. Create groups to organize your contacts better.</div>
	  			<?php } ?>
  			</div>
  			<div id="allGroups">
  			<?php echo $this->element('afterConAdd',array('groupList'=>$groupList)); ?>			
			</div>
  		</div>
  	</div>
  </div>
  <div id="wrapper" class="appColLeft">
	<div id="afterGroupAddDiv"></div>
  </div>
  <div class="appColLeft">
  	<div class="appColLeftBox">
		<div class="appTitle">Add Group</div>
		<fieldset>
        	<div class="field" style="padding-top: 10px;">
                <div class="fieldDetail">
                     <div class="fieldLabel2 leftFloat"><label for="name">Group Name</label></div>
                     <div class="fieldLabelSpace2">
                     	<input type="text" style="width: 255px;" tabindex="1" id="groupName" class="">
                     	<span style="display: none;" id="groupNameErr"></span>
                     </div>                     
             	</div>
        	</div>
        	
        	 	<div class="field" style="padding-top: 10px;">
                <div class="fieldDetail">
                     <div class="fieldLabel2 leftFloat">&nbsp;</div>
                     <div class="fieldLabelSpace2" id="sendButt">
                     	 <input type="button" onclick="appRemGrpAdd('groupName');" value="Create Group" class="css3But4" tabindex="6">
                     </div>                     
             	</div>
        	</div>
        	<div class="clearLeft"></div>
        </fieldset>	           
	</div>
  </div>
  <div class="clearRight">&nbsp;</div>