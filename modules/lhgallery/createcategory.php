<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/createcategory.tpl.php');

$CategoryData = new erLhcoreClassModelGalleryCategory();

// Assig logged user by default
$currentUser = erLhcoreClassUser::instance();
$CategoryData->owner_id = $currentUser->getUserID(); 
        
if ((int)$Params['user_parameters']['category_id'] > 0) {
    $CategoryDataParent = erLhcoreClassModelGalleryCategory::fetch($Params['user_parameters']['category_id']);
    $tpl->set('category_parent',$CategoryDataParent); 
}


if (isset($_POST['Update_Category']))
{      
    $definition = array(
        'CategoryName' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),        
        'DescriptionCategory' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ), 
        'HideFrontpage' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ), 
        'UserID' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'CategoryName' ) || $form->CategoryName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Please enter category name!');
    } else {$CategoryData->name = $form->CategoryName;}
    
    
    if ( $form->hasValidData( 'DescriptionCategory' ) && $form->DescriptionCategory != '' )
    {
        $CategoryData->description = $form->DescriptionCategory;
    }
    
    if ( $form->hasValidData( 'HideFrontpage' ) && $form->HideFrontpage == true )
    {
        $CategoryData->hide_frontpage = $form->HideFrontpage;
    } else {
        $CategoryData->hide_frontpage = 0;
    }
    
    if (count($Errors) == 0)
    {                  
        $CategoryData->owner_id = $form->UserID;   
             
        if (isset($CategoryDataParent))
        {
            $CategoryData->parent = $CategoryDataParent->cid;
        }
        
        erLhcoreClassGallery::getSession()->save($CategoryData);         
        $CategoryData->clearCategoryCache();        
               
        erLhcoreClassModule::redirect('gallery/admincategorys/'.$CategoryData->parent);
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('category',$CategoryData);


$Result['content'] = $tpl->fetch();

if (isset($CategoryDataParent)) {
    $pathObjects = array();
    erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$CategoryDataParent->cid);        
    foreach ($pathObjects as $pathItem)
    {
       $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name); 
    } 
} else {
    $path[] =  array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Home')); 
    
}

$Result['path'] = $path;