
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap-responsive.min.css?990' />
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />

<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 

<script>
    
jQuery.noConflict();
jQuery(document).ready(function() {
    jQuery('#disable_operator').click(function(){
       oprEnable(jQuery('#dis_pid').val(),1,jQuery("#disable_msg").val());
    });
    jQuery('#disableOperatorModal')        
        .on('shown', function() {
            // on show            
        });
});

function openOperatorDisableModal(pid,pname){
//        var msg = pname+' Mobile recharge is not available now. It will be available soon.'
        var msg = 'Dear Sir, Operator end se issue hone ke karan, Abhi kuch samay ke liye '+pname+' Operator ke recharge band hai. Asuvidha ke liye khed hai.';
        jQuery("#disable_msg").val(msg);
        jQuery("#modal_operator_name").html(pname);
        jQuery("#dis_pid").val(pid);
        jQuery('#disableOperatorModal')
        .modal('show');
        
}

function setAction(pid){
	var r=confirm("You sure?");
	if(r==true){
                var x = [];
                var y = '';
                jQuery('#ms_'+pid+' div .fstControls div').each(function(index, obj) {
                    x.push($(this).attr('data-value'));
                    y = x.join(',');
                });
                jQuery('#suppliers_'+pid).val(y);
		document.vendors.action="/panels/prodVendor/"+pid+"/"+y;
		document.vendors.submit();
	}
}

function oprEnable(pid,flag,msg){
        
	var r=confirm("You sure?");
        
	if(r==true){
                jQuery("#disable_operator").button('loading');
		var url = '/panels/oprEnable';
                if(flag=='1'){// to enable
                    var params = {'pid' : pid,'flag':flag,'msg': msg};
                }else{// to disable
                    var params = {'pid' : pid,'flag':flag ,'msg': ""};
                }
		
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{	jQuery("#disable_operator").button('reset');
					if(transport.responseText == 'success'){
						//alert('done');
						//window.location.href = "/panels/prodVendor/";
                                                if(flag=="0"){
                                                    $("#enableOperatorSpan"+pid).hide();
                                                    $("#disableOperatorSpan"+pid).show();
                                                }else if(flag=="1"){
                                                    
                                                    $("#disableOperatorSpan"+pid).hide();
                                                    $("#enableOperatorSpan"+pid).show();
                                                }
                                                jQuery('#disableOperatorModal')       
                                                    .modal('hide');
					}else{
						alert('Try again');
					}
				}
		});
		
	}
        
}
	
function disableVendor(pid,prod,vendor){
	var r= "";
	flag = $('hid_'+pid).value;
	if(flag == 0){
		r=confirm("You sure you want to disable?");
	}
	else {
		r=confirm("You sure you want to enable?");
	}
	
	if(r==true){
		var url = '/panels/disableVendor';
		var params = {'pid' : pid,'flag': flag,'product':prod,'vendor':vendor};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					if(transport.responseText.trim() == 'success'){
						if(flag == 0){
							$('ven_'+pid).style.backgroundColor='red';
							$('hid_'+pid).value = 1;
						}
						else {
							$('ven_'+pid).style.backgroundColor='#99ff99';
							$('hid_'+pid).value = 0;
						}
					}else{
						alert('Try again');
					}
				}
		});
		
	}
}
function blockSlab(slab_id,prod_id,status){
	var r= "";
	//flag = $('hid_'+pid).value;
	if(status == 1){
		r=confirm("You sure you want to block?");
	}
	else {
		r=confirm("You sure you want to unblock?");
	}
	
	if(r==true){
		var url = '/panels/blockSlab';
		var params = {'slab_id' : slab_id,'prod_id': prod_id,'status':status};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					if(transport.responseText == 'success'){
						if(status == 1){
							$('slab_'+slab_id+"_"+prod_id).style.backgroundColor='red';
							//$('slab_link_'+slab_id+"_"+prod_id).onClick = "blockSlab("+slab_id+","+prod_id+",0)";
                            jQuery('#slab_link_'+slab_id+"_"+prod_id).attr("onClick","blockSlab("+slab_id+","+prod_id+",0)");
						}
						else {
							$('slab_'+slab_id+"_"+prod_id).style.backgroundColor='#99ff99';
							jQuery('#slab_link_'+slab_id+"_"+prod_id).attr("onClick","blockSlab("+slab_id+","+prod_id+",1)");
						}
					}else{
						alert('Try again');
					}
				}
		});
		
	}
}
function refreshCache(){
	r=confirm("You sure you want to refresh Cache?");
	if(r==true){
		var url = '/panels/refreshCache';
		var params = {};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					if(transport.responseText == 'success'){
						alert('Cache refreshed !!');
					}else{
						alert('You are not authorized!!');
					}
				}
		});
	}
}

function deactiveVendor(pid){
	var r= "";
	var flag = $('hid_vend_'+pid).value;
//        var flag = $('#hid_vend_'+pid).val();
	if(flag == 1){
		r=confirm("You sure you want to disable?");
	}
	else {
		r=confirm("You sure you want to enable?");
	}
	
	if(r==true){
		var url = '/panels/deactivateVendor';
		var params = {'pid' : pid,'flag': flag};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					if(transport.responseText.trim() == 'success'){
						if(flag == 1){
							$('ven_vend_'+pid).style.backgroundColor='red';
							$('hid_vend_'+pid).value = 0;
						}
						else {
							$('ven_vend_'+pid).style.backgroundColor='#99ff99';
							$('hid_vend_'+pid).value = 1;
						}
					}else{
						alert('Try again');
					}
				}
		});
		
	}
}

function autoCheck(id){
	var divid = 'autocheck_'+id;
	var classname = jQuery('#'+divid).attr('class');
	var autocheck = jQuery('#'+divid).is(':checked');
	var oprid = id;
	if(autocheck == true){
	jQuery('.'+classname).prop('checked',true);
	} else {
		jQuery('.'+classname).prop('checked',false);

	}
	var ids = jQuery('.'+classname).map( function(){return jQuery(this).val(); }).get();
	var id = ids.join(',');
	
	
	var alldata = {auto_check: autocheck,
                   oprid: id,
		          }
				 
	jQuery.ajax({
            url: '/panels/updateOperatorFlag',
            type: "POST",
            data: alldata,
            dataType: "html",
            success: function(data) {
              alert('success');

            }
        });
	
	
}

</script>
<meta charset="utf-8">
<link rel="stylesheet" href="/css/fastselect.min.css">
<!--<script src="/js/build.min.js"></script>-->
<!--<link rel="stylesheet" href="/css/fastselect.min.css">-->
<script src="/js/fastselect.standalone.js"></script>
<style>
    .fstElement {
        font-size: 0.7em !important;
    }
</style>

<div id="disableOperatorModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="disableVendorModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-info">Disable Operator <span id="modal_operator_name"></span></h3>
  </div>
  <div class="modal-body">
    <input type="hidden" id="sms_type" value="1" /><br/>
    <input type="hidden" id="dis_pid" value="" /><br/>
    <textarea placeholder="Message" id="disable_msg" name="disable_msg" class="input-xxlarge"></textarea>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button id="disable_operator"  type="button" class="btn btn-danger" data-loading-text="Processing..." data-complete-text="Send">Disable</button>
  </div>
</div>
<!--<div id="enableVendorModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="disableVendorModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel" class="text-info">Enable Operator</h3>
  </div>
  <div class="modal-body">
    <input placeholder="Type" type="hidden" id="sms_type" value="1" /><br/>
    <input type="hidden" id="enb_pid" value="" /><br/>
    <textarea placeholder="Message" id="enable_msg" name="enable_msg" class="input-xxlarge"></textarea>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancle</button>
    <button id="enable_vendor"  type="button" class="btn btn-info" data-loading-text="Processing" data-complete-text="Send">Enable</button>
  </div>
</div>-->
<form name="vendors" method="post" >

<a href="javascript:void()" onclick="refreshCache()">Refresh Cache</a>
<div>  
<?php
foreach($vendors as $c){
	$style = "background-color: #99ff99";
	if($c['vendors']['active_flag'] == 0){
		$style = "background-color: red";
	}
	else if($c['vendors']['active_flag'] == 2){
		$style = "background-color: #c73525";
	}
?>
    <a href="javascript:void(0)" onClick="deactiveVendor(<?php echo $c['vendors']['id']; ?>)"><input id="hid_vend_<?php echo $c['vendors']['id'];?>" type="hidden" value="<?php echo $c['vendors']['active_flag']; ?>"><span id="ven_vend_<?php echo $c['vendors']['id']; ?>" style="<?php echo $style; ?>"><?php echo $c['vendors']['shortForm']; ?></span></a>
     |

<?php } ?>
</div>

<table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered">
	<thead>
		<th>Product Id</th>
		<th>Product Name</th>
		<th>Retailer Margin</th>
		<th>Vendors</th>
		<th>Enable/Disable</th>
                <th>Blocked Slabs</th>
                <th>Suppliers</th>
		<th>Action</th>
	</thead>
	<?php foreach($prods as $p){ ?>
	<tr>
		<td><?php echo $p['products']['id']; ?></td>
		<td><a href="/products/edit?product_id=<?php echo $p['products']['id']; ?>" target="_"><?php echo $p['products']['name']; ?></a></td>
		<td><?php echo $p['slabs_products']['percent']; ?></td>
		<td>
			
		<select name="vendor<?php echo $p['products']['id']; ?>" id="vendor<?php echo $p['products']['id']; ?>">
		<?php $vendors = "";
			foreach($comm as $c){
				if($p['products']['id'] == $c['vendors_commissions']['product_id']){
					$select = '';$title = "";
					if($c['vendors_commissions']['active'] == '1'){
					$select = 'selected';
                                            
					 }
					echo "<option ".$select." value='".$c['vendors_commissions']['id']."'>".$c['vendors']['company']." (".$c['vendors_commissions']['discount_commission'].")</option>";
					$style = "background-color: #99ff99";
					if($c['vendors_commissions']['oprDown'] != 0){
						$style = "background-color: red";
                                                $title = empty($c['users']['name']) ? "" : "Vendor Disabled By ".$c['users']['name'];
					}else{
                                         $title = empty($c['users']['name']) ? "" : "Vendor Enabled By ".$c['users']['name'];
                                        }
					$vendors .= ' | '
                                                . '<a href="javascript:void(0)" title="'.$title.'" onClick="disableVendor('.$c['vendors_commissions']['id'].','.$p['products']['id'].','.$c['vendors_commissions']['vendor_id'].')">'
                                                                                                                                                                . '<input id="hid_'.$c['vendors_commissions']['id'].'" type="hidden" value="'.$c['vendors_commissions']['oprDown'].'"><span id="ven_'.$c['vendors_commissions']['id'].'" style="'.$style.'">'.$c['vendors']['shortForm'].'</span>'
                                                                                                                                                                . '</a>';
				}
			}
			$vendors .= ' |';
		?>
		</select>
		</td>
		<td width="450px"><?php echo $vendors;?></td>
        
            <?php $slabsHtml = "";
			foreach($slabs as $slab){
				//if($p['products']['blocked_slabs'] == $slab['slabs']['id']){
//					$select = '';$title = "";
//					if($c['vendors_commissions']['active'] == '1'){
//					$select = 'selected';
//                                            
//					 }
//					echo "<option ".$select." value='".$c['vendors_commissions']['id']."'>".$c['vendors']['company']." (".$c['vendors_commissions']['discount_commission'].")</option>";
					$style = "background-color: #99ff99";
                    $title=""; 
                    //$select = '';$title = "";
                    $blockedSlabs = explode(",",$p['products']['blocked_slabs']);
					if(in_array($slab['slabs']['id'],$blockedSlabs)){
						$style = "background-color: red";
                        $slabsHtml .= ' | <a href="javascript:void(0)" title="'.$title.'" id="slab_link_'.$slab['slabs']['id'].'_'.$p['products']['id'].'" onClick="blockSlab('.$slab['slabs']['id'].','.$p['products']['id'].',0)"><span id="slab_'.$slab['slabs']['id'].'_'.$p['products']['id'].'" style="'.$style.'">'.$slab['slabs']['name'].'</span></a>';
					}else{
                        $slabsHtml .= ' | <a href="javascript:void(0)" title="'.$title.'" id="slab_link_'.$slab['slabs']['id'].'_'.$p['products']['id'].'" onClick="blockSlab('.$slab['slabs']['id'].','.$p['products']['id'].',1)"><span id="slab_'.$slab['slabs']['id'].'_'.$p['products']['id'].'" style="'.$style.'">'.$slab['slabs']['name'].'</span></a>';
                    }
					
				//}
			}
			$slabsHtml .= ' |';
			
		    if($p['products']['service_id']==1){
			$prodname = explode(' ', $p['products']['name']);
			} else {
				$prodname[0] = $p['products']['id'];
			}
			
		?>
            <td width="100px"><?php echo $slabsHtml;?></td>
            <td width="100px" id="ms_<?php echo $p['products']['id']; ?>">
                    <input type="hidden" id="suppliers_<?php echo $p['products']['id']; ?>" name="suppliers">
                    <select class="multipleSelect" multiple>
                        <?php foreach($inv_suppliers[$p['products']['id']] as $i_v) { ?>
                        <option value="<?php echo $i_v['supplier_id']; ?>" <?php if(in_array($i_v['supplier_id'],$inv_supplier_operator[$p['products']['id']])) { echo "selected"; } ?>><?php echo $i_v['name']; ?></option>
                        <?php } ?>
                    </select>
            </td>
		            <td>
                        <a role="button" class="badge badge-info" onClick="setAction(<?php echo $p['products']['id']; ?>)" href="javascript:void(0)">Change</a>
                        <span id="#disableOperatorSpan<?php  echo $p['products']['id']; ?>" <?php if($p['products']['oprDown'] != '0') echo "style='display:none'"; ?>> <a id="openDisableOperatorModal" href="javascript:void(0)" role="button" class="badge badge-success" onClick="openOperatorDisableModal(<?php echo $p['products']['id'].",'".$p['products']['name']."'"; ?>)" >Disable</a> </span>
                       
                        <span id="#enableOperatorSpan<?php  echo $p['products']['id']; ?>" <?php if($p['products']['oprDown'] == '0') echo "style='display:none'";  ?>><a id="openEnableOperatorModal" href="javascript:void(0)" role="button" class="badge badge-important" onClick="oprEnable(<?php echo $p['products']['id'] ?>,0,'');" >Enable</a>  </span>
						Modem auto-failure<input type="checkbox" name="auto_check" id="autocheck_<?php echo $p['products']['id']; ?>" class='autocheck_<?php echo $prodname[0];  ?>' <?php if($p['products']['auto_check']=='1'){ ?> checked="checked" <?php } ?> value="<?php echo $p['products']['id'] ?>" onclick="autoCheck(this.value);">
                   </td>
<!--             data-toggle="modal"   <td><a href="#disableVendorModal" role="button" class="badge badge-info" data-toggle="modal">SMS</a></td>-->
<!--                <a href="javascript:void(0)" onClick="oprEnable(<?php echo $p['products']['id']; ?>,1)">Disable</a>
                <a href="javascript:void(0)" onClick="oprEnable(<?php echo $p['products']['id']; ?>,0)">Enable</a> -->
        </tr>
	<?php } ?>
</table>
</form>

<script>
//    $('.multipleSelect').fastselect();
    $(function() { $('.attireMainNav').html(''); });
</script>
