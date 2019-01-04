<div class="container">
    <?php 
    $action = '/smstemplates/add';
    $submit_button_label = 'Add';
    if($mode == 'edit'){
        $action = '/smstemplates/update';
        $submit_button_label = 'Update';
    }
    ?>
    <form id="add_template_form" class="form-horizontal" method="POST" action="<?php echo $action; ?>">
        <?php 
        if($mode == 'edit'){ ?>
            <input type="hidden" id="template_id" value="<?php echo $template['id']; ?>" name="template_id"/>
        <?php }
        ?>
        
        <input type="hidden" id="page" value="<?php echo $this->params['url']['page']; ?>" name="page"/>
        <input type="hidden" id="query" value="<?php echo $this->params['url']['q']; ?>" name="query"/>
        <div class="form-group">
          <label class="control-label col-sm-2" for="operator">Operator</label>
          <div class="col-sm-6">
            <select class="form-control" id="operator" name="operator" required>
                <option value="">-- SELECT OPERATOR --</option>
                <?php 
                foreach ($providers as $id => $name) {
                    
                    $selected = '';
                    if($mode == 'edit'){
                        if($template['opr_id'] == $id){
                            $selected = 'selected="selected"';
                        }
                    }
                    echo '<option '.$selected.'value="'.$id.'">'.$name.'</option>';
                }
                ?>
            </select>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="sms">SMS:</label>
          <div class="col-sm-6">
            <textarea class="form-control" rows="5" id="sms" name="sms" required><?php echo $template['template']; ?></textarea>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="template">Template:</label>
          <div class="col-sm-6">
            <textarea class="form-control" rows="5" id="template" name="template" required><?php echo $template['template1']; ?></textarea>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="type">Type</label>
          <div class="col-sm-6">
              <select class="form-control" id="type" name="type" required onchange="javascript:changeTypeFlag(this.value);">
                <option value="">-- SELECT TYPE --</option>
                <?php 
                foreach ($types as $id => $name) {
                    $selected = '';
                    if($mode == 'edit'){
                        if($template['type'] == $id){
                            $selected = 'selected="selected"';
                        }
                    }
                    echo '<option '.$selected.'value="'.$id.'">'.$name.'</option>';
                }
                ?>
            </select>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="type">Type Flag</label>
          <div class="col-sm-6">
            <select class="form-control" id="type_flag" name="type_flag" required>
                <option value="">-- SELECT TYPE FLAG --</option>
                <?php 
                if($mode == 'edit'){
                    foreach ($type_flags[$template['type']] as $id => $name) {
                        $selected = '';
                        if($template['type_flag'] == $id){
                            $selected = 'selected="selected"';
                        }
                        echo '<option '.$selected.'value="'.$id.'">'.$name.'</option>';
                    }
                }
                ?>
            </select>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default"><?php echo $submit_button_label; ?></button>
          </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="/boot/js/bootstrap-validator.js"></script>
<script>
    $('#add_template_form').validator();
    function changeTypeFlag(type){
        
        var flags = '<?php echo json_encode($type_flags); ?>';
        flags = JSON.parse(flags);
        flags = flags[type];
        var flag_options = '<option value="">-- SELECT TYPE FLAG --</option>';
        $.each(flags,function(index,flag){
            flag_options += '<option value='+index+'>'+flag+'</option>';
        });
        if(flag_options != ''){
            $('#type_flag').html(flag_options);
        }
        
    }
</script>