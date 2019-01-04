<div class="col-lg-4 col-lg-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">OTP Verification</div>
                    <div class="panel-body">
                        <?php $messages=$this->Session->flash(); ?>
                        <?php if(!empty($messages) && preg_match('/Errors/',$messages)): ?>
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
                        <form class="form-horizontal" method="post" >
                                <div class="form-group">
                                    <label class="col-xs-4 control-label size12" for="otp">Enter OTP</label>
                                    <div class="col-xs-8">
                                        <input type="text" name="otp" id="otp" class="form-control size12" placeholder="" required="">
                                    </div>
                                </div>
                                 <div class="form-group">
                                     <div class="col-xs-2 col-lg-offset-4">
                                          <input type="submit" class="btn btn-primary btn-sm" value="Submit" />
                                     </div>
                                 </div>
                            </form>
                      </div>
        </div>
</div>
