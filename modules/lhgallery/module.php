<?php

$Module = array( "name" => "Gallery",
				 'variable_params' => true );

$ViewList = array();
   
$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array()
    );
        
$ViewList['rootcategory'] = array( 
    'script' => 'rootcategory.php',
    'params' => array()
    ); 
      
$ViewList['translatebox'] = array( 
    'script' => 'translatebox.php',
    'params' => array('msg_id')
    );
          
$ViewList['translatecomment'] = array( 
    'script' => 'translatecomment.php',
    'params' => array('msg_id','siteaccess')
    );
   
$ViewList['album'] = array( 
    'script' => 'album.php',
    'params' => array('album_id'),
    'uparams' => array('sort','resolution')
    ); 
      
$ViewList['lastuploadstoalbums'] = array( 
    'script' => 'lastuploadstoalbums.php',
    'params' => array(),
    );
          
$ViewList['lastuploadstoalbumsadmin'] = array( 
    'script' => 'lastuploadstoalbumsadmin.php',
    'functions' => array( 'administrate' ),
    'params' => array(),
    );
    
$ViewList['catjson'] = array( 
    'script' => 'catjson.php',
    'functions' => array( 'administrate' ),
    'params' => array('category_id')
    );

    
$ViewList['albumrss'] = array( 
    'script' => 'albumrss.php',
    'params' => array('album_id')
    );  
      
$ViewList['getpallete'] = array( 
    'script' => 'getpallete.php',
    'params' => array(),
    'multiple_arguments' => array ( 'color', 'ncolor' ),
    'uparams' => array('color','ncolor','keyword','mode','resolution','match')
    );
          
$ViewList['getnpallete'] = array( 
    'script' => 'getnpallete.php',
    'params' => array(),
    'multiple_arguments' => array ( 'color', 'ncolor' ),
    'uparams' => array('color','ncolor','keyword','mode','resolution','match')
    );
        
$ViewList['category'] = array( 
    'script' => 'category.php',
    'params' => array('category_id')
    );
            
$ViewList['ownercategorys'] = array( 
    'script' => 'ownercategorys.php',
    'params' => array('owner_id')
    );
    
$ViewList['albumnamesuggest'] = array( 
    'script' => 'albumnamesuggest.php',
    'params' => array('directory_id','name'),
    'functions' => array( 'personal_albums' ),
);     
    
$ViewList['albumlistdirectory'] = array( 
    'script' => 'albumlistdirectory.php',
    'params' => array('directory','recursive'),
    'functions' => array( 'administrate' ),
); 

$ViewList['lastsearches'] = array( 
    'script' => 'lastsearches.php',
    'params' => array()
    );
        
$ViewList['image'] = array( 
    'script' => 'image.php',
    'params' => array('image_id'),
    'multiple_arguments' => array ( 'color','ncolor' ),
    'uparams' => array('mode','keyword','sort','resolution','color','match','ncolor'),
    ); 
            
$ViewList['commentsajax'] = array( 
    'script' => 'commentsajax.php',
    'params' => array('image_id')
);  
           
$ViewList['showimageinfo'] = array( 
    'script' => 'showimageinfo.php',
    'params' => array('image_id','sort')
); 
           
$ViewList['showalbuminfo'] = array( 
    'script' => 'showalbuminfo.php',
    'params' => array('album_id')
); 
           
$ViewList['addcomment'] = array( 
    'script' => 'addcomment.php',
    'params' => array('image_id'),
    'uparams' => array()
);   
            
$ViewList['deletecomment'] = array( 
    'script' => 'deletecomment.php',
    'params' => array('comment_id'),   
    'functions' => array( 'administrate' ),
); 
       
$ViewList['anaglyph'] = array( 
    'script' => 'anaglyph.php',
    'params' => array('image_id')
);       

$ViewList['anaglyphimage'] = array( 
    'script' => 'anaglyphimage.php',
    'params' => array('image_id')
);  
      
$ViewList['popular'] = array( 
    'script' => 'popular.php',
    'params' => array(),
    'uparams' => array('resolution')
    ); 
          
$ViewList['popularrecent'] = array( 
    'script' => 'popularrecent.php',
    'params' => array(),
    ); 
             
$ViewList['ratedrecent'] = array( 
    'script' => 'ratedrecent.php',
    'params' => array(),
    );
                  
$ViewList['suggest'] = array( 
    'script' => 'suggest.php',
    'params' => array('q'),
    );          
        
$ViewList['lastuploads'] = array( 
    'script' => 'lastuploads.php',
    'params' => array(),
    'uparams' => array('resolution')
    ); 
            
$ViewList['color'] = array( 
    'script' => 'color.php',
    'params' => array(),
    'multiple_arguments' => array (
        'color',
        'ncolor'
    ),
    'uparams' => array('color','ncolor','resolution')
); 
    
$ViewList['lastuploadsrss'] = array( 
    'script' => 'lastuploadsrss.php',
    'params' => array()
    ); 
                
$ViewList['toprated'] = array( 
    'script' => 'toprated.php',
    'params' => array(),
    'uparams' => array('resolution')
    );   
$ViewList['topratedrss'] = array( 
    'script' => 'topratedrss.php',
    'params' => array()
    ); 
               
$ViewList['lasthits'] = array( 
    'script' => 'lasthits.php',
    'params' => array(),
    'uparams' => array('resolution')
    );   
     
$ViewList['lasthitsrss'] = array( 
    'script' => 'lasthitsrss.php',
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
    'multiple_arguments' => array (
        'color',
        'ncolor'
    ),
    'uparams' => array('mode','keyword','sort','direction','resolution','color','ncolor','match'),
);   
      
$ViewList['lastcommented'] = array( 
    'script' => 'lastcommented.php',
    'params' => array(),
    'uparams' => array('resolution'),
    );
          
$ViewList['lastrated'] = array( 
    'script' => 'lastrated.php',
    'params' => array(),
    'uparams' => array('resolution'),
    );   
    
$ViewList['lastcommentedrss'] = array( 
    'script' => 'lastcommentedrss.php',
    'params' => array()
    ); 
       
$ViewList['lastratedrss'] = array( 
    'script' => 'lastratedrss.php',
    'params' => array()
    ); 
          
$ViewList['search'] = array( 
    'script' => 'search.php',
    'params' => array(),
    'multiple_arguments' => array ( 'color','ncolor' ),
    'uparams' => array('keyword','sort','resolution','ncolor','color','match'),
    );
    
$ViewList['searchrss'] = array( 
    'script' => 'searchrss.php',
    'params' => array(),
    'uparams' => array('keyword'),
    );
        
$ViewList['myalbums'] = array( 
    'script' => 'myalbums.php',
    'params' => array(), 
    'functions' => array( 'personal_albums' ),
    ); 
       
$ViewList['createalbum'] = array( 
    'script' => 'createalbum.php',
    'params' => array(),
    'functions' => array( 'personal_albums' ),
    );  
                 
$ViewList['createalbumadmin'] = array( 
    'script' => 'createalbumadmin.php',
    'params' => array('category_id'),
    'functions' => array( 'administrate' ),
    ); 
                         
$ViewList['createalbumadminbatch'] = array( 
    'script' => 'createalbumadminbatch.php',
    'params' => array('category_id'),
    'functions' => array( 'administrate' ),
    );
                    
$ViewList['createcategory'] = array( 
    'script' => 'createcategory.php',
    'params' => array('category_id'),
    'functions' => array( 'administrate' ),
    );
    
$ViewList['albumedit'] = array( 
    'script' => 'albumedit.php',
    'params' => array('album_id'),
    'uparams' => array('action'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' =>'administrate'),
    ); 
       
$ViewList['albumeditadmin'] = array( 
    'script' => 'albumeditadmin.php',
    'params' => array('album_id'),
    'uparams' => array('action'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' =>'administrate'),
    );
    
$ViewList['addimages'] = array( 
    'script' => 'addimages.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );
        
$ViewList['addimagesadmin'] = array( 
    'script' => 'addimagesadmin.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );   
         
$ViewList['moveimages'] = array( 
    'script' => 'moveimages.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
);  

$ViewList['moveimage'] = array( 
    'script' => 'moveimage.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' =>'administrate')
);

$ViewList['fileuploadcontainer'] = array( 
    'script' => 'fileuploadcontainer.php',
    'params' => array('fileID','album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::canUpload','param' => 'album_id'),'global' => 'administrate'),
);
        
$ViewList['fileuploadcontainerarchive'] = array( 
    'script' => 'fileuploadcontainerarchive.php',
    'functions' => array( 'public_upload' ),
    'params' => array('fileID')
    ); 
       
$ViewList['getsession'] = array( 
    'script' => 'getsession.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::canUpload','param' => 'album_id'),'global' => 'administrate'),
    );
           
$ViewList['getsessionarchive'] = array( 
    'script' => 'getsessionarchive.php',
    'functions' => array( 'public_upload_archive' ),
    'params' => array()
    );
    

$ViewList['upload'] = array( 
    'script' => 'upload.php',
    'functions' => array( ),
    'params' => array()
    ); 
        
$ViewList['uploadarchive'] = array( 
    'script' => 'uploadarchive.php',
    'functions' => array( ),
    'params' => array()
    );  
       
$ViewList['sessiondone'] = array( 
    'script' => 'sessiondone.php',
    'params' => array('hash')
    );  
          
$ViewList['admincategorys'] = array( 
    'script' => 'admincategorys.php',
    'params' => array('category_id'),    
    'functions' => array( 'administrate' ),
    );    
            
$ViewList['managealbum'] = array( 
    'script' => 'managealbum.php',
    'params' => array('category_id'),    
    'functions' => array( 'administrate' ),
    );   
              
$ViewList['managealbumimages'] = array( 
    'script' => 'managealbumimages.php',
    'params' => array('album_id'),    
    'uparams' => array('action','sort'),    
    'functions' => array( 'administrate' ),
    );  
                 
$ViewList['movebyresolution'] = array( 
    'script' => 'movebyresolution.php',
    'params' => array('album_id'),    
    'functions' => array( 'administrate' ),
    );  
    
$ViewList['movealbumphotos'] = array( 
    'script' => 'movealbumphotos.php',
    'functions' => array( 'administrate' ),
    'params' => array('album_id')
    );
                 
$ViewList['editcategory'] = array( 
    'script' => 'editcategory.php',
    'params' => array('category_id'),    
    'functions' => array( 'administrate' ),
    );
                  
$ViewList['duplicates'] = array( 
    'script' => 'duplicates.php',
    'params' => array(),    
    'functions' => array( 'administrate' ),
    );
                       
$ViewList['deleteduplicatesession'] = array( 
    'script' => 'deleteduplicatesession.php',
    'params' => array('duplicate_session_id'),    
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryDuplicateCollection::canDelete','param' => 'duplicate_session_id'),'global' => 'administrate'),
    ); 
    
$ViewList['batchadd'] = array( 
    'script' => 'batchadd.php',
    'params' => array(),    
    'uparams' => array('directory','import','importrecur'),
    'functions' => array( 'administrate' ),
    );         
              
$ViewList['addimagesbatch'] = array( 
    'script' => 'addimagesbatch.php',
    'params' => array('album_id'),  
    'uparams' => array('image'),  
    'functions' => array( 'administrate' ),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    );
              
$ViewList['mylistalbum'] = array( 
    'script' => 'mylistalbum.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    ); 
                 
$ViewList['updateimage'] = array( 
    'script' => 'updateimage.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' =>'administrate'),
    ); 
                    
$ViewList['editimage'] = array( 
    'script' => 'editimage.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' =>'administrate')
    );
                        
$ViewList['switchimage'] = array( 
    'script' => 'switchimage.php',
    'params' => array('image_id'),
    'uparams' => array('type'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' =>'administrate')
    );
                         
$ViewList['editimageuser'] = array( 
    'script' => 'editimageuser.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' =>'administrate')
    );  
                       
$ViewList['deductvote'] = array( 
    'script' => 'deductvote.php',
    'params' => array('image_id'),
    'functions' => array( 'administrate' )
    ); 
                          
$ViewList['editcomment'] = array( 
    'script' => 'editcomment.php',
    'params' => array('msg_id'),
    'uparams' => array('action'),
    'functions' => array( 'administrate' )
    ); 
                     
$ViewList['deleteimage'] = array( 
    'script' => 'deleteimage.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' => 'administrate'),
    );   
                       
$ViewList['rotate'] = array( 
    'script' => 'rotate.php',
    'params' => array('image_id','action'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' => 'administrate'),
); 
                         
$ViewList['deletealbum'] = array( 
    'script' => 'deletealbum.php',
    'params' => array('album_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
);  
                            
$ViewList['setfolderthumb'] = array( 
    'script' => 'setfolderthumb.php',
    'params' => array('image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryImage::isImageOwner','param' => 'image_id'),'global' => 'administrate'),
);   
                           
$ViewList['deletecategory'] = array( 
    'script' => 'deletecategory.php',
    'params' => array('category_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryCategory::isCategoryOwner','param' => 'category_id'),'global' => 'administrate'),
    ); 
                             
$ViewList['deletealbumadmin'] = array( 
    'script' => 'deletealbumadmin.php',
    'params' => array('album_id'),
    'uparams' => array('moduler','functionr','page'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    ); 

$ViewList['publicupload'] = array( 
    'script' => 'publicupload.php',
    'functions' => array( 'public_upload' ),
    'params' => array()
    ); 
    
$ViewList['publicarchiveupload'] = array( 
    'script' => 'publicarchiveupload.php',
    'params' => array()
    ); 
    
$ViewList['addtofavorites'] = array( 
    'script' => 'addtofavorites.php',
    'params' => array('image_id')
    ); 
                              
$ViewList['deletefavorite'] = array( 
    'script' => 'deletefavorite.php',
    'params' => array('image_id'),
     'functions' => array( ),
    );
     
$ViewList['myfavorites'] = array( 
    'script' => 'myfavorites.php',
    'params' => array()
    ); 
                            
$ViewList['sharehtml'] = array( 
    'script' => 'sharehtml.php',
    'params' => array('image_id')
    );  
                      
$ViewList['sharephpbb'] = array( 
    'script' => 'sharephpbb.php',
    'params' => array('image_id')
    ); 
                         
$ViewList['ratebanlist'] = array( 
    'script' => 'ratebanlist.php',
    'params' => array(),
    'uparams' => array('delete'),
    'functions' => array( 'administrate' )
);
                             
$ViewList['commentbanlist'] = array( 
    'script' => 'commentbanlist.php',
    'params' => array(),
    'uparams' => array('delete'),
    'functions' => array( 'administrate' )
); 
                            
    
$FunctionList = array();  
$FunctionList['use'] = array('explain' => 'General registered user permission [use]');
$FunctionList['administrate'] = array('explain' => 'Global edit permission [administrate]');
$FunctionList['personal_albums'] = array('explain' => 'Allow users to have personal albums [personal_albums]');
$FunctionList['public_upload'] = array('explain' => 'Allow anyone to upload images using flash [public_upload]');
$FunctionList['public_upload_archive'] = array('explain' => 'Allow anyone to upload archive [public_upload_archive]');
$FunctionList['auto_approve'] = array('explain' => 'Function grants auto approvement for all uploaded photos');
$FunctionList['auto_approve_self_photos'] = array('explain' => 'Function grants auto approvement for user self uploaded photos');
$FunctionList['can_approve_self_photos'] = array('explain' => 'Function grants self uploaded photos approvement');
$FunctionList['can_approve_all_photos'] = array('explain' => 'Function grants permission to approve all users uploaded photos');

?>