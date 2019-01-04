<!DOCTYPE html>
<html>
<head>
  <title> List Service Partner</title> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" media="screen" href="/boot/css/select2.css">
  <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
  <link rel="stylesheet" href="/boot/css/serviceintegration.css">
  <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="/boot/js/bootstrap-3.3.7.min.js"></script>  
  <script type="text/javascript" src="/boot/js/select2.js"></script>
<script type="text/javascript" src="/boot/js/serviceintegration.js"></script>
`
  
<body>
    <div class="row">
          <a type="button"  href="/serviceintegration/servicesForm" id='serviceIntegration' name="serviceIntegration" class="btn btn-primary" >Home</a>          
    </div>    
<h2>List Service Partner</h2>
<div class="container">
<form id="servcForm" name="servcForm" method="POST">
  <div class="table-responsive">
  <table class="table">
      <thead>
          <tr>
              <th> Id </th>
              <th> Key </th>
              <th> Security Key </th>
              <th> Name  </th>
              <th> Salt </th>
              <th> Callback </th>
              <th> Params </th>
              <th> Redirect </th>              
              <th> Action </th>
              
          </tr>
      </thead>
      <tbody>          
          <?php $i=1;
                     foreach($servicePartner as $servcPartner) {?>
              <td>  <?php echo $servcPartner['service_partners']['id']; ?></td>
              <td> <input type="text" class="form-control" id="upservPKey_<?php echo $servcPartner['service_partners']['id'];?>" name="upservPKey_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['key']; ?>" disabled="true">  </td>
              <td> <input type="text" class="form-control" id="upservPSecKey_<?php echo $servcPartner['service_partners']['id'];?>" name="upservPSecKey_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['secret_key']; ?>" disabled="true">  </td>
              <td> <input type="text" class="form-control" id="upservPName_<?php echo $servcPartner['service_partners']['id'];?>" name="upservPName_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['name']; ?>" disabled="true">  </td>
              <td> <input type="text" class="form-control" id="upservPSalt_<?php echo $servcPartner['service_partners']['id'];?>" name="upservPSalt_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['salt']; ?>" disabled="true">  </td>
              <td> <textarea class="form-control" id="upservPCallback_<?php echo $servcPartner['service_partners']['id'];?>" name="upservPCallback_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['callback']; ?>" disabled="true"><?php echo $servcPartner['service_partners']['callback']; ?></textarea> </td>
              <td> <textarea type="text" class="form-control" id="upservPRedirect_<?php echo $servcPartner['service_partners']['id'];?>" name="upservP_Redirect_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['redirect']; ?>" disabled="true"><?php echo $servcPartner['service_partners']['redirect']; ?></textarea></td>
              <td> <textarea type="text" class="form-control" id="upservPParams_<?php echo $servcPartner['service_partners']['id'];?>" name="upservPParams_<?php echo $servcPartner['service_partners']['id'];?>" value = "<?php echo $servcPartner['service_partners']['params']; ?>" disabled="true"><?php echo $servcPartner['service_partners']['params']; ?></textarea>  </td>
<!--              <td> <label class="switch">
                      <input type="checkbox" id="upservStatus_<?php echo $servc['services']['id'];?>" name="upservStatus_<?php echo $servc['services']['id'];?>" <?php echo ($servc['services']['toShow'] == '1')?"checked":""; ?> disabled="true"> <span class="slider round"></span>
                  </label>
              </td>-->
              <td>
                  <button type="button" class="btn btn-primary" id="upservpartner_enb_<?php echo $servcPartner['service_partners']['id'];?>" name="upservpartner_enb_<?php echo $servcPartner['service_partners']['id'];?>" onclick="servcPartnerupdEnable(<?php echo $servcPartner['service_partners']['id']; ?>)">Update</button>  
                  <button type="button" class="btn btn-primary" id="upservpartner_<?php echo $servcPartner['service_partners']['id'];?>" name="upservpartner_<?php echo $servcPartner['service_partners']['id'];?>" disabled="true" onclick="servcPartnerUpdate(<?php echo $servcPartner['service_partners']['id']; ?>)">Submit</button>  
              </td>             
      </tbody>  
      <?php $i++; } ?>
      </table>
  </div>
  </form>
</div>
</body>
</html>