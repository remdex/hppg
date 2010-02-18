<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/index','User home');?></h1>
</div>

<div class="attribute-short">
<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/index','Welcome. Here you can:');?></p>
<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('/user/account')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/index','Edit personal settings');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/myalbums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/index','Manage personal albums');?></a></li>
</ul>
</div>