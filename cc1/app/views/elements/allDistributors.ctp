<link href="/icheck/skins/all.css?v=1.0.2" rel="stylesheet">
<script src="/icheck/demo/js/jquery.js"></script>
<script src="/icheck/icheck.js?v=1.0.2"></script>
<script>
   	$(document).ready(function(){
	    $('.demo-list input').iCheck({
                radioClass: 'iradio_square-blue',
            }).on('ifChecked', function(event){
                var data = event.target.id.split("_");
                var dist_id = data[0];
                var user_id = data[1];
                res = confirm('Are you sure whether you wanna convert Distributor ID : '+ dist_id + ' to New System ?');
                if(res == true) {
                    $.post('/shops/convertDistToNewSystem/', {'dist_id': dist_id, 'user_id': user_id}, function(e) {
                        if(trim(e) == 1) {
                            alert("Distributor ID : "+ dist_id +" is moved to New System");
                        }
                    });
                }
	    });
        });
</script>

	  			<fieldset style="padding:0px;border:0px;margin:0px;">

				<div class="appTitle" style="margin-top:10px;">All <?php echo $modelName; ?>s</div>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
        			<?php $refund =0; ?>
			          <tr class="noAltRow altRow" >
                                    <th style="width:20px;">Sr. No.</th>
                                    <th style="width:20px;">ID</th>
                                    <th style="width:20px;">User ID</th>
			            <th style="width:135px;">Name</th>
                        <th style="width:40px;">Mobile</th>
                        <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) {?>
			             <th style="width:40px;">SuperDistributor Name</th>
                        <?php
                        }
                        ?>



                        <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>

						 <!--<th style="width:40px;">Alternate Mobile</th>-->
                                    <th style="width:40px;">Reference</th>
			             <?php //if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) {?>
			            <!--<th style="width:40px;">Type</th>-->
			            <?php //} ?>
			            <th class="number" style="width:40px;">Margin(%)</th>
			            <th class="number" style="width:40px;">Incentive(%)</th>
			            <th style="width:50px;">SD/One Time</th>
                        <?php
                        }
                        ?>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) { ?>
			            <th class="number" style="width:75px"><?php if($_SESSION ['Auth']['show_sd'] == 1){ echo 'Master Distributor'; }else { echo 'RM'; } ?> Name</th>
			            <?php } ?>
                                    <th class="number" style="width:105px">Opening Balance &nbsp;&nbsp;&nbsp;&nbsp;<span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:85px">Transferred Today <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:100px">Current Balance &nbsp;&nbsp;&nbsp;&nbsp;<span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) { ?>
                        <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>
			             <th class="number" style="width:50px">Kits Left</th>
                        <?php 
                        } 
                        ?>
			            <th  style="width:50px">Slab</th>
			            <th style="width:65px">Area</th>
                        <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>
                        <th style="width:35px">Commission Type</th>
                        <?php 
                        } 
                        ?>
                        <th style="width:50px">Created</th>
				<?php if($_SERVER['SERVER_NAME'] != 'cc.pay1.in' && $_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){ ?>
                                   <th style="width:75px">Convert to New System</th>
				<?php
                 } 
                 ?>
                <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>
			           <th style="width:40px;">&nbsp;</th>
                       <?php 
                       } 
                       ?>
			            <th style="width:40px;">&nbsp;</th>
			            <?php 
                        } 
                        ?>
			          </tr>
			        </thead>
                    <tbody>
                    <?php $i=0; $totBal = 0; $totTran = 0; $totSale = 0;$totAvg = 0;
                    foreach($records as $rec){
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';

			            $type = 'd';
		            	$totBal = $totBal + $rec['users']['balance'];
		            	$totTran = $totTran + $rec[0]['xfer'] + ( isset($datas[$rec[$modelName]['id']])?$datas[$rec[$modelName]['id']]:0 );
		            	$totSale = $totSale + $rec['users']['opening_balance'];
		            	if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {
		            		$refund = $refund + $rec[$modelName]['discounted_money'];
		            	}
			        ?>
                        <tr class="<?php echo $class; ?>"  <?php if (($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) && $rec[$modelName]['active_flag'] == 0 )  echo "style='text-decoration:line-through'"; ?> >
                                    <td><?php echo ($i+1); ?></td>
                                    <td><?php echo $rec[$modelName]['id']; ?></td>
                                    <td><?php echo $rec[$modelName]['user_id']; ?></td>
			            <td><a href="<?php echo $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER ? "#" : "/shops/showDetails/".$type."/".$rec[$modelName]['id']; ?>"><?php if($modelName == "Distributor" || $modelName == "MasterDistributor")echo $rec[$modelName]['company']; else echo $rec[$modelName]['shopname']; ?></a></td>
			            <td><?php echo $rec['users']['mobile']; ?></td>
                        <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
                         <td style="width:40px;"><?php echo $rec[$modelName]['sd_company_name']; ?></td>
                        <?php
                        }
                        ?>
						<!-- <td><?php echo $rec[$modelName]['alternate_number']; ?></td>-->
                        <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>
                                    <td><?php echo $rec[$modelName]['reference']; ?></td>
			             <?php //if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) {?>
			            <!--<td class="number"><?php if($rec[$modelName]['level'] == 1) echo "Distributor"; else if($rec[$modelName]['level'] == 2) echo "Star Agent"; else if($rec[$modelName]['level'] == 3) echo "Agent";?></td>-->
			            <?php //}?>
			            <td class="number"><?php echo $rec[$modelName]['margin']; ?></td>
			            <td class="number"><?php echo $rec[$modelName]['incentive']; ?></td>
			            <td class="number"><?php echo $rec[$modelName]['sd_amt'] ."/". $rec[$modelName]['one_time']; ?></td>
                        <?php 
                        } 
                        ?>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) { ?>
			            <td class="number"><?php echo empty ($rec['rm']['name']) ?"--" :$rec['rm']['name']; ?></td>
			            <?php
                         }
                        ?>
                                    <td class="number"><?php echo $rec['users']['opening_balance']; ?></td>
			            <td class="number"><?php echo sprintf('%.2f', $rec[0]['xfer'] + ( isset( $datas[$rec[$modelName]['id']] ) ? $datas[$rec[$modelName]['id']] : 0 ) ); ?></td>
			            <td class="number"><?php echo $rec['users']['balance']; ?></td>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) { ?>
                         <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>
			            <td class="number"><?php echo $rec[$modelName]['kits']; ?></td>
                         <?php 
                         } 
                         ?>
			            <td class="number"><?php echo $rec["slabs"]['name']; ?></td>
			            <td><?php echo $rec[$modelName]['area_range'] . " - " . $rec[$modelName]['city']; ?></td>
                         <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR) { ?>
                                    <?php $commission_type = array(0=>'Primary', 1=>'Tertiary'); ?>
                                    <td><?php echo $commission_type[$rec[$modelName]['commission_type']]; ?></td>
                        <?php 
                        } 
                        ?>
                                    <td class="number"><?php echo $rec[$modelName]['created']; ?></td>
				<?php if($_SERVER['SERVER_NAME'] != 'cc.pay1.in' && $_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){ ?>
                                    <td class="number"><?php if(($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) && $rec[$modelName]['active_flag'] == 1) { ?><center><ul class="demo-list" style="list-style:none"><li><input type="radio" id="<?php echo $rec[$modelName]['id'].'_'.$rec[$modelName]['user_id']; ?>" name="demo_<?php echo $rec[$modelName]['id']; ?>" <?php if($rec[$modelName]['system_used'] == 1) { echo "checked"; } ?>></li></ul></center><?php } ?></td>
				<?php } ?>
                <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){ ?>
                                    <td class="number"><a target="Sale Report" href="/shops/graphRetailer/?type=<?php echo $type; ?>&id=<?php echo $rec[$modelName]['id']; ?>">Analyze</a></td>
                                    <?php 
                                    } 
                                    ?>
                                    <?php if($_SESSION['Auth']['User']['group_id'] != RELATIONSHIP_MANAGER){
                                        ?>
                                    <td class="number"><a href="/shops/editRetailer/<?php echo $type; ?>/<?php echo $rec[$modelName]['id']; ?>">edit</a></td>

    			    	<?php 
                        } } 
                        ?>
    			    </tr>
    			    <?php $i++; } ?>
			         </tbody>
			         <tfoot>
			         	<tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                                                ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <?php } if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {
                                                ?>
                                            <td></td>
                                            <?php }
                                            ?>
                                            <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                                                ?>
                                            <td class="number"></td>
                                            <?php }
                                            ?>
                                            <td class="number"><?php echo $totSale; ?></td>
                                            <td class="number"><?php echo sprintf('%.2f',$totTran); ?></td>
                                            <td class="number"><?php echo $totBal; ?></td>
                                            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) {
                                              if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                                                ?>
                                            <td class="number"><?php echo $refund; ?></td>
                                            <?php 
                                        }
                                            ?>

                                            <td></td>
                                            <td></td>
                                            <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                                                ?>
                                            <td class="number"></td>
                                            <?php }
                                            ?>
                                            <td class="number"></td>
                                            <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                                                ?>
                                           <td></td>
                                           <?php 
                                           } 
                                           ?>
                                           <td></td>

                                        <?php  } ?>

			         	</tr>
			         </tfoot>
			   	</table>
			</fieldset>
