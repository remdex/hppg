<?php

$Module = array( "name" => "Article module",
				 'variable_params' => true );

$ViewList = array();

$ViewList['static'] = array( 
    'script' => 'static.php',
    'params' => array('static_id')
    );    
           
$ViewList['staticlist'] = array( 
    'script' => 'staticlist.php',
    'params' => array(),    
    'functions' => array( 'edit' )
    );     
          
$ViewList['editstatic'] = array( 
    'script' => 'editstatic.php',
    'params' => array('static_id'),    
    'functions' => array( 'edit' )
    );
              
$ViewList['newstatic'] = array( 
    'script' => 'newstatic.php',
    'params' => array(),    
    'functions' => array( 'edit' )
    );
   
$FunctionList = array();  
$FunctionList['edit'] = array('explain' => 'Allow edit articles');  

?>