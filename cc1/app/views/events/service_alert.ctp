<style>
    .flash_message { text-align: center; color: #808080; }

    .error-msg { color: red; display: none; }
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        Service Alert
    </div>
    <div class="panel-body">
        <form id="service_alert" method="POST" action="/events/generateServiceAlert" enctype="multipart/form-data" >
            <div class="flash_message">
                <h5><?php echo $this->Session->flash(); ?></h5>
            </div>

            <label for="name" class="control-label">Title :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="title_err">* Enter Title</div>
            </div><br />

            <label for="name" class="control-label">Description :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="description_err">* Enter Description</div>
            </div><br />

            <label for="name" class="control-label">Alert Type :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <select class="form-control" id="alert_type" name="alert_type" >
                            <option value="">Select Alert Type</option>
                            <option value="1">Red</option>
                            <option value="2">Green</option>
                            <option value="3">Blue</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="alert_type_err">* Select Alert Type</div>
            </div><br />

            <input type="hidden" id="type" name="type" value="2">

            <div class="row padding-top-10">
                <div class="col-md-1 col-xs-3 padding-top-30">
                    <button type="button" class="btn btn-success" onclick="return validation();">Send</button>
                </div>
                <div class="col-md-1 col-xs-3 padding-top-10">
                    <button type="button" class="btn btn-default">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function validation() {

        $('.error-msg').hide();

        var title       = $('#title').val();
        var description = $('#description').val();
        var alert_type = $('#alert_type').val();
            
        var res = 0;
        if(title == '') {
            $('#title_err').show();
            res = 1;
        }
        if(description == '') {
            $('#description_err').show();
            res = 1;
        }
        if(alert_type == '') {
            $('#alert_type_err').show();
            res = 1;
        }
        if(res == 0) {
            $('#service_alert').submit();
        }
    }
</script>