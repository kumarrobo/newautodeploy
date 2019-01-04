
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<?php
error_reporting(0);
$body = "";

$body.="<input type='hidden' id='pass_wrd' value=''>";
$type = array("1" => array("1"),
        "2" => array("1", "2","3"),
        "3" => array("1","2"),
        "4" => array("1", "2"),
        "6" => array("1"),
        "7" => array("1","2"),
        "8" => array("1","2"),
        "9" => array("1"),
        "10" => array("1"),
        "11" => array("1","2"),
        "12" => array("1"),
        "13" => array("1"),
        "14" => array("1"),
        "15" => array("1", "2"),
        "16" => array("1", "2","3"),
        "17" => array("1"),
        "18" => array("1", "2"),
        "19	" => array("1"),
        "20" => array("1"),
        "21" => array("1"),
        "27" => array("1"),
        "28" => array("1"),
        "29" => array("1","2"),
        "30" => array("1", "2", "3", "4", "5","6",'7'),
        "31" => array("1", "2", "3", "4", "5","6",'7'),
        "34" => array("1")
);

$oprType = json_encode($type);
?>
<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<?php
$body.="<form method=\"post\" id=\"simform\" method=\"post\">" .
        "<input type=\"hidden\" id='vendor_id' value='" . $VendorId . "'>";
if (!empty($simData['New Sims']) && count($simData['New Sims']) > 0) {

    $body.="<table style=\"width: 100%;border:1px solid #ddd\"><tr width='100%'><td colspan='14'><b style=\"color:red;\">" . 'New Sims' . "</b></td></tr>";

    $body.="<tr>
          <th style=\"width:20px;border:1px solid #ccc;\">Sim No</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Port No</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Operator</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Mobile</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Circle</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Type</th>";
	if($_SESSION['Auth']['User']['group_id'] != 9){
          $body.="<th style=\"width:20px;border:1px solid #ccc;\">Pin</th>";
		}
          $body.="<th style=\"width:20px;border:1px solid #ccc;\">Balance</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Commision</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Limit</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Roaming limit</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Vendor Tag</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Vendor ID</th>";
      if($_SESSION['Auth']['User']['group_id'] != 9){
          $body.="<th style=\"width:20px;border:1px solid #ccc;\">Action</th>";
		}
          $body.="</tr>";
    $i = 0;
    foreach ($simData['New Sims'] as $k => $v) {
        if ($i % 2 == 0)
            $class = '';
        else
            $class = 'altRow';
        $body.="<tr class=" . $class . ">
            <td id='sim_" . $v['id'] . "'>" . $v['scid'] . "</td>
            <td>" . $v['device_num'] . "</td>
            <td><select disabled=\"disabled\" onchange='operatorchange(" . $v['id'] . ")' class=" . $v['id'] . " id='opr_" . $v['id'] . "'><option value=''>---Select Operator----</option>";
        foreach ($oprData as $oprkey => $oprval) {
            $selected = "";
            if ($oprval['products']['id'] == $v['opr_id']) {
                $selected = "selected";
            }
            $body.="<option value='" . $oprval['products']['id'] . "' $selected>" . $oprval['products']['name'] . "</option>";
        }
        $body.="</select><div class='multiple_records_".$v['id']."' style='display:none;'><input type='checkbox' class='multiple_".$v['id']."'  value=''><span id='multiple_".$v['id']."'></span></div></td>
            <td><input type=\"text\" value='" . $v['mobile'] . "'  class=" . $v['id'] . " disabled=\"disabled\" id='mobile_" . $v['id'] . "'></td>
            <td><select name=\"circle\"  class=" . $v['id'] . " disabled=\"disabled\" id='circle_" . $v['id'] . "'>";
        	$body.="<option value=''>Select</option>";
			$body.="<option value=''>Select Circle</option>";
        foreach ($circleData as $circlekey => $circleval) {
            $selected = "";
            if ($circleval['mobile_numbering_area']['area_code'] == $v['circle']) {
                $selected = "selected";
            }
            $body.="<option value=" . $circleval['mobile_numbering_area']['area_code'] . " $selected>" . $circleval['mobile_numbering_area']['area_code'] . "</option>";
        }
        $body.="</td></select><td><select name=\"type\" disabled=\"disabled\" class=" . $v['id'] . " id='type_" . $v['id'] . "'>";
        if (isset($type[$v['opr_id']])) {
            foreach ($type[$v['opr_id']] as $typekey => $typeval) {
                $selected = "";
                if ($typeval == $v['type']) {
                    $selected = "selected";
                }
                $body.="<option value=" . $typeval . " $selected>" . $typeval . "</option>";
            }
        }
        $body.="</select></td>
		 <input type='hidden' value='" . $v['id'] . "' id='parbal_" . $v['id'] . "'><input type='hidden' value='" . $v['scid'] . "' id='sim_" . $v['id'] . "'>
          <input type='hidden' value='" . $v['machine_id'] . "' id='machine_" . $v['id'] . "'>";
		if($_SESSION['Auth']['User']['group_id'] != 9){
          $body.="<td><input  style=\"width:60px;\" type='password' class='pass " . $v['id'] . "' disabled=\"disabled\" value='" . $v['pin'] . "' id='pass_" . $v['id'] . "'>
          <a href=\"#\" onclick=\"showpassmodal('" . $v['id'] . "')\">Show Pin</a>
          </td>";
		  }
          $body.="<td id='bal_" . $v['id'] . "'>" . $v['balance'] . "</td>
          <td><input type=\"text\" value='" . $v['commission'] . "'  id='comm_" . $v['id'] . "'disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td><input type=\"text\" value='" . $v['limit'] . "' id='limit_" . $v['id'] . "' disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td ><input type=\"text\" value='" . $v['roaming_limit'] . "' id='roaming_" . $v['id'] . "' disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td ><input type=\"text\" value='" . $v['vendor'] . "' id='vendor_" . $v['id'] . "'  disabled=\"disabled\" class=" . $v['id'] . "></td>";
        
         //Supplier Id  
          $body.="<td>";
          $body.="<select disabled='disabled' class='".$v['id']."' name='inv_supplier_id_".$v['id']."'>";
          $body.="<option value=''>Select Vendor</option>";
          foreach($vendors as $vendor):
              $selected=($v['inv_supplier_id']==$vendor['suppliers']['id'])?"selected":"";
           //   $selected=($v['vendor']==$vendor['suppliers']['name'])?"selected":"";
              $body.="<option value='".$vendor['suppliers']['id']."'  $selected >".$vendor['suppliers']['name']."</option>";
          endforeach;
          $body.="</select>";
          $body.="</td>";
          if($_SESSION['Auth']['User']['group_id'] != 9){
         $body.="
          <td id ='edit_" . $v['id'] . "'><input type=\"button\" value=\"edit\" onclick=\"editdata(" . $v['id'] . ");\"></td>
          <td id ='insert_" . $v['id'] . "'><input type=\"button\" value=\"insert\" onclick=\"insertdata(" . $v['id'] . ");\"></td> 
          <td  id ='save_" . $v['id'] . "' style=\"display:none;\"><input type=\"button\" value=\"save\"  onclick=\"savedata(" . $v['id'] . ");\"></td>";
		  }
          $body.="</tr>";
        $i++;
    }
}
$body.="</table>";
echo $body;

echo "<br/><br/>";
?>
Upload Excel File Over Here : 


<form  action='/sims/getModemsimsDetails' method="post" enctype="multipart/form-data">
<input type="file" name="simentry" id="simentry" />
<input type="submit" value="Submit">
</form>

<?php
if (!empty($simData['Existing Sims']) && count($simData['Existing Sims']) > 0) {
    $body1 = "";
    $body1.="<table style=\"width: 100%;border:1px solid #ddd\"><tr width='100%'><td colspan='14'><b style=\"color:red;\">" . 'Existing  Sims' . "</b></td></tr>";
    $body1.="<tr>
          <th style=\"width:20px;border:1px solid #ccc;\">Sim No</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Port No</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Operator</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Mobile</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Circle</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Type</th>";
	if($_SESSION['Auth']['User']['group_id'] != 9){
          $body1.="<th style=\"width:20px;border:1px solid #ccc;\">Pin</th>";
	}
          $body1.="<th style=\"width:20px;border:1px solid #ccc;\">Balance</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Commision</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Limit</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Roaming limit</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Vendor Tag</th>";
         $body1.=" <th style=\"width:20px;border:1px solid #ccc;\">Vendor ID</th> "
				 ."<th style=\"width:20px;border:1px solid #ccc;\">Merge</th>"
				 . "<th style=\"width:20px;border:1px solid #ccc;\">Modem</th>"
				 . "<th style=\"width:20px;border:1px solid #ccc;\">Block Tag</th>"
				 . "<th style=\"width:20px;border:1px solid #ccc;\">New Sim</th>"; 
				
          $body1.=" <th style=\"width:20px;border:1px solid #ccc;\">Block</th>"; 
       //   $body1.=" <th style=\"width:20px;border:1px solid #ccc;\">Vendor Tag</th>"; 
		  if($_SESSION['Auth']['User']['group_id'] != 9){
        $body1.="
          <th style=\"width:40px;border:1px solid #ccc;\">Action</th>";
		  }
          $body1.="</tr>";
		
    $i = 0;
    foreach ($simData['Existing Sims'] as $k => $v) {
        if ($i % 2 == 0)
            $class = '';
        else
            $class = 'altRow';
        $body1.="<tr class=" . $class . ">
            <td id='sim_" . $v['id'] . "' >" . $v['scid'] . "</td>
            <td>" . $v['device_num'] . "</td>
            <td><select disabled=\"disabled\" onchange='operatorchange(" . $v['id'] . ")' class=" . $v['id'] . " id='opr_" . $v['id'] . "'><option value=''>---Select Operator----</option>";
        foreach ($oprData as $oprkey => $oprval) {
            $selected = "";
            if ($oprval['products']['id'] == $v['opr_id']) {
                $selected = "selected";
            }
            $body1.="<option value='" . $oprval['products']['id'] . "' $selected>" . $oprval['products']['name'] . "</option>";
        }
        $body1.="</select><div class='multiple_records_".$v['id']."' style='display:none;'><input type='checkbox' class='multiple_".$v['id']."'  value=''><span id='multiple_".$v['id']."'></span></div></td>
            <td><input type=\"text\" value='" . $v['mobile'] . "'  class=" . $v['id'] . " disabled=\"disabled\" id='mobile_" . $v['id'] . "'></td>
            <td><select name=\"circle\"  class=" . $v['id'] . " disabled=\"disabled\" id='circle_" . $v['id'] . "'>";
        $body1 .="<option value=''>Select</option>";
        foreach ($circleData as $circlekey => $circleval) {
            $selected = "";
            if ($circleval['mobile_numbering_area']['area_code'] == $v['circle']) {
                $selected = "selected";
            }
            $body1.="<option value=" . $circleval['mobile_numbering_area']['area_code'] . " $selected>" . $circleval['mobile_numbering_area']['area_code'] . "</option>";
        }
        $body1.="</td></select><td><select name=\"type\" disabled=\"disabled\" class=" . $v['id'] . " id='type_" . $v['id'] . "'>";
        if (isset($type[$v['opr_id']])) {
            foreach ($type[$v['opr_id']] as $typekey => $typeval) {
                $selected = "";
                if ($typeval == $v['type']) {
                    $selected = "selected";
                }
                $body1.="<option value=" . $typeval . " $selected>" . $typeval . "</option>";
            }
        }
        $body1.="</select></td>
			<input type='hidden' value='" . $v['par_bal'] . "' id='parbal_" . $v['id'] . "'><input type='hidden' value='" . $v['scid'] . "' id='sim_" . $v['id'] . "'>
          <input type='hidden' value='" . $v['machine_id'] . "' id='machine_" . $v['id'] . "'>";
		if($_SESSION['Auth']['User']['group_id'] != 9){
          $body1.="<td><input type='password' style=\"width:60px;\" class='pass " . $v['id'] . "' disabled=\"disabled\" value='" . $v['pin'] . "' id='pass_" . $v['id'] . "'>
          <a style=\"width:20px;\" href=\"#\" onclick=\"showpassmodal('" . $v['id'] . "')\">Show Pin</a>
		</td>";
		}
          $body1.="<td id='bal_" . $v['id'] . "'>" . $v['balance'] . "</td>
          <td><input type=\"text\" value='" . $v['commission'] . "'  id='comm_" . $v['id'] . "'disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td><input type=\"text\" value='" . $v['limit'] . "' id='limit_" . $v['id'] . "' disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td ><input type=\"text\" value='" . $v['roaming_limit'] . "' id='roaming_" . $v['id'] . "' disabled=\"disabled\" class=" . $v['id'] . "></td>";
          $body1.="<td ><input type=\"text\" value='" . $v['vendor_tag'] . "' id='vendor_" . $v['id'] . "'  disabled=\"disabled\" class=" . $v['id'] . "></td>"; 
        
         //Supplier Id
          $body1.="<td>";
          $body1.="<select disabled='disabled' class='".$v['id']."' name='inv_supplier_id_".$v['id']."'>";
          $body1.="<option value=''>Select Vendor</option>";
          foreach($vendors as $vendor):
              $selected=($v['inv_supplier_id']==$vendor['suppliers']['id'])?"selected":"";
           //   $selected=($v['vendor']==$vendor['suppliers']['name'])?"selected":"";
              $body1.="<option value='".$vendor['suppliers']['id']."'  $selected >".$vendor['suppliers']['name']."</option>";
          endforeach;
          $body1.="</select>";
          $body1.="</td>";
		  
		   $body1.="<td>";
          $body1.="<select disabled='disabled' class='".$v['id']."' id ='merge_sim_id_".$v['id']."'>";
          $body1.="<option value=''>Select Sims</option>";
          foreach($simData['New Sims'] as $val):
              
          $body1.="<option value='".$val['scid']."'>".$val['scid']."</option>";
          endforeach;
          $body1.="</select>";
          $body1.="</td>";
		  
		  
		  
		   $body1.="<td>";
          $body1.="<select disabled='disabled' class='".$v['id']."' id ='modem_".$v['id']."'>";
          $body1.="<option value=''>Select Modems</option>";
          foreach($vendorsdata as $val):
              
          $body1.="<option value='".$val['vendors']['id']."'>".$val['vendors']['company']."</option>";
          endforeach;
          $body1.="</select>";
          $body1.="</td>";
          
          $body1.="<td>";
          $body1.="<select disabled='disabled' class='".$v['id']."' name ='blocktag_".$v['id']."'>";
          $body1.="<option value=''>Select Tag</option>";
          foreach($blocktags as $blocktag):
          $selected=($v['block_tag']==$blocktag['inv_block_tags']['id'])?"selected":"";
          $body1.="<option value='".$blocktag['inv_block_tags']['id']."' $selected>".$blocktag['inv_block_tags']['block_tag']."</option>";
          endforeach;
          $body1.="</select>";
          $body1.="</td>";
		  
		 $body1.="<td><input type='text' style=\"width:220px;\"  id='new_sim_" . $v['id'] . "'>";
    
        
          $checked=$v['block']?"checked":"";
          $body1.="<td><input $checked  type='checkbox'  class='".$v['id']."' disabled='disabled' id='block_".$v['id']."' name='block_".$v['id']."'  value='".$v['id']."'  onclick=\"checkstate(".$v['id'].");\"></td>";

       //   $body1.="<td><input  type='text'  class='".$v['id']."' disabled='disabled' id='vendor_tag_".$v['id']."'  value='".$v['vendor_tag']."' ></td>";
          
		  
		   if($v['id'] == $v['par_bal'] && $_SESSION['Auth']['User']['group_id'] != 9){
          $body1.="<td id ='edit_" . $v['id'] . "'><input type=\"button\" value=\"edit\" onclick=\"editdata(" . $v['id'] . ");\"></td>
          <td id ='insert_" . $v['id'] . "'><input type=\"button\" value=\"insert\" onclick=\"insertdata(" . $v['id'] . ");\"></td>    
          <td  id ='save_" . $v['id'] . "' style=\"display:none;\"><input type=\"button\" value=\"save\"  onclick=\"savedata(" . $v['id'] . ");\"></td>";
		   $body1.="&nbsp;&nbsp;<td  id ='shiftsim_" . $v['id'] . "'><input type=\"button\" value=\"Shiftsim\"  onclick=\"shiftsim(" . $v['id'] . ");\"></td>";
		   $body1.="&nbsp;&nbsp;<td  id ='shiftbal_" . $v['id'] . "'><input type=\"button\" value=\"ShiftBalance\"  onclick=\"shiftbal(" . $v['id'] . ");\"></td>";
			  }
          $body1.="</tr>";
            $i++;
    }
    $body1.="</table>";
    echo $body1;
}

echo "<br/><br/>";


if (!empty($simData['Hidden Sims']) && count($simData['Hidden Sims']) > 0) {
    $body2 = "";
    $body2.="<table style=\"width: 100%;border:1px solid #ddd\"><tr width='100%'><td colspan='14'><b style=\"color:red;\">" . 'Hidden Sims' . "</b></td></tr>";
    $body2.="<tr>
          <th style=\"width:20px;border:1px solid #ccc;\">Sim No</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Port No</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Operator</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Mobile</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Circle</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Type</th>";
	
	     if($_SESSION['Auth']['User']['group_id'] != 9){
          $body2.="<th style=\"width:20px;border:1px solid #ccc;\">Pin</th>";
		 }
          $body2.="<th style=\"width:20px;border:1px solid #ccc;\">Balance</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Commision</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Limit</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Roaming limit</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Vendor Tag</th>
		
		  <th style=\"width:20px;border:1px solid #ccc;\">Vendor ID</th>
          <th style=\"width:20px;border:1px solid #ccc;\">Hide flag</th>";
		  if($_SESSION['Auth']['User']['group_id'] != 9){
          $body2.="<th style=\"width:20px;border:1px solid #ccc;\">Action</th>";
		  }
          $body2.="</tr>";
    $i = 0;
    foreach ($simData['Hidden Sims'] as $k => $v) {
        if ($i % 2 == 0)
            $class = '';
        else
            $class = 'altRow';
        $body2.="<tr class=" . $class . ">
            <td id='sim_" . $v['id'] . "' >" . $v['scid'] . "</td>
            <td>" . $v['device_num'] . "</td>
            <td><select disabled=\"disabled\" onchange='operatorchange(" . $v['id'] . ")' class=" . $v['id'] . " id='opr_" . $v['id'] . "'><option value=''>---Select Operator----</option>";
        foreach ($oprData as $oprkey => $oprval) {
            $selected = "";
            if ($oprval['products']['id'] == $v['opr_id']) {
                $selected = "selected";
            }
            $body2.="<option value='" . $oprval['products']['id'] . "' $selected>" . $oprval['products']['name'] . "</option>";
        }
        $body2.="</select></td>
            <td><input type=\"text\" value='" . $v['mobile'] . "'  class=" . $v['id'] . " disabled=\"disabled\" id='mobile_" . $v['id'] . "'></td>
            <td><select name=\"circle\"  class=" . $v['id'] . " disabled=\"disabled\" id='circle_" . $v['id'] . "'>";
        $body2 .="<option value=''>Select</option>";
        foreach ($circleData as $circlekey => $circleval) {
            $selected = "";
            if ($circleval['mobile_numbering_area']['area_code'] == $v['circle']) {
                $selected = "selected";
            }
            $body2.="<option value=" . $circleval['mobile_numbering_area']['area_code'] . " $selected>" . $circleval['mobile_numbering_area']['area_code'] . "</option>";
        }
        $body2.="</td></select><td><select name=\"type\" disabled=\"disabled\" class=" . $v['id'] . " id='type_" . $v['id'] . "'>";
        if (isset($type[$v['opr_id']])) {
            foreach ($type[$v['opr_id']] as $typekey => $typeval) {
                $selected = "";
                if ($typeval == $v['type']) {
                    $selected = "selected";
                }
                $body2.="<option value=" . $typeval . " $selected>" . $typeval . "</option>";
            }
        }
        $body2.="</select></td>
			<input type='hidden' value='" . $v['par_bal'] . "' id='parbal_" . $v['id'] . "'><input type='hidden' value='" . $v['scid'] . "' id='sim_" . $v['id'] . "'>
          <input type='hidden' value='" . $v['machine_id'] . "' id='machine_" . $v['id'] . "'>";
		if($_SESSION['Auth']['User']['group_id'] != 9){
          $body2.="<td><input type='password' style=\"width:60px;\" class='pass " . $v['id'] . "' disabled=\"disabled\" value='" . $v['pin'] . "' id='pass_" . $v['id'] . "'>
              <a style=\"width:20px;\" href=\"#\" onclick=\"showpassmodal('" . $v['id'] . "')\">Show Pin</a>
          </td>";
		}
          $body2.="<td id='bal_" . $v['id'] . "'>" . $v['balance'] . "</td>
          <td><input type=\"text\" value='" . $v['commission'] . "'  id='comm_" . $v['id'] . "'disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td><input type=\"text\" value='" . $v['limit'] . "' id='limit_" . $v['id'] . "' disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td ><input type=\"text\" value='" . $v['roaming_limit'] . "' id='roaming_" . $v['id'] . "' disabled=\"disabled\" class=" . $v['id'] . "></td>
          <td ><input type=\"text\" value='" . $v['vendor_tag'] . "' id='vendor_" . $v['id'] . "'  disabled=\"disabled\" class=" . $v['id'] . "></td>
		";
         // <td><input  type=\"text\"  class='".$v['id']."' disabled='disabled' id='vendor_tag_".$v['id']."'  value='".$v['vendor_tag']."' ></td>";
        $body2.="<td>";
          $body2.="<select disabled='disabled' class='".$v['id']."' name='inv_supplier_id_".$v['id']."'>";
          $body2.="<option value=''>Select Vendor</option>";
          foreach($vendors as $vendor):
              $selected=($v['inv_supplier_id']==$vendor['suppliers']['id'])?"selected":"";
           //   $selected=($v['vendor']==$vendor['suppliers']['name'])?"selected":"";
              $body2.="<option value='".$vendor['suppliers']['id']."'  $selected >".$vendor['suppliers']['name']."</option>";
          endforeach;
          $body2.="</select>";
          $body2.="</td>";
          
		  if($_SESSION['Auth']['User']['group_id'] != 9){
          $body2.="<td><input type=\"checkbox\" disabled=\"disabled\" id='showflag_" . $v['id'] . "' value='' checked class=" . $v['id'] . "></td>
          <td id ='edit_" . $v['id'] . "'><input type=\"button\" value=\"edit\" onclick=\"editdata(" . $v['id'] . ");\"></td>
          <td id ='insert_" . $v['id'] . "'><input type=\"button\" value=\"insert\" onclick=\"insertdata(" . $v['id'] . ");\"></td>     
          <td  id ='save_" . $v['id'] . "' style=\"display:none;\"><input type=\"button\" value=\"save\"  onclick=\"savedata(" . $v['id'] . ");\"></td>";
		  }
          $body2.="</tr>";
        $i++;
		  }
    $body2.="</table>";
    echo $body2;
}
?>
<div id="smsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h2 id="myModalLabel" class="text-info"></h2>
    </div>
    <div class="modal-body">
        <span id="pass_value"></span>  
        <input placeholder="Password" type="password" id="pass_word" /><br/>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" onclick="cancel()" aria-hidden="true">Close</button>
        <button id="send_sms" type="button" class="btn btn-info" onclick="SubmitPassword()" data-loading-text="Sending..." data-complete-text="Send">Submit</button>
    </div>
</div>

<div id="blocksimsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="blocksimsModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h2 id="myModalLabel" class="text-info"></h2>
    </div>
    <div class="modal-body">
        <input type="hidden" id="sim_id" value="" />
        <input type="hidden" id="opr_id" value="" />
        <input type="hidden" id="vendor_id" value="" />
        <input type="hidden" id="balance" value="" />
        <input type="hidden" id="mobileno" value="" />
        <input type="hidden" id="ischecked" value="" />
        <input type="hidden" id="inv_supplier_id" value="" />
        <input type="hidden" id="blocktag" value="" />
        <span>Previous record of this sim already exists. Do you want to reset it?</span>  

    </div>
    <div class="modal-footer">        
        <button id="resetdata" type="button" class="btn btn-info" onclick="ResetSimStatus()">Yes</button>
        <button id="addnewdata" class="btn" type="button" onclick="AddNewData()" aria-hidden="true">No</button>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });


    function editdata(id) {
        $("#edit_" + id).hide();
        $("#save_" + id).show();
        $("." + id).removeAttr("disabled");

    }

    function savedata(id) {
        $("#edit_" + id).show();
        $("#save_" + id).hide();
        $("." + id).attr("disabled", "disabled");
        var oprId = $("#opr_" + id).val();
        var mobile = $("#mobile_" + id).val();
        var circle = $("#circle_" + id).val();
        var type = $("#type_" + id).val();
        var pin = $("#pass_" + id).val();
        var bal = $("#bal_" + id).html();
        var comm = $("#comm_" + id).val();
        var limit = $("#limit_" + id).val();
        var roaming = $("#roaming_" + id).val();
        //var vendor = $("#vendor_" + id).val();
        var showflag = $("#showflag_" + id).val();
        var parbal = $("#parbal_" + id).val();
        var simid = $("#sim_" + id).html();
        var machineid = $("#machine_" + id).val();
        var Vendorid = $("#vendor_id").val();
		
        var supplier_id=$("select[name=inv_supplier_id_"+id+"]").val();
        var block=$("#block_"+id).is(':checked');
		var vendor_tag=$("#vendor_"+id).val();
		//var vendor_tag=$("select[name=inv_supplier_id_"+id+"]  option:selected").text();
		var checkmultiple = $(".multiple_"+id).is(':checked');
                
         var vendor=$("select[name=inv_supplier_id_"+id+"]  option:selected").text();
         var mergeSimid = $("#merge_sim_id_"+id).val();
         var blocktag_id=$("select[name=blocktag_"+id+"]").val();
      
		
          // var vendor = $("#vendor_" + id).val();
        //var vendor =supplier_id[1] ;
        var inv_supplier_id=supplier_id;
       
       if(supplier_id=="")
       {
           alert("Error : Select Vendor ID");
           return ;
       }
       if(vendor_tag=="")
       {
           alert("Error : Fill Vendor tag");
           return ;
       }
       
        if ($("#showflag_" + id).is(":checked")) {
            var showflag = "1";
        } else {
            var showflag = "0";
        }
        var alldata = {id: id,
            operator: oprId,
            mobile: mobile,
            circle: circle,
            type: type,
            pin: pin,
            balance: bal,
            comm: comm,
            limit: limit,
            roaming: roaming,
            vendor: vendor,
           // showflag: showflag,
            parbal: parbal,
            simid: simid,
            machineid: machineid,
            Vendorid: Vendorid,
            inv_supplier_id:inv_supplier_id,
            block:block,
            vendor_tag:vendor_tag,
            checkmultiple:checkmultiple,
            merge:mergeSimid,
            blocktag_id:blocktag_id
        };
        
        if((block==true) && (blocktag_id==""))
            {
                alert("Error : Select blocktag.");
                return ;
            }
        $.ajax({
            url: '/sims/updateSimData/',
            type: "POST",
            data: alldata,
            dataType: "json",
            success: function(data) {
                console.log(data);
			 alert(data.data);
                         if(data.status=="success")
                         { 
                             if($('#blockh_'+id).length)
                             {
                                 showmodal(id);
                                 $('input#blockh_'+id).remove();                                 
                             }
                             else
                             {
                                 return false;
                             }
                         }

            },beforeSend: function(){
               $('.loader').show();
               },
               complete: function(){
                  $('.loader').hide();
                },
               error: function (xhr,error) {
              
               } 
        });
       
    }
    
    function insertdata(id){
        
        var oprId = $("#opr_" + id).val();
        var mobile = $("#mobile_" + id).val();
        var circle = $("#circle_" + id).val();
        var type = $("#type_" + id).val();
        var pin = $("#pass_" + id).val();
        var bal = $("#bal_" + id).html();
        var comm = $("#comm_" + id).val();
        var limit = $("#limit_" + id).val();
        var roaming = $("#roaming_" + id).val();
       // var vendor = $("#vendor_" + id).val();
        var showflag = $("#showflag_" + id).val();
        var parbal = $("#parbal_" + id).val();
        var simid = $("#sim_" + id).html();
        var machineid = $("#machine_" + id).val();
        var Vendorid = $("#vendor_id").val();
        var inv_supplier_id=$("select[name=inv_supplier_id_"+id+"]").val();
        var vendor_tag= $("#vendor_" + id).val();
        var vendor=$("select[name=inv_supplier_id_"+id+"]  option:selected").text();

        
          if(inv_supplier_id=="")
       {
           alert("Error : Select Vendor ID");
           return ;
       }
         if(vendor_tag=="")
       {
           alert("Error : Fill Vendor tag");
           return ;
       }
       
        var alldata = {id: id,
            operator: oprId,
            mobile: mobile,
            circle: circle,
            type: type,
            pin: pin,
            balance: bal,
            comm: comm,
            limit: limit,
            roaming: roaming,
            vendor: vendor,
            showflag: showflag,
            parbal: parbal,
            simid: simid,
            machineid: machineid,
            Vendorid: Vendorid,
            insert:"insert",
            inv_supplier_id:inv_supplier_id,
             vendor_tag:vendor_tag
        };
        
        
        
        $.ajax({
            url: '/sims/updateSimData/',
            type: "POST",
            data: alldata,
            dataType: "json",
            success: function(data) {
                 alert(data.data);

            }
        });
        
    }

    function operatorchange(id) {
        var type = JSON.parse(<?php echo json_encode($oprType); ?>);
        var oprId = $("#opr_" + id).val();
        var operatortype = type[oprId];
        $("#type_" + id).empty();

        for (var j = 0; j < operatortype.length; j++) {
            $("#type_" + id).append("<option value='" + operatortype[j] + "'>" + operatortype[j] + "</option>");
        }

    }

    function SubmitPassword() {

        var password = $("#pass_word").val();
        if (password == '') {
            alert("Enter Password");
            return false;
        }

        $.ajax({
            url: '/sims/checkPassword/',
            type: "POST",
            data: {"password": password},
            dataType: "json",
            success: function(data) {
                if (data.result == "success") {
                    var passwrdvalue = $("#pass_wrd").val();
                    var passtext = $("#pass_" + passwrdvalue).val();
                    $("#pass_word").hide();
                    $("#pass_value").html(passtext);

                } else {
                    alert("Password does not match");
                    return false;
                }
            }
        });

    }

    function showpassmodal(id) {
        $('#smsModal').modal('show');
        $("#pass_word").show();
        $("#pass_word").val('');
        $("#pass_value").html('');
        $("#pass_wrd").val(id);

    }
	
	function shiftsim(id){
		
		var  supplier_id=$("select[name=inv_supplier_id_"+id+"]").val();
		var bal = $("#bal_" + id).html();
		var  shifted_modem_id = $("#modem_"+id).val();
	    var oprId = $("#opr_" + id).val();
		 var parbal = $("#parbal_" + id).val();
		 var modemId = "<?php  echo $VendorId;?>";
		if(shifted_modem_id == ''){
			alert("Please Select Modem");
			return false;
		}
		
//		if(bal == 0){
//			alert("Sim can not be shifted with zero balance");
//			return false;
//		}
		$.ajax({
            url: '/sims/shiftSims/',
            type: "POST",
            data: {"supplier_id": supplier_id,"shifted_modem_id" : shifted_modem_id,"oprId" : oprId,"parbal":parbal,"modemId":modemId},
            dataType: "json",
            success: function(data) {
				
				 alert(data.data);
                
            }
        });
		
	}
	
	function shiftbal(id){
		
		var supplier_id=$("select[name=inv_supplier_id_"+id+"]").val();
		var bal = $("#bal_" + id).html();
		var old_sim_id = $("#sim_"+id).html();
		var new_sim_id = $("#new_sim_"+id).val();
	    var oprId = $("#opr_" + id).val();
		var modemId = "<?php  echo $VendorId;?>";
		
		if(new_sim_id == ''){
			alert("Please Enter New sim Id");
			
			return false;
		}
	
		$.ajax({
            url: '/panels/shiftbalance/',
            type: "POST",
            data: {"supplier_id": supplier_id,"oprId" : oprId,"new_sim_id":new_sim_id,"bal":bal,"modemId":modemId,"old_sim_id":old_sim_id},
            dataType: "json",
            success: function(data) {
				
				 alert(data.data);
                
            }
        });
		
	}

</script>
<script type="text/javascript" src="/boot/js/blocksims.js"></script>

