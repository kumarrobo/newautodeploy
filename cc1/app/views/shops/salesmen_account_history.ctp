<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div>
    <?php // print_r($search);die;?>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'history'));?>

                <a href="/shops/accountHistory/0/1/"><input class="retailBut enabledBut btn btn-default" style="padding: 0px 5px 3px; float: right;" value="Back" type="button"></a>
                <br/><br/>
                   <?php $messages=$this->Session->flash(); ?>
                    <?php if(!empty($messages) && preg_match('/Error/',$messages)): ?>
                        <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <p><?php  echo $messages; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($messages) && preg_match('/Success/',$messages)): ?>
                        <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <p><?php  echo $messages; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" id="SalesmenReport" action="/shops/salesmenAccountHistory">
                       <div>
                        <div style="float: left; width: 70px; margin-top: 5px; font-weight: bold;">Search :</div><input type="hidden" id="search_id" name="search_id" value="<?php echo $search_id ?>">
                        <div style="float: left; width: 250px;"><input type="text" class="form-control" style="width: 200px;" id="search" name="search" placeholder="Search by Name" autocomplete="off" value="<?php echo $search; ?>"><div id='livesearch'></div></div>
                        <div style="float: left; width: 100px; margin-top: 5px; font-weight: bold;">From Date : </div><div style="float: left; width: 150px;"><input type="text" class="form-control" style="width: 110px;" id="from_date" placeholder="From" name="from_date" value="<?php echo $from_date; ?>"></div>
                        <div style="float: left; width: 80px; margin-top: 5px; font-weight: bold;">To Date : </div><div style="float: left; width: 150px;"><input type="text" class="form-control" style="width: 110px;" id="to_date" placeholder="To" name="to_date" value="<?php echo $to_date;  ?>"></div>
                        <div><input class="btn btn-primary" type="submit" value="Submit" style="padding: 5px 5px;" ></div>  
                    </div>
                    </form>
	
                    <div>
    			<div class="appTitle" style="margin-top:50px;">Transaction History ( <?php echo $from_date;?> &nbsp;-&nbsp;  <?php echo $to_date;?>)</div>
                        <table class="table table-bordered table-hover" style="width:900px;margin-top:20px;">
                            <thead>
                              <tr>
                                <th style="text-align:center;">Txn Id</th>                                    
                                <th style="text-align:center;">Particulars</th>                                    
                                <th style="text-align:center;">Debit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>                                    
                                <th style="text-align:center;">Opening (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Closing (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Time</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($transactions as $transaction){ ?>
                                    <tr>
                                        <td style="text-align:center;"><?php echo $transaction['shop_transactions']['id'];?></td>                                            
                                        <td style="text-align:center;"><?php echo $transaction['shop_transactions']['note'];?></td>   
                                        <td class="number"><?php if(!empty($transaction['shop_transactions']['debit'])) echo round($transaction['shop_transactions']['debit'],2); else echo "-";?></td>
                                        <td class="number"><?php if(!empty($transaction['shop_transactions']['credit'])) echo round($transaction['shop_transactions']['credit'],2); else echo "-";?></td>
                                        <td style="text-align:center;"><?php echo $transaction['shop_transactions']['source_opening'];?></td>
                                        <td style="text-align:center;"><?php echo $transaction['shop_transactions']['source_closing'];?></td>
                                        <td style="text-align:center;"><?php echo $transaction['shop_transactions']['timestamp'];?></td>
                                    </tr>
                                    <?php }  ?>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>
</div>
<script>
    $('#from_date, #to_date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "-1d",
        multidate: false,
        autoclose: true
    });
    
    $("#search").keyup(function() {
        
        clearOptions();
        
        var str = $("#search").val();
        
        if (str.length > 0) {

            $('#livesearch').html("Loading ...");
            $('#livesearch').css({'border':'1px solid #A5ACB2','width':'250px'});
                
            $.post('/shops/salesmenList', { 'str': str}, function(e) {
                var list = "";

                for (var x in e) {
                    if (e[x].length == undefined) {
                        list = list + "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ e[x].salesmen['id'] +",\""+ e[x].salesmen['name'] +"\");'>"+ e[x].salesmen['name'] +"</a></div>"; 
                    }
                }

                if (list != '') {
                    $('#livesearch').html(list);
                } else {
                    $('#livesearch').html('<center>No Record Found !!!</center>');
                }
                $('#livesearch').css({'width':'400px'});
                
            }, 'json');
        }
    });
    
    function clearOptions () {
        $('#livesearch').html('');
        $('#livesearch').css({'border':'0px'});
    }

    function selectType (id, name) {
        $('#search_id').val(id);
        $('#search').val(name);

        clearOptions();
    }

</script>
