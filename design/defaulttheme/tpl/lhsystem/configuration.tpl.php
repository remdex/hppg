<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration');?></legend>

<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/userlist')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Users');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/grouplist')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of groups');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('permission/roles')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of roles');?></a></li>
</ul>

</fieldset>