<?php

$Module = array( "name" => "Gallery",
				 'variable_params' => true );

$ViewList = array();
   
$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array()
    );  
     
$ViewList['album'] = array( 
    'script' => 'album.php',
    'params' => array('album_id'),
    'uparams' => array('sort')
    );
    
$ViewList['category'] = array( 
    'script' => 'category.php',
    'params' => array('category_id')
    );
    
$ViewList['image'] = array( 
    'script' => 'image.php',
    'params' => array('image_id'),
    'uparams' => array('mode','keyword','sort'),
    );  
      
$ViewList['popular'] = array( 
    'script' => 'popular.php',
    'params' => array()
    );  
         
$ViewList['testing'] = array( 
    'script' => 'testing.php',
    'params' => array()
    ); 
         
$ViewList['lastuploads'] = array( 
    'script' => 'lastuploads.php',
    'params' => array()
    ); 
            
$ViewList['toprated'] = array( 
    'script' => 'toprated.php',
    'params' => array()
    );   
           
$ViewList['lasthits'] = array( 
    'script' => 'lasthits.php',
    'params' => array()
    );    
            
$ViewList['tagphoto'] = array( 
    'script' => 'tagphoto.php',
    'params' => array()
    ); 
                
$ViewList['addvote'] = array( 
    'script' => 'addvote.php',
    'params' => array()
    ); 
                    
$ViewList['ajaximages'] = array( 
    'script' => 'ajaximages.php',    
    'params' => array('image_id'),
    'uparams' => array('mode','keyword','sort'),
    );   
      
$ViewList['lastcommented'] = array( 
    'script' => 'lastcommented.php',
    'params' => array()
    );   
       
$ViewList['search'] = array( 
    'script' => 'search.php',
    'params' => array(),
    'uparams' => array('keyword','total','sort'),
    );
    
$ViewList['myalbums'] = array( 
    'script' => 'myalbums.php',
    'params' => array(),
    'pagelayout' => 'main_3column',
    'functions' => array( 'use' ),
    ); 
       
$ViewList['createalbum'] = array( 
    'script' => 'createalbum.php',
    'params' => array(),
    'pagelayout' => 'main_3column',
    'functions' => array( 'use' ),
    );  
                 
$ViewList['createalbumadmin'] = array( 
    'script' => 'createalbumadmin.php',
    'params' => array('category_id'),
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    ); 
                    
$ViewList['createcategory'] = array( 
    'script' => 'createcategory.php',
    'params' => array('category_id'),
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    );
    
$ViewList['editalbum'] = array( 
    'script' => 'editalbum.php',
    'params' => array('album_id'),
    'pagelayout' => 'main_3column',
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' =>'administrate'),
    ); 
       
$ViewList['editalbumadmin'] = array( 
    'script' => 'editalbumadmin.php',
    'params' => array('album_id'),
    'pagelayout' => 'admin',
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' =>'administrate'),
    );
    
$ViewList['addimages'] = array( 
    'script' => 'addimages.php',
    'params' => array('album_id'),
    'pagelayout' => 'main_3column',
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );
        
$ViewList['addimagesadmin'] = array( 
    'script' => 'addimagesadmin.php',
    'params' => array('album_id'),
    'pagelayout' => 'admin',
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );
    
$ViewList['fileuploadcontainer'] = array( 
    'script' => 'fileuploadcontainer.php',
    'params' => array('fileID','album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::canUpload','param' => 'album_id'),'global' => 'administrate'),
    ); 
       
$ViewList['getsession'] = array( 
    'script' => 'getsession.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::canUpload','param' => 'album_id'),'global' => 'administrate'),
    );
    
$ViewList['upload'] = array( 
    'script' => 'upload.php',
    'params' => array()
    );  
       
$ViewList['sessiondone'] = array( 
    'script' => 'sessiondone.php',
    'params' => array('hash')
    );  
          
$ViewList['admincategorys'] = array( 
    'script' => 'admincategorys.php',
    'params' => array('category_id'),    
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    );    
            
$ViewList['managealbum'] = array( 
    'script' => 'managealbum.php',
    'params' => array('category_id'),    
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    );   
              
$ViewList['managealbumimages'] = array( 
    'script' => 'managealbumimages.php',
    'params' => array('album_id'),    
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    );  
             
$ViewList['editcategory'] = array( 
    'script' => 'editcategory.php',
    'params' => array('category_id'),    
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    ); 
    
$ViewList['batchadd'] = array( 
    'script' => 'batchadd.php',
    'params' => array(),    
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    );         
              
$ViewList['addimagesbatch'] = array( 
    'script' => 'addimagesbatch.php',
    'params' => array('album_id'),    
    'pagelayout' => 'admin',
    'functions' => array( 'administrate' ),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );
              
$ViewList['mylistalbum'] = array( 
    'script' => 'mylistalbum.php',
    'params' => array('album_id'),
    'pagelayout' => 'main_3column',
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    ); 
                 
$ViewList['updateimage'] = array( 
    'script' => 'updateimage.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' =>'administrate'),
    ); 
                     
$ViewList['deleteimage'] = array( 
    'script' => 'deleteimage.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' => 'administrate'),
    ); 
                         
$ViewList['deletealbum'] = array( 
    'script' => 'deletealbum.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );   
                           
$ViewList['deletecategory'] = array( 
    'script' => 'deletecategory.php',
    'params' => array('category_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryCategory::isCategoryOwner','param' => 'category_id'),'global' => 'administrate'),
    ); 
                             
$ViewList['deletealbumadmin'] = array( 
    'script' => 'deletealbumadmin.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    ); 

$ViewList['publicupload'] = array( 
    'script' => 'publicupload.php',
    'params' => array()
    ); 
      
$FunctionList = array();  
$FunctionList['use'] = array('explain' => 'General registered user permission');
$FunctionList['administrate'] = array('explain' => 'Global edit permission');

?>