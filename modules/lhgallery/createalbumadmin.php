<?php

$CategoryData = erLhcoreClassModelGalleryCategory::fetch($Params['user_parameters']['category_id']);

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/createalbumadmin.tpl.php');
$AlbumData = new erLhcoreClassModelGalleryAlbum();

// Parent category by default
$AlbumData->category = $CategoryData->cid;

// Logged user by default
$currentUser = erLhcoreClassUser::instance();
$AlbumData->owner_id = $currentUser->getUserID();

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
        'UserID' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        ), 
        'AlbumCategoryID' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'AlbumName' ) || $form->AlbumName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Please enter album name!');
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
        $AlbumData->owner_id = $form->UserID;
        $AlbumData->category = $form->AlbumCategoryID;
        $AlbumData->storeThis();
                         
        erLhcoreClassModule::redirect('/gallery/admincategorys/'.$AlbumData->category);
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }        
}

$tpl->set('album',$AlbumData);$pathObjects = array();

$path = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$CategoryData->cid);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
}

$Result['content'] = $tpl->fetch();
$Result['path'] = $path;