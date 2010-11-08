<?php

// Make sure we are the only process at current time. Timeout 20 minits.
if (file_exists("cache/cacheconfig/pid_convert.process") && filectime("cache/cacheconfig/pid_convert.process") < time()-20*60)
exit;

touch("cache/cacheconfig/pid_convert.process");

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryPendingConvert' );
$objects = $session->find( $q, 'erLhcoreClassModelGalleryPendingConvert' );

foreach ($objects as $object)
{
	$object->process();
}

unlink("cache/cacheconfig/pid_convert.process");

echo "Cron finished - ",date('Y-m-d :H:i'),"\n";
exit;