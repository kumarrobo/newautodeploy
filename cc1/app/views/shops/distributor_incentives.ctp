<?php if(!$res) { ?>
<form action="/shops/distributorIncentives/" method="post" enctype="multipart/form-data" >
    <br/>
    Select Service : 
    <select name="service">
        <?php foreach ($services as $service) { ?>
        <option value="<?php echo $service['parent_id']; ?>"><?php echo $service['parent_name']; ?></option>
        <?php } ?>
    </select><br/><br/>
    Upload File : <input type="file" name="incentive" />
    <input type="submit" value="Submit">
</form>
<?php
} else {
    
    if(is_array($res)) {
?>        
<table border="1" cellpadding=0 cellspacing=0 style="width: 80%;">
    <thead>
        <tr style="font-weight: bold; line-height: 2em;">
            <td>&nbsp;ID</td>
            <td>&nbsp;Shop</td>
            <td>&nbsp;Name</td>
            <td>&nbsp;Mobile</td>
            <td>&nbsp;Amount</td>
            <td>&nbsp;Narration</td>
            <td>&nbsp;Status</td>
            <td>&nbsp;Remark</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $r_data) { ?>
        <tr style="line-height: 1.5em;">
            <td><?php echo "&nbsp;&nbsp;".$r_data['id']."&nbsp;"; ?></td>
            <td><?php echo $r_data['company'] ? "&nbsp;".$r_data['company'] : "<strong><center>-</center></strong>"; ?></td>
            <td><?php echo $r_data['name'] ? "&nbsp;".$r_data['name'] : "<strong><center>-</center></strong>"; ?></td>
            <td><?php echo $r_data['mobile'] ? "&nbsp;".$r_data['mobile'] : "<strong><center>-</center></strong>"; ?></td>
            <td><?php echo "&nbsp;".$r_data['amount']; ?></td>
            <td><?php echo "&nbsp;".$r_data['narration']; ?></td>
            <td><?php echo "&nbsp;".$r_data['status']; ?></td>
            <td><?php echo "&nbsp;".$r_data['remark']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php
    } else {
            echo $res;
    } }
?>
