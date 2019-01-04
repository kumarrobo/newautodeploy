  <div class="col-lg-10 col-lg-offset-1">
        <div class="panel panel-pay1">
            <div class="panel-heading">List User</div>
            <div class="panel-body">
                    <button onclick="location.href='/acl/listUser'" class="btn btn-default btn-sm pull-right" type="button">Back to lists</button>
                    <table class="table table-condensed table-hover table-striped">
                    <thead>
                    <tr>
                     <th>Id</th>    
                    <th>Name</th>
                     <th><a  href="/acl/add/">Add User</a> | <a  href="/acl/addGroup/">Add Group</a> </th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($group as $val): ?>

                    <tr>
                     <td><?php echo $val['groups']['id'] ?></td>    
                    <td><?php echo $val['groups']['name'] ?></td>
                
                    <td><a target="_blank" href="/acl/addGroup/<?php echo $val['groups']['id'];?>">Edit</a>
                    </td>
                    </tr>

                    <?php endforeach; ?>


                    </tbody>

                    </table>
            </div>
        </div>
  </div>