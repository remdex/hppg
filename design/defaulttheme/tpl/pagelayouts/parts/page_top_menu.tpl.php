<ul>
        <?php
        $currentUser = erLhcoreClassUser::instance();                       
        if ($currentUser->isLogged()) : 
        $UserData = $currentUser->getUserData(true);
        ?>                                       	
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/index/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>) 
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/logout/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?></a>                  
        <? 
        unset($UserData);                    
        else : ?>                                    	
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/login/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></a>                          
        	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/registration/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Register');?></a>                          
        <?
        endif;
        unset($currentUser);
        ?>
        <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/myfavorites/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My favorites');?></a>
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhshop','use')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('/shop/basket/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Basket');?></a>
		<?php endif;?>
		
		<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','public_upload')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/publicupload/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Upload');?></a> 
		<?php endif;?>
		
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','public_upload_archive')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/publicarchiveupload')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Upload zip');?></a> 
		<?php endif;?>		
</ul>