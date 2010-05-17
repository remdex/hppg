<?php


return array (

        //Core classes
        'erLhcoreClassModule'       		=> 'core/lhcore/lhmodule.php',
        'erLhcoreClassSystem'       		=> 'core/lhcore/lhsys.php',
        'erLhcoreClassDesign'       		=> 'core/lhcore/lhdesign.php',        
        'erLhcoreClassTemplate'     		=> 'core/lhtpl/tpl.php',
        'erLhcoreClassURL'          		=> 'core/lhcore/lhurl.php', 
        'lhPaginator'               		=> 'core/lhexternal/lhpagination.php', 
        'erLhcoreClassLog'          		=> 'core/lhcore/lhlog.php', 
        'erLhcoreClassCacheSystem'  		=> 'core/lhcore/lhsys.php',           
        'erLhcoreClassLazyDatabaseConfiguration' => 'core/lhcore/lhdb.php', 
        'erConfigClassLhConfig'     		=> 'core/lhconfig/lhconfig.php',      
        'erConfigClassLhCacheConfig' 		=> 'core/lhconfig/lhcacheconfig.php',   
             
        'erLhcoreClassRole'     			=> 'core/lhpermission/lhrole.php',
        'erLhcoreClassModules'  			=> 'core/lhpermission/lhmodules.php',
        'erLhcoreClassRoleFunction'  		=> 'core/lhpermission/lhrolefunction.php',
        'erLhcoreClassGroupRole'  			=> 'core/lhpermission/lhgrouprole.php',
        'erLhcoreClassGroupUser'  			=> 'core/lhuser/lhgroupuser.php',
        'erLhcoreClassModelForgotPassword'  => 'models/lhuser/erlhcoreclassmodelforgotpassword.php',
                 
        // Translations
        'erTranslationClassLhTranslation' 	=> 'core/lhcore/lhtranslation.php',
         'erLhcoreClassCharTransform' 	    => 'core/lhcore/lhchartransform.php',
         
        // Core clases
        'erLhcoreClassUser'        			=> 'core/lhuser/lhuser.php',
        'erLhcoreClassGroup'        		=> 'core/lhuser/lhgroup.php',
        'SphinxClient'        		        => 'core/lhgallery/sphinxapi.php',
        'PHPMailer'                         => 'core/lhmailer/class.phpmailer.php',
                 
        // Core models
        'erLhcoreClassModelUser' 			=> 'models/lhuser/erlhcoreclassmodeluser.php',
        'erLhcoreClassModelGroup' 			=> 'models/lhuser/erlhcoreclassmodelgroup.php',
        'erLhcoreClassModelGroupUser' 		=> 'models/lhuser/erlhcoreclassmodelgroupuser.php',
        'erLhcoreClassModelGroupRole' 		=> 'models/lhpermission/erlhcoreclassmodelgrouprole.php',
        'erLhcoreClassModelRole' 			=> 'models/lhpermission/erlhcoreclassmodelrole.php',
        'erLhcoreClassModelRoleFunction' 	=> 'models/lhpermission/erlhcoreclassmodelrolefunction.php',
        
        // Gallery models
        'erLhcoreClassModelGalleryAlbum' 	=> 'models/lhgallery/erlhcoreclassmodelalbum.php',
        'erLhcoreClassModelGalleryCategory' => 'models/lhgallery/erlhcoreclassmodelcategory.php',
        'erLhcoreClassModelGalleryImage' 	=> 'models/lhgallery/erlhcoreclassmodelimage.php',
        'erLhcoreClassModelGalleryComment' 	=> 'models/lhgallery/erlhcoreclassmodelcomment.php',
        'erLhcoreClassModelGalleryUpload' 	=> 'models/lhgallery/erlhcoreclassmodelupload.php',
        'erLhcoreClassModelGalleryUploadArchive' 	=> 'models/lhgallery/erlhcoreclassmodeluploadarchive.php',
        'erLhcoreClassGallery' 	              => 'core/lhgallery/lhgallery.php',
        'erLhcoreClassGalleryArchive' 	      => 'core/lhgallery/lharchive.php',
        'erLhcoreClassModelGalleryLastSearch' => 'models/lhgallery/erlhcoreclassmodellastsearch.php',
        'erLhcoreClassImageConverter'         => 'core/lhgallery/lhimageconverter.php',        
        'erLhcoreClassGalleryBatch'           => 'core/lhgallery/lhbatch.php',
        'erLhcoreClassLhMemcache'             => 'core/lhcore/lhmemcache.php',
        
        //Favorites        
        'erLhcoreClassModelGalleryMyfavoritesImage' 	=> 'models/lhgallery/erlhcoreclassmodelmyfavoritesimage.php',
        'erLhcoreClassModelGalleryMyfavoritesSession' 	=> 'models/lhgallery/erlhcoreclassmodelmyfavoritessession.php', 
);
    
?>