<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Map Open ID to existing account');?></h1>
</div>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/mapaccounts')?>">

<div class="in-blk">
<h2>Map to <?=$user->email?></h2>

<div class="map-login">
    <? if (isset($failed_authenticate)) : ?><h2 class="error-h2">Incorrect login or password</h2><? endif;?>
    
    <div class="in-blk">
    <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Username');?></label>
    <input class="inputfield" type="text" name="Username" value="" />
    </div>
    
    <div class="in-blk">
    <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password');?></label>
    <input class="inputfield" type="password" name="Password" value="" />
    </div>
</div>

<?php if (isset($multiple_action)) : ?>
<strong>OR</strong>
<label><input type="checkbox" value="1" name="CreateAccount" /> create account</label>
<?php endif;?>

</div>

<input class="default-button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Next');?>" name="MapAccounts" />
</form>
