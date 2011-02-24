<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Map OpenID to existing or create a new account');?></h1>
</div>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/mapaccounts')?>">

<div class="in-blk">
<label><input type="radio" class="mtype" name="MapOption" checked value="1">Map to <?=$user->email?>, Username (<?=$user->username?>)</label>
<label><input type="radio" class="mtype" name="MapOption" value="2">Create account</label>
</div>

<input class="default-button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Next');?>" name="MapAccounts" />
</form>
