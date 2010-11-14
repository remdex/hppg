<?php

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGallerySearchHistory' );  
$q->where ( 
          $q->expr->like( 'keyword', $q->bindValue(urldecode($Params['user_parameters']['q']).'%') )
);
$q->orderBy('keyword ASC' ); 

$objects = $session->find( $q );

$items = array();
foreach ($objects as $object)
{
    $items[] = array('value' => $object->keyword,'label' => $object->keyword.' ('.$object->countresult.')');
}

echo json_encode($items);
exit;