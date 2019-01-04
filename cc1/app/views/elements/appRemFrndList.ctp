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

<!-- Popup send friend -->
  <div class="appColRight">
  	<div class="appDataGrp">
  		<div class="appDataTitle">Contact List</div>
  		<div>
  			<div id="blankStateFrndList">
	  			<?php if(count($friendList) > 0) {?>
	  			<div class="strng">
	  				<table cellpadding="0px" cellspacing="0px" border="0" class="dataTable2" style="margin-bottom:0" >                    	
                      	<tr>                       
                        	<th width="150px">Name</th>
                        	<th width="100px">Mobile No.</th>
                        	<th>&nbsp;</th>
                      	</tr>                     	
                    </table>
	  			</div>
	  			<?php }else{ ?>
	  			<div class="dataTable2"  style="font-size:0.9em">You can maintain a contact list of mobile numbers. This list can be used site wide for features like Bhulakkad, Free SMS & Message forwarding.</div>
	  			<?php } ?>
  			</div>
  			<div id="allFriends">
  			<?php echo $this->element('afterGrpAdd',array('friendList'=>$friendList)); ?>			
			</div>
  		</div>
  	</div>
  </div>
  
      
      	<div id="wrapper" class="appColLeft">
		<div id="afterContactAddDiv">		
		</div>
		</div>
		
   		<div class="appColLeft">
      		<div class="appColLeftBox">
      			<div class="appTitle">Add Contact Manually</div>
      			<fieldset>
                	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat"><label for="nickName">Name</label></div>
	                         <div class="fieldLabelSpace2">
	                         	<input class="" type="text" id="nickName" name="data[Friendlist][nickname]" tabindex="1" style="width:255px" />
	                         	<span id="nickNameErr" style="display:none"></span>
	                         </div>                     
	                 	</div>
	            	</div>	            	
	            	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail">
	                         	<div class="fieldLabel2 leftFloat"><label for="friendMobile">Mobile Number</label></div>
	                         	<div class="fieldLabelSpace2">
								<input type="text" class="" id="friendMobile" name="data[Friendlist][mobile]" tabindex="2" style="width:160px" /><span class="hints">10 digit Indian mobile number</span>
								<span id="friendMobileErr" style="display:none"></span>
								</div>                     
	                 	</div>
	            	</div>
	            	
	            	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat" id="groupListLabel" ><?php //if(count($groupList) > 0){ ?>	 <label for="nickName">Select Group(s)</label> <?php //} ?> </div>
	                         <div class="fieldLabelSpace2">
	                         	<div id="appRemGrpLstBox">
                                <?php if(count($groupList) > 0){ ?>	                         
	                         	<select id="groupList" multiple size="5" style="width:165px">
	                         	<?php 
	                         		foreach($groupList as $gl){
	                         			echo  "<option value='".$gl['Grouplist']['id']."'>".$gl['Grouplist']['name']."</option>";
	                         	 	} 
	                         	 ?>	                         		
	                         	</select>
	                         	<a href="javascript:void(0);" onclick="deselectMultiple($('groupList'));">Deselect</a> 
                                <?php } ?>
	                         	</div>                                                                 
	                         	<a href="javascript:void(0);" onclick="$('appRemGroupaddBox').show();">Create Group</a>
	                         </div>                     
	                 	</div>
	            	</div>
	            	
	            	<div style="padding-top: 10px;display:none;" class="field" id="appRemGroupaddBox">
	                    <div class="fieldDetail">
	                         
	                         	<?php if(count($groupList) < 1){ ?>
	                         	<div id="appRemGroupBlankState">You don't have any groups created yet. Create groups to organize your contacts better.</div>
	                      		<?php } ?>   	
	                         	<!-- Grey Box -->
				            	<div style="background:#d0cfcf;border:1px solid #555555;padding:0px 0px 12px 12px;margin-bottom:20px" class="field">
				            		<div class="rightFloat" style="border-left:1px solid #555555;border-bottom:1px solid #555555; font-size:0.9em;line-height:0.8em; font-weight:bold" ><a href="javascript:void(0);" onclick="$('appRemGroupaddBox').hide();" style="padding:2px 4px 4px; display:block">x</a></div> <!-- Common class -->
				            		<div>&nbsp;</div>
				            		<div></div>
				            		
				            		<div class="field">
					                    <div class="fieldDetail">
					                         <div class="fieldLabel2 leftFloat"><label for="name">Group Name</label></div>
						                     <div class="fieldLabelSpace2">
						                     	<input type="text" id="groupName" style="width:220px"><br>
						                     	<span style="display: none;" id="groupNameErr"></span>
						                     </div>		                                              
					                 	</div>
					            	</div>
					            	<div class="field">
					                    <div class="fieldDetail">
					                         <div class="fieldLabel2 leftFloat">&nbsp;</div>
						                     <div class="fieldLabelSpace2" id="sendButt">
						                     	 <input type="button" onclick="appRemGrpAddQuick('groupName');"  value="Create Group" class="css3But4"  tabindex="6">
                                               
						                     </div>	                                              
					                 	</div>
					            	</div>					            	            		
								</div>					
				            	<!-- Grey Box ends -->                     
	                 	</div>
	            	</div>	            
	            	 	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat">&nbsp;</div>
	                         <div id="sendButt" class="fieldLabelSpace2">
	                         	 <input tabindex="6" type="button"  value="Add" class="css3But4" onClick="appRemAddFriend('nickName','friendMobile');">
                                 
            <?php /* echo $ajax->submit('butSubmit.png', array('url'=> array('controller'=>'users', 'action'=>'addFriend'),'update'=>'formSuccess','before'=>'formCheck("friendForm")','complete'=>'addFriend("formSuccess","friendsTable")')); */?>                       </div>                     
	                 	</div>
	            	</div>
	            	<div class="clearLeft"></div>	            	
	            </fieldset>	          
			</div>
		<div style="font-size:16px;font-weight:bold;margin:10px 0">OR</div>
			<div class="appColLeftBox">
      			<div class="appTitle">Upload Contact using CSV file</div>
      			<fieldset>                	          	
	            	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail" id="ahahaha">
	                         	<div class="fieldLabel2 leftFloat"><label for="friendMobile">Upload Contacts<span class="hints">(CSV files only)</span></label></div>
	                         	<div class="fieldLabelSpace2">
	                         	<form id="excelUpload" name="excelUpload" enctype="multipart/form-data" encoding="multipart/form-data">
									<input type="file" name="excelfile" id="excelfile" onchange="micoxUpload(this.form,'/reminders/uploadExcel','csvFileErr','Loading...','Error in upload');"/>
									<span class="hints">Record format: &lt;name&gt;,<10 digit mobile numer><br>e.g.<br>Dinesh,989247XXXX<br>Jitesh,981947XXXX</span>
									<span id="csvFileErr" style="display:block"></span>
								</form>	
								</div>                     
	                 	</div>
	            	</div>	            	
	            	<div class="clearLeft"></div>	            	
	            </fieldset>	          
			</div>		
		</div>
		<div class="clearRight">&nbsp;</div>
