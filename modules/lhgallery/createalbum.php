<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/createalbum.tpl.php');

$AlbumData = new erLhcoreClassModelGalleryAlbum();

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
    
    if (count($Errors) == 0)
    {     
        $currentUser = erLhcoreClassUser::instance();
        $AlbumData->owner_id = $currentUser->getUserID(); 
        $AlbumData->category = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('owner_id' => $currentUser->getUserID())),'cid');  
        $AlbumData->storeThis();
            
        if (isset($_POST['CreateAlbumAndUpload']))
            erLhcoreClassModule::redirect('gallery/addimages/'.$AlbumData->aid);
        else
            erLhcoreClassModule::redirect('gallery/myalbums');
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('album',$AlbumData);

$Result['content'] = $tpl->fetch();