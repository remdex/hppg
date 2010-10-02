<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Delete photo by ID')?></legend> 
    <input type="text" class="default-input" id="PhotoQuickDelete" value="">
    <input type="button" class="default-button" onclick="return hw.deletePhotoQuick($('#PhotoQuickDelete').val(),'<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Photo was deleted')?>')" value="OK" />
</fieldset>