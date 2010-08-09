<div id="navcontainer">
    <ul id="navlist">
        <li><a href="<?=erLhcoreClassDesign::baseurl('user/account')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My account');?></a></li>
        
         <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','personal_albums')) : ?>
    	<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/myalbums')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Manage albums');?></a></li>
    	<?php endif;?>
    	
    </ul>
</div>	