              <!-- Popup send friend -->
						<div style="width:730px;"> 	                  		
                            <div class="appColRight">
                            	<div id="friendListTop" class="friendListTop1">
                                 <div id="friendListTable">
                        			<table cellpadding="0px" cellspacing="0px" border="0" class="dataTable2" style="margin-bottom:0" >
                          <tr>
                                <th colspan="4">Friend List</th>
                            </tr>
                          <?php if(count($friendList) > 0) {?>
                            <tr class="nobg" style="font-weight:bold">
                                            <td width="26px"><input  type="checkbox" name="checkAll" id="checkAll" onclick="selectAll(this,'friendsTableDiv','data[Friendlist][id]')"/></td>
                                            <td width="180px">Name</td>
                                            <td class="90px">Mobile No.</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    <?php }else{ ?>
									<script>noFriends = 1;</script>
                                   <tr class="nobg">
                                        <td colspan="3" style="padding:5px 0;">You can maintain a contact list of mobile numbers. This list can be used site wide for features like Bhulakkad, Free SMS & Message forwarding.</td>
                                   </tr> 
                                    <?php }?></table>
                                  </div>
                                    
                            	<div class="friendList" id="friendsTableDiv" style="max-height:150px">
                                <table cellpadding="0px" cellspacing="0px" class="dataTable2" id="friendsTable" style="margin-top:0;">
                                  <?php foreach($friendList as $friend) {?>
                                  <tr id="frnd<?php echo $friend['Friendlist']['mobile']; ?>">
                                    <td width="26px"><input title="" onclick="selectFriend('friendsTableDiv','data[Friendlist][id]')" type="checkbox" name="data[Friendlist][id]" value="<?php echo $friend['Friendlist']['id']; ?>"/></td>
                                    <td width="180px" id="nickname_<?php echo $friend['Friendlist']['id']; ?>"><?php echo $friend['Friendlist']['nickname']; ?></td>
                                    <td width="90px"><?php echo $friend['Friendlist']['mobile']; ?></td>
                                    <td><img src="/img/spacer.gif" onclick="delFriend('<?php echo $friend['Friendlist']['mobile']; ?>','<?php echo $friend['Friendlist']['id']; ?>')" title="Delete" alt="Delete" class="otherSprite oSPos18">
           </td>
                                    
                                  </tr>
                                  <?php } ?>
                                </table>
                              </div>              </div>                
                           <div style="margin-top:20px; border:1px solid #595959; padding:10px">
    							<div class="appTitle">Add Friend</div>	
                            	<fieldset>
                                   <div class="field" style="padding-top: 10px;">
	                   					<div class="fieldDetail">
                                             <div class="fieldLabel2 leftFloat"><label for="nickName">Friend Name</label></div>
                                             <div class="fieldLabelSpace2">
                                                <input tabindex="4" type="text" id="nickName" name="data[Friendlist][nickname]" class="fieldWidth1"/></div>
                                    	</div> </div>
                                        <div class="field" style="padding-top: 10px;">
                                   		<div class="fieldDetail">
                                             <div class="fieldLabel2 leftFloat"><label for="friendMobile">Mobile No.</label></div>
                                             <div class="fieldLabelSpace2">
                                                <input tabindex="5" type="text" id="friendMobile" name="data[Friendlist][mobile]" class="fieldWidth1"/></div>
                                        </div></div>
                                        <div class="field" style="padding-top: 10px;">
                                        <div class="fieldDetail">
                                             <div class="fieldLabel2 leftFloat">&nbsp;</div>
                                             <div class="fieldLabelSpace2">
                                             	<input tabindex="6" type="image" src="/img/spacer.gif" class="otherSprite oSPos7"  onclick="addFriend('nickName','friendMobile');">
                                                <?php /* echo $ajax->submit('butSubmit.png', array('url'=> array('controller'=>'users', 'action'=>'addFriend'),'update'=>'formSuccess','before'=>'formCheck("friendForm")','complete'=>'addFriend("formSuccess","friendsTable")')); */?>
                                             </div>
                                        </div></div>
                                </fieldset>
                      
                            	</div>                                                
                        	</div>
                            <div class="appColLeft">
                            	<div class="appColLeftBox"> 
                                    <div class="appTitle"><?php echo $msgData['Message']['title']; ?></div>
                                    <div class="field" style="padding-top:10px;">
            
                                          <div class="leftFloat">
                                            <label for="mobileNumber">Mobile No: </label>&nbsp;
                                          </div>
                                          <div>
                                            <input tabindex="1" type="text" id="mobileNumber" class="fieldWidth1"/>
                                          </div>
                                          <br class="clearLeft" />
                                      
                                    </div>
                                    <strong>You can edit the message below</strong> 
                                    <textarea tabindex="2" id='textContent' onkeyup="countCharacters('textContent','charCount');" class="editSMS"><?php echo strip_tags(trim($msgData['Message']['content']));?></textarea>
                                    <span id='charCount' class="hints"><?php echo $msgData['Message']['charCount']; ?>&nbsp;chars <?php echo $objGeneral->getMessageCharge($msgData['Message']['id'])*100 . " Paise"; ?></span>
                                    <div class="tagged" style="padding:10px 0px 5px;">
                                        <div class="field">
                                            <span id="defMsg" style="font-weight:bold;"></span>
                                            <div id="numFriends" class="highlight">Select friend(s) from the Friend List.
                                            </div>
                                        </div>
                                        <div class="clearLeft field">&nbsp;</div>
                                        <div  id="sendButt">
                                            <input tabindex="3" type="image" src="/img/spacer.gif" class="otherSprite oSPos8" onClick="sendMessageToFriends('<?php echo $objMd5->encrypt($msgData['Message']['id'],encKey);?>');">
                                        </div>
                                    </div>
								</div> 
                            </div>                 
                       		
                    	</div>	    
                        <div class="clearBoth"></div>             	
                  <!-- Popup send friend -->
                  <script>$$('table.dataTableBody tr:nth-child(even)').invoke("addClassName", "altRow"); countCharacters('textContent','charCount');</script>