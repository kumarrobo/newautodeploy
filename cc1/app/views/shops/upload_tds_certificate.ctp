<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'upload_tds'));?>
    		<div id="innerDiv">
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
                    <form method="post" id="tds_form" enctype="multipart/form-data">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="col-lg-5 control-label" for="quarter">Quarter </label>
                                     <div class="col-lg-7">
                                        <select class="form-control" id="quarter" name="quarter">
                                                <option value="1" <?php if($params['quarter'] == 1){ echo "selected"; }?>>Q1</option>
                                                <option value="2" <?php if($params['quarter'] == 2){ echo "selected"; }?>>Q2</option>
                                                <option value="3" <?php if($params['quarter'] == 3){ echo "selected"; }?>>Q3</option>
                                                <option value="4" <?php if($params['quarter'] == 4){ echo "selected"; }?>>Q4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php  $starting_year  = '2017';
                            $ending_year    = date('Y');  ?>  
                            <div class="col-lg-2">
                            <div class="form-group">
                                <label class="col-lg-5 control-label" for="year">Year</label>
                                <div class="col-lg-7">
                                <select id="year" name="year">
                                      <?php   for($starting_year; $starting_year <= $ending_year; $starting_year++) {
                                              if($starting_year == $params['year']) { $sel = 'selected'; }
                                              echo "<option $sel >".$starting_year."</option>";
                                              $sel = ''; } ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Upload </label>
                                    <div class="col-lg-9">
                                        <input type="file" name="tds_file" id="tds_file" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-default btn-primary btn-xs">Upload</button>
                                    </div>
                                </div>
                            </div>
                    </form>
                    </div>
 		</div>
    </div>
 </div>