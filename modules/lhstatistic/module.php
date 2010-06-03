<?php

$Module = array( "name" => "Google analytics API statistic module");

$ViewList = array();
   
$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array(),
    'functions' => array( 'admin' ),
    );    
    
$ViewList['view'] = array( 
    'script' => 'view.php',
    'params' => array()
    ); 
        
$ViewList['authanalytics'] = array( 
    'script' => 'authanalytics.php',
    'params' => array(),
    'functions' => array( 'admin' ),
    );  
           
$ViewList['choosesite'] = array( 
    'script' => 'choosesite.php',
    'params' => array(),
    'functions' => array( 'admin' ),
    );    
 
$FunctionList['admin'] = array('explain' => 'Admin functions');  
$FunctionList['use'] = array('explain' => 'Allow users view statistic');  

?>