<link rel="stylesheet" media="screen" href="/boot/css/c2d.css">

<style>
    
.show_switch {
  position: relative;
  display: inline-block;
  width: 53px;
  height: 22px;
  margin:  auto;
}


.svn_switch{
  position: relative;
  display: inline-block;
  width: 53px;
  height: 22px;
  margin:  auto;
}

.svnall_switch{
  position: relative;
  display: inline-block;
  width: 53px;
  height: 22px;
  margin:  auto;
}


.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 20px;
  left: 2px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

    
    .flash_message { text-align: center; color: #808080; margin-top: -20px;}
</style>

<div class="flash_message">
    <h5><?php echo $this->Session->flash(); ?></h5>
</div>
<nav class="navbar navbar-default">
        <div class="container-fluid">
                <div class = "row">	
                        <div class = "col-md-2">
                                <div class="navbar-header">
                                        <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'panels','action'=>'vendors'))); ?>
                                </div>
                        </div>
                       <div class = "col-md-8" align = "center">
                                <h2><b>Vendor Listing</b></h2>
                       </div>  
                </div>
        </div>
</nav>
<span>
    Page : <select onchange="window.location='/panels/vendors/'+this.value+'/<?php echo $recs ?>';" style="margin-right: 25px;">
                <?php for($i=1;$i<=ceil($totalrecords/$recs);$i++) { ?>
                <option <?php if($page == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
    Records / Page : <select onchange="window.location='/panels/vendors/1/'+this.value;" style="margin-right: 25px;">
                        <option <?php if($recs == 100) { echo "selected"; } ?>>100</option>
                        <option <?php if($recs == 500) { echo "selected"; } ?>>500</option>
                        <option <?php if($recs == 1000) { echo "selected"; } ?>>1000</option>
                        <option <?php if($recs == 5000) { echo "selected"; } ?>>5000</option>
                    </select>
    <strong>Total Records : <span style="color: blue;"><?php echo $totalrecords; ?></span></strong>
</span>
<span style="float:right;"><button style="height: 5%; color:red; margin: -9px 0px 0px 0px;" onclick="window.location='/panels/addEditVendor'">Add Vendor</button></span><br /><br />

<div class="tab-content">
        <div class="tab-pane active" id="list">
                <div class="table-responsive">
                        <table class="tablesorter table table-hover table-bordered" id = "plantable">
                                <thead>
                                        <tr>
                                                <th class = "field-label active" style = "width: 3%;">#</th>
                                                <th class = "field-label active" style = "width: 5%;">MACHINE ID</th>
                                                <th class = "field-label active" style = "width: 13%;">MACHINE NAME</th>
                                                <th class = "field-label active" style = "width: 10%;">SHORT FORM</th>
                                                <th class = "field-label active" style = "width: 5%;">TYPE</th>
                                                <th class = "field-label active" style = "width: 4%;">USER ID</th>
                                                <th class = "field-label active" style = "width: 5%;">MOBILE</th>
                                                <th class = "field-label active" style = "width: 7%;">
                                                    SVN FLAG
                                                    
                                                       <label class="svnall_switch">
                                                           <input type="checkbox" name = "svn_all" id="svn_all"> 
                                                      <div class="slider round"></div>
                                                     </label>
                                                    
                                                    
                                                </th>
                                                <th class = "field-label active" style = "width: 7%;">SHOW FLAG</th>
                                                <th class = "field-label active" style = "width: 12%;">UPDATED</th>
                                                <th class = "field-label active" style = "width: 3%;"><center>ACTION</center></th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php $type = array(0 => 'API', 1 => 'Modem'); ?>
                                        <?php foreach($listing_data as $list) { ?>
                                        <tr id="rec_<?php echo $list['vendors']['id']; ?>">
                                                <td><?php echo $list['vendors']['id']; ?></td>
                                                <td><?php echo $list['vendors']['machine_id']; ?></td>
                                                <td><?php echo $list['vendors']['company']; ?></td>
                                                <td><?php echo $list['vendors']['shortForm']; ?></td>
                                                <td><?php echo $type[$list['vendors']['update_flag']]; ?></td>
                                                <td><?php echo $list['vendors']['user_id'] != 0 ? $list['vendors']['user_id'] : '<strong><center>-</center></strong>'; ?></td>
                                                <td><?php echo $list['users']['mobile'] != '' ? $list['users']['mobile'] : '<strong><center>-</center></strong>'; ?></td>
                                                <td> <label class="svn_switch" data-id="<?php echo $list['vendors']['id']; ?>">
                                                      <input type="checkbox" class="flag" <?php if($list['vendors']['svn_flag'] == 1) { echo "checked"; } ?> data-size="mini">
                                                      <div class="slider round"></div>
                                                     </label></td>                          
                                                <td> <label class="show_switch"  data-id="<?php echo $list['vendors']['id']; ?>">
                                                      <input type="checkbox" class="flag" <?php if($list['vendors']['show_flag'] == 1) { echo "checked"; } ?> data-size="mini">
                                                      <div class="slider round"></div>
                                                     </label></td>
                                                <td><?php echo $list['vendors']['update_time'] != '0000-00-00 00:00:00' ? date('dS M Y',strtotime($list['vendors']['update_time'])).'&nbsp;&nbsp;&nbsp;'.date('H:i:s A',strtotime($list['vendors']['update_time'])) : '<strong><center>-</center></strong>'; ?></td>
                                                <!--<td><center><a href="/panels/addEditVendor/<?php echo $list['vendors']['id']; ?>">Edit</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#" class="delete" data-id="<?php echo $list['vendors']['id']; ?>">Delete</a></center></td>-->
                                                <td><center><a href="/panels/addEditVendor/<?php echo $list['vendors']['id']; ?>">Edit</a></center></td>
                                        </tr>
                                        <?php } ?>
                                </tbody>
                        </table>
                </div>
        </div>
</div>

<script>

    $('label.show_switch').change(function(e) {
            
            var id = $(this).data('id');
            
            $.post('/panels/changeFlag', {id: id, flag: 'show_flag'}, function(e) { if(e != 1) { alert("Something Went Wrong"); }}, 'json');
    });
    
    $('label.svn_switch').change(function(e) {
        
            var id = $(this).data('id');
            
            $.post('/panels/changeFlag', {id: id, flag: 'svn_flag'}, function(e) { if(e != 1) { alert("Something Went Wrong"); }}, 'json');
    });
    
    
   
    $('label.svnall_switch').change(function(e) {
         
     if(document.getElementById("svn_all").checked == true)
    {  
        var all= 1;
        document.getElementById("svn_all").checked == true;
       
    }    
    else {
        
        var all= 0;
        document.getElementById("svn_all").checked == false;

    }
        
         
                
                $.post('/panels/changeSVNFlag', {update_flag: all}, function(e) { if(e != 1) { alert("Something Went Wrong"); }}, 'json');
    });
    
    
//    $('.delete').click(function(e) {
//        
//            e.preventDefault();
//            var id = $(this).data('id');
//            
//            var res = confirm("Are You Sure ?");
//
//            if(res == true) {
//                    $.post('/panels/deleteRec', {id: id}, function(e) { e == 1 ? $("#rec_"+id).html('') : alert("Something Went Wrong"); }, 'json');
//            }
//    });
    
</script>
<script src="/js/jquery.min.js"></script>
<script src="/boot/js/highlight.js"></script>
<!--<script src="/boot/js/bootstrap-switch.js"></script>-->
<script src="/boot/js/main.js"></script>