<?php

$Module = array( "name" => "Users, groups management");

$ViewList = array();
   
$ViewList['login'] = array( 
    'script' => 'login.php',
    'params' => array()
    ); 
     
$ViewList['logout'] = array( 
    'script' => 'logout.php',
    'params' => array()
    );
    
$ViewList['account'] = array( 
    'script' => 'account.php',
    'params' => array(),
    'pagelayout' => 'main_3column',
    'functions' => array( 'selfedit' )
    );  
      
$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array(),    
    'pagelayout' => 'main_3column',
    'functions' => array( 'selfedit' )
    );  
      
$ViewList['userlist'] = array( 
    'script' => 'userlist.php',
    'params' => array(),
    'functions' => array( 'userlist' ),
    'pagelayout' => 'admin'
    );
          
$ViewList['grouplist'] = array( 
    'script' => 'grouplist.php',
    'params' => array(),
    'functions' => array( 'grouplist' ),
    'pagelayout' => 'admin'
    );
    
$ViewList['edit'] = array( 
    'script' => 'edit.php',
    'params' => array('user_id'),
    'functions' => array( 'edituser' ),
    'pagelayout' => 'admin'
    ); 
       
$ViewList['delete'] = array( 
    'script' => 'delete.php',
    'params' => array('user_id'),
    'functions' => array( 'deleteuser' ),
    'pagelayout' => 'admin'
    );   
                
$ViewList['new'] = array( 
    'script' => 'new.php',
    'params' => array(),
    'functions' => array( 'createuser' ),
    'pagelayout' => 'admin'
    ); 
           
$ViewList['newgroup'] = array( 
    'script' => 'newgroup.php',
    'params' => array(),
    'functions' => array( 'creategroup', 'editgroup' ),
    'pagelayout' => 'admin'
    );
    
$ViewList['editgroup'] = array( 
    'script' => 'editgroup.php',
    'params' => array('group_id'),
    'functions' => array( 'editgroup' ),
    'pagelayout' => 'admin'
    );     
    
$ViewList['groupassignuser'] = array( 
    'script' => 'groupassignuser.php',
    'params' => array('group_id'),
    'functions' => array( 'groupassignuser' ),
    'pagelayout' => 'admin'
    ); 
    
$ViewList['deletegroup'] = array( 
    'script' => 'deletegroup.php',
    'params' => array('group_id'),
    'functions' => array( 'deletegroup' ),
    'pagelayout' => 'admin'
    ); 
    
$ViewList['registration'] = array( 
    'script' => 'registration.php',
    'params' => array()
    );
    
$ViewList['registered'] = array( 
    'script' => 'registered.php',
    'params' => array()
    );
 
$ViewList['forgotpassword'] = array( 
    'script' => 'forgotpassword.php',
    'params' => array(),
    );

$ViewList['remindpassword'] = array( 
    'script' => 'remindpassword.php',
    'params' => array('hash'),
    ); 
           
$FunctionList = array();
$FunctionList['groupassignuser'] = array('explain' => 'Allow logged user to assing user to group');  
$FunctionList['editgroup'] = array('explain' => 'Allow logged user to edit group');  
$FunctionList['creategroup'] = array('explain' => 'Allow logged user to create group');  
$FunctionList['deletegroup'] = array('explain' => 'Allow logged user to delete group');  
$FunctionList['createuser'] = array('explain' => 'Allow logged user to create another user');  
$FunctionList['deleteuser'] = array('explain' => 'Allow logged user to delete another user');  
$FunctionList['edituser'] = array('explain' => 'Allow logged user to edit another user');  
$FunctionList['grouplist'] = array('explain' => 'Allow logged user to list group');  
$FunctionList['userlist'] = array('explain' => 'Allow logged user to list users');  
$FunctionList['selfedit'] = array('explain' => 'Allow logged user to edit his own data');  


?>