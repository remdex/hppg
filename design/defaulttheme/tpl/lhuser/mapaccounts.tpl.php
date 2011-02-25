<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Open ID');?></h1>
</div>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/mapaccounts')?>">

<div class="in-blk float-break">


<?php if (isset($map_to_current)) : ?>

<div class="left map-option">
<h2>Map to my account</h2>
<label><input type="radio" value="2" name="CreateAccount" checked /> my account (<?=htmlspecialchars($current_user->username)?>)</label>
</div>

<?php else : ?>

<div class="left map-option">
    <?php if (isset($multiple_action)) : ?>
    <h2>Login with other user</h2>
    <?php else :?>
    <h2>Login to map account</h2>
    <?php endif;?>
    
    <label><input type="radio" value="3" name="CreateAccount" <?=$create_account == 3 ? 'checked="checked"' : ''?>/> map to other account</label>
     
    <div class="map-login"<?php if (isset($map_to_current) && $create_account != 3) :?>style="display:none"<?php endif; ?>>
        <? if (isset($failed_authenticate)) : ?><h2 class="error">Incorrect login or password</h2><? endif;?>
        
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Username');?></label>
        <input class="inputfield" type="text" name="Username" value="" />
        </div>
        
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password');?></label>
        <input class="inputfield" type="password" name="Password" value="" />
        </div>
    </div>

</div>
<?php endif;?>


<div class="left map-option">
<?php if (isset($multiple_action)) : ?>
<strong>OR</strong>
<label><input type="radio" value="1" name="CreateAccount" /> create account</label>
<?php endif;?>
</div>


</div>

<div class="float-break">
<input class="default-button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Next');?>" name="MapAccounts" />
</div>

</form>

<script>
$('input[name=CreateAccount]').change(function(){    
    if ($(this).val() == 3){
        $('.map-login').fadeIn();
        $('input[name=Username]').focus();
    } else {
        $('.map-login').fadeOut();
    }
});
</script>