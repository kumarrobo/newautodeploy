<link href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" type="text/css"/>
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>  
<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js" type="text/javascript"></script>

<style>div#listusers_filter{float: right;font-size: 12px;}div#listusers_length,div#listusers_info{font-size: 12px;}select.input-sm{height:22px;padding:0px;}</style>
<div class="col-lg-12">
        <div class="panel panel-pay1">
            <div class="panel panel-heading">Group Users</div>
           
            <form id="reportform" name="reportform" class="form-inline" method="POST" action="/acl/groupUsers">
                <?php echo $this->Session->flash(); ?>
            <div class="form-group">
                <label for="sel1" style="padding:30px;" >Group Name</label>
                <select class="form-control" id="group" name="group" style= "width:200px; " >
                <option value="">Select Group</option>
                <?php foreach ($all_groups as $datas) { ?>
                <option <?php if($this->params['form']['group'] == $datas['groups']['id']) { echo "selected"; } ?> value="<?php echo $datas['groups']['id']; ?>" ><?php echo $datas['groups']['name']; ?></option>
                <?php } ?>
              </select>
            </div>
                <button type="submit"  class="btn btn-primary">Submit</button>
                <label for="sel1" style=" margin-left: 350px; " >Search :</label>  
                <input type="text" id="search" name="search" class="form-control" value="<?php echo $_POST['search']; ?>" placeholder="Search by ID OR Mobile" style="width: 18%;">&nbsp;
            </form>
            <div class="panel-body">
            <table class="table table-condensed table-hover table-striped table-bordered">
                        <thead>
                         <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($user as $users){
                                echo '<tr>';
                                echo '<td>'.$users['users']['id'].'</td>';
                                echo '<td>'.$users['users']['name'].'</td>';
                                echo '<td>'.$users['users']['mobile'].'</td>';
                                echo '</tr>';
                            }
                                ?>
                        </tbody>
            </table>
            </div>  
                <div class="row">
                <div class="col-md-7"></div>
                <div class="col-md-5 text-right">
                <?php echo $this->element('pagination'); ?>
                </div>
                </div>
            
            <script>
              function goToPage(page = 1) {
                  $('#reportform').attr('action','/acl/groupUsers/?page='+ page);
                  $('#reportform').submit();
                }
               function myFunction(){
               }
            </script>
        </div>
</div>

