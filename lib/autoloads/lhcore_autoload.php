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
        'erLhcoreClassModelGallerySearchHistory' => 'models/lhgallery/erlhcoreclassmodelsearchhistory.php',
        'erLhcoreClassImageConverter'         => 'core/lhgallery/lhimageconverter.php',        
        'erLhcoreClassGalleryImagemagickHandler'         => 'core/lhgallery/lhgalleryconverterhandler.php',        
        'erLhcoreClassGalleryGDHandler'       => 'core/lhgallery/lhgallerygdconverterhandler.php',        
        'erLhcoreClassGalleryBatch'           => 'core/lhgallery/lhbatch.php',
        'erLhcoreClassLhMemcache'             => 'core/lhcore/lhmemcache.php',
        'erLhcoreClassModelGalleryDelayImageHit' 		=> 'models/lhgallery/erlhcoreclassmodeldelayimagehit.php',
        'erLhcoreClassModelGalleryDuplicateCollection'  => 'models/lhgallery/erlhcoreclassmodelduplicatecollection.php',
        'erLhcoreClassModelGalleryDuplicateImage' 		=> 'models/lhgallery/erlhcoreclassmodelduplicateimage.php',
        'erLhcoreClassModelGalleryConfig' 	  => 'models/lhgallery/erlhcoreclassmodelconfig.php',
                
        //Favorites        
        'erLhcoreClassModelGalleryMyfavoritesImage' 	=> 'models/lhgallery/erlhcoreclassmodelmyfavoritesimage.php',
        'erLhcoreClassModelGalleryMyfavoritesSession' 	=> 'models/lhgallery/erlhcoreclassmodelmyfavoritessession.php', 
        
        // Articles
        'erLhcoreClassModelArticleStatic' 	=> 'models/lharticle/erlhcoreclassmodelarticlestatic.php',
        'erLhcoreClassArticle' 	  			=> 'core/lharticle/lharticle.php', 
        'CKEditor' 	  						=> 'core/lharticle/ckeditor_php5.php',
        
        // System config
        'erLhcoreClassSystemConfig'			=> 'core/lhsystemconfig/lhsystemconfig.php',
        'erLhcoreClassModelSystemConfig'	=> 'models/lhsystemconfig/erlhcoreclassmodelconfig.php',
        
        // Simple shop module
         'erLhcoreClassShop' 	            	=> 'core/lhshop/lhshop.php',
         'erLhcoreClassModelShopImageVariation' => 'models/lhshop/erlhcoreclassmodelimagevariation.php',
         'erLhcoreClassModelShopBasketSession'  => 'models/lhshop/erlhcoreclassmodelbasketssession.php',
         'erLhcoreClassModelShopBasketImage'    => 'models/lhshop/erlhcoreclassmodelbasketimage.php',
         'erLhcoreClassShopPaymentHandler'      => 'core/lhshop/lhpaymenthandler.php',
         'erLhcoreClassModelShopPaymentSetting' => 'models/lhshop/erlhcoreclassmodelpaymentsetting.php',
         'erLhcoreClassModelShopOrder' 			=> 'models/lhshop/erlhcoreclassmodelorder.php',
         'erLhcoreClassShopPaymentHandlerMokejimaiLTMacro' => 'core/lhshop/paymenthandlers/mokejimailt_macro/classes/handler.php',         
         'erLhcoreClassModelShopOrderItem' 		=> 'models/lhshop/erlhcoreclassmodelorderitem.php',
         'erLhcoreClassModelShopBaseSetting' 	=> 'models/lhshop/erlhcoreclassmodelbasesetting.php',
         'erLhcoreClassModelShopUserCredit' 	=> 'models/lhshop/erlhcoreclassmodelusercredit.php',
         'erLhcoreClassModelShopUserCreditOrder'=> 'models/lhshop/erlhcoreclassmodelusercreditorder.php',
         'erLhcoreClassShopMail'				=> 'core/lhshop/lhshopmail.php',
         
         // Paypal handler options
         'erLhcoreClassShopPaymentHandlerPaypal' => 'core/lhshop/paymenthandlers/paypal_handler/classes/handler.php',
        
         
);
    
?>