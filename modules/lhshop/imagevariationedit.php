<?php
$tpl = erLhcoreClassTemplate::getInstance('lhshop/imagevariationedit.tpl.php');

$imageVariation = erLhcoreClassShop::getSession()->load( 'erLhcoreClassModelShopImageVariation', (int)$Params['user_parameters']['variation_id']);  

if (isset($_POST['UpdateVariation']))
{
    $definition = array(
        'Name' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        ),
        'Width' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        ),    
        'Height' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        ),    
        'Credits' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        ),    
        'Position' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        ),    
        'VariationType' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
        
    if ( !$form->hasValidData( 'Name' ) || $form->Name == '')
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Please enter variation name!');
    } else $imageVariation->name = $form->Name; 
        
    if ( !$form->hasValidData( 'VariationType') && (!$form->hasInputField( 'Width' ) || $form->Width == 0)  )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Please enter width!');
    } elseif (!$form->hasInputField( 'VariationType')) $imageVariation->width = $form->Width;    
        
    if ( !$form->hasValidData( 'VariationType') && (!$form->hasInputField( 'Height' ) || $form->Height == 0)  )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Please enter height!');
    } elseif (!$form->hasInputField( 'VariationType')) $imageVariation->height = $form->Height;
     
    if ( $form->hasValidData( 'VariationType') && $form->VariationType == true )
    {
    	$imageVariation->type = erLhcoreClassModelShopImageVariation::ORIGINAL_VARIATION;
    } else $imageVariation->type = erLhcoreClassModelShopImageVariation::CUSTOM_VARIATION;
    
    if ( !$form->hasValidData( 'Credits' ) || $form->Credits == 0  ) 
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Please enter credit number!');
    } else $imageVariation->credits = $form->Credits;   
    
    if ( !$form->hasValidData( 'Position' ) ) 
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Please enter variation position!');
    } else $imageVariation->position = $form->Position;
    
    if (count($Errors) == 0)
    {      	
        erLhcoreClassShop::getSession()->update($imageVariation);
        
        erLhcoreClassModule::redirect('shop/imagevariation');
        return ;
        
    }  else {
        $tpl->set('errArr',$Errors);
    }
}

$tpl->set('imagevariation',$imageVariation);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('url' => erLhcoreClassDesign::baseurl('shop/imagevariation'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Images variations sizes')),
array('title' => $imageVariation->name)
)

?>