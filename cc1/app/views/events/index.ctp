<link href="/boot/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<style>
    .flash_message { text-align: center; color: #808080; }

    .error-msg { color: red; display: none; }
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        Marketing Alert
    </div>
    <div class="panel-body">
        <form id="event_notification" method="POST" action="/events/callEvent" enctype="multipart/form-data" >
            <div class="flash_message">
                <h5><?php echo $this->Session->flash(); ?></h5>
            </div>

            <label for="name" class="control-label">Image :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="file" class="form-control" id="image" name="image" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="image_url_err">* Image URL is compulsory</div>
            </div><br />

            <label for="name" class="control-label padding-top-20">Action :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <div class="col-md-3">
                        <select class="form-control" id="action" name="action" onchange="addUrl(this.value);" style="margin-left: -15px;">
                            <option value="">Select Action</option>
                            <?php foreach($actions as $key=>$action) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $action; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div id="addUrl"></div>
            <div class="row">
                <div class="col-md-12 error-msg" id="action_err">* Select Action</div>
            </div><br />

            <label for="name" class="control-label">Button Text :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Enter Button Text" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="button_text_err">* Enter text on button</div>
            </div><br />

            <input type="hidden" id="type" name="type" value="3">

            <label for="name" class="control-label">Expiry Date :</label>
            <div class="row">
                <div class="col-md-3 padding-top-10">
                    <div class="date form_datetime" data-date-format="yyyy-mm-dd hh:ii:00">
                            <input type="text" class="form-control" placeholder="Select Expiry Date" readonly>
                            <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="expiry_date" name="expiry_date" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="expiry_data_err">* Select Expiry Date</div>
            </div><br/>

            <label for="name" class="control-label padding-top-20">Event :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <div class="col-md-3">
                        <select class="form-control" id="event" name="event" style="margin-left: -15px;">
                            <option value="">Select Event</option>
                            <?php foreach($events as $event) { ?>
                            <option value="<?php echo $event['events_action']['event_name']; ?>"><?php echo $event['events_action']['event_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPlanForm">Add Event</button>
                </div>
                <div class="col-md-12 error-msg" id="event_err">* Select Event</div>
            </div><br />

            <input type="hidden" id="type" name="type" value="3">

            <div class="row padding-top-10">
                <div class="col-md-1 col-xs-3 padding-top-30">
                    <button type="button" class="btn btn-success" onclick="return validation();">Send</button>
                </div>
                <div class="col-md-1 col-xs-3 padding-top-10">
                    <button type="button" class="btn btn-default">Cancel</button>
                </div>
            </div>
        </form>

        <div class="row">
                <div class="modal fade bs-example-modal-lg" id="newPlanForm" tabindex="-1" role="dialog" aria-labelledby="newPlanLabel">
                        <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span id="close" aria-hidden="true">&times;</span>
                                                </button>
                                                <h3 class="modal-title" id="newPlanLabel" align="center">Add New Event</h3>
                                        </div>
                                        <div class="modal-body">
                                                <form class="form-inline" id="addEvent">
                                                        <div class="form-group">
                                                                <label for="planDescription">Event :</label><br />
                                                                <input type="text" class="form-control" id="new_event" name="new_event" style="width: 400%" />
                                                        </div>

                                                        <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-success" onclick="addEvent();" >Submit</button>
                                                        </div> 

                                                </form>

                                        </div>

                                </div>
                        </div>
                </div>
        </div>
    </div>
</div>

<script>
    
    function validation() {
        var minute = $('.datetimepicker .datetimepicker-minutes tbody .active').html()+":00";
        var date    = $('.datetimepicker .datetimepicker-minutes .switch').html().split(" ");
        var month  = {January:"1",February:"2",March:"3",April:"4",May:"5",June:"6",July:"7",August:"8",September:"9",October:"10",November:"11",December:"12"};
        var final_date = date[2]+"-"+month[date[1]]+"-"+date[0]+" "+minute;
        $('#expiry_date').val(final_date);

        $('.error-msg').hide();

        var action      = $('#action').val();
        var event       = $('#event').val();
        var button_text = $('#button_text').val();
            
        var res = 0;
        if(action == '') {
            $('#action_err').show();
            res = 1;
        }
        if(event == '') {
            $('#event_err').show();
            res = 1;
        }
        if(button_text == '') {
            $('#button_text_err').show();
            res = 1;
        }
        if($('#button_url').length > 0){
            var str = $('#button_url').val().match('http');

            if(str == null) {
                $('#action_err').html('Enter Valid HTTP address');
                $('#action_err').show();
                res = 1;
            }
        }
        
        if(res == 0) {
            $('#event_notification').submit();
        }
    }

    function addEvent() {

            var new_event = $('#new_event').val();

            $.post('/events/addEvent', {new_event: new_event}, function(e) {

                $('#close').click();

                $('#event').append('<option>'+new_event+'</option>');
                
            });
    }

    function addUrl(val) {

            if(val == 0 || val == 6) {

                str = '<br /><input type="text" class="form-control" id="button_url" name="button_url" placeholder="Enter Button Redirection URL &nbsp;&nbsp; e.g. http://pay1.in/" />';

                $('#addUrl').html(str);
            } else {

                $('#addUrl').html('');
            }
    }
    
    $(function() {
            $('.form_datetime').datetimepicker({autoclose: true, startDate: new Date()});
    });
</script>

<script type="text/javascript" src="/boot/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>