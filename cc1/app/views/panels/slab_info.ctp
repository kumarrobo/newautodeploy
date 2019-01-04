<html>
    <head>        
        <title> Slab Description Panel</title>        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>        
        <script>
            $(document).ready(function () {
                $('#prod_list1').multiselect({
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeSelectAllOption: true
                });
            });
        </script>
    </head>    
    <body>             
        <div class="col-md-1 pull-right"> 
            <button class="btn btn-alert"  id='slab_home_btn' name ='slab_home_btn'><a href='/panels/slabReport'target='_blank' >Slab Home</a>
        </div>
        <div class="container">
            <div class="page-header">
                <h1> Slab Update Panel</h1>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Product</th>                    
                    <th>Discount</th>
                    <th>Service Charge</th>
                    <th>Service Tax</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($slab_desp as $sdesp) { ?>
                    <tr> <td> <?php echo $i; ?></td>
                        <td> <label id='service_name' name='service_name'><?php echo $sdesp['serv']['name']; ?></label> </td> 
                        <td> <label id='product_name' name='product_name'><?php echo $sdesp['p']['name']; ?></label> </td>
                        <td> <input type="input" name="prod_dis_<?php echo $sdesp['p']['id']; ?>" id="prod_dis_<?php echo $sdesp['p']['id']; ?>" style="width:150px;" onkeypress="return event.charCode >= 46 && event.charCode <= 57"
                                    value="<?php echo $sdesp['sp']['percent']; ?>" class="form-control" disabled="true"></td>
                        <td> <input type="input" name="prod_sc_<?php echo $sdesp['p']['id']; ?>" id="prod_sc_<?php echo $sdesp['p']['id']; ?>" style="width:150px;" onkeypress="return event.charCode >= 46 && event.charCode <= 57"
                                    value="<?php echo $sdesp['sp']['service_charge']; ?>" class="form-control" disabled="true"></td>
                        <td>
                            <select  class="form-control"  name="prod_tax_<?php echo $sdesp['p']['id']; ?>" id="prod_tax_<?php echo $sdesp['p']['id']; ?>" disabled="true">                                                            
                                <option value="0" <?php echo ($sdesp['sp']['service_tax'] == 0) ?   "selected " : '';?>>0</option>
                                <option value="1" <?php  echo ($sdesp['sp']['service_tax'] == 1) ?  "selected" : '';?>>1</option>
                            </select>
                        </td>
                        <td> <button type="button" id="slab_prod_edit_<?php echo $sdesp['p']['id']; ?>" onclick="edit(<?php echo $sdesp['p']['id']; ?>,<?php echo $sdesp['serv']['id']; ?>)" class="btn btn-primary">Update</button> </td>
                        <td><button type="button" onclick="slab_product_change(<?php echo $sdesp['sp']['id']; ?>,<?php echo $sdesp['p']['id']; ?>)" class="btn btn-primary" id="slab_prod_update_<?php echo $sdesp['p']['id']; ?>" disabled="true">submit</button></td>
                    </tr>
                    <?php $i++; } ?>
            </tbody>
            <?php if (!empty($prod_det)) { ?>
            </table>           
            <h1> Insert Product</h1>  
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>                    
                        <th>Discount</th>
                        <th>Service Charge</th>
                        <th>Service Tax</th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td>1</td>                                
                        <td> 
                            <!--                       <div class="col-md-2 type">                                                                                    
                            
                                                       <select  class="form-control" style='width:300px;float:left;' name="prod_list[]" id="prod_list" multiple="multiple">                            
                            <?php foreach ($prod_det as $prod): ?>
                                                                <option value="<?php echo $prod['products']['id']; ?>"><?php echo $prod['products']['name']; ?></option>
                            <?php endforeach; ?>
                                                    </select>
                                                   </div>-->
                            <select  class="form-control" style='width:300px;float:left;' name="prod_list" id="prod_list" disabled="true">                            
                                <option>Select</option>
                                <?php foreach ($prod_det as $prod): ?>
                                    <option value="<?php echo $prod['products']['id']; ?>"><?php echo $prod['products']['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td> <input type="input" name="ins_prod_dis" id="ins_prod_dis" style="width:150px;"
                                    value="0.00" class="form-control" onkeypress="return event.charCode >= 46 && event.charCode <= 57" disabled="true"></td>
                        <td> <input type="input" name="ins_prod_sc" id="ins_prod_sc" style="width:150px;"
                                    value="0" class="form-control" onkeypress="return event.charCode >= 46 && event.charCode <= 57" disabled="true"></td>
                        <td> <input type="input" name="ins_prod_tax" id="ins_prod_tax" style="width:150px;"
                                    value="0.0" class="form-control" onkeypress="return event.charCode >= 46 && event.charCode <= 57" disabled="true"></td>          
                        <td> <button type="button"id="ins_slab_prod_edit" onclick="ins_edit()" class="btn btn-primary">Update</button> </td>
                        <td><button type="button" onclick="ins_slab_product(<?php echo $sdesp['s']['id']; ?>)" class="btn btn-primary" id="ins_slab_prod_update" disabled="true">submit</button></td>                        
                    </tr>
                </tbody>
            </table>   
        <?php } ?>
        <script>
            function ins_edit() {
                $('#prod_list').removeAttr('disabled');
                $('#ins_prod_dis').removeAttr('disabled');
                $('#ins_prod_sc').removeAttr('disabled');
                $('#ins_prod_tax').removeAttr('disabled');
                $('#ins_slab_prod_update').removeAttr('disabled');                                
                $('#ins_slab_prod_edit').prop('disabled', true);

            }
            function edit(id,sid) {                
                
                if(sid == 4){
                $('#prod_dis_' + id).prop('disabled', true);
                $('#prod_sc_' + id).removeAttr('disabled');
                $('#prod_tax_' + id).removeAttr('disabled');
                }
                else {
                $('#prod_sc_' + id).prop('disabled', true);
                $('#prod_tax_' + id).prop('disabled', true);   
                $('#prod_dis_' + id).removeAttr('disabled');
                }
                $('#slab_prod_update_' + id).removeAttr('disabled');
                $('#slab_prod_edit_'+ id).prop('disabled', true);
                
                
            }

            function ins_slab_product(id) {
//                var prod_name = [$('#prod_list').val()];
                var prod_name = $('#prod_list').val();
                var prod_disc = $('#ins_prod_dis').val();
                var prod_sc = $('#ins_prod_sc').val();
                var prod_tax = $('#ins_prod_tax').val();

                $.ajax({
                    type: "POST",
                    url: '/panels/slabDataUpdate',
                    dataType: "json",
                    data: {ins_id: id, prod_name: prod_name, ins_p_dis: prod_disc, ins_p_sc: prod_sc, ins_p_tax: prod_tax},

                    success: function (data) {
                        $('#prod_list').prop('disabled',true);
                        $('#ins_prod_dis').prop('disabled', true);
                        $('#ins_prod_sc').prop('disabled', true);
                        $('#ins_prod_tax').prop('disabled', true);
                        alert("Product Inserted Successfully");                      
                         $('#ins_slab_prod_update').prop('disabled', true);
                         location.reload();

                    },
                    error: function (data) {
                        alert("error found");
                    }
                });

            }
            function slab_product_change(sp_id, id) {
                var p_dis = $('#prod_dis_' + id).val();
                var p_sc = $('#prod_sc_' + id).val();
                var p_tax = $('#prod_tax_' + id).val();

                $.ajax({
                    type: "POST",
                    url: '/panels/slabDataUpdate',
                    dataType: "json",
                    data: {sp_id: sp_id, p_dis: p_dis, p_sc: p_sc, p_tax: p_tax},

                    success: function (data) {
                        $('#prod_dis_' + id).prop('disabled', true);
                        $('#prod_sc_' + id).prop('disabled', true);
                        $('#prod_tax_' + id).prop('disabled', true);
                        alert("Changes Updated Successfully");
                        $('#slab_prod_update_'+id).prop('disabled', true);
                        location.reload();

                    },
                    error: function (data) {
                        alert("error found");
                    }
                });
            }
        </script>
    </body>
</html>
