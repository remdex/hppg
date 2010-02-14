<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/batchadd.tpl.php');

$directoryList = erLhcoreClassGalleryBatch::listDirectory(isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums');
$tpl->set('directoryList',$directoryList);

$directory = isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums';

if (isset($_GET['import']) && $_GET['import'] == 1)
{
    if (is_writable($directory)) 
        $tpl->set('writable',true);        
    else
        $tpl->set('writable',false);
        
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectory($directory,true)); 
}

if (isset($_GET['importrecur']) && $_GET['importrecur'] == 1)
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
foreach ($pathDirectorys as $pathItem)
{
   $previousFolder .= $pathItem.'/';
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/batchadd/').'?directory='.urlencode(rtrim($previousFolder,'/')),'title' => $pathItem); 
}
 
$Result['path'] = $path;