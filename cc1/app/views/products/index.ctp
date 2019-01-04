<?php echo $this->element('product_sidebar'); ?>
<div style="float:left; margin-left: 20px; width:75%;">
    <title>Products</title>
    <div class="panel panel-default">
      <div class="panel-heading">Products</div>
      <div class="panel-body">
        <form method="get" id="list_form" role="form" action="/products/edit">
            <div class="form-group">
                <label class="control-label col-sm-2 " for="product_id">Select a product: </label>   
                <div class="col-sm-4"> 
                   <select class="form-control" id="product_id" name="product_id">
                           <?php foreach($products as $p): ?>
                                   <option value="<?php echo $p['products']['id'] ?>"  <?php if($product_id == $p['products']['id']) echo "selected" ?>>
                                           <?php echo $p['products']['name'] ?>
                                   </option>
                           <?php endforeach; ?>
                   </select>
                </div>
                <div class="form-group"> 
                   <div class="col-sm-2">
                           <button type="submit" class="btn btn-success btn-sm">Edit</button>
                   </div>
               </div>
            </div>
        </form>
      </div>
    </div>
</div>