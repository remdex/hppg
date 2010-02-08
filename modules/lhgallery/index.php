<?php
$cache = CSCacheAPC::getMem();   
if (($Result = $cache->restore(md5('index_page'))) === false)
{  
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/index.tpl.php');
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array();
    
    $cache->store(md5('index_page'),$Result);
}
?>