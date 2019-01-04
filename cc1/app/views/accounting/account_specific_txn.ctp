<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    
    .fix-width {
        width: 300px;
    }
    
    thead{
       background-color: #428bca;
       color: #fff;
    }
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <li><button class="tablinks" onclick="window.location='/accounting/txnUpload'">File Upload</button></li>
        <li><button class="tablinks active" onclick="window.location='/accounting/autoUpload'">Txn Entry</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/bankTxnListing'">Txn Listing</button></li>
        <li><button class="tablinks dropdown-toggle" onclick="window.location='/accounting/closingBalanceReport'">Closing Balance Report</button></li>
        <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
        <li><button class="tablinks" onclick="window.location='/accounting/bankStatements'">Bank Statements</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/ledger'">Ledger</button></li>
        <li><button class="tablinks" onclick="window.location='/accounting/debitSystem'">Debit System</button></li>
    </ul>

</div>
<br>
<div>
    <form name="main_form" method="post" action="/accounting/clearNonclearedTxn">
        <div style="color: red; text-align: center;"><?php echo $this->Session->flash(); ?></div>
        <?php foreach ($txn_details as $td) { ?>
        <div style="margin: 20px 0px 30px 0px;">
            <div style='float: left; width: 125px; font-weight: bold;'>Transaction ID : </div><div style="float:left;"><?php echo $td['atd']['pay1_txn_id']; ?></div><div style='float: left; width: 55px; margin-left: 25px; font-weight: bold;'>Bank : </div><div style="float:left;"><?php echo $td['bd']['bank_name']; ?></div><div style='float: left; width: 100px; margin-left: 25px; font-weight: bold;'>Description : </div><div style="float:left;"><?php echo substr($td['atd']['description'], 0, 25); ?></div><div style='float: left; width: 70px; margin-left: 25px; font-weight: bold;'>Amount : </div><div style="float:left;"><?php echo floor($td['atd']['amount']); ?></div><div style='float: left; width: 50px; margin-left: 25px; font-weight: bold;'>Date : </div><div style="float:left;"><?php echo $td['atd']['txn_date']; ?></div>
            <div style='clear: both;'></div>
        </div>
        <?php $txns[] = $td['atd']['pay1_txn_id']; ?>
        <?php $amount += floor($td['atd']['amount']); ?>
        <?php } ?>
        <input type="hidden" name='txn_id' value='<?php echo implode(',',$txns); ?>'>
        <div>
            <div style="float:left; margin-top: 5px; width: 125px;">Category : </div><div style="float:left;"><select id="category" name='category' class="form-control fix-width" onchange="categoryChange(this.value);">
                <option value="" disabled selected>Select Below</option>
                <?php foreach ($data['categories'] as $category) { ?>
                    <option value="<?php echo $data['view'][$category['id']] ? $category['id'] : $category['category']; ?>"><?php echo $category['category']; ?></option>
                <?php } ?>
                </select></div>
            <div style='clear: both;'></div>
        </div>
        <div id="box" style="float:left; width: 55%; "></div>
        <div id="listing" style="float:left; overflow-y:auto; height: 270px; width: 465px;">
        <?php if ($limits) { ?>
            <table class='table table-bordered table-hover' style='width: 450px;'>
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody style='color: #4e4e4e;'>
                    <?php foreach ($limits as $limit) {  $data1=json_decode($limit['bank_details'],true);?>
                    <tr id='limit_<?php echo $limit['id']; ?>'>
                        <td><input type="checkbox" class="close" id="close_<?php echo $limit['id']; ?>" onchange="limitMsg(<?php echo $limit['id'] ?>,'<?php echo strtolower($limit['dist_type']) ?>',<?php echo $limit['dist_id'] ?>,'<?php echo $limit['dist_id'] ." : ". $limit['dist_name'] ." - ".$limit['mobile']; ?>')"></td>
                        <td><?php echo $limit['id']; ?></td>
                        <td><?php echo $limit['dist_type']; ?></td>
                        <td><?php echo $limit['dist_name'] ." - ".$limit['mobile']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($limit['created_on'])); ?></td>
                        <td><?php if(($data1['bank_slip'])!=null) { ?> <center><a href="<?php echo $data1['bank_slip']; ?> "target="_blank">View</a></center><?php } ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        </div>
        <div style='clear: both;'></div><br/>
        <!--<input type='hidden' id='password' name='password'>-->
        <div style='margin-left: 160px;'><button class="btn btn-primary" onclick="onSubmit();">Submit</button></div>
    </form>
</div>

<?php

    if (strpos($txn_details[0]['atd']['description'], 'NEFT') !== false || strpos($txn_details[0]['atd']['description'], 'IMPS') !== false || strpos($txn_details[0]['atd']['description'], 'MMT') !== false || strpos($txn_details[0]['atd']['description'], 'UPI') !== false || strpos($txn_details[0]['atd']['description'], 'RTGS') !== false) {
        $type = '2';
    } else if (strpos($txn_details[0]['atd']['description'], 'TRANSFER') !== false || strpos($txn_details[0]['atd']['description'], 'TPT') !== false) {
        $type = '3';
    } else if (strpos($txn_details[0]['atd']['description'], 'CHEQUE DEPOSIT') !== false) {
        $type = '4';
    } else {
        $type = '1';
    }

?>

<script>
    
    window.onload = function () {
        <?php if ($txn_details[0]['atd']['txn_status'] == 'Cr') { ?>
            $('#category').val('1');
            categoryChange('1');
            $('#type').val('distributor');
            $('#type').change();
            <?php if (count($txn_details) == 1) { ?>
            editable('<?php echo $type ?>');
            <?php } if ($auto_details) { ?>
                $('#type').val('<?php echo $auto_details['role']; ?>');
                $('#type').change();
                $('#type_id').val('<?php echo $auto_details['details'][0]['u']['id']; ?>');
                $('#type_data').val('<?php echo $auto_details['details'][0]['u']['id'].' : '.trim(str_replace("'","",$auto_details['details'][0]['u']['name'])).' - '.$auto_details['details'][0]['u']['mobile']; ?>');
                showRequest();
            <?php } ?>
            <?php if ($lead_sel) { ?>
                $('#type').val('<?php echo $lead_sel['type']; ?>');
                $('#type').change();
                $('#type_id').val('<?php echo $lead_sel['type_id']; ?>');
            <?php } ?>
        <?php } ?>
    };
    
    function categoryChange (val) {
        var data = JSON.parse('<?php echo addslashes(json_encode($data)); ?>');
        var html = "<br/>";

        if (data.view[$("#category").val()] != undefined) {
            var fields = data.view[$("#category").val()].fields;
            for (var x in fields) {
                if (fields[x].label != undefined) {
                    var style = '';
                    if(fields[x].label == 'Narration') {
                        var style = 'margin-top: 25px;';
                    } else if(fields[x].label == 'Txn ID') {
                        var style = 'margin-top: 25px;';
                    }
                    
                    html += "<div style='float:left; margin-top: 5px; width: 125px; "+ style +"'>" + fields[x].label + " : </div>";
                    if (fields[x].input == 'dropdown') {
                        html += "<div style='float:left;'><select id='" + fields[x].field + "' name='type_id' class='form-control fix-width'>";
                        html += "<option value='' disabled selected>Select Below</option>";
                        var drop_data = data[fields[x].field];
                        for (var y in drop_data) {
                            if (drop_data[y].id != undefined) {
                                html += "<option value='" + drop_data[y].id + "'>" + drop_data[y].name + "</option>";
                            }
                        }
                        html += "</select></div><br/><br/><input type='hidden' name='type' value='" + fields[x].field + "'>";
                    } else if (fields[x].input == 'text') {
                        html += "<br><div style='float:left;'><input type='text' id='" + fields[x].field + "' name='" + fields[x].field + "' class='form-control fix-width' /></div>";
                        if (fields[x].field == 'bank_txn_id') {
                            html += "<button type='button' class='btn btn-success' style='width: 16%; line-height: 0.7em; margin: 5px 0px 0px 25px; padding-left: 6px;' onclick='checkDetails();'>Check Details</button>";
                        }
                        html += "<br/><br/>";
                    } else if (fields[x].input == 'textarea') {
                        html += "<br><div style='float:left;'><textarea rows='2' cols='50' id='" + fields[x].field + "' name='" + fields[x].field + "' class='form-control fix-width'></textarea></div><br/><br/><br/>";
                    }
                    if (fields[x].field == 'type') { var binding = 1; }
                }
            }
        } else {
            html += "<div style='float:left; margin-top: 5px; width: 125px;'>Sub-category : </div><div style='float:left;'><select id='subcategory' name='category' class='form-control fix-width'>";
            html += "<option value='' disabled selected>Select Below</option>";

            for (var z in data.subcategories) {
                if (data.subcategories[z].category == $("#category").val()) {
                    html += "<option value='" + data.subcategories[z].id + "'>" + data.subcategories[z].subcategory + "</option>";
                }
            }
            html += "</select></div><br/><br/><br/>";
            html += "<div style='float:left; margin-top: 5px; width: 125px;'>Narration : </div><div style='float:left; width:100px'><textarea rows='2' cols='50' id='narration' name='narration' class='form-control fix-width'></textarea></div><br/><br/><br/>";
        }

        $("#box").html(html);

        if(binding) { $("#type").change(function() {
            var html = "<br/><div style='float:left; margin-top: 5px; width: 125px;'>Select " + $('#type').val().charAt(0).toUpperCase() + $('#type').val().slice(1) + " : &nbsp;</div><div style='float:left;'><input type='hidden' name='type_id' id='type_id' /><input type='text' id='type_data' class='form-control fix-width' onkeyup='showResult(this.value)' autocomplete='off' /><div id='livesearch'></div>";
            html += "</div><br/><br/><br/><input type='hidden' name='type' value='" + $('#type').val() + "'>";

            if ($('#category').val() == 1) {
                html += "<div style='float:left; margin-top: 5px; width: 125px;'>Request Mode : </div>";
                html += "<div style='float:left; width:300px;'><select class='form-control fix-width' name='mode' id='mode' onchange='showRequest();'>";
                html += "<option value='' disabled>Select Mode</option>";
                html += "<option selected>By SMS</option>";
                html += "<option>By Calling</option>";
                html += "<option>By Whatsapp</option>";
                html += "<option>By Gmail</option>";
                html += "</select></div><br/><br/><br/>";
                var amount = "<?php echo $amount; ?>";
                var refund = "";
                <?php if (count($txn_details) == 1) { ?>
                if (amount > 199000) {
                    refund = " + Refund : " + (parseFloat(amount) - parseFloat(199000));
                    amount = 199000;
                }
                <?php } ?>
                html += "<div style='float:left; margin-top: 5px; width: 125px;'>Amount : </div><div style='float:left; width:300px'><input type='text' id='amount' name='amount' value='"+ amount +"' onkeyup='editable(1);' class='form-control fix-width' disabled='disabled' /><span id='refund' style='color: red;'>"+refund+"</span></div><div style='float:left; margin: 5px 0 0 25px; color: #1467D2;'><input type='checkbox' onchange='fullRefund();'> Full Refund</div><br/><br/><br/>";
                if ($("#type").val() != 'superdistributor') {
                        html += "<div style='float:left; margin-top: 5px; width: 125px;'>Discount : </div><div style='float:left; width:500px'><input type='text' id='discount' class='form-control fix-width' disabled /></div><br/><br/><br/>";
                }
                html += "<div style='float:left; margin-top: 5px; width: 125px;'>Type : </div><div style='float:left; width:500px; color: slategrey;'><div style='float:left; width: 150px;'><input type='radio' name='typeRadio' style='width: 25px;' value='1' onclick='editable(1)' <?php if ($type == 1) { echo "checked"; } ?>> Cash </div><div style='float:left; width: 200px;'><input type='radio' name='typeRadio' style='width: 25px;' onclick='editable(0)' value='2' <?php if ($type == 2) { echo "checked"; } ?>> NEFT / RTGS / IMPS </div><div style='clear: both;'></div><div style='float:left; width: 150px;'><input type='radio' name='typeRadio' style='width: 25px;' onclick='editable(0)' value='3' <?php if ($type == 3) { echo "checked"; } ?>> ATM Transfer </div><div style='float:left; width: 200px;'><input type='radio' name='typeRadio' style='width: 25px;' onclick='editable(0)' value='4' <?php if ($type == 4) { echo "checked"; } ?>> Cheque / DD</div><br></div><br/><br/><br/>";
            }

            html += "<div style='float:left; margin-top: 5px; width: 125px;'>Narration : </div><div style='float:left; width:500px'><textarea rows='2' cols='50' id='narration' name='narration' class='form-control fix-width'></textarea></div><br/><br/><br/>";
            
            $(".emptySpan").html('');
            $("#box").append("<span class='emptySpan'>"+html+"</span>");
        }); }
    }
    
    function showRequest() {
        
        var discounts = JSON.parse('<?php echo json_encode($discount); ?>');
        var discount =  discounts[$('#type_id').val()] != undefined ? discounts[$('#type_id').val()] + ' %' : '';
        $('#discount').val(discount);

        $('#listing').html('');
        if($('#mode').val() == 'By SMS') {
            var type = $('#type').val();
            var id   = $('#type_id').val();
            var txn_id = '<?php echo $txn_details[0]['atd']['pay1_txn_id']; ?>';
            
            $.post('/accounting/showRequest/', {'type': type, 'id': id, 'txn_id': txn_id}, function(e) {
                var data = JSON.parse(e);
                var html = "<table class='table table-bordered table-hover' style='width: 450px;'>";
                html += "<thead>";             
                    html += "<tr>";
                        html += "<th>ID</th>";
                        html += "<th>Bank Name</th>";
                        html += "<th>Amount</th>";
                        html += "<th>Created</th>";
                        html += "<th>Action</th>";
                    html += "</tr>";
                html += "</thead>";

                html += "<tbody style='color: #4e4e4e;'>";
                if (data.length > 0) {
                    for(var x in data) {
                        if(data[x].limits) {
                            html += "<tr id='limit_"+data[x].limits.id+"'>";
                                html += "<td>"+data[x].limits.id+"</td>";
                                html += "<td>"+data[x].limits.bank_name+"</td>";
                                html += "<td>"+data[x].limits.amount+"</td>";
                                html += "<td>"+data[x][0].date+" "+data[x][0].time+"</td>";
                                html += "<td><center><img src='/img/close.png' onclick='clearSMSReq("+data[x].limits.id+")'></center></td>";
//                                html += "<td>"+data[x][0].time+"</td>";
//                                alert(data[x][0].bank_details);
//                                html += "<td><center><div style='margin-bottom: 5px;'><a href='http://pay1limits.s3.amazonaws.com/limits_939915217282095758.png' target='_child'><b>View</b></a></div><div><img src='/img/close.png' onclick='clearSMSReq("+data[x].limits.id+")'></div></center></td>";
                            html += "</tr>";
                        }
                    }
                } else {
                    html += "<tr><td colspan='5'><center>No Pending SMS Request</center></td></tr>";
                }
                html += "</tbody>";
                html += "</table>";
                $('#listing').html(html);
            });
        }
    }
    
    function clearSMSReq (val) {
        if(confirm("Are You Sure ?") == true) {
            $.post('/accounting/clearSMSReq/', {'id': val}, function(e) {
                if(e == 1) {
                    $('#limit_'+val).html('');
                } else {
                    alert("Something Went Wrong !!!");
                }
            });
        }
    }

</script>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>

<script>

    function onSubmit() {

        if ($('#category').val() == '1') {
            var res = confirm('Are you sure ?');
            if (res == false) {
                return false;
            }
//            var res = prompt('Enter Password for Confirmation : ');
//            $('#password').val(res);
            $('#amount').removeAttr('disabled');
        } else if ($.inArray($('#category').val(), ['55','56']) !== -1) {
            var res = confirm('Are you sure ?');
            if (res == true) {}
        }
    }
    
    function editable(val) {
//        $('#amount').removeAttr('disabled');
//        $('#amount').attr('disabled', 'On');
        
        <?php if (count($txn_details) == 1) { ?>
            
            var amount  = '<?php echo floor($txn_details[0]['atd']['amount']); ?>';
            var max_amt = 199000;
            var refund  = '';

            if (val == 1) {
                if (amount > max_amt && $('#amount').val() != 0) {
                    refund = " + Refund : " + (parseFloat(amount) - max_amt);
                    amount = max_amt;
                } else if ($('#amount').val() == 0) {
                    refund = " + Refund : " + amount;
                    amount = 0;
                }
            }

            $('#amount').val(amount);
            $('#refund').html(refund);
            
        <?php } ?>
    }
    
    function fullRefund () {

        $('#amount').val('0');
        editable(1);
    }
    
    function checkDetails () {
        
        var bank_txn_id = $('#bank_txn_id').val();
        
        if (bank_txn_id != '') {
            $.post('/accounting/txnDetails/', {'bank_txn_id': bank_txn_id}, function(e) {
                var data = JSON.parse(e);
                var html = "<table class='table table-bordered table-hover' style='width: 450px;'>";
                html += "<thead>";             
                    html += "<tr>";
                        html += "<th>ID</th>";
                        html += "<th>Name</th>";
                        html += "<th>Amount</th>";
                        html += "<th>Created</th>";
                    html += "</tr>";
                html += "</thead>";

                html += "<tbody style='color: #4e4e4e;'>";
                if (data) {
                        html += "<tr id='st_"+data.st.id+"'>";
                            html += "<td>"+data.st.id+"</td>";
                            html += "<td>"+data[0].name+"</td>";
                            html += "<td>"+data.st.amount+"</td>";
                            html += "<td>"+data.st.timestamp+"</td>";
                        html += "</tr>";
                } else {
                    html += "<tr><td colspan='4'><center>No Txn Found</center></td></tr>";
                }
                html += "</tbody>";
                html += "</table>";
                
                $('#listing').html(html);
            });
        }
    }
    
    function limitMsg (id, type, profile_id, name) {
    
            $('.close').prop('checked',false);
            $('#close_'+id).prop('checked',true);
            
            $('#type').val(type);
            $('#type').change();
            $('#type_id').val(profile_id);
            $('#type_data').val(name);
            
            var margins = JSON.parse('<?php echo json_encode($margins) ?>');
            $('#discount').val(margins[profile_id] + ' %');
    }
    
    $(document).click(function() {
        clearOptions();
    });
    
    function showResult(str) {
       
        clearOptions();
        
        //if (str.length > 1) {
            var type = $('#type').val();
            $('#livesearch').html("Loading ...");
            $('#livesearch').css({'border':'1px solid #A5ACB2','width':'50px'});
                
            $.post('/accounting/typeList', {'type': type, 'str': str}, function(e) {
                var list = "";

                for (var x in e) {
                    if (e[x].length == undefined) {
                        list = list + "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ e[x].id +",\""+ e[x].name +"\");'>"+ e[x].name +"</a></div>"; 
//                        list = list + "<div style='padding: 5px 0 0 0;' class='selection' id='select_"+ e[x].id +"' data-select-id='"+ e[x].id +"' >"+ e[x].name +"</div>"; 
                    }
                }

                if (list != '') {
                    $('#livesearch').html(list);
                    $('#livesearch').css({'width':'500px'});
                    
//                    $('.selection').on('mouseover',function(){
//                        alert(this.attr('select-id'));
//                        $('.selection').css('border','1px solid black');
//                    }).on('mouseout',function(){
//                        
//                    });
                } else {
                    clearOptions ();
                }
            }, 'json');
        //}
            
        return;
    }
    
    function clearOptions () {
        $('#livesearch').html('');
        $('#livesearch').css({'border':'0px'});
    }

    function selectType (id, name) {
        $('#type_id').val(id);
        $('#type_data').val(name);

        clearOptions();
        showRequest();
    }

</script>
