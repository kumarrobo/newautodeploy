<?php

$out = array();
if($_REQUEST['query'] == 'start'){
        if (ctype_alnum($_REQUEST['host']) && is_numeric($_REQUEST['number'])) {
                exec('linphonecsh init');
                exec('linphonecsh register --host ' . $_REQUEST['host'] . ' --username 113');
                exec('linphonecsh dial ' . $_REQUEST['number'],$out);
                //exec('linphonecsh dial 9004387418',$out);
                echo implode('<br/>',$out);
        }
}
else if($_REQUEST['query'] == 'end'){
	exec('linphonecsh hangup',$out);
	echo implode('<br/>',$out);
}
?>