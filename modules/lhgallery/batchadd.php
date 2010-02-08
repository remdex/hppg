<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/batchadd.tpl.php');

$directoryList = erLhcoreClassGalleryBatch::listDirectory(isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums');
$tpl->set('directoryList',$directoryList);

if (isset($_GET['import']) && $_GET['import'] == 1)
{
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectory(isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums',true));
}

if (isset($_GET['importrecur']) && $_GET['importrecur'] == 1)
{
    $tpl->set('filesList',erLhcoreClassGalleryBatch::listDirectoryRecursive(isset($_GET['directory']) ? urldecode($_GET['directory']) : 'albums'));
}

$Result['content'] = $tpl->fetch();

