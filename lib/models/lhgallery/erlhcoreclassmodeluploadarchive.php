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
   			
   			if ($this->user_id == 0 || $this->user_id == erConfigClassLhConfig::getInstance()->conf->getSetting( 'user_settings','anonymous_user_id')) {
   				$album->category = $publicCategoryID;
   			} else {
   				$album->category = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('owner_id' => $this->user_id)),'cid');
   			}
   			
   			if ($album->category == 0) {
   			    $album->category = $publicCategoryID;
   			}
   			 			
   			$album->title = $this->album_name;
   			$album->description = $this->description;   			
   			$album->owner_id = $this->user_id;
   			$album->keyword = $this->keywords;
   			
   			$session->save($album);
   			
   		} else {  
   		    if ($this->album_id > 0){
   			  $album = erLhcoreClassModelGalleryAlbum::fetch($this->album_id); 
   		    } else {
   		       $this->cleanup();
   		       return ;
   		    }
   		}
   		
   		$config = erConfigClassLhConfig::getInstance();
   		
   		// Auto approvement
   		$userOwner = erLhcoreClassUser::instance();
	    $userOwner->setLoggedUser($this->user_id);
	    
	    $canApproveSelfImages = $userOwner->hasAccessTo('lhgallery','auto_approve_self_photos');
        $canApproveAllImages =  $userOwner->hasAccessTo('lhgallery','auto_approve');           
        $approved = ($album->owner_id == $this->user_id && $canApproveSelfImages) || ($canApproveAllImages == true);           	    
	       			 
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
				
				if (($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($pathExtracted)) !== false /*erLhcoreClassImageConverter::isPhotoLocal($pathExtracted)*/) {
										
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
				    	
				    	$filetype->processLocal($image,array(
                	       'photo_dir'        => $photoDir,
                	       'file_name_physic' => $fileNamePhysic,
                	       'post_file_name'   => $pathExtracted,
                	       'file_session'     => $this,
                	       'album'            => $album,
            	       ));
	       			    
				    	$image->ctime = time();
				    	$image->owner_id = $this->user_id;
				    	$image->pic_rating = 0;
				    	$image->votes = 0;

				    	$image->title = '';
				    	$image->caption = '';
				    	$image->keywords =  '';
				    	$image->approved =  $approved;
				    	$image->filename = $fileNamePhysic;

				    	$session->update($image);
				    	$image->clearCache();
				    	
				    } catch (Exception $e) {				    	
				    	erLhcoreClassLog::writeCronjob('Exception during archive image import'.$e);
				    	$session->delete($image);
				    	unlink($pathExtracted);
				    }
				    
					
				} else {
					unlink($pathExtracted);
				}
			}			
						
			$archive->next();
		} 
		
		$album->clearAlbumCache();
		
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