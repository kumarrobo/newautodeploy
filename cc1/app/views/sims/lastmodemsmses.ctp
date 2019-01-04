
<style>   

    #Yestbutton{

    width: 5em; height : 2em;
    font-weight: bold;
    font-size: 20px; 
    font-family: ariel;
                }

    #Tombutton{
    
    width: 5em; height : 2em;
    font-weight: bold;
    font-size: 20px; 
    font-family: ariel; 
    /*background-color: #CAE1FF;*/
               }
               

    #blankbutton{
    
    visibility: hidden;
        
                }


</style>

<?php

    /* Yesterday and Today date defining*/

    $dateyeslink = date_create($paramdate); 
    date_sub($dateyeslink, date_interval_create_from_date_string('1 days'));

    $datetolink = date_create($paramdate); 
    date_add($datetolink, date_interval_create_from_date_string('1 days'));


?>


    <div  style="float:right; width:50%">
        
        <button type="submit" id="tombutton" <?php if ($paramdate > date("Y-m-d") or $paramdate == date("Y-m-d") ){ ?> disabled <?php   } ?>onclick="tomdate()"> > </button>       
    </div>

<div style="float:right;width :3%">
    
    <button type="submit"  id = "blankbutton"> < </button>
</div>
    <div style="float:right; ">


    
        <button type="submit" id="yestbutton"  onclick="yest()"> < </button>
    </div>

   


<script>
    
    function yest(){

window.location ='/sims/lastModemSMSes/<?php echo $paramvendor;  ?>/<?php echo $paramdev;  ?>/1/1500/<?php echo date_format($dateyeslink,'Y-m-d');?>';

    }
    </script>

    <script>
        
function tomdate(){
            
            
 window.location = '/sims/lastModemSMSes/<?php echo $paramvendor; ?>/<?php echo $paramdev; ?>/1/1500/<?php echo date_format($datetolink,'Y-m-d');?>';

}
        
    </script>
<br /><br /><br />    
    
<?php

    echo "<pre>"; print_r($modem_sms);

?>