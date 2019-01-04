<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/smartpay.js"></script>
<style>th{width:200px;}form{font-size: 13px;}</style>
<div class="container">
    <div class="row">
    <div class="col-lg-12">
           <div class="page-header">
                        <h3>Service Activation</h3>
           </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Search</div>
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
                    <div class="row">
                        <form action="/smartpay/getUserServices" method="post">
                        <div class="col-lg-4">
                    <div class="form-group">  
                        <label class="control-label col-lg-4">Enter Mobile</label>
                        <div class="col-lg-8"><input type="text" value="<?php echo isset($mobile)?$mobile:"";?>" id="mobile" name="mobile"></div>                    
                    </div>
                        </div>
                        <div class="col-lg-2">
                    <div class="form-group">
                        <label for="" class="col-lg-2 control-label"></label>
                        <button class="btn btn-primary btn-sm" type="submit" id="">Search</button>
                    </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(!empty($servicedetails)): ?>
    <div class="row" id="updateServices">
        <div class="col-lg-12">
            <div class="panel panel-default">
                  <div class="panel-heading bg-success">Service Activation</div>
                  <div class="panel-body">
                      <div class="row">
                          <div class="col-lg-12">                              
                              <form method="post" action="/smartpay/updateUserServices">
                                  <table class="table table-condensed table-hover table-striped">
                                      <thead>
                                          <tr>
                                            <th>Service Name</th>
                                            <th>Kit Flag</th>                                            
                                            <th>Registration Form</th>                                              
                                            <th>Service Flag</th>
                                            <th>Device ID</th>      
                                            <th></th>
                                          </tr>
                                      </thead>
                                      
                                      <tbody>
                                          <?php foreach ($servicedetails as $k=>$v):
                                              if($k!=9):?>
                                          <tr>
                                                <td><?php echo $services[$k]; ?></td>
                                                <td>
                                                        <input type="checkbox"  name="services[<?php echo $k;?>][kit_flag]"  onclick="checkKitFlag(this,<?php echo $k; ?>)"<?php echo $v['kit_flag']==1?'checked':'';?>>
                                                         <input type="hidden" value="<?php echo $v['kit_flag']?>" name="services[<?php echo $k;?>][kit_flag]" >                                              
                                                </td>                                                
                                                <td><span><?php echo ($v['registration_flag']==1)?"Filled":"Not filled";?></span></td>                                                
                                                <td>
                                                    <input type="checkbox" name="services[<?php echo $k;?>][service_flag]" onclick="checkServiceFlag(this,<?php echo $k; ?>)" <?php echo (($v['service_flag']==1))?'checked':'';?>>                                                    
                                                    <input type="hidden" value="<?php echo $v['service_flag']?>"  name="services[<?php echo $k;?>][service_flag]" >                                                    <!--</div>-->
                                                </td>     
                                                <td> 
                                                    <?php echo !empty($v['device_id'])?$v['device_id']:"";?>
                                                    <?php if(empty($v['device_id'])):?>
                                                    <button type="button" class="btn btn-default btn-sm" id="btnmapdevice" onclick="saveDeviceMapping(<?php echo $k; ?>,'<?php echo $v['device_id'];?>')" style="font-size:12px;padding: 0px">Map Device</button>
                                                    <?php else:?>
                                                    <button type="button" class="btn btn-default btn-sm" id="btnmapdevice" onclick="saveDeviceMapping(<?php echo $k; ?>,'<?php echo $v['device_id'];?>')" style="font-size:12px;padding: 0px">Change Device</button>
                                                    <?php endif;?>                                                      
                                                </td>
                                                <td>
                                                    <?php ?>
                                                    <?php if($k==10): echo !empty($v['csp_id'])?$v['csp_id']:"";?><button type="button" class="btn btn-default btn-sm" id="btnmapcsp" onclick="saveCspMapping(<?php echo $k; ?>,'<?php echo $v['csp_id'];?>','<?php echo $v['csp_pass'];?>')" style="font-size:12px;padding: 0px">Map CSR</button><?php elseif($k==8): echo !empty($v['tid'])?$v['tid']:"";?><button type="button" class="btn btn-default btn-sm" id="btnmaptid" onclick="saveTIDMapping(<?php echo $k; ?>,'<?php echo $v['tid'];?>')" style="font-size:12px;padding: 0px">Map TID</button> <?php endif;?>
                                                </td>
                                          </tr>
                                          <?php endif;endforeach;?>
                                            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                            <input type="hidden" name="mobile" value="<?php echo $mobile;?>">
                                      </tbody>
                                      
                                  </table>
                                  <button class="btn btn-primary btn-sm col-sm-offset-6" type="submit" id="btnsaveservices" >Save</button>
                           </form>     
                          </div>
                      </div>
                  </div>
               </div>
        </div>
    </div>
<?php endif;?>
    
<?php if(!empty($doc_details)):?>
<div class="row" id="">
        <div class="col-lg-12">
            <div class="panel panel-default">
                  <div class="panel-heading">Document Verification</div>
                  <div class="panel-body">
                      <div class="row">
                          <div class="col-lg-12">                              
                              <form method="post" action="/smartpay/updateUserDocs">
                                  <table class="table table-condensed table-hover table-striped">
                                      <thead>
                                          <tr>
                                            <th>Document Name</th>
                                            <th>Image</th>
                                            <?php if($panel_flag==true):?><th>Status</th><?php endif;?>
                                          </tr>
                                      </thead>
                                      
                                      <tbody>
                                          <?php foreach ($doc_details as $k=>$v):
                                            if(array_key_exists($k, $doc_labels)):
                                             ?>
                                          <tr>
                                                <td>
                                                  <?php echo $doc_labels[$k]; ?>
                                                </td>                                                
                                                <td>
                                                    <?php if(count($v['links']) > 0):
                                                           foreach ($v['links'] as $link):?>
                                                        <a href="<?php echo "https://".$link;?>" target="_blank">IMG </a>|
                                                    <?php endforeach;
                                                    else: echo "Not uploaded";endif;?>
                                                </td>
                                                <?php?>
                                                <?php if($panel_flag==true):?>
                                                <td>
                                                    <input type="checkbox" name="<?php echo in_array($k, array('pan','aadhar','photo'))?$k."_status":$k."_form_status";?>" onclick="checkDocStatus(this,'<?php echo $k;?>')" <?php echo $v['status']==true?'checked':'';?> >
                                                     <input type="hidden" value="<?php echo !empty($v['status'])?$v['status']:0;?>"  name="<?php echo in_array($k, array('pan','aadhar','photo'))?$k."_status":$k."_form_status";?>" >                                                    <!--</div>-->
                                                </td>
                                                <?php endif;?>
                                                <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                                <input type="hidden" name="mobile" value="<?php echo $mobile;?>">
                                          </tr>
                                          <?php endif;
                                          endforeach;?>
                                      </tbody>
                                      
                                  </table>
                                  <?php if($panel_flag==true):?>
                                  <div class="form-group">
                                      <label class="control-label col-lg-2">Pay1 Status</label>
                                      <div class="col-lg-4">
                                                  <select name="pay1_status" id="pay1_status">
                                                      <option value="0"<?php if($doc_details['pay1_status']==0){ echo "selected"; }?>>Pending</option>
                                                      <option value="1"<?php if($doc_details['pay1_status']==1){ echo "selected"; }?>>Approved</option>
                                                      <option value="2"<?php if($doc_details['pay1_status']==2){ echo "selected"; }?>>Not approved</option>
                                                  </select>
                                      </div>
                                  </div>
                                  
                                  <div class="form-group">
                                      <label class="control-label col-lg-2">Internal Comment</label>
                                      <div class="col-lg-4">   <textarea name="pay1_comment" id="pay1_comment"><?php echo !empty($doc_details['pay1_comment'])?$doc_details['pay1_comment']:"";?></textarea></div>
                                  </div>
                                  
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Bank Status</label>
                                        <div class="col-lg-4">
                                                  <select name="bank_status" id="bank_status">
                                                      <option value="0"<?php if($doc_details['bank_status']==0){ echo "selected"; }?>>Pending</option>
                                                      <option value="1"<?php if($doc_details['bank_status']==1){ echo "selected"; }?>>Approved</option>
                                                      <option value="2"<?php if($doc_details['bank_status']==2){ echo "selected"; }?>>Not approved</option>
                                                  </select>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                      <label class="control-label col-lg-2">App Comment<br>(<small><strong>These comments will be shown on App</strong></small>)</label>
                                      <div class="col-lg-4"> <textarea name="bank_comment" id="bank_comment" ><?php echo !empty($doc_details['bank_comment'])?$doc_details['bank_comment']:"";?></textarea></div>
                                    </div>                                  
                                  <button class="btn btn-primary btn-sm col-sm-offset-6" type="submit" id="btnsavedocs" >Save</button>
                                  <?php endif;?>
                           </form>     
                          </div>
                      </div>
                  </div>
               </div>
        </div>
    </div>
<?php endif;?>
    
    <div id="servicecommentmdl" class="modal fade" >
        <div class="modal-dialog" style="width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Map Device</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">Enter Device Id</label></div>
                    <div class="col-lg-7"><textarea id="device_id" name="device_id"></textarea></div>
                </div>
                <input type="hidden" name="service_id" id="service_id" value="">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary col-lg-2 col-lg-offset-4" id="btndevicecomment">Save</button>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    
    <div id="cspcommentmdl" class="modal fade" >
        <div class="modal-dialog" style="width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Map CSR Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">Enter CSR Id</label></div>
                    <div class="col-lg-7"><textarea id="csp_id" name="csp_id"></textarea></div>
                </div>
                <div class="row">
                    <div class="col-lg-5"><label class="">Enter CSR Password</label></div>
                    <div class="col-lg-7"><textarea id="csp_pass" name="csp_pass"></textarea></div>
                </div>
                <input type="hidden" name="service_id" id="service_id" value="">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary col-lg-2 col-lg-offset-4" id="btncspcomment">Save</button>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    
    <div id="tidcommentmdl" class="modal fade" >
        <div class="modal-dialog" style="width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Map TID</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">Enter TID</label></div>
                    <div class="col-lg-7"><textarea id="tid" name="tid"></textarea></div>
                </div>
                <input type="hidden" name="service_id" id="service_id" value="">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-primary col-lg-2 col-lg-offset-4" id="btntidcomment">Save</button>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>