<html>
    <head>
        <title>Insurance Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="/boot/js/insurance.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-4.1.0.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

        
        <style>
        .table th{
            align-self: center;
            background: #93E5DD;
        }
        .btn.btn-primary.search {
            margin: 24px;
        }
        .type .btn-group{display: block;}
        .type .multiselect.dropdown-toggle.btn.btn-default {
            display: block;
            overflow: hidden;
            width: 100%;
        }
    </style>
    </head>
    <body>        
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">                
                    <ul class="nav navbar-nav">
                        <li class="<?php
                        if ($type == "0") {
                            echo "active";
                        }
                        ?>" ><a href = "/insurance/index/0/" >Insurance</a></li>
                        <li class="<?php
                        if ($type == "1") {
                            echo "active";
                        }
                        ?>"><a href = "/insurance/mutualfundPanel/1/">Mutual fund</a></li>
                    </ul>
                </div>
            </div>
        </nav> <br><br>
        <h2>Insurance Report </h2><br><br>
        <form id="insuranceForm" name="insuranceForm" method="POST">            
               <div class="row">
                   <div class="col-md-3">
                       <label for="frmdate" >From  </label><br>
                       <input type="text" id ="insfrmdate" name ="insfrmdate"  style="width:220px" value="<?php echo $frmdate; ?>">
               </div>
                   <div class="col-md-3">
                       <label for="todate" >To  </label><br>
                       <input type="text" id ="instodate" name ="instodate" style="width:220px" value="<?php echo $todate; ?>">
                   </div>
                   <div class="col-md-3">
                       <label for="custMob" > Customer Mobile  </label><br>
                       <input type="text" id ="custMob" name ="custMob" style="width:220px" value="<?php echo $custmobile; ?>">
                   </div>
                   <div class="col-md-3">
                       <label for="retId" >Retailer User Id  </label><br>
                       <input type="text" id ="retId" name ="retId" style="width:220px" value="<?php echo $retId; ?>">
                   </div>                   
                   <br><br><br>
                <div class="col-md-3">
                    <label for="todate" >Policy No </label><br>
                    <input type="text" id ="instoPolicyno" name ="instoPolicyno" style="width:220px" value="<?php echo $policyId; ?>">
                </div>
                   <div class="col-md-3"> 
                    <label for="instoProdId" >Product Id  </label><br>                    
                    <select  class="form-control" id="instoProdId" name="instoProdId"  value="<?php echo $s; ?>">                            
                        <option value="">Select</option>
                        <?php foreach ($productdet as $prod): ?>                 
                            <option value="<?php echo $prod['products']['product_id']; ?>"<?php if ($prod['products']['product_id'] == $productId) echo "selected" ?>>
                                <?php echo $prod['products']['label']; ?></option>                                                     
                        <?php endforeach; ?>
                    </select>
                </div>
                   <div class="col-md-3 type">                                                                                    
                       <label for="transval">Status Type</label>                                                        
                       <select  class="form-control" style='width:300px;float:left;' id="transval" name="transval[]"  multiple="multiple"   value="<?php echo $s; ?>">                                                               
                           <?php foreach ($status_val as $txnno => $txnval): ?>                 
                               <option value="<?php echo $txnno ?>" <?php if (in_array($txnno, $statusval)) echo "selected" ?> >
                                   <?php echo $txnval ?></option>                                                     
                           <?php endforeach; ?>
                       </select>
                   </div>                      
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;float: right;" id="inssubmit" name="inssubmit">Submit</button>
               </div>            
            
            <?php if (isset($retInsuranceData)) { ?>
                <div class="row">
                    <div class="col-md-1">
                        <label for="earing" >Earning  </label><br>
                        <label><?php echo $retInsuranceData[0][0]['earnings'] ?></label>

                    </div>
                    <div class="col-md-2">
                        <label for="policysold" >Policy Sold  </label><br>                       
                        <label><?php echo $retInsuranceData[0][0]['policies_sold'] ?></label>
                    </div> </div> <?php } ?>
            
            
               <div class="row">                   
                       <div class="col-md-5">                       
                       <table id="Product_Det" class="table table-hover table-bordered"><br><br>
                       <thead style="background: #93E5DD;">
                           <th>Product id</th>
                           <th>Name</th>
                           <th>Label</th>
                           <th>Count</th>
                           <th>Amount</th>
                           <th>Total</th>
                           </thead>                           
                           <tr>
                               <?php foreach($productDetail as $pd) { ?>
                               <td><?php echo $pd['b']['product_id']; ?></td>
                               <td><?php echo $pd['b']['name']; ?></td>
                               <td><?php echo $pd['b']['label']; ?></td>
                               <td><?php echo $pd['0']['cnt']; ?></td>
                               <td><?php echo $pd['0']['amount']; ?></td>
                               <td><?php echo $pd['0']['total']; ?></td>
                           </tr>
                               <?php } ?>
                       </table>
                       </div>
                       <div class="col-md-2 pull-right">                       
                       <table id="total_det" class="table table-hover table-bordered"><br><br>
                       <thead style="background: #93E5DD;">
                           <th>Total Amount</th>
                       </thead>
                       <tr>
                           <td><?php echo $totalDetail[0][0]['total']; ?></td>
                       </tr>    
                       </table>
                       </div>                   
               </div>

               <div class="table-responsive">
                   <table class="tablex table-hover"><br><br>
                       <thead style="background: #93E5DD;">
                       <th> Id </th>
                       <th> Transaction Id </th>
                       <th> Customer Name </th>
                       <th> Customer Mobile </th>
                       <th> Policy No     </th>
                       <th> Product Name </th>
                       <th> Retailer Name </th> 
                       <th> Retailer Mobile </th> 
                       <th> Start Date</th> 
                       <th> End Date </th> 
                       <th> Tenure </th>    
                       <th> Status  </th>     
                       </thead>     
                       <?php $i= 1;?>
                       <?php foreach($InsuranceDetails as $insDetails){?>
                       <tr>
                           <td><?php echo $i; ?> </td>
                           <td><?php echo $insDetails['t']['wallet_transaction_id']; ?> </td>
                           <td><?php echo $insDetails['c']['name']; ?> </td>
                           <td><?php echo $insDetails['c']['mobile']; ?> </td>
                           <td><?php echo $insDetails['cp']['policy_id']; ?> </td>                                                      
                           <td><?php echo $insDetails['p']['label']; ?> </td>                           
                           <td> <?php echo $retailer_name[$insDetails['t']['user_id']]['imp']['name']; ?></td>
                           <td> <a href="/panels/retInfo/<?php echo ($retailer_name[$insDetails['t']['user_id']]['ret']['mobile']) ? $retailer_name[$insDetails['t']['user_id']]['ret']['mobile'] : $retailer_name[$insDetails['t']['user_id']]['dist']['mobile'];?>" target="_blank"><?php echo ($retailer_name[$insDetails['t']['user_id']]['ret']['mobile']) ? $retailer_name[$insDetails['t']['user_id']]['ret']['mobile'] : $retailer_name[$insDetails['t']['user_id']]['dist']['mobile'];?></a></td>
                           <td><?php echo $insDetails['cp']['start_date']; ?> </td>
                           <td><?php echo $insDetails['cp']['end_date']; ?> </td>
                           <td><?php echo $insDetails['p']['tenure']; ?> </td>
                           <td><?php echo $status_val[$insDetails['cp']['status']]; ?> </td>
                       </tr>
                       <?php $i++ ;} ?>
                   </table>               
               </div>                 
        </form>       
    </body>
</html>
<script>

</script>