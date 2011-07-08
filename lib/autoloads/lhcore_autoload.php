<?php


return array_merge(array (

        //Core classes
        'erLhcoreClassModule'       		=> 'lib/core/lhcore/lhmodule.php',
        'erLhcoreClassSystem'       		=> 'lib/core/lhcore/lhsys.php',
        'erLhcoreClassDesign'       		=> 'lib/core/lhcore/lhdesign.php',        
        'erLhcoreClassTemplate'     		=> 'lib/core/lhtpl/tpl.php',
        'erLhcoreClassURL'          		=> 'lib/core/lhcore/lhurl.php', 
        'lhPaginator'               		=> 'lib/core/lhexternal/lhpagination.php', 
        'S3'               		            => 'lib/core/lhexternal/S3.php',

        'erLhcoreClassLog'          		=> 'lib/core/lhcore/lhlog.php',                  
        'erLhcoreClassLazyDatabaseConfiguration' => 'lib/core/lhcore/lhdb.php', 
        'erConfigClassLhConfig'     		=> 'lib/core/lhconfig/lhconfig.php',      
        'erConfigClassLhCacheConfig' 		=> 'lib/core/lhconfig/lhcacheconfig.php',   
             
        'erLhcoreClassRole'     			=> 'lib/core/lhpermission/lhrole.php',
        'erLhcoreClassModules'  			=> 'lib/core/lhpermission/lhmodules.php',
        'erLhcoreClassRoleFunction'  		=> 'lib/core/lhpermission/lhrolefunction.php',
        'erLhcoreClassGroupRole'  			=> 'lib/core/lhpermission/lhgrouprole.php',        
        'erLhcoreClassModelForgotPassword'  => 'lib/models/lhuser/erlhcoreclassmodelforgotpassword.php',
        'erLhcoreClassModelOidMap'          => 'lib/models/lhuser/erlhcoreclassmodeloidmap.php',
                 
        // Translations
        'erTranslationClassLhTranslation' 	=> 'lib/core/lhcore/lhtranslation.php',
         'erLhcoreClassCharTransform' 	    => 'lib/core/lhcore/lhchartransform.php',
         
        // Core clases
        'erLhcoreClassUser'        			=> 'lib/core/lhuser/lhuser.php',
        'SphinxClient'        		        => 'lib/core/lhgallery/sphinxapi.php',
        'PHPMailer'                         => 'lib/core/lhmailer/class.phpmailer.php',
        'ezcAuthenticationDatabaseCredentialFilter'  => 'lib/core/lhuser/lhauthenticationdatabasecredentialfilter.php',
                 
        // Core models
        'erLhcoreClassModelUser' 			=> 'lib/models/lhuser/erlhcoreclassmodeluser.php',
        'erLhcoreClassModelGroup' 			=> 'lib/models/lhuser/erlhcoreclassmodelgroup.php',
        'erLhcoreClassModelGroupUser' 		=> 'lib/models/lhuser/erlhcoreclassmodelgroupuser.php',
        'erLhcoreClassModelGroupRole' 		=> 'lib/models/lhpermission/erlhcoreclassmodelgrouprole.php',
        'erLhcoreClassModelRole' 			=> 'lib/models/lhpermission/erlhcoreclassmodelrole.php',
        'erLhcoreClassModelRoleFunction' 	=> 'lib/models/lhpermission/erlhcoreclassmodelrolefunction.php',
        
        // Gallery models
        'erLhcoreClassModelGalleryAlbum' 	=> 'lib/models/lhgallery/erlhcoreclassmodelalbum.php',
        'erLhcoreClassModelGalleryCategory' => 'lib/models/lhgallery/erlhcoreclassmodelcategory.php',
        'erLhcoreClassModelGalleryImage' 	=> 'lib/models/lhgallery/erlhcoreclassmodelimage.php',
        'erLhcoreClassModelGalleryComment' 	=> 'lib/models/lhgallery/erlhcoreclassmodelcomment.php',
        'erLhcoreClassModelGalleryUpload' 	=> 'lib/models/lhgallery/erlhcoreclassmodelupload.php',
        'erLhcoreClassModelGalleryUploadArchive' 	=> 'lib/models/lhgallery/erlhcoreclassmodeluploadarchive.php',
        'erLhcoreClassGallery' 	                    => 'lib/core/lhgallery/lhgallery.php',
        'erLhcoreClassOgg' 	                        => 'lib/core/lhgallery/lhogg.php',
        'erLhcoreClassHTMLVConverter' 	            => 'lib/core/lhgallery/lhhtmlvconverter.php',
        'erLhcoreClassSWFConverter' 	            => 'lib/core/lhgallery/lhswfconverter.php',
        'erLhcoreClassFLVConverter' 	            => 'lib/core/lhgallery/lhflvconverter.php',
        'erLhcoreClassVideoConverter' 	            => 'lib/core/lhgallery/lhvideoconverter.php',
        'erLhcoreClassGalleryArchive' 	            => 'lib/core/lhgallery/lharchive.php',
        'FaceRestClient' 	                        => 'lib/core/lhgallery/FaceRestClient.php',
        'erLhcoreClassModelGallerySphinxSearch'     => 'lib/models/lhgallery/erlhcoreclassmodelsphinxsearch.php',
        'erLhcoreClassModelGalleryLastSearch'       => 'lib/models/lhgallery/erlhcoreclassmodellastsearch.php',
        'erLhcoreClassModelGallerySearchHistory'    => 'lib/models/lhgallery/erlhcoreclassmodelsearchhistory.php',
        'erLhcoreClassImageConverter'               => 'lib/core/lhgallery/lhimageconverter.php',        
        'erLhcoreClassGalleryImagemagickHandler'    => 'lib/core/lhgallery/lhgalleryconverterhandler.php',        
        'erLhcoreClassGalleryGDHandler'             => 'lib/core/lhgallery/lhgallerygdconverterhandler.php',        
        'erLhcoreClassGalleryBatch'                 => 'lib/core/lhgallery/lhbatch.php',
        'erLhcoreClassPalleteIndexImage'            => 'lib/core/lhgallery/lhpalleteindeximage.php',
        'erLhcoreClassLhMemcache'                   => 'lib/core/lhcore/lhmemcache.php',
        'erLhcoreClassLhAPC'                        => 'lib/core/lhcore/lhapc.php',
        'erLhcoreClassLhRedis'                      => 'lib/core/lhcore/lhredis.php',
        'erLhcoreClassModelGalleryDelayImageHit' 		=> 'lib/models/lhgallery/erlhcoreclassmodeldelayimagehit.php',
        'erLhcoreClassModelGalleryPopular24' 		    => 'lib/models/lhgallery/erlhcoreclassmodelpopular24.php',
        'erLhcoreClassModelGalleryRated24' 		        => 'lib/models/lhgallery/erlhcoreclassmodelrated24.php',
        'erLhcoreClassModelGalleryDuplicateCollection'  => 'lib/models/lhgallery/erlhcoreclassmodelduplicatecollection.php',
        'erLhcoreClassModelGalleryDuplicateImage' 		=> 'lib/models/lhgallery/erlhcoreclassmodelduplicateimage.php',
        'erLhcoreClassModelGalleryDuplicateImageHash' 	=> 'lib/models/lhgallery/erlhcoreclassmodelduplicateimagehash.php',
        'erLhcoreClassModelGalleryConfig' 	            => 'lib/models/lhgallery/erlhcoreclassmodelconfig.php',
        'erLhcoreClassModelGalleryFiletype'	            => 'lib/models/lhgallery/erlhcoreclassmodelfiletype.php',
        'erLhcoreClassModelGalleryPendingConvert'	    => 'lib/models/lhgallery/erlhcoreclassmodelpendingconvert.php',
        'erLhcoreClassModelGalleryPallete'	            => 'lib/models/lhgallery/erlhcoreclassmodelpallete.php',
        'erLhcoreClassModelGalleryFaceData'	            => 'lib/models/lhgallery/erlhcoreclassmodelfacedata.php',
        'erLhcoreClassModelGalleryRateBanIP'	        => 'lib/models/lhgallery/erlhcoreclassmodelratebanip.php',
        'erLhcoreClassModelGalleryCommentBanIP'	        => 'lib/models/lhgallery/erlhcoreclassmodelcommentbanip.php',

        //Favorites        
        'erLhcoreClassModelGalleryMyfavoritesImage' 	=> 'lib/models/lhgallery/erlhcoreclassmodelmyfavoritesimage.php',
        'erLhcoreClassModelGalleryMyfavoritesSession' 	=> 'lib/models/lhgallery/erlhcoreclassmodelmyfavoritessession.php', 
        
        // Articles
        'erLhcoreClassModelArticleStatic' 	=> 'lib/models/lharticle/erlhcoreclassmodelarticlestatic.php',
        'erLhcoreClassArticle' 	  			=> 'lib/core/lharticle/lharticle.php', 
        'CKEditor' 	  						=> 'lib/core/lharticle/ckeditor_php5.php',
        
        // System config
        'erLhcoreClassSystemConfig'			=> 'lib/core/lhsystemconfig/lhsystemconfig.php',
        'erLhcoreClassModelSystemConfig'	=> 'lib/models/lhsystemconfig/erlhcoreclassmodelconfig.php',
        
        // Simple shop module
         'erLhcoreClassShop' 	            	=> 'lib/core/lhshop/lhshop.php',
         'erLhcoreClassModelShopImageVariation' => 'lib/models/lhshop/erlhcoreclassmodelimagevariation.php',
         'erLhcoreClassModelShopBasketSession'  => 'lib/models/lhshop/erlhcoreclassmodelbasketssession.php',
         'erLhcoreClassModelShopBasketImage'    => 'lib/models/lhshop/erlhcoreclassmodelbasketimage.php',
         'erLhcoreClassShopPaymentHandler'      => 'lib/core/lhshop/lhpaymenthandler.php',
         'erLhcoreClassModelShopPaymentSetting' => 'lib/models/lhshop/erlhcoreclassmodelpaymentsetting.php',
         'erLhcoreClassModelShopOrder' 			=> 'lib/models/lhshop/erlhcoreclassmodelorder.php',
         'erLhcoreClassShopPaymentHandlerMokejimaiLTMacro' => 'lib/core/lhshop/paymenthandlers/mokejimailt_macro/classes/handler.php',         
         'erLhcoreClassModelShopOrderItem' 		=> 'lib/models/lhshop/erlhcoreclassmodelorderitem.php',
         'erLhcoreClassModelShopBaseSetting' 	=> 'lib/models/lhshop/erlhcoreclassmodelbasesetting.php',
         'erLhcoreClassModelShopUserCredit' 	=> 'lib/models/lhshop/erlhcoreclassmodelusercredit.php',
         'erLhcoreClassModelShopUserCreditOrder'=> 'lib/models/lhshop/erlhcoreclassmodelusercreditorder.php',
         'erLhcoreClassShopMail'				=> 'lib/core/lhshop/lhshopmail.php',
         
         
         'erLhcoreClassBBCode'				    => 'lib/core/lhbbcode/lhbbcode.php',
                  
         // Paypal handler options
         'erLhcoreClassShopPaymentHandlerPaypal' => 'lib/core/lhshop/paymenthandlers/paypal_handler/classes/handler.php',
         
         // Forum autoloads
         'erLhcoreClassForum'                    => 'lib/core/lhforum/lhforum.php',
         'erLhcoreClassModelForumCategory'       => 'lib/models/lhforum/erlhcoreclassmodelcategory.php',
         'erLhcoreClassModelForumMessage'        => 'lib/models/lhforum/erlhcoreclassmodelmessage.php',
         'erLhcoreClassModelForumTopic'          => 'lib/models/lhforum/erlhcoreclassmodeltopic.php',
         'erLhcoreClassModelForumFile'           => 'lib/models/lhforum/erlhcoreclassmodelfile.php',
         'erLhcoreClassModelForumReport'         => 'lib/models/lhforum/erlhcoreclassmodelreport.php'
                
         
),
include('var/autoloads/lhextension_autoload.php')
);
    
?>