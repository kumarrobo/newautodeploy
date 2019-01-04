<link rel="stylesheet" media="screen" href="/boot/css/select2.css">
<link rel="stylesheet" media="screen" href="/boot/css/jquery-clockpicker.min.css">
<style>
    .pagination {
        margin: 0px;
    }
    .row{
        margin-bottom: 20px;
    }
    .input-group-btn .btn{
        font-size: 20px;
    }
    td{
        border-top: 0px !important;
    }
    body{
        font-size: 13px;
    }
/*    input *{
        border-radius: 0 !important;
    }*/
.table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: rgba(253, 245, 154, 0.39);
}
.active-denom{
    color: #fff;
    background-color: #3ab54a;
    border: 1px solid #3ab54a;
    cursor: default;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.inactive-denom{
    color: #fff;
    background-color: #ee1c25;
    border: 1px solid #ee1c25;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.primary-denom{
    color: #fff;
    background-color: #006ebd;
    border: 1px solid #006ebd;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.select2-container--default .select2-selection--single .select2-selection__clear{
    float: none;
    padding: 4px;
    font-size: small;
}
.table-bordered {
    border: none;
}
.retailer-mapped-icon,.distributor-mapped-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    font-size: smaller;
    color: #fff;
    line-height: 20px;
    text-align: center;
    border-color: #fff;
    background: rgba(0, 0, 0, 0.79);
    font-weight: bold;
    display: inline-block;
    margin-bottom: 2px;
}
</style>
<title>Recharge Flow Rules</title>
<?php 


if($this->params['url']['page'] == ''){
    $this->params['url']['page'] = 1;
}
?>
<div class="rechargeflowrules container" style="overflow:auto;">
    
    <div class="row text-left" style="margin: 0px;">
        <!--<div class="col-md-12 text-left" style="color:#999;font-weight: bold;">-->
            <h4><b>Recharge Flow Rules</b></h4><br/>
        <!--</div>-->
<!--        <div class="col-md-3">
            <button onclick="location.href='/rechargeflowrules/add?page=<?php // echo $this->params['url']['page']; ?><?php // echo ($this->params['url']['q'] != '') ? '&q='.$this->params['url']['q'] : ''; ?>'" class="btn btn-success btn-md" type="button">Add New Template</button>
        </div>
        <div class="col-md-6">
            <div>
                <?php // echo $this->element('pagination');?>
            </div>
        </div>
        <div class="col-md-3">
            <form class="navbar-form" role="search">
                <div class="input-group add-on">
                    <input class="form-control" placeholder="Search" name="query" id="query" type="text" value="<?php // echo $this->params['url']['q']; ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick="javascript:if( $.trim($('#query').val()) == '' ){ return false; }goToPage('1');">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>-->
    </div>
    <div class="row padding-top-10">
        <div class="col-md-11">
            <form method="POST" action="/rechargeflowrules/index/" id="searchForm">
                <select id="vendor_filter" multiple="multiple" onchange="$('#vendor').val($('#vendor_filter').val());">
                    <?php foreach($vendors as $key=>$vendor) { ?>
                    <option value="<?php echo $key ?>" <?php if(in_array($key,explode(',',$filter_vendors))) { echo "selected"; } ?>><?php echo $vendor ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="vendors" id="vendor" value="<?php echo isset($filter_vendors) ? $filter_vendors : ''; ?>">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <select id="operator_filter" name="operators" multiple="multiple" onchange="$('#operator').val($('#operator_filter').val());">
                    <?php foreach($operators as $key=>$operator) { ?>
                    <option value="<?php echo $key ?>" <?php if(in_array($key,explode(',',$filter_operators))) { echo "selected"; } ?>><?php echo $operator ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="operators" id="operator" value="<?php echo isset($filter_operators) ? $filter_operators : ''; ?>">
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                <select id="status_filter" name="status" onchange="$('#status').val($('#status_filter').val());">
                    <option value="">All</option>
                    <?php foreach($statuses as $key => $status) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php if(isset($filter_status) && $key == $filter_status) { echo "selected"; } ?>><?php echo $status; ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="status" id="status" value="<?php echo isset($filter_status) ? $filter_status : ''; ?>">
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                <select id="type_filter" name="type" onchange="$('#type').val($('#type_filter').val());">
                    <option value="">All</option>
                    <?php foreach($types as $key => $type) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php if(isset($filter_type) && $key == $filter_type) { echo "selected"; } ?>><?php echo $type; ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="type" id="type" value="<?php echo isset($filter_type) ? $filter_type: ''; ?>">
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                <button type="submit" class="btn btn-success">Search</button>
            </form>
        </div>
        <div class="col-md-1">
            <?php
                $url1 = explode('/',$_SERVER['REQUEST_URI']);
                $url = explode('?',$url1[3]);
                $url[3] = !$url[0] ? 100 : $url[0];
            ?>
            <select style="padding: 6px 8px;"class="form-control" id="ivr_no" name="ivr_no" onchange="javascript:goToPage(1,this.value);">
            <!--<select class="form-control" id="ivr_no" name="ivr_no" onclick="window.location.replace('/rechargeflowrules/index/'+this.value);">-->
                <option <?php if($url[3] == 5) { echo "selected"; } ?>>5</option>
                <option <?php if($url[3] == 10) { echo "selected"; } ?>>10</option>
                <option <?php if($url[3] == 15) { echo "selected"; } ?>>15</option>
                <option <?php if($url[3] == 25) { echo "selected"; } ?>>25</option>
                <option <?php if($url[3] == 50) { echo "selected"; } ?>>50</option>
                <option <?php if($url[3] == 100) { echo "selected"; } ?>>100</option>
            </select>
        </div>
    </div>
   
     <?php 
        $params = $this->Session->read('Message.flash.params');
        unset($params['class']);
        if( count($params) > 0 ){ ?>
            <div id="flashMessage" class="alert alert-dismissable alert-warning">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="click to close">×</a>
                <ul class="list-group">
                    <?php 
                        foreach ($params as $variable_name => $variable_value) { ?>
                            <li class="list-group-item"><?php echo $variable_name.' = '.$variable_value; ?></li>
                        <?php }
                    ?>
                </ul>
            </div>
       <?php  }
        ?>
        <div id="flashMessage" class="message">
            <?php echo $this->Session->flash(); ?>
        </div>
       
        <?php
        if(is_array($recharge_flow_rules) && count($recharge_flow_rules) > 0 ){?>
            <table class="table table-hover table-striped table-striped table-condensed table-responsive" style="font-size: 13px;">
                <tr style="height: 70px;border-bottom: 1px solid #ccc;">
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Vendor</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Operator</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Primary Circle</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Active Circle</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Inactive Circle</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Denomination Circle</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Primary Denominations</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Denomination ON/OFF</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Operation time</th>
                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Retailers</th>
                </tr>
                <tbody id="rules_container">
                    
	<?php
	foreach ($recharge_flow_rules as $rule){ ?>
            <tr id="rule_<?php echo $rule['vendors_commissions']['id']; ?>">
		<td>
                    <span id="vendor_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="vendor_span">
                        <?php echo (array_key_exists($rule['vendors_commissions']['vendor_id'], $vendors)) ? $vendors[$rule['vendors_commissions']['vendor_id']] : '--'; ?>
                    </span>
		</td>
		<td>
                    <span id="operator_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="operator_span">
                            <?php echo (array_key_exists($rule['vendors_commissions']['product_id'], $operators)) ? $operators[$rule['vendors_commissions']['product_id']] : '--'; ?>
                    </span>
		</td>
		<td>
                    <span id="pc_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="pc_span">
                        <?php echo (array_key_exists($rule['vendors_commissions']['circle'], $circles)) ? $circles[$rule['vendors_commissions']['circle']] : '--'; ?>
                    </span>
		</td>
		<td>
                    <span id="ac_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="ac_span">
                        <?php 
                            foreach (explode(',',$rule['vendors_commissions']['circles_yes']) as $circle_id) {
                                echo (array_key_exists($circle_id, $circles)) ? $circles[$circle_id].'<br>' : '--'; 
                            }
                        ?>
                    </span>
		</td>
		<td>
                    <span id="ic_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="ic_span">
                        <?php 
                            foreach (explode(',',$rule['vendors_commissions']['circles_no']) as $circle_id) {
                                echo (array_key_exists($circle_id, $circles)) ? $circles[$circle_id].'<br>' : '--'; 
                            }
                        ?>
                    </span>
		</td>
		
		<td>
                    <span id="dc_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="dc_span">
                        <?php echo (array_key_exists($rule['vendors_commissions']['denom_circle'], $circles)) ? $circles[$rule['vendors_commissions']['denom_circle']] : '--'; ?>
                    </span>
		</td>
                <td>
                    <span id="pd_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="pd_span">
                        <?php 
                            foreach (explode(',',$rule['vendors_commissions']['denom_primary']) as $key => $denom) { 
                                if($denom != ''){ ?>
                                    <span class="primary-denom"><?php echo $denom; ?></span>
                                <?php } else {
                                    echo '--';
                                }
                             }
                        ?>
                    </span>
		</td>
		<td>
                    <span id="do_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="do_span">
                        <?php 
                            foreach (explode(',',$rule['vendors_commissions']['denom_yes']) as $key => $denom) { 
                                if($denom != ''){ ?>
                                    <span class="active-denom"><?php echo $denom; ?></span>
                                <?php } else {
                                    echo '--';
                                }
                             }
                        ?>
                    </span>
                        <br>
                    <span id="doff_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="doff_span">
                        <?php 
                            foreach (explode(',',$rule['vendors_commissions']['denom_no']) as $key => $denom) {
                                if($denom != ''){ ?>
                                    <span class="inactive-denom"><?php echo $denom; ?></span>
                                <?php } else {
                                    echo '--';
                                }
                             }
                        ?>
                    </span>
                    <?php 
//                    if(strpos($rule['vendors_commissions']['denom_yes'], ',')){
//                        foreach (explode(',',$rule['vendors_commissions']['denom_yes']) as $circle_id) {
//                            echo (array_key_exists($circle_id, $circles)) ? $circles[$circle_id].'<br>' : ''; 
//                        }
//                    }
                    ?>
		</td>
		<td>
                    <span id="of_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="of_span">
                        <?php echo 'From: '.date('H:i', strtotime($rule['vendors_commissions']['from_STD'])); ?>
                    </span>
                    <br>
                    <span id="ot_span_<?php echo $rule['vendors_commissions']['id']; ?>" class="ot_span">
                        <?php echo 'To: '.date('H:i',strtotime($rule['vendors_commissions']['to_STD'])); ?>
                    </span>
		</td>
		<?php 
                    
                    
                    
                ?>
		<td class="actions">
                    <?php
                        if($rule['vendors_commissions']['retailer_ids']){ ?>
                        <div class="retailer-mapped-icon" title="Retailers are mapped to this rule.">
                            <span>R</span>
                        </div>
                        <!--<span><small><i>Retailers Mapped</i></small></span><br>-->
                        <?php }
                        if($rule['vendors_commissions']['distributor_ids']){ ?>
                        <div class="distributor-mapped-icon" title="Distributors are mapped to this rule.">
                            <span>D</span>
                        </div>
                                <!--<span><small><i>Distributors Mapped</i></small></span>-->
                        <?php }
                        ?>
                    <button type="button" onclick='javascript:appendNewRuleTemplate(<?php echo $rule['vendors_commissions']['id']; ?>,<?php echo json_encode($rule['vendors_commissions']); ?>);' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#ee1c25;border-radius:0px;">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;Edit
                        
                    </button>
                    
<!--                    <button type="button" onclick='' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#f15a25;border-radius:0px;">
                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;View
                    </button>-->
                    <?php
                        if( $rule['vendors_commissions']['oprDown'] == 0 ){ ?>
                            <button title="click to disable this rule" type="button" onclick='javascript:toggleRule(<?php echo $rule['vendors_commissions']['id']; ?>,0,this,<?php echo $rule['vendors_commissions']['product_id']; ?>)' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#999;border-radius:0px;">
                                <span class="glyphicon" aria-hidden="true"></span>Disable Rule
                            </button>   
                        <?php } else { ?>
                                <button title="click to enable this rule" type="button" onclick='javascript:toggleRule(<?php echo $rule['vendors_commissions']['id']; ?>,1,this,<?php echo $rule['vendors_commissions']['product_id']; ?>)' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#3ab54a;border-radius:0px;">
                                    <span class="glyphicon" aria-hidden="true"></span>Enable Rule
                                </button>
                    <?php     }
                    ?>
                    <?php
                        if( $rule['vendors_commissions']['is_disabled'] ){ ?>
                            <button title="click to enable this rule for mapped retailers" type="button" onclick='javascript:toggleRuleForRetailers(<?php echo $rule['vendors_commissions']['id']; ?>,0,this,<?php echo $rule['vendors_commissions']['product_id']; ?>);' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#3ab54a;border-radius:0px;">
                                <span class="glyphicon" aria-hidden="true"></span>Enable for Retailers
                            </button>
                        <?php } else { ?>
                            <button title="click to disable this rule for mapped retailers" type="button" onclick='javascript:toggleRuleForRetailers(<?php echo $rule['vendors_commissions']['id']; ?>,1,this,<?php echo $rule['vendors_commissions']['product_id']; ?>);' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#999;border-radius:0px;">
                                <span class="glyphicon" aria-hidden="true"></span>Disable Local Area Mapping
                            </button>   
                    <?php     }
                    ?>
                    <input type="hidden" id="is_oprDown_<?php echo $rule['vendors_commissions']['id']; ?>" value="<?php echo $rule['vendors_commissions']['oprDown']; ?>">
                    <input type="hidden" id="is_disabled_<?php echo $rule['vendors_commissions']['id']; ?>" value="<?php echo $rule['vendors_commissions']['is_disabled']; ?>">
                    
		</td>
            </tr>
        <?php }?>
                </tbody>
	</table>
    <hr>

            
        <?php } else { ?>
            <center>
                <div class="row">
                    <h2>No Rules Found!</h2>
                    <a href="/rechargeflowrules">Back</a>
                </div>
            </center>
        <?php }
         ?>
</div>
    <div class="row">
        <div class="col-md-12 text-right" id="rule_add_button_container">
            <button type="button" onclick="javascript:appendNewRuleTemplate(0,[]);" class="btn btn-xs" style="font-size: 11px;color: #fff;background-color:#006ebd;border-radius:0px;">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;New Rule
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
        </div>
        <div class="col-md-5 text-right">
            <?php echo $this->element('pagination');?>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="retailers_list_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 0px;">
            <div class="modal-header"style="background-color:#428bca;color:#fff;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Select retailers</h4>
            </div>
            <div class="modal-body">
                <!--(<small><i>click save rule to save these retailers</i></small>)-->
                <h4 id="selected_text">SELECTED RETAILERS : </h4>
                <div style="height:auto;max-height: 400px;overflow-y: auto;">
                    <table class="table table-bordered table-condensed table-responsive table-striped table-hover selected-retailer-data-container">
                    </table>
                </div>
                <table class="retailer-filter-container" style='width: 100%;margin-bottom: 20px;margin-top: 20px;'>
                    
                    <!--<div class="col-md-2 text-left">-->
                    <td>
                        
                    
                        <select id="distributors" class="form-control" onchange="javascript:getRetailers(this);">
                            <option value=""></option>
                        </select>
                        </td>
                    <!--</div>-->
                    <td>
                    <!--<div class="col-md-2 text-left">-->
                    <select id="retailer_ids" class="form-control" onchange="javascript:filterRetailers()">
                            </select>
                    <!--</div>-->
                        </td>
                        <td>
                    <!--<div class="col-md-2 text-left">-->
                        <select id="retailer_names" class="form-control" onchange="javascript:filterRetailers()">
                            </select>
                    <!--</div>-->
                            </td>
                            <td>
                    <!--<div class="col-md-2 text-left">-->
                        <select id="shop_areas" class="form-control" onchange="javascript:filterRetailers()">
                            </select>
                    <!--</div>-->
                                </td>
                                <td>
                    <!--<div class="col-md-2 text-left">-->
                        <select id="circles" class="form-control" onchange="javascript:filterRetailers()">
                            </select>
                    <!--</div>-->
                                    </td>
                </table>
                
                <div style="height:auto;max-height: 400px;overflow-y: auto;">
                    <table class="table table-bordered table-condensed table-responsive table-striped table-hover retailer-data-container">
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="map_retailer_button" type="button" style="font-size: 11px;color: #fff;background-color:#006ebd;border-radius:0px; " class="btn btn-xs" onclick="javascript:mapRetailers();">SAVE</button>
                <button type="button" style="font-size: 11px;color: #fff;background-color:#f89421;border-radius:0px;" class="btn btn-xs" onclick="javascript:resetRetailers();">RESET</button>
                <button type="button" style="font-size: 11px;color: #fff;background-color:#ee1c25;border-radius:0px;" class="btn btn-xs" data-dismiss="modal">CANCEL</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-md" id="distributors_list_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 0px;">
            <div class="modal-header"style="background-color:#428bca;color:#fff;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Select distributors</h4>
            </div>
            <div class="modal-body">
<!--                <h4 id="selected_text_dist">SELECTED DISTRIBUTORS : </h4>
                <div style="height:auto;max-height: 400px;overflow-y: auto;">
                    <table class="table table-bordered table-condensed table-responsive table-striped table-hover selected-distributor-data-container">
                    </table>
                </div>-->
                
                <select id="distributors_dist" class="form-control" onchange="" multiple>
                    <option value=""></option>
                </select>
            </div>
            <div class="modal-footer">
                <button id="map_distributor_button" type="button" style="font-size: 11px;color: #fff;background-color:#006ebd;border-radius:0px; " class="btn btn-xs" onclick="javascript:mapDistributors();">SAVE</button>
                <!--<button type="button" style="font-size: 11px;color: #fff;background-color:#f89421;border-radius:0px;" class="btn btn-xs" onclick="javascript:resetRetailers();">RESET</button>-->
                <button type="button" style="font-size: 11px;color: #fff;background-color:#ee1c25;border-radius:0px;" class="btn btn-xs" data-dismiss="modal">CANCEL</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="acknowledgement_modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 0px;">
            <div class="modal-header"style="background-color:#428bca;color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><b>Alert !!!</b></h4>
            </div>
            <div class="modal-body">
                <h4 id="selected_text"><center>Rule Saved</center></h4>
            </div>
            <div class="modal-footer">
                <button type="button" style="font-size: 11px;color: #fff;background-color:#1cbc16;border-radius:0px;" class="btn btn-xs" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/boot/js/select2.js"></script>
<script type="text/javascript" src="/boot/js/jquery-clockpicker.min.js"></script>
<script>
    var retailers_to_be_mapped = [];
    var distributors_to_be_mapped = [];
    function checkAll(elm,distributor_id){
        $('.retailer-select[data-distributor-id="'+distributor_id+'"]').each(function(){
            if($(this).parents('tr').is(':visible')){
                $(this).prop('checked', $(elm).prop("checked")); //change all ".checkbox" checked status
            }
        });
    }
    function retailerCheck(elm){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(elm).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.retailer-select:checked').length == $('.retailer-select').length ){
            $("#select_all").prop('checked', true);
        }
    }
    
    function goToPage(page=1,recs=<?php echo $url[3]; ?>){
        
        $('#searchForm').attr('action','/rechargeflowrules/index/'+recs+'?page='+page);
        $('#searchForm').submit();
//        window.location.href = '?page='+$page;
    }
//    var template_index = 1;
    function appendNewRuleTemplate(template_index,rule_details){
        console.log(rule_details);
        // vendors
        var vendor_cell = '<select class="form-control" id="vendor_cell_'+template_index+'" style="width: 150px;" required onchange="javascript:$(\'.help-block-vendor-'+template_index+'\').html(\'\');"><option val=""></option>';
        $.each(JSON.parse('<?php  echo json_encode($vendors); ?>'),function(id,name){
            var selected = '';
            if( (Object.keys(rule_details).length > 0) && rule_details.vendor_id == id ){
                selected = 'selected="selected"';
            }
            vendor_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
        });
        vendor_cell += '</select>';
        
        // operators
        var operator_cell = '<select class="form-control" id="operator_cell_'+template_index+'" style="width: 150px;" required onchange="javascript:$(\'.help-block-operator-'+template_index+'\').html(\'\');"><option val=""></option>';
        $.each(JSON.parse('<?php echo json_encode($operators); ?>'),function(id,name){
            var selected = '';
            if( (Object.keys(rule_details).length > 0) && rule_details.product_id == id ){
                selected = 'selected="selected"';
            }
            operator_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
        });
        operator_cell += '</select>';
        
        // primary circles
        var primary_circle_cell = '<select class="form-control" id="primary_circle_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-pc-'+template_index+'\').html(\'\');"><option val=""></option>';
            $.each(JSON.parse('<?php echo json_encode($circles); ?>'),function(id,name){
                var selected = '';
                if( (Object.keys(rule_details).length > 0) && rule_details.circle == id ){
                    selected = 'selected="selected"';
                }
                primary_circle_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
            });
            primary_circle_cell += '</select>';
            
        // active circles
            var active_circle_cell = '<select class="form-control" multiple id="active_circle_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-ac-'+template_index+'\').html(\'\');">';
            if( Object.keys(rule_details).length > 0 && (rule_details.circles_yes) ){
                var active_circles = rule_details.circles_yes.split(',');
            }
        
            $.each(JSON.parse('<?php echo json_encode($circles); ?>'),function(id,name){
                var selected = '';
                if( (Object.keys(rule_details).length > 0) && $.inArray(id,active_circles) !== -1 ){
                    selected = 'selected="selected"';
                }
                active_circle_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
            });
            active_circle_cell += '</select>';
            
        // inactive circles
            var inactive_circle_cell = '<select class="form-control" multiple id="inactive_circle_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-ic-'+template_index+'\').html(\'\');">';
            if( Object.keys(rule_details).length > 0 && (rule_details.circles_no) ){
                var inactive_circles = rule_details.circles_no.split(',');
            }
            
            $.each(JSON.parse('<?php echo json_encode($circles); ?>'),function(id,name){
                var selected = '';
                if( (Object.keys(rule_details).length > 0) && $.inArray(id,inactive_circles) !== -1 ){
                    selected = 'selected="selected"';
                }
                inactive_circle_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
            });
            inactive_circle_cell += '</select>';
            
        // denomination circles
        var denomination_circle_cell = '<select class="form-control" id="denomination_circle_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-dc-'+template_index+'\').html(\'\');"><option val=""></option>';
            $.each(JSON.parse('<?php echo json_encode($circles); ?>'),function(id,name){
                var selected = '';
                if( (Object.keys(rule_details).length > 0) && rule_details.denom_circle == id ){
                    selected = 'selected="selected"';
                }
                denomination_circle_cell += '<option value="'+id+'" '+selected+'>'+name+'</option>';
            });
            denomination_circle_cell += '</select>';
            
 
            
        // denomination on
//        var denomination_on_data = [
//            {value:10,label:'10',color:'#fff'},
//            {value:20,label:'20',color:'green'},
//            {value:200,label:'200',color:'green'},
//            {value:150,label:'150',color:'green'},
//        ];
        
        var primary_denom = [];
        var primary_denomination_cell = '<select class="form-control" multiple id="primary_denomination_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-pd-'+template_index+'\').html(\'\');">';
        if( Object.keys(rule_details).length > 0 && (rule_details.denom_primary) ){
            primary_denom = rule_details.denom_primary.split(',');
        }
        
//        $.each(JSON.parse('<?php // echo json_encode($primary_denomination_options); ?>'),function(index,denom){
        $.each(primary_denom,function(index,denom){
//            var selected = '';
//            if( (Object.keys(rule_details).length > 0) && $.inArray( String(denom.value),primary_denom) !== -1 ){
//                selected = 'selected="selected"';
//            }
            var selected = 'selected="selected"';
//            primary_denomination_cell += '<option value="'+denom.value+'" '+selected+'>'+denom.label+'</option>';
            primary_denomination_cell += '<option value="'+denom+'" '+selected+'>'+denom+'</option>';
        });
        primary_denomination_cell += '</select>';
        
        var active_denom = [];
        var denomination_on_cell = '<select class="form-control" multiple id="denomination_on_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-do-'+template_index+'\').html(\'\');">';
        if( Object.keys(rule_details).length > 0 && (rule_details.denom_yes) ){
            active_denom = rule_details.denom_yes.split(',');
        }
//        $.each(JSON.parse('<?php // echo json_encode($active_denomination_options); ?>'),function(index,denom){
        $.each(active_denom,function(index,denom){
//            var selected = '';
//            if( (Object.keys(rule_details).length > 0) && $.inArray( String(denom.value),active_denom) !== -1 ){
//                selected = 'selected="selected"';
//            }
            var selected = 'selected="selected"';
//            denomination_on_cell += '<option value="'+denom.value+'" '+selected+'>'+denom.label+'</option>';
            denomination_on_cell += '<option value="'+denom+'" '+selected+'>'+denom+'</option>';
        });
        denomination_on_cell += '</select>';
            
        // denomination off
//        var denomination_off_data = [
//            {id:10,text:'10',color:'red'},
//            {id:20,text:'20',color:'red'},
//            {id:200,text:'200',color:'red'},
//            {id:150,text:'150',color:'red'},
//        ];
            var inactive_denom = [];
            var denomination_off_cell = '<select class="form-control" multiple id="denomination_off_cell_'+template_index+'" required onchange="javascript:$(\'.help-block-doff-'+template_index+'\').html(\'\');">';
            if( Object.keys(rule_details).length > 0 && (rule_details.denom_no) ){
                var inactive_denom = rule_details.denom_no.split(',');
            }
//            $.each(JSON.parse('<?php // echo json_encode($inactive_denomination_options); ?>'),function(index,denom){
            $.each(inactive_denom,function(index,denom){
//                var selected = '';
//                if( (Object.keys(rule_details).length > 0) && $.inArray(String(denom.value),inactive_denom) !== -1 ){
//                    selected = 'selected="selected"';
//                }
                var selected = 'selected="selected"';
//                denomination_off_cell += '<option value="'+denom.value+'" '+selected+'>'+denom.label+'</option>';
                denomination_off_cell += '<option value="'+denom+'" '+selected+'>'+denom+'</option>';
            });
            denomination_off_cell += '</select>';
            
        // from operation time
        var operation_from_time = '';
        if( Object.keys(rule_details).length > 0 && (rule_details.from_STD) ){
            var operation_from = rule_details.from_STD.split(':');
            operation_from_time = operation_from[0]+':'+operation_from[1];
        }
        var operation_from_cell = '<input placeholder="From time" class="form-control input-sm" id="operation_from_cell_'+template_index+'" readonly value="'+operation_from_time+'" required onchange="javascript:$(\'.help-block-of-'+template_index+'\').html(\'\');">';
//        var operation_from_cell = '<select class="form-control" id="operation_from_cell_'+template_index+'"><option val=""></option>';
//            operation_from_cell += '<option val="12:22:23">12:22:23</option>';
//            operation_from_cell += '<option val="12:22:23">12:22:23</option>';
//            operation_from_cell += '<option val="12:22:23">12:22:23</option>';
//            operation_from_cell += '</select>';
            
        // to operation time
        var operation_to_time = '';
        if( Object.keys(rule_details).length > 0 && (rule_details.to_STD) ){
            var operation_to = rule_details.to_STD.split(':');
            operation_to_time = operation_to[0]+':'+operation_to[1];
        }
        var operation_to_cell = '<input placeholder="To time" class="form-control input-sm" id="operation_to_cell_'+template_index+'" readonly value="'+operation_to_time+'" required onchange="javascript:$(\'.help-block-ot-'+template_index+'\').html(\'\');">';
//        var operation_to_cell = '<select class="form-control" id="operation_to_cell_'+template_index+'" ><option val=""></option>';
//            operation_to_cell += '<option val="12:22:23">12:22:23</option>';
//            operation_to_cell += '<option val="12:22:23">12:22:23</option>';
//            operation_to_cell += '<option val="12:22:23">12:22:23</option>';
//            operation_to_cell += '</select>';
            
        
            retailers_to_be_mapped[template_index] = [];
            distributors_to_be_mapped[template_index] = [];
            var rule_template_string = '';
            if( Object.keys(rule_details).length == 0 ){
                rule_template_string = '<tr id="rule_'+template_index+'">';
            } else {
                if(rule_details.retailer_ids){
                    $.each(rule_details.retailer_ids.split(','),function(index,retailer_id){
                        retailers_to_be_mapped[template_index].push(retailer_id);
                    });
                }
                if(rule_details.distributor_ids){
                    $.each(rule_details.distributor_ids.split(','),function(index,distributor_id){
                        distributors_to_be_mapped[template_index].push(distributor_id);
                    });
                }
            }
            
            if( template_index != 0 ){
                rule_details.oprDown = $('#is_oprDown_'+template_index).val();
                rule_details.is_disabled = $('#is_disabled_'+template_index).val();
                
            }
            
            var map_retailer_button = '<button id="map_retailer_button_'+template_index+'" type="button" onclick="javascript:showRetailersDialog('+template_index+');" class="btn btn-xs" style="font-size: 11px;color: #fff;background-color:#f89421;border-radius:0px;">Map Retailers</button>';
            var map_distributor_button = '<button id="map_distributor_button_'+template_index+'" type="button" onclick="javascript:showDistributorsDialog('+template_index+');" class="btn btn-xs" style="font-size: 11px;color: #fff;background-color:#0049b0;border-radius:0px;">Map Distributors</button>';
            var save_rule_button = '<button id="save_rule_button_'+template_index+'" type="button" onclick=\'javascript:saveRule('+template_index+','+JSON.stringify(rule_details)+');\' class="btn btn-xs" style="font-size: 11px;color: #fff;background-color:#006ebd;border-radius:0px;">Save Rule</button>';
            rule_template_string += '<td>'+vendor_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-vendor-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+operator_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-operator-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+primary_circle_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-pc-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+active_circle_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-ac-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+inactive_circle_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-ic-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+denomination_circle_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-dc-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+primary_denomination_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-pd-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+denomination_on_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-do-'+template_index+' with-errors"></span><br>'+denomination_off_cell+'<br><span style="color:#ee1c25;" class="pull-left help-block-doff-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+operation_from_cell+'<span style="color:#ee1c25;" class="pull-left help-block-of-'+template_index+' with-errors"></span><br>'+operation_to_cell+'<span style="color:#ee1c25;" class="pull-left help-block-ot-'+template_index+' with-errors"></span></td>';
            rule_template_string += '<td>'+map_retailer_button+'<br><br>'+map_distributor_button+'<br><br>'+save_rule_button+'</td>';
            
            if( Object.keys(rule_details).length == 0 ){
                rule_template_string += '</tr>';
                $('#rules_container').prepend(rule_template_string);
            } else {
                $('#rule_'+template_index).html(rule_template_string);
            }
        
        
        $("#vendor_cell_"+template_index).select2({
            placeholder: "Vendor",
            dropdownAutoWidth: 'true',
            width:'auto',
            allowClear: true
        });
        $("#operator_cell_"+template_index).select2({
            placeholder: "Operator",
            dropdownAutoWidth: 'true',
            width:'auto',
            allowClear: true
        });
        $("#primary_circle_cell_"+template_index).select2({
            placeholder: "Primary circle",
            dropdownAutoWidth: 'true',
            width:'auto',
            allowClear: true
        });
        $("#active_circle_cell_"+template_index).select2({
            placeholder: "Active circle",
            dropdownAutoWidth: 'true',
            width:'100%'
        });
        $("#inactive_circle_cell_"+template_index).select2({
            placeholder: "Inactive circle",
            dropdownAutoWidth: 'true',
            width:'100%'
        });
        $("#denomination_circle_cell_"+template_index).select2({
            placeholder: "Denomination circle",
            dropdownAutoWidth: 'true',
            width:'auto',
            allowClear: true
        });
        $("#primary_denomination_cell_"+template_index).select2({
            placeholder: "Primary Denominations",
            dropdownAutoWidth: 'true',
            width:'90%',
            tags: true
        });
        $("#denomination_on_cell_"+template_index).select2({
            placeholder: "Enabled denominations",
            dropdownAutoWidth: 'true',
            width:'90%',
            tags: true
//            data:denomination_on_data,
//            formatSelectionCssClass : function(tag, container) {
//                $(container).parent().css({ "background-color": tag.color });
//            }
        });
        $("#denomination_off_cell_"+template_index).select2({
            placeholder: "Disabled denominations",
            dropdownAutoWidth: 'true',
            width:'90%',
            tags: true
//            data:denomination_off_data
        });
        $("#operation_from_cell_"+template_index).clockpicker({
            autoclose: true,
            placement: 'bottom',
        });
//        $("#operation_from_cell_"+template_index).datetimepicker({
//            autoclose: true,
////            pickerPosition:'top-left',
//            startDate: new Date(),
//            width:'auto'
////            pick12HourFormat: true
//        });
        $("#operation_to_cell_"+template_index).clockpicker({
            autoclose: true,
            placement: 'bottom',
        });
//        $("#operation_to_cell_"+template_index).datetimepicker({
//            autoclose: true,
////            pickerPosition:'top-left',
//            startDate: new Date(),
//            width:'auto'
////            pick12HourFormat: true
//        });
        if( Object.keys(rule_details).length == 0 ){
            $('#rule_add_button_container').html('<button type="button" onclick="javascript:removeNewRuleTemplate('+template_index+');" class="btn btn-xs" style="font-size: 11px;color: #fff;background-color:#ee1c25;border-radius:0px;"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;Remove Rule</button>');
        }
    }
    function removeNewRuleTemplate(template_index){
        $('#rule_'+template_index).remove();
        $('#rule_add_button_container').html('<button type="button" onclick="javascript:appendNewRuleTemplate(0,[]);" class="btn btn-xs" style="font-size: 11px;color: #fff;background-color:#006ebd;border-radius:0px;"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;New Rule</button>');
    }
    function validateTemplate(template_index){
        var vendor = $('#vendor_cell_'+template_index).val();
        var allow = true;
        if(!vendor){
            $('.help-block-vendor-'+template_index).html('<small>*required</small>');
            allow = false;
        }
        var operator = $('#operator_cell_'+template_index).val();
        if(!operator){
            $('.help-block-operator-'+template_index).html('<small>*required</small>');
            allow = false;
        }
        var primary_circle = $('#primary_circle_cell_'+template_index).val();
        if(!primary_circle){
//            $('.help-block-pc-'+template_index).html('<small>*required</small>');
//             allow = false;
        }
        var active_circle = $('#active_circle_cell_'+template_index).val();
        if(!active_circle){
//            $('.help-block-ac-'+template_index).html('<small>*required</small>');
//            allow = false;
        }
        var inactive_circle = $('#inactive_circle_cell_'+template_index).val();
        if(!inactive_circle){
//            $('.help-block-ic-'+template_index).html('<small>*required</small>');
//            allow = false;
        }
        if(primary_circle && inactive_circle && inactive_circle.indexOf(primary_circle) >= 0){
            $('.help-block-pc-'+template_index).html("<small>* Primary Circle can't be inactive</small>");
            allow = false;
        }
        if(active_circle && inactive_circle && active_circle.join(',') == inactive_circle.join(',')){
            $('.help-block-ac-'+template_index).html("<small>* Active circle and inactive circle both can't be same</small>");
            allow = false;
        }
        var denomination_circle = $('#denomination_circle_cell_'+template_index).val();
        if(!denomination_circle){
//            $('.help-block-dc-'+template_index).html('<small>*required</small>');
//             allow = false;
        }
        if(denomination_circle && inactive_circle && inactive_circle.indexOf(denomination_circle) >= 0){
            $('.help-block-dc-'+template_index).html("<small>* Denomination Circle can't be inactive</small>");
            allow = false;
        }
        var primary_denomination = $('#primary_denomination_cell_'+template_index).val();
        if(primary_denomination){
            $.each(primary_denomination,function(key,denom){
                if(isNaN(denom)){
                    $('.help-block-pd-'+template_index).html('<small>Invalid Denomination</small>');
                    allow = false;
                    return false;
                }
                if(parseInt(denom) < 0){
                    $('.help-block-pd-'+template_index).html('<small>Denomination can not ne -ve</small>');
                    allow = false;
                    return false;
                }
            });
        }
        var denomination_on = $('#denomination_on_cell_'+template_index).val();
        if(denomination_on){
            $.each(denomination_on,function(key,denom){
                if(isNaN(denom)){
                    $('.help-block-do-'+template_index).html('<small>Invalid Denomination</small>');
                    allow = false;
                    return false;
                }
                if(parseInt(denom) < 0){
                    $('.help-block-do-'+template_index).html('<small>Denomination can not ne -ve</small>');
                    allow = false;
                    return false;
                }
            });
        }
//        if(!denomination_on){
//            $('.help-block-do-'+template_index).html('<small>*required</small>');
//             allow = false;
//        }
        var denomination_off = $('#denomination_off_cell_'+template_index).val();
        if(denomination_off){
            $.each(denomination_off,function(key,denom){
                if(isNaN(denom)){
                    $('.help-block-doff-'+template_index).html('<small>Invalid Denomination</small>');
                    allow = false;
                    return false;
                }
                if(parseInt(denom) < 0){
                    $('.help-block-doff-'+template_index).html('<small>Denomination can not ne -ve</small>');
                    allow = false;
                    return false;
                }
            });
        }
//        if(!denomination_off){
//            $('.help-block-doff-'+template_index).html('<small>*required</small>');
//             allow = false;
//        }
        if(primary_denomination && denomination_off){
            for (var i in denomination_off) {
                if(primary_denomination.indexOf(denomination_off[i]) >= 0) {
                    $('.help-block-doff-'+template_index).html("<small>* Denomination Off can't be Primary Denominations</small>");
                    allow = false;
                }
            }
        }
        if(denomination_on && denomination_off && denomination_on.join(',') == denomination_off.join(',')){
            $('.help-block-doff-'+template_index).html("<small>* Deno. On and Deno. Off both can't be same</small>");
             allow = false;
        }
        var operation_from = $('#operation_from_cell_'+template_index).val();
        if(!operation_from){
//            $('.help-block-of-'+template_index).html('<small>*required</small>');
//             allow = false;
        }
        var operation_to = $('#operation_to_cell_'+template_index).val();
        if(!operation_to){
//            $('.help-block-ot-'+template_index).html('<small>*required</small>');
//             allow = false;
        }
        if(!allow){
            return false;
        }
        return true;
    }
    
    function showDistributorsDialog(index){
        
        
        if(!validateTemplate(index)){
            return false;   
        }
//        $('.retailer-data-container').html('');
//        $('#distributors').val('').trigger("change");
//        $('#map_retailer_button').attr('onclick','javascript:mapRetailers('+index+');');
//        $('#retailers_list_modal').modal('toggle');
        var url = '/rechargeflowrules/getDistributors';
        $.ajax({
            type: 'POST',
            url: url,
            dataType:'json',
            data: '',
            error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
            },
            success: function(response) {
                var distributors = '';
                var distributor_array = [];
                $.each(response,function(key,distributor){
                    var selected = '';
                    if( (distributors_to_be_mapped[index]) && ( distributors_to_be_mapped[index].length > 0 ) && ($.inArray(distributor.distributors.id,distributors_to_be_mapped[index]) != -1) ){
                        selected = 'selected="selected"';
                    }
                    distributors += '<option value="'+distributor.distributors.id+'" '+selected+'>'+distributor.distributors.company+'</option>';
                    distributor_array[distributor.distributors.id] = distributor.distributors.company;
                });
                $('#distributors_dist').html(distributors);
                $("#distributors_dist").select2({
                    placeholder: "Select Distributors",
                    dropdownAutoWidth: 'true',
                    width: 'auto',
                    allowClear: true
                });
                

                $('#map_distributor_button').attr('onclick','javascript:mapDistributors('+index+');');
                $('#distributors_list_modal').modal('toggle');
            }
        });
    
    }
    function showRetailersDialog(index){
        
        if(!validateTemplate(index)){
            return false;   
        }
//        $('.retailer-data-container').html('');
//        $('#distributors').val('').trigger("change");
//        $('#map_retailer_button').attr('onclick','javascript:mapRetailers('+index+');');
//        $('#retailers_list_modal').modal('toggle');
        var url = '/rechargeflowrules/getDistributors';
        $.ajax({
            type: 'POST',
            url: url,
            dataType:'json',
            data: '',
            error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
            },
            success: function(response) {
                var distributors = '';
                var distributor_array = [];
                $.each(response,function(index,distributor){
                    distributors += '<option value="'+distributor.distributors.id+'" >'+distributor.distributors.company+'</option>';
                    distributor_array[distributor.distributors.id] = distributor.distributors.company;
                });
                $('#distributors').html(distributors);
                $("#distributors").select2({
                    placeholder: "Distributor",
                    dropdownAutoWidth: 'true',
                    width: 'auto',
                    allowClear: true
                });
                $("#retailer_ids").select2({
                    placeholder: "Retailer ID",
                    dropdownAutoWidth: 'true',
                    width: 'auto',
                    allowClear: true
                });
                $("#retailer_names").select2({
                    placeholder: "Retailer Name",
                    dropdownAutoWidth: 'true',
                    width: 'auto',
                    allowClear: true
                });
                $("#shop_areas").select2({
                    placeholder: "Shop Area",
                    dropdownAutoWidth: 'true',
                    width: 'auto',
                    allowClear: true
                });
                $("#circles").select2({
                    placeholder: "Circle",
                    dropdownAutoWidth: 'true',
                    width: 'auto',
                    allowClear: true
                });
                $('#selected_text').hide();
                $('.selected-retailer-data-container').html('');
                if( (retailers_to_be_mapped[index]) && ( retailers_to_be_mapped[index].length > 0 ) ){
                    getRetailers(retailers_to_be_mapped[index].join(),1,distributor_array); // mode 1 means get selected retailers to show on modal opening
                }
                
                $('.retailer-data-container').html('');
                $('#distributors').val('').trigger("change");
                $('#map_retailer_button').attr('onclick','javascript:mapRetailers('+index+');');
                $('#retailers_list_modal').modal('toggle');
            }
        });
    }
    
//    function getDistributors(selected_dists,dists){
//        var dist_table_rows = '';
//            dist_table_rows += '<thead>\n\
//                                    <tr><th style="background-color:#006ebd;color: #fff;vertical-align: middle;" ></th>\n\
//                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;" >Distributor</th></tr></thead>';
//                      
//                      
//            $.each($.unique(selected_dists),function(index,selected_dist){
//                dist_table_rows += '<tr>';
//                dist_table_rows += '<td><input data-distributor-id="'+selected_dist+'" class="distributor-select" type="checkbox" name="select" onchange="javascript:retailerCheck(this)" checked="checked"></td>';
//                dist_table_rows += '<td>'+dists[selected_dist]+'</td>';
//                dist_table_rows += '</tr>';
//                dist_table_rows += '</tbody>';
//            });  
//
//            $('#selected_text_dist').show();
//            $('.selected-distributor-data-container').html(dist_table_rows);
//    }
    function getRetailers(element,mode,distributors){
        var data = '';
        if( mode == 1 ){
            data = {retailers : element};
        } else {
            $('.retailer-data-container').html('<center><img src="/boot/images/loading.gif" /></center>');
            var distributor_id  = $(element).val();
            if(!distributor_id){
                $('.retailer-data-container').html('');
                $('#retailer_ids').html('');
                $('#retailer_names').html('');
                $('#shop_areas').html('');
                $('#circles').html('');
                return false;
            }
            var distributor_name  = $(element).text();
            data = {distributor_id : distributor_id};
        }
        
        var url = '/rechargeflowrules/getRetailers';
        $.ajax({
            type: 'POST',
            url: url,
            dataType:'json',
            data: data,
            beforeSuccess:function(){
//                $('.retailer-data-container').html('<img src="/boot/images/loading.gif" />');
            },
            error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
            },
            success: function(response) {
                if( Object.keys(response).length > 0 ){
                    var retailer_table_rows = '';
//                    if( mode != 1 ){
//                        retailer_table_rows += '<caption><h4>'+distributor_name+'</h4></caption>';
//                    }
                    retailer_table_rows += '<thead>\n\
                                            <tr><th style="background-color:#006ebd;color: #fff;vertical-align: middle;" >';
                    if( mode != 1 ){
                        retailer_table_rows+= '<input id="select_all" type="checkbox" onchange="javascript:checkAll(this,\''+response[0].ret.parent_id+'\')" />';
                    }          
                    retailer_table_rows += '</th>';
                    if( mode == 1 ){
                        retailer_table_rows += '<th style="background-color:#006ebd;color: #fff;vertical-align: middle;" >Distributor</th>';
                    }
                    retailer_table_rows += '<th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Retailer ID</th>\n\
                                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Retailer Name</th>\n\
                                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Shop Area</th>\n\
                                    <th style="background-color:#006ebd;color: #fff;vertical-align: middle;">Circle</th>\n\
                                </tr></thead><tbody>';
                    if( mode != 1 ){
                        var retailer_ids = '<option value=""></option';
                        var retailer_names = '<option value=""></option';
                        var shop_areas = '<option value=""></option';
                        var circles = '<option value=""></option';
                    }
                    $.each(response,function(index,retailer){
                        
                        if( mode != 1 ){
                            if(retailer.ret.id){
//                                console.log(retailer.ret.id);
                                retailer_ids+= '<option value="'+retailer.ret.id+'">'+retailer.ret.id+'</option>';
                            }
                            if(retailer.ret.name){
                                retailer_names+= '<option value="'+retailer.ret.name+'">'+retailer.ret.name+'</option>';
                            }
                            if(retailer.ret.area){
                                shop_areas+= '<option value="'+retailer.ret.area+'">'+retailer.ret.area+'</option>';
                            }
                            if(retailer.loc.circle){
                                circles+= '<option value="'+retailer.loc.circle+'">'+retailer.loc.circle+'</option>';
                            }
                        }
                        retailer_table_rows += '<tr>';
                        if( mode == 1 ){
                            retailer_table_rows += '<td><input data-retailer-id="'+retailer.ret.id+'" class="retailer-select" type="checkbox" name="select" onchange="javascript:retailerCheck(this)" checked="checked"></td>';
                        } else {
                            retailer_table_rows += '<td><input data-distributor-id = "'+retailer.ret.parent_id+'" data-retailer-id="'+retailer.ret.id+'" class="retailer-select" type="checkbox" name="select" onchange="javascript:retailerCheck(this)"></td>';
                        }
                        if( mode == 1 ){
                            retailer_table_rows += '<td>'+distributors[retailer.ret.parent_id]+'</td>';
                        }
                        
                        retailer_table_rows += '<td class="retailerid_cell">'+retailer.ret.id+'</td>';
                        retailer_table_rows += '<td class="retailername_cell">'+retailer.ret.name+'</td>';
                        if(retailer.ret.area){
                            retailer_table_rows += '<td class="shoparea_cell">'+retailer.ret.area+'</td>';
                        } else {
                            retailer_table_rows += '<td class="shoparea_cell">--</td>';
                        }
                        
                        if(retailer.loc.circle){
                            retailer_table_rows += '<td class="circle_cell">'+retailer.loc.circle+'</td>';
                        } else {
                            retailer_table_rows += '<td class="circle_cell">--</td>';
                        }
                        
                        
                        retailer_table_rows += '</tr>';
                    });
                    
                    if( mode != 1 ){
                        $('#retailer_ids').html(retailer_ids);
                        $('#retailer_names').html(retailer_names);
                        $('#shop_areas').html(shop_areas);
                        $('#circles').html(circles);

                        $("#retailer_ids option").each(function(){
                            $(this).siblings("[value='"+ this.value+"']").remove();
                        });
                        $("#retailer_names option").each(function(){
                            $(this).siblings("[value='"+ this.value+"']").remove();
                        });
                        $("#shop_areas option").each(function(){
                            $(this).siblings("[value='"+ this.value+"']").remove();
                        });
                        $("#circles option").each(function(){
                            $(this).siblings("[value='"+ this.value+"']").remove();
                        });
                    }
                    retailer_table_rows += '</tbody>';
                    if( mode == 1 ){
                        $('#selected_text').show();
                        $('.selected-retailer-data-container').html(retailer_table_rows);
                    } else {
                        $('.retailer-data-container').html(retailer_table_rows);
                    }
                } else {
                    $('.retailer-data-container').html('<center><h4>No retailers found for this distributor!</h4></center>');
                    $('#retailer_ids').html('');
                    $('#retailer_names').html('');
                    $('#shop_areas').html('');
                    $('#circles').html('');
                }
            }
        });
    }
    
    function saveRule(index,rule_details){
//    res = validateTemplate(index);
//    alert(res);return false;
        if(!validateTemplate(index)){
            return false;   
        }
       
        if( (retailers_to_be_mapped[index]) && (retailers_to_be_mapped[index].length == 0) ){
//            alert('Please map retailers');
//            return false;
            if(!confirm('Are you sure you don\'t want to map retailers to this rule ?')){
                return false;
            }
        }
        var vendor = $('#vendor_cell_'+index).val();
        var operator = $('#operator_cell_'+index).val();
        var primary_circle = $('#primary_circle_cell_'+index).val();
        var active_circle = $('#active_circle_cell_'+index).val();
        var inactive_circle = $('#inactive_circle_cell_'+index).val();
        var denomination_circle = $('#denomination_circle_cell_'+index).val();
        var primary_denomination = $('#primary_denomination_cell_'+index).val();
        var denomination_on = $('#denomination_on_cell_'+index).val();
        var denomination_off = $('#denomination_off_cell_'+index).val();
        var operation_from = $('#operation_from_cell_'+index).val().split(':');
        var operation_to = $('#operation_to_cell_'+index).val().split(':');
        var from_date = new Date();
        from_date.setHours(operation_from[0],operation_from[1]);
//
        var to_date = new Date();
        to_date.setHours(operation_to[0],operation_to[1]);

        var from = new Date(from_date);
        var to = new Date(to_date);
        if(from.getHours()+":"+from.getMinutes() != "0:0" && to.getHours()+":"+to.getMinutes() != "0:0" && from_date >= to_date){
            alert('Start time can not be greater than or equal to end time.');
            return false;
        }
        
        var url = '/rechargeflowrules/saveRule';
        $.ajax({
            type: 'POST',
            url: url,
            dataType:'json',
            data: {
                retailers_to_be_mapped : retailers_to_be_mapped[index],
                distributors_to_be_mapped : distributors_to_be_mapped[index],
                vendor : vendor,
                operator : operator,
                primary_circle : primary_circle,
                active_circle : active_circle,
                inactive_circle : inactive_circle,
                denomination_circle : denomination_circle,
                primary_denomination : primary_denomination,
                denomination_on : denomination_on,
                denomination_off : denomination_off,
                operation_from : operation_from[0]+':'+operation_from[1],
                operation_to : operation_to[0]+':'+operation_to[1],
                index:index
            },
            beforeSuccess:function(){
            },
            error: function() {
                alert('Could not save the rule. Please try again! Note: There can be only one rule for vendor and operator.Try changing operator or vendor.');
                return false;
//                   $('#info').html('<p>An error has occurred</p>');
            },
            success: function(rule_id) {
                
                $('#acknowledgement_modal').modal('toggle');
                if(rule_id){
                    
                    if(!Object.keys(rule_details).length){
                        rule_details = {};
                    }
                    rule_details.vendor_id = vendor;
                    rule_details.product_id = operator;
                    rule_details.circle = primary_circle;
                    if(active_circle != null) {
                        rule_details.circles_yes = active_circle.join(',');
                    } else {
                        rule_details.circles_yes = "";
                    }
                    if(inactive_circle != null) {
                        rule_details.circles_no = inactive_circle.join(',');
                    } else {
                        rule_details.circles_no = "";
                    }
                    rule_details.denom_circle = denomination_circle;
                    if(primary_denomination != null) {
                        rule_details.denom_primary = primary_denomination.join(',');
                    } else {
                        rule_details.denom_primary = "";
                    }
                    if(denomination_on != null) {
                        rule_details.denom_yes = denomination_on.join(',');
                    } else {
                        rule_details.denom_yes = "";
                    }
                    if(denomination_off != null) {
                        rule_details.denom_no = denomination_off.join(',');
                    } else {
                        rule_details.denom_no = "";
                    }
                    rule_details.from_STD = operation_from[0]+':'+operation_from[1];
                    rule_details.to_STD = operation_to[0]+':'+operation_to[1];
                    rule_details.retailer_ids = '';
                    rule_details.distributor_ids = '';
                    if( (retailers_to_be_mapped[index]) && (retailers_to_be_mapped[index].length > 0) ){
                        rule_details.retailer_ids = retailers_to_be_mapped[index].join(',');
                    }
                    if( (distributors_to_be_mapped[index]) && (distributors_to_be_mapped[index].length > 0) ){
                        rule_details.distributor_ids = distributors_to_be_mapped[index].join(',');
                    }

                    var active_circles = '';
                    $.each($('#active_circle_cell_'+index).select2('data'),function(key,active_circle){
                        active_circles += active_circle.text+'<br>';
                    });
                    var inactive_circles = '';
                    $.each($('#inactive_circle_cell_'+index).select2('data'),function(key,inactive_circle){
                        inactive_circles += inactive_circle.text+'<br>';
                    });
                    var primary_denominations = '';
                    $.each($('#primary_denomination_cell_'+index).select2('data'),function(key,primary_denomination){
                        primary_denominations += '<span class="primary-denom">'+primary_denomination.text+'</span>';
                    });
                    
                    var active_denominations = '';
                    $.each($('#denomination_on_cell_'+index).select2('data'),function(key,active_denomination){
                        active_denominations += '<span class="active-denom">'+active_denomination.text+'</span>';
                    });
                    
                    var inactive_denominations = '';
                    $.each($('#denomination_off_cell_'+index).select2('data'),function(key,inactive_denomination){
                        inactive_denominations += '<span class="inactive-denom">'+inactive_denomination.text+'</span>';
                    });
                    var rule_string = '<td><span id="vendor_span_'+rule_id+'" class="vendor_span">'+$('#vendor_cell_'+index).select2('data')[0].text+'</span></td>';
                    rule_string += '<td><span id="operator_span_'+rule_id+'" class="operator_span">'+$('#operator_cell_'+index).select2('data')[0].text+'</span></td>';
                    rule_string += '<td><span id="pc_span_'+rule_id+'" class="pc_span">'+($('#primary_circle_cell_'+index).select2('data')[0].text == "" ? "--" : $('#primary_circle_cell_'+index).select2('data')[0].text)+'</span></td>';
                    rule_string += '<td><span id="ac_span_'+rule_id+'" class="ac_span">'+(active_circles == "" ? "--" : active_circles)+'</span></td>';
                    rule_string += '<td><span id="ic_span_'+rule_id+'" class="ic_span">'+(inactive_circles == "" ? "--" : inactive_circles)+'</span></td>';
                    rule_string += '<td><span id="dc_span_'+rule_id+'" class="dc_span">'+($('#denomination_circle_cell_'+index).select2('data')[0].text == "" ? "--" : $('#denomination_circle_cell_'+index).select2('data')[0].text)+'</span></td>';
                    rule_string += '<td><span id="pd_span_'+rule_id+'" class="pd_span">'+(primary_denominations == "" ? "--" : primary_denominations)+'</span></td>';
                    rule_string += '<td><span id="do_span_'+rule_id+'" class="do_span">'+(active_denominations == "" ? "--" : active_denominations)+'</span><br><span id="doff_span_'+index+'" class="doff_span">'+(inactive_denominations == "" ? "--" : inactive_denominations)+'</span></td>';
                    rule_string += '<td><span id="of_span_'+rule_id+'" class="of_span">From: '+operation_from[0]+':'+operation_from[1]+'</span><br><span id="ot_span_'+index+'" class="ot_span">To: '+operation_to[0]+':'+operation_to[1]+'</span></td>';
                    rule_string += '<td class="actions"><button type="button" onclick=\'javascript:appendNewRuleTemplate('+rule_id+','+JSON.stringify(rule_details)+');\' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#ee1c25;border-radius:0px;">';
                    rule_string += '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;Edit';
                    rule_string += '</button>';
                    
                   
                    if( rule_details.oprDown == 0 ){
                        rule_string += '<button title="click to disable this rule" type="button" onclick=\'javascript:toggleRule('+rule_id+',0,this,'+operator+');\' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#999;border-radius:0px;">';
                        rule_string += '<span class="glyphicon" aria-hidden="true"></span>Disable Rule</button>';
                    } else {
                        rule_string += '<button title="click to enable this rule" type="button" onclick=\'javascript:toggleRule('+rule_id+',1,this,'+operator+');\' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#3ab54a;border-radius:0px;">';
                        rule_string += '<span class="glyphicon" aria-hidden="true"></span>Enable Rule</button>';
                    }
                    
                    if( rule_details.is_disabled == 1 ){
                        rule_string += '<button title="click to enable this rule for retailers" type="button" onclick=\'javascript:toggleRuleForRetailers('+rule_id+',0,this,'+operator+');\' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#3ab54a;border-radius:0px;">';
                        rule_string += '<span class="glyphicon" aria-hidden="true"></span>Enable for retailers</button></td>';
                    } else {
                        rule_string += '<button title="click to disable this rule for retailers" type="button" onclick=\'javascript:toggleRuleForRetailers('+rule_id+',1,this,'+operator+');\' class="btn btn-xs btn-block" style="font-size: 11px;color: #fff;background-color:#999;border-radius:0px;">';
                        rule_string += '<span class="glyphicon" aria-hidden="true"></span>Disable Local Area Mapping</button></td>';
                    }
                    rule_string += '<input type="hidden" id="is_oprDown_'+rule_id+'" value="'+rule_details.oprDown+'">';
                    rule_string += '<input type="hidden" id="is_disabled_'+rule_id+'" value="'+rule_details.is_disabled+'">';
                    
                    $('#rule_'+index).attr('id','rule_'+rule_id);
                    $('#rule_'+rule_id).html(rule_string);
                } else {
                    alert('Could not save the rule. Please try again! Note: There can be only one rule for vendor and operator.Try changing operator or vendor.');
                    return false;
                }
            }
        });
    }
    function mapDistributors(index){
        distributors_to_be_mapped[index] = [];
        var selected_distributors = $('#distributors_dist').val();
        distributors_to_be_mapped[index] = selected_distributors;
        console.log(distributors_to_be_mapped[index]);
       $('#distributors_list_modal').modal('toggle');
        
    }
    function mapRetailers(index){
        retailers_to_be_mapped[index] = [];
        var selected_retailers = [];
        $('.retailer-select').each(function(index,element,retailers_to_be_mapped){
            if($(element).is(':checked')){
                selected_retailers.push(parseInt($(element).attr('data-retailer-id')));
            }
        });
        retailers_to_be_mapped[index] = selected_retailers;
        $('#retailers_list_modal').modal('toggle');
    }
    function resetRetailers(){
//        if(confirm('Are you sure you want to reset saved retailers ? ')){
//            $('.selected-retailer-data-container').html('');
//            retailers_to_be_mapped.length = 0;
            $(".retailer-select").prop('checked',false);
            $("#select_all").prop('checked', false); //change "select all" checked status to false
//        }
    }
    function filterRetailers(){
        var selected_retailer_id = $('#retailer_ids').val();
        var selected_retailer_name = $('#retailer_names').val();
        var selected_shop_area = $('#shop_areas').val();
        var selected_circle = $('#circles').val();
       
        $('.retailer-data-container tbody tr').each(function(index,row){
            var show_row = true;
            
            var retailerid = $(row).children('.retailerid_cell').text();
            var retailername = $(row).children('.retailername_cell').text();
            var shoparea = $(row).children('.shoparea_cell').text();
            var circle = $(row).children('.circle_cell').text();
            
            
            if(selected_retailer_id && (retailerid != selected_retailer_id) ){
                show_row = false;
            }
            if(selected_retailer_name && (retailername != selected_retailer_name) ){
                show_row = false;
            }
            if(selected_shop_area && (shoparea != selected_shop_area)){
                show_row = false;
            }
            if( (selected_circle) && (circle != selected_circle) ){
                show_row = false;
            }
            
            
            if(show_row){
                $(row).show();
            } else {
                $(row).hide();
            }
           
        });
        //check "select all" if all checkbox items are checked
        if ($('.retailer-select:checked').length == $('.retailer-select').length ){
            $("#select_all").prop('checked', true);
        } else {
            $("#select_all").prop('checked', false);
        }
    }
    function toggleRule(rule_id,status,elm,product_id){
        var confirm_string = 'Are you sure you want to enable this rule ?';
        if(status == 0){
            confirm_string = 'Are you sure you want to disable this rule ?';
        }
        if(confirm(confirm_string)){
//            var url = '/rechargeflowrules/toggleRule';
            var url = '/panels/disableVendor';
            $.ajax({
                type: 'POST',
                url: url,
                data: {pid:rule_id,flag:status,product:product_id},
                error: function() {

                },
                success: function(response) {
                    if( (response == 'success') && (status == 0) ){
                        
                        $(elm).attr('onclick','javascript:toggleRule('+rule_id+',1,this,'+product_id+')');
                        $(elm).html('<span class="glyphicon" aria-hidden="true"></span>Enable Rule');
                        $(elm).attr('title','click to enable this rule');
                        $(elm).attr('style','font-size: 11px;color: #fff;background-color:#3ab54a;border-radius:0px;');
                        status = 2;
                        
                    } else {
                        
                        $(elm).attr('onclick','javascript:toggleRule('+rule_id+',0,this,'+product_id+')');
                        $(elm).attr('style','font-size: 11px;color: #fff;background-color:#999;border-radius:0px;');
                        $(elm).attr('title','click to disable this rule');
                        $(elm).html('<span class="glyphicon" aria-hidden="true"></span>Disable Rule');
                        status = 0;
                    }
                    
                    $('#is_oprDown_'+rule_id).val(status);
                }
            });
        } else {
            return false;
        }
    }
    function toggleRuleForRetailers(rule_id,status,elm,product_id){
        var confirm_string = 'Are you sure you want to disable this rule for retailers ?';
        if(status == 0){
            confirm_string = 'Are you sure you want to enable this rule for retailers ?';
        }
    
        if(confirm(confirm_string)){
            var url = '/rechargeflowrules/toggleRuleForRetailers';
            $.ajax({
                type: 'POST',
                url: url,
                dataType:'json',
                data: {rule_id:rule_id,status:status,product:product_id},
                error: function() {

                },
                success: function(response) {
                    if( (response) && (status == 1) ){
                        $(elm).attr('onclick','javascript:toggleRuleForRetailers('+rule_id+',0,this,'+product_id+')');
                        $(elm).attr('style','font-size: 11px;color: #fff;background-color:#3ab54a;border-radius:0px;');
                        $(elm).attr('title','click to enable this rule for mapped retailers');
                        $(elm).html('<span class="glyphicon" aria-hidden="true"></span>Enable for Retailers');
                    } else {
                        $(elm).attr('onclick','javascript:toggleRuleForRetailers('+rule_id+',1,this,'+product_id+')');
                        $(elm).html('<span class="glyphicon" aria-hidden="true"></span>Disable Local Area Mapping');
                        $(elm).attr('title','click to disable this rule for mapped retailers');
                        $(elm).attr('style','font-size: 11px;color: #fff;background-color:#999;border-radius:0px;');
                    }
                     $('#is_disabled_'+rule_id).val(status);
                }
            });
        } else {
            return false;
        }
    }
    
    $("#vendor_filter").select2({
        placeholder: "Select Vendor",
        dropdownAutoWidth: 'true',
        width:'250px',
        allowClear: true
    });
    
    $("#operator_filter").select2({
        placeholder: "Select Operator",
        dropdownAutoWidth: 'true',
        width:'250px',
        allowClear: true
    });
    $("#status_filter").select2({
        placeholder: "Active/Inactive",
        dropdownAutoWidth: 'true',
        width:'120px',
        allowClear: true
    });
    $("#type_filter").select2({
        placeholder: "Basic/Advanced",
        dropdownAutoWidth: 'true',
        width:'130px',
        allowClear: true
    });
    
</script>
