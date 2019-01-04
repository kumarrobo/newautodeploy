<html>
    <head>
        <title>From To Report</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link   rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script type="text/javascript" src="/boot/js/dmt.js"></script>
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <style>
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
        <script>
            $(document).ready(function () {
                $('#transtatus').multiselect({
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeSelectAllOption: true
                });
            });
                        
        </script>
    </head>
    <style>   
        tfoot tr td{border:0px !important;}
    </style>
    <body>        
        <?php $banktype = 'ekonew'; ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li><a href = "/dmt/index/<?php echo $banktype; ?>" >Search</a></li>
                    <li ><a href="/dmt/dmtFromto/<?php echo $banktype; ?>" >All Transactions</a></li>
                    <li><a  href="/dmt/dmtAdminPanel" >Notification Panel</a></li>                    
                </ul>
            </div>
        </nav>
        <style>
            tfoot {border:none !important;}
        </style>
        <?php
        $fldno = 0;
        $fldname = 0;
        ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li class="<?php if($banktype == "ekonew") {
                        echo "active";
                        }
        ?>"><a href = "/dmt/accvalidationreport/ekonew/<?php echo date('Y-m-d'); ?>" >New Eko</a></li>
                </ul>
            </div>
        </nav>
        <div style="margin-left:20px">
            <form id="accValidation" name="accValidation" method="POST">
            <div class="row">
            <div class="col-md-2">
                <label for="from_date">From Date</label>
                <input type="text" class="form-control" style='width:180px;float:left;'  id="dmt_from" name="dmt_from" value="<?php if (!empty($frm)) echo $frm; ?>">
            </div>
            <div class="col-md-2">
                <label for="till_date">Till Date</label>
                <input type="text" class="form-control" style='width:180px;float:left;'  id="dmt_till" name="dmt_till" value="<?php if (!is_null($tos)) echo $tos; ?>">
            </div>                
            <div class="col-md-1">     
                <button  class="btn btn-primary search"  id = "filerbtn" name = "filterbtn" >Search</button>  
            </div>   
            <input type="hidden" name="fer_fld" id ="fer_fld">                    
            <a href='#' class="btn btn-primary search"  id='acct_down' name ='acct_down' >Download Excel</a>
            </div>
        <div>Validation Report for date : <?php echo isset($this->params['pass'][1]) ? $this->params['pass'][1] : date('Y-m-d'); ?><br/></div>
       
        <table border="1" style="font-size: 11px" class="table table-bordered table-responseive">
            <thead>
                <tr>
                    <th># <?php echo $results_count; ?></th>
                    <th>Retailer No</th>
                    <th>Acc</th>
                    <th>Bank</th>
                    <th>Benename</th>
                    <?php if ($banktype == "ekonew") { ?>
                    <th>Vendor</th>                
                    <?php } ?>                    
                    <th>Status</th>
                    <th>tid</th>
                    <th>wallet debited</th>
                    <th>Amount debited from retailers</th>                        
                    <th>Amount debited from pay1</th>
                    <th>Created at</th>
                    <th>Response</th>
                </tr>
            </thead>
            <tbody> 
                <?php if ($results_count > 0): ?>
                <?php $i = 1;
                        foreach ($results as $row): ?>
                        <tr>
                <?php $arr = json_decode($row['eko_response'], true); ?>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['accno']; ?></td>
                            <td><?php echo $row['bank_name']; ?></td>
                            <td><?php echo $row['bene_name']; ?></td>
                            <?php if($banktype == "ekonew" ) { ?>
                            <td><?php echo $row['vendor']; ?></td>
                            <?php }?>
                            <td><?php echo $row['eko_status']; ?></td>
                            <td><?php echo isset($arr['data']['tid']) ? $arr['data']['tid'] : $arr['data']['txnId']; ?></td>
                            <td><?php echo $row['debited'] == '1' ? 'yes' : 'no'; ?></td>   
                            <td width="250px"><?php 
                                foreach($row['ret_margin'] as $key => $value){
                                    echo $key.' : <b>'. $value['margin'].'</b>'.'</br>';                               
                                  }
                            ?></td>   
                        <?php
                        $temp = json_decode($row['eko_response'], true);
                        ?> <?php if($row['vendor'] == 'bankit'  ){?>
                            
                            <td><?php  if($row['bankit_fee'] == '00') { echo '2.00'; } else { echo "NA"; }?></td>
                            <?php } else { ?>
                            <td><?php echo isset($temp['data']['fee']) ? $temp['data']['fee'] : "NA"; ?></td>
                         <?php   } ?>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td><?php echo $row['eko_response']; ?></td>
                        </tr>
        <?php $i++;
    endforeach; ?>
<?php else: echo "No Records";
endif; ?>
            </tbody>
        </table>        
        </form>
        </div>    
    </body>
</html>
        
<script>
                $('#acct_down').click(function (e) {
                    e.preventDefault();
                    $('#fer_fld').val('1');
                    $('#filerbtn').click();  
                    $('#fer_fld').val('');
                });        
    </script>