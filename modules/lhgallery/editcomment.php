<?php

try {
    $comment = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryComment', (int)$Params['user_parameters']['msg_id'] );
    
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/editcomment.tpl.php'); 
    $tpl->set('action',$Params['user_parameters_unordered']['action']); 
 
    if ($Params['user_parameters_unordered']['action'] == 'edit') {   
        $comment->msg_author = $_POST['msg_author'];
        $comment->msg_body = $_POST['msg_body'];
        $comment->saveThis(); 
        
        CSCacheAPC::getMem()->increaseCacheVersion('last_commented_image_version_'.$comment->pid);
    }
    
    
    $tpl->set('comment',$comment);
    echo json_encode(array('result' => $tpl->fetch(),'error' => 'false'));
    exit;
        
} catch (Exception $e){
    exit;
}