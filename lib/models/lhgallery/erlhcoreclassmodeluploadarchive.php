<?php

class erLhcoreClassModelGalleryUploadArchive {
        
   public function getState()
   {
       return array(
               'id'           => $this->id,
               'album_id'     => $this->album_id,             
               'user_id'      => $this->user_id,             
               'filename'     => $this->filename,             
               'album_name'   => $this->album_name,
               'keywords'     => $this->keywords,
               'description'  => $this->description
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
    
   private function cleanup()
   {
   		if (file_exists("var/tmpfiles/{$this->id}")) {
   			ezcBaseFile::removeRecursive("var/tmpfiles/{$this->id}");
   		}
   		
   		if (file_exists("var/archives/{$this->filename}")) {
   			unlink("var/archives/{$this->filename}");
   		}
   		
   		erLhcoreClassGallery::getSession()->delete($this);
   }
   
   public function import()
   {   	
   		   	
   		try {
   			$archive = ezcArchive::open( "var/archives/{$this->filename}" ); 
   		} catch (Exception $e){
   			$this->cleanup();
   			return ;
   		}	
   		
   		if (!$archive->valid()) {
   			$this->cleanup();
   			return ;
   		}
   		
   		$wwwUser = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'default_www_user' );
   		$wwwUserGroup = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'default_www_group' );
   		$publicCategoryID = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'public_category_id' );
   		   		
   		$session = erLhcoreClassGallery::getSession();
   		
   		if ($this->album_name != '')
   		{
   			$album = new erLhcoreClassModelGalleryAlbum();
   			
   			if ($this->user_id == 0) {
   				$album->category = $publicCategoryID;
   			} else {
   				$album->category = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('owner_id' => $this->user_id)),'cid');
   			}
   			  			
   			$album->title = $this->album_name;
   			$album->description = $this->description;   			
   			$album->owner_id = $this->user_id;
   			$album->keyword = $this->keywords;
   			
   			$session->save($album);
   			
   		} else {   		
   			$album = erLhcoreClassModelGalleryAlbum::fetch($this->album_id);   			   			
   		}
   		
   		$config = erConfigClassLhConfig::getInstance();
   		 	   			 
   		while( $archive->valid() )
		{
			// Returns the current entry (ezcArchiveEntry).
			$entry = $archive->current();
			
			// ezcArchiveEntry has an __toString() method.
			if ($entry->isFile())
			{
				if (!file_exists("var/tmpfiles/".$this->id))
				mkdir("var/tmpfiles/".$this->id,$config->conf->getSetting( 'site', 'StorageDirPermissions' ));
				    					
				$pathExtracted = "var/tmpfiles/{$this->id}/" . $entry->getPath();								
				$archive->extractCurrent( "var/tmpfiles/{$this->id}/" );
				
				if (erLhcoreClassImageConverter::isPhotoLocal($pathExtracted)) {
										
					$image = new erLhcoreClassModelGalleryImage();
				    $image->aid = $album->aid;				    				    	
				    $session->save($image);

				    try {

				    	$photoDir = 'albums/userpics/'.$this->user_id;
				    	if (!file_exists($photoDir)) {
				    		mkdir($photoDir,$config->conf->getSetting( 'site', 'StorageDirPermissions' ));
				    		chown($photoDir,$wwwUser);
				    		chgrp($photoDir,$wwwUserGroup);
				    	}

				    	$photoDir = 'albums/userpics/'.$this->user_id.'/'.$album->aid;
				    	if (!file_exists($photoDir)) {
				    		mkdir($photoDir,$config->conf->getSetting( 'site', 'StorageDirPermissions' ));				    		
				    		chown($photoDir,$wwwUser);
				    		chgrp($photoDir,$wwwUserGroup);
				    	}

				    	$pathElements = explode('/',$pathExtracted);
				    	end($pathElements);

				    	$fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName(current($pathElements));

				    	if (file_exists($photoDir.'/'.$fileNamePhysic)) {
				    		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.$fileNamePhysic;
				    	}

				    	erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $pathExtracted, $photoDir.'/normal_'.$fileNamePhysic );
				    	erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb',$pathExtracted, $photoDir.'/thumb_'.$fileNamePhysic );
				    					    	
				    	$dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
						// If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
						if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
								erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $pathExtracted, $photoDir.'/'.$fileNamePhysic ); 
						} else  {
								rename($pathExtracted,$photoDir.'/'.$fileNamePhysic);
						}
									    	
				    	chown($photoDir.'/'.$fileNamePhysic,$wwwUser);
				    	chown($photoDir.'/normal_'.$fileNamePhysic,$wwwUser);
				    	chown($photoDir.'/thumb_'.$fileNamePhysic,$wwwUser);
				    	
				    	chgrp($photoDir.'/'.$fileNamePhysic,$wwwUserGroup);
				    	chgrp($photoDir.'/normal_'.$fileNamePhysic,$wwwUserGroup);
				    	chgrp($photoDir.'/thumb_'.$fileNamePhysic,$wwwUserGroup);
				    					    					    	
				    	chmod($photoDir.'/'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
				    	chmod($photoDir.'/normal_'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
				    	chmod($photoDir.'/thumb_'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
				    	
				    	$image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
				    	$image->total_filesize = filesize($photoDir.'/'.$fileNamePhysic)+filesize($photoDir.'/thumb_'.$fileNamePhysic)+filesize($photoDir.'/normal_'.$fileNamePhysic);
				    	$image->filepath = 'userpics/'.$this->user_id.'/'.$album->aid.'/';

				    	$imageAnalyze = new ezcImageAnalyzer( $photoDir.'/'.$fileNamePhysic );
				    	$image->pwidth = $imageAnalyze->data->width;
				    	$image->pheight = $imageAnalyze->data->height;
				    	$image->hits = 0;
				    	$image->ctime = time();
				    	$image->owner_id = $this->user_id;
				    	$image->pic_rating = 0;
				    	$image->votes = 0;

				    	$image->title = '';
				    	$image->caption = '';
				    	$image->keywords =  '';
				    	$image->approved =  1;
				    	$image->filename = $fileNamePhysic;

				    	$session->update($image);
				    	$image->clearCache();
				    	
				    } catch (Exception $e) {
				    	print_r($e);
				    	$session->delete($image);
				    	unlink($pathExtracted);
				    }
				    
					
				} else {
					unlink($pathExtracted);
				}
			}			
						
			$archive->next();
		} 
		
  		$this->cleanup();  		  	 
   }
   
   public $id = null;
   public $album_id = 0;
   public $user_id = 0;
   public $filename = '';
   public $album_name = '';
   public $description = '';
   public $keywords = '';
   
   

}