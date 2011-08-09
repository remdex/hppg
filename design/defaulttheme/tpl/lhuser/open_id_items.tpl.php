<div class="open-id-item">

    <h2>Open ID - Google authentification</h2>
    <?php $list = erLhcoreClassModelOidMap::getList(array('filter' => array('user_id' => $user->id,'open_id_type' => erLhcoreClassModelOidMap::OPEN_ID_GOOGLE)));
    if (count($list) > 0) : ?>
        <ul>
        <?php foreach ($list as $open_id) : ?>
            <li><img src="<?=erLhcoreClassDesign::design('images/icons/open_id_1.png')?>" alt="" title="<?=htmlspecialchars($open_id->open_id);?>" />
            <span title="<?=htmlspecialchars($open_id->open_id);?>">(<?=$open_id->email?>)</span> <a href="<?=erLhcoreClassDesign::baseurl('user/removeopenid')?>/<?=$open_id->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove Open ID');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myfavorites','Remove Open ID');?>"></a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else :?>
    
    <br />

        <div id="googe-login-block">
            <a onclick="hw.loginOpenID()" style="cursor:pointer;" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Add google account');?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/open_id_1.png')?>" alt="" title="" /></a>
        </div>
        
        <div id="loading-block" style="display:none;">
            <img src="<?=erLhcoreClassDesign::design('images/newdesign/ajax-loader.gif')?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Working');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Working');?>" /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Working');?>...
        </div>
    

    <?php endif;?>

</div>    