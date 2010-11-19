<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/editcategory.tpl.php');

$CategoryData = erLhcoreClassModelGalleryCategory::fetch($Params['user_parameters']['category_id']);

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
    	$CategoryData->owner_id = $form->UserID;
    	
        erLhcoreClassGallery::getSession()->update($CategoryData);         
        $CategoryData->clearCategoryCache();        
               
        erLhcoreClassModule::redirect('gallery/admincategorys');
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }        
}

$tpl->set('category',$CategoryData);

$Result['content'] = $tpl->fetch();

$pathObjects = array();
$pathCategorys = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$CategoryData->cid);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name); 
   $pathCategorys[] = $pathItem->cid;
}
 
$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$CategoryData->cid,'title' => $pathItem->name); 
 
$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;