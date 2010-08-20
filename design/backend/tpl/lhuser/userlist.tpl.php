<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Users');?></legend>

<?php if (isset($pages)) : ?>
    <div class="navigator">
    <div class="right"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, Found - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?>
    </div>
<? endif;?>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Username');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','E-mail');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelUser::getUserList(array('offset' => $pages->low, 'limit' => $pages->items_per_page)) as $user) : ?>
    <tr>
        <td><?=$user->id?></td>
        <td><?=$user->username?></td>
        <td><?=$user->email?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$user->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Edit user');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Edit user');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('user/delete')?>/<?=$user->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete user');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete user');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<?php if (isset($pages)) : ?>
    <div class="navigator">
    <div class="right"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist',"Page %currentpage of %totalpage",array('currentpage' => $pages->current_page,'totalpage' => $pages->num_pages))?>, Found - <?=$pages->items_total?></div>
    <?=$pages->display_pages();?>
    </div>
<? endif;?>
<br />
<div>
<a href="<?=erLhcoreClassDesign::baseurl('user/new/')?>"><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','New user');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','New user');?>" /></a>
</div>
</fieldset>