<div class="anaglyph-option">
<label><input type="radio" checked="checked" name="DisplayOption" value="original"> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/anaglyph','Original')?></label>
<label><input type="radio" name="DisplayOption" value="anaglyph"> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/anaglyph','Anaglyph')?></label>
<label><input type="radio" name="DisplayOption" value="animating"> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/anaglyph','Animating')?></label>
</div>

<div align="center">
    <div id="imageContainerAnaglyph" style="overflow:hidden;">
        <img id="ImageAnaglyphContainer" src="<?=erLhcoreClassDesign::imagePath($Image->filepath.urlencode($Image->filename))?>" />
    </div>
</div>

<script type="text/javascript">

var posX = 0;
var running = false;
var stopAnimation = false;

$('input[name=DisplayOption]').change(function() {  
      
    $('#imageContainerAnaglyph').css({'width':'<?=$Image->pwidth?>px'}); 
    $('#ImageAnaglyphContainer').css({'margin-left':'0px'});
       
    if ($(this).val() == 'original') {
        stopAnimation = true;
        $('#ImageAnaglyphContainer').attr('src','<?=erLhcoreClassDesign::imagePath($Image->filepath.urlencode($Image->filename))?>');
    } else if ($(this).val() == 'animating') {        
        $('#ImageAnaglyphContainer').attr('src','<?=erLhcoreClassDesign::imagePath($Image->filepath.urlencode($Image->filename))?>');        
        $('#imageContainerAnaglyph').css({'width':'<?=round($Image->pwidth/2)?>px'});        
        if (running == false) {
            stopAnimation = false;
            setTimeout('animateImage()',100);
        }                
    } else {
        stopAnimation = true;
        $('#ImageAnaglyphContainer').attr('src','<?=erLhcoreClassDesign::baseurl('gallery/anaglyphimage').'/'.$Image->pid?>');
    }
})

function animateImage() {
    
    if (posX == 0){
        $('#ImageAnaglyphContainer').css({'margin-left':'-<?=round($Image->pwidth/2)?>px'});
        posX = 1;
    } else {
        $('#ImageAnaglyphContainer').css({'margin-left':'0px'});
        posX =0;
    }
    
    if (stopAnimation == false){
        running = true;
        setTimeout('animateImage()',100);
    } else {
        running = false;
    }
}

</script>