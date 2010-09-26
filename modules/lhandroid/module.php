<?php

$Module = array( "name" => "Android Module",
				 'variable_params' => true );

$ViewList = array();
   
$ViewList['main'] = array( 
    'script' => 'main.php',
    'params' => array()
    );  
         
$FunctionList = array();  
$FunctionList['use'] = array('explain' => 'General registered user permission [use]');
$FunctionList['administrate'] = array('explain' => 'Global edit permission [administrate]');
$FunctionList['personal_albums'] = array('explain' => 'Allow users to have personal albums [personal_albums]');
$FunctionList['public_upload'] = array('explain' => 'Allow anyone to upload images using flash [public_upload]');
$FunctionList['public_upload_archive'] = array('explain' => 'Allow anyone to upload archive [public_upload_archive]');

?>