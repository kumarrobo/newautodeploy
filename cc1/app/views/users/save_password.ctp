 <div class="col-lg-4 col-lg-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading"><h5>Change Password</h5></div>
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
                                                <label for="new_pwd" class="col-lg-4 control-label">Password</label>
                                                <div class="col-lg-8">
                                                    <input type="password"   id="password" name="password" class="form-control form-inp" data-error="This field is required" required>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="confirm_pwd" class="col-lg-4 control-label">Confirm Password</label>
                                                <div class="col-lg-8">
                                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control form-inp" data-error="This field is required" required>
                                                </div>
                                                
                                            </div>
                                 <div class="form-group">
                                          <button class="btn btn-primary btn-sm col-sm-offset-4" type="submit">Save</button>
                                          <button class="btn btn-default btn-sm" type="reset">Reset</button>
                                 </div>
                            </form>
                      </div>
        </div>
</div>
