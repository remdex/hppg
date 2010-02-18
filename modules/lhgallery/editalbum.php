<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/albumedit.tpl.php');

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
        erLhcoreClassGallery::getSession()->update($AlbumData); 
        
        $AlbumData->clearAlbumCache();        
        CSCacheAPC::getMem()->increaseCacheVersion('album_count_version');
            
        erLhcoreClassModule::redirect('gallery/myalbums');
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('album',$AlbumData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),

array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My albums')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit',$AlbumData->title)),


);