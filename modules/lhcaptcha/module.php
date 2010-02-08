<?php

$Module = array( "name" => "Article module",
				 'variable_params' => true );

$ViewList = array();
   
$ViewList['image'] = array( 
    'script' => 'image.php',
    'params' => array('captcha_name')
    );

$FunctionList = array();  

?>