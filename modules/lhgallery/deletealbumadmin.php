<?php

// Simple is it? :)
$category = $Params['user_object']->category;
$Params['user_object']->removeThis();

if ( $Params['user_parameters_unordered']['moduler'] === null && $Params['user_parameters_unordered']['functionr'] === null )
{
    erLhcoreClassModule::redirect('gallery/admincategorys/'.$category);
} else {    
    $pageURL = is_numeric($Params['user_parameters_unordered']['page']) && $Params['user_parameters_unordered']['page'] > 1 ? '/(page)/' . $Params['user_parameters_unordered']['page'] : '';      
    erLhcoreClassModule::redirect($Params['user_parameters_unordered']['moduler'] . '/' . $Params['user_parameters_unordered']['functionr'] . $pageURL);
}
exit;
