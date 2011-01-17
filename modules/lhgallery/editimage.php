<?php

try {
        $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/editimage.tpl.php');
        
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

$tpl->set('image',$Image);

$Result['content'] = $tpl->fetch();
$Result['path'] = $Image->path;
