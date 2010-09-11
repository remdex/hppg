<?php

$session = erLhcoreClassGallery::getSession();

$q = $session->database->createSelectQuery();  
$q->select( $q->alias( 'MAX(pid)', 'max_pid' ) )->from( "lh_gallery_duplicate_image_hash" ); 
$stmt = $q->prepare();       
$stmt->execute();   
$lastIndexedPID = $stmt->fetchColumn();  

if (!is_numeric($lastIndexedPID)) {
    $lastIndexedPID = 0;
}

$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
$q->where( 
       $q->expr->gt( 'pid', $lastIndexedPID )
);
     
$q->orderBy('pid DESC' );

$objects = $session->findIterator( $q, 'erLhcoreClassModelGalleryImage' );

// First check that we have all objects in hash table
foreach ($objects as $object)
{
   $found = erLhcoreClassModelGalleryDuplicateImageHash::getImageCount(array('filter' => array('pid' => $object->pid)));
   if ($found == 0) {       
       if (file_exists($object->file_path_filesystem)) {      
           $imageHash = new erLhcoreClassModelGalleryDuplicateImageHash();
           $imageHash->hash = md5_file($object->file_path_filesystem);
           $imageHash->pid = $object->pid;
           $session->save($imageHash);        
       }       
   }
}

$db = ezcDbInstance::get();
$q = $db->createSelectQuery();
$q->select( 'pid,hash, count( * ) AS n' )
	->from( 'lh_gallery_duplicate_image_hash' )
	->groupBy( 'hash' )
	->having( $q->expr->gt('n',1) ); 

$stmt = $q->prepare();
$stmt->execute();
$duplicates = $stmt->fetchAll();

$dulicateSessionObject = false;
foreach ($duplicates as $duplicate)
{
	$images = erLhcoreClassModelGalleryDuplicateImageHash::getImages(array('filter' => array('hash' => $duplicate['hash'])));
	$Original = false;
	$OriginalSaved = false;
	$dulicateSessionObject = false;
	
	foreach ($images as $imageDuplicate)
	{
	    
	     $image = $imageDuplicate->image;
	     
		 $photoPath = 'albums/'.$image->filepath;
       	 $filePath = $photoPath.$image->filename;
       	 if (file_exists($filePath))
       	 {
       	 	// First item cycle, mark current image as original
       	 	if ($Original == false){$Original = $image;continue;}
       	 	
       	 	if (sha1_file( 'albums/'.$Original->filepath.$Original->filename) == sha1_file( 'albums/'.$image->filepath.$image->filename))
       	 	{
       	 		// Check if image ID is already not in duplicate table
       	 		$q = $db->createSelectQuery();  
       			$q->select( "COUNT(pid)" )->from( "lh_gallery_duplicate_image" ); 
	       	 	$q->where( 
	                 $q->expr->eq( 'pid', $image->pid )     
	          	);
          		$stmt = $q->prepare();       
              	$stmt->execute();   
              	if ($stmt->fetchColumn() == 0)
              	{
              		
	       	 		if ($dulicateSessionObject == false){
	       	 			$dulicateSessionObject = new erLhcoreClassModelGalleryDuplicateCollection();
	       	 			$dulicateSessionObject->time = time();
	       	 			$session->save($dulicateSessionObject);
	       	 		}
	       	 		
	       	 		if ($OriginalSaved == false) {
	       	 			$duplicateRecord = new erLhcoreClassModelGalleryDuplicateImage();
	       	 			$duplicateRecord->pid = $Original->pid;
	       	 			$duplicateRecord->duplicate_collection_id = $dulicateSessionObject->id;
	       	 			$session->save($duplicateRecord);
	       	 			$OriginalSaved = true;
	       	 		}
	       	 		
	       	 		$duplicateRecord = new erLhcoreClassModelGalleryDuplicateImage();
	       	 		$duplicateRecord->pid = $image->pid;
	       	 		$duplicateRecord->duplicate_collection_id = $dulicateSessionObject->id;
	       	 		$session->save($duplicateRecord);
              	}       	 			
       	 	}
       	 }
	}	
}

?>