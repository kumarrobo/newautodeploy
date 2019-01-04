<link href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" />
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>  
<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>  
<script>$(document).ready(function() {
        $('#listGroup').DataTable();
    });</script>
<style>div#listusers_filter{float: right;font-size: 12px;}div#listusers_length,div#listusers_info{font-size: 12px;}select.input-sm{height:22px;padding:0px;}</style>
<div class="col-lg-12">
        <div class="panel panel-pay1">
            <div class="panel panel-heading">Group Access</div>
                <div class="panel panel-body">
                    <?php $message = $this->Session->flash(); ?>
                    <?php if(!empty($message) && preg_match('/Errors/', $message)): ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" >&times;</a>
                            <p><?php echo $message; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($message) && preg_match('/Success/', $message)): ?>
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <p><?php echo $message; ?></p>
                        </div>
                    <?php endif; ?>
                    <table class="table table-condensed table-hover table-striped table-bordered" id="listGroup">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Group Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($groupList as $value){
                            echo '<tr>';
                            echo '<td>'.$value['groups']['id'].'</td>';
                            echo '<td>'.$value['groups']['name'].'</td>';
                            echo '<td>';
                            if($value['groups']['outside_access'] == 1){
                                echo '<a class="remaccess" id='.$value['groups']['id'].' style="color:red">Remove Access</a>';
                            }else{
                                echo '<a class="giveaccess" id='.$value['groups']['id'].' style="color:green">Give Access</a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                        
                    </table>
                </div>
        </div>
</div>
<script>
$(document).ready(function(){
    $(document).on('click','.giveaccess', function(){
        var id = $(this).attr('id');
        var conf = confirm('Are you sure?');
        
        if(conf == true){
            $('.giveaccess').attr('href','/acl/giveaccess/'+id);
        }
    });
    
    $(document).on('click','.remaccess', function(){
        var id = $(this).attr('id');
        var conf = confirm('Are you sure?');
        
        if(conf == true){
            $('.remaccess').attr('href','/acl/removeaccess/'+id);
        }
    });
});
</script>