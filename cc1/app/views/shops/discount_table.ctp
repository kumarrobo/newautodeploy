<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'setting'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_profile',array('side_tab' => 'commission'));?>
    		<div id="innerDiv" class="leftFloat">
	  			<div class="appTitle" style="margin-top:0px;">Discount Table</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="1" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th>Sr. No.</th>
			            <th>Product</th>
			            <?php /*if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
			            	echo "<th>Super Distributor (%) </th>";
			             }
			             if($_SESSION['Auth']['User']['group_id'] <= DISTRIBUTOR){
			            	echo "<th>Distributor (%) </th>";
			             }*/
			             if($_SESSION['Auth']['User']['group_id'] <= RETAILER){
			            	echo "<th>Retailer (%) </th>";
			             }
			             ?>
			          </tr>
			        </thead>
                    <tbody>
                    <?php $i=1; foreach($data['R'] as $dt){?>
                    <tr>
            			<td><?php echo $i; ?></td>
            			<td><?php echo $dt['0']['prodName']; ?></td>
            			<?php /*if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){
			            	echo "<td> ".$data['SD'][$i-1]['0']['prodPercent']." </td>";
			             }
			             if($_SESSION['Auth']['User']['group_id'] <= DISTRIBUTOR){
			            	echo "<td> ".$data['D'][$i-1]['0']['prodPercent']." </td>";
			             }*/
			             if($_SESSION['Auth']['User']['group_id'] <= RETAILER){
			            	echo "<td> ".$dt['0']['prodPercent']." </td>";
			             }
			             ?>
            		</tr>	
                    <?php $i++;} ?>
                    </tbody>
                    </table>  
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>