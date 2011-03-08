<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/batchadd.tpl.php');

$directoryList = erLhcoreClassGalleryBatch::listDirectory(isset($Params['user_parameters_unordered']['directory']) ? base64_decode(urldecode($Params['user_parameters_unordered']['directory'])) : 'albums');
$tpl->set('directoryList',$directoryList);

$directory = isset($Params['user_parameters_unordered']['directory']) ? base64_decode(urldecode($Params['user_parameters_unordered']['directory'])) : 'albums';



if (isset($Params['user_parameters_unordered']['import']) && $Params['user_parameters_unordered']['import'] == 1)
{
    if (is_writable($directory)) 
        $tpl->set('writable',true);        
    else
        $tpl->set('writable',false);
        
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectory($directory,true)); 
}

if (isset($Params['user_parameters_unordered']['importrecur']) && $Params['user_parameters_unordered']['importrecur'] == 1)
{
    if (is_writable($directory)) 
        $tpl->set('writable',true);        
    else
        $tpl->set('writable',false);
        
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectoryRecursive($directory));
}

$Result['content'] = $tpl->fetch();


$pathDirectorys = explode('/',$directory);     
$previousFolder = '';

$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/batchadd').'/(directory)/'.urlencode(base64_encode(rtrim($previousFolder,'/'))),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Mass image upload')); 

foreach ($pathDirectorys as $pathItem)
{
   $previousFolder .= $pathItem.'/';
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/batchadd').'/(directory)/'.urlencode(base64_encode(rtrim($previousFolder,'/'))),'title' => $pathItem); 
}
 
$Result['path'] = $path;