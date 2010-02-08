<?php

$Module = array( "name" => "Permissions configuration");

$ViewList = array();
   
$ViewList['roles'] = array( 
    'script' => 'roles.php',
    'params' => array(),
    'functions' => array( 'list' ),
    'pagelayout' => 'admin'
    );    
    
$ViewList['newrole'] = array( 
    'script' => 'newrole.php',
    'params' => array(),
    'functions' => array( 'new' ),
    'pagelayout' => 'admin'
    );    
     
$ViewList['editrole'] = array( 
    'script' => 'editrole.php',
    'params' => array('role_id'),
    'functions' => array( 'edit' ),
    'pagelayout' => 'admin'
    );    
      
$ViewList['modulefunctions'] = array( 
    'script' => 'modulefunctions.php',
    'params' => array('module_path'),
    'functions' => array( 'edit' ),
    'pagelayout' => 'admin'
);   
    
$ViewList['deleterole'] = array( 
    'script' => 'deleterole.php',
    'params' => array('role_id'),
    'functions' => array( 'delete' ),
    'pagelayout' => 'admin'
);   
  
$ViewList['groupassignrole'] = array( 
    'script' => 'groupassignrole.php',
    'params' => array('group_id'),
    'functions' => array( 'edit' ),
    'pagelayout' => 'admin'
);   

$ViewList['roleassigngroup'] = array( 
    'script' => 'roleassigngroup.php',
    'params' => array('role_id'),
    'functions' => array( 'edit' ),
    'pagelayout' => 'admin'
); 
      
$FunctionList['edit'] = array('explain' => 'Access to edit role');  
$FunctionList['delete'] = array('explain' => 'Access to delete role');  
$FunctionList['list'] = array('explain' => 'Access to list roles');  
$FunctionList['new'] = array('explain' => 'Access to create new role');  

?>