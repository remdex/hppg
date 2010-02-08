<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/addimages.tpl.php');
$AlbumData = $Params['user_object'];


$tpl->set('album',$AlbumData);
$Result['content'] = $tpl->fetch();

