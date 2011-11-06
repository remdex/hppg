<?php

$Module = array( "name" => "Similarity module",
				 'variable_params' => true );

$ViewList = array();
   
$ViewList['image'] = array( 
    'params' => array('image_id'),    
);
   
$ViewList['imagejson'] = array( 
    'params' => array('image_id'),    
);
    
$ViewList['uploadsimilar'] = array( 
    'params' => array(),    
);
    
$ViewList['uploadcanvas'] = array( 
    'params' => array(),    
);
    
$ViewList['sketch'] = array( 
    'params' => array(),    
);
    
$FunctionList = array();  