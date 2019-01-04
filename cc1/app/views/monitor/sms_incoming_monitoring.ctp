<html>
    
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <div id="innerDiv">
                 <?php echo $this->element('monitoring_header'); ?>
                <div style ="font:10px">
                    <div> 
                      <form name="smsincomingform" id="smsincomingform">
                          <label for="datepicker">Date</label><input type="text" name="datepicker" id="datepicker"  onmouseover="fnInitCalendar(this, 'datepicker','close=true')" value="<?php echo !empty($this->params['url']['datepicker'])?$this->params['url']['datepicker']:date('d-m-Y'); ?>" />
                          <input type="submit" value="Submit">
                      </form>  
                    </div>
                    <div style ="font-size:20px;padding: 5px;"> SMS Incoming Reports </div>
              <table class="ListTable" width="100%" border="1" style="border-collapse:collapse;font-size: 14px;">
                        <thead style="font-weight: bold; background: none repeat scroll 0 0 #F3F3F3">
<!--                            <tr>
                                <td colspan="4" > SMS INCOMING REPORT (Last 30 mins report)</td>                            
                            </tr>-->
                            <tr>
                                <td align="center">VMN No.</td>
                                <td align="center">Percentage(%)</td>
                                <td align="center">Count</td>
                                <td align="center">Todays Count</td>
                                <td align="center">Monthly Count</td>
                                <td align="center">Last Hit</td>
                                <td align="center">Contact Details</td>
                                <?php if($date == $today) {?>
                                <td align="center">Action</td> <?php }?>
                            </tr>
                        </thead>
                        <tbody>     
                            <?php 
                            if(count($data) > 0){
                                
                                foreach ($data as $key => $value) { ?>
                                    <tr bgcolor="<?php echo ($value['per'] <= 5  ? '#c73525' : '#99ff99'); ?>">
                                    <td align="center"><?php echo empty($value['virtual_num'])?"Unknown":$value['virtual_num'] ;?></td>
                                    <td align="center"><?php echo $value['per']. " %" ;?></td>
                                    <td align="center"><?php echo $value['cnt'] ;?></td>
                                    <td align="center"><?php echo $value['tcnt'] ;?></td> 
                                    <td align="center"><?php echo $value['mcnt'] ;?></td> 
                                    <td align="center"><?php echo $value['last_hit'] ;?></td>
                                    <td align="center"><?php echo $value['contact_details'] ;?></td>
                                   <?php if($date == $today) {?>
                                    <td align="center">
                                        <input type="radio" name=radio_no_<?php echo $value['virtual_num']; ?> onclick="unsetQueue(<?php echo $value['virtual_num']?>)" autocomplete="off" value='<?php echo empty($value['virtual_num'])?"Unknown":$value['virtual_num'] ;?>' <?php if(!array_key_exists($value['virtual_num'],$r)) { echo 'checked="checked"';} ?>>On</input>
                                        <input type="radio" name=radio_no_<?php echo $value['virtual_num']; ?> autocomplete="off" onclick="setQueue(<?php echo $value['virtual_num']?>)" value='<?php echo empty($value['virtual_num'])?"Unknown":$value['virtual_num'] ;?>' <?php if(array_key_exists($value['virtual_num'],$r)) { echo 'checked="checked"';} ?>>Off</input>
                                    </td>
                                   <?php }?>
                                </tr>  

                                    <?php 
                                 }
                             }else{ ?>
                                 <tr >
                                   <td colspan="4"> All VMN Nos are not working .</td>
                                </tr>  
                                 
                              <?php } ?>
                                                     
                        </tbody>
                
                </table>
               <div> 
            </div>
            <br class="clearLeft" />
         </div>
       </div>          
    </div>
</div>
       
<br class="clearRight" />
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">

    function setQueue(x)
    {//alert(x);
       var num = $('input:radio[name=radio_no_'+x+']:checked').val();
      // $(":radio").removeAttr("checked");
       console.log(num);

        var url = '/monitor/setQueue/';
        var data = {num: num};
       console.log(data);
       //return;
       $.ajax({
        url: url,
        type: "POST",
        dataType: 'json', 
        data: data, 
         success:function(data) 
         { 
             console.log(data);
             if(data.status=='done')
             {
             alert("Successfully updated");
             }
         } ,
         error: function()
            {
            alert('failure');
            }
       
        
    });
   }

   function unsetQueue(x)
    {
       // alert(x);
       var num = $('input:radio[name=radio_no_'+x+']:checked').val();
        var r=confirm("Are u sure?");
        if(!r)
        {
            return;
        }        
        var url = '/monitor/unsetQueue/';
        var data = {num: num};
        console.log(data);
       //return;
       $.ajax({
        url: url,
        type: "POST",
        dataType: 'json', 
        data: data, 
         success:function(data) 
         {
             console.log(data);
            if(data.status=='done')
            {
            alert("Done"); 
            }
        },
          error: function()
            {
            alert('Failure');
            }

    });
   }
   
 </script>