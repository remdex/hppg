<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/batchadd.tpl.php');

$directoryList = erLhcoreClassGalleryBatch::listDirectory(isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums');
$tpl->set('directoryList',$directoryList);

if (isset($_GET['import']) && $_GET['import'] == 1)
{
    $directory = isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums';
    
    if (is_writable($directory)) 
        $tpl->set('writable',true);        
    else
        $tpl->set('writable',false);
        
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectory($directory,true));
    
    
}

if (isset($_GET['importrecur']) && $_GET['importrecur'] == 1)
{
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectoryRecursive(isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums'));
}

$Result['content'] = $tpl->fetch();

