<style>
    .flash_message {
        text-align: center;
        color: #808080;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        Event Notification
    </div>
    <div class="panel-body">
        <form id="event_notification" method="POST" action="/shops/callEvent/" enctype="multipart/form-data" >
            <div class="flash_message">
                <h5><?php echo $this->Session->flash(); ?></h5>
            </div>

            <label for="name" class="control-label">Image URL :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="image_url" name="image_url" placeholder="Enter Image URL" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="image_url_err">* Image URL is compulsory</div>
            </div><br />

            <label for="name" class="control-label padding-top-20">Action :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <div class="col-md-3">
                        <select class="form-control" id="action" name="action" style="margin-left: -15px;">
                            <option value="">Select Action</option>
                            <?php foreach($actions as $action) { ?>
                            <option><?php echo $action; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
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

            <label for="name" class="control-label padding-top-20">Event :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <div class="col-md-3">
                        <select class="form-control" id="event" name="event" style="margin-left: -15px;">
                            <option value="">Select Event</option>
                            <?php foreach($events as $event) { ?>
                            <option value="<?php echo $event['events_action']['id']; ?>"><?php echo $event['events_action']['event_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPlanForm">Add Event</button>
                </div>
                <div class="col-md-12 error-msg" id="event_err">* Select Event</div>
            </div><br />

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
                                                        <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h3 class="modal-title" id="newPlanLabel" align="center">Add New Event</h3>
                                        </div>
                                        <div class="modal-body">
                                                <form class="form-inline" method="post" action="/shops/addEvent" id="addEvent">
                                                        <div class="form-group">
                                                                <label for="planDescription">Event :</label><br />
                                                                <textarea class="form-control" id="new_event" name="new_event" rows="4" cols="70"></textarea>
                                                        </div>

                                                        <div class="modal-footer">
                                                             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                             <button type="submit" class="btn btn-success">Submit</button>
                                                        </div> 

                                                </form>

                                        </div>

                                </div>
                        </div>
                </div>
        </div>
<script>
    function validation() {
        $('.error-msg').hide();

        var image = $('#image_url').val();
        var action = $('#action').val();
        var event = $('#event').val();
        var button_text = $('#button_text').val();
            
        var res = 0;
        if(image == '') {
            $('#image_url_err').show();
            res = 1;
        }
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
        if(res == 0) {
            $('#event_notification').submit();
        }
    }
</script>

<style>
.error-msg { color: red; display: none; }
</style>