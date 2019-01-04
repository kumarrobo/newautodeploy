<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/boot/css/buttons.dataTables.min.css">
<div id='innerDiv'>
<?php echo $form->create(null, array('url' => array('controller' => 'accounting', 'action' => 'payuSalesReport')));
?>
<fieldset class="fields1" style="border:0px;margin:0px;">
    <div class="appTitle">PayU sales report</div>
    <?php echo $this->Session->flash(); ?>
<div>
            
    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">Retailers : </div><div><input type="hidden" class="fieldLabel1 form-control autocomplete" id="retailer_id" name="data[retailer]"></div><div style="float: left; width: 350px;"><input type="text" class="fieldLabel1 form-control autocomplete" style="width:300px;" id="retailer" placeholder="Search by ID / Company Name" onkeyup="showResult(this.value)" autocomplete="off"></div>
    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">From Date : </div><div style="float: left; width: 150px;"><input type="text" class="form-control" style="width: 110px;" id="from_date" name="data[fromdate]"value="<?php echo $date;?>" ></div>
    <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">To Date : </div><div style="float: left; width: 150px;"><input type="text" class="form-control" style="width: 110px;" id="to_date" name='data[todate]' placeholder="To" value="<?php echo $date;?>"></div>
    <div style="float: left; width: 100px;  font-weight: bold;"><button  type="button" class="btn btn-primary" id="view">Submit</button></div>
    <div id="livesearch"></div>     
            
</div>
        </div>
    <div id="ds">
    </div>

</fieldset>
<?php echo $form->end(); ?>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/boot/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/boot/js/jszip.min.js"></script>
<script type="text/javascript" src="/boot/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="/boot/js/buttons.print.min.js"></script>
<script>jQuery.noConflict();</script>
<script>
    jQuery('#from_date, #to_date').datepicker({
        dateFormat: 'dd-mm-yy',
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true,

    });

     window.onclick = function() {
        clearOptions();
    }
      function showResult(str){ 
            if(str == ''){ 
                clearOptions();
            }
            var list = "";

            if (str.length > 1) {
                jQuery('#livesearch').html("Loading ...");
                jQuery('#livesearch').css({'border':'1px solid #A5ACB2','width':'50px'});
                jQuery.ajax({
                            type : 'POST',
                            url : '/accounting/retailerList',
                            dataType : "json",
                            data : {
                                search : str
                            },
                            success: function(data){
                                jQuery.each(data, function(k, v) {
                                    list +=  "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ v.id +",\""+ v.name +"\",\""+ v.ret_name +"\",\""+ v.mobile +"\");'>"+v.id+"-"+ v.ret_name +"</a></div>";
                                });
                                jQuery('#livesearch').html(list);
                                jQuery('#livesearch').css({'width':'300px','margin-left': '100px'});
                            }
                });
            }
        }
          function selectType (id, name,ret_name,mobile) {
            jQuery('#retailer_id').val(id);
            jQuery('#retailer').val(name);
            jQuery('#name').val(ret_name);
            jQuery('#contact').val(mobile);

        }
        function clearOptions () {
            jQuery('#livesearch').html('');
            jQuery('#livesearch').css({'border':'0px'});
        }

        jQuery(function(){
            jQuery('#view').click();
        });
            jQuery('#view').click(function() {
            var data = {
                'viewdata': 1,
                'ret': jQuery('#retailer_id').val(),
                'from_date' : jQuery('#from_date').val(),
                'to_date': jQuery('#to_date').val()
            };

             jQuery.ajax({
                type: 'POST',
                url: '/accounting/viewPayUReport',
                dataType: 'json',
                data: data,
                success: function(response) {
                    if (response.status == 'failed') {
                        alert(response.msg);
                        return false;
                    }
                     if (response.length == 0) {
                        jQuery('#ds').html("");
                    }

                        var retreport = "<div class='panel panel-primary filterable'>";
                        retreport += "<div class='panel-heading'>";
                        retreport += "<h3 class='panel-title'>PayU sales report</h3>";
                        retreport += "</div>";
                        retreport += "<div class=''>";
                        retreport += "<table class='table table-responsive display' id='rettxn'>";
                        retreport += "<thead>";
                        retreport += "<tr class='filters'>";
                        retreport += "<th>Txn id</th>";
                        retreport += "<th>PayU Id</th>";
                        retreport += "<th>Shop Name</th>";
                        retreport += "<th>Mobile</th>";
                        retreport += "<th>Amount</th>";
                        retreport += "<th>Timestamp</th>";
                        retreport += "</tr>";
                        retreport += "</thead>";
                        retreport += "<tbody>";
                        for (var x in response) {
                            if (!isNaN(x)) {
                                retreport += "<tr>";
                                    retreport += "<td>" + (response[x].shop_transactions.id == null ? '' : response[x].shop_transactions.id) + "</td>";
                                    retreport += "<td>" + (response[x].shop_transactions.note == null ? '' : response[x].shop_transactions.note) + "</td>";
                                    retreport += "<td>" + (response[x].retailers.shopname == null ? '' : response[x].retailers.shopname) + "</td>";
                                    retreport += "<td>" + (response[x].retailers.mobile == null ? '' : response[x].retailers.mobile) + "</td>";
                                    retreport += "<td>" + (response[x].shop_transactions.amount == null ? '' : response[x].shop_transactions.amount) + "</td>";
                                    retreport += "<td>" + (response[x].shop_transactions.timestamp == null ? '' : response[x].shop_transactions.timestamp) + "</td>";
                                retreport += "</tr>";
                            }
                        }
                        retreport += "</table>";
                        retreport += "</div>";
                        retreport += "</div>";
                        jQuery("#ds").html(retreport);
                        jQuery('#rettxn').dataTable({
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
                            "pageLength": 10,
                            "lengthMenu": [10, 20, 50],
                            "dom": 'Bfrtip',
                            "buttons": [
                                 'excel'
                            ]
                        });
                }
        });
  });

</script>
</div>