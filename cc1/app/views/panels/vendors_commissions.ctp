<style>
.table td, .table th {
   text-align: center;   
}
.loading {
	width: 100%;
	height: 100%;
	z-index: 999;
	position: absolute;
	top: 0px;
	left: 0px;
	opacity: 0.3;
	background: none repeat scroll 0% 0% #939393;
}

.loading-container .loading-content {
	background: none repeat scroll 0% 0% #FFF;
	border: 5px solid #AAA;
	left: 40%;
	top: 50px;
	position: absolute;
	text-align: center;
	vertical-align: middle;
	z-index: 999;
	padding: 10px 50px;
}

.loading-container .loading-content img {
	vertical-align: middle;
}

.loading-container .loading-content {
	text-align: center;
}
.down {
	background-color: #f97777;
}
tr:hover.down {
	background-color: #f97777;
}
</style>
<script>
$(document).ready(function(){ 
    $('#circles_yes').multipleSelect({ 
        selectAll: false, 
        width: 380, 
        multipleWidth: 170, 
        multiple: true, 
        placeholder: "Select circles"
    });
    $('#circles_no').multipleSelect({ 
        selectAll: false, 
        width: 380, 
        multipleWidth: 170, 
        multiple: true, 
        placeholder: "Select circles"
    });

    $('#circles_yes').change(function(){
    	var values = $('#circles_yes').multipleSelect('getSelects');
    	console.log(values);
	    $("#circles_no > option").each(function() {
	        if(values.indexOf(this.value) == -1){
	        	$(this).attr('disabled', false);
	        }    
	        else
	        	$(this).attr('disabled', true);
        	console.log(values.indexOf(this.value));
// 	        console.log(this.text + ' ' + this.value); 
	    });  
	    $('#circles_no').multipleSelect('refresh');
    });

	$('#circles_no').change(function(){
		var values = $('#circles_no').multipleSelect('getSelects');
	    $("#circles_yes > option").each(function() {
	        if(values.indexOf(this.value) == -1){
				$(this).attr('disabled', false);
	        }    
	        else
	        	$(this).attr('disabled', true);
// 	        console.log(this.text + ' ' + this.value);
	    });  
	    $('#circles_yes').multipleSelect('refresh');
	});
});

function vendors_commissions(){
   	if($('#vendor_id').val() == "" && $('#product_id').val() == ""){
		alert('You can either select all vendors or all products, not both');
		return false;
	}	
    $('#list_form').submit();
}

function goToPage(page){
    $('#page').val(page);
    vendors_commissions();
}

function add(){
    $('#add_title').html("Add");

    $('#add_vendor_id').val("");
    $('#add_product_id').val("");
    $('#discount_commission').val("");
//     $('#active').attr("checked", false);
//     $('#operator_down').attr("checked", false);
    $('#circle_id').val("");
    $('#circles_yes').multipleSelect("uncheckAll");
    $('#circles_no').multipleSelect("uncheckAll");
        $('#commission_fixed').val("");
	$('#tat_time').val("");
	$('#cap_per_min').val("");
	
    $('#add_modal').modal("show");
}

function edit(vc_id){
    $('#add_title').html("Edit");
    
    var vc = $('#vc_data_' + vc_id);
    $('#vc_id').val(vc.data('vcid'));
    $('#add_vendor_id').val(vc.data('vid'));
    $('#add_product_id').val(vc.data('pid'));
    $('#discount_commission').val(vc.data('dc'));
    
    var isapi=vc.data('isapi');
    
    if(isapi=='0'){
          $('#discount_commission').removeAttr("disabled");
    }
    else{
         $('#discount_commission').attr("disabled", "disabled");
    }
    $('#is_api').val(isapi);
    
//     if(vc.data('active') == 1)
//         $('#active').attr("checked", true);
//     else    
//         $('#active').attr("checked", false);
//     if(vc.data('od') == 1)
//         $('#operator_down').attr("checked", true);
//     else    
//         $('#operator_down').attr("checked", false);
    $('#commission_fixed').val(vc.data('cf'));
    $('#circle_id').val(vc.data('circle'));
    $('#tat_time').val(vc.data('tattime'));
    $('#cap_per_min').val(vc.data('cpm'));
    
    var cy = vc.data('cy').split(',');
    var cn = vc.data('cn').split(',');
    $('#circles_yes').multipleSelect("setSelects", cy);
    $('#circles_no').multipleSelect("setSelects", cn);

    $('#add_modal').modal("show");
}

function hide(vc_id,vid,pid)
{
    var url = '/panels/hide/';
                                var params = {'vid' : vid,'pid': pid};
                                $.ajax({
                                        type:"POST",
                                        url:url,
                                        dataType:"json",
                                        data:params,
                                        success:function(data)
                                        {
                                            if(data.status=='done')
                                                {
                                                    alert("Done");
                                                    $("#hide_"+vc_id).hide();
                                                    $("#show_"+vc_id).show();
                                                   
                                                }
                                        },
                                        error:function()
                                        {
                                            alert("Error");
                                        }
                                    });

}

function show(vc_id,vid,pid)
{
    var url = '/panels/show/';
                                var params = {'vid' : vid,'pid': pid};
                                $.ajax({
                                        type:"POST",
                                        url:url,
                                        dataType:"json",
                                        data:params,
                                        success:function(data)
                                        {
                                            if(data.status=='done')
                                                {
                                                    alert("Done");
                                                    $("#show_"+vc_id).hide();
                                                    $("#hide_"+vc_id).show();

                                                }
                                        },
                                        error:function()
                                        {
                                            alert("Error");
                                        }
                                    });

}


function save(){
	if(validate()){
		var vc_id = $('#vc_id').val();
		var url = "/panels/saveVendorCommission/" + vc_id;
		var circles_yes = $('#circles_yes').multipleSelect("getSelects").join(',');
		var circles_no = $('#circles_no').multipleSelect("getSelects").join(',');
// 		var active, operator_down;
// 		if($('#active').is(':checked')){
// 			active = 1;
// 		}	
// 		else
// 			active = 0;
// 		if($('#operator_down').is(':checked')){
// 			operator_down = 1;
// 		}	
// 		else
// 			operator_down = 0;
		var data = $('#add_form').serialize() + "&cy=" + circles_yes + "&cn=" + circles_no; 
		$('#loader').show();
		$('#save').attr('disabled', true);
		$.post(url, data, function(response){
			$('#save').attr('disabled', false);
			$('#loader').hide();
			$('#add_modal').modal('hide');

			alert('Done');
                                                        window.location.reload();
		});
	}	
}

function validate(){
	if($('#add_vendor_id').val() == ""){
		alert('Select a vendor');
		return false;
	}	
	else if($('#add_product_id').val() == ""){
		alert('Select a product');
		return false;
	}	
//	else if($('#discount_commission').val() == ""){
//		alert('Enter the discount commission');
//		return false;
//	}	
        if($('#commission_fixed').val()<0){
            alert('Commission fixed cannot be less than 0');
		return false;
        }
	else if($('#tat_time').val() < 0){
		alert('Turnaround time cannot be less than 0');
		return false;
	}
	
	return true;
}
</script>
		
<title>Vendor Product Mapping</title>
<div class="panel panel-default">
  <div class="panel-heading">Vendor Product Mapping </div>
  <div class="panel-body">
    <form method="post" id="list_form" role="form">
    <input type="hidden" id="page" name="page" value="<?php echo isset($page) ? $page : 1; ?>" >
    <div class="row" style="margin:20px">
    	<div class="form-group col-lg-5">
    	<label for="vendor_id">Select a vendor: </label>
            <select id="vendor_id" name="vendor_id">
                    <option value="">All</option>
                    <?php foreach($vendors as $v): ?>
                            <option value="<?php echo $v['vendors']['id'] ?>" <?php if($vendor_id == $v['vendors']['id']) echo "selected" ?> >
                                    <?php echo $v['vendors']['company'] ?>
                            </option>
                    <?php endforeach ?>
            </select>
         </div>
         <div class="form-group col-lg-5">
             <label for="product_id" style="width:100px; margin-left :-55px;">Select a product: </label>   
            <select id="product_id" name="product_id">
                    <option value="">All</option>
                    <?php foreach($products as $p): ?>
                            <option value="<?php echo $p['products']['id'] ?>" <?php if($product_id == $p['products']['id']) echo "selected" ?> >
                                    <?php echo $p['products']['name'] ?>
                            </option>
                    <?php endforeach ?>
            </select>
            </div>
            <div class="col-lg-1">
                    <button type="button" class="btn btn-default btn-sm" onclick="vendors_commissions();">Submit</button>
            </div>
           <?php /*<div class="col-lg-1">
                    <button type="button" class="btn btn-primary btn-sm" onclick="add();">Add</button>
            </div>	*/?>
    </div>
    </form>
    <div class="row">
        <table class="table table-striped table-hover table-condensed table-bordered" style="border-collapse:collapse;">
	<tr>
		<th>#</th>
		<th>Vendor</th>
		<th>Product</th>
		<th>Discount commission (%)</th>
                <th>Commission Fixed</th>
		<th>Cap Per Min</th>
		<th>Primary Circle</th>
		<th>Active Circles</th>
		<th>Inactive Circles</th>
		<th>Turnaround Time (Hr.)</th>
		<th>Updated by</th>
                <th>Hide/Show</th>
                <th>Action</th>
	</tr>
        <?php 
        foreach($vendors_commissions as $vc): ?>
        <tr <?php if($vc['vc']['oprDown'] != 0) echo "class='down'" ?>>
            <td id="vc_data_<?php echo $vc['vc']['id'] ?>"
                data-vcid="<?php echo $vc['vc']['id'] ?>"
                data-vid="<?php echo $vc['v']['id'] ?>"
                data-pid="<?php echo $vc['p']['id'] ?>"
                data-dc="<?php echo $vc['vc']['discount_commission'] ?>"
                data-cf="<?php echo $vc['vc']['commission_fixed'] ?>"
                data-cpm="<?php echo $vc['vc']['cap_per_min'] ?>"
                data-circle="<?php echo $vc['vc']['circle'] ?>"
                data-cy="<?php echo $vc['vc']['circles_yes'] ?>"
                data-cn="<?php echo $vc['vc']['circles_no'] ?>"
                data-tattime="<?php echo $vc['vc']['tat_time'] ?>"
                data-isapi="<?php echo $vc[0]['is_api'] ?>"
                data-isdeleted="<?php echo $vc['vc']['is_deleted'] ?>"
            ><?php echo $vc['vc']['id'] ?></td>
            <td><?php echo $vc['v']['company'] ?></td>
            <td><?php echo $vc['p']['name'] ?></td>
            <td><?php echo $vc['vc']['discount_commission'] ?></td>
            <td><?php echo $vc['vc']['commission_fixed'] ?></td>
            <td><?php echo $vc['vc']['cap_per_min'] ?></td>
            <td><?php echo $vc['vc']['circle'] ?></td>
            <td><?php echo $vc['vc']['circles_yes'] ?></td>
            <td><?php echo $vc['vc']['circles_no'] ?></td>
            <td><?php echo $vc['vc']['tat_time'] ?></td>
            <td><?php echo $vc['u']['name'] ?></td>
            
            <?php if(isset($vc['v']['id']) && isset($vc['p']['id'])){?>
            <td style="width: 120px;text-align:center;">
                <button style="<?php if($vc['vc']['is_deleted']==1){ ?>display:none;<?php } ?>" id="hide_<?php echo $vc['vc']['id']; ?>" type="button" class="btn btn-success btn-sm" onclick="hide(<?php echo $vc['vc']['id'] ; ?>,<?php echo $vc['v']['id'] ; ?>,<?php echo $vc['p']['id']; ?>)">Hide</button>
                <button style="<?php if($vc['vc']['is_deleted']==0){ ?>display:none;<?php } ?>" id="show_<?php echo $vc['vc']['id']; ?>" type="button" class="btn btn-success btn-sm" onclick="show(<?php echo $vc['vc']['id'] ; ?>,<?php echo $vc['v']['id'] ; ?>,<?php echo $vc['p']['id']; ?>)">Show</button>
            <?php } ?>
                
            </td> 
            <td><button type="button" class="btn btn-success btn-sm" onclick="edit(<?php echo $vc['vc']['id']; ?>)">Edit</button></td>
        	
        </tr>
        <?php endforeach ?>
        </table>
    </div>
  </div>
</div>
<?php //echo $this->element('pagination');?>

<div class="modal fade" id="add_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="add_title">Add</h4>
      </div>
      <div class="modal-body">
      	<div id="loader" class="loading-container" style="display:none">
			<div class="loading"></div>
			<div class="loading-content" style="top:180px;">
				<img src="/img/ajax-loader-2.gif" alt="">
				<span class="text"></span>
			</div>
		</div>
        <form method="post" id="add_form" class="form-horizontal" role="form">
        	<input type="hidden" id="vc_id" value="" />
                <input type="hidden" id="is_api" value=""  name="is_api"/>
            <div class="form-group">
                <label class="control-label col-sm-4" for="add_vendor_id">Vendor:</label>
                <div class="col-sm-8">
                    <select id="add_vendor_id" name="add_vendor_id" class="col-lg-4" style="width:100%;" disabled="">
                    <option value="">Select a vendor</option>
                    <?php foreach($vendors as $v): ?>
                            <option value="<?php echo $v['vendors']['id'] ?>" >
                                    <?php echo $v['vendors']['company'] ?>
                            </option>
                    <?php endforeach ?>
                  </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="add_product_id">Product:</label>
                <div class="col-sm-8">
                    <select id="add_product_id" name="add_product_id" class="col-lg-4" style="width:100%;" disabled="">
                    <option value="">Select a product</option>
                    <?php foreach($products as $p): ?>
                            <option value="<?php echo $p['products']['id'] ?>" >
                                    <?php echo $p['products']['name'] ?>
                            </option>
                    <?php endforeach ?>
                  </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="discount_commission">Discount commission:</label>
                <div class="col-sm-8">
                    <input   value="0" type="text" class="form-control input-sm" id="discount_commission" name="discount_commission" placeholder="Enter discount commission">
                </div>
            </div>
                <div class="form-group">
                <label class="control-label col-sm-4" for="commission_fixed">Commission Fixed:</label>
                <div class="col-sm-8">
                    <input   value="0" type="text" class="form-control input-sm" id="commission_fixed" name="commission_fixed" placeholder="Enter commission fixed">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="tat_time">Complaint turnaround time:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control input-sm" id="tat_time" name="tat_time" placeholder="Enter complaint turnaround time">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="tat_time">Capacity per minute:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control input-sm" id="cap_per_min" name="cap_per_min" placeholder="Enter capacity per minute">
                </div>
            </div>
<!--             <div class="form-group"> -->
<!--                 <div class="col-sm-offset-4 col-sm-4"> -->
<!--                   <div class="checkbox"> -->
<!--                     <label><input id="active" name="active" type="checkbox"> Active</label> -->
<!--                   </div> -->
<!--                 </div> -->
<!--                 <div class="col-sm-4"> -->
<!--                   <div class="checkbox"> -->
<!--                     <label><input id="operator_down" name="operator_down" type="checkbox"> Operator down</label> -->
<!--                   </div> -->
<!--                 </div> -->
<!--             </div> -->
            <div class="form-group">
                <label class="control-label col-sm-4" for="circle_id">Circle:</label>
                <div class="col-sm-8">
                    <select id="circle_id" name="circle_id" class="col-lg-4" style="width:100%;" disabled="">
                    <option value="">Select a primary circle</option>
                    <?php foreach($circles as $c): ?>
                            <option value="<?php echo $c['mobile_numbering_area']['id'] ?>" >
                                    <?php echo $c['mobile_numbering_area']['area_name'] ?>
                            </option>
                    <?php endforeach ?>
                  </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="circle_yes">Active circles:</label>
                <div class="col-sm-8">
                    <select id="circles_yes" name="circles_yes[]" class="col-lg-4" multiple="multiple" disabled="">
                    <?php foreach($circles as $c): ?>
                            <option value="<?php echo $c['mobile_numbering_area']['id'] ?>" >
                                    <?php echo $c['mobile_numbering_area']['area_name'] ?>
                            </option>
                    <?php endforeach ?>
                  </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="circle_no">Inactive circles:</label>
                <div class="col-sm-8">
                    <select id="circles_no" name="circles_no[]" class="col-lg-4" multiple="multiple" disabled="">
                    <?php foreach($circles as $c): ?>
                            <option value="<?php echo $c['mobile_numbering_area']['id'] ?>" >
                                    <?php echo $c['mobile_numbering_area']['area_name'] ?>
                            </option>
                    <?php endforeach ?>
                  </select>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
          <p style="float: left; font-weight: bold; color: red;">Note : Discount commission can be only updated from inventory panel</p>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="save();" id="save">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->