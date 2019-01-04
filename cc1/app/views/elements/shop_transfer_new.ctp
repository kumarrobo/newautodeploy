
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<style type="text/css">
    .autocomplete-suggestions { border: 1px solid #999; background: #fff; cursor: default; overflow: auto; }
    .autocomplete-suggestion { padding: 10px 5px; font-size: 1.0em; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #f0f0f0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399ff; }
    ul#ui-id-1 { font-size:12px; text-align:left; }
</style>
<?php
foreach ($retailers as $retailer) {
    $datavalue[] = array("value" => ($retailer['ur']['shopname'] != '' ? $retailer['ur']['shopname'] : $retailer['retailers']['shopname']) . "-" . $retailer['retailers']['mobile'], "data" => $retailer['retailers']['id']);
}
if(isset($salesman)){
foreach ($salesman as $sales) {
    $datavalue1[] = array("value" => $sales['salesmen']['name'] . "-" . $sales['salesmen']['mobile'], "data" => $sales['salesmen']['id']);
}
}
?>
<script>
    var $j = jQuery.noConflict();

    $j(function () {
        var data1 = <?php echo json_encode($datavalue); ?>;
        var projects = data1;
        
        <?php if($data['confirm_flag'] == 1) { ?>
        numinwrd('amount');
        <?php } ?>

        $j('.autocomplete').autocomplete({
            minLength: 0,
            source: projects,
            focus: function (event, ui) {
                $j('.autocomplete').val(ui.item.value);
                return false;
            },
            select: function (event, ui) {
                $j('.autocomplete').val(ui.item.value);
                type = $j('#type').val();
                getLastTrnfrd(ui.item.data, type);
                $j("#shop").val(ui.item.data);
                return false;
            }
        })

        .autocomplete("instance")._renderItem = function (ul, item) {
                return $j("<li>")
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
        };
        
        $j(document).keypress(function(event){
                if (event.which == '13') {
                        event.preventDefault();
                }
        });
    });
</script>

<?php echo $form->create('shop'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
    <?php
    echo $this->Session->flash();
    echo "<br/>";
    if(!isset($data['confirm_flag'])) {
        $data['confirm_flag'] = 0;
    }
    ?>

    <div class="appTitle">Transfer Balance</div>
    <div style="width:60%; float:left">
        <div>
            <div>
                <div class="field" style="padding-top:5px;">
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="amount"><?php if($data['confirm_flag'] == 0) { ?>Enter <?php } ?>Amount (<img src="/img/rs.gif" align="absmiddle">)</label></div>
                        <div class="fieldLabelSpace1">
                            <?php if($data['confirm_flag'] == 0) { ?>
                            <input tabindex="1" type="text" id="amount" name="data[amount]" autocomplete="off" onkeyup="numinwrd('amount')" value="<?php if (isset($data)) echo $data['amount']; ?>"/><span style="color:green;font-size:11px" id="amount_word"></span>
                            <?php } else { ?>
                            <span><input type="text" name="confirm" id="confirm" /></span><b> <span id="amount_word"></span> Rupees</b>
                            <input type="hidden" name="data[amount]" id="amount" value="<?php echo $data['amount']; ?>">
                            <?php } ?>
                        </div>                     
                    </div>
                </div>
            </div>
            <?php if($data['confirm_flag'] == 0) { ?>
            <div class="field">
                <div class="fieldDetail" style="width:350px;">
                    <div class="fieldLabel1 leftFloat"><label for="shop"><span id="label">Select Category</span></label></div>
                    <div class="fieldLabelSpace1">
                        <select id="type" name="data[type]" onchange="typeChange();" style="border: 1px solid #4d5e69; width: 165px; height: 22px; background-color: white;">
                            <option <?php if($data['type'] == RETAILER) { echo "selected"; } ?>>Retailer</option>
                            <option <?php if($data['type'] == SALESMAN) { echo "selected"; } ?>>Salesman</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <input type="hidden" name="data[type]" id="type" value="<?php echo $data['type'] == RETAILER ? 'Retailer' : 'Salesman'; ?>">
            <?php } ?>
            <div class="field">
                <div class="fieldDetail" style="width:350px;">
                    <div class="fieldLabel1 leftFloat"><label for="shop"><span id="label_sr"><?php if($data['confirm_flag'] == 0) { ?>Select <?php } ?><?php echo $data['type'] == SALESMAN ? 'Salesman' : 'Retailer'; ?></span></label></div>
                    <div class="fieldLabelSpace1">
                        <?php if($data['confirm_flag'] == 0) { ?>
                        <input type="text" class="autocomplete" style="width:459px;" id="shop1" name="data[shop1]" tabindex="2" placeholder="Select Retailer" value="<?php if (isset($data) && isset($data['shop1'])) echo $data['shop1']; ?>">
                        <input type ="hidden" id="shop" name="data[shop]" style="width:459px;" value="<?php if (isset($data) && isset($data['shop'])) echo $data['shop']; ?>">
                        <?php } else { ?>
                        <span><b><?php echo $data['shop1']; ?></b></span>
                        <input type="hidden" name="data[shop]" id="shop" value="<?php echo $data['shop']; ?>">
                        <?php } ?>
                        <div id="dist_sub"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($data['confirm_flag'] == 0) { ?>
        <div>
            <div class="field">
                <div class="fieldDetail" style="width:800px;">
                    <div class="fieldLabel1 leftFloat"><label for="type">Transfer Type</label></div>
                    <div class="fieldLabelSpace1">
                        <input type="radio" name="data[typeRadio]" id="typeRadio" value="1" <?php if (isset($data['typeRadio']) && $data['typeRadio'] == 1) echo "checked"; ?>/> Cash
                        <input style="margin-left:10px;" type="radio" name="data[typeRadio]" id="typeRadio" value="2" <?php if (isset($data['typeRadio']) && $data['typeRadio'] == 2) echo "checked"; ?> /> NEFT/RTGS
                        <input style="margin-left:10px;" type="radio" name="data[typeRadio]" id="typeRadio" value="3" <?php if (isset($data['typeRadio']) && $data['typeRadio'] == 3) echo "checked"; ?> /> ATM Transfer
                        <input style="margin-left:10px;" type="radio" name="data[typeRadio]" id="typeRadio" value="4" <?php if (isset($data['typeRadio']) && $data['typeRadio'] == 4) echo "checked"; ?> /> Cheque/DD
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <input type="hidden" name="data[typeRadio]" id="typeRadio" value="<?php echo $data['typeRadio']; ?>">
        <?php } ?>
        
        <input name="data[group]" type="hidden" value="<?php echo $_SESSION['Auth']['User']['group_id']; ?>">

        <?php if ($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR && in_array($_SESSION['Auth']['id'], explode(",", DISTS)) && $data['confirm_flag'] == 0) { ?>
            <div>	 
                <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="type">Bank Details</label></div>
                        <div class="fieldLabelSpace1">
                            <select name ="data[bank_name]" id ="bank_name"  value="">
                                <option value= "">--SELECT BANK--</option>
                                <?php foreach ($bankDetails as $bval) { ?>
                                    <option value ="<?php echo $bval['bank_details']['bank_name']; ?>" <?php if (isset($data) && isset($data['bank_name']) && $bval['bank_details']['bank_name'] == $data['bank_name']) echo " selected"; ?>><?php echo $bval['bank_details']['bank_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else if ($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR && in_array($_SESSION['Auth']['id'], explode(",", DISTS)) && $data['confirm_flag'] == 1) { ?>
            <div>	 
                <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="type">Bank Name</label></div>
                        <div class="fieldLabelSpace1">
                            <span><b><?php echo $data['bank_name']; ?></b></span>
                            <input type="hidden" name="data[bank_name]" id="bank_name" value="<?php echo $data['bank_name']; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        <?php if($data['confirm_flag'] == 1) { ?>
                <div id="divType">
                    <div class="field">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="type">Balance Amount</label></div>
                            <div class="fieldLabelSpace1">
                                <span><b><?php echo $balance; ?></b></span>
                            </div>
                        </div>
                    </div>
                </div>
        <?php } ?>
        
        <div id="divType">
            <div class="field">
                <div class="fieldDetail" style="width:350px;">
                    <div class="fieldLabel1 leftFloat"><label for="type">Bank TxnID</label></div>
                    <div class="fieldLabelSpace1">
                        <?php if($data['confirm_flag'] == 0) { ?>
                        <textarea id="description" name="data[description]" style="width:180px;height:55px;"><?php if (isset($data['description'])) echo $data['description']; ?></textarea>
                        <?php } else {
                                $transfer_type = array(1=>'Cash',2=>'NEFT/RTGS',3=>'ATM Transfer',4=>'Cheque/DD');
                        ?>
                        <span><b><?php echo $data['description'] != '' ? $transfer_type[$data['typeRadio']]." - ".$data['description'] : $transfer_type[$data['typeRadio']]; ?></b></span>
                        <input type="hidden" name="data[description]" id="description" value="<?php echo $data['description']; ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" name="data[confirm_flag]" id="confirm_flag" value="<?php echo $data['confirm_flag']; ?>" />
        
        <div class="field" style="padding-top:15px;">               		
            <div class="fieldDetail" style="width:375px;">
                <div class="fieldLabelSpace1" id="sub_butt" style="float: left;">
                    <?php
                            $button = $data['confirm_flag'] == 0 ? 'Transfer Balance' : 'Confirm Transfer';
                            if($data['confirm_flag'] == 0) {
                                    echo $ajax->submit($button, array('id' => 'sub', 'tabindex' => '3', 'url' => array('controller' => 'shops', 'action' => 'amountTransferNew'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv'));
                            } else {
                    ?>
                    <input type="button" class="retailBut enabledBut" onclick="checkConfirm()" value="<?php echo ' '.$button.' '; ?>" id="tran_confirm" />
                    <?php } ?>
                </div>
                <?php if($data['confirm_flag'] == 1) { ?>
                <div style="background-color: black; float: left; padding: 3px;"><a href="/shops/view"><b style="color: white;">Cancel</b></a></div>
                <br><br><div id="load" style="margin-left: 175px;"></div>
                <?php } ?>
            </div>
        </div>
        <?php echo $this->Session->flash(); ?>
    </div>

    <div id="lastTxns">


    </div>
</fieldset>
<?php echo $form->end(); ?>
<script>
    if ($('amount'))
        $('amount').focus();

    function typeCheck()
    {
        var sel = document.getElementsByName('data[typeRadio]');
        var str = '';
        for (var i = 0; i < sel.length; i++) {
            if (sel[i].checked == true) {
                str = sel[i].value;
            }
        }

        if (str == 1) {
            $('divType').hide();
        } else {
            $('divType').show();
        }

    }

    function autocalculate() {
        var id = $('shop').value;
        var amount = $('amount').value;
        if (id == 0) {
            alert("Please " + $('label').innerHTML);
        } else if (amount <= 0) {
            alert("Please enter correct amount");
        } else {
            var url = '/shops/calculateCommission';
            var params = {'id': id, 'amount': amount};
            var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
                onSuccess: function (transport)
                {
                    var html = transport.responseText;
                    $('commission').value = html;
                    //numinwrd('commission');
                }
            });
        }
    }

    function numinwrd(id)
    {
        var numbr = document.getElementById(id).value;
        var str = new String(numbr)
        var splt = str.split("");
        var rev = splt.reverse();
        var once = ['Zero', ' One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        var twos = ['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
        var tens = ['', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety'];
        numlen = rev.length;
        var word = new Array();

        var j = 0;
        for (i = 0; i < numlen; i++)
        {
            switch (i)
            {
                case 0:
                    if ((rev[i] == 0) || (rev[i + 1] == 1))
                    {
                        word[j] = '';
                    } else
                    {
                        word[j] = once[rev[i]];
                    }
                    word[j] = word[j];

                    break;
                case 1:
                    abovetens();
                    break;
                case 2:
                    if (rev[i] == 0)
                    {
                        word[j] = '';
                    } else if ((rev[i - 1] == 0) || (rev[i - 2] == 0))
                    {
                        word[j] = once[rev[i]] + " Hundred ";
                    } else
                    {
                        word[j] = once[rev[i]] + "Hundred and";
                    }
                    break;
                case 3:
                    if (rev[i] == 0 || rev[i + 1] == 1)
                    {
                        word[j] = '';
                    } else
                    {
                        word[j] = once[rev[i]];
                    }
                    if ((rev[i + 1] != 0) || (rev[i] > 0))
                    {
                        word[j] = word[j] + " Thousand";
                    }
                    break;
                case 4:
                    abovetens();
                    break;

                case 5:
                    if ((rev[i] == 0) || (rev[i + 1] == 1))
                    {
                        word[j] = '';
                    } else
                    {
                        word[j] = once[rev[i]];
                    }
                    word[j] = word[j] + "Lakhs";
                    break;

                case 6:
                    abovetens();
                    break;

                case 7:
                    if ((rev[i] == 0) || (rev[i + 1] == 1))
                    {
                        word[j] = '';
                    } else
                    {
                        word[j] = once[rev[i]];
                    }
                    word[j] = word[j] + "Crore";
                    break;

                case 8:
                    abovetens();
                    break;
                default:
                    break;
            }

            j++;

        }

        function abovetens()
        {
            if (rev[i] == 0)
            {
                word[j] = '';
            } else if (rev[i] == 1)
            {
                word[j] = twos[rev[i - 1]];
            } else
            {
                word[j] = tens[rev[i]];
            }
        }

        word.reverse();
        var finalw = '';
        for (i = 0; i < numlen; i++)
        {

            finalw = finalw + word[i];

        }

        $(id + '_word').innerHTML = finalw;
    }


    function setCommissionAndPer() {

        var amt = $("amount").value == "" ? 0 : $("amount").value;
        var comm = parseFloat($("commission_per").value) * parseFloat(amt) / 100;

        $("commission").value = parseFloat(comm).toFixed(2);
    }
    
    function checkConfirm() {
        if ($('amount').value != $('confirm').value) {
            alert("Plz enter same amount !");
            return false;
        }
<?php if ($this->Session->read('Auth.User.id') == 1 || $_SESSION['Auth']['User']['group_id'] == ADMIN) { ?>
            if ($('pass').value != '' && $('password').value == '') {
                alert("Please Enter Pasword !");
                return false;
            }
<?php } ?>

        showLoader2("load");
        $('tran_confirm').disable();
        var url = '/shops/amountTransferNew';
        var params = $('shopAmountTransferNewForm').serialize();
        var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
            onSuccess: function (transport)
            {
                var html = transport.responseText;
                $("innerDiv").update(html);
            }
        });
    }

    function getLastTrnfrd(object, type) {

        var shop_id = object;
        var url = '/shops/lastTransferred';
        var params = {'id': shop_id, 'type': type};
        $('lastTxns').innerHTML = "";
        showLoader2("dist_sub");
        var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
            onSuccess: function (transport)
            {
                var html = transport.responseText;
                var parsedJSON = eval('(' + html + ')');
                $('dist_sub').innerHTML = "";
                var text = '<div style="float:left; background:#bbff1f;height=100px; width:38%;">';

                if (parsedJSON != null) {
                    text += '<div class="appTitle" style="margin-top:20px;">Last Transferred</div>';

                    text += '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">' +
                            '<thead>' +
                            '<tr class="noAltRow altRow">' +
                            '<th style="width:80px;">Txn Id</th>' +
                            '<th style="width:80px;">Amount</th>' +
                            '<th style="width:80px;">Comm</th>' +
                            '<th style="width:80px;">Time</th>' +
                            '<th style="width:80px;">Bank Id</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>';

                    for (var i = 0; i < parsedJSON.length; i++) {
                        var arr = parsedJSON[i];
                        if (i % 2 == 0)
                            class1 = '';
                        else
                            class1 = 'altRow';
                        text += '<tr class="' + class1 + '">';
                        text += '<td>' + arr.st1.id + '</td>';
                        text += '<td>' + arr.st1.amount + '</td>';
                        if (arr.st2.amount == null)
                            comm = 0;
                        else
                            comm = arr.st2.amount;
                        text += '<td>' + comm + '</td>';
                        text += '<td>' + arr.st1.timestamp + '</td>';
                        text += '<td>' + arr.st1.note + '</td>';
                        text += '</tr>';
                    }

                    text += '</tbody></table>';
                } else {
                    text += 'No transfer in last 7 days';
                }
                text += '</div>';
                $('lastTxns').innerHTML = text;
            }
        });

    }

    function typeChange() {

        $j('#label_sr').html("Select " + $j('#type').val());
        $j('#shop1').attr("placeholder", "Select " + $j('#type').val());
        $j('#shop1').val('');
        $j('#shop').val('');

        if ($j('#type').val() == 'Salesman') {
            var projects = <?php echo json_encode($datavalue1); ?>;
        } else {
            var projects = <?php echo json_encode($datavalue); ?>;
        }

        $j('.autocomplete').autocomplete('option', 'source', projects);
    }

    function reloadBal(bal) {
    
        if(bal != undefined && !isNaN(bal) && bal >= 0) {
            $j('#UserBalance').html('Balance : <span><img class="rupee1" src="/img/rs.gif" align="absMiddle" style="margin-bottom:3px;"></span>'+bal+'.00');
        }
    }

</script>
