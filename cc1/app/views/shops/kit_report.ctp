<div>
   	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
            <div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'kitreport'));?>
                <h3> <tr><center><td>Distributor Kits</td></center></tr></h3>
                <tr>
                    <td>
                        <center>
                                <table style="border-collapse: collapse;border: 1px solid black; width: 450px;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid black;color:#0167A9;padding: 10px;">Services</th>
                                            <th style="border: 1px solid black;color:#0167A9;padding: 10px;">Plan</th>
                                            <th style="border: 1px solid black;color:#0167A9;">No. Of Kits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($data as $a) {
                                                echo '<tr><td style="border: 1px solid black;padding: 5px;"><b><center>'.$a['services']['name']. '</center></b></td>';
                                                echo '<td style="border: 1px solid black;padding: 5px;"><b><center>'.$a['service_plans']['plan'].'</center></b></td>';
                                                echo '<td style="border: 1px solid black;padding: 5px;"><b><center>'.$a['distributors_kits']['kits'].'</center></b></td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                        </center>
                    </td>
                </tr>
            </div>
    </div>
</div>

