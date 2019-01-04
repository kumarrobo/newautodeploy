<?php // echo $this->element('product_sidebar'); ?>
<div class="row col-sm-6 col-sm-offset-3" style="margin-left: 250px;">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Edit</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="edit_form" method="post" role="form" onsubmit="return save();">

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pname">Name</label>
                        <div class="col-sm-9 ">
                            <input class="form-control" disabled="disabled" type="text" id="pname" name="pname" style="width: 380px;" value="<?php echo $products[0]['products']['name'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="to_show">Show on panel</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="to_show" name="to_show" style="width: 380px;">
                            <option <?php if($products[0]['products']['to_show'] == 0) echo "selected='selected'" ?> value="0">No</option>
                            <option <?php if($products[0]['products']['to_show'] == 1) echo "selected='selected'" ?> value="1">Yes</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-3" for="invalid">Invalid amount</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="invalid" name="invalid" style="width: 380px;"><?php echo $products[0]['products']['invalid'];?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="circle_yes">Active circles</label>
                            <div class="col-sm-9">
                                <select id="circles_yes" name="circles_yes[]" multiple="multiple">
                                    <?php foreach($circles as $c): ?>
                                            <option value="<?php echo $c['mobile_numbering_area']['id'] ?>" <?php echo (strpos($products[0]['products']['circle_yes'],$c['mobile_numbering_area']['id']) === false)?'':'selected';?><?php echo (strpos($products[0]['products']['circle_no'],$c['mobile_numbering_area']['id']) === false)?'':'disabled';?>>
                                                    <?php echo $c['mobile_numbering_area']['area_name'] ?>
                                            </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="circle_no">Inactive circles</label>
                            <div class="col-sm-9">
                                <select id="circles_no" name="circles_no[]" multiple="multiple">
                                    <?php foreach($circles as $c): ?>
                                    <option value="<?php echo $c['mobile_numbering_area']['id'] ?>" <?php echo (strpos($products[0]['products']['circle_no'],$c['mobile_numbering_area']['id']) === false)?'':'selected';?><?php echo (strpos($products[0]['products']['circle_yes'],$c['mobile_numbering_area']['id']) === false)?'':'disabled';?>>
                                                    <?php echo $c['mobile_numbering_area']['area_name'] ?>
                                            </option>
                                    <?php endforeach ?>
                                </select>                                 
                            </div>
                    </div>

                    <input type="hidden" id="id" name="id" value="<?php echo $products[0]['products']['id'];?>">
                    <br/>

                    <div class="form-group">
                          <div class="col-sm-2 col-sm-offset-5">
                              <button type="submit" class="btn btn-primary" id="submitform" name="submitform">Submit</button>
                          </div>
                    </div>

                </form>
        </div>
    </div>
</div>