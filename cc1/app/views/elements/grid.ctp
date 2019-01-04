<div class="hints" style="padding:5px 0px 10px 10px;" id="Pagedesc"></div>
<ul class="gridBox">                    
    	<!--<div class="packageDetail">
    				<div class="packName">                                      	  
                      <a onclick="getPackage('santa-banta-jokes')" href="javascript:void(0);">Santa Banta Jokes</a><a href="javascript:void(0);" class="smallerFont" style="font-weight:normal;"> [ Know more ]</a>	
                    </div>
   			</div> -->
    <?php  foreach($packData as $othPackage) { ?>
    	<li class="gridBoxElement">
    		<div class="gridBoxElementCont">                    			
    			<div class="leftFloat packageImg thumbImage1" style="position:relative">
    				<?php if($this->Session->read('Auth.User')) {?>    				
    				<a href="javascript:void(0);" onclick="getPackage('<?php echo $othPackage['Package']['url']; ?>')">
    				<?php } else {?>
    				<a href="/packages/view/<?php echo $othPackage['Package']['url']; ?>">
    				<?php } ?>
    				
    				<?php if($othPackage['Package']['id'] == '159'){ ?>
    				
    				<img width="70px" height="70px" src="/img/jlp.jpg" /> 
    				
    				<?php }else{ 	
    				echo $html->image("/img/spacer.gif", 
                                array( "alt" => $othPackage['Package']['name'], "title" => $othPackage['Package']['name'],
                                       "class" => 'package_'.$othPackage['Package']['id'],
                                       "border" => '0',	
                                       "title" => $othPackage['Package']['name'],
                                       "href" => "javascript:void(0);"
                                 ));
                          if(in_array($othPackage['Package']['id'],$moneyBack)){       
                        		echo $html->image("/img/spacer.gif",
	                          		array( "alt" => '7 day back', "title" => '7 day back', "class" => 'day7Smaller'));
                           }
                    } ?></a>
                </div>
                <div class="packageDetail gridLeftCol">
                	<div class="packName">
	            		<?php if($this->Session->read('Auth.User')) {?>  
	                      <a href="javascript:void(0);" onclick="getPackage('<?php echo $othPackage['Package']['url']; ?>')"> 
	                    <?php } else {?>
	                      <a href="/packages/view/<?php echo $othPackage['Package']['url']; ?>"> 
	                    <?php }
	                      	echo $othPackage['Package']['name']; ?>
	                      </a>
                    </div>
                    <div>
                    	<span style="padding-right: 3px;"><img height="12px" src="/img/rs.gif"></span><?php echo floor($othPackage['Package']['price']); ?> for <?php echo floor($othPackage['Package']['validity']); ?> days
                    </div>
                    <div><?php echo $objGeneral->getFrequency($othPackage['Package']['frequency']); ?>
		            </div>
                    <div class="but1"><a class="buttSprite1" onclick="subPackage('<?php echo $objMd5->encrypt($othPackage['Package']['id'],encKey) ?>','sub',this);" href="javascript:void(0);"><img class="butSubscribe1" src="/img/spacer.gif"></a></div>
                	<?php if($othPackage['Package']['trial_days'] > 0) {?>
                          <span style="margin-left:12px;" > &nbsp; or</span> <a href="javascript:void(0);" style="font-size:11px; text-decoration:underline;" onclick="subPackageTrial('<?php echo $objMd5->encrypt($othPackage['Package']['id'],encKey) ?>','sub',this);">Try FREE</a>
                    <?php } ?>
                </div>
                <div class="clearLeft"></div>
			</div>
		</li>
	<?php } ?>
    </ul>
    <div class="clearLeft"></div>