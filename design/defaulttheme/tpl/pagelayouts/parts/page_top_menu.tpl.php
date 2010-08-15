<ul>
        <?php
        $currentUser = erLhcoreClassUser::instance();                       
        if ($currentUser->isLogged()) : 
        $UserData = $currentUser->getUserData();
        ?>                                       	
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/index/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>)</li> 
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/logout/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?></a></li>                  
        <? 
        unset($UserData);                    
        else : ?>                                    	
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/login/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></a></li>                          
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/registration/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Register');?></a></li>                          
        <?
        endif;
        unset($currentUser);
        ?>
        <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/myfavorites/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My favorites');?></a></li>
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhshop','use')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('/shop/basket/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Basket');?></a></li>
		<?php endif;?>
		
		<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','public_upload')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/publicupload/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Upload image');?></a></li> 
		<?php endif;?>
		
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','public_upload_archive')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/publicarchiveupload')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Upload zipped images');?></a></li> 
		<?php endif;?>
		
		
      
        
		         
        
</ul>