<div id="innerBlock" class="ie6Fix2 inlineBlockElementNew">
				  <input type='hidden' value='<?php echo $count;?>' id='count'>
				  <div style="padding-left:10px;padding-bottom:10px; float:left">
			        	
			        	<span>
					        <?php if(isset($bcrumb) && $bcrumb == "1") {
					        	echo '<a href="/"> Home </a>';
					        }
					        else {
					        	echo $ajax->link( 
				    				'Packages', 
				    				array('controller' => 'users', 'action' => 'package' ), 
				    				array( 'update' => 'pageContent')
									); 
							}
							?>
						</span>
						&nbsp;>>&nbsp;
						<span id="catName">
						</span>
			        </div>
				  <?php if($count > $limit) {?>
				  <div class="ie6Fix2 pagination" style="padding-bottom:10px;padding-top:0px; float:right;"> <!-- rightFloat -->
                  	  <ul style="padding:0px; margin:0px;">
                      	<li><?php if($page == 1) { ?>
			           		<span class="lightText"> &lt;&lt; Previous </span>
			           		<?php } else { ?>
			           		<span> <a class="noAffect" href="javascript:void(0);" onclick="getPage(<?php echo ($page - 1);?>);">&lt;&lt; Previous</a> </span> 
			           		<?php } ?></li>
                        <li class="paginationNo"><?php $tot = (int)($count/$limit);
			           		 if($count%$limit != 0)
			 					$tot++;
			 					
			 				 for($i =1;$i<=$tot;$i++){
			 				 	if($i != $page){?>
			 				 		<span> <a class="" href="javascript:void(0);" onclick="getPage(<?php echo $i;?>);"><?php echo $i;?></a> </span>
			 				 <?php	} else { ?>
			 				 		<span class="current"><?php echo $i;?></span>
			 				 <?php }
			 				 	
			 				 }	
			           ?>  </li>
                        <li class="lastElement"><?php if($page == $tot) { ?>
			           		<span class="lightText"> Next &gt;&gt; </span>
			           		<?php } else { ?>
			           		<span> <a class="noAffect" href="javascript:void(0);" onclick="getPage(<?php echo ($page + 1);?>);">Next &gt;&gt;</a> </span> 
			           		<?php }?></li>
                      </ul>
                      <br class="clearLeft" />
			          
			        </div>
			        
			        <?php } ?>
			        
			        <!-- <br class="clearRight" /> -->
                    <div class="clearBoth"></div>
                <?php if($layout == 'L') { ?>
	                <ul class="box6Cont2" title="Package List">
	                <?php foreach($packData as $othPackage) { ?>
	                            <li class="ie6Fix2">
                                  <div id="packSubbut<?php echo $othPackage['Package']['id']  ?>" class="rightFloat butColumn" style="display:block;">
                                  	<a href="javascript:void(0);" id="but<?php echo $othPackage['Package']['id']  ?>" onclick="subPackage('<?php echo $objMd5->encrypt($othPackage['Package']['id'],encKey) ?>','sub',this);" class="buttSprite" ><img src="/img/spacer.gif" class="butSubscribe" /></a>
                                  	<?php if($othPackage['Package']['trial_days'] > 0) {?>
                                  		<span> &nbsp; or</span> <a href="javascript:void(0);" style="font-size:11px; margin-top:5px; text-decoration:underline;" onclick="subPackageTrial('<?php echo $objMd5->encrypt($othPackage['Package']['id'],encKey) ?>','sub',this);">Try FREE</a>
                                  	<?php } ?>
                                  </div>
                                  <div id="packSubLoaderbut<?php echo $othPackage['Package']['id']  ?>" class="rightFloat butColumn" style="display:none; padding-right:10px;"><img src="/img/loader2.gif" /></div>
                                  <div class="packageDetail">
                                      <div class="leftFloat packageImg thumbImage1" style="position:relative">
                                      <?php if($this->Session->read('Auth.User')) {?>  
                                      <a href="javascript:void(0);" onclick="getPackage('<?php echo $othPackage['Package']['url']; ?>')"> 
                                      <?php } else {?>
                                      <a href="/packages/view/<?php echo $othPackage['Package']['url']; ?>"> 
                                      <?php } 
                                      	echo $html->image("/img/spacer.gif", 
                                            array( "alt" => $othPackage['Package']['name'], "title" => $othPackage['Package']['name'],
                                                   "class" => 'package_'.$othPackage['Package']['id'],
                                                   "border" => '0',	
                                                   "title" => $othPackage['Package']['name'],
                                                   "href" => "javascript:void(0);"
                                             ));
                                        /*if(in_array($othPackage['Package']['id'],$moneyBack)){     
                                        	echo $html->image("/img/spacer.gif",
                          						array( "alt" => '7 day back', "title" => '7 day back', "class" => 'day7Smaller'));
                                 		}*/
                                      ?></a>
                                      </div>                                      
                                      <div class="packageDetail1">
                                      	<h2 class="packName">
                                      	<?php if($this->Session->read('Auth.User')) {?><a href="javascript:void(0);" onclick="getPackage('<?php echo $othPackage['Package']['url']; ?>')"><?php } else {?><a href="/packages/view/<?php echo $othPackage['Package']['url']; ?>"><?php }echo $othPackage['Package']['name']; ?></a>	
                                      	
                                      	</h2>
									  	<p class="packDesc"><?php echo $othPackage['Package']['shortDesc']; ?></p>
                                      </div>
                                      <div class="packageDetail2 leftFloat">
                                      	<div><span style="padding-right:3px;">Price:</span><span style="padding-right:3px;"><img src="/img/rs.gif" height="12px"/></span><?php echo floor($othPackage['Package']['price']); ?> for <?php echo floor($othPackage['Package']['validity']); ?> days</div>                                      	
									  	<div><span>Frequency: </span><?php echo $objGeneral->getFrequency($othPackage['Package']['frequency']);?></div>
                                        <!-- <div><span>SMS Code:</span> <?php echo $othPackage['Package']['code']; ?></div>  -->                                      
                                      </div>
                                      <div class="clearLeft">&nbsp;</div>
                                  </div>
                                  <div class="clearBoth">&nbsp;</div>
	                            </li>
	                           <?php } ?>                               
	                </ul>
	                
	                <?php } if($layout == 'G') {
	                	echo $this->element('grid',array('packData' => $packData));
	                 } ?>
	                <?php if($count > $limit) {?>
                    <div>
                    	<div class="leftFloat">&nbsp;</div>
                    	<div class="ie6Fix2 pagination" style="padding-bottom:10px;padding-top:0px; float:right; "> <!-- rightFloat -->
                  	  <ul style="margin:0px; padding:0px;">
                      	<li><?php if($page == 1) { ?>
			           		<span class="lightText"> &lt;&lt; Previous </span>
			           		<?php } else { ?>
			           		<span> <a class="noAffect" href="javascript:void(0);" onclick="getPage(<?php echo ($page - 1);?>);">&lt;&lt; Previous</a> </span> 
			           		<?php } ?></li>
                        <li class="paginationNo"><?php $tot = (int)($count/$limit);
			           		 if($count%$limit != 0)
			 					$tot++;
			 					
			 				 for($i =1;$i<=$tot;$i++){
			 				 	if($i != $page){?>
			 				 		<span> <a class="" href="javascript:void(0);" onclick="getPage(<?php echo $i;?>);"><?php echo $i;?></a> </span>
			 				 <?php	} else { ?>
			 				 		<span class="current"><?php echo $i;?></span>
			 				 <?php }
			 				 	
			 				 }	
			           ?>  </li>
                        <li class="lastElement"><?php if($page == $tot) { ?>
			           		<span class="lightText"> Next &gt;&gt; </span>
			           		<?php } else { ?>
			           		<span> <a class="noAffect" href="javascript:void(0);" onclick="getPage(<?php echo ($page + 1);?>);">Next &gt;&gt;</a> </span> 
			           		<?php }?></li>
                      </ul>
                      <br class="clearLeft" />
			          
			        </div>
                    </div>	                
		           <br class="clearBoth" />
		           <?php } ?> 	              
</div>
<script>
getBreadCrumb('catName',0);
</script>
