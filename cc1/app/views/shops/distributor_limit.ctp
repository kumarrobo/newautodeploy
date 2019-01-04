<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css" type="text/css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css" type="text/css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css" type="text/css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>


<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    
    thead{
        background-color: #428bca;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f6f6f6;
    }
    
     .modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }
</style>

<h2> <tr><center><td>Distributors Limit</td></center></tr></h2>
<table class="table table-bordered table-hover table-striped table-responsive" style="width:900px;">            
    <thead>             
        <tr>   
            <th style="text-align:center;">ID</th>
            <th style="text-align:center;">Company Name</th>
            <th style="text-align:center;">Mobile</th>
            <th style="text-align:center;">Limit</th>
            <th> </th>
        </tr>
    </thead>
    <tbody style="color: #4e4e4e;">
        <?php 
       
        foreach ($result as $datas) {
            $user_id = $datas['distributors']['user_id'];
            echo '<tr><td style="padding: 5px;"><b><center>'. $datas['distributors']['id'] . '</center></b></td>';
            echo '<td style="padding: 5px;"><b><center>' . $result_names[$user_id]['imp']['shop_est_name']. '</center></b></td>';
            echo '<td style="padding: 5px;"><b><center>' . $datas['distributors']['mobile'] . '</center></b></td>';
            echo '<td style="padding: 5px;"><b><center id="limit_'.$datas['distributors']['id'].'">' . $datas['distributors']['max_limit'] . '</center></b></td>';
            echo '<td style="padding: 5px; color:green;" id="edit_'.$datas['distributors']['id'].'"><b><center> <a href="javascript:void(0)"  onclick="updatelimit('. $datas['distributors']['id'] .',\''.$result_names[$user_id]['imp']['shop_est_name'].'\','.$datas['distributors']['mobile'].','.$datas['distributors']['max_limit'].');">Edit</a> </center></b></td></tr>';;
        }
        ?>
    </tbody>
    
</table>

<div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        
<span class="close">&times;</span>
<h3>Update Limit</h3>
        
            <form id="limit" style="padding :30px;">
                
                <div class="form-group">
                <label for="id" >Id : </label>
                <input type="text" class="form-control" id="id"  name="id" readonly>
                
                <label for="name" >Company Name : </label>
                <input type="text" class="form-control" id="name"  name="name" readonly>
             
                <label for="mobile" >Mobile : </label>
                <input type="text" class="form-control" id="mobile"  name="mobile" readonly>
                
                <label for="limit">Limit : </label>
                <input type="text" class="form-control" id="max_limit"  name="limit">
                <br>
                <button type="button" class="btn btn-default" onclick="submit1()" id="update" >Submit</button>
                
                <div id='innerDiv'></div>
                
                <?php // echo $ajax->submit('Submit', array('url' => array('controller' => 'accounting', 'action' => 'distributorlimit'), 'class' => 'btn btn-primary ', 'update' => 'innerDiv')); ?>
            
                </div>
            </form>
      </div>
      
    </div>
  </div>
<script>
    $('.table').dataTable({
//        "order": [[0, "desc" ]],
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
        "pageLength":100,
        "lengthMenu": [100, 200, 500],
    });
    function updatelimit(id,company,mobile,max_limit){
        document.getElementById('id').value=id;
        document.getElementById('name').value=company;
        document.getElementById('mobile').value=mobile;
        document.getElementById('max_limit').value=max_limit;
        var modal = document.getElementById('myModal');
        modal.style.display = "block";
//    var btn = document.getElementById("click");
        var span = document.getElementsByClassName("close")[0];
//    btn.onclick = function() {
//        modal.style.display = "block";
//    }
        window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
//            location.reload();
            $("#update").show();
            $('#innerDiv').html("");
            }
        }
        span.onclick = function() {
        modal.style.display = "none";
//        location.reload();
        $("#update").show();
        $('#innerDiv').html("");
    }
    }
    
    function submit1(){
        var id=$("#id").val();
        var limit=$("#max_limit").val();
        
        $.ajax({ url: '/shops/distributorLimit', 
            type: 'POST', 
            data: $('#limit').serialize(),
            dataType: 'json', 
            success: function (data) { 
                
                $('#innerDiv').html(data.msg); 
                if(data.to_save){
                $('#limit_'+id).html(limit);
                $("#edit_"+id).html('Updated');
                $("#update").hide();
            }
                //setTimeout(function(){ window.location = "/accounting/distributorlimit"; }, 2000);
            } 
        });

    }
    
    
    
    
</script>
