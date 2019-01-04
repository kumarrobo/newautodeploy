
<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<div>
 <form role="form" id="updateform"  action="/shops/limitTransfer" method="POST" enctype="multipart/form-data">
  <table cellspacing ="0" cellpadding="0">
   <tr>
    
   <td>Upload File*</td>
   <td><input type="file" name="upload_file" id="uploadfile" accept="image/*" capture="camera"> <input type="button" id="upload" onclick="javascript:showloader();"  value="submit"> <label><img id="loader" style="display:none;" src="/img/loading.gif"/></</td>
    </tr>
 </table> 
     <?php 
      if (isset($transferRecord) && count($transferRecord) >0) {
    if (isset($transferRecord['transfer']) && count($transferRecord['transfer']) > 0) {
        ?>
             <table style="width: 100%;border:1px solid #ddd;">
                 <tr>
                     <td colspan="8" style="width:20px;border:1px solid #ccc;">Successfully Transfered</td>
                 </tr>
                 <tr>
                     <th style="width:20px;border:1px solid #ccc;">No.</th>
                     <th style="width:20px;border:1px solid #ccc;">Transaction ID</th>
                     <th style="width:20px;border:1px solid #ccc;">Value Date</th>
                     <th style="width:20px;border:1px solid #ccc;">Txn Posted Date</th>
                     <th style="width:20px;border:1px solid #ccc;">Description</th>
                     <th style="width:20px;border:1px solid #ccc;">Cr/Dr</th>
                     <th style="width:20px;border:1px solid #ccc;">Transaction Amount(INR)</th>
                     <th style="width:20px;border:1px solid #ccc;">Pay1 Trans ID</th>
                     <th style="width:20px;border:1px solid #ccc;">Success Msg</th>
                 </tr>
                 <?php 
                 $i = 1;
                 foreach ($transferRecord['transfer'] as $tranferkey => $transferval) {
                 
                  if ($i % 2 == 0)
                  $class = '';
                  else
                  $class = 'altRow'; ?>
                 <tr class="<?php echo $class; ?>">
                     <td style="align:center;"><?php echo $i; ?></td>
                         <td style="align:center;"><?php echo $transferval['2']; ?></td>
                         <td style="align:center;"><?php echo $transferval['3']; ?></td>
                         <td style="align:center;"><?php echo $transferval['4']; ?></td>
                         <td style="align:center;"><?php echo $transferval['6']; ?></td>
                         <td style="align:center;"><?php echo $transferval['7']; ?></td>
                         <td style="align:center;"><?php echo $transferval['8']; ?></td>
                         <td style="align:center;"><?php echo $transferval['txnid']; ?></td>
                         <td style="align:center;"><?php echo $transferval['msg']; ?></td>
                     </tr>
        <?php $i++;} ?>
             </table>
                      
                      
    <?php
    }
     if (isset($transferRecord['alreadydone']) && count($transferRecord['alreadydone']) > 0) { echo "<br/><br/>";
        ?>
             <table style="width: 100%;border:1px solid #ddd;">
                 <tr>
                     <th colspan="7">Already Done Transfer</td>
                 </tr>
                <tr>
                     <th style="width:20px;border:1px solid #ccc;">No.</th>
                     <th style="width:20px;border:1px solid #ccc;">Transaction ID</th>
                     <th style="width:20px;border:1px solid #ccc;">Value Date</th>
                     <th style="width:20px;border:1px solid #ccc;">Txn Posted Date</th>
                     <th style="width:20px;border:1px solid #ccc;">Description</th>
                     <th style="width:20px;border:1px solid #ccc;">Cr/Dr</th>
                     <th style="width:20px;border:1px solid #ccc;">Transaction Amount(INR)</th>
                     
                 </tr>
                 <?php  $i = 1; foreach ($transferRecord['alreadydone'] as $tranferkey => $transferval) {  
                     if ($i % 2 == 0)
                     $class = '';
                     else
                     $class = 'altRow'; ?>
                     <tr class="<?php echo $class; ?>">
                         <td style="align:center;"><?php echo $i; ?></td>
                         <td style="align:center;"><?php echo $transferval['2']; ?></td>
                         <td style="align:center;"><?php echo $transferval['3']; ?></td>
                         <td style="align:center;"><?php echo $transferval['4']; ?></td>
                         <td style="align:center;"><?php echo $transferval['6']; ?></td>
                         <td style="align:center;"><?php echo $transferval['7']; ?></td>
                         <td style="align:center;"><?php echo $transferval['8']; ?></td>
              
                         
                     </tr>
        <?php  $i++;} ?>
             </table>
    <?php
    }
     if (isset($transferRecord['failed']) && count($transferRecord['failed']) > 0) { echo "<br/><br/>";
        ?>
             <table style="width: 100%;border:1px solid #ddd;">
                 <tr>
                     <th colspan="7">Failed Transfer</th>
                 </tr>
                 <tr>
                     <th style="width:20px;border:1px solid #ccc;">No.</th>
                     <th style="width:20px;border:1px solid #ccc;">Transaction ID</th>
                     <th style="width:20px;border:1px solid #ccc;">Value Date</th>
                     <th style="width:20px;border:1px solid #ccc;">Txn Posted Date</th>
                     <th style="width:20px;border:1px solid #ccc;">Description</th>
                     <th style="width:20px;border:1px solid #ccc;">Cr/Dr</th>
                     <th style="width:20px;border:1px solid #ccc;">Transaction Amount(INR)</th>
                     <th style="width:20px;border:1px solid #ccc;">Description</th>
                     
                 </tr>
                 <?php $i =1 ;
                     foreach ($transferRecord['failed'] as $tranferkey => $transferval) {
                     if ($i % 2 == 0)
                     $class = '';
                     else
                     $class = 'altRow'; ?>
                    
                     <tr class="<?php echo $class; ?>">
                         <td style="align:center;"><?php echo $i; ?></td>
                         <td style="align:center;"><?php echo $transferval['2']; ?></td>
                         <td style="align:center;"><?php echo $transferval['3']; ?></td>
                         <td style="align:center;"><?php echo $transferval['4']; ?></td>
                         <td style="align:center;"><?php echo $transferval['6']; ?></td>
                         <td style="align:center;"><?php echo $transferval['7']; ?></td>
                         <td style="align:center;"><?php echo $transferval['8']; ?></td>
                         <td style="align:center;"><?php echo $transferval['msg']; ?></td>
                         
                     </tr>
        <?php $i++;} ?>
             </table>
                      
                      
    <?php
    }
}
?>
</form>
</div>
<script>
     jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
function showloader()
{
    if($("#uploadfile").val()==''){
      alert("Please upload file!!!");
      return false;
    } else {
    $("#upload").hide();
    $("#loader").show();
     $("#updateform").submit();
     }
        
}
</script>