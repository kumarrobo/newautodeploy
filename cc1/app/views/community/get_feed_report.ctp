<html>
    <head>
        <title> Listing Panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="/boot/css/serviceintegration.css">        
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
 
        <style>
            .row{
                margin-top: 20px;

            }
            thead{
                background-color: #2d67c4;
            }
        </style>
        <script>
        $(document).ready(function(){
            $('.table').dataTable({
             "order": [[0, "asc" ]],
            "pageLength":10,
            "lengthMenu": [10,100, 200, 500],
            });

        });
        </script>
    </head>
    <body>
        <div class="pull-right">                                                 
            <a href='/community/uploadPanel' class="btn btn-primary"  id='home_btn' name ='home_btn' >Upload Panel</a>
        </div>
        <h2>Community View Panel</h2>
        <br><br>
        <div class="row">
        <div>
            <table class="table table-hover table-responsive table-bordered ">
                <thead>
                <th>id</th>
                <th>Feed Type</th>
                <th>Title</th>
                <th>Representation Type </th>
                <th>Visibility</th>
                <!--<th>Action</th>-->
                </thead>              
                <?php
                $i = 1 ;
                foreach($feedval as $val => $key) { ?>
                <tr>
                <td><?php  echo $i;?></td>    
                <td><?php  echo $getfeedtype[$key['feed_type']]; ?></td>
                <td><?php  echo $key['title']; ?></td>
                <td><?php  echo $getreprType[$key['resource_representation_type']]; ?></td>
                    <td> <label class="switch">
                            <input type="checkbox" id="getfeed_<?php echo $key['feed_type'];?>" name="getfeed_<?php echo $key['feed_type'];?>" <?php echo ($key['visibility'] == '1')?"checked":""; ?> onchange="feedVisibility(<?php echo $key['id'] ?>,<?php echo $key['visibility']; ?>)"> <span class="slider round"></span>
                  </label>
                    </td><?php $resource = urlencode(json_encode($key['res'])) ?>
                    <!--- <td><input type="button" id='ViewFeed"'class="btn btn-primary" value="View More" onclick='showMoreFeed(<?php echo json_encode($key['res']);?>,<?php echo json_encode($key['small_icon']); ?>)'></td> -->
                    <!--<td><a href="setFeed/?id=<?php echo $key['id'];?>,res=<?php echo $resource;?> " type="button" id='ViewFeed"'class="btn btn-primary" >View More</a>-->
                <?php $i++; }?>
                    
                </tr>
            </table>
            
            

        </div>
        </div>
    </body>
</html>


<script>
    function feedVisibility(id,value){
      $.ajax({
        type: "POST",
        url: '/community/updateFeed',
        dataType: "json",
        data: {id:id,value:value},
        success: function (data) {
            alert("feed Updated Successfully");
        }
    });        
   }
    function showMoreFeed(key,key2){
        alert("gotcha");
        console.log(key);
        console.log(key2);
        var a = JSON.parse(key['0']['res_url']);
        
        console.log(a);
      
    }
    

</script>