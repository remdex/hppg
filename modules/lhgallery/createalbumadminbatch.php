<?php


$CategoryData = erLhcoreClassModelGalleryCategory::fetch($Params['user_parameters']['category_id']);

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/createalbumadminbatch.tpl.php');


$tpl->set('categoryID',$CategoryData->cid);

if (isset($_POST['CreateAlbum']) || isset($_POST['CreateAlbumAndUpload']))
{      
	$dataAlbum = array();
	
    $definition = array(
        'AlbumName' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),  
        'AlbumPublic' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'AlbumName' ) || $form->AlbumName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Please enter album name!');
    } else {
    	
    	$dataAlbum['titles'] = $form->AlbumName;
    }
     
    if ( $form->hasValidData( 'AlbumPublic' ) && $form->AlbumPublic == true )
    {
        $dataAlbum['public'] = 1;
    } else {
        $dataAlbum['public'] = 0;
    }
    
    if (count($Errors) == 0)
    {         	
    	$albumNames = explode("\n",trim($dataAlbum['titles']));

    	foreach ($albumNames as $name)
    	{
			$AlbumData = new erLhcoreClassModelGalleryAlbum();
	        $currentUser = erLhcoreClassUser::instance();
	        $AlbumData->owner_id = $currentUser->getUserID(); 
	        $AlbumData->category = $CategoryData->cid;  
	        $AlbumData->title = trim($name);
	        $AlbumData->public = $dataAlbum['public'];	                 
	        erLhcoreClassGallery::getSession()->save($AlbumData); 
    	}
    	
        CSCacheAPC::getMem()->increaseCacheVersion('album_count_version');        
        $AlbumData->clearAlbumCache();
                  
        
        erLhcoreClassModule::redirect('/gallery/admincategorys/'.$AlbumData->category);
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }        
}

$tpl->set('album',$AlbumData);
$pathObjects = array();

$path = array();
$pathCategorys = array(); 
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$CategoryData->cid);        
foreach ($pathObjects as $pathItem)
{
   $pathCategorys[] = $pathItem->cid;
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
}

$Result['content'] = $tpl->fetch();
$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;