<form id="entry" name="entry" method="post" accept-charset="utf-8" >
<?php echo $this->element('messages_add',array('logData' => $logData,'table' => $table));?>
<?php if($table != "data_fun") {?>

Content<br> <textarea onkeyup="countChars('transl');" onkeydown="countChars('transl');" 
			 class="input textarea" id="transl" 
			name="data[<?php echo $table; ?>][content]" style="height: 150px; width: 502px; line-height: 1.5em; 
			font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;" autocomplete="off"></textarea> <br>
		
			<small><i>
			<span id="left"> 0&nbsp;chars </span>
			</i>
            </small>
		<br>
		<?php if($table == "data_astrologies") {?>
		<div class="field">
            <div class="fieldDetail">
                 <div class="fieldLabel leftFloat"><label for="dob">Date</label></div>
                 
                 <div class="fieldLabelSpace">
                 
                    <select tabindex="4"  name="data[<?php echo $table; ?>][date][month]">
                    <?php $i = 0; while($i<12) {
                    	$i++;
                    	$str = '';
                    	$month_arr = array("Jan","Feb","Mar","Apr","May","Jun",
										"Jul","Aug","Sep","Oct","Nov","Dec");
										

						if($i < 10){
							if($month_arr[$i-1] ==  date('M'))
							echo '<option selected="selected" value="0'.$i.'">'.$month_arr[$i-1].'</option>';
							else	
							echo '<option value="0'.$i.'">'.$month_arr[$i-1].'</option>';
						}
						else {
							if($month_arr[$i-1] ==  date('M'))
							echo '<option selected="selected" value="'.$i.'">'.$month_arr[$i-1].'</option>';
							else
							echo '<option value="'.$i.'">'.$month_arr[$i-1].'</option>';
						}
					}
					?>
					</select>
					<select tabindex="5" name="data[<?php echo $table; ?>][date][day]">
						<?php $i=0; 
							while($i < 31){
								$i++;
					
								if($i < 10){
									if('0'.$i ==  date('d')+1)
									echo '<option selected="selected" value="0'.$i.'">0'.$i.'</option>';
									else
									echo '<option value="0'.$i.'">0'.$i.'</option>';				
								}
								else {
									if($i ==  date('d')+1)
									echo '<option selected="selected" value="'.$i.'">'.$i.'</option>';
									else
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
						
							}	
						?>
					</select>
					
					<select tabindex="6" name="data[<?php echo $table; ?>][date][year]">
					<option selected="selected" value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
					</select>
                 </div>
               </div>
            </div>
                   
<?php }} else { ?>
	
	Message Id<br> <input type="text" name="data[<?php echo $table; ?>][content]">		
<?php } ?>
<br>
Select Package<br>
<select id="packages" multiple="multiple" name="data[Package][]">
<?php foreach($packs as $pack) {?>
<option value="<?php echo $pack['Package']['id']; ?>"><?php echo $pack['Package']['name']; ?></option>
<?php } ?>
</select>

<br><br>
<input type="hidden" id="table" name="data[table]" value="<?php echo $table; ?>">
<input type="hidden" id="table" name="data[category]" value="<?php echo $catid; ?>">
<div id="submitted"></div>
<div class="field">
	<?php echo $ajax->submit('spacer.gif', array('url'=> array('controller'=>'groups', 'action'=>'enterData'), 'update' => 'ocategory', 'class' => 'otherSprite oSPos7', 'after' => '$("submitted").innerHTML = "Submitted successfully";')); ?>
</div>
</form>		