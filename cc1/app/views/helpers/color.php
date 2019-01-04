<?php

class ColorHelper extends AppHelper
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
            
            echo $color;
            
            
    }
    
}
