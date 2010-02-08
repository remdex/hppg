<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password remind');?></h1>
</div>
<div class="attribute-short">
<div id="messages">
	<? if (isset($error)) : ?><h2 class="error-h2"><?=$error;?></h2><? endif;?>
</div>
<br />

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/user/forgotpassword/')?>">
<div class="in-blk">
<label>E-mail:</label>
<input type="text" class="inputfield" name="Email" value="" />
</div>


<input type="submit" class="default-button" value="Restore password" name="Forgotpassword" />


</form>
</div>