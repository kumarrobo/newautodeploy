<div>
    <?php /*echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));*/?>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <?php //echo $this->element('shop_side_activities',array('side_tab' => 'transfer'));?>
            <div id="innerDiv">
                <?php //echo $this->element('shop_transfer'); ?>
                 	 	 	 	  	 	 
                <table class="ListTable" >
                    <thead>
                        <tr>
                        <th>S.No.</th>
                        <th>Dist Name</th>
                        <th>Retailer Name</th>
                        <th>Mobile</th>
                        <th>Balance</th>
                        <th>Avg Sale(Last 14 days)</th>
                        <th>Last Transaction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cnt = 1; foreach($data as $dt){ ?>
                            <?php foreach($dt as $d){ ?>
                            <tr>
                                <td align="center" class="center"> <?php echo $cnt;$cnt++; ?> </td>
                                <td align="center" class="center"> <?php echo $d['distributors']['company'] ?> </td>
                                <td align="center" class="center"> <?php echo $d['retailers']['shopname'] ?> </td>
                                <td align="center" class="center"><?php echo $d['retailers']['mobile'] ?></td>
                                <td align="center" class="center"><?php echo intval($d['retailers']['balance']) ?></td>
                                <td align="center" class="center"><?php echo intval($d[0]['avg_sale']) ?></td>
                                <td align="center" class="center"><?php echo $d[0]['last_trans'] ?></td>
                            </tr>
                            <?php } ?>
                         <?php } ?>
                    </tbody>
                </table>
                
            </div>
            
        </div>

    </div>
</div>
