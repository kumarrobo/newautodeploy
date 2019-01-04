<?php
class CommentsComponent extends Object{
    var $components = array('General', 'B2cextender', 'Recharge');
    var $Memcache = null;
    
    function addComment($user_id,$ref_id,$module_id,$tag_id,$subtag_id,$comment,$cc_id,$dataSource = null)
    {
        $userObj = is_null($dataSource) ? ClassRegistry::init('User') : $dataSource;
        
        $add_comment = $userObj->query('INSERT INTO comments_new(user_id,ref_id,module_id,tag_id,subtag_id,comment,cc_id,created_date,created_at)'
                . 'VALUES('.$user_id.','.$ref_id.','.$module_id.','.$tag_id.','.$subtag_id.',"'.$comment.'",'.$cc_id.',"'.date('Y-m-d').'","'.date('Y-m-d H:i:s').'")');

        if($add_comment)
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }        
    }
    
    function getComments($ref_id,$module_id)
    {
        $userObj = ClassRegistry::init('Slaves');
        
        $comments = $userObj->query('SELECT tag_id,subtag_id,comment,created_at,u.name as username '
                                . 'FROM comments_new c '
                                . 'JOIN users u ON (c.cc_id = u.id)'
                                . ' WHERE ref_id = '.$ref_id.' '
                                . 'AND module_id = '.$module_id.' ');
   
        return $comments;
    }
    
    function getCommentCount($ref_ids,$module_id)
    {
        $userObj = ClassRegistry::init('Slaves');
        
        $count = $userObj->query('SELECT COUNT(id) as msg_count,ref_id '
                                . 'FROM comments_new c '
                                . 'WHERE ref_id IN ('.$ref_ids.') '
                                . 'AND module_id = '.$module_id.' '
                                . 'GROUP BY ref_id');
        return $count;        
    }
}