<title>Retailer List</title>
<style>
    .form-control{
        padding: 5px;
    }
   
    .row{
        /*margin-bottom: 20px;*/
    }
</style>
<script>

function saveMaintenanceSm(rid,obj,original_salesman)
{
	var salesManId=obj.options[obj.selectedIndex].value;
		
	var r=confirm("You sure?");
	if(r==true){
            var url = '/salesmen/mapSalesman';
            $.ajax({
                type: 'POST',
                url: url,
                data: {'rid' : rid,'sid':salesManId},
                error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
                },
                success: function(response) {
                    alert('Done');
                }
            });
            
		
//		var params = {'rid' : rid,'sid':salesManId};
//		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
//		onSuccess:function(transport)
//				{			
//					alert('done');
//				}
//		});
	}else{
            $(obj).val(original_salesman);
            return false;
        }
}

function changeDistributor(rid,obj,retailer_balance,original_distributor)
{
        var group_id = '<?php echo $_SESSION['Auth']['User']['group_id']; ?>';
        // check if retailers balance is not less than 10, then revert the distributor and notify user
        if( retailer_balance > 500 && group_id != '25'){
            $(obj).val(original_distributor);
            alert('Only retailers with balance less than or equal to 500 can be shifted.');
            return false;
        }
	var distId=obj.options[obj.selectedIndex].value;
		
	var r=confirm("Please check if retailer pending is cleared from last distributor. If everything is ok, go ahead");
	if(r==true){
            var url = '/panels/changeDistributor';
            $.ajax({
                type: 'POST',
                url: url,
                data: {'rid' : rid,'sid':distId},
                error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
                },
                success: function(response) {
                    alert('Done');
                }
            });
	} else{
            $(obj).val(original_distributor);
            return false;
        }
}

function shiftRental(rid,obj,mob,original_rental_flag)
{       
	var flag=obj.options[obj.selectedIndex].value;
	var r=confirm("You sure?");
	if(r==true){
		var url = '/salesmen/rentalRetailer';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {'rid' : rid,'flag':flag , 'mobile' : mob},
                    error: function() {
    //                   $('#info').html('<p>An error has occurred</p>');
                    },
                    success: function(response) {
                        if($.trim(response) == 'success'){
                            alert('done');
                        }else{
                            $(obj).val(original_rental_flag);
                            alert(response);
                        }
                    }
                });
//		var params = {'rid' : rid,'flag':flag , 'mobile' : mob};
//		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
//		onSuccess:function(transport)
//				{			
//					if(transport.responseText == 'success'){
//						alert('done');
//					}else{
//						alert(transport.responseText);
//					}
//				}
//		});
		
	}else{
            $(obj).val(original_rental_flag);
            return false;
        }
}

function retEnable(rid,obj,original_block_flag)
{
	var flag=obj.options[obj.selectedIndex].value;
	var r=confirm("You sure?");
	if(r==true){
		var url = '/salesmen/blockRetailer';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {'rid' : rid,'flag':flag},
                    error: function() {
    //                   $('#info').html('<p>An error has occurred</p>');
                    },
                    success: function(response) {
                        if($.trim(response) == 'success'){
                            alert('done');
                        }else{
                            $(obj).val(original_block_flag);
                            alert('Try again');
                        }
                    }
                });
//		var params = {'rid' : rid,'flag':flag};
//		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
//		onSuccess:function(transport)
//				{			
//					if(transport.responseText == 'success'){
//						alert('done');
//					}else{
//						alert('Try again');
//					}
//				}
//		});
		
	}else{
            $(obj).val(original_block_flag);
            return false;
        }
}

function findRet(flag,obj)
{
	if(flag==1){
		var distId=obj.options[obj.selectedIndex].value;
		var url="/panels/retColl/"+distId;
	}
	else {
            var salesManId= $(obj).val();
            var distId= $('#distributor').val();
//		var salesManId=obj.options[obj.selectedIndex].value;
//		var distId=$('#distributor').options[$('#distributor').selectedIndex].value;
		var url="/panels/retColl/"+distId+"/"+salesManId;
	}
	window.location.href = url;
}
function delRetailer(typ , rid , flag){
        var toShow , block , msg;
        if(flag == 'delete'){
                toShow = 0 ;
                block = 1;                
                
        }else if(flag == 'revert'){
                toShow = 1;
                block = 0;
        }else{
            return false;
        }
       
	if(confirm("Do you want to "+flag+" this retailer ?")){
          
            var url = '/shops/deleteRetailer';
            $.ajax({
                type: 'POST',
                url: url,
                dataType:'json',
                data: {'id' : rid,'type':typ , 'toShow' : toShow , 'block' : block },
                error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
                },
                success: function(response) {
                    if($.trim(response.status) == "success"){
                        $("#ret_"+rid).parent().html('-');
                    }
                }
            });
//            var params = {'id' : rid,'type':typ , 'toShow' : toShow , 'block' : block };
//            var myAjax = new Ajax.Request(url, {method: 'post',type: 'JSON', parameters: params,
//                    onSuccess:function(transport)
//                            {
//                                    if(transport.status == 200){
//                                        $("ret_"+rid).hide();
//                                    }
//                            }
//            });
            
        }
}

function goToPage($page){
    window.location.href = '?page='+$page;
//	$('page').value = $page;
//
//	$('form').submit();
}



</script>
    <?php // modal for distributor selection for multiple retailers ?>
  <div class="modal fade" id="shift_mltiple_retailers_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">select Distributor</h4>
        </div>
        <div class="modal-body">
            <select name="distributor" id="distributors_for_multiple_retailers" class="form-control">
            <?php
		foreach($distList as $d)
                    {									
                        echo "<option value='".$d['distributors']['id']."' >".
                                $d['distributors']['company']."-".$d['users']['mobile'].
                            "</option>";
                    }
            ?>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:changeDistributorForMultipleRetailers()">Shift</button>
        </div>
      </div>
    </div>
  </div>
<div class="row">
    <form id="form" name="retailerCollection" method="POST" action="<?php echo strtok($_SERVER['REQUEST_URI'],'?'); ?>">
        <!--<input type="hidden" id="page" name="page" value="<?php //  if(isset($page)) echo $page ?>" >-->
        <input type="hidden" id="page" name="page" value="1" >

        <div class="col-md-3">
            <label>Distributor : </label>
            <select name="distributor" id="distributor" onChange="findRet(1,this)" class="form-control">
                <?php
                    foreach($distList as $d)
                        {										
                            $sel = '';
                            if($distId == $d['distributors']['id'])
                            {
                                    $sel = 'selected';							
                            }
                            echo "<option ".$sel." value='".$d['distributors']['id']."' >".$d['distributors']['company']."-".$d['users']['mobile']."</option>";
                        }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Maintenance Salesman :</label>
            <select name="maint_salesman_retailer" id="maint_salesman_retailer" onChange="findRet(2,this)" class="form-control">
                <?php
                    echo "<option value='0'>None</option>";
                    foreach($salesmenList as $d)
                        {
                            $sel = '';
                            if($sid == $d['salesmen']['id'])
                            {
                                    $sel = 'selected';								
                            } 
                            echo "<option ".$sel." value='".$d['salesmen']['id']."' >".$d['salesmen']['name']."-".$d['salesmen']['mobile']."-".$d['salesmen']['id']."</option>";
                        }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>&nbsp;</label>
            <input type="text" placeholder="Search by retailer mobile or shop name" name="search_term" id="search_term" value="<?php echo $search_term ?>" class="form-control">
        </div>
        <div class="col-md-1">
            <label>&nbsp;</label>
            <input type="submit" value="Search" class="btn btn-default"/> 
        </div>


					
 

<!-- Acquisition Salesman : <select name="acq_salesman_retailer" id="acq_salesman_retailer" onChange="findRet('2',this)">
	<?php
		/*echo "<option value='0'>None</option>";
		foreach($salesmenList as $d)
		{
			$sel = '';
			if($sid == $d['salesmen']['id'] && $flag == 2)
			$sel = 'selected';			
	 		echo "<option ".$sel." value='".$d['salesmen']['id']."' >".$d['salesmen']['name']."-".$d['salesmen']['mobile']."-".$d['salesmen']['id']."</option>";
		}*/
	?>	
						</select> -->
    
        <div class="col-md-2">
            <label>&nbsp;</label>
            <input class="btn btn-primary btn-md" type="button"  onclick="javascript:openShiftMultipleRetailersModal()" value="Shift Multiple Retailers"/> 
        </div>
    </form>
</div>
<?php 
if( count($amountTransferred) > 0 ){ ?>
<div class="row">
    <div class="col-md-7">
    </div>
    <div class="col-md-5 text-right">
        <?php echo $this->element('pagination');?>
    </div>
</div>

<table class="table-condensed table-bordered table-striped table-responsive" style="font-size: 13px !important;">
	<tr>
            <td style="color:#fff;background-color: #428bca;"><input type="checkbox" id="select_all"/></td>
            <td style="color:#fff;background-color: #428bca;">Index</td>
            <td style="color:#fff;background-color: #428bca;">Retailer Id</td>
            <td style="color:#fff;background-color: #428bca;">Shop Name</td>
            <td style="color:#fff;background-color: #428bca;">Days old</td>
            <td style="color:#fff;background-color: #428bca;">Salesman</td>
            <td style="color:#fff;background-color: #428bca;">Balance</td>
            <td style="color:#fff;background-color: #428bca;">Overall</td>
            <td style="color:#fff;background-color: #428bca;">Deleted</td>
            <td style="color:#fff;background-color: #428bca;">Rental/Kit</td>
            <td style="color:#fff;background-color: #428bca;">Block/Unblock</td>
            <td style="color:#fff;background-color: #428bca;">Maintenance Salesmen</td>
            <td style="color:#fff;background-color: #428bca;">Shift</td>
	</tr>
	<?php
        
		$i = 1;
		$amtTran = 0;
		$amtBal = 0;
		$setupColl = 0;
		$avg1 = 0;
		$avg2 = 0;
        $color = '';
		
		foreach($amountTransferred as $at){
			$date1 = new DateTime(date('Y-m-d'));
			$date2 = new DateTime($at['0']['created']);
			$interval = $date1->diff($date2);
			$days = $interval->y . " yr, " . $interval->m." mths, ".$interval->d." days ";
			
            echo "<tr style='color:".$color."'><td><input data-retailer-id = '".$at['ret']['id']."' data-retailer-balance = '".$at['users']['balance']."' id= 'check_".$at['ret']['id']."' class='retailer-select' type='checkbox' name='check[]'></td><td>".$i."</td><td><a href='/panels/retInfo/".$at['ret']['mobile']."'>".$at['ret']['id']."</a>&nbsp;</td><td><a href='/panels/retInfo/".$at['ret']['mobile']."'>".$at['ur']['shopname']."</a>&nbsp;</td><td>".$days."&nbsp;</td><td>".$at['salesmen']['name']."&nbsp;</td>";
            
            echo "<td>".$at['users']['balance']."&nbsp;</td>";
            echo "<td>".$at['0']['sm']."&nbsp;</td>";
			
                        if($at['ret']['toShow'] == 0)
                            echo "<td><a id=\"ret_".$at['ret']['id']."\" href=\"javascript:void(0);\" title=\"Revert deletion of this retailer \" onclick=\"delRetailer('r',".$at['ret']['id'].",'revert');\">Revert</a></td>" ;
                        else 
                            echo "<td>-</td>";
			echo "<td>";
			echo '<select class="form-control" name="rental_ret" id="rental_ret" onChange="shiftRental('.$at['ret']['id'].',this,'.$at['ret']['mobile'].','.$at['ret']['rental_flag'].')">';
			if($at['ret']['rental_flag'] == 0){
				echo '<option value="0" selected>Kit</option>';
				echo '<option value="1">Rental</option>';
			}
			else if($at['ret']['rental_flag'] == 1 || $at['ret']['rental_flag'] == 2){
				echo '<option value="0">Kit</option>';
				echo '<option value="1" selected>Rental</option>';
			}
			echo '</select>';
			echo "</td>";
			
			echo "<td>";
			
			echo '<select class="form-control" name="block_salesmanDD" id="block_salesmanDD" onChange="retEnable('.$at['ret']['id'].',this,'.$at['ret']['block_flag'].')">';
			if($at['ret']['block_flag'] == '0'){
				echo '<option value="0" selected>None</option>';
				echo '<option value="1">Partially Blocked</option>';
				echo '<option value="2">Fully Blocked</option>';
			}
			else if($at['ret']['block_flag'] == '1'){
				echo '<option value="0">None</option>';
				echo '<option value="1" selected>Partially Blocked</option>';
				echo '<option value="2">Fully Blocked</option>';
			}
			if($at['ret']['block_flag'] == '2'){
				echo '<option value="0">None</option>';
				echo '<option value="1">Partially Blocked</option>';
				echo '<option value="2" selected>Fully Blocked</option>';
			}
			
			echo '</select>';
			echo "</td>";
		
			echo "<td>";
				echo '<select class="form-control" name="maintenance_salesmanDD" id="maintenance_salesmanDD" onChange="saveMaintenanceSm('.$at['ret']['id'].',this,'.$at['ret']['maint_salesman'].')">';
					echo '<option value="0">None</option>';
					foreach($salesmenList as $d)
					{		
								$sel = '';
								if($at['ret']['maint_salesman'] == $d['salesmen']['id'])
								$sel = 'selected';
								
						 		echo "<option ".$sel." value='".$d['salesmen']['id']."' >".$d['salesmen']['name']." (".$d['salesmen']['mobile'].")</option>";
						 		
					}
												
					
			echo '</select>';
			echo "</td>";
			
			echo "<td>";
				echo '<select data-retailer-id = "'.$at['ret']['id'].'" class="form-control" name="distDD" id="distDD" onChange="changeDistributor('.$at['ret']['id'].',this,'.$at['users']['balance'].','.$at['ret']['parent_id'].')">';
					foreach($distList as $d)
					{		
								$sel = '';
								if($at['ret']['parent_id'] == $d['distributors']['id'])
								$sel = 'selected';
								
						 		echo "<option ".$sel." value='".$d['distributors']['id']."' >".$d['distributors']['company']."</option>";
						 		
					}
												
					
			echo '</select>';
			echo "</td>";
		
			echo "</tr>";
			
			$amtTran = $amtTran + 	$at['0']['sm'];
            if(isset($at['users']['balance']))
			$amtBal = $amtBal + $at['users']['balance'];
			//$amtColl = $amtColl + 	$amountCollected[$at['st']['ref2_id']];
          // if(isset($setupCollected[$at['st']['ref2_id']]))
			//$setupColl = $setupColl + 	$setupCollected[$at['st']['ref2_id']];
           // if(isset($amountCollected[$at['st']['ref2_id']]['average']))
			//$avg1 = $avg1 + $amountCollected[$at['st']['ref2_id']]['average'];
           // if(isset($amountCollected[$at['st']['ref2_id']]['average1']))
			//$avg2 = $avg2 + $amountCollected[$at['st']['ref2_id']]['average1'];			
			$i++;
		}
		echo "<tr><td colspan='6' align='right'><b>Total</b>&nbsp;</td><td colspan='2'><b>".$amtBal."</b></td></tr>";
        
	?>
</table>
<div class="row">
    <div class="col-md-7">
    </div>
    <div class="col-md-5 text-right">
            <?php echo $this->element('pagination');?>
    </div>
</div>
<?php } else { ?>
            <center>
                <div class="row">
                    <h2>No Retailers Found!</h2>
                    <a href="/panels/retColl">Back</a>
                </div>
            </center>
        <?php } ?>
<!--<script type="text/javascript" src="https://www.google.com/jsapi"></script>--> 
<!--<script src="/boot/js/jquery-2.0.3.min.js"></script>
<script>$.noConflict();</script>-->
<!--<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>-->
<script>    
    //select all checkboxes
    $( document ).ready(function() {
        $("#select_all").change(function(){  //"select all" change 
            $(".retailer-select").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
        });

        //".checkbox" change 
        $('.retailer-select').change(function(){ 
            //uncheck "select all", if one of the listed checkbox item is unchecked
            if(false == $(this).prop("checked")){ //if this item is unchecked
                $("#select_all").prop('checked', false); //change "select all" checked status to false
            }
            //check "select all" if all checkbox items are checked
            if ($('.retailer-select:checked').length == $('.retailer-select').length ){
                $("#select_all").prop('checked', true);
            }
        });
    });
    
    /**
     * this will open the modal only if atleast 1 retailer is selected 
     * and retailers balance should be less than 10
     */
    function openShiftMultipleRetailersModal(){
        var group_id = '<?php echo $_SESSION['Auth']['User']['group_id']; ?>';
        var show_modal = false;
        var retailer_counter = 0;
        $('.retailer-select').each(function(key,checkbox){
            if( $(checkbox).is(':checked') ){
                show_modal = true;
                if($(checkbox).attr('data-retailer-balance') > 500 && group_id != '25'){
                    retailer_counter++;
                }
            }
        });
        if(!show_modal){
            alert('Select at least one retailer to shift.');
            return false;
        }
        
        if( retailer_counter == 0 ){
            $('#shift_mltiple_retailers_modal').modal('toggle');
        } else {
            alert('Selected retailers should have balance less than or equal to 500');
            return false;
        }
    }
    /**
     * 
     * this will update the distributor for selected retailers
     */
    function changeDistributorForMultipleRetailers(){ 
        var group_id = '<?php echo $_SESSION['Auth']['User']['group_id']; ?>';
        var distributor = $('#distributors_for_multiple_retailers').val();
        var retailers = [];
        $('.retailer-select').each(function(key,checkbox){
            if( ($(checkbox).is(':checked') && $(checkbox).attr('data-retailer-balance') <= 500) || ($(checkbox).is(':checked') && $('checkbox').attr('data-retailer-balance') > 500 && group_id == '25') ){
                retailers.push($(checkbox).attr('data-retailer-id'));
            }
        });
        var confirm_shift =confirm("Please check if retailer pending is cleared from last distributor. If everything is ok, go ahead");
	if(confirm_shift){
            $.ajax({
                type: 'POST',
                url: '/panels/changeDistributorForMultipleRetailers',
                dataType: 'json',
                data: {
                   retailers: retailers,
                   distributor: distributor
                },
                error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
                },
                success: function(response) {
                    if(response != ''){
                        $.each(response.success_shifts,function(retailer_id,retailer_name){
                            $('select[data-retailer-id = "'+retailer_id+'"]').val(distributor);
                        });
                    }
                    $(".retailer-select").prop('checked',false);
                    alert('Done');
                }
            });
		
	} else {
            return false;
        }
    }
    
</script>