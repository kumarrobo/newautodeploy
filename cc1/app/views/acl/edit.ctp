<style> body{font-size: 12px !important;} input.size12{ font-size: 12px;height : 30px;}label.size12{     font-variant: small-caps;font-size: 13px}div.ms-drop ul li label{font-size: 12px;}.ms-drop input[type="checkbox"]{margin-top:0px;}</style>
<script>$(document).ready(function(){ $('select.mslct').multipleSelect({width: 380,multipleWidth: 170,multiple: true});});</script>
    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel panel-pay1">
            <div class="panel-heading">Edit User</div>
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
                        <form class="form-horizontal" method="post" action="/acl/edit/<?php echo $this->params['pass'][0]; ?>">
                                <div class="form-group">
                                    <label class="col-xs-2 control-label size12" for="inputSuccess">Username</label>
                                    <div class="col-xs-4">
                                        <input type="text" name="username" id="inputSuccess" class="form-control size12" placeholder="" required="" value="<?php echo $userData['user']['name']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-2 control-label size12" for="inputError">Mobile</label>
                                    <div class="col-xs-4">
                                        <input type="text"  name="mobile" id="inputError" class="form-control size12" placeholder="" required="" disabled="" value="<?php echo $userData['user']['mobile']; ?>">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-xs-2 control-label size12" for="inputWarning">Groups</label>
                                    <div class="col-xs-4">
                                        <select name="groups_cc[]" id="groups_cc" class="mslct" multiple>
                                                <?php foreach($groups as $group): ?>
                                                 <option  value="<?php echo $group['groups']['id'] ?>"  <?php if(in_array($group['groups']['id'],$userData['groups'])): echo "Selected"; endif; ?>><?php echo $group['groups']['name'] ?></option>
                                                 <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
<!--                                 <div class="form-group">
                                    <label class="col-xs-2 control-label size12" for="inputWarning">Inventory</label>
                                    <div class="col-xs-4">
                                        <select name="groups_inv[]" id="groups_inv" class="mslct">
                                                <?php foreach($groups as $group): if($group['groups']['source']=='2'): ?>
                                                 <option  value="<?php echo $group['groups']['id'] ?>" <?php if(in_array($group['groups']['id'],$userData['groups'])): echo "Selected"; endif; ?>><?php echo $group['groups']['name'] ?></option><?php endif; ?>
                                                 <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>-->
                                 <div class="form-group">
                                     <div class="col-xs-2 col-lg-offset-2">
                                          <input type="submit" class="btn btn-default btn-sm btn-danger" value="Update" />
                                     </div>
                                     <div class="col-xs-2 col-lg-offset-2">
                                            <button onclick="location.href='/acl/listUser'" class="btn btn-default btn-sm" type="button">Back to Lists</button>
                                     </div>
                                 </div>
                            </form>
                      </div>
        </div>
</div>
