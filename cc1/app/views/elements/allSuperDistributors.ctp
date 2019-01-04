

	  			<fieldset style="padding:0px;border:0px;margin:0px;">

				<div class="appTitle" style="margin-top:10px;">All <?php echo $SDmodelName;?></div>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow" >
                                    <th style="width:20px;">Sr. No.</th>
                                    <th style="width:20px;">ID</th>
                                    <th style="width:20px;">User ID</th>
			            <th style="width:135px;">Name</th>
			            <th style="width:40px;">Mobile</th>

			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>
			            <th class="number" style="width:75px">RM Name</th>
			            <?php } ?>
                          <th class="number" style="width:105px">Opening Balance &nbsp;&nbsp;&nbsp;&nbsp;<span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:85px">Transferred Today <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:100px">Current Balance &nbsp;&nbsp;&nbsp;&nbsp;<span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>
			            <th  style="width:50px">Slab</th>
			            <th style="width:65px">Area</th>
                        <th style="width:50px">Created</th>
                        <?php if($_SESSION['Auth']['User']['group_id'] != RELATIONSHIP_MANAGER) { ?>
			            <th style="width:40px;">Action</th>
			            <?php } }  ?>
			          </tr>
			        </thead>
                    <tbody>
                    <?php $i=0; $totBal = 0; $totTran = 0; $totSale = 0;
                    foreach($SDrecords as $rec){
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';

			            $type = 'sd';
		            	$totBal = $totBal + $rec['users']['balance'];
		            	$totTran = $totTran + $rec[0]['xfer'] + ( isset($datas[$rec[$SDmodelName]['id']])?$datas[$rec[$SDmodelName]['id']]:0 );
		            	$totSale = $totSale + $rec['users']['opening_balance'];
			        ?>
                        <tr class="<?php echo $class; ?>"  <?php if ($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR && $rec[$SDmodelName]['active_flag'] == 0 )  echo "style='text-decoration:line-through'"; ?> >
                                    <td><?php echo ($i+1); ?></td>
                                    <td><?php echo $rec[$SDmodelName]['id']; ?></td>
                                    <td><?php echo $rec[$SDmodelName]['user_id']; ?></td>
			            <td><a href="<?php echo $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER ? "#" : "/shops/showDetails/".$type."/".$rec[$SDmodelName]['id']; ?>"><?php if($SDmodelName == "Distributor" || $SDmodelName == "MasterDistributor")echo $rec[$SDmodelName]['company']; else echo $rec[$SDmodelName]['shopname']; ?></a></td>
			            <td><?php echo $rec['users']['mobile']; ?></td>
			             
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>
			            <td class="number"><?php echo empty ($rec['rm']['name']) ?"--" :$rec['rm']['name']; ?></td>
			            <?php }?>
                                    <td class="number"><?php echo $rec['users']['opening_balance']; ?></td>
			            <td class="number"><?php echo sprintf('%.2f', $rec[0]['xfer'] + ( isset( $datas[$rec[$SDmodelName]['id']] ) ? $datas[$rec[$SDmodelName]['id']] : 0 ) ); ?></td>
			            <td class="number"><?php echo $rec['users']['balance']; ?></td>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>
			            <td class="number"><?php echo $rec["slabs"]['name']; ?></td>
			            <td><?php echo $rec[$SDmodelName]['city']; ?></td>
                                    <td class="number"><?php echo $rec[$SDmodelName]['created']; ?></td>
                                    <?php if($_SESSION['Auth']['User']['group_id'] != RELATIONSHIP_MANAGER){?>
                                    <td class="number"><a href="/shops/editSuperDistributor/<?php echo $rec[$SDmodelName]['id']; ?>">Edit</a></td>

    			    	<?php } } ?>
    			    </tr>
    			    <?php $i++; } ?>
			         </tbody>
			         <tfoot>
			         	<tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>
                                            <td></td>
                                            <?php }?>
                                            <td class="number"></td>
                                            <td class="number"><?php echo $totSale; ?></td>
                                            <td class="number"><?php echo sprintf('%.2f',$totTran); ?></td>
                                            <td class="number"><?php echo $totBal; ?></td>
                                            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>

                                            <td></td>
                                            <td class="number"></td>
                                            <td class="number"></td>
                                           <td></td>

                                        <?php  } ?>

			         	</tr>
			         </tfoot>
			   	</table>
			</fieldset>
