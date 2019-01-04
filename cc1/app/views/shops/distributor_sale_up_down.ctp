<div>
    <?php //echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <?php //echo $this->element('shop_side_activities',array('side_tab' => 'transfer'));?>
            <div id="innerDiv">
                <?php //echo $this->element('shop_transfer'); ?>
                <fieldset style="padding:0px;border:0px;margin:0px;">
                <table class="ListTable" >
                    <thead>
                        <tr>
                        <th>Dist ID</th>
                        <th>Dist Name</th>
                        <th>Company</th>
                        <th>Balance</th>
                        <th>Avg Sale(Last 30 days)</th>
                        <th>Last Transaction</th>
                        <th>Difference(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $d){ ?>
                           
                            <tr>
                                <td align="center" class="center"><?php echo $d['id'] ?></td>
                                <td align="center" class="center"> <?php echo $d['name'] ?> </td>
                                <td align="center" class="center"> <?php echo $d['company'] ?> </td>
                                
                                <td align="center" class="center"><?php echo intval($d['balance']) ?></td>
                                <td align="center" class="center"><?php echo intval($d['avg_sale']) ?></td>
                                <td align="center" class="center"><?php echo $d['last_trans'] ?></td>
                                <td align="center" class="center"><?php echo $d['diff_per'] ?></td>
                            </tr>
                           
                         <?php } ?>
                    </tbody>
                </table>
                </fieldset>	 	 	 	  	 	 
            </div>
            
        </div>

    </div>
</div>
