<?php

$session = erLhcoreClassGallery::getSession();

$db = ezcDbInstance::get();
$q = $db->createSelectQuery();
$q->select( 'pid, filesize, count( * ) AS n' )
	->from( 'lh_gallery_images' )
	->groupBy( 'filesize' )
	->having( $q->expr->gt('n',1) ); 

$stmt = $q->prepare();
$stmt->execute();
$duplicates = $stmt->fetchAll();

$dulicateSessionObject = false;
foreach ($duplicates as $duplicate)
{
	$images = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true, 'filter' => array('filesize' => $duplicate['filesize'])));
	$Original = false;
	$OriginalSaved = false;
	$dulicateSessionObject = false;
	
	foreach ($images as $image)
	{
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