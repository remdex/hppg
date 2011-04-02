<?php

$Image = $Params['user_object'];

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/editimage.tpl.php');

$tpl->set('image',$Image);

$Result['content'] = $tpl->fetch();

$Album = $Image->album;

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Account')),
array('url' => erLhcoreClassDesign::baseurl('gallery/myalbums'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Albums')),
array('url' => erLhcoreClassDesign::baseurl('gallery/mylistalbum').'/'.$Album->aid, 'title' => $Album->title),
array('title' => $Image->name_user),
);