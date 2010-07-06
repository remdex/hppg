<div class="top-menu float-break">
<ul>
                    <?php
                    $currentUser = erLhcoreClassUser::instance();                       
                    if ($currentUser->isLogged()) : 
                    $UserData = $currentUser->getUserData();
                    ?>                                       	
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/index/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>)</li> 
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/logout/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?></a></li>                  
                    <? 
                    unset($UserData);                    
                    else : ?>                                    	
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/login/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></a></li>                          
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/registration/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Register');?></a></li>                          
                    <?
                    endif;
                    unset($currentUser);
                    ?>
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/popular/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular images');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastuploads/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/toprated/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lasthits/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last hits');?> </a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastcommented/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last commented');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/publicupload/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Upload image');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/myfavorites/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My favorites');?></a></li>
</ul>
</div>