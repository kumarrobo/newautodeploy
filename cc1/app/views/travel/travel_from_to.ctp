
<html>
    <head>
        <title>From To Report</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link   rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <style>
            .btn.btn-primary.search {
                margin: 24px;
            }
            .type .btn-group{display: block;}
            .type .multiselect.dropdown-toggle.btn.btn-deault {
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
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li><a href = "/travel/index" >Search</a></li>
                    <li class="active"><a href="/travel/travelFromTo" >All Transactions</a></li>                   
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
    </head>
    <form name="index" id = "index" method="POST">
        <div>
            <input type="hidden" class="form-control" id="travel_from"   value ="<?php echo isset($_POST['travel_from']) ? $_POST['travel_from'] : ''; ?>" />
            <input type="hidden" class="form-control" id="travel_till"   value="<?php echo isset($_POST['travel_till']) ? $_POST['travel_till'] : ''; ?>" /> 
        </div>
    </form>     
    <?php
    if ($this->params['url']['page'] == '') {
        $this->params['url']['page'] = 1;
    }
    ?>
<body>


    <form name="reportform" id = "reportform" method="POST" >
        <div class="wrapper">
            <div class="container">
                <h1>Reports</h1>
                <div class="row">
                    <div class="col-md-2">
                        <label for="from_date">From Date</label>
                        <input type="text" class="form-control" style='width:180px;float:left;'  id="travel_from" name="travel_from" value="<?php if (!empty($frm)) echo $frm; ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="till_date">To Date</label>
                        <input type="text" class="form-control" style='width:180px;float:left;'  id="travel_till" name="travel_till" value="<?php if (!is_null($to)) echo $to; ?>">
                    </div>
                    
                    <div class="col-md-2 type">                                                                                    
                        <label for="transtatus">Status Type</label>                                                        
                        <select  class="form-control" style='width:300px;float:left;' id="transtatus" name="transtatus[]"  multiple="multiple"   value="<?php echo $s; ?>">                                                                            
                            <?php $travel_txn_status = Configure::read('Travel_pay1_status'); ?>
                            <?php foreach ($travel_txn_status as $txnno => $txnval): ?>                 
                                <option value="<?php echo $txnno ?>" <?php if (in_array($txnno,$transactatus)) echo "selected" ?> >
                                    <?php echo $txnval ?></option>                                                     
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="travel_pnr">PNR no.</label> 
                        <input type="text" class="form-control" style='width:170px;float:left;'  id="travel_pnr" name="travel_pnr" value="<?php if (!is_null($pnr)) echo $pnr; ?>">                        
                    </div>       
                    <?php $trantypearr = array('-1' => 'All', 'App' => 'App', 'Web' => 'Web'); ?>
                    <div class="col-md-1">
                        <label for="transtype"> Type </label>
                        <select class="form-control" style='width:90px;' id ="trantype" name="trantype">                            
                            <?php foreach ($trantypearr as $typeval => $typename): ?>
                                <option value ="<?php echo $typeval; ?>" <?php if ($typeval == $txntype) echo "selected" ?>><?php echo $typename; ?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>                      
                    <div class="col-md-1">
                        <?php
                        $url1 = explode('/', $_SERVER['REQUEST_URI']);
                        $url = explode('?', $url1[3]);
                        $url[4] = !$url[0] ? 100 : $url[0];
                        ?>
                        <label for="ivr_no">Pages</label>                       
                        <select class="form-control"  style='width:90px;' id="ivr_no" name="ivr_no" onchange="javascript:goToPage(1, this.value);">            
                            <option <?php
                            if ($url[4] == 10) {
                                echo "selected";
                            }
                            ?>>10</option>
                            <option <?php
                            if ($url[4] == 20) {
                                echo "selected";
                            }
                            ?>>20</option>
                            <option <?php
                            if ($url[4] == 30) {
                                echo "selected";
                            }
                            ?>>30</option>
                            <option <?php
                            if ($url[4] == 50) {
                                echo "selected";
                            }
                            ?>>50</option>
                            <option <?php
                            if ($url[4] == 100) {
                                echo "selected";
                            }
                            ?>>100</option>
                            <option <?php
                            if ($url[4] == 1000) {
                                echo "selected";
                            }
                            ?>>1000</option>
                        </select>
                    </div>
                    <div class="col-md-1">     
                        <button  class="btn btn-primary search"  id = "filerbtn" name = "filterbtn" >Search</button>  
                    </div>   
                    <div class="pull-right">              
                        <input type="hidden" name="fer_fld" id ="fer_fld">                                   
                        <a href='#' class="btn btn-primary search"  id='rtbtn' name ='rtbtn' >Download Excel</a>
                    </div>
                </div>
            </div>
            <div class="retailer-details">
                <div class="col-md-12">
                    <!--For getting No of Records -->                                                
                    <?php
                    foreach ($report_data as $srno) {
                        $totRec[] = $srno['id'];
                    }
                    ?>
                    <?php $Rec[] = count($totRec); ?>
                    <div class="container-fluid"> 
                        <?php if($days <= 10) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" >
                                <div class="pull-left"><b>No of Records : <span class="pull-right"> <?php echo implode(" ", $Rec); ?> </b></span></div><br></br>
                                <div class="pull-center">
                                    <label for="saleamount">  Sale Amount : </label>                                    
                                    <label for="sale_amount"><?php echo floor($totAmount); ?></label>
                                </div>
                                <div class="pull-right">
                                    <label for="saleamount">  Agency Balance : </label>                                    
                                    <label for="sale_amount"><?php echo floor($agency_bal); ?></label>
                                </div>

                                <thead>
                                <th>Date </th>
                                <th>Vendor Txn Id</th>                                    
                                <th>PNR no</th>
                                <th>Booking/Refund</th>
                                <th>Pass Count</th>
                                <th>Mark up</th>
                                <th>Mode</th>
                                <th>Comm</th>
                                <th>Charges</th>
                                <th>TDS</th>
                                <th>GST</th>
                                <th>Refund Processed Date & Time</th>  
                                <th>Status</th>    
                                <th>Ticket</th>                                
                                </thead>
                                <tbody>

                                    <?php foreach ($report_data as $data) { ?>
                                        <tr>                                            
                                            <td><?php echo $data['tdate']; ?></td> 
                                            <td><?php echo $data['vendor_txn_id']; ?></td>                                                                                                     
                                            <td><?php echo $data['pnr']; ?></td>
                                            <td><?php echo floor($data['amount']); ?></td>
                                            <td><?php echo ($data['status'] == '6')?$data['cancel']:$data['pass']; ?></td>
                                            <td><?php echo $data['mark_up']; ?></td>                                            
                                            <td><?php echo $data['source']; ?></td>
                                            <td><?php echo isset($data['comm'])?$data['comm']:0; ?></td>
                                            <td><?php echo isset($data['charges'])?$data['charges']:0; ?></td>
                                            <td><?php echo isset($data['tds'])?$data['tds']:0; ?></td>
                                            <td><?php echo isset($data['gst'])?$data['gst']:0; ?></td>                                            
                                            <td><?php if(($data['status'] == '5') || ($data['status'] == '6')) { echo $data['update']; } else { echo 'NA';} ?></td>
                                            <?php $pay1txn_status = Configure::read('Travel_pay1_status'); ?>
                                            <td><?php echo $pay1txn_status[$data['status']]; ?></td>       
                                            <?php if(!isset($data['pnr'])) { ?>
                                            <td><button type="button" class="btn btn-primary" onclick="ticketView('<?php echo $data['pnr'] ?>','<?php echo $data['booking_id'] ?>')" disabled="true">Ticket</button></td>
                                            <?php } else {  ?>
                                            <td><button type="button" class="btn btn-primary" onclick="ticketView('<?php echo $data['pnr'] ?>','<?php echo $data['booking_id'] ?>')">Ticket</button></td>
                                            <?php } ?>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <!--For gett`ing Sum of Amt-->
                                    <?php
                                    foreach ($report_data as $repo) {
                                        $totAmt[] = $repo['amount'];
                                    }
                                    ?>
                                    <?php $amount[] = array_sum($totAmt); ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td> <div class="pull-right"><b>Total  :  </b></span></div> </td>
                                        <td><?php echo implode(" ", $amount); ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                        
                </div>
            </div>    
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5 text-right">
                    <?php echo $this->element('pagination'); ?>
                </div>
            <?php } else { ?>            
                        <br>
                        <div class="alert alert-danger">
                          <strong>Message</strong> Data Range Cannot be greater than 10 days
                        </div>                        
            
            <?php } ?>
            </div>
        </div>
    </form>
</body>
</html>
            <script>
                $('#rtbtn').click(function (e) {
                    e.preventDefault();
                    $('#fer_fld').val('1');
                    $('#filerbtn').click();
                    $('#fer_fld').val('');
                });
                function goToPage(page = 1, recs =<?php echo $url[4]; ?>) {
                    $('#reportform').attr('action', '/travel/travelFromTo/' + recs + '?page=' + page);
                    $('#reportform').submit();
                }
            </script>
    <script>


        // When the document is ready
        $(document).ready(function () {
            $('#travel_from, #travel_till').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
                endDate: "1d",
                multidate: false,
                autoclose: true,
                todayHighlight: true
            });
        });        
    
      function ticketView(pnr,book_id){        
              $.ajax({
                type: "POST",
                url: '/travel/travelTicket',
                dataType: "json",
                data: {pnr: pnr, booking_id: book_id},
                success: function (data) {
                       window.open(data,'_newtab');
                },
                error: function (err) {
                    console.log(err);

                }
            });
        }
    
    </script>     
