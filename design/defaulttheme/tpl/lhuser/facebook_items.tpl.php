<div class="open-id-item">
    <h2>Facebook account map</h2>
    <?php $list = erLhcoreClassModelUserFB::getList(array('filter' => array('user_id' => $user->id)));
    if (count($list) > 0) : ?>
        <ul>
        <?php foreach ($list as $open_id) : ?>
            <li>
             <a href="<?=erLhcoreClassDesign::baseurl('fb/albums')?>">Import images from facebook</a> | <a href="<?=erLhcoreClassDesign::baseurl('user/removefblogin')?>/<?=$open_id->user_id?>"><?=htmlspecialchars($open_id->name)?> <img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove');?>"></a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else :?>
        <?php include_once(erLhcoreClassDesign::designtpl('lhuser/facebook_login.tpl.php'));?>
    <?php endif;?>
</div>    