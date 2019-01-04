<div class="container">
    
    <div class="row pull-right switch">
        <div class="col-lg-12">
                    <div class="btn-group">
                    <button class="btn btn-sm btn-default active"  autofocus="true" href="#modem_view" data-toggle="tab">Modem</button>
                    <button class="btn btn-sm btn-default" href="#operators_view" data-toggle="tab">Operator</button>
                    </div>
        </div>
    </div>
    
    <div class="tab-content">
        

    <?php echo $this->element('modem_view'); ?>

    <?php echo $this->element('operators_view_ajax'); ?>    
  
    <?php echo $this->element('modals_view'); ?>    
     </div>
    
 </div>