<h3>User menu</h3>
<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/account')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My account');?></a></li>
	<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','personal_albums')) : ?>
	<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/myalbums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Manage albums');?></a></li>
	<?php endif;?>
	
	<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhshop','use')) : ?>		
	<li><a href="<?=erLhcoreClassDesign::baseurl('shop/mycredits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Credits');?></a></li>
	<li><a href="<?=erLhcoreClassDesign::baseurl('shop/myorders')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My orders');?></a></li>
	<li><a href="<?=erLhcoreClassDesign::baseurl('shop/mycreditsorders')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My credits orders');?></a></li>
	<?php endif;?>
</ul>
	