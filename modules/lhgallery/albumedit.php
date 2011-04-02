<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/albumedit.tpl.php');

$AlbumData = $Params['user_object'] ;


if (isset($Params['user_parameters_unordered']['action']) && $Params['user_parameters_unordered']['action'] == 'removethumb') {
    $AlbumData->album_pid = 0;
    $AlbumData->updateThis();
}

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
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Please enter album name!');
    } else {$AlbumData->title = $form->AlbumName;}
        
    if ( $form->hasValidData( 'AlbumDescription' ) && $form->AlbumDescription != '' )
    {
        $AlbumData->description = $form->AlbumDescription;
    } else {
        $AlbumData->description = '';
    }
    
    if ( $form->hasValidData( 'AlbumKeywords' ) && $form->AlbumKeywords != '' )
    {
        $AlbumData->keyword = $form->AlbumKeywords;
    } else {
        $AlbumData->keyword = '';
    }
    
    if (count($Errors) == 0)
    {                        

        $AlbumData->updateThis();
        erLhcoreClassModule::redirect('gallery/myalbums');
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('album',$AlbumData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Account')),
array('url' => erLhcoreClassDesign::baseurl('gallery/myalbums'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Albums')),
array('title' => $AlbumData->title)
);