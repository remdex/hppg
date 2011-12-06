<?php

ini_set("max_execution_time", "9600");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once "./ezcomponents/Base/src/base.php";

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( './', './lib/autoloads'); 

ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);
    
$input = new ezcConsoleInput();

$helpOption = $input->registerOption(
    new ezcConsoleOption(
        's',
        'siteaccess',
        ezcConsoleInput::TYPE_STRING 
    )
);
 
$directoryOption = $input->registerOption(
    new ezcConsoleOption(
        'd',
        'directory',
        ezcConsoleInput::TYPE_STRING 
    )
); 

$categoryOption = $input->registerOption(
    new ezcConsoleOption(
        'c',
        'category',
        ezcConsoleInput::TYPE_INT 
    )
);



try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
} 

$siteAccessName = 'site_admin';
if ( !$helpOption->value === false )
{
    $siteAccessName = $helpOption->value;
} 


try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}

$instance = erLhcoreClassSystem::instance();
$instance->SiteAccess = $siteAccessName; 
$instance->SiteDir = './';
$cfgSite = erConfigClassLhConfig::getInstance();    
$defaultSiteAccess = $cfgSite->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->getSetting('site_access_options',$siteAccessName);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$siteAccessName;   
$output = new ezcConsoleOutput();

$output->formats->success->color   = 'green';

$output->outputText( "Directory base - {$directoryOption->value}\n", 'success');
$listAlbums = erLhcoreClassGalleryBatch::listDirectory($directoryOption->value);
$destinationCategory = erLhcoreClassModelGalleryCategory::fetch($categoryOption->value);
$session = erLhcoreClassGallery::getSession();

foreach ($listAlbums as $dir) {
    
    $images = erLhcoreClassGalleryBatch::listDirectoryRecursive($dir);
    if (count($images) > 0) {        
        $output->outputText( "Importing directory - {$dir}\n");        
        $albumName = trim(str_replace(array('-',','),array(' ',''),basename($dir)));
        $output->outputText( "Album name - {$albumName}\n");       
        
        $q = $session->database->createSelectQuery();  
        $q->select( "aid" )->from( "lh_gallery_albums" );       
        $q->where($q->expr->eq( 'title',$q->bindValue( $albumName )),$q->expr->eq( 'category',$q->bindValue( $categoryOption->value )));      
        $stmt = $q->prepare();       
        $stmt->execute();   
        $AlbumID = $stmt->fetchColumn();  
        
        if ($AlbumID === false) {                     
           $AlbumData = new erLhcoreClassModelGalleryAlbum();	   
	       $AlbumData->owner_id = 1; 
	       $AlbumData->category = $destinationCategory->cid;  
	       $AlbumData->title = trim($albumName);
	       $AlbumData->public = 1;
	       $AlbumData->storeThis();
	       $AlbumID = $AlbumData->aid;
        }
        
        $output->outputText( "Destination album ID - {$AlbumID}\n"); 
                       
        foreach ($images as $imagePath) {
            
            try {
                
                $photoDir = dirname($imagePath);
                $fileName = basename($imagePath);
                
                if ((!preg_match('/^(normal_|.normal_|thumb_|.thumb_)/i',basename($fileName))) && ($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($imagePath)) !== false) {  
                       
                    if (!file_exists($photoDir.'/normal_'.$fileName) && !file_exists($photoDir.'/thumb_'.$fileName))
                    {                                              
                        $image = new erLhcoreClassModelGalleryImage();
                        $image->aid = $AlbumID;
                              
                        $session->save($image); 
                       
                        $filetype->processLocalBatch($image,array(
                	       'photo_dir'        => $photoDir,
                	       'file_name_physic' => $fileName,
                	       'post_file_name'   => $imagePath    	      
            	        ));
                       
                       $image->filepath = str_replace('albums/','',$photoDir).'/';
                                                     
                       $image->hits = 0;
                       $image->ctime = time();
                       $image->owner_id = 1;
                       $image->pic_rating = 0;
                       $image->votes = 0;
                       
                       $image->title = '';
                       $image->caption = '';
                       $image->keywords =  '';
                       $image->approved =  1;
                       $image->filename = $fileName;
                              
                       $session->update($image);
                       $image->clearCache();
                       
                       // Index colors
            	       erLhcoreClassPalleteIndexImage::indexImage($image,true);  
            	       
            	       // Index in search table
            	       erLhcoreClassModelGallerySphinxSearch::indexImage($image,true);
            	           	       
            	       erLhcoreClassModelGalleryAlbum::updateAddTime($image); 
            	       
            	       $output->outputText( "Imported image - {$imagePath}\n"); 
                    } else {               
                       //$output->outputText( "Skiping image - {$imagePath}\n");
                    }
                } else {               
                   // $output->outputText( "Skiping image - {$imagePath}\n");
                }
            } catch (Exception $e) {
                $session->delete($image);                
//              $output->outputText('Exception during upload'.$e); 
//              exit;                
            } 
        } 
        
        echo $output->outputText('Memory usage - '. round((memory_get_usage()/1024/1024),2)."\n", 'success');    
    }    
}
//php ./bin/php/inport_directory.php -d albums/somepath -c destination_category_id
$output->outputText( "Import finished \n"); 