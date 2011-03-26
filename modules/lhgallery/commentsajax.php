<?php

$imageCommentVersion = CSCacheAPC::getMem()->getCacheVersion('comments_'.(int)$Params['user_parameters']['image_id']);
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryComment::getCount(array('cache_key' => 'comments_count_v_'.(int)$Params['user_parameters']['image_id'].'_'.$imageCommentVersion,'filter' => array('pid' => (int)$Params['user_parameters']['image_id'])));
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/commentsajax').'/'.(int)$Params['user_parameters']['image_id'];
$pages->setItemsPerPage(10);
$pages->paginate();

if ($pages->items_total > 0) { 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/commentsajax.tpl.php');
    $tpl->set('pages',$pages);
    $tpl->set('comments',erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_v_'.(int)$Params['user_parameters']['image_id'].'_'.$imageCommentVersion,'filter' => array('pid' => (int)$Params['user_parameters']['image_id']),'offset' => $pages->low, 'limit' => $pages->items_per_page)));
    echo json_encode(array('result' => $tpl->fetch(),'error' => 'false','container' => 'comments-list','scrollto' => 'comment-container'));
} else {
    echo json_encode(array('error' => 'true'));
}
exit;