<title>Transaction Panel</title>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">

    </div>
 <!-- for getting the rbl or eko name from url-->
 <?php $url1 = explode('/', $_SERVER['REQUEST_URI']);
       $banktype = $url1[3]; ?>
    <ul class="nav navbar-nav">
      <li><a href="/dmt/index/<?php echo $banktype; ?>">Search</a></li>
      <li><a href="/dmt/dmtFromto/<?php echo $banktype; ?>" >All Transactions</a></li>                    
    </ul>
  </div>
</nav>
<div class="wrapper trans-detail-main">
    <div class="container-fluid">
        <h1>Transaction Detail Screen</h1>
        <div class="row">
            <form id="transact_form" name="transact_form" method="POST">
            <div class="col-md-12 trans-details">
                <span class="amnt_trans_head">Transaction Details</span>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <th>Retailer Mobile/Shop Name</th>
                        <th>Wallet ID</th>
                        <th>Bank Trans ID</th>
                        <th>Order ID </th>
                        <th>Sender Mobile / Name</th>
                        <th>Beneficiary Name/Acc No.</th>
                        <th>Transaction Amount</th>                        
                        <th>Commission</th>                        
                        <th>Service Charge</th>              
                        <th>TDS</th>
                        <th>Opening</th>                        
                        <th>Closing</th>                        
                        <th>Status</th>
                        <th>Date Time</th>
                        <th>Refund Date/Time</th>
                        <th>Comment Tag</th>
                        <th> Comments </th>
                        <th> Action </th>
                        </thead>
                        <tbody>
                            <tr>                                                                
                                <?php $retNo = $txn_mob_id[$pay1txnData[0]['ret_id']]; $retName = $txn_shopname[$pay1txnData[0]['ret_id']]; $retId = $pay1txnData[0]['ret_id'] ?>
                               <td><?php echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/" . $retId ."'>" . $retNo . "</a> "." /". "\xA" ;    
                        echo "<a style='font-size:Normal;' target='_blank' href='/dmt/retailersReport/$banktype/0/". $retId ."'>" . $retName . "</a> "; ?></td>                    
                                <td><?php echo $pay1txnData[0]['wallet_id']; ?></td>
                                <td><?php echo $pay1txnData[0]['bank_id']; ?></td>
                                <td><?php echo $pay1txnData[0]['order_id']; ?></td>
                                    <?php $sendNo = $pay1txnData[0]['send_mob'];  $sendName=$pay1txnData[0]['send_name'];?>
          <td><?php echo "<a style='font-size:Normal;' target='_blank' href='/dmt/sendersReport/$banktype/" . $sendNo ."'>" . $pay1txnData[0]['send_mob'] . "</a> "." /". "\xA" ;
                    echo "<a style='font-size:Normal;' target='_blank' href='/dmt/sendersReport/$banktype/". $sendNo . "'>" . $pay1txnData[0]['send_name'] . "</a> "; ?></td>
                                <td><?php echo $pay1txnData[0]['bene_name']."/"."\Xa";
                                        echo $pay1txnData[0]['bene_accno']?></td>
                                <td><?php echo $pay1txnData[0]['amount']; ?></td>  
                                <td><?php echo $pay1txnData[0]['commission']; ?></td>
                                <td><?php echo $pay1txnData[0]['pay1_charge']; ?></td>
                                 <td><?php echo $pay1txnData[0]['tds']; ?></td>
                                <td><?php echo $pay1txnData[0]['opening']; ?></td>
                                <td><?php echo $pay1txnData[0]['closing']; ?></td>                                
                                <?php $transStatus = Configure::read('Remit_pay1_status'); ?>
                                <td><?php echo $transStatus[$pay1txnData[0]['status']]; ?></td>
                                <td><?php echo $pay1txnData[0]['created_at']; ?></td>
                                <td><?php echo $refund_det; ?></td>
                                <td>
                                    <div class="col-md-6">
                                        <select id="comm_tag" name="comm_tag" class="form-control" style="width:180px">
                                        <option value="">select</option>
                                        <?php foreach($dmttags as $dmtTag){ ?>                                         
                                        <option value="<?php echo $dmtTag['taggings_new']['id']?>"><?php echo $dmtTag['taggings_new']['name']?></option>
                                       <?php } ?>
                                    </select>
                                    </div>
                                </td>
                                <td><div class="col-md-6">
                                    <textarea id="comm" name="comm" class="form-control" style="width:180px"> </textarea>
                                    </div>
                                </td>
                                <td><button class="btn btn-primary"type="submit" id="transBtn" name="transBtn">Submit</button></td>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $pay1txnData[0]['user_id']; ?>">
                        <input type="hidden" id="order_id" name="order_id" value="<?php echo $pay1txnData[0]['order_id']; ?>">
                        
                            </tr>                                
                        </tbody>
                    </table>
                </div>
                <?php if(!empty($dmt_comments)){ ?>
                <h3>Comment Section</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <th> id </th>
                        <th> Order id </th>
                        <th> Tag </th>
                        <th> Comment</th>
                        <th> Commented By </th>   
                        <th> Created on </th>
                        </thead>
                        <tbody>
                                <?php 
                                $i = 1;
                                foreach($dmt_comments as $dmt) { ?>
                                <tr> 
                                <td><?php echo $i; ?> </td>
                                <td> <?php echo $dmt['comments_new']['ref_id'] ?></td>
                                <td> <?php echo $tagname[$dmt['comments_new']['subtag_id']]; ?></td>
                                <td> <?php echo $dmt['comments_new']['comment']; ?></td>
                                <td> <?php echo $users[$dmt['comments_new']['cc_id']]; ?></td>
                                <td> <?php echo $dmt['comments_new']['created_at']; ?></td>
                                </tr>
                                <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <?php if($banktype == "ekonew" ) { ?>
                        <th>Vendor</th>                
                        <?php }?>
                        <?php if($banktype != "ekonew" ) { ?>
                        <th>EKO Trans ID</th>                
                        <?php } else {?>
                        <th>Vendor Trans ID</th>                
                        <?php } ?>
                        <th>Bank Trans ID</th>
                        <th>Order Id</th>
                        <th>Internal Response</th>                        
                        <th>Vendor Response EKO </th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Mode</th>                       
                        <th>Status</th>
                        <th>Time Stamp</th>
                        <th>Processing Time</th>
                        </thead>
                        <tbody>
                            
                            
                </td>

                 <?php foreach($pay1txnData as $txnData) {                                   
                ?>                  
                            <tr>
                                <?php if($banktype == "ekonew" ) { ?>
                                <td><?php echo $txnData['vendor']; ?> </td>
                                <?php }?>
                                <td><?php echo $txnData['eko_txn_id']; ?> </td>
                                <td><?php echo $txnData['bank_id']; ?></td>                                  
                                <td><?php echo $txnData['order_id']; ?></td>
                                <td><?php echo $txnData['remarks']; ?></td>
                                <?php $banktxn_status = Configure::read('Remit_bank_status.eko');?>                                
                                <td><?php echo $txnData['vendor'] .":". $banktxn_status[$txnData['bank_status']];?></td>
                                <td><?php echo $txnData['amount']; ?></td>
                                <td><?php echo $txnData['reason'];?></td>
                                <td><?php echo ($txnData['trans_type'] == 1) ? 'NEFT' : 'IMPS'; ?></td>                                 
                                <?php $pay1_status = Configure::read('Remit_pay1_status'); ?>
                                <td><?php echo $pay1_status[$txnData['status']]; ?></td>
                                <td><?php echo $txnData['created_at']; ?></td>
                                <td><?php echo $txnData['updated_at']; ?></td>
                            </tr>
                            
                           <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </form>     
        </div>
    </div>
</div>
