<div class="appColLeftBox">
      			<div class="appTitle">Set future Message for groups/individuals</div>      			
				<fieldset>
				<form name="appRemForm" id="appRemForm" method="post">					
	            	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat"><label for="username">To:</label></div>
	                         <div class="fieldLabelSpace2" style="position:relative;left:-6px; font-size:0.8em; font-weight:bold;">
	                            <div id="r_receivers" style="max-height:175px; overflow:auto">
								 	<span>
								 		<span>
								 			<span id="li_me">
								 				<nobr>
								 					<div style="float: none;">
								 						<div style="overflow: visible; font-size: 12px;">
								 							<img border="0" align="middle" style="vertical-align: top;" src="/img/imgs/line3.gif">&nbsp; 							
								 							<input value="<?php echo $_SESSION['Auth']['User']['mobile']; ?>" type='checkbox' name='c_me'> Me 							
								 						</div>
								 					</div>
								 				</nobr> 			
								 			</span>
								 		<?php 
								 			$grpItr = 0;
								 			$grpCnt = count($contactArr);
								 			foreach($contactArr as $k=>$v){
								 			if ($grpItr == $grpCnt - 1) {
									        	$grpImg = '2';
									        	$conLneImg = 'empty';
								    		}else{
								    			$grpImg = '3';
								    			$conLneImg = 'line1';
								    		}
								 		?>
								 			<span id="li_<?php echo $k;?>">
									 			<nobr>
									 				<div style="float: none;">
									 					<div style="overflow: visible;font-size: 12px;">
									 						<img border="0" onclick='showChildren("<?php echo $k;?>","<?php echo $grpImg;?>",this);' align="middle" style="vertical-align: top;" src="/img/imgs/plus<?php echo $grpImg;?>.gif">&nbsp;	 						
									 						<input type='checkbox' value="" onclick='checkChildren($("li_<?php echo $k;?>"),this);'> <?php echo ucwords($k);?>
									 					</div>
									 				</div>
									 			</nobr>
									 			<span style="display: none;" id="li_<?php echo $k;?>_children">
												<?php
													$conItr = 0;
													$conCnt = count($v);
													foreach($v as $k1=>$v1){
													if ($conItr == $conCnt - 1) {
											        	$conImg = 'line2';			        	
										    		}else{
										    			$conImg = 'line3';		    		
										    		} 
										    		
												?>	 			
									 				<span>
									 					<nobr>
									 						<div style="float: none;">
									 							<div style="font-size: 11px; font-weight: normal">
									 								<img border="0" align="top" src="/img/imgs/<?php echo $conLneImg;?>.gif">
									 								<img border="0" align="middle" style="vertical-align: top;" src="/img/imgs/<?php echo $conImg;?>.gif">&nbsp;	 								
									 								<input type='checkbox' value="<?php echo $v1;?>" onclick='checkParent($("li_<?php echo $k;?>"),this);'> <?php echo $k1 . " - " . $v1;?>	 								
									 							</div>
									 						</div>
									 					</nobr>	 					
									 				</span>
									 		   <?php $conItr++; } ?>			 					 				
								 				</span> 				
									 		</span>
								 		<?php
								 			$grpItr++;		
								 			}
								 		?> 		
									 	</span>
									</span>
								</div>															
	                         </div>	                         
	            		</div>
	            	<div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat">&nbsp;</div>
	                         <div class="fieldLabelSpace2" style="position:relative;left:-6px; font-size:0.8em; font-weight:bold">
	                         	<div style="margin-bottom:3px;">
                                <span class="leftFloat"></span>
                                <span style="padding-top:2px; padding-left: 6px;">Others</span>
                                </div>
                                <div style="display:block;position:relative;left:6px;">
                                	<input type="text" id="AppRemFor" name="data[appRem][For]" value="" style="width:260px;">
                                </div>	                            	                           
	                         </div>
	                         <div class="fieldLabelSpace2"><span class="hints">Type 10 digit mobile number(s). Use comma (,) to separate multiple numbers.</span>
	                         <div style="display:none;" class="inlineErr1" id="appRemFor_err"></div>
	                         </div>
	                                                                       
	                 	</div>
	            	</div>	            		            	
	            	<div style="padding-top: 10px;" class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat"><label for="username">Message:</label></div>
	                         <div class="fieldLabelSpace2">
	                         	<textarea onkeyup="resCharacters('appRemMsg',APP_REM_MSG_LMT - APP_REM_MSG_FIXED,'appRemCharLmt');" onkeydown="resCharacters('appRemMsg',APP_REM_MSG_LMT - APP_REM_MSG_FIXED,'appRemCharLmt');" name="appRemMsg" id="appRemMsg" cols="39" rows="4" onfocus="if(this.value == 'Your message here'){this.value='';}" onblur="if(this.value == ''){this.value='Your message here';}" >Your message here</textarea>
	                            <div><span id="appRemCharLmt">Upto <?php echo APP_REM_MSG_LMT - APP_REM_MSG_FIXED; ?> chars</span></div>
                              <span style="display:none;" class="inlineErr1" id="appRemMsg_err"></span>   
	                         </div>
	                                           
	                 	</div>
	            	</div>
	            	
	            	<div class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat"><label for="username">Schedule:</label></div>
	                         <div class="fieldLabelSpace2" style="position:relative;left:-6px; font-size:0.8em; font-weight:bold">
	                         	<div style="margin-bottom:3px;">
                                <span class="leftFloat"><input onclick="remindWhen();" type="radio" name="appRemSend" id="appRemSendLr" value="l" checked/> For future</span>
                                <span class="leftFloat"><input onclick="remindWhen();" type="radio" name="appRemSend" id="appRemSendNw" value="n" /> Right now!</span>
                                </div>	                            
	                         </div>	                         	                         
	                         </div>	                                                                      
	                 	</div>
	            	
	            	<div style="padding-top: 0px;display:block;" class="field" id="remindLater">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat">&nbsp;</div>
	                         <div class="fieldLabelSpace2">	                         
	                         	<div style="margin-right:10px;float:left;margin-left:-5px;">&nbsp;<input type="text" name="appRemDate" id="appRemDate" onmouseover="fnInitCalendar(this, 'appRemDate','expiry=true,close=true')" maxlength="10" style="width:70px;"><span class="hints">&nbsp;Date</span> </div>
	                            <div style="float:left;"> 
	                            <?php //TRAI Changes
	                            	/*if(!DND_FLAG || TRANS_FLAG){
	                            		$timeArr = array("08:00" => "08:00 AM","08:30" => "08:30 AM","09:00" => "09:00 AM","09:30" => "09:30 AM","10:00" => "10:00 AM","10:30" => "10:30 AM","11:00" => "11:00 AM","11:30" => "11:30 AM","12:00" => "12:00 PM","12:30" => "12:30 PM","13:00" => "01:00 PM","13:30" => "01:30 PM","14:00" => "02:00 PM","14:30" => "02:30 PM","15:00" => "03:00 PM","15:30" => "03:30 PM","16:00" => "04:00 PM","16:30" => "04:30 PM","17:00" => "05:00 PM","17:30" => "05:30 PM","18:00" => "06:00 PM","18:30" => "06:30 PM","19:00" => "07:00 PM","19:30" => "07:30 PM","20:00" => "08:00 PM","20:30" => "08:30 PM","21:00" => "09:00 PM","21:30" => "09:30 PM","22:00" => "10:00 PM","22:30" => "10:30 PM","23:00" => "11:00 PM","23:30" => "11:30 PM","00:00" => "00:00 AM","00:30" => "00:30 AM","01:00" => "01:00 AM","01:30" => "01:30 AM","02:00" => "02:00 AM","02:30" => "02:30 AM","03:00" => "03:00 AM","03:30" => "03:30 AM","04:00" => "04:00 AM","04:30" => "04:30 AM","05:00" => "05:00 AM","05:30" => "05:30 AM","06:00" => "06:00 AM","06:30" => "06:30 AM","07:00" => "07:00 AM","07:30" => "07:30 AM"); 
	                            	}
	                            	else {
	                            		$timeArr = array("09:30" => "09:30 AM","10:00" => "10:00 AM","10:30" => "10:30 AM","11:00" => "11:00 AM","11:30" => "11:30 AM","12:00" => "12:00 PM","12:30" => "12:30 PM","13:00" => "01:00 PM","13:30" => "01:30 PM","14:00" => "02:00 PM","14:30" => "02:30 PM","15:00" => "03:00 PM","15:30" => "03:30 PM","16:00" => "04:00 PM","16:30" => "04:30 PM","17:00" => "05:00 PM","17:30" => "05:30 PM","18:00" => "06:00 PM","18:30" => "06:30 PM","19:00" => "07:00 PM","19:30" => "07:30 PM","20:00" => "08:00 PM","20:30" => "08:30 PM");
	                            	}*/
	                            	
	                            	$timeArr = array("10:00" => "10:00 AM","10:30" => "10:30 AM","11:00" => "11:00 AM","11:30" => "11:30 AM","12:00" => "12:00 PM","12:30" => "12:30 PM","13:00" => "01:00 PM","13:30" => "01:30 PM","14:00" => "02:00 PM","14:30" => "02:30 PM","15:00" => "03:00 PM","15:30" => "03:30 PM","16:00" => "04:00 PM","16:30" => "04:30 PM","17:00" => "05:00 PM","17:30" => "05:30 PM","18:00" => "06:00 PM","18:30" => "06:30 PM","19:00" => "07:00 PM","19:30" => "07:30 PM","20:00" => "08:00 PM");
	                            	//ends
	                            ?>                           
	                            <select name="appRemTime" id="appRemTime" style="width:80px">
	                            	<?php 
	                            	 foreach($timeArr as $k => $v){
	                            	 	$selected = '';
	                            	 	if($k == date('H').":00")
	                            	 	$selected = 'selected';
	                            	 	
	                            		echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
	                            	 }
	                            	?>							          
							    </select>
	                            <span class="hints">Time</span>
	                            </div>
    							<div class="clearLeft"></div>
		                           	 <span style="display:none;" class="inlineErr1" id="appRemDateTime_err"></span>     
	                         </div><div class="clearLeft"></div>
	                         <div class="fieldLabelSpace2" style="padding-top:10px;position:relative; left:-4px; font-size:0.8em; font-weight:bold">
	                            <input type="checkbox" name="appRemRepeatChk" id="appRemRepeatChk" onclick="if(this.checked==true){$('appRemRepeatBox').show();}else{$('appRemRepeatBox').hide();}"/> Repeat Message
	                         </div> 
                                            
	                 	</div>
	            	</div>
	            	<!-- Grey Box -->
	            	<div id="appRemRepeatBox" style="display:none;background:#d0cfcf;border:1px solid #555555;padding:0px 0px 12px 12px;margin-bottom:20px" class="field">
	            		<div class="rightFloat" style="border-left:1px solid #555555;border-bottom:1px solid #555555; font-size:0.9em;line-height:0.8em; font-weight:bold" ><a href="javascript:void(0);" onclick="$('appRemRepeatBox').hide();$('appRemRepeatChk').checked=false;" style="padding:2px 4px 4px; display:block">x</a></div> <!-- Common class -->
	            		<div>&nbsp;</div>
	            		<div></div>
	            		
	            		<div class="field">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel2 leftFloat"><label for="username">Repeats</label></div>
		                         <div class="fieldLabelSpace2">
		                         	<select style="width:225px" name="appRemRepeatBy" id="appRemRepeatBy" onchange="appRemRepeat(this.value);">          
							          <option value="1">Daily</option>
							          <option value="2">Weekly</option>
							          <option value="3">Monthly</option>
							          <option value="4">Yearly</option>
							        </select>
		                         	<!--<select style="width:225px"><option>Weekly</option></select>-->		                            
		                         </div>		                                              
		                 	</div>
		            	</div>
		            	<div class="field">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel2 leftFloat"><label for="username">Repeat every</label></div>
		                         <div class="fieldLabelSpace2">
		                         	<select style="width:60px" name="appRemRepeatFreq" id="appRemRepeatFreq">
		                         	<?php for($i=1;$i<=30;$i++){ ?>
							          	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							        <?php } ?>
		                         	</select>
		                         	<span id="appRemRepeatType">Days</span>
		                         </div>		                                              
		                 	</div>
		            	</div>
		            	<div class="field" id="appRemWeekBox" name="appRemWeekBox" style="display:none;">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel2 leftFloat"><label for="username">Repeat on</label></div>
		                         <div class="fieldLabelSpace2" style="position:relative; left:-4px;">
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="0"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">S</div>
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="1"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">M</div>
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="2"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">T</div>
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="3"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">W</div>
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="4"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">T</div>
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="5"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">F</div>
		                         	<div class="leftFloat"><input type="checkbox" name="appRemWeekday" value="6"></div><div class="leftFloat" style="margin-right:5px; padding-top:2px;">S</div>		                         	
		                            <div class="clearLeft">&nbsp;</div>
		                            <span style="display:none;" class="inlineErr1" id="appRemWeekday_err"></span>
		                         </div>
		                         		                                              
		                 	</div>
		            	</div>
		            	<div class="field">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel2 leftFloat"><label for="username">Ends on</label></div>
		                         <div class="fieldLabelSpace2" style="position:relative; left:-4px;">
		                         	<span class="" style="margin-right:5px;"><input type="radio" name="appRemEndRadio" id="appRemEndRadioN" value="never" checked/>Never</span>
		                         	<span class="" style="margin-right:5px;"><input type="radio" name="appRemEndRadio" id="appRemEndRadioU" value="until" />Until</span>
		                         	<span class="" style="margin-right:5px;"><input type="text" name="appRemEndDate" id="appRemEndDate" onmouseover="fnInitCalendar(this, 'appRemEndDate','expiry=true,elapse=1,close=true')" style="width:117px;" /></span>
		                         	<span class="clearLeft">&nbsp;</span>
		                         	<div style="display:none;" class="inlineErr1" id="appRemEndDate_err"></div>
		                         </div>		                         		                                              
		                 	</div>
		            	</div>
	            		
					</div>
					<!-- Grey Box ends -->
	            	<div class="field">
	                    <div class="fieldDetail">
	                         <div class="fieldLabel2 leftFloat">&nbsp;</div>
	                         <div class="fieldLabelSpace2" id="sendButt">
	                            <input type="button"  value="Set Message" class="css3But4" onclick="setReminder();">
	                         </div>
	                         <div class="fieldLabelSpace2">
	                         <span id="appRemAjaxErr" class="inlineErr1" style="display:none;"> </span>
	                         </div>	                                              
	                 	</div>
	            	</div>
	            	<div class="clearLeft"></div>	            
	           	</form>
	           	</fieldset>
			</div>
		</div>	