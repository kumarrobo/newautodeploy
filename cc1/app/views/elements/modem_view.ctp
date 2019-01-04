<?php $selectedDate = 0;
$todayDate = strtotime('today');
$selectedDate = !empty($this->params['url']['searchbydate']) ? strtotime($this->params['url']['searchbydate']) : $todayDate; ?>    
<?php $previousDay = ($todayDate - $selectedDate > 0) ? true : false; ?>
<?php $recharge_method = Configure::read('recharge_method'); ?>

<div id="modem_view" class="tab-pane fade in active">

    <?php //echo $this->element('success_failure'); ?>

    <?php echo $this->element('modemwise_success_failure'); ?>
    <?php echo $this->element('api_balances'); ?>
    <?php echo $this->element('highlights'); ?>

    <?php //echo $this->element('last_updated_modem'); ?>

<?php echo $this->element('search_form'); ?>

    <marquee behavior="alternate" scrollamount="2" class="row">
        <div class="alert alert-danger" role="alert">
            <label style="font-size:large;">Disabled Modem:</label>
            <?php
            echo implode(",", $disabledModem);
            ?>
            <br/>

        </div>
    </marquee>

    <?php if (!empty($modems)): ?>
        <?php
        foreach ($modems as $modem):
//                   if(empty($modem['operators']))continue;
            ?>



            <?php
            $totalactiveSims = 0;
            $totalSims = 0;
            $totalBlocked = 0;
            $totalStopped = 0;
            $totalBalance = 0;
            $totalOpening = 0;
            $totalIncoming = 0;
            $totalSale = 0;
            $totalDiff = 0;
            $totalClosing = 0;
            $totalIncomingClo = 0;
            $totalHomeSale = 0;
            $totalRoamingSale = 0;
            $totalServerDiffnew = 0;
            ?>



            <div class="row" <?php if ($modem['vendors']['inactive'] == 1): ?> style="background-color:#f9e1e1" <?php endif; ?>>

                <div class="col-lg-12">
                    <h2><?php echo $modem['vendors']['company']; ?> </h2>
                    <p><?php if (!empty($modem['vendors']['portsInfo']['lasttime'])): echo $modem['vendors']['portsInfo']['lasttime'];
            endif ?>

                        <?php
                        if (!empty($modem['vendors']['portsInfo']['ports'])):
                            $ports = json_decode($modem['vendors']['portsInfo']['ports'], true);
                            ?>
                            [ Total Ports : <?php echo $ports['total']; ?>  Detected : <?php echo $ports['ports']; ?> IP :<?php echo isset($modem['vendors']['portsInfo']['modem_ip']) ? $modem['vendors']['portsInfo']['modem_ip'] : ""; ?>]

                <?php endif; ?>
                    </p>

                </div>
        <?php //if(!$isDistributer):  ?>
                <div class="row">
                    <div class="col-lg-12">

                        <button class="btn btn-default btn-showall btn-xs" data-modemid="modem_<?php echo $modem['vendors']['id']; ?>"><i class="glyphicon glyphicon-eye-open"></i> Show All</button>
                        <button class="btn btn-default btn-hideall btn-xs" data-modemid="modem_<?php echo $modem['vendors']['id']; ?>"><i class="glyphicon glyphicon-eye-close"></i> Hide All</button>
                        <a target="_blank" class="btn btn-info btn-xs" href="http://<?php echo $modem['vendors']['ip'] . ":" . $modem['vendors']['port'] . "/phpmyadmin/"; ?>"><?php echo $modem['vendors']['company']; ?> balances</a>
                        <a target="_blank" class="btn btn-info btn-xs" href="/recharges/simPanel/<?php echo $modem['vendors']['id'] ?>">Sims</a>
                        <a target="_blank" class="btn btn-info btn-xs" href="/sims/getModemsimsDetails/<?php echo $modem['vendors']['id'] ?>">Edit Sims</a>
                        <a href="#downloadTransactionsModal" data-toggle="modal" data-address="<?php echo $modem['vendors']['ip'] ?>:<?php echo $modem['vendors']['port']; ?>" class="btn btn-xs btn-download open-downloadtransactionsmodal"><span class="glyphicon glyphicon-arrow-down"></span>Download Transaction</a>

                    </div>
                </div>   
        <?php //endif;   ?> 
                <div class="col-lg-12">
                    <table class="table table-condensed table-hover modem_view_table" id="modem_<?php echo $modem['vendors']['id']; ?>">

                        <thead>
                            <tr>
                                <th>Operator</th>
                                <th>Sims</th>
                                <th>Request/min</th>
                                <th>Blocked/Stopped</th>
                                <th>Balance</th>
                                <th>Opening</th>
                                <th>Closing</th>
                                <th>Incoming</th>
                                <th>R. Sale</th>
                                <th>H.Sale</th>
                                <th>Sale</th>
                                <th>Diff</th>
                                <th>S.Diff</th>
                                <th>Blocked Bal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($modem['operators'] as $operator):

                                $operator_id = $modem['vendors']['id'] . "_" . $operator['products']['id'];
                                $totalactiveSims += $operator['products']['totalActiveSims'];
                                $totalSims += $operator['products']['totalSims'];
                                $totalBlocked += $operator['products']['totalBlockedSims'];
                                $totalStopped += $operator['products']['totalStoppedSims'];
                                $totalBalance += $operator['products']['totalBalance'];
                                $totalOpening += $operator['products']['totalOpening'];
                                $totalIncoming += $operator['products']['totalIncoming'];
                                $totalSale += $operator['products']['totalSale'];
                                $totalClosing += $operator['products']['totalClosing'];
                                $totalIncomingClo += $operator['products']['totalIncomingClo'];
                                $totalHomeSale += $operator['products']['totalHomeSale'];
                                $totalRoamingSale += $operator['products']['totalRoamingSale'];
                                $totalServerDiffnew += $operator['products']['totalServerDiffnew'];
                                ?>
                                <tr class="operators" data-operator-id="<?php echo $operator['products']['id']; ?>">
                                        <?php $icon = $this->params['url']['mode'] != "search" ? "glyphicon-plus" : "glyphicon-minus"; ?>
                                    <td  data-operator="<?php echo "operator_" . $operator_id; ?>" class="level1"><i class="glyphicon <?php echo $icon; ?>"></i><?php echo $operator['products']['name'] ?></td>
                                    <td><?php echo $operator['products']['totalActiveSims'] ?>/<?php echo $operator['products']['totalSims'] ?></td>
                                    <td>
            <?php if (isset($requests['modemview'][$modem['vendors']['id']][$operator['products']['id']])): ?>
                <?php echo $requests['modemview'][$modem['vendors']['id']][$operator['products']['id']]['successrequests'] ?> /     <?php echo $requests['modemview'][$modem['vendors']['id']][$operator['products']['id']]['totalrequests'] ?> 
            <?php else: echo "NA";
            endif; ?>
                                    </td>
                                    <td><?php echo $operator['products']['totalBlockedSims'] ?> / <?php echo $operator['products']['totalStoppedSims'] ?></td>
                                    <td><?php echo number_format($operator['products']['totalBalance'], 2); ?></td>
                                    <td><?php echo number_format($operator['products']['totalOpening'], 2); ?></td>
                                    <td><?php echo number_format($operator['products']['totalClosing'], 2); ?></td>
                                    <td><?php echo number_format($operator['products']['totalIncoming'], 2); ?></td>
                                    <td><?php echo number_format($operator['products']['totalRoamingSale'], 2); ?></td>
                                    <td><?php echo number_format($operator['products']['totalHomeSale'], 2); ?></td>
                                    <td><?php echo number_format($operator['products']['totalSale'], 2); ?></td>
                                    <td>
                                        <?php
                                        //Current Day

                                        if (!$previousDay):

                                            echo number_format(-($operator['products']['totalOpening'] - $operator['products']['totalBalance'] + $operator['products']['totalIncoming'] - $operator['products']['totalSale'] + $operator['products']['totalIncomingClo']), 2);
                                        else:
                                            //Previous Day       

                                            echo number_format(-($operator['products']['totalOpening'] - $operator['products']['totalClosing'] + $operator['products']['totalIncoming'] - $operator['products']['totalSale'] + $operator['products']['totalIncomingClo']), 2);
                                        endif;
                                        ?>
                                    </td>
                                    <td><?php echo number_format($operator['products']['totalServerDiffnew'], 2); ?></td>
                                    <td><?php echo $operator['products']['totalBlockedBalance'] ?></td>
                                </tr>


            <?php foreach ($operator['products']['suppliers'] as $key => $supplier): ?>




                                    <tr data-supplier-name="<?php echo $key; ?>" <?php if ($this->params['url']['mode'] != "search"): ?> style="display: none;" <?php endif; ?> class="suppliers <?php echo "supplier_" . $operator_id; ?>"  >

                                        <td> <div class="level2"><i class="glyphicon <?php echo $icon; ?>"></i><?php echo $key; ?></div></td>
                                        <td><div><?php echo $supplier['totalActiveSims'] ?>/<?php echo $supplier['totalSims'] ?></div></td>
                                        <td>NA</td>
                                        <td><div><?php echo $supplier['totalBlockedSims'] ?> / <?php echo $supplier['totalStoppedSims'] ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalBalance'], 2); ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalOpening'], 2); ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalClosing'], 2); ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalIncoming'], 2); ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalRoamingSale'], 2); ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalHomeSale'], 2); ?></div></td>
                                        <td><div><?php echo number_format($supplier['totalSale'], 2); ?></div></td>
                                        <td><div>
                                                <?php
                                                if (!$previousDay):
                                                    echo number_format(-($supplier['totalOpening'] - $supplier['totalBalance'] + $supplier['totalIncoming'] + $supplier['totalIncomingClo'] - $supplier['totalSale']), 2);
                                                else:
                                                    echo number_format(-($supplier['totalOpening'] - $supplier['totalClosing'] + $supplier['totalIncoming'] + $supplier['totalIncomingClo'] - $supplier['totalSale']), 2);
                                                endif;
                                                ?>
                                            </div>
                                        </td>
                                        <td><div><?php echo number_format($supplier['totalServerDiffnew'], 2); ?></div></td>
                                        <td><div><?php echo $supplier['totalBlockedBalance']; ?></div></td>

                                    </tr>

                                    <tr  <?php if ($this->params['url']['mode'] != "search"): ?> style="display: none;" <?php endif; ?> class="sims">
                                        <td colspan="14" >

                                            <div>
                                                <table  class="table table-condensed table-hover table-bordered sims sims_<?php echo $modem['vendors']['id']; ?>" >
                                                    <thead>
                                                    <th>S-D/M/P</th>
                                                    <th>Number</th>
                                                    <th>Opr</th>
                                                    <th>Vendor</th>
                                                    <th>Margin</th>
                                                    <th>Balance</th>
                                                    <th>Opening</th>
                                                    <th>Closing</th>
                                                    <th>Incoming</th>
                                                    <th>T.Sale</th>
                                                    <th>Limit</th>
                                                    <th>Sale</th>
                                                    <th>Inc</th>
                                                    <th>Diff</th>
                                                    <th>S.Diff</th>
                                                    <th>Succ %/Inprocess</th>
                                                    <th>PT/SBR</th>
                                                    <th>RT</th>
                                                    <th>L Succ</th>
                                                    <th>Last Txn</th>
                                                    <th>Flag</th>
                                                    <th>Status</th>
                                                    <th>Comment</th>
                                                    <th>Action</th>

                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($supplier['sims'] as $sim): $sim = (object) $sim; ?>
                                                            <?php
                                                            $key = $sim->inv_supplier_id . "_" . $sim->opr_id;
                                                            $commentkey = $sim->vendor_id . "_" . $sim->opr_id . "_" . $sim->scid . "_" . $sim->sync_date;

                                                            if (!$previousDay):
                                                                $diff = -($sim->opening - $sim->balance + $sim->tfr - $sim->sale + $sim->inc);
                                                            else:
                                                                $diff = -($sim->opening - $sim->closing + $sim->tfr + $sim->inc - $sim->sale);
                                                            endif;
                                                            ?>
                                                            <tr  data-sale-flag="1" style="background-color:<?php echo $this->Sims->setColor($sim); ?>"  data-mobile-no="<?php echo $sim->mobile; ?>"  data-supplier-tr="<?php echo "supplier_" . $operator_id; ?>" data-saleamt="<?php echo empty($sim->sale) ? 0 : $sim->sale; ?>" data-diff="<?php echo $diff; ?>" >
                                                                <td><?php echo $sim->signal; ?>-<?php echo $sim->id; ?>/<?php echo $sim->machine_id; ?>/<?php echo $sim->device_num; ?><?php if ($sim->active_flag): ?> <div style="background: #12EB50;width: 10px;height: 10px;border-radius: 50%;"></div> <?php endif; ?></td>
                                                                <td id="mobile_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>" data-oprid="<?php echo $sim->opr_id; ?>" data-mobile="<?php echo $sim->mobile; ?>"><?php echo $sim->mobile; ?></td>
                                                                <td><?php echo $sim->operator; ?></td>
                                                                <td><?php echo $sim->vendor_tag; ?>(<?php echo $sim->vendor; ?>)</td>
                                                                <td><?php echo $sim->commission; ?></td>
                                                                <td data-cur="<?php echo $sim->balance; ?>" data-vendorid="<?php echo $modem['vendors']['id']; ?>" data-simid="<?php echo $sim->id; ?>" id="cur_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>">
                                                                    <span><?php echo $sim->balance; ?></span>
                    <?php /*  if($this->Session->read('Auth.User.group_id')=="25"): ?> <a data-toggle="modal"   href="#editbalancemodal" data-simid="<?php echo $sim->id; ?>"  data-vendorid="<?php echo $modem['vendors']['id'];  ?>" data-deviceid="<?php echo $sim->id; ?>" data-parbal="<?php echo $sim->par_bal; ?>" data-balance="<?php  echo $sim->balance;  ?>" data-mobile="<?php  echo $sim->mobile;  ?>">Edit</a><?php endif; */ ?>
                                                                </td>
                                                                <td id="opening_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>" data-opening="<?php echo $sim->opening; ?>"><?php echo $sim->opening; ?></td>
                                                                <td id="closing_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>" data-closing="<?php echo $sim->closing; ?>">
                                                                    <span><?php echo $sim->closing; ?></span>
                    <?php if ($previousDay && !$isDistributer && ($this->Session->read('Auth.User.group_id') == "25" || $this->Session->read('Auth.User.group_id') == "10")): ?>
                                                                        <a data-toggle="modal"   href="#editclosingmodal" data-vendorid="<?php echo $modem['vendors']['id']; ?>" data-deviceid="<?php echo $sim->id; ?>" data-closing="<?php echo $sim->closing; ?>" data-mobile="<?php echo $sim->mobile; ?>">Edit</a>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                    <?php //echo $sim->tfr;  ?>
                                                                    <div  data-originalincoming="<?php echo $sim->tfr; ?>" id="original_val_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>"><?php echo $sim->tfr; ?></div>
                                                                    <?php if (!$isDistributer): ?>
                                                                        <span id="updated_val_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>">
                                                                            <a onclick="showUpdateHtml('<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>')">Edit</a>
                                                                        </span>
                                                                    <?php endif; ?> 
                                                                </td>
                                                                <td id="sale_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>"  data-sale="<?php echo $sim->sale; ?>"><?php echo $sim->sale; ?></td>
                                                                <td>
                    <?php echo $sim->limit; ?>/<?php echo $sim->roaming_limit; ?>
                                                                </td>
                                                                <td><?php echo ($sim->sale - $sim->roaming_today) > 0 ? ($sim->sale - $sim->roaming_today) : 0.00; ?> / <?php echo $sim->roaming_today; ?></td>
                                                                <td><?php echo $sim->inc; ?></td>
                                                                <td id="diff_<?php echo $sim->id . "_" . $modem['vendors']['id']; ?>" data-diff="<?php echo $diff; ?>"><?php echo round($diff, 2); ?></td>
                                                                <td><?php echo number_format($sim->server_diff, 2); ?></td>
                                                                <td><?php if ($sim->success > 0): echo $sim->success . "%";
                    endif; ?>/<?php echo $sim->inprocess; ?></td>
                                                                <td><?php echo $sim->process_time; ?>  secs<?php if (!empty($simbalrange[$key])): ?>/<?php echo $sim->bal_range; ?>/<?php echo $simbalrange[$key]; ?><?php endif; ?> </td>
                                                                <td><?php echo $recharge_method[$sim->recharge_method]; ?></td>
                    <?php $dateinlink = isset($this->params['url']['searchbydate']) ? $this->params['url']['searchbydate'] : date('Y-m-d'); ?>
                                                                <td><a href="/sims/lastModemSMSes/<?php echo $modem['vendors']['id']; ?>/<?php echo $sim->id; ?>/1/1500/<?php echo $dateinlink; ?>" target="_blank"><?php echo!empty($sim->last) ? $sim->last : "Last Sms"; ?></a></td>
                                                                        <td><?php if(!$isDistributer): ?><a href="/sims/lastModemTransactions/<?php echo $modem['vendors']['id'];  ?>/<?php echo $sim->id;  ?>/1/1500/<?php echo $dateinlink;?>"target="_blank">Last Txn</a><?php else: ?><a href="/sims/lastModemTransactions/<?php echo $modem['vendors']['id'];  ?>/<?php echo $sim->id;  ?>/1/1500/<?php echo $dateinlink;?>"target="_blank">Last Txn</a><?php endif;  ?></td>
                                                                <td <?php if ($sim->stop_flag): ?>style="background-color: red;"<?php endif; ?>><a  onclick="stopDevice('<?php echo $sim->id; ?>', '<?php echo!$sim->stop_flag ? 1 : 0; ?>', '<?php echo $modem['vendors']['id']; ?>', this);"><?php echo $sim->stop_flag ? "Start" : "Stop"; ?></a></td>
                                                                <td> <?php if (!$isDistributer): ?> <div id="status"><button class="btn btn-default btn-xs" id="status" onclick="checkSimStatus('<?php echo $sim->id; ?>', '<?php echo $modem['vendors']['id']; ?>', this);"/>Status</button></div>
                                                                        <div id="negdiff"><button class="btn btn-default btn-xs" id="negdiff" onclick="checkNegDiff('<?php echo $sim->id; ?>', '<?php echo $modem['vendors']['id']; ?>', this);">NegDiff</button></div><?php endif; ?></td> 
                                                                <td><a  data-oprid="<?php echo $sim->opr_id; ?>" data-vendorid="<?php echo $modem['vendors']['id']; ?>"  data-scid="<?php echo $sim->scid; ?>" data-oprid="<?php echo $sim->opr_id; ?>" data-commentdate="<?php echo $this->params['url']['searchbydate']; ?>" data-toggle="modal" href="#addcommentmodal">View(<?php echo!empty($commentcountarr[$commentkey]) ? $commentcountarr[$commentkey] : 0; ?>)</a></td>
                                                                <td> <?php // if(!$isDistributer):  ?><?php echo $this->element('simAction', array('sim' => $sim, 'vendor_id' => $modem['vendors']['id'])); ?> <?php // endif;  ?></td>
                                                            </tr>
                <?php endforeach; ?>
                                                    </tbody>

                                                </table>

                                            </div>

                                        </td>
                                    </tr>




            <?php endforeach; ?>


        <?php endforeach; ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th><?php echo $totalactiveSims; ?>/<?php echo $totalSims; ?></th>
                                <th>NA</th>
                                <th><?php echo $totalBlocked; ?>/<?php echo $totalStopped; ?></th>
                                <th><?php echo number_format($totalBalance, 2); ?></th>
                                <th><?php echo number_format($totalOpening, 2); ?></th>
                                <th><?php echo number_format($totalClosing, 2); ?></th>
                                <th><?php echo number_format($totalIncoming, 2); ?></th>
                                <th><?php echo number_format($totalRoamingSale, 2); ?></th>
                                <th><?php echo number_format($totalHomeSale, 2); ?></th>
                                <th><?php echo number_format($totalSale, 2); ?></th>
                                <th>
                                    <?php
                                    if (!$previousDay):
                                        $totalDiff = -($totalOpening - $totalBalance + $totalIncoming - $totalSale + $totalIncomingClo);
                                        echo number_format($totalDiff, 2);
                                    else:
                                        $totalDiff = -($totalOpening - $totalClosing + $totalIncoming + $totalIncomingClo - $totalSale);
                                        echo number_format($totalDiff, 2);
                                    endif;
                                    ?>
                                </th>
                                <th><?php echo number_format($totalServerDiffnew, 2); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>



                    </table>

                    <div class="row">
                        <div class="col-lg-6">
                            <ul style="list-style: none;padding: 0px">
                                <li><h3>Total Balance : <span data-balance="<?php echo $totalBalance; ?>" class="permodembalance"> <?php echo number_format($totalBalance, 2); ?></span>  [<?php echo number_format($totalIncoming + $totalDiff, 2); ?>] </h3></li>
                            </ul>
                        </div>
                    </div>
                    <hr/>

                </div>
            </div>


    <?php endforeach;
else: echo "<br /><br /> No records fetched";
endif; ?>

</div>
