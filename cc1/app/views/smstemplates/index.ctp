<style>
.pagination {
    margin: 0px;
}
.row{
    margin-bottom: 20px;
}
.input-group-btn .btn{
    font-size: 20px;
}
</style>
<title>SMS Templates</title>
<?php 


if($this->params['url']['page'] == ''){
    $this->params['url']['page'] = 1;
}
?>
<div class="templates container">
    
    <div class="row">
        <div class="col-md-3">
            <button onclick="location.href='/smstemplates/add?page=<?php echo $this->params['url']['page']; ?><?php echo ($this->params['url']['q'] != '') ? '&q='.$this->params['url']['q'] : ''; ?>'" class="btn btn-success btn-md" type="button">Add New Template</button>
        </div>
        <div class="col-md-6">
            <div>
                <?php echo $this->element('pagination');?>
            </div>
        </div>
        <div class="col-md-3">
            <!--<form class="navbar-form" role="search">-->
                <div class="input-group add-on">
                    <input class="form-control" placeholder="Search" name="query" id="query" type="text" value="<?php echo $this->params['url']['q']; ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick="javascript:if( $.trim($('#query').val()) == '' ){ return false; }goToPage('1');">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
            <!--</form>-->
        </div>
    </div>
   
     <?php 
        $params = $this->Session->read('Message.flash.params');
        unset($params['class']);
        if( count($params) > 0 ){ ?>
            <div id="flashMessage" class="alert alert-dismissable alert-warning">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="click to close">×</a>
                <ul class="list-group">
                    <?php 
                        foreach ($params as $variable_name => $variable_value) { ?>
                            <li class="list-group-item"><?php echo $variable_name.' = '.$variable_value; ?></li>
                        <?php }
                    ?>
                </ul>
            </div>
       <?php  }
        ?>
        <div id="flashMessage" class="message">
            <?php echo $this->Session->flash(); ?>
        </div>
       
        <?php
        if(is_array($templates) && count($templates) > 0 ){
            echo $this->element('smstemplates_container');
        } else { ?>
            <center>
                <div class="row">
                    <h2>No Records Found!</h2>
                    <a href="/smstemplates">Back</a>
                </div>
            </center>
        <?php }
         ?>
</div>
    <script>
        function goToPage($page){
            var $query = $('#query').val();
            
            if( $.trim($query) !== '' ){
                window.location.href = '?page='+$page+'&q='+$query;
            } else {
                window.location.href = '?page='+$page;
            }
        }
    </script>