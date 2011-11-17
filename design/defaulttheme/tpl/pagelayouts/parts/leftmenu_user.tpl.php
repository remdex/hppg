<h3>User menu</h3>
<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/account')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My account');?></a>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/profilesettings')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Profile page');?></a>
    
	<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhgallery','personal_albums')) : ?>
	<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/myalbums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Manage albums');?></a>
	<?php endif;?>
	
	<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhshop','use')) : ?>		
	<li><a href="<?=erLhcoreClassDesign::baseurl('shop/mycredits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Credits');?></a>
	<li><a href="<?=erLhcoreClassDesign::baseurl('shop/myorders')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My orders');?></a>
	<li><a href="<?=erLhcoreClassDesign::baseurl('shop/mycreditsorders')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My credits orders');?></a>
	<?php endif;?>
	
	<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'facebook', 'enabled' ) == true) : ?>
    <li><a href="<?=erLhcoreClassDesign::baseurl('fb/albums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Import images from facebook');?></a>
    <?php endif;?>

</ul>
	