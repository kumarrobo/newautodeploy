<link href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" />
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>  
<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>  
<script>$(document).ready(function() {
        $('#listusers').DataTable();
    });</script>
<style>div#listusers_filter{float: right;font-size: 12px;}div#listusers_length,div#listusers_info{font-size: 12px;}select.input-sm{height:22px;padding:0px;}</style>
<div class="col-lg-12">
    <div class="panel panel-pay1">
        <div class="panel-heading">List User</div>
        <div class="panel-body">
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
            <table class="table table-condensed table-hover table-striped table-bordered" id="listusers">
                <thead>
                    <tr>
                        <th>Id</th>    
                        <th>User Name</th>
                        <th>Mobile Number</th>
                        <th>Group</th>
                        <th><a  href="/acl/add/">Add User</a> | <a  href="/acl/addGroup/">Add Group</a> </th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($userData as $val): ?>

                    <tr>
                        <td><?php echo $val['users']['id'] ?></td>
                        <td><?php echo $val['users']['username'] ?></td>
                        <td><?php echo $val['users']['mobile'] ?></td>
                        <td><?php echo $val[0]['groups'] ?></td>
                        <td>
                            <a href="/acl/edit/<?php echo $val['users']['id'];?>">Edit</a> | 
                            <a onclick="change_outside_access_status(<?php echo $val['users']['id'];?>,<?php echo ($val['users']['outside_access'] == 0) ? 1 : 0?>);" style="<?php echo ($val['users']['outside_access'] == 0) ? '' : 'color:red;'?>" class="active" id="<?php echo $val['users']['id'];?>" ><?php echo ($val['users']['outside_access'] == 0) ? "External Access" : "Remove Access"?></a> | 
                            <a class="deactivate" id="<?php echo $val['users']['id']; ?>"><?php echo ($val['users']['active_flag'] == 0) ? 'Activate'  : 'Deactivate';?></a>
                            <input type='hidden' id='deactivate_flag' value='<?php echo $val['users']['active_flag'];?>'>
                        </td>
                    </tr>

                    <?php endforeach; ?>


                </tbody>

            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
       $(document).on('click', '.deactivate', function () {
            var id = $(this).attr('id');
            var flag = $('#deactivate_flag').val();
            var conf = confirm('Are you sure?');
            
            if(conf == true){
                $('.deactivate').attr('href','/acl/delete/'+id+'/'+flag);
            }
       }); 
    });
    
   function change_outside_access_status(id,val){
        var check= confirm("Please click on OK to continue.");
        if(check==true)
        {
            $('.active').attr('href','/acl/externalaccess/'+id+'/'+val);
        }
   }
</script>