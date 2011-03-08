<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/albumeditadmin.tpl.php');

$AlbumData = $Params['user_object'] ;

if (isset($_POST['CreateAlbum']) || isset($_POST['CreateAlbumAndUpload']))
{      
    $definition = array(
        'AlbumName' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),
        
        'AlbumDescription' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ), 
        'AlbumKeywords' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ), 
        'AlbumPublic' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ), 
        'AlbumCategoryID' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        ), 
        'UserID' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'AlbumName' ) || $form->AlbumName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter album name!');
    } else {$AlbumData->title = $form->AlbumName;}
    
    
    if ( $form->hasValidData( 'AlbumDescription' ) && $form->AlbumDescription != '' )
    {
        $AlbumData->description = $form->AlbumDescription;
    }
    
    if ( $form->hasValidData( 'AlbumKeywords' ) && $form->AlbumKeywords != '' )
    {
        $AlbumData->keyword = $form->AlbumKeywords;
    }
    
    if ( $form->hasValidData( 'AlbumPublic' ) && $form->AlbumPublic == true )
    {
        $AlbumData->public = 1;
    } else {
        $AlbumData->public = 0;
    }
    
    if (count($Errors) == 0)
    {   
    	// Clear previous category cache
    	if ($AlbumData->category != $form->AlbumCategoryID) {
    		$category = erLhcoreClassModelGalleryCategory::fetch($AlbumData->category);
    		$category->clearCategoryCache();
    	}
    	    	
    	$AlbumData->category = $form->AlbumCategoryID;
    	$AlbumData->owner_id = $form->UserID;
    	
    	$AlbumData->updateThis();
            
        erLhcoreClassModule::redirect('gallery/admincategorys/'.$AlbumData->category);
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('album',$AlbumData);

$Result['content'] = $tpl->fetch();

$pathObjects = array();
$pathCategorys = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$AlbumData->category);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name);
   $pathCategorys[] = $pathItem->cid;
}
 
$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$AlbumData->aid,'title' => $AlbumData->title); 
 
$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;
$Result['album_id'] = $AlbumData->aid;