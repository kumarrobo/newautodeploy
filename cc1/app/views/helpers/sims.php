<?php

class SimsHelper extends AppHelper
{
    
    function setColor($sim=array())
    {
            $color = '';
            
            if ($sim->active_flag == 1 && $sim->balance < 3000)
               $color = '#8c65e3';
            else if ($sim->active_flag == 0 && $sim->balance > 3000)
               $color = '#c73525';
            else if ($sim->active_flag == 1 && $sim->balance > 3000 && date('Y-m-d H:i:s', strtotime('-45 minutes')) > $sim->last)
               $color = '#f6ff00';
            else if ($sim->active_flag == 1)    
               $color = '#99ff99';
            

            if($sim->block==1):
                  $color = '#420420';
            endif;
            
            $current_date=date('Y-m-d H:i:s');
            
           // if(strtotime($sim->last)-strtotime("$current_date -5 minutes")<=0):
            if(($sim->roaming_limit > 0) && ($sim->roaming_today >=0) && ($sim->roaming_today < ($sim->roaming_limit-100)) && $sim->active_flag != 1 && $sim->balance>100 ):
                $color = '#ffa500';
            endif;
            
            if(($sim->roaming_limit > 0) && ($sim->roaming_today >=0) && ($sim->roaming_today < ($sim->roaming_limit-100)) && $sim->active_flag != 0 && $sim->balance>100):
                $color = '#c0c0c0';
            endif;
            
            if((strtotime($sim->last)-strtotime("$current_date -36 hours")<=0) && ($sim->balance>100)):
                  $color = '#19ffd1';
            endif;
            
            if($sim->block):
                $color="#99ffcc";
            endif;
            
            echo $color;
            
            
    }
    
}
