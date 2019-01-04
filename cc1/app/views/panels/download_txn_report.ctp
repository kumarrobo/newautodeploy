<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><b>Transaction Report</b></div>
            <div class="panel-body">
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
                    <form method="post" id="tds_form">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="col-lg-5 control-label" for="quarter">Date</label>
                                 <div class="col-lg-7">
                                    <input type="text" class="form-control" style=""  id="txn_date" name="txn_date"   value="<?php echo isset($this->params['form']['txn_date'])?$this->params['form']['txn_date']:date('Y-m-d', strtotime('-1 day')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-download"></span>Download</button>
                                </div>
                            </div>
                        </div>                       
                    </form>
                </div>
        </div>
</div>
<script>
    $(document).ready(function () {
            $('#txn_date').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
                endDate: "1d",
                multidate: false,
                autoclose: true,
                orientation: 'top right',
                todayHighlight: true
            });  
        });
</script>