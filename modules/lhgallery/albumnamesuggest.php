<?php

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryAlbum' );  
$q->where ( 
          $q->expr->like( 'title', $q->bindValue(urldecode($Params['user_parameters']['name']).'%') )
);
$q->orderBy('title ASC' ); 



$objects = $session->find( $q, 'erLhcoreClassModelGalleryAlbum' );

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/albumnamesuggest.tpl.php');
$tpl->set('albums',$objects);
$tpl->set('key_directory',$Params['user_parameters']['directory_id']);

echo json_encode(array('result' => $tpl->fetch()));
exit;