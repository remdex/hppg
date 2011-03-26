<script language="javascript">
$(document).ready(function()	{
    mySettings.previewParserPath = '<?=erLhcoreClassDesign::baseurl('bbcode/preview')?>';
    $('<?=$bbcodeElementID?>').markItUp(mySettings);
});
</script>