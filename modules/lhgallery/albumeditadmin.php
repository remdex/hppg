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
    
    if ( $form->hasValidData( 'AlbumPublic' ) && $form->AlbumKeywords == true )
    {
        $AlbumData->public = 1;
    } else {
        $AlbumData->public = 0;
    }
    
    if (count($Errors) == 0)
    {                                
        erLhcoreClassGallery::getSession()->update($AlbumData); 
        
        $AlbumData->clearAlbumCache();        
        CSCacheAPC::getMem()->increaseCacheVersion('album_count_version');
            
        erLhcoreClassModule::redirect('gallery/admincategorys/'.$AlbumData->category);
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('album',$AlbumData);

$Result['content'] = $tpl->fetch();

$pathObjects = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$AlbumData->category);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
}
 
$path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/managealbumimages/').$AlbumData->aid,'title' => $AlbumData->title); 
 
$Result['path'] = $path;