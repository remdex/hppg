<?php

$session = erLhcoreClassGallery::getSession();

$q = $session->createFindQuery( 'erLhcoreClassModelGalleryDuplicateCollection' );
$objects = $session->find( $q, 'erLhcoreClassModelGalleryDuplicateCollection' ); 

foreach ($objects as $object)
{	
	$originalImageId = null;
	$oldestID = null;
	
	foreach ($object->duplicate_images as $imageDuplicateItem) { 
    	 $imageDuplicate = $imageDuplicateItem->image;
    	 
    	 if ($oldestID == null || $oldestID > $imageDuplicate->pid)
    	 {
    	 	$oldestID = $imageDuplicate->pid;
    	 }    	     	 
	}
	
	echo "Original ID-",$oldestID,"\n";
	
	foreach ($object->duplicate_images as $imageDuplicateItem) { 
    	 $imageDuplicate = $imageDuplicateItem->image;
    	 
    	 
    	 if ($imageDuplicate->pid > 0 && $oldestID != $imageDuplicate->pid)
    	 {
    	 	echo "Removing image with ID - ",$imageDuplicate->pid,"\n";
    	 	$imageDuplicate->removeThis();
    	 } else {
    	 	echo "Skipping original - ",$imageDuplicate->pid,"\n";
    	 }   	     	 
	}
	
	$object->removeThis();
}

// Clear all cache
CSCacheAPC::getMem()->increaseImageManipulationCache();