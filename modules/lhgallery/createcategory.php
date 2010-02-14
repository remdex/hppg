<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/admin/createcategory.tpl.php');

$CategoryData = new erLhcoreClassModelGalleryCategory();

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
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'CategoryName' ) || $form->CategoryName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter category name!');
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
                 
        if (isset($CategoryDataParent))
        {
            $CategoryData->parent = $CategoryDataParent->cid;
        }
        
        $currentUser = erLhcoreClassUser::instance();
        $CategoryData->owner_id = $currentUser->getUserID(); 
        
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


$pathObjects = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$CategoryDataParent->cid);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
}
 
$path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$CategoryData->cid,'title' => $pathItem->name); 
 
$Result['path'] = $path;