
<html>
    <head>
        <title>Transaction History</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-4.1.0.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>

        <style>

            .btn.btn-primary.search {
                margin: 24px;
            }
            .type .btn-group{display: block;}
            .type .multiselect.dropdown-toggle.btn.btn-default {
                display: block;
                overflow: hidden;
                width: 220px;
                
            }
        </style>
    </head>
    <body>        
                    <div class="pull-right">              
                        <a href='#' class="btn btn-primary search btn-lg"  id='prodDownload' name ='prodDownload' ><span class="glyphicon glyphicon-download-alt"> Download </span> </a>
                    </div>
        <h2>Transaction History</h2><br><br>
        <form  name="productForm" id="productForm" method="POST">                        
            <div class="row">
                <div class="col-md-3">
                    <label for="frmdate" >From  </label><br>
                    <input type="text" id ="prodfrmdate" class="form-control" name ="prodfrmdate"  style="width:220px" value="<?php if (!empty($frmdate)) echo $frmdate; ?>">
                </div>
                <div class="col-md-3">
                    <label for="todate" >To  </label><br>
                    <input type="text" id ="prodtodate" class="form-control" name ="prodtodate" style="width:220px" value="<?php if (!empty($todate)) echo $todate; ?>"  >
                </div>

                <div class="col-md-3 type">
                    <label for="prodProducts" >Products</label><br>
                    <select  class="form-control" style='width:300px;float:left;' id="prodProducts" name="prodProducts[]"  multiple value="<?php echo $s; ?>"  >                            
                        <?php foreach ($serviceprod as $prod): ?>                 
                              <option value="<?php echo $prod['services']['id']; ?>"<?php if (in_array($prod['services']['id'], $prodtype)) echo "selected" ?>>
                             <?php echo $prod['services']['name']; ?></option>                                                     
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="prodRetid" >Ret Id </label><br>
                    <input type="text"  class="form-control" id ="prodRetid" name ="prodRetid" style="width:220px" value="<?php echo $retId; ?>">                      
                </div>
`               </div> 
                <div class="row">                    
                 <div class="col-md-3">
                    <label for="prodRetMob" > Retailer Mobile  </label><br>
                    <input type="text" class="form-control" id ="prodRetMob" name ="prodRetMob" style="width:220px" value="<?php echo $retMob; ?>">
                </div>                    
                    <div class="col-md-3">
                        <label for="prodUserid" >Ret User Id </label><br>
                        <input type="text"  class="form-control" id ="prodUserid" name ="prodUserid" style="width:220px" value="<?php echo $userId; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="prodShoptxnid" >Shop Txn Id </label><br>
                        <input type="text"  class="form-control" id ="prodShoptxnid" name ="prodShoptxnid" style="width:220px" value="<?php echo $shopTxnId; ?>">
                    </div>
                <div class="col-md-3">
                    <label for="prodVendortxnid" >Vendor Txn Id </label><br>
                    <input type="text"  class="form-control" id ="prodVendortxnid" name ="prodVendortxnid" style="width:220px" value="<?php echo $vendorTxnId; ?>">
                    <br>
                </div></div>
            <div class="row">
                <div class="col-md-3 type">
                    <label for="prodStatus" >Status </label><br>
                    <select  class="form-control" id="prodStatus" name="prodStatus"  value="<?php echo $s; ?>">                                                    
                        <?php foreach ($statusarr as $txnno => $txnval): ?>                                             
                            <option value="<?php echo $txnno ?>" <?php if ($txnno == $status_value) echo "selected" ?> >
                                <?php echo $txnval ?></option>                                                     
                        <?php endforeach; ?>
                    </select>
                </div>                               
            <div class="col-md-1">
                <?php
                $url1 = explode('/', $_SERVER['REQUEST_URI']);
                $url = explode('?', $url1[3]);
                $url[3] = !$url[0] ? 100 : $url[0];
                ?>
                <label for="ivr_no">Pages</label>                       
                <select class="form-control"  style='width:90px;' id="ivr_no" name="ivr_no" onchange="goToPage(1, this.value);">            
                    <option <?php
                    if ($url[3] == 100) {
                        echo "selected";
                    }
                    ?>>100</option>
                    <option <?php
                    if ($url[3] == 200) {
                        echo "selected";
                    }
                    ?>>200</option>
                    <option <?php
                    if ($url[3] == 500) {
                        echo "selected";
                    }
                    ?>>500</option>
                    <option <?php
                    if ($url[3] == 1000) {
                        echo "selected";
                    }
                    ?>>1000</option>
                    <option <?php
                    if ($url[3] == 2000) {
                        echo "selected";
                    }
                    ?>>2000</option>
                </select>
            </div>            
                <div class="col-md-3">
                    <input type="hidden" name="prod_fld" id ="prod_fld">                                   
                    <button style="margin-top : 22px;"type="submit" class="btn btn-primary btn-md" id ="prodSubmit" name ="prodSubmit" >Submit</button>
                </div>         
            </div>

            <?php if($days < 8) { ?>
            <div class="table-responsive">
                
                <table class=" table table-hover table-responsive table-bordered"<br><br>
                    <thead style="background: #00FF80;">
                    <th> Id </th>
                    <th> Shop Transaction Id  </th>                    
                    <th> Retailer Id </th>
                    <th> Product Activation Id</th>
                    <th> Vendor txn Id   </th>
                    <th> Description </th>
                    <th> Transacting Amount </th> 
                    <th> Comm/Charges </th> 
                    <th> Status </th> 
                    <th> Date Time</th> 

                    </thead>     
                    <tr>
                        <?php
                        $i = 1;
                        foreach ($allTxnArray as $trans) {
                            ?>
                            <td> <?php echo $i; ?></td>
                            <td> <?php echo $trans['shopid']; ?></td>
                            <td> <?php echo $trans['retid']; ?></td>
                            <!--<td> <?php //echo $trans['userid']; ?></td>-->                            
                            <td> <?php echo $trans['product_activation_id']; ?></td>                            
                            <td> <?php echo $trans['txn_id']; ?></td>                            
                            <td> <?php echo $trans['description']; ?></td>
                            <td> <?php echo $trans['amount']; ?></td>
                            <td> <?php echo $trans['cc']; ?></td>
                            <td> <?php echo ($status_value == 0)? 'Partail/Refunded':$statusarr[$trans['status']]; ?></td>
                            <td> <?php echo $trans['datetime']; ?></td>
                        </tr>
                        <?php $i++;
                    }
                    ?>
                </table>
            </div>
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5 text-right">
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div>
            <?php } else { ?>
            <br>
                        <div class="alert alert-danger">
                          <strong>Message</strong> Data Range Cannot be greater than 7 days
                        </div>                        
            <?php } ?>            
        </form>
    </body>
</html>
<script>
    function goToPage(page = 1, recs =<?php echo $url[3]; ?>) {
        $('#productForm').attr('action', '/products/allTransaction/' + recs + '?page=' + page);
        $('#productForm').submit();

    }
    $(document).ready(function () {
        $('#prodfrmdate, #prodtodate').datepicker({
            format: "yyyy-mm-dd",
            endDate: "1d",
            multidate: false,
            autoclose: true,
            todayHighlight: true
        });
//        $('.prodtable').dataTable({
//// "order": [[0, "desc" ]],
//            "pageLength": 50,
//            "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, 'All']],
//        });
        $('#prodProducts').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true
        });

    });

    $('#prodDownload').click(function (e) {
        e.preventDefault();
        $('#prod_fld').val('1');
        $('#prodSubmit').click();
        $('#prod_fld').val('');
    });


</script>
