<div>
    <?php /*echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));*/?>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <?php //echo $this->element('shop_side_activities',array('side_tab' => 'transfer'));?>
            <div id="innerDiv">
                <?php //echo $this->element('shop_transfer'); ?>
                 	 	 	 	  	 	 
                <table class="ListTable" >
                    <thead>
                        <th>S.No.</th>
                        <th>Distributor</th>
                        <th>Retailer Name</th>
                        <th>Mobile</th>
                        <th>Balance</th>
                        <th>Sale</th>
                    </thead>
                    <tbody>
                        <?php $cnt=1;foreach($data as $d){ ?>
                           
                            <tr>
                                <td align="center" class="center"> <?php echo $cnt;$cnt++; ?> </td>
                                <td align="center" class="center"> <?php echo $d['distributors']['company'] ?> </td>
                                <td align="center" class="center"> <?php echo $d['retailers']['shopname'] ?> </td>
                                <td align="center" class="center"><?php echo $d['retailers']['mobile'] ?></td>
                                <td align="center" class="center"><?php echo intval($d['retailers']['balance']) ?></td>
                                <td align="center" class="center"><?php echo intval($d['retailers_logs']['sale']) ?></td>
                            </tr>
                            
                         <?php } ?>
                    </tbody>
                </table>
                
            </div>
            
        </div>

    </div>
</div>
