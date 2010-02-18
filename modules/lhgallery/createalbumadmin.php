<?php


$CategoryData = erLhcoreClassModelGalleryCategory::fetch($Params['user_parameters']['category_id']);

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/createalbumadmin.tpl.php');
$AlbumData = new erLhcoreClassModelGalleryAlbum();
$tpl->set('categoryID',$CategoryData->cid);

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
    
    if (count($Errors) == 0)
    {     
        $currentUser = erLhcoreClassUser::instance();
        $AlbumData->owner_id = $currentUser->getUserID(); 
        $AlbumData->category = $CategoryData->cid;            
        erLhcoreClassGallery::getSession()->save($AlbumData); 
        
        CSCacheAPC::getMem()->increaseCacheVersion('album_count_version');        
        $AlbumData->clearAlbumCache();
                  
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