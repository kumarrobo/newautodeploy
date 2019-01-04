
<?php if ($page != 'download') { 
    Configure::load('platform'); ?>
    <script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" media="screen" href="/css/newleads.css">
    <script src="/js/prototype.js" type="text/javascript"></script>
    <script src="/js/merge1.js" type="text/javascript"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="/js/autocomplete.js" type="text/javascript"></script>    
    <div class="container">
        <form  name="leadsform" id="leadsform" action ="/panels/leads/" method="post">
            <input type="hidden" name='download' id ='download' value="">
            <div class="row">
                <div class="form-group col-md-3">
                    <label>From Date: </label> <input class="form-control" type="text" name="from" id="from" onmouseover="fnInitCalendar(this, 'from', 'close=true')" <?php if (isset($fromdate)) { ?>value="<?php echo $fromdate; ?>" <?php } else { ?>value="<?php echo date("d-m-y");
    } ?>"/>
                </div>
                <div class="form-group col-md-3">
                    <label> To Date: </label> <input class="form-control" type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to', 'close=true')" <?php if (isset($todate)) { ?>value="<?php echo $todate; ?>" <?php } else { ?>value="<?php echo date("d-m-y");
    } ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label> Partner Interest: </label> 
                    <select id="interest" name="interest" class="form-control">
                        <option>All</option>
                        <option <?php if ($interest == "1") echo "selected='selected'" ?> value="1">Retailer</option>
                        <option <?php if ($interest == "2") echo "selected='selected'" ?> value="2">Distributor</option>
                    </select>&nbsp;
                </div>
                <div class="form-group col-md-3">
                    <label> Lead State : </label> 
                    <select id="leadstate" name="leadstate" class="form-control">
                        <option>All</option>
                        <?php foreach ($leadState as $lstate) { ?>
                            <option <?php if ($lstate['lead_attributes_values']['id'] == $leadstatefltr) echo "selected='selected'" ?> value= <?php echo $lstate['lead_attributes_values']['id']; ?>><?php echo $lstate['lead_attributes_values']['lead_values']; ?></option>
    <?php } ?>
                    </select>
                </div>
            </div>
            <div class ="row">
                <div class="form-group col-md-3">
                    <label>Search : </label>
                    <input class="form-control" type="text" id="search" name="search" value="<?php echo $_POST['search']; ?>" placeholder="Name or Email or Phone No or Firm Name">&nbsp;
                </div>
                <div class="form-group col-md-3">
                    <label> City : </label>
                    <input class="form-control" type="text"  id="city" name="city" placeholder="Enter City" value="<?php echo $_POST['city']; ?>" >
                </div>
                <div class="form-group col-md-3">
                    <label> State : </label>
                    <input class="form-control" type="text"  id="state" name="state"  placeholder="Enter State" value="<?php echo $_POST['state']; ?>">
                </div>
                <div id="autoCompleteCity"></div>                    

                <div class="form-group form-group col-md-2">
                    <input class="form-control btn btn-primary" type="button" value="Submit" onclick="setAction();" style="margin-top:23px;">      
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <?php
                        $url1 = explode('/', $_SERVER['REQUEST_URI']);
                        $url = explode('?', $url1[4]);
                        ?>
                        <label for="lead_no">Pages</label>                       
                        <select class="form-control" id="lead_no" name="lead_no" onchange="javascript:goToPage(1, this.value)">                        
                            <option <?php if ($recs == 10) {
                            echo "selected";
                        } ?>>10</option>
                            <option <?php if ($recs == 20) {
                            echo "selected";
                        } ?>>20</option>
                            <option <?php if ($recs == 30) {
                            echo "selected";
                        } ?>>30</option>
                            <option <?php if ($recs == 50) {
                            echo "selected";
                        } ?>>50</option>
                            <option <?php if ($recs == 100) {
                            echo "selected";
                        } ?>>100</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--       For Downloading the report on Excel
                    <input type="button" value="Download" onclick="exportdata();" >-->
            <br/><br/>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Index</th><th>Date</th><th>Mobile</th><th >Name</th><th style="width:135px;border:1px solid #ccc;">Email</th><th style="width:135px;border:1px solid #ccc;">Firm Name</th><th style="width:135px;border:1px solid #ccc;">Current Business</th><th style="width:135px;border:1px solid #ccc;">Pincode</th><th style="width:135px;border:1px solid #ccc;">City</th><th style="width:135px;border:1px solid #ccc;">State</th><th style="width:135px;border:1px solid #ccc;">Lead State</th><th style="width:135px;border:1px solid #ccc;">Lead Source</th><th style="width:135px;border:1px solid #ccc;">Lead Campaign</th><th style="width:135px;border:1px solid #ccc;">Type</th><th style="width:135px;border:1px solid #ccc;">Status</th><th style="width:135px;border:1px solid #ccc;">Sub Status</th><th style="width:135px;border:1px solid #ccc;">Agent Name</th><th style="width:135px;border:1px solid #ccc;">Comments</th><th style="width:135px;border:1px solid #ccc;">Follow up</th><th style="width:135px;border:1px solid #ccc;">Follow up Reason</th><th style="width:135px;border:1px solid #ccc;">Interest Change</th><th style="width:155px;border:1px solid #ccc;">Action</th>
                    </tr>    
                            <?php
                            $i = 1;
                            foreach ($leadData as $val) {
                                if ($i % 2 == 0)
                                    $class = '';
                                else
                                    $class = 'altRow';

                                echo "<tr id='lead_row_" . $val['leads_new']['id'] . "' class=" . $class . "><td style=\"width:20px;border:1px solid #ccc;\">$i</td><td style=\"width:5px;border:1px solid #ccc;\">" . $val['leads_new']['creation_date'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['phone'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['name'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['email'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['shop_name'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['current_business'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['pin_code'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['city'] . "</td><td style=\"width:20px;border:1px solid #ccc;\">" . $val['leads_new']['state'] . "</td>";
                                ?>
                        <td style="width:20px;border:1px solid #ccc;">
                            <select id='lead_state_<?php echo $val['leads_new']['id'] ?>' name='lead_state_<?php echo $val['leads_new']['id'] ?>"' disabled=\"disabled\">
                                <option value='Select'>Select</option>
        <?php foreach ($leadState as $lstate) { ?>
                                    <option <?php if ($lstate['lead_attributes_values']['id'] == $val['leads_new']['lead_state']) echo "selected='selected'" ?> value= <?php echo $lstate['lead_attributes_values']['id']; ?>><?php echo $lstate['lead_attributes_values']['lead_values']; ?></option>
                        <?php } ?>           
                            </select>
                        </td>
                        <?php 
                        $leadSource = Configure::read('lead_source');?>                       
                        <td style="width:20px;border:1px solid #ccc;">
                            <input type="text" id='lead_src_<?php echo $val['leads_new']['id'] ?>' name='lead_src_<?php echo $val['leads_new']['id'] ?>' disabled=\"disabled\" value='<?php echo array_search(($val['leads_new']['lead_source']),$leadSource); ?>'>                                        
                        </td>          
                        <td style="width:20px;border:1px solid #ccc">                    
                            <textarea id="campaign_<?php echo $val['leads_new']['id'] ?>" name='campaign_<?php echo $val['leads_new']['id'] ?>' rows=2  disabled=\"disabled\" ><?php echo $val['leads_new']['lead_campaign']; ?></textarea>

                        </td>
        <?php $leadType = array('1' => 'Retailer', '2' => 'Distributor'); ?>
                        <td style="width:20px;border:1px solid #ccc">
                            <input type="text" id='interest_<?php echo $val['leads_new']['id'] ?>' name="interest_<?php echo $val['leads_new']['id'] ?>" disabled=\"disabled\" value='<?php echo $leadType[$val['leads_new']['interest']]; ?>'>                                        
                        </td>

                        <td style="width:20px;border:1px solid #ccc">
                            <select id="status_<?php echo $val['leads_new']['id'] ?>" name="status_<?php echo $val['leads_new']['id'] ?>" disabled=\"disabled\" onchange="leadStatus(this.value,<?php echo $val['leads_new']['id'] ?>)">
                                <option>Select</option>
        <?php foreach ($leadsStatus as $status) { ?>
                                    <option <?php if ($status['lead_attributes_values']['id'] == $val['leads_new']['status']) echo "selected='selected'" ?> value ='<?php echo $status['lead_attributes_values']['id']; ?>'><?php echo $status['lead_attributes_values']['lead_values']; ?></option>        
                        <?php } ?>
                            </select>
                        </td>
                        <td style="width:20px;border:1px solid #ccc;">                     
                            <select id='substatus_<?php echo $val['leads_new']['id'] ?>' name='substatus_<?php echo $val['leads_new']['id'] ?>' disabled=\"disabled\">                        
                        <?php foreach ($leadSubstatus as $leadstatus) { ?>
                                    <option <?php if ($leadstatus['lead_attributes_values']['id'] == $val['leads_new']['sub_status']) echo "selected='selected'" ?> value ='<?php echo $leadstatus['lead_attributes_values']['id']; ?>'><?php echo $leadstatus['lead_attributes_values']['lead_values']; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                        <td style="width:20px;border:1px solid #ccc;">
                            <input type="text" id='agentname_<?php echo $val['leads_new']['id'] ?>' name='agentname_<?php echo $val['leads_new']['id'] ?>' value ='<?php echo $val['leads_new']['agent_name']; ?>' disabled=\"disabled\">

                        </td>
        <?php //foreach($leadComment as $leadComment){  ?>
                        <td style="width:20px;border:1px solid #ccc">
                            <textarea  id="comments_<?php echo $val['leads_new']['id'] ?>"  name="comments_<?php echo $val['leads_new']['id'] ?>" rows=2 disabled=\"disabled\"> <?php echo $leadComm[$val['leads_new']['id']] ?></textarea>
                        </td>               
        <?php //}  ?>
                        <td style="width:20px;border:1px solid #ccc;">

        <?php if ($val['leads_new']['followup_date'] == "0000-00-00") { ?>
                                <input type="text" id="datepicker_<?php echo $val['leads_new']['id'] ?>" disabled=\"disabled\" value ='<?php echo $val['leads_new']['followup_date']; ?>' style="width:75px;cursor: pointer;" onmouseover="fnInitCalendar(this, 'datepicker_<?php echo $val['leads_new']['id'] ?>', 'close=true')" value='<?php echo $val['leads_new']['followup_date']; ?>'/>                    
        <?php } else {
            $followup_date = date("d-m-Y", strtotime($val['leads_new']['followup_date']));
            ?>
                                <input type="text" id="datepicker_<?php echo $val['leads_new']['id'] ?>" disabled=\"disabled\" value ='<?php echo $followup_date; ?>' style="width:75px;cursor: pointer;" onmouseover="fnInitCalendar(this, 'datepicker_<?php echo $val['leads_new']['id'] ?>', 'close=true')" value='<?php echo $followup_date; ?>'/>
                        <?php } ?>  
                        </td>
                        <td style="width:20px;border:1px solid #ccc;">
                            <textarea  id="followupreason_<?php echo $val['leads_new']['id'] ?>"  name="followupreason_<?php echo $val['leads_new']['id'] ?>" rows=2 disabled=\"disabled\" ><?php echo $val['leads_new']['followup_remark']; ?></textarea>

                        <td style="width:20px;border:1px solid #ccc">
                            <select id='interestchg_<?php echo $val['leads_new']['id'] ?>' name="interestchg_<?php echo $val['leads_new']['id'] ?>" disabled=\"disabled\">
                                <option value='0' >None</option>
                                <option <?php if ($val['leads_new']['interest_change'] == 1) echo "selected='selected'"; ?>value ='1'>Retailer</option>
                                <option <?php if ($val['leads_new']['interest_change'] == 2) echo "selected='selected'"; ?> value='2'>Distributor</option>
                            </select>
                        </td>
        <?php
        echo"<td style=\"width:30px;border:1px solid #ccc;\"><a href='javascript:void(0)' onclick='editform(" . $val['leads_new']['id'] . ");'>Edit&nbsp;&nbsp</a><a href='javascript:void(0)' onclick='submitform(" . $val['leads_new']['id'] . ");'>Submit</a></td></tr>";
//                "<a  onclick='txnhistory(" . $val['leads_new']['id'] . ");' data-toggle='modal' class='dropdown-toggle active' href='#leadhistory' > History</a></td></tr>";                           
        $i++;
    }
    ?>
                </table>   
            </div>
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5 text-right">
    <?php echo $this->element('pagination'); ?>
                </div>
            </div>

        </form> 
    </div>
   
    <script>
        jQuery(document).ready(function () {
            jQuery('#state').autocomplete({
                source: "/panels/leadStates",
                minLength: 1,
                select: function (event, ui) {
                    event.preventDefault();
                    var a = ui.item.label;
                    jQuery("#state").val(a);
                }
            });
        });
    </script>
    <script type="text/javascript">

        function setAction()
        {
            jQuery("#download").val('');
            jQuery("#leadsform").submit();
        }

        function editform(id)
        {
            jQuery('#lead_row_' + id).find('input,select,textarea').removeAttr('disabled');
        }

        function exportdata() {
            jQuery("#download").val('download');
            jQuery("#leadsform").submit();
        }

        function submitform(id) {            
            var url = '/panels/leads/';
            var lead_state = jQuery("#lead_state_" + id).val();
            var lead_src = jQuery("#lead_src_" + id).val();
            var lead_campaign = jQuery("#campaign_" + id).val();
            var lead_status = jQuery("#status_" + id).val();
            var lead_substatus = jQuery("#substatus_" + id).val();
            var comment = jQuery("#comments_" + id).val();
            var agentname = jQuery('#agentname_' + id).val();
            var followupreason = jQuery('#followupreason_' + id).val();
            var followup = jQuery('#datepicker_' + id).val();
            var interestchg = jQuery('#interestchg_' + id).val();
            var data = "id=" + id + "&leadstate=" + lead_state + "&camp=" + lead_campaign + "&status=" + lead_status +
                    "&substatus=" + lead_substatus + "&comm=" + comment + "&agentname=" + agentname + "&remarks=" + followupreason + "&followup=" + followup + "&change=" + interestchg;
            jQuery.ajax({
                type: "POST",
                url: url,
                datatype: "json",
                data: data,
                success: function (data) {
                    vJSONResp = JSON.parse(data);
                    if (vJSONResp.status == "success") {
                        jQuery('#lead_row_' + id).find('input,select,textarea').attr("disabled", "disabled");
                    }
                }
            });
        }

        function changeInterest(id, name, email, interest) {

            var changeinterest = {Retailer: 'Distributor', Distributor: 'Retailer'};

            if (confirm("Are you sure you want to change the status of " + name + " (" + email + ") from '" + interest + "' to '" + changeinterest[interest] + "' ?")) {
                jQuery.post('/panels/changeInterest', {id: id, interest: changeinterest[interest]}, function (e) {
                    window.location.reload();
                });
            }
        }

        var cities = ['<?php echo implode("','", $cities); ?>'];
        new Autocompleter.Local("city", "autoCompleteCity", cities, {});
    </script>
    <script>
        function leadStatus(statusval, id) {
            var val = statusval;
            jQuery.ajax({
                url: '/panels/leadSubstatus',
                type: "POST",
                datatype: "json",
                data: {lstatus: val},
                success: function (data) {
                    var response = [];
                    response = jQuery.parseJSON(data);
                    jQuery('#substatus_' + id).val('#substatus_' + id);
                    jQuery('#substatus_' + id).html("");
                    jQuery("#substatus_" + id).attr('disabled', false);

                    jQuery.each(response, function (key, value)
                    {
                        jQuery('#substatus_' + id).append('<option  value=' + key + '>' + value + '</option>');

                    });
                }
            });
        }

        function goToPage(page = 1, recs =<?php echo $recs; ?>) {
            jQuery('#leadsform').attr('action', '/panels/leads/' + recs + '?page=' + page);
            jQuery('#leadsform').submit();
        }
    </script>
<?php } ?>
