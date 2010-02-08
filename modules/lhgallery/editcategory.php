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
